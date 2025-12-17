@extends('admin.layouts.app')
@section('title')
    Locations
@endsection
@section('content')
    <div class="">
        <div class="card card-body py-3">
            <div>
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" id="cinema-chain-tab" href="#cinema-chain" role="tab">
                            <span>Cinema Chains</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " data-bs-toggle="tab" href="#location" id="location-tab" role="tab">
                            <span>Locations  </span>
                        </a>
                    </li>
                    <!--<li class="nav-item">
                        <a class="nav-link " data-bs-toggle="tab" href="#slot" id="slot-tab" role="tab">
                            <span>Slots  </span>
                        </a>
                    </li>-->
                    <li class="nav-item">
                        <a class="nav-link " data-bs-toggle="tab" href="#hall-type" id="hall-type-tab" role="tab">
                            <span>Halls Type  </span>
                        </a>
                    </li>

                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active" id="cinema-chain" role="tabpanel">

                        <div class="">
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <div class="d-sm-flex align-items-center justify-space-between">
                                        <nav aria-label="breadcrumb" class="ms-auto">
                                            <ol class="breadcrumb">
                                                <li class="breadcrumb-item">
                                                    <button class="btn bg-success text-white" id="create_cinema_chain">
                                                        + New Cinema Chain
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
                                <table id="cinema-chains-table"
                                       class="table table-striped table-bordered display text-nowrap dataTable">
                                    <thead>
                                    <tr class="text-center">
                                        <th style="width:160px;">ID</th>
                                        <th>Name</th>
                                        <th style="width:160px;">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane " id="location" role="tabpanel">
                        <div class="">
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <div class="d-sm-flex align-items-center justify-space-between">

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
                                            <th class="text-center">Cinema Chain</th>
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

                    <div class="tab-pane " id="slot" role="tabpanel">
                        <div class="">
                            <div class="row align-items-center">
                              <div class="col-12">
                                <div class="d-sm-flex align-items-center justify-space-between">
                                  <nav aria-label="breadcrumb" class="ms-auto">
                                    <ol class="breadcrumb">
                                      <li class="breadcrumb-item" aria-current="page">
                                        <button class="btn bg-success  text-white " id="create_slot">
                                            + New Slots
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
                                <table id="slots-table" class="table table-striped table-bordered display text-nowrap dataTable">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="text-center" style="width:160px;">ID</th>
                                            <th class="text-center">Name</th>
                                            <th class="text-center">CPM</th>
                                            <th  class="text-center" style="width:160px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane " id="hall-type" role="tabpanel">
                        <div class="">
                            <div class="row align-items-center">
                              <div class="col-12">
                                <div class="d-sm-flex align-items-center justify-space-between">
                                  <nav aria-label="breadcrumb" class="ms-auto">
                                    <ol class="breadcrumb">
                                      <li class="breadcrumb-item" aria-current="page">
                                        <button class="btn bg-success  text-white " id="create_hall">
                                            + New Hall Types
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
                                <table id="halls-table" class="table table-striped table-bordered display text-nowrap dataTable">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="text-center" style="width:160px;">ID</th>
                                            <th class="text-center">Name</th>
                                            <th  class="text-center" style="width:160px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Cinema Chain Modal --}}
        <div class="modal" id="create_cinema_chain_modal" tabindex="-1">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <form id="create_cinema_chain_form">
                        <div class="modal-header bg-primary">
                            <h4 class="modal-title text-white">Create Cinema Chain</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label>Name</label>
                                <input type="text" class="form-control" id="name" required>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button class="btn bg-danger-subtle text-danger" data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-success">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal" id="edit_cinema_chain_modal" tabindex="-1">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <form id="edit_cinema_chain_form">
                        <div class="modal-header bg-primary">
                            <h4 class="modal-title text-white">Edit Cinema Chain</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <input type="hidden" id="id">
                            <div class="mb-3">
                                <label>Name</label>
                                <input type="text" class="form-control" id="name" required>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button class="btn bg-danger-subtle text-danger" data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-success">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    {{-- Cinema Chain Modal --}}

    {{-- Locations Modal --}}
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
                                    <label class="">Cinema Chain:</label>
                                    <select class="form-control" id="cinema_chain_id">
                                        <option value="">Cinema Chain</option>
                                        @foreach($cinemaChains as $chain)
                                            <option value="{{ $chain->id }}">{{ $chain->name }}</option>
                                        @endforeach
                                    </select>
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
                                    <label class="">Cinema Chain:</label>
                                    <select class="form-control" id="cinema_chain_id">
                                        <option value="">Cinema Chain</option>
                                        @foreach($cinemaChains as $chain)
                                            <option value="{{ $chain->id }}">{{ $chain->name }}</option>
                                        @endforeach
                                    </select>
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
    {{-- End Locations Modal --}}

    {{-- Slots Modal --}}
        <div class="modal  " id="create_slot_modal" tabindex="-1" aria-labelledby="bs-example-modal-lg" aria-modal="true" role="dialog">
            <div class="modal-dialog modal-md  modal-dialog-centered">
                <div class="modal-content">
                    <form method="post" id="create_slot_form">
                        <div class="modal-header d-flex align-items-center  bg-primary ">
                            <h4 class="modal-title text-white " id="myLargeModalLabel ">
                                Create Slots
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

        <div class="modal " id="edit_slot_modal" tabindex="-1" aria-labelledby="bs-example-modal-lg" aria-modal="true" role="dialog">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <form method="post" id="edit_slot_form">
                        <div class="modal-header d-flex align-items-center  bg-primary ">
                            <h4 class="modal-title text-white " id="myLargeModalLabel ">
                                Edit Slot
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
    {{-- End Slots Modal --}}

    {{-- Halls Type Modal --}}
        <div class="modal  " id="create_hall_modal" tabindex="-1" aria-labelledby="bs-example-modal-lg" aria-modal="true" role="dialog">
            <div class="modal-dialog modal-md  modal-dialog-centered">
                <div class="modal-content">
                    <form method="post" id="create_hall_form">
                        <div class="modal-header d-flex align-items-center  bg-primary ">
                            <h4 class="modal-title text-white " id="myLargeModalLabel ">
                                Create Hall Types
                            </h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <div class="row">
                                <div class="mb-3">
                                    <label for="name" class="">Name:</label>
                                    <input type="text" class="form-control" id="name" placeholder="Name" required>
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

        <div class="modal " id="edit_hall_modal" tabindex="-1" aria-labelledby="bs-example-modal-lg" aria-modal="true" role="dialog">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <form method="post" id="edit_hall_form">
                        <div class="modal-header d-flex align-items-center  bg-primary ">
                            <h4 class="modal-title text-white " id="myLargeModalLabel ">
                                Edit Hall Types
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
    {{-- End Halls Type Modal --}}
