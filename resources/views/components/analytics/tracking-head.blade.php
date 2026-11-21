@php
    $trackingSettings = app(\App\Services\Admin\AdminSettingService::class)->all();
    $trackingEnabled = (bool) ($trackingSettings['tracking_enabled'] ?? '1');
    $dataLayerEnabled = (bool) ($trackingSettings['datalayer_enabled'] ?? '1');
    $gtmEnabled = (bool) ($trackingSettings['gtm_enabled'] ?? '1');
    $gtmId = trim((string) ($trackingSettings['gtm_id'] ?? 'GTM-W62NQS6R'));
    if (empty($gtmId)) {
        $gtmId = 'GTM-W62NQS6R';
    }
    $serverContainerUrl = rtrim(trim((string) ($trackingSettings['server_container_url'] ?? '')), '/');
    $ga4Enabled = (bool) ($trackingSettings['ga4_enabled'] ?? '0');
    $ga4Id = trim((string) ($trackingSettings['ga4_measurement_id'] ?? ''));
    $fbPixelEnabled = (bool) ($trackingSettings['fb_pixel_enabled'] ?? '0');
    $fbPixelId = trim((string) ($trackingSettings['fb_pixel_id'] ?? ''));
    $customHeadScript = $trackingSettings['custom_head_script'] ?? '';

    $pageEventId = 'evt_pv_' . time() . '_' . substr(bin2hex(random_bytes(3)), 0, 6);
    $flashAddToCart = session('analytics_add_to_cart');
    $flashPurchase = session('analytics_purchase');
@endphp

