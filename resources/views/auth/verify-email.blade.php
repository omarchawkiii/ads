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
                                            <h5 class="mb-0">{{ __('authtrad.Before proceeding, please check your email for a verification link.') }}</h5>
                                            <p class="text-muted mt-2">{{ __('authtrad.If you did not receive the email') }}</p>

                                            @if (session('status') == 'verification-link-sent')
                                                <div class="alert alert-info" role="alert">
                                                    {{ __('authtrad.A fresh verification link has been sent to your email address.') }}
                                                </div>
                                            @endif

                                        </div>

                                        <div class="mt-4">
                                            <form class="auth-input" method="POST" action="{{ route('verification.send') }}">
                                                @csrf
                                                <div class="mt-2">
                                                    <button class="btn btn-primary w-100" type="submit">{{ __('authtrad.click here to request another') }}</button>
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



