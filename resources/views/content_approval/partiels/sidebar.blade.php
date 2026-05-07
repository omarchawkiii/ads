<aside class="left-sidebar with-vertical">
    <div>
      <div class="brand-logo d-flex align-items-center">
        <a href="{{ route('content_approval.dashboard') }}" class="text-nowrap logo-img">
          <img src="{{ asset('assets/images/logos/logo.png')}}" alt="Logo">
        </a>
      </div>

      <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
        <ul class="sidebar-menu" id="sidebarnav">
          <li class="nav-small-cap">
            <iconify-icon icon="solar:menu-dots-linear" class="mini-icon"></iconify-icon>
            <span class="hide-menu">Dashboards</span>
          </li>

          <li class="sidebar-item {{ request()->routeIs('content_approval.dashboard') ? 'active' : '' }}">
            <a class="sidebar-link {{ request()->routeIs('content_approval.dashboard') ? 'active' : '' }}" href="{{ route('content_approval.dashboard') }}" aria-expanded="false">
              <iconify-icon icon="solar:widget-add-line-duotone" class=""></iconify-icon>
              <span class="hide-menu">Dashboard</span>
            </a>
          </li>

          <li><span class="sidebar-divider lg"></span></li>

          <li class="nav-small-cap">
            <iconify-icon icon="solar:menu-dots-linear" class="mini-icon"></iconify-icon>
            <span class="hide-menu">Content</span>
          </li>

          <li class="sidebar-item {{ request()->routeIs('content_approval.dcp_creatives.index') ? 'active' : '' }}">
            <a class="sidebar-link {{ request()->routeIs('content_approval.dcp_creatives.index') ? 'active' : '' }}" href="{{ route('content_approval.dcp_creatives.index') }}" aria-expanded="false">
              <i class="mdi mdi-bullhorn"></i>
              <span class="hide-menu">DCP Creatives</span>
            </a>
          </li>
        </ul>
      </nav>
    </div>
</aside>
