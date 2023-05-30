<?php
/**
 * Created by PhpStorm.
 * User: salman
 * Date: 4/14/2020
 * Time: 5:27 PM
 */
?>
<form id="searchResultPeriodGridList" method="post" enctype="multipart/form-data"
        @if(isset($data['insertedData']->vehicle_id))
          action="{{ route('vehicle-info-update', ['id' => $data['insertedData']->vehicle_id]) }}">
        <input name="_method" type="hidden" value="PUT">
        @else
            action="{{ route('vehicle-info-store') }}">
        @endif
        {{ csrf_field() }}
    <div class="row justify-content-center">
        <div class="col-12">

            <div class="row my-1">
                <div class="col-sm-12">

                    <fieldset class="border col-sm-12">
                        <legend class="w-auto required" style="font-size: 14px;"> Vehicle Information </legend>

                        <div class="row my-1">
                            <div class="col-md-3">
                                <label>Is Vehicle Owned by CPA</label>
                                <select class="custom-select select2"
                                        name="cpaVehicleYn"
                                        id="cpaVehicleYn">
                                    @if(isset($data['loadDecisionDropdown']))
                                        @foreach($data['loadDecisionDropdown'] as $option)
                                            {!!$option!!}
                                        @endforeach
                                    @endif
                                </select>
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-3">
                                <label class="required">VEHICLE REG NO.:</label>
                                <input type="text"
                                       value="{{isset($data['insertedData']->vehicle_reg_no) ? $data['insertedData']->vehicle_reg_no : ''}}"
                                       class="form-control"
                                       id="vehicleRegNo"
                                       name="vehicleRegNo"
                                       maxlength="17"
                                       minlength="17"
                                       placeholder="Vehicle Reg. No."
                                      required
                                />
                                <span class="text-danger"></span>
                            </div>
                            <div class="col-md-3">
                                <label class="required">Chassis No.:</label>
                                <input type="text"
                                       value="{{isset($data['insertedData']->chassis_no) ? $data['insertedData']->chassis_no : ''}}"
                                       class="form-control chassisNo"
                                       maxlength="17"
                                       minlength="17"
                                       id="chassisNo"
                                       name="chassisNo"
                                       placeholder="Chassis No."
                                      required
                                />
                                <span class="text-danger"></span>
                            </div>
                            <div class="col-md-3">
                                <label class="required">Engine No.:</label>
                                <input type="text"
                                       value="{{isset($data['insertedData']->engine_no) ? $data['insertedData']->engine_no : ''}}"
                                       class="form-control"
                                       id="engineNo"
                                       name="engineNo"
                                       maxlength="17"
                                       minlength="17"
                                       placeholder="Engine No."
                                      required
                                />
                                <span class="text-danger"></span>
                            </div>

                        </div>

                        <div class="row my-1">

                            <div class="col-md-3 d-none">
                                <label>Vehicle CPA No.:</label>
                                <input type="text"
                                       value="{{isset($data['insertedData']->vehicle_cpa_no) ? $data['insertedData']->vehicle_cpa_no : ''}}"
                                       class="form-control"
                                       id="VehicleCpaNo"
                                       name="VehicleCpaNo"
                                       placeholder="Vehicle CPA No."

                                />
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-3">
                                <label class="required">CC:</label>
                                <input type="text"
                                       value="{{isset($data['insertedData']->cc) ? $data['insertedData']->cc : ''}}"
                                       class="form-control"
                                       id="cc"
                                       name="cc"
                                       placeholder="cc"
                                       required
                                />
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-3">
                                <label>Laden weight (Kg):</label>
                                <input type="number"
                                       value="{{isset($data['insertedData']->laden_weight) ? $data['insertedData']->laden_weight : 0 }}"
                                       class="form-control"
                                       id="ladenWeight"
                                       name="ladenWeight"
                                       placeholder="Laden weight"

                                />
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-3">
                                <label>Unladen weight (Kg):</label>
                                <input type="number"
                                       value="{{isset($data['insertedData']->unladen_weight) ? $data['insertedData']->unladen_weight : 0 }}"
                                       class="form-control"
                                       id="unladenWeight"
                                       name="unladenWeight"
                                       placeholder="Unladen weight"

                                />
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-3">
                                <label class="required">MANUFACTUR YEAR:</label>
                                <input type="text"
                                       value="{{isset($data['insertedData']->manufactur_year) ? $data['insertedData']->manufactur_year : ''}}"
                                       class="form-control"
                                       id="manufacturYear"
                                       name="manufacturYear"
                                       placeholder="Manufactur Year"
                                       required
                                />
                                <span class="text-danger"></span>
                            </div>

                        </div>

                        <div class="row my-1">

                            <div class="col-md-3">
                                <label class="required">Registration Date:</label>
                                <div class="input-group date" id="datetimepicker8" data-target-input="nearest">
                                    <input type="text"
                                           value="{{isset($data['insertedData']->reg_year) ? $data['insertedData']->reg_year : ''}}"
                                           class="form-control datetimepicker-input"
                                           data-toggle="datetimepicker" data-target="#datetimepicker8"
                                           id="regYear"
                                           name="regYear"
                                           autocomplete="off" required
                                    />
                                </div>
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-3">
                                <label>Current Mileage:</label>
                                <input type="text"
                                       value="{{isset($data['insertedData']->current_mileage) ? $data['insertedData']->current_mileage : 0}}"
                                       class="form-control"
                                       id="currentMileage"
                                       name="currentMileage"
                                       placeholder="Current Mileage"
                                />
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-3">
                                <label class="required">Initial mileage:</label>
                                <input type="text"
                                       value="{{isset($data['insertedData']->initial_mileage) ? $data['insertedData']->initial_mileage : 0}}"
                                       class="form-control"
                                       id="initialMileage"
                                       name="initialMileage"
                                       placeholder="Initial mileage"
                                      required
                                />
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-3">
                                <label>Purchase price:</label>
                                <input type="text"
                                       value="{{isset($data['insertedData']->purchase_price) ? $data['insertedData']->purchase_price : ''}}"
                                       class="form-control"
                                       id="purchasePrice"
                                       name="purchasePrice"
                                       placeholder="Purchase price"
                                />
                                <span class="text-danger"></span>
                            </div>

                        </div>

                        <div class="row my-1">

                            <div class="col-md-3">
                                <label>Maintenance Cost:</label>
                                <input type="text"
                                       value="{{isset($data['insertedData']->maintanance_cost) ? $data['insertedData']->maintanance_cost : ''}}"
                                       class="form-control"
                                       id="maintananceCost"
                                       name="maintananceCost"
                                       placeholder="Maintenance Cost"

                                />
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-3">
                                <label>Vehicle Name:</label>
                                <input type="text"
                                       value="{{isset($data['insertedData']->vehicle_name) ? $data['insertedData']->vehicle_name : ''}}"
                                       class="form-control"
                                       id="vehicleName"
                                       name="vehicleName"
                                       placeholder="Vehicle Name"
                                       autocomplete="off"
                                />
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-3">
                                <label>Vehicle Name Bangla:</label>
                                <input type="text"
                                       value="{{isset($data['insertedData']->vehicle_name_bn) ? $data['insertedData']->vehicle_name_bn : ''}}"
                                       class="form-control"
                                       id="vehicleNameBn"
                                       name="vehicleNameBn"
                                       placeholder="Vehicle Bangla Name"
                                       autocomplete="off"
                                />
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-3">
                                <label class="required">Fitness issue date:</label>
                                <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                                    <input type="text"
                                           value="{{isset($data['insertedData']->fitness_issue_date) ? date('d-m-Y', strtotime($data['insertedData']->fitness_issue_date)) : ''}}"
                                           class="form-control datetimepicker-input"
                                           data-toggle="datetimepicker" data-target="#datetimepicker1"
                                           id="fitnessIssueDate"
                                           name="fitnessIssueDate"
                                           autocomplete="off" required
                                    />
                                </div>
                                <span class="text-danger"></span>
                            </div>

                        </div>

                        <div class="row my-1">

                            <div class="col-md-3">
                                <label class="required">Fitness expiry date:</label>
                                <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                                    <input type="text"
                                           value="{{isset($data['insertedData']->fitness_expiry_date) ? date('d-m-Y', strtotime($data['insertedData']->fitness_expiry_date))  : ''}}"
                                           class="form-control datetimepicker-input"
                                           data-toggle="datetimepicker" data-target="#datetimepicker2"
                                           id="fitnessExpiryDate"
                                           name="fitnessExpiryDate"
                                           autocomplete="off" required
                                    />
                                </div>
                                <span class="text-danger"></span>
                            </div>
                            <div class="col-md-3">
                                <label class="required">Purchase Date:</label>
                                <div class="input-group date" id="datetimepicker3" data-target-input="nearest">
                                    <input type="text"
                                           value="{{isset($data['insertedData']->purchase_date) ? date('d-m-Y', strtotime($data['insertedData']->purchase_date)) : ''}}"
                                           class="form-control datetimepicker-input"
                                           data-toggle="datetimepicker" data-target="#datetimepicker3"
                                           id="purchaseDate"
                                           name="purchaseDate"
                                           autocomplete="off" required
                                    />
                                </div>
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-3">
                                <label class="required">Tax Token Issue Date:</label>
                                <div class="input-group date" id="datetimepicker4" data-target-input="nearest">
                                    <input type="text"
                                           value="{{isset($data['insertedData']->tax_token_issue_date) ? date('d-m-Y', strtotime($data['insertedData']->tax_token_issue_date)) : ''}}"
                                           class="form-control datetimepicker-input"
                                           data-toggle="datetimepicker" data-target="#datetimepicker4"
                                           id="taxTokenIssueDate"
                                           name="taxTokenIssueDate"
                                           autocomplete="off" required
                                    />
                                </div>
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-3">
                                <label class="required">Tax token expiry date:</label>
                                <div class="input-group date" id="datetimepicker5" data-target-input="nearest">
                                    <input type="text"
                                           value="{{isset($data['insertedData']->tax_token_expiry_date) ? date('d-m-Y', strtotime($data['insertedData']->tax_token_expiry_date)) : ''}}"
                                           class="form-control datetimepicker-input"
                                           data-toggle="datetimepicker" data-target="#datetimepicker5"
                                           id="taxTokenExpiryDate"
                                           name="taxTokenExpiryDate"
                                           autocomplete="off" required
                                    />
                                </div>
                                <span class="text-danger"></span>
                            </div>

                        </div>

                        <div class="row my-1">

                            <div class="col-md-3">
                                <label>Route permit issue date:</label>
                                <div class="input-group date" id="datetimepicker6" data-target-input="nearest">
                                    <input type="text"
                                           value="{{isset($data['insertedData']->route_permit_issue_date) ?  date('d-m-Y', strtotime($data['insertedData']->route_permit_issue_date)) : ''}}"
                                           class="form-control datetimepicker-input"
                                           data-toggle="datetimepicker" data-target="#datetimepicker6"
                                           id="routePermitIssueDate"
                                           name="routePermitIssueDate"
                                           autocomplete="off"
                                    />
                                </div>
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-3">
                                <label>Route permit expiry date:</label>
                                <div class="input-group date" id="datetimepicker7" data-target-input="nearest">
                                    <input type="text"
                                           value="{{isset($data['insertedData']->route_permit_expiry_date) ?  date('d-m-Y', strtotime($data['insertedData']->route_permit_expiry_date)) : ''}}"
                                           class="form-control datetimepicker-input"
                                           data-toggle="datetimepicker" data-target="#datetimepicker7"
                                           id="routePermitExpiryDate"
                                           name="routePermitExpiryDate"
                                           autocomplete="off"
                                    />
                                </div>
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-3">
                                <label class="required">No. of Seats:</label>
                                <input type="text"
                                       value="{{isset($data['insertedData']->no_of_seats) ? $data['insertedData']->no_of_seats : ''}}"
                                       class="form-control"
                                       id="no_of_seats"
                                       name="no_of_seats"
                                       placeholder="No. of Seats"
                                       required
                                />
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-3">
                                <label>Vehicle Class:</label>
                                <select
                                    class="custom-select select2"
                                    name="vehicleClassId"
                                    id="vehicleClassId">
                                    @if(isset($data['get_vehicle_class']))
                                        @foreach($data['get_vehicle_class'] as $option)
                                            {!!$option!!}
                                        @endforeach
                                    @endif
                                </select>
                                <span class="text-danger"></span>
                            </div>
                        </div>

                        <div class="row my-1">

                            <div class="col-md-3">
                                <label>Vehicle Type:</label>
                                <select
                                        class="custom-select select2"
                                        name="vehicleTypeId"
                                        id="vehicleTypeId">
                                    @if(isset($data['get_vehicle_type']))
                                        @foreach($data['get_vehicle_type'] as $option)
                                            {!!$option!!}
                                        @endforeach
                                    @endif
                                </select>
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-3">
                                <label>Manufacturer :</label>
                                <select
                                        class="custom-select select2"
                                        name="manufacturerId"
                                        id="manufacturerId">
                                    @if(isset($data['get_manufacturer']))
                                        @foreach($data['get_manufacturer'] as $option)
                                            {!!$option!!}
                                        @endforeach
                                    @endif
                                </select>
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-3">
                                <label>Vehicle Model Name:</label>
                                <input type="text"
                                       value="{{isset($data['insertedData']->model_name) ? $data['insertedData']->model_name : ''}}"
                                       class="form-control"
                                       id="modelName"
                                       name="modelName"
                                       placeholder="Vehicle Model Name"

                                />
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-3">
                                <label class="vehicleSupplierYN">Vehicle Supplier:</label>
                                <select
                                    class="custom-select select2 vehicleSupplierRequired"
                                    name="vehicleSupplierId"
                                    id="vehicleSupplierId">
                                    @if(isset($data['get_vehicle_supplier']))
                                        @foreach($data['get_vehicle_supplier'] as $option)
                                            {!!$option!!}
                                        @endforeach
                                    @endif
                                </select>
                                <span class="text-danger"></span>
                            </div>

                        </div>

                        <div class="row my-1">

                            <div class="col-md-3">
                                <label>Air Conditioner(AC):</label>
                                <select  class="custom-select"
                                        name="acYn"
                                        id="acYn">
                                    @if(isset($data['loadDecisionDropdown']))
                                        @foreach($data['loadDecisionDropdown'] as $option)
                                            {!!$option!!}
                                        @endforeach
                                    @endif
                                </select>
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-3">
                                <label>Makers Country :</label>
                                <select
                                        class="custom-select select2"
                                        name="makersCountryId"
                                        id="makersCountryId">
                                    @if(isset($data['get_country']))
                                        @foreach($data['get_country'] as $option)
                                            {!!$option!!}
                                        @endforeach
                                    @endif
                                </select>
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-3">
                                <label>Tracker Device:</label>
                                <select
                                        class="custom-select select2"
                                        name="trackerId"
                                        id="trackerId">
                                    @if(isset($data['get_tracker_device']))
                                        @foreach($data['get_tracker_device'] as $option)
                                            {!!$option!!}
                                        @endforeach
                                    @endif
                                </select>
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-3">
                                <label >Fuel Type:</label>
                                <select
                                    class="custom-select select2"
                                    name="fuelTypeId"
                                    id="fuelTypeId">
                                    @if(isset($data['get_fuel_types']))
                                        @foreach($data['get_fuel_types'] as $option)
                                            {!!$option!!}
                                        @endforeach
                                    @endif
                                </select>
                                <span class="text-danger"></span>
                            </div>

                        </div>

                        <div class="row my-1">
                                <div class="col-md-3">
                                    <label>Vehicle Color:</label>
                                    <select
                                            class="custom-select select2"
                                            name="colorId"
                                            id="colorId">
                                        @if(isset($data['get_color']))
                                            @foreach($data['get_color'] as $option)
                                                {!!$option!!}
                                            @endforeach
                                        @endif
                                    </select>
                                    <span class="text-danger"></span>
                                </div>

                                <div class="col-md-3">
                                    <label>Vehicle Status:</label>
                                    <select
                                            class="custom-select select2"
                                            name="vehicleStatusId"
                                            id="vehicleStatusId">
                                            @if(isset($data['get_vehicle_status']))
                                                @foreach($data['get_vehicle_status'] as $option)
                                                    {!!$option!!}
                                                @endforeach
                                            @endif
                                    </select>
                                    <span class="text-danger"></span>
                                </div>

                            <div class="col-md-3">
                                <label class="required">Engine Type:</label>
                                <select required
                                    class="custom-select select2"
                                    name="engineTypeId"
                                    id="engineTypeId">
                                    @if(isset($data['get_engine_type_list']))
                                        @foreach($data['get_engine_type_list'] as $option)
                                            {!!$option!!}
                                        @endforeach
                                    @endif
                                </select>
                                <span class="text-danger"></span>
                            </div>

                                <div class="col-md-3">
                                      <label class="required">Status :</label>

                                        </br>
                                          <div class="form-check form-check-inline">
                                              <input class="form-check-input" type="radio" name="active_yn"
                                                  required id="active_yn" value="Y" checked
                                                  @if(isset($data['insertedData']->active_yn) && $data['insertedData']->active_yn == "Y")
                                                  checked
                                                  @endif
                                                  />
                                              <label class="form-check-label"
                                                  for="reporter_cpa_yes">Operational</label>
                                          </div>
                                          <div class="form-check form-check-inline">
                                              <input class="form-check-input" type="radio" name="active_yn"
                                                  required id="active_yn" value="N"
                                                  @if(isset($data['insertedData']->active_yn) && $data['insertedData']->active_yn == "N")
                                                  checked
                                                  @endif
                                                  />
                                              <label class="form-check-label"
                                                  for="reporter_cpa_no">Non-operational</label>
                                            </div>
                                        <span class="text-danger"></span>
                                </div>

                            <div class="col-md-3">
                                <label class="required">VIP reserved :</label>

                                </br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="vehicle_vip_yn"
                                           required id="vehicle_vip_yn" value="Y"
                                           @if(isset($data['insertedData']->vehicle_vip_yn) && $data['insertedData']->vehicle_vip_yn == "Y")
                                           checked
                                        @endif
                                    />
                                    <label class="form-check-label"
                                           for="reporter_cpa_yes">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="vehicle_vip_yn"
                                           required id="vehicle_vip_yn" value="N" checked
                                           @if(isset($data['insertedData']->vehicle_vip_yn) && $data['insertedData']->vehicle_vip_yn == "N")
                                           checked
                                        @endif
                                    />
                                    <label class="form-check-label"
                                           for="reporter_cpa_no">No</label>
                                </div>
                                <span class="text-danger"></span>
                            </div>

                        </div>

                        <div class="row my-1 d-none">
                            <div class="col-md-3">
                                <label>Assigned Employee :</label>
                                <select class="custom-select employee_id select2"
                                        name="assigned_emp_id"
                                        id="assigned_emp_id">
                                    @if(isset($data['insertedData']->assigned_emp_id))
                                        <option value="{{$data['insertedData']->assigned_emp_id}}">{{$data['insertedData']->emp_code}}</option>
                                    @endif
                                </select>
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-3">
                                <label>Assigned Employee Name:</label>
                                <input type="text"
                                       class="form-control"
                                       id="employee_name"
                                       readonly
                                />
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-3">
                                <label>Department Name:</label>
                                <input type="text"
                                       class="form-control"
                                       id="department_name"
                                       readonly
                                />
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-3">
                                <label>Designation Name:</label>
                                <input type="text"
                                       class="form-control"
                                       id="designation"
                                       readonly
                                />
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-3">
                                <label>Mobile No.:</label>
                                <input type="text"
                                       class="form-control"
                                       id="emp_emergency_contact_mobile"
                                       readonly
                                />
                                <span class="text-danger"></span>
                            </div>

                        </div>

                        {{--<div class="row my-1">
                        <div class="col-md-3">
                                <label class="required">Vehicle owner name:</label>
                                <input
                                       type="text"
                                       value="{{isset($data['insertedData']->vehicle_owner_name) ? $data['insertedData']->vehicle_owner_name : ''}}"
                                       class="form-control"
                                       id="vehicleOwnerName"
                                       name="vehicleOwnerName"
                                       placeholder="Vehicle owner name"
                                       required
                                />
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-3 vehicleOwnerDetails">
                                <label class="vehicleOwnerDetailsLabel">Vehicle owner bangla name:</label>
                                <input type="text"
                                       value="{{isset($data['insertedData']->vehicle_owner_name_bn) ? $data['insertedData']->vehicle_owner_name_bn : ''}}"
                                       class="form-control vehicleOwnerDetailsField"
                                       id="vehicleOwnerNameBn"
                                       name="vehicleOwnerNameBn"
                                       placeholder="Vehicle owner bangla name"

                                />
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-3 vehicleOwnerDetails">
                                <label class="vehicleOwnerDetailsLabel">Vehicle owner NID:</label>
                                <input type="text"
                                       value="{{isset($data['insertedData']->vehicle_owner_nid) ? $data['insertedData']->vehicle_owner_nid : ''}}"
                                       class="form-control nid vehicleOwnerDetailsField"
                                       minlength="10"
                                       maxlength="17"
                                       id="vehicleOwnerNid"
                                       name="vehicleOwnerNid"
                                       placeholder="Vehicle owner NID"

                                />
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-3 vehicleOwnerDetails">
                                <label class="vehicleOwnerDetailsLabel">Vehicle Owner Mobile No.:</label>
                                <input type="text"
                                       minlength="10"
                                       maxlength="11"
                                       value="{{isset($data['insertedData']->vehicle_owner_mobile_no) ? $data['insertedData']->vehicle_owner_mobile_no : ''}}"
                                       class="form-control mobile vehicleOwnerDetailsField"
                                       id="vehicleOwnerMobileNo"
                                       name="vehicleOwnerMobileNo"
                                       placeholder="Vehicle Owner Mobile No."

                                />
                                <span class="text-danger"></span>
                            </div>
                        </div>--}}


                        <div class="col-sm-12">

                            <fieldset class="border col-sm-12">
                                <legend class="w-auto required" style="font-size: 14px;"> Documents Attachment </legend>

                                <div class="row setup-content my-2" id="education">
                                    <div class="col-md-12">
                                        @include('mea.vms.vehicleinfo.attachmentdetails')
                                    </div>
                                </div>

                            </fieldset>

                        </div>

                        <div class="col-md-12">
                            <label>&nbsp;</label>
                            <div class="d-flex justify-content-end col">
                                <button type="submit" id="submit" class="btn btn btn-dark shadow mr-1 mb-1 btn-secondary">
                                    @if(empty($data['insertedData']->vehicle_id))
                                        Save
                                    @else
                                        Update
                                    @endif
                                </button> &nbsp;

                                @if(empty($data['insertedData']->vehicle_id))
                                    <button type="reset" class="btn btn-light-secondary mb-1">
                                        Reset
                                    </button>
                                @else
                                    <a href="{{route('vehicle-info-index')}}">
                                        <button type="button" class="btn btn-light-secondary mb-1">
                                            Reset
                                        </button>
                                    </a>
                                @endif
                            </div>
                        </div>

                    </fieldset>
                </div>
            </div>
        </div>

    </div>
</form>

