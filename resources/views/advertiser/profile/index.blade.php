@extends('advertiser.layouts.app')
@section('title') My Profile @endsection

@section('content')
<div class="row g-4">

  {{-- ── Left: Avatar + Info card ── --}}
  <div class="col-lg-4">
    <div class="card text-center">
      <div class="card-body py-5">
        <img src="{{ asset('assets/images/profile/user-1.jpg') }}"
             class="rounded-circle mb-3 border border-3 border-primary"
             width="100" height="100" alt="avatar">
        <h5 class="fw-bold mb-1">{{ $user->name }} {{ $user->last_name }}</h5>
        <span class="badge bg-primary-subtle text-primary mb-2">Advertiser</span>
        <p class="text-muted mb-0 fs-2">{{ $user->email }}</p>
        @if($user->username)
          <p class="text-muted fs-2">Username : {{ $user->username }}</p>
        @endif
      </div>
      <ul class="list-group list-group-flush text-start">
        <li class="list-group-item d-flex justify-content-between px-4 py-3">
          <span class="text-muted fs-2">First Name</span>
          <span class="fw-semibold fs-2" id="display_name">{{ $user->name }}</span>
        </li>
        <li class="list-group-item d-flex justify-content-between px-4 py-3">
          <span class="text-muted fs-2">Last Name</span>
          <span class="fw-semibold fs-2" id="display_last_name">{{ $user->last_name ?? '—' }}</span>
        </li>
        <li class="list-group-item d-flex justify-content-between px-4 py-3">
          <span class="text-muted fs-2">Username</span>
          <span class="fw-semibold fs-2" id="display_username">{{ $user->username ?? '—' }}</span>
        </li>
        <li class="list-group-item d-flex justify-content-between px-4 py-3">
          <span class="text-muted fs-2">Email</span>
          <span class="fw-semibold fs-2" id="display_email">{{ $user->email }}</span>
        </li>
        <li class="list-group-item d-flex justify-content-between px-4 py-3">
          <span class="text-muted fs-2">Role</span>
          <span class="badge bg-primary-subtle text-primary">Advertiser</span>
        </li>
      </ul>
    </div>
  </div>

  {{-- ── Right: Edit form ── --}}
  <div class="col-lg-8">
    <div class="card">
      <div class="card-body p-4">
        <h5 class="card-title fw-semibold mb-4">Edit Profile Information</h5>

        <form id="profile_form">
          @csrf
          <div class="row g-3">

            <div class="col-md-6">
              <label class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="name" name="name"
                     value="{{ $user->name }}" required>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Last Name</label>
              <input type="text" class="form-control" id="last_name" name="last_name"
                     value="{{ $user->last_name }}">
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Username</label>
              <input type="text" class="form-control" id="username" name="username"
                     value="{{ $user->username }}">
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
              <input type="email" class="form-control" id="email" name="email"
                     value="{{ $user->email }}" required>
            </div>

          </div>

          @if($user->advertiser_type === 'direct')
          <hr class="my-4">
          <h6 class="fw-semibold mb-3 text-primary">
            <i class="mdi mdi-domain me-1"></i> Company / Client Information
          </h6>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">Brand Name</label>
              <input type="text" class="form-control" id="customer_brand_name" name="customer_brand_name"
                     value="{{ $customer->brand_name ?? '' }}" placeholder="Brand name">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Company Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="customer_name" name="customer_name"
                     value="{{ $customer->name ?? '' }}" placeholder="Company name" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Address 1</label>
              <input type="text" class="form-control" id="customer_address" name="customer_address"
                     value="{{ $customer->address ?? '' }}" placeholder="Address line 1">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Address 2</label>
              <input type="text" class="form-control" id="customer_address_2" name="customer_address_2"
                     value="{{ $customer->address_2 ?? '' }}" placeholder="Address line 2">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">City</label>
              <input type="text" class="form-control" id="customer_city" name="customer_city"
                     value="{{ $customer->city ?? '' }}" placeholder="City">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">State</label>
              <input type="text" class="form-control" id="customer_state" name="customer_state"
                     value="{{ $customer->state ?? '' }}" placeholder="State">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Postcode</label>
              <input type="text" class="form-control" id="customer_postcode" name="customer_postcode"
                     value="{{ $customer->postcode ?? '' }}" placeholder="Postcode">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Country</label>
              <select class="form-select" id="customer_country" name="customer_country">
                @include('advertiser.partiels.country_options')
              </select>
              @if($customer?->country)
              <script>document.getElementById('customer_country').value = "{{ $customer->country }}";</script>
              @endif
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">E-Mail</label>
              <input type="email" class="form-control" id="customer_email" name="customer_email"
                     value="{{ $customer->email ?? '' }}" placeholder="Contact email">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Phone</label>
              <input type="text" class="form-control" id="customer_phone" name="customer_phone"
                     value="{{ $customer->phone ?? '' }}" placeholder="Phone number">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Name of PIC</label>
              <input type="text" class="form-control" id="customer_pic_name" name="customer_pic_name"
                     value="{{ $customer->pic_name ?? '' }}" placeholder="Person in charge">
            </div>
          </div>
          @endif

          <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4" id="save_profile_btn">
              <span id="save_profile_spinner" class="spinner-border spinner-border-sm me-1 d-none"></span>
              Save Changes
            </button>
            <button type="button" class="btn btn-outline-secondary px-4" id="reset_user_password">
              Change Password
            </button>
          </div>
        </form>

      </div>
    </div>
  </div>

</div>
@endsection

@section('custom_script')
<script>
  $('#profile_form').on('submit', function (e) {
    e.preventDefault();

    $('#save_profile_btn').prop('disabled', true);
    $('#save_profile_spinner').removeClass('d-none');

    $.ajax({
      url: '{{ route("advertiser.profile.update") }}',
      method: 'PUT',
      data: $(this).serialize(),
      headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
    })
    .done(function (res) {
      // Update display panel
      $('#display_name').text($('#name').val());
      $('#display_last_name').text($('#last_name').val() || '—');
      $('#display_username').text($('#username').val() || '—');
      $('#display_email').text($('#email').val());

      Swal.fire({ icon: 'success', title: 'Saved', text: res.message, timer: 2000, showConfirmButton: false });
    })
    .fail(function (xhr) {
      let msg = 'Update failed.';
      if (xhr.status === 422 && xhr.responseJSON?.errors) {
        msg = Object.values(xhr.responseJSON.errors).flat().join('\n');
      }
      Swal.fire({ icon: 'error', title: 'Error', text: msg });
    })
    .always(function () {
      $('#save_profile_btn').prop('disabled', false);
      $('#save_profile_spinner').addClass('d-none');
    });
  });
</script>
@endsection
