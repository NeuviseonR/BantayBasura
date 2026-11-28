@extends('layouts.tickets')

@section('title', $ticket->reference)

@section('content')
    <div class="ticket-back-row"><a href="{{ route('user-tickets.index') }}"><i class="fa-solid fa-arrow-left"></i> Back to Support Tickets</a></div>

    @if (session('success'))
        <div class="ticket-alert ticket-alert-success" role="status"><i class="fa-solid fa-circle-check"></i><span>{{ session('success') }}</span></div>
    @endif
    @if (session('ticket_notice'))
        <div class="ticket-alert ticket-alert-info" role="status"><i class="fa-solid fa-circle-info"></i><span>{{ session('ticket_notice') }}</span></div>
    @endif

    <section class="ticket-detail-heading">
        <div>
            <span class="ticket-eyebrow">{{ $ticket->reference }} · SUBMITTED {{ $ticket->created_at->format('M d, Y') }}</span>
            <h1>{{ $ticket->title }}</h1>
            <div class="ticket-detail-heading-meta"><span class="ticket-category-pill">{{ $ticket->category_label }}</span><span class="ticket-status status-{{ $ticket->status }}">{{ $ticket->status_label }}</span></div>
        </div>
        <div class="ticket-lock-label"><i class="fa-solid fa-lock"></i> Original ticket is locked</div>
    </section>

    <div class="ticket-detail-layout">
        <section class="ticket-conversation-panel">
            <div class="ticket-conversation-heading"><div><h2>Ticket conversation</h2><p>Your original concern and all replies are kept together.</p></div><span>{{ $ticket->messages->count() + 1 }} {{ Str::plural('entry', $ticket->messages->count() + 1) }}</span></div>

            <article class="ticket-message ticket-message-original">
                <div class="ticket-message-avatar ticket-avatar-user"><i class="fa-regular fa-user"></i></div>
                <div class="ticket-message-content">
                    <div class="ticket-message-top"><div><strong>You</strong><span>Original submission</span></div><time>{{ $ticket->created_at->format('M d, Y · h:i A') }}</time></div>
                    <p class="ticket-message-body">{{ $ticket->description }}</p>
                    @if ($ticket->attachment_path)
                        <a href="{{ route('user-tickets.attachment', $ticket) }}" class="ticket-attachment-link"><i class="fa-regular fa-image"></i><span>{{ $ticket->attachment_original_name ?: 'View attached screenshot' }}</span><i class="fa-solid fa-download"></i></a>
                    @endif
                    @if ($ticket->wasteReport)
                        <div class="ticket-linked-report"><i class="fa-regular fa-clipboard"></i><div><small>Linked waste report</small><strong>{{ $ticket->wasteReport->reference }} — {{ $ticket->wasteReport->title }}</strong><span>Status: {{ ucfirst($ticket->wasteReport->status) }}</span></div></div>
                    @endif
                </div>
            </article>

            @forelse ($ticket->messages as $message)
                <article class="ticket-message {{ $message->sender_id === auth()->id() ? 'ticket-message-mine' : 'ticket-message-staff' }}">
                    <div class="ticket-message-avatar {{ $message->sender_id === auth()->id() ? 'ticket-avatar-user' : 'ticket-avatar-staff' }}"><i class="{{ $message->sender_id === auth()->id() ? 'fa-regular fa-user' : 'fa-solid fa-headset' }}"></i></div>
                    <div class="ticket-message-content">
                        <div class="ticket-message-top"><div><strong>{{ $message->sender_id === auth()->id() ? 'You' : ($message->sender?->name ?? ucfirst(str_replace('_', ' ', $message->sender_role))) }}</strong><span>{{ $message->sender_id === auth()->id() ? 'Your reply' : 'Support team' }}</span></div><time>{{ $message->created_at->format('M d, Y · h:i A') }}</time></div>
                        <p class="ticket-message-body">{{ $message->body }}</p>
                    </div>
                </article>
            @empty
                <div class="ticket-awaiting-reply"><i class="fa-regular fa-hourglass-half"></i><div><strong>Waiting for the support team</strong><p>Your ticket has been submitted. Please allow around 2–3 working days for an initial response.</p></div></div>
            @endforelse

            @if ($canReply)
                <form method="POST" action="{{ route('user-tickets.reply', $ticket) }}" class="ticket-reply-form" id="ticketReplyForm" data-banned-words="{{ json_encode(config('ticketing.banned_words')) }}">
                    @csrf
                    <label for="body">Add a reply</label>
                    <textarea id="body" name="body" rows="4" maxlength="3000" placeholder="Add information or answer a question from the support team..." required>{{ old('body') }}</textarea>
                    @error('body')<small class="ticket-field-error">{{ $message }}</small>@enderror
                    <div class="ticket-form-alert" id="ticketReplyProfanityAlert" role="alert" hidden><i class="fa-solid fa-triangle-exclamation"></i><span>Please remove inappropriate or prohibited language before sending. Your text will remain in the form.</span></div>
                    <div class="ticket-reply-actions"><small>Replies are saved as part of this ticket and cannot be deleted.</small><button type="submit" class="ticket-button ticket-button-primary"><i class="fa-regular fa-paper-plane"></i> Send Reply</button></div>
                </form>
            @else
                <div class="ticket-closed-notice"><i class="fa-solid fa-lock"></i><div><strong>This ticket is {{ $ticket->status_label }}.</strong><p>It is now read-only. You can still view the full conversation, but you cannot send additional replies or edit the original submission.</p></div></div>
            @endif
        </section>

        <aside class="ticket-details-sidebar">
            <div class="ticket-details-card">
                <h3>Ticket details</h3>
                <dl>
                    <div><dt>Reference number</dt><dd>{{ $ticket->reference }}</dd></div>
                    <div><dt>Category</dt><dd>{{ $ticket->category_label }}</dd></div>
                    <div><dt>Submitted</dt><dd>{{ $ticket->created_at->format('M d, Y') }}</dd></div>
                    <div><dt>Last activity</dt><dd>{{ $ticket->updated_at->diffForHumans() }}</dd></div>
                    <div><dt>Status</dt><dd><span class="ticket-status status-{{ $ticket->status }}">{{ $ticket->status_label }}</span></dd></div>
                </dl>
            </div>
            <div class="ticket-details-note"><i class="fa-solid fa-shield-halved"></i><div><strong>Your submission is preserved</strong><p>The original title, category, description, and attachment cannot be changed or deleted after submission.</p></div></div>
            <div class="ticket-details-note"><i class="fa-regular fa-calendar-check"></i><div><strong>Response time</strong><p>Please allow around 2–3 working days for an initial response. Response times may vary depending on the concern.</p></div></div>
        </aside>
    </div>
@endsection
