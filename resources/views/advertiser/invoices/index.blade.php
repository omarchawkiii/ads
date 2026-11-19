@extends('advertiser.layouts.app')
@section('title') My Invoices @endsection

@section('content')
<div class="">

    <div class="card card-body py-3">
        <div class="row align-items-center">
            <div class="col-12">
                <div class="d-sm-flex align-items-center justify-space-between">
                    <h4 class="mb-4 mb-sm-0 card-title">My Invoices</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body table-responsive">
            <table id="invoices-table" class="table table-striped table-bordered display text-nowrap dataTable">
                <thead>
                    <tr class="text-center">
                        <th class="text-center">Invoice #</th>
                        <th class="text-center">Campaign</th>
                        <th class="text-center">Subtotal (RM)</th>
                        <th class="text-center">Tax</th>
                        <th class="text-center">Total (RM)</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Issued</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

</div>

<div class="modal fade" id="view_invoice_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white">Invoice <span id="v_number"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="inv_detail"></div>
            <div class="modal-footer">
                <a href="#" id="v_download" class="btn btn-outline-secondary" target="_blank"><i class="mdi mdi-download"></i> Download PDF</a>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_script')
@include('partials.invoice_detail')
<script>
    function getInvoiceStatusBadge(status) {
        const map = {
            'paid':    '<span class="badge bg-success-subtle text-success">Paid</span>',
            'unpaid':  '<span class="badge bg-danger-subtle text-danger">Unpaid</span>',
            'pending': '<span class="badge bg-warning-subtle text-warning">Pending</span>',
        };
        return map[status] ?? '<span class="badge bg-secondary-subtle text-secondary">' + status + '</span>';
    }

    var waitModal = new bootstrap.Modal(document.getElementById('wait-modal'));

    function loadInvoices() {
        waitModal.show();

        if ($.fn.DataTable.isDataTable('#invoices-table')) {
            $('#invoices-table').DataTable().destroy();
        }

        $.ajax({
            url: '{{ route("advertiser.invoices.list") }}',
            type: 'GET',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function (response) {
                var rows = '';
                $.each(response.invoices, function (i, inv) {
                    rows +=
                        '<tr class="text-center">' +
                        '<td class="align-middle">' + (inv.number ?? '—') + '</td>' +
                        '<td class="align-middle">' + (inv.compaign ? inv.compaign.name : '—') + '</td>' +
                        '<td class="align-middle">' + parseFloat(inv.total_ht ?? 0).toFixed(2) + '</td>' +
                        '<td class="align-middle">' + parseFloat(inv.tax ?? 0).toFixed(2) + '%</td>' +
                        '<td class="align-middle fw-bold">' + parseFloat(inv.total_ttc ?? 0).toFixed(2) + '</td>' +
                        '<td class="align-middle">' + getInvoiceStatusBadge(inv.status) + '</td>' +
                        '<td class="align-middle">' + (inv.date ?? '—') + '</td>' +
                        '<td class="align-middle">' +
                            '<button type="button" class="btn btn-sm btn-primary me-1 view_invoice" data-id="' + inv.id + '"><i class="mdi mdi-magnify"></i> View</button>' +
                            '<a href="{{ url('') }}/advertiser/invoices/' + inv.id + '/download" ' +
                               'class="btn btn-sm btn-outline-primary" target="_blank">' +
                               '<i class="mdi mdi-download"></i> Download' +
                            '</a>' +
                        '</td>' +
                        '</tr>';
                });

                $('#invoices-table tbody').html(rows);
                waitModal.hide();

                $('#invoices-table').DataTable({
                    iDisplayLength: 10,
                    destroy: true,
                    autoWidth: false,
                    language: { search: '_INPUT_', searchPlaceholder: 'Search...' }
                });
            },
            error: function () {
                waitModal.hide();
                Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to load invoices.' });
            }
        });
    }

    loadInvoices();

    $(document).on('click', '.view_invoice', function () {
        var id = $(this).data('id');
        $('#inv_detail').html('<div class="text-center py-4"><div class="spinner-border"></div></div>');
        $('#v_number').text('');
        $('#v_download').attr('href', "{{ url('') }}/advertiser/invoices/" + id + '/download');
        $('#view_invoice_modal').modal('show');

        $.getJSON("{{ url('') }}/advertiser/invoices/" + id + '/show')
            .done(function (data) {
                $('#v_number').text(data.invoice.number || '');
                $('#inv_detail').html(window.renderInvoiceDetail(data, { admin: false }));
            })
            .fail(function () {
                $('#inv_detail').html('<div class="text-danger">Unable to load this invoice.</div>');
            });
    });
</script>
@endsection
