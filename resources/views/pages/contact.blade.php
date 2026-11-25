@extends('layouts.storefront')

@section('seo_meta')
    <title>Curator Concierge & International Support | Sorolata</title>
    <meta name="description" content="Connect with our curatorial team in Dhaka or London for custom commissions, expat gifting, or heritage provenance verification.">
    <link rel="canonical" href="{{ route('pages.contact') }}">
@endsection

@section('content')
    <x-heritage-header
        :eyebrow="__('Curator Concierge')"
        :title="__('Connect With Our Atelier')"
        :subtitle="__('Whether you wish to commission an archival family heirloom, verify a Geographical Indication tag, or arrange bespoke international expat courier delivery—our curatorial desk is at your service.')"
        :breadcrumb="__('Contact')"
    />

    <div class="max-w-6xl mx-auto px-6 lg:px-12 py-16">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-12">
            
            <!-- Contact Form -->
            <div class="md:col-span-7 bg-white rounded-[32px] p-8 sm:p-12 border border-[#E8E8E8] shadow-soft-xs space-y-6">
                <h2 class="font-editorial-heading text-3xl font-light text-[#1D1D1D]">
                    {{ __('Dispatch An Inquiry') }}
                </h2>

                <form class="space-y-4">
                    <div class="space-y-1">
                        <label class="text-xs font-semibold uppercase text-[#1D1D1D]">{{ __('Your Name') }}</label>
                        <input type="text" placeholder="{{ __('Full name...') }}" class="w-full text-xs p-3.5 rounded-full border border-[#E8E8E8] bg-[#F8F6F2] focus:outline-none focus:border-[#214E34]">
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold uppercase text-[#1D1D1D]">{{ __('Email Address') }}</label>
                        <input type="email" placeholder="{{ __('Email address...') }}" class="w-full text-xs p-3.5 rounded-full border border-[#E8E8E8] bg-[#F8F6F2] focus:outline-none focus:border-[#214E34]">
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold uppercase text-[#1D1D1D]">{{ __('Subject / Inquiry Nature') }}</label>
                        <select class="w-full text-xs p-3.5 rounded-full border border-[#E8E8E8] bg-[#F8F6F2] focus:outline-none focus:border-[#214E34]">
                            <option>{{ __('Custom Handloom Commission (Muslin / Jamdani)') }}</option>
                            <option>{{ __('Expat Wedding Registry & Gifting') }}</option>
                            <option>{{ __('Order Provenance & Courier Tracking') }}</option>
                            <option>{{ __('Wholesale & Museum Acquisition') }}</option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold uppercase text-[#1D1D1D]">{{ __('Message') }}</label>
                        <textarea rows="4" placeholder="{{ __('How may our curatorial team assist you today?') }}" class="w-full text-xs p-3.5 rounded-[18px] border border-[#E8E8E8] bg-[#F8F6F2] focus:outline-none focus:border-[#214E34]"></textarea>
                    </div>

                    <x-button type="button" variant="primary" size="md" class="w-full justify-center">
                        {{ __('Send Message to Curators →') }}
                    </x-button>
                </form>
            </div>

            <!-- Direct Channels & Concierge Details -->
            <div class="md:col-span-5 space-y-8">
                
                <div class="bg-white rounded-[32px] p-8 border border-[#E8E8E8] shadow-soft-xs space-y-6">
                    <h3 class="font-editorial-heading text-2xl font-light text-[#1D1D1D]">
                        {{ __('Immediate Channels') }}
                    </h3>

                    <div class="space-y-4 text-xs text-[#555555]">
                        <div class="flex items-start gap-3">
                            <x-icon name="sparkles" class="w-4 h-4 text-[#214E34] mt-0.5" />
                            <div>
                                <strong class="text-[#1D1D1D] block">{{ __('WhatsApp Concierge') }}</strong>
                                <a href="https://wa.me/8801700000000" class="text-[#214E34] font-semibold hover:underline">+880 1700 000000 ({{ __('Live Chat') }})</a>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <x-icon name="globe" class="w-4 h-4 text-[#214E34] mt-0.5" />
                            <div>
                                <strong class="text-[#1D1D1D] block">{{ __('Email Dispatches') }}</strong>
                                <a href="mailto:concierge@sorolata.com" class="text-[#214E34] font-semibold hover:underline">concierge@sorolata.com</a>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <x-icon name="map-pin" class="w-4 h-4 text-[#214E34] mt-0.5" />
                            <div>
                                <strong class="text-[#1D1D1D] block">{{ __('Bengal Curatorial Headquarters') }}</strong>
                                <span>{{ __('Road 71, Gulshan-2, Dhaka 1212, Bangladesh') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-[#214E34] text-[#F8F6F2] rounded-[32px] p-8 shadow-soft-md space-y-3">
                    <span class="text-xs uppercase tracking-widest text-[#DE7D4E] font-bold">{{ __('Support Hours') }}</span>
                    <h4 class="font-editorial-heading text-2xl font-light">{{ __('Worldwide Concierge Active') }}</h4>
                    <p class="text-xs text-[#F8F6F2]/80 leading-relaxed font-light">
                        {{ __('Our curatorial specialists assist patrons across Dhaka (BST), London (GMT), and New York (EST) timezones within 2 hours.') }}
                    </p>
                </div>

            </div>

        </div>
    </div>
@endsection
