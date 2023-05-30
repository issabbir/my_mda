<?php
/**
 * Created by PhpStorm.
 * User: salman
 * Date: 6/7/2020
 * Time: 10:27 AM
 */
?>
<form id="searchResultPeriodGridList" method="post" enctype="multipart/form-data"
      @if(isset($data['insertedData']->fuel_consum_id))
      action="{{ route('fuel-consumption-update', ['id' => $data['insertedData']->fuel_consum_id]) }}">
    <input name="_method" type="hidden" value="PUT">
    @else
        action="{{ route('fuel-consumption-store') }}">
    @endif
    {{ csrf_field() }}
    <div class="row">
        <div class="col-12">
            <div class="row my-1">
                <div class="col-md-3">
                    <label class="required">VEHICLE REG NO.:</label>
                    <select required
                            class="custom-select select2"
                            name="vehicle_id"
                            id="vehicle_id">
                        @if(isset($data['get_vehicle_reg_no_list']))
                            @foreach($data['get_vehicle_reg_no_list'] as $option)
                                {!!$option!!}
                            @endforeach
                        @endif
                    </select>
                    <span class="text-danger"></span>
                </div>
                <div class="col-md-3">
                    <label class="required">Driver CPA Reg. No:</label>
                    <select required
                            class="custom-select driver_id select2"
                            name="driver_id"
                            id="driver_id">
                        @if(isset($data['get_driver_reg_list']))
                            @foreach($data['get_driver_reg_list'] as $option)
                                {!!$option!!}
                            @endforeach
                        @endif
                    </select>
                    <span class="text-danger"></span>
                </div>
                <div class="col-md-3">
                    <label>Driver Name:</label>
                    <input type="text"
                           value="{{isset($data['insertedData']->driver_name) ? $data['insertedData']->driver_name : ''}}"
                           class="form-control"
                           id="driver_name"
                           name="driver_name"
                           readonly
                    />
                    <span class="text-danger"></span>
                </div>

                <div class="col-md-3">
                    <label class="required">Fuel Consumption Type:</label>
                    <select required
                            class="custom-select select2"
                            name="fuel_consumption_type_id"
                            id="fuel_consumption_type_id">
                        @if(isset($data['get_fuel_consumption_types']))
                            @foreach($data['get_fuel_consumption_types'] as $option)
                                {!!$option!!}
                            @endforeach
                        @endif
                    </select>
                    <span class="text-danger"></span>
                </div>
            </div>

            <div class="row my-1">
                <div class="col-md-3">
                    <label class="required">Depot Type:</label>
                    <select required
                            class="custom-select"
                            name="depot_type"
                            id="depot_type">
                        @if(isset($data['loadDepotTypeDropdown']))
                            @foreach($data['loadDepotTypeDropdown'] as $option)
                                {!!$option!!}
                            @endforeach
                        @endif
                    </select>
                    <span class="text-danger"></span>
                </div>
{{--                <div class="col-md-9 typeOutside displayNone">--}}
                <div class="col-md-3 typeOutside displayNone">
                    <label>Depot Name:</label>
                    <input type="text"
                           value="{{isset($data['insertedData']->depot_name) ? $data['insertedData']->depot_name : ''}}"
                           class="form-control"
                           id="depot_name"
                           name="depot_name"

                    />
                    <span class="text-danger"></span>
                </div>

                <div class="col-md-3 typeOutside displayNone">
                    <label>Depot Address:</label>
                    <input type="text"
                           value="{{isset($data['insertedData']->depot_address) ? $data['insertedData']->depot_address : ''}}"
                           class="form-control"
                           id="depot_address"
                           name="depot_address"

                    />
                    <span class="text-danger"></span>
                </div>
                <div class="col-md-3 typeOutside displayNone">
                    <label>Money Recipt No.:</label>
                    <input type="text"
                           value="{{isset($data['insertedData']->fuel_voucher_no) ? $data['insertedData']->fuel_voucher_no : ''}}"
                           class="form-control"
                           id="fuel_voucher_no"
                           name="fuel_voucher_no"
                    />
                    <span class="text-danger"></span>
                </div>
{{--                </div>--}}
            </div>
        </div>

        <div class="col-12">
            <div class="row my-1">
                <div class="col-md-3">
                    <label for="work_type_id">WORK TYPE:</label>
                    <select class="custom-select select2"
                            name="work_type_id"
                            id="work_type_id">
                        @if(isset($data['get_work_type_list']))
                            @foreach($data['get_work_type_list'] as $option)
                                {!!$option!!}
                            @endforeach
                        @endif
                    </select>
                    <span class="text-danger"></span>
                </div>

                <div class="col-md-3">
                    <label for="engine_type_id">Engine TYPE:</label>
                    <input name="engine_type_id" id="engine_type_id" type="hidden" value="{{isset($data['insertedData']->engine_type_id) ? $data['insertedData']->engine_type_id : ''}}">
                    <input type="text"
                           value="{{isset($data['insertedData']->engine_type) ? $data['insertedData']->engine_type : ''}}"
                           class="form-control"
                           id="engine_type"
                           readonly
                    />
{{--                    <select class="custom-select select2"
                            name="engine_type_id"
                            id="engine_type_id">
                        @if(isset($data['get_engine_type_list']))
                            @foreach($data['get_engine_type_list'] as $option)
                                {!!$option!!}
                            @endforeach
                        @endif
                    </select>--}}
                    <span class="text-danger"></span>
                </div>

                <div class="col-md-3">
                    <label for="qty_unit_id">Fuel Quantity Unit:</label>
                    <select class="custom-select select2"
                            name="qty_unit_id"
                            id="qty_unit_id">
                        @if(isset($data['get_fuel_unit_list']))
                            @foreach($data['get_fuel_unit_list'] as $option)
                                {!!$option!!}
                            @endforeach
                        @endif
                    </select>
                    <span class="text-danger"></span>
                </div>

                <div class="col-md-3">
                    <label for="refuel_frequency_id">REFUEL FREQUENCY:</label>
                    <select class="custom-select select2"
                            name="refuel_frequency_id"
                            id="refuel_frequency_id">
                        @if(isset($data['get_refuel_frequency_list']))
                            @foreach($data['get_refuel_frequency_list'] as $option)
                                {!!$option!!}
                            @endforeach
                        @endif
                    </select>
                    <span class="text-danger"></span>
                </div>
            </div>

        </div>

        <div class="col-12">
                <fieldset class="border col-sm-12">
                    <legend class="w-auto required" style="font-size: 14px;"> Fuel Refueling </legend>
                    <div class="row my-1">

                        <div class="col-md-3">
                            <label class="required">Refueling Date:</label>
                            <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                                <input type="text"
                                       value="{{isset($data['insertedData']->refueling_date) ? date('d-m-Y h:i A', strtotime($data['insertedData']->refueling_date)) : ''}}"
                                       class="form-control datetimepicker-input"
                                       data-toggle="datetimepicker" data-target="#datetimepicker2"
                                       id="refueling_date"
                                       name="refueling_date"
                                       autocomplete="off"
                                       required
                                />
                            </div>
                            <span class="text-danger"></span>
                        </div>

                        <div class="col-md-3">
                            <label>Last Refueling Date:</label>
                            <div class="input-group date" id="datetimepicker3" data-target-input="nearest">
                                <input type="text"
                                       value="{{isset($data['insertedData']->last_refueling_date) ? date('d-m-Y h:i A', strtotime($data['insertedData']->last_refueling_date)) : ''}}"
                                       class="form-control datetimepicker-input"
                                       data-toggle="datetimepicker" data-target="#datetimepicker3"
                                       id="last_refueling_date"
                                       name="last_refueling_date"
                                       autocomplete="off"
                                       readonly
                                />
                            </div>
                            <span class="text-danger"></span>
                        </div>

                        <div class="col-md-3">
                            <label>Mileage on Refueling:</label>
                            <input type="text"
                                   value="{{isset($data['insertedData']->mileage_on_refueling) ? $data['insertedData']->mileage_on_refueling : 0}}"
                                   class="form-control"
                                   id="mileage_on_refueling"
                                   name="mileage_on_refueling"
                                   placeholder="Mileage on Refueling"
                            />
                            <span class="text-danger"></span>
                        </div>

                        <div class="col-md-3">
                            <label>Last Refueling Mileage:</label>
                            <input type="text"
                                   value="{{isset($data['insertedData']->last_refueling_mileage) ? $data['insertedData']->last_refueling_mileage : 0}}"
                                   class="form-control"
                                   id="last_refueling_mileage"
                                   name="last_refueling_mileage"
                                   placeholder="Last Refueling Mileage"
                                    readonly
                            />
                            <span class="text-danger"></span>
                        </div>

                    </div>

                    <div class="row my-1">
                        <div class="col-md-3">
                            <label class="required">Fuel Type:</label>
                            <select required
                                    class="custom-select select2"
                                    name="fuel_type_id"
                                    id="fuel_type_id">
                                @if(isset($data['get_fuel_types']))
                                    @foreach($data['get_fuel_types'] as $option)
                                        {!!$option!!}
                                    @endforeach
                                @endif
                            </select>
                            <span class="text-danger"></span>
                        </div>

                        <div class="col-md-3">
                            <label class="required">Fuel Quantity:</label>
                            <input type="number"
                                   value="{{isset($data['insertedData']->fuel_qty) ? $data['insertedData']->fuel_qty : 0}}"
                                   class="form-control"
                                   id="fuel_qty"
                                   name="fuel_qty"
                                   placeholder="Fuel Quantity"
                                   required
                            />
                            <span class="text-danger"></span>
                        </div>
                        <div class="col-md-3">
                            <label>Fuel Unit Price(BDT):</label>
                            <input type="number"
                                   value="{{isset($data['insertedData']->fuel_unit_price) ? $data['insertedData']->fuel_unit_price : 0}}"
                                   class="form-control"
                                   id="fuel_unit_price"
                                   name="fuel_unit_price"
                                   placeholder="Fuel Unit Price"
                            />
                            <span class="text-danger"></span>
                        </div>

                        <div class="col-md-3">
                            <label>Fuel Total Amount(BDT):</label>
                            <input type="text"
                                   value="{{isset($data['insertedData']->total_fuel_amount) ? $data['insertedData']->total_fuel_amount : 0}}"
                                   class="form-control"
                                   id="total_fuel_amount"
                                   name="total_fuel_amount"
                                   placeholder="0"
                                    readonly
                            />
                            <span class="text-danger"></span>
                        </div>

                    </div>

                </fieldset>
        </div>

        <div class="col-12">
            <div class="row my-1">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea placeholder="Remarks"
                                  rows="2" wrap="soft"
                                  name="remarks"
                                  class="form-control"
                                  id="remarks" >{!! old('remarks',isset($data['insertedData']->remarks) ? $data['insertedData']->remarks : '')!!}</textarea>

                        <small class="text-muted form-text"> </small>
                    </div>
                </div>

                <div class="col-md-3">
                    <label>Attachment:</label>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-7 custom-file b-form-file form-group">
                                <input type="file" id="fuel_voucher" name="fuel_voucher"
                                       class="custom-file-input"
                                       @if(isset($data['insertedData']->fuel_voucher)) value="data:{{$data['insertedData']->fuel_voucher_doc_type}};base64, {{$data['insertedData']->fuel_voucher}}"
                                @else
                                    {{--required --}}
                                    @endif>
                                <label for="fuel_voucher" data-browse="Browse"
                                       class="custom-file-label  @if(isset($data['insertedData']->fuel_voucher)) required @endif">
                                    Attachment</label>
                            </div>
                            @if(isset($data['insertedData']->fuel_voucher_doc_name))

                                <div class="col-md-4 defaultImgDiv ">
                                    <a href="{{ route('fuel-voucher-attachment', ['id' => $data['insertedData']->fuel_consum_id]) }}" target="_blank">
                                        {{--<img    class="defaultImg"
                                                src="data:{{$data['insertedData']->fuel_voucher_doc_type}};base64,{{$data['insertedData']->fuel_voucher}}"
                                                alt="{{$data['insertedData']->fuel_voucher_doc_name}}"
                                                width="30"
                                                height="40"/>--}}
                                        Download<i class="bx bx-download cursor-pointer" title="Download"></i></a>
                                </div>
                            @endif
                        </div>
                    </div>
                    <span class="text-danger"></span>
                </div>

                <div class="col-md-3">
                    <label>&nbsp;</label>
                    <div class="d-flex justify-content-end col">
                        <button type="submit" id="submit" class="btn btn btn-dark shadow mr-1 mb-1 btn-secondary">
                            @if(empty($data['insertedData']->fuel_consum_id))
                                Save
                            @else
                                Update
                            @endif
                        </button> &nbsp;

{{--
                        @if(empty($data['insertedData']->fuel_consum_id))
                            <button type="reset" class="btn btn-light-secondary mb-1">
                                Reset
                            </button>
                        @else
--}}
                            <a href="{{route('fuel-consumption-index')}}">
                                <button type="button" class="btn btn-light-secondary mb-1">
                                    Reset
                                </button>
                            </a>
                        {{--@endif--}}
                    </div>
                </div>

            </div>
        </div>

    </div>
</form>


