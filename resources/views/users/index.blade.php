@extends('layouts.app')
@section('title')
    Users
@endsection
@section('content')
    <div class="page-header user-shadow">
        <h3 class="page-title ">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">users</li>
            </ol>
        </h3>
        <nav aria-label="breadcrumb">
            <a id="create_user_btn" class="btn btn-primary  btn-icon-text">
                <i class="mdi mdi-plus btn-icon-prepend"></i> Create user
            </a>
        </nav>
    </div>
    <div class="row  ">
        <div class="col-xl-4">
            <div class="input-group mb-2 mr-sm-2">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="mdi mdi-home-map-marker"></i></div>
                    </div>
                    <select class="form-select  form-control form-select-sm" aria-label=".form-select-sm example"
                        id="location" name="location[]" multiple="multiple">
                        @foreach ($locations as $location)
                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="users-listing" class="table table-hover ">
                            <thead>
                                <tr>
                                    <th class="sorting text-center  sorting_asc">Order #</th>
                                    <th class="sorting text-center ">First Name</th>
                                    <th class="sorting text-center ">Last Name</th>
                                    <th class="sorting text-center ">Username</th>
                                    <th class="sorting text-center ">Email</th>
                                    <th class="sorting text-center ">Role</th>
                                    <th class="sorting text-center ">Inventory Approver</th>
                                    <th class="sorting text-center ">option</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $key => $user)
                                    <tr class="odd text-center  ">
                                        <td class="sorting_1">{{ $key + 1 }} </td>
                                        <td> {{ $user->name }} </td>
                                        <td> {{ $user->last_name }} </td>
                                        <td> {{ $user->username }} </td>
                                        <td>{{ $user->email }} </td>
                                        <td>@if( $user->role =="1") Admin @else Manager @endif
                                        <td>@if( $user->inventory_approver) Can Approuve  @else Cannot Approve @endif
                                        </td>



                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-outline-primary dropdown-toggle" type="button"
                                                    id="dropdownMenuOutlineButton6" data-bs-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false"> Actions </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuOutlineButton6"
                                                    style="">
                                                    <a class="btn btn-outline-primary dropdown-item edit_user"
                                                        id="{{ $user->id }}">Edit</a>
                                                    <a class="btn btn-outline-primary dropdown-item edit_password_btn"
                                                        id="{{ $user->id }}">Edit password</a>
                                                    <a class="btn btn-outline-primary dropdown-item delete_user"
                                                        id="{{ $user->id }}">Delete</a>
                                                    <a class="btn btn-outline-primary dropdown-item show_locations"
                                                        id="{{ $user->id }}">Show Locations</a>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <div id="create_user_modal" class="modal hide fade" role="dialog" aria-labelledby="edit_user_modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered   modal-xl">
            <div class="modal-content border-0">
                <div class="modal-header">
                    <h5>Create User</h5>
                    <button type="button" class="btn-close" id="createMemberBtn-close" data-bs-dismiss="modal"
                        aria-label="Close"><span aria-hidden="true"
                            style="color:white;font-size: 26px;line-height: 18px;">×</span></button>
                </div>
                <div class="modal-body text-center p-4">
                    <div class="">
                        <form method="POST" id="create_user_form" class="needs-validation" novalidate action="{{ route('users.store') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group mb-2 mr-sm-2">
                                         <label class="w-100 " style="text-align: left"> Locations </label>
                                        <div class="input-group row">
                                            <div class="col-md-9" style="padding: 0">
                                                <select class="form-select  form-control form-select-sm"
                                                    aria-label=".form-select-sm example" id="location_create_user" name="location[]"
                                                    multiple="multiple" required>


                                                    @foreach ($locations as $location)
                                                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label>
                                                    <input type="checkbox" id="toggleSelectAll_create_location"> Select All
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group  has-validation">
                                         <label class="w-100 " style="text-align: left"> First Name</label>
                                        <input type="text" class="form-control" placeholder="First Name"
                                            value="{{ old('name') }}" name="name" required id="name">
                                        @error('name')
                                            <div class="text-danger mt-1 ">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group  has-validation">
                                         <label class="w-100 " style="text-align: left"> Last Name</label>
                                        <input type="text" class="form-control" placeholder="Last Name"
                                            value="{{ old('last_name') }}" name="last_name" required id="last_name">
                                        @error('last_name')
                                            <div class="text-danger mt-1 ">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="w-100 " style="text-align: left">Username</label>
                                        <input type="text" class="form-control" placeholder="Username"
                                            value="{{ old('username') }}" name="username" required id="username">
                                        @error('username')
                                            <div class="text-danger mt-1 ">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                         <label class="w-100 " style="text-align: left">Email</label>
                                        <input type="Email" class="form-control" placeholder="Email"
                                            value="{{ old('email') }}" name="email" required id="email">
                                        @error('email')
                                            <div class="text-danger mt-1 ">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                         <label class="w-100 " style="text-align: left">Password</label>
                                        <input type="password" class="form-control" placeholder="Password"
                                            value="{{ old('password') }}" name="password" required id="password">
                                        @error('password')
                                            <div class="text-danger mt-1 ">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                         <label class="w-100 " style="text-align: left"> Confirm Password </label>
                                        <input type="password" class="form-control" placeholder=" Confirm Password "
                                            value="{{ old('confirm_password') }}" name="confirm_password" id="confirm_password" required>
                                        @error('confirm_password')
                                            <div class="text-danger mt-1 ">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group mb-2 mr-sm-2">
                                        <label class="w-100 " style="text-align: left"> Role </label>
                                        <select class="form-select  form-control form-select-sm" aria-label=".form-select-sm example" name="role" id="role" required>
                                            <option selected="" value>Role</option>
                                                    <option value="1">Admin</option>
                                                    <option value="2">Manager</option>
                                                    <option value="3">Cinema Staff</option>
                                            </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group mb-2 mr-sm-2">
                                        <label class="w-100 " style="text-align: left"> Inventory Approver </label>
                                        <select class="form-select  form-control form-select-sm" aria-label=".form-select-sm example" name="inventory_approver" id="inventory_approver" required>
                                            <option selected="" value>Inventory Approver</option>
                                                    <option value=1>Can Approuve</option>
                                                    <option value=0>Cannot Approve</option>
                                            </select>
                                    </div>
                                </div>



                                <div class=" m-2">
                                    <button type="submit" class="btn btn-success me-2">Submit</button>
                                    <button class="btn btn-dark" data-bs-dismiss="modal" aria-label="Close" type="button" >Cancel</button>

                                </div>


                                <br />
                                <br />
                                <br />
                            </div>
                        </form>
                    </div>

                </div>
            </div>
            <!--end modal-content-->
        </div>
    </div>


    <!-- Edit  user -->
    <div id="edit_user_modal" class="modal hide fade" role="dialog" aria-labelledby="edit_user_modal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl ">
            <div class="modal-content border-0">
                <div class="modal-header">
                    <h5>Edit User</h5>
                    <button type="button" class="btn-close" id="createMemberBtn-close" data-bs-dismiss="modal"
                        aria-label="Close"><span aria-hidden="true"
                            style="color:white;font-size: 26px;line-height: 18px;">×</span></button>
                </div>
                <div class="modal-body minauto text-center p-4">
                    <div class="">
                        <form method="PUT" class="needs-validation" novalidate id="edit_user_form">
                            @csrf
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="input-group mb-2 mr-sm-2">
                                        <label class="w-100 " style="text-align: left"> Locations </label>
                                        <div class="input-group row">
                                            <div class="col-md-9"  style="padding: 0">
                                            <select class="form-select  form-control form-select-sm"
                                                aria-label=".form-select-sm example" id="location_edit_user"
                                                name="location[]" multiple="multiple" required>


                                            </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label>
                                                    <input type="checkbox" id="toggleSelectAll_edit_location"> Select All
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group  has-validation">
                                        <label class="w-100 " style="text-align: left"> Name</label>
                                        <input id="name" type="text" class="form-control"
                                            placeholder="User Name" value="{{ old('name') }}" name="name" required id="name">
                                        @error('name')
                                            <div class="text-danger mt-1 ">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group  has-validation">
                                         <label class="w-100 " style="text-align: left"> Last Name</label>
                                        <input type="text" class="form-control" placeholder="Last Name"
                                            value="{{ old('last_name') }}" name="last_name" required id="last_name">
                                        @error('last_name')
                                            <div class="text-danger mt-1 ">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="w-100 " style="text-align: left">Username</label>
                                        <input id="username" type="text" class="form-control" placeholder="Username"
                                            value="{{ old('username') }}" name="username"  required>
                                        @error('username')
                                            <div class="text-danger mt-1 ">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="w-100 " style="text-align: left">Email</label>
                                        <input id="Email" type="Email" class="form-control" placeholder="Email"
                                            value="{{ old('email') }}" name="email" required>
                                        @error('email')
                                            <div class="text-danger mt-1 ">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group mb-2 mr-sm-2">
                                        <label class="w-100 " style="text-align: left"> Role </label>
                                        <select id="role" class="form-select  form-control form-select-sm" id="role" aria-label=".form-select-sm example" name="role" required>
                                                <option value>Role</option>
                                                <option value="1">Admin</option>
                                                <option value="2">Manager</option>
                                                <option value="3">Cinema Staff</option>
                                            </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group mb-2 mr-sm-2">
                                        <label class="w-100 " style="text-align: left"> Inventory Approver </label>
                                        <select  class="form-select  form-control form-select-sm" id="inventory_approver" aria-label=".form-select-sm example" name="inventory_approver" required>
                                                <option value>Inventory Approver</option>
                                                <option value="1">Can Approuve</option>
                                                <option value="0">Cannot Approve</option>
                                            </select>
                                    </div>
                                </div>



                                <div class=" m-2">
                                    <button type="submit" class="btn btn-success me-2" id="submit_update">Submit</button>
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

    <!-- Edit  Password -->
    <div id="edit_password_modal" class="modal hide fade" role="dialog" aria-labelledby="edit_user_modal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered  ">
            <div class="modal-content border-0">
                <div class="modal-header">
                    <h5>Edit password</h5>
                    <button type="button" class="btn-close" id="createMemberBtn-close" data-bs-dismiss="modal"
                        aria-label="Close"><span aria-hidden="true"
                            style="color:white;font-size: 26px;line-height: 18px;">×</span></button>
                </div>
                <div class="modal-body minauto text-center p-4">
                    <div class="">
                        <form method="PUT" class="needs-validation" novalidate id="edit_password_form">
                            @csrf
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="w-100 " style="text-align: left">Password</label>
                                        <input id="password" type="password" class="form-control"
                                            placeholder="Password" value="{{ old('password') }}" name="password"
                                            required>
                                        @error('password')
                                            <div class="text-danger mt-1 ">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <input type="hidden" id="id_user_password" value>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="w-100 " style="text-align: left"> Confirm Password </label>
                                        <input id="confirm_password" type="password" class="form-control"
                                            placeholder=" Confirm Password " value="{{ old('confirm_password') }}"
                                            name="confirm_password" required>
                                        @error('confirm_password')
                                            <div class="text-danger mt-1 ">{{ $message }}</div>
                                        @enderror
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
    <div id="show_location_modal" class="modal hide fade" role="dialog" aria-labelledby="edit_user_modal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered  ">
            <div class="modal-content border-0">
                <div class="modal-header">
                    <h5>Locations</h5>
                    <button type="button" class="btn-close" id="createMemberBtn-close" data-bs-dismiss="modal"
                        aria-label="Close"><span aria-hidden="true"
                            style="color:white;font-size: 26px;line-height: 18px;">×</span></button>
                </div>
                <div class="modal-body minauto text-center p-4">
                    <div class="">

                    </div>

                </div>
            </div>
            <!--end modal-content-->
        </div>
    </div>

    <div class=" modal fade " id="create_user_infos" tabindex="-1" role="dialog"  aria-labelledby="delete_client_modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered  modal-xl">
            <div class="modal-content border-0">
                <div class="modal-header">
                    <h4>User Created infos </h4>
                    <button type="button" class="btn-close" id="createMemberBtn-close" data-bs-dismiss="modal"
                        aria-label="Close"><span aria-hidden="true"
                            style="color:white;font-size: 26px;line-height: 18px;">×</span></button>
                </div>
                <div class="modal-body  p-4">


                </div>


            </div>
        <!--end modal-content-->
        </div>
    </div>

    <div class=" modal fade " id="update_password_infos" tabindex="-1" role="dialog"  aria-labelledby="delete_client_modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered  modal-xl">
            <div class="modal-content border-0">
                <div class="modal-header">
                    <h4>Update password  infos </h4>
                    <button type="button" class="btn-close" id="createMemberBtn-close" data-bs-dismiss="modal"
                        aria-label="Close"><span aria-hidden="true"
                            style="color:white;font-size: 26px;line-height: 18px;">×</span></button>
                </div>
                <div class="modal-body  p-4">


                </div>


            </div>
        <!--end modal-content-->
        </div>
    </div>

    <div class=" modal fade " id="delete_user_infos" tabindex="-1" role="dialog"  aria-labelledby="delete_client_modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered  modal-xl">
            <div class="modal-content border-0">
                <div class="modal-header">
                    <h4>Delete user   infos </h4>
                    <button type="button" class="btn-close" id="createMemberBtn-close" data-bs-dismiss="modal"
                        aria-label="Close"><span aria-hidden="true"
                            style="color:white;font-size: 26px;line-height: 18px;">×</span></button>
                </div>
                <div class="modal-body  p-4">


                </div>


            </div>
        <!--end modal-content-->
        </div>
    </div>

