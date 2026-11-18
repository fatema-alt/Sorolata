<?php

declare(strict_types=1);

$enFile = __DIR__ . '/../lang/en.json';
$bnFile = __DIR__ . '/../lang/bn.json';

if (!file_exists($enFile) || !file_exists($bnFile)) {
    echo "ERROR: Language files not found.\n";
    exit(1);
}

$enContent = file_get_contents($enFile);
$bnContent = file_get_contents($bnFile);

$en = json_decode($enContent, true, 512, JSON_THROW_ON_ERROR);
$bn = json_decode($bnContent, true, 512, JSON_THROW_ON_ERROR);

echo "VALID: lang/en.json and lang/bn.json are valid JSON.\n";
echo "Total EN keys: " . count($en) . "\n";
echo "Total BN keys: " . count($bn) . "\n";

$missingKeys = [];
$totalKeysChecked = 0;

function scanDirForPhp(string $dir, array $excludeSubstrings = []): array
{
    $files = [];
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($iterator as $file) {
        if (!$file->isFile() || !in_array($file->getExtension(), ['php', 'blade'], true)) {
            continue;
        }
        $path = str_replace('\\', '/', $file->getPathname());
        $skip = false;
        foreach ($excludeSubstrings as $exclude) {
            if (str_contains($path, $exclude)) {
                $skip = true;
                break;
            }
        }
        if (!$skip) {
            $files[] = $path;
        }
    }
    return $files;
}

// 1. Scan views (excluding admin and docs)
$viewFiles = scanDirForPhp(__DIR__ . '/../resources/views', ['/admin/', '/docs/']);

// 2. Scan app/ (excluding Admin and Api)
$appFiles = scanDirForPhp(__DIR__ . '/../app', ['/Admin/', '/Api/']);

$allFiles = array_merge($viewFiles, $appFiles);

foreach ($allFiles as $file) {
    $content = file_get_contents($file);

    // Match __('...') and __("...")
    if (preg_match_all("/__\(\s*(['\"])(.*?)\\1\s*(?:,\s*(\[.*?\]))?\s*\)/s", $content, $matches)) {
        foreach ($matches[2] as $rawKey) {
            $totalKeysChecked++;
            $key = str_replace(['\\\'', '\\"'], ["'", '"'], $rawKey);
            // Skip dynamic variables like __($variable)
            if (str_starts_with($key, '$')) {
                continue;
            }
            if (!array_key_exists($key, $bn)) {
                $missingKeys[] = [
                    'file' => str_replace(str_replace('\\', '/', realpath(__DIR__ . '/..')) . '/', '', $file),
                    'key' => $key
                ];
            }
        }
    }
}

echo "Checked {$totalKeysChecked} __() instances across views and app code.\n";

// 3. Audit non-admin/api controllers for raw unlocalized flash messages
$controllerAuditErrors = [];
$controllerFiles = scanDirForPhp(__DIR__ . '/../app/Http/Controllers', ['/Admin/', '/Api/']);

foreach ($controllerFiles as $file) {
    $lines = file($file);
    foreach ($lines as $lineNum => $line) {
        // Check for ->with('success'|'error'|'info'|'warning'|'status', 'Raw string')
        if (preg_match('/->with\(\s*[\'"](?:success|error|info|warning|status|coupon_error|coupon_success|review_success)[\'"]\s*,\s*([\'"][^\'\"]+[\'"])/', $line, $m)) {
            // If string literal passed directly without __()
            $controllerAuditErrors[] = [
                'file' => basename($file),
                'line' => $lineNum + 1,
                'matched' => trim($line),
            ];
        }
    }
}

$hasErrors = false;

if (!empty($missingKeys)) {
    $hasErrors = true;
    echo "\nWARNING: " . count($missingKeys) . " keys missing in lang/bn.json:\n";
    foreach ($missingKeys as $m) {
        echo " - [{$m['file']}] {$m['key']}\n";
    }
} else {
    echo "SUCCESS: 0 missing keys in lang/bn.json across all scanned files!\n";
}

if (!empty($controllerAuditErrors)) {
    $hasErrors = true;
    echo "\nWARNING: Found " . count($controllerAuditErrors) . " raw English/unlocalized flash messages in controllers:\n";
    foreach ($controllerAuditErrors as $err) {
        echo " - [{$err['file']}:{$err['line']}] {$err['matched']}\n";
    }
} else {
    echo "SUCCESS: 0 raw unlocalized flash messages found in non-admin controllers!\n";
}

if ($hasErrors) {
    exit(1);
}

echo "All i18n checks passed cleanly!\n";
exit(0);
