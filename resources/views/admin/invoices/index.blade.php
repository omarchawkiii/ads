@extends('admin.layouts.app')
@section('title')
    Invoices
@endsection
@section('content')
    <div class="">

        <div class="card card-body py-3">
            <div class="row align-items-center">
                <div class="col-12">
                    <div class="d-sm-flex align-items-center justify-space-between">
                        <h4 class="mb-4 mb-sm-0 card-title">Invoices</h4>
                        <nav aria-label="breadcrumb" class="ms-auto">
                            <ol class="breadcrumb">

                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>


        <div class="card">
            <div class="card-body table-responsive">
                <table id="invoices-table" class="table table-striped table-bordered display text-nowrap dataTable">
                    <thead>
                        <tr class="text-center">
                            <th class="text-center" style="width:160px;">Invoice #</th>
                            <th class="text-center">Advertiser</th>
                            <th class="text-center">Campaign</th>
                            <th class="text-center">Subtotal (RM)</th>
                            <th class="text-center">Tax (6%)</th>
                            <th class="text-center">Total</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Issued</th>
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

    <div class="modal  " id="create_invoice_modal" tabindex="-1" aria-labelledby="bs-example-modal-lg" aria-modal="true"
        role="dialog">
        <div class="modal-dialog modal-md  modal-dialog-centered">
            <div class="modal-content">
                <form method="post" id="create_invoice_form">
                    <div class="modal-header d-flex align-items-center  bg-primary ">
                        <h4 class="modal-title text-white " id="myLargeModalLabel ">
                            Create Invoice
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


    <div class="modal " id="edit_invoice_modal" tabindex="-1" aria-labelledby="bs-example-modal-lg" aria-modal="true"
        role="dialog">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <form method="post" id="edit_invoice_form">
                    <div class="modal-header d-flex align-items-center  bg-primary ">
                        <h4 class="modal-title text-white " id="myLargeModalLabel ">
                            Edit Invoice
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

    <!-- View Invoice Modal -->
    <div class="modal fade" id="view_invoice_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xxl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white d-flex align-items-center">
                    <h5 class="modal-title text-white">Invoice: <span id="v_number">–</span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div id="inv_content">
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <div><strong>Advertiser:</strong> <span id="v_advertiser">–</span></div>
                                <div><strong>Campaign:</strong> <span id="v_campaign">–</span></div>
                                <div><strong>Billing Period:</strong> <span id="v_billing_period">–</span></div>
                            </div>
                            <div class="col-md-4 text-end">
                                <div><strong>Invoice Date:</strong> <span id="v_invoice_date">–</span></div>
                                <div class="mt-2"><strong>Status:</strong><span id="v_status">–</span></div>
                            </div>
                        </div>
                    </div>

                    <!-- CPM Breakdown: statique -->
                    <div class="card mb-3">
                        <div class="card-header">💰 CPM Breakdown</div>
                        <div class="card-body p-0">
                            <table class="table mb-0">
                                <tbody>
                                    <tr>
                                        <td><strong>Description</strong></td>
                                        <td><strong>Value</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Base CPM</td>
                                        <td>RM 30.00</td>
                                    </tr>
                                    <tr>
                                        <td>Targeting Surcharge</td>
                                        <td>RM 12.00</td>
                                    </tr>
                                    <tr>
                                        <td>Slot Multiplier (x2)</td>
                                        <td>RM 84.00</td>
                                    </tr>
                                    <tr>
                                        <td>Impressions</td>
                                        <td>53,000</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Gross Cost</strong></td>
                                        <td class="fw-bold">RM 4,452.00</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Proof of Play: statique -->
                    <div class="card mb-3">
                        <div class="card-header">🎬 Proof of Play</div>
                        <div class="card-body p-0">
                            <table class="table  mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Cinema</th>
                                        <th>Film</th>
                                        <th>Slot</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Apr 5</td>
                                        <td>19:30</td>
                                        <td>KLCC</td>
                                        <td>Kung Fu Panda 4</td>
                                        <td>Slot A</td>
                                    </tr>
                                    <tr>
                                        <td>Apr 6</td>
                                        <td>21:45</td>
                                        <td>Sunway Pyramid</td>
                                        <td>Inside Out 2</td>
                                        <td>Slot A</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-body p-0">
                            <div class="ms-auto text-end pe-3">
                                <div><small class="">Subtotal (RM) : </small> <span id="v_subtotal"
                                        class="fw-semibold">RM 0.00</span></div>
                                <div class="mt-1"><small class="">Tax : </small> <span id="v_tax"
                                        class="fw-semibold">RM 0.00</span></div>
                                <div class="mt-1"><small class="">Total (RM): </small> <span id="v_total"
                                        class="fw-bold fs-5">RM 0.00</span></div>
                            </div>
                        </div>
                    </div>


                </div> <!-- end inv_content -->


                <div class="modal-footer">
                    <button type="button" id="v_download" class="btn btn-outline-secondary">Download PDF</button>
                    <button type="button" id="v_close" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('custom_script')
    <script src="{{ asset('assets/js/helper.js') }}"></script>
    <script>
        $(function() {
            $(document).on('click', '#create_invoice', function() {
                $('#create_invoice_modal').modal('show');
            })

            function get_invoices() {
                $('#wait-modal').modal('show');

                $("#invoices-table").dataTable().fnDestroy();
                var url = "{{ url('') }}" + '/invoices/list';
                var result = " ";
                $.ajax({
                        url: url,
                        method: 'GET',
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            $.each(response.invoices, function(index, value) {
                                var user_invoices = "";
                                index++;
                                result = result +
                                    '<tr class="odd text-center">' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' +
                                    getInvoiceNumber(value.number) + ' </td>' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' +
                                    value.compaign.user.name + ' ' + value.compaign.user.name +
                                    ' </td>' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' +
                                    value.compaign.name + ' </td>' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' +
                                    value.total_ht + ' </td>' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' +
                                    value.tax + '% </td>' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' +
                                    value.total_ttc + ' </td>' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' +
                                    getInvoiceStatusText(value.status) + ' </td>' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' +
                                    formatDateEN(value.due_date) + ' </td>' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' +
                                    '<button id="' + value.id +
                                    '" type="button" class="view ustify-content-center btn mb-1 btn-rounded btn-primary m-1" >' +
                                    '<i class="mdi mdi-magnify"></i>' +
                                    '</button>' +


                                    '</td>' +
                                    '</tr>';
                            });
                            $('#invoices-table tbody').html(result)
                            $('#wait-modal').modal('hide');
                            // $('#loader-modal').css('display','none')
                            /***** refresh datatable **** **/

                            var invoices = $('#invoices-table').DataTable({
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
            get_invoices();

            $(document).on("submit", "#create_invoice_modal", function(event) {

                event.preventDefault();
                var name = $('#create_invoice_modal #name ').val();
                var cpm = $('#create_invoice_modal #cpm').val();

                var url = '{{ url('') }}' + '/invoices';

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

                        get_invoices();
                        Swal.fire({
                            title: 'Done!',
                            text: 'invoice deleted successfully.',
                            icon: 'success',
                            confirmButtonText: 'Continue'
                        });
                        reset_form('#create_invoice_modal form')
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
                        $('#create_invoice_modal').modal('hide');
                    });

            })

            $(document).on('click', '.delete', function() {
                var id = $(this).attr('id');
                console.log(id)
                console.log(encodeURIComponent(id))
                const url = '{{ url('') }}' + '/invoices/' + encodeURIComponent(id);
                const csrf = '{{ csrf_token() }}';

                // SweetAlert2 confirm
                Swal.fire({
                    title: 'Delete invoice?',
                    text: 'Are you sure you want to delete this invoice?',
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

                            get_invoices();
                            Swal.fire({
                                title: 'Done!',
                                text: 'invoice deleted successfully.',
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
                var invoice = $(this).attr('id');
                var url = '{{ url('') }}' + '/invoices/' + invoice + '/show/';

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
                        $('#edit_invoice_modal').modal('show');
                        $('#edit_invoice_modal #name').val(response.invoice.name)
                        $('#edit_invoice_modal #cpm').val(response.invoice.cpm)
                        $('#edit_invoice_modal #id').val(invoice)

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(response);
                    }
                })
            })

            $(document).on("submit", "#edit_invoice_form", function(event) {
                event.preventDefault();
                var name = $('#edit_invoice_form #name').val();
                var cpm = $('#edit_invoice_form #cpm').val();
                var id = $('#edit_invoice_form #id').val();
                var url = '{{ url('') }}' + '/invoices/' + id;

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
                        $('#edit_invoice_modal').modal('hide');
                        get_invoices()
                        swal.fire({
                            title: 'Done!',
                            text: 'invoice Updated Successfully ',
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
                        $('#edit_invoice_modal').modal('hide');
                        $("#wait-modal").modal('hide');
                        Swal.fire({
                            title: 'Error',
                            text: 'Operation failed.',
                            icon: 'error'
                        });
                    })
            })

            $(document).on('click', '.view', function() {
                const id = $(this).data('id') || $(this).attr('id');
                const url = "{{ url('') }}/invoices/" + encodeURIComponent(id) + '/show';

                // show modal + hide content until loaded
                $('#view_invoice_modal').modal('show');
                $('#inv_content').hide();

                $.ajax({
                        url: url,
                        method: 'GET',
                        beforeSend: function() {
                            $("#wait-modal").modal('show');
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    })
                    .done(function(data) {
                        console.log(getInvoiceStatusText(data.invoice.status))


                        $('#v_number').text(jv(getInvoiceNumber(data.invoice.number)));
                        $('#v_advertiser').text(jv(data.invoice.compaign.user.name) + " " + jv(data
                            .invoice.compaign.user.last_name));
                        $('#v_campaign').text(jv(data.invoice.compaign.name));
                        $('#v_billing_period').text(jv(formatDateEN(data.invoice.date)));
                        $('#v_invoice_date').text(jv(data.invoice.due_date));
                        $('#v_status').html(getInvoiceStatusText(data.invoice.status) || 'unpaid');

                        $('#v_subtotal').text(data.invoice.total_ht ?? 0);
                        $('#v_tax').text(data.invoice.tax + '%' ?? 0);
                        $('#v_total').text(data.invoice.total_ttc ?? +0);

                        $('#view_invoice_modal #v_download').attr('data-invoice-id', data.invoice.id);
                        // hide loader & show content
                        $("#wait-modal").modal('hide');
                    })
                    .fail(function(xhr) {
                        console.error(xhr);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Impossible de charger la facture.'
                        });
                        $('#view_invoice_modal').modal('hide');
                        $("#wait-modal").modal('hide');
                    })
                    .always(function() {
                        $("#wait-modal").modal('hide');
                        $('#inv_content').show();
                    });
            });
            $(document).on('click', '#v_download', function() {
                const invoiceId = $(this).attr('data-invoice-id');
                if (!invoiceId) {
                    alert('Invoice ID missing.');
                    return;
                }

                // URL de téléchargement — adapte si tu as un prefix ou nom de route différent
                const downloadUrl = "{{ url('') }}/invoices/" + encodeURIComponent(invoiceId) +
                    '/download';

                // Option simple : rediriger le navigateur vers l'URL pour déclencher le download
                // (Le controller Laravel doit renvoyer response()->download() ou PDF::download()).
                window.location = downloadUrl;

                // Alternative (fetch + blob) — si tu veux garder la page et forcer le "save as" :
                // fetch(downloadUrl, { credentials: 'same-origin' })
                //   .then(r => r.blob())
                //   .then(blob => {
                //     const url = window.URL.createObjectURL(blob);
                //     const a = document.createElement('a');
                //     a.href = url;
                //     a.download = 'invoice.pdf'; // tu peux changer le nom dynamiquement
                //     document.body.appendChild(a);
                //     a.click();
                //     a.remove();
                //     window.URL.revokeObjectURL(url);
                //   })
                //   .catch(e => { alert('Download failed'); console.error(e); });
            });
        });
    </script>
@endsection
