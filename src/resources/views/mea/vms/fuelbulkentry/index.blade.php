@extends('layouts.default')

@section('title')

@endsection

@section('header-style')
    <style>
    .font-lower-size{
        font-size: 10px;
    }
    .font-mid-size{
        font-size: 12px;
    }
    </style>
    <!--Load custom style link or css-->
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <form id="searchResultPeriodGridList" method="post" action="{{ route('fuel-bulk-entry-store') }}">
                {{ csrf_field() }}

                <div class="card" id="form-card">
                    <div class="card-body">
                        <h5 style="Color: #132548" class="card-title">Fuel Bulk Entry Details </h5>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <div class="row my-1">
                                    <div class="col-md-4">
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
                                        <span class="text-danger" id="errFuelConsumptionTypeId"></span>
                                    </div>

                                    <div class="col-md-4">
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

                                    <div class="col-md-4">
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
                                </div>

                                <div class="row my-1">
                                    <div class="col-md-4">
                                        <label class="required">Refueling Date:</label>
                                        <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                                            <input type="text"
                                                   value="{{isset($data['insertedData']->refueling_date) ? date('d-m-Y', strtotime($data['insertedData']->refueling_date)) :''}}"
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

                                    <div class="col-md-4">
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


                                    <div class="col-md-4">

                                        <label for="refuel_frequency_id">Vehicle Type:</label>
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

                                </div>

                            </div>

                        </div>

                        <div class="col-md-12">
                            <label>&nbsp;</label>
                            <div class="d-flex justify-content-end col">
                                <a href="{{route('fuel-bulk-entry-index')}}">
                                    <button
                                        class="btn btn-light-secondary mb-1" type="button" role="button">
                                        <i class="fa fa-refresh"></i> Clear
                                    </button>
                                </a>&nbsp;&nbsp;&nbsp;

                                &nbsp;&nbsp;&nbsp;
                                <button
                                    class="btn btn btn-dark shadow mr-1 mb-1 btn-secondary"
                                    id="search" onclick="getFuelBulkVehList()" type="button">
                            <span> <i class="fa fa-search" aria-hidden="true"></i>
                                Search
                            </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <!-- Table Start -->
                    <div class="card-body">
                        <h4 class="card-title">Fuel Consumption Details List</h4><!---->
                        <hr>

                        <div class="table-responsive">
                            <table id="searchResultTable" class="table table-sm datatable mdl-data-table dataTable">
                                <thead>
                                <tr>
                                    <th  class="font-mid-size">SL#</th>
                                    <th  class="font-mid-size">
                                        <div
                                            class="checkbox-custom chekbox-primary input-group">
                                            <input class="to-labelauty" type="checkbox" id="selectYN_"
                                                   onclick="checkAll(this)"/> <label for="selectYN_"> &nbsp;&nbsp;&nbsp;
                                                <strong>
                                                    ALL</strong></label>
                                        </div>
                                    </th>
                                    <th  class="font-mid-size">Vehicle Reg. No.</th>
                                    <th  class="font-mid-size">Vehicle Type</th>
                                    <th  class="font-mid-size">Work Type</th>
                                    <th  class="font-mid-size">Mileage on Refueling</th>
                                    <th>Quantity</th>
                                    <th  class="font-mid-size">Engine Type</th>
                                </tr>
                                </thead>

                                <tbody style="" id="">
                                <td>

                                </td>

                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-12">
                            <label>&nbsp;</label>
                            <div class="d-flex justify-content-end col">
                                <button type="submit" id="submit"
                                        class="btn btn btn-dark shadow mr-1 mb-1 btn-secondary">
                                    Save
                                </button> &nbsp;
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>



@endsection

