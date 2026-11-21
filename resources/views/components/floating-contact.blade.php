@php
    $whatsappNumber = config('sorolata.whatsapp', '8801700000000');
    $whatsappUrl = "https://wa.me/{$whatsappNumber}?text=" . urlencode(__('Hello Sorolata Concierge, I would like to inquire about your handcrafted collections.'));
    $phoneDisplay = config('sorolata.phone_display', '+880 1700-000000');
    $phoneRaw = config('sorolata.phone', '+8801700000000');
    $facebookUrl = config('sorolata.facebook_url', 'https://facebook.com');
    $currentLang = app()->getLocale() === 'en' ? 'en' : 'bn';
@endphp

<div 
    x-data="{ 
        open: false,
        hoverPrompt: false,
        toggle() { this.open = !this.open; },
        close() { this.open = false; }
    }"
    @keydown.escape.window="close()"
    @click.outside="close()"
    class="sl-fab-container fixed bottom-20 md:bottom-8 right-5 md:right-8 z-50 flex flex-col items-end select-none font-sans"
    id="sorolataFloatingWidget"
>
    <!-- Expanded Contact Options -->
    <div 
        class="flex flex-col items-end gap-3.5 mb-3.5 transition-all duration-300 origin-bottom-right"
        :class="open ? 'pointer-events-auto opacity-100 translate-y-0 scale-100' : 'pointer-events-none opacity-0 translate-y-4 scale-95'"
        x-cloak
        aria-hidden="!open"
    >
        <!-- 1. Direct Call -->
        <a 
            href="tel:{{ $phoneRaw }}" 
            class="sl-contact-row group flex items-center gap-3 no-underline focus:outline-none"
            style="transition-delay: 120ms;"
            aria-label="{{ __('Call Us') }}"
        >
            <div class="sl-contact-tag bg-white/95 backdrop-blur-md border border-[#E8E5DD] text-[#1D2B24] px-4 py-2 rounded-2xl shadow-[0_8px_24px_rgba(29,43,36,0.12)] transition-all duration-200 group-hover:shadow-[0_10px_28px_rgba(29,43,36,0.18)] group-hover:-translate-x-1">
                <span class="block text-xs font-semibold text-[#1D2B24] leading-tight" data-bn="সরাসরি কল করুন" data-en="Direct Call">
                    {{ $currentLang === 'en' ? 'Direct Call' : 'সরাসরি কল করুন' }}
                </span>
                <span class="block text-[11px] font-medium text-[#C96A3D] mt-0.5" data-bn="{{ $phoneDisplay }} • সকাল ১০টা - রাত ৮টা" data-en="{{ $phoneDisplay }} • 10 AM - 8 PM">
                    {{ $phoneDisplay }} • {{ $currentLang === 'en' ? '10 AM - 8 PM' : 'সকাল ১০টা - রাত ৮টা' }}
                </span>
            </div>
            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#E8A33D] to-[#C96A3D] text-white flex items-center justify-center shadow-[0_8px_20px_rgba(201,106,61,0.35)] transition-transform duration-200 group-hover:scale-110 group-focus-visible:ring-2 group-focus-visible:ring-[#C96A3D] group-focus-visible:ring-offset-2">
                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                    <path d="M6.62 10.79a15.05 15.05 0 006.59 6.59l2.2-2.2a1 1 0 011.02-.24c1.12.37 2.33.57 3.57.57a1 1 0 011 1V20a1 1 0 01-1 1A17 17 0 013 4a1 1 0 011-1h3.5a1 1 0 011 1c0 1.25.2 2.45.57 3.57a1 1 0 01-.25 1.02l-2.2 2.2z"/>
                </svg>
            </div>
        </a>

        <!-- 2. WhatsApp Concierge -->
        <a 
            href="{{ $whatsappUrl }}" 
            target="_blank" 
            rel="noopener noreferrer"
            class="sl-contact-row group flex items-center gap-3 no-underline focus:outline-none"
            style="transition-delay: 70ms;"
            aria-label="{{ __('WhatsApp Concierge') }}"
        >
            <div class="sl-contact-tag bg-white/95 backdrop-blur-md border border-[#E8E5DD] text-[#1D2B24] px-4 py-2 rounded-2xl shadow-[0_8px_24px_rgba(29,43,36,0.12)] transition-all duration-200 group-hover:shadow-[0_10px_28px_rgba(29,43,36,0.18)] group-hover:-translate-x-1">
                <div class="flex items-center gap-1.5">
                    <span class="block text-xs font-semibold text-[#1D2B24] leading-tight" data-bn="হোয়াটসঅ্যাপ কনসিয়ার্জ" data-en="WhatsApp Concierge">
                        {{ $currentLang === 'en' ? 'WhatsApp Concierge' : 'হোয়াটসঅ্যাপ কনসিয়ার্জ' }}
                    </span>
                    <span class="inline-block w-2 h-2 rounded-full bg-[#25D366] animate-pulse"></span>
                </div>
                <span class="block text-[11px] text-[#6B7A72] mt-0.5" data-bn="তাৎক্ষণিক লাইভ সহায়তা ও অর্ডার" data-en="Instant Live Assistance & Order">
                    {{ $currentLang === 'en' ? 'Instant Live Assistance & Order' : 'তাৎক্ষণিক লাইভ সহায়তা ও অর্ডার' }}
                </span>
            </div>
            <div class="w-12 h-12 rounded-full bg-[#25D366] text-white flex items-center justify-center shadow-[0_8px_22px_rgba(37,211,102,0.38)] transition-transform duration-200 group-hover:scale-110 group-focus-visible:ring-2 group-focus-visible:ring-[#25D366] group-focus-visible:ring-offset-2">
                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                    <path d="M20.5 3.5A11.8 11.8 0 0012 0C5.4 0 .1 5.3.1 11.9c0 2.1.5 4.1 1.6 5.9L0 24l6.4-1.7a11.9 11.9 0 005.6 1.4c6.6 0 11.9-5.3 11.9-11.9 0-3.2-1.2-6.2-3.4-8.3zM12 21.7c-1.8 0-3.6-.5-5.1-1.4l-.4-.2-3.8 1 1-3.7-.2-.4a9.8 9.8 0 01-1.5-5.2C2.1 6.5 6.5 2.1 12 2.1c2.6 0 5.1 1 6.9 2.9a9.7 9.7 0 012.9 6.9c0 5.4-4.4 9.8-9.8 9.8zm5.4-7.3c-.3-.1-1.8-.9-2-1-.3-.1-.5-.1-.7.1l-.9 1.1c-.2.2-.3.2-.6.1-1.8-.9-2.9-1.6-4-3.6-.3-.5.3-.5.9-1.6.1-.2 0-.4 0-.5l-.9-2.2c-.2-.6-.5-.5-.7-.5h-.6c-.2 0-.5.1-.8.4-.3.3-1.1 1.1-1.1 2.6s1.1 3 1.3 3.2c.1.2 2.2 3.4 5.4 4.7 2 .8 2.8.9 3.8.7.6-.1 1.8-.7 2-1.4.3-.7.3-1.3.2-1.4-.1-.2-.3-.3-.6-.4z"/>
                </svg>
            </div>
        </a>

        <!-- 3. Facebook Messenger / Page -->
        <a 
            href="{{ $facebookUrl }}" 
            target="_blank" 
            rel="noopener noreferrer"
            class="sl-contact-row group flex items-center gap-3 no-underline focus:outline-none"
            style="transition-delay: 20ms;"
            aria-label="{{ __('Facebook Page') }}"
        >
            <div class="sl-contact-tag bg-white/95 backdrop-blur-md border border-[#E8E5DD] text-[#1D2B24] px-4 py-2 rounded-2xl shadow-[0_8px_24px_rgba(29,43,36,0.12)] transition-all duration-200 group-hover:shadow-[0_10px_28px_rgba(29,43,36,0.18)] group-hover:-translate-x-1">
                <span class="block text-xs font-semibold text-[#1D2B24] leading-tight" data-bn="ফেসবুক পেজ" data-en="Facebook Page">
                    {{ $currentLang === 'en' ? 'Facebook Page' : 'ফেসবুক পেজ' }}
                </span>
                <span class="block text-[11px] text-[#6B7A72] mt-0.5" data-bn="ইনবক্সে মেসেজ পাঠান" data-en="Send us a message">
                    {{ $currentLang === 'en' ? 'Send us a message' : 'ইনবক্সে মেসেজ পাঠান' }}
                </span>
            </div>
            <div class="w-12 h-12 rounded-full bg-[#1877F2] text-white flex items-center justify-center shadow-[0_8px_22px_rgba(24,119,242,0.35)] transition-transform duration-200 group-hover:scale-110 group-focus-visible:ring-2 group-focus-visible:ring-[#1877F2] group-focus-visible:ring-offset-2">
                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                    <path d="M24 12a12 12 0 10-13.9 11.9v-8.4H7.1V12h3V9.4c0-3 1.8-4.7 4.5-4.7 1.3 0 2.7.2 2.7.2v3h-1.5c-1.5 0-2 .9-2 1.9V12h3.4l-.5 3.5h-2.8v8.4A12 12 0 0024 12z"/>
                </svg>
            </div>
        </a>
    </div>

    <!-- Main Trigger Button Wrapper -->
    <div class="relative flex items-center gap-3">
        <!-- Floating Prompt Pill (Visible when closed on hover or subtle initial attraction) -->
        <div 
            x-show="!open" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-2"
            x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-x-0"
            x-transition:leave-end="opacity-0 translate-x-2"
            class="hidden md:flex items-center gap-2 bg-white/95 backdrop-blur-md border border-[#E8E5DD] px-3.5 py-1.5 rounded-full shadow-[0_4px_16px_rgba(29,43,36,0.12)] cursor-pointer hover:bg-white transition-all text-[#214E34]"
            @click="toggle()"
        >
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span class="text-xs font-semibold tracking-wide" data-bn="সহায়তা প্রয়োজন?" data-en="Need Help?">
                {{ $currentLang === 'en' ? 'Need Help?' : 'সহায়তা প্রয়োজন?' }}
            </span>
        </div>

        <!-- Master FAB Button -->
        <button 
            type="button"
            @click="toggle()"
            :aria-expanded="open.toString()"
            aria-label="{{ __('Contact Options & Concierge') }}"
            class="sl-fab-trigger relative w-14 h-14 md:w-[60px] md:h-[60px] rounded-full text-white cursor-pointer flex items-center justify-center focus:outline-none focus-visible:ring-4 focus-visible:ring-[#214E34]/40 transition-transform duration-300 active:scale-95 hover:scale-105"
            style="background: linear-gradient(135deg, #214E34 0%, #2D6846 60%, #1A3F2A 100%); box-shadow: 0 12px 30px rgba(33,78,52,0.42), 0 0 0 1px rgba(212,175,55,0.25);"
        >
            <!-- Radar Ping Ring (Only radiates when closed) -->
            <span 
                class="absolute inset-0 rounded-full border-2 border-[#2D6846] pointer-events-none"
                :class="open ? 'opacity-0' : 'animate-sl-ping'"
            ></span>

            <!-- Online Badge Dot -->
            <span 
                class="absolute top-1 right-1 w-3.5 h-3.5 bg-emerald-400 rounded-full border-2 border-white shadow-sm"
                :class="open ? 'scale-0 opacity-0' : 'scale-100 opacity-100'"
                style="transition: all 0.2s ease;"
            ></span>

            <!-- Icon: Chat Bubble (Rotates & fades out on open) -->
            <svg 
                class="w-6 h-6 md:w-7 md:h-7 fill-white transition-all duration-300"
                :class="open ? 'opacity-0 scale-75 rotate-90 absolute' : 'opacity-100 scale-100 rotate-0'"
                viewBox="0 0 24 24"
            >
                <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H5.2L4 17.2V4h16v12z"/>
                <circle cx="8" cy="10" r="1.3"/>
                <circle cx="12" cy="10" r="1.3"/>
                <circle cx="16" cy="10" r="1.3"/>
            </svg>

            <!-- Icon: Close 'X' (Rotates in on open) -->
            <svg 
                class="w-6 h-6 md:w-7 md:h-7 stroke-white transition-all duration-300 fill-none"
                :class="open ? 'opacity-100 scale-100 rotate-0' : 'opacity-0 scale-75 -rotate-90 absolute'"
                viewBox="0 0 24 24" 
                stroke-width="2.2" 
                stroke-linecap="round" 
                stroke-linejoin="round"
            >
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>
</div>

<style>
    @keyframes sl-ping {
        0% { transform: scale(1); opacity: 0.8; }
        70% { transform: scale(1.6); opacity: 0; }
        100% { transform: scale(1.6); opacity: 0; }
    }
    .animate-sl-ping {
        animation: sl-ping 2.5s cubic-bezier(0, 0, 0.2, 1) infinite;
    }
    @media (prefers-reduced-motion: reduce) {
        .animate-sl-ping {
            animation: none !important;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Dynamic bilingual switch listener in case page updates lang attribute
        function updateFloatingLang() {
            var isEn = document.documentElement.lang && document.documentElement.lang.toLowerCase().indexOf('en') === 0;
            var widget = document.getElementById('sorolataFloatingWidget');
            if (!widget) return;
            widget.querySelectorAll('[data-bn]').forEach(function (el) {
                el.textContent = isEn ? el.getAttribute('data-en') : el.getAttribute('data-bn');
            });
        }
        new MutationObserver(updateFloatingLang).observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['lang']
        });
    });
</script>
