
@extends('layouts.default')

@section('title')
@endsection
@section('header-style')
    <!--Load custom style link or css-->
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <!--form start-->
            <div class="card">
                <div class="card-body">
                    <section id="horizontal-vertical">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        @if(Session::has('message'))
                                            <div class="alert {{Session::get('m-class') ? Session::get('m-class') : 'alert-danger'}} show"
                                                 role="alert">
                                                {{ Session::get('message') }}
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-content">
                                        <form action="" method="post" id="search-form">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-4 mb-1">
                                                    <label for="budget_group_id">Authorization For</label>
                                                    <select name="workflow_master_id" id="workflow_master_id" class="form-control select2">
                                                        @foreach($workflow  as $op)
                                                            <option value="{{$op['workflow_master_id'] }}">{{$op['workflow_name']}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4 mb-1" style="margin-top: 21px">
                                                    <button type="submit" name="save"
                                                            class="btn btn btn-outline-dark"><i
                                                            class="bx bxs-search"></i> Search
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                        <h4 class="card-title">Pending List</h4>
                                        <div class="card-body card-dashboard">
                                            <div class="table-responsive">
                                                <table id="requisition-list" class="table table-sm dataTable">
                                                    <thead>
                                                    <tr>
                                                        <th>SL</th>
                                                        <th>Ref No</th>
                                                        <th>Vessel Name</th>
                                                        <th>Incharge</th>
                                                        <th>Authorization For</th>
                                                        <th>Current Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
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
        </div>
    </div>
@endsection

@section('footer-script')
    <!--Load custom script-->
    <script type="text/javascript">
        $(document).ready(function () {
            function pendingList() {
                let oTable =   $('.dataTable').DataTable({
                    processing: true,
                    serverSide: true,
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Show All"]],
                    ajax: {
                        url: "{{route('approval.datatable')}}",
                        type:'POST', headers:{  'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        data: function (d) {
                            d.workflow_master_id = $('#workflow_master_id').val();
                        }
                    },
                    columns: [
                        {"data": 'DT_RowIndex', "name": 'DT_RowIndex', searchable: true},
                        {data: 'reference_no', name: 'reference_no', searchable: true},
                        {data: 'vessel_name', name: 'vessel_name', searchable: true},
                        {data: 'vessel_incharge', name: 'vessel_incharge', searchable: true},
                        {data: 'authorization_for', name: 'authorization_for', searchable: true},
                        {data: 'cur_status', name: 'cur_status', searchable: true},
                        {data: 'action', name: 'action'},
                    ]
                });
                $('#search-form').on('submit', function (e) {
                    oTable.draw();
                    e.preventDefault();
                });
            };
            pendingList();

        });
    </script>

@endsection