@endsection


@section('custom_script')
    <script src="{{ asset('assets/js/helper.js') }}"></script>
    {{-- Cinema Chain  Script --}}
        <script>
            $(function() {

                $(document).on('click', '#create_cinema_chain', function() {
                    $('#create_cinema_chain_modal').modal('show');
                })

                function get_cinema_chains() {
                    $('#wait-modal').modal('show');

                    $("#cinema-chains-table").dataTable().fnDestroy();
                    var url = "{{ url('') }}" + '/cinema-chains/list';
                    var result = " ";

                    $.ajax({
                        url: url,
                        method: 'GET',
                        processData: false,
                        contentType: false,
                        success: function(response) {

                            $.each(response.cinemaChains, function(index, value) {
                                index++;
                                result = result +
                                    '<tr class="odd text-center">' +
                                    '<td class="text-body align-middle fw-medium">' + index + '</td>' +
                                    '<td class="text-body align-middle fw-medium">' + value.name + '</td>' +
                                    '<td class="text-body align-middle fw-medium">' +
                                    '<button id="' + value.id + '" type="button" class="edit btn mb-1 btn-rounded btn-warning m-1">' +
                                    '<i class="mdi mdi-tooltip-edit"></i>' +
                                    '</button>' +
                                    '<button id="' + value.id + '" type="button" class="delete btn mb-1 btn-rounded btn-danger m-1">' +
                                    '<i class="mdi mdi-delete"></i>' +
                                    '</button>' +
                                    '</td>' +
                                    '</tr>';
                            });

                            $('#cinema-chains-table tbody').html(result);
                            $('#wait-modal').modal('hide');

                            $('#cinema-chains-table').DataTable({
                                "iDisplayLength": 10,
                                destroy: true,
                                "language": {
                                    search: "_INPUT_",
                                    searchPlaceholder: "Search..."
                                }
                            });
                        },
                        error: function() {}
                    }).always(function() {
                        $('#wait-modal').modal('hide');
                    });
                }
                get_cinema_chains();
                $(document).on('click', '#cinema-chain-tab', function() {
                    get_cinema_chains();
                })
                /* CREATE */
                $(document).on("submit", "#create_cinema_chain_modal", function(event) {
                    event.preventDefault();

                    var name = $('#create_cinema_chain_modal #name').val();
                    var url = '{{ url('') }}' + '/cinema-chains';

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            name: name,
                            "_token": "{{ csrf_token() }}",
                        },
                    })
                    .done(function() {
                        get_cinema_chains();
                        Swal.fire({
                            title: 'Done!',
                            text: 'Cinema chain created successfully.',
                            icon: 'success',
                            confirmButtonText: 'Continue'
                        });
                        reset_form('#create_cinema_chain_modal form');
                    })
                    .fail(function() {
                        Swal.fire({
                            title: 'Error',
                            text: 'Operation failed.',
                            icon: 'error'
                        });
                    })
                    .always(function() {
                        $('#wait-modal').modal('hide');
                        $('#create_cinema_chain_modal').modal('hide');
                    });
                });

                /* DELETE */
                $(document).on('click', '#cinema-chain .delete', function() {
                    var id = $(this).attr('id');
                    var url = '{{ url('') }}' + '/cinema-chains/' + encodeURIComponent(id);
                    const csrf = '{{ csrf_token() }}';

                    Swal.fire({
                        title: 'Delete Cinema Chain?',
                        text: 'Are you sure you want to delete this cinema chain?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it',
                        cancelButtonText: 'Cancel'
                    }).then(function(result) {
                        if (!result.isConfirmed) return;

                        const wait = bootstrap.Modal.getOrCreateInstance(document.getElementById('wait-modal'));
                        wait.show();

                        $.ajax({
                            url: url,
                            method: 'POST',
                            data: {
                                _method: 'DELETE',
                                _token: csrf
                            },
                            headers: {
                                'X-CSRF-TOKEN': csrf
                            }
                        })
                        .done(function() {
                            get_cinema_chains();
                            Swal.fire({
                                title: 'Done!',
                                text: 'Cinema chain deleted successfully.',
                                icon: 'success',
                                confirmButtonText: 'Continue'
                            });
                        })
                        .fail(function() {
                            Swal.fire({
                                title: 'Error',
                                text: 'Deletion failed.',
                                icon: 'error'
                            });
                        })
                        .always(function() {
                            $('#wait-modal').modal('hide');
                        });
                    });
                });

                /* EDIT */
                $(document).on('click', '#cinema-chain .edit', function() {
                    var id = $(this).attr('id');
                    var url = '{{ url('') }}' + '/cinema-chains/' + id + '/show';

                    $.ajax({
                        url: url,
                        type: 'GET',
                        beforeSend: function() {
                            $("#wait-modal").modal('show');
                        },
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            $("#wait-modal").modal('hide');
                            $('#edit_cinema_chain_modal').modal('show');
                            $('#edit_cinema_chain_modal #name').val(response.cinemaChain.name);
                            $('#edit_cinema_chain_modal #id').val(id);
                        }
                    });
                });

                /* UPDATE */
                $(document).on("submit", "#edit_cinema_chain_form", function(event) {
                    event.preventDefault();

                    var name = $('#edit_cinema_chain_form #name').val();
                    var id = $('#edit_cinema_chain_form #id').val();
                    var url = '{{ url('') }}' + '/cinema-chains/' + id;

                    $.ajax({
                        url: url,
                        type: 'PUT',
                        data: {
                            name: name,
                            "_token": "{{ csrf_token() }}",
                        },
                        beforeSend: function() {
                            $("#wait-modal").modal('show');
                        },
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        },
                    })
                    .done(function() {
                        $("#wait-modal").modal('hide');
                        $('#edit_cinema_chain_modal').modal('hide');
                        get_cinema_chains();
                        Swal.fire({
                            title: 'Done!',
                            text: 'Cinema chain updated successfully.',
                            icon: 'success',
                            confirmButtonText: 'Continue'
                        });
                    })
                    .fail(function() {
                        $('#edit_cinema_chain_modal').modal('hide');
                        $("#wait-modal").modal('hide');
                        Swal.fire({
                            title: 'Error',
                            text: 'Operation failed.',
                            icon: 'error'
                        });
                    });
                });

            });
        </script>
    {{-- End  Cinema Chain  Script --}}

    {{-- Locations Script --}}
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
                                        value.name + ' </td>' +
                                        '<td class="text-body align-middle fw-medium">' +
                                            (value.cinema_chain ? value.cinema_chain.name : 'No Cinema Chain') +
                                        '</td>' +
                                        '<td class="text-body align-middle fw-medium text-decoration-none">' +
                                        value.cpm + ' </td>' +

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
                $(document).on('click', '#location-tab', function() {
                    get_locations();
                    refresh_cinema_chain();
                })


                $(document).on("submit", "#create_location_modal", function(event) {

                    event.preventDefault();
                    var name = $('#create_location_modal #name ').val();
                    var cpm = $('#create_location_modal #cpm').val();
                    var cinema_chain_id = $('#create_location_modal #cinema_chain_id').val();
                    var url = '{{ url('') }}' + '/locations';

                    $.ajax({
                            url: url,
                            type: 'POST',
                            method: 'POST',
                            data: {
                                name: name,
                                cpm: cpm,
                                cinema_chain_id:cinema_chain_id,
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

                $(document).on('click', '#location .delete', function() {
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

                $(document).on('click', '#location .edit', function() {

                    refresh_cinema_chain()
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
                            $('#edit_location_modal #cinema_chain_id').val(response.location.cinema_chain_id ?? '');
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
                    var cinema_chain_id = $('#edit_location_form #cinema_chain_id').val();
                    var url = '{{ url('') }}' + '/locations/' + id;

                    $.ajax({
                            url: url,
                            type: 'PUT',
                            method: 'PUT',
                            data: {
                                name: name,
                                cpm: cpm,
                                cinema_chain_id:cinema_chain_id,
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

                function refresh_cinema_chain()
                {
                    var cinema_chain_create= $('#create_location_form #cinema_chain_id');
                    var cinema_chain_edit= $('#edit_location_form #cinema_chain_id');
                    var url = "{{ url('') }}" + '/cinema-chains/list';
                    $.ajax({
                        url: url,
                        type: "GET",
                        success: function (response) {
                            cinema_chain_create.empty();
                            cinema_chain_edit.empty();
                            cinema_chain_create.append('<option value="">Cinema Chain</option>');
                            cinema_chain_edit.append('<option value="">Cinema Chain</option>');

                            $.each(response.cinemaChains, function (index, chain) {
                                cinema_chain_create.append(
                                    `<option value="${chain.id}">${chain.name}</option>`
                                );
                                cinema_chain_edit.append(
                                    `<option value="${chain.id}">${chain.name}</option>`
                                );
                            });
                        },
                    });
                }

            });
        </script>
    {{-- End Locations  Script --}}

    {{-- Locations Script --}}
        <script>

            $(function() {
                $(document).on('click', '#create_slot', function() {
                    $('#create_slot_modal').modal('show');
                })
                function get_slots() {
                    $('#wait-modal').modal('show');

                    $("#slots-table").dataTable().fnDestroy();
                    var url = "{{ url('') }}" + '/slots/list';
                    var result = " ";
                    $.ajax({
                            url: url,
                            method: 'GET',
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                $.each(response.slots, function(index, value) {
                                    var user_slots = "";
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
                                $('#slots-table tbody').html(result)
                                $('#wait-modal').modal('hide');
                                // $('#loader-modal').css('display','none')
                                /***** refresh datatable **** **/

                                var slots = $('#slots-table').DataTable({
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
                $(document).on('click', '#slot-tab', function() {
                    get_slots();
                })


                $(document).on("submit", "#create_slot_modal", function(event) {

                    event.preventDefault();
                    var name = $('#create_slot_modal #name ').val();
                    var cpm = $('#create_slot_modal #cpm').val();

                    var url = '{{ url('') }}' + '/slots';

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

                            get_slots();
                            Swal.fire({
                                title: 'Done!',
                                text: 'Slot Created successfully.',
                                icon: 'success',
                                confirmButtonText: 'Continue'
                            });
                            reset_form('#create_slot_modal form')
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
                            $('#create_slot_modal').modal('hide');
                        });

                })

                $(document).on('click', '#slot .delete', function() {
                    var id = $(this).attr('id');
                    console.log(id)

                    const url = '{{ url('') }}' + '/slots/' + encodeURIComponent(id);
                    const csrf = '{{ csrf_token() }}';
                    console.log(url)
                    // SweetAlert2 confirm
                    Swal.fire({
                        title: 'Delete Slot?',
                        text: 'Are you sure you want to delete this Slot?',
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

                                get_slots();
                                Swal.fire({
                                    title: 'Done!',
                                    text: 'Slot deleted successfully.',
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

                $(document).on('click', '#slot .edit', function() {
                    var slot = $(this).attr('id');
                    var url = '{{ url('') }}' + '/slots/' + slot+ '/show/';

                    $.ajax({
                        url: url,
                        type: 'GET',
                        method: 'GET',
                        beforeSend: function () {
                            $("#wait-modal").modal('show');
                        },
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            "_token": "{{ csrf_token() }}",
                        },
                        success: function(response) {

                            $("#wait-modal").modal('hide');
                            $('#edit_slot_modal').modal('show');
                            $('#edit_slot_modal #name').val(response.slot.name)
                            $('#edit_slot_modal #cpm').val(response.slot.cpm)
                            $('#edit_slot_modal #id').val(slot)

                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(response);
                        }
                    })
                })

                $(document).on("submit","#edit_slot_form" , function(event) {
                    event.preventDefault();
                    var name = $('#edit_slot_form #name').val();
                    var cpm = $('#edit_slot_form #cpm').val();
                    var id = $('#edit_slot_form #id').val();
                    var url = '{{ url('') }}' + '/slots/'+ id;

                    $.ajax({
                        url: url,
                        type: 'PUT',
                        method: 'PUT',
                        data: {
                            name: name,
                            cpm: cpm,
                            "_token": "{{ csrf_token() }}",
                        },
                        beforeSend: function () {
                            $("#wait-modal").modal('show');
                        },
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            "_token": "{{ csrf_token() }}",
                        },

                    })
                    .done(function(response) {

                        $("#wait-modal").modal('hide');
                        $('#edit_slot_modal').modal('hide');
                        get_slots()
                            swal.fire({
                                title: 'Done!',
                                text: 'Slot Updated Successfully ',
                                icon: 'success',
                                button: {
                                    text: "Continue",
                                    value: true,
                                    visible: true,
                                    className: "btn btn-primary"
                                }
                            })
                    }).fail(function(xhr) {
                        $('#edit_slot_modal').modal('hide');
                        $("#wait-modal").modal('hide');
                        Swal.fire({
                            title: 'Error',
                            text: 'Operation failed.',
                            icon: 'error'
                        });
                    })
                })

            });
        </script>
    {{-- End Locations  Script --}}

    {{-- Halls Type Script --}}
        <script>

            $(function() {
                $(document).on('click', '#create_hall', function() {
                    $('#create_hall_modal').modal('show');
                })
                function get_halls() {
                    $('#wait-modal').modal('show');

                    $("#halls-table").dataTable().fnDestroy();
                    var url = "{{ url('') }}" + '/halls/list';
                    var result = " ";
                    $.ajax({
                            url: url,
                            method: 'GET',
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                $.each(response.halls, function(index, value) {
                                    index++;
                                    result = result +
                                        '<tr class="odd text-center">' +
                                        '<td class="text-body align-middle fw-medium text-decoration-none">' +
                                        index + ' </td>' +
                                        '<td class="text-body align-middle fw-medium text-decoration-none">' +
                                        value.name + ' </td>' +
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
                                $('#halls-table tbody').html(result)
                                $('#wait-modal').modal('hide');
                                // $('#loader-modal').css('display','none')
                                /***** refresh datatable **** **/

                                var halls = $('#halls-table').DataTable({
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
                $(document).on('click', '#hall-type-tab', function() {
                    get_halls();
                })


                $(document).on("submit", "#create_hall_modal", function(event) {

                    event.preventDefault();
                    var name = $('#create_hall_modal #name ').val();

                    var url = '{{ url('') }}' + '/halls';

                    $.ajax({
                            url: url,
                            type: 'POST',
                            method: 'POST',
                            data: {
                                name: name,
                                "_token": "{{ csrf_token() }}",
                            },
                        })
                        .done(function(response) {

                            get_halls();
                            Swal.fire({
                                title: 'Done!',
                                text: 'Hall Created successfully.',
                                icon: 'success',
                                confirmButtonText: 'Continue'
                            });
                            reset_form('#create_hall_modal form')
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
                            $('#create_hall_modal').modal('hide');
                        });

                })

                $(document).on('click', '#hall-type .delete', function() {
                    var id = $(this).attr('id');
                    console.log(id)

                    const url = '{{ url('') }}' + '/halls/' + encodeURIComponent(id);
                    const csrf = '{{ csrf_token() }}';
                    console.log(url)
                    // SweetAlert2 confirm
                    Swal.fire({
                        title: 'Delete hall?',
                        text: 'Are you sure you want to delete this hall?',
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

                                get_halls();
                                Swal.fire({
                                    title: 'Done!',
                                    text: 'Hall deleted successfully.',
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

                $(document).on('click', '#hall-type .edit', function() {
                    var hall = $(this).attr('id');
                    var url = '{{ url('') }}' + '/halls/' + hall+ '/show/';

                    $.ajax({
                        url: url,
                        type: 'GET',
                        method: 'GET',
                        beforeSend: function () {
                            $("#wait-modal").modal('show');
                        },
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            "_token": "{{ csrf_token() }}",
                        },
                        success: function(response) {

                            $("#wait-modal").modal('hide');
                            $('#edit_hall_modal').modal('show');
                            $('#edit_hall_modal #name').val(response.hall.name)
                            $('#edit_hall_modal #id').val(hall)

                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(response);
                        }
                    })
                })

                $(document).on("submit","#edit_hall_form" , function(event) {
                    event.preventDefault();
                    var name = $('#edit_hall_form #name').val();
                    var id = $('#edit_hall_form #id').val();
                    var url = '{{ url('') }}' + '/halls/'+ id;

                    $.ajax({
                        url: url,
                        type: 'PUT',
                        method: 'PUT',
                        data: {
                            name: name,
                            "_token": "{{ csrf_token() }}",
                        },
                        beforeSend: function () {
                            $("#wait-modal").modal('show');
                        },
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            "_token": "{{ csrf_token() }}",
                        },

                    })
                    .done(function(response) {

                        $("#wait-modal").modal('hide');
                        $('#edit_hall_modal').modal('hide');
                        get_halls()
                            swal.fire({
                                title: 'Done!',
                                text: 'Hall Updated Successfully ',
                                icon: 'success',
                                button: {
                                    text: "Continue",
                                    value: true,
                                    visible: true,
                                    className: "btn btn-primary"
                                }
                            })
                    }).fail(function(xhr) {
                        $('#edit_hall_modal').modal('hide');
                        $("#wait-modal").modal('hide');
                        Swal.fire({
                            title: 'Error',
                            text: 'Operation failed.',
                            icon: 'error'
                        });
                    })
                })

            });
        </script>
    {{-- End Halls Type   Script --}}
@endsection
