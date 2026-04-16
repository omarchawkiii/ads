@extends('advertiser.layouts.app')
@section('title') My Clients @endsection

@php
    $avatarColors = ['#e05d44','#2ea84f','#e07b39','#3b82c4','#9c3b9c','#3b9c8c','#c4903b','#d94f8a'];
@endphp

@section('content')

{{-- ── Header ── --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0">My Clients</h4>
    <button class="btn btn-success px-4" id="create_customer">
        + New Client
    </button>
</div>

{{-- ── Info Banner ── --}}
<div class="card mb-4 border-primary border-opacity-25">
    <div class="card-body d-flex gap-3 align-items-start py-3">
        <div class="flex-shrink-0 rounded-2 d-flex align-items-center justify-content-center bg-primary bg-opacity-10"
             style="width:44px;height:44px;">
            <i class="mdi mdi-domain fs-5 text-primary"></i>
        </div>
        <div>
            <h6 class="fw-bold mb-1">Agency Account — {{ auth()->user()->name }} {{ auth()->user()->last_name }}</h6>
            <p class="mb-1 text-body-secondary">
                You are logged in as an <strong class="text-body">Advertising Agency</strong>.
                The "Clients" section lets you manage the brands you represent.
                Campaigns and invoices can be scoped per client. When creating a campaign, you will select which client
                the campaign belongs to — enabling <strong class="text-body">separate billing, analytics, and DCP creative libraries</strong> per brand.
            </p>
            <p class="mb-0 text-body-secondary">
                If you are a <strong class="text-body">direct advertiser</strong> (not an agency), this section stores your own company profile used for billing and campaign attribution.
            </p>
        </div>
    </div>
</div>

{{-- ── Stats Row ── --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-2 d-flex align-items-center justify-content-center bg-warning-subtle"
                     style="width:44px;height:44px;">
                    <i class="mdi mdi-account-group text-warning fs-5"></i>
                </div>
                <div>
                    <div class="text-body-secondary small">Total Clients</div>
                    <div class="fw-bold fs-3 text-body">{{ $totalClients }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-2 d-flex align-items-center justify-content-center bg-success-subtle"
                     style="width:44px;height:44px;">
                    <i class="mdi mdi-layers text-success fs-5"></i>
                </div>
                <div>
                    <div class="text-body-secondary small">Active Campaigns</div>
                    <div class="fw-bold fs-3 text-body">{{ $activeCampaigns }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-2 d-flex align-items-center justify-content-center bg-info-subtle"
                     style="width:44px;height:44px;">
                    <i class="mdi mdi-credit-card text-info fs-5"></i>
                </div>
                <div>
                    <div class="text-body-secondary small">Outstanding Invoices</div>
                    <div class="fw-bold fs-3 text-body">RM {{ number_format($outstandingAmount, 2) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Client Cards Grid ── --}}
<div class="row g-3">
@forelse($customers as $i => $customer)
    @php
        $words    = explode(' ', trim($customer->name));
        $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : substr($words[0], 1, 1)));
        $color    = $avatarColors[$i % count($avatarColors)];
    @endphp

    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">

                {{-- Client header --}}
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="rounded-2 d-flex align-items-center justify-content-center fw-bold text-white fs-5 flex-shrink-0"
                         style="width:52px;height:52px;background:{{ $color }};">
                        {{ $initials }}
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <h6 class="fw-bold mb-0">{{ $customer->name }}</h6>
                        <span class="text-body-secondary small">Client since {{ $customer->created_at->format('M Y') }}</span>
                    </div>
                    <div class="d-flex gap-1 flex-shrink-0">
                        <button class="btn btn-sm btn-outline-primary edit-client"
                                data-id="{{ $customer->id }}"
                                title="Edit">
                            <i class="mdi mdi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger delete-client"
                                data-id="{{ $customer->id }}"
                                data-name="{{ $customer->name }}"
                                title="Delete">
                            <i class="mdi mdi-delete"></i>
                        </button>
                    </div>
                </div>

                {{-- Info tiles --}}
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="p-2 rounded-2 bg-body-secondary">
                            <div class="text-body-secondary" style="font-size:10px;letter-spacing:.05em;">CONTACT</div>
                            <div class="small text-body" title="{{ $customer->email }}">{{ $customer->email ?: '—' }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 rounded-2 bg-body-secondary">
                            <div class="text-body-secondary" style="font-size:10px;letter-spacing:.05em;">PHONE</div>
                            <div class="small text-body">{{ $customer->phone ?: '—' }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 rounded-2 bg-body-secondary">
                            <div class="text-body-secondary" style="font-size:10px;letter-spacing:.05em;">DCP CREATIVES</div>
                            <div class="small fw-semibold text-body">{{ $customer->dcp_creatives_count }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 rounded-2 bg-body-secondary">
                            <div class="text-body-secondary" style="font-size:10px;letter-spacing:.05em;">ADDRESS</div>
                            <div class="small text-body" title="{{ $customer->address }}">{{ $customer->address ?: '—' }}</div>
                        </div>
                    </div>
                </div>

                {{-- Bottom action buttons --}}
                <div class="d-flex gap-2">
                    <a href="{{ route('advertiser.compaigns.index') }}?customer_id={{ $customer->id }}"
                       class="btn btn-sm btn-outline-primary flex-fill">
                        <i class="mdi mdi-chart-bar me-1"></i>View Campaigns
                    </a>
                    <a href="#" class="btn btn-sm btn-outline-primary flex-fill" >
                        <i class="mdi mdi-chart-line me-1"></i>Analytics
                    </a>
                    <a href="#" class="btn btn-sm btn-outline-primary flex-fill" >
                        <i class="mdi mdi-receipt me-1"></i>Invoices
                    </a>
                </div>

            </div>
        </div>
    </div>

@empty
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5 text-body-secondary">
                <i class="mdi mdi-account-group-outline fs-1 mb-3 d-block"></i>
                No clients yet. Click <strong>+ New Client</strong> to add your first one.
            </div>
        </div>
    </div>
@endforelse
</div>

{{-- ── CREATE MODAL ── --}}
<div class="modal fade" id="create_customer_modal" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <form id="create_customer_form">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title text-white">Create Client</h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="create_name" required placeholder="Client name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" class="form-control" id="create_address" placeholder="Address">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="create_email" placeholder="Email">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" class="form-control" id="create_phone" placeholder="Phone">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success save-btn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── EDIT MODAL ── --}}
<div class="modal fade" id="edit_customer_modal" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <form id="edit_customer_form">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title text-white">Edit Client</h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id">
                    <div class="mb-3">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_name" required placeholder="Client name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" class="form-control" id="edit_address" placeholder="Address">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email" placeholder="Email">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" class="form-control" id="edit_phone" placeholder="Phone">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success save-btn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('custom_script')
<script>
$(function () {
    const CSRF = '{{ csrf_token() }}';
    const BASE = '{{ url("") }}/advertiser/customers';

    // ── OPEN CREATE ──
    $('#create_customer').on('click', function () {
        $('#create_customer_form')[0].reset();
        $('#create_customer_modal').modal('show');
    });

    // ── CREATE ──
    $('#create_customer_form').on('submit', function (e) {
        e.preventDefault();
        const btn = $(this).find('.save-btn').prop('disabled', true);
        $.ajax({
            url: BASE,
            method: 'POST',
            data: {
                name:    $('#create_name').val(),
                address: $('#create_address').val(),
                email:   $('#create_email').val(),
                phone:   $('#create_phone').val(),
                _token:  CSRF
            }
        })
        .done(function () {
            Swal.fire({ icon: 'success', title: 'Done!', text: 'Client created successfully.', timer: 1800, showConfirmButton: false })
                .then(() => location.reload());
        })
        .fail(function (xhr) {
            const msg = xhr.responseJSON?.message || 'Operation failed.';
            Swal.fire('Error', msg, 'error');
            btn.prop('disabled', false);
        });
    });

    // ── OPEN EDIT ──
    $(document).on('click', '.edit-client', function () {
        const id = $(this).data('id');
        $.get(BASE + '/' + id + '/show', function (res) {
            const c = res.customer;
            $('#edit_id').val(c.id);
            $('#edit_name').val(c.name);
            $('#edit_address').val(c.address);
            $('#edit_email').val(c.email);
            $('#edit_phone').val(c.phone);
            $('#edit_customer_modal').modal('show');
        });
    });

    // ── UPDATE ──
    $('#edit_customer_form').on('submit', function (e) {
        e.preventDefault();
        const id  = $('#edit_id').val();
        const btn = $(this).find('.save-btn').prop('disabled', true);
        $.ajax({
            url:    BASE + '/' + id,
            method: 'PUT',
            data: {
                name:    $('#edit_name').val(),
                address: $('#edit_address').val(),
                email:   $('#edit_email').val(),
                phone:   $('#edit_phone').val(),
                _token:  CSRF
            }
        })
        .done(function () {
            Swal.fire({ icon: 'success', title: 'Done!', text: 'Client updated successfully.', timer: 1800, showConfirmButton: false })
                .then(() => location.reload());
        })
        .fail(function () {
            Swal.fire('Error', 'Operation failed.', 'error');
            btn.prop('disabled', false);
        });
    });

    // ── DELETE ──
    $(document).on('click', '.delete-client', function () {
        const id   = $(this).data('id');
        const name = $(this).data('name');
        Swal.fire({
            title: 'Delete Client?',
            text:  `Are you sure you want to delete "${name}"?`,
            icon:  'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it',
            confirmButtonColor: '#dc3545'
        }).then(result => {
            if (!result.isConfirmed) return;
            $.post(BASE + '/' + id, { _method: 'DELETE', _token: CSRF })
                .done(function () {
                    Swal.fire({ icon: 'success', title: 'Done!', text: 'Client deleted successfully.', timer: 1800, showConfirmButton: false })
                        .then(() => location.reload());
                })
                .fail(function () {
                    Swal.fire('Error', 'Deletion failed.', 'error');
                });
        });
    });
});
</script>
@endsection
