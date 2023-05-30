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
                    <h5 style="Color: #132548" class="card-title">Vehicle Enlisted</h5>
                    <hr>
                    @include('mea.vms.vehicleinfo.form')
                </div>
            </div>

            <div class="card">
                <!-- Table Start -->
                <div class="card-body">
                    <h4 class="card-title">Vehicle List</h4><!---->
                    <hr>
                    <div class="table-responsive">
                        <table id="searchResultTable" class="table table-sm datatable mdl-data-table dataTable">
                            <thead>
                                    <tr>
                                        {{--<th>Vehicle CPA No.</th>--}}
                                        <th>Vehicle Reg. No.</th>
                                        <th>Chassis No.</th>
                                        <th>Engine No.</th>
                                        <th>Model</th>
                                        {{--<th>Assigned Employee Name</th>
                                        <th>Mobile</th>--}}
                                        <th>Status</th>
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

            datePicker('#datetimepicker1');
            datePicker('#datetimepicker2');
            datePicker('#datetimepicker3');
            datePicker('#datetimepicker4');
            datePicker('#datetimepicker5');
            datePicker('#datetimepicker6');
            datePicker('#datetimepicker7');
            datePicker('#datetimepicker8');

            $(document).on('keyup','#vehicleRegNo,#chassisNo,#engineNo',function () {
                $(this).val($(this).val().toUpperCase());
            });

            $('#searchResultTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: APP_URL+'/vms/datatable-vehicle-list',
                columns: [
                    /*{ data: 'vehicle_cpa_no', name: 'vehicle_cpa_no',searchable: true },*/
                    { data: 'vehicle_reg_no', name: 'vehicle_reg_no',searchable: true },
                    { data: 'chassis_no', name: 'chassis_no', searchable: true },
                    { data: 'engine_no', name: 'engine_no',searchable: true },
                    { data: 'model_name', name: 'model_name',searchable: true },
                    /*{ data: 'vehicle_owner_name', name: 'vehicle_owner_name',searchable: true },
                    { data: 'vehicle_owner_mobile_no', name: 'vehicle_owner_mobile_no',searchable: true },*/
                    /*{ data: 'assigned_employee_name', name: 'assigned_employee_name',searchable: true },
                    { data: 'emp_emergency_contact_mobile', name: 'emp_emergency_contact_mobile',searchable: true },*/
                    { data: 'active_yn', name: 'active_yn',searchable: true },
                    { data: 'action', name: 'action'},
                ]
            });


            var serviceFormIndex = 1;
             @if(isset($data['insertedData']))
            var insertedDocsData = [{!! json_encode($data['insertedDocsData'])!!}];
            $.each(insertedDocsData, function (rowIndex, rowValue) {
                $.each(rowValue, function (index, value) {
                    var doc_master_id_selected = value.doc_master_id;
                    var doc_file_selected = value.doc_file;
                    var doc_file_name_selected = value.doc_file_name;
                    var doc_file_path_selected = value.doc_file_path;
                    var doc_type_selected = value.doc_type;
                    var doc_type_id_selected = value.doc_type_id;
                    rowGenerate(index + 1,doc_master_id_selected,doc_file_selected,doc_file_name_selected,doc_file_path_selected,doc_type_selected,doc_type_id_selected); //, value.emp_id is the insertedData To make Selected
                });
            });
            @else
                rowGenerate(serviceFormIndex);
            @endif

            if ($('#serviceForm tr')) {
                serviceFormIndex = $('#serviceForm tr').length + 1;
            }
            $('#addServiceForm').on('click', function (e) {
                e.preventDefault();
                rowGenerate(serviceFormIndex);
            });

            $("#serviceForm").on('click', '.remove-education-form', function (e) {
                var rowNumArr = e.target.id.split('removeId-');
                let details_id = $('#doc_master_id'+rowNumArr[1]).val();
                let rowRemoveFlag =0;

                if(confirm("Are you sure you want to delete this?")){
                    if(
                        (details_id !== undefined) && (details_id !== null) && (details_id !== '')
                    ){

                        if(details_id){

                            $.ajax({
                                type: "GET",
                                url: APP_URL+'/ajax/doc-delete/'+details_id,
                                success: function (data) {
                                    alert(data.o_status_message);
                                    if(data.o_status_code == '1') {
                                        rowRemoveFlag =1;
                                       // sumServiceJobCost();
                                    }

                                },
                                error: function (data) {
                                    alert('error');
                                }
                            });

                            //if(rowRemoveFlag)
                            $(this).parent().parent().remove();

                        }else{
                            e.preventDefault();
                            return false;
                        }
                    }else{
                        $(this).parent().parent().remove();
                        //sumServiceJobCost();
                    }

                }
                else{
                    e.preventDefault();
                    return false;
                }
                e.preventDefault();

            });


            function rowGenerate(index, doc_master_id_selected = 0,doc_file_selected = '',doc_file_name_selected = '',doc_file_path_selected='',doc_type_selected = '',doc_type_id_selected = 1) {
                serviceFormIndex = index;
                //console.log(serviceFormIndex);
                let dynamicServiceHiddenId = '<input type="hidden" name="doc_master_id[' + serviceFormIndex + ']" id="doc_master_id' + serviceFormIndex + '" value="" >';
                let dynamicDocsFileName = '<input type="text" required name="doc_file_name[' + serviceFormIndex + ']" id="doc_file_name' + serviceFormIndex + '" value="'+ doc_file_name_selected +'" placeholder="File Name" class="form-control" >';
                let dynamicDocsFile = '<div class="col-sm-12"><div class="row">' +
                    '<div class="custom-file b-form-file form-group col-sm-7">' +
                    '<input type="file" value=""' +
                    'class="custom-file-input"' +
                    //'data-toggle="datetimepicker" data-target="#serviceDatePick'+ serviceFormIndex +'"'+
                    'name="doc_file[' + serviceFormIndex + ']"' +
                    'id="doc_file' + serviceFormIndex + '"' +
                    ' />' +
                    '<label for="driver_photo" data-browse="Browse"' +
                    'class="custom-file-label required">Attached</label>' +
                    '</div>';
                if(doc_file_selected){
                    dynamicDocsFile += '<div class="col-sm-5 defaultImgDiv">' +
                        '<img class="defaultImg"' +
                        'src="data:' + doc_type_selected + ';base64,' + doc_file_selected + '"' +
                        'alt="' + doc_file_name_selected + '"' +
                        'width="70"' +
                        'height="80"/> &nbsp;' +
                        '<a href="'+APP_URL+'/vms/vehicles-attachments/download/'+doc_master_id_selected+'" target="_blank">'+doc_file_name_selected+' Download</a>'+
                        '</div>';
                }
                dynamicDocsFile += '</div></div>';

                let removeButton = '';
                if(serviceFormIndex==1){
                    removeButton = '';
                }else{
                    removeButton = '<button id="removeId-'+serviceFormIndex+'" type="button" class="btn mr-2 btn-secondary btn-sm remove-education-form" name="removeServiceForm">Remove</button>';
                }

                let dynamicServiceFormTemplate = '<tr role="row">' +
                    '<td aria-colindex="1" role="cell">' +dynamicServiceHiddenId+ dynamicDocsFileName + '</td>' +
                    '<td aria-colindex="2" role="cell">' + dynamicDocsFile + '</td>' +
                    '<td aria-colindex="7" role="cell">'+removeButton+'</td>' +
                    '</tr>';
                $(dynamicServiceFormTemplate).fadeIn("slow").appendTo('#serviceForm');

                if (doc_master_id_selected)
                    $('#doc_master_id' + serviceFormIndex).val(doc_master_id_selected);

                serviceFormIndex++;
            }

            function filterMobileNumber()
            {
                $('.mobile').on('keyup', function() {
                    numericAndMaxDigit(this);
                });
            }

            function filterNidNumber()
            {
                $('.nid').on('keyup', function() {
                    numericAndMaxDigit(this);
                });
            }

            function filterChassisNumber()
            {
                $('.chassisNo').on('keyup', function() {
                    //numericAndMaxDigit(this);
                    maxMinLength(this);
                });
            }

            // laden
            $(document).on('keyup',"#unladenWeight", function () {
                checkgraterThen();
            });

            $(document).on('keyup',"#ladenWeight", function () {
                checkgraterThen();
            });

            function checkgraterThen(){
                var laden = $('#ladenWeight').val();
                var unladen = $('#unladenWeight').val();

                if (Number(unladen) > Number(laden)) {
                    alert('Unladen weight can not be grater than laden weight');
                    $('#unladenWeight').val(0);
                    $('#unladenWeight').focus();
                }
            }
            // unladen

            // Mileage
            $(document).on('keyup',"#currentMileage", function () {
                checkMileageGraterThen();
            });

            $(document).on('keyup',"#initialMileage", function () {
                checkMileageGraterThen();
            });

            function checkMileageGraterThen(){
                var currentMileage = $('#currentMileage').val();
                var initialMileage = $('#initialMileage').val();

                if (Number(initialMileage) > Number(currentMileage)) {
                    alert('Initial Mileage can not be grater than Current Mileage');
                    $('#initialMileage').val(0);
                    $('#initialMileage').focus();
                }
            }
            // Mileage

