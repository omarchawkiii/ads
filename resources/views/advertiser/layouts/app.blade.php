<!DOCTYPE html>
<html lang="en" dir="ltr"  @if(Auth::user()->theme?->light)  data-bs-theme="light" @else   data-bs-theme="dark"  @endif data-color-theme="Blue_Theme" data-layout="vertical">

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
    @include('advertiser.partiels.sidebar')
    <div class="page-wrapper">
        @include('advertiser.partiels.header')

        <div class="body-wrapper">
            <div class="container-fluid padding-15">
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

    <div id="user_edit_password_modal" class="modal hide fade" role="dialog" aria-labelledby="edit_user_modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered  ">
            <div class="modal-content border-0">
                <div class="modal-header d-flex align-items-center  bg-primary ">
                    <h4 class="modal-title text-white " id="myLargeModalLabel ">
                        Edit Your password
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body minauto text-center p-4">
                    <div class="">
                        <form method="PUT" class="needs-validation" novalidate id="user_edit_password_form">
                            @csrf
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="w-100 " style="text-align: left">Old Password</label>
                                        <input id="old_password" type="password" class="form-control"
                                            placeholder="Old Password" required>
                                        <div class="text-danger mt-1 " id="old_password_error"></div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="w-100 " style="text-align: left">Password</label>
                                        <input id="password" type="password" class="form-control"
                                            placeholder="Password"  required>

                                        <div class="text-danger mt-1 " id="password_error"></div>

                                    </div>
                                </div>


                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="w-100 " style="text-align: left"> Confirm Password </label>
                                        <input id="confirm_password" type="password" class="form-control"
                                            placeholder=" Confirm Password " required>

                                            <div class="text-danger mt-1 " id="confirm_password_error"></div>

                                    </div>
                                </div>



                                <div class=" m-2">
                                    <button type="submit" class="btn btn-success me-2" id="submit_update_password">Submit</button>
                                    <button data-bs-dismiss="modal" aria-label="Close" type="button"  class="btn btn-dark">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
            <!--end modal-content-->
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


    <script>
        (function () {
           'use strict'

           // Fetch all the forms we want to apply custom Bootstrap validation styles to
           var forms = $( "#user_edit_password_form" )

           // Loop over them and prevent submission
           Array.prototype.slice.call(forms)
               .forEach(function (form) {
               form.addEventListener('submit', function (event) {

                   if (!form.checkValidity()) {

                   event.preventDefault()
                   event.stopPropagation()
                   }
                   form.classList.add('was-validated')
               }, false)
               })
           })()
       $(document).on('click', '#reset_user_password', function() {

           $('#user_edit_password_form').trigger('reset');
           $('#old_password_error').html('')
           $('#confirm_password_error').html('')
            $('#user_edit_password_modal').modal('show');
       });

       $(document).on("submit","#user_edit_password_form" , function(event) {
           event.preventDefault();
           var id = $('#id_user_password').val();
           var password = $('#password').val();
           var old_password = $('#old_password').val();
           var confirm_password = $('#confirm_password').val();
           var url = '{{ url('') }}' + '/user/user_update_password';
           var all_location = $('#location').val();
           //$('#edit_user_modal').modal('show');
           $.ajax({
               url: url,
               type: 'PUT',
               method: 'PUT',
               data: {
                   id: id,
                   password: password,
                   old_password: old_password,
                   confirm_password: confirm_password,

                   "_token": "{{ csrf_token() }}",
               },

               headers: {
                   'X-CSRF-TOKEN': "{{ csrf_token() }}",
                   "_token": "{{ csrf_token() }}",
               },
               success: function(response) {

                   $('#old_password_error').html('')
                   $('#confirm_password_error').html('')
                   if(response =="success")
                   {
                       Swal.fire({
                           title: 'Done!',
                           text: 'Password Updated Successfully',
                           icon: 'success',
                           confirmButtonText: 'Continue'
                       });

                       $('#user_edit_password_modal').modal('hide') ;
                       $('#user_edit_password_form').trigger('reset');
                   }
                   else if( response =="The old password is incorrect.")
                   {
                       $('#old_password_error').html('The old password is incorrect.')
                   }
                   else if( response =="The password and confirm password do not match.")
                   {
                       $('#confirm_password_error').html('The password and confirm password do not match.')
                   }
               },
               error: function(jqXHR, textStatus, errorThrown) {
                   console.log(response);
               }
           })

       })

       $(document).on("click",".change_theme" , function(event) {

           url = '{{ url('') }}' + '/change_theme';
           event.preventDefault();
           $.ajax({
               url: url,
               method: 'POST',
               headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
               data: {}, // ta méthode change_theme() n'attend pas de payload
               success: function (resp) {

                   // si ton controller renvoie JSON { light: 1 } tu peux forcer l'état à partir de resp
                   if (resp && typeof resp.light !== 'undefined') {


                   }
               },
               error: function (xhr) {
               },
               complete: function () {
               }
           });
       })

       new bootstrap.Tooltip(document.body, {
           selector: '[data-bs-toggle="tooltip"]',
           trigger: 'hover focus'
       });
   </script>


@yield('custom_script')


</body>

</html>

