@extends('layouts.purok')
@section('title', 'Volunteer Applications')
@section('content')<div class="volunteer-page">
        <h1>Volunteer Applications</h1>
        <p class="page-sub">Review submitted identity documents and approve or decline resident applications.</p>
        @if (session('notice'))
            <div class="notice">{{ session('notice') }}</div>
        @endif
        <div class="table-card">
            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>Applicant</th>
                            <th>Contact</th>
                            <th>Submitted</th>
                            <th>Documents</th>
                            <th>Decision</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($apps as $app)
                            <tr>
                                <td><strong>{{ $app->user->name }}</strong><br><small>{{ $app->user->email }}</small></td>
                                <td>{{ $app->user->mobile }}<br>{{ $app->user->zone }}</td>
                                <td>{{ $app->created_at->format('M d, Y') }}</td>
                                <td><a href="{{ route('purok.volunteer-id', [$app, 'front']) }}" target="_blank">Front ID</a>
                                    @if ($app->id_back_path)
                                        ·
                                        <a href="{{ route('purok.volunteer-id', [$app, 'back']) }}" target="_blank">Back ID</a>
                                    @endif
                                    <br>
                                    <small>{{ $app->id_type }}: {{ $app->id_number }}</small>
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('purok.volunteer-review', $app) }}"
                                        class="decision-form">@csrf
                                        <textarea name="review_notes" placeholder="Notes (optional)"></textarea><button class="btn primary small" name="decision"
                                            value="approve">Approve</button><button class="btn secondary small"
                                            name="decision" value="reject">Reject</button>
                                    </form>
                                </td>
                        </tr>@empty<tr>
                                <td colspan="5">No pending applications.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
</div>@endsection
