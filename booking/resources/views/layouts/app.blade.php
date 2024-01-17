<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Laravel')) {{ ' - ' . config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Css -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        <!-- Favicon -->
        <link href="{{ asset('storage/favicon.png') }}" rel="icon" type="image/png">
    </head>
    <body class="font-sans antialiased">
        <div class="bg-gray-100 min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if(isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="px-4">
                {{ $slot }}
            </main>
        </div>

        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/datepicker.min.js"></script>
    </body>
</html>
