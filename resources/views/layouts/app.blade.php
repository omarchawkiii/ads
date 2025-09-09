<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-topbar="light" data-sidebar="light"
    data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>
    <meta charset="utf-8" />
    <title>NOC | @yield('title') </title>
    <link rel="stylesheet" href="{{ asset('/assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/vendors/jvectormap/jquery-jvectormap.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/vendors/flag-icon-css/css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/vendors/owl-carousel-2/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/vendors/owl-carousel-2/owl.theme.default.min.css') }}">

    @if(Auth::user()->theme?->light)
        <link rel="stylesheet" href="{{ asset('/assets/css/modern-vertical/style_light.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('/assets/css/modern-vertical/style_blue.css') }}">
    @endif

    <link rel="shortcut icon" href="{{ asset('/assets/images/favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('/assets/images/favicon.ico') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @yield('custom_css')

    <style>
        .loader {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            display: block;
            margin: auto;
            position: relative;
            color: #297eee ;
            box-sizing: border-box;
            animation: animloader 1s linear infinite alternate;
            }

            @keyframes animloader {
            0% {
                box-shadow: -38px -12px ,  -14px 0,  14px 0, 38px 0;
            }
            33% {
                box-shadow: -38px 0px, -14px -12px,  14px 0, 38px 0;
            }
            66% {
                box-shadow: -38px 0px , -14px 0, 14px -12px, 38px 0;
            }
            100% {
                box-shadow: -38px 0 , -14px 0, 14px 0 , 38px -12px;
            }
        }

    </style>
</head>

