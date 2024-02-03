<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name')) {{ ' - ' . config('app.name') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Css -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        <!-- Scripts -->
        <script defer src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/datepicker.min.js"></script>
        <script defer src="{{ mix('js/app.js') }}"></script>

        <!-- Favicon -->
        <link href="{{ asset('storage/favicon.png') }}" rel="icon" type="image/png">
    </head>
    <body class="font-sans antialiased">
        <div class="bg-gray-100 min-h-screen">
            <!-- Navigation Menu -->
            @include('layouts.navigation')

            <!-- Page Content -->
            <main class="font-sans text-gray-900 antialiased">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
