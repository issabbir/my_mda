@extends('layouts.default')

@section('title')
    Works Request
@endsection

@section('header-style')
    <!--Load custom style link or css-->
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            @section('content')
                <div class="row">
                    <div class="col-12">
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
                            <div class="card-content">
                                <div class="card-body">
                                    <h4 class="card-title">Works Requisition</h4>
                                    {{--<form method="POST" action="{{route('mwe.operation.third-party-assign-post')}}" enctype="multipart/form-data">
                                        {!! csrf_field() !!}--}}
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="row my-1">
                                                <div class="col-md-12">
                                                    <label class="input-required">Request Number</label>
                                                    <input
                                                        type="text"
                                                        id="request_number"
                                                        class="form-control"
                                                        value="{{isset($maintenanceReqData->request_number)?$maintenanceReqData->request_number:''}}"
                                                        readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row my-1">
                                                <div class="col-md-12">
                                                    <label class="input-required">Department</label>
                                                    <input type="text" class="form-control" disabled
                                                           name="department_id" id="department_id"
                                                           value="{{isset($maintenanceReqData->department)?$maintenanceReqData->department->name:''}}">
                                                    <input type="hidden" class="form-control"
                                                           name="maintenance_req_id"
                                                           id="maintenance_req_id"
                                                           value="{{isset($maintenanceReqData)?$maintenanceReqData->id:''}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row my-1">
                                                <div class="col-md-12">
                                                    <label class="input-required">Vessel</label>
                                                    <input type="text" class="form-control" disabled
                                                           name="vessel_id"
                                                           id="vessel_id"
                                                           value="{{isset($maintenanceReqData->vessel)?$maintenanceReqData->vessel->name:''}}">
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="row my-1">
                                                <div class="col-md-12">
                                                    <label class="input-required">Current Maintenance Status</label>
                                                    <input type="text" class="form-control" disabled
                                                           name="current_status" id="current_status"
                                                           value="{{isset($maintenanceReqData->status)?\App\Helpers\HelperClass::getReqStatus($maintenanceReqData->status)->name:''}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row my-1">
                                                <div class="col-md-12">
                                                    <label class="input-required">Workshop Name</label>
                                                    <input type="text" class="form-control" disabled
                                                           name="workshop_id"
                                                           id="workshop_id"
                                                           value="{{isset($workshop)?$workshop->name:''}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row my-1">
                                                <div class="col-md-12">
                                                    <label class="input-required">Vessel Master</label>
                                                    <input type="text" class="form-control" disabled
                                                           name="vessel_master_id" id="vessel_master_id"
                                                           value="{{isset($maintenanceReqData->vesselMaster)?$maintenanceReqData->vesselMaster->emp_name:''}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="row my-1">
                                                <div class="col-md-12">
                                                    <label>Job Numner</label>
                                                    <input type="text" class="form-control" disabled
                                                           name="job_num" id="job_num"
                                                           value="{{isset($task->job_number)?$task->job_number:''}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-12">
                                            <h4 class="card-title"><strong>Task</strong></h4>
                                            <div class="bs-example">
                                                <div class="accordion" id="accordionExample">
                                                    <div class="card">
                                                        <div class="card-header" id="headingOne">
                                                            <h2 class="mb-0">
                                                                <button
                                                                    class="btn btn-link btn-block text-left btn-secondary"
                                                                    type="button"
                                                                    data-toggle="collapse"
                                                                    id="show_task_details_{{$task->id}}"
                                                                    data-target="#collapse-{{$task->id}}"
                                                                    aria-expanded="true"
                                                                    aria-controls="collapse-{{$task->id}}">
                                                                    # {{$task->name}}
                                                                    {{--<i class='bx bxs-area bx-pull-right bx-md'
                                                                       style="top: auto"></i>
                                                                    <span id="requisition_status_{{$task->id}}"
                                                                          style="margin-top: 6px"
                                                                          class="bx-pull-right mr-5"> STATUS :
                                <strong> {{(!$task->status)?'NOT INITIALIZED':App\Helpers\HelperClass::getRequisitionStatus($task->status)}}</strong>
                            </span>--}}
                                                                </button>
                                                            </h2>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <section id="horizontal-vertical">
                                        <div class="row">
                                            <div class="col" id="final-selection-message"></div>
                                        </div>
                                        <form id="add-form" method="post" name="add-form">
                                            {!! csrf_field() !!}
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="card">
                                                        <div class="row mt-2 ml-2">
                                                            {{--<div class="col-md-9">
                                                                <div id="start-no-field" class="form-group">
                                                                    <label class="required">Task Detail</label>
                                                                    <input type="text" id="description"
                                                                           class="form-control"
                                                                           oninput="this.value = this.value.toUpperCase()"
                                                                           placeholder="Task Detail"
                                                                           autocomplete="off">
                                                                </div>
                                                            </div>--}}
                                                            <div class="col-md-9">
                                                                <div id="start-no-field" class="form-group">
                                                                    <label class="required">Work</label>
                                                                    <select id="description" class="form-control select2">
                                                                        <option value="">Select one</option>
                                                                        @foreach($list_of_works as $task)
                                                                            <option
                                                                                value="{{$task->work_title}}">{{$task->work_title}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>


                                                            <div class="col-md-3 mt-2">
                                                                <div id="start-no-field"
                                                                     class="form-group">
                                                                    <button
                                                                        class="btn btn-secondary hvr-underline-reveal"
                                                                        id="new-task"
                                                                        type="button">
                                                                        <i class="bx bxs-add-to-queue"></i> Add
                                                                    </button>&nbsp;
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </section>
                                    <section id="horizontal-vertical">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card">
                                                    <div class="card-content">
                                                        <div class="card-body card-dashboard">
                                                            <div class="table-responsive">
                                                                <table id="searchResultTable"
                                                                       class="table table-sm datatable mdl-data-table">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Task Detail</th>
                                                                        <th>Delete</th>
                                                                    </tr>
                                                                    </thead>

                                                                    <tbody id="resultDetailsBody">

                                                                    </tbody>
                                                                </table>
                                                                <input type='hidden' id="maintenance_req_id"
                                                                       name='maintenance_req_id'
                                                                       value='{{isset($maintenance_req_id) ? $maintenance_req_id : ''}}'>
                                                                <input type='hidden' id="workshopId" name='workshopId'
                                                                       value='{{isset($workshopId) ? $workshopId : ''}}'>
                                                                <input type='hidden' id="vessel_inspection_id"
                                                                       name='vessel_inspection_id'
                                                                       value='{{isset($vessel_inspection_id) ? $vessel_inspection_id : ''}}'>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    <form method="POST" action="{{route('mwe.operation.third-party-assign-post')}}"
                                          enctype="multipart/form-data">
                                        {!! csrf_field() !!}
                                        <div class="row ml-1">
                                            <div class="col-md-4">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label class="required">Forward To</label>
                                                        <select class="select2 form-control" style="width: 100%"
                                                                id="forward_to" name="forward_to">
                                                            <option value="">Select One</option>
                                                            @if(isset($send_to) && !empty($send_to))
                                                                @foreach($send_to as $value)
                                                                    <option value="{{$value->emp_id}}" {{isset($thirdpartyreq->forward_to_ssae) && $thirdpartyreq->forward_to_ssae == $value->emp_id ? 'selected' : ''}}>
                                                                        {{$value->emp_code.' - '.$value->emp_name.' - '.$value->role_name}}
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{--<div class="row mt-1 mb-1">
                                            <div class="col-md-12 mt-1">
                                                <label for="description"
                                                       class="form-control-label text-uppercase required">Comments</label>
                                                <textarea name="remarks"
                                                          @if($thirdpartyreq != null) readonly
                                                          @endif
                                                          id="remarks" required
                                                          oninput="this.value = this.value.toUpperCase()"
                                                          class="form-control" style="height:200px;"
                                                          rows="4" cols="200">{{isset($thirdpartyreq->remarks) ? $thirdpartyreq->remarks : ''}}</textarea>
                                                <input type='hidden' name='maintenance_req_id' value='{{isset($maintenance_req_id) ? $maintenance_req_id : ''}}'>
                                                <input type='hidden' name='workshopId' value='{{isset($workshopId) ? $workshopId : ''}}'>
                                                <input type='hidden' name='vessel_inspection_id' value='{{isset($vessel_inspection_id) ? $vessel_inspection_id : ''}}'>
                                            </div>
                                        </div>--}}
                                        <div class="form-group">
                                            <div class="col-md-12 pr-0 d-flex justify-content-end">
                                                <div class="form-group">
                                                    @if($thirdpartyreq == null)
                                                        <button id="boat-employee-save" type="submit" onclick="return confirm('Are you sure?')"
                                                                class="btn btn btn-dark shadow mr-1 mb-1">Submit For
                                                            Requisition
                                                        </button>
                                                        <a type="reset"
                                                           href="{{ route('mwe.operation.workshop-requisition-create', ['id' => $maintenance_req_id,'workshopId' => $workshopId])  }}"
                                                           class="btn btn-light-secondary mb-1"> Back</a>
                                                    @else
                                                        <a type="reset"
                                                           href="{{ route('mwe.operation.workshop-requisition-create', ['id' => $maintenance_req_id,'workshopId' => $workshopId])  }}"
                                                           class="btn btn-light-secondary mb-1"> Back</a>
                                                    @endif
                                                    <input type='hidden' id="maintenance_req_id"
                                                           name='maintenance_req_id'
                                                           value='{{isset($maintenance_req_id) ? $maintenance_req_id : ''}}'>
                                                    <input type='hidden' id="workshopId" name='workshopId'
                                                           value='{{isset($workshopId) ? $workshopId : ''}}'>
                                                    <input type='hidden' id="vessel_inspection_id"
                                                           name='vessel_inspection_id'
                                                           value='{{isset($vessel_inspection_id) ? $vessel_inspection_id : ''}}'>

                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
