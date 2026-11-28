@extends('layouts.tickets')

@section('title', 'Create a Ticket')

@section('content')
    <div class="ticket-back-row"><a href="{{ route('user-tickets.index') }}"><i class="fa-solid fa-arrow-left"></i> Back to Support Tickets</a></div>

    <section class="ticket-page-heading ticket-create-heading">
        <div>
            <span class="ticket-eyebrow"><i class="fa-solid fa-pen-to-square"></i> NEW SUPPORT REQUEST</span>
            <h1>Create a Ticket</h1>
            <p>Tell us what happened. Please provide enough detail so the right team can understand your concern.</p>
        </div>
    </section>

    @if ($errors->has('ticket'))
        <div class="ticket-alert ticket-alert-warning"><i class="fa-solid fa-circle-exclamation"></i><span>{{ $errors->first('ticket') }}</span></div>
    @endif

    <div class="ticket-create-layout">
        <section class="ticket-form-panel">
            <form method="POST" action="{{ route('user-tickets.store') }}" enctype="multipart/form-data" id="ticketCreateForm" data-banned-words="{{ json_encode(config('ticketing.banned_words')) }}">
                @csrf
                @if (!empty($appealViolation))
                    <input type="hidden" name="volunteer_violation_id" value="{{ $appealViolation->id }}">
                    <div class="ticket-alert ticket-alert-info"><i class="fa-solid fa-scale-balanced"></i><span>This ticket will be linked to your penalty recorded on {{ $appealViolation->recorded_at->format('M d, Y') }} ({{ $appealViolation->points_deducted }} points deducted).</span></div>
                @endif
                <div class="ticket-form-section">
                    <div class="ticket-form-section-heading"><span>01</span><div><h2>Describe your concern</h2><p>All fields marked with * are required.</p></div></div>

                    <div class="ticket-field">
                        <label for="category">What is your concern about? <span>*</span></label>
                        <select id="category" name="category" required>
                            <option value="">Select a category</option>
                            @foreach ($categories as $value => $label)
                                <option value="{{ $value }}" @selected(old('category', !empty($appealViolation) ? 'volunteer_point_dispute' : '') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('category')<small class="ticket-field-error">{{ $message }}</small>@enderror
                    </div>

                    <div class="ticket-field">
                        <label for="title">Ticket title <span>*</span></label>
                        <input type="text" id="title" name="title" maxlength="120" value="{{ old('title') }}" placeholder="Briefly summarize your concern" required>
                        <div class="ticket-field-foot"><small>Keep it short and specific.</small><small><span id="titleCount">{{ mb_strlen(old('title', '')) }}</span>/120</small></div>
                        @error('title')<small class="ticket-field-error">{{ $message }}</small>@enderror
                    </div>

                    <div class="ticket-field">
                        <label for="description">Description <span>*</span></label>
                        <textarea id="description" name="description" rows="7" maxlength="3000" placeholder="Explain what happened, where it happened, and what help you need..." required>{{ old('description') }}</textarea>
                        <div class="ticket-field-foot"><small>Please avoid sharing passwords or sensitive personal information.</small><small><span id="descriptionCount">{{ mb_strlen(old('description', '')) }}</span>/3000</small></div>
                        @error('description')<small class="ticket-field-error">{{ $message }}</small>@enderror
                    </div>
                    <div class="ticket-form-alert" id="ticketProfanityAlert" role="alert" hidden><i class="fa-solid fa-triangle-exclamation"></i><span>Please remove inappropriate or prohibited language before submitting. Your text will remain in the form.</span></div>
                </div>

                <div class="ticket-form-section">
                    <div class="ticket-form-section-heading"><span>02</span><div><h2>Add supporting information <em>Optional</em></h2><p>Attach one screenshot or link one of your existing waste reports.</p></div></div>

                    <div class="ticket-attachment-choice">
                    @if (!empty($appealViolation))
                        <div class="ticket-field ticket-report-select"><strong>Penalty being appealed</strong><p>{{ $appealViolation->description }} ({{ $appealViolation->points_deducted }} points deducted)</p></div>
                    @endif
                        <div class="ticket-attachment-option">
                            <div class="ticket-attachment-option-icon"><i class="fa-regular fa-image"></i></div>
                            <div class="ticket-attachment-option-copy"><strong>Upload a screenshot</strong><small>JPG, PNG, or WEBP · Up to 5 MB</small></div>
                            <input type="file" id="attachment" name="attachment" accept="image/jpeg,image/png,image/webp">
                        </div>
                        @error('attachment')<small class="ticket-field-error">{{ $message }}</small>@enderror
                        <div class="ticket-file-preview" id="ticketFilePreview" hidden>
                            <i class="fa-regular fa-image"></i><span id="ticketFileName"></span><button type="button" id="removeTicketFile" aria-label="Remove screenshot"><i class="fa-solid fa-xmark"></i></button>
                        </div>

                        <div class="ticket-attachment-divider"><span>OR</span></div>

                        <div class="ticket-field ticket-report-select">
                            <label for="waste_report_id">Attach one of your waste reports</label>
                            <select id="waste_report_id" name="waste_report_id">
                                <option value="">No report attached</option>
                                @foreach ($reports as $report)
                                    <option value="{{ $report->id }}" @selected((string) old('waste_report_id') === (string) $report->id)>{{ $report->reference }} — {{ $report->title }} ({{ ucfirst($report->status) }})</option>
                                @endforeach
                            </select>
                            <small>This links the report to your ticket; it does not create or change the report.</small>
                            @error('waste_report_id')<small class="ticket-field-error">{{ $message }}</small>@enderror
                            @if ($reports->isEmpty())<small class="ticket-field-hint">You do not have any waste reports to attach yet.</small>@endif
                        </div>
                    </div>
                </div>

                <div class="ticket-submit-notice"><i class="fa-solid fa-lock"></i><p><strong>Please review before sending.</strong> After submission, the original title, description, category, and attachment cannot be edited or deleted. You can still reply to the ticket while it is active.</p></div>

                <div class="ticket-form-actions">
                    <a href="{{ route('user-tickets.index') }}" class="ticket-button ticket-button-light">Cancel</a>
                    <button type="submit" class="ticket-button ticket-button-primary"><i class="fa-regular fa-paper-plane"></i> Submit Ticket</button>
                </div>
            </form>
        </section>

        <aside class="ticket-guidance-panel">
            <div class="ticket-guidance-icon"><i class="fa-regular fa-clock"></i></div>
            <h3>What happens next?</h3>
            <ol>
                <li><span>1</span><div><strong>Your ticket is recorded</strong><p>It will appear in your ticket history with a reference number.</p></div></li>
                <li><span>2</span><div><strong>The team reviews it</strong><p>An Administrator, Account & Support Manager, or Purok Leader may handle your concern.</p></div></li>
                <li><span>3</span><div><strong>Check back for a reply</strong><p>Please allow around 2–3 working days for an initial response. Actual response time may vary.</p></div></li>
            </ol>
            <div class="ticket-guidance-foot"><i class="fa-solid fa-circle-info"></i><p>You can have one active ticket at a time. Once it is resolved or closed, you may submit another.</p></div>
        </aside>
    </div>
@endsection
