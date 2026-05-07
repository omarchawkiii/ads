@extends('content_approval.layouts.app')
@section('title') My Profile @endsection

@section('content')
<div class="row g-4">
  <div class="col-lg-4">
    <div class="card text-center">
      <div class="card-body py-5">
        <img src="{{ asset('assets/images/profile/user-1.jpg') }}"
             class="rounded-circle mb-3 border border-3 border-primary"
             width="100" height="100" alt="avatar">
        <h5 class="fw-bold mb-1">{{ $user->name }} {{ $user->last_name }}</h5>
        <span class="badge bg-primary-subtle text-primary mb-2">Content Approval</span>
        <p class="text-muted mb-0 fs-2">{{ $user->email }}</p>
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
          <span class="text-muted fs-2">Email</span>
          <span class="fw-semibold fs-2" id="display_email">{{ $user->email }}</span>
        </li>
        <li class="list-group-item d-flex justify-content-between px-4 py-3">
          <span class="text-muted fs-2">Role</span>
          <span class="badge bg-primary-subtle text-primary">Content Approval</span>
        </li>
      </ul>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="card">
      <div class="card-body p-4">
        <h5 class="card-title fw-semibold mb-4">Edit Profile Information</h5>
        <form id="profile_form">
          @csrf
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
        <button type="button" class="btn btn-outline-secondary" id="reset_user_password">
          <i class="mdi mdi-lock-reset me-1"></i> Change Password
        </button>
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
            url: '{{ route('content_approval.profile.update') }}',
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
            $('#display_name').text($('#name').val());
            $('#display_last_name').text($('#last_name').val() || '—');
            $('#display_email').text($('#email').val());
            Swal.fire({ title: 'Done!', text: 'Profile updated successfully.', icon: 'success', confirmButtonText: 'Continue' });
        })
        .fail(function () {
            Swal.fire({ title: 'Error', text: 'Operation failed.', icon: 'error' });
        });
    });
});
</script>
@endsection
