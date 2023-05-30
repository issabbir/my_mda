<from method="POST" action="" enctype="multipart/form-data">
    {!! csrf_field() !!}
    <div class="row">
        <div class="col-md-4">
            <div class="row my-1">
                <div class="col-md-12">
                    <label class="input-required">Job Number<span class="required"></span></label>
                    <input type="text" class="form-control" name="job_number" id="job_number_{{$val->id}}"
                           value="{{$val->job_number}}" readonly>
                    <input type="hidden" class="form-control" name="vessel_inspection_id_{{$val->id}}"
                           id="vessel_inspection_id_{{$val->id}}" value="{{$val->id}}">
                    <input type="hidden" class="form-control" name="maintenance_req_id_{{$val->id}}"
                           id="maintenance_req_id_{{$val->id}}" value="{{$val->maintenance_req_id}}">
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="row my-1">
                <div class="col-md-12">
                    <label class="input-required">Requisition Number<span class="required"></span></label>
                    <input type="text" class="form-control" name="requisition_number_{{$val->id}}"
                           id="requisition_number_{{$val->id}}" value="{{$val->requisition_number}}" readonly>
                </div>
            </div>
        </div>
        @php
            use Illuminate\Support\Facades\Auth;
            $getKey = json_encode(Auth::user()->roles->pluck('role_key'));//dd($getKey)
        @endphp
        @if (strpos($getKey, "MDA_XEN") == TRUE)
            @if($val->status=='A')
                <div class="col-md-4" id="hide_show">
                    <div class="d-flex justify-content-end">
                        <a id="go_there" class="btn btn-info btn-secondary ml-1 cursor-pointer" target="_blank"
                           href="{{url('/report/render/RPT_VESSEL_REPORT?xdo=/~weblogic/MWE/RPT_VESSEL_REPORT.xdo'.'&p_maintenance_req_id='.($val->maintenance_req_id).'&P_vessel_inspection_id='.($val->id).'&type=pdf&filename=RPT_VESSEL_REPORT')}}">
                            <i class="bx bx-printer"></i> Workshop Requisition</a>
                    </div>
                </div>
            @endif
        @endif
        @if (strpos($getKey, "MDA_SSAE_M") == TRUE && $val->asw_name!=null)
            <div class="col-md-4">
                <div class="row my-1">
                    <div class="col-md-12">
                        <label class="required">Forward To</label>
                        <input type="text" class="form-control" value="{{$val->asw_name}}" disabled>
                    </div>
                </div>
            </div>
        @elseif (strpos($getKey, "MDA_ASW") == TRUE && $val->xen_name!=null)
            <div class="col-md-4">
                <div class="row my-1">
                    <div class="col-md-12">
                        <label class="required">Forward To</label>
                        <input type="text" class="form-control" value="{{$val->xen_name}}" disabled>
                    </div>
                </div>
            </div>
        @elseif(strpos($getKey, "MDA_XEN") == FALSE)
            <div class="col-md-4">
                <div class="row my-1">
                    <div class="col-md-12">
                        <label class="required">Forward To</label>
                        <select class="select2 form-control" style="width: 100%"
                                id="forward_to_{{$val->id}}" name="forward_to_{{$val->id}}">
                            <option value="">Select One</option>
                            @if(isset($send_to) && !empty($send_to))
                                @foreach($send_to as $value)
                                    <option value="{{$value->emp_id}}">
                                        {{$value->emp_code.' - '.$value->emp_name.' - '.$value->role_name}}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>
        @endif
        {{--        @dd($val->status)--}}
        <div class="col-md-4" style="display: none">
            <div class="row my-1">
                <div class="col-md-12">
                    <label>Status</label>
                    <div>
                         <span class="badge badge-pill badge-info pt-1 w-75 h-75 form-control">
                            <strong>  {{(!$val->status)?'NOT INITIALIZED':App\Helpers\HelperClass::getRequisitionStatus($val->status)}}</strong>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="row my-1">
                <div class="col-md-12">
                    <label class="input-required">Details<span class="required"></span></label>
                    <textarea type="text" name="task_details_{{$val->id}}" readonly id="task_details_{{$val->id}}"
                              placeholder="Write detail task details" class="form-control"
                              oninput="this.value = this.value.toUpperCase()">{{$val->task_details}}</textarea>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="row my-1">
                <div class="col-md-12">
                    <label>Remarks</label>
                    <textarea type="text" name="remarks_{{$val->id}}" id="remarks_{{$val->id}}"
                              @if(isset($val->remarks)) readonly @endif
                              placeholder="Write remark" class="form-control"
                              oninput="this.value = this.value.toUpperCase()">{{$val->remarks}}</textarea>
                </div>
            </div>
        </div>
        @if (strpos($getKey, "MDA_SSAE_M") == TRUE)
            <div class="col-md-4">
                <div class="row my-1">
                    <div class="col-md-12">
                        <label>Remarks of SSAE</label>
                        <textarea type="text" name="remarks_ssae_{{$val->id}}" id="remarks_ssae_{{$val->id}}"
                                  @if(isset($val->remarks_ssae)) readonly @endif
                                  placeholder="Write remark" class="form-control"
                                  oninput="this.value = this.value.toUpperCase()">{{$val->remarks_ssae}}</textarea>
                    </div>
                </div>
            </div>
        @endif
        @if (strpos($getKey, "MDA_ASW") == TRUE || strpos($getKey, "MDA_XEN") == TRUE)
            <div class="col-md-4">
                <div class="row my-1">
                    <div class="col-md-12">
                        <label>Remarks of SSAE</label>
                        <textarea type="text" name="remarks_ssae_{{$val->id}}" id="remarks_ssae_{{$val->id}}"
                                  @if(isset($val->remarks_ssae)) readonly @endif
                                  placeholder="Write remark" class="form-control"
                                  oninput="this.value = this.value.toUpperCase()">{{$val->remarks_ssae}}</textarea>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="row my-1">
                    <div class="col-md-12">
                        <label>Remarks of ASW</label>
                        <textarea type="text" name="remarks_asw_{{$val->id}}" id="remarks_asw_{{$val->id}}"
                                  @if(isset($val->remarks_asw)) readonly @endif
                                  placeholder="Write remark" class="form-control"
                                  oninput="this.value = this.value.toUpperCase()">{{$val->remarks_asw}}</textarea>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="">
                <table class="table table-bordered table-hover">
                    <thead class="bg-rgba-secondary">
                    <tr>
                        <th class="hidden">id</th>
                        <th class="hidden">Requisition id</th>
                        <th class="hidden">Vessel Inspection id</th>
                        <th class="hidden">Product Id</th>
                        <th scope="col">Product</th>
                        <th class="hidden">Unit Id</th>
                        <th scope="col">Unit</th>
                        <th scope="col">Demand Qty</th>
                        <th scope="col">Approved Qty</th>
                        <th scope="col">Collection Qty</th>
                        <th scope="col">Scrap Qty</th>
                        <th scope="col">Used Qty</th>
                        <th class="hidden">status</th>
                        {{--                        <th scope="col">Action</th>--}}
                    </tr>
                    </thead>
                    <tbody class="workshopRequisitionItems_{{$val->id}}">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
{{--    @php
        use Illuminate\Support\Facades\Auth;
        $getKey = json_encode(Auth::user()->roles->pluck('role_key'));//dd($getKey)
    @endphp--}}
    <div class="row">
        <div class="col-md-8 my-1" id="response_{{$val->id}}"></div>
        <div class="col-md-4">
            <div class="row my-1 ">
                <div class="col-md-12">
                    <div class="d-flex justify-content-end col">
                        @if($val->status!=='C')
                            <div class="btn" role="btn" aria-label="Basic example">
                                <button
                                    type="submit"
                                    id="approved_workshop_requisition_{{$val->id}}"
                                    value="A"
                                    data-value="{{ ($val->status=='A')?'R': 'A'}}"
                                    class="btn btn btn-dark mb-1">
                                    @if (strpos($getKey, "MDA_XEN") == TRUE)
                                        Approved
                                    @else
                                        Checked
                                    @endif
                                    {{--{{ ($val->status=='A')?'Received ': 'Checked'}}--}}
                                </button>
                                <button
                                    type="submit"
                                    id="rechecked_workshop_requisition_{{$val->id}}"
                                    value="R"
                                    class="btn btn btn-info mb-1"
                                    {{ ($val->status=='A')?'hidden': ''}}>
                                    Save
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</from>
@section('footer-script')
    <!--Load custom script-->
    @parent
    <script>
        $(document).on('click', '#approved_workshop_requisition_{{$val->id}}', function (e) {
            e.preventDefault();
            let vessel_inspection_id = $('#vessel_inspection_id_{{$val->id}}').val();
            let maintenance_req_id = $('#maintenance_req_id_{{$val->id}}').val();
            let job_number = $('#job_number_{{$val->id}}').val();
            let requisition_number = $('#requisition_number_{{$val->id}}').val();
            let remarks = $('#remarks_{{$val->id}}').val();
            let remarks_ssae = $('#remarks_ssae_{{$val->id}}').val();
            let remarks_asw = $('#remarks_asw_{{$val->id}}').val();
            let task_details = $('#task_details_{{$val->id}}').val();
            let action_status = $('#approved_workshop_requisition_{{$val->id}}').val();
            let forward_to = $('#forward_to_{{$val->id}}').val();
            let req_items = [];
            @if (strpos($getKey, "MDA_XEN") == FALSE)
            if (forward_to == '' || forward_to == undefined || forward_to == null) {
                return sweetAlert('FORWARD TO CAN NOT BE NULL')
            }
            @endif

            $("table tbody.workshopRequisitionItems_{{$val->id}} tr").each(function (index) {
                var currentRow = $(this).closest("tr");
                var req_item_id = currentRow.find("td:eq(0) input[type='hidden']").val();
                var requisition_id = currentRow.find("td:eq(1) input[type='hidden']").val();
                var vessel_inspection_id = currentRow.find("td:eq(2) input[type='hidden']").val();
                var product_id = currentRow.find("td:eq(3) input[type='hidden']").val();
                var unit_id = currentRow.find("td:eq(5) input[type='hidden']").val();
                var demand_qty = currentRow.find("td:eq(7) input[type='text']").val();
                var received_qty = currentRow.find("td:eq(8) input[type='text']").val();
                var collected_qty = currentRow.find("td:eq(9) input[type='hidden']").val();
                var old_return_qty = currentRow.find("td:eq(10) input[type='hidden']").val();
                var used_qty = currentRow.find("td:eq(11) input[type='hidden']").val();
                var status = currentRow.find("td:eq(12) input[type='hidden']").val();
                var obj = {};
                obj.req_item_id = req_item_id;
                obj.requisition_id = requisition_id;
                obj.vessel_inspection_id = vessel_inspection_id;
                obj.product_id = product_id;
                obj.unit_id = unit_id;
                obj.demand_qty = demand_qty;
                obj.old_return_qty = old_return_qty;
                obj.used_qty = used_qty;
                obj.collected_qty = collected_qty;
                obj.received_qty = received_qty;
                obj.status = status;
                req_items.push(obj);
            });
            workshopRequisitionAuthorized(vessel_inspection_id, maintenance_req_id, job_number, requisition_number, remarks, task_details, action_status, req_items, forward_to, remarks_ssae, remarks_asw);
            window.location.reload();
        });
        $(document).on('click', '#rechecked_workshop_requisition_{{$val->id}}', function (e) {
            e.preventDefault();
            var vessel_inspection_id = $('#vessel_inspection_id_{{$val->id}}').val();
            var maintenance_req_id = $('#maintenance_req_id_{{$val->id}}').val();
            var job_number = $('#job_number_{{$val->id}}').val();
            var requisition_number = $('#requisition_number_{{$val->id}}').val();
            var remarks = $('#remarks_{{$val->id}}').val();
            var task_details = $('#task_details_{{$val->id}}').val();
            var action_status = $('#rechecked_workshop_requisition_{{$val->id}}').val();
            var req_items = [];
            $("table tbody.workshopRequisitionItems_{{$val->id}} tr").each(function (index) {
                var currentRow = $(this).closest("tr");
                var req_item_id = currentRow.find("td:eq(0) input[type='hidden']").val();
                var requisition_id = currentRow.find("td:eq(1) input[type='hidden']").val();
                var vessel_inspection_id = currentRow.find("td:eq(2) input[type='hidden']").val();
                var product_id = currentRow.find("td:eq(3) input[type='hidden']").val();
                var unit_id = currentRow.find("td:eq(5) input[type='hidden']").val();
                var demand_qty = currentRow.find("td:eq(7) input[type='text']").val();
                var received_qty = currentRow.find("td:eq(8) input[type='text']").val();
                var collected_qty = currentRow.find("td:eq(9) input[type='hidden']").val();
                var old_return_qty = currentRow.find("td:eq(10) input[type='hidden']").val();
                var used_qty = currentRow.find("td:eq(11) input[type='hidden']").val();
                var status = currentRow.find("td:eq(12) input[type='hidden']").val();
                var obj = {};
                obj.req_item_id = req_item_id;
                obj.requisition_id = requisition_id;
                obj.vessel_inspection_id = vessel_inspection_id;
                obj.product_id = product_id;
                obj.unit_id = unit_id;
                obj.demand_qty = demand_qty;
                obj.old_return_qty = old_return_qty;
                obj.used_qty = used_qty;
                obj.collected_qty = collected_qty;
                obj.received_qty = received_qty;
                obj.status = status;
                req_items.push(obj);
            });
            workshopRequisitionAuthorized(vessel_inspection_id, maintenance_req_id, job_number, requisition_number, remarks, task_details, action_status, req_items);
            window.location.reload();
        });

        function workshopRequisitionAuthorized(vessel_inspection_id, maintenance_req_id, job_number, requisition_number, remarks, task_details, action_status, req_items, forward_to, remarks_ssae, remarks_asw) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax(
                {
                    type: 'POST',
                    url: '{{route('mwe.operation.workshop-requisition-authorized')}}',
                    data: {
                        _token: CSRF_TOKEN,
                        vessel_inspection_id: vessel_inspection_id,
                        maintenance_req_id: maintenance_req_id,
                        job_number: job_number,
                        requisition_number: requisition_number,
                        remarks: remarks,
                        task_details: task_details,
                        action_status: action_status,
                        req_items: req_items,
                        forward_to: forward_to,
                        remarks_ssae: remarks_ssae,
                        remarks_asw: remarks_asw,
                    },
                    success: function (data) {
                        if (data.response.status_code === '1') {
                            document.getElementById("show_task_details_" + ((data.response.vessel_inspection_id))).innerHTML = '<button'
                                + 'class="btn btn-link btn-block text-left btn-secondary"'
                                + 'type="button"'
                                + 'data-toggle="collapse"'
                                + 'id="show_task_details_{{$val->id}}"'
                                + 'data-target="#collapse-{{$val->id}}"'
                                + 'aria-expanded="true" aria-controls="collapse-{{$val->id}}"># {{$val->name}}'
                                + '<i class="bx bxs-plus-circle bx-pull-right bx-md"  style="top: auto"></i>'
                                + '<span id="requisition_status_{{$val->id}}" style="margin-top: 6px" class="bx-pull-right mr-5"> STATUS :'
                                + '<strong>' + data.response.requisition_status + '</strong>'
                                + '</span></button>';
                            $('.workshopRequisitionItems_{{$val->id}}').html(data.html);
                            $("#response_" + ((data.response.vessel_inspection_id))).html('<div class="alert alert-success alert-dismissible fade show" role="alert">'
                                + '<strong>' + data.response.status_message + '</strong>'
                                + '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                                + '<span aria-hidden="true">&times;</span>'
                                + '</button></div>');
                        } else {
                            $("#response_" + ((data.response.vessel_inspection_id))).html('<div class="alert alert-danger alert-dismissible fade show" role="alert">'
                                + '<strong>' + data.response.status_message + '</strong>'
                                + '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                                + '<span aria-hidden="true">&times;</span>'
                                + '</button></div>');
                        }
                    }
                }
            );
        }

        $(document).ready(function () {
            $(document).on('click', '#show_task_details_{{$val->id}}', function (e) {
                e.preventDefault();
                var vessel_inspection_id = $('#vessel_inspection_id_{{$val->id}}').val();
                showTaskDetail(vessel_inspection_id);
            });

            function showTaskDetail(vessel_inspection_id) {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax(
                    {
                        type: 'POST',
                        url: '{{route('mwe.operation.workshop-req-auth-task-details')}}',
                        data: {
                            _token: CSRF_TOKEN,
                            vessel_inspection_id: vessel_inspection_id,
                        },
                        success: function (data) {
                            // console.log('onclickinspection',data);
                            $('.workshopRequisitionItems_{{$val->id}}').html(data.html);
                        }
                    }
                );
            }

            $(document).on('click', '#add_workshop_req_item_{{$val->id}}', function (e) {
                e.preventDefault();
                var vessel_inspection_id = $('#vessel_inspection_id_{{$val->id}}').val();
                var product_id = $('#product_id_{{$val->id}}').val();
                var demand_qty = $('#demand_qty_{{$val->id}}').val();
                var unit_id = $('#unit_id_{{$val->id}}').val();
                console.log(vessel_inspection_id, product_id, demand_qty, unit_id);
                addWorkShopRequisitionItem(vessel_inspection_id, product_id, demand_qty, unit_id);
            });

            function addWorkShopRequisitionItem(vessel_inspection_id, product_id, demand_qty, unit_id) {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax(
                    {
                        type: 'POST',
                        url: '{{route('mwe.operation.workshop-requisition-item-add')}}',
                        data: {
                            _token: CSRF_TOKEN,
                            vessel_inspection_id: vessel_inspection_id,
                            product_id: product_id,
                            demand_qty: demand_qty,
                            unit_id: unit_id,
                        },
                        success: function (data) {
                            if (data.response.status == true) {
                                $('.workshopRequisitionItems_{{$val->id}}').html(data.html);
                            } else {
                            }

                        }
                    }
                );
            }

            $(document).on('click', '#remove_workshop_req_item_{{$val->id}}', function (e) {
                e.preventDefault();
                var vessel_inspection_id = $('#vessel_inspection_id_{{$val->id}}').val();
                var workshop_req_item_id = $(this).attr('data-workshop_req_item_id');
                console.log(workshop_req_item_id);
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax(
                    {
                        type: 'DELETE',
                        url: '{{route('mwe.operation.remove-workshop-req-item')}}',
                        data: {
                            _token: CSRF_TOKEN,
                            vessel_inspection_id: vessel_inspection_id,
                            workshop_req_item_id: workshop_req_item_id
                        },
                        success: function (data) {
                            if (data.response.status == true) {
                                $('.workshopRequisitionItems_{{$val->id}}').html(data.html);
                            } else {
                            }
                        }
                    }
                );
            });


            function addWorkShopRequisitionItem(vessel_inspection_id, product_id, demand_qty, unit_id) {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax(
                    {
                        type: 'POST',
                        url: '{{route('mwe.operation.workshop-requisition-item-add')}}',
                        data: {
                            _token: CSRF_TOKEN,
                            vessel_inspection_id: vessel_inspection_id,
                            product_id: product_id,
                            demand_qty: demand_qty,
                            unit_id: unit_id,
                        },
                        success: function (data) {
                            if (data.response.status == true) {
                                $('.workshopRequisitionItems_{{$val->id}}').html(data.html);
                            } else {
                            }

                        }
                    }
                );
            }
        });
    </script>

@endsection
