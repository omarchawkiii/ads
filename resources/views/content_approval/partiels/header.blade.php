<header class="topbar">
    <div class="with-vertical">
      <nav class="navbar navbar-expand-lg p-0">
        <ul class="navbar-nav">
          <li class="nav-item nav-icon-hover-bg rounded-circle d-flex">
            <a class="nav-link sidebartoggler" id="headerCollapse" href="javascript:void(0)">
              <iconify-icon icon="solar:hamburger-menu-line-duotone" class="fs-6"></iconify-icon>
            </a>
          </li>
        </ul>

        <div class="d-block d-lg-none py-9 py-xl-0">
          <img src="{{ asset('assets/images/logos/logo.png')}}" alt="adsamrt-img">
        </div>
        <a class="navbar-toggler p-0 border-0 nav-icon-hover-bg rounded-circle" href="javascript:void(0)" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false">
          <iconify-icon icon="solar:menu-dots-bold-duotone" class="fs-6"></iconify-icon>
        </a>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
          <div class="d-flex align-items-center justify-content-between">
            <ul class="navbar-nav flex-row mx-auto ms-lg-auto align-items-center justify-content-center">

              <li class="nav-item">
                @if(Auth::user()->theme?->light)
                  <a class="change_theme nav-link moon dark-layout nav-icon-hover-bg rounded-circle" href="{{ route('change_theme') }}">
                    <iconify-icon icon="solar:moon-line-duotone" class="moon fs-6"></iconify-icon>
                  </a>
                  <a class="change_theme nav-link sun light-layout nav-icon-hover-bg rounded-circle" href="{{ route('change_theme') }}" style="display: none">
                    <iconify-icon icon="solar:sun-2-line-duotone" class="sun fs-6"></iconify-icon>
                  </a>
                @else
                  <a class="change_theme nav-link sun light-layout nav-icon-hover-bg rounded-circle" href="{{ route('change_theme') }}">
                    <iconify-icon icon="solar:sun-2-line-duotone" class="sun fs-6"></iconify-icon>
                  </a>
                  <a class="change_theme nav-link moon dark-layout nav-icon-hover-bg rounded-circle" href="{{ route('change_theme') }}" style="display: none">
                    <iconify-icon icon="solar:moon-line-duotone" class="moon fs-6"></iconify-icon>
                  </a>
                @endif
              </li>

              <li class="nav-item dropdown">
                <a class="nav-link" href="javascript:void(0)" id="drop1" aria-expanded="false">
                  <div class="d-flex align-items-center gap-2 lh-base">
                    <img src="{{ asset('assets/images/profile/user-1.jpg')}}" class="rounded-circle" width="35" height="35" alt="avatar">
                    <iconify-icon icon="solar:alt-arrow-down-bold" class="fs-2"></iconify-icon>
                  </div>
                </a>
                <div class="dropdown-menu profile-dropdown dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop1">
                  <div class="position-relative px-4 pt-3 pb-2">
                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom gap-6">
                      <img src="{{ asset('assets/images/profile/user-1.jpg')}}" class="rounded-circle" width="56" height="56" alt="avatar">
                      <div>
                        <h5 class="mb-0 fs-12">{{ Auth::user()->name }} {{ Auth::user()->last_name }} <span class="text-primary fs-11">Content Approval</span></h5>
                        <p class="mb-0 text-dark">{{ Auth::user()->email }}</p>
                      </div>
                    </div>
                    <div class="message-body">
                      <a href="{{ route('content_approval.profile.index') }}" class="p-2 dropdown-item h6 rounded-1">My Profile</a>
                      <a onclick="event.preventDefault(); document.getElementById('logout-form').submit();" href="{{ route('logout') }}" role="button" class="p-2 dropdown-item h6 rounded-1">Sign Out</a>
                      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                      </form>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    </div>
</header>
