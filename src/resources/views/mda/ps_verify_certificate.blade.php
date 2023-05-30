@extends('layouts.default')

@section('title')
    Verify certificate
@endsection

@section('header-style')
    <!--Load custom style link or css-->
@endsection

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
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="card-title">Pilotage certificate list</h3>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Filter by status</label>
                                            <select class="form-control statusFilter ">
                                                <option value="A">Requested</option>
                                                <option value="C">Approved</option>
                                            </select>
                                        </div>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 table-responsive">
                                        <div class="table-responsive row">
                                            <table class="table table-sm datatable" id="ps_verify_certificate">
                                                <thead>
                                                <tr>
                                                    <th style="width: 2%">SL</th>
                                                    <th style="width: 5%">Type</th>
                                                    <th>Vessel</th>
                                                    <th>Pilot</th>
                                                    <th style="width: 10%">Pilot Boarded at</th>
                                                    <th style="width: 10%">Pilot left at</th>
                                                    <th>Remark</th>
                                                    <th>Invoice No.</th>
                                                    {{--<th>Status</th>--}}
                                                    <th>Actions</th>
                                                    <th style="display:none;">
                                                </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('mda.approval.workflowmodal')

@include('mda.approval.workflowselect')

@section('footer-script')
    <!--Load custom script-->
    <script>
        let userRoles = '@php echo json_encode(Auth::user()->roles->pluck('role_key')); @endphp';
        $('#ps_verify_certificate tbody').on('click', '.workflowBtn', function () {
            let row = $(this).data("pilotageid");
            //var data_row = $('#ps_verify_certificate').DataTable().row($(this).parents('tr')).data();
            //var row_id = data_row.id;
            //alert(row_id);
            getFlow(row);
        });

        function getFlow(row_id) {
            let myModal = $('#workflowM');
            $('#application_id_flow').val(row_id);
            $('#t_name').val('mda.pilotages');
            $('#c_name').val('id');
            $.ajax({
                type: 'GET',
                url: '/get-approval-list',
                success: function (msg) {
                    $("#flow_id").html(msg.options);
                }
            });
            myModal.modal({show: true});
            return false;
        }

        $('#ps_verify_certificate tbody').on('click', '.approveBtn', function () {
            let row = $(this).data("pilotageid");
            //var data_row = $('#ps_verify_certificate').DataTable().row($(this).parents('tr')).data();
            //var row_id = data_row.id;
            goFlow(row);
        });

        function goFlow(row_id) {
            let myModal = $('#status-show');
            let tmp = null;
            let t_name = 'mda.pilotages';
            let c_name = 'id';

            $.ajax({
                async: false,
                type: 'GET',
                url: '/get-workflow-id',
                data: {row_id: row_id, t_name: t_name, c_name: c_name},
                success: function (msg) {
                    $("#workflow_id").val(msg);
                    tmp = msg;
                }
            });
            $("#object_id").val(row_id);
            $("#get_url").val(window.location.pathname.slice(1) + '?id=' + row_id + '&pop=true');
            $.ajax({
                type: 'GET',
                url: '/approval',
                data: {workflowId: tmp, objectid: row_id},
                success: function (msg) {
                    let wrkprc = msg.workflowProcess;
                    if (typeof wrkprc === 'undefined' || wrkprc === null || wrkprc.length === 0) {
                        $('#current_status').hide();
                    } else {
                        $('#current_status').show();
                        $("#step_name").text(msg.workflowProcess[0].workflow_step.workflow);
                        $("#step_approve_by").text('By ' + msg.workflowProcess[0].user.emp_name);
                        $("#step_time").text(msg.workflowProcess[0].insert_date);
                        $("#step_approve_desig").text(msg.workflowProcess[0].user.designation);
                        $("#step_note").text(msg.workflowProcess[0].note);
                    }

                    let steps = "";
                    $('.step-progressbar').html(steps);
                    $.each(msg.progressBarData, function (j) {
                        steps += "<li data-step=" + msg.progressBarData[j].process_step + " class='step-progressbar__item'>" + msg.progressBarData[j].workflow + "</li>"
                    });
                    $('.step-progressbar').html(steps);

                    let content = "";
                    $.each(msg.workflowProcess, function (i) {
                        let note = msg.workflowProcess[i].note;
                        if (note == null) {
                            note = '';
                        }
                        content += "<div class='row d-flex justify-content-between px-1'>" +
                            "<div class='hel'>" +
                            "<span class='ml-1 font-medium'>" +
                            "<h5 class='text-uppercase'>" + msg.workflowProcess[i].workflow_step.workflow + "</h5>" +
                            "</span>" +
                            "<span>By " + msg.workflowProcess[i].user.emp_name + "</span>" +
                            "</div>" +
                            "<div class='hel'>" +
                            "<span class='btn btn-secondary btn-sm mt-1' style='border-radius: 50px'>" + msg.workflowProcess[i].insert_date + "</span>" +
                            "<br>" +
                            "<span style='margin-left: .3rem'>" + msg.workflowProcess[i].user.designation + "</span>" +
                            "</div>" +
                            "</div>" +
                            "<hr>" +
                            "<span class='m-b-15 d-block border p-1' style='border-radius: 5px'>" + note + "" +
                            "</span><hr>";//msg.workflowProcess[i].insert_date;
                    });

                    $('#content_bdy').html(content);

                    if (msg.current_step && msg.current_step.process_step) {
                        $('.step-progressbar li').each(function (i) {

                            if ($(this).data('step') > msg.current_step.process_step) {
                                $(this).addClass('step-progressbar__item step-progressbar__item--active');
                            } else {
                                $(this).addClass('step-progressbar__item step-progressbar__item--complete');
                            }
                        })
                    } else {
                        $('.step-progressbar li').addClass('step-progressbar__item step-progressbar__item--active');
                    }

                    $("#status_id").html(msg.options);

                    if ($.isEmptyObject(msg.next_step)) {
                        $(".no-permission").css("display", "block");
                        $(document).find('#hide_portion').hide();
                    } else if (JSON.stringify(userRoles).indexOf(msg.next_step.user_role) > -1) {
                        if (msg.next_step.workflow_key == 'APPROVE') {
                            $(document).find('.no-permission').hide();
                            $(document).find('#hide_portion').show();
                            $(document).find('#close_btn').hide();
                            $(document).find('#save_btn').hide();
                            $(document).find('#hide_div').hide();
                            $(document).find('#approve_btn').show();
                            $(document).find('#bonus_id_prm').val('277');
                        } else {
                            $(document).find('.no-permission').hide();
                            $(document).find('#hide_portion').show();
                        }

                    } else {
                        $(".no-permission").css("display", "block");
                        $(document).find('#hide_portion').hide();
                    }
                }
            });
            myModal.modal({show: true});
            return false;
        }

        $(document).ready(function () {
            $("#workflow_assign_form").attr('action', '{{ route('get-approval-post') }}');
            $("#workflow_form").attr('action', '{{ route('approval-post') }}');

            let certificateTable = $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 20,
                lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
                initComplete: function (settings, json) {
                    $('body').find('.dataTables_scrollBody').css("height", "auto");
                    $('body').find('.dataTables_scrollBody').css("max-height", "300px");
                },
                ajax: {
                    url: '{{ route('ps-verify-certificate-datatable', isset($data->id)?$data->id:0 ) }}',
                    type: 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: function (d) {
                        d.searched_status = $(".statusFilter").val();
                    }
                },
                "columns": [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                    {"data": "pilotage_type"},
                    {"data": "vessel_name"},
                    {"data": "cpa_pilot"},
                    {"data": "pilot_borded_at"},
                    {"data": "pilot_left_at"},
                    {"data": "verify_remarks"},
                    // {"data":"status"},
                    {"data": "invoice_no"},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                    {"data": "id"},
                ],
                language: {
                    paginate: {
                        next: '<i class="bx bx-chevron-right">',
                        previous: '<i class="bx bx-chevron-left">'
                    }
                }
            });
            certificateTable.columns([9]).visible(false);

            $(".statusFilter").on("change", function () {
                certificateTable.draw();
            });
        });

        /*function addButton(text, style, id) {
            return $('<button class="'+style+'" style="margin-left:5px;" id="'+id+'">' + text + '</button>');
        }*/

        function approve_disapprove(e) {
            let pilotageId = $(e).data("pilotageid");
            let status = $(e).data("status");
            let rowId = "#" + $(e).attr("id");
            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            let msg;
            if (status == "C") {
                msg = "approve";
            } else {
                msg = "reject";
            }

            /* var buttons = $('<div>')
                 .append(addButton('Cancel',"btn btn-primary","cancel_"))
                 .append(addButton('Add remarks',"btn btn-info","remark_"))
                 .append(addButton("Ok","btn btn-primary","ok_"));
             swal({
                 title: "Are you sure want to "+msg+" this?",
                 text: "You won't be able to revert this!",
                 type: "warning",
                 html:buttons,
                 showConfirmButton: false,
                 showCancelButton: false,
                 onOpen:function (e) {
                     $("#cancel_").on('click',function () {
                         swal.close();
                     });

                     $("#remark_").on('click',function () {

                     });

                     $("#ok_").on('click',function () {
                         console.log("Ok");
                     });
                 }
             })*/
            swal({
                title: "Are you sure want to " + msg + " this?",
                text: "You won't be able to revert this!",
                type: "warning",
                html: "<textarea id='remark' class='form-control' placeholder='Add remarks..'></textarea>",
                preConfirm: function () {
                    return new Promise(function (response) {
                        response({remarks: $('#remark').val()})
                    })
                },
                showCancelButton: !0,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, " + msg + " it!",
                confirmButtonClass: "btn btn-primary",
                cancelButtonClass: "btn btn-danger ml-1",
                buttonsStyling: !1
            }).then(function (e) {
                if (e.value || e.value.remarks) {
                    let remark = e.value.remarks;
                    $.ajax({
                        type: "POST",
                        url: "/ps-verify-certificate/approve",
                        data: {_token: CSRF_TOKEN, remark: remark, "status": status, "pilotage_id": pilotageId},
                        dataType: "JSON",
                        success: function (data) {
                            //TODO:successmessage
                            if (data.success === true) {
                                var table = $('.dataTable').DataTable();
                                table.ajax.reload(null, false);
                                swal("Done!", data.status_message, "success");
                            } else {
                                swal("Error!", data.status_message, "error");
                            }
                        },
                        error: function (data) {
                            console.log(data);
                        }
                    });

                } else {
                    e.dismiss;
                }
            }, function (dismiss) {
                return false;
            })
        }


    </script>

@endsection
