<from method="POST" action="" id="submit_workshop_requisitions_{{$val->id}}"
      name="submit_workshop_requisitions_{{$val->id}}" enctype="multipart/form-data">
    {!! csrf_field() !!}
    @php
        $is_readonly='readonly'
    @endphp
    {{--<ul class="nav nav-tabs nav-justified" id="myTab2" role="tablist">
        <li class="nav-item">
            <a class="nav-link" id="home-tab-justified" data-toggle="tab" href="#home-just" role="tab" aria-controls="home-just" aria-selected="false">
                Home
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" id="profile-tab-justified" data-toggle="tab" href="#profile-just" role="tab" aria-controls="profile-just" aria-selected="true">
                Assign Third Party
            </a>
        </li>
    </ul>
    <div class="tab-content pt-1">
        <div class="tab-pane" id="home-just" role="tabpanel" aria-labelledby="home-tab-justified">
            <p>
                Biscuit powder jelly beans. Lollipop candy canes croissant icing chocolate cake. Cake fruitcake powder
                pudding pastry.Danish fruitcake bonbon bear claw gummi bears apple pie. Chocolate sweet topping
                fruitcake cake.
            </p>
        </div>
        <div class="tab-pane active" id="profile-just" role="tabpanel" aria-labelledby="profile-tab-justified">
            <p>
                Chocolate cake icing tiramisu liquorice toffee donut sweet roll cake. Cupcake dessert icing dragée
                dessert. Liquorice jujubes cake tart pie donut. Cotton candy candy canes lollipop liquorice chocolate
                marzipan muffin pie liquorice.
            </p>
        </div>
    </div>--}}
    @if($val->tp_req_yn!='C' && $val->status!='A' && $val->status!='I')
        <div class="row">
            <div class="d-flex justify-content-end col">
                <a type="reset"
                   href="{{ route('mwe.operation.third-party-assign', ['maintenance_req_id' => $val->maintenance_req_id,'workshopId' => $val->workshop_id,'vessel_inspection_id' => $val->id])  }}{{--{{route("duty-roster-index")}}--}}"
                   class="btn btn-light-secondary mt-2">@if($val->tp_req_yn=='Y') View Details @else Request for Works @endif </a>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-md-4">
            <div class="row my-1">
                <div class="col-md-12">
                    <label class="input-required">Job Number<span class="required"></span></label>
                    <input
                        type="text"
                        class="form-control"
                        name="job_number"
                        required
                        id="job_number_{{$val->id}}"
                        {{--value="{{$val->inspector_job_number}}" readonly--}}
                        value="{{$val->job_number}}" readonly
                        {{(in_array($val->status,\App\Enums\Mwe\ConfigRole::can_not_edit_requisition_master))?$is_readonly:''}}>
                    <input
                        type="hidden"
                        class="form-control"
                        name="vessel_inspection_id_{{$val->id}}"
                        id="vessel_inspection_id_{{$val->id}}"
                        value="{{$val->id}}">
                    <input
                        type="hidden"
                        class="form-control"
                        name="maintenance_req_id_{{$val->id}}"
                        id="maintenance_req_id_{{$val->id}}"
                        value="{{$val->maintenance_req_id}}">
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="row my-1">
                <div class="col-md-12">
                    <label class="input-required">Requisition Number<span class="required"></span></label>
                    <input
                        type="text" @if(isset($val->requisition_number)) readonly @endif
                        class="form-control"
                        name="requisition_number_{{$val->id}}"
                        id="requisition_number_{{$val->id}}"
                        value="{{$val->requisition_number}}"
                        {{(in_array($val->status,\App\Enums\Mwe\ConfigRole::can_not_edit_requisition_master))?$is_readonly:''}}>
                    {{--<input
                        type="hidden"
                        class="form-control"
                        name="vessel_inspection_id_{{$val->id}}"
                        id="vessel_inspection_id_{{$val->id}}"
                        value="{{$val->id}}">
                    <input
                        type="hidden"
                        class="form-control"
                        name="maintenance_req_id_{{$val->id}}"
                        id="maintenance_req_id_{{$val->id}}"
                        value="{{$val->maintenance_req_id}}">--}}
                </div>
            </div>
        </div>

        @php
            use Illuminate\Support\Facades\Auth;
            $getKey = json_encode(Auth::user()->roles->pluck('role_key'));
        @endphp

        @if($val->status !="C" && $val->status !="A")
            <div class="col-md-3">
                <div class="row my-1">
                    <div class="col-md-12">
                        <label class="required">Forward To</label>
                        @if (strpos($getKey, "MDA_SAE_M") == TRUE && $val->forward_to_ssae!='')
                            <input
                                type="text"
                                class="form-control" disabled
                                value="{{$val->ssae_code.' - '.$val->ssae_name}}">
                            <input
                                type="hidden"
                                class="form-control"
                                name="forward_to_{{$val->id}}"
                                id="forward_to_{{$val->id}}"
                                value="{{$val->forward_to_ssae}}">
                        @elseif (strpos($getKey, "MDA_SSAE_M") == TRUE && $val->forward_to_asw!='')
                            <input
                                type="text"
                                class="form-control" disabled
                                value="{{$val->asw_code.' - '.$val->asw_name}}">
                            <input
                                type="hidden"
                                class="form-control"
                                name="forward_to_{{$val->id}}"
                                id="forward_to_{{$val->id}}"
                                value="{{$val->forward_to_asw}}">
                        @else
                            <select class="select2 form-control" style="width: 100%"
                                    id="forward_to_{{$val->id}}" name="forward_to_{{$val->id}}">
                                <option value="">Select One</option>
                                @if(isset($send_to) && !empty($send_to))
                                    @foreach($send_to as $value)
                                        <option
                                            value="{{$value->emp_id}}">{{$value->emp_code.' - '.$value->emp_name.' - '.$value->role_name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        @endif

                    </div>
                </div>
            </div>
        @endif

        <input type="hidden" name="req_status_{{$val->id}}" id="req_status_{{$val->id}}"
               value="{{(!$val->status)?'P':$val->status}}">
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="row my-1">
                <div class="col-md-12">
                    <label>Remarks</label>
                    <input
                        type="text" disabled
                        class="form-control"
                        value="{{$val->show_remarks}}">
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="row my-1">
                <div class="col-md-3">
                    <label>Attachment</label>
                    @if($val->attachment)
                        {{--<a class="mt-1" href="{{ route('mwe.operation.inspection-order-download', [$val->id]) }}"
                           target="_blank"><i
                                class="bx bx-download"></i></a>--}}
                        <a target="_blank" class="form-control"
                           style="text-align: center;"
                           href="{{ route('mwe.operation.inspection-order-download',$val->job_dtl_id)}}"><i
                                class="bx bx-download"></i></a>
                    @else <p class="mt-1">No Attachment Found!!!</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="row my-1">
                <div class="col-md-12">
                    <label class="input-required">Details<span class="required"></span></label>
                    <textarea
                        type="text"
                        name="task_details_{{$val->id}}"
                        id="task_details_{{$val->id}}"
                        placeholder="Write detail task details"
                        class="form-control"
                        oninput="this.value = this.value.toUpperCase()"
                        {{(in_array($val->status,\App\Enums\Mwe\ConfigRole::can_not_edit_requisition_master))?$is_readonly:''}}>{{$val->task_details}}</textarea>
                </div>
            </div>
        </div>
    </div>
    <div
        class="row" {{(in_array($val->status,\App\Enums\Mwe\ConfigRole::can_not_edit_requisition_master))?'hidden':''}}>
        <div class="col-md-12">
            <div class="row my-1">
                <div class="col-md-12">
                    <label>Remarks</label>
                    <textarea
                        type="text"
                        name="remarks_{{$val->id}}"
                        id="remarks_{{$val->id}}"
                        placeholder="Write remark"
                        class="form-control"
                        oninput="this.value = this.value.toUpperCase()">{{$val->remarks}}</textarea>
                </div>
            </div>
        </div>
    </div>
    <div
        class="row" {{(in_array($val->status,\App\Enums\Mwe\ConfigRole::can_not_edit_requisition_master))?'hidden':''}}>
        <div class="col-md-3">
            <div class="row my-1">
                <div class="col-md-12">
                    {{--@include('mwe.partial.requisition_product_search')--}}
                    <label class="input-required">Product<span class="required"></span></label>
                    <select name="product_id" id="product_id_{{$val->id}}" class="form-control select2" style="width: 100%">
                        <option value="">Select one</option>
                        @forelse($products as $unit)
                            <option value="{{ $unit->id }}">{{$unit->name}}</option>
                        @empty
                            <option value="">Product List empty</option>
                        @endforelse
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="row my-1">
                <div class="col-md-12">
                    <label class="input-required">Quantity<span class="required"></span></label>
                    <input type="text" class="form-control" name="demand_qty" id="demand_qty_{{$val->id}}" value=""/>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="row my-1">
                <div class="col-md-12">
                    <label class="input-required">Unit<span class="required"></span></label>
                    <select name="unit_id" id="unit_id_{{$val->id}}" class="form-control select2" style="width: 100%">
                        <option value="">Select one</option>
                        @forelse($units as $unit)
                            <option value="{{ $unit->id }}">{{$unit->name}}</option>
                        @empty
                            <option value="">Vessel List empty</option>
                        @endforelse
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="row my-1">
                <div class="col-md-12" style="margin-top: 20px">
                    <div class="d-flex col">
                        <button type="button" name="add_workshop_req_item_{{$val->id}}"
                                id="add_workshop_req_item_{{$val->id}}" class="btn btn btn-dark shadow mr-1 mb-1"> Add
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="bg-rgba-secondary">
                    <tr>
                        <th class="hidden">id</th>
                        <th class="hidden">Requisition id</th>
                        <th class="hidden">Vessel Inspection id</th>
                        <th class="hidden">Product Id</th>
                        <th scope="col">Item</th>
                        <th class="hidden">Unit Id</th>
                        <th scope="col">Unit</th>
                        <th scope="col">Demand Qty</th>
                        @if(in_array($val->status,\App\Enums\Mwe\ConfigRole::can_update_requisition_items))
                            <th scope="col">Approved Qty</th>
                            <th scope="col">Collection Qty</th>
                            <th scope="col">Scrap Qty</th>
                            <th scope="col">Used Qty</th>
                        @endif
                        @if(!in_array($val->status,\App\Enums\Mwe\ConfigRole::can_not_edit_requisition_master))
                            <th class="hidden">status</th>
                            <th scope="col">Action</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody class="workshopRequisitionItems_{{$val->id}}">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 my-1" id="response_{{$val->id}}"></div>
        <div class="col-md-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-end col">
                    @if($val->status!=='C')
                        <button type="submit" id="submit_workshop_requisitionss_{{$val->id}}"
                                class="btn btn btn-dark shadow mr-1 mb-1">{{($val->status=='R')?'Re-submit':'Submit'}}
                        </button>
                        @if($val->status=='A')
                            <button type="submit" id="workshop_job_complete_{{$val->id}}"
                                    class="btn btn btn-success shadow  mb-1" value="C">Complete
                            </button>
                        @endif

                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-12 my-1" id="response_{{$val->id}}">
        </div>
    </div>
</from>
@section('footer-script')
    <!--Load custom script-->
    @parent
    <script>
        $(document).on('click', '#submit_workshop_requisitionss_{{$val->id}}', function (e) {
            e.preventDefault();
            let show_task_details = $('#show_task_details_{{$val->id}}');
            let vessel_inspection_id = $('#vessel_inspection_id_{{$val->id}}').val();
            let maintenance_req_id = $('#maintenance_req_id_{{$val->id}}').val();
            let job_number = $('#job_number_{{$val->id}}').val();
            let requisition_number = $('#requisition_number_{{$val->id}}').val();
            let remarks = $('#remarks_{{$val->id}}').val();
            let task_details = $('#task_details_{{$val->id}}').val();
            let req_status = $('#req_status_{{$val->id}}').val();
            let forward_to = $('#forward_to_{{$val->id}}').val();
            let req_items = [];
            if (job_number == '' || job_number == undefined || job_number == null) {
                return sweetAlert('JOB NUMBER NOT BE NULL')
            }
            if (task_details == '' || task_details == undefined || task_details == null) {
                return sweetAlert('TASK DETAILS NOT BE NULL')
            }
            if (forward_to == '' || forward_to == undefined || forward_to == null) {
                if(req_status!='A'){
                    return sweetAlert('FORWARD TO CAN NOT BE NULL')
                }
            }
            $("table tbody.workshopRequisitionItems_{{$val->id}} tr").each(function (index) {
                let currentRow = $(this).closest("tr");
                let req_item_id = currentRow.find("td:eq(0) input[type='hidden']").val();
                let requisition_id = currentRow.find("td:eq(1) input[type='hidden']").val();
                let vessel_inspection_id = currentRow.find("td:eq(2) input[type='hidden']").val();
                let product_id = currentRow.find("td:eq(3) input[type='hidden']").val();
                let unit_id = currentRow.find("td:eq(5) input[type='hidden']").val();
                let demand_qty = currentRow.find("td:eq(7) input[type='text']").val();
                let received_qty = currentRow.find("td:eq(8) input[type='text']").val();
                let collected_qty = currentRow.find("td:eq(9) input[type='text']").val();
                let old_return_qty = currentRow.find("td:eq(10) input[type='text']").val();
                let used_qty = currentRow.find("td:eq(11) input[type='text']").val();
                let status = currentRow.find("td:eq(12) input[type='hidden']").val();
                let obj = {};
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
            workshopRequisitionProcess(vessel_inspection_id, maintenance_req_id, job_number, requisition_number, remarks, task_details, req_status, req_items, show_task_details, forward_to);
            location.reload();
        });

        function workshopRequisitionProcess(vessel_inspection_id, maintenance_req_id, job_number, requisition_number, remarks, task_details, req_status, req_items, show_task_details, forward_to) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax(
                {
                    type: 'POST',
                    url: '{{route('mwe.operation.workshop-requisition-process')}}',
                    data: {
                        _token: CSRF_TOKEN,
                        vessel_inspection_id: vessel_inspection_id,
                        maintenance_req_id: maintenance_req_id,
                        job_number: job_number,
                        requisition_number: requisition_number,
                        remarks: remarks,
                        task_details: task_details,
                        req_status: req_status,
                        req_items: req_items,
                        forward_to: forward_to,
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
                            document.getElementById("response_" + ((data.response.vessel_inspection_id))).innerHTML = '<div class="alert alert-success alert-dismissible fade show" role="alert">'
                                + '<strong>' + data.response.status_message + '</strong>'
                                + '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                                + '<span aria-hidden="true">&times;</span>'
                                + '</button></div>';
                        } else {
                            document.getElementById("response_" + ((data.response.vessel_inspection_id))).innerHTML = '<div class="alert alert-danger alert-dismissible fade show" role="alert">'
                                + '<strong>' + data.response.status_message + '</strong>'
                                + '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                                + '<span aria-hidden="true">&times;</span>'
                                + '</button></div>';
                        }
                    }
                }
            );
        }

        $(document).on('click', '#workshop_job_complete_{{$val->id}}', function (e) {
            e.preventDefault();
            var vessel_inspection_id = $('#vessel_inspection_id_{{$val->id}}').val();
            var maintenance_req_id = $('#maintenance_req_id_{{$val->id}}').val();
            var job_number = $('#job_number_{{$val->id}}').val();
            var requisition_number = $('#requisition_number_{{$val->id}}').val();
            var remarks = $('#remarks_{{$val->id}}').val();
            var task_details = $('#task_details_{{$val->id}}').val();
            var req_status = $('#req_status_{{$val->id}}').val();
            var action_status = $('#workshop_job_complete_{{$val->id}}').val();
            var req_items = [];
            if (job_number == '' || job_number == undefined || job_number == null) {
                return sweetAlert('JOB NUMBER NOT BE NULL')
            }
            if (task_details == '' || task_details == undefined || task_details == null) {
                return sweetAlert('TASK DETAILS NOT BE NULL')
            }
            $("table tbody.workshopRequisitionItems_{{$val->id}} tr").each(function (index) {
                var currentRow = $(this).closest("tr");
                var req_item_id = currentRow.find("td:eq(0) input[type='hidden']").val();
                var requisition_id = currentRow.find("td:eq(1) input[type='hidden']").val();
                var vessel_inspection_id = currentRow.find("td:eq(2) input[type='hidden']").val();
                var product_id = currentRow.find("td:eq(3) input[type='hidden']").val();
                var unit_id = currentRow.find("td:eq(5) input[type='hidden']").val();
                var demand_qty = currentRow.find("td:eq(7) input[type='text']").val();
                var received_qty = currentRow.find("td:eq(8) input[type='text']").val();
                var collected_qty = currentRow.find("td:eq(9) input[type='text']").val();
                var old_return_qty = currentRow.find("td:eq(10) input[type='text']").val();
                var used_qty = currentRow.find("td:eq(11) input[type='text']").val();
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
            workshopRequisitionComplete(vessel_inspection_id, maintenance_req_id, job_number, requisition_number, remarks, task_details, req_status, action_status, req_items);
        });

        function workshopRequisitionComplete(vessel_inspection_id, maintenance_req_id, job_number, requisition_number, remarks, task_details, req_status, action_status, req_items) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax(
                {
                    type: 'POST',
                    url: '{{route('mwe.operation.workshop-requisition-complete')}}',
                    data: {
                        _token: CSRF_TOKEN,
                        vessel_inspection_id: vessel_inspection_id,
                        maintenance_req_id: maintenance_req_id,
                        job_number: job_number,
                        requisition_number: requisition_number,
                        remarks: remarks,
                        task_details: task_details,
                        req_status: req_status,
                        req_items: req_items,
                        action_status: action_status,
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
                            document.getElementById("response_" + (data.response.vessel_inspection_id)).innerHTML = '<div class="alert alert-success alert-dismissible fade show" role="alert">'
                                + '<strong>' + data.response.status_message + '</strong>'
                                + '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                                + '<span aria-hidden="true">&times;</span>'
                                + '</button></div>';
                        } else {
                            document.getElementById("response_" + (data.response.vessel_inspection_id)).innerHTML = '<div class="alert alert-danger alert-dismissible fade show" role="alert">'
                                + '<strong>' + data.response.status_message + '</strong>'
                                + '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                                + '<span aria-hidden="true">&times;</span>'
                                + '</button></div>';
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
                        url: '{{route('mwe.operation.workshop-requisition-task-details')}}',
                        data: {
                            _token: CSRF_TOKEN,
                            vessel_inspection_id: vessel_inspection_id,
                        },
                        success: function (data) {
                            console.log('onclickinspection', data);
                            $('.workshopRequisitionItems_{{$val->id}}').html(data.html);
                        }
                    }
                );
            }

            $(document).on('click', '#add_workshop_req_item_{{$val->id}}', function (e) {
                e.preventDefault();
                var vessel_inspection_id = $('#vessel_inspection_id_{{$val->id}}').val();
                var product_id = $('#product_id_{{$val->id}}').val();
                if (product_id == '' || product_id == undefined || product_id == null) {
                    return sweetAlert('PRODUCT MUST BE SELECT')
                }
                var demand_qty = $('#demand_qty_{{$val->id}}').val();
                if (demand_qty == '' || demand_qty == undefined || demand_qty == null) {
                    return sweetAlert('QUANTITY MUST BE INPUT')
                }
                var unit_id = $('#unit_id_{{$val->id}}').val();
                if (unit_id == '' || unit_id == undefined || unit_id == null) {
                    return sweetAlert('UNIT MUST BE SELECT')
                }
                addWorkShopRequisitionItem(vessel_inspection_id, product_id, demand_qty, unit_id);
                $('#product_id_{{$val->id}}').val('').trigger('change');;
                $('#demand_qty_{{$val->id}}').val('');
                $('#unit_id_{{$val->id}}').val('').trigger('change');;
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
                                document.getElementById("response_" + (data.response.data.P_VESSEL_INSPECTION_ID)).innerHTML = '<div class="alert alert-success alert-dismissible fade show" role="alert">'
                                    + '<strong>' + data.response.status_message + '</strong>'
                                    + '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                                    + '<span aria-hidden="true">&times;</span>'
                                    + '</button></div>';
                            } else {
                                document.getElementById("response_" + (data.response.data.P_VESSEL_INSPECTION_ID)).innerHTML = '<div class="alert alert-danger alert-dismissible fade show" role="alert">'
                                    + '<strong>' + data.response.status_message + '</strong>'
                                    + '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                                    + '<span aria-hidden="true">&times;</span>'
                                    + '</button></div>';
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
                                document.getElementById("response_" + (data.response.data.P_VESSEL_INSPECTION_ID)).innerHTML = '<div class="alert alert-success alert-dismissible fade show" role="alert">'
                                    + '<strong>' + data.response.status_message + '</strong>'
                                    + '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                                    + '<span aria-hidden="true">&times;</span>'
                                    + '</button></div>';
                            } else {
                                document.getElementById("response_" + (data.response.data.P_VESSEL_INSPECTION_ID)).innerHTML = '<div class="alert alert-danger alert-dismissible fade show" role="alert">'
                                    + '<strong>' + data.response.status_message + '</strong>'
                                    + '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                                    + '<span aria-hidden="true">&times;</span>'
                                    + '</button></div>';
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
                                document.getElementById("response_" + (data.response.data.P_VESSEL_INSPECTION_ID)).innerHTML = '<div class="alert alert-success alert-dismissible fade show" role="alert">'
                                    + '<strong>' + data.response.status_message + '</strong>'
                                    + '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                                    + '<span aria-hidden="true">&times;</span>'
                                    + '</button></div>';
                            } else {
                                document.getElementById("response_" + (data.response.data.P_VESSEL_INSPECTION_ID)).innerHTML = '<div class="alert alert-danger alert-dismissible fade show" role="alert">'
                                    + '<strong>' + data.response.status_message + '</strong>'
                                    + '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                                    + '<span aria-hidden="true">&times;</span>'
                                    + '</button></div>';
                            }

                        }
                    }
                );
            }
        });
    </script>

@endsection
