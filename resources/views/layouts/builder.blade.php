<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" translate="no" class="h-full antialiased dark notranslate">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google" content="notranslate">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Visual Page Builder' }} — Sorolata Engine</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=Hind+Siliguri:wght@400;600&family=Manrope:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        /* Visual Page Builder Studio Theme */
        :root {
            --builder-bg: #12141a;
            --builder-panel: #1a1d24;
            --builder-border: #282d37;
            --builder-surface: #222731;
            --builder-accent: #214E34;
            --builder-terracotta: #C96A3D;
        }

        /* Custom scrollbar for builder panels */
        .builder-scroll::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        .builder-scroll::-webkit-scrollbar-track {
            background: #12141a;
        }
        .builder-scroll::-webkit-scrollbar-thumb {
            background: #2f3644;
            border-radius: 999px;
        }
        .builder-scroll::-webkit-scrollbar-thumb:hover {
            background: #3e4758;
        }
    </style>
</head>
<body class="h-full bg-[#12141a] text-[#E0E2EC] font-sans antialiased overflow-hidden flex flex-col select-none">

    {{ $slot ?? '' }}
    @yield('content')

    @livewireScripts
</body>
</html>
