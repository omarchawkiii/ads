@extends('admin.layouts.app')
@section('title')
    Slots Planning
@endsection

@section('content')
<div class="">

    <div class="card card-body py-3">
        <div class="row align-items-center">
            <div class="col-12">
                <div class="d-sm-flex align-items-center justify-space-between">
                    <h4 class="mb-4 mb-sm-0 card-title">Slots Planning</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body table-responsive">
            <table id="slots-planning-table" class="table table-striped table-bordered display text-nowrap dataTable">
                <thead>
                    <tr class="text-center">
                        <th>#</th>
                        <th>Campaign</th>
                        <th>Slot</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

</div>
@endsection



@section('custom_script')
    <script src="{{ asset('assets/libs/jquery-steps/build/jquery.steps.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>>
    <script src="{{ asset('assets/libs/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/dist/js/select2.min.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="{{ asset('assets/js/helper.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.10/index.global.min.js"></script>


    <script>
        $(function() {

            function formatDate(dt) {
                if (!dt) return '-';
                const d = new Date(dt);
                const day = String(d.getDate()).padStart(2,'0');
                const month = String(d.getMonth()+1).padStart(2,'0');
                const year = d.getFullYear();
                return `${day}/${month}/${year}`;
            }

            function loadPlanning() {
                $('#wait-modal').modal('show');
                $("#slots-planning-table").dataTable().fnDestroy();

                $.ajax({
                    url: "{{ route('planning.slots.list') }}",
                    method: 'GET',
                    success: function(res) {
                        let rows = '';
                        $.each(res.data, function(i, v) {
                            rows += `
                                <tr class="text-center">
                                    <td>${i+1}</td>
                                    <td>${v.compaign}</td>
                                    <td>${v.slot}</td>
                                    <td>${formatDate(v.start_date)}</td>
                                    <td>${formatDate(v.end_date)}</td>
                                </tr>`;
                        });

                        $('#slots-planning-table tbody').html(rows);

                        $('#slots-planning-table').DataTable({
                            "iDisplayLength": 10,
                            destroy: true,
                            language: {
                                search: "_INPUT_",
                                searchPlaceholder: "Search..."
                            }
                        });
                    },
                    complete: function() {
                        $('#wait-modal').modal('hide');
                    }
                });
            }

            loadPlanning();
        });
    </script>





@endsection

@section('custom_css')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/dist/css/select2.min.css') }}">

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
    <style>
        .slot-box {
            border-radius: 6px;
            background: #f9f9f9;
        }
        </style>
@endsection
