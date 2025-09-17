<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title inertia>Dharma & Astri Wedding</title>

    <!-- Primary Meta Tags -->
    <meta name="title" content="Dharma & Astri Wedding">
    <meta name="description" content="Kami mengundang Anda untuk menghadiri pernikahan kami">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="Dharma & Astri Wedding">
    <meta property="og:description" content="Kami mengundang Anda untuk menghadiri pernikahan kami">
    <meta property="og:image" content="{{ asset('assets/images/main.webp') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="Dharma & Astri Wedding">
    <meta property="twitter:description" content="Kami mengundang Anda untuk menghadiri pernikahan kami">
    <meta property="twitter:image" content="{{ asset('assets/images/main.webp') }}">

    <!-- WhatsApp -->
    <meta property="og:site_name" content="Dharma & Astri Wedding">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Fanwood+Text&family=Poiret+One&family=Poly&family=Dancing+Script&family=Playball&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Wittgenstein:ital,wght@0,400..900;1,400..900&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>

<body class="font-sans antialiased">
    @inertia
</body>

</html>
