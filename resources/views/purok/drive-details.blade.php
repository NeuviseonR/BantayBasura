@extends('layouts.purok')
@section('title', 'Cleanup Drive Participants')
@section('content')<div class="volunteer-page">
        <a class="back-link" href="{{ route('purok-activities-db') }}">← Cleanup activities</a>
        <h1>{{ $drive->title }}</h1>
        <p class="page-sub">{{ $drive->start_at->format('M d, Y g:i A') }} · {{ $drive->location }}</p>
        <div class="table-card">
            <h2>Participants ({{ $drive->registrations->count() }})</h2>
            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>Volunteer</th>
                            <th>Registered</th>
                            <th>Attendance</th>
                            <th>Evidence</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($drive->registrations as $reg)
                            <tr>
                                <td>{{ $reg->volunteer->name }}</td>
                                <td>{{ $reg->registered_at?->format('M d, Y') }}</td>
                                <td><span
                                        class="status {{ $reg->attendance_status }}">{{ ucfirst(str_replace('_', ' ', $reg->attendance_status)) }}</span>
                                </td>
                                <td>
                                    @foreach ($reg->evidence as $e)
                                        <a href="{{ Storage::disk('public')->url($e->photo_path) }}" target="_blank">View
                                            photo</a>
                                        @endforeach @if ($reg->attendance_status === 'awaiting_verification')
                                            <form method="POST"
                                                action="{{ route('purok-activities-db.verify-photo', $reg) }}">@csrf<button
                                                    class="btn primary small">Approve photos</button></form>
                                        @endif
                                </td>
                        </tr>@empty<tr>
                                <td colspan="4">No volunteers registered yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
</div>@endsection
