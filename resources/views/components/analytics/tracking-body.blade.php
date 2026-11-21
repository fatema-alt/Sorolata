@php
    $trackingSettings = app(\App\Services\Admin\AdminSettingService::class)->all();
    $trackingEnabled = (bool) ($trackingSettings['tracking_enabled'] ?? '1');
    $gtmEnabled = (bool) ($trackingSettings['gtm_enabled'] ?? '0');
    $gtmId = trim((string) ($trackingSettings['gtm_id'] ?? ''));
    $serverContainerUrl = rtrim(trim((string) ($trackingSettings['server_container_url'] ?? '')), '/');
    $customBodyScript = $trackingSettings['custom_body_script'] ?? '';
@endphp

@if($trackingEnabled)
    {{-- Google Tag Manager (noscript) --}}
    @if($gtmEnabled && !empty($gtmId))
        <noscript>
            <iframe src="{{ !empty($serverContainerUrl) ? $serverContainerUrl : 'https://www.googletagmanager.com' }}/ns.html?id={{ $gtmId }}"
                    height="0" width="0" style="display:none;visibility:hidden"></iframe>
        </noscript>
    @endif

    {{-- Custom Body Script (Injected from Admin Panel) --}}
    @if(!empty($customBodyScript))
        {!! $customBodyScript !!}
    @endif
@endif
