@extends('admin.layouts.app')
@section('title')
Campaign
@endsection
@section('content')
    <div class=" py-4">

        <div class="card card-body py-3">
            <div class="row align-items-center">
                <div class="col-12">
                    <div class="d-sm-flex align-items-center justify-space-between">
                        <h4 class="mb-4 mb-sm-0 card-title"> Campaign </h4>
                        <nav aria-label="breadcrumb" class="ms-auto">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item" aria-current="page">
                                    <button id="btnReservedSlots" class="btn btn-info ">
                                        View All Reserved Slots
                                    </button>
                                    <!--<button class="btn bg-success  text-white " id="create_compaign">
                                        + New Campaign
                                    </button>-->

                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body table-responsive">
                <table id="compaigns-table" class="table table-striped table-bordered display text-nowrap dataTable">
                    <thead>
                        <tr class="text-center ">
                            <th class="text-center" style="width:160px;">ID</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Created By</th>

                            <th class="text-center">Start Date</th>
                            <th class="text-center">End Date</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal  " id="create_compaign_modal" tabindex="-1" aria-labelledby="bs-example-modal-lg" aria-modal="true"
        role="dialog">
        <div class="modal-dialog modal-lg  modal-dialog-centered">
            <div class="modal-content ">

                <div class="modal-header d-flex align-items-center  bg-primary ">
                    <h4 class="modal-title text-white " id="myLargeModalLabel ">
                        Create Campaign
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body wizard-content">
                    <form action="#" class="validation-wizard wizard-circle mt-5" id="create_compaign_form">
                        <!-- Step 1 -->
                        <h6>Basics</h6>
                        <section>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="name"> Campaign Name : <span
                                                class="danger">*</span>
                                        </label>
                                        <input type="text" class="form-control required" id="name" name="name"
                                            placeholder="campaign Name" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="compaign_objective"> Campaign Objective : <span
                                                class="danger">*</span>
                                        </label>
                                        <select class="form-select required" id="compaign_objective"
                                            name="compaign_objective">
                                            <option value="">Select...</option>
                                            @foreach ($compaign_objectives as $compaign_objective)
                                                <option value="{{ $compaign_objective->id }}">
                                                    {{ $compaign_objective->name }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="compaign_category"> Ad Category : <span
                                                class="danger">*</span>
                                        </label>
                                        <select class="form-select required" id="compaign_category"
                                            name="compaign_category">
                                            <option value="">Select...</option>
                                            @foreach ($compaign_categories as $compaign_category)
                                                <option value="{{ $compaign_category->id }}">{{ $compaign_category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="start_date">Start Date</label>
                                        <input type="date" class="form-control required" id="start_date" name="start_date"  />
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="end_date">End Date</label>
                                        <input type="date" class="form-control required" id="end_date" name="end_date"  />
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="budget">Desired Budget</label> <span
                                            class="danger">(RM)</span>
                                        <input type="number" class="form-control required" id="budget" name="budget"  />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="langue"> Preferred Language : <span
                                                class="danger">*</span>
                                        </label>
                                        <select class="form-select required" id="langue" name="langue">
                                            <option value="">Select...</option>
                                            @foreach ($langues as $langue)
                                                <option value="{{ $langue->id }}">{{ $langue->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label" for="note">Internal Notes :</label>
                                        <textarea name="note" id="note" rows="4" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>

                        </section>
                        <!-- Step 2 -->
                        <h6> Targeting </h6>
                        <section>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="location"> Select Cinemas : <span
                                                class="danger">*</span>
                                        </label>

                                        <select class="form-select required select2" multiple="" id="location"
                                            name="location[]">
                                            <option value="">Select...</option>
                                            @foreach ($locations as $location)
                                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="hall_type"> Hall Types : <span
                                                class="danger">*</span>
                                        </label>
                                        <select class="form-select required select2" id="hall_type" multiple=""
                                            name="hall_type[]">
                                            <option value="">Select...</option>
                                            @foreach ($hall_types as $hall_type)
                                                <option value="{{ $hall_type->id }}">{{ $hall_type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="movie"> Select Movies : <span
                                                class="danger">*</span>
                                        </label>
                                        <select class="form-select required" id="movie" name="movie">
                                            <option value="">Select...</option>
                                            @foreach ($movies as $movie)
                                                <option value="{{ $movie->id }}">{{ $movie->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="movie_genre"> Genres : <span
                                                class="danger">*</span>
                                        </label>
                                        <select class="form-select required select2" multiple="" id="movie_genre"
                                            name="movie_genre[]">

                                            <option value="">Select...</option>
                                            @foreach ($movie_genres as $movie_genre)
                                                <option value="{{ $movie_genre->id }}">{{ $movie_genre->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="gender"> Gender : <span class="danger">*</span>
                                        </label>
                                        <select class="form-select required" id="gender" name="gender">
                                            <option value="">Select...</option>
                                            @foreach ($genders as $gender)
                                                <option value="{{ $gender->id }}">{{ $gender->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="target_type"> Target Type : <span
                                                class="danger">*</span>
                                        </label>
                                        <select class="form-select select2 required" id="target_type"
                                            name="target_type[]">
                                            <option value="">Select...</option>
                                            @foreach ($target_types as $target_type)
                                                <option value="{{ $target_type->id }}">{{ $target_type->name }}
                                                    ({{ $target_type->detail }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="interest"> Interests : <span
                                                class="danger">*</span>
                                        </label>
                                        <select class="form-select required select2" multiple="" id="interest"
                                            name="interest[]">
                                            <option value="">Select...</option>
                                            @foreach ($interests as $interest)
                                                <option value="{{ $interest->id }}">{{ $interest->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </section>
                        <!-- Step 3 -->
                        <h6>DCP Creative & Slot</h6>
                        <section>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="slot"> Select Ad Slot Tier : <span
                                                class="danger">*</span>
                                        </label>

                                        <select class="form-select required" id="slot" name="slot">
                                            <option value="">Select...</option>
                                            @foreach ($slots as $slot)
                                                <option value="{{ $slot->id }}">{{ $slot->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="duration"> Select Ad Duration : <span
                                                class="danger">*</span>
                                        </label>

                                        <select class="form-select required" id="duration" name="duration">
                                            <option value="">Select...</option>
                                            <option value="15">15 seconds</option>
                                            <option value="30">30 seconds</option>
                                            <option value="45">45 seconds</option>
                                            <option value="60">60 seconds</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label" for="dcp_creative"> DCP Creative : <span
                                                class="danger">*</span>
                                        </label>

                                        <select class="form-select required select2" id="dcp_creative" multiple=""  name="dcp_creative[]">
                                            <option value="">Select...</option>
                                            @foreach ($dcp_creatives as $dcp_creative)
                                                <option value="{{ $dcp_creative->id }}">{{ $dcp_creative->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                            </div>
                        </section>
                        <!-- Step 4 -->
                        <h6>Review & Submit</h6>
                        <section>
                            <div class="row" id="review-summary">
                                <div class="col-md-6">
                                    <h6 class="mb-3">Basics</h6>
                                    <dl class="row">
                                        <dt class="col-5">Campaign Name</dt>
                                        <dd class="col-7" id="rv_name">–</dd>
                                        <dt class="col-5">Objective</dt>
                                        <dd class="col-7" id="rv_objective">–</dd>
                                        <dt class="col-5">Ad Category</dt>
                                        <dd class="col-7" id="rv_category">–</dd>

                                        <dt class="col-5">Start</dt>
                                        <dd class="col-7" id="rv_start">–</dd>
                                        <dt class="col-5">End</dt>
                                        <dd class="col-7" id="rv_end">–</dd>
                                        <dt class="col-5">Budget (RM)</dt>
                                        <dd class="col-7" id="rv_budget">–</dd>
                                        <dt class="col-5">Language</dt>
                                        <dd class="col-7" id="rv_langue">–</dd>
                                        <dt class="col-5">Notes</dt>
                                        <dd class="col-7" id="rv_note">–</dd>
                                    </dl>
                                </div>

                                <div class="col-md-6">
                                    <h6 class="mb-3">Targeting & Slot</h6>
                                    <dl class="row">
                                        <dt class="col-5">Cinemas</dt>
                                        <dd class="col-7" id="rv_cinema">–</dd>
                                        <dt class="col-5">Hall Type</dt>
                                        <dd class="col-7" id="rv_hall">–</dd>
                                        <dt class="col-5">Movie</dt>
                                        <dd class="col-7" id="rv_movie">–</dd>
                                        <dt class="col-5">Genre</dt>
                                        <dd class="col-7" id="rv_genre">–</dd>
                                        <dt class="col-5">Gender</dt>
                                        <dd class="col-7" id="rv_gender">–</dd>
                                        <dt class="col-5">Target Type</dt>
                                        <dd class="col-7" id="rv_target_type">–</dd>
                                        <dt class="col-5">Interests</dt>
                                        <dd class="col-7" id="rv_interest">–</dd>
                                        <dt class="col-5">Slot Tier</dt>
                                        <dd class="col-7" id="rv_slot_tier">–</dd>
                                        <dt class="col-5">Ad Duration</dt>
                                        <dd class="col-7" id="rv_ad_duration">–</dd>
                                    </dl>
                                </div>
                            </div>

                        </section>
                    </form>
                </div>

            </div>

        </div>
        <!-- /.modal-content -->
    </div>


    <div class="modal" id="dcps_modal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header bg-primary">
              <h5 class="modal-title text-white">Slots & DCP Creatives</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <div id="dcps_content"></div>
            </div>
          </div>
        </div>
    </div>

    <div class="modal fade" id="reservedSlotsModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Slots réservés</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Slot</th>
                                <th>DCP</th>
                                <th>Campaign</th>
                                <th>Movie</th>
                                <th>Genre</th>
                                <th>Duration (s)</th>
                                <th>Location</th>
                            </tr>
                        </thead>
                        <tbody id="reservedSlotsTable"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== VIEW CAMPAIGN MODAL ===== --}}
    <div class="modal fade" id="view_compaign_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Campaign details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="cmpg_loader" class="py-4 text-center" style="display:none;">
                        <div class="spinner-border" role="status"></div>
                        <div class="mt-2">Loading…</div>
                    </div>
                    <div id="cmpg_content">
                        <div class="row">
                            <div class="col-md-3">
                                <h6 class="mb-3 fw-semibold">Basics</h6>
                                <dl class="row small">
                                    <dt class="col-5 text-muted">Name</dt>       <dd class="col-7" id="v_name">–</dd>
                                    <dt class="col-5 text-muted">Advertiser</dt> <dd class="col-7" id="v_user">–</dd>
                                    <dt class="col-5 text-muted">Objective</dt>  <dd class="col-7" id="v_objective">–</dd>
                                    <dt class="col-5 text-muted">Category</dt>   <dd class="col-7" id="v_category">–</dd>

                                    <dt class="col-5 text-muted">Start</dt>      <dd class="col-7" id="v_start">–</dd>
                                    <dt class="col-5 text-muted">End</dt>        <dd class="col-7" id="v_end">–</dd>
                                    <dt class="col-5 text-muted">Budget</dt>     <dd class="col-7" id="v_budget">–</dd>
                                    <dt class="col-5 text-muted">Language</dt>   <dd class="col-7" id="v_langue">–</dd>
                                    <dt class="col-5 text-muted">Duration</dt>   <dd class="col-7" id="v_duration">–</dd>
                                    <dt class="col-5 text-muted">Status</dt>     <dd class="col-7" id="v_status">–</dd>
                                    <dt class="col-5 text-muted">Notes</dt>      <dd class="col-7" id="v_note">–</dd>
                                </dl>
                            </div>
                            <div class="col-md-3">
                                <h6 class="mb-3 fw-semibold">Targeting</h6>
                                <dl class="row small">
                                    <dt class="col-5 text-muted">Cinemas</dt>     <dd class="col-7" id="v_locations">–</dd>
                                    <dt class="col-5 text-muted">Hall Types</dt>  <dd class="col-7" id="v_hall_types">–</dd>
                                    <dt class="col-5 text-muted">Movie</dt>       <dd class="col-7" id="v_movie">–</dd>
                                    <dt class="col-5 text-muted">Genres</dt>      <dd class="col-7" id="v_movie_genres">–</dd>
                                    <dt class="col-5 text-muted">Gender</dt>      <dd class="col-7" id="v_gender">–</dd>
                                    <dt class="col-5 text-muted">Target Type</dt> <dd class="col-7" id="v_target_types">–</dd>
                                    <dt class="col-5 text-muted">Interests</dt>   <dd class="col-7" id="v_interests">–</dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <h6 class="mb-3 fw-semibold">Template & Slots</h6>
                                <div id="v_slots_details" class="mt-1"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('custom_script')
    <script src="{{ asset('assets/libs/jquery-steps/build/jquery.steps.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>>
    <script src="{{ asset('assets/libs/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/dist/js/select2.min.js') }}"></script>
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <script src="{{ asset('assets/js/helper.js') }}?v={{ filemtime(public_path('assets/js/helper.js')) }}"></script>
    <script>
        $(function() {

            $(document).on('click', '#create_compaign', function() {
                $('#create_compaign_modal').modal('show');
            });

            $(document).on('shown.bs.modal', '#create_compaign_modal', function() {
                const $modal = $(this);

                $modal.find('.select2').each(function() {
                    const $sel = $(this);

                    // Si déjà initialisé (réouverture), détruire proprement
                    if ($sel.hasClass('select2-hidden-accessible')) {
                        $sel.select2('destroy');
                    }

                    $sel.select2({
                        placeholder: "Select...",
                        dropdownParent: $modal, // évite le menu derrière le modal
                        width: '100%',
                        tags: false
                    });
                });
            });

            $(document).on('hidden.bs.modal', '#create_compaign_modal', function() {
                $(this).find('.select2.select2-hidden-accessible').select2('destroy');
            });
            var form = $("#create_compaign_form").show();
            $(".validation-wizard").steps({
                    headerTag: "h6",
                    bodyTag: "section",
                    transitionEffect: "fade",
                    titleTemplate: '<span class="step">#index#</span> #title#',
                    labels: {
                        finish: "Submit",
                    },
                    onStepChanging: function(event, currentIndex, newIndex) {
                        return (
                            currentIndex > newIndex ||
                            (!(3 === newIndex && Number($("#age-2").val()) < 18) &&
                                (currentIndex < newIndex &&
                                    (form.find(".body:eq(" + newIndex + ") label.error").remove(),
                                        form.find(".body:eq(" + newIndex + ") .error").removeClass(
                                            "error")),
                                    (form.validate().settings.ignore = ":disabled,:hidden"),
                                    form.valid()))
                        );
                    },
                    onFinishing: function(event, currentIndex) {
                        return (form.validate().settings.ignore = ":disabled"), form.valid();
                    },
                    onFinished: function(event, currentIndex) {
                        event.preventDefault();


                        const url = "{{ url('') }}/compaigns";
                        const data = $(this).serialize();
                        console.log(data)
                        $.ajax({
                                url: url,
                                method: "POST",
                                data: data,
                                "_token": "{{ csrf_token() }}",
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                                }
                            })
                            .done(function(response) {
                                get_compaigns();
                                Swal.fire({
                                    title: 'Done!',
                                    text: 'Compaign created successfully.',
                                    icon: 'success',
                                    confirmButtonText: 'Continue'
                                });
                                reset_form('#create_compaign_modal form'); // ton helper si tu l’as
                            })
                            .fail(function(xhr) {
                                let msg = 'Operation failed.';
                                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                                    msg = Object.values(xhr.responseJSON.errors).flat().join('\n');
                                }
                                Swal.fire({
                                    title: 'Error',
                                    text: msg,
                                    icon: 'error'
                                });
                            })
                            .always(function() {
                                $('#wait-modal').modal('hide');
                                $('#create_compaign_modal').modal('hide');
                            });
                    },
                }),
            $(".validation-wizard").validate({
                ignore: "input[type=hidden]",
                errorClass: "text-danger",
                successClass: "text-success",
                highlight: function(element, errorClass) {
                    $(element).removeClass(errorClass);
                },
                unhighlight: function(element, errorClass) {
                    $(element).removeClass(errorClass);
                },
                errorPlacement: function(error, element) {
                    error.insertAfter(element);
                },
                rules: {
                    email: {
                        email: !0,
                    },
                },
            });

            function getSelectText(selector) {
                const $el = $(selector);
                if ($el.length === 0) return '–';
                const txt = $el.find('option:selected').text().trim();
                return txt || '–';
            }

            function getVal(selector) {
                const $el = $(selector);
                if ($el.length === 0) return '–';
                const v = $el.val();
                return (v === null || v === '') ? '–' : v;
            }

            function fmtBudget(v) {
                const n = parseFloat(v);
                return isNaN(n) ? '–' : n.toLocaleString(undefined, {
                    maximumFractionDigits: 2
                });
            }

            $('#create_compaign_form').on('stepChanged', function(e, currentIndex) {
                if (currentIndex === 3) updateReview();
            });

            $('#create_compaign_form').on('input change', 'input, select, textarea', function() {
                // Si la step 4 est visible, actualiser en direct
                const step4Visible = $('#review-summary').is(':visible');
                if (step4Visible) updateReview();
            });
            function formatDateLocalISO(dateStr) {
                if (!dateStr) return '–';
                // Crée un objet Date à partir du string ISO
                const dateObj = new Date(dateStr);
                // Récupère uniquement la partie YYYY-MM-DD en local
                const year = dateObj.getFullYear();
                const month = (dateObj.getMonth() + 1).toString().padStart(2, '0');
                const day = dateObj.getDate().toString().padStart(2, '0');
                const localDate = new Date(`${year}-${month}-${day}`);
                return formatDateEN(localDate, { variant: 'short' });
            }
            window.get_compaigns = function get_compaigns() {
                $('#wait-modal').modal('show');

                $("#compaigns-table").dataTable().fnDestroy();
                var url = "{{ url('') }}" + '/compaigns/list';
                var result = " ";
                $.ajax({
                        url: url,
                        method: 'GET',
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            console.log(response)
                            $.each(response.compaigns, function(index, value) {


                                index++;
                                result = result +
                                    '<tr class="odd text-left">' +
                                    '<td class="text-center align-middle fw-medium text-decoration-none">' +
                                    index + ' </td>' +
                                    '<td class="text-center align-middle fw-medium text-decoration-none">' +
                                    value.name + ' </td>' +
                                    '<td class="text-center align-middle fw-medium text-decoration-none">' +
                                        (value.user ? value.user.name + ' ' + value.user.last_name : '') +
                                    ' </td>' +
                                    '<td class="text-center align-middle fw-medium text-decoration-none">' +
                                        value.start_date + ' </td>' +
                                    '<td class="text-center align-middle fw-medium text-decoration-none">' +
                                        value.end_date + ' </td>' +
                                    '<td class="text-center align-middle fw-medium text-decoration-none">' +
                                            getStatusText(value.status) + ' </td>' +
                                    '<td class=" align-middle fw-medium text-decoration-none">' +
                                        getStatusAction(value.status, value.id )+

                                    '<button data-bs-toggle="tooltip" title="View" data-id="' + value.id +
                                    '" type="button" class="view btn  mb-1 btn-rounded btn-info m-1">' +
                                    '<i class="mdi mdi-magnify" style="pointer-events:none"></i>' +
                                    '</button>' +
                                    '<button data-bs-toggle="tooltip" title="View DCPs" class="btn  mb-1 btn-rounded btn-warning m-1 view-dcps" data-id="' + value.id +'">'+
                                        '<i class="mdi mdi-bullhorn" style="pointer-events:none"></i>' +
                                    '</button>'+
                                    '<button data-bs-toggle="tooltip" title="Delete" data-id="' + value.id +
                                    '" type="button" class="delete btn  mb-1 btn-rounded btn-danger m-1">' +
                                    '<i class="mdi mdi-delete" style="pointer-events:none"></i>' +
                                    '</button>' +




                                    '</td>' +
                                    '</tr>';
                            });
                            $('#compaigns-table tbody').html(result)
                            $('#wait-modal').modal('hide');
                            // $('#loader-modal').css('display','none')
                            /***** refresh datatable **** **/

                            var compaigns = $('#compaigns-table').DataTable({
                                "iDisplayLength": 10,
                                destroy: true,
                                "bDestroy": true,
                                autoWidth: false,
                                columnDefs: [
                                    { targets: -1, width: 'auto' }
                                ],
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
            get_compaigns();

            function formatDateTime(dt) {
                if (!dt) return '-';
                const d = new Date(dt);
                const day = String(d.getDate()).padStart(2,'0');
                const month = String(d.getMonth()+1).padStart(2,'0');
                const year = d.getFullYear();
                const h = String(d.getHours()).padStart(2,'0');
                const m = String(d.getMinutes()).padStart(2,'0');
                return `${day}/${month}/${year} ${h}:${m}`;
            }
            $('#btnReservedSlots').on('click', function () {
                var url = "{{ url('') }}" + '/planning/slots/reserved/all';
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function (res) {
                        console.log(res)

                        let html = '';

                        res.data.forEach(function (row) {
                            html += `
                                <tr>
                                    <td>${row.slot_name}</td>
                                    <td>${row.dcp_name}</td>
                                    <td>${row.compaign_name}</td>
                                    <td>${row.movie_title}</td>
                                    <td>${row.genre_name}</td>
                                    <td>${row.duration}</td>
                                    <td>${row.location_name}</td>
                                </tr>
                            `;
                        });

                        $('#reservedSlotsTable').html(html);
                        $('#reservedSlotsModal').modal('show');
                    }
                });
            });

            /*$('#btnReservedSlots').on('click', function () {

                let data = {
                    start_date: $('#start_date').val(),
                    end_date: $('#end_date').val(),
                    template_slot_id: $('#template_slot_id').val(),
                    location_id: $('#location').val(),
                    movie_genre_id: $('#movie_genre').val(),
                };
                var url = "{{ url('') }}" + '/planning/slots/reserved';
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: data,
                    success: function (res) {

                        let html = '';

                        res.data.forEach(function (row) {
                            html += `
                                <tr>
                                    <td>${row.slot_name}</td>
                                    <td>${row.dcp_name}</td>
                                    <td>${row.compaign_name}</td>
                                    <td>${row.movie_title}</td>
                                    <td>${row.genre_name}</td>
                                    <td>${row.play_date}</td>
                                    <td>${row.duration}</td>
                                    <td>${row.location_name}</td>
                                </tr>
                            `;
                        });

                        $('#reservedSlotsTable').html(html);
                        $('#reservedSlotsModal').modal('show');
                    }
                });
            });*/
            $(document).on('click', '.view-dcps', function() {
                const compaignId = $(this).closest('[data-id]').attr('data-id');
                const url = "{{ url('planning/slots') }}/" + compaignId + "/dcps";

                $('#dcps_content').html('Loading...');
                $('#dcps_modal').modal('show');

                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(res) {
                        let html = '';

                        if (!res.slots.length) {
                            html = '<p class="text-center text-muted">No DCP creatives found.</p>';
                        } else {
                            res.slots.forEach(slot => {
                                html += `
                                    <table class="table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th>DCP</th>
                                                <th>Duration (s)</th>
                                                <th>Play At</th>
                                            </tr>
                                        </thead>
                                        <tbody>`;

                                        slot.dcps.forEach(dcp => {
                                            let dates = '-';
                                            if (dcp.play_at && dcp.play_at.length) {
                                                dates = dcp.play_at.map(dt => formatDateTime(dt)).join('<br>');
                                            }

                                            html += `
                                                <tr>
                                                    <td>${dcp.name}</td>
                                                    <td class="text-center">${dcp.duration}</td>

                                                </tr>`;
                                        });

                                html += `
                                            </tbody>
                                        </table>
                                    </div>`;
                            });
                        }

                        $('#dcps_content').html(html);
                    },
                    error: function() {
                        $('#dcps_content').html('<p class="text-danger">Failed to load data.</p>');
                    }
                });
            });

            $(document).on("submit", "#create_compaign_modal", function(event) {

                event.preventDefault();
                var name = $('#create_compaign_modal #name ').val();

                var url = '{{ url('') }}' + '/compaigns';

                $.ajax({
                        url: url,
                        type: 'POST',
                        method: 'POST',
                        data: {
                            name: name,
                            "_token": "{{ csrf_token() }}",
                        },
                    })
                    .done(function(response) {

                        get_compaigns();
                        Swal.fire({
                            title: 'Done!',
                            text: 'compaign Created successfully.',
                            icon: 'success',
                            confirmButtonText: 'Continue'
                        });
                        reset_form('#create_compaign_modal form')
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
                        $('#create_compaign_modal').modal('hide');
                    });

            })




            /* -------- APPROVE -------- */
            $(document).on('click', '.approuve', function () {
                var id  = $(this).closest('[id]').attr('id');
                var url = "{{ url('') }}" + '/compaigns/approuve/' + id;
                Swal.fire({
                    title: 'Approve this campaign?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    confirmButtonText: 'Yes, approve',
                }).then(function (result) {
                    if (!result.isConfirmed) return;
                    $('#page-loader').css('display', 'flex');
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: { _method: 'PUT', _token: "{{ csrf_token() }}" },
                        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                        success: function () {
                             get_compaigns();
                            $('#page-loader').hide();
                            Swal.fire('Approved!', 'Campaign has been approved.', 'success')

                        },
                        error: function (xhr) {
                            $('#page-loader').hide();
                            var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Operation failed.';
                            Swal.fire('Error', msg, 'error');
                        }
                    });
                });
            });

            /* -------- REJECT -------- */
            $(document).on('click', '.reject', function () {
                var id  = $(this).closest('[id]').attr('id');
                var url = "{{ url('') }}" + '/compaigns/reject/' + id;
                Swal.fire({
                    title: 'Reject this campaign?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'Yes, reject',
                }).then(function (result) {
                    if (!result.isConfirmed) return;
                    $('#page-loader').css('display', 'flex');
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: { _method: 'PUT', _token: "{{ csrf_token() }}" },
                        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                        success: function () {
                            get_compaigns()
                            $('#page-loader').hide();
                            Swal.fire('Rejected', 'Campaign has been rejected.', 'success');
                        },
                        error: function (xhr) {
                            $('#page-loader').hide();
                            var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Operation failed.';
                            Swal.fire('Error', msg, 'error');
                        }
                    });
                });
            });

            /* -------- DELETE -------- */
            $(document).on('click', '.delete', function () {
                var id  = $(this).attr('data-id');
                var url = "{{ url('') }}" + '/compaigns/' + id;
                Swal.fire({
                    title: 'Delete this campaign?',
                    text: 'This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'Yes, delete',
                }).then(function (result) {
                    if (!result.isConfirmed) return;
                    $('#page-loader').css('display', 'flex');
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: { _method: 'DELETE', _token: "{{ csrf_token() }}" },
                        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                        success: function () {
                            $('#page-loader').hide();
                            Swal.fire('Deleted!', 'Campaign has been deleted.', 'success')
                                .then(function () { get_compaigns(); });
                        },
                        error: function (xhr) {
                            $('#page-loader').hide();
                            var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Operation failed.';
                            Swal.fire('Error', msg, 'error');
                        }
                    });
                });
            });

            /* -------- VIEW -------- */
            $(document).on('click', '.view', function () {
                var id  = $(this).closest('[data-id]').attr('data-id');
                var url = "{{ url('') }}" + '/compaigns/' + id + '/show';

                // open modal + show loader
                $('#view_compaign_modal').modal('show');
                $('#cmpg_content').hide();
                $('#cmpg_loader').show();

                $.ajax({ url: url, method: 'GET' })
                    .done(function (d) {
                        // helpers
                        var jv = function(v){ return v || '–'; };
                        var jn = function(v){
                            if (!v) return '–';
                            if (Array.isArray(v)) return v.map(function(x){ return x.name; }).join(', ') || '–';
                            return v.name || '–';
                        };
                        var fmtDate = function(s){
                            if (!s) return '–';
                            var p = s.split('-');
                            if (p.length < 3) return s;
                            var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                            return p[2] + ' ' + months[parseInt(p[1],10)-1] + ' ' + p[0];
                        };
                        var statusMap = { 1:'<span class="badge bg-warning text-dark">Pending</span>',
                                          2:'<span class="badge bg-success">Approved</span>',
                                          3:'<span class="badge bg-secondary">Draft</span>',
                                          4:'<span class="badge bg-danger">Rejected</span>' };

                        $('#v_name').text(jv(d.name));
                        $('#v_user').text(d.user ? (d.user.name || '') + ' ' + (d.user.last_name || '') : '–');
                        $('#v_objective').text(jn(d.compaign_objective));
                        $('#v_category').text(jn(d.compaign_category));
                        $('#v_start').text(fmtDate(d.start_date));
                        $('#v_end').text(fmtDate(d.end_date));
                        $('#v_budget').text(d.budget ? '$' + Number(d.budget).toLocaleString() : '–');
                        $('#v_langue').text(jn(d.langue));
                        $('#v_duration').text(d.ad_duration ? d.ad_duration + ' s' : '–');
                        $('#v_status').html(statusMap[d.status] || '–');
                        $('#v_note').text(jv(d.note));

                        $('#v_locations').text(jn(d.locations));
                        $('#v_hall_types').text(jn(d.hall_types));
                        $('#v_movie').text(jn(d.movies));
                        $('#v_movie_genres').text(jn(d.movie_genres));
                        $('#v_gender').text(jn(d.gender));
                        $('#v_target_types').text(jn(d.target_types));
                        $('#v_interests').text(jn(d.interests));

                        // slots + DCPs
                        var html = '';
                        if (d.slots && d.slots.length) {
                            d.slots.forEach(function(slot) {
                                html += '<div class="border rounded p-2 mb-2"><strong>' + slot.name + '</strong>' +
                                        '<span class="text-muted ms-1 small">(Max: ' + slot.max_duration + 's)</span>' +
                                        '<ul class="mt-1 mb-0 small">';
                                var dcps = (d.dcp_creatives || []).filter(function(dcp){
                                    return dcp.pivot && dcp.pivot.slot_id == slot.id;
                                });
                                if (dcps.length) {
                                    dcps.forEach(function(dcp){
                                        html += '<li>' + dcp.name + ' – ' + dcp.duration + 's</li>';
                                    });
                                } else {
                                    html += '<li class="text-muted">No DCP assigned</li>';
                                }
                                html += '</ul></div>';
                            });
                        } else {
                            html = '<div class="text-muted small">No slots found.</div>';
                        }
                        $('#v_slots_details').html(html);
                    })
                    .fail(function () {
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Unable to load campaign details.' });
                        $('#view_compaign_modal').modal('hide');
                    })
                    .always(function () {
                        $('#cmpg_loader').hide();
                        $('#cmpg_content').show();
                    });
            });

        });
    </script>
@endsection

@section('custom_css')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/dist/css/select2.min.css') }}">

    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
@endsection
