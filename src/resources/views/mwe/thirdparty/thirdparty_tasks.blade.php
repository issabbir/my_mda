@extends('layouts.default')

@section('title')
    Works Task Monitor
@endsection

@section('header-style')
    <!--Load custom style link or css-->

@endsection
@section('content')

    <div class="card">
        @if(Session::has('message'))
            <div
                class="alert {{Session::get('m-class') ? Session::get('m-class') : 'alert-danger'}} show"
                role="alert">
                {{ Session::get('message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <div class="card-body">
            <section id="horizontal-vertical">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Works Task Monitor</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body card-dashboard">
                                    <div class="table-responsive">
                                        <table id="searchResultTable" class="table table-sm mdl-data-table">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Request No</th>
                                                <th>REQUEST DATE & Time</th>
                                                <th>Inspector Job No</th>
                                                <th>VESSEL NAME</th>
                                                <th>DEPARTMENT</th>
                                                <th>TASK</th>
                                                <th>Request Status</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>

                                            <tbody id="resultDetailsBody">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

@endsection

@section('footer-script')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">

        function reqList() {
            let url = '{{route('mwe.operation.third-party-tasks-datatable')}}';
            let oTable = $('#searchResultTable').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                    url: url,
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'request_number', name: 'request_number', searchable: true},
                    {data: 'request_date', name: 'request_date', searchable: true},
                    {data: 'inspector_job_number', name: 'inspector_job_number', searchable: true},
                    {data: 'vessel_name', name: 'vessel_name', searchable: true},
                    {data: 'department_name', name: 'department_name', searchable: true},
                    {data: 'name', name: 'name', searchable: true},
                    {data: 'approve_yn', name: 'approve_yn'},
                    {data: 'action', name: 'action', searchable: false},
                ]
            });
        };

        $(document).ready(function () {
            reqList();
        });
    </script>

@endsection

