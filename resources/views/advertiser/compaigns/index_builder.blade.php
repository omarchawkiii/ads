@extends('advertiser.layouts.app')
@section('title')
    Campaign
@endsection
@section('content')

    <div class="">
        <div class="row">

            <!-- LEFT : DCP creatives -->
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white">
                        DCP Creatives
                    </div>
                    <div class="card-body" style="padding: 15px">

                        <div class="mb-2">
                            <select id="dcp-category-filter" class=" form-select">
                                <option value="">All categories</option>
                                @foreach ($compaign_categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>


                        <ul id="dcp-list" class="list-group">
                            @foreach ($dcp_creatives as $dcp)
                                <li class="list-group-item dcp-item mb-1"
                                    data-id="{{ $dcp->id }}"
                                    data-duration="{{ $dcp->duration }}"
                                    data-category="{{ $dcp->compaign_category_id }}">
                                    <div>
                                        <p class="mb-1">{{ $dcp->name }}</p>
                                        <span class="badge bg-secondary">
                                            Duration: {{ (int) $dcp->duration }}s
                                        </span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- RIGHT -->
            <div class="col-md-9">

                <!-- Filters -->
                <div class="card mb-3">
                    <div class="card-header bg-info text-white">
                        Filters
                    </div>
                    <div class="card-body">
                        <div class="row g-2">

                            <div class="col-md-3">
                                <label>Start Date</label>
                                <input type="date" id="start_date" name="start_date" class="form-control">
                            </div>

                            <div class="col-md-3">
                                <label>End Date</label>
                                <input type="date" id="end_date" name="end_date" class="form-control">
                            </div>

                            <div class="col-md-3">
                                <label>Template Slot</label>
                                <select id="template_slot" name="template_slot" class="form-select">
                                    <option value="">Select...</option>
                                    @foreach ($slot_templates as $tpl)
                                        <option value="{{ $tpl->id }}">{{ $tpl->name }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="col-md-3">
                                <label for="hall_type"> Hall Types : <span
                                        class="danger">*</span>
                                </label>
                                <select class="form-select required select2" id="hall_type" multiple=""
                                    name="hall_type[]"  data-placeholder="Select hall types">
                                    <option value="__all__">Select All</option>
                                    @foreach ($hall_types as $hall_type)
                                        <option value="{{ $hall_type->id }}">{{ $hall_type->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label>Cinema Chain</label>
                                <select id="cinema_chain" name="cinema_chain" class="form-select">
                                    <option value="">Select...</option>
                                    @foreach ($cinema_chains as $chain)
                                        <option value="{{ $chain->id }}">{{ $chain->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-5">
                                <label>Location</label>
                                <select id="location" name="location[]" multiple class="form-select select2" data-placeholder="Select Location" disabled>

                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Genre</label>
                                <select id="movie_genre" name="movie_genre[]" multiple class="form-select select2" data-placeholder="Select Genre">
                                    <option value="__all__">Select All</option>
                                    @foreach ($movie_genres as $g)
                                        <option value="{{ $g->id }}">{{ $g->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-10">
                                <label for="movie"> Select Movies : </label>
                                <select class="form-select select2" multiple id="movie" name="movie[]" >
                                    <option value="__all__">Select All</option>
                                    @foreach ($movies as $movie)
                                        <option value="{{ $movie->id }}"
                                                data-genre="{{ $movie->movie_genre_id }}">
                                            {{ $movie->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2 d-flex align-items-end">
                                <button id="btn-load-slots" class="btn btn-primary w-100">
                                    Load Slots
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Slots -->
                <div class="card">
                    <div class="card-header bg-success text-white">
                        Available Slots
                    </div>
                    <div class="card-body">
                        <div id="slots-container" class="row g-3">
                            <div class="text-center text-muted">
                                Please select filters and load slots
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col text-end">
                                <button id="btn-save-campaign" class="btn btn-success">
                                    💾 Save Campaign
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="saveCampaignModal" tabindex="-1">
        <div class="modal-dialog modal-lg  modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Save Campaign</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Campaign Name</label>
                                <input type="text" id="compaign_name" class="form-control"
                                    placeholder="Enter campaign name" >
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="budget">Desired Budget</label> <span
                                    class="danger">(RM)</span>
                                <input type="number" class="form-control" id="budget" name="budget" />
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
                                <label class="form-label" for="target_type"> Audience Targeting : <span
                                        class="danger">*</span>
                                </label>
                                <select class="form-select  select2 required" id="target_type"
                                    name="target_type[]" multiple>
                                    <option value="__all__">Select All</option>
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
                                <select class="form-select required select2"  id="interest"
                                    name="interest[]" multiple>

                                    <option value="__all__">Select All</option>
                                    @foreach ($interests as $interest)
                                        <option value="{{ $interest->id }}">{{ $interest->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button id="confirm-save-campaign" class="btn btn-primary">
                        Confirm
                    </button>
                </div>

            </div>
        </div>
    </div>

@endsection


@section('custom_script')
    <script src="{{ asset('assets/libs/jquery-steps/build/jquery.steps.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/dist/js/select2.min.js') }}"></script>
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="{{ asset('assets/js/helper.js') }}"></script>
    <script>
        $(function() {



            $(document).ready(function () {

                const $genreSelect = $('#movie_genre');
                const $movieSelect = $('#movie');

                function filterMoviesByGenre() {
                    const selectedGenres = $genreSelect.val() || [];
                    if (
                        selectedGenres.length === 0 ||
                        selectedGenres.includes('__all__')
                    ) {
                        $movieSelect.find('option').each(function () {
                            $(this).prop('disabled', false).show();
                        });

                        $movieSelect.trigger('change.select2');
                        return;
                    }

                    // sinon filtrer par genre
                    $movieSelect.find('option').each(function () {
                        const genreId = $(this).data('genre');

                        // garder "Select All"
                        if ($(this).val() === '__all__') {
                            $(this).prop('disabled', false).show();
                            return;
                        }

                        if (selectedGenres.includes(String(genreId))) {
                            $(this).prop('disabled', false).show();
                        } else {
                            $(this).prop('disabled', true).hide();
                            $(this).prop('selected', false);
                        }
                    });

                    $movieSelect.trigger('change.select2');
                }
                $genreSelect.on('change', filterMoviesByGenre);
            });

            function initSelect2WithSelectAll(selector, parent = null) {

                $(selector).select2({
                    width: '100%',
                    closeOnSelect: true,
                    dropdownParent: parent ? $(parent) : $(document.body)
                });

                $(selector).on('select2:select select2:unselect', function (e) {

                    const ALL_VALUE = '__all__';
                    const $select = $(this);
                    const values = $select.val() || [];

                    if (e.params?.data?.id === ALL_VALUE) {

                        if (values.includes(ALL_VALUE)) {
                            const allValues = $select.find('option:not(:disabled)')
                                .map(function () { return this.value })
                                .get()
                                .filter(v => v !== ALL_VALUE);

                            $select.val(allValues).trigger('change.select2');
                        } else {
                            $select.val(null).trigger('change.select2');
                        }

                        $select.select2('close');
                    }
                });
            }
            $(document).ready(function () {
                initSelect2WithSelectAll('#brand');
                initSelect2WithSelectAll('#location');
                initSelect2WithSelectAll('#hall_type');
                initSelect2WithSelectAll('#movie_genre');
                initSelect2WithSelectAll('#movie');

                //initSelect2WithSelectAll('#interest');
                initSelect2WithSelectAll('#dcp_creative');
                //initSelect2WithSelectAll('#target_type');
            });

            const today = new Date().toISOString().split('T')[0];
            $('#start_date').attr('min', today);
            $('#end_date').attr('min', today);

            $('#start_date').on('change', function() {
                $('#end_date').attr('min', $(this).val()).val('');
            });

            // load locations
            $('#cinema_chain').on('change', function () {

                let chainId = $(this).val();
                let $location = $('#location');

                // 🔄 Reset + loader
                $location
                    .prop('disabled', true)
                    .empty()
                    .append('<option value="">Loading locations...</option>')
                    .trigger('change'); // important pour Select2

                if (!chainId) {
                    $location.prop('disabled', false).empty().trigger('change');
                    return;
                }

                $.get("{{ url('') }}/advertiser/cinema-chain/" + chainId + "/locations")
                    .done(function (res) {

                        $location.empty();
                        if( res.locations.length)
                        {
                            // Option Select All (si besoin)
                            $location.append('<option value="__all__">Select All</option>');

                            res.locations.forEach(function (loc) {
                                $location.append(
                                    `<option value="${loc.id}">${loc.name}</option>`
                                );
                            });
                        }
                        $location.prop('disabled', false).trigger('change');
                    })
                    .fail(function () {
                        $location
                            .empty()
                            .append('<option value="">Error loading locations</option>')
                            .prop('disabled', false)
                            .trigger('change');
                    });
            });


            // draggable DCP
            $(".dcp-item").draggable({
                helper: "clone",
                revert: "invalid"
            });

            $('#btn-load-slots').on('click', function() {
                loadAvailableSlots();
            });

            function loadAvailableSlots() {

                const startDate = $('#start_date').val();
                const endDate   = $('#end_date').val();
                const locations = $('#location').val();
                const genres    = $('#movie_genre').val();
                const compaign_category = $('#compaign_category').val()
                const hall_type_id = $('#hall_type').val()

                console.log(hall_type_id)


                // 🔍 Front validation
                if (!startDate || !endDate || !locations || locations.length === 0 || !genres || genres.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Missing information',
                        text: 'Please select start date, end date, location and movie genre before loading slots.'
                    });
                    return;
                }

                $('#slots-container').html('<div class="text-center">Loading...</div>');

                $.ajax({
                    url: "{{ url('') }}/advertiser/slots/available",
                    method: "GET",
                    data: {
                        start_date: startDate,
                        end_date: endDate,
                        cinema_chain_id: $('#cinema_chain').val(),
                        location_id: locations,
                        movie_genre_id: genres,
                        compaign_category_id: compaign_category,
                        template_slot_id: $('#template_slot').val(),
                        hall_type_id: hall_type_id,
                        movie_id:$('#movie').val(),
                        _token: "{{ csrf_token() }}"
                    }
                }).done(function(res) {

                    $('#slots-container').empty();

                    if (!res.slots || res.slots.length === 0) {
                        $('#slots-container').html(
                            '<div class="text-center text-muted">No slots available</div>');
                        return;
                    }

                    res.slots.forEach(function(slot) {
                        $('#slots-container').append(`
                            <div class="col-md-4">
                                <div class="slot-box droppable"
                                    data-id="${slot.id}"
                                    data-remaining="${slot.remaining}"
                                    data-max="${slot.max_duration}">
                                    <strong>${slot.name}</strong><br>
                                    <small>
                                        Remaining: <span class="remaining">${slot.remaining}</span>s /
                                        Max: <span class="max">${slot.max_duration}</span>s
                                    </small>
                                    <div class="assigned-list mt-2"></div>
                                </div>
                            </div>
                        `);
                    });

                    initDroppable();
                });
            }
            function initDroppable(){
                $('.droppable').droppable({
                    accept: '.dcp-item',
                    hoverClass: 'active',
                    drop: function(e, ui){

                        let dcpId = ui.draggable.data('id');
                        let dcpName = ui.draggable.find('p:first').text();
                        let dcpDuration = parseInt(ui.draggable.data('duration'));

                        let $slot = $(this);
                        let remaining = parseInt($slot.data('remaining'));
                        let max = parseInt($slot.data('max'));

                        // ❗ Vérifier si le DCP est déjà assigné dans CE slot
                        let alreadyAssigned = $slot.find(`.assigned[data-dcp="${dcpId}"]`).length > 0;

                        if (alreadyAssigned) {
                            showError("This creative has already been assigned to this slot.");
                            return;
                        }

                        // ❗ 1) Check max duration
                        if(dcpDuration > max){
                            showError(
                                "This creative cannot be assigned to this slot because its duration is greater than the maximum allowed duration of the slot."
                            );
                            return;
                        }

                        // ❗ 2) Check remaining duration
                        if(dcpDuration > remaining){
                            showError(
                                "This creative cannot be assigned to this slot because there is not enough remaining time available."
                            );
                            return;
                        }

                        // ✅ 3) Update remaining
                        let newRemaining = remaining - dcpDuration;
                        $slot.data('remaining', newRemaining);
                        $slot.find('.remaining').text(newRemaining);

                        // ✅ 4) Append assigned DCP with remove button
                        let $item = $(`
                            <div class="assigned" data-dcp="${dcpId}" data-duration="${dcpDuration}">
                                <span>${dcpName} (${dcpDuration}s)</span>
                                <span class="remove">×</span>
                            </div>
                        `);

                        $slot.find('.assigned-list').append($item);

                        // ✅ 5) Notify backend
                        $.post("{{ url('') }}/advertiser/slots/assign-dcp", {
                            _token: "{{ csrf_token() }}",
                            slot_id: $slot.data('id'),
                            dcp_id: dcpId
                        });
                    }
                });
            }
            function showError(message){
                Swal.fire({
                    icon: 'error',
                    title: 'Not allowed',
                    text: message,
                    confirmButtonColor: '#d33'
                });
            }
            // remove assigned DCP
            $(document).on('click', '.assigned .remove', function(){
                let $item = $(this).closest('.assigned');

                Swal.fire({
                    icon: 'warning',
                    title: 'Remove creative?',
                    text: 'Do you really want to remove this creative from the slot?',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, remove',
                    cancelButtonText: 'Cancel'
                }).then((result) => {

                    if(!result.isConfirmed) return;

                    let duration = parseInt($item.data('duration'));
                    let dcpId = $item.data('dcp');
                    let $slot = $item.closest('.slot-box');

                    let remaining = parseInt($slot.data('remaining')) + duration;
                    $slot.data('remaining', remaining);
                    $slot.find('.remaining').text(remaining);

                    $item.remove();

                    $.post("{{ url('') }}/advertiser/slots/remove-dcp", {
                        _token: "{{ csrf_token() }}",
                        slot_id: $slot.data('id'),
                        dcp_id: dcpId
                    });
                });
            });
            $('#btn-save-campaign').on('click', function(){
                initSelect2WithSelectAll('#interest')
                initSelect2WithSelectAll('#target_type')

                $('#compaign_name').val('');
                $('#compaign_category').val('');
                $('#budget').val('');
                $('#langue').val('');
                $('#target_type').val('');
                $('#interest').val('');


                const startDate = $('#start_date').val();
                const endDate   = $('#end_date').val();
                const locations = $('#location').val();
                const genres    = $('#movie_genre').val();

                // 🔍 Front validation
                if (!startDate || !endDate || !locations || locations.length === 0 || !genres || genres.length === 0 ) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Missing information',
                        text: 'Please select start date, end date, location and movie genre before loading slots.'
                    });
                    return;
                }

                let slotsData = [];
                $('.slot-box').each(function(){
                    let $slot = $(this);
                    let slotId = $slot.data('id');

                    let dcps = [];
                    $slot.find('.assigned').each(function(){
                        dcps.push({
                            dcp_id: $(this).data('dcp'),
                            duration: $(this).data('duration')
                        });
                    });

                    if(dcps.length > 0){
                        slotsData.push({
                            slot_id: slotId,
                            dcps: dcps
                        });
                    }
                });
                if(!slotsData.length)
                {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Missing DCP creative',
                        text: 'No DCP creative has been assigned to any slot yet..'
                    });
                    return;
                }

                $('#saveCampaignModal').modal('show');
            });
            $('#confirm-save-campaign').on('click', function(){

                let campaignName = $('#compaign_name').val().trim();

                if(!campaignName){
                    showError(
                        "Please enter a campaign name."
                    );

                    return;
                }

                let slotsData = [];

                $('.slot-box').each(function(){
                    let $slot = $(this);
                    let slotId = $slot.data('id');

                    let dcps = [];
                    $slot.find('.assigned').each(function(){
                        dcps.push({
                            dcp_id: $(this).data('dcp'),
                            duration: $(this).data('duration')
                        });
                    });

                    if(dcps.length > 0){
                        slotsData.push({
                            slot_id: slotId,
                            dcps: dcps
                        });
                    }
                });

                let payload = {
                    _token: "{{ csrf_token() }}",

                    compaign_name: campaignName,

                    // filters
                    start_date: $('#start_date').val(),
                    end_date: $('#end_date').val(),
                    cinema_chain_id: $('#cinema_chain').val(),
                    budget: $('#budget').val() ?? 0,
                    langue: $('#langue').val() ,
                    gender: $('#gender').val() ,
                    location_id: $('#location').val(),
                    movie_genre_id: $('#movie_genre').val(),
                    compaign_category_id: $('#compaign_category').val(),
                    template_slot_id: $('#template_slot').val(),
                    hall_type_id: $('#hall_type').val(),
                    movie_id:$('#movie').val(),
                    target_type: $('#target_type').val() ,
                    interest: $('#interest').val() ,


                    slots: slotsData
                };

                $.ajax({
                    url: "{{ url('') }}/advertiser/compaigns",
                    method: "POST",
                    data: payload,
                    beforeSend: function(){
                        $('#confirm-save-campaign').prop('disabled', true);
                    }
                })
                .done(function(res){
                    Swal.fire({
                            title: 'Done!',
                            text: 'Campaign Created successfully.',
                            icon: 'success',
                            confirmButtonText: 'Continue'
                        });
                    $('#saveCampaignModal').modal('hide');
                    $('#confirm-save-campaign').prop('disabled', false);
                    // option: redirect
                    // window.location.href = res.redirect;
                })
                .fail(function(){
                    showError(
                        "Error while saving campaign."
                    );

                    $('#confirm-save-campaign').prop('disabled', false);
                });
            });

            $(document).on('change', '#dcp-category-filter', function () {
                const selected = $(this).val();

                $('#dcp-list .dcp-item').each(function () {
                    const itemCat = $(this).data('category');

                    if (!selected || itemCat == selected) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });


        });


    </script>
@endsection

@section('custom_css')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/dist/css/select2.min.css') }}">

    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />

    <style>
        .select2-container.select2-container--default.select2-container--open
        {
            z-index: 9999999 !important ;
        }

        .dcp-item {
            cursor: grab;
        }

        .slot-box {
            border: 2px dashed #ccc;
            padding: 15px;
            min-height: 120px;
            border-radius: 6px;
            background: #f9f9f9;
        }

        .slot-box.active {
            border-color: #0d6efd;
            background: #eef5ff;
        }

        .assigned {
            background: #d1e7dd;
            border-radius: 4px;
            padding: 4px 8px;
            margin-bottom: 5px;
            font-size: 13px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .assigned .remove {
            cursor: pointer;
            color: #dc3545;
            font-weight: bold;
            margin-left: 8px;
        }
        .list-group-item
        {
            margin: 11px 0;
            border-radius: 10px;
            border: 1px solid #e0e6eb;
            border-top-width: 1px !important;
        }


        body .select2-container--default .select2-selection--multiple
        {
            line-height: 28px;
            min-height: 40px;
        }
        .select2-container--default .select2-selection--multiple {
    position: relative;
    padding-right: 30px;
}

.select2-container--default .select2-selection--multiple::after {
    content: "▾";
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #666;
    pointer-events: none;
}

    </style>
@endsection
