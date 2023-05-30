<div class="col-md-9">
    <div class="card mb-2">
        <div class="card-body">
            <h4 class="card-title">Description Of Fuel Consumption</h4>
            <div class="row">
                <div class="col-md-4 mb-1">
                    <input type="hidden" name="fuel_consumption_mst_id" id="fuel_consumption_mst_id" value="{{$data->fuel_consumption_mst_id}}">
                    <input type="hidden" name="cpa_vessel_id" id="cpa_vessel_id" value="{{(app('request')->get('vessel_id'))?app('request')->get('vessel_id'):$data->cpa_vessel_id}}">
                    <label class="input-required" for="consumption_from">Consumption From Date</label>
                    <div class="input-group date" onfocusout="$(this).datetimepicker('hide')" id="consumption_from" data-target-input="nearest">
                        <input type="text" name="consumption_from"  value="{{ old('consumption_from', App\Helpers\HelperClass::defaultDateTimeFormat($data->consumption_from,'LOCALDATE')) }}" class="form-control consumption_from"
                               data-target="#consumption_from" data-toggle="datetimepicker" placeholder="Consumption From Date" autocomplete="off" disabled />
                        <div class="input-group-append" data-target="#consumption_from" data-toggle="datetimepicker">
                            <div class="input-group-text">
                                <i class="bx bx-calendar"></i>
                            </div>
                        </div>
                    </div>
                    @if($errors->has("consumption_from"))
                        <span class="help-block">{{$errors->first("consumption_from")}}</span>
                    @endif
                </div>
                <div class="col-md-4 mb-1">
                    <label class="input-required" for="consumption_to">Consumption To Date</label>
                    <div class="input-group date" onfocusout="$(this).datetimepicker('hide')" id="consumption_to" data-target-input="nearest">
                        <input type="text" name="consumption_to"  value="{{ old('consumption_to', App\Helpers\HelperClass::defaultDateTimeFormat($data->consumption_to,'LOCALDATE')) }}" class="form-control consumption_to"
                               data-target="#consumption_to" data-toggle="datetimepicker" placeholder="Consumption To Date" autocomplete="off"  disabled />
                        <div class="input-group-append" data-target="#consumption_to" data-toggle="datetimepicker" >
                            <div class="input-group-text">
                                <i class="bx bx-calendar"></i>
                            </div>
                        </div>
                    </div>
                    @if($errors->has("consumption_to"))
                        <span class="help-block">{{$errors->first("consumption_to")}}</span>
                    @endif
                </div>
                <div class="col-md-4 mb-1">
                    <label class="input-required" for="consumption_ref_no">consumption ref no</label>
                    <input type="text" name="consumption_ref_no" id="consumption_ref_no"
                           value="{{ old('consumption_ref_no', $data->consumption_ref_no) }}"
                           placeholder="SRN/FUEL/0012" class="form-control consumption_ref_no" readonly autocomplete="off"/>
                    @if ($errors->has('consumption_ref_no'))
                        <span class="help-block">{{ $errors->first('consumption_ref_no') }}</span>
                    @endif
                </div>
            </div>
            <div class="row">
                <table class="table table-sm mb-0 table-responsive-sm" id="fuel_consumption">
                    <thead class="thead-dark">
                    <tr>
                        <th class="hidden">Fuel Consumption Dtl Id</th>
                        <th colspan="4">Engine Name</th>
{{--                        <th colspan="4">Fuel Type</th>--}}
                        <th>Working Hours(hrs)</th>
                        <th>Hourly Consumed Fuel(ltr)</th>
                        <th>Total Consumed Fuel(ltr)</th>
                        <th>Remark</th>
                    </tr>
                    </thead>
                    <tbody class="fuel_consumption">
                    @include('cms.approval.partial.items')
                    </tbody>
                    <tfoot>
                    <tr class="thead-dark">
                        <th colspan="6" style="text-align: right">Total</th>
                        <th id="total_fuel_consumption" style="text-align: right"></th>
                        <th></th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        @if(($mapping_data['curWorkFlowStep']['workflow_detail']['role']=='CM_DOC_MASTER'))
            <div class="card-body">
                <h4 class="card-title">Fuel Allocate Information</h4>
                <div class="row">
                    <div class="col-md-4 mb-1">
                        <label  for="reallocate_fuel">Allocate Fuel(Ltr)</label>
                        <input type="text" name="reallocate_fuel" id="reallocate_fuel" class="form-control" value="{{$data->received_fuel}}" readonly>
                    </div>
                    <div class="col-md-4 mb-1">
                        <label for="reallocate_fuel_date">Allocate Fuel Date</label>
                        <input type="text" name="reallocate_fuel_date" id="reallocate_fuel_date" class="form-control" readonly value="{{App\Helpers\HelperClass::defaultDateTimeFormat($data->received_date,'LOCALDATE')}}">
                    </div>
                    <div class="col-md-4 mb-1">
                        <label for="reallocate_fuel_by">Allocate By</label>
                        @php
                            $employee=\App\Entities\Security\User::where('user_id',$data->fuel_allocated_by)->first();
                            $fuel_allocator_data=\App\Entities\Pmis\Employee\Employee::where('emp_id',$employee->emp_id)->first();
                        @endphp
                        <input type="text" name="reallocate_fuel_by" id="reallocate_fuel_by" class="form-control consumption_ref_no" readonly value="{{$fuel_allocator_data->emp_name}},{{isset($fuel_allocator_data->designation)?$fuel_allocator_data->designation->designation:''}}"/>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
