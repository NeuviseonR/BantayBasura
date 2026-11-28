<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\User;
use App\Support\TicketTextFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ResidentTicketController extends Controller
{
    public function index(Request $request)
    {
        $user = $this->eligibleUser($request);
        $activeTicket = $user->supportTickets()
            ->whereIn('status', config('ticketing.active_statuses'))
            ->latest()
            ->first();

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::in(['open', 'in_progress', 'waiting_for_user', 'resolved', 'closed'])],
            'category' => ['nullable', Rule::in(array_keys(config('ticketing.categories', [])))],
            'sort' => ['nullable', Rule::in(['newest', 'oldest'])],
        ]);

        $tickets = $user->supportTickets()
            ->with('wasteReport')
            ->when($filters['search'] ?? null, function (Builder $query, string $search) {
                $query->where(function (Builder $subQuery) use ($search) {
                    $subQuery->where('title', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');

                    if (preg_match('/(?:BB[- ]?TKT[- ]?)?#?0*(\d+)$/i', trim($search), $matches)) {
                        $subQuery->orWhere('id', (int) $matches[1]);
                    }
                });
            })
            ->when($filters['status'] ?? null, fn (Builder $query, string $status) => $query->where('status', $status))
            ->when($filters['category'] ?? null, fn (Builder $query, string $category) => $query->where('category', $category))
            ->orderBy('created_at', ($filters['sort'] ?? 'newest') === 'oldest' ? 'asc' : 'desc')
            ->paginate(8)
            ->withQueryString();

        return view('tickets.index', [
            'tickets' => $tickets,
            'activeTicket' => $activeTicket,
            'categories' => config('ticketing.categories'),
            'filters' => $filters,
        ]);
    }

    public function create(Request $request)
    {
        $user = $this->eligibleUser($request);
        $activeTicket = $user->supportTickets()
            ->whereIn('status', config('ticketing.active_statuses'))
            ->latest()
            ->first();

        if ($activeTicket) {
            return redirect()->route('user-tickets.show', $activeTicket)
                ->with('ticket_notice', 'You already have an active ticket. You can submit another one after it has been resolved or closed.');
        }

        $reports = $user->wasteReports()->latest()->get();
        $appealViolation = null;
        if ($request->filled('violation')) {
            $appealViolation = \App\Models\VolunteerViolation::query()->where('volunteer_id', $user->id)->whereKey($request->integer('violation'))->firstOrFail();
        }

        return view('tickets.create', [
            'categories' => config('ticketing.categories'),
            'reports' => $reports,
            'appealViolation' => $appealViolation,
        ]);
    }

    public function store(Request $request, TicketTextFilter $filter)
    {
        $user = $this->eligibleUser($request);

        $data = $request->validate([
            'category' => ['required', Rule::in(array_keys(config('ticketing.categories', [])))],
            'title' => ['required', 'string', 'min:5', 'max:' . config('ticketing.max_title_length', 120)],
            'description' => ['required', 'string', 'min:20', 'max:' . config('ticketing.max_description_length', 3000)],
            'attachment' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:' . config('ticketing.max_attachment_kb', 5120)],
            'waste_report_id' => ['nullable', 'integer', 'exists:waste_reports,id'],
            'volunteer_violation_id' => ['nullable', 'integer', 'exists:volunteer_violations,id'],
        ], [
            'category.required' => 'Please choose what your concern is about.',
            'title.min' => 'Please use a more descriptive title (at least 5 characters).',
            'description.min' => 'Please provide a little more detail (at least 20 characters).',
            'attachment.image' => 'Please attach an image file.',
            'attachment.mimes' => 'Use a JPG, PNG, or WEBP image.',
            'attachment.max' => 'The attachment must be 5 MB or smaller.',
        ]);

        if ($filter->containsBannedWords($data['title']) || $filter->containsBannedWords($data['description'])) {
            throw ValidationException::withMessages([
                'description' => 'Your ticket contains language that is not allowed. Please revise your title or description before submitting.',
            ]);
        }

        if ($request->hasFile('attachment') && !empty($data['waste_report_id'])) {
            throw ValidationException::withMessages([
                'attachment' => 'Please choose either an image attachment or an existing waste report, not both.',
                'waste_report_id' => 'Please choose either an image attachment or an existing waste report, not both.',
            ]);
        }

        if (!empty($data['volunteer_violation_id'])) {
            $ownsViolation = \App\Models\VolunteerViolation::query()->where('volunteer_id', $user->id)->whereKey($data['volunteer_violation_id'])->exists();
            if (!$ownsViolation || $user->role !== 'volunteer') {
                throw ValidationException::withMessages(['volunteer_violation_id' => 'You can only appeal one of your own volunteer penalties.']);
            }
            if ($data['category'] !== 'volunteer_point_dispute') {
                throw ValidationException::withMessages(['category' => 'Choose the Volunteer Points / Penalty Appeal category for this appeal.']);
            }
        }

        if (!empty($data['waste_report_id'])) {
            $belongsToUser = $user->wasteReports()->whereKey($data['waste_report_id'])->exists();
            if (!$belongsToUser) {
                throw ValidationException::withMessages([
                    'waste_report_id' => 'You can only attach one of your own waste reports.',
                ]);
            }
        }

        $ticket = DB::transaction(function () use ($request, $user, $data) {
            // Lock the account row to prevent two near-simultaneous submissions
            // from creating two active tickets for the same account.
            User::query()->whereKey($user->id)->lockForUpdate()->firstOrFail();

            $alreadyHasActiveTicket = SupportTicket::query()
                ->where('user_id', $user->id)
                ->whereIn('status', config('ticketing.active_statuses'))
                ->exists();

            if ($alreadyHasActiveTicket) {
                throw ValidationException::withMessages([
                    'ticket' => 'You already have an active ticket. Please wait until it is resolved or closed before opening another one.',
                ]);
            }

            $attachmentPath = null;
            $attachmentName = null;
            $attachmentMime = null;

            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $attachmentPath = $file->store('ticket-attachments', 'local');
                $attachmentName = $file->getClientOriginalName();
                $attachmentMime = $file->getMimeType();
            }

            $ticket = SupportTicket::create([
                'user_id' => $user->id,
                'waste_report_id' => $data['waste_report_id'] ?? null,
                'volunteer_violation_id' => $data['volunteer_violation_id'] ?? null,
                'category' => $data['category'],
                'title' => trim($data['title']),
                'description' => trim($data['description']),
                'attachment_path' => $attachmentPath,
                'attachment_original_name' => $attachmentName,
                'attachment_mime_type' => $attachmentMime,
                'status' => 'open',
            ]);

            if ($ticket->volunteer_violation_id) {
                \App\Models\VolunteerViolation::whereKey($ticket->volunteer_violation_id)->update(['status' => 'appealed']);
            }

            return $ticket;
        });

        return redirect()->route('user-tickets.show', $ticket)
            ->with('success', 'Your ticket has been submitted. Please allow around 2–3 working days for a staff member to respond.');
    }

    public function show(Request $request, SupportTicket $ticket)
    {
        $user = $this->eligibleUser($request);
        abort_unless($ticket->user_id === $user->id, 404);

        $ticket->load(['wasteReport', 'volunteerViolation', 'messages.sender']);

        return view('tickets.show', [
            'ticket' => $ticket,
            'canReply' => $ticket->isActive(),
        ]);
    }

    public function reply(Request $request, SupportTicket $ticket, TicketTextFilter $filter)
    {
        $user = $this->eligibleUser($request);
        abort_unless($ticket->user_id === $user->id, 404);

        if (!$ticket->isActive()) {
            return redirect()->route('user-tickets.show', $ticket)
                ->with('ticket_notice', 'This ticket has been resolved or closed, so replies are no longer available.');
        }

        $data = $request->validate([
            'body' => ['required', 'string', 'min:2', 'max:' . config('ticketing.max_reply_length', 3000)],
        ]);

        if ($filter->containsBannedWords($data['body'])) {
            throw ValidationException::withMessages([
                'body' => 'Your reply contains language that is not allowed. Please revise it before sending.',
            ]);
        }

        $ticket->messages()->create([
            'sender_id' => $user->id,
            'sender_role' => $user->role,
            'body' => trim($data['body']),
        ]);

        if ($ticket->status === 'waiting_for_user') {
            $ticket->status = 'open';
        }
        $ticket->save();
        $ticket->touch();

        return redirect()->route('user-tickets.show', $ticket)->with('success', 'Your reply has been added to the ticket.');
    }

    public function attachment(Request $request, SupportTicket $ticket)
    {
        $user = $this->eligibleUser($request);
        abort_unless($ticket->user_id === $user->id, 404);
        abort_unless($ticket->attachment_path && Storage::disk('local')->exists($ticket->attachment_path), 404);

        return Storage::disk('local')->download(
            $ticket->attachment_path,
            $ticket->attachment_original_name ?: 'ticket-attachment'
        );
    }

    private function eligibleUser(Request $request): User
    {
        $user = $request->user();
        abort_unless($user && in_array($user->role, ['resident', 'volunteer'], true), 403);

        return $user;
    }
}
