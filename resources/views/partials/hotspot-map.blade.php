{{-- Expects $hotspots: [x, y, severity (high|medium|low), label] --}}
<div class="map-wrap">
  <svg class="hotspot-map" viewBox="0 0 320 300" role="img" aria-label="Map of waste hotspots in your purok" xmlns="http://www.w3.org/2000/svg">
    <rect width="320" height="300" fill="#eef0e0"/>
    <path d="M0 214 C40 200 70 232 100 252 C124 268 150 282 176 300 L0 300 Z" fill="#cfe3ea"/>
    <path d="M222 0 L320 0 L320 96 C290 88 262 70 246 44 C238 28 230 12 222 0 Z" fill="#cfe0b8"/>
    <path d="M24 38 C46 24 82 28 96 48 C106 64 92 88 66 90 C40 92 14 72 24 38 Z" fill="#d7e6c2"/>
    <g fill="none" stroke="#ffffff" stroke-linecap="round" stroke-linejoin="round">
      <path d="M-10 120 C60 112 120 136 190 120 C240 108 290 118 330 108" stroke-width="9"/>
      <path d="M96 -10 C104 60 92 120 108 180 C118 224 150 262 160 310" stroke-width="9"/>
      <path d="M210 -10 C206 40 226 80 220 130 C216 170 236 230 250 310" stroke-width="7"/>
      <path d="M-10 186 C50 178 120 196 180 186 C230 178 280 190 330 182" stroke-width="6"/>
      <path d="M150 20 C170 60 176 80 210 98" stroke-width="4"/>
      <path d="M30 140 C50 170 60 200 70 230" stroke-width="4"/>
    </g>
    @foreach ($hotspots as $h)
      <g class="pin {{ $h['severity'] }}" transform="translate({{ $h['x'] }} {{ $h['y'] }})" tabindex="0">
        <title>{{ $h['label'] }} ({{ ucfirst($h['severity']) }})</title>
        <path d="M0 0c-6-9-11-13-11-19a11 11 0 1 1 22 0c0 6-5 10-11 19z"/>
        <circle cx="0" cy="-19" r="4" fill="#fff"/>
      </g>
    @endforeach
  </svg>

  <ul class="map-legend" aria-label="Map legend">
    <li><span class="dot high"></span> High</li>
    <li><span class="dot medium"></span> Medium</li>
    <li><span class="dot low"></span> Low</li>
  </ul>
</div>
