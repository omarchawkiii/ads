@extends('advertiser.layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-5">
      <!-- -------------------------------------------- -->
      <!-- Welcome Card -->
      <!-- -------------------------------------------- -->
      <div class="card text-bg-primary">
        <div class="card-body">
          <div class="row">
            <div class="col-sm-7">
              <div class="d-flex flex-column h-100">
                <div class="hstack gap-3">
                  <span class="d-flex align-items-center justify-content-center round-48 bg-white rounded flex-shrink-0">
                    <iconify-icon icon="solar:course-up-outline" class="fs-7 text-muted"></iconify-icon>
                  </span>
                  <h5 class="text-white fs-6 mb-0 text-nowrap">Welcome Back
                    <br>David
                  </h5>
                </div>
                <div class="mt-4 mt-sm-auto">
                  <div class="row">
                    <div class="col-6">
                      <span class="opacity-75">Budget</span>
                      <h4 class="mb-0 text-white mt-1 text-nowrap fs-13 fw-bolder">
                        $98,450</h4>
                    </div>
                    <div class="col-6 border-start border-light" style="--bs-border-opacity: .15;">
                      <span class="opacity-75">Expense</span>
                      <h4 class="mb-0 text-white mt-1 text-nowrap fs-13 fw-bolder">
                        $2,440</h4>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-5 text-center text-md-end">
              <img src="../assets/images/backgrounds/welcome-bg.png" alt="welcome" class="img-fluid mb-n7 mt-2" width="180">
            </div>
          </div>


        </div>
      </div>
      <div class="row">
        <!-- -------------------------------------------- -->
        <!-- Customers -->
        <!-- -------------------------------------------- -->
        <div class="col-md-6">
          <div class="card bg-secondary-subtle overflow-hidden shadow-none">
            <div class="card-body p-4">
              <span class="text-dark-light">Customers</span>
              <div class="hstack gap-6">
                <h5 class="mb-0 fs-7">36,358</h5>
                <span class="fs-11 text-dark-light fw-semibold">-12%</span>
              </div>
            </div>
            <div id="customers" style="min-height: 70px;"><div id="apexchartscustomers" class="apexcharts-canvas apexchartscustomers apexcharts-theme-light" style="width: 214px; height: 70px;"><svg id="SvgjsSvg2574" width="214" height="70" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;"><foreignObject x="0" y="0" width="214" height="70"><div class="apexcharts-legend" xmlns="http://www.w3.org/1999/xhtml" style="max-height: 35px;"></div></foreignObject><rect id="SvgjsRect2578" width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe"></rect><g id="SvgjsG2614" class="apexcharts-yaxis" rel="0" transform="translate(-18, 0)"></g><g id="SvgjsG2576" class="apexcharts-inner apexcharts-graphical" transform="translate(0, 0)"><defs id="SvgjsDefs2575"><clipPath id="gridRectMaskpea6ahor"><rect id="SvgjsRect2580" width="220" height="72" x="-3" y="-1" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="forecastMaskpea6ahor"></clipPath><clipPath id="nonForecastMaskpea6ahor"></clipPath><clipPath id="gridRectMarkerMaskpea6ahor"><rect id="SvgjsRect2581" width="218" height="74" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><linearGradient id="SvgjsLinearGradient2586" x1="0" y1="0" x2="0" y2="1"><stop id="SvgjsStop2587" stop-opacity="0.2" stop-color="var(--bs-secondary)" offset="1"></stop><stop id="SvgjsStop2588" stop-opacity="0.1" stop-color="" offset="1"></stop><stop id="SvgjsStop2589" stop-opacity="0.1" stop-color="" offset="1"></stop></linearGradient></defs><line id="SvgjsLine2579" x1="0" y1="0" x2="0" y2="70" stroke="#b6b6b6" stroke-dasharray="3" stroke-linecap="butt" class="apexcharts-xcrosshairs" x="0" y="0" width="1" height="70" fill="#b1b9c4" filter="none" fill-opacity="0.9" stroke-width="1"></line><g id="SvgjsG2592" class="apexcharts-grid"><g id="SvgjsG2593" class="apexcharts-gridlines-horizontal" style="display: none;"><line id="SvgjsLine2596" x1="0" y1="0" x2="214" y2="0" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2597" x1="0" y1="14" x2="214" y2="14" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2598" x1="0" y1="28" x2="214" y2="28" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2599" x1="0" y1="42" x2="214" y2="42" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2600" x1="0" y1="56" x2="214" y2="56" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2601" x1="0" y1="70" x2="214" y2="70" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line></g><g id="SvgjsG2594" class="apexcharts-gridlines-vertical" style="display: none;"></g><line id="SvgjsLine2603" x1="0" y1="70" x2="214" y2="70" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line><line id="SvgjsLine2602" x1="0" y1="1" x2="0" y2="70" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line></g><g id="SvgjsG2595" class="apexcharts-grid-borders" style="display: none;"></g><g id="SvgjsG2582" class="apexcharts-area-series apexcharts-plot-series"><g id="SvgjsG2583" class="apexcharts-series" seriesName="customers" data:longestSeries="true" rel="1" data:realIndex="0"><path id="SvgjsPath2590" d="M 0 70 L 0 42C4.627021565648876, 38.59459861406683, 29.105692501101494, 7.700346013659766, 42.800000000000004, 10.5S71.42810455744207, 60.65891668338675, 85.60000000000001, 59.5S115.21355381172047, 7.274158079589353, 128.4, 3.5S157.30501123611438, 32.727455108710295, 171.20000000000002, 35S207.88841193152143, 19.998896990616235, 214, 17.5 L 214 70 L 0 70M 0 42z" fill="url(#SvgjsLinearGradient2586)" fill-opacity="1" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-area" index="0" clip-path="url(#gridRectMaskpea6ahor)" pathTo="M 0 70 L 0 42C4.627021565648876, 38.59459861406683, 29.105692501101494, 7.700346013659766, 42.800000000000004, 10.5S71.42810455744207, 60.65891668338675, 85.60000000000001, 59.5S115.21355381172047, 7.274158079589353, 128.4, 3.5S157.30501123611438, 32.727455108710295, 171.20000000000002, 35S207.88841193152143, 19.998896990616235, 214, 17.5 L 214 70 L 0 70M 0 42z" pathFrom="M -1 168 L -1 168 L 42.800000000000004 168 L 85.60000000000001 168 L 128.4 168 L 171.20000000000002 168 L 214 168"></path><path id="SvgjsPath2591" d="M 0 42C4.627021565648876, 38.59459861406683, 29.105692501101494, 7.700346013659766, 42.800000000000004, 10.5S71.42810455744207, 60.65891668338675, 85.60000000000001, 59.5S115.21355381172047, 7.274158079589353, 128.4, 3.5S157.30501123611438, 32.727455108710295, 171.20000000000002, 35S207.88841193152143, 19.998896990616235, 214, 17.5" fill="none" fill-opacity="1" stroke="var(--bs-secondary)" stroke-opacity="1" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-area" index="0" clip-path="url(#gridRectMaskpea6ahor)" pathTo="M 0 42C4.627021565648876, 38.59459861406683, 29.105692501101494, 7.700346013659766, 42.800000000000004, 10.5S71.42810455744207, 60.65891668338675, 85.60000000000001, 59.5S115.21355381172047, 7.274158079589353, 128.4, 3.5S157.30501123611438, 32.727455108710295, 171.20000000000002, 35S207.88841193152143, 19.998896990616235, 214, 17.5" pathFrom="M -1 168 L -1 168 L 42.800000000000004 168 L 85.60000000000001 168 L 128.4 168 L 171.20000000000002 168 L 214 168" fill-rule="evenodd"></path><g id="SvgjsG2584" class="apexcharts-series-markers-wrap apexcharts-hidden-element-shown" data:realIndex="0"><g class="apexcharts-series-markers"><circle id="SvgjsCircle2618" r="0" cx="0" cy="0" class="apexcharts-marker w02crqo3z no-pointer-events" stroke="#ffffff" fill="var(--bs-secondary)" fill-opacity="1" stroke-width="2" stroke-opacity="0.9" default-marker-size="0"></circle></g></g></g><g id="SvgjsG2585" class="apexcharts-datalabels" data:realIndex="0"></g></g><line id="SvgjsLine2604" x1="0" y1="0" x2="214" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line><line id="SvgjsLine2605" x1="0" y1="0" x2="214" y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line><g id="SvgjsG2606" class="apexcharts-xaxis" transform="translate(0, 0)"><g id="SvgjsG2607" class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"></g></g><g id="SvgjsG2615" class="apexcharts-yaxis-annotations"></g><g id="SvgjsG2616" class="apexcharts-xaxis-annotations"></g><g id="SvgjsG2617" class="apexcharts-point-annotations"></g></g></svg><div class="apexcharts-tooltip apexcharts-theme-dark"><div class="apexcharts-tooltip-series-group" style="order: 1;"><span class="apexcharts-tooltip-marker" style="background-color: var(--bs-secondary);"></span><div class="apexcharts-tooltip-text" style="font-family: inherit; font-size: 12px;"><div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div><div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div><div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div></div></div></div><div class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-dark"><div class="apexcharts-yaxistooltip-text"></div></div></div></div>
          </div>
        </div>
        <!-- -------------------------------------------- -->
        <!-- Projects -->
        <!-- -------------------------------------------- -->
        <div class="col-md-6">
          <div class="card bg-danger-subtle overflow-hidden shadow-none">
            <div class="card-body p-4">
              <span class="text-dark-light">Projects</span>
              <div class="hstack gap-6 mb-4">
                <h5 class="mb-0 fs-7">78,298</h5>
                <span class="fs-11 text-dark-light fw-semibold">+31.8%</span>
              </div>
              <div class="mx-n1">
                <div id="projects" style="min-height: 46px;"><div id="apexchartsvgltippo" class="apexcharts-canvas apexchartsvgltippo apexcharts-theme-light" style="width: 174px; height: 46px;"><svg id="SvgjsSvg2619" width="174" height="46" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(-10, 0)" style="background: transparent;"><foreignObject x="0" y="0" width="174" height="46"><div class="apexcharts-legend" xmlns="http://www.w3.org/1999/xhtml" style="max-height: 23px;"></div></foreignObject><g id="SvgjsG2674" class="apexcharts-yaxis" rel="0" transform="translate(-18, 0)"></g><g id="SvgjsG2621" class="apexcharts-inner apexcharts-graphical" transform="translate(16.975, 0)"><defs id="SvgjsDefs2620"><linearGradient id="SvgjsLinearGradient2623" x1="0" y1="0" x2="0" y2="1"><stop id="SvgjsStop2624" stop-opacity="0.4" stop-color="rgba(216,227,240,0.4)" offset="0"></stop><stop id="SvgjsStop2625" stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)" offset="1"></stop><stop id="SvgjsStop2626" stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)" offset="1"></stop></linearGradient><clipPath id="gridRectMaskvgltippo"><rect id="SvgjsRect2628" width="178" height="46" x="-14.975000000000001" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="forecastMaskvgltippo"></clipPath><clipPath id="nonForecastMaskvgltippo"></clipPath><clipPath id="gridRectMarkerMaskvgltippo"><rect id="SvgjsRect2629" width="152.05" height="50" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath></defs><rect id="SvgjsRect2627" width="10.178437500000001" height="46" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke-dasharray="3" fill="url(#SvgjsLinearGradient2623)" class="apexcharts-xcrosshairs" y2="46" filter="none" fill-opacity="0.9"></rect><g id="SvgjsG2653" class="apexcharts-grid"><g id="SvgjsG2654" class="apexcharts-gridlines-horizontal" style="display: none;"><line id="SvgjsLine2657" x1="-12.975000000000001" y1="0" x2="161.025" y2="0" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2658" x1="-12.975000000000001" y1="11.5" x2="161.025" y2="11.5" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2659" x1="-12.975000000000001" y1="23" x2="161.025" y2="23" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2660" x1="-12.975000000000001" y1="34.5" x2="161.025" y2="34.5" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2661" x1="-12.975000000000001" y1="46" x2="161.025" y2="46" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line></g><g id="SvgjsG2655" class="apexcharts-gridlines-vertical" style="display: none;"></g><line id="SvgjsLine2663" x1="0" y1="46" x2="148.05" y2="46" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line><line id="SvgjsLine2662" x1="0" y1="1" x2="0" y2="46" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line></g><g id="SvgjsG2656" class="apexcharts-grid-borders" style="display: none;"></g><g id="SvgjsG2630" class="apexcharts-bar-series apexcharts-plot-series"><g id="SvgjsG2631" class="apexcharts-series" rel="1" seriesName="Project" data:realIndex="0"><path id="SvgjsPath2636" d="M -5.089218750000001 42.001 L -5.089218750000001 32.751000000000005 C -5.089218750000001 30.751000000000005 -3.0892187500000006 28.751 -1.0892187500000006 28.751 L 1.0892187500000006 28.751 C 3.0892187500000006 28.751 5.089218750000001 30.751000000000005 5.089218750000001 32.751000000000005 L 5.089218750000001 42.001 C 5.089218750000001 44.001 3.0892187500000006 46.001 1.0892187500000006 46.001 L -1.0892187500000006 46.001 C -3.0892187500000006 46.001 -5.089218750000001 44.001 -5.089218750000001 42.001 Z " fill="var(--bs-white)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskvgltippo)" pathTo="M -5.089218750000001 42.001 L -5.089218750000001 32.751000000000005 C -5.089218750000001 30.751000000000005 -3.0892187500000006 28.751 -1.0892187500000006 28.751 L 1.0892187500000006 28.751 C 3.0892187500000006 28.751 5.089218750000001 30.751000000000005 5.089218750000001 32.751000000000005 L 5.089218750000001 42.001 C 5.089218750000001 44.001 3.0892187500000006 46.001 1.0892187500000006 46.001 L -1.0892187500000006 46.001 C -3.0892187500000006 46.001 -5.089218750000001 44.001 -5.089218750000001 42.001 Z " pathFrom="M -5.089218750000001 46.001 L -5.089218750000001 46.001 L 5.089218750000001 46.001 L 5.089218750000001 46.001 L 5.089218750000001 46.001 L 5.089218750000001 46.001 L 5.089218750000001 46.001 L -5.089218750000001 46.001 Z" cy="28.75" cx="5.089218750000001" j="0" val="3" barHeight="17.25" barWidth="10.178437500000001"></path><path id="SvgjsPath2638" d="M 13.41703125 42.001 L 13.41703125 21.251 C 13.41703125 19.251 15.41703125 17.251 17.41703125 17.251 L 19.595468750000002 17.251 C 21.595468750000002 17.251 23.595468750000002 19.251 23.595468750000002 21.251 L 23.595468750000002 42.001 C 23.595468750000002 44.001 21.595468750000002 46.001 19.595468750000002 46.001 L 17.41703125 46.001 C 15.41703125 46.001 13.41703125 44.001 13.41703125 42.001 Z " fill="var(--bs-white)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskvgltippo)" pathTo="M 13.41703125 42.001 L 13.41703125 21.251 C 13.41703125 19.251 15.41703125 17.251 17.41703125 17.251 L 19.595468750000002 17.251 C 21.595468750000002 17.251 23.595468750000002 19.251 23.595468750000002 21.251 L 23.595468750000002 42.001 C 23.595468750000002 44.001 21.595468750000002 46.001 19.595468750000002 46.001 L 17.41703125 46.001 C 15.41703125 46.001 13.41703125 44.001 13.41703125 42.001 Z " pathFrom="M 13.41703125 46.001 L 13.41703125 46.001 L 23.595468750000002 46.001 L 23.595468750000002 46.001 L 23.595468750000002 46.001 L 23.595468750000002 46.001 L 23.595468750000002 46.001 L 13.41703125 46.001 Z" cy="17.25" cx="23.595468750000002" j="1" val="5" barHeight="28.75" barWidth="10.178437500000001"></path><path id="SvgjsPath2640" d="M 31.923281250000002 42.001 L 31.923281250000002 21.251 C 31.923281250000002 19.251 33.92328125 17.251 35.92328125 17.251 L 38.10171875 17.251 C 40.10171875 17.251 42.10171875 19.251 42.10171875 21.251 L 42.10171875 42.001 C 42.10171875 44.001 40.10171875 46.001 38.10171875 46.001 L 35.92328125 46.001 C 33.92328125 46.001 31.923281250000002 44.001 31.923281250000002 42.001 Z " fill="var(--bs-white)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskvgltippo)" pathTo="M 31.923281250000002 42.001 L 31.923281250000002 21.251 C 31.923281250000002 19.251 33.92328125 17.251 35.92328125 17.251 L 38.10171875 17.251 C 40.10171875 17.251 42.10171875 19.251 42.10171875 21.251 L 42.10171875 42.001 C 42.10171875 44.001 40.10171875 46.001 38.10171875 46.001 L 35.92328125 46.001 C 33.92328125 46.001 31.923281250000002 44.001 31.923281250000002 42.001 Z " pathFrom="M 31.923281250000002 46.001 L 31.923281250000002 46.001 L 42.10171875 46.001 L 42.10171875 46.001 L 42.10171875 46.001 L 42.10171875 46.001 L 42.10171875 46.001 L 31.923281250000002 46.001 Z" cy="17.25" cx="42.10171875" j="2" val="5" barHeight="28.75" barWidth="10.178437500000001"></path><path id="SvgjsPath2642" d="M 50.429531250000004 42.001 L 50.429531250000004 9.751 C 50.429531250000004 7.7509999999999994 52.429531250000004 5.751 54.429531250000004 5.751 L 56.607968750000005 5.751 C 58.607968750000005 5.751 60.607968750000005 7.7509999999999994 60.607968750000005 9.751 L 60.607968750000005 42.001 C 60.607968750000005 44.001 58.607968750000005 46.001 56.607968750000005 46.001 L 54.429531250000004 46.001 C 52.429531250000004 46.001 50.429531250000004 44.001 50.429531250000004 42.001 Z " fill="var(--bs-white)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskvgltippo)" pathTo="M 50.429531250000004 42.001 L 50.429531250000004 9.751 C 50.429531250000004 7.7509999999999994 52.429531250000004 5.751 54.429531250000004 5.751 L 56.607968750000005 5.751 C 58.607968750000005 5.751 60.607968750000005 7.7509999999999994 60.607968750000005 9.751 L 60.607968750000005 42.001 C 60.607968750000005 44.001 58.607968750000005 46.001 56.607968750000005 46.001 L 54.429531250000004 46.001 C 52.429531250000004 46.001 50.429531250000004 44.001 50.429531250000004 42.001 Z " pathFrom="M 50.429531250000004 46.001 L 50.429531250000004 46.001 L 60.607968750000005 46.001 L 60.607968750000005 46.001 L 60.607968750000005 46.001 L 60.607968750000005 46.001 L 60.607968750000005 46.001 L 50.429531250000004 46.001 Z" cy="5.75" cx="60.607968750000005" j="3" val="7" barHeight="40.25" barWidth="10.178437500000001"></path><path id="SvgjsPath2644" d="M 68.93578125 42.001 L 68.93578125 15.501 C 68.93578125 13.501 70.93578125 11.501 72.93578125 11.501 L 75.11421875 11.501 C 77.11421875 11.501 79.11421875 13.501 79.11421875 15.501 L 79.11421875 42.001 C 79.11421875 44.001 77.11421875 46.001 75.11421875 46.001 L 72.93578125 46.001 C 70.93578125 46.001 68.93578125 44.001 68.93578125 42.001 Z " fill="var(--bs-white)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskvgltippo)" pathTo="M 68.93578125 42.001 L 68.93578125 15.501 C 68.93578125 13.501 70.93578125 11.501 72.93578125 11.501 L 75.11421875 11.501 C 77.11421875 11.501 79.11421875 13.501 79.11421875 15.501 L 79.11421875 42.001 C 79.11421875 44.001 77.11421875 46.001 75.11421875 46.001 L 72.93578125 46.001 C 70.93578125 46.001 68.93578125 44.001 68.93578125 42.001 Z " pathFrom="M 68.93578125 46.001 L 68.93578125 46.001 L 79.11421875 46.001 L 79.11421875 46.001 L 79.11421875 46.001 L 79.11421875 46.001 L 79.11421875 46.001 L 68.93578125 46.001 Z" cy="11.5" cx="79.11421875" j="4" val="6" barHeight="34.5" barWidth="10.178437500000001"></path><path id="SvgjsPath2646" d="M 87.44203125 42.001 L 87.44203125 21.251 C 87.44203125 19.251 89.44203125 17.251 91.44203125 17.251 L 93.62046875 17.251 C 95.62046875 17.251 97.62046875 19.251 97.62046875 21.251 L 97.62046875 42.001 C 97.62046875 44.001 95.62046875 46.001 93.62046875 46.001 L 91.44203125 46.001 C 89.44203125 46.001 87.44203125 44.001 87.44203125 42.001 Z " fill="var(--bs-white)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskvgltippo)" pathTo="M 87.44203125 42.001 L 87.44203125 21.251 C 87.44203125 19.251 89.44203125 17.251 91.44203125 17.251 L 93.62046875 17.251 C 95.62046875 17.251 97.62046875 19.251 97.62046875 21.251 L 97.62046875 42.001 C 97.62046875 44.001 95.62046875 46.001 93.62046875 46.001 L 91.44203125 46.001 C 89.44203125 46.001 87.44203125 44.001 87.44203125 42.001 Z " pathFrom="M 87.44203125 46.001 L 87.44203125 46.001 L 97.62046875 46.001 L 97.62046875 46.001 L 97.62046875 46.001 L 97.62046875 46.001 L 97.62046875 46.001 L 87.44203125 46.001 Z" cy="17.25" cx="97.62046875" j="5" val="5" barHeight="28.75" barWidth="10.178437500000001"></path><path id="SvgjsPath2648" d="M 105.94828125000001 42.001 L 105.94828125000001 32.751000000000005 C 105.94828125000001 30.751000000000005 107.94828125000001 28.751 109.94828125000001 28.751 L 112.12671875000001 28.751 C 114.12671875000001 28.751 116.12671875000001 30.751000000000005 116.12671875000001 32.751000000000005 L 116.12671875000001 42.001 C 116.12671875000001 44.001 114.12671875000001 46.001 112.12671875000001 46.001 L 109.94828125000001 46.001 C 107.94828125000001 46.001 105.94828125000001 44.001 105.94828125000001 42.001 Z " fill="var(--bs-white)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskvgltippo)" pathTo="M 105.94828125000001 42.001 L 105.94828125000001 32.751000000000005 C 105.94828125000001 30.751000000000005 107.94828125000001 28.751 109.94828125000001 28.751 L 112.12671875000001 28.751 C 114.12671875000001 28.751 116.12671875000001 30.751000000000005 116.12671875000001 32.751000000000005 L 116.12671875000001 42.001 C 116.12671875000001 44.001 114.12671875000001 46.001 112.12671875000001 46.001 L 109.94828125000001 46.001 C 107.94828125000001 46.001 105.94828125000001 44.001 105.94828125000001 42.001 Z " pathFrom="M 105.94828125000001 46.001 L 105.94828125000001 46.001 L 116.12671875000001 46.001 L 116.12671875000001 46.001 L 116.12671875000001 46.001 L 116.12671875000001 46.001 L 116.12671875000001 46.001 L 105.94828125000001 46.001 Z" cy="28.75" cx="116.12671875000001" j="6" val="3" barHeight="17.25" barWidth="10.178437500000001"></path><path id="SvgjsPath2650" d="M 124.45453125000002 42.001 L 124.45453125000002 21.251 C 124.45453125000002 19.251 126.45453125 17.251 128.45453125 17.251 L 130.63296875000003 17.251 C 132.63296875000003 17.251 134.63296875000003 19.251 134.63296875000003 21.251 L 134.63296875000003 42.001 C 134.63296875000003 44.001 132.63296875000003 46.001 130.63296875000003 46.001 L 128.45453125 46.001 C 126.45453125 46.001 124.45453125000002 44.001 124.45453125000002 42.001 Z " fill="var(--bs-white)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskvgltippo)" pathTo="M 124.45453125000002 42.001 L 124.45453125000002 21.251 C 124.45453125000002 19.251 126.45453125 17.251 128.45453125 17.251 L 130.63296875000003 17.251 C 132.63296875000003 17.251 134.63296875000003 19.251 134.63296875000003 21.251 L 134.63296875000003 42.001 C 134.63296875000003 44.001 132.63296875000003 46.001 130.63296875000003 46.001 L 128.45453125 46.001 C 126.45453125 46.001 124.45453125000002 44.001 124.45453125000002 42.001 Z " pathFrom="M 124.45453125000002 46.001 L 124.45453125000002 46.001 L 134.63296875000003 46.001 L 134.63296875000003 46.001 L 134.63296875000003 46.001 L 134.63296875000003 46.001 L 134.63296875000003 46.001 L 124.45453125000002 46.001 Z" cy="17.25" cx="134.63296875000003" j="7" val="5" barHeight="28.75" barWidth="10.178437500000001"></path><path id="SvgjsPath2652" d="M 142.96078125000003 42.001 L 142.96078125000003 32.751000000000005 C 142.96078125000003 30.751000000000005 144.96078125000003 28.751 146.96078125000003 28.751 L 149.13921875000003 28.751 C 151.13921875000003 28.751 153.13921875000003 30.751000000000005 153.13921875000003 32.751000000000005 L 153.13921875000003 42.001 C 153.13921875000003 44.001 151.13921875000003 46.001 149.13921875000003 46.001 L 146.96078125000003 46.001 C 144.96078125000003 46.001 142.96078125000003 44.001 142.96078125000003 42.001 Z " fill="var(--bs-white)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskvgltippo)" pathTo="M 142.96078125000003 42.001 L 142.96078125000003 32.751000000000005 C 142.96078125000003 30.751000000000005 144.96078125000003 28.751 146.96078125000003 28.751 L 149.13921875000003 28.751 C 151.13921875000003 28.751 153.13921875000003 30.751000000000005 153.13921875000003 32.751000000000005 L 153.13921875000003 42.001 C 153.13921875000003 44.001 151.13921875000003 46.001 149.13921875000003 46.001 L 146.96078125000003 46.001 C 144.96078125000003 46.001 142.96078125000003 44.001 142.96078125000003 42.001 Z " pathFrom="M 142.96078125000003 46.001 L 142.96078125000003 46.001 L 153.13921875000003 46.001 L 153.13921875000003 46.001 L 153.13921875000003 46.001 L 153.13921875000003 46.001 L 153.13921875000003 46.001 L 142.96078125000003 46.001 Z" cy="28.75" cx="153.13921875000003" j="8" val="3" barHeight="17.25" barWidth="10.178437500000001"></path><g id="SvgjsG2633" class="apexcharts-bar-goals-markers"><g id="SvgjsG2635" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskvgltippo)"></g><g id="SvgjsG2637" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskvgltippo)"></g><g id="SvgjsG2639" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskvgltippo)"></g><g id="SvgjsG2641" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskvgltippo)"></g><g id="SvgjsG2643" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskvgltippo)"></g><g id="SvgjsG2645" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskvgltippo)"></g><g id="SvgjsG2647" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskvgltippo)"></g><g id="SvgjsG2649" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskvgltippo)"></g><g id="SvgjsG2651" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskvgltippo)"></g></g><g id="SvgjsG2634" class="apexcharts-bar-shadows apexcharts-hidden-element-shown"></g></g><g id="SvgjsG2632" class="apexcharts-datalabels apexcharts-hidden-element-shown" data:realIndex="0"></g></g><line id="SvgjsLine2664" x1="-12.975000000000001" y1="0" x2="161.025" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line><line id="SvgjsLine2665" x1="-12.975000000000001" y1="0" x2="161.025" y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line><g id="SvgjsG2666" class="apexcharts-xaxis" transform="translate(0, 0)"><g id="SvgjsG2667" class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"></g></g><g id="SvgjsG2675" class="apexcharts-yaxis-annotations"></g><g id="SvgjsG2676" class="apexcharts-xaxis-annotations"></g><g id="SvgjsG2677" class="apexcharts-point-annotations"></g></g></svg><div class="apexcharts-tooltip apexcharts-theme-dark"><div class="apexcharts-tooltip-title" style="font-family: inherit; font-size: 12px;"></div><div class="apexcharts-tooltip-series-group" style="order: 1;"><span class="apexcharts-tooltip-marker" style="background-color: var(--bs-white);"></span><div class="apexcharts-tooltip-text" style="font-family: inherit; font-size: 12px;"><div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div><div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div><div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div></div></div></div><div class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-dark"><div class="apexcharts-yaxistooltip-text"></div></div></div></div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-7">
      <!-- -------------------------------------------- -->
      <!-- Revenue Forecast -->
      <!-- -------------------------------------------- -->
      <div class="card">
        <div class="card-body pb-4">
          <div class="d-md-flex align-items-center justify-content-between mb-4">
            <div class="hstack align-items-center gap-3">
              <span class="d-flex align-items-center justify-content-center round-48 bg-primary-subtle rounded flex-shrink-0">
                <iconify-icon icon="solar:layers-linear" class="fs-7 text-primary"></iconify-icon>
              </span>
              <div>
                <h5 class="card-title">Revenue Forecast</h5>
                <p class="card-subtitle mb-0">Overview of Profit</p>
              </div>
            </div>

            <div class="hstack gap-9 mt-4 mt-md-0">
              <div class="d-flex align-items-center gap-2">
                <span class="d-block flex-shrink-0 round-8 bg-primary rounded-circle"></span>
                <span class="text-nowrap text-muted">2024</span>
              </div>
              <div class="d-flex align-items-center gap-2">
                <span class="d-block flex-shrink-0 round-8 bg-danger rounded-circle"></span>
                <span class="text-nowrap text-muted">2023</span>
              </div>
              <div class="d-flex align-items-center gap-2">
                <span class="d-block flex-shrink-0 round-8 bg-secondary rounded-circle"></span>
                <span class="text-nowrap text-muted">2022</span>
              </div>
            </div>
          </div>
          <div style="height: 285px;" class="me-n7">
            <div id="revenue-forecast" style="min-height: 315px;"><div id="apexcharts7xlnr4qpg" class="apexcharts-canvas apexcharts7xlnr4qpg apexcharts-theme-light" style="width: 623px; height: 300px;"><svg id="SvgjsSvg2681" width="623" height="300" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg apexcharts-zoomable" xmlns:data="ApexChartsNS" transform="translate(-10, 0)" style="background: transparent;"><foreignObject x="0" y="0" width="623" height="300"><div class="apexcharts-legend" xmlns="http://www.w3.org/1999/xhtml" style="max-height: 150px;"></div></foreignObject><rect id="SvgjsRect2686" width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe"></rect><g id="SvgjsG2765" class="apexcharts-yaxis" rel="0" transform="translate(14.55999755859375, 0)"><g id="SvgjsG2766" class="apexcharts-yaxis-texts-g"><text id="SvgjsText2768" font-family="inherit" x="20" y="31.4" text-anchor="end" dominant-baseline="auto" font-size="11px" font-weight="400" fill="#adb0bb" class="apexcharts-text apexcharts-yaxis-label " style="font-family: inherit;"><tspan id="SvgjsTspan2769">120</tspan><title>120</title></text><text id="SvgjsText2771" font-family="inherit" x="20" y="88.27350000000001" text-anchor="end" dominant-baseline="auto" font-size="11px" font-weight="400" fill="#adb0bb" class="apexcharts-text apexcharts-yaxis-label " style="font-family: inherit;"><tspan id="SvgjsTspan2772">90</tspan><title>90</title></text><text id="SvgjsText2774" font-family="inherit" x="20" y="145.14700000000002" text-anchor="end" dominant-baseline="auto" font-size="11px" font-weight="400" fill="#adb0bb" class="apexcharts-text apexcharts-yaxis-label " style="font-family: inherit;"><tspan id="SvgjsTspan2775">60</tspan><title>60</title></text><text id="SvgjsText2777" font-family="inherit" x="20" y="202.02050000000003" text-anchor="end" dominant-baseline="auto" font-size="11px" font-weight="400" fill="#adb0bb" class="apexcharts-text apexcharts-yaxis-label " style="font-family: inherit;"><tspan id="SvgjsTspan2778">30</tspan><title>30</title></text><text id="SvgjsText2780" font-family="inherit" x="20" y="258.894" text-anchor="end" dominant-baseline="auto" font-size="11px" font-weight="400" fill="#adb0bb" class="apexcharts-text apexcharts-yaxis-label " style="font-family: inherit;"><tspan id="SvgjsTspan2781">0</tspan><title>0</title></text></g></g><g id="SvgjsG2683" class="apexcharts-inner apexcharts-graphical" transform="translate(44.55999755859375, 30)"><defs id="SvgjsDefs2682"><clipPath id="gridRectMask7xlnr4qpg"><rect id="SvgjsRect2688" width="562.0181274414062" height="229.49400000000003" x="-3" y="-1" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="forecastMask7xlnr4qpg"></clipPath><clipPath id="nonForecastMask7xlnr4qpg"></clipPath><clipPath id="gridRectMarkerMask7xlnr4qpg"><rect id="SvgjsRect2689" width="560.0181274414062" height="231.49400000000003" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><linearGradient id="SvgjsLinearGradient2694" x1="0" y1="0" x2="0" y2="1"><stop id="SvgjsStop2695" stop-opacity="0.1" stop-color="var(--bs-danger)" offset="0"></stop><stop id="SvgjsStop2696" stop-opacity="0.01" stop-color="" offset="1"></stop><stop id="SvgjsStop2697" stop-opacity="0.01" stop-color="" offset="1"></stop></linearGradient><linearGradient id="SvgjsLinearGradient2703" x1="0" y1="0" x2="0" y2="1"><stop id="SvgjsStop2704" stop-opacity="0.1" stop-color="var(--bs-secondary)" offset="0"></stop><stop id="SvgjsStop2705" stop-opacity="0.01" stop-color="" offset="1"></stop><stop id="SvgjsStop2706" stop-opacity="0.01" stop-color="" offset="1"></stop></linearGradient><linearGradient id="SvgjsLinearGradient2712" x1="0" y1="0" x2="0" y2="1"><stop id="SvgjsStop2713" stop-opacity="0.1" stop-color="var(--bs-primary)" offset="0"></stop><stop id="SvgjsStop2714" stop-opacity="0.01" stop-color="" offset="1"></stop><stop id="SvgjsStop2715" stop-opacity="0.01" stop-color="" offset="1"></stop></linearGradient></defs><line id="SvgjsLine2687" x1="0" y1="0" x2="0" y2="227.49400000000003" stroke="#b6b6b6" stroke-dasharray="3" stroke-linecap="butt" class="apexcharts-xcrosshairs" x="0" y="0" width="1" height="227.49400000000003" fill="#b1b9c4" filter="none" fill-opacity="0.9" stroke-width="1"></line><g id="SvgjsG2718" class="apexcharts-grid"><g id="SvgjsG2719" class="apexcharts-gridlines-horizontal"><line id="SvgjsLine2731" x1="0" y1="56.87350000000001" x2="556.0181274414062" y2="56.87350000000001" stroke="rgba(0,0,0,0.05)" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2732" x1="0" y1="113.74700000000001" x2="556.0181274414062" y2="113.74700000000001" stroke="rgba(0,0,0,0.05)" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2733" x1="0" y1="170.62050000000002" x2="556.0181274414062" y2="170.62050000000002" stroke="rgba(0,0,0,0.05)" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line></g><g id="SvgjsG2720" class="apexcharts-gridlines-vertical"><line id="SvgjsLine2722" x1="0" y1="0" x2="0" y2="227.49400000000003" stroke="rgba(0,0,0,0.05)" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2723" x1="79.43116106305804" y1="0" x2="79.43116106305804" y2="227.49400000000003" stroke="rgba(0,0,0,0.05)" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2724" x1="158.86232212611608" y1="0" x2="158.86232212611608" y2="227.49400000000003" stroke="rgba(0,0,0,0.05)" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2725" x1="238.29348318917414" y1="0" x2="238.29348318917414" y2="227.49400000000003" stroke="rgba(0,0,0,0.05)" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2726" x1="317.72464425223217" y1="0" x2="317.72464425223217" y2="227.49400000000003" stroke="rgba(0,0,0,0.05)" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2727" x1="397.1558053152902" y1="0" x2="397.1558053152902" y2="227.49400000000003" stroke="rgba(0,0,0,0.05)" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2728" x1="476.5869663783482" y1="0" x2="476.5869663783482" y2="227.49400000000003" stroke="rgba(0,0,0,0.05)" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2729" x1="556.0181274414062" y1="0" x2="556.0181274414062" y2="227.49400000000003" stroke="rgba(0,0,0,0.05)" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line></g><line id="SvgjsLine2736" x1="0" y1="227.49400000000003" x2="556.0181274414062" y2="227.49400000000003" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line><line id="SvgjsLine2735" x1="0" y1="1" x2="0" y2="227.49400000000003" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line></g><g id="SvgjsG2721" class="apexcharts-grid-borders"><line id="SvgjsLine2730" x1="0" y1="0" x2="556.0181274414062" y2="0" stroke="rgba(0,0,0,0.05)" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2734" x1="0" y1="227.49400000000003" x2="556.0181274414062" y2="227.49400000000003" stroke="rgba(0,0,0,0.05)" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line></g><g id="SvgjsG2690" class="apexcharts-area-series apexcharts-plot-series"><g id="SvgjsG2691" class="apexcharts-series" seriesName="2023" data:longestSeries="true" rel="1" data:realIndex="0"><path id="SvgjsPath2698" d="M 0 227.49400000000003 L 0 132.70483333333334C12.525057513530298, 129.71547815264526, 54.38104603599746, 107.76828963862387, 79.43116106305804, 113.74700000000003S132.4791978972111, 169.0462850909823, 158.86232212611608, 170.62050000000002S217.74277968380676, 134.26179748632993, 238.2934831891741, 123.22591666666669S291.3415200233272, 86.88446490901772, 317.72464425223217, 85.31025000000002S372.8427418307695, 121.00049785892568, 397.1558053152902, 113.74700000000003S459.0834462038954, 50.44836090553669, 476.5869663783482, 37.91566666666668S545.2362812410736, 5.146605955546165, 556.0181274414062, 2.842170943040401e-14 L 556.0181274414062 227.49400000000003 L 0 227.49400000000003M 0 132.70483333333334z" fill="url(#SvgjsLinearGradient2694)" fill-opacity="1" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-area" index="0" clip-path="url(#gridRectMask7xlnr4qpg)" pathTo="M 0 227.49400000000003 L 0 132.70483333333334C12.525057513530298, 129.71547815264526, 54.38104603599746, 107.76828963862387, 79.43116106305804, 113.74700000000003S132.4791978972111, 169.0462850909823, 158.86232212611608, 170.62050000000002S217.74277968380676, 134.26179748632993, 238.2934831891741, 123.22591666666669S291.3415200233272, 86.88446490901772, 317.72464425223217, 85.31025000000002S372.8427418307695, 121.00049785892568, 397.1558053152902, 113.74700000000003S459.0834462038954, 50.44836090553669, 476.5869663783482, 37.91566666666668S545.2362812410736, 5.146605955546165, 556.0181274414062, 2.842170943040401e-14 L 556.0181274414062 227.49400000000003 L 0 227.49400000000003M 0 132.70483333333334z" pathFrom="M -1 227.49400000000003 L -1 227.49400000000003 L 79.43116106305804 227.49400000000003 L 158.86232212611608 227.49400000000003 L 238.2934831891741 227.49400000000003 L 317.72464425223217 227.49400000000003 L 397.1558053152902 227.49400000000003 L 476.5869663783482 227.49400000000003 L 556.0181274414062 227.49400000000003"></path><path id="SvgjsPath2699" d="M 0 132.70483333333334C12.525057513530298, 129.71547815264526, 54.38104603599746, 107.76828963862387, 79.43116106305804, 113.74700000000003S132.4791978972111, 169.0462850909823, 158.86232212611608, 170.62050000000002S217.74277968380676, 134.26179748632993, 238.2934831891741, 123.22591666666669S291.3415200233272, 86.88446490901772, 317.72464425223217, 85.31025000000002S372.8427418307695, 121.00049785892568, 397.1558053152902, 113.74700000000003S459.0834462038954, 50.44836090553669, 476.5869663783482, 37.91566666666668S545.2362812410736, 5.146605955546165, 556.0181274414062, 2.842170943040401e-14" fill="none" fill-opacity="1" stroke="var(--bs-danger)" stroke-opacity="1" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-area" index="0" clip-path="url(#gridRectMask7xlnr4qpg)" pathTo="M 0 132.70483333333334C12.525057513530298, 129.71547815264526, 54.38104603599746, 107.76828963862387, 79.43116106305804, 113.74700000000003S132.4791978972111, 169.0462850909823, 158.86232212611608, 170.62050000000002S217.74277968380676, 134.26179748632993, 238.2934831891741, 123.22591666666669S291.3415200233272, 86.88446490901772, 317.72464425223217, 85.31025000000002S372.8427418307695, 121.00049785892568, 397.1558053152902, 113.74700000000003S459.0834462038954, 50.44836090553669, 476.5869663783482, 37.91566666666668S545.2362812410736, 5.146605955546165, 556.0181274414062, 2.842170943040401e-14" pathFrom="M -1 227.49400000000003 L -1 227.49400000000003 L 79.43116106305804 227.49400000000003 L 158.86232212611608 227.49400000000003 L 238.2934831891741 227.49400000000003 L 317.72464425223217 227.49400000000003 L 397.1558053152902 227.49400000000003 L 476.5869663783482 227.49400000000003 L 556.0181274414062 227.49400000000003" fill-rule="evenodd"></path><g id="SvgjsG2692" class="apexcharts-series-markers-wrap apexcharts-hidden-element-shown" data:realIndex="0"><g class="apexcharts-series-markers"><circle id="SvgjsCircle2785" r="0" cx="0" cy="0" class="apexcharts-marker w7n99x1 no-pointer-events" stroke="var(--bs-danger)" fill="var(--bs-danger)" fill-opacity="1" stroke-width="2" stroke-opacity="0.9" default-marker-size="0"></circle></g></g></g><g id="SvgjsG2700" class="apexcharts-series" seriesName="2022" data:longestSeries="true" rel="2" data:realIndex="1"><path id="SvgjsPath2707" d="M 0 227.49400000000003 L 0 161.14158333333336C12.525057513530298, 158.15222815264528, 53.04803683415305, 143.75796490901774, 79.43116106305804, 142.18375000000003S132.4791978972111, 153.2368815756844, 158.86232212611608, 151.6626666666667S211.91035896026912, 131.13061842431563, 238.2934831891741, 132.70483333333334S291.3415200233272, 162.71579824235107, 317.72464425223217, 161.14158333333336S370.7726810863852, 124.80013157568439, 397.1558053152902, 123.22591666666669S450.4816739674842, 148.54739190535352, 476.5869663783482, 151.6626666666667S542.9654812359743, 143.74138738065662, 556.0181274414062, 142.18375000000003 L 556.0181274414062 227.49400000000003 L 0 227.49400000000003M 0 161.14158333333336z" fill="url(#SvgjsLinearGradient2703)" fill-opacity="1" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-area" index="1" clip-path="url(#gridRectMask7xlnr4qpg)" pathTo="M 0 227.49400000000003 L 0 161.14158333333336C12.525057513530298, 158.15222815264528, 53.04803683415305, 143.75796490901774, 79.43116106305804, 142.18375000000003S132.4791978972111, 153.2368815756844, 158.86232212611608, 151.6626666666667S211.91035896026912, 131.13061842431563, 238.2934831891741, 132.70483333333334S291.3415200233272, 162.71579824235107, 317.72464425223217, 161.14158333333336S370.7726810863852, 124.80013157568439, 397.1558053152902, 123.22591666666669S450.4816739674842, 148.54739190535352, 476.5869663783482, 151.6626666666667S542.9654812359743, 143.74138738065662, 556.0181274414062, 142.18375000000003 L 556.0181274414062 227.49400000000003 L 0 227.49400000000003M 0 161.14158333333336z" pathFrom="M -1 227.49400000000003 L -1 227.49400000000003 L 79.43116106305804 227.49400000000003 L 158.86232212611608 227.49400000000003 L 238.2934831891741 227.49400000000003 L 317.72464425223217 227.49400000000003 L 397.1558053152902 227.49400000000003 L 476.5869663783482 227.49400000000003 L 556.0181274414062 227.49400000000003"></path><path id="SvgjsPath2708" d="M 0 161.14158333333336C12.525057513530298, 158.15222815264528, 53.04803683415305, 143.75796490901774, 79.43116106305804, 142.18375000000003S132.4791978972111, 153.2368815756844, 158.86232212611608, 151.6626666666667S211.91035896026912, 131.13061842431563, 238.2934831891741, 132.70483333333334S291.3415200233272, 162.71579824235107, 317.72464425223217, 161.14158333333336S370.7726810863852, 124.80013157568439, 397.1558053152902, 123.22591666666669S450.4816739674842, 148.54739190535352, 476.5869663783482, 151.6626666666667S542.9654812359743, 143.74138738065662, 556.0181274414062, 142.18375000000003" fill="none" fill-opacity="1" stroke="var(--bs-secondary)" stroke-opacity="1" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-area" index="1" clip-path="url(#gridRectMask7xlnr4qpg)" pathTo="M 0 161.14158333333336C12.525057513530298, 158.15222815264528, 53.04803683415305, 143.75796490901774, 79.43116106305804, 142.18375000000003S132.4791978972111, 153.2368815756844, 158.86232212611608, 151.6626666666667S211.91035896026912, 131.13061842431563, 238.2934831891741, 132.70483333333334S291.3415200233272, 162.71579824235107, 317.72464425223217, 161.14158333333336S370.7726810863852, 124.80013157568439, 397.1558053152902, 123.22591666666669S450.4816739674842, 148.54739190535352, 476.5869663783482, 151.6626666666667S542.9654812359743, 143.74138738065662, 556.0181274414062, 142.18375000000003" pathFrom="M -1 227.49400000000003 L -1 227.49400000000003 L 79.43116106305804 227.49400000000003 L 158.86232212611608 227.49400000000003 L 238.2934831891741 227.49400000000003 L 317.72464425223217 227.49400000000003 L 397.1558053152902 227.49400000000003 L 476.5869663783482 227.49400000000003 L 556.0181274414062 227.49400000000003" fill-rule="evenodd"></path><g id="SvgjsG2701" class="apexcharts-series-markers-wrap apexcharts-hidden-element-shown" data:realIndex="1"><g class="apexcharts-series-markers"><circle id="SvgjsCircle2786" r="0" cx="0" cy="0" class="apexcharts-marker w4x0hharm no-pointer-events" stroke="var(--bs-secondary)" fill="var(--bs-secondary)" fill-opacity="1" stroke-width="2" stroke-opacity="0.9" default-marker-size="0"></circle></g></g></g><g id="SvgjsG2709" class="apexcharts-series" seriesName="2024" data:longestSeries="true" rel="3" data:realIndex="2"><path id="SvgjsPath2716" d="M 0 227.49400000000003 L 0 37.91566666666668C9.762774314901355, 43.74086945408147, 53.76388271090346, 80.75122280773688, 79.43116106305804, 85.31025000000002S134.7180542371707, 68.32644124534698, 158.86232212611608, 75.83133333333336S220.78996301472128, 139.1299724277967, 238.2934831891741, 151.6626666666667S291.24759056454616, 189.57833333333338, 317.72464425223217, 189.57833333333338S372.1056902882296, 145.68395630529054, 397.1558053152902, 151.6626666666667S450.93194793517284, 222.90168801594882, 476.5869663783482, 227.49400000000003S546.2553531265049, 185.92461945408147, 556.0181274414062, 180.09941666666668 L 556.0181274414062 227.49400000000003 L 0 227.49400000000003M 0 37.91566666666668z" fill="url(#SvgjsLinearGradient2712)" fill-opacity="1" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-area" index="2" clip-path="url(#gridRectMask7xlnr4qpg)" pathTo="M 0 227.49400000000003 L 0 37.91566666666668C9.762774314901355, 43.74086945408147, 53.76388271090346, 80.75122280773688, 79.43116106305804, 85.31025000000002S134.7180542371707, 68.32644124534698, 158.86232212611608, 75.83133333333336S220.78996301472128, 139.1299724277967, 238.2934831891741, 151.6626666666667S291.24759056454616, 189.57833333333338, 317.72464425223217, 189.57833333333338S372.1056902882296, 145.68395630529054, 397.1558053152902, 151.6626666666667S450.93194793517284, 222.90168801594882, 476.5869663783482, 227.49400000000003S546.2553531265049, 185.92461945408147, 556.0181274414062, 180.09941666666668 L 556.0181274414062 227.49400000000003 L 0 227.49400000000003M 0 37.91566666666668z" pathFrom="M -1 227.49400000000003 L -1 227.49400000000003 L 79.43116106305804 227.49400000000003 L 158.86232212611608 227.49400000000003 L 238.2934831891741 227.49400000000003 L 317.72464425223217 227.49400000000003 L 397.1558053152902 227.49400000000003 L 476.5869663783482 227.49400000000003 L 556.0181274414062 227.49400000000003"></path><path id="SvgjsPath2717" d="M 0 37.91566666666668C9.762774314901355, 43.74086945408147, 53.76388271090346, 80.75122280773688, 79.43116106305804, 85.31025000000002S134.7180542371707, 68.32644124534698, 158.86232212611608, 75.83133333333336S220.78996301472128, 139.1299724277967, 238.2934831891741, 151.6626666666667S291.24759056454616, 189.57833333333338, 317.72464425223217, 189.57833333333338S372.1056902882296, 145.68395630529054, 397.1558053152902, 151.6626666666667S450.93194793517284, 222.90168801594882, 476.5869663783482, 227.49400000000003S546.2553531265049, 185.92461945408147, 556.0181274414062, 180.09941666666668" fill="none" fill-opacity="1" stroke="var(--bs-primary)" stroke-opacity="1" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-area" index="2" clip-path="url(#gridRectMask7xlnr4qpg)" pathTo="M 0 37.91566666666668C9.762774314901355, 43.74086945408147, 53.76388271090346, 80.75122280773688, 79.43116106305804, 85.31025000000002S134.7180542371707, 68.32644124534698, 158.86232212611608, 75.83133333333336S220.78996301472128, 139.1299724277967, 238.2934831891741, 151.6626666666667S291.24759056454616, 189.57833333333338, 317.72464425223217, 189.57833333333338S372.1056902882296, 145.68395630529054, 397.1558053152902, 151.6626666666667S450.93194793517284, 222.90168801594882, 476.5869663783482, 227.49400000000003S546.2553531265049, 185.92461945408147, 556.0181274414062, 180.09941666666668" pathFrom="M -1 227.49400000000003 L -1 227.49400000000003 L 79.43116106305804 227.49400000000003 L 158.86232212611608 227.49400000000003 L 238.2934831891741 227.49400000000003 L 317.72464425223217 227.49400000000003 L 397.1558053152902 227.49400000000003 L 476.5869663783482 227.49400000000003 L 556.0181274414062 227.49400000000003" fill-rule="evenodd"></path><g id="SvgjsG2710" class="apexcharts-series-markers-wrap apexcharts-hidden-element-shown" data:realIndex="2"><g class="apexcharts-series-markers"><circle id="SvgjsCircle2787" r="0" cx="0" cy="0" class="apexcharts-marker whhk0c1mw no-pointer-events" stroke="var(--bs-primary)" fill="var(--bs-primary)" fill-opacity="1" stroke-width="2" stroke-opacity="0.9" default-marker-size="0"></circle></g></g></g><g id="SvgjsG2693" class="apexcharts-datalabels" data:realIndex="0"></g><g id="SvgjsG2702" class="apexcharts-datalabels" data:realIndex="1"></g><g id="SvgjsG2711" class="apexcharts-datalabels" data:realIndex="2"></g></g><line id="SvgjsLine2737" x1="0" y1="0" x2="556.0181274414062" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line><line id="SvgjsLine2738" x1="0" y1="0" x2="556.0181274414062" y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line><g id="SvgjsG2739" class="apexcharts-xaxis" transform="translate(0, 0)"><g id="SvgjsG2740" class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"><text id="SvgjsText2742" font-family="inherit" x="0" y="256.494" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#adb0bb" class="apexcharts-text apexcharts-xaxis-label " style="font-family: inherit;"><tspan id="SvgjsTspan2743">Jan</tspan><title>Jan</title></text><text id="SvgjsText2745" font-family="inherit" x="79.43116106305806" y="256.494" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#adb0bb" class="apexcharts-text apexcharts-xaxis-label " style="font-family: inherit;"><tspan id="SvgjsTspan2746">Feb</tspan><title>Feb</title></text><text id="SvgjsText2748" font-family="inherit" x="158.86232212611608" y="256.494" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#adb0bb" class="apexcharts-text apexcharts-xaxis-label " style="font-family: inherit;"><tspan id="SvgjsTspan2749">Mar</tspan><title>Mar</title></text><text id="SvgjsText2751" font-family="inherit" x="238.2934831891741" y="256.494" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#adb0bb" class="apexcharts-text apexcharts-xaxis-label " style="font-family: inherit;"><tspan id="SvgjsTspan2752">Apr</tspan><title>Apr</title></text><text id="SvgjsText2754" font-family="inherit" x="317.7246442522321" y="256.494" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#adb0bb" class="apexcharts-text apexcharts-xaxis-label " style="font-family: inherit;"><tspan id="SvgjsTspan2755">May</tspan><title>May</title></text><text id="SvgjsText2757" font-family="inherit" x="397.15580531529014" y="256.494" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#adb0bb" class="apexcharts-text apexcharts-xaxis-label " style="font-family: inherit;"><tspan id="SvgjsTspan2758">Jun</tspan><title>Jun</title></text><text id="SvgjsText2760" font-family="inherit" x="476.5869663783482" y="256.494" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#adb0bb" class="apexcharts-text apexcharts-xaxis-label " style="font-family: inherit;"><tspan id="SvgjsTspan2761">July</tspan><title>July</title></text><text id="SvgjsText2763" font-family="inherit" x="556.0181274414064" y="256.494" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#adb0bb" class="apexcharts-text apexcharts-xaxis-label " style="font-family: inherit;"><tspan id="SvgjsTspan2764">Aug</tspan><title>Aug</title></text></g></g><g id="SvgjsG2782" class="apexcharts-yaxis-annotations"></g><g id="SvgjsG2783" class="apexcharts-xaxis-annotations"></g><g id="SvgjsG2784" class="apexcharts-point-annotations"></g><rect id="SvgjsRect2788" width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe" class="apexcharts-zoom-rect"></rect><rect id="SvgjsRect2789" width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe" class="apexcharts-selection-rect"></rect></g></svg><div class="apexcharts-tooltip apexcharts-theme-dark"><div class="apexcharts-tooltip-title" style="font-family: inherit; font-size: 12px;"></div><div class="apexcharts-tooltip-series-group" style="order: 1;"><span class="apexcharts-tooltip-marker" style="background-color: var(--bs-danger);"></span><div class="apexcharts-tooltip-text" style="font-family: inherit; font-size: 12px;"><div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div><div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div><div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div></div></div><div class="apexcharts-tooltip-series-group" style="order: 2;"><span class="apexcharts-tooltip-marker" style="background-color: var(--bs-secondary);"></span><div class="apexcharts-tooltip-text" style="font-family: inherit; font-size: 12px;"><div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div><div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div><div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div></div></div><div class="apexcharts-tooltip-series-group" style="order: 3;"><span class="apexcharts-tooltip-marker" style="background-color: var(--bs-primary);"></span><div class="apexcharts-tooltip-text" style="font-family: inherit; font-size: 12px;"><div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div><div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div><div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div></div></div></div><div class="apexcharts-xaxistooltip apexcharts-xaxistooltip-bottom apexcharts-theme-dark"><div class="apexcharts-xaxistooltip-text" style="font-family: inherit; font-size: 12px;"></div></div><div class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-dark"><div class="apexcharts-yaxistooltip-text"></div></div></div></div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-5">
      <!-- -------------------------------------------- -->
      <!-- Your Performance -->
      <!-- -------------------------------------------- -->
      <div class="card">
        <div class="card-body">
          <h5 class="card-title fw-semibold">Your Performance</h5>
          <p class="card-subtitle mb-0 lh-base">Last check on 25 february</p>

          <div class="row mt-4">
            <div class="col-md-6">
              <div class="vstack gap-9 mt-2">
                <div class="hstack align-items-center gap-3">
                  <div class="d-flex align-items-center justify-content-center round-48 rounded bg-primary-subtle flex-shrink-0">
                    <iconify-icon icon="solar:shop-2-linear" class="fs-7 text-primary"></iconify-icon>
                  </div>
                  <div>
                    <h6 class="mb-0 text-nowrap">64 new orders</h6>
                    <span>Processing</span>
                  </div>

                </div>
                <div class="hstack align-items-center gap-3">
                  <div class="d-flex align-items-center justify-content-center round-48 rounded bg-danger-subtle">
                    <iconify-icon icon="solar:filters-outline" class="fs-7 text-danger"></iconify-icon>
                  </div>
                  <div>
                    <h6 class="mb-0">4 orders</h6>
                    <span>On hold</span>
                  </div>

                </div>
                <div class="hstack align-items-center gap-3">
                  <div class="d-flex align-items-center justify-content-center round-48 rounded bg-secondary-subtle">
                    <iconify-icon icon="solar:pills-3-linear" class="fs-7 text-secondary"></iconify-icon>
                  </div>
                  <div>
                    <h6 class="mb-0">12 orders</h6>
                    <span>Delivered</span>
                  </div>

                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="text-center mt-sm-n7">
                <div id="your-preformance" style="min-height: 113.7px;"><div id="apexchartsycrsouhh" class="apexcharts-canvas apexchartsycrsouhh apexcharts-theme-light" style="width: 184px; height: 113.7px;"><svg id="SvgjsSvg2790" width="184" height="113.69999999999999" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;"><foreignObject x="0" y="0" width="184" height="113.69999999999999"><div class="apexcharts-legend" xmlns="http://www.w3.org/1999/xhtml"></div></foreignObject><g id="SvgjsG2792" class="apexcharts-inner apexcharts-graphical" transform="translate(2.5, 10)"><defs id="SvgjsDefs2791"><clipPath id="gridRectMaskycrsouhh"><rect id="SvgjsRect2793" width="187" height="285" x="-3" y="-1" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="forecastMaskycrsouhh"></clipPath><clipPath id="nonForecastMaskycrsouhh"></clipPath><clipPath id="gridRectMarkerMaskycrsouhh"><rect id="SvgjsRect2794" width="185" height="287" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath></defs><g id="SvgjsG2795" class="apexcharts-pie"><g id="SvgjsG2796" transform="translate(0, 0) scale(1)"><circle id="SvgjsCircle2797" r="74.06341463414634" cx="90.5" cy="90.5" fill="transparent"></circle><g id="SvgjsG2798" class="apexcharts-slices"><g id="SvgjsG2799" class="apexcharts-series apexcharts-pie-series" seriesName="245" rel="1" data:realIndex="0"><path id="SvgjsPath2800" d="M 8.207317073170728 90.49999999999999 A 82.29268292682927 82.29268292682927 0 0 1 23.92382099948604 42.12957460402915 L 30.58143889953744 46.96661714362624 A 74.06341463414634 74.06341463414634 0 0 0 16.43658536585366 90.49999999999999 L 8.207317073170728 90.49999999999999 z" fill="var(--bs-danger)" fill-opacity="1" stroke-opacity="1" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-pie-area apexcharts-donut-slice-0" index="0" j="0" data:angle="36" data:startAngle="-90" data:strokeWidth="2" data:value="20" data:pathOrig="M 8.207317073170728 90.49999999999999 A 82.29268292682927 82.29268292682927 0 0 1 23.92382099948604 42.12957460402915 L 30.58143889953744 46.96661714362624 A 74.06341463414634 74.06341463414634 0 0 0 16.43658536585366 90.49999999999999 L 8.207317073170728 90.49999999999999 z" stroke="var(--bs-card-bg)"></path></g><g id="SvgjsG2801" class="apexcharts-series apexcharts-pie-series" seriesName="45" rel="2" data:realIndex="1"><path id="SvgjsPath2802" d="M 23.92382099948604 42.12957460402915 A 82.29268292682927 82.29268292682927 0 0 1 65.07016246290067 12.235007659028085 L 67.6131462166106 20.061506893125284 A 74.06341463414634 74.06341463414634 0 0 0 30.58143889953744 46.96661714362624 L 23.92382099948604 42.12957460402915 z" fill="var(--bs-warning)" fill-opacity="1" stroke-opacity="1" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-pie-area apexcharts-donut-slice-1" index="0" j="1" data:angle="36" data:startAngle="-54" data:strokeWidth="2" data:value="20" data:pathOrig="M 23.92382099948604 42.12957460402915 A 82.29268292682927 82.29268292682927 0 0 1 65.07016246290067 12.235007659028085 L 67.6131462166106 20.061506893125284 A 74.06341463414634 74.06341463414634 0 0 0 30.58143889953744 46.96661714362624 L 23.92382099948604 42.12957460402915 z" stroke="var(--bs-card-bg)"></path></g><g id="SvgjsG2803" class="apexcharts-series apexcharts-pie-series" seriesName="14" rel="3" data:realIndex="2"><path id="SvgjsPath2804" d="M 65.07016246290067 12.235007659028085 A 82.29268292682927 82.29268292682927 0 0 1 115.92983753709933 12.2350076590281 L 113.3868537833894 20.061506893125284 A 74.06341463414634 74.06341463414634 0 0 0 67.6131462166106 20.061506893125284 L 65.07016246290067 12.235007659028085 z" fill="var(--bs-warning-bg-subtle)" fill-opacity="1" stroke-opacity="1" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-pie-area apexcharts-donut-slice-2" index="0" j="2" data:angle="36" data:startAngle="-18" data:strokeWidth="2" data:value="20" data:pathOrig="M 65.07016246290067 12.235007659028085 A 82.29268292682927 82.29268292682927 0 0 1 115.92983753709933 12.2350076590281 L 113.3868537833894 20.061506893125284 A 74.06341463414634 74.06341463414634 0 0 0 67.6131462166106 20.061506893125284 L 65.07016246290067 12.235007659028085 z" stroke="var(--bs-card-bg)"></path></g><g id="SvgjsG2805" class="apexcharts-series apexcharts-pie-series" seriesName="78" rel="4" data:realIndex="3"><path id="SvgjsPath2806" d="M 115.92983753709933 12.2350076590281 A 82.29268292682927 82.29268292682927 0 0 1 157.07617900051397 42.12957460402916 L 150.41856110046257 46.966617143626245 A 74.06341463414634 74.06341463414634 0 0 0 113.3868537833894 20.061506893125284 L 115.92983753709933 12.2350076590281 z" fill="var(--bs-secondary-bg-subtle)" fill-opacity="1" stroke-opacity="1" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-pie-area apexcharts-donut-slice-3" index="0" j="3" data:angle="36" data:startAngle="18" data:strokeWidth="2" data:value="20" data:pathOrig="M 115.92983753709933 12.2350076590281 A 82.29268292682927 82.29268292682927 0 0 1 157.07617900051397 42.12957460402916 L 150.41856110046257 46.966617143626245 A 74.06341463414634 74.06341463414634 0 0 0 113.3868537833894 20.061506893125284 L 115.92983753709933 12.2350076590281 z" stroke="var(--bs-card-bg)"></path></g><g id="SvgjsG2807" class="apexcharts-series apexcharts-pie-series" seriesName="95" rel="5" data:realIndex="4"><path id="SvgjsPath2808" d="M 157.07617900051397 42.12957460402916 A 82.29268292682927 82.29268292682927 0 0 1 172.79268167344003 90.48563721739919 L 164.56341350609603 90.48707349565926 A 74.06341463414634 74.06341463414634 0 0 0 150.41856110046257 46.966617143626245 L 157.07617900051397 42.12957460402916 z" fill="var(--bs-secondary)" fill-opacity="1" stroke-opacity="1" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-pie-area apexcharts-donut-slice-4" index="0" j="4" data:angle="36" data:startAngle="54" data:strokeWidth="2" data:value="20" data:pathOrig="M 157.07617900051397 42.12957460402916 A 82.29268292682927 82.29268292682927 0 0 1 172.79268167344003 90.48563721739919 L 164.56341350609603 90.48707349565926 A 74.06341463414634 74.06341463414634 0 0 0 150.41856110046257 46.966617143626245 L 157.07617900051397 42.12957460402916 z" stroke="var(--bs-card-bg)"></path></g></g></g></g><line id="SvgjsLine2809" x1="0" y1="0" x2="181" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line><line id="SvgjsLine2810" x1="0" y1="0" x2="181" y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line></g></svg><div class="apexcharts-tooltip apexcharts-theme-dark" style="left: 116.883px; top: 0.59375px;"><div class="apexcharts-tooltip-series-group apexcharts-active" style="order: 1; display: flex;"><span class="apexcharts-tooltip-marker" style="background-color: var(--bs-secondary-bg-subtle);"></span><div class="apexcharts-tooltip-text" style="font-family: inherit; font-size: 12px;"><div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label">78: </span><span class="apexcharts-tooltip-text-y-value">20</span></div><div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div><div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div></div></div><div class="apexcharts-tooltip-series-group" style="order: 2; display: none;"><span class="apexcharts-tooltip-marker" style="background-color: var(--bs-secondary-bg-subtle);"></span><div class="apexcharts-tooltip-text" style="font-family: inherit; font-size: 12px;"><div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label">78: </span><span class="apexcharts-tooltip-text-y-value">20</span></div><div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div><div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div></div></div><div class="apexcharts-tooltip-series-group" style="order: 3; display: none;"><span class="apexcharts-tooltip-marker" style="background-color: var(--bs-secondary-bg-subtle);"></span><div class="apexcharts-tooltip-text" style="font-family: inherit; font-size: 12px;"><div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label">78: </span><span class="apexcharts-tooltip-text-y-value">20</span></div><div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div><div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div></div></div><div class="apexcharts-tooltip-series-group" style="order: 4; display: none;"><span class="apexcharts-tooltip-marker" style="background-color: var(--bs-secondary-bg-subtle);"></span><div class="apexcharts-tooltip-text" style="font-family: inherit; font-size: 12px;"><div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label">78: </span><span class="apexcharts-tooltip-text-y-value">20</span></div><div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div><div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div></div></div><div class="apexcharts-tooltip-series-group" style="order: 5; display: none;"><span class="apexcharts-tooltip-marker" style="background-color: var(--bs-secondary-bg-subtle);"></span><div class="apexcharts-tooltip-text" style="font-family: inherit; font-size: 12px;"><div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label">78: </span><span class="apexcharts-tooltip-text-y-value">20</span></div><div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div><div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div></div></div></div></div></div>
                <h2 class="fs-8">275</h2>
                <p class="mb-0">
                  Learn insigs how to manage all aspects of your
                  startup.
                </p>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
    <div class="col-lg-7">
      <div class="row">
        <div class="col-md-6">
          <!-- -------------------------------------------- -->
          <!-- Customers -->
          <!-- -------------------------------------------- -->
          <div class="card">
            <div class="card-body">
              <div class="d-flex align-items-start justify-content-between">
                <div>
                  <h5 class="card-title fw-semibold">Customers</h5>
                  <p class="card-subtitle mb-0">Last 7 days</p>
                </div>
                <span class="fs-11 text-success fw-semibold lh-lg">+26.5%</span>
              </div>
              <div class="py-4 my-1">
                <div id="customers-area" style="min-height: 100px;"><div id="apexchartsy16akh6z" class="apexcharts-canvas apexchartsy16akh6z apexcharts-theme-light" style="width: 251px; height: 100px;"><svg id="SvgjsSvg2813" width="251" height="100" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;"><foreignObject x="0" y="0" width="251" height="100"><div class="apexcharts-legend" xmlns="http://www.w3.org/1999/xhtml" style="max-height: 50px;"></div></foreignObject><rect id="SvgjsRect2817" width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe"></rect><g id="SvgjsG2852" class="apexcharts-yaxis" rel="0" transform="translate(-18, 0)"></g><g id="SvgjsG2815" class="apexcharts-inner apexcharts-graphical" transform="translate(0, 0)"><defs id="SvgjsDefs2814"><clipPath id="gridRectMasky16akh6z"><rect id="SvgjsRect2819" width="257" height="102" x="-3" y="-1" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="forecastMasky16akh6z"></clipPath><clipPath id="nonForecastMasky16akh6z"></clipPath><clipPath id="gridRectMarkerMasky16akh6z"><rect id="SvgjsRect2820" width="255" height="104" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath></defs><line id="SvgjsLine2818" x1="0" y1="0" x2="0" y2="100" stroke="#b6b6b6" stroke-dasharray="3" stroke-linecap="butt" class="apexcharts-xcrosshairs" x="0" y="0" width="1" height="100" fill="#b1b9c4" filter="none" fill-opacity="0.9" stroke-width="1"></line><g id="SvgjsG2830" class="apexcharts-grid"><g id="SvgjsG2831" class="apexcharts-gridlines-horizontal" style="display: none;"><line id="SvgjsLine2834" x1="0" y1="0" x2="251" y2="0" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2835" x1="0" y1="25" x2="251" y2="25" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2836" x1="0" y1="50" x2="251" y2="50" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2837" x1="0" y1="75" x2="251" y2="75" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2838" x1="0" y1="100" x2="251" y2="100" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line></g><g id="SvgjsG2832" class="apexcharts-gridlines-vertical" style="display: none;"></g><line id="SvgjsLine2840" x1="0" y1="100" x2="251" y2="100" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line><line id="SvgjsLine2839" x1="0" y1="1" x2="0" y2="100" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line></g><g id="SvgjsG2833" class="apexcharts-grid-borders" style="display: none;"></g><g id="SvgjsG2821" class="apexcharts-line-series apexcharts-plot-series"><g id="SvgjsG2822" class="apexcharts-series" seriesName="Aprilx07x" data:longestSeries="true" rel="1" data:realIndex="0"><path id="SvgjsPath2825" d="M 0 100C2.1571752734042913, 96.77712857559119, 31.22023534958147, 43.44608328272951, 41.833333333333336, 37.5S69.74164855743527, 52.60489225189624, 83.66666666666667, 53.125S111.5749818907686, 40.10489225189624, 125.5, 40.625S154.05572259361034, 59.22556017573474, 167.33333333333334, 56.25S198.89176994681023, 28.015376525810616, 209.16666666666666, 21.875S244.8813680205081, 8.535345609869982, 251, 6.25" fill="none" fill-opacity="1" stroke="var(--bs-primary)" stroke-opacity="1" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-line" index="0" clip-path="url(#gridRectMasky16akh6z)" pathTo="M 0 100C2.1571752734042913, 96.77712857559119, 31.22023534958147, 43.44608328272951, 41.833333333333336, 37.5S69.74164855743527, 52.60489225189624, 83.66666666666667, 53.125S111.5749818907686, 40.10489225189624, 125.5, 40.625S154.05572259361034, 59.22556017573474, 167.33333333333334, 56.25S198.89176994681023, 28.015376525810616, 209.16666666666666, 21.875S244.8813680205081, 8.535345609869982, 251, 6.25" pathFrom="M -1 100 L -1 100 L 41.833333333333336 100 L 83.66666666666667 100 L 125.5 100 L 167.33333333333334 100 L 209.16666666666666 100 L 251 100" fill-rule="evenodd"></path><g id="SvgjsG2823" class="apexcharts-series-markers-wrap apexcharts-hidden-element-shown" data:realIndex="0"><g class="apexcharts-series-markers"><circle id="SvgjsCircle2856" r="0" cx="0" cy="0" class="apexcharts-marker wlp463bfk no-pointer-events" stroke="transparent" fill="var(--bs-primary)" fill-opacity="1" stroke-width="2" stroke-opacity="0.9" default-marker-size="0"></circle></g></g></g><g id="SvgjsG2826" class="apexcharts-series" seriesName="LastxWeek" data:longestSeries="true" rel="2" data:realIndex="1"><path id="SvgjsPath2829" d="M 0 100C5.137448359928214, 96.9298117370947, 32.5594133871767, 81.58134259127152, 41.833333333333336, 75S70.19216798141971, 43.14140289191614, 83.66666666666667, 40.625S112.44778280315862, 62.78755678702277, 125.5, 59.375S153.56179882643252, 20.29312513150034, 167.33333333333334, 18.75S195.24164855743527, 49.47989225189623, 209.16666666666666, 50S246.19819986215504, 25.103301885503345, 251, 21.875" fill="none" fill-opacity="1" stroke="var(--bs-primary-bg-subtle)" stroke-opacity="1" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-line" index="1" clip-path="url(#gridRectMasky16akh6z)" pathTo="M 0 100C5.137448359928214, 96.9298117370947, 32.5594133871767, 81.58134259127152, 41.833333333333336, 75S70.19216798141971, 43.14140289191614, 83.66666666666667, 40.625S112.44778280315862, 62.78755678702277, 125.5, 59.375S153.56179882643252, 20.29312513150034, 167.33333333333334, 18.75S195.24164855743527, 49.47989225189623, 209.16666666666666, 50S246.19819986215504, 25.103301885503345, 251, 21.875" pathFrom="M -1 100 L -1 100 L 41.833333333333336 100 L 83.66666666666667 100 L 125.5 100 L 167.33333333333334 100 L 209.16666666666666 100 L 251 100" fill-rule="evenodd"></path><g id="SvgjsG2827" class="apexcharts-series-markers-wrap apexcharts-hidden-element-shown" data:realIndex="1"><g class="apexcharts-series-markers"><circle id="SvgjsCircle2857" r="0" cx="0" cy="0" class="apexcharts-marker wv9hgubxy no-pointer-events" stroke="transparent" fill="var(--bs-primary-bg-subtle)" fill-opacity="1" stroke-width="2" stroke-opacity="0.9" default-marker-size="0"></circle></g></g></g><g id="SvgjsG2824" class="apexcharts-datalabels" data:realIndex="0"></g><g id="SvgjsG2828" class="apexcharts-datalabels" data:realIndex="1"></g></g><line id="SvgjsLine2841" x1="0" y1="0" x2="251" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line><line id="SvgjsLine2842" x1="0" y1="0" x2="251" y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line><g id="SvgjsG2843" class="apexcharts-xaxis" transform="translate(0, 0)"><g id="SvgjsG2844" class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"></g></g><g id="SvgjsG2853" class="apexcharts-yaxis-annotations"></g><g id="SvgjsG2854" class="apexcharts-xaxis-annotations"></g><g id="SvgjsG2855" class="apexcharts-point-annotations"></g></g></svg><div class="apexcharts-tooltip apexcharts-theme-dark"><div class="apexcharts-tooltip-series-group" style="order: 1;"><span class="apexcharts-tooltip-marker" style="background-color: var(--bs-primary);"></span><div class="apexcharts-tooltip-text" style="font-family: inherit; font-size: 12px;"><div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div><div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div><div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div></div></div><div class="apexcharts-tooltip-series-group" style="order: 2;"><span class="apexcharts-tooltip-marker" style="background-color: var(--bs-primary-bg-subtle);"></span><div class="apexcharts-tooltip-text" style="font-family: inherit; font-size: 12px;"><div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div><div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div><div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div></div></div></div><div class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-dark"><div class="apexcharts-yaxistooltip-text"></div></div></div></div>
              </div>
              <div class="d-flex flex-column align-items-center gap-2 w-100 mt-3">
                <div class="d-flex align-items-center gap-2 w-100">
                  <span class="d-block flex-shrink-0 round-8 bg-primary rounded-circle"></span>
                  <h6 class="fs-3 fw-normal text-muted mb-0">April 07 - April 14</h6>
                  <h6 class="fs-3 fw-normal mb-0 ms-auto text-muted">6,380</h6>
                </div>
                <div class="d-flex align-items-center gap-2 w-100">
                  <span class="d-block flex-shrink-0 round-8 bg-light rounded-circle"></span>
                  <h6 class="fs-3 fw-normal text-muted mb-0">Last Week</h6>
                  <h6 class="fs-3 fw-normal mb-0 ms-auto text-muted">4,298</h6>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <!-- -------------------------------------------- -->
          <!-- Sales Overview -->
          <!-- -------------------------------------------- -->
          <div class="card">
            <div class="card-body">
              <h5 class="card-title fw-semibold">Sales Overview</h5>
              <p class="card-subtitle mb-1">Last 7 days</p>

              <div class="position-relative labels-chart">
                <span class="fs-11 label-1">0%</span>
                <span class="fs-11 label-2">25%</span>
                <span class="fs-11 label-3">50%</span>
                <span class="fs-11 label-4">75%</span>
                <div id="sales-overview" style="min-height: 210.75px;"><div id="apexchartsqg7ubm9z" class="apexcharts-canvas apexchartsqg7ubm9z apexcharts-theme-light" style="width: 251px; height: 210.75px;"><svg id="SvgjsSvg2858" width="251" height="210.75" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;"><foreignObject x="0" y="0" width="251" height="210.75"><div class="apexcharts-legend" xmlns="http://www.w3.org/1999/xhtml"></div></foreignObject><g id="SvgjsG2860" class="apexcharts-inner apexcharts-graphical" transform="translate(23.5, 0)"><defs id="SvgjsDefs2859"><clipPath id="gridRectMaskqg7ubm9z"><rect id="SvgjsRect2861" width="211" height="229" x="-2.5" y="-0.5" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="forecastMaskqg7ubm9z"></clipPath><clipPath id="nonForecastMaskqg7ubm9z"></clipPath><clipPath id="gridRectMarkerMaskqg7ubm9z"><rect id="SvgjsRect2862" width="210" height="232" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath></defs><g id="SvgjsG2863" class="apexcharts-radialbar"><g id="SvgjsG2864"><g id="SvgjsG2865" class="apexcharts-tracks"><g id="SvgjsG2866" class="apexcharts-radialbar-track apexcharts-track" rel="1"><path id="apexcharts-radialbarTrack-0" d="M 103 26.496951219512184 A 76.50304878048782 76.50304878048782 0 1 1 26.496951219512184 103.00000000000001" fill="none" fill-opacity="1" stroke="rgba(242,242,242,0.85)" stroke-opacity="1" stroke-linecap="round" stroke-width="9.043475609756099" stroke-dasharray="0" class="apexcharts-radialbar-area" data:pathOrig="M 103 26.496951219512184 A 76.50304878048782 76.50304878048782 0 1 1 26.496951219512184 103.00000000000001"></path></g><g id="SvgjsG2868" class="apexcharts-radialbar-track apexcharts-track" rel="2"><path id="apexcharts-radialbarTrack-1" d="M 103 40.820121951219505 A 62.179878048780495 62.179878048780495 0 1 1 40.820121951219505 103.00000000000001" fill="none" fill-opacity="1" stroke="rgba(242,242,242,0.85)" stroke-opacity="1" stroke-linecap="round" stroke-width="9.043475609756099" stroke-dasharray="0" class="apexcharts-radialbar-area" data:pathOrig="M 103 40.820121951219505 A 62.179878048780495 62.179878048780495 0 1 1 40.820121951219505 103.00000000000001"></path></g><g id="SvgjsG2870" class="apexcharts-radialbar-track apexcharts-track" rel="3"><path id="apexcharts-radialbarTrack-2" d="M 103 55.14329268292683 A 47.85670731707317 47.85670731707317 0 1 1 55.14329268292683 103" fill="none" fill-opacity="1" stroke="rgba(242,242,242,0.85)" stroke-opacity="1" stroke-linecap="round" stroke-width="9.043475609756099" stroke-dasharray="0" class="apexcharts-radialbar-area" data:pathOrig="M 103 55.14329268292683 A 47.85670731707317 47.85670731707317 0 1 1 55.14329268292683 103"></path></g></g><g id="SvgjsG2872"><g id="SvgjsG2874" class="apexcharts-series apexcharts-radial-series" seriesName="36x" rel="1" data:realIndex="0"><path id="SvgjsPath2875" d="M 103 26.496951219512184 A 76.50304878048782 76.50304878048782 0 0 1 157.0958245741282 157.09582457412816" fill="none" fill-opacity="0.85" stroke="var(--bs-primary)" stroke-opacity="1" stroke-linecap="round" stroke-width="9.323170731707318" stroke-dasharray="0" class="apexcharts-radialbar-area apexcharts-radialbar-slice-0" data:angle="135" data:value="50" index="0" j="0" data:pathOrig="M 103 26.496951219512184 A 76.50304878048782 76.50304878048782 0 0 1 157.0958245741282 157.09582457412816"></path></g><g id="SvgjsG2876" class="apexcharts-series apexcharts-radial-series" seriesName="10x" rel="2" data:realIndex="1"><path id="SvgjsPath2877" d="M 103 40.820121951219505 A 62.179878048780495 62.179878048780495 0 1 1 66.45158469358236 153.30457804962518" fill="none" fill-opacity="0.85" stroke="var(--bs-secondary)" stroke-opacity="1" stroke-linecap="round" stroke-width="9.323170731707318" stroke-dasharray="0" class="apexcharts-radialbar-area apexcharts-radialbar-slice-1" data:angle="216" data:value="80" index="0" j="1" data:pathOrig="M 103 40.820121951219505 A 62.179878048780495 62.179878048780495 0 1 1 66.45158469358236 153.30457804962518"></path></g><g id="SvgjsG2878" class="apexcharts-series apexcharts-radial-series" seriesName="36x" rel="3" data:realIndex="2"><path id="SvgjsPath2879" d="M 103 55.14329268292683 A 47.85670731707317 47.85670731707317 0 0 1 150.26751183634718 95.51356159226675" fill="none" fill-opacity="0.85" stroke="var(--bs-danger)" stroke-opacity="1" stroke-linecap="round" stroke-width="9.323170731707318" stroke-dasharray="0" class="apexcharts-radialbar-area apexcharts-radialbar-slice-2" data:angle="81" data:value="30" index="0" j="2" data:pathOrig="M 103 55.14329268292683 A 47.85670731707317 47.85670731707317 0 0 1 150.26751183634718 95.51356159226675"></path></g><circle id="SvgjsCircle2873" r="42.33496951219514" cx="103" cy="103" class="apexcharts-radialbar-hollow" fill="transparent"></circle></g></g></g><line id="SvgjsLine2880" x1="0" y1="0" x2="206" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line><line id="SvgjsLine2881" x1="0" y1="0" x2="206" y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line></g></svg></div></div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

    <div class="col-lg-8">
      <!-- -------------------------------------------- -->
      <!-- Revenue by Product -->
      <!-- -------------------------------------------- -->
      <div class="card mb-lg-0">
        <div class="card-body">
          <div class="d-flex flex-wrap gap-3 mb-9 justify-content-between align-items-center">
            <h5 class="card-title fw-semibold mb-0">Revenue by Product</h5>
            <select class="form-select w-auto fw-semibold">
              <option value="1">Sep 2024</option>
              <option value="2">Oct 2024</option>
              <option value="3">Nov 2024</option>
            </select>
          </div>

          <div class="table-responsive">
            <ul class="nav nav-tabs theme-tab gap-3 flex-nowrap" role="tablist">
              <li class="nav-item" role="presentation">
                <a class="nav-link active" data-bs-toggle="tab" href="#app" role="tab" aria-selected="true">
                  <div class="hstack gap-2">
                    <iconify-icon icon="solar:widget-linear" class="fs-4"></iconify-icon>
                    <span>App</span>
                  </div>

                </a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#mobile" role="tab" aria-selected="false" tabindex="-1">
                  <div class="hstack gap-2">
                    <iconify-icon icon="solar:smartphone-line-duotone" class="fs-4"></iconify-icon>
                    <span>Mobile</span>
                  </div>
                </a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#saas" role="tab" aria-selected="false" tabindex="-1">
                  <div class="hstack gap-2">
                    <iconify-icon icon="solar:calculator-linear" class="fs-4"></iconify-icon>
                    <span>SaaS</span>
                  </div>
                </a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#other" role="tab" aria-selected="false" tabindex="-1">
                  <div class="hstack gap-2">
                    <iconify-icon icon="solar:folder-open-outline" class="fs-4"></iconify-icon>
                    <span>Others</span>
                  </div>
                </a>
              </li>
            </ul>
          </div>
          <div class="tab-content mb-n3">
            <div class="tab-pane active" id="app" role="tabpanel">
              <div class="table-responsive" data-simplebar="init"><div class="simplebar-wrapper" style="margin: 0px;"><div class="simplebar-height-auto-observer-wrapper"><div class="simplebar-height-auto-observer"></div></div><div class="simplebar-mask"><div class="simplebar-offset" style="right: 0px; bottom: 0px;"><div class="simplebar-content-wrapper" tabindex="0" role="region" aria-label="scrollable content" style="height: auto; overflow: hidden;"><div class="simplebar-content" style="padding: 0px;">
                <table class="table text-nowrap align-middle table-custom mb-0 last-items-borderless">
                  <thead>
                    <tr>
                      <th scope="col" class="fw-normal ps-0">Assigned
                      </th>
                      <th scope="col" class="fw-normal">Progress</th>
                      <th scope="col" class="fw-normal">Priority</th>
                      <th scope="col" class="fw-normal">Budget</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="ps-0">
                        <div class="d-flex align-items-center gap-6">
                          <img src="../assets/images/products/dash-prd-1.jpg" alt="prd1" width="48" class="rounded">
                          <div>
                            <h6 class="mb-0">Minecraf App</h6>
                            <span>Jason Roy</span>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span>73.2%</span>
                      </td>
                      <td>
                        <span class="badge bg-success-subtle text-success">Low</span>
                      </td>
                      <td>
                        <span class="text-dark-light">$3.5k</span>
                      </td>
                    </tr>
                    <tr>
                      <td class="ps-0">
                        <div class="d-flex align-items-center gap-6">
                          <img src="../assets/images/products/dash-prd-2.jpg" alt="prd1" width="48" class="rounded">
                          <div>
                            <h6 class="mb-0">Web App Project</h6>
                            <span>Mathew Flintoff</span>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span>73.2%</span>
                      </td>
                      <td>
                        <span class="badge bg-warning-subtle text-warning">Medium</span>
                      </td>
                      <td>
                        <span class="text-dark-light">$3.5k</span>
                      </td>
                    </tr>
                    <tr>
                      <td class="ps-0">
                        <div class="d-flex align-items-center gap-6">
                          <img src="../assets/images/products/dash-prd-3.jpg" alt="prd1" width="48" class="rounded">
                          <div>
                            <h6 class="mb-0">Modernize Dashboard</h6>
                            <span>Anil Kumar</span>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span>73.2%</span>
                      </td>
                      <td>
                        <span class="badge bg-secondary-subtle text-secondary">Very
                          High</span>
                      </td>
                      <td>
                        <span class="text-dark-light">$3.5k</span>
                      </td>
                    </tr>
                    <tr>
                      <td class="ps-0">
                        <div class="d-flex align-items-center gap-6">
                          <img src="../assets/images/products/dash-prd-4.jpg" alt="prd1" width="48" class="rounded">
                          <div>
                            <h6 class="mb-0">Dashboard Co</h6>
                            <span>George Cruize</span>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span>73.2%</span>
                      </td>
                      <td>
                        <span class="badge bg-danger-subtle text-danger">High</span>
                      </td>
                      <td>
                        <span class="text-dark-light">$3.5k</span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div></div></div></div><div class="simplebar-placeholder" style="width: 690px; height: 357px;"></div></div><div class="simplebar-track simplebar-horizontal" style="visibility: hidden;"><div class="simplebar-scrollbar" style="width: 0px; display: none;"></div></div><div class="simplebar-track simplebar-vertical" style="visibility: hidden;"><div class="simplebar-scrollbar" style="height: 0px; display: none;"></div></div></div>
            </div>
            <div class="tab-pane" id="mobile" role="tabpanel">
              <div class="table-responsive" data-simplebar="init"><div class="simplebar-wrapper" style="margin: 0px;"><div class="simplebar-height-auto-observer-wrapper"><div class="simplebar-height-auto-observer"></div></div><div class="simplebar-mask"><div class="simplebar-offset" style="right: 0px; bottom: 0px;"><div class="simplebar-content-wrapper" tabindex="0" role="region" aria-label="scrollable content" style="height: auto; overflow: hidden;"><div class="simplebar-content" style="padding: 0px;">
                <table class="table text-nowrap align-middle table-custom mb-0 last-items-borderless">
                  <thead>
                    <tr>
                      <th scope="col" class="fw-normal ps-0">Assigned
                      </th>
                      <th scope="col" class="fw-normal">Progress</th>
                      <th scope="col" class="fw-normal">Priority</th>
                      <th scope="col" class="fw-normal">Budget</th>
                    </tr>
                  </thead>
                  <tbody>

                    <tr>
                      <td class="ps-0">
                        <div class="d-flex align-items-center gap-6">
                          <img src="../assets/images/products/dash-prd-2.jpg" alt="prd1" width="48" class="rounded">
                          <div>
                            <h6 class="mb-0">Web App Project</h6>
                            <span>Mathew Flintoff</span>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span>73.2%</span>
                      </td>
                      <td>
                        <span class="badge bg-warning-subtle text-warning">Medium</span>
                      </td>
                      <td>
                        <span class="text-dark-light">$3.5k</span>
                      </td>
                    </tr>
                    <tr>
                      <td class="ps-0">
                        <div class="d-flex align-items-center gap-6">
                          <img src="../assets/images/products/dash-prd-3.jpg" alt="prd1" width="48" class="rounded">
                          <div>
                            <h6 class="mb-0">Modernize Dashboard</h6>
                            <span>Anil Kumar</span>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span>73.2%</span>
                      </td>
                      <td>
                        <span class="badge bg-secondary-subtle text-secondary">Very
                          High</span>
                      </td>
                      <td>
                        <span class="text-dark-light">$3.5k</span>
                      </td>
                    </tr>
                    <tr>
                      <td class="ps-0">
                        <div class="d-flex align-items-center gap-6">
                          <img src="../assets/images/products/dash-prd-1.jpg" alt="prd1" width="48" class="rounded">
                          <div>
                            <h6 class="mb-0">Minecraf App</h6>
                            <span>Jason Roy</span>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span>73.2%</span>
                      </td>
                      <td>
                        <span class="badge bg-success-subtle text-success">Low</span>
                      </td>
                      <td>
                        <span class="text-dark-light">$3.5k</span>
                      </td>
                    </tr>
                    <tr>
                      <td class="ps-0">
                        <div class="d-flex align-items-center gap-6">
                          <img src="../assets/images/products/dash-prd-4.jpg" alt="prd1" width="48" class="rounded">
                          <div>
                            <h6 class="mb-0">Dashboard Co</h6>
                            <span>George Cruize</span>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span>73.2%</span>
                      </td>
                      <td>
                        <span class="badge bg-danger-subtle text-danger">High</span>
                      </td>
                      <td>
                        <span class="text-dark-light">$3.5k</span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div></div></div></div><div class="simplebar-placeholder" style="width: 0px; height: 0px;"></div></div><div class="simplebar-track simplebar-horizontal" style="visibility: hidden;"><div class="simplebar-scrollbar" style="width: 0px; display: none;"></div></div><div class="simplebar-track simplebar-vertical" style="visibility: hidden;"><div class="simplebar-scrollbar" style="height: 0px; display: none;"></div></div></div>
            </div>
            <div class="tab-pane" id="saas" role="tabpanel">
              <div class="table-responsive" data-simplebar="init"><div class="simplebar-wrapper" style="margin: 0px;"><div class="simplebar-height-auto-observer-wrapper"><div class="simplebar-height-auto-observer"></div></div><div class="simplebar-mask"><div class="simplebar-offset" style="right: 0px; bottom: 0px;"><div class="simplebar-content-wrapper" tabindex="0" role="region" aria-label="scrollable content" style="height: auto; overflow: hidden;"><div class="simplebar-content" style="padding: 0px;">
                <table class="table text-nowrap align-middle table-custom mb-0 last-items-borderless">
                  <thead>
                    <tr>
                      <th scope="col" class="fw-normal ps-0">Assigned
                      </th>
                      <th scope="col" class="fw-normal">Progress</th>
                      <th scope="col" class="fw-normal">Priority</th>
                      <th scope="col" class="fw-normal">Budget</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="ps-0">
                        <div class="d-flex align-items-center gap-6">
                          <img src="../assets/images/products/dash-prd-2.jpg" alt="prd1" width="48" class="rounded">
                          <div>
                            <h6 class="mb-0">Web App Project</h6>
                            <span>Mathew Flintoff</span>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span>73.2%</span>
                      </td>
                      <td>
                        <span class="badge bg-warning-subtle text-warning">Medium</span>
                      </td>
                      <td>
                        <span class="text-dark-light">$3.5k</span>
                      </td>
                    </tr>
                    <tr>
                      <td class="ps-0">
                        <div class="d-flex align-items-center gap-6">
                          <img src="../assets/images/products/dash-prd-1.jpg" alt="prd1" width="48" class="rounded">
                          <div>
                            <h6 class="mb-0">Minecraf App</h6>
                            <span>Jason Roy</span>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span>73.2%</span>
                      </td>
                      <td>
                        <span class="badge bg-success-subtle text-success">Low</span>
                      </td>
                      <td>
                        <span class="text-dark-light">$3.5k</span>
                      </td>
                    </tr>

                    <tr>
                      <td class="ps-0">
                        <div class="d-flex align-items-center gap-6">
                          <img src="../assets/images/products/dash-prd-3.jpg" alt="prd1" width="48" class="rounded">
                          <div>
                            <h6 class="mb-0">Modernize Dashboard</h6>
                            <span>Anil Kumar</span>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span>73.2%</span>
                      </td>
                      <td>
                        <span class="badge bg-secondary-subtle text-secondary">Very
                          High</span>
                      </td>
                      <td>
                        <span class="text-dark-light">$3.5k</span>
                      </td>
                    </tr>
                    <tr>
                      <td class="ps-0">
                        <div class="d-flex align-items-center gap-6">
                          <img src="../assets/images/products/dash-prd-4.jpg" alt="prd1" width="48" class="rounded">
                          <div>
                            <h6 class="mb-0">Dashboard Co</h6>
                            <span>George Cruize</span>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span>73.2%</span>
                      </td>
                      <td>
                        <span class="badge bg-danger-subtle text-danger">High</span>
                      </td>
                      <td>
                        <span class="text-dark-light">$3.5k</span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div></div></div></div><div class="simplebar-placeholder" style="width: 0px; height: 0px;"></div></div><div class="simplebar-track simplebar-horizontal" style="visibility: hidden;"><div class="simplebar-scrollbar" style="width: 0px; display: none;"></div></div><div class="simplebar-track simplebar-vertical" style="visibility: hidden;"><div class="simplebar-scrollbar" style="height: 0px; display: none;"></div></div></div>
            </div>

            <div class="tab-pane" id="other" role="tabpanel">
              <div class="table-responsive" data-simplebar="init"><div class="simplebar-wrapper" style="margin: 0px;"><div class="simplebar-height-auto-observer-wrapper"><div class="simplebar-height-auto-observer"></div></div><div class="simplebar-mask"><div class="simplebar-offset" style="right: 0px; bottom: 0px;"><div class="simplebar-content-wrapper" tabindex="0" role="region" aria-label="scrollable content" style="height: auto; overflow: hidden;"><div class="simplebar-content" style="padding: 0px;">
                <table class="table text-nowrap align-middle table-custom mb-0 last-items-borderless">
                  <thead>
                    <tr>
                      <th scope="col" class="fw-normal ps-0">Assigned
                      </th>
                      <th scope="col" class="fw-normal">Progress</th>
                      <th scope="col" class="fw-normal">Priority</th>
                      <th scope="col" class="fw-normal">Budget</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="ps-0">
                        <div class="d-flex align-items-center gap-6">
                          <img src="../assets/images/products/dash-prd-1.jpg" alt="prd1" width="48" class="rounded">
                          <div>
                            <h6 class="mb-0">Minecraf App</h6>
                            <span>Jason Roy</span>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span>73.2%</span>
                      </td>
                      <td>
                        <span class="badge bg-success-subtle text-success">Low</span>
                      </td>
                      <td>
                        <span class="text-dark-light">$3.5k</span>
                      </td>
                    </tr>
                    <tr>
                      <td class="ps-0">
                        <div class="d-flex align-items-center gap-6">
                          <img src="../assets/images/products/dash-prd-3.jpg" alt="prd1" width="48" class="rounded">
                          <div>
                            <h6 class="mb-0">Modernize Dashboard</h6>
                            <span>Anil Kumar</span>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span>73.2%</span>
                      </td>
                      <td>
                        <span class="badge bg-secondary-subtle text-secondary">Very
                          High</span>
                      </td>
                      <td>
                        <span class="text-dark-light">$3.5k</span>
                      </td>
                    </tr>
                    <tr>
                      <td class="ps-0">
                        <div class="d-flex align-items-center gap-6">
                          <img src="../assets/images/products/dash-prd-2.jpg" alt="prd1" width="48" class="rounded">
                          <div>
                            <h6 class="mb-0">Web App Project</h6>
                            <span>Mathew Flintoff</span>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span>73.2%</span>
                      </td>
                      <td>
                        <span class="badge bg-warning-subtle text-warning">Medium</span>
                      </td>
                      <td>
                        <span class="text-dark-light">$3.5k</span>
                      </td>
                    </tr>

                    <tr>
                      <td class="ps-0">
                        <div class="d-flex align-items-center gap-6">
                          <img src="../assets/images/products/dash-prd-4.jpg" alt="prd1" width="48" class="rounded">
                          <div>
                            <h6 class="mb-0">Dashboard Co</h6>
                            <span>George Cruize</span>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span>73.2%</span>
                      </td>
                      <td>
                        <span class="badge bg-danger-subtle text-danger">High</span>
                      </td>
                      <td>
                        <span class="text-dark-light">$3.5k</span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div></div></div></div><div class="simplebar-placeholder" style="width: 0px; height: 0px;"></div></div><div class="simplebar-track simplebar-horizontal" style="visibility: hidden;"><div class="simplebar-scrollbar" style="width: 0px; display: none;"></div></div><div class="simplebar-track simplebar-vertical" style="visibility: hidden;"><div class="simplebar-scrollbar" style="height: 0px; display: none;"></div></div></div>
            </div>
          </div>


        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <!-- -------------------------------------------- -->
      <!-- Total settlements -->
      <!-- -------------------------------------------- -->
      <div class="card bg-primary-subtle mb-0">
        <div class="card-body">
          <div class="hstack align-items-center gap-3 mb-4">
            <span class="d-flex align-items-center justify-content-center round-48 bg-white rounded flex-shrink-0">
              <iconify-icon icon="solar:box-linear" class="fs-7 text-primary"></iconify-icon>
            </span>
            <div>
              <p class="mb-1 text-dark-light">Total settlements</p>
              <h4 class="mb-0 fw-bolder">$122,580
            </h4></div>
          </div>
          <div style="height: 278px;">
            <div id="settlements" style="min-height: 315px;"><div id="apexchartssjn84hpx" class="apexcharts-canvas apexchartssjn84hpx apexcharts-theme-light" style="width: 300px; height: 300px;"><svg id="SvgjsSvg2883" width="300" height="300" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg apexcharts-zoomable" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;"><foreignObject x="0" y="0" width="300" height="300"><div class="apexcharts-legend" xmlns="http://www.w3.org/1999/xhtml" style="max-height: 150px;"></div></foreignObject><rect id="SvgjsRect2888" width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe"></rect><g id="SvgjsG2958" class="apexcharts-yaxis" rel="0" transform="translate(-18, 0)"></g><g id="SvgjsG2885" class="apexcharts-inner apexcharts-graphical" transform="translate(12, 30)"><defs id="SvgjsDefs2884"><clipPath id="gridRectMasksjn84hpx"><rect id="SvgjsRect2890" width="284" height="224.89866666666666" x="-3" y="-1" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="forecastMasksjn84hpx"></clipPath><clipPath id="nonForecastMasksjn84hpx"></clipPath><clipPath id="gridRectMarkerMasksjn84hpx"><rect id="SvgjsRect2891" width="282" height="226.89866666666666" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath></defs><line id="SvgjsLine2889" x1="0" y1="0" x2="0" y2="222.89866666666666" stroke="#b6b6b6" stroke-dasharray="3" stroke-linecap="butt" class="apexcharts-xcrosshairs" x="0" y="0" width="1" height="222.89866666666666" fill="#b1b9c4" filter="none" fill-opacity="0.9" stroke-width="1"></line><g id="SvgjsG2897" class="apexcharts-grid"><g id="SvgjsG2898" class="apexcharts-gridlines-horizontal"><line id="SvgjsLine2902" x1="0" y1="111.44933333333333" x2="278" y2="111.44933333333333" stroke="var(--bs-primary-bg-subtle)" stroke-dasharray="4" stroke-linecap="butt" class="apexcharts-gridline"></line></g><g id="SvgjsG2899" class="apexcharts-gridlines-vertical"></g><line id="SvgjsLine2905" x1="0" y1="222.89866666666666" x2="278" y2="222.89866666666666" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line><line id="SvgjsLine2904" x1="0" y1="1" x2="0" y2="222.89866666666666" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line></g><g id="SvgjsG2900" class="apexcharts-grid-borders"><line id="SvgjsLine2901" x1="0" y1="0" x2="278" y2="0" stroke="var(--bs-primary-bg-subtle)" stroke-dasharray="4" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2903" x1="0" y1="222.89866666666666" x2="278" y2="222.89866666666666" stroke="var(--bs-primary-bg-subtle)" stroke-dasharray="4" stroke-linecap="butt" class="apexcharts-gridline"></line></g><g id="SvgjsG2892" class="apexcharts-line-series apexcharts-plot-series"><g id="SvgjsG2893" class="apexcharts-series" seriesName="settlements" data:longestSeries="true" rel="1" data:realIndex="0"><path id="SvgjsPath2896" d="M 0 159.21333333333334C3.088888888888889, 159.21333333333334, 12.355555555555558, 159.21333333333334, 18.533333333333335, 159.21333333333334S30.888888888888893, 95.528, 37.06666666666667, 95.528S49.422222222222224, 95.528, 55.6, 95.528S67.95555555555556, 175.13466666666665, 74.13333333333334, 175.13466666666665S86.4888888888889, 175.13466666666665, 92.66666666666667, 175.13466666666665S105.02222222222223, 206.97733333333332, 111.2, 206.97733333333332S123.55555555555557, 206.97733333333332, 129.73333333333335, 206.97733333333332S142.0888888888889, 175.13466666666665, 148.26666666666668, 175.13466666666665S160.62222222222223, 175.13466666666665, 166.8, 175.13466666666665S179.15555555555557, 63.68533333333335, 185.33333333333334, 63.68533333333335S197.6888888888889, 63.68533333333335, 203.86666666666667, 63.68533333333335S216.22222222222223, 191.05599999999998, 222.4, 191.05599999999998S234.75555555555556, 191.05599999999998, 240.93333333333334, 191.05599999999998S253.28888888888892, 0, 259.4666666666667, 0S274.9111111111111, 0, 278, 0" fill="none" fill-opacity="1" stroke="var(--bs-primary)" stroke-opacity="1" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-line" index="0" clip-path="url(#gridRectMasksjn84hpx)" pathTo="M 0 159.21333333333334C3.088888888888889, 159.21333333333334, 12.355555555555558, 159.21333333333334, 18.533333333333335, 159.21333333333334S30.888888888888893, 95.528, 37.06666666666667, 95.528S49.422222222222224, 95.528, 55.6, 95.528S67.95555555555556, 175.13466666666665, 74.13333333333334, 175.13466666666665S86.4888888888889, 175.13466666666665, 92.66666666666667, 175.13466666666665S105.02222222222223, 206.97733333333332, 111.2, 206.97733333333332S123.55555555555557, 206.97733333333332, 129.73333333333335, 206.97733333333332S142.0888888888889, 175.13466666666665, 148.26666666666668, 175.13466666666665S160.62222222222223, 175.13466666666665, 166.8, 175.13466666666665S179.15555555555557, 63.68533333333335, 185.33333333333334, 63.68533333333335S197.6888888888889, 63.68533333333335, 203.86666666666667, 63.68533333333335S216.22222222222223, 191.05599999999998, 222.4, 191.05599999999998S234.75555555555556, 191.05599999999998, 240.93333333333334, 191.05599999999998S253.28888888888892, 0, 259.4666666666667, 0S274.9111111111111, 0, 278, 0" pathFrom="M -1 222.89866666666666 L -1 222.89866666666666 L 18.533333333333335 222.89866666666666 L 37.06666666666667 222.89866666666666 L 55.6 222.89866666666666 L 74.13333333333334 222.89866666666666 L 92.66666666666667 222.89866666666666 L 111.2 222.89866666666666 L 129.73333333333335 222.89866666666666 L 148.26666666666668 222.89866666666666 L 166.8 222.89866666666666 L 185.33333333333334 222.89866666666666 L 203.86666666666667 222.89866666666666 L 222.4 222.89866666666666 L 240.93333333333334 222.89866666666666 L 259.4666666666667 222.89866666666666 L 278 222.89866666666666" fill-rule="evenodd"></path><g id="SvgjsG2894" class="apexcharts-series-markers-wrap apexcharts-hidden-element-shown" data:realIndex="0"><g class="apexcharts-series-markers"><circle id="SvgjsCircle2962" r="0" cx="0" cy="0" class="apexcharts-marker wom0qqybp no-pointer-events" stroke="var(--bs-primary)" fill="var(--bs-primary)" fill-opacity="1" stroke-width="3" stroke-opacity="0.9" default-marker-size="0"></circle></g></g></g><g id="SvgjsG2895" class="apexcharts-datalabels" data:realIndex="0"></g></g><line id="SvgjsLine2906" x1="0" y1="0" x2="278" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line><line id="SvgjsLine2907" x1="0" y1="0" x2="278" y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line><g id="SvgjsG2908" class="apexcharts-xaxis" transform="translate(0, 0)"><g id="SvgjsG2909" class="apexcharts-xaxis-texts-g" transform="translate(0, -10)"><text id="SvgjsText2911" font-family="inherit" x="0" y="245.89866666666666" text-anchor="end" dominant-baseline="auto" font-size="10px" font-weight="600" fill="#adb0bb" class="apexcharts-text apexcharts-xaxis-label " style="font-family: inherit;" transform="rotate(0 1.1803054809570312 240.89866638183594)"><tspan id="SvgjsTspan2912">1W</tspan><title>1W</title></text><text id="SvgjsText2914" font-family="inherit" x="18.53333333333334" y="245.89866666666666" text-anchor="end" dominant-baseline="auto" font-size="10px" font-weight="600" fill="#adb0bb" class="apexcharts-text apexcharts-xaxis-label " style="font-family: inherit;" transform="rotate(0 1 -1)"><tspan id="SvgjsTspan2915"></tspan><title></title></text><text id="SvgjsText2917" font-family="inherit" x="37.06666666666668" y="245.89866666666666" text-anchor="end" dominant-baseline="auto" font-size="10px" font-weight="600" fill="#adb0bb" class="apexcharts-text apexcharts-xaxis-label " style="font-family: inherit;" transform="rotate(0 38.246971130371094 240.89866638183594)"><tspan id="SvgjsTspan2918">3W</tspan><title>3W</title></text><text id="SvgjsText2920" font-family="inherit" x="55.60000000000001" y="245.89866666666666" text-anchor="end" dominant-baseline="auto" font-size="10px" font-weight="600" fill="#adb0bb" class="apexcharts-text apexcharts-xaxis-label " style="font-family: inherit;" transform="rotate(0 1 -1)"><tspan id="SvgjsTspan2921"></tspan><title></title></text><text id="SvgjsText2923" font-family="inherit" x="74.13333333333334" y="245.89866666666666" text-anchor="end" dominant-baseline="auto" font-size="10px" font-weight="600" fill="#adb0bb" class="apexcharts-text apexcharts-xaxis-label " style="font-family: inherit;" transform="rotate(0 75.31645202636719 240.89866638183594)"><tspan id="SvgjsTspan2924">5W</tspan><title>5W</title></text><text id="SvgjsText2926" font-family="inherit" x="92.66666666666667" y="245.89866666666666" text-anchor="end" dominant-baseline="auto" font-size="10px" font-weight="600" fill="#adb0bb" class="apexcharts-text apexcharts-xaxis-label " style="font-family: inherit;" transform="rotate(0 1 -1)"><tspan id="SvgjsTspan2927"></tspan><title></title></text><text id="SvgjsText2929" font-family="inherit" x="111.2" y="245.89866666666666" text-anchor="end" dominant-baseline="auto" font-size="10px" font-weight="600" fill="#adb0bb" class="apexcharts-text apexcharts-xaxis-label " style="font-family: inherit;" transform="rotate(0 112.38530731201172 240.89866638183594)"><tspan id="SvgjsTspan2930">7W</tspan><title>7W</title></text><text id="SvgjsText2932" font-family="inherit" x="129.73333333333332" y="245.89866666666666" text-anchor="end" dominant-baseline="auto" font-size="10px" font-weight="600" fill="#adb0bb" class="apexcharts-text apexcharts-xaxis-label " style="font-family: inherit;" transform="rotate(0 1 -1)"><tspan id="SvgjsTspan2933"></tspan><title></title></text><text id="SvgjsText2935" font-family="inherit" x="148.26666666666665" y="245.89866666666666" text-anchor="end" dominant-baseline="auto" font-size="10px" font-weight="600" fill="#adb0bb" class="apexcharts-text apexcharts-xaxis-label " style="font-family: inherit;" transform="rotate(0 149.45228576660156 240.89866638183594)"><tspan id="SvgjsTspan2936">9W</tspan><title>9W</title></text><text id="SvgjsText2938" font-family="inherit" x="166.79999999999998" y="245.89866666666666" text-anchor="end" dominant-baseline="auto" font-size="10px" font-weight="600" fill="#adb0bb" class="apexcharts-text apexcharts-xaxis-label " style="font-family: inherit;" transform="rotate(0 1 -1)"><tspan id="SvgjsTspan2939"></tspan><title></title></text><text id="SvgjsText2941" font-family="inherit" x="185.33333333333331" y="245.89866666666666" text-anchor="end" dominant-baseline="auto" font-size="10px" font-weight="600" fill="#adb0bb" class="apexcharts-text apexcharts-xaxis-label " style="font-family: inherit;" transform="rotate(0 186.51425170898438 240.89866638183594)"><tspan id="SvgjsTspan2942">11W</tspan><title>11W</title></text><text id="SvgjsText2944" font-family="inherit" x="203.86666666666665" y="245.89866666666666" text-anchor="end" dominant-baseline="auto" font-size="10px" font-weight="600" fill="#adb0bb" class="apexcharts-text apexcharts-xaxis-label " style="font-family: inherit;" transform="rotate(0 1 -1)"><tspan id="SvgjsTspan2945"></tspan><title></title></text><text id="SvgjsText2947" font-family="inherit" x="222.39999999999998" y="245.89866666666666" text-anchor="end" dominant-baseline="auto" font-size="10px" font-weight="600" fill="#adb0bb" class="apexcharts-text apexcharts-xaxis-label " style="font-family: inherit;" transform="rotate(0 223.58091735839844 240.89866638183594)"><tspan id="SvgjsTspan2948">13W</tspan><title>13W</title></text><text id="SvgjsText2950" font-family="inherit" x="240.9333333333333" y="245.89866666666666" text-anchor="end" dominant-baseline="auto" font-size="10px" font-weight="600" fill="#adb0bb" class="apexcharts-text apexcharts-xaxis-label " style="font-family: inherit;" transform="rotate(0 1 -1)"><tspan id="SvgjsTspan2951"></tspan><title></title></text><text id="SvgjsText2953" font-family="inherit" x="259.4666666666667" y="245.89866666666666" text-anchor="end" dominant-baseline="auto" font-size="10px" font-weight="600" fill="#adb0bb" class="apexcharts-text apexcharts-xaxis-label " style="font-family: inherit;" transform="rotate(0 260.6504211425781 240.89866638183594)"><tspan id="SvgjsTspan2954">15W</tspan><title>15W</title></text><text id="SvgjsText2956" font-family="inherit" x="278.00000000000006" y="245.89866666666666" text-anchor="end" dominant-baseline="auto" font-size="10px" font-weight="600" fill="#adb0bb" class="apexcharts-text apexcharts-xaxis-label " style="font-family: inherit;" transform="rotate(0 1 -1)"><tspan id="SvgjsTspan2957"></tspan><title></title></text></g></g><g id="SvgjsG2959" class="apexcharts-yaxis-annotations"></g><g id="SvgjsG2960" class="apexcharts-xaxis-annotations"></g><g id="SvgjsG2961" class="apexcharts-point-annotations"></g><rect id="SvgjsRect2963" width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe" class="apexcharts-zoom-rect"></rect><rect id="SvgjsRect2964" width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe" class="apexcharts-selection-rect"></rect></g></svg><div class="apexcharts-tooltip apexcharts-theme-dark"><div class="apexcharts-tooltip-title" style="font-family: inherit; font-size: 12px;"></div><div class="apexcharts-tooltip-series-group" style="order: 1;"><span class="apexcharts-tooltip-marker" style="background-color: var(--bs-primary);"></span><div class="apexcharts-tooltip-text" style="font-family: inherit; font-size: 12px;"><div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div><div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div><div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div></div></div></div><div class="apexcharts-xaxistooltip apexcharts-xaxistooltip-bottom apexcharts-theme-dark"><div class="apexcharts-xaxistooltip-text" style="font-family: inherit; font-size: 12px;"></div></div><div class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-dark"><div class="apexcharts-yaxistooltip-text"></div></div></div></div>
          </div>
          <div class="row mt-4 mb-2">
            <div class="col-md-6 text-center">
              <p class="mb-1 text-dark-light lh-lg">Total balance</p>
              <h4 class="mb-0 text-nowrap">$122,580</h4>
            </div>
            <div class="col-md-6 text-center mt-3 mt-md-0">
              <p class="mb-1 text-dark-light lh-lg">Withdrawals</p>
              <h4 class="mb-0">$31,640</h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
