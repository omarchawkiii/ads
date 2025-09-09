<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical">

<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Favicon icon-->
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/favicon.png')}}">

    <!-- Core Css -->
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('assets/libs/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/sweetalert2/dist/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css')}}">
    <title>AdSmart | @yield('title')  </title>
    @yield('custom_css')
    <style>
        .loader {
            border-radius: 50%;
            display: block;
            margin: auto;
            position: relative;
            color: #297eee ;
            box-sizing: border-box;
            animation: animloader 1s linear infinite alternate;
            }
    </style>
</head>

<body class="link-sidebar">
  <!-- Preloader -->
  <div class="preloader">
    <img src="{{ asset('assets/images/logos/favicon.png')}}" alt="loader" class="lds-ripple img-fluid">
  </div>
  <div id="main-wrapper">
    @include('admin.partiels.sidebar')
    <div class="page-wrapper">
        @include('admin.partiels.header')

        <div class="body-wrapper">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
    </div>
    <div class="modal    " id="wait-modal" tabindex="-1" role="dialog" aria-labelledby="loadingModal2Label"aria-hidden="true" style="background: #00000038;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="    background: transparent; border: none; box-shadow: none;">
                <div class="modal-body text-center" style="min-height: auto; height: 260px; display: flex ">
                    <h5 class="modal-title mb-3"> </h5>
                    <div class="loader">
                        <h5 class="modal-title mb-3 text-white"> Loading...</h5>
                        <div class="spinner-border text-primary " role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
    <script>
        function handleColorTheme(e)
        {
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

    <!-- solar icons -->
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
    <script src="{{ asset('assets/libs/sweetalert2/dist/sweetalert2.min.js')}}"></script>

@yield('custom_script')


</body>

</html>

