@extends('layouts.default')

@section('title')
    Approval Detail
@endsection

@section('header-style')
    <!--Load custom style link or css-->

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
                <form id="add-form" method="post" name="add-form">
                    {!! csrf_field() !!}
                    <input type='hidden' id='thirdparty_req_id' name="thirdparty_req_id"
                           value='{{isset($thirdparty_req_id) ? $thirdparty_req_id : ''}}'>
                    <input type='hidden' id='assign_id' name="assign_id"
                           value='{{isset($assign_id) ? $assign_id : ''}}'>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4 class="card-title mt-2">Third Party Task Detail</h4>
                                    </div>
                                </div>
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
                    </div>
                </form>
            </section>
            <section id="horizontal-vertical">
                <form id="final-results-form" method="post" nam="final-results-form">
                    {!! csrf_field() !!}
                    <input type='hidden' id='thirdparty_req_id' name="thirdparty_req_id"
                           value='{{isset($thirdparty_req_id) ? $thirdparty_req_id : ''}}'>
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
                                                    <th>Select Estimated Completion Date</th>
                                                    <th>Select COMPLETED DATE</th>
                                                    <th>Select Status</th>
                                                    <th>Delete</th>
                                                </tr>
                                                </thead>

                                                <tbody id="resultDetailsBody">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mt-2">

                                    <button type="submit" class="btn btn btn-info shadow btn-secondary"
                                            name="final-results-submission" id="final-results-submission">Submit
                                    </button>&nbsp;

                                    <a type="reset" onclick="if (! confirm('Are you sure to complete the third party works?')) { return false; }" href="{{ route('mwe.operation.task-final-submit', ['id' => $thirdparty_req_id])  }}"
                                       class="btn btn btn-success shadow btn-secondary" id="final-submit"> Approve Task Complete</a>

                                    {{--<button type="button" class="btn btn btn-success shadow btn-secondary"
                                            name="final-results-submission" id="final-results-submission">Approve Task
                                        Complete
                                    </button>&nbsp;--}}
                                </div>
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
                    {"data": "estimated_date_ch"},
                    {"data": "completed_date_ch"},
                    {"data": "task_status_id_ch"},
                    {"data": "action"},
                ],
            });
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
            var answer = confirm('Are you sure?');
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
                    url: "{{route('mwe.operation.add-new-task-dtl')}}",
                    data: {
                        thirdparty_req_id: $('#thirdparty_req_id').val(),
                        assign_id: $('#assign_id').val(),
                        description: desc
                    },
                    success: function (data) {
                        $('#final-selection-message').html(data.html);
                        $('#description').val('');
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
        });

    </script>

@endsection

