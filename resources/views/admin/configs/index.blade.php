@extends('admin.layouts.app')
@section('title')
    Setting
@endsection
@section('content')
    <div class="container py-4">

        <div class="card card-body py-3">
            <div class="row align-items-center">
              <div class="col-12">
                <div class="d-sm-flex align-items-center justify-space-between">
                  <h4 class="mb-4 mb-sm-0 card-title">Setting</h4>
                </div>
              </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">

                {{-- Bootstrap 5 Tabs --}}
                <ul class="nav nav-tabs mb-4" id="configTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="noc-tab" data-bs-toggle="tab"
                                data-bs-target="#noc-pane" type="button" role="tab"
                                aria-controls="noc-pane" aria-selected="true">
                            <i class="ti ti-server me-1"></i> NOC Configuration
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tax-tab" data-bs-toggle="tab"
                                data-bs-target="#tax-pane" type="button" role="tab"
                                aria-controls="tax-pane" aria-selected="false">
                            <i class="ti ti-receipt-tax me-1"></i> Tax Configuration
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="price-tab" data-bs-toggle="tab"
                                data-bs-target="#price-pane" type="button" role="tab"
                                aria-controls="price-pane" aria-selected="false">
                            <i class="ti ti-coin me-1"></i> Default Prices
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="rules-tab" data-bs-toggle="tab"
                                data-bs-target="#rules-pane" type="button" role="tab"
                                aria-controls="rules-pane" aria-selected="false">
                            <i class="ti ti-adjustments me-1"></i> Pricing Rules
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="configTabsContent">

                    {{-- ── Tab 1 : NOC ── --}}
                    <div class="tab-pane fade show active" id="noc-pane" role="tabpanel" aria-labelledby="noc-tab">
                        <form id="noc_form">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="form-check form-switch">
                                        <label class="form-check-label" for="use_noc">Use NOC</label>
                                        <input class="form-check-input" type="checkbox" id="use_noc"
                                               @if($config->use_noc) checked @endif>
                                    </div>
                                </div>
                            </div>
                            <div id="noc_fields" class="row"
                                 @if(!$config->use_noc) style="display:none;" aria-hidden="true" @endif>
                                <div class="col-md-12">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="link"
                                               placeholder="NOC IP Address" value="{{ $config->link }}">
                                        <label for="link">IP Address</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="noc_user"
                                               placeholder="Username" value="{{ $config->user }}">
                                        <label for="noc_user">Username</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="password" class="form-control" id="password"
                                               placeholder="Password" value="{{ $config->password }}">
                                        <label for="password">Password</label>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-2">
                                <button type="submit" class="btn btn-primary hstack gap-6" id="save_noc">
                                    <i class="ti ti-send fs-4"></i> Save NOC Config
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- ── Tab 2 : Tax ── --}}
                    <div class="tab-pane fade" id="tax-pane" role="tabpanel" aria-labelledby="tax-tab">
                        <form id="tax_form">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Tax Rate (%)</label>
                                    <div class="input-group mb-3">
                                        <input type="number" class="form-control" id="tax_value"
                                               min="0" max="100" step="0.01"
                                               value="{{ $config->tax ?? 6 }}"
                                               placeholder="e.g. 6">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <small class="text-muted">
                                        This rate is applied automatically when a new invoice is generated.
                                    </small>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-primary hstack gap-6" id="save_tax">
                                    <i class="ti ti-send fs-4"></i> Save Tax Config
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- ── Tab 3 : Default prices ── --}}
                    <div class="tab-pane fade" id="price-pane" role="tabpanel" aria-labelledby="price-tab">
                        <form id="price_form">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Default price for a location (RM / play)</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">RM</span>
                                        <input type="number" class="form-control" id="default_location_price"
                                               min="0" step="0.01" required
                                               value="{{ $config->default_location_price ?? 0 }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Default price for a slot (RM / play)</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">RM</span>
                                        <input type="number" class="form-control" id="default_slot_price"
                                               min="0" step="0.01" required
                                               value="{{ $config->default_slot_price ?? 0 }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Default price for a hall type (RM / play)</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">RM</span>
                                        <input type="number" class="form-control" id="default_hall_type_price"
                                               min="0" step="0.01" required
                                               value="{{ $config->default_hall_type_price ?? 0 }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Default cinema chain revenue share (%)</label>
                                    <div class="input-group mb-3">
                                        <input type="number" class="form-control" id="default_revenue_share_percent"
                                               min="0" max="100" step="0.01" required
                                               value="{{ $config->default_revenue_share_percent ?? 60 }}">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <small class="text-muted">Part of the invoice subtotal (HT) shown to a cinema chain user, unless the chain has its own %.</small>
                                </div>
                            </div>
                            <small class="text-muted d-block">
                                Used when a location, a slot or a hall type has no price of its own.
                                Base price per play = location price + slot price + hall types price; multipliers (Pricing Rules tab) apply on top.
                            </small>
                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-primary hstack gap-6" id="save_prices">
                                    <i class="ti ti-send fs-4"></i> Save Default Prices
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- ── Tab 4 : Pricing rules (multipliers) ── --}}
                    <div class="tab-pane fade" id="rules-pane" role="tabpanel" aria-labelledby="rules-tab">
                        @php
                            $cfgVal = fn ($k) => $config->{$k} ?? \App\Models\Config::PRICING_DEFAULTS[$k];
                            $groups = [
                                'Position in the show' => [
                                    ['position_first_multiplier', 'First segment'],
                                    ['position_middle_multiplier', 'Middle segment'],
                                    ['position_premium_multiplier', 'Premium (before the film)'],
                                ],
                                'Targeting (highest applicable level)' => [
                                    ['target_region_multiplier', 'State / region (part of the network)'],
                                    ['target_movie_multiplier', 'Specific movie'],
                                    ['target_audience_multiplier', 'Smart audience (target types / interests)'],
                                ],
                                'Ad length' => [
                                    ['length_15_multiplier', 'Up to 15 seconds'],
                                    ['length_30_multiplier', 'Up to 30 seconds'],
                                    ['length_45_multiplier', 'Up to 45 seconds'],
                                    ['length_60_multiplier', 'Over 45 seconds (60 s)'],
                                ],
                            ];
                        @endphp
                        <form id="rules_form">
                            <p class="text-muted">
                                Price per play = (location + slot + hall types price) &times; position &times; targeting &times; length &times; season &times; volume,
                                with the combined multiplier capped. The multipliers are frozen on each invoice line when the invoice is generated.
                            </p>
                            @foreach($groups as $title => $fields)
                                <h6 class="fw-semibold mt-2">{{ $title }}</h6>
                                <div class="row">
                                    @foreach($fields as [$key, $label])
                                        <div class="col-md-4">
                                            <label class="form-label">{{ $label }}</label>
                                            <div class="input-group mb-3">
                                                <input type="number" class="form-control rule-input" id="{{ $key }}" name="{{ $key }}"
                                                       min="0.01" max="99" step="0.01" required value="{{ $cfgVal($key) }}">
                                                <span class="input-group-text">&times;</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach

                            <h6 class="fw-semibold mt-2">Volume, cap and minimum</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">Volume discount from (weeks)</label>
                                    <input type="number" class="form-control rule-input mb-3" id="volume_min_weeks" name="volume_min_weeks"
                                           min="1" max="520" step="1" required value="{{ $cfgVal('volume_min_weeks') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Volume multiplier</label>
                                    <div class="input-group mb-3">
                                        <input type="number" class="form-control rule-input" id="volume_multiplier" name="volume_multiplier"
                                               min="0.01" max="99" step="0.01" required value="{{ $cfgVal('volume_multiplier') }}">
                                        <span class="input-group-text">&times;</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Combined multiplier cap</label>
                                    <div class="input-group mb-3">
                                        <input type="number" class="form-control rule-input" id="max_multiplier" name="max_multiplier"
                                               min="0.01" max="99" step="0.01" required value="{{ $cfgVal('max_multiplier') }}">
                                        <span class="input-group-text">&times;</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Minimum per cinema per week (RM)</label>
                                    <div class="input-group mb-1">
                                        <span class="input-group-text">RM</span>
                                        <input type="number" class="form-control rule-input" id="min_outlet_week_price" name="min_outlet_week_price"
                                               min="0" step="0.01" required value="{{ $cfgVal('min_outlet_week_price') }}">
                                    </div>
                                    <small class="text-muted d-block mb-3">0 = no minimum. Applied only to cinemas that played the campaign.</small>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-2">
                                <button type="submit" class="btn btn-primary hstack gap-6" id="save_rules">
                                    <i class="ti ti-send fs-4"></i> Save Pricing Rules
                                </button>
                            </div>
                        </form>

                        <hr>
                        <h6 class="fw-semibold">Seasons (blockbuster / festive periods)</h6>
                        <small class="text-muted d-block mb-2">
                            Each campaign day inside a season takes the season multiplier (the highest one if seasons overlap);
                            the campaign's season multiplier is the average over its days.
                        </small>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle" id="seasons_table">
                                <thead><tr><th>Name</th><th>Start</th><th>End</th><th>Multiplier</th><th></th></tr></thead>
                                <tbody>
                                    @foreach($seasons as $s)
                                        <tr data-id="{{ $s->id }}">
                                            <td><input type="text" class="form-control form-control-sm s-name" value="{{ $s->name }}"></td>
                                            <td><input type="date" class="form-control form-control-sm s-start" value="{{ $s->start_date->format('Y-m-d') }}"></td>
                                            <td><input type="date" class="form-control form-control-sm s-end" value="{{ $s->end_date->format('Y-m-d') }}"></td>
                                            <td><input type="number" class="form-control form-control-sm s-mult" min="0.01" max="99" step="0.01" value="{{ $s->multiplier }}"></td>
                                            <td class="text-end text-nowrap">
                                                <button type="button" class="btn btn-sm btn-outline-primary season-save">Save</button>
                                                <button type="button" class="btn btn-sm btn-outline-danger season-delete">Delete</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr id="season_new">
                                        <td><input type="text" class="form-control form-control-sm s-name" placeholder="e.g. Chinese New Year"></td>
                                        <td><input type="date" class="form-control form-control-sm s-start"></td>
                                        <td><input type="date" class="form-control form-control-sm s-end"></td>
                                        <td><input type="number" class="form-control form-control-sm s-mult" min="0.01" max="99" step="0.01" value="1.20"></td>
                                        <td class="text-end"><button type="button" class="btn btn-sm btn-success" id="season_add">Add season</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>{{-- end tab-content --}}

            </div>
        </div>
    </div>
