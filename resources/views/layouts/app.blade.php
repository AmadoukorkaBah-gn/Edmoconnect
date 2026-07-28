<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ config('app.name', 'WiFi Zone') }}</title>

    @vite(['resources/css/app.css','resources/js/app.js'])

    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
@stack('scripts')
<body class="bg-gray-100">

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar (fixe, ne scrolle jamais) --}}
    @include('layouts.sidebar')

    {{-- Colonne de droite : décalée de la largeur de la sidebar sur desktop --}}
    <div class="flex-1 flex flex-col h-screen overflow-hidden lg:pl-72">

        {{-- Navbar (fixe, ne scrolle jamais) --}}
        @include('layouts.navbar')

        {{-- Seul ce bloc scrolle --}}
        <main class="flex-1 overflow-y-auto p-6">

            @yield('content')

        </main>

    </div>

</div>

</body>

</html>
