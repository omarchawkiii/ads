<!-- Sidebar Start -->
<aside class="left-sidebar with-vertical">
    <!-- ---------------------------------- -->
    <!-- Start Vertical Layout Sidebar -->
    <!-- ---------------------------------- -->

    <div>

      <div class="brand-logo d-flex align-items-center">
        <a href="{{ route('advertiser.dashboard') }}" class="text-nowrap logo-img">
          <img src="{{ asset('assets/images/logos/logo.png')}}" alt="Logo">
        </a>

      </div>

      <!-- ---------------------------------- -->
      <!-- Dashboard -->
      <!-- ---------------------------------- -->
      <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
        <ul class="sidebar-menu" id="sidebarnav">
          <!-- ---------------------------------- -->
          <!-- Home -->
          <!-- ---------------------------------- -->
          <li class="nav-small-cap">
            <iconify-icon icon="solar:menu-dots-linear" class="mini-icon"></iconify-icon>
            <span class="hide-menu">Dashboards</span>
          </li>
          <!-- ---------------------------------- -->
          <!-- Dashboard -->
          <!-- ---------------------------------- -->
           <li class="sidebar-item {{ request()->routeIs('advertiser.dashboard') ? 'active' : '' }}">
            <a class="sidebar-link {{ request()->routeIs('advertiser.dashboard') ? 'active' : '' }}" href="{{ route('advertiser.dashboard') }}" id="get-url"  aria-expanded="false">
                <iconify-icon icon="solar:widget-add-line-duotone" class=""></iconify-icon>
              <span class="hide-menu">Dashboard</span>
            </a>
          </li>


          <li>
            <span class="sidebar-divider lg"></span>
          </li>


          <li class="sidebar-item {{ request()->routeIs('advertiser.compaigns.index') ? 'active' : '' }}">
            <a class="sidebar-link {{ request()->routeIs('advertiser.compaigns.index') ? 'active' : '' }}" href="{{ route('advertiser.compaigns.index') }}" id="get-url" aria-expanded="false">
                <i class="mdi mdi-chart-bar"> </i>
              <span class="hide-menu">Campaigns</span>
            </a>
          </li>

          <li class="sidebar-item {{ request()->routeIs('advertiser.compaigns.index_builder') ? 'active' : '' }}">
            <a class="sidebar-link {{ request()->routeIs('advertiser.compaigns.index_builder') ? 'active' : '' }}" href="{{ route('advertiser.compaigns.index_builder') }}" id="get-url" aria-expanded="false">
                <i class="mdi mdi-chart-bar"> </i>
              <span class="hide-menu">Campaigns Builder</span>
            </a>
          </li>

          <li class="sidebar-item {{ request()->routeIs('advertiser.dcp_creatives.index') ? 'active' : '' }}">
            <a class="sidebar-link {{ request()->routeIs('advertiser.dcp_creatives.index') ? 'active' : '' }}" href="{{ route('advertiser.dcp_creatives.index') }}" id="get-url" aria-expanded="false">
                <i class="mdi mdi-bullhorn"> </i>
              <span class="hide-menu">DCP Creative</span>
            </a>
          </li>
          @if(auth()->user()->advertiser_type !== 'direct')
          <li class="sidebar-item {{ request()->routeIs('advertiser.customers.index') ? 'active' : '' }}">
            <a class="sidebar-link {{ request()->routeIs('advertiser.customers.index') ? 'active' : '' }}" href="{{ route('advertiser.customers.index') }}" id="get-url" aria-expanded="false">
                <i class="mdi mdi-account-star"> </i>
              <span class="hide-menu">My Clients</span>
            </a>
          </li>
          @endif

          <li>
            <span class="sidebar-divider lg"></span>
          </li>

          <li class="nav-small-cap">
            <iconify-icon icon="solar:menu-dots-linear" class="mini-icon"></iconify-icon>
            <span class="hide-menu">Billing</span>
          </li>

          <li class="sidebar-item {{ request()->routeIs('advertiser.invoices.index') ? 'active' : '' }}">
            <a class="sidebar-link {{ request()->routeIs('advertiser.invoices.index') ? 'active' : '' }}" href="{{ route('advertiser.invoices.index') }}" id="get-url" aria-expanded="false">
                <i class="mdi mdi-receipt"> </i>
              <span class="hide-menu">Billing</span>
            </a>
          </li>

        </ul>
      </nav>

    </div>
  </aside>
  <!--  Sidebar End -->