@section('footer-script')
    <!--Load custom script-->
    <script type="text/javascript">
        function getFuelBulkVehList() {
            var fuel_consumption_type_id =  $("#fuel_consumption_type_id").val();
            var qty_unit_id =  $("#qty_unit_id").val();
            var refuel_frequency_id =  $("#refuel_frequency_id").val();
            var fuel_type_id =  $("#fuel_type_id").val();
            var refueling_date =  $("#refueling_date").val();
            var vehicle_type_id =  $("#vehicleTypeId").val();

            if (fuel_type_id && fuel_consumption_type_id) {
                let url = APP_URL+'/vms/populate-vehicle-list';
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {fuel_consumption_type_id:fuel_consumption_type_id, qty_unit_id:qty_unit_id,refuel_frequency_id:refuel_frequency_id,fuel_type_id:fuel_type_id,vehicle_type_id:vehicle_type_id,refueling_date:refueling_date},
                    success: function (responseData) {
                        $('#searchResultTable > tbody').empty();
                        $.each(responseData, function (i, item) {
                                var sl = i + 1;
                                var tr = '<tr>';
                                tr = tr + '<td style="width: 5%!important" class="font-lower-size">' + sl + '</td>';
                                tr = tr + '<td style="width: 5%!important">' + '<input class="to-labelauty" name="fuelBulkEntryCheckbox[' + item.vehicle_id + ']" type="checkbox" id="selectYN_' + item.vehicle_id + '" value="' + item.vehicle_id + '"  onclick="checkIndividual(this,' + item.vehicle_id + ')"/> <label for="selectYN_' + item.vehicle_id + '"></label>' + '</td>';
                                tr = tr + '<td style="width: 20%!important">' + '<input type="hidden" name="fuelBulkEntry[' + item.vehicle_id + '][vehicle_id]" value="' + item.vehicle_id + '">' + '<input class="font-lower-size form-control" readonly type="text" id="vehicle_reg_no_' + item.vehicle_id + '" disabled name="fuelBulkEntry[' + item.vehicle_id + '].vehicle_reg_no"  value="' + item.vehicle_reg_no + '"' + '>' + '</td>';
                                tr = tr + '<td style="width: 13%!important" class="font-lower-size">' + '<input type="hidden" name="fuelBulkEntry[' + item.vehicle_id + '][vehicle_type_id]" value="' + item.vehicle_type_id + '">' + item.vehicle_type + '</td>';
                                tr = tr + '<td style="width: 12%!important" class="font-lower-size">' + '<input type="hidden" name="fuelBulkEntry[' + item.vehicle_id + '][work_type_id]" value="' + item.work_type_id + '">' + item.work_type + '</td>';
                                tr = tr + '<td style="width: 15%!important">' + '<input class="form-control" type="text" id="mileage_on_refueling_' + item.vehicle_id + '"  name="fuelBulkEntry[' + item.vehicle_id + '][mileage_on_refueling]" readonly onkeypress="return isNumberKey(event)" value=""' + '>' + '</td>';
                                tr = tr + '<td style="width: 15%!important">' + '<input class="form-control" type="text" id="quantity_' + item.vehicle_id + '"  name="fuelBulkEntry[' + item.vehicle_id + '][quantity]"  readonly onkeypress="return isNumberKey(event)" value="' + item.qty + '" ' + '>' + '</td>';
                                tr = tr + '<td style="width: 15%!important" class="font-lower-size">' + '<input type="hidden" name="fuelBulkEntry[' + item.vehicle_id + '][engine_type_id]" value="' + item.engine_type_id + '">';
                                tr = tr + '<input type="hidden" name="fuelBulkEntry[' + item.vehicle_id + '][fuel_type_id]" value="' + item.fuel_type_id + '">'+ item.engine_type + '</td>';
                                tr = tr + '</tr>';

                                $('#searchResultTable > tbody:last').append(tr);
                            }
                        );
                    },
                        //(data) {
                        /*alert(data[0].qty);
                        // console.log(data);
                        if ($.trim(data) == '' ) {
                            $('#fuel_qty').val('0');
                        }
                        else {
                            $('#fuel_qty').val(data[0].qty);
                        }
                        },
                        */

                    // error: function (data) {
                    // console.log(data);
                    // }
                });

            }
        }


        function checkAll(check) {
            var id = check.id;
            if (check.checked) {
                $('input:checkbox[id^="' + id + '"]').each(function () {
                    $('input:checkbox[id^="' + id + '"]').prop("checked", true);
                });

                $('input[id^="vehicle_reg_no_"]').prop("readonly", false);
                $('input[id^="quantity_"]').prop("readonly", false);
                $('input[id^="mileage_on_refueling_"]').prop("readonly", false);
            } else {
                $('input:checkbox[id^="' + id + '"]').each(function () {
                    $('input:checkbox[id^="' + id + '"]').prop("checked", false);
                });

                $('input[id^="quantity_"]').prop("readonly", true);
                $('input[id^="mileage_on_refueling_"]').prop("readonly", true);
                $('input[id^="vehicle_reg_no_"]').prop("readonly", true);
            }
        }

        function checkIndividual(check, i) {

            if (check.checked) {
                $("#vehicle_reg_no_" + i).prop("readonly", false);
                $("#quantity_" + i).prop("readonly", false);
                $("#mileage_on_refueling_" + i).prop("readonly", false);
            } else {
                $("#vehicle_reg_no_" + i).prop("readonly", true);
                $("#quantity_" + i).prop("readonly", true);
                $("#mileage_on_refueling_" + i).prop("readonly", true);
            }
        }

        function isNumberKey(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode;
            //var maxChar = $('#year').val();
            var errid = "err" + evt.target.id;
            var msg = '<label class="error" for="' + evt.target.id + '">Please enter a valid number.</label>';
            if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
                $("#" + errid).html(msg);
                return false;
            } else {
                $("#" + errid).html('');
                return true;
            }
        }

        $(document).ready(function () {
            datePicker('#datetimepicker2');

            function populateVehicleList() {
                /*            $(document).on('change', '#fuel_consumption_type_id, #fuel_type_id, #qty_unit_id, #work_type_id, #refuel_frequency_id', function(){
                                 var fuel_consumption_type_id =  $("#fuel_consumption_type_id").val();
                                 var qty_unit_id =  $("#qty_unit_id").val();
                                 var refuel_frequency_id =  $("#refuel_frequency_id").val();
                                 var fuel_type_id =  $("#fuel_type_id").val();

                                if (fuel_type_id && fuel_consumption_type_id) {
                                    let url = APP_URL+'/ajax/get-bulk-fuel-quantity';
                                    $.ajax({
                                        type: "GET",
                                        url: url,
                                        data: {fuel_consumption_type_id:fuel_consumption_type_id, qty_unit_id:qty_unit_id,refuel_frequency_id:refuel_frequency_id,fuel_type_id:fuel_type_id},
                                        success: function (data) {
                                            alert(data[0].qty);
                                            // console.log(data);
                                            if ($.trim(data) == '' ) {
                                                $('#fuel_qty').val('0');
                                            }
                                            else {
                                                $('#fuel_qty').val(data[0].qty);
                                            }
                                        },
                                        // error: function (data) {
                                        // console.log(data);
                                        // }
                                    });

                                }
                            });*/

/*
                $.ajax({
                    type: 'get',
                    url: APP_URL + '/vms/fuel-bulk-entry-list',
                    // data: {},
                    success: function (responseData) {
                        $('#searchResultTable > tbody').empty();
                        $.each(responseData, function (i, item) {
                                var sl = i + 1;
                                var tr = '<tr>';
                                tr = tr + '<td style="width: 5%!important">' + sl + '</td>';
                                tr = tr + '<td style="width: 10%!important">' + '<input class="to-labelauty" name="fuelBulkEntryCheckbox[' + item.vehicle_id + ']" type="checkbox" id="selectYN_' + item.vehicle_id + '" value="' + item.vehicle_id + '"  onclick="checkIndividual(this,' + item.vehicle_id + ')"/> <label for="selectYN_' + item.vehicle_id + '"></label>' + '</td>';
                                tr = tr + '<td style="width: 15%!important">' + '<input type="hidden" name="fuelBulkEntry[' + item.vehicle_id + '][vehicle_id]" value="' + item.vehicle_id + '">' + '<input class="form-control" readonly type="text" id="vehicle_reg_no_' + item.vehicle_id + '" disabled name="fuelBulkEntry[' + item.vehicle_id + '].vehicle_reg_no"  value="' + item.vehicle_reg_no + '"' + '>' + '</td>';
                                tr = tr + '<td style="width: 13%!important">' + '<input type="hidden" name="fuelBulkEntry[' + item.vehicle_id + '][vehicle_type_id]" value="' + item.vehicle_type_id + '">' + item.vehicle_type + '</td>';
                                tr = tr + '<td style="width: 12%!important">' + '<input type="hidden" name="fuelBulkEntry[' + item.vehicle_id + '][work_type_id]" value="' + item.work_type_id + '">' + item.work_type + '</td>';
                                tr = tr + '<td style="width: 15%!important">' + '<input class="form-control" type="text" id="mileage_on_refueling_' + item.vehicle_id + '"  name="fuelBulkEntry[' + item.vehicle_id + '][mileage_on_refueling]" readonly onkeypress="return isNumberKey(event)" value=""' + '>' + '</td>';
                                tr = tr + '<td style="width: 15%!important">' + '<input class="form-control" type="text" id="quantity_' + item.vehicle_id + '"  name="fuelBulkEntry[' + item.vehicle_id + '][quantity]"  readonly onkeypress="return isNumberKey(event)" value=""' + '>' + '</td>';
                                tr = tr + '<td style="width: 15%!important">' + '<input type="hidden" name="fuelBulkEntry[' + item.vehicle_id + '][engine_type_id]" value="' + item.engine_type_id + '">' + item.engine_type + '</td>';
                                tr = tr + '</tr>';

                                $('#searchResultTable > tbody:last').append(tr);
                            }
                        );
                    },
                });
*/
            }
        });


    </script>
@endsection