<body class="sidebar-fixed">
    <div class="container-scroller">
        @include('partiels.sidebar')
        <div class="container-fluid page-body-wrapper">
            @include('partiels.header')
            <div class="main-panel">
                <div class="content-wrapper">
                    @yield('content')
                </div>

            </div>
        </div>
    </div>

    <!--<div class="modal fade show header-popup" id="header_kdm_errors_modal" tabindex="-1" aria-labelledby="ModalLabel" aria-modal="true" role="dialog">
        <div class="modal-dialog  modal-xl" role="document" style="max-width: 93%; width: 93%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> KDMS Errors List</h5>
                    <input type="hidden">
                    <button type="button" class="btn-close" id="createMemberBtn-close" data-bs-dismiss="modal"
                        aria-label="Close"><span aria-hidden="true"
                            style="color:white;font-size: 26px;line-height: 18px;">×</span></button>
                </div>
                <div class="modal-body">


                    <div id="list_kdm_errors" class="table-responsive preview-list multiplex">

                        <table class="table " id="header_table_list_kdm_errors">
                            <thead>
                                <tr>
                                    <th>Location</th>
                                    <th>CPL UUID</th>
                                    <th>Annotation Text </th>
                                    <th>Details</th>
                                    <th width="10%">Screen</th>
                                    <th width="30%">Date</th>
                                </tr>
                            </thead>
                            <tbody id="header_body_list_kdm_errors">

                            </tbody>
                        </table>
                    </div>

                </div>


            </div>
        </div>
    </div>

    <div class="modal fade show header-popup" id="header_server_errors_modal" tabindex="-1"
        aria-labelledby="ModalLabel" aria-modal="true" role="dialog">
        <div class="modal-dialog  modal-xl" role="document" style="max-width: 93%; width: 93%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> Sever Errors List</h5>
                    <input type="hidden">
                    <button type="button" class="btn-close" id="createMemberBtn-close" data-bs-dismiss="modal"
                        aria-label="Close"><span aria-hidden="true"
                            style="color:white;font-size: 26px;line-height: 18px;">×</span></button>
                </div>
                <div class="modal-body">


                    <div id="list_server_errors" class="table-responsive preview-list multiplex">

                        <table class="table " id="table_list_server_errors">
                            <thead>
                                <tr>
                                    <th>Location</th>
                                    <th> Event Id</th>
                                    <th> Date</th>
                                    <th> Class</th>
                                    <th> Type</th>
                                    <th> SubType</th>
                                    <th> Severity</th>
                                    <th> Error Code</th>
                                    <th> Screen</th>
                                </tr>
                            </thead>
                            <tbody id="header_body_list_server_errors">

                            </tbody>
                        </table>
                    </div>

                </div>


            </div>
        </div>
    </div>

    <div class="modal fade show header-popup" id="header_projector_errors_modal" tabindex="-1"
        aria-labelledby="ModalLabel" aria-modal="true" role="dialog">
        <div class="modal-dialog  modal-xl" role="document" style="max-width: 93%; width: 93%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> Projector Errors List</h5>
                    <input type="hidden">
                    <button type="button" class="btn-close" id="createMemberBtn-close" data-bs-dismiss="modal"
                        aria-label="Close"><span aria-hidden="true"
                            style="color:white;font-size: 26px;line-height: 18px;">×</span></button>
                </div>
                <div class="modal-body">


                    <div id="list_projector_errors" class="table-responsive preview-list multiplex">

                        <table class="table " id="table_list_projector_errors">
                            <thead>
                                <tr>
                                    <th>Location</th>
                                    <th> Title</th>
                                    <th> Time</th>
                                    <th> Code</th>
                                    <th> Severity</th>
                                    <th> Message</th>
                                    <th> Screen</th>
                                </tr>
                            </thead>
                            <tbody id="header_body_list_projector_errors">

                            </tbody>
                        </table>
                    </div>

                </div>


            </div>
        </div>
    </div>
    <div class="modal fade show" id="header_storage_errors_modal" tabindex="-1" aria-labelledby="ModalLabel"  aria-modal="true" role="dialog">
        <div class="modal-dialog  modal-xl"  role="document"  style="max-width: 93%; width: 93%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> Storage Errors List</h5>
                    <input type="hidden">
                    <button type="button" class="btn-close" id="createMemberBtn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true" style="color:white;font-size: 26px;line-height: 18px;">×</span></button>
                </div>
                <div class="modal-body">

                    <div id="list_storage_errors"  class="table-responsive preview-list multiplex">

                        <table class="table " id="header_table_list_storage_errors">
                            <thead>
                            <tr>
                                <th> Location</th>
                                <th> Stat</th>
                                <th> Screen</th>

                            </tr>
                            </thead>
                            <tbody id="header_body_list_storage_errors">

                            </tbody>
                        </table>
                    </div>

                </div>


            </div>
        </div>
    </div> -->

    <div class="modal fade show" id="error_modal" tabindex="-1" role="dialog" aria-labelledby="delete_client_modalLabel" aria-modal="true" >
        <div class="modal-dialog modal-dialog-centered  modal-xl">
            <div class="modal-content border-0">
                <div class="modal-header p-4 pb-0">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                          <a class="nav-link active" id="Kdms-tab" data-bs-toggle="tab" href="#Kdms" role="tab" aria-controls="home" aria-selected="true">Kdms </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" id="Server-tab" data-bs-toggle="tab" href="#Server" role="tab" aria-controls="Content Server" aria-selected="false">Server </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" id="Projector-tab" data-bs-toggle="tab" href="#Projector" role="tab" aria-controls="Projector" aria-selected="false">Projector </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="Sound-tab" data-bs-toggle="tab" href="#Sound" role="tab" aria-controls="Sound" aria-selected="false">Sound </a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" id="Storage-tab" data-bs-toggle="tab" href="#Storage" role="tab" aria-controls="Storage" aria-selected="false">Storage </a>
                          </li>
                      </ul>
                    <button type="button" class="btn-close" id="createMemberBtn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true" style="color:white;font-size: 26px;line-height: 18px;">×</span></button>
                </div>
                <div class="modal-body text-center p-4">
                    <div class="tab-content border-0">
                        <div class="tab-pane fade active show" id="Kdms" role="tabpanel" aria-labelledby="Kdms-tab">
                            <div id="list_kdm_errors" class="table-responsive preview-list multiplex">
                                <h5 class="mt-2 mb-2">KDMS Errors List</h5>
                                <table class="table " id="header_table_list_kdm_errors">
                                    <thead>
                                        <tr>
                                            <th>Location</th>
                                            <th>CPL UUID</th>
                                            <th>Annotation Text </th>
                                            <th>Details</th>
                                            <th width="10%">Screen</th>
                                            <th width="30%">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody id="header_body_list_kdm_errors">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Server" role="tabpanel" aria-labelledby="Server-tab">
                            <div id="list_server_errors" class="table-responsive preview-list multiplex">
                                <h5 class="modal-title"> Sever Errors List</h5>
                                <table class="table " id="table_list_server_errors">
                                    <thead>
                                        <tr>
                                            <th>Location</th>
                                            <th> Event Id</th>
                                            <th> Date</th>
                                            <th> Class</th>
                                            <th> Type</th>
                                            <th> SubType</th>
                                            <th> Severity</th>
                                            <th> Error Code</th>
                                            <th> Screen</th>
                                        </tr>
                                    </thead>
                                    <tbody id="header_body_list_server_errors">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Projector" role="tabpanel" aria-labelledby="Projector-tab">
                            <h5 class="modal-title"> Projector Errors List</h5>
                            <table class="table " id="table_list_projector_errors">
                                <thead>
                                    <tr>
                                        <th>Location</th>
                                        <th> Title</th>
                                        <th> Time</th>
                                        <th> Code</th>
                                        <th> Severity</th>
                                        <th> Message</th>
                                        <th> Screen</th>
                                    </tr>
                                </thead>
                                <tbody id="header_body_list_projector_errors">

                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="Sound" role="tabpanel" aria-labelledby="Sound-tab">
                            <div id="list_kdm_errors" class="table-responsive preview-list multiplex">
                                <h5 class="mt-2 mb-2">Sound Errors List</h5>
                                <table class="table " id="header_table_list_sound_errors">
                                    <thead>
                                        <tr>
                                            <th>Location</th>
                                            <th>Alarm Id</th>
                                            <th>Date Saved </th>
                                            <th>Severity</th>
                                            <th>Title</th>
                                            <th>Clearable</th>
                                            <th>Hardware</th>
                                            <th>Screen</th>

                                        </tr>
                                    </thead>
                                    <tbody id="header_body_list_sound_errors">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Storage" role="tabpanel" aria-labelledby="Storage-tab">
                            <div id="list_storage_errors"  class="table-responsive preview-list multiplex">
                                <h5 class="modal-title"> Storage Errors List</h5>
                                <table class="table " id="header_table_list_storage_errors">
                                    <thead>
                                    <tr>
                                        <th> Location</th>
                                        <th> Stat</th>
                                        <th> Screen</th>

                                    </tr>
                                    </thead>
                                    <tbody id="header_body_list_storage_errors">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!--end modal-content-->
        </div>
    </div>


     <!-- Edit  Password -->
     <div id="user_edit_password_modal" class="modal hide fade" role="dialog" aria-labelledby="edit_user_modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered  ">
            <div class="modal-content border-0">
                <div class="modal-header">
                    <h5>Edit Your password</h5>
                    <button type="button" class="btn-close" id="createMemberBtn-close" data-bs-dismiss="modal"
                        aria-label="Close"><span aria-hidden="true"
                            style="color:white;font-size: 26px;line-height: 18px;">×</span></button>
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



    <div class="modal    " id="wait-modal" tabindex="-1" role="dialog" aria-labelledby="loadingModal2Label"aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center" style="min-height: auto; height: 260px; display: flex ">
                    <h5 class="modal-title mb-3">  </h5>
                    <span class="loader"></span>
                </div>
            </div>
        </div>
    </div>


    <!-- plugins:js -->
    <script src="{{ asset('/assets/vendors/js/vendor.bundle.base.js') }}"></script>

    <script src="{{ asset('/assets/vendors/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('/assets/vendors/progressbar.js/progressbar.min.js') }}"></script>
    <script src="{{ asset('/assets/vendors/jvectormap/jquery-jvectormap.min.js') }}"></script>
    <script src="{{ asset('/assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
    <script src="{{ asset('/assets/vendors/owl-carousel-2/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('/assets/js/jquery.cookie.js') }}"></script>

    <script src="{{ asset('/assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('/assets/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('/assets/js/misc.js') }}"></script>
    <script src="{{ asset('/assets/js/settings.js') }}"></script>
    <script src="{{ asset('/assets/js/todolist.js') }}"></script>

    <script src="{{ asset('/assets/js/dashboard.js') }}"></script>
    <script src="{{ asset('/assets/vendors/sweetalert/sweetalert.min.js') }}"></script>

    @yield('custom_script')


    <script>

        // Example starter JavaScript for disabling form submissions if there are invalid fields
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
    </script>


    <script>
        function header_getdata() {

            var url = "{{ url('') }}" + '/get_header_error';
            $.ajax({
                url: url,
                method: 'GET',
                success: function(response) {
                    console.log(response)
                    var data;


                    if (response.total_errors > 0) {
                        $('#notificationDropdown .count').html(response.total_errors)
                        $('#notificationDropdown .count').addClass("bg-warning").removeClass("bg-success");
                    } else {
                        $('#notificationDropdown .count').html('0')
                        $('#notificationDropdown .count').removeClass("bg-warning").addClass("bg-success");
                    }


                    if (response.kdm_errors > 0) {
                        $('#header_kdm_errors').html(response.kdm_errors + ' Kdm Errors Detected ')
                        $('#icon_kdm_errors').css("color", "rgb(255, 93, 93)");
                    } else {
                        $('#header_kdm_errors').html('Healthy')
                        $('#icon_kdm_errors').css("color", "rgb(48, 255, 48)");
                    }
                    if (response.kdm_errors > 0) {
                        $('#header_kdm_errors').html(response.kdm_errors + ' Kdm Errors Detected ')
                        $('#icon_kdm_errors').css("color", "rgb(255, 93, 93)");
                    } else {
                        $('#header_kdm_errors').html('Healthy')
                        $('#icon_kdm_errors').css("color", "rgb(48, 255, 48)");
                    }

                    if (response.nbr_sound_alert > 0) {
                        $('#header_sound_errors').html(response.nbr_sound_alert + ' Sound Errors Detected ')
                        $('#icon_sound_errors').css("color", "rgb(255, 93, 93)");
                    } else {
                        $('#header_sound_errors').html('Healthy')
                        $('#icon_sound_errors').css("color", "rgb(48, 255, 48)");
                    }

                    if (response.nbr_projector_alert > 0) {
                        $('#header_projector_errors').html(response.nbr_projector_alert +
                            ' Projector Errors Detected   ')
                        $('#icon_projector_errors').css("color", "rgb(255, 93, 93)");
                    } else {
                        $('#header_projector_errors').html('Healthy')
                        $('#icon_projector_errors').css("color", "rgb(48, 255, 48)");
                    }

                    if (response.nbr_server_alert > 0) {
                        $('#header_server_errors').html(response.nbr_server_alert + ' Server Errors Detected ')
                        $('#icon_server_errors').css("color", "rgb(255, 93, 93)");
                    } else {
                        $('#header_server_errors').html('Healthy')
                        $('#icon_server_errors').css("color", "rgb(48, 255, 48)");
                    }

                    if (response.nbr_storage_errors > 0) {
                        $('#header_storage_errors').html(response.nbr_storage_errors +
                            ' Storage Errors Detected ')
                        $('#icon_storage_errors').css("color", "rgb(255, 93, 93)");
                    } else {
                        $('#header_storage_errors').html('Healthy')
                        $('#icon_storage_errors').css("color", "rgb(48, 255, 48)");
                    }

                },
                error: function(response) {

                }
            })

        }

        header_getdata();


        $(document).on('click', '.header_kdm_errors_btn, #Kdms-tab', function() {
            var location = $(this).data('location');
            header_get_kdms_errors_list(location)
            $('#error_modal').modal('show')
            $('.nav-tabs #Kdms-tab').tab('show');;
        });

        function header_get_kdms_errors_list(location) {
                var url = "{{ url('') }}" + '/get_kdm_errors_list';
                $.ajax({
                    url: url,
                    data: {
                        location: location,
                    },
                    method: 'GET',
                    success: function(response) {
                        console.log(response)
                        var data;
                        if (response.kdms_errors_list.length > 0) {
                            $.each(response.kdms_errors_list, function(index, kdm) {

                                data +=
                                    '<tr class="odd ">' +
                                    '<td class="sorting_1"> ' + kdm.location.name + '  </td>' +
                                    '<td class="sorting_1"> ' + kdm.cpl_id + '  </td>' +
                                    '<td class="sorting_1"> ' + kdm.annotationText + '  </td>' +
                                    '<td class="sorting_1"> ' + kdm.details + '  </td>' +
                                    '<td class="sorting_1"> ' + kdm.serverName + '  </td>' +
                                    '<td class="sorting_1"> ' + kdm.date_time + '  </td>' +
                                    '</tr>'

                            })

                            $('#header_body_list_kdm_errors').html(data);

                        } else {
                            $('#header_body_list_kdm_errors').html(
                                '<div id="table_logs_processing" class="dataTables_processing card">No data available </div>'
                                );
                        }


                    },
                    error: function(response) {

                    }
                })

        }



        $(document).on('click', '.header_server_errors_btn, #Server-tab', function() {

            var location = $(this).data('location');
            header_get_server_errors_list(location)
            $('#error_modal').modal('show');
            $('.nav-tabs #Server-tab').tab('show');

            //$('#header_server_errors_modal').modal('show');
        });

        function header_get_server_errors_list(location) {

            var url = "{{ url('') }}" + '/get_server_errors_list';
            $.ajax({
                url: url,
                data: {
                    location: location,
                },
                method: 'GET',
                success: function(response) {
                    console.log(response)
                    var data;
                    if (response.server_errors_list.length > 0) {
                        $.each(response.server_errors_list, function(index, server) {
                            data +=
                                '<tr class="odd  ">' +
                                '<td class="sorting_1"> ' + server.location.name + '  </td>' +
                                '<td class="sorting_1"> ' + server.eventId + '  </td>' +
                                '<td class="sorting_1"> ' + server.date + '  </td>' +
                                '<td class="sorting_1" > ' + server.class + '  </td>' +
                                '<td class="sorting_1"> ' + server.type + '  </td>' +
                                '<td class="sorting_1"> ' + server.subType + '  </td>' +
                                '<td class="sorting_1"> ' + server.criticity + '  </td>' +
                                '<td class="sorting_1"> ' + server.errorCode + '  </td>' +
                                '<td class="sorting_1"> ' + server.serverName + '  </td>' +
                                '</tr>'

                        })

                        $('#header_body_list_server_errors').html(data);

                    } else {
                        $('#header_body_list_server_errors').html(
                            '<div id="table_logs_processing" class="dataTables_processing card">No data available </div>'
                            );
                    }


                },
                error: function(response) {

                }
            })
        }

        $(document).on('click', '.header_projector_errors_btn, #Projector-tab', function() {

            var location = $(this).data('location');
            console.log(location)
            header_get_projector_errors_list(location)
            $('#error_modal').modal('show');
            $('.nav-tabs #Projector-tab').tab('show');
            //$('#header_projector_errors_modal').modal('show');
        });

        function header_get_projector_errors_list(location) {
            var url = "{{ url('') }}" + '/get_projector_errors_list';
                $.ajax({
                    url: url,
                    data:
                    {
                        location: location,
                    },
                    method: 'GET',
                    success: function(response)
                    {
                        console.log(response)
                        var data;
                        if (response.projector_errors_list.length > 0) {

                            $.each(response.projector_errors_list, function(index, projector) {
                                data +=
                                    '<tr class="odd ">' +
                                    '<td class="sorting_1"> ' + projector.location.name + '  </td>' +
                                    '<td class="sorting_1"> ' + projector.title + '  </td>' +
                                    '<td class="sorting_1"> ' + projector.time_saved + '  </td>' +
                                    '<td class="sorting_1" > ' + projector.code + '  </td>' +
                                    '<td class="sorting_1"> ' + projector.severity + '  </td>' +
                                    '<td class="sorting_1"> ' + projector.message + '  </td>' +
                                    '<td class="sorting_1"> ' + projector.serverName + '  </td>'

                                    +
                                    '</tr>'

                            })
                            console.log(data);
                            $('#header_body_list_projector_errors').html(data);

                        } else
                        {
                            $('#header_body_list_projector_errors').html(
                                '<div id="table_logs_processing" class="dataTables_processing card">No data available </div>'
                                );
                        }


                    },
                    error: function(response) {

                    }
                })
        }

        $(document).on('click', '.show_storage_errors_details, #Storage-tab', function() {

            var location = $(this).data('location');
            console.log(location)
            header_get_storage_errors_list(location)
            $('#error_modal').modal('show');
            $('.nav-tabs #Storage-tab').tab('show');

            //$('#header_storage_errors_modal').modal('show');
        });
        function header_get_storage_errors_list(location)
        {

            var url = "{{ url('') }}" + '/get_storage_errors_list';
            $.ajax({
                url: url,
                data: {
                    location: location,
                },
                method: 'GET',
                success: function(response) {

                    var data ="";
                    if(response.storage_errors_list.length > 0)
                    {

                        $.each(response.storage_errors_list, function(index, storage) {
                        data +='<tr class="odd ">'
                                +'<td class="sorting_1"> ' + storage.location.name + '  </td>'
                                +'<td class="sorting_1">  Disk Space Quota   </td>'
                                +'<td class="sorting_1"> '+ storage.serverName+'  </td>'
                            +'</tr>'

                        })
                        console.log(data)
                        $('#header_body_list_storage_errors').html(data) ;

                    }
                    else
                    {
                        $('#header_body_list_storage_errors').html('<div id="table_logs_processing" class="dataTables_processing card">No data available </div>') ;
                    }


                },
                error: function(response) {

                }
            })



        }


        $(document).on('click', '.header_sound_errors_btn', function() {

            var location = $(this).data('location');
            console.log(location)
            header_get_sound_errors_list(location)
            $('#error_modal').modal('show');
            $('.nav-tabs #Sound-tab').tab('show');

            //$('#header_storage_errors_modal').modal('show');
        });
        function convertTimestampToDate(timestamp) {
            // Convert the timestamp to an integer (assuming it is in seconds)
            const seconds = Math.floor(timestamp);

            // Create a Date object using the timestamp (in milliseconds)
            const date = new Date(seconds * 1000);

            // Format the date into a string
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
            const day = String(date.getDate()).padStart(2, '0');
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            const secondsStr = String(date.getSeconds()).padStart(2, '0');

            // Return the formatted date string
            return `${year}-${month}-${day} ${hours}:${minutes}:${secondsStr}`;
        }
        function header_get_sound_errors_list(location)
        {

            var url = "{{ url('') }}" + '/get_sound_errors_list';
            $.ajax({
                url: url,
                data: {
                    location: location,
                },
                method: 'GET',
                success: function(response) {

                    var data ;
                    if(response.sounds_errors_list.length > 0)
                    {
                            $.each(response.sounds_errors_list, function(index, sound) {

                            data +=
                                '<tr class="odd ">'
                                    +'<td class="sorting_1"> ' + sound.location.name + '  </td>'
                                    +'<td class="sorting_1"> '+ sound.alarm_id+'  </td>'
                                    +'<td class="sorting_1"> ' + convertTimestampToDate(sound.date_saved)+'  </td>'
                                    +'<td class="sorting_1"> '+ sound.severity+'  </td>'
                                    +'<td class="sorting_1"> '+ sound.title+'  </td>'
                                    +'<td class="sorting_1"> '+ sound.clearable+'  </td>'
                                    +'<td class="sorting_1"> '+ sound.hardware+'  </td>'
                                    +'<td class="sorting_1"> '+ sound.screen+'  </td>'
                                +'</tr>'

                            })

                            $('#header_body_list_sound_errors').html(data) ;

                    }
                    else
                    {
                        $('#header_body_list_sound_errors').html('<div id="table_logs_processing" class="dataTables_processing card">No data available </div>') ;
                    }


                },
                error: function(response) {

                }
            })



        }


        $(document).on('click', '#reset_user_password', function() {
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
                    if(response =="success")
                    {
                        swal({
                            title: 'Done!',
                            text: 'Password Updated Successfully ',
                            icon: 'success',
                            button: {
                                text: "Continue",
                                value: true,
                                visible: true,
                                className: "btn btn-primary"
                            }
                        })
                        $('#user_edit_password_modal').modal('hide') ;
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
    </script>
    <script>
        let content_height = document.querySelector('body').offsetHeight;
        var screenHeight = $(window).height();
        console.log(screenHeight)
        let navbar_height = 200;

        //let footer_height = document.querySelector('.footer').offsetHeight;
        let nav_tabs = document.querySelector('.nav.nav-tabs').offsetHeight  * 2;
        let content_max_height = screenHeight - navbar_height - nav_tabs;
        console.log(nav_tabs)
        console.log(content_max_height)
        $(".multiplex").height(content_max_height);
        $(".modal-body:not(.minauto)").css({"maxHeight":content_max_height-200});


        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })


    </script>
</body>


</html>
