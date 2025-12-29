@extends('advertiser.layouts.app')
@section('title')
    Edit Campaign
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
                            <input type="date" id="start_date" name="start_date" class="form-control" value="{{ $compaign->start_date }}">
                        </div>

                        <div class="col-md-3">
                            <label>End Date</label>
                            <input type="date" id="end_date" name="end_date" class="form-control" value="{{ $compaign->end_date }}">
                        </div>

                        <div class="col-md-3">
                            <label>Template Slot</label>
                            <select id="template_slot" name="template_slot" class="form-select">
                                <option value="">Select...</option>
                                @foreach ($slot_templates as $tpl)
                                    <option value="{{ $tpl->id }}" @if($compaign->templateSlot && $compaign->templateSlot->id == $tpl->id) selected @endif>{{ $tpl->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Ad Category</label>
                            <select id="compaign_category" name="compaign_category" class="form-select">
                                <option value="">Select...</option>
                                @foreach ($compaign_categories as $cat)
                                    <option value="{{ $cat->id }}" @if($compaign->compaign_category_id == $cat->id) selected @endif>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Cinema Chain</label>
                            <select id="cinema_chain" name="cinema_chain" class="form-select">
                                <option value="">Select...</option>
                                @foreach ($cinema_chains as $chain)
                                    <option value="{{ $chain->id }}" @if($compaign->slots->first() && $compaign->slots->first()->cinema_chain_id == $chain->id) selected @endif>{{ $chain->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-5">
                            <label>Location</label>
                            <select id="location" name="location[]" multiple class="form-select select2">
                                <option value="__all__">Select All</option>
                                @foreach ($locations as $loc)
                                    <option value="{{ $loc->id }}" @if($compaign->locations->pluck('id')->contains($loc->id)) selected @endif>{{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label>Genre</label>
                            <select id="movie_genre" name="movie_genre[]" multiple class="form-select select2">
                                <option value="__all__">Select All</option>
                                @foreach ($movie_genres as $g)
                                    <option value="{{ $g->id }}" @if($compaign->movieGenres->pluck('id')->contains($g->id)) selected @endif>{{ $g->name }}</option>
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
                    Assigned Slots
                </div>
                <div class="card-body">
                    <div id="slots-container" class="row g-3">

                        @forelse ($compaign->slots as $slot)
                            <div class="col-md-4">
                                <div class="slot-box droppable"
                                    data-id="{{ $slot->id }}"
                                    data-remaining="{{ $slot->max_duration - $slot->dcpCreatives->sum('duration') }}"
                                    data-max="{{ $slot->max_duration }}">
                                    <strong>{{ $slot->name }}</strong><br>
                                    <small>
                                        Remaining: <span class="remaining">{{ $slot->max_duration - $slot->dcpCreatives->sum('duration') }}</span>s /
                                        Max: <span class="max">{{ $slot->max_duration }}</span>s
                                    </small>
                                    <div class="assigned-list mt-2">
                                        @foreach ($slot->dcpCreatives as $dcp)
                                            <div class="assigned" data-dcp="{{ $dcp->id }}" data-duration="{{ $dcp->duration }}">
                                                <span>{{ $dcp->name }} ({{ $dcp->duration }}s)</span>
                                                <span class="remove">×</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted">Please select filters and load slots</div>
                        @endforelse

                    </div>
                    <div class="row mt-4">
                        <div class="col text-end">
                            <button id="btn-save-campaign" class="btn btn-success">
                                💾 Update Campaign
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@section('custom_script')
<script>
$(function() {

    // Init Select2 with "Select All" logic
    function initSelect2WithSelectAll(selector) {
        $(selector).select2({ width: '100%', closeOnSelect: false });
        $(selector).on('select2:select select2:unselect', function (e) {
            const ALL_VALUE = '__all__';
            const $select = $(this);
            const values = $select.val() || [];
            if (e.params?.data?.id === ALL_VALUE) {
                if (values.includes(ALL_VALUE)) {
                    const allValues = $select.find('option').map(function () { return this.value; }).get().filter(v => v !== ALL_VALUE);
                    $select.val(allValues).trigger('change.select2');
                } else {
                    $select.val(null).trigger('change.select2');
                }
            }
        });
    }

    initSelect2WithSelectAll('#location');
    initSelect2WithSelectAll('#movie_genre');

    // DCP draggable
    $(".dcp-item").draggable({ helper: "clone", revert: "invalid" });

    function initDroppable(){
        $('.droppable').droppable({
            accept: '.dcp-item',
            hoverClass: 'active',
            drop: function(e, ui){
                let dcpId = ui.draggable.data('id');
                let dcpName = ui.draggable.find('p').text();
                let dcpDuration = parseInt(ui.draggable.data('duration'));
                let $slot = $(this);
                let remaining = parseInt($slot.data('remaining'));
                let max = parseInt($slot.data('max'));
                if ($slot.find(`.assigned[data-dcp="${dcpId}"]`).length > 0) { alert("Already assigned"); return; }
                if(dcpDuration > max || dcpDuration > remaining){ alert("Not enough slot duration"); return; }
                let newRemaining = remaining - dcpDuration;
                $slot.data('remaining', newRemaining);
                $slot.find('.remaining').text(newRemaining);
                $slot.find('.assigned-list').append(`<div class="assigned" data-dcp="${dcpId}" data-duration="${dcpDuration}"><span>${dcpName} (${dcpDuration}s)</span><span class="remove">×</span></div>`);
            }
        });
    }
    initDroppable();

    // Remove assigned DCP
    $(document).on('click', '.assigned .remove', function(){
        let $item = $(this).closest('.assigned');
        let duration = parseInt($item.data('duration'));
        let $slot = $item.closest('.slot-box');
        let remaining = parseInt($slot.data('remaining')) + duration;
        $slot.data('remaining', remaining);
        $slot.find('.remaining').text(remaining);
        $item.remove();
    });

    // Save/Update campaign
    $('#btn-save-campaign').on('click', function(){
        let campaignName = prompt("Enter campaign name:", "{{ $compaign->name }}");
        if(!campaignName) return;
        let slotsData = [];
        $('.slot-box').each(function(){
            let $slot = $(this);
            let slotId = $slot.data('id');
            let dcps = [];
            $slot.find('.assigned').each(function(){
                dcps.push({ dcp_id: $(this).data('dcp'), duration: $(this).data('duration') });
            });
            if(dcps.length>0) slotsData.push({ slot_id: slotId, dcps: dcps });
        });
        if(!slotsData.length){ alert("No DCP assigned"); return; }
        $.post("{{ url('') }}/advertiser/compaigns/{{ $compaign->id }}", {
            _method: 'PUT',
            _token: "{{ csrf_token() }}",
            name: campaignName,
            start_date: $('#start_date').val(),
            end_date: $('#end_date').val(),
            template_slot_id: $('#template_slot').val(),
            compaign_category_id: $('#compaign_category').val(),
            slots: slotsData
        }, function(res){
            alert("Campaign updated successfully!");
            location.reload();
        });
    });

});
</script>
@endsection
