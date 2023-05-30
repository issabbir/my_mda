@extends('layouts.default')

@section('title')
    Works Request Detail
@endsection

@section('header-style')
    <!--Load custom style link or css-->
    <style>
        a.disabled {
            pointer-events: none;
            cursor: default;
        }
    </style>
@endsection
@section('content')

    <div class="card">
        <div class="card-body">
            <section id="horizontal-vertical">
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
                <div class="row">
                    <div class="col" id="final-selection-message"></div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="card-title mt-2 ml-2">Task Monitor</h4>
                    </div>
                </div>
                <form id="change-status" method="post" name="change-status">
                    {!! csrf_field() !!}
                    <input type='hidden' id='thirdparty_req_id' name="thirdparty_req_id"
                           value='{{isset($thirdparty_req_id) ? $thirdparty_req_id : ''}}'>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                {{--<div class="row">
                                    <div class="col-md-6">
                                        <h4 class="card-title mt-2">Change Task Status</h4>
                                    </div>
                                </div>--}}
                                <div class="row mt-2 ml-2">
                                    <div class="col-md-3">
                                        <div id="start-no-field" class="form-group">
                                            <label class="required">Task Detail</label>
                                            <select id="task_monitor_id" name="task_monitor_id" class="form-control select2 task_monitor_id">
                                                <option value="">Select one</option>
                                                @foreach($tasks as $task)
                                                    <option
                                                        value="{{$task->task_monitor_id}}">{{$task->description}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Task Status</label>
                                            <input type="text" class="form-control" id="show_status" disabled/>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Estimated COMPLETION DATE</label>
                                            <input type="text" class="form-control" id="show_est_comp_date" disabled/>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Completed Date</label>
                                            <input type="text" class="form-control" id="show_comp_date" disabled/>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div id="start-no-field" class="form-group">
                                            <label>Status Change</label>
                                            <select name="status" id="status" class="form-control">
                                                <option value="0">Select one</option>
                                                <option value="1">PENDING</option>
                                                <option value="2">IN PROGRESS</option>
                                                <option value="3">COMPLETED</option>
                                                <option value="4">NOT POSSIBLE</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div id="start-no-field" class="form-group">
                                            <label>ESTIMATED COMPLETION DATE</label>
                                            <div class="input-group date"
                                                 onfocusout="$(this).datetimepicker('hide')"
                                                 id="est_com_date" data-target-input="nearest">
                                                <input type="text" name="est_com_date"
                                                       class="form-control est_com_date"
                                                       data-target="#est_com_date" readonly
                                                       data-toggle="datetimepicker" autocomplete="off"/>
                                                <div class="input-group-append"
                                                     data-target="#est_com_date"
                                                     data-toggle="datetimepicker">
                                                    <div class="input-group-text">
                                                        <i class="bx bx-calendar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div id="start-no-field" class="form-group">
                                            <label>COMPLETED DATE</label>
                                            <div class="input-group date"
                                                 onfocusout="$(this).datetimepicker('hide')"
                                                 id="completed_date" data-target-input="nearest">
                                                <input type="text" name="completed_date"
                                                       class="form-control completed_date"
                                                       data-target="#completed_date" readonly
                                                       data-toggle="datetimepicker" autocomplete="off"/>
                                                <div class="input-group-append"
                                                     data-target="#completed_date"
                                                     data-toggle="datetimepicker">
                                                    <div class="input-group-text">
                                                        <i class="bx bx-calendar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3 mt-2">
                                        <div id="start-no-field"
                                             class="form-group">
                                            <button class="btn btn-secondary hvr-underline-reveal" id="new-task"
                                                    type="button">
                                                <i class="bx bxs-cloud-upload"></i> Submit
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
                <form id="final-results-form" method="post" name="final-results-form">
                    {!! csrf_field() !!}
                    <input type='hidden' id='thirdparty_req_id' name="thirdparty_req_id"
                           value='{{isset($thirdparty_req_id) ? $thirdparty_req_id : ''}}'>
                    <input type='hidden' id='maintenance_req_id' name="maintenance_req_id"
                           value='{{isset($maintenance_req_id) ? $maintenance_req_id : ''}}'>
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
                                                    <th>Status</th>
                                                    <th>Estimated Completion Date</th>
                                                    <th>COMPLETED DATE</th>
                                                    {{--<th>Select Estimated Completion Date</th>
                                                    <th>Select COMPLETED DATE</th>
                                                    <th>Select Status</th>--}}
                                                    {{--<th>Delete</th>--}}
                                                </tr>
                                                </thead>

                                                <tbody id="resultDetailsBody">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="d-flex justify-content-start">
                                            <a type="reset"
                                               href="{{ route('mwe.operation.third-party-tasks-monitor-index') }}"
                                               class="btn btn-light-secondary mr-1" id="final-submit"> Back</a>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex justify-content-end">
                                            @php
                                                $getKey = json_encode(Auth::user()->roles->pluck('role_key'));
                                            @endphp
                                            @if (strpos($getKey, "MDA_XEN") == FALSE)
                                                {{--<button type="submit" class="btn btn btn-info shadow btn-secondary"
                                                        name="final-results-submission"
                                                        id="final-results-submission">Submit
                                                </button>--}}&nbsp;
                                            @else
                                                <a type="reset"
                                                   onclick="if (! confirm('Are you sure to complete the third party works?')) { return false; }"
                                                   href="{{ route('mwe.operation.task-final-submit', ['id' => $thirdparty_req_id])  }}"
                                                   class="btn btn btn-success shadow btn-secondary @if($total!=0) disabled @endif"
                                                   id="final-submit"> Approve Task Complete</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{--<div class="d-flex justify-content-end mt-2">

                                    <button type="submit" class="btn btn btn-info shadow btn-secondary"
                                            name="final-results-submission" id="final-results-submission">Submit
                                    </button>&nbsp;

                                    <a type="reset" onclick="if (! confirm('Are you sure to complete the third party works?')) { return false; }" href="{{ route('mwe.operation.task-final-submit', ['id' => $thirdparty_req_id])  }}"
                                       class="btn btn btn-success shadow btn-secondary" id="final-submit"> Approve Task Complete</a>

                                    --}}{{--<button type="button" class="btn btn btn-success shadow btn-secondary"
                                            name="final-results-submission" id="final-results-submission">Approve Task
                                        Complete
                                    </button>&nbsp;--}}{{--
                                </div>--}}
                            </div>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>

