@extends('advertiser.layouts.app')
@section('title')
    DCP Creative
@endsection
@section('content')
    <div class="">
        <div class="card card-body py-3">
            <div class="row align-items-center">
                <div class="col-12">
                    <div class="d-sm-flex align-items-center justify-space-between">
                        <h4 class="mb-4 mb-sm-0 card-title"> DCP Creative</h4>
                        <nav aria-label="breadcrumb" class="ms-auto">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item" aria-current="page">


                                    <button class="btn bg-success  text-white" data-bs-toggle="modal"
                                        data-bs-target="#uploadModal">
                                        + New DCP Creative
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
                <table id="dcp_creatives-table" class="table table-striped table-bordered display text-nowrap dataTable">
                    <thead>
                        <tr class="text-center">
                            <th class="text-center" style="width:160px;">ID</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">UUID</th>
                            <th class="text-center">Duration</th>
                            <th class="text-center">Category</th>
                            <th class="text-center">My Client</th>
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

    <div class="modal  " id="create_dcp_creative_modal" tabindex="-1" aria-labelledby="bs-example-modal-lg"
        aria-modal="true" role="dialog">
        <div class="modal-dialog modal-md  modal-dialog-centered">
            <div class="modal-content">
                <form method="post" id="create_dcp_creative_form" enctype="multipart/form-data">
                    <div class="modal-header d-flex align-items-center  bg-primary ">
                        <h4 class="modal-title text-white " id="myLargeModalLabel ">
                            Create DCP Creative
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="xml_file" class="form-label">Fichier XML</label>
                            <input type="file" class="form-control" id="xml_file" name="xml_file" accept=".xml"
                                required>

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

    <div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">New DCP Creative</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form id="uploadForm">
                        <div class="row g-3">

                            <div class="col-12">
                                <label class="form-label fw-semibold">ZIP / TAR / 7Z File <span class="text-danger">*</span></label>
                                <div id="dropZone" class="dz-area">
                                    <div class="dz-inner" id="dropZoneInner">
                                        <i class="mdi mdi-cloud-upload-outline dz-icon"></i>
                                        <div class="dz-text">Drag &amp; Drop your file here</div>
                                        <div class="dz-subtext">or <span class="dz-browse-link">click to browse</span></div>
                                        <div id="dz-filename" class="dz-filename d-none"></div>
                                    </div>
                                </div>
                                <input type="file" id="fileInput" accept=".zip,.tar,.7z" class="d-none">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                                <select class="form-select" id="compaign_category_id" required>
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories ?? [] as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold"> Objective</label>
                                <select class="form-select" id="compaign_objective_id">
                                    <option value="">-- Select Objective --</option>
                                    @foreach($objectives ?? [] as $obj)
                                        <option value="{{ $obj->id }}">{{ $obj->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Language</label>
                                <select class="form-select" id="langue_id">
                                    <option value="">-- Select Language --</option>
                                    @foreach($langues ?? [] as $langue)
                                        <option value="{{ $langue->id }}">{{ $langue->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Gender Target</label>
                                <select class="form-select" id="gender_id">
                                    <option value="">-- Select Gender --</option>
                                    @foreach($genders ?? [] as $gender)
                                        <option value="{{ $gender->id }}">{{ $gender->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label fw-semibold mb-0">Target Types</label>
                                    <div class="d-flex gap-1">
                                        <button type="button" class="btn btn-outline-primary btn-xs py-0 px-2 select-all-btn" data-target="#target_type_ids">
                                            <i class="mdi mdi-check-all"></i> All
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-xs py-0 px-2 clear-all-btn" data-target="#target_type_ids">
                                            <i class="mdi mdi-close"></i> Clear
                                        </button>
                                    </div>
                                </div>
                                <select class="select2-multi w-100" id="target_type_ids" multiple>
                                    @foreach($targetTypes ?? [] as $tt)
                                        <option value="{{ $tt->id }}">{{ $tt->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label fw-semibold mb-0">Interests</label>
                                    <div class="d-flex gap-1">
                                        <button type="button" class="btn btn-outline-primary btn-xs py-0 px-2 select-all-btn" data-target="#interest_ids">
                                            <i class="mdi mdi-check-all"></i> All
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-xs py-0 px-2 clear-all-btn" data-target="#interest_ids">
                                            <i class="mdi mdi-close"></i> Clear
                                        </button>
                                    </div>
                                </div>
                                <select class="select2-multi w-100" id="interest_ids" multiple>
                                    @foreach($interests ?? [] as $interest)
                                        <option value="{{ $interest->id }}">{{ $interest->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            @if($isDirect)
                                @if($autoCustomer)
                                    <input type="hidden" id="customer_id" value="{{ $autoCustomer->id }}">
                                @else
                                    {{-- no customer yet — handled by JS popup below --}}
                                    <input type="hidden" id="customer_id" value="">
                                @endif
                            @else
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">My Client <span class="text-danger">*</span></label>
                                <select class="form-select" id="customer_id" required>
                                    <option value="">-- Select Client --</option>
                                    @foreach($customers ?? [] as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" id="startBtn" class="btn btn-success">Start uploading</button>
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    {{-- ── DCP Detail Modal ── --}}
    <div class="modal fade" id="dcpDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white ">
                        <i class="mdi mdi-information-outline me-1"></i>
                        DCP Creative Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="dcpDetailBody">
                    <div class="text-center py-4">
                        <div class="spinner-border text-info" role="status"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal 2 : Progression (séparé, non fermable pendant l'upload) -->
    <div class="modal fade" id="progressModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title"> Uploading…</h5>
                    <button type="button" class="btn-close" id="xCloseProgress" disabled aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-2">
                        <div class="progress">
                            <div id="progressBar" class="progress-bar" role="progressbar" style="width:0%">0%</div>
                        </div>
                        <div class="small text-muted mt-1" id="progressText">Initialization...</div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" id="cancelBtn" class="btn btn-outline-danger">Cancel</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection


@section('custom_script')
    {{-- Disable AMD loader temporarily so Select2 attaches to window.jQuery --}}
    <script>var define_bak = window.define; window.define = undefined;</script>
    <script src="{{ asset('/assets/libs/select2/dist/js/select2.min.js') }}"></script>
    <script>window.define = define_bak;</script>
    <script src="{{ asset('assets/js/helper.js') }}?v={{ filemtime(public_path('assets/js/helper.js')) }}"></script>

    <script>
        $(function() {
            const CSRF = '{{ csrf_token() }}';
            const CHUNK_SIZE_FALLBACK = 10 * 1024 * 1024; // 10 Mo si le backend ne renvoie pas

            // ── Select2 multi ──────────────────────────────────────────────
            if (typeof $.fn.select2 === 'function') {
                $('.select2-multi').select2({
                    placeholder: '-- Select --',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('#uploadModal'),
                });
            } else {
                console.warn('Select2 not loaded – falling back to native multi-select');
            }

            // Select All / Clear All
            $(document).on('click', '.select-all-btn', function () {
                const $sel = $($(this).data('target'));
                $sel.find('option').prop('selected', true);
                $sel.trigger('change');
            });
            $(document).on('click', '.clear-all-btn', function () {
                const $sel = $($(this).data('target'));
                $sel.val(null).trigger('change');
            });

            // ── Drag-and-drop zone ─────────────────────────────────────────
            const $dropZone  = $('#dropZone');
            const $fileInput = $('#fileInput');

            function showFile(file) {
                if (!file) return;
                const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
                $('#dz-filename')
                    .removeClass('d-none')
                    .html(`<i class="mdi mdi-file-check me-1 text-success"></i><strong>${file.name}</strong> <span class="text-muted">(${sizeMB} MB)</span>`);
                $dropZone.addClass('dz-has-file');
            }

            function clearDropZone() {
                $fileInput.val('');
                $('#dz-filename').addClass('d-none').html('');
                $dropZone.removeClass('dz-has-file dz-drag-over');
            }

            // Click on zone → open file picker
            $dropZone.on('click', function () { $fileInput.trigger('click'); });

            $fileInput.on('change', function () {
                showFile(this.files[0]);
            });

            // Drag events
            $dropZone.on('dragover dragenter', function (e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).addClass('dz-drag-over');
            });
            $dropZone.on('dragleave dragend', function (e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).removeClass('dz-drag-over');
            });
            $dropZone.on('drop', function (e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).removeClass('dz-drag-over');
                const files = e.originalEvent.dataTransfer.files;
                if (files && files.length > 0) {
                    // Transfer to real file input via DataTransfer
                    const dt = new DataTransfer();
                    dt.items.add(files[0]);
                    $fileInput[0].files = dt.files;
                    showFile(files[0]);
                }
            });

            // Reset drop zone when modal closes
            $('#uploadModal').on('hidden.bs.modal', function () {
                clearDropZone();
            });

            @if($isDirect && !$autoCustomer)
            Swal.fire({
                icon: 'warning',
                title: 'No Client Found',
                html: `You need to add your company/client information before uploading a DCP Creative.<br><br><a href="{{ route('advertiser.profile.index') }}" class="btn btn-primary btn-sm">Go to Profile</a>`,
                showConfirmButton: false,
                allowOutsideClick: false,
            });
            @endif

            const uploadModal = new bootstrap.Modal(document.getElementById('uploadModal'));
            const progressModal = new bootstrap.Modal(document.getElementById('progressModal'), {
                backdrop: 'static',
                keyboard: false
            });

            let cancelRequested = false;

            function setProgress(pct, text) {
                pct = Math.max(0, Math.min(100, pct));
                $('#progressBar').css('width', pct + '%').text(pct.toFixed(1) + '%');
                if (text) $('#progressText').text(text);
            }

            function resetProgressUI() {
                setProgress(0, 'Initialisation…');
                $('#xCloseProgress').prop('disabled', true);
                $('#cancelBtn').prop('disabled', false);
            }

            $('#cancelBtn').on('click', function() {
                cancelRequested = true;
                $('#progressText').text('Annulation en cours…');
                $('#cancelBtn').prop('disabled', true);
            });

            $('#uploadForm').on('submit', async function(e) {
                e.preventDefault();
                const compaign_category_id  = $('#compaign_category_id').val();
                const compaign_objective_id = $('#compaign_objective_id').val();
                const langue_id             = $('#langue_id').val();
                const gender_id             = $('#gender_id').val();
                const target_type_ids       = $('#target_type_ids').val() || [];
                const interest_ids          = $('#interest_ids').val() || [];
                const customer_id           = $('#customer_id').val();
                const file = $('#fileInput')[0].files[0];
                if (!file) return;

                @if($isDirect && !$autoCustomer)
                Swal.fire({
                    icon: 'warning',
                    title: 'No Client Found',
                    html: `Please add your company/client information on your profile page first.<br><br><a href="{{ route('advertiser.profile.index') }}" class="btn btn-primary btn-sm">Go to Profile</a>`,
                    showConfirmButton: false,
                });
                return;
                @endif

                if (!/\.(zip|tar|7z)$/i.test(file.name)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid file',
                        text: 'Please select a .zip, .tar, or .7z file.'
                    });
                    return;
                }

                // Open progress window
                resetProgressUI();
                cancelRequested = false;
                uploadModal.hide();
                progressModal.show();

                try {
                    // 1) INIT
                    setProgress(0, 'Initialization…');
                    const initRes = await $.ajax({
                        url: "{{ route('advertiser.zip.upload.init') }}",
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': CSRF
                        },
                        data: {
                            file_name: file.name,
                            file_size: file.size,
                            compaign_category_id,
                            customer_id,
                        }
                    });

                    if (!initRes.ok) throw new Error(initRes.message || 'Init error');

                    const uploadId = initRes.upload_id;
                    const CHUNK = initRes.chunk_size || CHUNK_SIZE_FALLBACK;
                    const total = Math.ceil(file.size / CHUNK);

                    let uploadedBytesBefore = 0;
                    let currentIndex = 0;

                    // 2) Send chunk by chunk
                    while (currentIndex < total) {
                        if (cancelRequested) throw new Error('cancelled');

                        const start = currentIndex * CHUNK;
                        const end = Math.min(start + CHUNK, file.size);
                        const blob = file.slice(start, end);

                        const fd = new FormData();
                        fd.append('upload_id', uploadId);
                        fd.append('index', currentIndex);
                        fd.append('compaign_category_id', compaign_category_id);
                        fd.append('customer_id', customer_id);
                        fd.append('total', total);
                        fd.append('file_name', file.name);
                        fd.append('chunk', blob, `chunk_${currentIndex}`);

                        const pctBase = (uploadedBytesBefore / file.size) * 100;

                        await $.ajax({
                            url: "{{ route('advertiser.zip.upload.chunk') }}",
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': CSRF
                            },
                            data: fd,
                            processData: false,
                            contentType: false,
                            xhr: function() {
                                const xhr = $.ajaxSettings.xhr();
                                if (xhr.upload) {
                                    xhr.upload.addEventListener('progress', function(e) {
                                        if (e.lengthComputable) {
                                            const totalPct = pctBase + (e.loaded /
                                                file.size) * 100;
                                            setProgress(totalPct, 'Uploading…');
                                        }
                                    }, false);
                                }
                                return xhr;
                            }
                        });

                        uploadedBytesBefore += (end - start);
                        setProgress((uploadedBytesBefore / file.size) * 100, 'Uploading');
                        currentIndex++;
                    }

                    // 3) COMPLETE
                    setProgress(99, 'Server-side assembly…');
                    const completeRes = await $.ajax({
                        url: "{{ route('advertiser.zip.upload.complete') }}",
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': CSRF
                        },
                        data: {
                            upload_id: uploadId,
                            total: total,
                            file_name: file.name,
                            compaign_category_id,
                            compaign_objective_id,
                            langue_id,
                            gender_id,
                            target_type_ids,
                            interest_ids,
                            customer_id,
                        }
                    });

                    if (!completeRes.ok) throw new Error(completeRes.message || 'Complete error');

                    setProgress(100, 'Completed ✔️');
                    $('#cancelBtn').prop('disabled', true);
                    $('#xCloseProgress').prop('disabled', false);

                    // Close progress modal and show success alert
                    progressModal.hide();
                    Swal.fire({
                        icon: 'success',
                        title: 'Upload completed',
                        text: `DCP Creative: ${completeRes.cpl_meta.name} (${(completeRes.final.size / (1024*1024)).toFixed(1)} MB) Uploaded Successfully `,
                        confirmButtonText: 'OK'
                    });

                    if (completeRes.warning) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'ZIP warning',
                            text: completeRes.warning,
                        });
                    }

                } catch (err) {
                    progressModal.hide();
                    if (String(err.message) === 'cancelled') {
                        Swal.fire({
                            icon: 'info',
                            title: 'Upload cancelled',
                            text: 'You cancelled the upload.'
                        });
                    } else {
                        console.error(err);
                        const msg = err.responseJSON?.message || err.message || 'Unknown error';
                        Swal.fire({
                            icon: 'error',
                            title: 'Upload error',
                            text: msg
                        });
                    }
                } finally {
                    // Visual reset for next upload
                    resetProgressUI();
                    clearDropZone();
                    $('#compaign_category_id').val('');
                    $('#compaign_objective_id').val('');
                    $('#langue_id').val('');
                    $('#gender_id').val('');
                    $('#target_type_ids').val(null).trigger('change');
                    $('#interest_ids').val(null).trigger('change');
                    $('#customer_id').val('');
                    get_dcp_creatives();
                }
            });

            function get_dcp_creatives() {
                $('#wait-modal').modal('show');

                $("#dcp_creatives-table").dataTable().fnDestroy();
                var url = "{{ url('') }}" + '/advertiser/dcp_creatives/list';
                var result = " ";
                $.ajax({
                        url: url,
                        method: 'GET',
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            $.each(response.dcp_creatives, function(index, value) {
                                index++;
                                result = result +
                                    '<tr class="odd text-center">' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' +
                                    index + ' </td>' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' +
                                    value.name + ' </td>' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' +
                                    value.uuid + ' </td>' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' +
                                    formatHMS(value.duration) + ' </td>' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' + (value.compaign_category ? value.compaign_category.name : '-') + '</td>' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' + (value.customer ? value.customer.name : '-') + '</td>' +
                                    /*'<td class="text-body align-middle fw-medium text-decoration-none">' + (
                                        value.status == 1 ? '<span class="badge bg-warning-subtle text-warning">Pending</span> ' :
                                        value.status == 2 ? '<span class="badge bg-success-subtle text-success">Approved</span>' :
                                        value.status == 3 ? '<span class="badge bg-danger-subtle text-danger">Rejected</span>': '-'
                                    ) + '</td>' +*/
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' +
                                        '<button data-id="' + value.id + '" type="button" class="  ustify-content-center btn mbtn-rounded btn-success  view-dcp m-1" title="View details">' +
                                        '<i class="mdi mdi-eye"></i>' +
                                        '</button>' +
                                        '<button id="' + value.id +
                                        '" type="button" class="delete justify-content-center btn  btn-danger m-1">' +
                                        '<i class="mdi mdi-delete"></i>' +
                                        '</button>' +
                                    '</td>' +
                                    '</tr>';
                            });
                            $('#dcp_creatives-table tbody').html(result)
                            $('#wait-modal').modal('hide');
                            // $('#loader-modal').css('display','none')
                            /***** refresh datatable **** **/

                            var dcp_creatives = $('#dcp_creatives-table').DataTable({
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
            get_dcp_creatives();

            // ── View DCP Details ───────────────────────────────────────────
            const dcpDetailModal = new bootstrap.Modal(document.getElementById('dcpDetailModal'));

            $(document).on('click', '.view-dcp', function () {
                const id  = $(this).data('id');
                const url = '{{ url('') }}' + '/advertiser/dcp_creatives/' + id + '/show';

                $('#dcpDetailBody').html('<div class="text-center py-4"><div class="spinner-border text-info" role="status"></div></div>');
                dcpDetailModal.show();

                $.ajax({ url: url, method: 'GET', headers: { 'X-CSRF-TOKEN': CSRF } })
                .done(function (res) {
                    const d = res.dcp_creative;

                    const badge = (label, cls) =>
                        label ? `<span class="badge ${cls} me-1">${label}</span>` : '<span class="text-muted">—</span>';

                    const tags = (arr, cls) =>
                        arr && arr.length
                            ? arr.map(i => `<span class="badge ${cls} me-1">${i.name}</span>`).join('')
                            : '<span class="text-muted">—</span>';

                    $('#dcpDetailBody').html(`
                        <div class="row g-3">
                            <div class="col-12">
                                <h6 class="fw-bold border-bottom pb-2 mb-1 text-white">
                                    <i class="mdi mdi-film me-1"></i>${d.name ?? '—'}
                                </h6>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 rounded bg-body-secondary h-100">
                                    <div class="fw-semibold text-muted small mb-2 text-uppercase">File Info</div>
                                    <table class="table table-sm table-borderless mb-0">
                                        <tr><td class="text-muted pe-3" style="width:40%">UUID</td>
                                            <td><code class="small">${d.uuid ?? '—'}</code></td></tr>
                                        <tr><td class="text-muted pe-3">Duration</td>
                                            <td>${typeof formatHMS === 'function' ? formatHMS(d.duration) : d.duration}</td></tr>
                                        <tr><td class="text-muted pe-3">Category</td>
                                            <td>${d.compaign_category ? d.compaign_category.name : '—'}</td></tr>
                                        <tr><td class="text-muted pe-3">My Client</td>
                                            <td>${d.customer ? d.customer.name : '—'}</td></tr>
                                    </table>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 rounded bg-body-secondary h-100">
                                    <div class="fw-semibold text-muted small mb-2 text-uppercase">Targeting</div>
                                    <table class="table table-sm table-borderless mb-0">
                                        <tr><td class="text-muted pe-3" style="width:40%">Objective</td>
                                            <td>${d.compaign_objective ? badge(d.compaign_objective.name, 'bg-primary-subtle text-primary') : '<span class="text-muted">—</span>'}</td></tr>
                                        <tr><td class="text-muted pe-3">Language</td>
                                            <td>${d.langue ? badge(d.langue.name, 'bg-info-subtle text-info') : '<span class="text-muted">—</span>'}</td></tr>
                                        <tr><td class="text-muted pe-3">Gender</td>
                                            <td>${d.gender ? badge(d.gender.name, 'bg-warning-subtle text-warning') : '<span class="text-muted">—</span>'}</td></tr>
                                    </table>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 rounded bg-body-secondary">
                                    <div class="fw-semibold text-muted small mb-2 text-uppercase">Target Types</div>
                                    <div>${tags(d.target_types, 'bg-success-subtle text-success')}</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 rounded bg-body-secondary">
                                    <div class="fw-semibold text-muted small mb-2 text-uppercase">Interests</div>
                                    <div>${tags(d.interests, 'bg-secondary-subtle text-secondary')}</div>
                                </div>
                            </div>
                        </div>
                    `);
                })
                .fail(function () {
                    $('#dcpDetailBody').html('<div class="alert alert-danger">Failed to load details.</div>');
                });
            });

            // ─────────────────────────────────────────────────────────────────
            $(document).on('click', '.delete', function() {
                var id = $(this).attr('id');

                const url = '{{ url('') }}' + '/advertiser/dcp_creatives/';
                const csrf = '{{ csrf_token() }}';

                // SweetAlert2 confirm
                Swal.fire({
                    title: 'Delete DCP Creative?',
                    text: 'Are you sure you want to delete this DCP Creative?',
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
                            method: 'DELETE', // compat Laravel
                            data: {
                                id: id,
                                _token: csrf
                            },
                            headers: {
                                'X-CSRF-TOKEN': csrf
                            }
                        })
                        .done(function(response) {

                            get_dcp_creatives();
                            Swal.fire({
                                title: 'Done!',
                                text: 'DCP Creative deleted successfully.',
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
        });
    </script>
@endsection

@section('custom_css')
    <link rel="stylesheet" href="{{ asset('/assets/libs/select2/dist/css/select2.min.css') }}">
    <style>
        /* ── Progress bar ── */
        .progress { height: 15px !important; }
        #progressText { text-align: center; }

        /* ── Drag & Drop zone ── */
        .dz-area {
            border: 2px dashed var(--bs-primary);
            border-radius: 10px;
            padding: 28px 20px;
            text-align: center;
            cursor: pointer;
            transition: background .2s, border-color .2s;
            background: var(--bs-body-bg);
            user-select: none;
        }
        .dz-area:hover,
        .dz-area.dz-drag-over {
            background: color-mix(in srgb, var(--bs-primary) 8%, transparent);
            border-color: var(--bs-primary);
        }
        .dz-area.dz-has-file {
            border-style: solid;
            border-color: var(--bs-success);
            background: color-mix(in srgb, var(--bs-success) 6%, transparent);
        }
        .dz-icon {
            font-size: 2.4rem;
            color: var(--bs-primary);
            line-height: 1;
        }
        .dz-area.dz-has-file .dz-icon { color: var(--bs-success); }
        .dz-text {
            font-weight: 600;
            font-size: .95rem;
            margin-top: 6px;
        }
        .dz-subtext {
            font-size: .82rem;
            color: var(--bs-secondary-color);
            margin-top: 2px;
        }
        .dz-browse-link {
            color: var(--bs-primary);
            text-decoration: underline;
            cursor: pointer;
        }
        .dz-filename {
            margin-top: 10px;
            font-size: .85rem;
            word-break: break-all;
        }

        /* ── Select All / Clear All buttons ── */
        .btn-xs { font-size: .72rem; line-height: 1.4; }
    </style>
@endsection
