@extends('layouts.default')

@section('title')
    Works Request
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
                                <h4 class="card-title">Works Request List</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body card-dashboard">
                                    <div class="table-responsive">
                                        <table id="searchResultTable" class="table table-sm mdl-data-table">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Request No</th>
                                                <th>Inspector Job No</th>
                                                <th>VESSEL NAME</th>
                                                <th>DEPARTMENT</th>
                                                <th>TASK</th>
                                                <th>REQUEST DATE</th>
                                                <th>Third Party</th>
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

        $('#searchResultTable tbody').on('click', '.rejectBtn', function () {
            let row_id = $(this).data("thirdpartyreqid");
            //alert(row_id);
            rejectReq(row_id);
        });
        function rejectReq(row_id){
            let url = '{{route('mwe.operation.request-data-remove')}}';
            Swal.fire({
                title: 'Are you sure?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, reject it!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: 'GET',
                        url: url,
                        data: {row_id: row_id},
                        success: function (msg) {
                            if (msg == 99) {
                                Swal.fire({
                                    title: 'Something Went Wrong.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                                //return false;
                            } else {
                                Swal.fire({
                                    title: 'Rejected!',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(function () {
                                    location.reload();
                                });
                            }
                        }
                    });
                }
            });
        }


        function reqList() {
            let url = '{{route('mwe.operation.third-party-requests-datatable')}}';
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
                    {data: 'inspector_job_number', name: 'inspector_job_number', searchable: true},
                    {data: 'vessel_name', name: 'vessel_name', searchable: true},
                    {data: 'department_name', name: 'department_name', searchable: true},
                    {data: 'name', name: 'name', searchable: true},
                    {data: 'request_date', name: 'request_date', searchable: true},
                    {data: 'thirdparty_name', name: 'thirdparty_name', searchable: true},
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

