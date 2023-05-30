
@extends('layouts.default')

@section('title')
    Workshop Requisition
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
                            <div class="card-content">
                                <div class="card-body">
                                    <h4 class="card-title">Workshop Requisition</h4>
                                    <form method="POST" action="" enctype="multipart/form-data">
                                        {!! csrf_field() !!}
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="row my-1">
                                                    <div class="col-md-12">
                                                        <label class="input-required">Request Number</label>
                                                        <input
                                                            type="text"
                                                            name="request_number"
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
                                                        <label>Department</label>
                                                        <input type="text" class="form-control" disabled name="department_id" id="department_id" value="{{isset($maintenanceReqData->department)?$maintenanceReqData->department->name:''}}">
                                                        <input type="hidden" class="form-control"  name="maintenance_req_id" id="maintenance_req_id" value="{{isset($maintenanceReqData)?$maintenanceReqData->id:''}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="row my-1">
                                                    <div class="col-md-12">
                                                        <label class="input-required">Vessel<span class="required"></span></label>
                                                        <input type="text" class="form-control" disabled name="vessel_id" id="vessel_id" value="{{isset($maintenanceReqData->vessel)?$maintenanceReqData->vessel->name:''}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">

                                            <div class="col-md-4">
                                                <div class="row my-1">
                                                    <div class="col-md-12">
                                                        <label class="input-required">Vessel Master <span class="required"></span></label>
                                                        <input type="text" class="form-control" disabled name="vessel_master_id" id="vessel_master_id" value="{{isset($maintenanceReqData->vesselMaster)?$maintenanceReqData->vesselMaster->emp_name:''}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="row my-1">
                                                    <div class="col-md-12">
                                                        <label class="input-required">Current Maintenance Status</label>
                                                        <input type="text" class="form-control" disabled name="current_status" id="current_status" value="{{isset($maintenanceReqData->status)?\App\Helpers\HelperClass::getReqStatus($maintenanceReqData->status)->name:''}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="row my-1">
                                                    <div class="col-md-12">
                                                        <label class="input-required">Workshop Name</label>
                                                        <input type="text" class="form-control" disabled name="workshop_id" id="workshop_id" value="{{isset($workshop)?$workshop->name:''}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-1">
                                            <div class="col-12">
                                               <h4 class="card-title"><strong>Task</strong></h4>
                                                @include('mwe.partial.workshop_auth_tasks')
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
<script>
    $(document).ready(function(){
        $(".collapse").on('show.bs.collapse', function(){
            $(this).prev(".card-header").find(".bx").removeClass("bx bxs-plus-circle").addClass("bx bxs-minus-circle");
        }).on('hide.bs.collapse', function(){
            $(this).prev(".card-header").find(".bx").removeClass("bx bxs-minus-circle").addClass("bx bxs-plus-circle");
        });
    });
</script>

@endsection



