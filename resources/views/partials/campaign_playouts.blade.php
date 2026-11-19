{{--
    Diffusions confirmées (NOC ads_played) dans la modale "Campaign details".
    Prérequis : <div id="cmpg_playouts"></div> dans la modale.
    Variables : $playoutsBase (URL, '{id}' remplacé), $refreshBase (URL PUT admin, optionnel).
    Appel : window.loadPlayouts(campaignId)
--}}
<script>
    (function () {
        var playoutsBase = @json($playoutsBase);
        var refreshBase  = @json($refreshBase ?? null);
        var csrf         = @json(csrf_token());
        var currentId    = null;

        function esc(v) {
            return $('<div>').text(v == null ? '' : String(v)).html();
        }
        function fmtDt(s) { return s ? esc(s.substring(0, 16).replace('T', ' ')) : '–'; }
        function fmtSeconds(sec) {
            sec = Number(sec) || 0;
            var h = Math.floor(sec / 3600), m = Math.floor((sec % 3600) / 60), s = sec % 60;
            return (h ? h + 'h ' : '') + (h || m ? m + 'm ' : '') + s + 's';
        }
        function counters(o) {
            return '<td class="text-end fw-semibold">' + o.played + '</td>' +
                   '<td class="text-end">' + o.incomplete + '</td>' +
                   '<td class="text-end">' + o.failed + '</td>';
        }

        function render(summary, chainResults) {
            var html = '<hr><div class="d-flex justify-content-between align-items-center mb-2">' +
                       '<h6 class="mb-0 fw-semibold">Ads played (confirmed by the NOC)</h6>';
            if (refreshBase) {
                html += '<button type="button" class="btn btn-sm btn-outline-primary" id="btn_refresh_playouts">' +
                        '<i class="ti ti-refresh"></i> Refresh</button>';
            }
            html += '</div>';

            (chainResults || []).filter(function (c) { return !c.ok; }).forEach(function (c) {
                html += '<div class="alert alert-danger py-1 small mb-2"><strong>' + esc(c.cinema_chain) + ':</strong> ' + esc(c.message) + '</div>';
            });

            if (!summary.fetched) {
                html += '<div class="text-muted small">No data fetched from the NOC yet.' +
                        (refreshBase ? ' Click "Refresh".' : '') + '</div>';
                $('#cmpg_playouts').html(html);
                return;
            }

            if (summary.synced_until) {
                html += '<div class="alert alert-warning py-1 small mb-2">NOC data is up to date until <strong>' +
                        fmtDt(summary.synced_until) + '</strong>. Later plays are not counted yet.' +
                        ' <span class="text-muted">(fetched ' + fmtDt(summary.last_fetched_at) + ')</span></div>';
            } else {
                html += '<div class="alert alert-warning py-1 small mb-2">At least one NOC has not synchronised any log yet: counts may be incomplete.</div>';
            }

            html += '<div class="table-responsive"><table class="table table-sm align-middle mb-0">' +
                    '<thead><tr><th>Ad</th><th class="text-end">Played</th><th class="text-end">Incomplete</th>' +
                    '<th class="text-end">Failed</th><th class="text-end">Duration</th></tr></thead><tbody>';

            summary.dcps.forEach(function (d, i) {
                var cid = 'pl_' + i;
                html += '<tr><td>' + esc(d.name) +
                        (d.fetched ? '' : ' <span class="badge bg-secondary">not fetched</span>') +
                        (d.fetched && !d.played ? ' <span class="text-muted small">no play yet</span>' : '') +
                        (d.by_location.length ? ' <a href="#" class="small ms-1 pl-toggle" data-target="' + cid + '">details</a>' : '') +
                        '</td>' + counters(d) + '<td class="text-end">' + fmtSeconds(d.played_seconds) + '</td></tr>';

                if (d.by_location.length) {
                    html += '<tr id="' + cid + '" style="display:none"><td colspan="5" class="bg-body-tertiary">';
                    if (d.by_chain.length > 1) {
                        html += '<div class="small mb-2">' + d.by_chain.map(function (c) {
                            return '<strong>' + esc(c.cinema_chain) + '</strong>: ' + c.played + ' played';
                        }).join(' &middot; ') + '</div>';
                    }
                    d.by_location.forEach(function (l) {
                        html += '<div class="small mb-2"><strong>' + esc(l.location) + '</strong>' +
                                (d.by_chain.length > 1 ? ' <span class="text-muted">(' + esc(l.cinema_chain) + ')</span>' : '') +
                                ' — ' + l.played + ' played, ' + l.incomplete + ' incomplete, ' + l.failed + ' failed, ' +
                                fmtSeconds(l.played_seconds);
                        if (l.by_day.length) {
                            html += '<table class="table table-sm table-bordered mb-0 mt-1 w-auto"><thead><tr><th>Date</th>' +
                                    '<th class="text-end">Played</th><th class="text-end">Incomplete</th><th class="text-end">Failed</th></tr></thead><tbody>';
                            l.by_day.forEach(function (day) {
                                html += '<tr><td>' + esc(day.date) + '</td>' + counters(day) + '</tr>';
                            });
                            html += '</tbody></table>';
                        }
                        html += '</div>';
                    });
                    html += '</td></tr>';
                }
            });
            html += '</tbody></table></div>';
            $('#cmpg_playouts').html(html);
        }

        window.loadPlayouts = function (id) {
            currentId = id;
            $('#cmpg_playouts').html('<div class="text-muted small py-2">Loading ads played…</div>');
            $.getJSON(playoutsBase.replace('{id}', encodeURIComponent(id)))
                .done(function (summary) { render(summary, null); })
                .fail(function () { $('#cmpg_playouts').html('<div class="text-danger small">Unable to load ads played.</div>'); });
        };

        $(document).on('click', '.pl-toggle', function (e) {
            e.preventDefault();
            $('#' + $(this).data('target')).toggle();
        });

        $(document).on('click', '#btn_refresh_playouts', function () {
            var $btn = $(this).prop('disabled', true).text('Refreshing…');
            $.ajax({
                url: refreshBase.replace('{id}', encodeURIComponent(currentId)),
                type: 'POST',
                data: { _method: 'PUT', _token: csrf },
                headers: { 'X-CSRF-TOKEN': csrf }
            }).done(function (res) {
                render(res.summary, res.chains);
            }).fail(function () {
                $btn.prop('disabled', false).text('Refresh');
                Swal.fire('Error', 'Unable to refresh ads played.', 'error');
            });
        });
    })();
</script>