@endsection

@section('footer-script')

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        function convertDate(inputFormat) {
            function pad(s) {
                return (s < 10) ? '0' + s : s;
            }

            var d = new Date(inputFormat)
            return [d.getFullYear(), pad(d.getMonth() + 1), pad(d.getDate())].join('-')
        }
        $('select[name="task_monitor_id"]').on('change', function() {
            let task_monitor_id = $(this).val();
            if (task_monitor_id) {
                $.ajax({
                    type: 'GET',
                    url: "{{route('mwe.operation.get-monitor-data')}}",
                    data: {task_monitor_id: task_monitor_id},
                    success: function (data) {
                        $("#show_status").val(data.result.task_status);
                        if(data.result.estimated_date!=null){
                            $("#show_est_comp_date").val(convertDate(data.result.estimated_date));
                        }else{
                            $("#show_est_comp_date").val('');
                        }

                        if(data.result.completed_date!=null){
                            $("#show_comp_date").val(convertDate(data.result.completed_date));
                        }else{
                            $("#show_comp_date").val('');
                        }

                        console.log(data.result);
                    },
                    error: function (data) {
                        alert('error');
                    }
                });
            }
        });

        $("#status").change(function(){
            if(this.value==0){
                $(".est_com_date").prop('readonly', true);
                $(".completed_date").prop('readonly', true);
                $(".est_com_date").val('');
                $(".completed_date").val('');
            }else if(this.value==1){
                $(".est_com_date").prop('readonly', true);
                $(".completed_date").prop('readonly', true);
                $(".est_com_date").val('');
                $(".completed_date").val('');
            }else if(this.value==2){
                $(".est_com_date").prop('readonly', false);
                $(".completed_date").prop('readonly', true);
                $(".est_com_date").val('');
                $(".completed_date").val('');
            }else if(this.value==3){
                $(".est_com_date").prop('readonly', true);
                $(".completed_date").prop('readonly', false);
                $(".est_com_date").val('');
                $(".completed_date").val('');
            }else if(this.value==4){
                $(".est_com_date").prop('readonly', true);
                $(".completed_date").prop('readonly', true);
                $(".est_com_date").val('');
                $(".completed_date").val('');
            }

        });


        minSysDatePicker("#est_com_date");
        minSysDatePicker("#completed_date");

        function minSysDatePicker(getId) {
            $(getId).datetimepicker({

                format: 'DD-MM-YYYY',
                minDate: new Date(),
                useCurrent: false,
                widgetPositioning: {
                    horizontal: 'left',
                    vertical: 'bottom'
                },
                // format: 'L',
                icons: {
                    time: 'bx bx-time',
                    date: 'bx bxs-calendar',
                    up: 'bx bx-up-arrow-alt',
                    down: 'bx bx-down-arrow-alt',
                    previous: 'bx bx-chevron-left',
                    next: 'bx bx-chevron-right',
                    today: 'bx bxs-calendar-check',
                    clear: 'bx bx-trash',
                    close: 'bx bx-window-close'
                }
            });
        }

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
                                    /*let maintenance_req_id = $('#maintenance_req_id').val();
                                    taskDtlList(maintenance_req_id);*/
                                    let thirdparty_req_id = $('#thirdparty_req_id').val();
                                    taskDtlList(thirdparty_req_id);
                                });
                            }
                        }
                    });
                }
            });
        });

        function call_date_picker(e) {
            datePicker(e);
        }

        function taskDtlList(thirdparty_req_id) {
            let url = '{{route('mwe.operation.task-monitor-datatable')}}';
            let tblPreventivi = $('#searchResultTable').DataTable({
                processing: true,
                serverSide: true,
                bDestroy: true,
                pageLength: 20,
                async: true,
                bFilter: true,
                lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
                /*serverSide: true,
                bDestroy: true,*/
                ajax: {
                    url: url,
                    data: {thirdparty_req_id: thirdparty_req_id},
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                "columns": [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                    {"data": "description"},
                    {"data": "task_status"},
                    {"data": "estimated_date"},
                    {"data": "completed_date"},
                    /*{"data": "estimated_date_ch"},
                    {"data": "completed_date_ch"},
                    {"data": "task_status_id_ch"},*/
                    /*{"data": "action"},*/
                ],
            });
            /*let url_status = '{{route('mwe.operation.status-check')}}';
            $.ajax({
                type: 'POST',
                'headers': {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                url: url_status,
                data: {
                    thirdparty_req_id: thirdparty_req_id,
                },
                success: function (data) {
                    if(data!='0'){
                        $("#elementID").prop("disabled", true);
                    }
                },
                error: function (data) {
                    alert('error');
                }
            });*/
        }

        $(".add-task-dtl").click(function () {
            let description = $("#description").val();
            if (description) {
                let markup = "<tr><td><input type='checkbox' name='record'>" +
                    "<input type='hidden' name='tab_description[]' value='" + description + "'>" +
                    "</td><td>" + description + "</td>";
                $("#description").val("");
                $("#table-doc tbody").append(markup);
            } else {
                Swal.fire({
                    title: 'Fill required value.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });

        $(".delete-task-dtl").click(function () {
            $("#table-doc tbody").find('input[name="record"]').each(function () {
                if ($(this).is(":checked")) {
                    $(this).parents("tr").remove();
                }
            });
        });

        $(document).ready(function () {
            let thirdparty_req_id = $('#thirdparty_req_id').val();
            taskDtlList(thirdparty_req_id);
        });

        $('#new-task').on('click', function (e) {
            e.preventDefault();
            let answer = confirm('Are you sure?');
                if (answer == true) {
                    $.ajax({
                        type: 'POST',
                        'headers': {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        url: "{{route('mwe.operation.change-info')}}",
                        data: {
                            task_monitor_id: $('#task_monitor_id').val(),
                            status: $('#status').val(),
                            est_com_date: $('.est_com_date').val(),
                            completed_date: $('.completed_date').val(),
                        },
                        success: function (data) {
                            $('#final-selection-message').html(data.html);
                            $(".task_monitor_id").val('').trigger('change');
                            $(".est_com_date").val('');
                            $(".completed_date").val('');
                            $(".est_com_date").prop('readonly', true);
                            $(".completed_date").prop('readonly', true);
                            $("#show_est_comp_date").val('');
                            $("#show_comp_date").val('');
                            $("#status").val('0');
                            $("#show_status").val('');
                            let thirdparty_req_id = $('#thirdparty_req_id').val();
                            taskDtlList(thirdparty_req_id);
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

        $('#final-results-form').on('submit', function (e) {
            e.preventDefault();
            var answer = confirm('Are you sure?');

            if (answer == true) {
                $.ajax({
                    type: 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    url: "{{route('mwe.operation.task-dtl-data-submit')}}",
                    data: $(this).serialize(),
                    success: function (data) {
                        $('#final-selection-message').html(data.html);
                        let maintenance_req_id = $('#thirdparty_req_id').val();
                        taskDtlList(maintenance_req_id);
                    },
                    error: function (data) {
                        alert('error');
                    }
                });
            } else {
                $('#final-selection-message').html('');
            }
        });

    </script>

@endsection

