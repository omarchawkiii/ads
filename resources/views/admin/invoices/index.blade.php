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


        <div class="row" id="inv_summary">
            <div class="col-md-3 col-6">
                <div class="card"><div class="card-body py-3">
                    <div class="text-muted small">Total billed</div>
                    <div class="fs-5 fw-semibold" id="sum_billed">–</div>
                </div></div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card"><div class="card-body py-3">
                    <div class="text-muted small">Collected</div>
                    <div class="fs-5 fw-semibold text-success" id="sum_paid">–</div>
                </div></div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card"><div class="card-body py-3">
                    <div class="text-muted small">Outstanding</div>
                    <div class="fs-5 fw-semibold text-warning" id="sum_outstanding">–</div>
                </div></div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card"><div class="card-body py-3">
                    <div class="text-muted small">Overdue</div>
                    <div class="fs-5 fw-semibold text-danger" id="sum_overdue">–</div>
                </div></div>
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
                            <th class="text-center">Tax</th>
                            <th class="text-center">Total (RM)</th>
                            <th class="text-center">Paid (RM)</th>
                            <th class="text-center">Remaining (RM)</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Issued</th>
                            <th class="text-center">Due</th>
                            <th class="text-center" style="width:160px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
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
                    <div id="inv_content"><div id="inv_detail"></div></div>
                </div>


                <div class="modal-footer">
                    <button type="button" id="v_discount" class="btn btn-outline-warning"><i class="mdi mdi-percent"></i> Discount</button>
                    <button type="button" id="v_download" class="btn btn-outline-secondary">Download PDF</button>
                    <button type="button" id="v_close" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Discount Modal ── --}}
    <div class="modal fade" id="discount_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <form id="discount_form">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title">Discount — <span id="disc_number"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="disc_invoice_id">
                        <input type="hidden" id="disc_subtotal">
                        <input type="hidden" id="disc_tax">
                        <input type="hidden" id="disc_paid">

                        <div class="row mb-3">
                            <div class="col-6"><div class="small text-muted">Subtotal (RM)</div><div class="fw-semibold" id="disc_subtotal_txt">–</div></div>
                            <div class="col-6"><div class="small text-muted">Already paid (RM)</div><div class="fw-semibold" id="disc_paid_txt">–</div></div>
                        </div>

                        <div class="row">
                            <div class="col-5 mb-3">
                                <label class="form-label fw-semibold">Type</label>
                                <select class="form-select" id="disc_type">
                                    <option value="percent">Percentage (%)</option>
                                    <option value="fixed">Fixed amount (RM)</option>
                                </select>
                            </div>
                            <div class="col-7 mb-3">
                                <label class="form-label fw-semibold">Value <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="disc_value" min="0.01" step="0.01" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Reason <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="disc_reason" rows="2" maxlength="500" required
                                      placeholder="e.g. Loyalty discount, commercial gesture, service incident…"></textarea>
                        </div>
                        <div class="alert alert-light border small mb-0" id="disc_preview">–</div>
                        <div class="text-danger small mt-2" id="disc_error"></div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-outline-danger" id="disc_remove" style="display:none;">Remove discount</button>
                        <div>
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-warning" id="disc_submit">Apply discount</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── Payment Modal ── --}}
    <div class="modal fade" id="pay_invoice_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <form id="pay_invoice_form" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">Add Payment — <span id="pay_inv_number"></span></h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="pay_invoice_id">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Invoice Total (RM)</label>
                            <input type="text" class="form-control" id="pay_total" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Already Paid (RM)</label>
                            <input type="text" class="form-control" id="pay_already_paid" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Remaining (RM)</label>
                            <input type="text" class="form-control" id="pay_remaining" disabled>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Amount to Pay (RM) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="pay_amount" name="amount"
                                   min="0.01" step="0.01" placeholder="e.g. 500.00" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Note / Justification</label>
                            <textarea class="form-control" id="pay_note" name="note" rows="3"
                                      placeholder="Payment reference, bank transfer ID, etc."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Proof of Payment <small class="text-muted">(PDF / image, max 5 MB)</small></label>
                            <input type="file" class="form-control" id="pay_proof" name="proof"
                                   accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success" id="pay_submit_btn">
                            <span id="pay_spinner" class="spinner-border spinner-border-sm me-1 d-none"></span>
                            Record Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── Proof Viewer Modal ── --}}
    <div class="modal fade" id="proof_viewer_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title">Proof of Payment</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-3" id="proof_viewer_body">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
                <div class="modal-footer">
                    <a id="proof_download_link" href="#" class="btn btn-outline-secondary" target="_blank">
                        <i class="mdi mdi-download"></i> Download
                    </a>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('custom_script')
    <script src="{{ asset('assets/js/helper.js') }}?v={{ filemtime(public_path('assets/js/helper.js')) }}"></script>
    @include('partials.invoice_detail')
    <script>
        // Override inline to avoid browser cache issues on this page
        function getInvoiceStatusText(status) {
            var map = {
                'paid':           '<span class="badge bg-success-subtle text-success">Paid</span>',
                'unpaid':         '<span class="badge bg-danger-subtle text-danger">Unpaid</span>',
                'pending':        '<span class="badge bg-warning-subtle text-warning">Pending</span>',
                'partially_paid': '<span class="badge bg-info-subtle text-info">Partially Paid</span>',
            };
            if (!status) return '<span class="badge bg-danger-subtle text-danger">Unpaid</span>';
            return map[status] || '<span class="badge bg-secondary-subtle text-secondary">' + status + '</span>';
        }

        $(function() {
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
                                index++;
                                var advertiser = value.compaign && value.compaign.user
                                    ? jv(value.compaign.user.name) + ' ' + jv(value.compaign.user.last_name)
                                    : '—';
                                var campaign = value.compaign ? jv(value.compaign.name) : '—';
                                var money = function(v) { return parseFloat(v || 0).toFixed(2); };
                                var status = value.overdue
                                    ? '<span class="badge bg-danger">Overdue</span>'
                                    : getInvoiceStatusText(value.status);
                                result = result +
                                    '<tr class="odd text-center">' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' + getInvoiceNumber(value.number) + ' </td>' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' + advertiser + ' </td>' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' + campaign + ' </td>' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' + money(value.total_ht) + ' </td>' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' + (value.tax || 0) + '% </td>' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' + money(value.total_ttc) + ' </td>' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' + money(value.paid) + ' </td>' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' + money(value.remaining) + ' </td>' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' + status + ' </td>' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' + formatDateEN(value.date) + ' </td>' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' + formatDateEN(value.due_date) + ' </td>' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' +
                                    '<button data-id="' + value.id + '" type="button" class="view btn mb-1 btn-rounded btn-primary m-1">' +
                                    '<i class="mdi mdi-magnify"></i>' +
                                    '</button>' +
                                    (value.remaining > 0
                                        ? '<button data-id="' + value.id + '" data-total="' + value.total_ttc + '" data-number="' + value.number + '" type="button" class="pay_invoice btn mb-1 btn-rounded btn-success m-1">' +
                                          '<i class="mdi mdi-cash-plus"></i> Pay' +
                                          '</button>'
                                        : '') +
                                    '</td>' +
                                    '</tr>';
                            });
                            var sm = response.summary || {};
                            var rm = function(v) { return 'RM ' + parseFloat(v || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}); };
                            $('#sum_billed').text(rm(sm.billed));
                            $('#sum_paid').text(rm(sm.paid));
                            $('#sum_outstanding').text(rm(sm.outstanding));
                            $('#sum_overdue').text(rm(sm.overdue_total) + ' (' + (sm.overdue_count || 0) + ')');
                            $('#invoices-table tbody').html(result)
                            $('#wait-modal').modal('hide');
                            // $('#loader-modal').css('display','none')
                            /***** refresh datatable **** **/

                            var invoices = $('#invoices-table').DataTable({
                                "iDisplayLength": 10,
                                "order": [],
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
                        var inv = data.invoice;
                        $('#v_number').text(jv(getInvoiceNumber(inv.number)));
                        $('#inv_detail').html(window.renderInvoiceDetail(data, { admin: true }));

                        $('#view_invoice_modal #v_download').attr('data-invoice-id', data.invoice.id);
                        window.currentInvoice = data;
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
            /* ── Discount ── */
            function rm(v) { return parseFloat(v || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}); }

            function discountPreview() {
                var sub = parseFloat($('#disc_subtotal').val()) || 0;
                var tax = parseFloat($('#disc_tax').val()) || 0;
                var paid = parseFloat($('#disc_paid').val()) || 0;
                var val = parseFloat($('#disc_value').val()) || 0;
                var amount = $('#disc_type').val() === 'percent' ? Math.round(sub * val) / 100 : val;
                var net = sub - amount;
                var total = Math.round(net * (1 + tax / 100) * 100) / 100;
                var msg = 'Discount: <strong>RM ' + rm(amount) + '</strong> &rarr; net RM ' + rm(net) +
                          ' + tax ' + tax + '% = new total <strong>RM ' + rm(total) + '</strong>';
                var bad = amount >= sub || total < paid;
                $('#disc_preview').html(msg).toggleClass('border-danger', bad);
                $('#disc_submit').prop('disabled', val <= 0 || bad);
            }
            $(document).on('input change', '#disc_type, #disc_value', discountPreview);

            $(document).on('click', '#v_discount', function () {
                var d = window.currentInvoice;
                if (!d) return;
                var inv = d.invoice, t = d.totals;
                $('#disc_invoice_id').val(inv.id);
                $('#disc_number').text(inv.number);
                $('#disc_subtotal').val(t.subtotal); $('#disc_tax').val(t.tax_rate); $('#disc_paid').val(t.paid);
                $('#disc_subtotal_txt').text(rm(t.subtotal)); $('#disc_paid_txt').text(rm(t.paid));
                $('#disc_type').val(t.discount_type || 'percent');
                $('#disc_value').val(t.discount_value || '');
                $('#disc_reason').val(t.discount_reason || '');
                $('#disc_error').text('');
                $('#disc_remove').toggle(t.discount > 0);
                discountPreview();
                bootstrap.Modal.getOrCreateInstance(document.getElementById('discount_modal')).show();
            });

            function sendDiscount(payload) {
                var id = $('#disc_invoice_id').val();
                $('#disc_error').text('');
                return $.ajax({
                    url: "{{ url('') }}/invoices/" + id + '/discount',
                    type: 'POST',
                    data: Object.assign({ _method: 'PUT', _token: "{{ csrf_token() }}" }, payload),
                    headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" }
                }).done(function (res) {
                    bootstrap.Modal.getOrCreateInstance(document.getElementById('discount_modal')).hide();
                    get_invoices();
                    // recharge le détail ouvert
                    $.getJSON("{{ url('') }}/invoices/" + id + '/show', function (data) {
                        window.currentInvoice = data;
                        $('#inv_detail').html(window.renderInvoiceDetail(data, { admin: true }));
                    });
                    Swal.fire({ title: 'Done!', text: res.message, icon: 'success', timer: 1800, showConfirmButton: false });
                }).fail(function (xhr) {
                    var errs = xhr.responseJSON && xhr.responseJSON.errors;
                    $('#disc_error').text(errs ? Object.values(errs)[0][0] : 'Operation failed.');
                });
            }

            $(document).on('submit', '#discount_form', function (e) {
                e.preventDefault();
                sendDiscount({ type: $('#disc_type').val(), value: $('#disc_value').val(), reason: $('#disc_reason').val() });
            });
            $(document).on('click', '#disc_remove', function () { sendDiscount({ type: 'none' }); });

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

            /* ── Open payment modal ── */
            $(document).on('click', '.pay_invoice', function () {
                const id     = $(this).data('id');
                const total  = parseFloat($(this).data('total'));
                const number = $(this).data('number');

                // Fetch existing payments to compute already-paid
                $.get("{{ url('') }}/invoices/" + id + '/show', function (data) {
                    const paid = data.invoice.payments
                        ? data.invoice.payments.reduce(function (s, p) { return s + parseFloat(p.amount); }, 0)
                        : 0;
                    const remaining = Math.max(0, total - paid);

                    $('#pay_invoice_id').val(id);
                    $('#pay_inv_number').text(number);
                    $('#pay_total').val(parseFloat(total).toFixed(2));
                    $('#pay_already_paid').val(parseFloat(paid).toFixed(2));
                    $('#pay_remaining').val(parseFloat(remaining).toFixed(2));
                    $('#pay_amount').attr('max', remaining).val('');
                    $('#pay_note').val('');
                    $('#pay_proof').val('');

                    var payModal = new bootstrap.Modal(document.getElementById('pay_invoice_modal'));
                    payModal.show();
                });
            });

            /* ── Submit payment ── */
            $(document).on('submit', '#pay_invoice_form', function (e) {
                e.preventDefault();

                const id       = $('#pay_invoice_id').val();
                const formData = new FormData(this);
                formData.append('_token', '{{ csrf_token() }}');

                $('#pay_submit_btn').prop('disabled', true);
                $('#pay_spinner').removeClass('d-none');

                $.ajax({
                    url:         "{{ url('') }}/invoices/" + id + '/payments',
                    type:        'POST',
                    data:        formData,
                    processData: false,
                    contentType: false,
                })
                .done(function (res) {
                    bootstrap.Modal.getInstance(document.getElementById('pay_invoice_modal')).hide();
                    Swal.fire({
                        icon:  'success',
                        title: 'Payment Recorded',
                        html:  'Paid: <strong>RM ' + parseFloat(res.paid).toFixed(2) + '</strong><br>' +
                               'Remaining: <strong>RM ' + parseFloat(res.remaining).toFixed(2) + '</strong>',
                        timer: 3000,
                        showConfirmButton: false,
                    });
                    get_invoices();
                })
                .fail(function (xhr) {
                    let msg = 'Operation failed.';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        msg = Object.values(xhr.responseJSON.errors).flat().join('\n');
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    Swal.fire({ icon: 'error', title: 'Error', text: msg });
                })
                .always(function () {
                    $('#pay_submit_btn').prop('disabled', false);
                    $('#pay_spinner').addClass('d-none');
                });
            });

            /* ── View proof popup ── */
            $(document).on('click', '.view_proof_btn', function () {
                const paymentId = $(this).data('id');
                const proof     = $(this).data('proof');
                const proofUrl  = "{{ url('') }}/payments/" + paymentId + "/proof";
                const ext       = proof.split('.').pop().toLowerCase();

                $('#proof_download_link').attr('href', proofUrl);
                $('#proof_viewer_body').html(
                    '<div class="spinner-border text-primary" role="status"></div>'
                );

                var proofModal = new bootstrap.Modal(document.getElementById('proof_viewer_modal'));
                proofModal.show();

                if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
                    var img = new Image();
                    img.onload = function () {
                        $('#proof_viewer_body').html(
                            '<img src="' + proofUrl + '" class="img-fluid rounded" style="max-height:75vh;">'
                        );
                    };
                    img.onerror = function () {
                        $('#proof_viewer_body').html(
                            '<p class="text-danger">Unable to load the image.</p>'
                        );
                    };
                    img.src = proofUrl;
                } else if (ext === 'pdf') {
                    $('#proof_viewer_body').html(
                        '<iframe src="' + proofUrl + '" style="width:100%;height:75vh;border:none;"></iframe>'
                    );
                } else {
                    $('#proof_viewer_body').html(
                        '<p>Preview not available. <a href="' + proofUrl + '" target="_blank">Click here to open the file.</a></p>'
                    );
                }
            });
        });
    </script>
@endsection
