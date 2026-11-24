<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bantay Basura - My Reports</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/user-dash.css', 'resources/css/user-my-reports.css', 'resources/css/custom.css'])
</head>
<body>
    @include('partials.sidebar')
    <div class="main-wrapper">
        <header class="top-header">
            <div class="user-greeting">
                <div class="avatar-initials">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div class="greeting-text"><h2>Hello, {{ auth()->user()->name }}!</h2></div>
            </div>
            <div class="top-actions">
                <div class="profile-pill">
                    <div class="avatar-initials">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <div class="profile-info"><div class="name">{{ auth()->user()->name }}</div><div class="role">{{ ucfirst(auth()->user()->role) }}</div></div>
                    <i class="fa-solid fa-chevron-down dropdown-icon"></i>
                </div>
            </div>
        </header>

        <main class="dashboard-content reports-page">
            <div class="reports-page-heading">
                <div class="page-title">
                    <h1>My Reports</h1>
                    <p>Review the waste concerns you have submitted and follow their progress.</p>
                </div>
                <a href="{{ route('user-report') }}" class="reports-create-btn"><i class="fa-solid fa-plus"></i> New report</a>
            </div>

            @if (session('success'))
                <div class="reports-success" role="status"><i class="fa-regular fa-circle-check"></i>{{ session('success') }}</div>
            @endif

            <section class="report-summary" aria-label="Report summary">
                <article class="summary-card summary-total">
                    <span class="summary-icon"><i class="fa-solid fa-folder-open"></i></span>
                    <div><span class="summary-label">Total reports</span><strong>{{ $totalReports }}</strong></div>
                </article>
                <article class="summary-card summary-pending">
                    <span class="summary-icon"><i class="fa-regular fa-clock"></i></span>
                    <div><span class="summary-label">Pending</span><strong>{{ $pendingReports }}</strong></div>
                </article>
                <article class="summary-card summary-resolved">
                    <span class="summary-icon"><i class="fa-regular fa-circle-check"></i></span>
                    <div><span class="summary-label">Resolved</span><strong>{{ $resolvedReports }}</strong></div>
                </article>
            </section>

            <section class="reports-section">
                <div class="reports-section-heading">
                    <div><h2>Submitted reports</h2><p>Select a report to view its details, evidence, and recorded location. Place names are provided by <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener noreferrer">© OpenStreetMap contributors</a>.</p></div>
                    <span class="reports-count">{{ $totalReports }} {{ $totalReports === 1 ? 'report' : 'reports' }}</span>
                </div>
                <div class="reports-card">
                    <div class="reports-head" aria-hidden="true"><span>Report</span><span>Date submitted</span><span>Location</span><span>Status</span><span></span></div>
                    @forelse ($reports as $report)
                        @php
                            $status = strtolower($report->status ?? 'pending');
                            $statusLabels = ['pending' => 'Pending', 'verified' => 'Under review', 'resolved' => 'Resolved', 'rejected' => 'Rejected'];
                            $statusLabel = $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status));
                            $statusNotes = [
                                'pending' => 'Your report has been submitted and is waiting for the barangay team to review it.',
                                'verified' => 'Your report has been verified and is being reviewed or acted on by the barangay team.',
                                'resolved' => 'This report has been marked as resolved by the barangay team.',
                                'rejected' => 'This report was not accepted. Please contact your barangay team if you need clarification.',
                            ];
                            $categoryLabels = \App\Http\Controllers\WasteReportController::CATEGORIES;
                            $categoryLabel = $categoryLabels[$report->category] ?? ucfirst(str_replace('_', ' ', $report->category));
                            $locationLabel = $report->location_name ?: 'GPS location saved (place name unavailable)';
                            $latitude = $report->latitude;
                            $longitude = $report->longitude;
                        @endphp
                        <article class="report-row">
                            <button type="button" class="report-toggle" aria-expanded="false" aria-controls="report-panel-{{ $report->id }}" id="report-btn-{{ $report->id }}">
                                <span class="cell cell-title">
                                    <span class="report-type-icon"><i class="fa-solid fa-recycle"></i></span>
                                    <span class="report-title-text">{{ $report->title }}<small>{{ $report->reference }} · {{ $categoryLabel }}</small></span>
                                </span>
                                <span class="cell cell-date"><i class="fa-regular fa-calendar"></i>{{ $report->created_at?->format('M d, Y · g:i A') }}</span>
                                <span class="cell cell-location" title="{{ $locationLabel }}"><i class="fa-solid fa-location-dot"></i>{{ $locationLabel }}</span>
                                <span class="cell cell-status"><span class="badge {{ $status }}">{{ $statusLabel }}</span></span>
                                <span class="cell cell-chevron"><i class="fa-solid fa-chevron-down"></i></span>
                            </button>
                            <div class="report-panel" id="report-panel-{{ $report->id }}" role="region" aria-labelledby="report-btn-{{ $report->id }}">
                                <div class="report-panel-inner">
                                    <div class="report-detail">
                                        @if ($report->photo_path)
                                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($report->photo_path) }}" alt="Photo evidence for {{ $report->title }}" class="detail-photo" loading="lazy">
                                        @else
                                            <div class="detail-photo detail-photo-empty"><i class="fa-regular fa-image"></i><span>No photo attached</span></div>
                                        @endif
                                        <div class="detail-body">
                                            <dl class="detail-grid">
                                                <div><dt>Report ID</dt><dd>{{ $report->reference }}</dd></div>
                                                <div><dt>Category</dt><dd>{{ $categoryLabel }}</dd></div>
                                                <div><dt>Submitted</dt><dd>{{ $report->created_at?->format('F d, Y · g:i A') }}</dd></div>
                                                <div><dt>Location</dt><dd>{{ $locationLabel }}</dd></div>
                                                <div><dt>GPS coordinates</dt><dd>{{ number_format((float) $latitude, 7) }}, {{ number_format((float) $longitude, 7) }}<a href="https://www.google.com/maps?q={{ $latitude }},{{ $longitude }}" target="_blank" rel="noopener noreferrer" class="map-link">Open in Google Maps <i class="fa-solid fa-arrow-up-right-from-square"></i></a></dd></div>
                                            </dl>
                                            <div class="detail-description"><h4>Description</h4><p>{{ $report->description }}</p></div>
                                            <div class="detail-note {{ $status }}">
                                                <i class="{{ $status === 'resolved' ? 'fa-regular fa-circle-check' : ($status === 'rejected' ? 'fa-solid fa-circle-exclamation' : 'fa-regular fa-hourglass-half') }}"></i>
                                                <span>{{ $statusNotes[$status] ?? 'Your report status will be updated here when the barangay team takes action.' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="reports-empty">
                            <i class="fa-regular fa-clipboard"></i><h3>No reports yet</h3>
                            <p>Reports you submit will appear here so you can track their status.</p>
                            <a href="{{ route('user-report') }}" class="btn-report"><i class="fa-solid fa-plus"></i> Submit your first report</a>
                        </div>
                    @endforelse
                </div>
            </section>
        </main>
    </div>
    <script>
        const menuToggle = document.getElementById('menuToggle');
        const navList = document.getElementById('navList');
        if (menuToggle && navList) menuToggle.addEventListener('click', () => navList.classList.toggle('active'));

        const toggles = document.querySelectorAll('.report-toggle');
        function setOpen(button, open) {
            button.setAttribute('aria-expanded', open ? 'true' : 'false');
            button.closest('.report-row')?.classList.toggle('open', open);
        }
        toggles.forEach(button => button.addEventListener('click', () => {
            const willOpen = button.getAttribute('aria-expanded') !== 'true';
            toggles.forEach(other => setOpen(other, false));
            setOpen(button, willOpen);
        }));
    </script>
</body>
</html>