/*            $(document).on('change', '#cpaVehicleYn', function () {
                changeVehicleOwnerDetails();
            });

            function changeVehicleOwnerDetails() {
                var cpaVehicleYn = $('#cpaVehicleYn').val();

                if (cpaVehicleYn == 'Y') {
                    $('.vehicleOwnerDetails').addClass('displayNone');
                    $('.vehicleOwnerDetailsLabel').removeClass('required');
                    $('.vehicleOwnerDetailsField').prop('required', false);
                    $('.vehicleOwnerDetailsField').val('');
                } else {
                    $('.vehicleOwnerDetails').removeClass('displayNone');
                    $('.vehicleOwnerDetailsLabel').addClass('required');
                    $('.vehicleOwnerDetailsField').prop('required', true);
                }
            }
            changeVehicleOwnerDetails();*/
            checkgraterThen();
            checkMileageGraterThen();

            filterMobileNumber();
            filterNidNumber();
            filterChassisNumber();


            selectCpaEmployee('.employee_id',setJobByCpaEmployeeDetails,'/ajax/employee-details/');

            function selectCpaEmployee(clsSelector,callBack,targetUrl)
            {
                    $(clsSelector).each(function() {

                        $(this).select2({
                            placeholder: "Select",
                            allowClear: false,
                            ajax: {
                                delay: 250,
                                url: APP_URL+'/ajax/employees/',
                                data: function (params) {
                                    if(params.term) {
                                        if (params.term.trim().length  < 1) {
                                            return false;
                                        }
                                    } else {
                                        return false;
                                    }
                                    return params;
                                },
                                dataType: 'json',
                                processResults: function(data) {
                                    console.log(data);
                                    var formattedResults = $.map(data, function(obj, idx) {
                                        obj.id = obj.emp_id;
                                        obj.text = obj.emp_code;
                                        return obj;
                                    });
                                    return {
                                        results: formattedResults,
                                    };
                                },
                                cache: true
                            }
                        });


                        if(
                            ($(this).attr('data-emp-id') !== undefined) && ($(this).attr('data-emp-id') !== null) && ($(this).attr('data-emp-id') !== '')
                        ) {
                            selectDefaultCpaEmployee($(this), $(this).attr('data-emp-id'));
                        }

                        $(this).on('select2:select', function (e) {
                            var that = this;
                            var selectedCode = $(this).find(':selected').text();
                            var selectedId = $(this).find(':selected').val();
                            var selectedEmployee = e.params.data;

                            if(selectedId) {
                                $.ajax({
                                    type: "GET",
                                    url: APP_URL+targetUrl+selectedId,
                                    success: function (data) {
                                        callBack(that, data);
                                    },
                                    error: function (data) {
                                        alert('error');
                                    }
                                });
                            }

                        });
                    });
            }

            function setJobByCpaEmployeeDetails(elem, data)
            {
                //console.log(data);
                $('#employee_id').val(data.emp_id).trigger('change');
                $('#employee_name').val(data.emp_name);
                $('#department_name').val(data.department_name);
                $('#designation').val(data.designation);
                $('#emp_emergency_contact_mobile').val(data.emp_emergency_contact_mobile);
            }

            $(document).on('change',"#cpaVehicleYn", function () {
                var cpaVehicleYn = $("#cpaVehicleYn").val();
                if(cpaVehicleYn =='Y'){
                    $('.vehicleSupplierYN').removeClass('required');
                    $('.vehicleSupplierRequired').prop("required", false);
                }else{
                    $(".vehicleSupplierYN").addClass("required");
                    $('.vehicleSupplierRequired').prop("required", true);
                }
            });


            let assigned_emp_id = $('#assigned_emp_id').val();
                if(assigned_emp_id) {
                    $.ajax({
                        type: "GET",
                        url: APP_URL+'/ajax/employee-details/'+assigned_emp_id,
                        success: function (data) {
                            $('#employee_name').val(data.emp_name);
                            $('#department_name').val(data.department_name);
                            $('#designation').val(data.designation);
                            $('#emp_emergency_contact_mobile').val(data.emp_emergency_contact_mobile);
                        },
                        error: function (data) {
                            alert('error');
                        }
                    });
                }
        });

    </script>
@endsection


