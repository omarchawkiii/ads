<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical">

<head>
  <!-- Required meta tags -->
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Favicon icon-->
  <link rel="shortcut icon" type="image/png" href="{{asset('assets/images/logos/favicon.png')}}">

  <!-- Core Css -->
  <link rel="stylesheet" href="{{asset('assets/css/styles.css')}}">

  <title>AdSmart | @yield('title')  </title>
</head>

<body class="link-sidebar">
  <!-- Preloader -->
  <div class="preloader">
    <img src="{{asset('assets/images/logos/favicon.png')}}" alt="loader" class="lds-ripple img-fluid">
  </div>
  <div id="main-wrapper">

  </div>
  <div class="dark-transparent sidebartoggler"></div>
  <!-- Import Js Files -->
  <script src="{{asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('assets/libs/simplebar/dist/simplebar.min.js')}}"></script>
  <script src="{{asset('assets/js/theme/app.init.js')}}"></script>
  <script src="{{asset('assets/js/theme/theme.js')}}"></script>
  <script src="{{asset('assets/js/theme/app.min.js')}}"></script>
  <script src="{{asset('assets/js/theme/sidebarmenu-default.js')}}"></script>

  <!-- solar icons -->
  <script src="{{asset('assets/iconify-icon%401.0.8/dist/iconify-icon.min.js')}}"></script>
</body>

</html>
