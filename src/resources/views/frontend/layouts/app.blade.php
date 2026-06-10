<!doctype html>
<html lang="id">
<head>
    @include('frontend.partials.head')
    @stack('styles')
</head>

<body class="@yield('body_class')">
    @include('frontend.partials.preloader')
    @include('frontend.partials.navbar')

    @yield('content')

    @include('frontend.partials.footer')

    @include('frontend.partials.scripts')

    @stack('scripts')
</body>
</html>