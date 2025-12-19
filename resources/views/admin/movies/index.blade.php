@extends('admin.layouts.app')
@section('title')
    Movies
@endsection
@section('content')
    <div class="">

        <div class="card card-body py-3">
            <div class="row align-items-center">
              <div class="col-12">
                <div class="d-sm-flex align-items-center justify-space-between">
                  <h4 class="mb-4 mb-sm-0 card-title">Movies</h4>
                  <nav aria-label="breadcrumb" class="ms-auto">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item" aria-current="page">
                        <button class="btn bg-success  text-white " id="create_movie">
                            + New Movie
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
                <table id="movies-table" class="table table-striped table-bordered display text-nowrap dataTable">
                    <thead>
                        <tr class="text-center">
                            <th class="text-center" style="width:160px;">ID</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Genre</th>
                            <th class="text-center">Movie Code </th>
                            <th class="text-center">Language</th>
                            <th class="text-center">Runtime</th>
                            <th class="text-center">Status</th>
                            <th  class="text-center" style="width:160px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Create Modal --}}

    <div class="modal  " id="create_movie_modal" tabindex="-1" aria-labelledby="bs-example-modal-lg" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-md  modal-dialog-centered">
            <div class="modal-content">
                <form method="post" id="create_movie_form">
                    <div class="modal-header d-flex align-items-center  bg-primary ">
                        <h4 class="modal-title text-white " id="myLargeModalLabel ">
                            Create movie
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
                                <label for="movie_genre" class="">Genre:</label>
                                <select class="form-control" id="movie_genre" required>
                                    <option value="">-- Select Genre --</option>
                                    @foreach($genres ?? [] as $genre)
                                        <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="">Language:</label>
                                <select class="form-control" id="langue_id" required>
                                    <option value="">-- Select Language --</option>
                                    @foreach($langues ?? [] as $lang)
                                        <option value="{{ $lang->id }}">{{ $lang->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="">Runtime (min):</label>
                                <input type="number" class="form-control" id="runtime" placeholder="Runtime">
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


    <div class="modal " id="edit_movie_modal" tabindex="-1" aria-labelledby="bs-example-modal-lg" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <form method="post" id="edit_movie_form">
                    <div class="modal-header d-flex align-items-center  bg-primary ">
                        <h4 class="modal-title text-white " id="myLargeModalLabel ">
                            Edit movie
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
                                <label for="movie_genre" class="">Genre:</label>
                                <select class="form-control" id="movie_genre" required>
                                    <option value="">-- Select Genre --</option>
                                    @foreach($genres ?? [] as $genre)
                                        <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="">Language:</label>
                                <select class="form-control" id="langue_id" required>
                                    <option value="">-- Select Language --</option>
                                    @foreach($langues ?? [] as $lang)
                                        <option value="{{ $lang->id }}">{{ $lang->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="">Runtime (min):</label>
                                <input type="number" class="form-control" id="runtime">
                            </div>

                            <div class="mb-3">
                                <label class="">Status:</label>
                                <select class="form-control" id="status">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
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
            $(document).on('click', '#create_movie', function() {
                $('#create_movie_modal').modal('show');
            })
            function get_movies() {
                $('#wait-modal').modal('show');

                $("#movies-table").dataTable().fnDestroy();
                var url = "{{ url('') }}" + '/movies/list';
                var result = " ";
                $.ajax({
                        url: url,
                        method: 'GET',
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            $.each(response.movies, function(index, value) {
                                var user_movies = "";
                                index++;
                                result = result +
                                    '<tr class="odd text-center">' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' +
                                    index + ' </td>' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' +
                                    value
                                    .name + ' </td>' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' + (value.genre ? value.genre.name : '-') + '</td>' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' +
                                    value.uuid + ' </td>' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' + (value.langue ? value.langue.name : '-') + '</td>' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' + (value.runtime ?? '-') + ' min</td>' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' + (value.status ? '<span class="badge bg-success-subtle text-success">Active</span> ' : '<span class="badge bg-danger-subtle text-danger">Inactive</span>') + '</td>' +
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
                            $('#movies-table tbody').html(result)
                            $('#wait-modal').modal('hide');
                            // $('#loader-modal').css('display','none')
                            /***** refresh datatable **** **/

                            var movies = $('#movies-table').DataTable({
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
            get_movies();

            $(document).on("submit", "#create_movie_modal", function(event) {

                event.preventDefault();
                var name = $('#create_movie_modal #name ').val();
                var movie_genre_id = $('#create_movie_modal #movie_genre').val();
                var langue_id = $('#create_movie_modal #langue_id').val();
                var runtime = $('#create_movie_modal #runtime').val();
                var status = $('#create_movie_modal #status').val();

                var url = '{{ url('') }}' + '/movies';

                $.ajax({
                        url: url,
                        type: 'POST',
                        method: 'POST',
                        data: {
                            name: name,
                            movie_genre_id:movie_genre_id,
                            langue_id:langue_id,
                            runtime:runtime,
                            status:1,
                            "_token": "{{ csrf_token() }}",
                        },
                    })
                    .done(function(response) {

                        get_movies();
                        Swal.fire({
                            title: 'Done!',
                            text: 'movie created successfully.',
                            icon: 'success',
                            confirmButtonText: 'Continue'
                        });
                        reset_form('#create_movie_modal form')
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
                        $('#create_movie_modal').modal('hide');
                    });

            })

            $(document).on('click', '.delete', function() {
                var id = $(this).attr('id');
                console.log(id)
                console.log(encodeURIComponent(id))
                const url = '{{ url('') }}' + '/movies/' + encodeURIComponent(id);
                const csrf = '{{ csrf_token() }}';

                // SweetAlert2 confirm
                Swal.fire({
                    title: 'Delete movie?',
                    text: 'Are you sure you want to delete this movie?',
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

                            get_movies();
                            Swal.fire({
                                title: 'Done!',
                                text: 'movie deleted successfully.',
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
                var movie = $(this).attr('id');


                var url = '{{ url('') }}' + '/movies/' + movie+ '/show/';

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
                        console.log(response)
                        $("#wait-modal").modal('hide');
                        $('#edit_movie_modal').modal('show');
                        $('#edit_movie_modal #name').val(response.movie.name)
                        $('#edit_movie_modal #movie_genre').val(response.movie.movie_genre_id);
                        $('#edit_movie_modal #id').val(movie)
                        $('#edit_movie_modal #langue_id').val(response.movie.langue_id);
                        $('#edit_movie_modal #runtime').val(response.movie.runtime);
                        $('#edit_movie_modal #status').val(response.movie.status);

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(response);
                    }
                })
            })

            $(document).on("submit","#edit_movie_form" , function(event) {
                event.preventDefault();
                var name = $('#edit_movie_form #name').val();
                var movie_genre_id = $('#edit_movie_form #movie_genre').val();
                var langue_id = $('#edit_movie_form #langue_id').val();
                var runtime = $('#edit_movie_form #runtime').val();
                var status = $('#edit_movie_form #status').val();

                var id = $('#edit_movie_form #id').val();
                var url = '{{ url('') }}' + '/movies/'+ id;

                $.ajax({
                    url: url,
                    type: 'PUT',
                    method: 'PUT',
                    data: {
                        name: name,
                        movie_genre_id: movie_genre_id,
                        langue_id: langue_id,
                        runtime: runtime,
                        status:status,
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
                    $('#edit_movie_modal').modal('hide');
                    get_movies()
                        swal.fire({
                            title: 'Done!',
                            text: 'movie Updated Successfully ',
                            icon: 'success',
                            button: {
                                text: "Continue",
                                value: true,
                                visible: true,
                                className: "btn btn-primary"
                            }
                        })
                        $('#edit_user_modal').modal('hide') ;
                }).fail(function(xhr) {
                    $('#edit_movie_modal').modal('hide');
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
