<!--  Header Start -->
<header class="topbar">
    <div class="with-vertical">
      <!-- ---------------------------------- -->
      <!-- Start Vertical Layout Header -->
      <!-- ---------------------------------- -->
      <nav class="navbar navbar-expand-lg p-0">
        <ul class="navbar-nav">
          <li class="nav-item nav-icon-hover-bg rounded-circle d-flex">
            <a class="nav-link  sidebartoggler" id="headerCollapse" href="javascript:void(0)">
              <iconify-icon icon="solar:hamburger-menu-line-duotone" class="fs-6"></iconify-icon>
            </a>
          </li>


        </ul>

        <div class="d-block d-lg-none py-9 py-xl-0">
          <img src="{{ asset('assets/images/logos/logo.png')}}" alt="matdash-img">
        </div>
        <a class="navbar-toggler p-0 border-0 nav-icon-hover-bg rounded-circle" href="javascript:void(0)" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <iconify-icon icon="solar:menu-dots-bold-duotone" class="fs-6"></iconify-icon>
        </a>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
          <div class="d-flex align-items-center justify-content-between">
            <ul class="navbar-nav flex-row mx-auto ms-lg-auto align-items-center justify-content-center">

              <li class="nav-item">

                @if(Auth::user()->theme?->light)
                    <a class=" change_theme nav-link moon dark-layout nav-icon-hover-bg rounded-circle" href="{{ route('change_theme') }}">
                        <iconify-icon icon="solar:moon-line-duotone" class="moon fs-6"></iconify-icon>
                    </a>
                    <a class="change_theme nav-link sun light-layout nav-icon-hover-bg rounded-circle" href="{{ route('change_theme') }}"  style="display: none">
                        <iconify-icon icon="solar:sun-2-line-duotone" class="sun fs-6"></iconify-icon>
                    </a>
                @else
                    <a class="change_theme nav-link sun light-layout nav-icon-hover-bg rounded-circle" href="{{ route('change_theme') }}" >
                        <iconify-icon icon="solar:sun-2-line-duotone" class="sun fs-6"></iconify-icon>
                    </a>
                    <a class=" change_theme nav-link moon dark-layout nav-icon-hover-bg rounded-circle" href="{{ route('change_theme') }}" style="display: none">
                        <iconify-icon icon="solar:moon-line-duotone" class="moon fs-6"></iconify-icon>
                    </a>
                @endif
              </li>




              <!-- ------------------------------- -->
              <!-- start profile Dropdown -->
              <!-- ------------------------------- -->
              <li class="nav-item dropdown">
                <a class="nav-link" href="javascript:void(0)" id="drop1" aria-expanded="false">
                  <div class="d-flex align-items-center gap-2 lh-base">
                    <img src="{{ asset('assets/images/profile/user-1.jpg')}}" class="rounded-circle" width="35" height="35" alt="matdash-img">
                    <iconify-icon icon="solar:alt-arrow-down-bold" class="fs-2"></iconify-icon>
                  </div>
                </a>
                <div class="dropdown-menu profile-dropdown dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop1">
                  <div class="position-relative px-4 pt-3 pb-2">
                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom gap-6">
                      <img src="{{ asset('assets/images/profile/user-1.jpg')}}" class="rounded-circle" width="56" height="56" alt="matdash-img">
                      <div>
                        <h5 class="mb-0 fs-12">{{ Auth::user()->name }} {{Auth::user()->last_name  }}  <span class="text-success fs-11">@if( Auth::user()->role==1) Admin @elseif( Auth::user()->role==2) Advertiser  @endif</span>
                        </h5>
                        <p class="mb-0 text-dark">
                            {{ Auth::user()->email }}
                        </p>
                      </div>
                    </div>
                    <div class="message-body">
                      <a href="../main/page-user-profile.html" class="p-2 dropdown-item h6 rounded-1">
                        My Profile
                      </a>
                      <a href="#" class="p-2 dropdown-item h6 rounded-1" id="reset_user_password">
                        Reset Password
                      </a>
                      <a href="../main/app-invoice.html" class="p-2 dropdown-item h6 rounded-1">
                        My Invoice <span class="badge bg-danger-subtle text-danger rounded ms-8">4</span>
                      </a>
                      <a href="../main/page-account-settings.html" class="p-2 dropdown-item h6 rounded-1">
                        Account Settings
                      </a>

                      <a onclick="event.preventDefault(); document.getElementById('logout-form').submit();" href="{{ route('logout') }} " role="button" class="p-2 dropdown-item h6 rounded-1">
                        Sign Out
                      </a>
                      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                        </form>


                    </div>
                  </div>
                </div>
              </li>
              <!-- ------------------------------- -->
              <!-- end profile Dropdown -->
              <!-- ------------------------------- -->
            </ul>
          </div>
        </div>
      </nav>
      <!-- ---------------------------------- -->
      <!-- End Vertical Layout Header -->
      <!-- ---------------------------------- -->

      <!-- ------------------------------- -->
      <!-- apps Dropdown in Small screen -->
      <!-- ------------------------------- -->


    </div>
    <div class="app-header with-horizontal">
      <nav class="navbar navbar-expand-xl container-fluid p-0">
        <ul class="navbar-nav align-items-center">
          <li class="nav-item d-flex d-xl-none">
            <a class="nav-link sidebartoggler nav-icon-hover-bg rounded-circle" id="sidebarCollapse" href="javascript:void(0)">
              <iconify-icon icon="solar:hamburger-menu-line-duotone" class="fs-7"></iconify-icon>
            </a>
          </li>
          <li class="nav-item d-none d-xl-flex align-items-center">
            <a href="../horizontal/index.html" class="text-nowrap nav-link">
              <img src="{{ asset('assets/images/logos/logo.png')}}" alt="matdash-img">
            </a>
          </li>
          <li class="nav-item d-none d-xl-flex align-items-center nav-icon-hover-bg rounded-circle">
            <a class="nav-link" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#exampleModal">
              <iconify-icon icon="solar:magnifer-linear" class="fs-6"></iconify-icon>
            </a>
          </li>
          <li class="nav-item d-none d-lg-flex align-items-center dropdown nav-icon-hover-bg rounded-circle">
            <div class="hover-dd">
              <a class="nav-link" id="drop2" href="javascript:void(0)" aria-haspopup="true" aria-expanded="false">
                <iconify-icon icon="solar:widget-3-line-duotone" class="fs-6"></iconify-icon>
              </a>
              <div class="dropdown-menu dropdown-menu-nav dropdown-menu-animate-up py-0 overflow-hidden" aria-labelledby="drop2">
                <div class="position-relative">
                  <div class="row">
                    <div class="col-md-8">
                      <div class="p-4 pb-3">

                        <div class="row">
                          <div class="col-md-6">
                            <div class="position-relative">
                              <a href="../main/app-chat.html" class="d-flex align-items-center pb-9 position-relative">
                                <div class="bg-primary-subtle rounded round-48 me-3 d-flex align-items-center justify-content-center">
                                  <iconify-icon icon="solar:chat-line-bold-duotone" class="fs-7 text-primary"></iconify-icon>
                                </div>
                                <div>
                                  <h6 class="mb-0">Chat Application</h6>
                                  <span class="fs-11 d-block text-body-color">New messages arrived</span>
                                </div>
                              </a>
                              <a href="../main/app-invoice.html" class="d-flex align-items-center pb-9 position-relative">
                                <div class="bg-secondary-subtle rounded round-48 me-3 d-flex align-items-center justify-content-center">
                                  <iconify-icon icon="solar:bill-list-bold-duotone" class="fs-7 text-secondary"></iconify-icon>
                                </div>
                                <div>
                                  <h6 class="mb-0">Invoice App</h6>
                                  <span class="fs-11 d-block text-body-color">Get latest invoice</span>
                                </div>
                              </a>
                              <a href="../main/app-contact2.html" class="d-flex align-items-center pb-9 position-relative">
                                <div class="bg-warning-subtle rounded round-48 me-3 d-flex align-items-center justify-content-center">
                                  <iconify-icon icon="solar:phone-calling-rounded-bold-duotone" class="fs-7 text-warning"></iconify-icon>
                                </div>
                                <div>
                                  <h6 class="mb-0">Contact Application</h6>
                                  <span class="fs-11 d-block text-body-color">2 Unsaved Contacts</span>
                                </div>
                              </a>
                              <a href="../main/app-email.html" class="d-flex align-items-center pb-9 position-relative">
                                <div class="bg-danger-subtle rounded round-48 me-3 d-flex align-items-center justify-content-center">
                                  <iconify-icon icon="solar:letter-bold-duotone" class="fs-7 text-danger"></iconify-icon>
                                </div>
                                <div>
                                  <h6 class="mb-0">Email App</h6>
                                  <span class="fs-11 d-block text-body-color">Get new emails</span>
                                </div>
                              </a>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="position-relative">
                              <a href="../main/page-user-profile.html" class="d-flex align-items-center pb-9 position-relative">
                                <div class="bg-success-subtle rounded round-48 me-3 d-flex align-items-center justify-content-center">
                                  <iconify-icon icon="solar:user-bold-duotone" class="fs-7 text-success"></iconify-icon>
                                </div>
                                <div>
                                  <h6 class="mb-0">User Profile</h6>
                                  <span class="fs-11 d-block text-body-color">learn more information</span>
                                </div>
                              </a>
                              <a href="../main/app-calendar.html" class="d-flex align-items-center pb-9 position-relative">
                                <div class="bg-primary-subtle rounded round-48 me-3 d-flex align-items-center justify-content-center">
                                  <iconify-icon icon="solar:calendar-minimalistic-bold-duotone" class="fs-7 text-primary"></iconify-icon>
                                </div>
                                <div>
                                  <h6 class="mb-0">Calendar App</h6>
                                  <span class="fs-11 d-block text-body-color">Get dates</span>
                                </div>
                              </a>
                              <a href="../main/app-contact.html" class="d-flex align-items-center pb-9 position-relative">
                                <div class="bg-secondary-subtle rounded round-48 me-3 d-flex align-items-center justify-content-center">
                                  <iconify-icon icon="solar:smartphone-2-bold-duotone" class="fs-7 text-secondary"></iconify-icon>
                                </div>
                                <div>
                                  <h6 class="mb-0">Contact List Table</h6>
                                  <span class="fs-11 d-block text-body-color">Add new contact</span>
                                </div>
                              </a>
                              <a href="../main/app-notes.html" class="d-flex align-items-center pb-9 position-relative">
                                <div class="bg-warning-subtle rounded round-48 me-3 d-flex align-items-center justify-content-center">
                                  <iconify-icon icon="solar:notes-bold-duotone" class="fs-7 text-warning"></iconify-icon>
                                </div>
                                <div>
                                  <h6 class="mb-0">Notes Application</h6>
                                  <span class="fs-11 d-block text-body-color">To-do and Daily tasks</span>
                                </div>
                              </a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-4 d-none d-lg-flex">
                      <img src="{{ asset('assets/images/backgrounds/mega-dd-bg.jpg')}}" alt="mega-dd" class="img-fluid mega-dd-bg">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </li>
        </ul>
        <div class="d-block d-xl-none">
          <a href="index.html" class="text-nowrap nav-link">
            <img src="{{ asset('assets/images/logos/logo.png')}}" alt="matdash-img">
          </a>
        </div>
        <a class="navbar-toggler nav-icon-hover p-0 border-0 nav-icon-hover-bg rounded-circle" href="javascript:void(0)" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="p-2">
            <i class="ti ti-dots fs-7"></i>
          </span>
        </a>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
          <div class="d-flex align-items-center justify-content-between px-0 px-xl-8">
            <ul class="navbar-nav flex-row mx-auto ms-lg-auto align-items-center justify-content-center">
              <li class="nav-item dropdown">
                <a href="javascript:void(0)" class="nav-link nav-icon-hover-bg rounded-circle d-flex d-lg-none align-items-center justify-content-center" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobilenavbar" aria-controls="offcanvasWithBothOptions">
                  <iconify-icon icon="solar:sort-line-duotone" class="fs-6"></iconify-icon>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link nav-icon-hover-bg rounded-circle moon dark-layout" href="javascript:void(0)">
                  <iconify-icon icon="solar:moon-line-duotone" class="moon fs-6"></iconify-icon>
                </a>
                <a class="nav-link nav-icon-hover-bg rounded-circle sun light-layout" href="javascript:void(0)" style="display: none">
                  <iconify-icon icon="solar:sun-2-line-duotone" class="sun fs-6"></iconify-icon>
                </a>
              </li>
              <li class="nav-item d-block d-xl-none">
                <a class="nav-link nav-icon-hover-bg rounded-circle" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#exampleModal">
                  <iconify-icon icon="solar:magnifer-line-duotone" class="fs-6"></iconify-icon>
                </a>
              </li>


              <!-- ------------------------------- -->
              <!-- start profile Dropdown -->
              <!-- ------------------------------- -->
              <li class="nav-item dropdown">
                <a class="nav-link" href="javascript:void(0)" id="drop1" aria-expanded="false">
                  <div class="d-flex align-items-center gap-2 lh-base">
                    <img src="{{ asset('assets/images/profile/user-1.jpg')}}" class="rounded-circle" width="35" height="35" alt="matdash-img">
                    <iconify-icon icon="solar:alt-arrow-down-bold" class="fs-2"></iconify-icon>
                  </div>
                </a>
                <div class="dropdown-menu profile-dropdown dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop1">
                  <div class="position-relative px-4 pt-3 pb-2">
                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom gap-6">
                      <img src="{{ asset('assets/images/profile/user-1.jpg')}}" class="rounded-circle" width="56" height="56" alt="matdash-img">
                      <div>
                        <h5 class="mb-0 fs-12">David McMichael <span class="text-success fs-11">Pro</span>
                        </h5>
                        <p class="mb-0 text-dark">
                          david@wrappixel.com
                        </p>
                      </div>
                    </div>
                    <div class="message-body">
                      <a href="../main/page-user-profile.html" class="p-2 dropdown-item h6 rounded-1">
                        My Profile
                      </a>
                      <a href="../main/page-pricing.html" class="p-2 dropdown-item h6 rounded-1">
                        My Subscription
                      </a>
                      <a href="../main/app-invoice.html" class="p-2 dropdown-item h6 rounded-1">
                        My Invoice <span class="badge bg-danger-subtle text-danger rounded ms-8">4</span>
                      </a>
                      <a href="../main/page-account-settings.html" class="p-2 dropdown-item h6 rounded-1">
                        Account Settings
                      </a>
                      <a onclick="event.preventDefault(); document.getElementById('logout-form').submit();" href="{{ route('logout') }} " role="button" class="p-2 dropdown-item h6 rounded-1">
                        Sign Out test
                      </a>
                      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                        </form>
                    </div>
                  </div>
                </div>
              </li>
              <!-- ------------------------------- -->
              <!-- end profile Dropdown -->
              <!-- ------------------------------- -->
            </ul>
          </div>
        </div>
      </nav>

    </div>
  </header>
  <!--  Header End -->
