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
                    <h5 style="Color: #132548" class="card-title">Driver Assigned with Vehicle</h5>
                    <hr>
                    @include('mea.vms.vehicleassign.form')
                </div>
            </div>

            <div class="card">
                <!-- Table Start -->
                <div class="card-body">
                    <h4 class="card-title">Driver assigned vehicles List</h4><!---->
                    <hr>
                    <div class="table-responsive">
                        <table id="searchResultTable" class="table table-sm datatable mdl-data-table dataTable">
                            <thead>
                            <tr>
                                <th>Vehicle Reg. No.</th>
                                <th>Driver Reg. No.</th>
                                <th>Driver Name</th>
                                <th>Mobile</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Schedule</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tbody id="resultDetailsBody">

                            </tbody>
                        </table>
                    </div>
                    <br/>
                </div>
                <!-- Table End -->
            </div>

        </div>
    </div>
@endsection

@section('footer-script')
    <!--Load custom script-->
    <script type="text/javascript">

        $(document).ready(function () {
            changeVehicleReadonlyStatus();
            changeDriverReadonlyStatus();
            datePicker('#datetimepicker2');
            datePicker('#datetimepicker3');
            //datePickerUsingDiv('#datetimepicker2');
            //datePickerUsingDiv('#datetimepicker3');
            $('#searchResultTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: APP_URL + '/vms/datatable-vehicle-assign',
                columns: [
                    {data: 'vehicle_reg_no', name: 'vehicle_reg_no', searchable: true},
                    {data: 'driver_cpa_no', name: 'driver_cpa_no', searchable: true},
                    {data: 'driver_name', name: 'driver_name', searchable: true},
                    {data: 'mobile_no', name: 'mobile_no', searchable: true},
                    {data: 'start_date', name: 'start_date', searchable: true},
                    {data: 'end_date', name: 'end_date', searchable: true},
                    {data: 'schedule_yn', name: 'schedule_yn', searchable: true},
                    {data: 'active_yn', name: 'active_yn', searchable: true},
                    {data: 'action', name: 'action'},
                ]
            });

            $("#vehicle_reg_no").on("change", function () {
                let vehicle_reg_no = $("#vehicle_reg_no").val();
                let url = APP_URL + '/ajax/vehicle-info/';
                if (((vehicle_reg_no !== undefined) || (vehicle_reg_no != null)) && vehicle_reg_no) {
                    $.ajax({
                        type: "GET",
                        url: url + vehicle_reg_no,
                        success: function (data) {
                            $('#chassis_no').val(data.chassis_no);
                            $('#engine_no').val(data.engine_no);
                            $('#vehicle_cpa_no').val(data.vehicle_cpa_no);
                            $('#model_name').val(data.model_name);
                            $('#no_of_seats').val(data.no_of_seats);
                            $('#vehicle_class_name').val(data.vehicle_class_name);
                            $('#vehicle_type_name').val(data.vehicle_type_name);
                            //console.log(data);
                            changeVehicleReadonlyStatus();
                        },
                        error: function (data) {
                            alert('error');
                        }
                    });
                } else {
                    $('#chassis_no').val('');
                    $('#engine_no').val('');
                    $('#vehicle_cpa_no').val('');
                    $('#model_name').val('');
                    $('#no_of_seats').val('');
                    $('#vehicle_class_name').val('');
                    $('#vehicle_type_name').val('');

                }
            });

            $("#driver_id").on("change", function () {
                let driver_id = $("#driver_id").val();
                let url = APP_URL + '/ajax/driver-info/';
                if (((driver_id !== undefined) || (driver_id != null)) && driver_id) {
                    $.ajax({
                        type: "GET",
                        url: url + driver_id,
                        success: function (data) {
                            $('#driver_name').val(data.driver_name);
                            $('#dl_no').val(data.dl_no);
                            $('#driver_type_name').val(data.driver_type_name);
                            $('#start_date').val(data.start_date);
                            $('#end_date').val(data.end_date);
                            changeDriverReadonlyStatus();
                        },
                        error: function (data) {
                            alert('error');
                        }
                    });
                } else {
                    $('#driver_name').val('');
                    $('#dl_no').val('');
                    $('#driver_type_name').val('');
                    //$('#start_date').val('');
                    //$('#end_date').val('');
                }
            });

            function changeDriverReadonlyStatus(status = 1) {
                if (status == 1) {
                    $('#driver_name').prop('readonly', true);
                    $('#dl_no').prop('readonly', true);
                    $('#driver_type_name').attr('readonly', true);
                    /*$('#start_date').prop('readonly',true);
                    $('#end_date').prop('readonly',true);*/
                } else {
                    $('#driver_name').prop('readonly', false);
                    $('#dl_no').prop('readonly', false);
                    $('#driver_type_name').attr('readonly', false);
                    /*$('#start_date').prop('readonly',false);
                    $('#end_date').prop('readonly',false);*/
                }
            }

            function changeVehicleReadonlyStatus(status = 1) {
                if (status == 1) {
                    $('#chassis_no').prop('readonly', true);
                    $('#engine_no').prop('readonly', true);
                    $('#vehicle_cpa_no').prop('readonly', true);
                    $('#model_name').prop('readonly', true);
                    $('#no_of_seats').prop('readonly', true);
                    $('#vehicle_class_name').prop('readonly', true);
                    $('#vehicle_type_name').prop('readonly', true);
                } else {
                    $('#chassis_no').prop('readonly', false);
                    $('#engine_no').prop('readonly', false);
                    $('#vehicle_cpa_no').prop('readonly', false);
                    $('#model_name').prop('readonly', false);
                    $('#no_of_seats').prop('readonly', false);
                    $('#vehicle_class_name').prop('readonly', false);
                    $('#vehicle_type_name').prop('readonly', false);
                }
            }


            $("#schedule_yn_yes, #schedule_yn_no").on("click", function () {
                scheduleYnEffectFunction();
            });

            let sche_yn = $("input[name='schedule_yn']:checked").val();
            if (sche_yn == 'Y') {
                var type_id = 5;
                selectWorkType('.work_type_id', type_id);
            } else{
                var type_id = '';
                selectWorkType('.work_type_id', type_id);
                selectCpaEmployee('.used_employee_id');
            }

            function scheduleYnEffectFunction() {
                let schedule_yn = $("input[name='schedule_yn']:checked").val();

                if (schedule_yn == 'Y') {
                    var type_id = 5;
                    $('#schedule_div').removeClass('displayNone');
                    $('#used_employee_div').addClass('displayNone');
                    selectWorkType('.work_type_id', type_id);
                } else if (schedule_yn == 'N') {
                    var type_id = '';
                    $('#used_employee_div').removeClass('displayNone');
                    $('#schedule_div').addClass('displayNone');
                    selectCpaEmployee('.used_employee_id');
                    selectWorkType('.work_type_id', type_id);
                } else {
                    $('#schedule_div').removeClass('displayNone');
                    $('#used_employee_div').addClass('displayNone');
                }
            }

            function selectWorkType(clsSelector, typeId) {
                $(clsSelector).each(function () {
                    $(this).select2({
                        placeholder: "Select",
                        allowClear: false,
                        ajax: {
                            delay: 250,
                            url: APP_URL + '/ajax/workTypeDetails/' + typeId,
                            data: function (params) {
                                if (params.term) {
                                    if (params.term.trim().length < 1) {
                                        return false;
                                    }
                                } else {
                                    return false;
                                }

                                return params;
                            },
                            dataType: 'json',
                            processResults: function (data) {

                                var formattedResults = $.map(data, function (obj, idx) {
                                    obj.id = obj.work_type_id;
                                    obj.text = obj.work_type;
                                    return obj;
                                });
                                return {
                                    results: formattedResults,
                                };
                            },
                            cache: true
                        }
                    });
                });
            }


            function selectCpaEmployee(clsSelector) {
                $(clsSelector).each(function () {

                    let empDept = '';

                    $(this).select2({
                        placeholder: "Select",
                        allowClear: false,
                        ajax: {
                            delay: 250,
                            url: APP_URL + '/ajax/staffemployees/' + empDept,
                            data: function (params) {
                                if (params.term) {
                                    if (params.term.trim().length < 1) {
                                        return false;
                                    }
                                } else {
                                    return false;
                                }

                                return params;
                            },
                            dataType: 'json',
                            processResults: function (data) {

                                var formattedResults = $.map(data, function (obj, idx) {
                                    obj.id = obj.emp_id;
                                    obj.text = obj.emp_code + ' ' + obj.emp_name;
                                    return obj;
                                });
                                return {
                                    results: formattedResults,
                                };
                            },
                            cache: true
                        }
                    });
                });
            }


        });

    </script>
@endsection


