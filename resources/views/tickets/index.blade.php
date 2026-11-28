@extends('layouts.tickets')

@section('title', 'Support Tickets')

@section('content')
    <section class="ticket-page-heading">
        <div>
            <span class="ticket-eyebrow"><i class="fa-solid fa-life-ring"></i> HELP & SUPPORT</span>
            <h1>Support Tickets</h1>
            <p>Send a concern to the team and keep all replies in one organized place.</p>
        </div>
        @if (!$activeTicket)
            <a href="{{ route('user-tickets.create') }}" class="ticket-button ticket-button-primary">
                <i class="fa-solid fa-plus"></i> Create a Ticket
            </a>
        @else
            <a href="{{ route('user-tickets.show', $activeTicket) }}" class="ticket-button ticket-button-primary">
                <i class="fa-regular fa-folder-open"></i> View Active Ticket
            </a>
        @endif
    </section>

    @if (session('success'))
        <div class="ticket-alert ticket-alert-success" role="status"><i class="fa-solid fa-circle-check"></i><span>{{ session('success') }}</span></div>
    @endif
    @if (session('ticket_notice'))
        <div class="ticket-alert ticket-alert-info" role="status"><i class="fa-solid fa-circle-info"></i><span>{{ session('ticket_notice') }}</span></div>
    @endif

    @if ($activeTicket)
        <section class="ticket-active-banner">
            <div class="ticket-active-icon"><i class="fa-regular fa-hourglass-half"></i></div>
            <div class="ticket-active-copy">
                <strong>You already have an active ticket</strong>
                <p>To keep requests organized, you can open another ticket after this one is resolved or closed.</p>
                <span>{{ $activeTicket->reference }} · {{ $activeTicket->title }}</span>
            </div>
            <a href="{{ route('user-tickets.show', $activeTicket) }}" class="ticket-text-link">Open ticket <i class="fa-solid fa-arrow-right"></i></a>
        </section>
    @else
        <section class="ticket-tip-banner">
            <i class="fa-regular fa-lightbulb"></i>
            <p><strong>Before creating a ticket:</strong> You can search your previous tickets below to check whether you have already raised the same concern.</p>
        </section>
    @endif

    <section class="ticket-list-panel">
        <div class="ticket-panel-heading">
            <div>
                <h2>Your submitted tickets</h2>
                <p>Keep track of current and previous concerns.</p>
            </div>
            <span class="ticket-count">{{ $tickets->total() }} {{ \Illuminate\Support\Str::plural('ticket', $tickets->total()) }}</span>
        </div>

        <form method="GET" action="{{ route('user-tickets.index') }}" class="ticket-filters">
            <label class="ticket-search-field">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search by title, details, or ticket number..." aria-label="Search tickets">
            </label>
            <select name="status" aria-label="Filter by status">
                <option value="">All statuses</option>
                @foreach (['open' => 'Open', 'in_progress' => 'In Progress', 'waiting_for_user' => 'Waiting for You', 'resolved' => 'Resolved', 'closed' => 'Closed'] as $value => $label)
                    <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <select name="category" aria-label="Filter by category">
                <option value="">All categories</option>
                @foreach ($categories as $value => $label)
                    <option value="{{ $value }}" @selected(($filters['category'] ?? '') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <select name="sort" aria-label="Arrange tickets by date">
                <option value="newest" @selected(($filters['sort'] ?? 'newest') === 'newest')>Newest first</option>
                <option value="oldest" @selected(($filters['sort'] ?? '') === 'oldest')>Oldest first</option>
            </select>
            <button type="submit" class="ticket-button ticket-button-filter">Apply</button>
            @if (count(array_filter($filters)) > 0)
                <a href="{{ route('user-tickets.index') }}" class="ticket-clear-link">Clear</a>
            @endif
        </form>

        @if ($tickets->count())
            <div class="ticket-list">
                @foreach ($tickets as $ticket)
                    <a href="{{ route('user-tickets.show', $ticket) }}" class="ticket-list-item">
                        <div class="ticket-list-main">
                            <div class="ticket-list-icon {{ $ticket->isActive() ? 'is-active' : 'is-closed' }}"><i class="{{ $ticket->isActive() ? 'fa-regular fa-envelope-open' : 'fa-regular fa-envelope' }}"></i></div>
                            <div class="ticket-list-copy">
                                <div class="ticket-list-meta"><span>{{ $ticket->reference }}</span><span class="ticket-meta-dot">·</span><span>{{ $ticket->category_label }}</span></div>
                                <h3>{{ $ticket->title }}</h3>
                                <p>{{ \Illuminate\Support\Str::limit($ticket->description, 105) }}</p>
                            </div>
                        </div>
                        <div class="ticket-list-end">
                            <span class="ticket-status status-{{ $ticket->status }}">{{ $ticket->status_label }}</span>
                            <time datetime="{{ $ticket->created_at->toIso8601String() }}">{{ $ticket->created_at->format('M d, Y') }}</time>
                            <i class="fa-solid fa-chevron-right ticket-chevron"></i>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="ticket-pagination">{{ $tickets->links() }}</div>
        @else
            <div class="ticket-empty-state">
                <div class="ticket-empty-icon"><i class="fa-regular fa-folder-open"></i></div>
                <h3>{{ count(array_filter($filters)) ? 'No matching tickets found' : 'No tickets yet' }}</h3>
                <p>{{ count(array_filter($filters)) ? 'Try changing your search or filters.' : 'When you submit a concern, your ticket and its replies will appear here.' }}</p>
                @if (count(array_filter($filters)))
                    <a href="{{ route('user-tickets.index') }}" class="ticket-text-link">Clear filters</a>
                @elseif (!$activeTicket)
                    <a href="{{ route('user-tickets.create') }}" class="ticket-button ticket-button-primary"><i class="fa-solid fa-plus"></i> Create your first ticket</a>
                @endif
            </div>
        @endif
    </section>
@endsection
