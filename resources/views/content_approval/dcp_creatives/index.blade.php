@extends('content_approval.layouts.app')
@section('title') DCP Creatives @endsection

@section('content')
<div class="">
    <div class="card card-body py-3">
        <div class="row align-items-center">
            <div class="col-12">
                <div class="d-sm-flex align-items-center justify-space-between">
                    <h4 class="mb-4 mb-sm-0 card-title">DCP Creatives</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body table-responsive">
            <table id="dcps-table" class="table table-striped table-bordered display text-nowrap dataTable">
                <thead>
                    <tr class="text-center">
                        <th>#</th>
                        <th>Name</th>
                        <th>Customer</th>
                        <th>Duration</th>
                        <th>Edit Rate</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

{{-- Detail / Approve / Reject Modal --}}
<div class="modal" id="detail_modal" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center bg-primary">
                <h4 class="modal-title text-white">DCP Creative Detail</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="detail_id">
                {{-- Preview player --}}
                <div id="preview_wrap" class="mb-3 d-none">
                    <div id="preview_generating" class="d-none text-center py-3 text-muted">
                        <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                        Generation in progress, please wait...
                    </div>
                    <div id="preview_failed" class="d-none alert alert-warning py-2 mb-0 d-flex align-items-center justify-content-between">
                        <span><i class="mdi mdi-alert-outline me-1"></i> Preview not available (FFmpeg error or MXF codec not supported).</span>
                        <button type="button" class="btn btn-sm btn-outline-warning ms-3" id="btn_retry_preview">
                            <i class="mdi mdi-refresh me-1"></i> Retry
                        </button>
                    </div>
                    <video id="preview_player" class="d-none w-100 rounded" controls style="max-height:320px; background:#000;">
                        Your browser does not support the video tag.
                    </video>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>Name:</strong> <span id="d_name"></span></li>
                            <li class="list-group-item"><strong>Duration:</strong> <span id="d_duration"></span>s</li>
                            <li class="list-group-item"><strong>Edit Rate:</strong> <span id="d_edit_rate"></span></li>
                            <li class="list-group-item"><strong>Customer:</strong> <span id="d_customer"></span></li>
                            <li class="list-group-item"><strong>Category:</strong> <span id="d_category"></span></li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>Status:</strong> <span id="d_status"></span></li>
                            <li class="list-group-item"><strong>Added:</strong> <span id="d_date"></span></li>
                            <li class="list-group-item"><strong>Approval Note:</strong> <span id="d_note"></span></li>
                        </ul>
                    </div>
                </div>

                <hr>
                <div class="mb-3">
                    <label for="approval_note" class="form-label">Note (required for reject)</label>
                    <textarea class="form-control" id="approval_note" rows="3" placeholder="Leave a note..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-danger-subtle text-danger" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="btn_approve">
                    <i class="mdi mdi-check"></i> Approve
                </button>
                <button type="button" class="btn btn-danger" id="btn_reject">
                    <i class="mdi mdi-close"></i> Reject
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_script')
<script src="{{ asset('assets/js/helper.js') }}?v={{ filemtime(public_path('assets/js/helper.js')) }}"></script>
<script>
$(function () {

    function statusBadge(status) {
        if (status === 'pending')  return '<span class="badge bg-warning text-dark">Pending</span>';
        if (status === 'approved') return '<span class="badge bg-success">Approved</span>';
        if (status === 'rejected') return '<span class="badge bg-danger">Rejected</span>';
        return '<span class="badge bg-secondary">—</span>';
    }

    var previewPollTimer = null;

    function stopPreviewPoll() {
        if (previewPollTimer) {
            clearInterval(previewPollTimer);
            previewPollTimer = null;
        }
    }

    function showPreview(previewStatus, previewUrl) {
        $('#preview_wrap').removeClass('d-none');
        $('#preview_generating').addClass('d-none');
        $('#preview_failed').addClass('d-none');
        $('#preview_player').addClass('d-none').attr('src', '');

        if (previewStatus === 'ready' && previewUrl) {
            $('#preview_player').attr('src', previewUrl).removeClass('d-none')[0].load();
        } else if (previewStatus === 'failed') {
            $('#preview_failed').removeClass('d-none');
        } else {
            // pending or processing — show spinner and start polling
            $('#preview_generating').removeClass('d-none');
            startPreviewPoll($('#detail_id').val());
        }
    }

    function startPreviewPoll(id) {
        stopPreviewPoll();
        previewPollTimer = setInterval(function () {
            $.ajax({
                url: '{{ url('') }}/content-approval/dcp_creatives/' + id + '/preview-status',
                method: 'GET',
            })
            .done(function (res) {
                if (res.preview_status === 'ready' || res.preview_status === 'failed') {
                    stopPreviewPoll();
                    showPreview(res.preview_status, res.preview_url);
                }
            });
        }, 5000); // poll every 5 seconds
    }

    // Stop polling when modal closes
    $(document).on('hidden.bs.modal', '#detail_modal', function () {
        stopPreviewPoll();
        var player = document.getElementById('preview_player');
        if (player) { player.pause(); player.src = ''; }
    });

    function loadDcps() {
        $('#wait-modal').modal('show');
        $('#dcps-table').dataTable().fnDestroy();

        $.ajax({
            url: '{{ route('content_approval.dcp_creatives.list') }}',
            method: 'GET',
        })
        .done(function (response) {
            var result = '';
            $.each(response.dcps, function (i, dcp) {
                result += '<tr class="text-center">' +
                    '<td>' + (i + 1) + '</td>' +
                    '<td class="text-start">' + dcp.name + '</td>' +
                    '<td>' + (dcp.customer || '—') + '</td>' +
                    '<td>' + dcp.duration + 's</td>' +
                    '<td>' + (dcp.edit_rate || '—') + '</td>' +
                    '<td>' + (dcp.category || '—') + '</td>' +
                    '<td>' + statusBadge(dcp.status) + '</td>' +
                    '<td>' + (dcp.created_at || '—') + '</td>' +
                    '<td>' +
                        '<button type="button" class="btn btn-sm btn-info btn-review m-1" data-id="' + dcp.id + '" title="Review">' +
                            '<i class="mdi mdi-eye"></i>' +
                        '</button>' +
                    '</td>' +
                '</tr>';
            });
            $('#dcps-table tbody').html(result);
            $('#dcps-table').DataTable({
                iDisplayLength: 15,
                destroy: true,
                language: { search: '_INPUT_', searchPlaceholder: 'Search...' }
            });
        })
        .always(function () {
            $('#wait-modal').modal('hide');
        });
    }

    loadDcps();

    $(document).on('click', '.btn-review', function () {
        var id = $(this).data('id');
        $('#wait-modal').modal('show');

        $.ajax({
            url: '{{ url('') }}/content-approval/dcp_creatives/' + id + '/show',
            method: 'GET',
        })
        .done(function (response) {
            var dcp = response.dcp;
            $('#detail_id').val(dcp.id);
            $('#d_name').text(dcp.name);
            $('#d_duration').text(dcp.duration);
            $('#d_edit_rate').text(dcp.edit_rate || '—');
            $('#d_customer').text(dcp.customer ? dcp.customer.name : '—');
            $('#d_category').text(dcp.compaign_category ? dcp.compaign_category.name : '—');
            $('#d_status').html(statusBadge(dcp.status));
            $('#d_date').text(dcp.created_at ? dcp.created_at.substring(0, 10) : '—');
            $('#d_note').text(dcp.approval_note || '—');
            $('#approval_note').val('');
            showPreview(dcp.preview_status, response.preview_url);
            $('#detail_modal').modal('show');
        })
        .always(function () {
            $('#wait-modal').modal('hide');
        });
    });

    $(document).on('click', '#btn_approve', function () {
        var id = $('#detail_id').val();
        var note = $('#approval_note').val();

        $.ajax({
            url: '{{ url('') }}/content-approval/dcp_creatives/' + id + '/approve',
            method: 'POST',
            data: { _method: 'PUT', _token: '{{ csrf_token() }}', approval_note: note },
        })
        .done(function () {
            $('#detail_modal').modal('hide');
            loadDcps();
            Swal.fire({ title: 'Done!', text: 'DCP approved successfully.', icon: 'success', confirmButtonText: 'Continue' });
        })
        .fail(function () {
            Swal.fire({ title: 'Error', text: 'Operation failed.', icon: 'error' });
        });
    });

    $(document).on('click', '#btn_retry_preview', function () {
        var id = $('#detail_id').val();
        $(this).prop('disabled', true);

        $.ajax({
            url: '{{ url('') }}/content-approval/dcp_creatives/' + id + '/retry-preview',
            method: 'POST',
            data: { _token: '{{ csrf_token() }}' },
        })
        .done(function () {
            showPreview('pending', null);
        })
        .fail(function () {
            Swal.fire({ title: 'Error', text: 'Could not restart preview generation.', icon: 'error' });
        });
    });

    $(document).on('click', '#btn_reject', function () {
        var id = $('#detail_id').val();
        var note = $('#approval_note').val();

        if (!note.trim()) {
            Swal.fire({ title: 'Note required', text: 'Please provide a rejection reason.', icon: 'warning' });
            return;
        }

        $.ajax({
            url: '{{ url('') }}/content-approval/dcp_creatives/' + id + '/reject',
            method: 'POST',
            data: { _method: 'PUT', _token: '{{ csrf_token() }}', approval_note: note },
        })
        .done(function () {
            $('#detail_modal').modal('hide');
            loadDcps();
            Swal.fire({ title: 'Done!', text: 'DCP rejected.', icon: 'success', confirmButtonText: 'Continue' });
        })
        .fail(function (xhr) {
            var msg = 'Operation failed.';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                msg = Object.values(xhr.responseJSON.errors).flat().join(' ');
            }
            Swal.fire({ title: 'Error', text: msg, icon: 'error' });
        });
    });

});
</script>
@endsection
