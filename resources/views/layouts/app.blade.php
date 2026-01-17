<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <title>@yield('title', 'Sistem PANGLONG')</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Google Font -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        {{-- Icons Bootstrap --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

        @stack('styles')
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        @notifyCss
    </head>

    <body class="d-flex flex-column min-vh-100">

        {{-- HEADER MOBILE --}}
        <div class="d-block d-md-none mb-5">
            <x-header-mobile />
        </div>

        {{-- DESKTOP NAVBAR --}}
        <div class="d-none d-lg-block">
            <x-navbar />
        </div>

        {{-- Notification Component --}}
        <span class="text-capitalize">
            <x-notify::notify />
        </span>

        <div class="container-fluid dark-bg">
            <div class="row">

                {{-- SIDEBAR (TABLET ONLY) --}}
                <div class="d-none d-md-block d-lg-none col-md-3 p-0">

                    <x-sidebar />

                </div>

                {{-- MAIN CONTENT --}}
                <main class="col-12 col-md-9 col-lg-12 p-3 shadow-sm" style="min-height:50vh;">
                    @yield('content')
                </main>

            </div>
        </div>

        <x-footer />

        {{-- MOBILE NAV --}}
        <div class="d-block d-md-none">
            <x-mobile-nav />
        </div>
        @notifyJs
    </body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="{{ asset('javascript/index.js') }}"></script>

</html>
