<nav class="navbar p-0 fixed-top d-flex flex-row">
    <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
      <a class="navbar-brand brand-logo-mini logo-mobile" href="index.html"><img src="{{asset('/assets/images/logo-mini.png')}}" alt="logo" /></a>
    </div>
    <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
      <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
        <span class="mdi mdi-menu"></span>
      </button>
      <ul class="navbar-nav w-100">


        <li class="nav-item dropdown border-left nav-errors">
            <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
                <i class="mdi mdi-alert"></i>
                <span class="count bg-warning">!</span>
            </a>

          <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
            <h6 class="p-3 mb-0">Errors </h6>
            <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item" href="{{  route('snmp.get_snmp_with_filter')}}">
                    <div class="preview-thumbnail">
                        <div class="preview-icon bg-dark rounded-circle">
                            <i class="mdi mdi-access-point-network text-warning"></i>
                        </div>
                    </div>

                    <div class="preview-item-content">
                        <p class="preview-subject mb-1">SNMP </p>
                        <p class="text-muted ellipsis mb-0">Details  </p>
                    </div>

                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item  header_kdm_errors_btn">
                    <div class="preview-thumbnail">
                        <div class="preview-icon bg-dark rounded-circle">
                            <i class="mdi mdi-key-change  " id="icon_kdm_errors" style="color: rgb(255, 93, 93);"></i>
                        </div>
                    </div>
                    <div class="preview-item-content " id="show_kdms_errors_details">
                        <p class="preview-subject mb-1">Kdms </p>
                        <p class="text-muted ellipsis mb-0" id="header_kdm_errors"> Kdm Errors Detected  </p>
                    </div>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item  header_server_errors_btn ">
                    <div class="preview-thumbnail">
                        <div class="preview-icon bg-dark rounded-circle">
                            <i class="mdi mdi-server  " id="icon_server_errors" style="color: rgb(48, 255, 48);"></i>
                        </div>
                    </div>
                    <div class="preview-item-content" id="show_server_errors_details">
                        <p class="preview-subject mb-1">Server </p>
                        <p class="text-muted ellipsis mb-0" id="header_server_errors">Healthy </p>
                    </div>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item header_projector_errors_btn">
                    <div class="preview-thumbnail">
                        <div class="preview-icon bg-dark rounded-circle">
                            <i class="mdi mdi-projector   text- " id="icon_projector_errors" style="color: rgb(255, 93, 93);"></i>
                        </div>
                    </div>
                    <div class="preview-item-content" id="show_projector_errors_details">
                        <p class="preview-subject mb-1">Projector </p>
                        <p class="text-muted ellipsis mb-0" id="header_projector_errors">6 Projector Errors Detected  </p>
                    </div>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item header_sound_errors_btn">
                    <div class="preview-thumbnail">
                        <div class="preview-icon bg-dark rounded-circle">
                            <i class="mdi mdi-volume-high  text- " id="icon_sound_errors" style="color: rgb(48, 255, 48);"></i>
                        </div>
                    </div>
                    <div class="preview-item-content" id="show_sound_errors_details">
                        <p class="preview-subject mb-1">Sound </p>
                        <p class="text-muted ellipsis mb-0" id="header_sound_errors">Healthy </p>
                    </div>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item show_storage_errors_details">
                    <div class="preview-thumbnail">
                        <div class="preview-icon bg-dark rounded-circle">
                            <i class="mdi mdi-chart-pie  text- " id="icon_storage_errors" style="color: rgb(48, 255, 48);"></i>
                        </div>
                    </div>
                    <div class="preview-item-content" id="show_storage_errors_details">
                        <p class="preview-subject mb-1">Storage </p>
                        <p class="text-muted ellipsis mb-0" id="header_storage_errors">Healthy </p>
                    </div>
                </a>
          </div>
        </li>
      </ul>

      <ul class="navbar-nav navbar-nav-right">

        <li class="nav-item nav-settings d-none d-lg-block">
            <a class="nav-link" href="{{ route('config.change_theme') }}">

                @if(Auth::user()->theme?->light)
                    <i class="mdi mdi-weather-night"></i> Dark
                @else
                    <i class="mdi mdi-weather-sunny"></i>  Light
                @endif

            </a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link" id="profileDropdown" href="#" data-bs-toggle="dropdown">
            <div class="navbar-profile">
              <img class="img-xs rounded-circle" src="{{ asset('assets/images/faces/face15.jpg')}}" alt="">
              <p class="mb-0 d-none d-sm-block navbar-profile-name">{{ Auth::user()->name }}</p>
              <i class="mdi mdi-menu-down d-none d-sm-block"></i>
            </div>
          </a>
          <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="profileDropdown">
            <h6 class="p-3 mb-0">Profile</h6>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item preview-item">
              <div class="preview-thumbnail">
                <div class="preview-icon bg-dark rounded-circle">
                  <i class="mdi mdi-settings text-success"></i>
                </div>
              </div>
              <div class="preview-item-content">
                <p class="preview-subject mb-1">Settings</p>
              </div>
            </a>
            <a class="dropdown-item preview-item" id="reset_user_password">
                <div class="preview-thumbnail">
                  <div class="preview-icon bg-dark rounded-circle">
                    <i class="mdi mdi-lock-outline text-success"></i>
                  </div>
                </div>
                <div class="preview-item-content">
                  <p class="preview-subject mb-1" id="reset_user_password">Edit Password </p>
                </div>
              </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item preview-item">
              <div class="preview-thumbnail">
                <div class="preview-icon bg-dark rounded-circle">
                  <i class="mdi mdi-logout text-danger"></i>
                </div>
              </div>
              <div class="preview-item-content">

                <p class="preview-subject mb-1" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" href="{{ route('logout') }} " role="button">
                    <i class="bx bx-power-off fs-15 align-middle me-1 text-danger"></i>
                    <span key="t-logout">Log out</span> </p>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                    </form>


              </div>
            </a>

          </div>
        </li>
      </ul>
      <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
        <span class="mdi mdi-format-line-spacing"></span>
      </button>
    </div>
  </nav>



