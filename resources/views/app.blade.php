<!doctype html>
<html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="icon" href="{{ asset('assets/images/logos/favicon.png') }}" />


@routes
@inertiaHead


{{-- Si vous utilisez Vite (recommandé) --}}
@vite(['resources/js/app.js', 'resources/css/styles.css'])


{{-- Alternative legacy :
<link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}"> --}}


<title>{{ config('app.name') }} - @yield('title')</title>
</head>
<body class="link-sidebar">
{{-- Preloader peut être rendu dans Vue Layout, mais on peut garder un fallback HTML --}}
<div id="app">
@inertia
</div>


{{-- Scripts: via Vite will include your bundled theme scripts if imported in app.js --}}
@vite(['resources/js/app.js'])


{{-- Legacy script inclusion example:
<script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/theme/app.init.js') }}"></script>
--}}
</body>
</html>
