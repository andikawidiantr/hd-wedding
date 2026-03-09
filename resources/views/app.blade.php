<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @php
        $metaTitle = $page['props']['meta']['title'] ?? 'Hendra & Dinda Wedding';
        $metaDescription = $page['props']['meta']['description'] ?? 'Kami mengundang Anda untuk menghadiri pernikahan kami.';
        $metaImage = $page['props']['meta']['image'] ?? url('/assets/images/edit-26.jpg');
        $metaUrl = url()->current();
    @endphp

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title inertia>{{ $metaTitle }}</title>

    <link rel="canonical" href="{{ $metaUrl }}">
    <meta name="title" content="{{ $metaTitle }}">
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="robots" content="index,follow,max-image-preview:large">

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $metaUrl }}">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:image" content="{{ $metaImage }}">
    <meta property="og:image:secure_url" content="{{ $metaImage }}">
    <meta property="og:image:type" content="image/jpeg">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="Hendra & Dinda Wedding">

    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ $metaUrl }}">
    <meta property="twitter:title" content="{{ $metaTitle }}">
    <meta property="twitter:description" content="{{ $metaDescription }}">
    <meta property="twitter:image" content="{{ $metaImage }}">

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
