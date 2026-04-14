@extends('admin.layouts.app')
@section('title')
    users
@endsection

@section('custom_css')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/dist/css/select2.min.css') }}">
    <style>
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            min-height: 38px;
            padding: 2px 6px;
        }
    </style>
@endsection

@section('content')
    <div class="">

        <div class="card card-body py-3">
            <div class="row align-items-center">
                <div class="col-12">
                    <div class="d-sm-flex align-items-center justify-space-between">
                        <h4 class="mb-4 mb-sm-0 card-title">users</h4>
                        <nav aria-label="breadcrumb" class="ms-auto">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item" aria-current="page">
                                    <button class="btn bg-success  text-white " id="create_user">
                                        + New user
                                    </button>
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>


        <div class="card">
            <div class="card-body table-responsive">
                <table id="users-table" class="table table-striped table-bordered display text-nowrap dataTable">
                    <thead>
                        <tr class="text-center">
                            <th class="text-center" style="width:160px;">ID</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Username</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Role</th>
                            <th class="text-center" style="width:160px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Create Modal --}}

    <div class="modal  " id="create_user_modal" tabindex="-1" aria-labelledby="bs-example-modal-lg" aria-modal="true"
        role="dialog">
        <div class="modal-dialog modal-lg  modal-dialog-centered">
            <div class="modal-content">
                <form method="post" id="create_user_form">
                    <div class="modal-header d-flex align-items-center  bg-primary ">
                        <h4 class="modal-title text-white " id="myLargeModalLabel ">
                            Create user
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="">First Name </label>
                                    <input type="text" class="form-control" id="name" placeholder="First Name"
                                        required>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="last_name" class=""></label>
                                    <input type="text" class="form-control" id="last_name" placeholder="Last Name "
                                        required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="username" class="">Username </label>
                                    <input type="text" class="form-control" id="username" placeholder="Username"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="">Email </label>
                                    <input type="email" class="form-control" id="email" placeholder="Email" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="">Password </label>
                                    <input type="password" class="form-control" id="password" placeholder="Password"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="confirm_password" class="">Confirm Password </label>
                                    <input type="password" class="form-control" id="confirm_password"
                                        placeholder="Confirm Password" required>
                                </div>
                            </div>
                            <div class="col-md-6">

                                <div class="form-group mb-4">
                                    <label class="mr-sm-2" for="role">Role</label>
                                    <select class="form-select mr-sm-2" id="role" required>
                                        <option selected="" value="">Choose...</option>
                                        <option value="1">Admin </option>
                                        <option value="2">Advertiser </option>
                                        <option value="3">Marketing </option>
                                    </select>
                                </div>


                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-danger-subtle text-danger " data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-success">
                            Save
                        </button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>


    <div class="modal " id="edit_user_modal" tabindex="-1" aria-labelledby="bs-example-modal-lg" aria-modal="true"
        role="dialog">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <form method="post" id="edit_user_form">
                    <div class="modal-header d-flex align-items-center  bg-primary ">
                        <h4 class="modal-title text-white " id="myLargeModalLabel ">
                            Edit user
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" value="" id="id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="">First Name </label>
                                    <input type="text" class="form-control" id="name" placeholder="First Name"
                                        required>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="last_name" class=""></label>
                                    <input type="text" class="form-control" id="last_name" placeholder="Last Name "
                                        required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group mb-4">
                                    <label class="mr-sm-2" for="role">Role</label>
                                    <select class="form-select mr-sm-2" id="role" required>
                                        <option selected="" value="">Choose...</option>
                                        <option value="1">Admin </option>
                                        <option value="2">Advertiser </option>
                                        <option value="3">Marketing </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-danger-subtle text-danger " data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-success">
                            Save
                        </button>
                    </div>
                </form>
            </div>

        </div>

    </div>

    <div class="modal" id="assign_chains_modal" tabindex="-1">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <form id="assign_chains_form">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title text-white">Assign Cinema Chains</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="user_id">
                        <div class="mb-3">
                            <label>Cinema Chains</label>
                            <select id="cinema_chain_ids" name="cinema_chain_ids[]" multiple
                                    class="form-select select2-chains" style="width:100%"
                                    data-placeholder="Select cinema chains...">
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-danger-subtle text-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal " id="edit_password_modal" tabindex="-1" aria-labelledby="bs-example-modal-lg" aria-modal="true"
        role="dialog">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <form method="post" id="edit_password_form">
                    <div class="modal-header d-flex align-items-center  bg-primary ">
                        <h4 class="modal-title text-white " id="myLargeModalLabel ">
                            Edit Password
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" value="" id="id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="">Password </label>
                                    <input type="password" class="form-control" id="password" placeholder="Password"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="confirm_password" class="">Confirm Password </label>
                                    <input type="password" class="form-control" id="confirm_password"
                                        placeholder="Confirm Password" required>
                                    <div class="invalid-feedback" id="confirm-password-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-danger-subtle text-danger " data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-success">
                            Save
                        </button>
                    </div>
                </form>
            </div>

        </div>

    </div>
