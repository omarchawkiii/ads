@extends('admin.layouts.app')
@section('title')
Playlist Template Builder
@endsection
@section('content')
    <div class="">

        <div class="card card-body py-3">
            <div class="row align-items-center">
              <div class="col-12">
                <div class="d-sm-flex align-items-center justify-space-between">
                  <h4 class="mb-4 mb-sm-0 card-title"> Playlist Template Builder</h4>
                  <nav aria-label="breadcrumb" class="ms-auto">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item" aria-current="page">
                        <button class="btn bg-success  text-white " id="create_slot">
                            + New
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
                            <th class="text-center">Segments</th>

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

    <div class="modal  " id="create_slot_modal" tabindex="-1" aria-labelledby="bs-example-modal-lg" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-xl  modal-dialog-centered">
            <div class="modal-content">
                <form method="post" id="create_slot_form">
                    <div class="modal-header d-flex align-items-center  bg-primary ">
                        <h4 class="modal-title text-white " id="myLargeModalLabel ">
                            Create New  Playlist Template
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="mb-3">
                                <label class="">Template Name:</label>
                                <input type="text" class="form-control" id="template_name" placeholder="Template name" required>
                                <hr>
                            </div>



                            <div class="d-flex align-items-center mb-2 justify-content-between">

                                <h5 class="mb-0">Slots</h5>
                                <button type="button" class="btn btn-sm btn-primary me-2" id="add_slot_row">
                                    + Add Slot
                                </button>
                            </div>

                            <div id="slots_container"></div>

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
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <form method="post" id="edit_slot_form">
                    <div class="modal-header d-flex align-items-center  bg-primary ">
                        <h4 class="modal-title text-white " id="myLargeModalLabel ">
                            Edit  Playlist Template
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" value="" id="edit_template_id">
                        <div class="row">
                            <div class="mb-3">
                                <label class="">Template Name:</label>
                                <input type="text" class="form-control" id="edit_template_name" required>
                            </div>
                            <hr>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="mb-0">Slots</h5>
                                <button type="button" class="btn btn-sm btn-primary me-2" id="add_slot_row">
                                    + Add Slot
                                </button>

                            </div>
                            <div id="edit_slots_container"></div>



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
            $(document).on('click', '#create_slot', function() {

                $('#slots_container').html(slotRow());
                $('#template_name').val('');
                $('#create_slot_modal').modal('show');
            })
            function get_slots() {
                $('#wait-modal').modal('show');

                $("#slots-table").dataTable().fnDestroy();
                var url = "{{ url('') }}" + '/slots/list';
                var result = "";

                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(response) {
                        $.each(response.templateSlots, function(index, value) {
                            index++;

                            var segments = '';
                            if (value.slots && value.slots.length > 0) {
                                $.each(value.slots, function(i, slot) {
                                    segments +=  slot.name  + ' ( ' +
                                                (slot.max_duration ? slot.max_duration : 0) + 's' +
                                                ' / ' + (slot.max_ad_slot ?? 1) +
                                                ' ), ';
                                });
                                segments = segments.slice(0, -2); // enlever la dernière virgule
                            } else {
                                segments = '-';
                            }

                            result +=
                                '<tr class="odd text-center">' +
                                    '<td class="text-body align-middle fw-medium">' + index + '</td>' +
                                    '<td class="text-body align-middle fw-medium">' + value.name + '</td>' +
                                    '<td class="text-body align-middle fw-medium">' + segments + '</td>' +
                                    '<td class="text-body align-middle fw-medium">' +
                                        '<button id="' + value.id + '" type="button" class="edit btn btn-rounded btn-warning m-1">' +
                                            '<i class="mdi mdi-tooltip-edit"></i>' +
                                        '</button>' +
                                        '<button id="' + value.id + '" type="button" class="delete btn btn-rounded btn-danger m-1">' +
                                            '<i class="mdi mdi-delete"></i>' +
                                        '</button>' +
                                    '</td>' +
                                '</tr>';
                        });


                        $('#slots-table tbody').html(result);
                        $('#wait-modal').modal('hide');

                        $('#slots-table').DataTable({
                            "iDisplayLength": 10,
                            destroy: true,
                            "language": {
                                search: "_INPUT_",
                                searchPlaceholder: "Search..."
                            }
                        });
                    },
                    error: function() {
                        $('#wait-modal').modal('hide');
                    }
                }).always(function() {
                    $('#wait-modal').modal('hide');
                });
            }

            get_slots();


            function slotRow(slot = {}) {
                return `
                <div class="row g-2 slot-row mb-2 align-items-stretch mb-1">
                    <div class="col-md-4">
                        <label>Segment Name:</label>
                        <input type="text" class="form-control segment_name" placeholder="Segment" value="${slot.segment_name ?? ''}">
                    </div>
                    <div class="col-md-4">
                        <label>Slot Name:</label>
                        <input type="text" class="form-control slot_name" placeholder="Slot Name" value="${slot.name ?? ''}" required>
                    </div>
                    <div class="col-md">
                        <label>Max Duration (sec):</label>
                        <input type="number" min="1" class="form-control max_duration" placeholder="Duration" value="${slot.max_duration ?? ''}" required>
                    </div>
                    <div class="col-md">
                        <label>Max Ad Slots:</label>
                        <input type="number" min="1" class="form-control max_ad_slot" placeholder="Max Ads Slots" value="${slot.max_ad_slot ?? 1}" required>
                    </div>
                    <div class="col-md-1 d-flex justify-content-center align-items-center">
                        <button type="button" class="btn btn-danger btn-sm remove_slot_row mt-3">✕</button>
                    </div>
                </div>`;
            }

            $(document).on('click', '#add_slot_row', function() {
                $('#slots_container').append(slotRow());
            });

            // Supprimer une ligne
            $(document).on('click', '.remove_slot_row', function() {
                $(this).closest('.slot-row').remove();
            });


            $(document).on("submit", "#create_slot_modal", function(event) {

                event.preventDefault();
                var template_name = $('#template_name').val();
                var slots = [];

                $('#slots_container .slot-row').each(function() {
                    slots.push({
                        segment_name: $(this).find('.segment_name').val(),
                        name: $(this).find('.slot_name').val(),
                        max_duration: $(this).find('.max_duration').val(),
                        max_ad_slot: $(this).find('.max_ad_slot').val(),
                    });
                });


               /* var name = $('#create_slot_modal #name ').val();
                var cpm = $('#create_slot_modal #cpm').val();
                var max_duration = $('#create_slot_modal #max_duration').val();*/

                var url = '{{ url('') }}' + '/slots';

                $.ajax({
                        url: url,
                        type: 'POST',
                        method: 'POST',
                        data: {
                            template_name: template_name,
                            slots: slots,
                            /*name: name,
                            cpm: cpm,
                            max_duration:max_duration,*/
                            "_token": "{{ csrf_token() }}",
                        },
                    })
                    .done(function(response) {

                        get_slots();
                        Swal.fire({
                            title: 'Done!',
                            text: 'Template & Slots created successfully.',
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

            $(document).on('click', '.delete', function() {
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
                                text: 'Template and Slots deleted successfully.',
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

                        var template = response.template;

                        $('#edit_template_id').val(template.id);
                        $('#edit_template_name').val(template.name);
                        $('#edit_slots_container').html('');

                        if (template.slots.length > 0) {
                            $.each(template.slots, function(i, slot) {
                                $('#edit_slots_container').append(`
                                    <div class="row g-2 slot-row mb-2 align-items-stretch mb-1">
                                        <input type="hidden" class="slot_id" value="${slot.id}">
                                        <div class="col-md-4">
                                            <label>Segment Name:</label>
                                            <input type="text" class="form-control segment_name" value="${slot.segment_name ?? ''}" placeholder="Segment">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Slot Name:</label>
                                            <input type="text" class="form-control slot_name" value="${slot.name}" placeholder="Slot Name" required>
                                        </div>
                                        <div class="col-md">
                                            <label>Max Duration (sec):</label>
                                            <input type="number" min="1" class="form-control max_duration" value="${slot.max_duration}" placeholder="Duration" required>
                                        </div>
                                        <div class="col-md">
                                            <label>Max Ad Slots:</label>
                                            <input type="number" min="1" class="form-control max_ad_slot" value="${slot.max_ad_slot ?? 1}" placeholder="Max Ads" required>
                                        </div>
                                        <div class="col-md-1 d-flex justify-content-center align-items-center">
                                            <button type="button" class="btn btn-danger btn-sm remove_slot_row">✕</button>
                                        </div>
                                    </div>
                                `);
                            });

                        } else {
                            $('#edit_slots_container').append(slotRow());
                        }

                        $('#edit_slot_modal').modal('show');


                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(response);
                    }
                })
            })

            $(document).on("submit","#edit_slot_form" , function(event) {
                event.preventDefault();
                var id = $('#edit_template_id').val();
                var template_name = $('#edit_template_name').val();
                var slots = [];

                $('#edit_slots_container .slot-row').each(function() {
                    slots.push({
                        id: $(this).find('.slot_id').val(), // peut être vide
                        segment_name: $(this).find('.segment_name').val(),
                        name: $(this).find('.slot_name').val(),
                        max_duration: $(this).find('.max_duration').val(),
                        max_ad_slot: $(this).find('.max_ad_slot').val(),
                    });
                });

                /*var name = $('#edit_slot_form #name').val();
                var cpm = $('#edit_slot_form #cpm').val();
                var id = $('#edit_slot_form #id').val();
                var max_duration = $('#edit_slot_form #max_duration').val();*/
                var url = '{{ url('') }}' + '/slots/'+ id;

                $.ajax({
                    url: url,
                    type: 'PUT',
                    method: 'PUT',
                    data: {
                        template_name: template_name,
                        slots: slots,
                        /*name: name,
                        cpm: cpm,
                        max_duration:max_duration,*/
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
                    $('#edit_slot_modal').modal('hide');
                    $("#wait-modal").modal('hide');

                    get_slots()
                        swal.fire({
                            title: 'Done!',
                            text: 'Template & Slots updated successfully ',
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

            $(document).on('click', '#edit_add_slot_row', function() {
                $('#edit_slots_container').append(slotRow());
            });

            $(document).on('click', '.remove_slot_row', function() {
                $(this).closest('.slot-row').remove();
            });

        });
    </script>
@endsection
