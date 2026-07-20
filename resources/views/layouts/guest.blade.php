<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ config('app.name', 'WiFi Zone') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body class="font-sans text-gray-900 antialiased">

    <div class="min-h-screen flex flex-col items-center pt-6 sm:justify-center sm:pt-0 bg-gray-100">

        <div class="text-center">
            <div class="text-3xl font-bold text-blue-600">
                WiFi Zone
            </div>
            <div class="text-sm text-gray-500">
                Administration
            </div>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>

    </div>

</body>

</html>