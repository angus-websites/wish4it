<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <title>Back soon</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/core/favicon.png') }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{config('seo.description') }}">
    <meta name="keywords" content="Wish4it, wishlists, gift registry, share wishlists, family gifts, friends gifts, Event wishlists, birthday gifts, holiday wishes, Special occasion gifts, gifts, family, friends">

    <!-- Icons -->
    @foreach ([16, 32, 48] as $size)
        <link rel="icon" type="image/png" sizes="{{ $size }}x{{ $size }}" href="{{ asset("assets/images/meta/favicon-{$size}x{$size}.png") }}">
    @endforeach

    @foreach ([180, 152, 120] as $size)
        <link rel="apple-touch-icon" sizes="{{ $size }}x{{ $size }}" href="{{ asset("assets/images/meta/apple-touch-icon-{$size}x{$size}.png") }}">
    @endforeach

    @vite(['resources/css/app.css'])


</head>
<body class="font-serif antialiased">
    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
        <div class="p-10 flex flex-col items-center space-y-20">
            <img src="{{ asset('assets/images/core/logo.svg') }}" alt="Wish4it logo" class="h-12 sm:h-16 md:h-28">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-900">Back soon</h1>
                <p class="text-gray-600">We are currently performing maintenance. We will be back shortly.</p>
            </div>
        </div>
    </div>
</body>
</html>