@if($trackingEnabled)
    {{-- 1. Initialize Google Tag Manager & Global DataLayer --}}
    @if($gtmEnabled && !empty($gtmId))
        <!-- Google Tag Manager ({{ $gtmId }}) -->
        <script async src="{{ !empty($serverContainerUrl) ? $serverContainerUrl : 'https://www.googletagmanager.com' }}/gtm.js?id={{ $gtmId }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            // Helper to extract first-party cookies (_fbp, _fbc)
            function getSorolataCookie(name) {
                var value = "; " + document.cookie;
                var parts = value.split("; " + name + "=");
                if (parts.length === 2) return parts.pop().split(";").shift();
                return null;
            }

            // Helper to generate unique event_id for deduplication
            function generateSorolataEventId(prefix) {
                return (prefix || 'evt') + '_' + Date.now() + '_' + Math.random().toString(36).substring(2, 8);
            }

            // Standard GTM container start event
            window.dataLayer.push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });

            // Initial DataLayer PageView event matching PDF Step 04
            @if($dataLayerEnabled)
                window.dataLayer.push({
                    event: 'page_view',
                    event_id: '{{ $pageEventId }}',
                    event_time: Math.round(Date.now() / 1000),
                    send_page_view: true,
                    first_party_collection: true,
                    @if(!empty($serverContainerUrl))
                    server_container_url: '{{ $serverContainerUrl }}',
                    @endif
                    'x-fb-ck-fbp': getSorolataCookie('_fbp'),
                    'x-fb-ck-fbc': getSorolataCookie('_fbc')
                });
            @endif
        </script>
        <!-- End Google Tag Manager -->
    @else
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            function getSorolataCookie(name) {
                var value = "; " + document.cookie;
                var parts = value.split("; " + name + "=");
                if (parts.length === 2) return parts.pop().split(";").shift();
                return null;
            }

            function generateSorolataEventId(prefix) {
                return (prefix || 'evt') + '_' + Date.now() + '_' + Math.random().toString(36).substring(2, 8);
            }
        </script>
    @endif

    {{-- 3. Google Analytics 4 (GA4) Direct Fallback if GTM not used --}}
    @if($ga4Enabled && !empty($ga4Id) && (!$gtmEnabled || empty($gtmId)))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $ga4Id }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ $ga4Id }}', {
                'send_page_view': true,
                @if(!empty($serverContainerUrl))
                'server_container_url': '{{ $serverContainerUrl }}',
                @endif
                'first_party_collection': true
            });
        </script>
    @endif

    {{-- 4. Facebook (Meta) Pixel Base Code --}}
    @if($fbPixelEnabled && !empty($fbPixelId))
        <!-- Meta Pixel Code -->
        <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            
            fbq('init', '{{ $fbPixelId }}');
            fbq('track', 'PageView', {}, { eventID: '{{ $pageEventId }}' });
        </script>
        <!-- End Meta Pixel Code -->
    @endif

    {{-- 5. Full E-Commerce DataLayer & Event Pipeline Engine (PDF Steps 05 - 14) --}}
    <script>
        (function() {
            window.sorolataAnalytics = {
                // Step 05: View Item / View Content
                pushViewItem: function(item, customEventId) {
                    var eventId = customEventId || generateSorolataEventId('evt_vi');
                    var fbp = getSorolataCookie('_fbp');
                    var fbc = getSorolataCookie('_fbc');
                    var price = parseFloat(item.price) || 0;
                    var category = item.category || item.item_category || 'Heritage Handcraft';

                    @if($dataLayerEnabled)
                    window.dataLayer.push({ ecommerce: null });
                    window.dataLayer.push({
                        event: 'view_item',
                        event_id: eventId,
                        event_time: Math.round(Date.now() / 1000),
                        content_type: 'product',
                        value: price,
                        currency: 'BDT',
                        ecommerce: {
                            currency: 'BDT',
                            value: price,
                            items: [{
                                item_id: String(item.id || item.item_id),
                                item_name: item.name || item.item_name || item.title,
                                price: price,
                                item_category: category,
                                quantity: 1
                            }],
                            detail: {
                                products: [{
                                    id: String(item.id || item.item_id),
                                    name: item.name || item.item_name || item.title,
                                    price: price,
                                    category: category
                                }]
                            }
                        },
                        'x-fb-cd-content_type': 'product',
                        'x-fb-cd-content_name': item.name || item.item_name || item.title,
                        'x-fb-cd-content_ids': String(item.id || item.item_id),
                        'x-fb-cd-content_category': category,
                        'x-fb-ck-fbp': fbp,
                        'x-fb-ck-fbc': fbc,
                        first_party_collection: true
                    });
                    @endif

                    @if($fbPixelEnabled && !empty($fbPixelId))
                    if (window.fbq) {
                        fbq('track', 'ViewContent', {
                            content_type: 'product',
                            content_name: item.name || item.item_name || item.title,
                            content_ids: [String(item.id || item.item_id)],
                            content_category: category,
                            value: price,
                            currency: 'BDT'
                        }, { eventID: eventId });
                    }
                    @endif
                },

                // Step 06: Add To Cart
                pushAddToCart: function(item, quantity, customEventId) {
                    var qty = parseInt(quantity, 10) || 1;
                    var eventId = customEventId || generateSorolataEventId('evt_atc');
                    var fbp = getSorolataCookie('_fbp');
                    var fbc = getSorolataCookie('_fbc');
                    var price = parseFloat(item.price) || 0;
                    var totalVal = price * qty;
                    var category = item.category || item.item_category || 'Heritage Handcraft';

                    @if($dataLayerEnabled)
                    window.dataLayer.push({ ecommerce: null });
                    window.dataLayer.push({
                        event: 'add_to_cart',
                        event_id: eventId,
                        event_time: Math.round(Date.now() / 1000),
                        content_type: 'product',
                        value: totalVal,
                        currency: 'BDT',
                        ecommerce: {
                            currency: 'BDT',
                            value: totalVal,
                            items: [{
                                item_id: String(item.id || item.item_id),
                                item_name: item.name || item.item_name || item.title,
                                price: price,
                                item_category: category,
                                quantity: qty
                            }],
                            detail: {
                                products: [{
                                    id: String(item.id || item.item_id),
                                    name: item.name || item.item_name || item.title,
                                    price: price,
                                    category: category
                                }]
                            }
                        },
                        'x-fb-cd-content_type': 'product',
                        'x-fb-cd-content_name': item.name || item.item_name || item.title,
                        'x-fb-cd-content_ids': String(item.id || item.item_id),
                        'x-fb-cd-content_category': category,
                        'x-fb-ck-fbp': fbp,
                        'x-fb-ck-fbc': fbc,
                        first_party_collection: true
                    });
                    @endif

                    @if($fbPixelEnabled && !empty($fbPixelId))
                    if (window.fbq) {
                        fbq('track', 'AddToCart', {
                            content_type: 'product',
                            content_name: item.name || item.item_name || item.title,
                            content_ids: [String(item.id || item.item_id)],
                            content_category: category,
                            value: totalVal,
                            currency: 'BDT'
                        }, { eventID: eventId });
                    }
                    @endif
                },

                // Step 07: Initiate Checkout / Begin Checkout
                pushBeginCheckout: function(items, totalValue, customEventId) {
                    var eventId = customEventId || generateSorolataEventId('evt_bc');
                    var fbp = getSorolataCookie('_fbp');
                    var fbc = getSorolataCookie('_fbc');
                    var total = parseFloat(totalValue) || 0;
                    
                    var formattedItems = (items || []).map(function(it) {
                        return {
                            item_id: String(it.item_id || it.id),
                            item_name: it.item_name || it.name || it.title,
                            price: parseFloat(it.price) || 0,
                            item_category: it.item_category || it.category || 'Heritage Handcraft',
                            quantity: parseInt(it.quantity, 10) || 1
                        };
                    });

                    var contentNames = formattedItems.map(function(i) { return i.item_name; }).join(', ');
                    var contentIds = formattedItems.map(function(i) { return i.item_id; });
                    var totalQty = formattedItems.reduce(function(sum, i) { return sum + i.quantity; }, 0);

                    @if($dataLayerEnabled)
                    window.dataLayer.push({ ecommerce: null });
                    window.dataLayer.push({
                        event: 'begin_checkout',
                        event_id: eventId,
                        event_time: Math.round(Date.now() / 1000),
                        content_type: 'product',
                        value: total,
                        currency: 'BDT',
                        num_items: totalQty,
                        ecommerce: {
                            currency: 'BDT',
                            currencyCode: 'BDT',
                            value: total,
                            items: formattedItems
                        },
                        'x-fb-cd-content_type': 'product',
                        'x-fb-cd-content_name': contentNames,
                        'x-fb-cd-content_ids': contentIds.join(', '),
                        'x-fb-ck-fbp': fbp,
                        'x-fb-ck-fbc': fbc,
                        first_party_collection: true
                    });
                    @endif

                    @if($fbPixelEnabled && !empty($fbPixelId))
                    if (window.fbq) {
                        fbq('track', 'InitiateCheckout', {
                            content_type: 'product',
                            content_name: contentNames,
                            content_ids: contentIds,
                            value: total,
                            currency: 'BDT',
                            num_items: totalQty
                        }, { eventID: eventId });
                    }
                    @endif
                },

                // Step 08 & 10: Purchase & Enhanced Conversions
                pushPurchase: function(orderData, customEventId) {
                    var eventId = customEventId || orderData.event_id || generateSorolataEventId('evt_pur');
                    var fbp = getSorolataCookie('_fbp');
                    var fbc = getSorolataCookie('_fbc');
                    var total = parseFloat(orderData.total) || 0;

                    var formattedItems = (orderData.items || []).map(function(it) {
                        return {
                            item_id: String(it.item_id || it.id),
                            item_name: it.item_name || it.name || it.title,
                            price: parseFloat(it.price) || 0,
                            item_category: it.item_category || it.category || 'Heritage Handcraft',
                            quantity: parseInt(it.quantity, 10) || 1
                        };
                    });

                    var contentNames = formattedItems.map(function(i) { return i.item_name; }).join(', ');
                    var contentIds = formattedItems.map(function(i) { return i.item_id; });
                    var contentCategories = formattedItems.map(function(i) { return i.item_category; }).filter(Boolean).join(', ');
                    var totalQty = formattedItems.reduce(function(sum, i) { return sum + i.quantity; }, 0);

                    @if($dataLayerEnabled)
                    window.dataLayer.push({ ecommerce: null });
                    window.dataLayer.push({
                        event: 'purchase',
                        event_id: eventId,
                        event_time: Math.round(Date.now() / 1000),
                        content_type: 'product',
                        value: total,
                        currency: 'BDT',
                        transaction_id: String(orderData.order_number),
                        'x-fb-cd-content_type': 'Product',
                        'x-fb-cd-content_name': contentNames,
                        'x-fb-cd-content_ids': contentIds.join(', '),
                        'x-fb-cd-content_category': contentCategories || 'Heritage Handcraft',
                        'x-fb-cd-num_items': totalQty,
                        ecommerce: {
                            transaction_id: String(orderData.order_number),
                            value: total,
                            currency: 'BDT',
                            shipping: parseFloat(orderData.shipping) || 0,
                            coupon: orderData.coupon || '',
                            items: formattedItems
                        },
                        orderData: {
                            customer: {
                                billing: {
                                    email: orderData.customer_email || '',
                                    phone: orderData.customer_phone || '',
                                    first_name: orderData.first_name || '',
                                    last_name: orderData.last_name || '',
                                    country: 'Bangladesh',
                                    city: orderData.district || '',
                                    postal_code: '',
                                    coupon: orderData.coupon || ''
                                }
                            }
                        },
                        user_data: {
                            email_address: orderData.customer_email || '',
                            phone_number: orderData.customer_phone || '',
                            first_name: orderData.first_name || '',
                            last_name: orderData.last_name || '',
                            country: 'BD',
                            city: orderData.district || '',
                            postal_code: '',
                            coupon: orderData.coupon || ''
                        },
                        'x-fb-ck-fbp': fbp,
                        'x-fb-ck-fbc': fbc,
                        first_party_collection: true
                    });
                    @endif

                    @if($fbPixelEnabled && !empty($fbPixelId))
                    if (window.fbq) {
                        fbq('track', 'Purchase', {
                            content_type: 'product',
                            content_name: contentNames,
                            content_ids: contentIds,
                            content_category: contentCategories,
                            value: total,
                            currency: 'BDT',
                            num_items: totalQty
                        }, { eventID: eventId });
                    }
                    @endif
                },

                // Step 14: Contact / Phone Click Tracking
                pushContactClick: function(clickType, clickUrl) {
                    var eventId = generateSorolataEventId('evt_clk');
                    @if($dataLayerEnabled)
                    window.dataLayer.push({
                        event: 'contact_click',
                        event_id: eventId,
                        event_time: Math.round(Date.now() / 1000),
                        click_type: clickType,
                        click_url: clickUrl,
                        'x-fb-ck-fbp': getSorolataCookie('_fbp'),
                        'x-fb-ck-fbc': getSorolataCookie('_fbc')
                    });
                    @endif

                    @if($fbPixelEnabled && !empty($fbPixelId))
                    if (window.fbq) {
                        fbq('track', 'Contact', {
                            content_name: clickType,
                            content_category: 'Direct Concierge Communication'
                        }, { eventID: eventId });
                    }
                    @endif
                }
            };

            // Global Click Listener for Phone (tel:) & Concierge Links (PDF Step 14)
            document.addEventListener('click', function(e) {
                var target = e.target.closest('a');
                if (!target) return;
                var href = target.getAttribute('href') || '';
                if (href.indexOf('tel:') === 0) {
                    window.sorolataAnalytics.pushContactClick('phone_call', href);
                } else if (href.indexOf('wa.me') !== -1 || href.indexOf('whatsapp.com') !== -1) {
                    window.sorolataAnalytics.pushContactClick('whatsapp', href);
                }
            });

            // Handle Flash Add To Cart from traditional form submissions
            @if(!empty($flashAddToCart))
                document.addEventListener('DOMContentLoaded', function() {
                    window.sorolataAnalytics.pushAddToCart(
                        @json($flashAddToCart['item']),
                        {{ $flashAddToCart['item']['quantity'] ?? 1 }},
                        '{{ $flashAddToCart['event_id'] }}'
                    );
                });
            @endif

            // Handle Flash Purchase from Checkout redirection
            @if(!empty($flashPurchase))
                document.addEventListener('DOMContentLoaded', function() {
                    window.sorolataAnalytics.pushPurchase(
                        @json($flashPurchase),
                        '{{ $flashPurchase['event_id'] }}'
                    );
                });
            @endif
        })();
    </script>

    {{-- 6. Custom Head Script (Injected from Admin Panel) --}}
    @if(!empty($customHeadScript))
        {!! $customHeadScript !!}
    @endif
@endif