@endsection


@section('custom_script')
    <script src="{{ asset('assets/js/helper.js') }}?v={{ filemtime(public_path('assets/js/helper.js')) }}"></script>
    <script src="{{ asset('assets/libs/select2/dist/js/select2.full.min.js') }}"></script>
    <script>
        (function() {
            'use strict';

            const forms = document.querySelectorAll(
                '#create_user_modal #create_user_form, ' +
                '#edit_user_modal #edit_user_form, ' +
                '#edit_password_modal #edit_password_form'
            );

            forms.forEach(function(form) {
                const pwd = form.querySelector('#password');
                const pwd2 = form.querySelector('#confirm_password');

                // Vérification / sync des mots de passe
                const syncPasswords = () => {
                    if (!pwd || !pwd2) return; // ce form n’a pas ces champs
                    if (pwd.value !== pwd2.value) {
                        pwd2.setCustomValidity('Passwords do not match');
                    } else {
                        pwd2.setCustomValidity('');
                    }
                    // Mettre à jour l’UI si le form a déjà .was-validated
                    if (form.classList.contains('was-validated')) {
                        pwd2.reportValidity();
                    }
                };

                // Validation en temps réel
                if (pwd && pwd2) {
                    pwd.addEventListener('input', syncPasswords);
                    pwd2.addEventListener('input', syncPasswords);
                }

                form.addEventListener('submit', function(event) {
                    // 1) Custom check AVANT checkValidity()
                    syncPasswords();

                    // 2) Puis validation HTML5/Bootstrap
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }

                    form.classList.add('was-validated');
                }, false);
            });

            // Optionnel: reset à la fermeture du modal pour repartir propre
            $(document).on('hidden.bs.modal', '#create_user_modal, #edit_password_modal, #edit_user_modal', function() {
                const f = this.querySelector('form');
                if (f) {
                    f.reset();
                    f.classList.remove('was-validated');
                }
            });
        });


        $(function() {
            $(document).on('click', '#create_user', function() {
                $('#create_user_modal').modal('show');
            })

            function get_users() {
                var role = "";
                $('#wait-modal').modal('show');

                $("#users-table").dataTable().fnDestroy();
                var url = "{{ url('') }}" + '/users/list';
                var result = " ";
                $.ajax({
                        url: url,
                        method: 'GET',
                        processData: false,
                        contentType: false,

                        success: function(response) {
                            $.each(response.users, function(index, value) {
                                var user_users = "";
                                index++;
                                if (value.role == 1) {
                                    role = "Admin"
                                } else if (value.role == 2) {
                                    role = "Advertiser"
                                } else {
                                    role = "Marketing"
                                }
                                result = result +
                                    '<tr class="odd text-center">' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' +
                                    index + ' </td>' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' +
                                    value.name + ' ' + value.last_name + ' </td>' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' +
                                    value.username + ' </td>' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' +
                                    value.email + ' </td>' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' +
                                    role + ' </td>' +

                                    '<td class="text-body align-middle fw-medium text-decoration-none">' +
                                    '<button id="' + value.id +
                                    '" type="button" class="edit ustify-content-center btn mb-1 btn-rounded btn-warning m-1" >' +
                                    '<i class="mdi mdi-tooltip-edit "></i>' +
                                    '</button>' +
                                    '<button id="' + value.id +
                                    '" type="button" class="edit_password justify-content-center btn mb-1 btn-rounded btn-warning m-1">' +
                                    '<i class="mdi mdi-pencil-lock"></i>' +
                                    '</button>' +
                                    (value.role == 2 ?
                                    '<button id="' + value.id +
                                    '" type="button" class="assign_chains justify-content-center btn mb-1 btn-rounded btn-info m-1" title="Assign Cinema Chains">' +
                                    '<i class="mdi mdi-home-modern"></i>' +
                                    '</button>' : '') +
                                    '<button id="' + value.id +
                                    '" type="button" class="delete justify-content-center btn mb-1 btn-rounded btn-danger m-1">' +
                                    '<i class="mdi mdi-delete"></i>' +
                                    '</button>' +

                                    '</td>' +
                                    '</tr>';
                            });
                            $('#users-table tbody').html(result)
                            $('#wait-modal').modal('hide');
                            // $('#loader-modal').css('display','none')
                            /***** refresh datatable **** **/

                            var users = $('#users-table').DataTable({
                                "iDisplayLength": 10,
                                destroy: true,
                                "bDestroy": true,
                                "language": {
                                    search: "_INPUT_",
                                    searchPlaceholder: "Search..."
                                }
                            });
                        },
                        error: function(response) {}
                    })
                    .always(function() {
                        $('#wait-modal').modal('hide');
                    });

            }
            get_users();

            $(document).on("submit", "#create_user_form", function(event) {
                event.preventDefault();

                const $form = $(this);

                const payload = {
                    name: $form.find('#name').val(),
                    last_name: $form.find('#last_name').val(),
                    username: $form.find('#username').val(),
                    email: $form.find('#email').val(),
                    password: $form.find('#password').val(),
                    role: $form.find('#role').val(),
                    _token: "{{ csrf_token() }}",
                };

                $.ajax({
                        url: "{{ url('') }}/users",
                        method: "POST",
                        data: payload,
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        }
                    })
                    .done(function(response) {
                        get_users();
                        Swal.fire({
                            title: 'Done!',
                            text: 'User created successfully.',
                            icon: 'success',
                            confirmButtonText: 'Continue'
                        });
                        reset_form('#create_user_modal form')


                    })
                    .fail(function(xhr) {
                        Swal.fire({
                            title: 'Error',
                            text: 'Operation failed.',
                            icon: 'error'
                        });
                    })
                    .always(function() {
                        $('#wait-modal').modal('hide');
                        $('#create_user_modal').modal('hide');
                    });
            });

            $(document).on('click', '.delete', function() {
                var id = $(this).attr('id');
                console.log(id)
                console.log(encodeURIComponent(id))
                const url = '{{ url('') }}' + '/users/' + encodeURIComponent(id);
                const csrf = '{{ csrf_token() }}';

                // SweetAlert2 confirm
                Swal.fire({
                    title: 'Delete user?',
                    text: 'Are you sure you want to delete this user?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel'
                }).then(function(result) {
                    if (!result.isConfirmed) return;
                    $('#wait-modal').modal('show');
                    // Bootstrap 5 Modal: passer un ELEMENT, pas une string
                    const waitEl = document.getElementById('wait-modal');
                    const wait = bootstrap.Modal.getOrCreateInstance(waitEl);
                    wait.show();

                    $.ajax({
                            url: url,
                            method: 'POST', // compat Laravel
                            data: {
                                _method: 'DELETE',
                                _token: csrf
                            },
                            headers: {
                                'X-CSRF-TOKEN': csrf
                            }
                        })
                        .done(function(response) {

                            get_users();
                            Swal.fire({
                                title: 'Done!',
                                text: 'user deleted successfully.',
                                icon: 'success',
                                confirmButtonText: 'Continue'
                            });

                        })
                        .fail(function(xhr) {
                            Swal.fire({
                                title: 'Error',
                                text: 'Deletion failed.',
                                icon: 'error'
                            });
                        })
                        .always(function() {
                            $('#wait-modal').modal('hide'); //
                        });
                });
            });

            $(document).on('click', '.edit', function() {
                var user = $(this).attr('id');
                var url = '{{ url('') }}' + '/users/' + user + '/show/';

                $.ajax({
                    url: url,
                    type: 'GET',
                    method: 'GET',
                    beforeSend: function() {
                        $("#wait-modal").modal('show');
                    },
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(response) {

                        $("#wait-modal").modal('hide');
                        $('#edit_user_modal').modal('show');
                        $('#edit_user_modal #name').val(response.user.name)
                        $('#edit_user_modal #last_name').val(response.user.last_name)
                        $('#edit_user_modal #role').val(response.user.role)
                        $('#edit_user_modal #id').val(user)

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(response);
                    }
                })
            })

            $(document).on("submit", "#edit_user_form", function(event) {
                event.preventDefault();
                var id = $('#edit_user_form #id').val();
                const $form = $(this);
                const payload = {
                    name: $form.find('#name').val(),
                    last_name: $form.find('#last_name').val(),
                    role: $form.find('#role').val(),
                    _token: "{{ csrf_token() }}",
                };
                var url = '{{ url('') }}' + '/users/' + id;

                $.ajax({
                        url: url,
                        type: 'PUT',
                        method: 'PUT',
                        data: payload,
                        beforeSend: function() {
                            $("#wait-modal").modal('show');
                        },
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            "_token": "{{ csrf_token() }}",
                        },
                    })
                    .done(function(response) {

                        $("#wait-modal").modal('hide');
                        $('#edit_user_modal').modal('hide');
                        get_users()
                        swal.fire({
                            title: 'Done!',
                            text: 'user Updated Successfully ',
                            icon: 'success',
                            button: {
                                text: "Continue",
                                value: true,
                                visible: true,
                                className: "btn btn-primary"
                            }
                        })
                        $('#edit_user_modal').modal('hide');
                    }).fail(function(xhr) {
                        $('#edit_user_modal').modal('hide');
                        $("#wait-modal").modal('hide');
                        Swal.fire({
                            title: 'Error',
                            text: 'Operation failed.',
                            icon: 'error'
                        });
                    })
            })

            $(document).on('click', '.edit_password', function() {
                var user = $(this).attr('id');
                $('#edit_password_form #id').val(user)
                $('#edit_password_modal').modal('show');

            })

            $(document).on("submit", "#edit_password_form", function(event) {
                event.preventDefault();
                var id = $('#edit_password_form #id').val();
                var password = $('#edit_password_form #password').val();
                var confirm_password = $('#edit_password_form #confirm_password').val();

                if (password && confirm_password && password !== confirm_password) {
                    $('#edit_password_form  #confirm_password').addClass('is-invalid');
                    $(' #edit_password_form  #confirm-password-feedback').text('Password and confirmation do not match.');

                    return ;
                }

                const $form = $(this);
                const payload = {
                    password: password,
                    confirm_password : confirm_password,
                    _token: "{{ csrf_token() }}",
                };
                var url = '{{ url('') }}' + '/users/' + id + '/update_password/';

                $.ajax({
                        url: url,
                        type: 'PUT',
                        method: 'PUT',
                        data: payload,
                        beforeSend: function() {
                            $("#wait-modal").modal('show');
                        },
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            "_token": "{{ csrf_token() }}",
                        },
                    })
                    .done(function(response) {
                        $("#wait-modal").modal('hide');
                        get_users()
                        swal.fire({
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
                        $('#edit_password_modal').modal('hide');
                        reset_form('#edit_password_modal form')
                    }).fail(function(xhr) {
                        $('#edit_user_modal').modal('hide');
                        $("#wait-modal").modal('hide');
                        Swal.fire({
                            title: 'Error',
                            text: 'Operation failed.',
                            icon: 'error'
                        });
                    })
            })

            function checkUsernameExists(usernameInput,username) {

                $.ajax({
                    url: '{{ url('') }}' + '/users/check_username',
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
                            $(usernameInput).next('.invalid-feedback').remove();
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

            /* ---- Select2 helper for assign chains modal ---- */
            function initChainsSelect2() {
                var $s = $('#assign_chains_form #cinema_chain_ids');

                if ($s.hasClass('select2-hidden-accessible')) {
                    $s.select2('destroy');
                }

                $s.select2({
                    width: '100%',
                    dropdownParent: $('#assign_chains_modal'),
                    placeholder: 'Select cinema chains...',
                    closeOnSelect: false,
                });

                $s.on('select2:select select2:unselect', function(e) {
                    var ALL = '__all__';
                    var $select = $(this);
                    var values  = $select.val() || [];

                    if (e.params && e.params.data && e.params.data.id === ALL) {
                        if (values.indexOf(ALL) !== -1) {
                            // select all real options
                            var allValues = $select.find('option').map(function() { return this.value; }).get()
                                            .filter(function(v) { return v !== ALL; });
                            $select.val(allValues).trigger('change.select2');
                        } else {
                            $select.val(null).trigger('change.select2');
                        }
                        $select.select2('close');
                    }
                });
            }

            /* ---- Assign Cinema Chains ---- */
            $(document).on('click', '.assign_chains', function() {
                var userId = $(this).attr('id');
                $('#assign_chains_form #user_id').val(userId);

                var url = '{{ url('') }}' + '/users/' + userId + '/cinema-chains';
                $('#wait-modal').modal('show');

                $.ajax({ url: url, type: 'GET' })
                    .done(function(response) {
                        $('#wait-modal').modal('hide');
                        var $select = $('#assign_chains_form #cinema_chain_ids');
                        $select.empty();

                        // "Select All / Deselect All" special option
                        $select.append('<option value="__all__">— Select All —</option>');

                        $.each(response.cinemaChains, function(i, chain) {
                            var selected = response.assignedIds.indexOf(chain.id) !== -1 ? ' selected' : '';
                            $select.append('<option value="' + chain.id + '"' + selected + '>' + chain.name + '</option>');
                        });

                        initChainsSelect2();
                        $('#assign_chains_modal').modal('show');
                    })
                    .fail(function() {
                        $('#wait-modal').modal('hide');
                        Swal.fire({ title: 'Error', text: 'Could not load cinema chains.', icon: 'error' });
                    });
            });

            $(document).on('submit', '#assign_chains_form', function(event) {
                event.preventDefault();
                var userId = $('#assign_chains_form #user_id').val();
                var ids    = ($('#assign_chains_form #cinema_chain_ids').val() || [])
                                .filter(function(v) { return v !== '__all__'; });
                var url    = '{{ url('') }}' + '/users/' + userId + '/cinema-chains/sync';

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: { cinema_chain_ids: ids, _token: '{{ csrf_token() }}' },
                    beforeSend: function() { $('#wait-modal').modal('show'); },
                })
                .done(function() {
                    $('#wait-modal').modal('hide');
                    $('#assign_chains_modal').modal('hide');
                    Swal.fire({ title: 'Done!', text: 'Cinema chains assigned successfully.', icon: 'success', confirmButtonText: 'Continue' });
                })
                .fail(function() {
                    $('#wait-modal').modal('hide');
                    Swal.fire({ title: 'Error', text: 'Operation failed.', icon: 'error' });
                });
            });

        });
    </script>
@endsection
