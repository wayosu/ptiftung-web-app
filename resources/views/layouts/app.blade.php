<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Program Studi Pendidikan Teknologi Informasi</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link
        href="https://fonts.bunny.net/css?family=oswald:200,300,400,500,600,700|source-sans-pro:200,300,400,600,700,900"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.min.css" />

    <!-- Swiper -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    @vite('resources/css/app.css')

    <!-- Scripts -->
    @vite('resources/js/app.js')
</head>

<body>
    @include('layouts.topbar')
    @include('layouts.navbar')

    @yield('content')

    @include('layouts.footer')

    <a href="javascript:void(0)" id="btnBackToTop"
        class="fixed bottom-5 right-5 z-50 w-12 h-12 rounded-full items-center justify-center bg-yellow-900 text-navy-900 text-lg font-bold opacity-50 hover:opacity-100 transition-all duration-300 hidden">
        <i class="ri-arrow-up-line"></i>
    </a>

    {{-- @include('layouts.cookie') --}}

    @stack('js')
    @vite('resources/js/main.js')
</body>

</html>