@endsection


@section('footer-script')
    <!--Load custom script-->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $('#searchResultTable tbody').on('click', '.dltBtn', function () {
            let row_id = $(this).data("taskmonitorid");
            let url = '{{route('mwe.operation.task-data-remove')}}';

            Swal.fire({
                title: 'Are you sure?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: 'GET',
                        url: url,
                        data: {task_monitor_id: row_id},
                        success: function (msg) {
                            if (msg == 0) {
                                Swal.fire({
                                    title: 'Something Went Wrong!!',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                                //return false;
                            } else {
                                Swal.fire({
                                    title: 'Entry Successfully Deleted!',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(function () {
                                    let vessel_inspection_id = $('#vessel_inspection_id').val();
                                    let workshopId = $('#workshopId').val();
                                    let maintenance_req_id = $('#maintenance_req_id').val();
                                    taskDtlList(maintenance_req_id, workshopId, vessel_inspection_id);
                                });
                            }
                        }
                    });
                }
            });
        });

        function taskDtlList(maintenance_req_id, workshopId, vessel_inspection_id) {
            let url = '{{route('mwe.operation.entry-task-monitor-datatable')}}';
            let tblPreventivi = $('#searchResultTable').DataTable({
                processing: true,
                serverSide: true,
                bDestroy: true,
                pageLength: 20,
                bFilter: true,
                lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
                /*serverSide: true,
                bDestroy: true,*/
                ajax: {
                    url: url,
                    data: {
                        maintenance_req_id: maintenance_req_id,
                        workshopId: workshopId,
                        vessel_inspection_id: vessel_inspection_id
                    },
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                "columns": [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                    {"data": "description"},
                    {"data": "action"},
                ],
            });
        }

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
                    url: "{{route('mwe.operation.third-party-new-task-dtl')}}",
                    data: {
                        maintenance_req_id: $('#maintenance_req_id').val(),
                        workshopId: $('#workshopId').val(),
                        vessel_inspection_id: $('#vessel_inspection_id').val(),
                        description: desc
                    },
                    success: function (data) {
                        $('#final-selection-message').html(data.html);
                        $('#description').val('');
                        let vessel_inspection_id = $('#vessel_inspection_id').val();
                        let workshopId = $('#workshopId').val();
                        let maintenance_req_id = $('#maintenance_req_id').val();
                        taskDtlList(maintenance_req_id, workshopId, vessel_inspection_id);
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

        $(document).ready(function () {
            let vessel_inspection_id = $('#vessel_inspection_id').val();
            let workshopId = $('#workshopId').val();
            let maintenance_req_id = $('#maintenance_req_id').val();
            taskDtlList(maintenance_req_id, workshopId, vessel_inspection_id);
            $(".collapse").on('show.bs.collapse', function () {
                $(this).prev(".card-header").find(".bx").removeClass("bx bxs-plus-circle").addClass("bx bxs-minus-circle");
            }).on('hide.bs.collapse', function () {
                $(this).prev(".card-header").find(".bx").removeClass("bx bxs-minus-circle").addClass("bx bxs-plus-circle");
            });
        });
    </script>

@endsection



