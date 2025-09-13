<!-- Sidebar Start -->
<aside class="left-sidebar with-vertical">
    <!-- ---------------------------------- -->
    <!-- Start Vertical Layout Sidebar -->
    <!-- ---------------------------------- -->

    <div>

      <div class="brand-logo d-flex align-items-center">
        <a href="{{ route('dashboard') }}" class="text-nowrap logo-img">
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
          <li class="sidebar-item">
            <a class="sidebar-link" href="" id="get-url" aria-expanded="false">
              <iconify-icon icon="solar:widget-add-line-duotone" class=""></iconify-icon>
              <span class="hide-menu">Dashboard</span>
            </a>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('compaigns.index') }}" id="get-url" aria-expanded="false">

              <i class="mdi mdi-chart-bar"> </i>
              <span class="hide-menu">Compaigns </span>
            </a>
          </li>
          <li>
            <span class="sidebar-divider lg"></span>
          </li>

          <li class="nav-small-cap">
            <iconify-icon icon="solar:menu-dots-linear" class="mini-icon"></iconify-icon>
            <span class="hide-menu">Settings</span>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('locations.index') }}" id="get-url" aria-expanded="false">

              <i class="mdi mdi-home-modern"> </i>
              <span class="hide-menu">Locations Management</span>
            </a>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('slots.index') }}" id="get-url" aria-expanded="false">
              <iconify-icon icon="solar:widget-add-line-duotone" class=""></iconify-icon>
              <span class="hide-menu">Slots Management</span>
            </a>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('targets.index') }}" id="get-url" aria-expanded="false">

              <i class="mdi mdi-magnet-on" > </i>
              <span class="hide-menu">Targets Management</span>
            </a>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('brands.index') }}" id="get-url" aria-expanded="false">

              <i class="mdi mdi-source-branch" > </i>
              <span class="hide-menu">Brands Management</span>
            </a>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('halls.index') }}" id="get-url" aria-expanded="false">

              <i class="mdi mdi-monitor" > </i>
              <span class="hide-menu">Halls Type Management</span>
            </a>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('movies.index') }}" id="get-url" aria-expanded="false">

              <i class="mdi mdi-filmstrip" > </i>
              <span class="hide-menu">Movies  Management</span>
            </a>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('movies_genres.index') }}" id="get-url" aria-expanded="false">

              <i class=" mdi mdi-filmstrip" > </i>
              <span class="hide-menu">Movies Genres </span>
            </a>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('interests.index') }}" id="get-url" aria-expanded="false">

              <i class=" mdi mdi-nature-people" > </i>
              <span class="hide-menu">Interests Management</span>
            </a>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('users.index') }}" id="get-url" aria-expanded="false">

              <i class=" mdi mdi-account" > </i>
              <span class="hide-menu">Users Management</span>
            </a>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('compaign_categories.index') }}" id="get-url" aria-expanded="false">

              <i class=" mdi mdi-chart-bubble" > </i>
              <span class="hide-menu">Compaign Categories</span>
            </a>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('compaign_objectives.index') }}" id="get-url" aria-expanded="false">
              <i class=" mdi mdi-chart-line" > </i>

              <span class="hide-menu">Compaign Objectives</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('invoices.index') }}" id="get-url" aria-expanded="false">
              <i class=" mdi mdi-file-document" > </i>

              <span class="hide-menu">Billing</span>
            </a>
          </li>

        </ul>
      </nav>

    </div>
  </aside>
  <!--  Sidebar End -->
