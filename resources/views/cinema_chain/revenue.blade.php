@extends('cinema_chain.layouts.app')
@section('title') Revenue @endsection

@php
    $rm = fn ($v) => number_format((float) $v, 2);
    $statusBadge = [
        'paid'           => '<span class="badge bg-success">Paid</span>',
        'partially_paid' => '<span class="badge bg-info-subtle text-info">Partially paid</span>',
        'unpaid'         => '<span class="badge bg-danger-subtle text-danger">Unpaid</span>',
    ];
    $query = array_filter(['from' => $filters['from'], 'to' => $filters['to'], 'chain' => $filters['chain']], fn ($v) => $v !== null && $v !== '');
@endphp

@section('content')
<div class="card card-body py-3">
    <div class="d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-0 card-title">Revenue</h4>
        <a class="btn btn-outline-primary btn-sm" href="{{ route('cinema_chain.revenue.export', $query) }}">
            <i class="mdi mdi-download"></i> Export CSV
        </a>
    </div>
</div>

<div class="card card-body">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label">From</label>
            <input type="date" name="from" class="form-control" value="{{ $filters['from'] }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">To</label>
            <input type="date" name="to" class="form-control" value="{{ $filters['to'] }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Cinema chain</label>
            <select name="chain" class="form-select">
                <option value="">All my chains</option>
                @foreach($report['chains'] as $c)
                    <option value="{{ $c['id'] }}" @selected($filters['chain'] === $c['id'])>{{ $c['name'] }} ({{ rtrim(rtrim(number_format($c['percent'], 2), '0'), '.') }}%)</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-primary">Filter</button>
            <a class="btn btn-light" href="{{ route('cinema_chain.revenue') }}">Reset</a>
        </div>
    </form>
</div>

<div class="row">
    @foreach([
        ['Revenue HT (RM)', $report['totals']['gross']],
        ['Your share (RM)', $report['totals']['share']],
        ['Collected (RM)', $report['totals']['collected']],
        ['Pending (RM)', $report['totals']['pending']],
    ] as [$label, $value])
        <div class="col-md-3 col-6">
            <div class="card"><div class="card-body">
                <p class="mb-1 text-muted">{{ $label }}</p>
                <h4 class="mb-0">{{ $rm($value) }}</h4>
            </div></div>
        </div>
    @endforeach
</div>

<div class="card">
    <div class="card-header fw-semibold">Invoices <small class="text-muted fw-normal">(share = revenue &times; the revenue-share % frozen when the invoice was generated; collected is proportional to payments received)</small></div>
    <div class="card-body table-responsive p-0">
        <table class="table table-striped table-sm mb-0 text-nowrap">
            <thead class="table-head">
                <tr>
                    <th>Invoice</th><th>Campaign</th><th>Date</th><th>Status</th>
                    <th class="text-end">Plays</th><th class="text-end">Revenue HT</th>
                    <th class="text-end">%</th><th class="text-end">Your share</th><th class="text-end">Collected</th><th class="text-end">Pending</th>
                </tr>
            </thead>
            <tbody>
                @forelse($report['invoices'] as $r)
                    <tr>
                        <td>{{ $r['number'] }}</td>
                        <td>{{ $r['campaign'] }}</td>
                        <td>{{ $r['date'] }}</td>
                        <td>{!! $statusBadge[$r['status']] ?? e($r['status']) !!}</td>
                        <td class="text-end">{{ number_format($r['plays']) }}</td>
                        <td class="text-end">{{ $rm($r['gross']) }}</td>
                        <td class="text-end">{{ $r['share_percent'] !== null ? rtrim(rtrim(number_format($r['share_percent'], 2), '0'), '.') . '%' : '–' }}</td>
                        <td class="text-end">{{ $rm($r['share']) }}</td>
                        <td class="text-end">{{ $rm($r['collected']) }}</td>
                        <td class="text-end">{{ $rm($r['pending']) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="10" class="text-muted">No invoice for this period.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header fw-semibold">By month</div>
            <div class="card-body table-responsive p-0">
                <table class="table table-sm mb-0">
                    <thead class="table-head"><tr><th>Month</th><th class="text-end">Revenue HT</th><th class="text-end">Your share</th><th class="text-end">Collected</th></tr></thead>
                    <tbody>
                        @forelse($report['by_month'] as $m)
                            <tr><td>{{ $m['month'] }}</td><td class="text-end">{{ $rm($m['gross']) }}</td><td class="text-end">{{ $rm($m['share']) }}</td><td class="text-end">{{ $rm($m['collected']) }}</td></tr>
                        @empty
                            <tr><td colspan="4" class="text-muted">–</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header fw-semibold">By location</div>
            <div class="card-body table-responsive p-0">
                <table class="table table-sm mb-0">
                    <thead class="table-head"><tr><th>Location</th><th>Chain</th><th class="text-end">Plays</th><th class="text-end">Revenue HT</th><th class="text-end">Your share</th></tr></thead>
                    <tbody>
                        @forelse($report['by_location'] as $l)
                            <tr><td>{{ $l['location'] }}</td><td>{{ $l['chain'] }}</td><td class="text-end">{{ number_format($l['plays']) }}</td><td class="text-end">{{ $rm($l['gross']) }}</td><td class="text-end">{{ $rm($l['share']) }}</td></tr>
                        @empty
                            <tr><td colspan="5" class="text-muted">–</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
