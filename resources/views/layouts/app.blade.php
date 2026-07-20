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

<div class="flex min-h-screen">

    {{-- Sidebar --}}
    @include('layouts.sidebar')

    <div class="flex-1 flex flex-col">

        {{-- Navbar --}}
        @include('layouts.navbar')

        {{-- Contenu --}}
        <main class="flex-1 p-6">

            @yield('content')

        </main>

    </div>
    

</div>

</body>

</html>