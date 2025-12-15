@extends('admin.layouts.app')
@section('title')
    Campaign Definitions
@endsection
@section('content')
    <div class="py-4">



        <div class="card card-body py-3">
            <div>
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#genre" role="tab">
                            <span>Genre Definitions  </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " data-bs-toggle="tab" href="#interest" role="tab">
                            <span>Interest Definitions  </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " data-bs-toggle="tab" href="#objective" role="tab">
                            <span>Objective Definitions  </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " data-bs-toggle="tab" href="#categories" role="tab">
                            <span>Categories </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " data-bs-toggle="tab" href="#targets" role="tab">
                            <span>Targets Definitions </span>
                        </a>
                    </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active" id="genre" role="tabpanel">
                        <h1>Genre</h1>
                    </div>
                    <div class="tab-pane " id="interest" role="tabpanel">
                        <h1>interest</h1>
                    </div>
                    <div class="tab-pane " id="objective" role="tabpanel">
                        <h1>Objective</h1>
                    </div>


                    <div class="tab-pane " id="categories" role="tabpanel">
                        <div class="">
                            <div class="row align-items-center">
                              <div class="col-12">
                                <div class="d-sm-flex align-items-center justify-space-between">

                                  <nav aria-label="breadcrumb" class="ms-auto">
                                    <ol class="breadcrumb">
                                      <li class="breadcrumb-item" aria-current="page">
                                        <button class="btn bg-success  text-white " id="create_compaign_category">
                                            + New Compaign Category
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
                                <table id="compaign_categories-table" class="table table-striped table-bordered display text-nowrap dataTable">
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
                    <div class="tab-pane " id="targets" role="tabpanel">
                        <h1>targets</h1>
                    </div>

                </div>
            </div>
        </div>



    </div>

    {{-- Create Modal --}}

    <div class="modal  " id="create_compaign_category_modal" tabindex="-1" aria-labelledby="bs-example-modal-lg" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-md  modal-dialog-centered">
            <div class="modal-content">
                <form method="post" id="create_compaign_category_form">
                    <div class="modal-header d-flex align-items-center  bg-primary ">
                        <h4 class="modal-title text-white " id="myLargeModalLabel ">
                            Create Compaign Category
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


    <div class="modal " id="edit_compaign_category_modal" tabindex="-1" aria-labelledby="bs-example-modal-lg" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <form method="post" id="edit_compaign_category_form">
                    <div class="modal-header d-flex align-items-center  bg-primary ">
                        <h4 class="modal-title text-white " id="myLargeModalLabel ">
                            Edit Compaign Category
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

@endsection


@section('custom_script')
    <script src="{{ asset('assets/js/helper.js')}}"></script>
    <script>

        $(function() {
            $(document).on('click', '#create_compaign_category', function() {
                $('#create_compaign_category_modal').modal('show');
            })
            function get_compaign_categories() {
                $('#wait-modal').modal('show');

                $("#compaign_categories-table").dataTable().fnDestroy();
                var url = "{{ url('') }}" + '/compaign_categories/list';
                var result = " ";
                $.ajax({
                        url: url,
                        method: 'GET',
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            $.each(response.compaign_categories, function(index, value) {
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
                            $('#compaign_categories-table tbody').html(result)
                            $('#wait-modal').modal('hide');
                            // $('#loader-modal').css('display','none')
                            /***** refresh datatable **** **/

                            var compaign_categories = $('#compaign_categories-table').DataTable({
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
            get_compaign_categories();

            $(document).on("submit", "#create_compaign_category_modal", function(event) {

                event.preventDefault();
                var name = $('#create_compaign_category_modal #name ').val();

                var url = '{{ url('') }}' + '/compaign_categories';

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

                        get_compaign_categories();
                        Swal.fire({
                            title: 'Done!',
                            text: 'Compaign Category Created successfully.',
                            icon: 'success',
                            confirmButtonText: 'Continue'
                        });
                        reset_form('#create_compaign_category_modal form')
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
                        $('#create_compaign_category_modal').modal('hide');
                    });

            })

            $(document).on('click', '.delete', function() {
                var id = $(this).attr('id');
                console.log(id)

                const url = '{{ url('') }}' + '/compaign_categories/' + encodeURIComponent(id);
                const csrf = '{{ csrf_token() }}';
                console.log(url)
                // SweetAlert2 confirm
                Swal.fire({
                    title: 'Delete Compaign Category?',
                    text: 'Are you sure you want to delete this Compaign Category?',
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

                            get_compaign_categories();
                            Swal.fire({
                                title: 'Done!',
                                text: 'Compaign Category deleted successfully.',
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
                var compaign_category = $(this).attr('id');
                var url = '{{ url('') }}' + '/compaign_categories/' + compaign_category+ '/show/';

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
                        $('#edit_compaign_category_modal').modal('show');
                        $('#edit_compaign_category_modal #name').val(response.compaign_category.name)
                        $('#edit_compaign_category_modal #id').val(compaign_category)

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(response);
                    }
                })
            })

            $(document).on("submit","#edit_compaign_category_form" , function(event) {
                event.preventDefault();
                var name = $('#edit_compaign_category_form #name').val();
                var id = $('#edit_compaign_category_form #id').val();
                var url = '{{ url('') }}' + '/compaign_categories/'+ id;

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
                    $('#edit_compaign_category_modal').modal('hide');
                    get_compaign_categories()
                        swal.fire({
                            title: 'Done!',
                            text: 'Compaign Category Updated Successfully ',
                            icon: 'success',
                            button: {
                                text: "Continue",
                                value: true,
                                visible: true,
                                className: "btn btn-primary"
                            }
                        })
                }).fail(function(xhr) {
                    $('#edit_compaign_category_modal').modal('hide');
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
@endsection
