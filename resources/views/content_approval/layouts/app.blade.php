<!DOCTYPE html>
<html lang="en" dir="ltr" @if(Auth::user()->theme?->light)  data-bs-theme="light" @else   data-bs-theme="dark"  @endif data-color-theme="Blue_Theme" data-layout="vertical">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/favicon.png')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('assets/libs/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/sweetalert2/dist/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css')}}">
    <title>AdSmart | @yield('title')</title>
    <script>
        window.APP_URL    = "{{ url('') }}";
        window.CSRF_TOKEN = "{{ csrf_token() }}";
    </script>
    @yield('custom_css')
</head>

<body class="link-sidebar">
  <div class="preloader">
    <img src="{{ asset('assets/images/logos/favicon.png')}}" alt="loader" class="lds-ripple img-fluid">
  </div>
  <div id="main-wrapper">
    @include('content_approval.partiels.sidebar')
    <div class="page-wrapper">
        @include('content_approval.partiels.header')
        <div class="body-wrapper">
            <div class="container-fluid padding-15">
                @yield('content')
            </div>
            @include('partiels.loader')
        </div>
    </div>

    <div class="modal" id="wait-modal" tabindex="-1" role="dialog" aria-hidden="true" style="background: #00000038;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="background: transparent; border: none; box-shadow: none;">
                <div class="modal-body text-center" style="min-height: auto; height: 260px; display: flex">
                    <div class="loader">
                        <h5 class="modal-title mb-3 text-white">Loading...</h5>
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function handleColorTheme(e) {
            document.documentElement.setAttribute("data-color-theme", e);
        }
    </script>

    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{ asset('assets/libs/simplebar/dist/simplebar.min.js')}}"></script>
    <script src="{{ asset('assets/js/theme/app.init.js')}}"></script>
    <script src="{{ asset('assets/js/theme/theme.js')}}"></script>
    <script src="{{ asset('assets/js/theme/app.min.js')}}"></script>
    <script src="{{ asset('assets/js/theme/sidebarmenu-default.js')}}"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs5/js/jquery.dataTables.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
    <script src="{{ asset('assets/libs/sweetalert2/dist/sweetalert2.min.js')}}"></script>

    <script>
        $(document).on("click", ".change_theme", function(event) {
            event.preventDefault();
            $.ajax({
                url: '{{ url('') }}' + '/change_theme',
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                data: {}
            });
        });

        new bootstrap.Tooltip(document.body, {
            selector: '[data-bs-toggle="tooltip"]',
            trigger: 'hover focus'
        });
    </script>

    @yield('custom_script')
</body>
</html>
