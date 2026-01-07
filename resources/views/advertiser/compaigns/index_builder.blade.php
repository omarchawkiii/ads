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
                    <div class="card-body padding-15">
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
                                <select id="cinema_chain" name="cinema_chain[]" multiple class="form-select  select2" data-placeholder="Select Cinema Chain ">
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
                    <div class="card-body padding-15">
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
                                    name="target_type[]" multiple  data-placeholder="Select Audience Targeting  ">
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
                                <select class="form-select required select2"  data-placeholder="Select Interests  "  id="interest"
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
                initSelect2WithSelectAll('#cinema_chain');

                //initSelect2WithSelectAll('#interest');
                initSelect2WithSelectAll('#dcp_creative');
                //initSelect2WithSelectAll('#target_type');
            });
            function getCleanSelectValues(selector) {
                let values = $(selector).val() || [];
                return values.filter(v => v !== '__all__');
            }
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

                $.get("{{ url('') }}/advertiser/cinema-chain/get_location_from_cienma_chain",
                    {
                        cinema_chain_ids: $('#cinema_chain').val(),
                    })
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
                $('#start_date,#end_date,#location,#movie_genre,#template_slot ,#hall_type,#movie').on('change', function () {
                    const startDate = $('#start_date').val();
                    const endDate   = $('#end_date').val();
                    const locations = $('#location').val();
                    const genres    = $('#movie_genre').val();
                    const movie    = $('#movie').val();
                    const compaign_category = $('#compaign_category').val()
                    const hall_type_id = $('#hall_type').val()
                    if (!startDate || !endDate || !locations || locations.length === 0 || !genres || genres.length === 0 || movie.length === 0) {
                        $('#slots-container').html(
                            '<div class="text-center text-muted">Please select filters and load slots</div>');

                    }
                    else
                    {
                        loadAvailableSlots();
                    }



                });

            });

            function loadAvailableSlots() {

                const startDate = $('#start_date').val();
                const endDate   = $('#end_date').val();
                const locations = getCleanSelectValues('#location');

                if (!startDate || !endDate || !locations || locations.length === 0) {
                    Swal.fire('Missing info', 'Select start, end date and location', 'warning');
                    return;
                }

                $('#slots-container').html('<div class="text-center">Loading...</div>');

                $.get("{{ url('advertiser/slots/available') }}", {
                    start_date: startDate,
                    end_date: endDate,
                    template_slot_id: $('#template_slot').val(),
                    cinema_chain_id: $('#cinema_chain').val(),
                    location_id: locations,
                    movie_id: getCleanSelectValues('#movie'),
                    movie_genre_id: getCleanSelectValues('#movie_genre'),
                    hall_type_id: $('#hall_type').val(),
                    compaign_category_id: $('#compaign_category').val(),
                })
                .done(function (res) {

                    $('#slots-container').empty();

                    if (!res.slots || !res.slots.length) {
                        $('#slots-container').html(
                            '<div class="text-muted text-center">No slots available</div>'
                        );
                        return;
                    }
                    const slotCount = res.slots.length;

                    let colClass = 'col-md-4';

                    if (slotCount === 1) {
                        colClass = 'col-md-12';
                    } else if (slotCount === 2) {
                        colClass = 'col-md-6';
                    } else if (slotCount === 3) {
                        colClass = 'col-md-4';
                    } else {
                        colClass = 'col-md-3';
                    }
                    res.slots.forEach(slot => {

                        let positionsHtml = '';
                        let remainingDuration = slot.remaining_duration;

                        // ✅ ON UTILISE DIRECTEMENT slot.positions
                        slot.positions.forEach(pos => {

                            // 🔒 POSITION RÉSERVÉE
                            if (pos.type === 'reserved') {
                                positionsHtml += `
                                    <div class="slot-position reserved"
                                        data-position="${pos.position}">
                                        <span class="badge bg-secondary">
                                            Reserved (${pos.duration}s)
                                        </span>
                                    </div>
                                `;
                            }

                            // 🟢 POSITION LIBRE
                            if (pos.type === 'free') {
                                positionsHtml += `
                                    <div class="slot-position droppable-position"
                                        data-slot="${slot.slot_id}"
                                        data-position="${pos.position}">
                                        <span>Drop DCP here</span>
                                    </div>
                                `;
                            }
                        });

                        $('#slots-container').append(`
                            <div class="${colClass}">
                                <div class="slot-box"
                                    data-slot="${slot.slot_id}"
                                    data-max="${slot.max_duration}"
                                    data-remaining="${remainingDuration}">

                                    <strong>${slot.name}</strong><br>
                                    <small>
                                        Remaining:
                                        <span class="remaining">${remainingDuration}</span>s /
                                        Max: ${slot.max_duration}s
                                    </small>

                                    <div class="positions mt-2">
                                        ${positionsHtml}
                                    </div>
                                </div>
                            </div>
                        `);
                    });

                    initPositionDroppable(); // drag & drop par position
                })
                .fail(() => Swal.fire('Error', 'Failed to load slots', 'error'));
            }



            function initPositionDroppable() {
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
                    hoverClass: 'active',

                    drop: function (e, ui) {

                        const $position   = $(this);
                        const $slotBox    = $position.closest('.slot-box');

                        const slotId      = $slotBox.data('slot');
                        const position    = parseInt($position.data('position'), 10);

                        const dcpId       = ui.draggable.data('id');
                        const dcpName     = ui.draggable.find('p:first').text();
                        const dcpDuration = parseInt(ui.draggable.data('duration'), 10);

                        let remaining     = parseInt($slotBox.data('remaining'), 10);
                        let maxDuration   = parseInt($slotBox.data('max'), 10);

                        let maxAdSlot     = parseInt($slotBox.data('max_ad_slot'), 10);
                        let assigned      = parseInt($slotBox.data('assigned') || 0, 10);

                        /* ❌ 0) Position déjà occupée */
                        if ($position.find('.assigned').length) {
                            showError("This position is already occupied.");
                            return;
                        }

                        /* ❌ 1) DCP déjà utilisé dans ce slot */
                        if ($slotBox.find(`.assigned[data-dcp="${dcpId}"]`).length) {
                            showError("This creative is already assigned in this slot.");
                            return;
                        }

                        /* ❌ 3) Durée DCP > durée max slot */
                        if (dcpDuration > maxDuration) {
                            showError("This creative exceeds the maximum duration of the slot.");
                            return;
                        }

                        /* ❌ 4) Pas assez de durée restante */
                        if (dcpDuration > remaining) {
                            showError("Not enough remaining time in this slot.");
                            return;
                        }

                        /* ✅ 5) Mise à jour compteurs */
                        const newRemaining = remaining - dcpDuration;
                        assigned++;

                        $slotBox
                            .data('remaining', newRemaining)
                            .data('assigned', assigned);

                        $slotBox.find('.remaining').text(newRemaining);
                        $slotBox.find('.remaining-pos').text(maxAdSlot - assigned);

                        /* ✅ 6) Injection DCP dans la POSITION */
                        $position.html(`
                            <div class="assigned"
                                data-dcp="${dcpId}"
                                data-duration="${dcpDuration}"
                                data-position="${position}">
                                <span>${dcpName} (${dcpDuration}s)</span>
                                <span class="remove">×</span>
                            </div>
                        `);

                        /* ✅ 7) Suppression (libère la position) */
                        $position.find('.remove').on('click', function () {

                            $slotBox.data('remaining', newRemaining + dcpDuration);
                            $slotBox.data('assigned', assigned - 1);

                            $slotBox.find('.remaining')
                                .text(newRemaining + dcpDuration);

                            $slotBox.find('.remaining-pos')
                                .text(maxAdSlot - (assigned - 1));

                            $position.html('<span class="">Drop DCP here</span>');
                        });
                    }
                });
            }

            function initDroppable() {

                $('.droppable').droppable({
                    accept: '.dcp-item',
                    hoverClass: 'active',

                    drop: function (e, ui) {

                        let dcpId       = ui.draggable.data('id');
                        let dcpName     = ui.draggable.find('p:first').text();
                        let dcpDuration = parseInt(ui.draggable.data('duration'), 10);

                        let $slot = $(this);

                        let remaining    = parseInt($slot.data('remaining'), 10);
                        let maxDuration  = parseInt($slot.data('max'), 10);

                        let maxAdSlot    = parseInt($slot.data('max_ad_slot'), 10);
                        let assigned     = parseInt($slot.data('assigned') || 0, 10);

                        // ❌ 0) Déjà assigné dans ce slot
                        if ($slot.find(`.assigned[data-dcp="${dcpId}"]`).length) {
                            showError("This creative has already been assigned to this slot.");
                            return;
                        }

                        // ❌ 1) Max positions atteintes
                        if (assigned >= maxAdSlot) {
                            showError("Maximum number of creatives reached for this slot.");
                            return;
                        }

                        // ❌ 2) Durée du DCP > durée max du slot
                        if (dcpDuration > maxDuration) {
                            showError(
                                "This creative duration exceeds the maximum allowed duration for this slot."
                            );
                            return;
                        }

                        // ❌ 3) Pas assez de temps restant
                        if (dcpDuration > remaining) {
                            showError(
                                "Not enough remaining time available in this slot."
                            );
                            return;
                        }

                        // ✅ 4) Mise à jour des compteurs
                        let newRemaining = remaining - dcpDuration;
                        assigned++;

                        $slot
                            .data('remaining', newRemaining)
                            .data('assigned', assigned);

                        $slot.find('.remaining').text(newRemaining);
                        $slot.find('.remaining-pos').text(maxAdSlot - assigned);

                        // ✅ 5) Ajout visuel du DCP
                        let $item = $(`
                            <div class="assigned"
                                data-dcp="${dcpId}"
                                data-duration="${dcpDuration}">
                                <span>${dcpName} (${dcpDuration}s)</span>
                                <span class="remove">×</span>
                            </div>
                        `);

                        $slot.find('.assigned-list').append($item);

                        // ✅ 6) Optionnel : sync backend (si besoin immédiat)
                        /*
                        $.post("{{ url('advertiser/slots/assign-dcp') }}", {
                            _token: "{{ csrf_token() }}",
                            slot_id: $slot.data('id'),
                            dcp_id: dcpId
                        });
                        */
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
            $(document).on('click', '.assigned .remove', function () {

                let $item = $(this).closest('.assigned');
                let $slot = $item.closest('.slot-box');

                let duration = parseInt($item.data('duration'), 10);

                let remaining = parseInt($slot.data('remaining'), 10);
                let assigned  = parseInt($slot.data('assigned'), 10);
                let maxAdSlot = parseInt($slot.data('max_ad_slot'), 10);

                remaining += duration;
                assigned--;

                $slot
                    .data('remaining', remaining)
                    .data('assigned', assigned);

                $slot.find('.remaining').text(remaining);
                $slot.find('.remaining-pos').text(maxAdSlot - assigned);

                $item.remove();
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
                    showError("Please enter a campaign name.");
                    return;
                }

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

                    compaign_name: campaignName,

                    // filters
                    start_date: $('#start_date').val(),
                    end_date: $('#end_date').val(),
                    cinema_chain_id: $('#cinema_chain').val(),
                    budget: $('#budget').val() ?? 0,
                    langue: $('#langue').val(),
                    gender: $('#gender').val(),
                    location_id: $('#location').val(),
                    movie_genre_id: $('#movie_genre').val(),
                    compaign_category_id: $('#compaign_category').val(),
                    template_slot_id: $('#template_slot').val(),
                    hall_type_id: $('#hall_type').val(),
                    movie_id: $('#movie').val(),
                    target_type: $('#target_type').val(),
                    interest: $('#interest').val(),

                    slots: slotsData
                };

                $.ajax({
                    url: "{{ url('advertiser/compaigns') }}",
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
                })
                .fail(function(){
                    showError("Error while saving campaign.");
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



    </style>
@endsection
