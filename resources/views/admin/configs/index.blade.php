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
                  <h4 class="mb-4 mb-sm-0 card-title"> Setting</h4>
                  <nav aria-label="breadcrumb" class="ms-auto">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item" aria-current="page">

                      </li>
                    </ol>
                  </nav>
                </div>
              </div>
            </div>
          </div>


          <div class="card">
            <div class="card-body">

              <form>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="form-check form-switch">
                            <label class="form-check-label" for="use_noc">Use NOC</label>
                            <input class="form-check-input" type="checkbox" id="use_noc" @if($config->use_noc )  checked="true" @endif  >
                        </div>
                    </div>
                </div>
                <div id="noc_fields" class="row" @if(!$config->use_noc )  style="display: none;" aria-hidden="true" @endif>

                    <div class="col-md-12">
                        <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="link" placeholder="NOC IP Adress" value="{{ $config->link }}">
                        <label for="tb-fname">Ip Adress {{ $config->use_noc }}</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="user" placeholder="username"  value="{{ $config->user }}">
                        <label for="tb-email">Username</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="password" placeholder="Password"  value="{{ $config->password }}">
                        <label for="tb-pwd">Password</label>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="d-md-flex align-items-center">

                        <div class="ms-auto mt-3 mt-md-0">
                            <button type="submit" class="btn btn-primary hstack gap-6" id="save">
                            <i class="ti ti-send fs-4"></i>
                            Save
                            </button>
                        </div>
                        </div>
                    </div>
                </div>
              </form>
            </div>
          </div>
    </div>

    {{-- Create Modal --}}






@endsection


@section('custom_script')
    <script src="{{ asset('assets/js/helper.js')}}"></script>
    <script>

        $(function() {



            $(document).on("click", "#save", function(event) {

                event.preventDefault();
                var use_noc =  $('#use_noc').is(':checked');
                var link = $('#link').val();
                var user = $('#user').val();
                var password = $('#password').val();

                var url = '{{ url('') }}' + '/configs';

                $.ajax({
                        url: url,
                        type: 'POST',
                        method: 'POST',
                        data: {
                            use_noc:use_noc,
                            link: link,
                            user: user,
                            password: password,
                            "_token": "{{ csrf_token() }}",
                        },
                    })
                    .done(function(response) {


                        Swal.fire({
                            title: 'Done!',
                            text: 'config Saved successfully.',
                            icon: 'success',
                            confirmButtonText: 'Continue'
                        });

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
                        $('#create_config_modal').modal('hide');
                    });

            })
            const $checkbox = $('#use_noc');
            const $fields = $('#noc_fields');
            function applyNocState(show, instant = false) {
                if (show) {
                // accessibility
                $fields.attr('aria-hidden', 'false').find('input,button,select,textarea').prop('disabled', false);
                if (instant) $fields.show(); else $fields.slideDown(180);
                } else {
                $fields.attr('aria-hidden', 'true').find('input,button,select,textarea').prop('disabled', true);
                if (instant) $fields.hide(); else $fields.slideUp(180);
                }
            }

            $checkbox.on('change', function() {
                const isChecked = $(this).is(':checked');
                applyNocState(isChecked, false);
            });

        });
    </script>
@endsection
