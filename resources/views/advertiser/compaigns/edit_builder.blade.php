@extends('advertiser.layouts.app')
@section('title')
    Campaign
@endsection

@section('content')

    <div class="">
        <div class="row">

            {{-- ================= LEFT : DCP creatives ================= --}}
            <div class="col-md-3 ">
                <div class="card h-100 screen-max-height">
                    <div class="card-header bg-primary text-white">
                        DCP Creatives
                    </div>
                    <div class="card-body " style="padding:15px">

                        <div class="mb-2">
                            <select id="dcp-category-filter" class="form-select">
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
                                    data-duration="{{ (int)$dcp->duration }}"
                                    data-category="{{ $dcp->compaign_category_id }}">
                                    <p class="mb-1">{{ $dcp->name }}</p>
                                    <span class="badge bg-secondary">
                                        Duration: {{ (int)$dcp->duration }}s
                                    </span>
                                </li>
                            @endforeach
                        </ul>

                    </div>
                </div>
            </div>

            {{-- ================= RIGHT ================= --}}
            <div class="col-md-9 ">

                {{-- ================= FILTERS ================= --}}
                <div class="card mb-3 filter">
                    <div class="card-header bg-info text-white">
                        Filters
                    </div>
                    <div class="card-body padding-15">
                        <div class="row g-2">

                            <div class="col-md-3">
                                <label>Start Date</label>
                                <input type="date" id="start_date" class="form-control"
                                    value="{{ $isEdit ? $compaign->start_date : '' }}">
                            </div>

                            <div class="col-md-3">
                                <label>End Date</label>
                                <input type="date" id="end_date" class="form-control"
                                    value="{{ $isEdit ? $compaign->end_date : '' }}">
                            </div>

                            <div class="col-md-3">
                                <label>Template Slot</label>
                                <select id="template_slot" class="form-select">
                                    <option value="">Select...</option>
                                    @foreach ($slot_templates as $tpl)
                                        <option value="{{ $tpl->id }}"
                                            @selected($isEdit && $compaign->template_slot_id == $tpl->id)>
                                            {{ $tpl->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label>Hall Types</label>
                                <select id="hall_type" class="form-select select2" multiple>
                                    <option value="__all__">Select All</option>
                                    @foreach ($hall_types as $hall)
                                        <option value="{{ $hall->id }}"
                                            @selected($isEdit && $compaign->hallTypes->contains($hall->id))>
                                            {{ $hall->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label>Cinema Chain</label>
                                <select id="cinema_chain" class="form-select select2" multiple name="cinema_chain[]" >
                                    <option value="__all__">Select All</option>
                                    @foreach ($cinema_chains as $chain)
                                        <option value="{{ $chain->id }}"
                                            @selected($isEdit && $compaign->cinemaChains->contains($chain->id))>
                                            {{ $chain->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-5">
                                <label>Location</label>
                                <select id="location" class="form-select select2" multiple >
                                    <option value="__all__">Select All</option>
                                    @foreach ($locationsOfCinemaChaings as $location)
                                        <option
                                            value="{{ $location->id }}"
                                            @if(in_array($location->id, $selectedLocations ?? [])) selected @endif
                                        >
                                            {{ $location->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Genre</label>
                                <select id="movie_genre" class="form-select select2" multiple>
                                    <option value="__all__">Select All</option>
                                    @foreach ($movie_genres as $g)
                                        <option value="{{ $g->id }}"
                                            @selected($isEdit && $compaign->movieGenres->contains($g->id))>
                                            {{ $g->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-10">
                                <label>Movies</label>
                                <select id="movie" class="form-select select2" multiple>
                                    <option value="__all__">Select All</option>
                                    @foreach ($movies as $movie)
                                        <option value="{{ $movie->id }}"
                                                data-genre="{{ $movie->movie_genre_id }}"
                                            @selected($isEdit && $compaign->movies->contains($movie->id))>
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

                {{-- ================= SLOTS ================= --}}
                <div class="card available-slots-max-height">
                    <div class="card-header bg-success text-white">
                        Available Slots
                    </div>
                    <div class="card-body padding-15">
                        <div id="slots-container" class="row g-3">
                            <div class="text-center text-muted">
                                Please select filters and load slots
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col text-end">
                                <button id="btn-save-campaign" class="btn btn-success">
                                    {{ $isEdit ? '💾 Update Campaign' : '💾 Save Campaign' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ================= RESERVED INFO MODAL ================= --}}
    <div class="modal fade" id="reservedInfoModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title fw-bold">Position Reserved</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="reservedInfoBody">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= SAVE MODAL ================= --}}

    <div class="modal fade" id="saveCampaignModal" tabindex="-1">
        <div class="modal-dialog modal-lg  modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header d-flex align-items-center  bg-primary ">
                    <h4 class="modal-title text-white " id="myLargeModalLabel ">
                        Update Campaign
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>




                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Campaign Name</label>
                                <input type="text" id="compaign_name" class="form-control"
                                    placeholder="Enter campaign name"  value="{{ $isEdit ? $compaign->name : '' }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="budget">Desired Budget</label> <span
                                    class="danger">(RM)</span>
                                <input type="number" class="form-control" id="budget" name="budget"  value="{{ $isEdit ? $compaign->budget : '' }}"/>
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
                                        <option value="{{ $langue->id }}" @selected($isEdit && $compaign->langue_id == $langue->id)>{{ $langue->name }}</option>
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
                                        <option value="{{ $gender->id }}"
                                            @selected($isEdit && $compaign->gender_id == $gender->id)
                                            >{{ $gender->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="target_type"> Audience Targeting : <span
                                        class="danger">*</span>
                                </label>
                                <select class="form-select select2 required" id="target_type"
                                    name="target_type[]" multiple>
                                    <option value="__all__">Select All</option>
                                    @foreach ($target_types as $target_type)
                                        <option value="{{ $target_type->id }}" @selected($isEdit && $compaign->targetTypes->contains($target_type->id))>{{ $target_type->name }}
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
                                        <option value="{{ $interest->id }}" @selected($isEdit && $compaign->interests->contains($interest->id))>{{ $interest->name }}</option>
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
    <script src="{{ asset('assets/js/helper.js') }}?v={{ filemtime(public_path('assets/js/helper.js')) }}"></script>
    <script>

        $(function(){

            const EXISTING_SLOTS = @json($slotsData);
            const DCP_LIST = @json($dcp_creatives);
            const COMPAIGN_ID = {{ $compaign->id }};


            function renderDcpList(){
                const $dcpList = $('#dcp-list');
                $dcpList.empty();
                DCP_LIST.forEach(dcp => {
                    $dcpList.append(`
                        <li class="list-group-item dcp-item mb-1"
                            data-id="${dcp.id}"
                            data-duration="${dcp.duration}"
                            data-category="${dcp.compaign_category_id}">
                            <div>
                                <p class="mb-1">${dcp.name}</p>
                                <span class="badge bg-secondary">Duration: ${parseInt(dcp.duration)}s</span>
                            </div>
                        </li>
                    `);
                });
                $(".dcp-item").draggable({helper: "clone", revert: "invalid"});
            }

            function renderSlots(slots) {
                const $container = $('#slots-container');
                $container.empty();

                if (!slots.length) {
                    $container.html('<div class="text-center text-muted">No slots available</div>');
                    return;
                }

                slots.forEach(slot => {

                    const totalPositions = slot.max_ad_slot;
                    const assignedCount = slot.assigned_dcp ?? 0;
                    let remainingDuration = slot.remaining_duration;
                    console.log(slot)
                    let positionsHtml = '';

                    // 🔹 Construire chaque position
                    for (let posNum = 1; posNum <= totalPositions; posNum++) {

                        const positionData = slot.positions?.find(p => p.position === posNum);

                        if (positionData) {
                            const dcpName = DCP_LIST.find(x => x.id === positionData.dcp_id)?.name ?? 'Reserved';
                            const dcpDuration = DCP_LIST.find(x => x.id === positionData.dcp_id)?.duration ?? 'Reserved';
                            if (positionData.type === 'reserved') {
                                // 🔒 position réservée (autre campagne)
                                positionsHtml += `
                                    <div class="slot-position reserved" data-position="${posNum}">
                                        <span class="badge bg-secondary">Reserved (${dcpDuration}) S</span>
                                    </div>
                                `;
                                //remainingDuration -= positionData.duration;
                            } else {
                                // 🟢 position de la campagne courante
                                positionsHtml += `
                                    <div class="slot-position droppable-position"
                                        data-position="${posNum}"
                                        data-slot="${slot.slot_id}">
                                        <div class="assigned draggable"
                                            data-dcp="${positionData.dcp_id}"
                                            data-duration="${positionData.duration}"
                                            data-position="${posNum}">
                                            <span>${dcpName} (${positionData.duration}s)</span>
                                            <span class="remove">×</span>
                                        </div>
                                    </div>
                                `;
                                //remainingDuration -= positionData.duration;
                            }
                        } else {
                            // 🟢 position libre
                            positionsHtml += `
                                <div class="slot-position droppable-position"
                                    data-position="${posNum}"
                                    data-slot="${slot.slot_id}">
                                    <span class="">Drop DCP here</span>
                                </div>
                            `;
                        }
                    }

                    // 🔹 Ajouter le slot dans le DOM
                    $container.append(`
                        <div class="col-md-4">
                            <div class="slot-box"
                                data-slot="${slot.slot_id}"
                                data-max="${slot.max_duration}"
                                data-remaining="${slot.remaining_duration}"
                                data-max_ad_slot="${slot.max_ad_slot}"
                                data-assigned="${assignedCount}">
                                <strong>${slot.name}</strong><br>
                                <small>
                                    Remaining: <span class="remaining">${slot.remaining_duration}</span>s /
                                    Max: <span class="max">${slot.max_duration}</span>s
                                </small>

                                <div class="positions mt-2">
                                    ${positionsHtml}
                                </div>
                            </div>
                        </div>
                    `);
                });

                initPositionDroppableEdit();
            }


            function initPositionDroppableEdit() {
                $('.dcp-item').draggable({
                    helper: 'clone',
                    appendTo: 'body',
                    zIndex: 9999,
                    revert: 'invalid',
                    cursor: 'move',

                    start: function () {
                        $('body').addClass('dragging-dcp');
                    },

                    stop: function () {
                        $('body').removeClass('dragging-dcp');
                    }
                });

                $('.droppable-position').droppable({
                    accept: '.dcp-item',
                    hoverClass: 'droppable-hover',


                    drop: function (e, ui) {

                        const $position = $(this);
                        const $slotBox = $position.closest('.slot-box');

                        const slotId = $slotBox.data('slot');
                        const positionNum = parseInt($position.data('position'), 10);

                        const dcpId = ui.draggable.data('id');
                        const dcpName = ui.draggable.find('p:first').text();
                        const dcpDuration = parseInt(ui.draggable.data('duration'), 10);

                        let remaining = parseInt($slotBox.data('remaining'), 10);
                        let maxDuration = parseInt($slotBox.data('max'), 10);
                        let maxAdSlot = parseInt($slotBox.data('max_ad_slot'), 10);
                        let assigned = parseInt($slotBox.data('assigned') || 0, 10);

                        // ❌ Vérifications
                        if ($position.find('.assigned').length) {
                            showError("This position is already occupied.");
                            return;
                        }
                        if ($slotBox.find(`.assigned[data-dcp="${dcpId}"]`).length) {
                            showError("This creative is already assigned in this slot.");
                            return;
                        }

                        if (dcpDuration > maxDuration) {
                            showError("This creative exceeds the maximum duration of the slot.");
                            return;
                        }
                        if (dcpDuration > remaining) {
                            showError("Not enough remaining time in this slot.");
                            return;
                        }

                        // ✅ Mise à jour des compteurs
                        remaining -= dcpDuration;
                        assigned++;
                        $slotBox.data('remaining', remaining);
                        $slotBox.data('assigned', assigned);

                        $slotBox.find('.remaining').text(remaining);
                        $slotBox.find('.remaining-pos').text(maxAdSlot - assigned);

                        // ✅ Ajouter le DCP dans la position
                        $position.html(`
                            <div class="assigned draggable"
                                data-dcp="${dcpId}"
                                data-duration="${dcpDuration}"
                                data-position="${positionNum}">
                                <span>${dcpName} (${dcpDuration}s)</span>
                                <span class="remove">×</span>
                            </div>
                        `);

                        // ✅ Gestion suppression
                        $position.find('.remove').on('click', function () {
                            $slotBox.data('remaining', remaining + dcpDuration);
                            $slotBox.data('assigned', assigned - 1);

                            $slotBox.find('.remaining').text(remaining + dcpDuration);
                            $slotBox.find('.remaining-pos').text(maxAdSlot - (assigned - 1));

                            $position.html('<span class="">Drop DCP here</span>');
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
            $(document).on('click', '.assigned .remove', function(){

                let $assigned = $(this).closest('.assigned');
                let duration = parseInt($assigned.data('duration'));
                let $slot = $assigned.closest('.slot-box');

                let remaining = parseInt($slot.data('remaining'));
                let assignedCount = parseInt($slot.data('assigned'));
                let maxAdSlot = parseInt($slot.data('max_ad_slot'));

                // ➖ rollback
                remaining += duration;
                assignedCount--;

                $slot.data('remaining', remaining);
                $slot.data('assigned', assignedCount);

                $slot.find('.remaining').text(remaining);
                $slot.find('.remaining-positions').text(maxAdSlot - assignedCount);

                $assigned.remove();
            });

            // ⚡ Initial render
            renderDcpList();
            renderSlots(EXISTING_SLOTS);

            /* =====================================================
                *  SELECT2 + SELECT ALL
                * ===================================================== */
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

            initSelect2WithSelectAll('#hall_type');
            initSelect2WithSelectAll('#location');
            initSelect2WithSelectAll('#movie_genre');
            initSelect2WithSelectAll('#movie');
            initSelect2WithSelectAll('#cinema_chain');
            //initSelect2WithSelectAll('#interest');



            /* =====================================================
                *  DATE LOGIC
                * ===================================================== */
            const today = new Date().toISOString().split('T')[0];
            $('#start_date').attr('min', today);
            $('#end_date').attr('min', today);

            $('#start_date').on('change', function () {
                $('#end_date').attr('min', $(this).val()).val('');
            });

            /* =====================================================
                *  LOAD LOCATIONS
                * ===================================================== */
            $('#cinema_chain').on('change', function () {

                let chainId = $(this).val();
                let $location = $('#location');

                $location.prop('disabled', true).empty().trigger('change');

                if (!chainId) {
                    $location.prop('disabled', false);
                    return;
                }

                $.get("{{ url('') }}/advertiser/cinema-chain/get_location_from_cienma_chain",
                    {
                        cinema_chain_ids: $('#cinema_chain').val(),
                    })
                    .done(function (res) {

                        $location.append('<option value="__all__">Select All</option>');

                        res.locations.forEach(function (loc) {
                            $location.append(`<option value="${loc.id}">${loc.name}</option>`);
                        });

                        $location.prop('disabled', false).trigger('change');


                        const selectedLocations = @json($compaign->locations->pluck('id') ?? []);
                        $location.val(selectedLocations).trigger('change.select2');

                    });
            });

            /* =====================================================
                *  FILTER MOVIES BY GENRE
                * ===================================================== */
            $('#movie_genre').on('change', function () {

                const selected = $(this).val() || [];

                $('#movie option').each(function () {

                    if ($(this).val() === '__all__') return;

                    const genre = $(this).data('genre');

                    if (selected.includes('__all__') || selected.includes(String(genre))) {
                        $(this).prop('disabled', false).show();
                    } else {
                        $(this).prop('disabled', true).hide().prop('selected', false);
                    }
                });

                $('#movie').trigger('change.select2');
            });

            /* =====================================================
                *  DRAGGABLE DCP
                * ===================================================== */
            $('.dcp-item').draggable({
                helper: 'clone',
                revert: 'invalid'
            });

            /* =====================================================
                *  LOAD SLOTS
            * ===================================================== */

            $('#start_date,#end_date,#location,#movie_genre,#template_slot ,#hall_type,#movie').on('change', function () {
                const startDate = $('#start_date').val();
                const endDate   = $('#end_date').val();
                const locations = $('#location').val();
                const genres    = $('#movie_genre').val();
                const movie    = $('#movie').val();
                const compaign_category = $('#compaign_category').val()
                const hall_type_id = $('#hall_type').val()
                if (!startDate || !endDate || !locations || locations.length === 0 || !genres || genres.length === 0|| movie.length === 0) {
                    $('#slots-container').html(
                        '<div class="text-center text-muted">Please select filters and load slots</div>');

                }
                else
                {
                    loadAvailableSlots();
                }



            });

            function getCleanSelectValues(selector) {
                let values = $(selector).val() || [];
                return values.filter(v => v !== '__all__');
            }


            function loadAvailableSlots() {

                const startDate = $('#start_date').val();
                const endDate   = $('#end_date').val();

                if (!startDate || !endDate) {
                    Swal.fire('Missing info', 'Please select start and end date', 'warning');
                    return;
                }

                $('#slots-container').html('<div class="text-center">Loading...</div>');

                const payload = {
                    start_date: startDate,
                    end_date: endDate,
                    template_slot_id: $('#template_slot').val(),
                    cinema_chain_id: getCleanSelectValues('#cinema_chain'),
                    location_id: getCleanSelectValues('#location'),
                    movie_id: getCleanSelectValues('#movie'),
                    movie_genre_id: getCleanSelectValues('#movie_genre'),
                    hall_type_id: getCleanSelectValues('#hall_type'),
                    compaign_id: COMPAIGN_ID // 🟢 MODE EDIT
                };
                console.log('[loadAvailableSlots] payload →', payload);

                $.get("{{ url('advertiser/slots/getAvailableSlotsEdit') }}", payload)
                    .done(function (res) {
                        console.log('[loadAvailableSlots] response →', res);

                        $('#slots-container').empty();

                        if (!res.slots || !res.slots.length) {
                            $('#slots-container').html(
                                '<div class="text-muted text-center">No slots available</div>'
                            );
                            return;
                        }
                        const slotCount = res.slots.length;

                        res.slots.forEach(slot => {

                            let remainingDuration = slot.remaining_duration;
                            let assignedCount = 0;
                            let positionsHtml = '';

                            slot.positions.forEach(pos => {

                                /* 🔒 RÉSERVÉ (autre campagne) */
                                if (pos.type === 'reserved') {
                                    const encodedInfo = encodeURIComponent(JSON.stringify(pos.reserved_info || {}));
                                    positionsHtml += `
                                        <div class="slot-position reserved reserved-clickable"
                                            data-position="${pos.position}"
                                            data-info="${encodedInfo}"
                                            title="Click for details"
                                            style="cursor:pointer;">
                                            <span class="badge bg-secondary">Reserved (${pos.duration}s)</span>
                                            <span class="ms-1 text-muted" style="font-size:11px;">ℹ️</span>
                                        </div>
                                    `;
                                }

                                else if (pos.type === 'smart') {

                                    positionsHtml += `
                                        <div class="slot-position reserved"
                                            data-position="${pos.position}">
                                            <span class="badge bg-secondary">Smart Position</span>
                                        </div>
                                    `;
                                }

                                /* 🟢 CAMPAGNE COURANTE */
                                else if (pos.type === 'current') {

                                    assignedCount++;
                                    remainingDuration -= pos.duration;

                                    positionsHtml += `
                                        <div class="slot-position droppable-position"
                                            data-position="${pos.position}"
                                            data-slot="${slot.slot_id}">

                                            <div class="assigned draggable"
                                                data-dcp="${pos.dcp_id}"
                                                data-duration="${pos.duration}"
                                                data-position="${pos.position}">
                                                <span>${pos.dcp_name} (${pos.duration}s)</span>
                                                <span class="remove">×</span>
                                            </div>

                                        </div>
                                    `;
                                }

                                /* ⚪ LIBRE */
                                else {
                                    positionsHtml += `
                                        <div class="slot-position droppable-position"
                                            data-position="${pos.position}"
                                            data-slot="${slot.slot_id}">
                                            <span>Drop DCP here</span>
                                        </div>
                                    `;
                                }
                            });




                            $('#slots-container').append(`
                                <div class="col-md-12">
                                    <div class="slot-box"
                                        data-slot="${slot.slot_id}"
                                        data-max="${slot.max_duration}"
                                        data-remaining="${slot.remaining_duration}"
                                        data-max_ad_slot="${slot.max_ad_slot}"
                                        data-assigned="${assignedCount}">

                                        <strong>${slot.name}</strong><br>

                                        <small>
                                            Remaining:
                                            <span class="remaining">${slot.remaining_duration}</span>s /
                                            Max:
                                            <span class="max">${slot.max_duration}</span>s
                                        </small>


                                        <div class="positions mt-2">
                                            ${positionsHtml}
                                        </div>
                                    </div>
                                </div>
                            `);
                        });

                        initPositionDroppableEdit();
                    })
                    .fail(function(xhr) {
                        console.error('[loadAvailableSlots] FAIL →', xhr.status, xhr.responseJSON || xhr.responseText);
                        Swal.fire('Error', 'Failed to load available slots (status: ' + xhr.status + ')', 'error');
                    });
            }
            loadAvailableSlots()

            $('#btn-load-slots').on('click', function() {
                loadAvailableSlots();
            });

            /* =====================================================
                *  DROPPABLE
                * ===================================================== */

            /* =====================================================
                *  RESTORE ASSIGNED DCP (EDIT)
                * ===================================================== */
            function restoreAssignedDcps() {

                EXISTING_SLOTS.forEach(function (slot) {

                    const $slotBox = $(`.slot-box[data-id="${slot.id}"]`);
                    if (!$slotBox.length) return;

                    slot.dcps.forEach(function (dcp) {

                        let remaining = parseInt($slotBox.data('remaining')) - dcp.duration;
                        $slotBox.data('remaining', remaining);
                        $slotBox.find('.remaining').text(remaining);

                        $slotBox.find('.assigned-list').append(`
                            <div class="assigned" data-dcp="${dcp.id}" data-duration="${dcp.duration}">
                                <span>${dcp.name} (${dcp.duration}s)</span>
                                <span class="remove">×</span>
                            </div>
                        `);
                    });
                });
            }



            $('#btn-save-campaign').on('click', function(){
                initSelect2WithSelectAll('#target_type');
                initSelect2WithSelectAll('#interest', '#saveCampaignModal');

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

            /* =====================================================
                *  SAVE / UPDATE CAMPAIGN
            * ===================================================== */
            $('#confirm-save-campaign').on('click', function () {

                let slotsData = [];

                $('.slot-box').each(function(){
                    let $slot = $(this);
                    let slotId = $slot.data('slot');

                    let dcps = [];

                    // 🔹 On récupère les DCP par position, on ignore les positions vides
                    $slot.find('.slot-position').each(function(){
                        let $pos = $(this);
                        let $assigned = $pos.find('.assigned');

                        if($assigned.length){ // ⚡ ignorer les positions vides
                            let $dcp = $assigned.first();
                            dcps.push({
                                dcp_id: $dcp.data('dcp'),
                                duration: $dcp.data('duration'),
                                position: $dcp.data('position'),

                            });
                        }
                    });

                    if(dcps.length > 0){ // ⚡ n'envoyer que les slots qui ont au moins 1 DCP
                        slotsData.push({
                            slot_id: slotId,
                            dcps: dcps
                        });
                    }
                });

                let payload = {
                    _token: "{{ csrf_token() }}",
                    _method: 'PUT', // 🔹 IMPORTANT pour Laravel

                    compaign_name: $('#compaign_name').val(),
                    template_slot_id: $('#template_slot').val(), // 🔹 OBLIGATOIRE

                    start_date: $('#start_date').val(),
                    end_date: $('#end_date').val(),

                    cinema_chain_id: $('#cinema_chain').val(),

                    location_id: $('#location').val() ?? [],
                    budget: $('#budget').val() ?? 0,
                    langue: $('#langue').val() ,
                    gender: $('#gender').val() ,
                    target_type: $('#target_type').val() ,
                    interest: $('#interest').val() ,

                    movie_genre_id: $('#movie_genre').val() ?? [],
                    hall_type_id: $('#hall_type').val() ?? [],
                    movie_id: $('#movie').val() ?? [],

                    slots: slotsData
                };



                $('#saveCampaignModal').modal('hide');
                $('#page-loader').css('display', 'flex');

                $.ajax({
                    url: "{{ url('advertiser/compaigns/'.$compaign->id).'/update' }}",
                    type: 'POST', // POST + _method=PUT
                    data: payload
                })
                .done(function(res) {
                    $('#page-loader').hide();

                    var nocHtml = '';
                    if (res.noc_results && res.noc_results.length > 0) {
                        nocHtml += '<div class="mt-2 text-start" style="font-size:0.85em;">';
                        nocHtml += '<strong>NOC Push Results:</strong><ul class="mb-0 mt-1 ps-3">';
                        res.noc_results.forEach(function(r) {
                            if (r.sent) {
                                nocHtml += '<li class="text-success"><i class="mdi mdi-check-circle"></i> '
                                    + r.cinema_chain + ': sent successfully.</li>';
                            } else {
                                nocHtml += '<li class="text-danger"><i class="mdi mdi-close-circle"></i> '
                                    + r.cinema_chain + ': ' + (r.reason || 'failed') + '</li>';
                            }
                        });
                        nocHtml += '</ul></div>';
                    }

                    Swal.fire({
                        title: 'Success',
                        html: 'Campaign updated successfully.' + nocHtml,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                })
                .fail(function(xhr) {
                    $('#page-loader').hide();
                    console.error(xhr.responseText);
                    var msg = (xhr.responseJSON && xhr.responseJSON.message)
                        ? xhr.responseJSON.message
                        : 'Error while saving campaign';
                    Swal.fire('Error', msg, 'error');
                });
            });





           $(document).ready(function() {
                function adjustBlockHeight() {
                    // 📏 hauteur de la navbar
                    var navbarHeight = $('.navbar').outerHeight() || 0;
                    var filterHeight = $('.filter').outerHeight() || 0;
                    // 📏 hauteur de l'écran
                    var windowHeight = $(window).height();
                    console.log(navbarHeight)
                    console.log(filterHeight)
                    console.log(windowHeight)

                    // 🎯 calcul max-height pour le bloc
                    var maxHeight = windowHeight - navbarHeight -70 ;
                    var slotsMaxHeight = windowHeight - navbarHeight - filterHeight -80;

                    console.log(maxHeight)
                    console.log(slotsMaxHeight)
                    // ✅ appliquer au bloc ciblé
                    $('.screen-max-height').css('max-height', maxHeight + 'px');
                    $('.available-slots-max-height').css('max-height', slotsMaxHeight + 'px');
                }


                // 📌 appeler au chargement
                adjustBlockHeight();

                // 🔄 recalculer à chaque resize
                $(window).resize(function() {
                    adjustBlockHeight();
                });
            });

            /* =====================================================
             *  RESERVED INFO POPUP
             * ===================================================== */
            $(document).on('click', '.reserved-clickable', function () {
                const rawInfo = $(this).attr('data-info');
                if (!rawInfo) return;
                let info;
                try { info = JSON.parse(decodeURIComponent(rawInfo)); } catch(e) { return; }

                let html = '';

                if (info.periods && info.periods.length) {
                    html += '<h6 class="text-danger fw-bold">🔒 Occupied during your selection:</h6><ul class="mb-2">';
                    info.periods.forEach(p => {
                        html += `<li>From <strong>${p.from}</strong> to <strong>${p.to}</strong></li>`;
                    });
                    html += '</ul>';
                }

                if (info.free_periods && info.free_periods.length) {
                    html += '<h6 class="text-success fw-bold">✅ Available periods:</h6><ul class="mb-2">';
                    info.free_periods.forEach(p => {
                        html += `<li>From <strong>${p.from}</strong> to <strong>${p.to}</strong></li>`;
                    });
                    html += '</ul>';
                } else {
                    html += '<p class="text-danger mb-2">❌ No available dates within your selected period.</p>';
                }

                if (info.locations && info.locations.length) {
                    html += `<h6 class="fw-bold">📍 Conflicting location(s):</h6><p class="text-muted mb-2">${info.locations.join(', ')}</p>`;
                }
                if (info.cinema_chains && info.cinema_chains.length) {
                    html += `<h6 class="fw-bold">🎬 Conflicting cinema chain(s):</h6><p class="text-muted mb-2">${info.cinema_chains.join(', ')}</p>`;
                }
                if (info.hall_types && info.hall_types.length) {
                    html += `<h6 class="fw-bold">🏛️ Conflicting hall type(s):</h6><p class="text-muted mb-2">${info.hall_types.join(', ')}</p>`;
                }

                $('#reservedInfoBody').html(html);
                $('#reservedInfoModal').modal('show');
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
            background: white;
            border-radius: 10px !important;
            padding: 10px;
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


        .positions-container {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .reserved-bar {
            height: 34px;
            background: #e0e0e0;
            border: 1px dashed #bdbdbd;
            border-radius: 4px;
            cursor: not-allowed;
        }

        .free-position {
            height: 34px;
            border: 1px dashed #4caf50;
            border-radius: 4px;
        }

        .assigned {
            background: #d1e7dd;
            border: 1px solid #198754;
            padding: 6px;
            border-radius: 4px;
            cursor: move;
        }
        .slot-position {
            border: 1px dashed #ccc;
            padding: 8px;
            margin-bottom: 6px;
            min-height: 40px;
            text-align: center;
            background: #fafafa;
        }

        .slot-position.reserved {
            background: #e0e0e0;
            border: 1px solid #aaa;
            cursor: not-allowed;
        }

        .drop-hover {
            background: #e7f3ff;
            border-color: #0d6efd;
        }

        .assigned {
            background: #d1e7dd;
            padding: 5px;
            border-radius: 4px;
        }

        .droppable-hover {
            box-shadow: 0 0 15px rgb(54 199 108);
            border-radius: 5px;
            transition: box-shadow 0.2s ease;
        }

        /* ===================== DARK MODE ===================== */
        [data-bs-theme="dark"] .slot-box {
            background: #1e2a35;
            border-color: #3d5060;
        }

        [data-bs-theme="dark"] .slot-box.active {
            background: #162338;
            border-color: #0d6efd;
        }

        [data-bs-theme="dark"] .slot-position {
            background: #16202a;
            border-color: #3a4a56;
            color: #cdd9e5;
        }

        [data-bs-theme="dark"] .slot-position.reserved {
            background: #2c2c2c;
            border-color: #555;
        }

        [data-bs-theme="dark"] .drop-hover {
            background: #162338;
            border-color: #0d6efd;
        }

        [data-bs-theme="dark"] .assigned {
            background: #163328;
            border-color: #198754;
            color: #a3cfbb;
        }

        [data-bs-theme="dark"] .list-group-item {
            background: #1e2a35;
            border-color: #3d5060;
            color: #cdd9e5;
        }

        [data-bs-theme="dark"] .droppable-hover {
            box-shadow: 0 0 15px rgb(54 199 108);
        }
    </style>
@endsection



