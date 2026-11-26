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

  {{-- 2. HOME HERO SLIDER --}}
  @elseif($type === 'home_hero')
    @php
      $inspectorSlides = !empty($s['slides']) && is_array($s['slides'])
          ? $s['slides']
          : \App\Livewire\Builder\VisualPageBuilder::getDefaultHeroSlides($s);
    @endphp
    <div class="{{ $cardClass }}">
      <div>
        <label class="{{ $labelClass }}">Auto-Slide Timer (seconds)</label>
        <input type="number" step="0.5" min="0" max="60" placeholder="5.5" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.slide_interval" class="{{ $inputClass }}">
        <p class="text-[10px] text-gray-400 mt-1">Default: 5.5 seconds. Set to 0 to disable auto-sliding.</p>
      </div>
    </div>

    <div class="{{ $cardClass }}">
      <div class="flex items-center justify-between">
        <span class="text-xs font-semibold text-white">Hero Slider Slides ({{ count($inspectorSlides) }})</span>
        <button type="button" wire:click="addRepeaterItem('slides')" class="px-2 py-1 rounded bg-[#214E34] text-white text-[10px] font-semibold hover:bg-[#2c6846]">+ Add Slide</button>
      </div>
      @foreach($inspectorSlides as $i => $sl)
        <div class="p-2.5 rounded bg-[#1a1d24] border border-[#282d37] space-y-2" wire:key="hslide-{{ $sIndex }}-{{ $i }}">
          <div class="flex items-center justify-between text-[10px] text-gray-400">
            <span class="font-semibold text-gray-200">Slide #{{ $i + 1 }}</span>
            <div class="flex items-center gap-1">
              <button type="button" wire:click="moveRepeaterItem('slides', {{ $i }}, 'up')" class="px-1 hover:text-white">↑</button>
              <button type="button" wire:click="moveRepeaterItem('slides', {{ $i }}, 'down')" class="px-1 hover:text-white">↓</button>
              <button type="button" wire:click="removeRepeaterItem('slides', {{ $i }})" class="px-1 text-red-400 hover:text-red-300">✕</button>
            </div>
          </div>

          @if(!empty($sl['image_url']))
            <div class="relative rounded-lg overflow-hidden border border-[#282d37] h-20 bg-[#12141a]">
              <img src="{{ $sl['image_url'] }}" class="w-full h-full object-cover">
              <button type="button" wire:click="removeItemImage('slides', {{ $i }})" class="absolute top-1 right-1 px-1.5 py-0.5 rounded bg-red-600 text-white text-[9px] font-semibold">Remove</button>
            </div>
          @endif
          <input type="file" wire:click="setItemUploadTarget('slides', {{ $i }})" wire:model="uploadedItemImage" accept="image/*" class="block w-full text-[10px] text-gray-300 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:bg-[#214E34] file:text-white">
          <input type="text" placeholder="Slide Background Image URL" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.slides.{{ $i }}.image_url" class="{{ $inputClass }}">
          <div>
            <label class="{{ $labelClass }}">Photo Alignment</label>
            <select wire:model.live="sections.{{ $sIndex }}.settings.slides.{{ $i }}.image_position" class="{{ $inputClass }}">
              <option value="center top">Top (prevent upper crop)</option>
              <option value="center center">Center</option>
              <option value="center bottom">Bottom</option>
            </select>
          </div>

          <div>
            <label class="{{ $labelClass }}">Headline</label>
            <input type="text" placeholder="Headline" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.slides.{{ $i }}.headline" class="{{ $inputClass }}">
          </div>
          <div>
            <label class="{{ $labelClass }}">Subheadline</label>
            <textarea rows="2" placeholder="Subheadline" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.slides.{{ $i }}.subheadline" class="{{ $inputClass }}"></textarea>
          </div>
          <div class="grid grid-cols-2 gap-1.5">
            <input type="text" placeholder="Primary Button" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.slides.{{ $i }}.cta_text" class="{{ $inputClass }}">
            <input type="text" placeholder="Primary URL" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.slides.{{ $i }}.cta_href" class="{{ $inputClass }}">
          </div>
          <div class="grid grid-cols-2 gap-1.5">
            <input type="text" placeholder="Secondary Button" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.slides.{{ $i }}.secondary_text" class="{{ $inputClass }}">
            <input type="text" placeholder="Secondary URL" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.slides.{{ $i }}.secondary_href" class="{{ $inputClass }}">
          </div>
          <div>
            <label class="{{ $labelClass }}">Fallback Gradient / Color</label>
            <input type="text" placeholder="linear-gradient(...)" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.slides.{{ $i }}.bg" class="{{ $inputClass }}">
          </div>
        </div>
      @endforeach
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

    <div class="{{ $cardClass }}" x-data="{ pickProdId: '' }">
      <div class="flex items-center justify-between">
        <span class="text-xs font-semibold text-white">Add Existing Real Product</span>
      </div>
      <div class="flex items-center gap-1.5">
        <select x-model="pickProdId" class="{{ $inputClass }}">
          <option value="">-- Choose a product --</option>
          @foreach(($catalogProducts ?? collect()) as $cp)
            <option value="{{ $cp->id }}">{{ $cp->title }} (৳ {{ number_format((float) $cp->price, 0) }})</option>
          @endforeach
        </select>
        <button type="button"
                @click="if (pickProdId) { $wire.addExistingProductToSection(parseInt(pickProdId)); pickProdId = ''; }"
                class="px-3 py-1.5 rounded bg-[#214E34] hover:bg-[#193b27] text-white text-[10px] font-semibold shrink-0">+ Add</button>
      </div>
    </div>

    <div class="{{ $cardClass }}">
      <div class="flex items-center justify-between">
        <span class="text-xs font-semibold text-white">Product Cards</span>
        <button type="button" wire:click="addRepeaterItem('items')" class="px-2 py-1 rounded bg-[#282d37] text-gray-200 text-[10px] font-semibold">+ Custom Card</button>
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
          <div>
            <select @change="if ($event.target.value) $wire.selectExistingProductForItem({{ $i }}, parseInt($event.target.value))" class="{{ $inputClass }}">
              <option value="">Link / swap with existing product...</option>
              @foreach(($catalogProducts ?? collect()) as $cp)
                <option value="{{ $cp->id }}" @selected(($item['product_id'] ?? null) == $cp->id)>{{ $cp->title }} (৳ {{ number_format((float) $cp->price, 0) }})</option>
              @endforeach
            </select>
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

    <div class="{{ $cardClass }}" x-data="{ pickArtisanId: '' }">
      <div class="flex items-center justify-between">
        <span class="text-xs font-semibold text-white">Add Existing Artisan</span>
      </div>
      <div class="flex items-center gap-1.5">
        <select x-model="pickArtisanId" class="{{ $inputClass }}">
          <option value="">-- Choose an artisan --</option>
          @foreach(($catalogArtisans ?? collect()) as $ca)
            <option value="{{ $ca->id }}">{{ $ca->full_name }} ({{ $ca->district?->name_en ?? 'Bangladesh' }})</option>
          @endforeach
        </select>
        <button type="button"
                @click="if (pickArtisanId) { $wire.addExistingArtisanToSection(parseInt(pickArtisanId)); pickArtisanId = ''; }"
                class="px-3 py-1.5 rounded bg-[#214E34] hover:bg-[#193b27] text-white text-[10px] font-semibold shrink-0">+ Add</button>
      </div>
    </div>

    <div class="{{ $cardClass }}">
      <div class="flex items-center justify-between">
        <span class="text-xs font-semibold text-white">Maker Profiles</span>
        <button type="button" wire:click="addRepeaterItem('items')" class="px-2 py-1 rounded bg-[#282d37] text-gray-200 text-[10px] font-semibold">+ Custom Maker</button>
      </div>
      @foreach(($s['items'] ?? []) as $i => $item)
        <div class="p-2.5 rounded bg-[#1a1d24] border border-[#282d37] space-y-1.5" wire:key="maker-{{ $sIndex }}-{{ $i }}">
          <div class="mb-1">
            <select @change="if ($event.target.value) $wire.selectExistingArtisanForItem({{ $i }}, parseInt($event.target.value))" class="{{ $inputClass }}">
              <option value="">Link / swap with existing artisan...</option>
              @foreach(($catalogArtisans ?? collect()) as $ca)
                <option value="{{ $ca->id }}" @selected(($item['artisan_id'] ?? null) == $ca->id)>{{ $ca->full_name }} ({{ $ca->district?->name_en ?? 'Bangladesh' }})</option>
              @endforeach
            </select>
          </div>
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
    <div class="{{ $cardClass }}" x-data="{ pickStoryId: '' }">
      <div class="flex items-center justify-between">
        <span class="text-xs font-semibold text-white">Add Existing Story</span>
      </div>
      <div class="flex items-center gap-1.5">
        <select x-model="pickStoryId" class="{{ $inputClass }}">
          <option value="">-- Choose a story --</option>
          @foreach(($catalogStories ?? collect()) as $cs)
            <option value="{{ $cs->id }}">{{ $cs->title }}</option>
          @endforeach
        </select>
        <button type="button"
                @click="if (pickStoryId) { $wire.addExistingStoryToSection(parseInt(pickStoryId)); pickStoryId = ''; }"
                class="px-3 py-1.5 rounded bg-[#214E34] hover:bg-[#193b27] text-white text-[10px] font-semibold shrink-0">+ Add</button>
      </div>
    </div>

    <div class="{{ $cardClass }}">
      <div class="flex items-center justify-between">
        <span class="text-xs font-semibold text-white">Stories</span>
        <button type="button" wire:click="addRepeaterItem('items')" class="px-2 py-1 rounded bg-[#282d37] text-gray-200 text-[10px] font-semibold">+ Custom Story</button>
      </div>
      @foreach(($s['items'] ?? []) as $i => $item)
        <div class="p-2.5 rounded bg-[#1a1d24] border border-[#282d37] space-y-1.5" wire:key="story-{{ $sIndex }}-{{ $i }}">
          <div class="flex items-center justify-between text-[10px] text-gray-400">
            <span>Story #{{ $i + 1 }}</span>
            <button type="button" wire:click="removeRepeaterItem('items', {{ $i }})" class="px-1 text-red-400 hover:text-red-300">✕</button>
          </div>
          <div>
            <select @change="if ($event.target.value) $wire.selectExistingStoryForItem({{ $i }}, parseInt($event.target.value))" class="{{ $inputClass }}">
              <option value="">Link / swap with existing story...</option>
              @foreach(($catalogStories ?? collect()) as $cs)
                <option value="{{ $cs->id }}" @selected(($item['story_id'] ?? null) == $cs->id)>{{ $cs->title }}</option>
              @endforeach
            </select>
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
      <div>
        <label class="{{ $labelClass }}">Maker Name</label>
        <input type="text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.artisan_label" class="{{ $inputClass }}">
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
      <p class="text-[10px] text-emerald-400">Product photos & price sync automatically from the Product Editor (Media & Gallery tab).</p>
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

  {{-- 17. ABOUT US FULL PAGE --}}
  @elseif($type === 'home_about_page')
    <div class="{{ $cardClass }}">
      <span class="text-xs font-semibold text-white block">Top Banner</span>
      <input type="text" placeholder="Title" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.hero_title" class="{{ $inputClass }}">
      <textarea rows="2" placeholder="Subtitle" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.hero_subtitle" class="{{ $inputClass }}"></textarea>
      <div class="grid grid-cols-2 gap-2">
        <input type="text" placeholder="Button Text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.hero_cta_text" class="{{ $inputClass }}">
        <input type="text" placeholder="Button URL" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.hero_cta_href" class="{{ $inputClass }}">
      </div>
    </div>

    <div class="{{ $cardClass }}">
      <span class="text-xs font-semibold text-white block">Our Story & Arch Photo</span>
      @if(!empty($s['image_url']))
        <div class="relative rounded-lg overflow-hidden border border-[#282d37] h-24 bg-[#12141a]">
          <img src="{{ $s['image_url'] }}" class="w-full h-full object-cover">
          <button type="button" wire:click="removeImage" class="absolute top-1 right-1 px-1.5 py-0.5 rounded bg-red-600 text-white text-[9px] font-semibold">Remove</button>
        </div>
      @endif
      <input type="file" wire:model="uploadedImage" accept="image/*" class="block w-full text-[10px] text-gray-300 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:bg-[#214E34] file:text-white">
      <input type="text" placeholder="Story Photo URL" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.image_url" class="{{ $inputClass }}">
      <input type="text" placeholder="Eyebrow" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.story_eyebrow" class="{{ $inputClass }}">
      <input type="text" placeholder="Heading" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.story_heading" class="{{ $inputClass }}">
      <textarea rows="3" placeholder="Paragraph 1" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.story_p1" class="{{ $inputClass }}"></textarea>
      <textarea rows="3" placeholder="Paragraph 2" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.story_p2" class="{{ $inputClass }}"></textarea>
    </div>

    <div class="{{ $cardClass }}">
      <div class="flex items-center justify-between">
        <span class="text-xs font-semibold text-white">What We Promise</span>
        <button type="button" wire:click="addRepeaterItem('promises')" class="px-2 py-1 rounded bg-[#214E34] text-white text-[10px] font-semibold">+ Add</button>
      </div>
      <input type="text" placeholder="Heading" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.promises_heading" class="{{ $inputClass }}">
      <input type="text" placeholder="Subtitle" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.promises_subtitle" class="{{ $inputClass }}">
      @foreach(($s['promises'] ?? []) as $i => $pr)
        <div class="p-2 rounded bg-[#12141a] border border-[#282d37] space-y-1.5" wire:key="aprom-{{ $sIndex }}-{{ $i }}">
          <div class="flex justify-between text-[10px] text-gray-400"><span>Promise #{{ $i+1 }}</span><button type="button" wire:click="removeRepeaterItem('promises', {{ $i }})" class="text-red-400">✕</button></div>
          <input type="text" placeholder="Title" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.promises.{{ $i }}.title" class="{{ $inputClass }}">
          <textarea rows="2" placeholder="Description" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.promises.{{ $i }}.text" class="{{ $inputClass }}"></textarea>
        </div>
      @endforeach
    </div>

    <div class="{{ $cardClass }}">
      <div class="flex items-center justify-between">
        <span class="text-xs font-semibold text-white">How It Works Steps</span>
        <button type="button" wire:click="addRepeaterItem('steps')" class="px-2 py-1 rounded bg-[#214E34] text-white text-[10px] font-semibold">+ Add</button>
      </div>
      <input type="text" placeholder="Heading" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.steps_heading" class="{{ $inputClass }}">
      @foreach(($s['steps'] ?? []) as $i => $st)
        <div class="p-2 rounded bg-[#12141a] border border-[#282d37] space-y-1.5" wire:key="astep-{{ $sIndex }}-{{ $i }}">
          <div class="flex justify-between text-[10px] text-gray-400"><span>Step #{{ $i+1 }}</span><button type="button" wire:click="removeRepeaterItem('steps', {{ $i }})" class="text-red-400">✕</button></div>
          <div class="grid grid-cols-4 gap-1.5">
            <input type="text" placeholder="#" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.steps.{{ $i }}.n" class="{{ $inputClass }}">
            <input type="text" placeholder="Title" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.steps.{{ $i }}.title" class="{{ $inputClass }} col-span-3">
          </div>
          <input type="text" placeholder="Description" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.steps.{{ $i }}.text" class="{{ $inputClass }}">
        </div>
      @endforeach
    </div>

    <div class="{{ $cardClass }}">
      <div class="flex items-center justify-between">
        <span class="text-xs font-semibold text-white">Workshop Photos</span>
        <button type="button" wire:click="addRepeaterItem('workshop_photos')" class="px-2 py-1 rounded bg-[#214E34] text-white text-[10px] font-semibold">+ Add</button>
      </div>
      <input type="text" placeholder="Makers Heading" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.makers_heading" class="{{ $inputClass }}">
      <input type="text" placeholder="Workshops Heading" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.workshops_heading" class="{{ $inputClass }}">
      @foreach(($s['workshop_photos'] ?? []) as $i => $wp)
        <div class="p-2 rounded bg-[#12141a] border border-[#282d37] space-y-1.5" wire:key="awp-{{ $sIndex }}-{{ $i }}">
          <div class="flex justify-between text-[10px] text-gray-400"><span>Photo #{{ $i+1 }}</span><button type="button" wire:click="removeRepeaterItem('workshop_photos', {{ $i }})" class="text-red-400">✕</button></div>
          <input type="file" wire:click="setItemUploadTarget('workshop_photos', {{ $i }})" wire:model="uploadedItemImage" accept="image/*" class="block w-full text-[10px] text-gray-300 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:bg-[#214E34] file:text-white">
          <input type="text" placeholder="Image URL" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.workshop_photos.{{ $i }}.image_url" class="{{ $inputClass }}">
          <input type="text" placeholder="Caption" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.workshop_photos.{{ $i }}.caption" class="{{ $inputClass }}">
        </div>
      @endforeach
    </div>

    <div class="{{ $cardClass }}">
      <span class="text-xs font-semibold text-white block">Bottom Question Banner</span>
      <input type="text" placeholder="Heading" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.cta_heading" class="{{ $inputClass }}">
      <input type="text" placeholder="Subtitle" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.cta_subtitle" class="{{ $inputClass }}">
      <div class="grid grid-cols-2 gap-2">
        <input type="text" placeholder="Button Text" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.cta_button_text" class="{{ $inputClass }}">
        <input type="text" placeholder="Button URL" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.cta_button_href" class="{{ $inputClass }}">
      </div>
    </div>

  {{-- 18. CONTACT US FULL PAGE --}}
  @elseif($type === 'home_contact_page')
    <div class="{{ $cardClass }}">
      <span class="text-xs font-semibold text-white block">Top Banner & Form</span>
      <input type="text" placeholder="Title" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.hero_title" class="{{ $inputClass }}">
      <textarea rows="2" placeholder="Subtitle" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.hero_subtitle" class="{{ $inputClass }}"></textarea>
      <input type="text" placeholder="Form Heading" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.form_heading" class="{{ $inputClass }}">
      <input type="text" placeholder="Form Button" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.form_button_text" class="{{ $inputClass }}">
      <input type="text" placeholder="Form Helper Note" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.form_note" class="{{ $inputClass }}">
    </div>

    <div class="{{ $cardClass }}">
      <span class="text-xs font-semibold text-white block">Direct Contact Channels</span>
      <input type="text" placeholder="Section Heading" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.direct_heading" class="{{ $inputClass }}">
      <div class="grid grid-cols-2 gap-2">
        <input type="text" placeholder="WhatsApp Display" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.whatsapp_number" class="{{ $inputClass }}">
        <input type="text" placeholder="WhatsApp Digits" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.whatsapp_link" class="{{ $inputClass }}">
      </div>
      <input type="text" placeholder="Email Address" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.email_address" class="{{ $inputClass }}">
      <div class="grid grid-cols-2 gap-2">
        <input type="text" placeholder="Facebook Handle" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.facebook_handle" class="{{ $inputClass }}">
        <input type="text" placeholder="Facebook URL" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.facebook_url" class="{{ $inputClass }}">
      </div>
      <input type="text" placeholder="Open Hours" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.hours_line" class="{{ $inputClass }}">
      <input type="text" placeholder="Reply Time" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.reply_line" class="{{ $inputClass }}">
    </div>

    <div class="{{ $cardClass }}">
      <div class="flex items-center justify-between">
        <span class="text-xs font-semibold text-white">Delivery at a Glance</span>
        <button type="button" wire:click="addRepeaterItem('delivery_cards')" class="px-2 py-1 rounded bg-[#214E34] text-white text-[10px] font-semibold">+ Add</button>
      </div>
      <input type="text" placeholder="Heading" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.delivery_heading" class="{{ $inputClass }}">
      @foreach(($s['delivery_cards'] ?? []) as $i => $dc)
        <div class="p-2 rounded bg-[#12141a] border border-[#282d37] space-y-1.5" wire:key="cdc-{{ $sIndex }}-{{ $i }}">
          <div class="flex justify-between text-[10px] text-gray-400"><span>Card #{{ $i+1 }}</span><button type="button" wire:click="removeRepeaterItem('delivery_cards', {{ $i }})" class="text-red-400">✕</button></div>
          <input type="text" placeholder="Title" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.delivery_cards.{{ $i }}.title" class="{{ $inputClass }}">
          <textarea rows="2" placeholder="Description" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.delivery_cards.{{ $i }}.text" class="{{ $inputClass }}"></textarea>
        </div>
      @endforeach
    </div>

    <div class="{{ $cardClass }}">
      <div class="flex items-center justify-between">
        <span class="text-xs font-semibold text-white">Common Questions (FAQ)</span>
        <button type="button" wire:click="addRepeaterItem('faqs')" class="px-2 py-1 rounded bg-[#214E34] text-white text-[10px] font-semibold">+ Add</button>
      </div>
      <input type="text" placeholder="FAQ Heading" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.faq_heading" class="{{ $inputClass }}">
      @foreach(($s['faqs'] ?? []) as $i => $fq)
        <div class="p-2 rounded bg-[#12141a] border border-[#282d37] space-y-1.5" wire:key="cfaq-{{ $sIndex }}-{{ $i }}">
          <div class="flex justify-between text-[10px] text-gray-400"><span>FAQ #{{ $i+1 }}</span><button type="button" wire:click="removeRepeaterItem('faqs', {{ $i }})" class="text-red-400">✕</button></div>
          <input type="text" placeholder="Question" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.faqs.{{ $i }}.q" class="{{ $inputClass }}">
          <textarea rows="2" placeholder="Answer" wire:model.live.debounce.300ms="sections.{{ $sIndex }}.settings.faqs.{{ $i }}.a" class="{{ $inputClass }}"></textarea>
        </div>
      @endforeach
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
