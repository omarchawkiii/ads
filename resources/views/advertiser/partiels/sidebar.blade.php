<!-- Sidebar Start -->
<aside class="left-sidebar with-vertical">
    <!-- ---------------------------------- -->
    <!-- Start Vertical Layout Sidebar -->
    <!-- ---------------------------------- -->

    <div>

      <div class="brand-logo d-flex align-items-center">
        <a href="index.html" class="text-nowrap logo-img">
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
          <li class="sidebar-item" style="display: none">
            <a class="sidebar-link" href="" id="get-url" aria-expanded="false">
              <iconify-icon icon="solar:widget-add-line-duotone" class=""></iconify-icon>
              <span class="hide-menu">Dashboard </span>
            </a>
          </li>


          <li>
            <span class="sidebar-divider lg"></span>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('advertiser.compaigns.index') }}" id="get-url" aria-expanded="false">
                <i class="mdi mdi-chart-bar"> </i>
              <span class="hide-menu">Compaigns</span>
            </a>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('advertiser.dcp_creatives.index') }}" id="get-url" aria-expanded="false">
                <i class="mdi mdi-bullhorn"> </i>
              <span class="hide-menu">DCP Creative</span>
            </a>
          </li>

        </ul>
      </nav>

    </div>
  </aside>
  <!--  Sidebar End -->