@endsection

@section('custom_script')
    <!-- ------- DATA TABLE ---- -->
    <script src="{{ asset('/assets/vendors/datatables.net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <script>

        // Example starter JavaScript for disabling form submissions if there are invalid fields
            (function () {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = $( "#edit_user_form, #edit_password_form, #create_user_form" )

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


        $(document).ready(function() {
            $('#location').select2({
                placeholder: "Select a location",
                //allowClear: true,


            });

            $('#location_edit_user').select2({
                placeholder: "Select a location",
                //allowClear: true
            });

            $('#location_create_user').select2({
                placeholder: "Select a location",
                //allowClear: true
            });

            $('#toggleSelectAll_create_location').change(function() {
                if (this.checked) {
                    // Si la checkbox est cochée, sélectionne toutes les options
                    var allOptions = $('#location_create_user option').map(function() {
                        return $(this).val();
                    }).get();

                    $('#location_create_user').val(allOptions).trigger('change'); // Mettre à jour Select2
                } else {
                    // Si la checkbox est décochée, désélectionne toutes les options
                    $('#location_create_user').val(null).trigger('change'); // Mettre à jour Select2
                }
            });

            $('#toggleSelectAll_edit_location').change(function() {
                if (this.checked) {
                    // Si la checkbox est cochée, sélectionne toutes les options
                    var allOptions = $('#location_edit_user option').map(function() {
                        return $(this).val();
                    }).get();

                    $('#location_edit_user').val(allOptions).trigger('change'); // Mettre à jour Select2
                } else {
                    // Si la checkbox est décochée, désélectionne toutes les options
                    $('#location_edit_user').val(null).trigger('change'); // Mettre à jour Select2
                }
            });

        });
    </script>

    <script>
        (function($) {
            'use strict';
            $(function() {
                $('#location-listing').DataTable({
                    "aLengthMenu": [
                        [5, 10, 15, -1],
                        [5, 10, 15, "All"]
                    ],
                    "iDisplayLength": 10,
                    "language": {
                        search: "_INPUT_",
                        searchPlaceholder: "Search..."
                    }
                });

            });
        })(jQuery);
    </script>
    <!-- -------END  DATA TABLE ---- -->


    <script src="{{ asset('/assets/vendors/jquery-toast-plugin/jquery.toast.min.js') }}"></script>
    <script src="{{ asset('/assets/vendors/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('/assets/js/select2.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#location').select2({
                placeholder: "Select a location",
                allowClear: true
            });
        });
    </script>


    <script src="{{ asset('/assets/vendors/jquery-toast-plugin/jquery.toast.min.js') }}"></script>
    <script>
        (function($) {

            @if (session('message'))

                $.toast({
                    heading: 'Success',
                    text: '{{ session('message') }}',
                    showHideTransition: 'slide',
                    icon: 'success',
                    loaderBg: '#f96868',
                    position: 'top-right',
                    timeout: 5000
                })
            @endif
        })(jQuery);
    </script>




    <script>
        (function($) {
            'use strict';
            $(function() {
                $('#order-listing').DataTable({
                    "aLengthMenu": [
                        [5, 10, 15, -1],
                        [5, 10, 15, "All"]
                    ],
                    "iDisplayLength": 10,
                    "language": {
                        search: ""
                    }
                });
                $('#order-listing').each(function() {
                    var datatable = $(this);
                    // SEARCH - Add the placeholder for Search and Turn this into in-line form control
                    var search_input = datatable.closest('.dataTables_wrapper').find(
                        'div[id$=_filter] input');
                    search_input.attr('placeholder', 'Search');
                    search_input.removeClass('form-control-sm');
                    // LENGTH - Inline-Form control
                    var length_sel = datatable.closest('.dataTables_wrapper').find(
                        'div[id$=_length] select');
                    length_sel.removeClass('form-control-sm');
                });
            });
        })(jQuery);

        function checkEmailExists(emailInput,email) {

            $.ajax({
                        url: '{{ url('') }}' + '/user/check_email', // Assurez-vous que la route est définie correctement
                        type: 'POST',
                        data: {
                            email: email,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            console.log(response)
                            if (response.exists) {
                                // Email already exists

                                $(emailInput).addClass('is-invalid');
                                $(emailInput).next('.invalid-feedback').remove(); // Remove any existing feedback
                                $(emailInput).after('<div class="invalid-feedback">This email is already  used.</div>');
                                $('button[type="submit"]').prop('disabled', true);
                               return false ;
                            } else {
                                // Email does not exist, proceed with form submission
                                $(emailInput).removeClass('is-invalid');
                                $(emailInput).addClass('is-valid');
                                $(emailInput).next('.invalid-feedback').remove();
                                $('button[type="submit"]').prop('disabled', false);
                                return true ;
                            }
                        },
                        error: function(xhr) {
                            return false ;
                            console.log(xhr.responseText);
                        }
                    });

        }


        function checkUsernameExists(usernameInput,username) {

            $.ajax({
                        url: '{{ url('') }}' + '/user/check_username', // Assurez-vous que la route est définie correctement
                        type: 'POST',
                        data: {
                            username: username,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            console.log(response)
                            if (response.exists) {
                                // Email already exists

                                $(usernameInput).addClass('is-invalid');
                                $(usernameInput).next('.invalid-feedback').remove(); // Remove any existing feedback
                                $(usernameInput).after('<div class="invalid-feedback">This username is already used.</div>');
                                $('button[type="submit"]').prop('disabled', true);
                            return false ;
                            } else {
                                // Email does not exist, proceed with form submission
                                $(usernameInput).removeClass('is-invalid');
                                $(usernameInput).addClass('is-valid');
                                $(usernameInput).next('.invalid-feedback').remove();
                                $('button[type="submit"]').prop('disabled', false);
                                return true ;
                            }
                        },
                        error: function(xhr) {
                            return false ;
                            console.log(xhr.responseText);
                        }
                    });

        }


        var timer;
        $('#email').on('keyup', function() {
            var emailInput = $(this);
            var email = emailInput.val();
            clearTimeout(timer);

            if (email.length > 7) {
                timer = setTimeout(function() {
                    checkEmailExists(emailInput, email);
                }, 1000);
            }
        });

        $('#username').on('keyup', function() {
            var usernameInput = $(this);
            var username = usernameInput.val();


            clearTimeout(timer);

            if (username.length >2) {

                timer = setTimeout(function() {
                   checkUsernameExists(usernameInput, username);
                }, 1000);
            }
        });



        function get_users(location) {

            $("#users-listing").dataTable().fnDestroy();
            var loader_content =
                '<div class="jumping-dots-loader">' +
                '<span></span>' +
                '<span></span>' +
                '<span></span>' +
                '</div>'
            $('#users-listing tbody').html(loader_content)

            var url = "{{ url('') }}" + '/get_users/';
            var result = " ";

            $.ajax({
                url: url,
                method: 'GET',
                data: {
                    location: location,
                },
                success: function(response) {

                    $.each(response.users, function(index, value) {
                        var user_locations = "";

                        $.each(value.locations, function(index, value) {
                            console.log(value)
                            user_locations += '<div class="badge badge-outline-primary m-1">' +
                                value.name + '</div>';
                        })

                        index++;
                        var role="";
                        if(value.role ==1)
                        {
                            role="Admin";
                        }
                        else
                        {
                            role="Manager";
                        }

                        if(value.inventory_approver )
                        {
                            inventory_approver="Can Approuve";
                        }
                        else
                        {
                            inventory_approver="Cannot Approve";
                        }
                        result = result +
                            '<tr class="odd text-center">' +
                            '<td class="text-body align-middle fw-medium text-decoration-none">' +
                            index + ' </td>' +
                            '<td class="text-body align-middle fw-medium text-decoration-none">' + value
                            .name + ' </td>' +
                            '<td class="text-body align-middle fw-medium text-decoration-none">' + value
                            .last_name + ' </td>' +
                            '<td class="text-body align-middle fw-medium text-decoration-none">' + value
                            .username + ' </td>' +
                            '<td class="text-body align-middle fw-medium text-decoration-none">' + value
                            .email + ' </td>' +
                            '<td class="text-body align-middle fw-medium text-decoration-none">' + role + ' </td>' +
                            '<td class="text-body align-middle fw-medium text-decoration-none">' + inventory_approver + ' </td>' +
                            '<td class="text-body align-middle fw-medium text-decoration-none">' +
                            '<div class="dropdown">' +
                            '<button class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuOutlineButton6" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Actions </button>' +
                            '<div class="dropdown-menu" aria-labelledby="dropdownMenuOutlineButton6" style="">' +
                            '<a class="btn btn-outline-primary dropdown-item edit_user" id="' + value
                            .id + '">Edit</a>' +
                            '<a class="btn btn-outline-primary dropdown-item edit_password_btn" id="' + value
                            .id + '">Edit Password</a>' +
                            '<a class="btn btn-outline-primary dropdown-item delete_user" id="' + value
                            .id + '">Delete</a>' +
                            '<a class="btn btn-outline-primary dropdown-item show_locations" id="' + value
                            .id + '">Show Locations</a>' +
                            '</div>' +
                            '</div>' +
                            '</td>' +
                            '</tr>';
                    });
                    $('#users-listing tbody').html(result)

                    /***** refresh datatable **** **/

                    var spl_datatable = $('#users-listing').DataTable({
                        "iDisplayLength": 10,
                        destroy: true,
                        "bDestroy": true,
                        "language": {
                            search: "_INPUT_",
                            searchPlaceholder: "Search..."
                        }
                    });

                },
                error: function(response) {

                }
            })

        }
        $('#location').change(function() {
            var location = $('#location').val();
            get_users(location)
        });

        //Delete NOC SPLs
        $(document).on('click', '.delete_user', function() {

            var id = $(this).attr('id');
            var url = '{{ url('') }}' + '/user/' + id + '/destroy';
            var location = $('#location').val();

            swal({
                showCancelButton: true,
                title: 'User Deletion!',
                text: 'You are sure you want to delete this User',
                icon: 'warning',
                buttons: {
                    cancel: {
                        text: "Cancel",
                        value: null,
                        visible: true,
                        className: "btn btn-primary",
                        closeModal: true,
                    },

                    Confirm: {
                        text: "Yes, delete it!",
                        value: true,
                        visible: true,
                        className: "btn btn-danger",
                        closeModal: true,
                    },
                }
            }).then((result) => {
                console.log(result)
                if (result) {

                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        method: 'DELETE',
                        data: {
                            id: id,
                            "_token": "{{ csrf_token() }}",
                        },
                        beforeSend: function () {
                            $("#wait-modal").modal('show');
                        },

                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            "_token": "{{ csrf_token() }}",
                        },


                        success: function(response) {

                            $("#wait-modal").modal('hide');
                            if (response.success_infos.length> 0 || response.errors_infos.length > 0)
                            {
                                result = "";
                                if (response.errors_infos.length> 0 )
                                {
                                    result = "<h4> Failed Delete user On TMS </h4>" ;
                                    $.each(response.errors_infos, function( index, value ) {

                                        result = result
                                        +'<p>'
                                            +'<span class="align-middle fw-medium text-danger ">'+value.location+' </span>'
                                            +'<span class="align-middle fw-medium text-danger ">'+value.message+' </span>'
                                        +'</p>';
                                    });
                                }

                                if (response.success_infos.length> 0 )
                                {
                                    result = result + "<h4> Delete User on TMS   successfully </h4>" ;
                                    $.each(response.success_infos, function( index, value ) {

                                        result = result
                                        +'<p>'
                                            +'<span class="align-middle fw-medium text-success">'+value.location+' </span>'
                                            +'<span class="align-middle fw-medium text-success">'+value.message+' </span>'
                                        +'</p>';
                                    });
                                }

                                get_users(location)

                                $('#delete_user_infos .modal-body').html(result) ;
                                $('#delete_user_infos').modal('show') ;

                            } else
                            {

                                get_users(location)
                                swal({
                                    title: 'Done!',
                                    text: 'User Deleted Successfully ',
                                    icon: 'success',
                                    button: {
                                        text: "Continue",
                                        value: true,
                                        visible: true,
                                        className: "btn btn-primary"
                                    }
                                })


                            }

                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            swal.close();
                            showSwal('warning-message-and-cancel');

                            //console.log(response) ;
                        }
                    })


                }

            })

        })

        $(document).on('click', '.show_locations', function() {

            var id = $(this).attr('id');
            var url = '{{ url('') }}' + '/user/' + id + '/show_locations';

            $.ajax({
                url: url,
                type: 'GET',
                method: 'GET',
                data: {
                    id: id,
                    "_token": "{{ csrf_token() }}",
                },

                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    "_token": "{{ csrf_token() }}",
                },
                beforeSend: function() {
                    swal({
                        title: 'Refreshing',
                        allowEscapeKey: false,
                        allowOutsideClick: true,
                        onOpen: () => {
                            swal.showLoading();
                        }
                    });
                },
                success: function(response) {
                    result = "<div style='    text-align: left;'>" ;
                    swal.close();
                    $.each(response.locations, function(index, value) {

                        result = result + '<div class="badge badge-outline-primary m-1"> ' + value.name + ' </div>'

                    });
                    result = result +"</div>" ;

                    $('#show_location_modal .modal-body').html(result)
                    $('#show_location_modal').modal('show');


                },
                error: function(jqXHR, textStatus, errorThrown) {
                    swal.close();
                    showSwal('warning-message-and-cancel');

                    //console.log(response) ;
                }
            })


        })

        $(document).on('click', '.edit_user', function() {

            var id = $(this).attr('id');
            var url = '{{ url('') }}' + '/user/' + id + '/show';
            var location = $('#location').val();
            var location_option =""  ;
            var selected = false ;

            $('#edit_user_modal').modal('show');

            $('#location_edit_user').select2({
                placeholder: "Select a location",
                allowClear: true
            });



            $.ajax({
                url: url,
                type: 'GET',
                method: 'GET',
                data: {
                    id: id,
                    "_token": "{{ csrf_token() }}",
                },
                beforeSend: function () {
                    $("#wait-modal").modal('show');
                },


                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    "_token": "{{ csrf_token() }}",
                },
                success: function(response) {
                    console.log(response)
                    $("#wait-modal").modal('hide');
                    $('#edit_user_modal #name').val(response.user.name)
                    $('#edit_user_modal #last_name').val(response.user.last_name)
                    $('#edit_user_modal #Email').val(response.user.email)
                    $('#edit_user_modal #username').val(response.user.username)

                    $('#edit_user_modal #location').select2({
                        placeholder: "Select a location",
                        allowClear: true
                    });

                    $("#edit_user_modal #role option[value="+ response.user.role+"]").prop("selected", true)
                    console.log(String(response.user.inventory_approver))
                    $('#edit_user_modal #inventory_approver').val(String(response.user.inventory_approver));

                    console.log( response.user.inventory_approver)

                    $.each(response.locations, function(index, value) {

                        $.each(response.user.locations, function(index, selected_element) {
                            if(selected_element.id === value.id)
                            {
                                selected = true ;
                            }
                        })
                        if(selected)
                        {
                            location_option  =location_option
                            +'<option selected value="'+value.id+'">'+value.name+'</option>' ;
                        }
                        else
                        {
                            location_option  =location_option
                            +'<option value="'+value.id+'">'+value.name+'</option>' ;
                        }
                        selected = false ;
                    })
                    $('#edit_user_modal #location_edit_user').html(location_option)

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(response);
                }
            })
        })

        $(document).on('click', '#create_user_btn', function() {
            $('#create_user_modal').modal('show');
        })

        $(document).on("submit","#edit_user_form" , function(event) {

            event.preventDefault();


            var name = $('#edit_user_form #name').val();
            var last_name = $('#edit_user_form #last_name').val();
            var username = $('#edit_user_form #username').val();
            console.log('user name : ' + username)
            var email = $('#edit_user_form #Email').val();
            var role = $('#edit_user_form #role').val();
            var inventory_approver = $('#edit_user_form #inventory_approver').val();
            var location = $('#edit_user_form #location_edit_user').val();
            var all_location = $('#edit_user_form #location').val();
            var url = '{{ url('') }}' + '/user/update';

            //$('#edit_user_modal').modal('show');
            $.ajax({
                url: url,
                type: 'PUT',
                method: 'PUT',
                data: {
                    url: url,
                    name: name,
                    last_name: last_name,
                    email: email,
                    role: role,
                    inventory_approver: inventory_approver,
                    location: location,
                    username:username,
                    "_token": "{{ csrf_token() }}",
                },
                beforeSend: function () {
                    $("#wait-modal").modal('show');
                },

                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    "_token": "{{ csrf_token() }}",
                },
                success: function(response) {
                    $("#wait-modal").modal('hide');
                    get_users(all_location)
                        swal({
                            title: 'Done!',
                            text: 'User Updated Successfully ',
                            icon: 'success',
                            button: {
                                text: "Continue",
                                value: true,
                                visible: true,
                                className: "btn btn-primary"
                            }
                        })
                        $('#edit_user_modal').modal('hide') ;
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(response);
                }
            })


        })

        $(document).on("submit","#create_user_form" , function(event) {

            event.preventDefault();
            var email = $('#create_user_form #email').val();

            var name = $('#create_user_form #name').val();
            var last_name = $('#create_user_form #last_name').val();
            var username = $('#create_user_form #username').val();

            var password = $('#create_user_form #password').val();
            var role = $('#create_user_form #role').val();
            var location = $('#create_user_form #location_create_user').val();

            var url = '{{ url('') }}' + '/user/store';
            var all_location = $('#edit_user_form #location').val();
            //$('#edit_user_modal').modal('show');
            $.ajax({
                url: url,
                type: 'POST',
                method: 'POST',
                data: {
                    url: url,
                    name: name,
                    last_name: last_name,
                    email:email,
                    role: role,
                    location: location,
                    username:username,
                    password:password,
                    "_token": "{{ csrf_token() }}",
                },

                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    "_token": "{{ csrf_token() }}",
                },
                beforeSend: function () {
                    $("#wait-modal").modal('show');
                },
                success: function(response) {

                    $("#wait-modal").modal('hide');
                    if (response.created_success.length> 0 || response.created_errors.length > 0)
                        {
                            result = "";
                            $('#create_user_infos').modal('show') ;
                            if (response.created_errors.length> 0 )
                            {
                                result = "<h4> Failed Created Users On TMS </h4>" ;
                                $.each(response.created_errors, function( index, value ) {

                                    result = result
                                    +'<p>'
                                        +'<span class="align-middle fw-medium text-danger ">'+value.location+' </span>'
                                        +'<span class="align-middle fw-medium text-danger ">'+value.message+' </span>'
                                    +'</p>';
                                });
                            }

                            if (response.created_success.length> 0 )
                            {
                                result = result + "<h4> Users Created on TMS   successfully </h4>" ;
                                $.each(response.created_success, function( index, value ) {

                                    result = result
                                    +'<p>'
                                        +'<span class="align-middle fw-medium text-success">'+value.location+' </span>'
                                        +'<span class="align-middle fw-medium text-success">'+value.message+' </span>'
                                    +'</p>';
                                });
                            }

                            get_users(all_location)
                            //showSwal('warning-message-and-cancel')
                            $('#create_user_modal').modal('hide') ;
                            $('#create_user_infos .modal-body').html(result) ;


                        } else {

                            get_users(all_location)
                            swal({
                                title: 'Done!',
                                text: 'User Created Successfully ',
                                icon: 'success',
                                button: {
                                    text: "Continue",
                                    value: true,
                                    visible: true,
                                    className: "btn btn-primary"
                                }
                            })
                            $('#create_user_modal').modal('hide') ;

                        }

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(response);
                }
            })





        })



        $(document).on('click', '.edit_password_btn', function() {
            var id = $(this).attr('id');
            $('#id_user_password').val(id)
            $('#edit_password_modal').modal('show');
        })
        $(document).on("submit","#edit_password_form" , function(event) {
            event.preventDefault();
            var id = $('#id_user_password').val();
            var password = $('#edit_password_form #password').val();
            var url = '{{ url('') }}' + '/user/update_password';
            var all_location = $('#location').val();
            //$('#edit_user_modal').modal('show');
            $.ajax({
                url: url,
                type: 'PUT',
                method: 'PUT',
                data: {
                    id: id,
                    password: password,

                    "_token": "{{ csrf_token() }}",
                },

                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    "_token": "{{ csrf_token() }}",
                },
                beforeSend: function () {
                    $("#wait-modal").modal('show');
                },
                success: function(response) {
                    $("#wait-modal").modal('hide');
                    if (response.success_infos.length> 0 || response.errors_infos.length > 0)
                    {
                        result = "";
                        if (response.errors_infos.length> 0 )
                        {
                            result = "<h4> Failed Users Password Updated  On TMS </h4>" ;
                            $.each(response.errors_infos, function( index, value ) {

                                result = result
                                +'<p>'
                                    +'<span class="align-middle fw-medium text-danger ">'+value.location+' </span>'
                                    +'<span class="align-middle fw-medium text-danger ">'+value.message+' </span>'
                                +'</p>';
                            });
                        }

                        if (response.success_infos.length> 0 )
                        {
                            result = result + "<h4> Users Password Updated on TMS   successfully </h4>" ;
                            $.each(response.success_infos, function( index, value ) {

                                result = result
                                +'<p>'
                                    +'<span class="align-middle fw-medium text-success">'+value.location+' </span>'
                                    +'<span class="align-middle fw-medium text-success">'+value.message+' </span>'
                                +'</p>';
                            });
                        }

                        get_users(all_location)
                        $('#edit_password_modal').modal('hide') ;
                        $('#update_password_infos .modal-body').html(result) ;
                        $('#update_password_infos').modal('show') ;

                    } else
                    {

                        get_users(all_location)
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
                        $('#edit_password_modal').modal('hide') ;

                    }


                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(response);
                }
            })

        })
        // fix page hight
        $('#edit_user_modal , #create_user_modal').on('hidden.bs.modal', function () {
            let $modal = $(this);

            // Réinitialiser les champs input et textarea
            $modal.find('input, textarea').val('');

            // Réinitialiser les selects (y compris Select2)
            $modal.find('select').each(function () {
                $(this).val(null).trigger('change'); // Pour Select2
                $(this).prop('selectedIndex', 0); // Pour les selects normaux
            });

            // Supprimer les classes de validation Bootstrap
            $modal.find('.is-invalid, .is-valid').removeClass('is-invalid is-valid');

            // Supprimer les messages d'erreur Bootstrap
            $modal.find('.invalid-feedback, .valid-feedback').hide();

            // Supprimer la classe "was-validated" du <form> s'il existe
            $modal.find('form').removeClass('was-validated');
        });
        var t = $(window).height();
        $("#content_page").css("height", t - 300);
        $("#content_page").css("max-height", t - 300);
        $("#content_page").css("overflow-y", 'auto');
    </script>
