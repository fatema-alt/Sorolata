<?php

$enFile = __DIR__ . '/../lang/en.json';
$bnFile = __DIR__ . '/../lang/bn.json';

if (!file_exists($enFile) || !file_exists($bnFile)) {
    echo "ERROR: Language files not found.\n";
    exit(1);
}

$enContent = file_get_contents($enFile);
$bnContent = file_get_contents($bnFile);

$en = json_decode($enContent, true);
$bn = json_decode($bnContent, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo "ERROR: JSON syntax error in lang file: " . json_last_error_msg() . "\n";
    exit(1);
}

echo "VALID: lang/en.json and lang/bn.json are valid JSON.\n";
echo "Total EN keys: " . count($en) . "\n";
echo "Total BN keys: " . count($bn) . "\n";

// Scan views in Batch 1 & 2
$batchFiles = [
    __DIR__ . '/../resources/views/layouts/storefront.blade.php',
    __DIR__ . '/../resources/views/storefront/index.blade.php',
    __DIR__ . '/../resources/views/components/category-card.blade.php',
    __DIR__ . '/../resources/views/components/collection-card.blade.php',
    __DIR__ . '/../resources/views/components/district-card.blade.php',
    __DIR__ . '/../resources/views/components/floating-search.blade.php',
    __DIR__ . '/../resources/views/components/mobile-bottom-nav.blade.php',
    __DIR__ . '/../resources/views/components/product-card.blade.php',
    __DIR__ . '/../resources/views/components/shoppable-product-card.blade.php',
    __DIR__ . '/../resources/views/components/testimonial-card.blade.php',
    __DIR__ . '/../resources/views/shop/index.blade.php',
    __DIR__ . '/../resources/views/categories/show.blade.php',
    __DIR__ . '/../resources/views/collections/show.blade.php',
    __DIR__ . '/../resources/views/districts/index.blade.php',
    __DIR__ . '/../resources/views/districts/show.blade.php',
    __DIR__ . '/../resources/views/artisans/index.blade.php',
    __DIR__ . '/../resources/views/artisans/show.blade.php',
    __DIR__ . '/../resources/views/stories/index.blade.php',
    __DIR__ . '/../resources/views/stories/show.blade.php',
    __DIR__ . '/../resources/views/products/show.blade.php',
    // Batch 3
    __DIR__ . '/../resources/views/cart/index.blade.php',
    __DIR__ . '/../resources/views/checkout/index.blade.php',
    __DIR__ . '/../resources/views/auth/customer-login.blade.php',
    __DIR__ . '/../resources/views/auth/customer-register.blade.php',
    __DIR__ . '/../resources/views/account/index.blade.php',
    __DIR__ . '/../resources/views/pages/wishlist.blade.php',
    __DIR__ . '/../resources/views/pages/track-order.blade.php',
];

$missingKeys = [];
$totalKeysChecked = 0;

foreach ($batchFiles as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    
    // Match __('...') and __("...")
    if (preg_match_all("/__\(\s*(['\"])(.*?)\\1\s*(?:,\s*(\[.*?\]))?\s*\)/s", $content, $matches)) {
        foreach ($matches[2] as $rawKey) {
            $totalKeysChecked++;
            // Unescape quote if needed
            $key = str_replace(['\\\'', '\\"'], ["'", '"'], $rawKey);
            if (!array_key_exists($key, $bn)) {
                $missingKeys[] = [
                    'file' => basename($file),
                    'key' => $key
                ];
            }
        }
    }
}

echo "Checked {$totalKeysChecked} __() instances across Batch 1 views.\n";

if (empty($missingKeys)) {
    echo "SUCCESS: 0 missing keys in lang/bn.json for Batch 1 views!\n";
} else {
    echo "WARNING: " . count($missingKeys) . " keys missing in lang/bn.json:\n";
    foreach ($missingKeys as $m) {
        echo " - [{$m['file']}] {$m['key']}\n";
    }
    exit(1);
}
