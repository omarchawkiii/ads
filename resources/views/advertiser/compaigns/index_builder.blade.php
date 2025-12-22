@extends('advertiser.layouts.app')
@section('title')
    Campaign
@endsection
@section('content')

    <div class="container-fluid">
        <div class="row">

            <!-- LEFT : DCP creatives -->
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white">
                        DCP Creatives
                    </div>
                    <div class="card-body">

                        <div class="mb-2">
                            <select id="dcp-category-filter" class="form-select form-select-sm">
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
                                            Duration: {{ $dcp->duration }}s
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
                                <label>Ad Category</label>
                                <select id="compaign_category" name="compaign_category" class="form-select">
                                    <option value="">Select...</option>
                                    @foreach ($compaign_categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
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
                                <select id="location" name="location[]" multiple class="form-select select2">
                                    <option value="__all__">Select All</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Genre</label>
                                <select id="movie_genre" name="movie_genre[]" multiple class="form-select select2">
                                    <option value="__all__">Select All</option>
                                    @foreach ($movie_genres as $g)
                                        <option value="{{ $g->id }}">{{ $g->name }}</option>
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
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Save Campaign</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label>Campaign Name</label>
                        <input type="text" id="compaign_name" class="form-control"
                               placeholder="Enter campaign name" >
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
    <script src="{{ asset('assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>>
    <script src="{{ asset('assets/libs/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/dist/js/select2.min.js') }}"></script>
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="{{ asset('assets/js/helper.js') }}"></script>
    <script>
        $(function() {


            function initSelect2WithSelectAll(selector) {
                $(selector).select2({
                    width: '100%',
                    closeOnSelect: false
                });

                $(selector).on('select2:select select2:unselect', function (e) {

                    const ALL_VALUE = '__all__';
                    const $select = $(this);
                    const values = $select.val() || [];

                    // Si on clique sur "Select All"
                    if (e.params?.data?.id === ALL_VALUE) {
                        if (values.includes(ALL_VALUE)) {
                            // sélectionner tout sauf __all__
                            const allValues = $select.find('option')
                                .map(function () { return this.value; })
                                .get()
                                .filter(v => v !== ALL_VALUE);

                            $select.val(allValues).trigger('change.select2');
                        } else {
                            // désélectionner tout
                            $select.val(null).trigger('change.select2');
                        }
                    }
                });
            }
            $(document).ready(function () {
                initSelect2WithSelectAll('#brand');
                initSelect2WithSelectAll('#location');
                initSelect2WithSelectAll('#hall_type');
                initSelect2WithSelectAll('#movie_genre');
                initSelect2WithSelectAll('#interest');
                initSelect2WithSelectAll('#dcp_creative');
                initSelect2WithSelectAll('#target_type');
            });

            const today = new Date().toISOString().split('T')[0];
            $('#start_date').attr('min', today);
            $('#end_date').attr('min', today);

            $('#start_date').on('change', function() {
                $('#end_date').attr('min', $(this).val()).val('');
            });

            // load locations
            $('#cinema_chain').on('change', function() {
                let chainId = $(this).val();
                let $location = $('#location');
                $location.empty().append('<option value="__all__">Select All</option>');

                if (!chainId) return;

                $.get("{{ url('') }}/advertiser/cinema-chain/" + chainId + "/locations")
                    .done(function(res) {
                        res.locations.forEach(function(loc) {
                            $location.append(`<option value="${loc.id}">${loc.name}</option>`);
                        });
                        $location.trigger('change');
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
                        compaign_category_id: $('#compaign_category').val(),
                        template_slot_id: $('#template_slot').val(),
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
                        let dcpName = ui.draggable.find('span:first').text();
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
                $('#compaign_name').val('');
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
                    location_id: $('#location').val(),
                    movie_genre_id: $('#movie_genre').val(),
                    compaign_category_id: $('#compaign_category').val(),
                    template_slot_id: $('#template_slot').val(),

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
    </style>
@endsection