@endsection

@section('custom_css')
    <link rel="stylesheet" href="{{ asset('/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/vendors/jquery-toast-plugin/jquery.toast.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* ***** Select 2 **** */

        .select2.select2-container.select2-container--default {
            width: 90% !important;
            background: #2a3038;
        }

        .select2-container--default .select2-selection--multiple {
            border: none;
            background: #2a3038;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice,
        .select2-container--default .select2-selection--multiple .select2-selection__choice:nth-child(5n+1) {
            font-size: 14px;
            background: #2a3038;
        }

        .select2-container--default .select2-results__option--selected {
            background-color: #297eee;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            padding: 5px;
            padding-left: 21px;

        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            padding: 5px;
        }

        .select2-container--open .select2-dropdown--below {
            z-index: 999999999;
        }

        .select2-container .select2-selection--multiple .select2-selection__rendered {
            width: 100%;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice,
        .select2-container--default .select2-selection--multiple .select2-selection__choice:nth-child(5n+1) {
            float: left;
        }

        #edit_user_modal .select2.select2-container.select2-container--default {
            width: 100% !important;
            background: #2a3038;
        }
        #create_user_modal .select2.select2-container.select2-container--default ,
        #edit_user_modal .select2.select2-container.select2-container--default
        {
            width: 100% !important;

        }

    </style>
@endsection
