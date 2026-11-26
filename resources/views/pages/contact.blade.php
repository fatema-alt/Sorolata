@extends('layouts.storefront')

@section('seo_meta')
    <title>Contact Us — Sorolata</title>
    <meta name="description" content="Questions about an order, a gift or a delivery abroad? Talk to us. We are happy to help.">
    <link rel="canonical" href="{{ route('pages.contact') }}">
@endsection

@section('content')
    <!-- Dark Green Jali Header -->
    <section style="position:relative; overflow:hidden; background:#1F4D3A; color:#FBF6EC; border-bottom:3px solid #B8892F">
        <svg aria-hidden="true" width="100%" height="100%" style="position:absolute; inset:0; pointer-events:none; opacity:0.25"><rect width="100%" height="100%" fill="url(#patJaliLight)"></rect></svg>
        <div style="position:relative; max-width:900px; margin:0 auto; padding:56px 24px; text-align:center">
            <h1 style="margin:0 0 12px; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(38px,4.5vw,56px); line-height:1.05; color:#fff">{{ __('Contact Us') }}</h1>
            <p style="margin:0 auto; max-width:520px; font-size:15px; color:#E7EEE4; line-height:1.6">{{ __('Questions about an order, a gift or a delivery abroad? Talk to us. We are happy to help.') }}</p>
        </div>
    </section>

    <!-- Message Form & Direct Channels -->
    <section style="background:#F7F5F0">
        <div style="max-width:1060px; margin:0 auto; padding:64px 24px; display:flex; flex-wrap:wrap; gap:40px; align-items:flex-start">
            <!-- Left: Send us a message -->
            <div style="flex:1.15 1 360px; min-width:0; background:#fff; border:1.5px solid #D9C28A; border-radius:20px; padding:32px; box-shadow:0 10px 28px rgba(43,33,24,0.05)"
                 x-data="{
                     name: '',
                     contact: '',
                     topic: 'A question about an order',
                     message: '',
                     sendWhatsApp() {
                         const text = `Hello Sorolata!%0AName: ${encodeURIComponent(this.name || 'Guest')}%0AContact: ${encodeURIComponent(this.contact || '-')}%0ATopic: ${encodeURIComponent(this.topic)}%0AMessage: ${encodeURIComponent(this.message)}`;
                         window.open(`https://wa.me/8801711000000?text=${text}`, '_blank', 'noopener');
                     }
                 }">
                <h2 style="margin:0 0 20px; font-family:'Cormorant Garamond',Georgia,serif; font-weight:600; font-size:30px; color:#1F4D3A">{{ __('Send us a message') }}</h2>
                <form @submit.prevent="sendWhatsApp()" style="display:flex; flex-direction:column; gap:14px">
                    <div>
                        <label style="display:block; font-size:12px; font-weight:600; color:#5A5044; margin-bottom:5px">{{ __('Your name') }}</label>
                        <input type="text" x-model="name" required placeholder="{{ __('Full name') }}" style="width:100%; height:42px; border:1px solid #D9C28A; border-radius:10px; padding:0 14px; font-size:14px; background:#FBF6EC; color:#2B2118; box-sizing:border-box">
                    </div>
                    <div>
                        <label style="display:block; font-size:12px; font-weight:600; color:#5A5044; margin-bottom:5px">{{ __('Your email or phone') }}</label>
                        <input type="text" x-model="contact" required placeholder="{{ __('Email or WhatsApp number') }}" style="width:100%; height:42px; border:1px solid #D9C28A; border-radius:10px; padding:0 14px; font-size:14px; background:#FBF6EC; color:#2B2118; box-sizing:border-box">
                    </div>
                    <div>
                        <label style="display:block; font-size:12px; font-weight:600; color:#5A5044; margin-bottom:5px">{{ __('What is it about?') }}</label>
                        <select x-model="topic" style="width:100%; height:42px; border:1px solid #D9C28A; border-radius:10px; padding:0 12px; font-size:14px; background:#FBF6EC; color:#2B2118; box-sizing:border-box">
                            <option>{{ __('A question about an order') }}</option>
                            <option>{{ __('Custom handloom or gift order') }}</option>
                            <option>{{ __('International delivery (USA, UK, EU, Canada)') }}</option>
                            <option>{{ __('Something else') }}</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block; font-size:12px; font-weight:600; color:#5A5044; margin-bottom:5px">{{ __('Your message') }}</label>
                        <textarea rows="4" x-model="message" required placeholder="{{ __('Write your message here...') }}" style="width:100%; border:1px solid #D9C28A; border-radius:12px; padding:12px 14px; font-size:14px; background:#FBF6EC; color:#2B2118; box-sizing:border-box"></textarea>
                    </div>
                    <div style="padding-top:4px">
                        <button type="submit" style="background:#B8892F; color:#fff; border:0; border-radius:999px; padding:12px 26px; font-size:14px; font-weight:700; cursor:pointer">
                            {{ __('Send on WhatsApp') }}
                        </button>
                        <div style="font-size:11px; color:#7A6E5E; margin-top:8px">{{ __('This opens WhatsApp with your message ready to send.') }}</div>
                    </div>
                </form>
            </div>

            <!-- Right: Reach us directly -->
            <div style="flex:1 1 320px; min-width:0; display:flex; flex-direction:column; gap:14px">
                <h2 style="margin:0 0 6px; font-family:'Cormorant Garamond',Georgia,serif; font-weight:600; font-size:30px; color:#1F4D3A">{{ __('Reach us directly') }}</h2>

                <a href="https://wa.me/8801711000000" target="_blank" rel="noopener" style="display:flex; align-items:center; gap:14px; background:#fff; border:1px solid #E3D8BC; border-radius:14px; padding:14px 18px; text-decoration:none; color:inherit">
                    <span style="width:40px; height:40px; border-radius:10px; background:#1F4D3A; color:#F3DC9A; display:flex; align-items:center; justify-content:center; flex-shrink:0">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                    </span>
                    <div>
                        <div style="font-size:14px; font-weight:700; color:#1F2A24">{{ __('WhatsApp') }}</div>
                        <div style="font-size:13px; color:#5A5044">+880 1711-000000</div>
                    </div>
                </a>

                <a href="mailto:hello@sorolata.com" style="display:flex; align-items:center; gap:14px; background:#fff; border:1px solid #E3D8BC; border-radius:14px; padding:14px 18px; text-decoration:none; color:inherit">
                    <span style="width:40px; height:40px; border-radius:10px; background:#1F4D3A; color:#F3DC9A; display:flex; align-items:center; justify-content:center; flex-shrink:0">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 6l-10 7L2 6"/></svg>
                    </span>
                    <div>
                        <div style="font-size:14px; font-weight:700; color:#1F2A24">{{ __('Email') }}</div>
                        <div style="font-size:13px; color:#5A5044">hello@sorolata.com</div>
                    </div>
                </a>

                <a href="https://facebook.com/sorolatabangladesh" target="_blank" rel="noopener" style="display:flex; align-items:center; gap:14px; background:#fff; border:1px solid #E3D8BC; border-radius:14px; padding:14px 18px; text-decoration:none; color:inherit">
                    <span style="width:40px; height:40px; border-radius:10px; background:#1F4D3A; color:#F3DC9A; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-weight:800; font-size:18px">f</span>
                    <div>
                        <div style="font-size:14px; font-weight:700; color:#1F2A24">{{ __('Facebook') }}</div>
                        <div style="font-size:13px; color:#5A5044">facebook.com/sorolatabangladesh</div>
                    </div>
                </a>

                <div style="background:#EAE0CC; border:1px solid #D9C28A; border-radius:12px; padding:14px 18px; font-size:13px; color:#4A4136; line-height:1.6">
                    <div><strong>{{ __('Open:') }}</strong> {{ __('Saturday to Thursday, 10 AM – 8 PM (BST)') }}</div>
                    <div><strong>{{ __('We usually reply in:') }}</strong> {{ __('Under 2 hours on WhatsApp') }}</div>
                </div>
            </div>
        </div>
    </section>

    <svg aria-hidden="true" width="100%" height="18" style="display:block; background:#FBF6EC"><rect width="100%" height="18" fill="url(#patBandGold)"></rect></svg>

    <!-- Delivery at a glance -->
    <section style="background:#F0E6D0">
        <div style="max-width:1060px; margin:0 auto; padding:64px 24px">
            <h2 style="margin:0 0 32px; text-align:center; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(30px,3.6vw,42px); color:#1F2A24">{{ __('Delivery at a glance') }}</h2>
            <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(240px,1fr)); gap:20px">
                <div style="background:#fff; border:1px solid #D9C28A; border-radius:14px; padding:22px 24px">
                    <h3 style="margin:0 0 6px; font-family:'Cormorant Garamond',Georgia,serif; font-size:22px; font-weight:600; color:#1F4D3A">{{ __('Inside Bangladesh') }}</h3>
                    <p style="margin:0; font-size:13px; color:#5A5044; line-height:1.6">{{ __('Arrives in 2 to 4 days. Free over ৳ 5,000, or ৳ 80–120 standard courier.') }}</p>
                </div>
                <div style="background:#fff; border:1px solid #D9C28A; border-radius:14px; padding:22px 24px">
                    <h3 style="margin:0 0 6px; font-family:'Cormorant Garamond',Georgia,serif; font-size:22px; font-weight:600; color:#1F4D3A">{{ __('Abroad') }}</h3>
                    <p style="margin:0; font-size:13px; color:#5A5044; line-height:1.6">{{ __('USA, UK, Canada and Europe. Arrives in 7 to 10 days via tracked DHL/FedEx.') }}</p>
                </div>
                <div style="background:#fff; border:1px solid #D9C28A; border-radius:14px; padding:22px 24px">
                    <h3 style="margin:0 0 6px; font-family:'Cormorant Garamond',Georgia,serif; font-size:22px; font-weight:600; color:#1F4D3A">{{ __('Packing and insurance') }}</h3>
                    <p style="margin:0; font-size:13px; color:#5A5044; line-height:1.6">{{ __('Every parcel is packed by hand in breathable cloth or jute and fully insured.') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Common questions (FAQ Accordion) -->
    <section style="background:#F7F5F0">
        <div style="max-width:740px; margin:0 auto; padding:64px 24px" x-data="{ open: null }">
            <h2 style="margin:0 0 32px; text-align:center; font-family:'Cormorant Garamond',Georgia,serif; font-weight:500; font-size:clamp(30px,3.6vw,42px); color:#1F2A24">{{ __('Common questions') }}</h2>
            @php
                $faqs = [
                    ['q' => __('How long does delivery take?'), 'a' => __('Inside Bangladesh, orders arrive in 2 to 4 working days. International tracked parcels to the USA, UK, Canada, and Europe take 7 to 10 working days.')],
                    ['q' => __('Can I return something?'), 'a' => __('Yes. If a piece arrives damaged or is not what you expected, message us within 7 days of delivery for a free exchange or full refund.')],
                    ['q' => __('How do I pay?'), 'a' => __('Inside Bangladesh you can pay with bKash, Nagad, cards, or Cash on Delivery. International orders accept Visa, Mastercard, and online bank cards.')],
                    ['q' => __('Can I send a gift to family in Bangladesh?'), 'a' => __('Yes! Enter their Bangladesh address at checkout and add a personal note in your Collector Bag—we include complimentary botanical gift wrapping and a handwritten card.')],
                    ['q' => __('Can I ask for a special piece?'), 'a' => __('Of course. Message us on WhatsApp with the craft or district you have in mind, and we will check directly with our master makers.')],
                ];
            @endphp
            <div style="border-top:1px solid #D9C28A">
                @foreach($faqs as $idx => $faq)
                    <div style="border-bottom:1px solid #D9C28A">
                        <button type="button" @click="open = (open === {{ $idx }} ? null : {{ $idx }})"
                                style="width:100%; display:flex; align-items:center; justify-content:space-between; gap:16px; padding:18px 4px; background:none; border:0; text-align:left; font-family:'Cormorant Garamond',Georgia,serif; font-size:21px; font-weight:600; color:#1F4D3A; cursor:pointer">
                            <span>{{ $faq['q'] }}</span>
                            <span style="font-family:'Manrope',sans-serif; font-size:18px; color:#8F3F26" x-text="open === {{ $idx }} ? '−' : '+'">+</span>
                        </button>
                        <div x-show="open === {{ $idx }}" x-cloak style="padding:0 4px 18px; font-size:14px; color:#5A5044; line-height:1.65">
                            {{ $faq['a'] }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
