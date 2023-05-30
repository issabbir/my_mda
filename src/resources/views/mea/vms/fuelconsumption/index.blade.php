@extends('layouts.default')

@section('title')

@endsection

@section('header-style')

    <!--Load custom style link or css-->
@endsection
@section('content')
    <div class="row">
        <div class="col-12">

            <div class="card" id="form-card">
                <div class="card-body">
                    <h5 style="Color: #132548" class="card-title">Fuel Consumption Details </h5>
                    <hr>
                    @include('mea.vms.fuelconsumption.form')
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
                                        <th>Vehicle Reg. No.</th>
                                        <th>Driver Name</th>
                                        <th>Refueling Date</th>
                                       {{-- <th>Last Refueling</th>--}}
                                        <th>Mileage</th>
                                        <th>Fuel Type</th>
                                        <th>Quantity</th>
                                        <th>Unit price</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                            </thead>

                            <tbody id="resultDetailsBody">

                            </tbody>
                        </table>
                    </div>
                    <br> <br>
                </div>
                <!-- Table End -->
            </div>

        </div>
    </div>
@endsection

@section('footer-script')
    <!--Load custom script-->
    <script type="text/javascript">

        $(document).ready(function() {
            @if(isset($data['insertedData']->cpa_depot_yn))
                changeDepot('{{$data['insertedData']->cpa_depot_yn}}');
            @endif

            dateTimePicker('#datetimepicker2');
            dateTimePicker('#datetimepicker3');
            dateTimePicker('#datetimepicker4');
            $('#searchResultTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: APP_URL+'/vms/datatable-fuel-consumption',
                columns: [
                    { data: 'vehicle_reg_no', name: 'vehicle_reg_no',searchable: true },
                    { data: 'driver_name', name: 'driver_name',searchable: true },
                    { data: 'refueling_date', name: 'refueling_date', searchable: true },
                    //{ data: 'refueling_date', name: 'refueling_date', searchable: true },
                    { data: 'mileage_on_refueling', name: 'mileage_on_refueling',searchable: true },
                    { data: 'fuel_type_name', name: 'fuel_type_name',searchable: true },
                    { data: 'fuel_qty', name: 'fuel_qty',searchable: true },
                    { data: 'fuel_unit_price', name: 'fuel_unit_price',searchable: true },
                    { data: 'total_fuel_amount', name: 'total_fuel_amount',searchable: true },
                    { data: 'action', name: 'action'},
                ]
            });

            $("#driver_id").on("change", function () {
                let driver_id = $("#driver_id").val();
                let url = APP_URL+'/ajax/driver-info/';
                if( ((driver_id !== undefined) || (driver_id != null)) && driver_id) {
                    $.ajax({
                        type: "GET",
                        url: url+driver_id,
                        success: function (data) {
                            $('#driver_name').val(data.driver_name);
                        },
                        error: function (data) {
                            alert('error');
                        }
                    });
                } else {
                    $('#driver_name').val('');
                }
            });

            function checkcurrentMileageInputWithLastMileage(){
                var mileage_on_refueling =  $("#mileage_on_refueling").val();
                var last_refueling_mileage =  $("#last_refueling_mileage").val();
                if(Number(last_refueling_mileage)>Number(mileage_on_refueling)){
                    alert('Please change New Mileage it must be greeter than last mileage');
                    $("#mileage_on_refueling").focus();
                }
                console.log(mileage_on_refueling+' '+last_refueling_mileage);
            }
            $(document).on('change', '#mileage_on_refueling', function(){
                checkcurrentMileageInputWithLastMileage();
            });

            $(document).on('change', '#depot_type', function(){
                var depot_type =  $("#depot_type").val();
                changeDepot(depot_type);
            });

            $("#fuel_qty").on("keyup", function () {
                calculateTotalFuelAmount();
            });

            $("#fuel_unit_price").on("keyup", function () {
                calculateTotalFuelAmount();
            });

            function changeDepot(depot_type){
                if(depot_type == 'Y'){
                    $('.typeOutside').addClass('displayNone');
                }else{
                    $('.typeOutside').removeClass('displayNone');
                }
            }
            function calculateTotalFuelAmount(){
                var fuel_qty            = (Number($('#fuel_qty').val()) > 0 )? Number($('#fuel_qty').val()):0 ;
                var fuel_unit_price     = (Number($('#fuel_unit_price').val()) > 0 ) ? Number($('#fuel_unit_price').val()) : 0;
                var total_fuel_amount   = 0;

                total_fuel_amount = Math.ceil(fuel_qty*fuel_unit_price);

                $('#total_fuel_amount').val(total_fuel_amount);
            }

            let rentalStatus = 'N';
            function checkRentDateAndFuelDateMatched(){
               /* $("#refueling_date").on("change", function () {
                    alert(1);
                });*/
                $("#datetimepicker2").on("change.datetimepicker", function (e) {
                    //alert(1);
                    console.log($('#refueling_date').val());
                });
            }
            checkRentDateAndFuelDateMatched();

            $("#vehicle_id").on("change", function () {
                let vehicle_id = $("#vehicle_id").val();
                let url = APP_URL+'/ajax/fuel-last-refuelingdata/';
                if( ((vehicle_id !== undefined) || (vehicle_id != null)) && vehicle_id) {
                    $.ajax({
                        type: "GET",
                        url: url+vehicle_id,
                        success: function (data) {

                           // console.log(data);
                            if(data[1].driver_id){
                                $('#driver_id').val(data[1].driver_id).trigger('change');
                            }else{
                                $('#driver_id').val('').trigger('change');
                            }

                             $('#last_refueling_date').val(data[0].last_fuel_date);
                                if(data[0].last_fuel_mileage){
                                    $('#last_refueling_mileage').val(data[0].last_fuel_mileage);
                                }else{
                                    $('#last_refueling_mileage').val(0);
                                }
                             //$('#last_refueling_mileage').val(data[0].last_fuel_mileage);
                            if(data[0].pass_value) {
                                $('#fuel_type_id').val(data[0].pass_value).trigger('change');
                            } else {
                                $('#fuel_type_id').val(4).trigger('change');
                            }

                            if(data[1].cpa_vehicle_yn == 'N'){
                                rentalStatus = 'N';
                               //checkRentDateAndFuelDateMatched();
                            }else{
                                rentalStatus = 'Y';
                            }

                            $('#engine_type_id').val('');
                            $('#engine_type').val('');

                            if(data[2].engine_type_id){
                                $('#engine_type_id').val(data[2].engine_type_id);
                                $('#engine_type').val(data[2].engine_type);
                            }
                        },
                        error: function (data) {
                            alert('error');
                        }
                    });
                } else {
                    $('#driver_name').val('');
                }
            });

            checkcurrentMileageInputWithLastMileage();


            $(document).on('change', '#depot_type, #fuel_consumption_type_id, #work_type_id, #engine_type_id, #qty_unit_id, #refuel_frequency_id, #fuel_type_id', function(){
                var depot_type = $("#depot_type").val();
                var fuel_consumption_type_id =  $("#fuel_consumption_type_id").val();
                var work_type_id =  $("#work_type_id").val();
                var engine_type_id =  $("#engine_type_id").val();
                var qty_unit_id =  $("#qty_unit_id").val();
                var refuel_frequency_id =  $("#refuel_frequency_id").val();
                var fuel_type_id =  $("#fuel_type_id").val();

                if ((depot_type ==='Y') && fuel_consumption_type_id) {
                    let url = APP_URL+'/ajax/get-fuel-quantity';
                    // if( ((fuel_consumption_type_id !== undefined) || (fuel_consumption_type_id != null)) && fuel_consumption_type_id) {
                        $.ajax({
                            type: "GET",
                            url: url,
                            data: {depot_type: depot_type,fuel_consumption_type_id:fuel_consumption_type_id,work_type_id:work_type_id,engine_type_id:engine_type_id,qty_unit_id:qty_unit_id,refuel_frequency_id:refuel_frequency_id,fuel_type_id:fuel_type_id},
                            success: function (data) {
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

                    // } else {
                    //     alert("Please select Fuel Consumption Type");
                    // }

                } else if (depot_type ==='N') {
                    $("#fuel_qty").val(0);
                }
            });
        });


    </script>
@endsection


