@php
    $trackingSettings = app(\App\Services\Admin\AdminSettingService::class)->all();
    $gtmId = trim((string) ($trackingSettings['gtm_id'] ?? ''));
    if (empty($gtmId)) {
        $gtmId = 'GTM-W62NQS6R';
    }
    $serverContainerUrl = rtrim(trim((string) ($trackingSettings['server_container_url'] ?? '')), '/');
    $customBodyScript = $trackingSettings['custom_body_script'] ?? '';
@endphp

<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe src="{{ !empty($serverContainerUrl) ? $serverContainerUrl : 'https://www.googletagmanager.com' }}/ns.html?id={{ $gtmId }}"
            height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->

@if(!empty($customBodyScript))
    {!! $customBodyScript !!}
@endif
