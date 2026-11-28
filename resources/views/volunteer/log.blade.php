@php
    $points = $points ?? collect();
    $tier = $tier ?? collect();
    $transactions = $transactions ?? collect();
    $violations = $violations ?? collect();
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Volunteering Log</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/user-dash.css', 'resources/css/volunteer.css'])
</head>

<body>@include('partials.sidebar')<div class="main-wrapper">
        <header class="top-header">
            <div class="user-greeting">
                @if (auth()->user()->profile_photo_path)
                    <img src="{{ Storage::disk('public')->url(auth()->user()->profile_photo_path) }}" alt="{{ auth()->user()->name }}" class="avatar-img">
                @else
                    <div class="avatar-initials">{{ auth()->user()->initials }}</div>
                @endif
                <div class="greeting-text">
                    <h2>Hello, {{ auth()->user()->name }}!</h2>
                </div>
            </div>

            <div class="top-actions">

                <div class="profile-pill">
                    <div class="avatar-initials">{{ auth()->user()->initials }}</div>
                    <div class="profile-info">
                        <div class="name">{{ auth()->user()->name }}</div>
                        <div class="role">{{ ucfirst(auth()->user()->role) }}</div>
                    </div>
                    <i class="fa-solid fa-chevron-down dropdown-icon"></i>
                </div>
            </div>
        </header>

        <main class="dashboard-content volunteer-page">
            <h1>Volunteering Log</h1>
            <p class="page-sub">Your points, tier, and violation records are tracked transparently.</p>
            <section class="points-overview">
                <div><span>Points balance</span><strong>{{ $points }}</strong><small>points</small></div>
                <div><span>Current tier</span><strong class="tier-name">{{ $tier }}</strong><small>Based on your current points balance</small></div>
            </section>
            <section class="tier-guide">
                <h2>Volunteer tiers</h2>
                <div class="tier-grid">
                    <div><b>New Volunteer</b><span>0–29 points</span></div>
                    <div><b>Active Volunteer</b><span>30–59 points</span></div>
                    <div><b>Dedicated Volunteer</b><span>60–99 points</span></div>
                    <div><b>Community Champion</b><span>100+ points</span></div>
                </div>
            </section>
            <section class="table-card">
                <h2>Points transactions</h2>
                <div class="table-scroll">
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Activity / Reason</th>
                                <th>Change</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $t)
                                <tr>
                                    <td>{{ $t->created_at->format('M d, Y') }}</td>
                                    <td>{{ $t->description }}</td>
                                    <td class="{{ $t->points >= 0 ? 'positive' : 'negative' }}">
                                        {{ $t->points > 0 ? '+' : '' }}{{ $t->points }}</td>
                                    <td>{{ $t->balance_after }}</td>
                            </tr>@empty<tr>
                                    <td colspan="4" class="empty-cell">No points activity yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
            <section class="table-card">
                <h2>Violations</h2>
                <div class="table-scroll">
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Violation</th>
                                <th>Penalty</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($violations as $v)
                                <tr>
                                    <td>{{ $v->recorded_at->format('M d, Y') }}</td>
                                    <td>{{ $v->description }}</td>
                                    <td class="negative">-{{ $v->points_deducted }}</td>
                                    <td>{{ ucfirst($v->status) }}</td>
                                    <td>
                                        @if ($v->status === 'appealed')
                                            <span class="muted">Appeal submitted</span>
                                        @else
                                            <a class="btn secondary small" href="{{ route('user-tickets.create', ['violation' => $v->id]) }}">Appeal deduction</a>
                                        @endif
                                    </td>
                            </tr>@empty<tr>
                                    <td colspan="5" class="empty-cell">No violations recorded. Thank you for
                                        participating responsibly.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</body>

</html>
