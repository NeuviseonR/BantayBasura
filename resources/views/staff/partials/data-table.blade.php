<section class="staff-panel staff-table-panel">
    <div class="staff-panel-heading"><div><h2>{{ $tableTitle }}</h2>@if (!empty($tableDescription))<p>{{ $tableDescription }}</p>@endif</div>
        @if (!empty($tableAction))<button type="button" class="staff-button staff-button-outline staff-button-small" data-demo-toast="{{ $tableActionMessage ?? 'This is a preview-only action.' }}">{{ $tableAction }}</button>@endif
    </div>
    <div class="staff-table-toolbar">
        <label class="staff-search"><i class="fa-solid fa-magnifying-glass"></i><input type="search" placeholder="Search this list..." data-table-search></label>
        <span class="staff-record-count">{{ count($rows) }} sample records</span>
    </div>
    <div class="staff-table-wrap"><table class="staff-table"><thead><tr>@foreach ($headers as $header)<th>{{ $header }}</th>@endforeach</tr></thead>
        <tbody>@forelse ($rows as $row)<tr data-table-row>@foreach ($row as $cell)<td>{!! $cell !!}</td>@endforeach</tr>@empty<tr><td colspan="{{ count($headers) }}" class="staff-empty-cell">No records to display.</td></tr>@endforelse</tbody>
    </table></div>
    <div class="staff-table-footnote">Showing sample information for layout preview</div>
</section>