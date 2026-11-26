<div class="staff-page-heading">
    <div><span class="staff-eyebrow">{{ $eyebrow ?? ($actor === 'administrator' ? 'SYSTEM GOVERNANCE' : 'ACCOUNT & COMMUNITY SUPPORT') }}</span>
        <h1>{{ $pageTitle }}</h1><p>{{ $pageDescription }}</p>
    </div>
    @if (!empty($headingAction))
        <button type="button" class="staff-button staff-button-primary" data-demo-toast="{{ $headingActionMessage ?? 'This action will be connected during database integration.' }}"><i class="fa-solid {{ $headingActionIcon ?? 'fa-plus' }}"></i> {{ $headingAction }}</button>
    @endif
</div>