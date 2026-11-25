@php
  $type = $section['type'] ?? '';
  $s = $section['settings'] ?? [];
  $inputClass = 'w-full bg-[#12141a] border border-[#282d37] rounded-lg text-xs text-white px-2.5 py-2 focus:border-[#214E34] focus:outline-none';
  $labelClass = 'block text-[11px] font-medium text-gray-300 mb-1';
  $cardClass = 'p-3 rounded-lg bg-[#14161d] border border-[#282d37] space-y-2.5';
@endphp

@if($inspectorTab === 'content')

  {{-- 1. HOME HEADER --}}
  @if($type === 'home_header')
    <div class="{{ $cardClass }}">
      <div class="flex items-center justify-between">
        <span class="text-xs font-semibold text-white">Top Announcement Bar</span>
        <label class="inline-flex items-center gap-1.5 text-xs text-gray-300 cursor-pointer">
          <input type="checkbox" wire:model.live="sections.{{ $sIndex }}.settings.show_topbar" class="rounded bg-[#12141a] border-[#282d37] text-[#214E34]">
          <span>Show</span>
        </label>
      </div>
      <div>
        <label class="{{ $labelClass }}">Top Bar Text</label>
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.topbar_text" class="{{ $inputClass }}">
      </div>
    </div>

    <div class="{{ $cardClass }}">
      <span class="text-xs font-semibold text-white block">Brand & Controls</span>
      <div class="grid grid-cols-2 gap-2">
        <div>
          <label class="{{ $labelClass }}">Brand Name</label>
          <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.brand_name" class="{{ $inputClass }}">
        </div>
        <div>
          <label class="{{ $labelClass }}">Bengali Pill</label>
          <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.brand_bn" class="{{ $inputClass }}">
        </div>
      </div>
      <div class="grid grid-cols-2 gap-2">
        <div>
          <label class="{{ $labelClass }}">Search URL</label>
          <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.search_href" class="{{ $inputClass }}">
        </div>
        <div>
          <label class="{{ $labelClass }}">Cart URL</label>
          <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.cart_href" class="{{ $inputClass }}">
        </div>
      </div>
      <div class="grid grid-cols-2 gap-2">
        <div>
          <label class="{{ $labelClass }}">Wishlist URL</label>
          <input type="text" placeholder="/wishlist" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.wishlist_href" class="{{ $inputClass }}">
        </div>
        <div>
          <label class="{{ $labelClass }}">Account URL</label>
          <input type="text" placeholder="/account" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.account_href" class="{{ $inputClass }}">
        </div>
      </div>
      <div class="flex flex-wrap items-center gap-3 pt-1">
        <label class="inline-flex items-center gap-1.5 text-xs text-gray-300 cursor-pointer">
          <input type="checkbox" wire:model.live="sections.{{ $sIndex }}.settings.show_lang_toggle" class="rounded bg-[#12141a] border-[#282d37] text-[#214E34]">
          <span>EN / বাং</span>
        </label>
        <label class="inline-flex items-center gap-1.5 text-xs text-gray-300 cursor-pointer">
          <input type="checkbox" wire:model.live="sections.{{ $sIndex }}.settings.show_wishlist" class="rounded bg-[#12141a] border-[#282d37] text-[#214E34]">
          <span>Wishlist</span>
        </label>
        <label class="inline-flex items-center gap-1.5 text-xs text-gray-300 cursor-pointer">
          <input type="checkbox" wire:model.live="sections.{{ $sIndex }}.settings.show_account" class="rounded bg-[#12141a] border-[#282d37] text-[#214E34]">
          <span>Avatar</span>
        </label>
        <label class="inline-flex items-center gap-1.5 text-xs text-gray-300 cursor-pointer">
          <input type="checkbox" wire:model.live="sections.{{ $sIndex }}.settings.show_stitch_border" class="rounded bg-[#12141a] border-[#282d37] text-[#214E34]">
          <span>Stitch Border</span>
        </label>
      </div>
    </div>

    <div class="{{ $cardClass }}">
      <div class="flex items-center justify-between">
        <span class="text-xs font-semibold text-white">Navigation Links</span>
        <button type="button" wire:click="addRepeaterItem('nav_links')" class="px-2 py-1 rounded bg-[#214E34] text-white text-[10px] font-semibold hover:bg-[#2c6846]">+ Add Link</button>
      </div>
      @foreach(($s['nav_links'] ?? []) as $i => $item)
        <div class="p-2 rounded bg-[#1a1d24] border border-[#282d37] space-y-1.5" wire:key="nav-{{ $sIndex }}-{{ $i }}">
          <div class="flex items-center justify-between text-[10px] text-gray-400">
            <span>Link #{{ $i + 1 }}</span>
            <div class="flex items-center gap-1">
              <button type="button" wire:click="moveRepeaterItem('nav_links', {{ $i }}, 'up')" class="px-1 hover:text-white">↑</button>
              <button type="button" wire:click="moveRepeaterItem('nav_links', {{ $i }}, 'down')" class="px-1 hover:text-white">↓</button>
              <button type="button" wire:click="removeRepeaterItem('nav_links', {{ $i }})" class="px-1 text-red-400 hover:text-red-300">✕</button>
            </div>
          </div>
          <div class="grid grid-cols-2 gap-1.5">
            <input type="text" placeholder="Label" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.nav_links.{{ $i }}.label" class="{{ $inputClass }}">
            <input type="text" placeholder="#section" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.nav_links.{{ $i }}.href" class="{{ $inputClass }}">
          </div>
        </div>
      @endforeach
    </div>

  {{-- 2. HOME HERO --}}
  @elseif($type === 'home_hero')
    <div class="{{ $cardClass }}">
      <div>
        <label class="{{ $labelClass }}">Main Headline (H1)</label>
        <textarea rows="2" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.headline" class="{{ $inputClass }}"></textarea>
      </div>
      <div>
        <label class="{{ $labelClass }}">Subheadline</label>
        <textarea rows="2" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.subheadline" class="{{ $inputClass }}"></textarea>
      </div>
      <div class="grid grid-cols-2 gap-2">
        <div>
          <label class="{{ $labelClass }}">Primary Button</label>
          <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.primary_cta_text" class="{{ $inputClass }}">
        </div>
        <div>
          <label class="{{ $labelClass }}">Primary URL</label>
          <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.primary_cta_href" class="{{ $inputClass }}">
        </div>
      </div>
      <div class="grid grid-cols-2 gap-2">
        <div>
          <label class="{{ $labelClass }}">Secondary Button</label>
          <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.secondary_cta_text" class="{{ $inputClass }}">
        </div>
        <div>
          <label class="{{ $labelClass }}">Secondary URL</label>
          <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.secondary_cta_href" class="{{ $inputClass }}">
        </div>
      </div>
    </div>

    <div class="{{ $cardClass }}">
      <span class="text-xs font-semibold text-white block">Arch Frame Photo & Featured Card</span>
      @if(!empty($s['image_url']))
        <div class="relative rounded-lg overflow-hidden border border-[#282d37] h-28 bg-[#12141a]">
          <img src="{{ $s['image_url'] }}" class="w-full h-full object-cover">
          <button type="button" wire:click="removeImage" class="absolute top-1.5 right-1.5 px-2 py-0.5 rounded bg-red-600 text-white text-[10px] font-semibold">Remove</button>
        </div>
      @endif
      <div>
        <label class="{{ $labelClass }}">Upload Arch Image</label>
        <input type="file" wire:model="uploadedImage" accept="image/*" class="block w-full text-[11px] text-gray-300 file:mr-2 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-[11px] file:font-semibold file:bg-[#214E34] file:text-white hover:file:bg-[#2c6846]">
      </div>
      <div>
        <label class="{{ $labelClass }}">Or Image URL</label>
        <input type="text" placeholder="https://... or /storage/..." wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.image_url" class="{{ $inputClass }}">
      </div>
      <div class="grid grid-cols-2 gap-2">
        <div>
          <label class="{{ $labelClass }}">Featured Eyebrow</label>
          <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.featured_eyebrow" class="{{ $inputClass }}">
        </div>
        <div>
          <label class="{{ $labelClass }}">Featured Price</label>
          <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.featured_price" class="{{ $inputClass }}">
        </div>
      </div>
      <div class="grid grid-cols-2 gap-2">
        <div>
          <label class="{{ $labelClass }}">Featured Title</label>
          <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.featured_title" class="{{ $inputClass }}">
        </div>
        <div>
          <label class="{{ $labelClass }}">Featured Link</label>
          <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.featured_href" class="{{ $inputClass }}">
        </div>
      </div>
    </div>

    <div class="{{ $cardClass }}">
      <div class="flex items-center justify-between">
        <span class="text-xs font-semibold text-white">Trust Badges</span>
        <button type="button" wire:click="addRepeaterItem('trust_items')" class="px-2 py-1 rounded bg-[#214E34] text-white text-[10px] font-semibold">+ Add</button>
      </div>
      @foreach(($s['trust_items'] ?? []) as $i => $item)
        <div class="flex items-center gap-1.5" wire:key="trust-{{ $sIndex }}-{{ $i }}">
          <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.trust_items.{{ $i }}.text" class="{{ $inputClass }}">
          <button type="button" wire:click="removeRepeaterItem('trust_items', {{ $i }})" class="px-2 py-1.5 rounded bg-[#222731] text-red-400 text-xs">✕</button>
        </div>
      @endforeach
    </div>

  {{-- 3. HOME DISTRICTS & MAP --}}
  @elseif($type === 'home_districts')
    <div class="{{ $cardClass }}">
      <div>
        <label class="{{ $labelClass }}">Eyebrow</label>
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.eyebrow" class="{{ $inputClass }}">
      </div>
      <div>
        <label class="{{ $labelClass }}">Heading</label>
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.heading" class="{{ $inputClass }}">
      </div>
      <div>
        <label class="{{ $labelClass }}">Subtitle</label>
        <textarea rows="2" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.subtitle" class="{{ $inputClass }}"></textarea>
      </div>
    </div>

    <div class="{{ $cardClass }}">
      <span class="text-xs font-semibold text-emerald-400 block">Map Image (Replaces Default SVG Map)</span>
      @if(!empty($s['map_image_url']))
        <div class="relative rounded-lg overflow-hidden border border-[#282d37] h-32 bg-[#12141a]">
          <img src="{{ $s['map_image_url'] }}" class="w-full h-full object-contain">
          <button type="button" wire:click="removeImage" class="absolute top-1.5 right-1.5 px-2 py-0.5 rounded bg-red-600 text-white text-[10px] font-semibold">Remove</button>
        </div>
      @endif
      <div>
        <label class="{{ $labelClass }}">Upload Map Image</label>
        <input type="file" wire:model="uploadedImage" accept="image/*" class="block w-full text-[11px] text-gray-300 file:mr-2 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-[11px] file:font-semibold file:bg-[#214E34] file:text-white hover:file:bg-[#2c6846]">
      </div>
      <div>
        <label class="{{ $labelClass }}">Or Map Image URL</label>
        <input type="text" placeholder="Leave blank for default SVG map" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.map_image_url" class="{{ $inputClass }}">
      </div>
      <label class="inline-flex items-center gap-2 text-xs text-gray-300 cursor-pointer pt-1">
        <input type="checkbox" wire:model.live="sections.{{ $sIndex }}.settings.map_blend_multiply" class="rounded bg-[#12141a] border-[#282d37] text-[#214E34]">
        <span>Transparent White Background (Blend)</span>
      </label>
    </div>

    <div class="{{ $cardClass }}">
      <div class="flex items-center justify-between">
        <span class="text-xs font-semibold text-white">District Cards</span>
        <button type="button" wire:click="addRepeaterItem('items')" class="px-2 py-1 rounded bg-[#214E34] text-white text-[10px] font-semibold">+ Add District</button>
      </div>
      @foreach(($s['items'] ?? []) as $i => $item)
        <div class="p-2.5 rounded bg-[#1a1d24] border border-[#282d37] space-y-1.5" wire:key="dist-{{ $sIndex }}-{{ $i }}">
          <div class="flex items-center justify-between text-[10px] text-gray-400">
            <span class="font-semibold text-gray-200">{{ $item['name'] ?? 'District #' . ($i+1) }}</span>
            <div class="flex items-center gap-1">
              <button type="button" wire:click="moveRepeaterItem('items', {{ $i }}, 'up')" class="px-1 hover:text-white">↑</button>
              <button type="button" wire:click="moveRepeaterItem('items', {{ $i }}, 'down')" class="px-1 hover:text-white">↓</button>
              <button type="button" wire:click="removeRepeaterItem('items', {{ $i }})" class="px-1 text-red-400 hover:text-red-300">✕</button>
            </div>
          </div>
          <div class="grid grid-cols-3 gap-1.5">
            <input type="text" placeholder="#" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.n" class="{{ $inputClass }}">
            <input type="text" placeholder="Name (EN)" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.name" class="col-span-2 {{ $inputClass }}">
          </div>
          <div class="grid grid-cols-2 gap-1.5">
            <input type="text" placeholder="Bengali Name" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.bn" class="{{ $inputClass }}">
            <input type="text" placeholder="Craft Line" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.craft" class="{{ $inputClass }}">
          </div>
          <div class="grid grid-cols-2 gap-1.5">
            <input type="text" placeholder="CTA Text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.cta_text" class="{{ $inputClass }}">
            <input type="text" placeholder="Link URL" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.href" class="{{ $inputClass }}">
          </div>
        </div>
      @endforeach
      <div>
        <label class="{{ $labelClass }}">Bottom Coming Soon Note</label>
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.coming_soon_text" class="{{ $inputClass }}">
      </div>
    </div>

  {{-- 4. HOME CRAFTS --}}
  @elseif($type === 'home_crafts')
    <div class="{{ $cardClass }}">
      <div>
        <label class="{{ $labelClass }}">Eyebrow</label>
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.eyebrow" class="{{ $inputClass }}">
      </div>
      <div>
        <label class="{{ $labelClass }}">Heading</label>
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.heading" class="{{ $inputClass }}">
      </div>
    </div>

    <div class="{{ $cardClass }}">
      <div class="flex items-center justify-between">
        <span class="text-xs font-semibold text-white">Craft Cards</span>
        <button type="button" wire:click="addRepeaterItem('items')" class="px-2 py-1 rounded bg-[#214E34] text-white text-[10px] font-semibold">+ Add Craft</button>
      </div>
      @foreach(($s['items'] ?? []) as $i => $item)
        <div class="p-2.5 rounded bg-[#1a1d24] border border-[#282d37] space-y-1.5" wire:key="craft-{{ $sIndex }}-{{ $i }}">
          <div class="flex items-center justify-between text-[10px] text-gray-400">
            <span class="font-semibold text-gray-200">{{ $item['name'] ?? 'Craft #' . ($i+1) }}</span>
            <div class="flex items-center gap-1">
              <button type="button" wire:click="moveRepeaterItem('items', {{ $i }}, 'up')" class="px-1 hover:text-white">↑</button>
              <button type="button" wire:click="moveRepeaterItem('items', {{ $i }}, 'down')" class="px-1 hover:text-white">↓</button>
              <button type="button" wire:click="removeRepeaterItem('items', {{ $i }})" class="px-1 text-red-400 hover:text-red-300">✕</button>
            </div>
          </div>
          <input type="text" placeholder="Craft Name" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.name" class="{{ $inputClass }}">
          <input type="text" placeholder="Subtitle" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.line" class="{{ $inputClass }}">
          <div class="grid grid-cols-2 gap-1.5">
            <input type="text" placeholder="Photo Pill" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.photo_label" class="{{ $inputClass }}">
            <input type="text" placeholder="Link URL" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.href" class="{{ $inputClass }}">
          </div>
          <div class="pt-1 space-y-1">
            @if(!empty($item['image_url']))
              <div class="flex items-center justify-between text-[10px] text-emerald-400">
                <span>Custom photo set</span>
                <button type="button" wire:click="removeItemImage('items', {{ $i }})" class="text-red-400 hover:underline">Remove</button>
              </div>
            @endif
            <input type="file" wire:click="setItemUploadTarget('items', {{ $i }})" wire:model="uploadedItemImage" accept="image/*" class="block w-full text-[10px] text-gray-300 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:bg-[#282d37] file:text-white">
            <input type="text" placeholder="Or Image URL" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.image_url" class="{{ $inputClass }}">
          </div>
        </div>
      @endforeach
    </div>

  {{-- 5. HOME PRODUCTS --}}
  @elseif($type === 'home_products')
    <div class="{{ $cardClass }}">
      <div>
        <label class="{{ $labelClass }}">Eyebrow</label>
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.eyebrow" class="{{ $inputClass }}">
      </div>
      <div>
        <label class="{{ $labelClass }}">Heading</label>
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.heading" class="{{ $inputClass }}">
      </div>
      <div class="grid grid-cols-2 gap-2">
        <div>
          <label class="{{ $labelClass }}">Bottom CTA</label>
          <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.cta_text" class="{{ $inputClass }}">
        </div>
        <div>
          <label class="{{ $labelClass }}">CTA URL</label>
          <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.cta_href" class="{{ $inputClass }}">
        </div>
      </div>
    </div>

    <div class="{{ $cardClass }}">
      <div class="flex items-center justify-between">
        <span class="text-xs font-semibold text-white">Product Cards</span>
        <button type="button" wire:click="addRepeaterItem('items')" class="px-2 py-1 rounded bg-[#214E34] text-white text-[10px] font-semibold">+ Add Product</button>
      </div>
      @foreach(($s['items'] ?? []) as $i => $item)
        <div class="p-2.5 rounded bg-[#1a1d24] border border-[#282d37] space-y-1.5" wire:key="prod-{{ $sIndex }}-{{ $i }}">
          <div class="flex items-center justify-between text-[10px] text-gray-400">
            <span class="font-semibold text-gray-200 truncate">{{ $item['name'] ?? 'Product #' . ($i+1) }}</span>
            <div class="flex items-center gap-1 shrink-0">
              <button type="button" wire:click="moveRepeaterItem('items', {{ $i }}, 'up')" class="px-1 hover:text-white">↑</button>
              <button type="button" wire:click="moveRepeaterItem('items', {{ $i }}, 'down')" class="px-1 hover:text-white">↓</button>
              <button type="button" wire:click="removeRepeaterItem('items', {{ $i }})" class="px-1 text-red-400 hover:text-red-300">✕</button>
            </div>
          </div>
          <input type="text" placeholder="Product Title" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.name" class="{{ $inputClass }}">
          <div class="grid grid-cols-2 gap-1.5">
            <input type="text" placeholder="District" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.district" class="{{ $inputClass }}">
            <input type="text" placeholder="Maker" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.maker" class="{{ $inputClass }}">
          </div>
          <div class="grid grid-cols-3 gap-1.5">
            <input type="text" placeholder="Price" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.price" class="{{ $inputClass }}">
            <input type="text" placeholder="Old Price" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.old_price" class="{{ $inputClass }}">
            <input type="text" placeholder="Badge" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.badge" class="{{ $inputClass }}">
          </div>
          <div class="grid grid-cols-2 gap-1.5">
            <input type="text" placeholder="Button Text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.button_text" class="{{ $inputClass }}">
            <input type="text" placeholder="Link URL" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.href" class="{{ $inputClass }}">
          </div>
          <div class="pt-1 space-y-1">
            @if(!empty($item['image_url']))
              <div class="flex items-center justify-between text-[10px] text-emerald-400">
                <span>Product photo set</span>
                <button type="button" wire:click="removeItemImage('items', {{ $i }})" class="text-red-400 hover:underline">Remove</button>
              </div>
            @endif
            <input type="file" wire:click="setItemUploadTarget('items', {{ $i }})" wire:model="uploadedItemImage" accept="image/*" class="block w-full text-[10px] text-gray-300 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:bg-[#282d37] file:text-white">
            <input type="text" placeholder="Or Image URL" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.image_url" class="{{ $inputClass }}">
          </div>
        </div>
      @endforeach
    </div>

  {{-- 6. HOME STORY TIMELINE --}}
  @elseif($type === 'home_story_timeline')
    <div class="{{ $cardClass }}">
      <div>
        <label class="{{ $labelClass }}">Eyebrow</label>
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.eyebrow" class="{{ $inputClass }}">
      </div>
      <div>
        <label class="{{ $labelClass }}">Heading</label>
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.heading" class="{{ $inputClass }}">
      </div>
      <div>
        <label class="{{ $labelClass }}">Subtitle</label>
        <textarea rows="2" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.subtitle" class="{{ $inputClass }}"></textarea>
      </div>
      <div class="grid grid-cols-2 gap-2">
        <div>
          <label class="{{ $labelClass }}">CTA Text</label>
          <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.cta_text" class="{{ $inputClass }}">
        </div>
        <div>
          <label class="{{ $labelClass }}">CTA URL</label>
          <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.cta_href" class="{{ $inputClass }}">
        </div>
      </div>
    </div>

    <div class="{{ $cardClass }}">
      <div class="flex items-center justify-between">
        <span class="text-xs font-semibold text-white">Timeline Steps</span>
        <button type="button" wire:click="addRepeaterItem('items')" class="px-2 py-1 rounded bg-[#214E34] text-white text-[10px] font-semibold">+ Add Step</button>
      </div>
      @foreach(($s['items'] ?? []) as $i => $item)
        <div class="p-2.5 rounded bg-[#1a1d24] border border-[#282d37] space-y-1.5" wire:key="step-{{ $sIndex }}-{{ $i }}">
          <div class="flex items-center justify-between text-[10px] text-gray-400">
            <span>Step #{{ $i + 1 }}</span>
            <div class="flex items-center gap-1">
              <button type="button" wire:click="moveRepeaterItem('items', {{ $i }}, 'up')" class="px-1 hover:text-white">↑</button>
              <button type="button" wire:click="moveRepeaterItem('items', {{ $i }}, 'down')" class="px-1 hover:text-white">↓</button>
              <button type="button" wire:click="removeRepeaterItem('items', {{ $i }})" class="px-1 text-red-400 hover:text-red-300">✕</button>
            </div>
          </div>
          <input type="text" placeholder="Year / Era" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.when" class="{{ $inputClass }}">
          <textarea rows="2" placeholder="Description" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.text" class="{{ $inputClass }}"></textarea>
        </div>
      @endforeach
    </div>

  {{-- 7. HOME MAKERS --}}
  @elseif($type === 'home_makers')
    <div class="{{ $cardClass }}">
      <div>
        <label class="{{ $labelClass }}">Eyebrow</label>
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.eyebrow" class="{{ $inputClass }}">
      </div>
      <div>
        <label class="{{ $labelClass }}">Heading</label>
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.heading" class="{{ $inputClass }}">
      </div>
      <div>
        <label class="{{ $labelClass }}">Subtitle</label>
        <textarea rows="2" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.subtitle" class="{{ $inputClass }}"></textarea>
      </div>
    </div>

    <div class="{{ $cardClass }}">
      <div class="flex items-center justify-between">
        <span class="text-xs font-semibold text-white">Maker Profiles</span>
        <button type="button" wire:click="addRepeaterItem('items')" class="px-2 py-1 rounded bg-[#214E34] text-white text-[10px] font-semibold">+ Add Maker</button>
      </div>
      @foreach(($s['items'] ?? []) as $i => $item)
        <div class="p-2.5 rounded bg-[#1a1d24] border border-[#282d37] space-y-1.5" wire:key="maker-{{ $sIndex }}-{{ $i }}">
          <div class="flex items-center justify-between text-[10px] text-gray-400">
            <span class="font-semibold text-gray-200">{{ $item['name'] ?? 'Maker #' . ($i+1) }}</span>
            <div class="flex items-center gap-1">
              <button type="button" wire:click="moveRepeaterItem('items', {{ $i }}, 'up')" class="px-1 hover:text-white">↑</button>
              <button type="button" wire:click="moveRepeaterItem('items', {{ $i }}, 'down')" class="px-1 hover:text-white">↓</button>
              <button type="button" wire:click="removeRepeaterItem('items', {{ $i }})" class="px-1 text-red-400 hover:text-red-300">✕</button>
            </div>
          </div>
          <div class="grid grid-cols-2 gap-1.5">
            <input type="text" placeholder="Name" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.name" class="{{ $inputClass }}">
            <input type="text" placeholder="Craft" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.craft" class="{{ $inputClass }}">
          </div>
          <div class="grid grid-cols-2 gap-1.5">
            <input type="text" placeholder="District" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.place" class="{{ $inputClass }}">
            <input type="text" placeholder="Years" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.years" class="{{ $inputClass }}">
          </div>
          <div class="pt-1 space-y-1">
            @if(!empty($item['image_url']))
              <div class="flex items-center justify-between text-[10px] text-emerald-400">
                <span>Portrait set</span>
                <button type="button" wire:click="removeItemImage('items', {{ $i }})" class="text-red-400 hover:underline">Remove</button>
              </div>
            @endif
            <input type="file" wire:click="setItemUploadTarget('items', {{ $i }})" wire:model="uploadedItemImage" accept="image/*" class="block w-full text-[10px] text-gray-300 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:bg-[#282d37] file:text-white">
            <input type="text" placeholder="Or Portrait URL" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.image_url" class="{{ $inputClass }}">
          </div>
        </div>
      @endforeach
    </div>

  {{-- 8. HOME WHY --}}
  @elseif($type === 'home_why')
    <div class="{{ $cardClass }}">
      <label class="{{ $labelClass }}">Section Heading</label>
      <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.heading" class="{{ $inputClass }}">
    </div>
    <div class="{{ $cardClass }}">
      <div class="flex items-center justify-between">
        <span class="text-xs font-semibold text-white">Trust Pillars</span>
        <button type="button" wire:click="addRepeaterItem('items')" class="px-2 py-1 rounded bg-[#214E34] text-white text-[10px] font-semibold">+ Add Pillar</button>
      </div>
      @foreach(($s['items'] ?? []) as $i => $item)
        <div class="p-2.5 rounded bg-[#1a1d24] border border-[#282d37] space-y-1.5" wire:key="why-{{ $sIndex }}-{{ $i }}">
          <div class="flex items-center justify-between text-[10px] text-gray-400">
            <span class="font-semibold text-gray-200">{{ $item['title'] ?? 'Pillar #' . ($i+1) }}</span>
            <button type="button" wire:click="removeRepeaterItem('items', {{ $i }})" class="px-1 text-red-400 hover:text-red-300">✕</button>
          </div>
          <input type="text" placeholder="Title" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.title" class="{{ $inputClass }}">
          <textarea rows="2" placeholder="Description" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.text" class="{{ $inputClass }}"></textarea>
        </div>
      @endforeach
    </div>

  {{-- 9. HOME GIFTS --}}
  @elseif($type === 'home_gifts')
    <div class="{{ $cardClass }}">
      <div>
        <label class="{{ $labelClass }}">Eyebrow</label>
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.eyebrow" class="{{ $inputClass }}">
      </div>
      <div>
        <label class="{{ $labelClass }}">Heading</label>
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.heading" class="{{ $inputClass }}">
      </div>
      <div>
        <label class="{{ $labelClass }}">Subtitle</label>
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.subtitle" class="{{ $inputClass }}">
      </div>
    </div>
    <div class="{{ $cardClass }}">
      <div class="flex items-center justify-between">
        <span class="text-xs font-semibold text-white">Gift Cards</span>
        <button type="button" wire:click="addRepeaterItem('items')" class="px-2 py-1 rounded bg-[#214E34] text-white text-[10px] font-semibold">+ Add Gift</button>
      </div>
      @foreach(($s['items'] ?? []) as $i => $item)
        <div class="p-2.5 rounded bg-[#1a1d24] border border-[#282d37] space-y-1.5" wire:key="gift-{{ $sIndex }}-{{ $i }}">
          <div class="flex items-center justify-between text-[10px] text-gray-400">
            <span class="font-semibold text-gray-200">{{ $item['name'] ?? 'Gift #' . ($i+1) }}</span>
            <button type="button" wire:click="removeRepeaterItem('items', {{ $i }})" class="px-1 text-red-400 hover:text-red-300">✕</button>
          </div>
          <input type="text" placeholder="Title" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.name" class="{{ $inputClass }}">
          <textarea rows="2" placeholder="Description" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.text" class="{{ $inputClass }}"></textarea>
          <div class="grid grid-cols-2 gap-1.5">
            <input type="text" placeholder="CTA Text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.cta_text" class="{{ $inputClass }}">
            <input type="text" placeholder="Link URL" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.href" class="{{ $inputClass }}">
          </div>
          <input type="file" wire:click="setItemUploadTarget('items', {{ $i }})" wire:model="uploadedItemImage" accept="image/*" class="block w-full text-[10px] text-gray-300 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:bg-[#282d37] file:text-white">
          <input type="text" placeholder="Or Image URL" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.image_url" class="{{ $inputClass }}">
        </div>
      @endforeach
    </div>

  {{-- 10. HOME REVIEWS --}}
  @elseif($type === 'home_reviews')
    <div class="{{ $cardClass }}">
      <div>
        <label class="{{ $labelClass }}">Eyebrow</label>
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.eyebrow" class="{{ $inputClass }}">
      </div>
      <div>
        <label class="{{ $labelClass }}">Heading</label>
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.heading" class="{{ $inputClass }}">
      </div>
      <div>
        <label class="{{ $labelClass }}">Bottom Helper Note</label>
        <textarea rows="2" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.note" class="{{ $inputClass }}"></textarea>
      </div>
    </div>
    <div class="{{ $cardClass }}">
      <div class="flex items-center justify-between">
        <span class="text-xs font-semibold text-white">Reviews</span>
        <button type="button" wire:click="addRepeaterItem('items')" class="px-2 py-1 rounded bg-[#214E34] text-white text-[10px] font-semibold">+ Add Review</button>
      </div>
      @foreach(($s['items'] ?? []) as $i => $item)
        <div class="p-2.5 rounded bg-[#1a1d24] border border-[#282d37] space-y-1.5" wire:key="rev-{{ $sIndex }}-{{ $i }}">
          <div class="flex items-center justify-between text-[10px] text-gray-400">
            <span>Review #{{ $i + 1 }}</span>
            <button type="button" wire:click="removeRepeaterItem('items', {{ $i }})" class="px-1 text-red-400 hover:text-red-300">✕</button>
          </div>
          <textarea rows="2" placeholder="Quote" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.text" class="{{ $inputClass }}"></textarea>
          <input type="text" placeholder="Author, City" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.who" class="{{ $inputClass }}">
        </div>
      @endforeach
    </div>

  {{-- 11. HOME STORIES --}}
  @elseif($type === 'home_stories')
    <div class="{{ $cardClass }}">
      <div>
        <label class="{{ $labelClass }}">Eyebrow</label>
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.eyebrow" class="{{ $inputClass }}">
      </div>
      <div>
        <label class="{{ $labelClass }}">Heading</label>
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.heading" class="{{ $inputClass }}">
      </div>
      <div class="grid grid-cols-2 gap-2">
        <div>
          <label class="{{ $labelClass }}">CTA Text</label>
          <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.cta_text" class="{{ $inputClass }}">
        </div>
        <div>
          <label class="{{ $labelClass }}">CTA URL</label>
          <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.cta_href" class="{{ $inputClass }}">
        </div>
      </div>
    </div>
    <div class="{{ $cardClass }}">
      <div class="flex items-center justify-between">
        <span class="text-xs font-semibold text-white">Stories</span>
        <button type="button" wire:click="addRepeaterItem('items')" class="px-2 py-1 rounded bg-[#214E34] text-white text-[10px] font-semibold">+ Add Story</button>
      </div>
      @foreach(($s['items'] ?? []) as $i => $item)
        <div class="p-2.5 rounded bg-[#1a1d24] border border-[#282d37] space-y-1.5" wire:key="story-{{ $sIndex }}-{{ $i }}">
          <div class="flex items-center justify-between text-[10px] text-gray-400">
            <span>Story #{{ $i + 1 }}</span>
            <button type="button" wire:click="removeRepeaterItem('items', {{ $i }})" class="px-1 text-red-400 hover:text-red-300">✕</button>
          </div>
          <div class="grid grid-cols-2 gap-1.5">
            <input type="text" placeholder="Tag" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.tag" class="{{ $inputClass }}">
            <input type="text" placeholder="Read Time" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.read" class="{{ $inputClass }}">
          </div>
          <input type="text" placeholder="Story Title" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.title" class="{{ $inputClass }}">
          <input type="text" placeholder="Story URL" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.href" class="{{ $inputClass }}">
          <input type="file" wire:click="setItemUploadTarget('items', {{ $i }})" wire:model="uploadedItemImage" accept="image/*" class="block w-full text-[10px] text-gray-300 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:bg-[#282d37] file:text-white">
          <input type="text" placeholder="Or Image URL" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.image_url" class="{{ $inputClass }}">
        </div>
      @endforeach
    </div>

  {{-- 12. HOME NEWSLETTER --}}
  @elseif($type === 'home_newsletter')
    <div class="{{ $cardClass }}">
      <div>
        <label class="{{ $labelClass }}">Heading</label>
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.heading" class="{{ $inputClass }}">
      </div>
      <div>
        <label class="{{ $labelClass }}">Subtitle</label>
        <textarea rows="2" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.subtitle" class="{{ $inputClass }}"></textarea>
      </div>
      <div class="grid grid-cols-2 gap-2">
        <div>
          <label class="{{ $labelClass }}">Email Label</label>
          <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.email_label" class="{{ $inputClass }}">
        </div>
        <div>
          <label class="{{ $labelClass }}">Button Text</label>
          <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.button_text" class="{{ $inputClass }}">
        </div>
      </div>
      <div>
        <label class="{{ $labelClass }}">Placeholder</label>
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.email_placeholder" class="{{ $inputClass }}">
      </div>
    </div>

  {{-- 13. HOME FOOTER --}}
  @elseif($type === 'home_footer')
    <div class="{{ $cardClass }}">
      <div class="grid grid-cols-2 gap-2">
        <div>
          <label class="{{ $labelClass }}">Brand Name</label>
          <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.brand_name" class="{{ $inputClass }}">
        </div>
        <div>
          <label class="{{ $labelClass }}">Bengali Pill</label>
          <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.brand_bn" class="{{ $inputClass }}">
        </div>
      </div>
      <div>
        <label class="{{ $labelClass }}">Tagline</label>
        <textarea rows="2" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.tagline" class="{{ $inputClass }}"></textarea>
      </div>
      <div class="grid grid-cols-2 gap-2">
        <div>
          <label class="{{ $labelClass }}">Copyright</label>
          <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.copyright_text" class="{{ $inputClass }}">
        </div>
        <div>
          <label class="{{ $labelClass }}">Payment Note</label>
          <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.payment_text" class="{{ $inputClass }}">
        </div>
      </div>
    </div>

    <div class="{{ $cardClass }}">
      <div class="flex items-center justify-between">
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.col1_title" class="w-32 bg-[#12141a] border border-[#282d37] rounded text-xs text-white px-2 py-1">
        <button type="button" wire:click="addRepeaterItem('shop_links')" class="px-2 py-1 rounded bg-[#214E34] text-white text-[10px] font-semibold">+ Add</button>
      </div>
      @foreach(($s['shop_links'] ?? []) as $i => $item)
        <div class="grid grid-cols-5 gap-1.5 items-center" wire:key="fshop-{{ $sIndex }}-{{ $i }}">
          <input type="text" placeholder="Label" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.shop_links.{{ $i }}.label" class="col-span-2 {{ $inputClass }}">
          <input type="text" placeholder="URL" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.shop_links.{{ $i }}.href" class="col-span-2 {{ $inputClass }}">
          <button type="button" wire:click="removeRepeaterItem('shop_links', {{ $i }})" class="py-1.5 rounded bg-[#222731] text-red-400 text-xs">✕</button>
        </div>
      @endforeach
    </div>

    <div class="{{ $cardClass }}">
      <div class="flex items-center justify-between">
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.col2_title" class="w-32 bg-[#12141a] border border-[#282d37] rounded text-xs text-white px-2 py-1">
        <button type="button" wire:click="addRepeaterItem('help_links')" class="px-2 py-1 rounded bg-[#214E34] text-white text-[10px] font-semibold">+ Add</button>
      </div>
      @foreach(($s['help_links'] ?? []) as $i => $item)
        <div class="grid grid-cols-5 gap-1.5 items-center" wire:key="fhelp-{{ $sIndex }}-{{ $i }}">
          <input type="text" placeholder="Label" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.help_links.{{ $i }}.label" class="col-span-2 {{ $inputClass }}">
          <input type="text" placeholder="URL" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.help_links.{{ $i }}.href" class="col-span-2 {{ $inputClass }}">
          <button type="button" wire:click="removeRepeaterItem('help_links', {{ $i }})" class="py-1.5 rounded bg-[#222731] text-red-400 text-xs">✕</button>
        </div>
      @endforeach
    </div>

    <div class="{{ $cardClass }}">
      <div class="flex items-center justify-between">
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.col3_title" class="w-32 bg-[#12141a] border border-[#282d37] rounded text-xs text-white px-2 py-1">
        <button type="button" wire:click="addRepeaterItem('contact_lines')" class="px-2 py-1 rounded bg-[#214E34] text-white text-[10px] font-semibold">+ Add</button>
      </div>
      @foreach(($s['contact_lines'] ?? []) as $i => $item)
        <div class="flex items-center gap-1.5" wire:key="fcontact-{{ $sIndex }}-{{ $i }}">
          <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.contact_lines.{{ $i }}.text" class="{{ $inputClass }}">
          <button type="button" wire:click="removeRepeaterItem('contact_lines', {{ $i }})" class="px-2 py-1.5 rounded bg-[#222731] text-red-400 text-xs">✕</button>
        </div>
      @endforeach
    </div>

  {{-- 14. HERITAGE PAGE HEADER --}}
  @elseif($type === 'home_page_header')
    <div class="{{ $cardClass }}">
      <div>
        <label class="{{ $labelClass }}">Breadcrumb Label</label>
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.breadcrumb" class="{{ $inputClass }}">
      </div>
      <div>
        <label class="{{ $labelClass }}">Eyebrow</label>
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.eyebrow" class="{{ $inputClass }}">
      </div>
      <div>
        <label class="{{ $labelClass }}">Page Title (H1)</label>
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.title" class="{{ $inputClass }}">
      </div>
      <div>
        <label class="{{ $labelClass }}">Subtitle</label>
        <textarea rows="3" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.subtitle" class="{{ $inputClass }}"></textarea>
      </div>
    </div>

  {{-- 15. HERITAGE STORY & MEDIA BLOCK --}}
  @elseif($type === 'home_rich_text')
    <div class="{{ $cardClass }}">
      <div>
        <label class="{{ $labelClass }}">Eyebrow</label>
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.eyebrow" class="{{ $inputClass }}">
      </div>
      <div>
        <label class="{{ $labelClass }}">Heading</label>
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.heading" class="{{ $inputClass }}">
      </div>
      <div>
        <label class="{{ $labelClass }}">Narrative Body</label>
        <textarea rows="5" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.body" class="{{ $inputClass }}"></textarea>
      </div>
      <div class="grid grid-cols-2 gap-2">
        <div>
          <label class="{{ $labelClass }}">Button Text (optional)</label>
          <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.cta_text" class="{{ $inputClass }}">
        </div>
        <div>
          <label class="{{ $labelClass }}">Button URL</label>
          <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.cta_href" class="{{ $inputClass }}">
        </div>
      </div>
    </div>
    <div class="{{ $cardClass }}">
      <span class="text-xs font-semibold text-white block">Side Image (Optional)</span>
      @if(!empty($s['image_url']))
        <div class="relative rounded-lg overflow-hidden border border-[#282d37] h-28 bg-[#12141a]">
          <img src="{{ $s['image_url'] }}" class="w-full h-full object-cover">
          <button type="button" wire:click="removeImage" class="absolute top-1.5 right-1.5 px-2 py-0.5 rounded bg-red-600 text-white text-[10px] font-semibold">Remove</button>
        </div>
      @endif
      <input type="file" wire:model="uploadedImage" accept="image/*" class="block w-full text-[11px] text-gray-300 file:mr-2 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-[11px] file:font-semibold file:bg-[#214E34] file:text-white">
      <input type="text" placeholder="Or Image URL" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.image_url" class="{{ $inputClass }}">
    </div>

  {{-- 16. PRODUCT HERO & MULTI-IMAGE GALLERY --}}
  @elseif($type === 'home_product_showcase')
    <div class="{{ $cardClass }}">
      <div>
        <label class="{{ $labelClass }}">District Eyebrow</label>
        <input type="text" placeholder="Auto from product district" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.district_label" class="{{ $inputClass }}">
      </div>
      <div>
        <label class="{{ $labelClass }}">Product Title</label>
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.title" class="{{ $inputClass }}">
      </div>
      <div class="grid grid-cols-2 gap-2">
        <div>
          <label class="{{ $labelClass }}">Maker Name</label>
          <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.artisan_label" class="{{ $inputClass }}">
        </div>
        <div>
          <label class="{{ $labelClass }}">Years / Workshop</label>
          <input type="text" placeholder="32 years of handcraft" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.artisan_years" class="{{ $inputClass }}">
        </div>
      </div>
      <div>
        <label class="{{ $labelClass }}">Short Subtitle</label>
        <textarea rows="2" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.subtitle" class="{{ $inputClass }}"></textarea>
      </div>
      <div class="grid grid-cols-2 gap-2">
        <div>
          <label class="{{ $labelClass }}">Cart Button Text</label>
          <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.add_to_cart_text" class="{{ $inputClass }}">
        </div>
        <div>
          <label class="{{ $labelClass }}">Wishlist Text</label>
          <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.wishlist_text" class="{{ $inputClass }}">
        </div>
      </div>
      <div class="grid grid-cols-2 gap-2">
        <div>
          <label class="{{ $labelClass }}">WhatsApp Button</label>
          <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.whatsapp_text" class="{{ $inputClass }}">
        </div>
        <div>
          <label class="{{ $labelClass }}">WhatsApp URL</label>
          <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.whatsapp_href" class="{{ $inputClass }}">
        </div>
      </div>
      <div>
        <label class="{{ $labelClass }}">Maker Payout Title</label>
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.payout_title" class="{{ $inputClass }}">
      </div>
      <div>
        <label class="{{ $labelClass }}">Maker Payout Note</label>
        <textarea rows="2" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.payout_note" class="{{ $inputClass }}"></textarea>
      </div>
      <p class="text-[10px] text-emerald-400">Product photos & price sync automatically from the Product Editor (Media & Gallery tab).</p>
    </div>

    <div class="{{ $cardClass }}">
      <div class="flex items-center justify-between">
        <span class="text-xs font-semibold text-white">Delivery Selector Options</span>
        <button type="button" wire:click="addRepeaterItem('delivery_options')" class="px-2 py-1 rounded bg-[#214E34] text-white text-[10px] font-semibold">+ Add</button>
      </div>
      <div>
        <label class="{{ $labelClass }}">Selector Heading</label>
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.delivery_heading" class="{{ $inputClass }}">
      </div>
      @foreach(($s['delivery_options'] ?? []) as $i => $opt)
        <div class="p-2 rounded bg-[#12141a] border border-[#282d37] space-y-1.5" wire:key="pdeliv-{{ $sIndex }}-{{ $i }}">
          <div class="flex items-center justify-between">
            <span class="text-[10px] text-gray-400">Option #{{ $i + 1 }}</span>
            <button type="button" wire:click="removeRepeaterItem('delivery_options', {{ $i }})" class="text-red-400 text-xs">✕</button>
          </div>
          <input type="text" placeholder="Label" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.delivery_options.{{ $i }}.label" class="{{ $inputClass }}">
          <input type="text" placeholder="Delivery note" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.delivery_options.{{ $i }}.note" class="{{ $inputClass }}">
        </div>
      @endforeach
    </div>

    <div class="{{ $cardClass }}">
      <div class="flex items-center justify-between">
        <span class="text-xs font-semibold text-white">Trust Badges</span>
        <button type="button" wire:click="addRepeaterItem('trust_items')" class="px-2 py-1 rounded bg-[#214E34] text-white text-[10px] font-semibold">+ Add</button>
      </div>
      @foreach(($s['trust_items'] ?? []) as $i => $item)
        <div class="flex items-center gap-1.5" wire:key="ptrust-{{ $sIndex }}-{{ $i }}">
          <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.trust_items.{{ $i }}.text" class="{{ $inputClass }}">
          <button type="button" wire:click="removeRepeaterItem('trust_items', {{ $i }})" class="px-2 py-1.5 rounded bg-[#222731] text-red-400 text-xs">✕</button>
        </div>
      @endforeach
    </div>

  {{-- 17. HOW IT IS MADE (PROCESS STEPS) --}}
  @elseif($type === 'home_process_steps')
    <div class="{{ $cardClass }}">
      <div>
        <label class="{{ $labelClass }}">Eyebrow</label>
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.eyebrow" class="{{ $inputClass }}">
      </div>
      <div>
        <label class="{{ $labelClass }}">Heading</label>
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.heading" class="{{ $inputClass }}">
      </div>
    </div>
    <div class="{{ $cardClass }}">
      <div class="flex items-center justify-between">
        <span class="text-xs font-semibold text-white">Craft Steps ({{ count($s['items'] ?? []) }})</span>
        <button type="button" wire:click="addRepeaterItem('items')" class="px-2 py-1 rounded bg-[#214E34] text-white text-[10px] font-semibold">+ Add</button>
      </div>
      @foreach(($s['items'] ?? []) as $i => $step)
        <div class="p-2 rounded bg-[#12141a] border border-[#282d37] space-y-1.5" wire:key="pstep-{{ $sIndex }}-{{ $i }}">
          <div class="flex items-center justify-between">
            <span class="text-[10px] text-gray-400">Step #{{ $i + 1 }}</span>
            <button type="button" wire:click="removeRepeaterItem('items', {{ $i }})" class="text-red-400 text-xs">✕</button>
          </div>
          <div class="grid grid-cols-4 gap-1.5">
            <input type="text" placeholder="#" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.step" class="{{ $inputClass }}">
            <input type="text" placeholder="Step Title" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.title" class="{{ $inputClass }} col-span-3">
          </div>
          <textarea rows="2" placeholder="Description" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.items.{{ $i }}.text" class="{{ $inputClass }}"></textarea>
        </div>
      @endforeach
    </div>

  {{-- 18. MEET THE MAKER SPOTLIGHT --}}
  @elseif($type === 'home_maker_feature')
    <div class="{{ $cardClass }}">
      <div>
        <label class="{{ $labelClass }}">Eyebrow</label>
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.eyebrow" class="{{ $inputClass }}">
      </div>
      <div>
        <label class="{{ $labelClass }}">Maker Name</label>
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.name" class="{{ $inputClass }}">
      </div>
      <div>
        <label class="{{ $labelClass }}">Craft · District · Years</label>
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.meta" class="{{ $inputClass }}">
      </div>
      <div>
        <label class="{{ $labelClass }}">Maker Quote</label>
        <textarea rows="3" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.quote" class="{{ $inputClass }}"></textarea>
      </div>
      <div>
        <label class="{{ $labelClass }}">Portrait Image URL (optional)</label>
        <input type="text" placeholder="Auto from artisan profile" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.image_url" class="{{ $inputClass }}">
      </div>
      <div class="grid grid-cols-2 gap-2">
        <div>
          <label class="{{ $labelClass }}">Button Text</label>
          <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.cta_text" class="{{ $inputClass }}">
        </div>
        <div>
          <label class="{{ $labelClass }}">Button URL</label>
          <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.cta_href" class="{{ $inputClass }}">
        </div>
      </div>
    </div>

  {{-- 19. PRODUCT DETAILS & DISTRICT SPOTLIGHT --}}
  @elseif($type === 'home_product_details')
    <div class="{{ $cardClass }}">
      <div>
        <label class="{{ $labelClass }}">Specs Eyebrow</label>
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.eyebrow" class="{{ $inputClass }}">
      </div>
      <div>
        <label class="{{ $labelClass }}">Specs Heading</label>
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.heading" class="{{ $inputClass }}">
      </div>
      <div class="flex items-center justify-between pt-1">
        <span class="text-xs font-semibold text-white">Specification Rows</span>
        <button type="button" wire:click="addRepeaterItem('specs')" class="px-2 py-1 rounded bg-[#214E34] text-white text-[10px] font-semibold">+ Add</button>
      </div>
      @foreach(($s['specs'] ?? []) as $i => $spec)
        <div class="p-2 rounded bg-[#12141a] border border-[#282d37] space-y-1.5" wire:key="pspec-{{ $sIndex }}-{{ $i }}">
          <div class="flex items-center justify-between">
            <span class="text-[10px] text-gray-400">Row #{{ $i + 1 }}</span>
            <button type="button" wire:click="removeRepeaterItem('specs', {{ $i }})" class="text-red-400 text-xs">✕</button>
          </div>
          <input type="text" placeholder="Label (e.g. Materials)" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.specs.{{ $i }}.label" class="{{ $inputClass }}">
          <input type="text" placeholder="Value" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.specs.{{ $i }}.value" class="{{ $inputClass }}">
        </div>
      @endforeach
    </div>

    <div class="{{ $cardClass }}">
      <span class="text-xs font-semibold text-white block">District Spotlight Card</span>
      <div>
        <label class="{{ $labelClass }}">Card Eyebrow</label>
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.district_eyebrow" class="{{ $inputClass }}">
      </div>
      <div class="grid grid-cols-2 gap-2">
        <div>
          <label class="{{ $labelClass }}">District (EN)</label>
          <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.district_title" class="{{ $inputClass }}">
        </div>
        <div>
          <label class="{{ $labelClass }}">District (BN)</label>
          <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.district_bn" class="{{ $inputClass }}">
        </div>
      </div>
      <div>
        <label class="{{ $labelClass }}">District Story</label>
        <textarea rows="3" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.district_body" class="{{ $inputClass }}"></textarea>
      </div>
      <div class="grid grid-cols-2 gap-2">
        <div>
          <label class="{{ $labelClass }}">Button Text</label>
          <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.district_cta_text" class="{{ $inputClass }}">
        </div>
        <div>
          <label class="{{ $labelClass }}">Button URL</label>
          <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.district_cta_href" class="{{ $inputClass }}">
        </div>
      </div>
    </div>
  @endif

@elseif($inspectorTab === 'style')
  <div class="{{ $cardClass }}">
    <span class="text-xs font-semibold text-white block">Section Colors & Patterns</span>
    <div>
      <label class="{{ $labelClass }}">Background Color</label>
      <div class="flex items-center gap-2">
        <input type="color" wire:model.live="sections.{{ $sIndex }}.settings.bg_color" class="w-9 h-9 rounded bg-transparent border border-[#282d37] cursor-pointer">
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.bg_color" class="{{ $inputClass }}">
      </div>
    </div>
    @if(array_key_exists('text_color', $s))
      <div>
        <label class="{{ $labelClass }}">Heading / Text Color</label>
        <div class="flex items-center gap-2">
          <input type="color" wire:model.live="sections.{{ $sIndex }}.settings.text_color" class="w-9 h-9 rounded bg-transparent border border-[#282d37] cursor-pointer">
          <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.text_color" class="{{ $inputClass }}">
        </div>
      </div>
    @endif
    @if(array_key_exists('show_pattern', $s))
      <label class="inline-flex items-center gap-2 text-xs text-gray-300 cursor-pointer pt-1">
        <input type="checkbox" wire:model.live="sections.{{ $sIndex }}.settings.show_pattern" class="rounded bg-[#12141a] border-[#282d37] text-[#214E34]">
        <span>Show Jali Pattern Overlay</span>
      </label>
    @endif
    @if(array_key_exists('show_band', $s))
      <label class="inline-flex items-center gap-2 text-xs text-gray-300 cursor-pointer pt-1">
        <input type="checkbox" wire:model.live="sections.{{ $sIndex }}.settings.show_band" class="rounded bg-[#12141a] border-[#282d37] text-[#214E34]">
        <span>Show Jamdani Divider Band</span>
      </label>
    @endif
    @if(array_key_exists('show_bands', $s))
      <label class="inline-flex items-center gap-2 text-xs text-gray-300 cursor-pointer pt-1">
        <input type="checkbox" wire:model.live="sections.{{ $sIndex }}.settings.show_bands" class="rounded bg-[#12141a] border-[#282d37] text-[#214E34]">
        <span>Show Top/Bottom Jamdani Bands</span>
      </label>
    @endif
  </div>
@endif
