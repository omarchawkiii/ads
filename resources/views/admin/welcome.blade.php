@extends('admin.layouts.app')

@section('content')

{{-- ===================================================== --}}
{{-- ROW 1 — KPI CARDS --}}
{{-- ===================================================== --}}
<div class="row g-3 mb-4">

  {{-- Total Campaigns --}}
  <div class="col-sm-6 col-xl-3">
    <div class="card h-100">
      <div class="card-body d-flex align-items-center gap-3">
        <span class="d-flex align-items-center justify-content-center round-48 bg-primary-subtle rounded flex-shrink-0">
          <iconify-icon icon="solar:chart-2-linear" class="fs-7 text-primary"></iconify-icon>
        </span>
        <div>
          <p class="text-muted mb-1 fs-2">Total Campaigns</p>
          <h4 class="mb-0 fw-bold">{{ $totalCampaigns }}</h4>
          <small class="text-muted">{{ $draftCampaigns }} draft · {{ $rejectedCampaigns }} rejected</small>
        </div>
      </div>
    </div>
  </div>

  {{-- Pending Review --}}
  <div class="col-sm-6 col-xl-3">
    <div class="card h-100 border-warning border-opacity-50">
      <div class="card-body d-flex align-items-center gap-3">
        <span class="d-flex align-items-center justify-content-center round-48 bg-warning-subtle rounded flex-shrink-0">
          <iconify-icon icon="solar:clock-circle-linear" class="fs-7 text-warning"></iconify-icon>
        </span>
        <div>
          <p class="text-muted mb-1 fs-2">Pending Review</p>
          <h4 class="mb-0 fw-bold text-warning">{{ $pendingCampaigns }}</h4>
          <small class="text-muted">{{ $approvedCampaigns }} approved · {{ $activeCampaigns }} active now</small>
        </div>
      </div>
    </div>
  </div>

  {{-- Total Advertisers --}}
  <div class="col-sm-6 col-xl-3">
    <div class="card h-100">
      <div class="card-body d-flex align-items-center gap-3">
        <span class="d-flex align-items-center justify-content-center round-48 bg-success-subtle rounded flex-shrink-0">
          <iconify-icon icon="solar:users-group-two-rounded-linear" class="fs-7 text-success"></iconify-icon>
        </span>
        <div>
          <p class="text-muted mb-1 fs-2">Advertisers</p>
          <h4 class="mb-0 fw-bold">{{ $totalAdvertisers }}</h4>
          <small class="text-muted">{{ $totalDcps }} DCP creatives · {{ $totalSlots }} slots</small>
        </div>
      </div>
    </div>
  </div>

  {{-- Total Revenue --}}
  <div class="col-sm-6 col-xl-3">
    <div class="card h-100">
      <div class="card-body d-flex align-items-center gap-3">
        <span class="d-flex align-items-center justify-content-center round-48 bg-info-subtle rounded flex-shrink-0">
          <iconify-icon icon="solar:wallet-money-linear" class="fs-7 text-info"></iconify-icon>
        </span>
        <div>
          <p class="text-muted mb-1 fs-2">Total Revenue</p>
          <h4 class="mb-0 fw-bold">${{ number_format($totalRevenue, 0) }}</h4>
          <small class="text-success">${{ number_format($paidRevenue, 0) }} collected</small>
        </div>
      </div>
    </div>
  </div>

</div>

