@extends('content_approval.layouts.app')
@section('title') Dashboard @endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-body py-3 mb-4">
            <h4 class="card-title mb-0">Content Approval Dashboard</h4>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="p-3 bg-warning-subtle rounded">
                    <i class="mdi mdi-clock-outline fs-6 text-warning"></i>
                </div>
                <div>
                    <p class="mb-1 text-muted">Pending</p>
                    <h4 class="mb-0 fw-bold">{{ $pendingDcps }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="p-3 bg-success-subtle rounded">
                    <i class="mdi mdi-check-circle-outline fs-6 text-success"></i>
                </div>
                <div>
                    <p class="mb-1 text-muted">Approved</p>
                    <h4 class="mb-0 fw-bold">{{ $approvedDcps }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="p-3 bg-danger-subtle rounded">
                    <i class="mdi mdi-close-circle-outline fs-6 text-danger"></i>
                </div>
                <div>
                    <p class="mb-1 text-muted">Rejected</p>
                    <h4 class="mb-0 fw-bold">{{ $rejectedDcps }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="p-3 bg-primary-subtle rounded">
                    <i class="mdi mdi-bullhorn fs-6 text-primary"></i>
                </div>
                <div>
                    <p class="mb-1 text-muted">Total DCPs</p>
                    <h4 class="mb-0 fw-bold">{{ $totalDcps }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Recent DCP Creatives</h5>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Customer</th>
                                <th>Duration</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentDcps as $dcp)
                            <tr>
                                <td>{{ $dcp->name }}</td>
                                <td>{{ $dcp->customer?->name ?? '—' }}</td>
                                <td>{{ $dcp->duration }}s</td>
                                <td>
                                    @if($dcp->status === 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif($dcp->status === 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>{{ $dcp->created_at?->format('Y-m-d') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
