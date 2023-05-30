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
                                <h4 class="card-title">Works Request Approval List</h4>
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

    <div id="status-show" class="modal fade" role="dialog">
        <div class="modal-dialog modal-full">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-uppercase text-left">
                        Task Detail
                    </h4>
                    <button class="close" type="button" data-dismiss="modal"
                            area-hidden="true">
                        &times;
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col" id="final-selection-message"></div>
                    </div>
                    <form id="add-form" method="post" name="add-form">
                        {!! csrf_field() !!}
                        <div class="row">
                            <div class="col-12">
                                <div class="row mt-2 ml-2">
                                    <div class="col-md-9">
                                        <div id="start-no-field" class="form-group">
                                            <label class="required">Task Detail</label>
                                            <input type="text" id="description"
                                                   class="form-control"
                                                   oninput="this.value = this.value.toUpperCase()"
                                                   placeholder="Task Detail"
                                                   autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-md-3 mt-2">
                                        <div id="start-no-field"
                                             class="form-group">
                                            <button class="btn btn-secondary hvr-underline-reveal" id="new-task"
                                                    type="button">
                                                <i class="bx bxs-add-to-queue"></i> Add New
                                            </button>&nbsp;
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <form id="workflow_form" method="post">
                        {!! csrf_field() !!}
                        <fieldset class="border p-1 mt-2 mb-1 col-sm-12"
                                  id="detail_data">
                            <input type="hidden" id="thirdparty_req_id" name="thirdparty_req_id">
                            <input type="hidden" id="maintenance_req_id" name="maintenance_req_id">
                            <input type="hidden" id="mw_workshop_id" name="mw_workshop_id">
                            <input type="hidden" id="mw_vessel_inspections_id" name="mw_vessel_inspections_id">
                            <div class="col-sm-12 mt-1">
                                <div class="table-responsive">
                                    <table
                                        class="table table-sm table-striped table-light"
                                        id="table-dtl">
                                        <thead>
                                        <tr>
                                            <th role="columnheader" scope="col"
                                                aria-colindex="2"
                                                width="10%">#
                                            </th>
                                            <th role="columnheader" scope="col"
                                                aria-colindex="2" class="text-center"
                                                width="50%">Task Detail
                                            </th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                            <div class="row" style="display: none" id="ssae_note">
                                <div class="col-md-12 mt-1 ml-1">
                                    <div class="form-group">
                                        <label for="remarks">Note From SSAE</label>
                                        <textarea rows="2" wrap="soft"
                                                  class="form-control" disabled
                                                  id="ssae_remarks"></textarea>

                                        <small class="text-muted form-text"> </small>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="display: none" id="asw_note">
                                <div class="col-md-12 mt-1 ml-1">
                                    <div class="form-group">
                                        <label for="remarks">Note From ASW</label>
                                        <textarea rows="2" wrap="soft"
                                                  class="form-control" disabled
                                                  id="asw_remarks"></textarea>

                                        <small class="text-muted form-text"> </small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 mt-1 ml-1">
                                    <div class="form-group">
                                        <label for="remarks">Note</label>
                                        <textarea placeholder="Note"
                                                  rows="4" wrap="soft"
                                                  name="remarks"
                                                  class="form-control"
                                                  id="remarks"></textarea>

                                        <small class="text-muted form-text"> </small>
                                    </div>
                                </div>
                                @php
                                    use Illuminate\Support\Facades\Auth;
                                    $role_key = json_decode(Auth::user()->roles->pluck('role_key'));
                                @endphp
                                @if (!in_array("MDA_XEN", $role_key))
                                    <div class="col-md-3 mt-1 ml-1">
                                        <label class="required">Forward To</label>
                                        <select class="custom-select select2 form-control" required
                                                id="forward_to" name="forward_to">
                                            <option value="">Select One</option>
                                            @foreach($users as $value)
                                                <option
                                                    value="{{$value->emp_id}}">{{$value->emp_code.'-'.$value->emp_name.' ('.$value->role_name.')'}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group mt-1">
                                <div class="col-md-12 pr-0 d-flex justify-content-end">
                                    <div class="form-group">
                                        @if (!in_array("MDA_XEN", $role_key))
                                            <button id="save-info" type="button"
                                                    class="btn btn-primary forapp">Forward
                                            </button>
                                        @else
                                            <button id="save-info" type="button"
                                                    class="btn btn-primary forapp">Approve
                                            </button>
                                        @endif
                                        <button class="btn btn-primary mr-1"
                                                type="button" data-dismiss="modal">
                                            Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('footer-script')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">

        $('#searchResultTable tbody').on('click', '.approveBtn', function () {
            let row_id = $(this).data("thirdpartyreqid");
            approveReq(row_id);
            $('.forapp').attr('disabled', false);
            $('#remarks').val('');
            $('#asw_remarks').val('');
            $('#ssae_remarks').val('');
        });

        $('#new-task').on('click', function (e) {
            e.preventDefault();
            let answer = confirm('Are you sure?');
            let desc = $('#description').val();
            /*if (desc) {
                alert('Please Add Description.');
            }else{*/
            if (answer == true) {
                $.ajax({
                    type: 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    url: "{{route('mwe.operation.third-party-approval-new-task-dtl')}}",
                    data: {
                        thirdparty_req_id: $('#thirdparty_req_id').val(),
                        maintenance_req_id: $('#maintenance_req_id').val(),
                        workshopId: $('#mw_workshop_id').val(),
                        vessel_inspection_id: $('#mw_vessel_inspections_id').val(),
                        description: desc
                    },
                    success: function (data) {
                        $('#final-selection-message').html(data.html);
                        $('#description').val('');
                        onlyDtl($('#maintenance_req_id').val(), $('#mw_workshop_id').val(), $('#mw_vessel_inspections_id').val());
                    },
                    error: function (data) {
                        alert('error');
                    }
                });

            } else {
                $('#final-selection-message').html('');
            }
            //}

        });

        function onlyDtl(maintenance_req_id, mw_workshop_id, mw_vessel_inspections_id) {
            let url = '{{route('mwe.operation.only-dtl-datatable')}}';
            let tblPreventivi = $('#table-dtl').DataTable({
                processing: true,
                serverSide: true,
                bDestroy: true,
                pageLength: 20,
                bFilter: true,
                async: false,
                lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
                /*serverSide: true,
                bDestroy: true,*/
                ajax: {
                    url: url,
                    data: {maintenance_req_id: maintenance_req_id, mw_workshop_id: mw_workshop_id, mw_vessel_inspections_id: mw_vessel_inspections_id},
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                "columns": [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                    {"data": "description"},
                ],
            });
        }

        function approveReq(row_id) {
            let myModal = $('#status-show');
            let url = '{{ route("mwe.operation.third-party-request-approval-data", ":thirdparty_req_id") }}';
            url = url.replace(':thirdparty_req_id', row_id);
            $.ajax({
                url: url,
                success: function (msgs) {//console.log(msgs)
                    let markup = '';
                    let mst = msgs.mst_data;
                    $('#thirdparty_req_id').val(mst.thirdparty_req_id);
                    $('#maintenance_req_id').val(mst.maintenance_req_id);
                    $('#mw_workshop_id').val(mst.mw_workshop_id);
                    $('#mw_vessel_inspections_id').val(mst.mw_vessel_inspections_id);
                    onlyDtl(mst.maintenance_req_id, mst.mw_workshop_id, mst.mw_vessel_inspections_id);
                    if(mst.ssae_remarks){
                        document.getElementById('ssae_note').style.display = 'block';
                        $('#ssae_remarks').val(mst.ssae_remarks);
                    }
                    if(mst.asw_remarks){
                        document.getElementById('asw_note').style.display = 'block';
                        $('#asw_remarks').val(mst.asw_remarks);
                    }
                    /*let dtl = msgs.dtl_data;

                    $("#table-dtl > tbody").html("");
                    $.each(dtl, function (i) {
                        markup += "<tr role='row'>" +
                            "<td aria-colindex='2' role='cell'>" + dtl[i].description + "</td>" +
                            "</tr>";

                    });
                    $("#table-dtl tbody").html(markup);*/
                }
            });

            myModal.modal({show: true});
            return false;
        }

        $('#save-info').on('click', function (e) {
            e.preventDefault();
            var answer = confirm('Are you sure?');

            if (answer == true) {
                $.ajax({
                    type: 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    url: "{{route('mwe.operation.forward-to')}}",
                    data: {
                        thirdparty_req_id: $('#thirdparty_req_id').val(),
                        remarks: $('#remarks').val(),
                        forward_to: $('#forward_to').val(),
                    },
                    success: function (data) {
                        $('#final-selection-message').html(data.html);
                        //$('#remarks').val('');
                        //$('#status-show').modal('toggle');
                        $('#searchResultTable').DataTable().ajax.reload();
                        //reqList();
                        $('.forapp').attr('disabled', true);
                    },
                    error: function (data) {
                        //reqList();
                        alert('error');
                    }
                });

            } else {
                //reqList();
                $('#final-selection-message').html('');
            }
            //}

        });

        function reqList() {
            let url = '{{route('mwe.operation.third-party-request-approval-datatable')}}';
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
                    {data: 'action', name: 'action', searchable: false},
                ]
            });
        };

        $(document).ready(function () {
            reqList();
        });
    </script>

@endsection

