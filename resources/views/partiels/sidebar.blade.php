<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
      <!--<a class="sidebar-brand brand-logo" href="{{ route('snmp.get_dashbord') }}"><img src="{{asset('/assets/images/logo.png')}}" alt="logo" /></a>
      <a class="sidebar-brand brand-logo-mini" href="{{ route('snmp.get_dashbord') }}"><img src="{{asset('/assets/images/logo-mini.png')}}" alt="logo" /></a> -->
    </div>
    <ul class="nav">



        <li class="nav-item nav-category d-flex">
            <span class="nav-link logo_text" >NOC</span> <img class="logo_img" src="{{asset('/assets/images/logo.png')}}" alt="logo" style="height: 22px;  " />
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('snmp.get_dashbord') }}">
                <span class="menu-icon icon-box-danger">
                <i class="mdi mdi-speedometer"></i>
                </span>
                <span class="menu-title"> Dashboard</span>
            </a>
        </li>
        @if(Auth::user()->role != 3)
            <li class="nav-item menu-items">
                <a class="nav-link" href="{{ route('location.index') }}" >
                <span class="menu-icon icon-box-danger">
                    <i class="mdi mdi-map-marker"></i>
                </span>
                <span class="menu-title">Locations</span>
                </a>
            </li>
        @endif
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('playback.index') }}">
            <span class="menu-icon icon-box-warning">
                <i class="mdi mdi-calendar-today "></i>
            </span>
            <span class="menu-title">Playback</span>
            </a>
        </li>
        <!--<li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('snmp.get_snmp_with_filter') }}">
            <span class="menu-icon icon-box-danger">
                <i class="mdi mdi-alert-octagon "></i>
            </span>
            <span class="menu-title">SNMP Errors</span>
            </a>
        </li>-->
        @if(Auth::user()->role != 3)
            <li class="nav-item menu-items ">
                <a class="nav-link" data-bs-toggle="collapse" href="#show_snmp" aria-expanded="false" aria-controls="icons">
                <span class="menu-icon icon-box-danger">
                    <i class="mdi mdi-alert-octagon "></i>
                </span>
                <span class="menu-title"> Errors</span>
                <i class="menu-arrow"></i>
                </a>
                <div class="collapse " id="show_snmp">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link active_playlistbuilder " href="{{ route('snmp.get_snmp_with_filter') }}"> <i class="mdi mdi-checkbox-blank-circle me-1"></i> SNMP Errors </a></li>
                    <li class="nav-item"> <a class="nav-link active_playlistbuilder " href="{{ route('schedules.get_schedules_error') }}"> <i class="mdi mdi-checkbox-blank-circle me-1"></i>  Schedules Errors</a></li>
                </ul>
                </div>
            </li>

            <li class="nav-item menu-items ">
                <a class="nav-link" data-bs-toggle="collapse" href="#showm_reports" aria-expanded="false" aria-controls="icons">
                <span class="menu-icon icon-box-performance">
                    <i class="mdi mdi-bookmark-check "></i>
                </span>
                <span class="menu-title">Reports</span>
                <i class="menu-arrow"></i>
                </a>
                <div class="collapse " id="showm_reports">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link active_playlistbuilder " href="{{ route('logs.get_performance_log') }}"> <i class="mdi mdi-checkbox-blank-circle me-1"></i> Performance Logs</a></li>
                    <li class="nav-item"> <a class="nav-link active_playlistbuilder " href="{{ route('asset_infos.display_performance_log') }}"> <i class="mdi mdi-checkbox-blank-circle me-1"></i>  Asset Reports</a></li>
                    <!--<li class="nav-item"> <a class="nav-link active_playlistbuilder " href="{{ route('lamp_reports') }}"> <i class="mdi mdi-checkbox-blank-circle  me-1"></i> Lamp Reports</a></li> -->
                    <li class="nav-item"> <a class="nav-link active_playlistbuilder " href="{{ route('storage_report.get_storage_report_with_filter') }}"> <i class="mdi mdi-checkbox-blank-circle  me-1"></i> Storage Reports</a></li>
                    <li class="nav-item"> <a class="nav-link active_playlistbuilder " href="{{ route('ligth_history.index') }}"> <i class="mdi mdi-checkbox-blank-circle  me-1"></i> Ligth History </a></li>
                    <!--<li class="nav-item"> <a class="nav-link active_playlistbuilder " href="{{ route('server_storage_device.index') }}"> <i class="mdi mdi-checkbox-blank-circle  me-1"></i> Server Storage Device  </a></li>-->
                    <li class="nav-item"> <a class="nav-link active_playlistbuilder " href="{{ route('server_smart.index') }}"> <i class="mdi mdi-checkbox-blank-circle  me-1"></i> Server Smart Status  </a></li>
                    <li class="nav-item"> <a class="nav-link active_playlistbuilder " href="{{ route('server_alarm.index') }}"> <i class="mdi mdi-checkbox-blank-circle  me-1"></i> Server Alarm   </a></li>

                </ul>
                </div>
            </li>
        @endif
        <li class="nav-item menu-items ">
            <a class="nav-link" data-bs-toggle="collapse" href="#inventory_settings" aria-expanded="false" aria-controls="icons">
            <span class="menu-icon icon-box-performance">
                <i class="mdi mdi-settings"></i>
            </span>
            <span class="menu-title">Inventory Settings</span>
            <i class="menu-arrow"></i>
            </a>
            <div class="collapse " id="inventory_settings">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link active_playlistbuilder " href="{{ route('inventory_category.index') }}"> <i class="mdi mdi-checkbox-blank-circle me-1"></i> Categories </a></li>
                <li class="nav-item"> <a class="nav-link active_playlistbuilder " href="{{ route('part.index') }}"> <i class="mdi mdi-checkbox-blank-circle me-1"></i> Parts </a></li>
                <li class="nav-item"> <a class="nav-link active_playlistbuilder " href="{{ route('supplier.index') }}"> <i class="mdi mdi-checkbox-blank-circle me-1"></i> Suppliers </a></li>
                <li class="nav-item"> <a class="nav-link active_playlistbuilder " href="{{ route('storage_location.index') }}"> <i class="mdi mdi-checkbox-blank-circle me-1"></i> Storage Location </a></li>
                <li class="nav-item"> <a class="nav-link active_playlistbuilder " href="{{ route('cinema_location.index') }}"> <i class="mdi mdi-checkbox-blank-circle me-1"></i> Cinema Location </a></li>

            </ul>
            </div>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('breakdowns.dashboard_breakdown') }}">
            <span class="menu-icon icon-box-danger">
                <i class="mdi mdi-speedometer "></i>
            </span>
            <span class="menu-title">Tracker Dashboard</span>
            </a>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('breakdowns.calendars') }}">
            <span class="menu-icon icon-box-info">
                <i class="mdi mdi-calendar-text "></i>
            </span>
            <span class="menu-title">Tracker Calendar</span>
            </a>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('services.index') }}">
            <span class="menu-icon icon-box-primary">
                <i class=" mdi mdi-settings-box "></i>
            </span>
            <span class="menu-title">Services Tracker</span>
            </a>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('breakdowns.index') }}">
            <span class="menu-icon icon-box-warning">
                <i class=" mdi  mdi-alert "></i>
            </span>
            <span class="menu-title">Breakdown Tracker</span>
            </a>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('maintenances.index') }}">
            <span class="menu-icon icon-box-success">
                <i class="mdi mdi-checkbox-marked-circle-outline "></i>
            </span>
            <span class="menu-title">Maintenance Tracker</span>
            </a>
        </li>
        <li class="nav-item menu-items ">
            <a class="nav-link" data-bs-toggle="collapse" href="#consumables_tracker" aria-expanded="false" aria-controls="icons">
            <span class="menu-icon icon-box-playlistbuilder">
                <i class="mdi mdi-call-split"></i>
            </span>
            <span class="menu-title">Consumables Tracker</span>
            <i class="menu-arrow"></i>
            </a>
            <div class="collapse " id="consumables_tracker">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link active_playlistbuilder " href="{{ route('inventory_in.index') }}"> <i class="mdi mdi-checkbox-blank-circle me-1"></i> Inventory In  </a></li>
                <li class="nav-item"> <a class="nav-link active_playlistbuilder " href="{{ route('inventory_out.index') }}"> <i class="mdi mdi-checkbox-blank-circle me-1"></i> Inventory Out  </a></li>
                <li class="nav-item"> <a class="nav-link active_playlistbuilder " href="{{ route('transfer_request.index') }}"> <i class="mdi mdi-checkbox-blank-circle me-1"></i> Transfer Request   </a></li>
            </ul>
            </div>
        </li>
        @if(Auth::user()->role != 3)


        <li class="nav-item nav-category d-flex" style="align-items: center;">
            <span class="nav-link logo_text" style="margin-top: 12px;">CMS</span> <img class="logo_img" src="{{asset('/assets/images/cms_logo.png')}}" alt="logo" style="height: 22px;    margin-top: 12px;" />
        </li>



              <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('snmp.content_errors') }}">
            <span class="menu-icon icon-box-danger">
                <i class="mdi mdi-speedometer "></i>
            </span>
            <span class="menu-title">Dashboard</span>
            </a>
        </li>


        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('schedules.get_schedules_with_filter') }}">
            <span class="menu-icon icon-box-blue">
                <i class="mdi mdi-calendar-today "></i>
            </span>
            <span class="menu-title">Schedule Management</span>
            </a>
        </li>



        <li class="nav-item menu-items ">
            <a class="nav-link" data-bs-toggle="collapse" href="#showm_anagement" aria-expanded="false" aria-controls="icons">
            <span class="menu-icon icon-box-playlistbuilder">
                <i class="mdi mdi-contacts"></i>
            </span>
            <span class="menu-title">Content Management</span>
            <i class="menu-arrow"></i>
            </a>
            <div class="collapse " id="showm_anagement">
            <ul class="nav flex-column sub-menu">

                <li class="nav-item"> <a class="nav-link active_playlistbuilder " href="{{ route('spls.get_spl_with_filter') }}"> <i class="mdi mdi-checkbox-blank-circle me-1"></i> Playlists</a></li>
                <li class="nav-item"> <a class="nav-link active_playlistbuilder " href="{{ route('cpls.get_cpl_with_filter') }}"> <i class="mdi mdi-checkbox-blank-circle me-1"></i> CPLS</a></li>
                <li class="nav-item"> <a class="nav-link active_playlistbuilder " href="{{ route('kdms.get_Kdm_with_filter') }}"> <i class="mdi mdi-checkbox-blank-circle  me-1"></i> KDMs</a></li>
                <li class="nav-item"> <a class="nav-link active_playlistbuilder " href="{{ route('content_finder') }}"> <i class="mdi mdi-checkbox-blank-circle  me-1"></i> Content Finder</a></li>

            </ul>
            </div>
        </li>

        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('spls.spl_builder') }}">
            <span class="menu-icon icon-box-danger">
                <i class="mdi mdi-calendar-today "></i>
            </span>
            <span class="menu-title">Playlist Builder</span>
            </a>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('planner.index') }}">
            <span class="menu-icon icon-box-success">
                <i class="mdi mdi-chemical-weapon "></i>
            </span>
            <span class="menu-title">Planner </span>
            </a>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('spls.upload_spl') }}">
            <span class="menu-icon icon-box-success">
                <i class="mdi mdi-upload "></i>
            </span>
            <span class="menu-title">File Uploader </span>
            </a>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('ingester.index') }}">
            <span class="menu-icon icon-box-console">
                <i class="mdi mdi-console "></i>
            </span>
            <span class="menu-title">Ingester</span>
            </a>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('ingester.transfere_content') }}">
            <span class="menu-icon icon-box-console">
                <i class="mdi mdi-file-import"></i>
            </span>
            <span class="menu-title">Transfer Content </span>
            </a>
        </li>

        @endif

        @if (Auth::user()->role == 1 )
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('users.index') }}">
            <span class="menu-icon icon-user-performance">
                <i class="mdi mdi-account-multiple-outline"></i>
            </span>
            <span class="menu-title">Users Management </span>
            </a>
        </li>


        <li class="nav-item menu-items ">
            <a class="nav-link" data-bs-toggle="collapse" href="#showm_configuration" aria-expanded="false" aria-controls="icons">
            <span class="menu-icon icon-box-blue">
                <i class="mdi mdi-contacts"></i>
            </span>
            <span class="menu-title">Configuration</span>
            <i class="menu-arrow"></i>
            </a>
            <div class="collapse " id="showm_configuration">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link " href="{{ route('ingestersources.index') }}"> <i class="mdi mdi-checkbox-blank-circle me-1"></i> Ingest Sources</a></li>
                <li class="nav-item"> <a class="nav-link " href="{{ route('config.edit') }}"> <i class="mdi mdi-checkbox-blank-circle me-1"></i> Noc Settings</a></li>
            </ul>
            </div>
        </li>
        @endif
        @if(Auth::user()->role != 3)
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('queue_list.index') }}">
            <span class="menu-icon icon-box-console">
                <i class="mdi mdi-timer-sand"></i>
            </span>
            <span class="menu-title">Queue List </span>
            </a>
        </li>
        @endif



        <li>
            <br />
            <br /><br />
        </li>
    </ul>
  </nav>