{{-- ===================================================== --}}
{{-- ROW 2 — WELCOME + CAMPAIGN STATUS --}}
{{-- ===================================================== --}}
<div class="row g-3 mb-4">

  {{-- Welcome card --}}
  <div class="col-lg-4">
    <div class="card text-bg-primary h-100">
      <div class="card-body d-flex flex-column">
        <div class="hstack gap-3 mb-3">
          <span class="d-flex align-items-center justify-content-center round-48 bg-white rounded flex-shrink-0">
            <iconify-icon icon="solar:shield-user-linear" class="fs-7 text-primary"></iconify-icon>
          </span>
          <div>
            <p class="text-white opacity-75 mb-0 fs-2">Administrator</p>
            <h5 class="text-white mb-0">{{ Auth::user()->name }}</h5>
          </div>
        </div>
        <p class="text-white opacity-75 mb-4">
          Here's an overview of your platform activity. {{ $pendingCampaigns }} campaign(s) are waiting for your review.
        </p>
        <div class="row mt-auto">
          <div class="col-6">
            <span class="text-white opacity-75 fs-2">Total Budget</span>
            <h5 class="mb-0 text-white fw-bold mt-1">${{ number_format($totalBudget, 0) }}</h5>
          </div>
          <div class="col-6 border-start border-white border-opacity-25">
            <span class="text-white opacity-75 fs-2">Active Now</span>
            <h5 class="mb-0 text-white fw-bold mt-1">{{ $activeCampaigns }}</h5>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Campaign status breakdown --}}
  <div class="col-lg-4">
    <div class="card h-100">
      <div class="card-body">
        <h5 class="card-title fw-semibold">Campaign Status</h5>
        <p class="card-subtitle text-muted mb-4">All-time breakdown</p>

        @php
          $total = max($totalCampaigns, 1);
        @endphp

        <div class="vstack gap-3">
          <div>
            <div class="d-flex justify-content-between mb-1">
              <span class="fs-2 text-muted">Approved</span>
              <span class="fs-2 fw-semibold text-success">{{ $approvedCampaigns }}</span>
            </div>
            <div class="progress" style="height:6px">
              <div class="progress-bar bg-success" style="width:{{ round($approvedCampaigns / $total * 100) }}%"></div>
            </div>
          </div>
          <div>
            <div class="d-flex justify-content-between mb-1">
              <span class="fs-2 text-muted">Pending</span>
              <span class="fs-2 fw-semibold text-warning">{{ $pendingCampaigns }}</span>
            </div>
            <div class="progress" style="height:6px">
              <div class="progress-bar bg-warning" style="width:{{ round($pendingCampaigns / $total * 100) }}%"></div>
            </div>
          </div>
          <div>
            <div class="d-flex justify-content-between mb-1">
              <span class="fs-2 text-muted">Draft</span>
              <span class="fs-2 fw-semibold text-secondary">{{ $draftCampaigns }}</span>
            </div>
            <div class="progress" style="height:6px">
              <div class="progress-bar bg-secondary" style="width:{{ round($draftCampaigns / $total * 100) }}%"></div>
            </div>
          </div>
          <div>
            <div class="d-flex justify-content-between mb-1">
              <span class="fs-2 text-muted">Rejected</span>
              <span class="fs-2 fw-semibold text-danger">{{ $rejectedCampaigns }}</span>
            </div>
            <div class="progress" style="height:6px">
              <div class="progress-bar bg-danger" style="width:{{ round($rejectedCampaigns / $total * 100) }}%"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Campaigns per month (ApexCharts) --}}
  <div class="col-lg-4">
    <div class="card h-100">
      <div class="card-body">
        <h5 class="card-title fw-semibold">Campaigns / Month</h5>
        <p class="card-subtitle text-muted mb-3">Last 6 months</p>
        <div id="chart-monthly-admin" style="min-height:180px;"></div>
      </div>
    </div>
  </div>

</div>

{{-- ===================================================== --}}
{{-- ROW 3 — RECENT CAMPAIGNS --}}
{{-- ===================================================== --}}
<div class="row g-3">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <h5 class="card-title fw-semibold mb-0">Recent Campaigns</h5>
          <a href="{{ route('compaigns.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
        </div>
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead>
              <tr>
                <th>#</th>
                <th>Campaign</th>
                <th>Advertiser</th>
                <th>Budget</th>
                <th>Period</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @forelse($recentCampaigns as $c)
              <tr>
                <td class="text-muted fs-2">{{ $c->id }}</td>
                <td class="fw-semibold">{{ $c->name }}</td>
                <td>{{ $c->user?->name ?? '—' }}</td>
                <td>${{ number_format($c->budget, 0) }}</td>
                <td class="text-muted fs-2">
                  {{ \Carbon\Carbon::parse($c->start_date)->format('d M') }}
                  → {{ \Carbon\Carbon::parse($c->end_date)->format('d M Y') }}
                </td>
                <td>
                  @if($c->status == 1)
                    <span class="badge bg-warning-subtle text-warning">Pending</span>
                  @elseif($c->status == 2)
                    <span class="badge bg-success-subtle text-success">Approved</span>
                  @elseif($c->status == 3)
                    <span class="badge bg-secondary-subtle text-secondary">Draft</span>
                  @else
                    <span class="badge bg-danger-subtle text-danger">Rejected</span>
                  @endif
                </td>
              </tr>
              @empty
              <tr><td colspan="6" class="text-center text-muted py-4">No campaigns yet.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('custom_script')
<script>
  (function () {
    const monthlyData = @json($monthlyCampaigns);
    const labels  = Object.keys(monthlyData);
    const series  = Object.values(monthlyData);

    new ApexCharts(document.querySelector('#chart-monthly-admin'), {
      chart: { type: 'bar', height: 180, toolbar: { show: false }, sparkline: { enabled: false } },
      series: [{ name: 'Campaigns', data: series.length ? series : [0] }],
      xaxis: { categories: labels.length ? labels : ['—'] },
      colors: ['var(--bs-primary)'],
      plotOptions: { bar: { borderRadius: 4, columnWidth: '50%' } },
      dataLabels: { enabled: false },
      grid: { borderColor: 'var(--bs-border-color)', strokeDashArray: 4 },
      tooltip: { theme: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'dark' : 'light' },
    }).render();
  })();
</script>
@endsection
