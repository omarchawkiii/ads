{{--
    Rendu du détail d'une facture (admin et annonceur).
    JSON attendu : réponse de InvoiceController@show / my_invoice_show (App\Services\InvoiceDetails).
    Usage : $('#conteneur').html(window.renderInvoiceDetail(data, { admin: true|false }))
--}}
<script>
    window.renderInvoiceDetail = function (d, opts) {
        opts = opts || {};
        var inv = d.invoice, t = d.totals, c = d.campaign;

        function esc(v) { return $('<div>').text(v == null ? '' : String(v)).html(); }
        function money(v) { return parseFloat(v || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }
        function date(s) {
            if (!s) return '–';
            var p = String(s).substring(0, 10).split('-');
            if (p.length < 3) return esc(s);
            var m = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            return parseInt(p[2], 10) + ' ' + m[parseInt(p[1], 10) - 1] + ' ' + p[0];
        }
        function dt(s) { return s ? esc(String(s).substring(0, 16).replace('T', ' ')) : '–'; }
        function secs(sec) {
            sec = Number(sec) || 0;
            var h = Math.floor(sec / 3600), m = Math.floor((sec % 3600) / 60), s = sec % 60;
            return (h ? h + 'h ' : '') + (h || m ? m + 'm ' : '') + s + 's';
        }
        function row(label, value) {
            return '<div class="d-flex justify-content-between border-bottom py-1 small">' +
                   '<span class="text-muted me-3">' + label + '</span><span class="text-end">' + value + '</span></div>';
        }
        function list(arr) {
            return arr && arr.length ? arr.map(esc).join(', ') : '–';
        }
        function card(title, body, noPad) {
            return '<div class="card mb-3"><div class="card-header fw-semibold">' + title + '</div>' +
                   '<div class="card-body' + (noPad ? ' p-0 table-responsive' : '') + '">' + body + '</div></div>';
        }
        var statusMap = {
            paid: '<span class="badge bg-success">Paid</span>',
            unpaid: '<span class="badge bg-danger-subtle text-danger">Unpaid</span>',
            partially_paid: '<span class="badge bg-info-subtle text-info">Partially paid</span>'
        };
        var status = t.overdue ? '<span class="badge bg-danger">Overdue</span>'
                               : (statusMap[inv.status] || '<span class="badge bg-secondary">' + esc(inv.status || '–') + '</span>');

        var html = '';

        /* ---- En-tête : annonceur / client / facture ---- */
        var adv = d.advertiser;
        var left = adv
            ? row('Advertiser', esc(adv.name)) + row('Email', esc(adv.email || '–')) +
              (adv.type ? row('Type', esc(adv.type)) : '')
            : row('Advertiser', '–');
        (d.customers || []).forEach(function (cu) {
            left += '<div class="mt-2 small"><strong>' + esc(cu.name) + '</strong>' +
                    (cu.brand ? ' <span class="text-muted">(' + esc(cu.brand) + ')</span>' : '') +
                    (cu.address ? '<br>' + esc(cu.address) : '') +
                    (cu.email || cu.phone ? '<br>' + esc([cu.email, cu.phone].filter(Boolean).join(' · ')) : '') +
                    (cu.pic ? '<br>Contact: ' + esc(cu.pic) : '') + '</div>';
        });
        var right = row('Invoice', esc(inv.number || '–')) + row('Status', status) +
                    row('Issued', date(inv.date)) + row('Due', date(inv.due_date));
        html += '<div class="row">' +
                '<div class="col-md-6">' + card('Billed to', left) + '</div>' +
                '<div class="col-md-6">' + card('Invoice', right) + '</div></div>';

        /* ---- Campagne ---- */
        if (c) {
            var locs = (c.locations || []).map(function (l) { return esc(l.name) + (l.chain ? ' <span class="text-muted">(' + esc(l.chain) + ')</span>' : ''); }).join(', ') || '–';
            var adsList = (c.ads || []).map(function (a) { return esc(a.name) + (a.duration ? ' <span class="text-muted">(' + a.duration + 's)</span>' : ''); }).join(', ') || '–';
            var camp = '<div class="row"><div class="col-md-6">' +
                row('Campaign', esc(c.name)) +
                row('Period', date(c.start_date) + ' → ' + date(c.end_date) + (c.days ? ' <span class="text-muted">(' + c.days + ' days)</span>' : '')) +
                row('Objective', esc(c.objective || '–')) + row('Category', esc(c.category || '–')) +
                row('Language', esc(c.language || '–')) +
                '</div><div class="col-md-6">' +
                row('Cinema chains', list(c.cinema_chains)) +
                row('Locations', locs) +
                row('Slots', list(c.slots)) +
                row('Hall types', list(c.hall_types)) +
                row('Movies', list(c.movies)) +
                row('Ads', adsList) +
                '</div></div>';
            html += card('Campaign', camp);
        }

        /* ---- Détail de facturation ---- */
        var lines = '';
        function mx(v) { return Number(v == null ? 1 : v).toFixed(2).replace(/\.?0+$/, ''); }
        $.each(inv.lines || [], function (i, l) {
            if (l.line_type === 'minimum') {
                lines += '<tr class="table-warning"><td colspan="3">' + esc(l.dcp_name) + ' &ndash; ' + esc(l.location_name) + '</td>' +
                         '<td class="text-end">&ndash;</td><td class="text-end">&ndash;</td><td class="text-end">&ndash;</td>' +
                         '<td class="text-end fw-semibold">' + money(l.amount) + '</td></tr>';
                return;
            }
            var base = Number(l.location_price) + Number(l.slot_price) + Number(l.hall_price);
            var tip = 'Position x' + mx(l.multiplier_position) + ' | Targeting x' + mx(l.multiplier_targeting) + (l.targeting_level ? ' (' + l.targeting_level + ')' : '') +
                      ' | Length x' + mx(l.multiplier_length) + ' | Season x' + mx(l.multiplier_season) + ' | Volume x' + mx(l.multiplier_volume) +
                      (Number(l.multiplier_raw) > Number(l.multiplier) ? ' | capped from x' + mx(l.multiplier_raw) : '');
            lines += '<tr><td>' + esc(l.dcp_name) + '</td><td>' + esc(l.slot_name || '–') + '</td><td>' + esc(l.location_name) + '</td>' +
                     '<td class="text-end">' + Number(l.played).toLocaleString('en-US') + '</td>' +
                     '<td class="text-end">' + money(base) + '</td>' +
                     '<td class="text-end" title="' + esc(tip) + '">&times;' + mx(l.multiplier) + (Number(l.multiplier_raw) > Number(l.multiplier) ? ' <small class="text-warning">cap</small>' : '') + '</td>' +
                     '<td class="text-end">' + money(l.unit_price != null ? l.unit_price : base) + '</td>' +
                     '<td class="text-end fw-semibold">' + money(l.amount) + '</td></tr>';
        });
        html += card('Billing details <small class="text-muted fw-normal">(plays &times; base price &times; multipliers; hover a multiplier for its breakdown)</small>',
            '<table class="table table-sm mb-0 text-nowrap"><thead class="table-light"><tr>' +
            '<th>Ad</th><th>Slot</th><th>Location</th><th class="text-end">Plays</th><th class="text-end">Base (RM)</th>' +
            '<th class="text-end">Multiplier</th><th class="text-end">Per play (RM)</th><th class="text-end">Amount (RM)</th></tr></thead><tbody>' +
            (lines || '<tr><td colspan="8" class="text-muted">No detail available for this invoice.</td></tr>') +
            '</tbody></table>', true);

        /* ---- Preuve de diffusion ---- */
        var pop = d.proof_of_play;
        if (pop && pop.fetched) {
            var popRows = '';
            pop.dcps.forEach(function (a) {
                popRows += '<tr class="table-light"><td colspan="2"><strong>' + esc(a.name) + '</strong></td>' +
                           '<td class="text-end"><strong>' + a.played + '</strong></td><td class="text-end">' + a.incomplete + '</td>' +
                           '<td class="text-end">' + a.failed + '</td><td class="text-end">' + secs(a.played_seconds) + '</td></tr>';
                a.by_location.forEach(function (l) {
                    popRows += '<tr><td class="ps-4">' + esc(l.location) + (l.cinema_chain ? ' <span class="text-muted small">(' + esc(l.cinema_chain) + ')</span>' : '') + '</td><td></td>' +
                               '<td class="text-end">' + l.played + '</td><td class="text-end">' + l.incomplete + '</td>' +
                               '<td class="text-end">' + l.failed + '</td><td class="text-end">' + secs(l.played_seconds) + '</td></tr>';
                    l.by_day.forEach(function (day) {
                        popRows += '<tr class="text-muted small"><td class="ps-5">' + date(day.date) + '</td><td></td>' +
                                   '<td class="text-end">' + day.played + '</td><td class="text-end">' + day.incomplete + '</td>' +
                                   '<td class="text-end">' + day.failed + '</td><td></td></tr>';
                    });
                });
            });
            html += card('Proof of play <small class="text-muted fw-normal">(confirmed by the cinema servers; NOC data up to ' +
                         (pop.synced_until ? dt(pop.synced_until) : 'unknown') + ')</small>',
                '<table class="table table-sm mb-0 text-nowrap"><thead class="table-light"><tr><th>Ad / location / day</th><th></th>' +
                '<th class="text-end">Played</th><th class="text-end">Incomplete</th><th class="text-end">Failed</th><th class="text-end">Duration</th></tr></thead><tbody>' +
                popRows + '</tbody></table>', true);
        }

        /* ---- Totaux ---- */
        var tot = row('Subtotal (RM)', money(t.subtotal));
        if (t.discount) {
            var dl = 'Discount' + (t.discount_type === 'percent' ? ' (' + t.discount_value + '%)' : '');
            tot += row(dl, '<span class="text-success">- ' + money(t.discount) + '</span>');
            if (opts.admin && t.discount_reason) {
                tot += '<div class="small text-muted py-1">Reason: ' + esc(t.discount_reason) + '</div>';
            }
            tot += row('Net subtotal (RM)', money(t.net));
        }
        tot += row('Tax (' + t.tax_rate + '%)', money(t.tax_amount)) +
               '<div class="d-flex justify-content-between py-2"><strong>Total (RM)</strong><strong class="fs-5">' + money(t.total) + '</strong></div>' +
               row('Paid (RM)', '<span class="text-success">' + money(t.paid) + '</span>') +
               row('Remaining (RM)', '<strong class="' + (t.remaining > 0 ? 'text-danger' : 'text-success') + '">' + money(t.remaining) + '</strong>');
        html += '<div class="row"><div class="col-md-6 ms-auto">' + card('Summary', tot) + '</div></div>';

        /* ---- Paiements ---- */
        if (inv.payments && inv.payments.length) {
            var pay = '';
            $.each(inv.payments, function (i, p) {
                var proof = '–';
                if (p.proof) {
                    proof = opts.admin
                        ? '<button type="button" class="btn btn-sm btn-outline-primary view_proof_btn" data-id="' + p.id + '" data-proof="' + esc(p.proof) + '"><i class="mdi mdi-eye"></i> View</button>'
                        : '<span class="badge bg-secondary-subtle text-secondary">Attached</span>';
                }
                pay += '<tr><td>' + date(p.created_at) + '</td><td class="text-end">' + money(p.amount) + '</td><td>' + esc(p.note || '–') + '</td><td>' + proof + '</td></tr>';
            });
            html += card('Payment history',
                '<table class="table table-sm mb-0"><thead class="table-light"><tr><th>Date</th><th class="text-end">Amount (RM)</th><th>Note</th><th>Proof</th></tr></thead><tbody>' +
                pay + '</tbody></table>', true);
        }

        return html;
    };
</script>
