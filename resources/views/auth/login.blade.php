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
              <!-- ------------------------------------------------- -->
              <!-- Part 1 -->
              <!-- ------------------------------------------------- -->
              <div class="col-xl-6 border-end">
                <div class="row justify-content-center py-4">
                  <div class="col-lg-11">
                    <div class="card-body">
                      <a href="../main/index.html" class="text-nowrap logo-img d-block  mb-4 w-100">
                        <img src="{{asset('assets/images/logos/logo.png')}}" class="dark-logo" alt="Logo-Dark" style="margin: auto; display : table ">
                      </a>
                      <h2 class="lh-base m-4 text-center">Let's get you signed in</h2>


                      <form action="{{ route('login') }}" method="POST" class="m-5">
                        @csrf()
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Username Or Email Address</label>
                            <input  type="username" id="username" name="username" aria-describedby="username"  class="form-control @error('username') is-invalid @enderror"  placeholder="Enter your username or email" >
                            @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <label for="exampleInputPassword1" class="form-label">Password</label>
                                <a class="text-primary link-dark fs-2" href="../main/authentication-forgot-password2.html">Forgot
                                Password ?</a>
                            </div>
                            <input  class="form-control @error('password') is-invalid @enderror" name="password"  id="password" type="password" placeholder="Enter your password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-4">
                          <div class="form-check">
                            <input class="form-check-input primary" type="checkbox" value="" id="flexCheckChecked" checked="">
                            <label class="form-check-label text-dark" for="flexCheckChecked">
                              Keep me logged in
                            </label>
                          </div>
                        </div>
                        <button type="submit" class="btn btn-dark w-100 py-8 mb-4 rounded-1">Sign In</button>
                      </form>


                    </div>
                  </div>
                </div>

              </div>
              <!-- ------------------------------------------------- -->
              <!-- Part 2 -->
              <!-- ------------------------------------------------- -->
              <div class="col-xl-6 d-none d-xl-block">
                <div class="row justify-content-center align-items-start h-100">
                  <div class="col-lg-9 ">
                    <div id="auth-login" class="carousel slide auth-carousel mt-3 pt-2" data-bs-ride="carousel">
                      <div class="carousel-indicators">
                        <button type="button" data-bs-target="#auth-login" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#auth-login" data-bs-slide-to="1" aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#auth-login" data-bs-slide-to="2" aria-label="Slide 3"></button>
                      </div>
                      <div class="carousel-inner">
                        <div class="carousel-item active">
                          <div class="d-flex align-items-center justify-content-center w-100 h-100 flex-column gap-9 text-center">
                            <img src="{{asset('assets/images/backgrounds/login-side.png')}}" alt="login-side-img" width="300" class="img-fluid">
                            <h4 class="mb-0">Feature Rich 3D Charts</h4>
                            <p class="fs-12 mb-0">Donec justo tortor, malesuada vitae faucibus ac, tristique sit amet
                              massa.
                              Aliquam dignissim nec felis quis imperdiet.</p>
                            <a href="javascript:void(0)" class="btn btn-primary rounded-1">Learn More</a>
                          </div>
                        </div>
                        <div class="carousel-item">
                          <div class="d-flex align-items-center justify-content-center w-100 h-100 flex-column gap-9 text-center">
                            <img src="{{asset('assets/images/backgrounds/login-side.png')}}" alt="login-side-img" width="300" class="img-fluid">
                            <h4 class="mb-0">Feature Rich 2D Charts</h4>
                            <p class="fs-12 mb-0">Donec justo tortor, malesuada vitae faucibus ac, tristique sit amet
                              massa.
                              Aliquam dignissim nec felis quis imperdiet.</p>
                            <a href="javascript:void(0)" class="btn btn-primary rounded-1">Learn More</a>
                          </div>
                        </div>
                        <div class="carousel-item">
                          <div class="d-flex align-items-center justify-content-center w-100 h-100 flex-column gap-9 text-center">
                            <img src="{{asset('assets/images/backgrounds/login-side.png')}}" alt="login-side-img" width="300" class="img-fluid">
                            <h4 class="mb-0">Feature Rich 1D Charts</h4>
                            <p class="fs-12 mb-0">Donec justo tortor, malesuada vitae faucibus ac, tristique sit amet
                              massa.
                              Aliquam dignissim nec felis quis imperdiet.</p>
                            <a href="javascript:void(0)" class="btn btn-primary rounded-1">Learn More</a>
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

