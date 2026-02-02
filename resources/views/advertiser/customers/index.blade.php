@extends('advertiser.layouts.app')
@section('title')
    Customer
@endsection

@section('content')
<div class=" py-4">

    <div class="card card-body py-3">
        <div class="row align-items-center">
            <div class="col-12">
                <div class="d-sm-flex align-items-center justify-space-between">
                    <h4 class="mb-4 mb-sm-0 card-title">Customers</h4>
                    <nav aria-label="breadcrumb" class="ms-auto">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <button class="btn bg-success text-white" id="create_customer">
                                    + New Customer
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
            <table id="customers-table" class="table table-striped table-bordered display text-nowrap dataTable">
                <thead>
                    <tr class="text-center">
                        <th style="width:80px;">ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th style="width:160px;">Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

{{-- CREATE MODAL --}}
<div class="modal" id="create_customer_modal" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <form id="create_customer_form">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title text-white">Create Customer</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" class="form-control" id="name" required>
                    </div>
                    <div class="mb-3">
                        <label>Address</label>
                        <input type="text" class="form-control" id="address">
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" class="form-control" id="email">
                    </div>
                    <div class="mb-3">
                        <label>Phone</label>
                        <input type="text" class="form-control" id="phone">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn bg-danger-subtle text-danger" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class=" save-btn btn btn-success">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- EDIT MODAL --}}
<div class="modal" id="edit_customer_modal" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <form id="edit_customer_form">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title text-white">Edit Customer</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="id">

                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" class="form-control" id="name" required>
                    </div>
                    <div class="mb-3">
                        <label>Address</label>
                        <input type="text" class="form-control" id="address">
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" class="form-control" id="email">
                    </div>
                    <div class="mb-3">
                        <label>Phone</label>
                        <input type="text" class="form-control" id="phone">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn bg-danger-subtle text-danger" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="save-btn btn btn-success">
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
$(function () {

    $(document).on('click', '#create_customer', function () {
        $('#create_customer_modal').modal('show');
    });

    function get_customers() {

        $("#customers-table").dataTable().fnDestroy();
        let url = "{{ url('') }}/advertiser/customers/list";
        let result = "";
        $('#wait-modal').modal('show');
        $.get(url, function (response) {

            $.each(response.customers, function (index, value) {
                index++;
                result += `
                    <tr class="text-center">
                        <td>${index}</td>
                        <td>${value.name}</td>
                        <td>${value.email ?? ''}</td>
                        <td>${value.phone ?? ''}</td>
                        <td>
                            <button id="${value.id}" class="edit btn btn-warning btn-rounded m-1">
                                <i class="mdi mdi-tooltip-edit"></i>
                            </button>
                            <button id="${value.id}" class="delete btn btn-danger btn-rounded m-1">
                                <i class="mdi mdi-delete"></i>
                            </button>
                        </td>
                    </tr>`;
            });

            $('#customers-table tbody').html(result);

            $('#customers-table').DataTable({
                iDisplayLength: 10,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search..."
                }
            });
            $('#wait-modal').modal('hide');
        });
    }

    get_customers();

    // CREATE
    $(document).on("submit", "#create_customer_form", function (e) {
        e.preventDefault();

        let modal = $('#create_customer_modal');
        let btn   = modal.find('.save-btn');

        $.ajax({
            url: "{{ url('') }}/advertiser/customers",
            method: "POST",
            data: {
                name: modal.find('#name').val(),
                address: modal.find('#address').val(),
                email: modal.find('#email').val(),
                phone: modal.find('#phone').val(),
                _token: "{{ csrf_token() }}"
            },
            beforeSend: function () {

                btn.prop('disabled', true);
                $('#wait-modal').modal('show');

            }
        })
        .done(function () {
            modal.modal('hide');
            get_customers();
            Swal.fire("Done!", "Customer created successfully.", "success");
        })
        .fail(function () {
            Swal.fire("Error", "Operation failed.", "error");
        })
        .always(function () {
            // 🔓 re-enable button + hide loader
            btn.prop('disabled', false);
            modal.modal('hide');
        });
    })

    // DELETE
    $(document).on('click', '.delete', function () {
        let id = $(this).attr('id');

        Swal.fire({
            title: 'Delete Customer?',
            text: 'Are you sure you want to delete this customer?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it'
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.post("{{ url('') }}/advertiser/customers/" + id, {
                _method: 'DELETE',
                _token: "{{ csrf_token() }}"
            })
            .done(function () {
                get_customers();
                Swal.fire("Done!", "Customer deleted successfully.", "success");
            })
            .fail(function () {
                Swal.fire("Error", "Deletion failed.", "error");
            });
        });
    });

    // EDIT LOAD
    $(document).on('click', '.edit', function () {
        let id = $(this).attr('id');

        $.get("{{ url('') }}/advertiser/customers/" + id + "/show", function (response) {
            let c = response.customer;
            $('#edit_customer_modal #id').val(c.id);
            $('#edit_customer_modal #name').val(c.name);
            $('#edit_customer_modal #address').val(c.address);
            $('#edit_customer_modal #email').val(c.email);
            $('#edit_customer_modal #phone').val(c.phone);

            $('#edit_customer_modal').modal('show');
        });
    });

    // UPDATE
    $(document).on("submit", "#edit_customer_form", function (e) {
        e.preventDefault();
        let modal = $('#edit_customer_modal');
        let btn   = modal.find('.save-btn');

        let id = $('#edit_customer_modal #id').val();

        $.ajax({
            url: "{{ url('') }}/advertiser/customers/" + id,
            method: 'PUT',

            data: {
                name: $('#edit_customer_modal #name').val(),
                address: $('#edit_customer_modal #address').val(),
                email: $('#edit_customer_modal #email').val(),
                phone: $('#edit_customer_modal #phone').val(),
                _token: "{{ csrf_token() }}"
            },
            beforeSend: function () {
                btn.prop('disabled', true);
                $('#wait-modal').modal('show');

            }
        })
        .done(function () {
            $('#edit_customer_modal').modal('hide');
            get_customers();
            Swal.fire("Done!", "Customer updated successfully.", "success");
            modal.modal('hide');
        })
        .fail(function () {
            Swal.fire("Error", "Operation failed.", "error");
        })
        .always(function () {
            modal.modal('hide');
        });
    });

});
</script>
@endsection
