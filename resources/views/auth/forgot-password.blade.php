@extends('layouts.app_login')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="auth-full-page-content d-flex min-vh-100 py-sm-5 py-4">
            <div class="w-100">
                <div class="d-flex flex-column h-100 py-0 py-xl-4">

                    <div class="text-center mb-5">
                        <a href="{{ route('dashboard') }}">
                            <span class="logo-lg">
                                <img src="assets/images/logo-dark.png" alt="" height="37">
                            </span>
                        </a>
                    </div>
                    <div class="card my-auto overflow-hidden">
                        <div class="row g-0">
                            <div class="col-lg-6">
                                <div class="p-lg-5 p-4">
                                    <div class="text-center">
                                        {{ __('authtrad.Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                                    </div>

                                    @if (session('status'))
                                        <div class="alert alert-info" role="alert">
                                            {{ session('status') }}
                                        </div>
                                    @endif


                                    <div class="mt-4">
                                        <form method="POST" action="{{ route('password.email') }}">
                                            @csrf

                                            <div class="mb-3">
                                                <label for="email" class="col-form-label">{{ __('authtrad.Email Address') }}</label>
                                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="mb-0">
                                                <div class="col-md-12">
                                                    <button type="submit" class="btn btn-primary">
                                                        {{ __('authtrad.Send Password Reset Link') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="d-flex h-100 bg-auth align-items-end">
                                    <div class="p-lg-5 p-4">
                                        <div class="bg-overlay bg-primary"></div>
                                        <div class="p-0 p-sm-4 px-xl-0 py-5">
                                            <div id="reviewcarouselIndicators" class="carousel slide auth-carousel" data-bs-ride="carousel">
                                                <div class="carousel-indicators carousel-indicators-rounded">
                                                    <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="0" class="" aria-label="Slide 1"></button>
                                                    <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="1" aria-label="Slide 2" class="active" aria-current="true"></button>

                                                </div>

                                                <!-- end carouselIndicators -->
                                                <div class="carousel-inner mx-auto">
                                                    <div class="carousel-item">
                                                        <div class="testi-contain text-center">
                                                            <h5 class="fs-20 text-white mb-0">{{ __('authtrad.Facilitez la facturation, simplifiez la réussite')}}</h5>

                                                        </div>
                                                    </div>

                                                    <div class="carousel-item active">
                                                        <div class="testi-contain text-center">
                                                            <h5 class="fs-20 text-white mb-0">{{ __('authtrad.Simplifiez la facturation, maximisez vos gains')}}</h5>

                                                        </div>
                                                    </div>

                                                </div>
                                                <!-- end carousel-inner -->
                                            </div>
                                            <!-- end review carousel -->
                                        </div>
                                    </div>
                                </div>
                            </div>


                    </div>
                </div>
                <!-- end card -->


            </div>
        </div>
    </div>
</div>
<!-- end col -->
</div>
<!-- end row -->

  </div>

@endsection
