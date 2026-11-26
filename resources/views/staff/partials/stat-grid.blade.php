<section class="staff-stat-grid">
@foreach ($stats as $stat)
    <article class="staff-stat-card"><span class="staff-stat-icon {{ $stat['tone'] ?? 'green' }}"><i class="fa-solid {{ $stat['icon'] }}"></i></span>
        <div><small>{{ $stat['label'] }}</small><strong>{{ $stat['value'] }}</strong><span>{{ $stat['note'] }}</span></div>
    </article>
@endforeach
</section>