@endsection


@section('custom_script')
    <script src="{{ asset('assets/js/helper.js') }}?v={{ filemtime(public_path('assets/js/helper.js')) }}"></script>
    <script>
        $(function () {

            /* ── NOC toggle ── */
            const $checkbox = $('#use_noc');
            const $fields   = $('#noc_fields');

            function applyNocState(show, instant) {
                if (show) {
                    $fields.attr('aria-hidden', 'false').find('input,select,textarea').prop('disabled', false);
                    instant ? $fields.show() : $fields.slideDown(180);
                } else {
                    $fields.attr('aria-hidden', 'true').find('input,select,textarea').prop('disabled', true);
                    instant ? $fields.hide() : $fields.slideUp(180);
                }
            }

            $checkbox.on('change', function () {
                applyNocState($(this).is(':checked'), false);
            });

            /* ── Save NOC ── */
            $('#noc_form').on('submit', function (e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ url('') }}/configs',
                    type: 'POST',
                    data: {
                        use_noc:  $('#use_noc').is(':checked') ? 'true' : 'false',
                        link:     $('#link').val(),
                        user:     $('#noc_user').val(),
                        password: $('#password').val(),
                        _token:   '{{ csrf_token() }}',
                    },
                })
                .done(function () {
                    Swal.fire({ title: 'Saved!', text: 'NOC configuration saved.', icon: 'success', confirmButtonText: 'OK' });
                })
                .fail(function () {
                    Swal.fire({ title: 'Error', text: 'Operation failed.', icon: 'error' });
                });
            });

            /* ── Save default prices ── */
            $('#price_form').on('submit', function (e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ route('configs.prices') }}',
                    type: 'POST',
                    data: {
                        default_location_price: $('#default_location_price').val(),
                        default_slot_price:     $('#default_slot_price').val(),
                        default_hall_type_price: $('#default_hall_type_price').val(),
                        default_revenue_share_percent: $('#default_revenue_share_percent').val(),
                        _token: '{{ csrf_token() }}',
                    },
                })
                .done(function (res) {
                    Swal.fire({ title: 'Saved!', text: res.message, icon: 'success', timer: 2000, showConfirmButton: false });
                })
                .fail(function (xhr) {
                    let errs = xhr.responseJSON?.errors;
                    let msg = errs ? Object.values(errs)[0][0] : 'Operation failed.';
                    Swal.fire({ title: 'Error', text: msg, icon: 'error' });
                });
            });

            /* ── Save pricing multipliers ── */
            function ruleError(xhr) {
                let errs = xhr.responseJSON?.errors;
                Swal.fire({ title: 'Error', text: errs ? Object.values(errs)[0][0] : 'Operation failed.', icon: 'error' });
            }
            $('#rules_form').on('submit', function (e) {
                e.preventDefault();
                let data = { _token: '{{ csrf_token() }}' };
                $('#rules_form .rule-input').each(function () { data[this.name] = $(this).val(); });
                $.post('{{ route('configs.multipliers') }}', data)
                    .done(function (res) { Swal.fire({ title: 'Saved!', text: res.message, icon: 'success', timer: 2000, showConfirmButton: false }); })
                    .fail(ruleError);
            });

            function seasonData($row) {
                return {
                    name: $row.find('.s-name').val(), start_date: $row.find('.s-start').val(),
                    end_date: $row.find('.s-end').val(), multiplier: $row.find('.s-mult').val(),
                    _token: '{{ csrf_token() }}',
                };
            }
            $('#season_add').on('click', function () {
                $.post('{{ route('configs.seasons.store') }}', seasonData($('#season_new')))
                    .done(function () { location.hash = 'rules'; location.reload(); })
                    .fail(ruleError);
            });
            $(document).on('click', '.season-save', function () {
                let $row = $(this).closest('tr');
                $.ajax({ url: '{{ url('configs/seasons') }}/' + $row.data('id'), type: 'PUT', data: seasonData($row) })
                    .done(function (res) { Swal.fire({ title: 'Saved!', text: res.message, icon: 'success', timer: 1500, showConfirmButton: false }); })
                    .fail(ruleError);
            });
            $(document).on('click', '.season-delete', function () {
                let $row = $(this).closest('tr');
                Swal.fire({ title: 'Delete this season?', icon: 'warning', showCancelButton: true, confirmButtonText: 'Delete' }).then(function (r) {
                    if (!r.isConfirmed) return;
                    $.ajax({ url: '{{ url('configs/seasons') }}/' + $row.data('id'), type: 'DELETE', data: { _token: '{{ csrf_token() }}' } })
                        .done(function () { $row.remove(); })
                        .fail(ruleError);
                });
            });
            if (location.hash === '#rules') { new bootstrap.Tab(document.getElementById('rules-tab')).show(); }

            /* ── Save Tax ── */
            $('#tax_form').on('submit', function (e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ route('configs.tax') }}',
                    type: 'POST',
                    data: {
                        tax:    $('#tax_value').val(),
                        _token: '{{ csrf_token() }}',
                    },
                })
                .done(function (res) {
                    Swal.fire({ title: 'Saved!', text: res.message, icon: 'success', timer: 2000, showConfirmButton: false });
                })
                .fail(function (xhr) {
                    let msg = 'Operation failed.';
                    if (xhr.responseJSON?.errors?.tax) msg = xhr.responseJSON.errors.tax[0];
                    Swal.fire({ title: 'Error', text: msg, icon: 'error' });
                });
            });

        });
    </script>
@endsection
