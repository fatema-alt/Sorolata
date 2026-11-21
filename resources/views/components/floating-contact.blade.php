@php
    $adminSettings = app(\App\Services\Admin\AdminSettingService::class);
    $isEnabled = ($adminSettings->get('floating_contact_enabled', '1')) !== '0';

    if (! $isEnabled) {
        return;
    }

    $rawWa = (string) $adminSettings->get('whatsapp_number', '8801700000000');
    $cleanWa = preg_replace('/[^0-9]/', '', $rawWa) ?: '8801700000000';
    $waMsg = (string) $adminSettings->get('whatsapp_default_message', __('Hello Sorolata Concierge, I would like to inquire about your handcrafted collections.'));
    $whatsappUrl = "https://wa.me/{$cleanWa}?text=" . urlencode($waMsg);

    $facebookUrl = (string) $adminSettings->get('facebook_page_url', 'https://facebook.com/sorolatabangladesh');
    $phoneDisplay = (string) $adminSettings->get('contact_phone_display', '+880 1700-000000');
    $phoneRaw = (string) $adminSettings->get('contact_phone_number', '+8801700000000');

    $fbTitleBn = (string) $adminSettings->get('facebook_label_bn', 'ফেসবুক পেজ');
    $fbTitleEn = (string) $adminSettings->get('facebook_label_en', 'Facebook Page');
    $fbSubBn = (string) $adminSettings->get('facebook_subtitle_bn', 'ইনবক্সে মেসেজ পাঠান');
    $fbSubEn = (string) $adminSettings->get('facebook_subtitle_en', 'Send us a message');

    $waTitleBn = (string) $adminSettings->get('whatsapp_label_bn', 'হোয়াটসঅ্যাপ কনসিয়ার্জ');
    $waTitleEn = (string) $adminSettings->get('whatsapp_label_en', 'WhatsApp Concierge');
    $waSubBn = (string) $adminSettings->get('whatsapp_subtitle_bn', 'তাৎক্ষণিক লাইভ সহায়তা');
    $waSubEn = (string) $adminSettings->get('whatsapp_subtitle_en', 'Instant Live Assistance');

    $callTitleBn = (string) $adminSettings->get('call_label_bn', 'সরাসরি কল করুন');
    $callTitleEn = (string) $adminSettings->get('call_label_en', 'Call us');

    $isEn = app()->getLocale() === 'en';
@endphp

