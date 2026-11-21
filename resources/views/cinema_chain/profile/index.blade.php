@extends('cinema_chain.layouts.app')
@section('title') My Profile @endsection

@section('content')
<div class="row g-4">
  <div class="col-lg-4">
    <div class="card text-center">
      <div class="card-body py-5">
        <img src="{{ asset('assets/images/profile/user-1.jpg') }}"
             class="rounded-circle mb-3 border border-3 border-primary"
             width="100" height="100" alt="avatar">
        <h5 class="fw-bold mb-1" id="display_fullname">{{ $user->name }} {{ $user->last_name }}</h5>
        <span class="badge bg-primary-subtle text-primary mb-2">Cinema Chain</span>
        <p class="text-muted mb-0 fs-2" id="display_email">{{ $user->email }}</p>
      </div>
      <ul class="list-group list-group-flush text-start">
        <li class="list-group-item px-4 py-3">
          <span class="text-muted fs-2 d-block mb-1">My cinema chains</span>
          @forelse($user->cinemaChains()->orderBy('name')->get() as $chain)
            <span class="badge bg-info-subtle text-info me-1">{{ $chain->name }}</span>
          @empty
            <span class="text-muted">None assigned</span>
          @endforelse
        </li>
      </ul>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="card">
      <div class="card-body p-4">
        <h5 class="card-title fw-semibold mb-4">Edit Profile Information</h5>
        <form id="profile_form">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">First Name</label>
              <input type="text" class="form-control" id="name" value="{{ $user->name }}" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Last Name</label>
              <input type="text" class="form-control" id="last_name" value="{{ $user->last_name ?? '' }}">
            </div>
            <div class="col-md-6">
              <label class="form-label">Username</label>
              <input type="text" class="form-control" id="username" value="{{ $user->username ?? '' }}">
            </div>
            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" id="email" value="{{ $user->email }}" required>
            </div>
            <div class="col-12 text-end">
              <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="card mt-4">
      <div class="card-body p-4">
        <h5 class="card-title fw-semibold mb-4">Change Password</h5>
        <form id="password_form" autocomplete="off">
          <div class="row g-3">
            <div class="col-md-12">
              <label class="form-label">Current password</label>
              <input type="password" class="form-control" id="current_password" autocomplete="current-password" required>
              <div class="text-danger small mt-1" id="current_password_error"></div>
            </div>
            <div class="col-md-6">
              <label class="form-label">New password <small class="text-muted">(min. 8 characters)</small></label>
              <input type="password" class="form-control" id="new_password" minlength="8" autocomplete="new-password" required>
              <div class="text-danger small mt-1" id="password_error"></div>
            </div>
            <div class="col-md-6">
              <label class="form-label">Confirm new password</label>
              <input type="password" class="form-control" id="new_password_confirmation" minlength="8" autocomplete="new-password" required>
            </div>
            <div class="col-12 text-end">
              <button type="submit" class="btn btn-primary">Update Password</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('custom_script')
<script>
$(function () {
    $('#profile_form').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url: '{{ route('cinema_chain.profile.update') }}',
            method: 'POST',
            data: {
                _method: 'PUT',
                _token: '{{ csrf_token() }}',
                name:      $('#name').val(),
                last_name: $('#last_name').val(),
                username:  $('#username').val(),
                email:     $('#email').val(),
            },
        })
        .done(function () {
            $('#display_fullname').text($('#name').val() + ' ' + $('#last_name').val());
            $('#display_email').text($('#email').val());
            Swal.fire({ title: 'Done!', text: 'Profile updated successfully.', icon: 'success', confirmButtonText: 'Continue' });
        })
        .fail(function (xhr) {
            var errs = xhr.responseJSON && xhr.responseJSON.errors;
            Swal.fire({ title: 'Error', text: errs ? Object.values(errs).flat().join(' ') : 'Operation failed.', icon: 'error' });
        });
    });

    $('#password_form').on('submit', function (e) {
        e.preventDefault();
        $('#current_password_error, #password_error').text('');
        $.ajax({
            url: '{{ route('cinema_chain.profile.password') }}',
            method: 'POST',
            data: {
                _method: 'PUT',
                _token: '{{ csrf_token() }}',
                current_password:      $('#current_password').val(),
                password:              $('#new_password').val(),
                password_confirmation: $('#new_password_confirmation').val(),
            },
        })
        .done(function (res) {
            $('#password_form')[0].reset();
            Swal.fire({ title: 'Done!', text: res.message, icon: 'success', confirmButtonText: 'Continue' });
        })
        .fail(function (xhr) {
            var errs = (xhr.responseJSON && xhr.responseJSON.errors) || {};
            if (errs.current_password) { $('#current_password_error').text(errs.current_password[0]); }
            if (errs.password) { $('#password_error').text(errs.password[0]); }
            if (!errs.current_password && !errs.password) {
                Swal.fire({ title: 'Error', text: 'Operation failed.', icon: 'error' });
            }
        });
    });
});
</script>
@endsection
