@extends('admin.layouts.app')
@section('title')
    Locations
@endsection
@section('content')
    <div class="container py-4">

        <div class="card card-body py-3">
            <div class="row align-items-center">
                <div class="col-12">
                    <div class="d-sm-flex align-items-center justify-space-between">
                        <h4 class="mb-4 mb-sm-0 card-title">Locations</h4>
                        <nav aria-label="breadcrumb" class="ms-auto">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item" aria-current="page">
                                    <button class="btn bg-success  text-white " id="create_location">
                                        + New Location
                                    </button>
                                </li>
                                @if($config->use_noc ==1)
                                <li class="breadcrumb-item" aria-current="page">
                                    <button class="btn bg-primary  text-white " id="refresh">
                                        <i class="mdi mdi-refresh"> </i> Refresh
                                    </button>
                                </li>
                                @endif
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>


        <div class="card">
            <div class="card-body table-responsive">
                <table id="locations-table" class="table table-striped table-bordered display text-nowrap dataTable">
                    <thead>
                        <tr class="text-center">
                            <th class="text-center" style="width:160px;">ID</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">CPM</th>
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

    <div class="modal  " id="create_location_modal" tabindex="-1" aria-labelledby="bs-example-modal-lg" aria-modal="true"
        role="dialog">
        <div class="modal-dialog modal-md  modal-dialog-centered">
            <div class="modal-content">
                <form method="post" id="create_location_form">
                    <div class="modal-header d-flex align-items-center  bg-primary ">
                        <h4 class="modal-title text-white " id="myLargeModalLabel ">
                            Create Location
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="mb-3">
                                <label for="name" class="">Name:</label>
                                <input type="text" class="form-control" id="name" placeholder="Name" required>
                            </div>
                            <div class="mb-3">
                                <label for="cpm" class="">Base CPM (RM):</label>
                                <input type="number" class="form-control" id="cpm" value="0" min="0"
                                    required>
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


    <div class="modal " id="edit_location_modal" tabindex="-1" aria-labelledby="bs-example-modal-lg" aria-modal="true"
        role="dialog">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <form method="post" id="edit_location_form">
                    <div class="modal-header d-flex align-items-center  bg-primary ">
                        <h4 class="modal-title text-white " id="myLargeModalLabel ">
                            Edit Location
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" value="" id="id">
                        <div class="row">
                            <div class="mb-3">
                                <label for="name" class="">Name:</label>
                                <input type="text" class="form-control" id="name" placeholder="Name" required>
                            </div>
                            <div class="mb-3">
                                <label for="cpm" class="">Base CPM (RM):</label>
                                <input type="number" class="form-control" id="cpm" value="0" min="0"
                                    required>
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
    <script src="{{ asset('assets/js/helper.js') }}"></script>
    <script>
        $(function() {
            $(document).on('click', '#create_location', function() {
                $('#create_location_modal').modal('show');
            })

            function get_locations() {
                $('#wait-modal').modal('show');

                $("#locations-table").dataTable().fnDestroy();
                var url = "{{ url('') }}" + '/locations/list';
                var result = " ";
                $.ajax({
                        url: url,
                        method: 'GET',
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            $.each(response.locations, function(index, value) {
                                var user_locations = "";
                                index++;
                                result = result +
                                    '<tr class="odd text-center">' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' +
                                    index + ' </td>' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' +
                                    value
                                    .name + ' </td>' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' +
                                    value
                                    .cpm + ' </td>' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' +
                                    '<button id="' + value.id +
                                    '" type="button" class="edit ustify-content-center btn mb-1 btn-rounded btn-warning m-1" >' +
                                    '<i class="mdi mdi-tooltip-edit "></i>' +
                                    '</button>' +
                                    '<button id="' + value.id +
                                    '" type="button" class="delete justify-content-center btn mb-1 btn-rounded btn-danger m-1">' +
                                    '<i class="mdi mdi-delete"></i>' +
                                    '</button>' +

                                    '</td>' +
                                    '</tr>';
                            });
                            $('#locations-table tbody').html(result)
                            $('#wait-modal').modal('hide');
                            // $('#loader-modal').css('display','none')
                            /***** refresh datatable **** **/

                            var locations = $('#locations-table').DataTable({
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
            get_locations();

            $(document).on("submit", "#create_location_modal", function(event) {

                event.preventDefault();
                var name = $('#create_location_modal #name ').val();
                var cpm = $('#create_location_modal #cpm').val();

                var url = '{{ url('') }}' + '/locations';

                $.ajax({
                        url: url,
                        type: 'POST',
                        method: 'POST',
                        data: {
                            name: name,
                            cpm: cpm,
                            "_token": "{{ csrf_token() }}",
                        },
                    })
                    .done(function(response) {

                        get_locations();
                        Swal.fire({
                            title: 'Done!',
                            text: 'Location deleted successfully.',
                            icon: 'success',
                            confirmButtonText: 'Continue'
                        });
                        reset_form('#create_location_modal form')
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
                        $('#create_location_modal').modal('hide');
                    });

            })

            $(document).on('click', '.delete', function() {
                var id = $(this).attr('id');
                console.log(id)
                console.log(encodeURIComponent(id))
                const url = '{{ url('') }}' + '/locations/' + encodeURIComponent(id);
                const csrf = '{{ csrf_token() }}';

                // SweetAlert2 confirm
                Swal.fire({
                    title: 'Delete Location?',
                    text: 'Are you sure you want to delete this location?',
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

                            get_locations();
                            Swal.fire({
                                title: 'Done!',
                                text: 'Location deleted successfully.',
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
                var location = $(this).attr('id');
                var url = '{{ url('') }}' + '/locations/' + location + '/show/';

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
                        $('#edit_location_modal').modal('show');
                        $('#edit_location_modal #name').val(response.location.name)
                        $('#edit_location_modal #cpm').val(response.location.cpm)
                        $('#edit_location_modal #id').val(location)

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(response);
                    }
                })
            })

            $(document).on("submit", "#edit_location_form", function(event) {
                event.preventDefault();
                var name = $('#edit_location_form #name').val();
                var cpm = $('#edit_location_form #cpm').val();
                var id = $('#edit_location_form #id').val();
                var url = '{{ url('') }}' + '/locations/' + id;

                $.ajax({
                        url: url,
                        type: 'PUT',
                        method: 'PUT',
                        data: {
                            name: name,
                            cpm: cpm,
                            "_token": "{{ csrf_token() }}",
                        },
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
                        $('#edit_location_modal').modal('hide');
                        get_locations()
                        swal.fire({
                            title: 'Done!',
                            text: 'Location Updated Successfully ',
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
                        $('#edit_location_modal').modal('hide');
                        $("#wait-modal").modal('hide');
                        Swal.fire({
                            title: 'Error',
                            text: 'Operation failed.',
                            icon: 'error'
                        });
                    })
            })


            $(document).on('click', '#refresh', function() {
                var location = $(this).attr('id');
                var url = '{{ url('') }}' + '/locations/refresh_locations';

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
                        if (response && typeof response.status !== 'undefined') {
                            if (Number(response.status) === 1) {

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Succès',
                                    text: response.message ||
                                        'Locations rafraîchies avec succès.',
                                    showConfirmButton: false,
                                });
                                get_locations();
                            } else {
                                // Erreur métier renvoyée par le backend
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erreur',
                                    text: response.message ||
                                        'Une erreur est survenue lors du rafraîchissement.',
                                });
                            }
                        } else {
                            // Format inattendu
                            Swal.fire({
                                icon: 'warning',
                                title: 'Attention',
                                text: 'Réponse inattendue du serveur.',
                            });
                        }



                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $("#wait-modal").modal('hide');

                        // Essayer d'extraire le message envoyé par le backend
                        let msg = 'Erreur réseau. Réessaye plus tard.';
                        if (jqXHR && jqXHR.responseJSON && jqXHR.responseJSON.message) {
                            msg = jqXHR.responseJSON.message;
                        } else if (jqXHR && jqXHR.responseText) {
                            // parfois responseText contient du JSON stringifié
                            try {
                                const parsed = JSON.parse(jqXHR.responseText);
                                if (parsed && parsed.message) msg = parsed.message;
                            } catch (e) {
                                // ignore parse error
                            }
                        } else if (errorThrown) {
                            msg = errorThrown;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: msg
                        });

                        console.error('AJAX error:', textStatus, errorThrown, jqXHR);
                    }
                })
            })

        });
    </script>
@endsection
