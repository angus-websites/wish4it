<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>

        <title inertia>{{config('seo.title') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('assets/images/core/favicon.png') }}">
        <link rel="canonical" href="{{ url()->current() }}">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="{{config('seo.description') }}">
        <meta name="keywords" content="Wish4it, Wishlist, Gift registry, Share wishlists, Family gifts, Friend gifts, Event wishlists, Birthday gifts, Holiday wishes, Special occasion gifts">

        <meta property="og:title" content="{{config('seo.title')}}">
        <meta property="og:description" content="{{config('seo.description') }}">
        <meta property="og:url" content="{{ url()->current() }}">

        <meta property="og:image" content="{{ asset('assets/images/meta/ogimage.png') }}">
        <meta property="og:image:type" content="image/jpeg">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">

        <!-- Icons -->
        @foreach ([16, 32, 48] as $size)
            <link rel="icon" type="image/png" sizes="{{ $size }}x{{ $size }}" href="{{ asset("assets/images/meta/favicon-{$size}x{$size}.png") }}">
        @endforeach

        @foreach ([180, 152, 120] as $size)
            <link rel="apple-touch-icon" sizes="{{ $size }}x{{ $size }}" href="{{ asset("assets/images/meta/apple-touch-icon-{$size}x{$size}.png") }}">
        @endforeach

        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-serif antialiased">
        @inertia
    </body>
</html>
