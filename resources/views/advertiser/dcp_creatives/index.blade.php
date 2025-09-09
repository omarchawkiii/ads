@extends('advertiser.layouts.app')
@section('title')
    DCP Creative
@endsection
@section('content')
    <div class="container py-4">

        <div class="card card-body py-3">
            <div class="row align-items-center">
                <div class="col-12">
                    <div class="d-sm-flex align-items-center justify-space-between">
                        <h4 class="mb-4 mb-sm-0 card-title"> DCP Creative</h4>
                        <nav aria-label="breadcrumb" class="ms-auto">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item" aria-current="page">
                                    <button class="btn bg-success  text-white " id="create_dcp_creative">
                                        + New DCP Creative
                                    </button>

                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                                        Uploader un ZIP volumineux
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
                            <div class="form-text">Formats acceptés : .xml — max 5MB</div>
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
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Sélectionner un fichier ZIP</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form id="uploadForm">
                        <div class="mb-3">
                            <label class="form-label">Fichier ZIP</label>
                            <input type="file" class="form-control" id="fileInput" accept=".zip,.tar,.7z" required>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" id="startBtn" class="btn btn-success">Démarrer l’upload</button>
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Fermer</button>
                        </div>
                    </form>
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
                    <h5 class="modal-title">Upload en cours…</h5>
                    <button type="button" class="btn-close" id="xCloseProgress" disabled aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-2">
                        <div class="progress">
                            <div id="progressBar" class="progress-bar" role="progressbar" style="width:0%">0%</div>
                        </div>
                        <div class="small text-muted mt-1" id="progressText">Initialisation…</div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" id="cancelBtn" class="btn btn-outline-danger">Annuler</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection


@section('custom_script')
    <script src="{{ asset('assets/js/helper.js') }}"></script>

    <script>
        $(function() {
            const CSRF = '{{ csrf_token() }}';
            const CHUNK_SIZE_FALLBACK = 10 * 1024 * 1024; // 10 Mo si le backend ne renvoie pas

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

                const file = $('#fileInput')[0].files[0];
                if (!file) return;

                if (!/\.(zip|tar|7z)$/i.test(file.name)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Fichier invalide',
                        text: 'Veuillez sélectionner un fichier .zip'
                    });
                    return;
                }

                // Ouvrir la fenêtre de progression
                resetProgressUI();
                cancelRequested = false;
                uploadModal.hide();
                progressModal.show();

                try {
                    // 1) INIT
                    setProgress(0, 'Initialisation…');
                    const initRes = await $.ajax({
                        url: "{{ route('advertiser.zip.upload.init') }}",
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': CSRF
                        },
                        data: {
                            file_name: file.name,
                            file_size: file.size
                        }
                    });

                    if (!initRes.ok) throw new Error(initRes.message || 'Erreur init');

                    const uploadId = initRes.upload_id;
                    const CHUNK = initRes.chunk_size || CHUNK_SIZE_FALLBACK;
                    const total = Math.ceil(file.size / CHUNK);

                    let uploadedBytesBefore = 0;
                    let currentIndex = 0;

                    // 2) Envoi chunk par chunk
                    while (currentIndex < total) {
                        if (cancelRequested) throw new Error('cancelled');

                        const start = currentIndex * CHUNK;
                        const end = Math.min(start + CHUNK, file.size);
                        const blob = file.slice(start, end);

                        const fd = new FormData();
                        fd.append('upload_id', uploadId);
                        fd.append('index', currentIndex);
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
                                            const totalPct = pctBase + (e.loaded / file
                                                .size) * 100;
                                            setProgress(totalPct, `Upload…`);
                                        }
                                    }, false);
                                }
                                return xhr;
                            }
                        });

                        uploadedBytesBefore += (end - start);
                        setProgress((uploadedBytesBefore / file.size) * 100, `Upload`);
                        currentIndex++;
                    }

                    // 3) COMPLETE
                    setProgress(99, 'Assemblage côté serveur…');
                    const completeRes = await $.ajax({
                        url: "{{ route('advertiser.zip.upload.complete') }}",
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': CSRF
                        },
                        data: {
                            upload_id: uploadId,
                            total: total,
                            file_name: file.name
                        }
                    });

                    if (!completeRes.ok) throw new Error(completeRes.message || 'Erreur complete');

                    setProgress(100, 'Terminé ✔️');
                    $('#cancelBtn').prop('disabled', true);
                    $('#xCloseProgress').prop('disabled', false);

                    // Fermer le popup de progression et SweetAlert succès
                    progressModal.hide();
                    Swal.fire({
                        icon: 'success',
                        title: 'Upload terminé',
                        text: `Fichier assemblé: ${completeRes.final.filename} (${(completeRes.final.size / (1024*1024)).toFixed(1)} Mo)`,
                        confirmButtonText: 'OK'
                    });

                    if (completeRes.warning) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Avertissement ZIP',
                            text: completeRes.warning,
                        });
                    }

                } catch (err) {
                    progressModal.hide();
                    if (String(err.message) === 'cancelled') {
                        Swal.fire({
                            icon: 'info',
                            title: 'Upload annulé',
                            text: 'Vous avez annulé l’upload.'
                        });
                    } else {
                        console.error(err);
                        const msg = err.responseJSON?.message || err.message || 'Erreur inconnue';
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur upload',
                            text: msg
                        });
                    }
                } finally {
                    // Reset visuel pour un prochain upload
                    resetProgressUI();
                    $('#fileInput').val('');
                    get_dcp_creatives()
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
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' +
                                    '<button id="' + value.id +
                                    '" type="button" class="delete justify-content-center btn mb-1 btn-rounded btn-danger m-1">' +
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
    <style>
        .progress {
            height: 15px !important;
        }

        #progressText {
            text-align: center;
        }
    </style>
@endsection
