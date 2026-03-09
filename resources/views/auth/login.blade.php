@extends('layouts.app_login')
@section('title')
    Login
@endsection

@section('content')
<div class="position-relative overflow-hidden auth-bg min-vh-100 w-100 d-flex align-items-center justify-content-center">
    <div class="d-flex align-items-center justify-content-center w-100">
      <div class="row justify-content-center w-100 my-5 my-xl-0">
        <div class="col-md-9 d-flex flex-column justify-content-center">
          <div class="card mb-0 bg-body auth-login m-auto w-100">
            <div class="row gx-0">

              <!-- ================================================ -->
              <!-- Left — Sign-in form -->
              <!-- ================================================ -->
              <div class="col-xl-6 border-end">
                <div class="row justify-content-center py-4">
                  <div class="col-lg-11">
                    <div class="card-body">

                      <a href="{{ url('/') }}" class="text-nowrap logo-img d-block mb-4 w-100">
                        <img src="{{ asset('assets/images/logos/logo.png') }}" class="dark-logo" alt="AdSmart Logo" style="margin:auto; display:table;">
                      </a>

                      <h2 class="lh-base mb-1 text-center">Welcome back</h2>
                      <p class="text-muted text-center mb-4">Sign in to your AdSmart account to manage your campaigns.</p>

                      <form action="{{ route('login') }}" method="POST" class="mx-3 mx-md-5">
                        @csrf

                        <div class="mb-3">
                          <label for="username" class="form-label fw-semibold">Username or Email Address</label>
                          <input
                            type="text"
                            id="username"
                            name="username"
                            class="form-control @error('username') is-invalid @enderror"
                            placeholder="Enter your username or email"
                            value="{{ old('username') }}"
                            autofocus
                          >
                          @error('username')
                            <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                            </span>
                          @enderror
                        </div>

                        <div class="mb-4">
                          <div class="d-flex align-items-center justify-content-between">
                            <label for="password" class="form-label fw-semibold">Password</label>
                          </div>
                          <input
                            class="form-control @error('password') is-invalid @enderror"
                            name="password"
                            id="password"
                            type="password"
                            placeholder="Enter your password"
                          >
                          @error('password')
                            <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                            </span>
                          @enderror
                        </div>

                        <div class="d-flex align-items-center justify-content-between mb-4">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="rememberMe">
                            <label class="form-check-label text-dark" for="rememberMe">
                              Keep me signed in
                            </label>
                          </div>
                        </div>

                        <button type="submit" class="btn btn-dark w-100 py-8 mb-4 rounded-1">
                          Sign In
                        </button>
                      </form>

                      <p class="text-center text-muted small mb-3">
                        Having trouble signing in? Contact your administrator.
                      </p>

                    </div>
                  </div>
                </div>
              </div>

              <!-- ================================================ -->
              <!-- Right — Feature carousel -->
              <!-- ================================================ -->
              <div class="col-xl-6 d-none d-xl-block">
                <div class="row justify-content-center align-items-start h-100">
                  <div class="col-lg-9">
                    <div id="auth-login" class="carousel slide auth-carousel mt-3 pt-2" data-bs-ride="carousel">

                      <div class="carousel-indicators">
                        <button type="button" data-bs-target="#auth-login" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#auth-login" data-bs-slide-to="1" aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#auth-login" data-bs-slide-to="2" aria-label="Slide 3"></button>
                      </div>

                      <div class="carousel-inner">

                        <!-- Slide 1 -->
                        <div class="carousel-item active">
                          <div class="d-flex align-items-center justify-content-center w-100 h-100 flex-column gap-9 text-center">
                            <img src="{{ asset('assets/images/backgrounds/login-side.png') }}" alt="Campaign Management" width="300" class="img-fluid">
                            <h4 class="mb-0">Smart Campaign Management</h4>
                            <p class="fs-12 mb-0">
                              Plan, schedule, and monitor your cinema advertising campaigns in one place.
                              Set budgets, define durations, and track every detail with ease.
                            </p>
                            <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">Campaigns · Slots · DCP</span>
                          </div>
                        </div>

                        <!-- Slide 2 -->
                        <div class="carousel-item">
                          <div class="d-flex align-items-center justify-content-center w-100 h-100 flex-column gap-9 text-center">
                            <img src="{{ asset('assets/images/backgrounds/login-side.png') }}" alt="Audience Targeting" width="300" class="img-fluid">
                            <h4 class="mb-0">Precise Audience Targeting</h4>
                            <p class="fs-12 mb-0">
                              Reach the right viewers by targeting specific cinema chains, hall types,
                              movie genres, and audience demographics to maximize your ad performance.
                            </p>
                            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">Targeting · Filters · Reach</span>
                          </div>
                        </div>

                        <!-- Slide 3 -->
                        <div class="carousel-item">
                          <div class="d-flex align-items-center justify-content-center w-100 h-100 flex-column gap-9 text-center">
                            <img src="{{ asset('assets/images/backgrounds/login-side.png') }}" alt="Performance Insights" width="300" class="img-fluid">
                            <h4 class="mb-0">Full Approval Workflow</h4>
                            <p class="fs-12 mb-0">
                              Submit campaigns for review, track approval status in real time,
                              and manage invoices — all within a streamlined, transparent workflow.
                            </p>
                            <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill">Review · Approve · Invoice</span>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection
