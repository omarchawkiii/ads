{{-- Affichage du résultat de l'auto linking (movie code ↔ SPL) renvoyé par le NOC --}}
<script>
    function buildAutoLinkingHtml(al) {
        if (!al) return '';

        var esc = function (v) {
            return $('<div>').text(v == null ? '' : String(v)).html();
        };

        if (!al.enabled) {
            return '<div class="mt-1 text-muted"><i class="mdi mdi-link-off"></i> Auto linking is disabled on the NOC.</div>';
        }

        var html = '<div class="mt-1"><strong><i class="mdi mdi-link-variant"></i> Auto linking</strong>';

        if (al.message) {
            html += '<div class="text-muted">' + esc(al.message) + '</div>';
        }

        var results = al.results || [];
        if (!results.length) {
            if (!al.message) html += '<div class="text-muted">No movie to link (no matching movie with a show in the campaign period).</div>';
            return html + '</div>';
        }

        var statuses = {
            linked:         { cls: 'text-success', icon: 'mdi-check-circle',  label: 'Linked' },
            scheduled:      { cls: 'text-info',    icon: 'mdi-calendar-clock', label: 'Link scheduled' },
            already_linked: { cls: 'text-success', icon: 'mdi-check-all',      label: 'Already linked' },
            queued:         { cls: 'text-warning', icon: 'mdi-timer-sand',     label: 'Queued (TMS unreachable)' },
            no_spl:         { cls: 'text-danger',  icon: 'mdi-close-circle',   label: 'No SPL generated' },
            missing_cpls:   { cls: 'text-danger',  icon: 'mdi-alert',          label: 'SPL has missing CPLs' },
            ingest_failed:  { cls: 'text-danger',  icon: 'mdi-close-circle',   label: 'SPL ingest failed' },
            spl_not_in_lms: { cls: 'text-danger',  icon: 'mdi-close-circle',   label: 'SPL not in LMS' },
            failed:         { cls: 'text-danger',  icon: 'mdi-close-circle',   label: 'Failed' }
        };
        var ingests = {
            already_in_lms: 'SPL already in LMS',
            ingested:       'SPL ingested to TMS',
            updated:        'SPL re-sent to TMS (updated)',
            queued:         'SPL ingest queued'
        };

        var linked = results.filter(function (r) { return ['linked', 'scheduled', 'already_linked'].indexOf(r.status) !== -1; }).length;
        html += '<div class="text-muted">' + linked + ' / ' + results.length + ' movie(s) linked or scheduled</div>';

        html += '<table class="table table-sm table-bordered mt-1 mb-0" style="font-size:0.95em;">'
            + '<thead><tr><th>Location</th><th>Movie</th><th>Status</th></tr></thead><tbody>';

        results.forEach(function (r) {
            var s = statuses[r.status] || { cls: 'text-muted', icon: 'mdi-help-circle', label: r.status };
            var detail = [];
            if (r.ingest && ingests[r.ingest]) detail.push(ingests[r.ingest]);
            if (r.status === 'scheduled' && r.scheduled_at) detail.push('on ' + r.scheduled_at);
            if (r.message) detail.push(r.message);

            html += '<tr>'
                + '<td>' + esc(r.location) + '</td>'
                + '<td>' + esc(r.title) + '<br><small class="text-muted">' + esc(r.movie_code) + '</small></td>'
                + '<td class="' + s.cls + '"><i class="mdi ' + s.icon + '"></i> ' + esc(s.label)
                + (detail.length ? '<br><small class="text-muted">' + esc(detail.join(' · ')) + '</small>' : '')
                + '</td>'
                + '</tr>';
        });

        return html + '</tbody></table></div>';
    }
</script>
