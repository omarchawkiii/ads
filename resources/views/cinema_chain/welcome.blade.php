@extends('cinema_chain.layouts.app')
@section('title') Dashboard @endsection

@php
    $rm  = fn ($v) => 'RM ' . number_format((float) $v, 2);
    $max = max(1, collect($months)->max('share') ?? 1);
@endphp

@section('content')
<div class="card card-body py-3">
    <h4 class="mb-0 card-title">Revenue dashboard</h4>
    <small class="text-muted">Advertising revenue of your cinema chain(s). Amounts exclude tax.</small>
</div>

@if(empty($report['chains']))
    <div class="alert alert-warning">No cinema chain is assigned to your account yet. Please contact the administrator.</div>
@else
    <div class="row">
        @foreach([
            ['Revenue (HT)', $rm($report['totals']['gross']), 'text-primary'],
            ['Your share', $rm($report['totals']['share']), 'text-success'],
            ['Collected', $rm($report['totals']['collected']), 'text-info'],
            ['Pending', $rm($report['totals']['pending']), 'text-warning'],
        ] as [$label, $value, $cls])
            <div class="col-md-3 col-6">
                <div class="card"><div class="card-body">
                    <p class="mb-1 text-muted">{{ $label }}</p>
                    <h4 class="mb-0 {{ $cls }}">{{ $value }}</h4>
                </div></div>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-md-3 col-6"><div class="card"><div class="card-body">
            <p class="mb-1 text-muted">This month (your share)</p>
            <h5 class="mb-0">{{ $rm($thisMonth['share'] ?? 0) }}</h5>
        </div></div></div>
        <div class="col-md-3 col-6"><div class="card"><div class="card-body">
            <p class="mb-1 text-muted">Confirmed plays</p>
            <h5 class="mb-0">{{ number_format($report['totals']['plays']) }}</h5>
        </div></div></div>
        <div class="col-md-3 col-6"><div class="card"><div class="card-body">
            <p class="mb-1 text-muted">Invoices</p>
            <h5 class="mb-0">{{ $report['totals']['invoices'] }}</h5>
        </div></div></div>
        <div class="col-md-3 col-6"><div class="card"><div class="card-body">
            <p class="mb-1 text-muted">Current revenue share <small>(new invoices)</small></p>
            <h5 class="mb-0">
                @foreach($report['chains'] as $c)
                    <span class="d-block fs-4">{{ $c['name'] }}: {{ rtrim(rtrim(number_format($c['percent'], 2), '0'), '.') }}%</span>
                @endforeach
            </h5>
        </div></div></div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header fw-semibold">Last 6 months (your share)</div>
                <div class="card-body">
                    @forelse($months as $m)
                        <div class="d-flex align-items-center mb-2">
                            <span class="me-3" style="width:70px">{{ $m['month'] }}</span>
                            <div class="progress flex-grow-1" style="height:18px">
                                <div class="progress-bar bg-success" style="width: {{ round($m['share'] / $max * 100) }}%"></div>
                            </div>
                            <span class="ms-3 text-end" style="width:120px">{{ $rm($m['share']) }}</span>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No invoice yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header fw-semibold d-flex justify-content-between">
                    <span>Latest invoices</span>
                    <a href="{{ route('cinema_chain.revenue') }}">See all</a>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-sm mb-0">
                        <thead class="table-head"><tr><th>Invoice</th><th>Campaign</th><th class="text-end">Your share</th></tr></thead>
                        <tbody>
                            @forelse($recent as $r)
                                <tr><td>{{ $r['number'] }}</td><td>{{ $r['campaign'] }}</td><td class="text-end">{{ $rm($r['share']) }}</td></tr>
                            @empty
                                <tr><td colspan="3" class="text-muted">No invoice yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection
