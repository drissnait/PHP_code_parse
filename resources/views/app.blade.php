<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>@yield('title')</title>
        <link rel="shortcut icon" type="image/x-icon" href="{{url('favicon.ico')}}" />

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
        @yield('head_links')
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ url('/css/style.css') }}" />
    </head>
    <body>
        <header>
            @include('layouts.navbar')
        </header>
        <main>
            @yield('main')
        </main>
        <script src="{{ url('/js/app.js') }}" charset="utf-8"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
        <script src="https://unpkg.com/@popperjs/core@2"></script>

        @yield('js')

        <footer class="mt-5"></footer>
    </body>
</html>
