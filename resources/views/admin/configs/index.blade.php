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
                            </div>
                            <small class="text-muted d-block">
                                Used when a location, a slot or a hall type has no price of its own.
                                Invoice = confirmed plays &times; (location price + slot price + hall types price).
                            </small>
                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-primary hstack gap-6" id="save_prices">
                                    <i class="ti ti-send fs-4"></i> Save Default Prices
                                </button>
                            </div>
                        </form>
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