<!-- ============ SOROLATA LUXURY FLOATING CONCIERGE ============ -->
<style>
  .sl-fab{
    --green:#214E34; --green2:#2A6847; --wa:#25D366; --fb:#1877F2; --call:#C96A3D;
    position:fixed !important; right:24px !important; bottom:24px !important; z-index:99999 !important;
    display:flex !important; flex-direction:column !important; align-items:flex-end !important; gap:12px !important;
    font-family:"Hind Siliguri",system-ui,-apple-system,sans-serif !important;
    margin:0 !important; padding:0 !important;
  }
  /* Action rows */
  .sl-fab__item{
    display:flex !important; align-items:center !important; gap:10px !important; text-decoration:none !important;
    opacity:0 !important; transform:translateY(14px) scale(.9) !important; pointer-events:none !important;
    transition:opacity .22s, transform .28s cubic-bezier(.2,.9,.3,1.2) !important;
  }
  .sl-fab.is-open .sl-fab__item{
    opacity:1 !important; transform:none !important; pointer-events:auto !important;
  }
  .sl-fab.is-open .sl-fab__item:nth-child(3){transition-delay:.02s}
  .sl-fab.is-open .sl-fab__item:nth-child(2){transition-delay:.07s}
  .sl-fab.is-open .sl-fab__item:nth-child(1){transition-delay:.12s}

  .sl-fab__tag{
    background:#ffffff !important; color:#1d2b24 !important; font-size:13px !important; font-weight:600 !important;
    padding:8px 14px !important; border-radius:999px !important; white-space:nowrap !important;
    box-shadow:0 6px 20px rgba(29,43,36,.15) !important; border:1px solid #e6e1d8 !important;
    line-height:1.25 !important; text-align:right !important;
  }
  .sl-fab__tag small{
    display:block !important; font-size:11px !important; font-weight:400 !important; color:#6b7a72 !important; margin-top:1px !important;
  }
  .sl-fab__circle{
    width:50px !important; height:50px !important; border-radius:50% !important; display:grid !important; place-items:center !important;
    box-shadow:0 8px 22px rgba(0,0,0,.22) !important; transition:transform .18s !important;
    flex-shrink:0 !important;
  }
  .sl-fab__item:hover .sl-fab__circle{transform:scale(1.1) !important}
  .sl-fab__circle svg{width:24px !important; height:24px !important; fill:#ffffff !important}
  .sl-fab__item:focus-visible{outline:none !important}
  .sl-fab__item:focus-visible .sl-fab__circle{outline:3px solid var(--green) !important; outline-offset:3px !important}
  .is-wa .sl-fab__circle{background:var(--wa) !important}
  .is-fb .sl-fab__circle{background:var(--fb) !important}
  .is-call .sl-fab__circle{background:var(--call) !important}

  /* Main toggle button */
  .sl-fab__main{
    position:relative !important; width:60px !important; height:60px !important; border:0 !important; border-radius:50% !important; cursor:pointer !important;
    background:linear-gradient(135deg,var(--green),var(--green2)) !important; color:#ffffff !important;
    box-shadow:0 12px 30px rgba(31,77,54,.42) !important; display:grid !important; place-items:center !important;
    transition:transform .2s !important; padding:0 !important;
    border:1px solid rgba(212,175,55,0.3) !important;
  }
  .sl-fab__main:hover{transform:scale(1.06) !important}
  .sl-fab__main:focus-visible{outline:3px solid var(--wa) !important; outline-offset:3px !important}
  .sl-fab__main svg{position:absolute !important; width:26px !important; height:26px !important; transition:transform .3s, opacity .2s !important}
  .sl-fab__main .ico-chat{fill:#ffffff !important}
  .sl-fab__main .ico-x{stroke:#ffffff !important; stroke-width:2.4 !important; stroke-linecap:round !important; fill:none !important; opacity:0 !important; transform:rotate(-90deg) !important}
  .sl-fab.is-open .ico-chat{opacity:0 !important; transform:rotate(90deg) !important}
  .sl-fab.is-open .ico-x{opacity:1 !important; transform:none !important}
  .sl-fab__main::after{
    content:"" !important; position:absolute !important; inset:0 !important; border-radius:50% !important;
    border:2px solid var(--green2) !important; animation:sl-ping 2.6s ease-out infinite !important;
  }
  .sl-fab.is-open .sl-fab__main::after{animation:none !important; opacity:0 !important}
  @keyframes sl-ping{from{transform:scale(1);opacity:.7}to{transform:scale(1.55);opacity:0}}

  /* Mobile positioning (float above mobile bottom navigation bar) */
  @media (max-width:768px){
    .sl-fab{right:16px !important; bottom:74px !important}
  }
  @media (prefers-reduced-motion:reduce){
    .sl-fab *{animation:none !important; transition:none !important}
  }
</style>

<div class="sl-fab" id="slFab">
  <!-- 1. Facebook Page -->
  <a class="sl-fab__item is-fb" href="{{ $facebookUrl }}" target="_blank" rel="noopener" aria-label="Facebook">
    <span class="sl-fab__tag">
      <span data-bn="{{ $fbTitleBn }}" data-en="{{ $fbTitleEn }}">{{ $isEn ? $fbTitleEn : $fbTitleBn }}</span>
      <small data-bn="{{ $fbSubBn }}" data-en="{{ $fbSubEn }}">{{ $isEn ? $fbSubEn : $fbSubBn }}</small>
    </span>
    <span class="sl-fab__circle">
      <svg viewBox="0 0 24 24"><path d="M24 12a12 12 0 1 0-13.900 11.900v-8.400H7.100V12h3V9.400c0-3 1.800-4.700 4.500-4.700 1.300 0 2.700.2 2.700.2v3h-1.500c-1.500 0-2 .9-2 1.900V12h3.400l-.5 3.500h-2.800v8.400A12 12 0 0 0 24 12z"/></svg>
    </span>
  </a>

  <!-- 2. WhatsApp Concierge -->
  <a class="sl-fab__item is-wa" href="{{ $whatsappUrl }}" target="_blank" rel="noopener" aria-label="WhatsApp">
    <span class="sl-fab__tag">
      <span data-bn="{{ $waTitleBn }}" data-en="{{ $waTitleEn }}">{{ $isEn ? $waTitleEn : $waTitleBn }}</span>
      <small data-bn="{{ $waSubBn }}" data-en="{{ $waSubEn }}">{{ $isEn ? $waSubEn : $waSubBn }}</small>
    </span>
    <span class="sl-fab__circle">
      <svg viewBox="0 0 24 24"><path d="M20.500 3.500A11.800 11.800 0 0 0 12 0C5.400 0 .1 5.300.1 11.900c0 2.100.5 4.100 1.600 5.900L0 24l6.400-1.700a11.900 11.900 0 0 0 5.600 1.400c6.600 0 11.900-5.300 11.900-11.900 0-3.200-1.200-6.200-3.400-8.300zM12 21.700c-1.800 0-3.600-.5-5.100-1.400l-.4-.2-3.800 1 1-3.700-.2-.4a9.800 9.800 0 0 1-1.500-5.200C2.100 6.500 6.500 2.100 12 2.100c2.600 0 5.100 1 6.900 2.900a9.700 9.700 0 0 1 2.900 6.900c0 5.400-4.400 9.800-9.800 9.800zm5.400-7.300c-.3-.1-1.800-.9-2-1-.3-.1-.5-.1-.7.1l-.9 1.100c-.2.200-.3.200-.6.100-1.800-.9-2.900-1.600-4-3.600-.3-.5.300-.5.900-1.600.1-.2 0-.4 0-.5l-.9-2.200c-.2-.6-.5-.5-.7-.5h-.6c-.2 0-.5.1-.8.400-.3.300-1.100 1.100-1.100 2.600s1.100 3 1.300 3.200c.1.200 2.200 3.400 5.400 4.700 2 .8 2.800.9 3.800.7.600-.1 1.800-.7 2-1.400.3-.7.300-1.300.2-1.400-.1-.2-.3-.3-.6-.4z"/></svg>
    </span>
  </a>

  <!-- 3. Direct Call -->
  <a class="sl-fab__item is-call" href="tel:{{ $phoneRaw }}" aria-label="Call">
    <span class="sl-fab__tag">
      <span data-bn="{{ $callTitleBn }}" data-en="{{ $callTitleEn }}">{{ $isEn ? $callTitleEn : $callTitleBn }}</span>
      <small>{{ $phoneDisplay }}</small>
    </span>
    <span class="sl-fab__circle">
      <svg viewBox="0 0 24 24"><path d="M6.600 10.800a15.100 15.100 0 0 0 6.600 6.600l2.200-2.200a1 1 0 0 1 1-.25c1.100.37 2.300.57 3.600.57a1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.500a1 1 0 0 1 1 1c0 1.300.2 2.500.57 3.600a1 1 0 0 1-.25 1z"/></svg>
    </span>
  </a>

  <!-- Main Toggle Button -->
  <button class="sl-fab__main" id="slFabBtn" type="button" aria-expanded="false" aria-label="Contact options">
    <svg class="ico-chat" viewBox="0 0 24 24"><path d="M4 3h16a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H8l-5 4V5a2 2 0 0 1 1-2z"/></svg>
    <svg class="ico-x" viewBox="0 0 24 24"><path d="M6 6l12 12M18 6L6 18"/></svg>
  </button>
</div>

<script>
(function(){
  var fab = document.getElementById('slFab');
  var btn = document.getElementById('slFabBtn');
  if (!fab || !btn) return;

  function setOpen(isOpen){
    fab.classList.toggle('is-open', isOpen);
    btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
  }

  btn.addEventListener('click', function(e){
    e.stopPropagation();
    setOpen(!fab.classList.contains('is-open'));
  });

  document.addEventListener('keydown', function(e){
    if (e.key === 'Escape') setOpen(false);
  });

  document.addEventListener('click', function(e){
    if (!fab.contains(e.target)) setOpen(false);
  });

  // Dynamic Bengali/English locale switcher
  function renderLocale(){
    var isEn = document.documentElement.lang && document.documentElement.lang.toLowerCase().indexOf('en') === 0;
    var langKey = isEn ? 'en' : 'bn';
    fab.querySelectorAll('[data-bn]').forEach(function(el){
      if (el.dataset && el.dataset[langKey]) {
        el.textContent = el.dataset[langKey];
      }
    });
  }

  if (window.MutationObserver) {
    new MutationObserver(renderLocale).observe(document.documentElement, {
      attributes: true,
      attributeFilter: ['lang']
    });
  }
})();
</script>
