@extends('layouts.default')

@section('title')

@endsection

@section('header-style')

    <!--Load custom style link or css-->
    <style>
        .bg_color_loc {
            background-color: #F6F6F6 !important;
        }
        .bg_color_none {
            background-color: #ffffff !important;
        }
    </style>
@endsection
@section('content')
    @php
        $defaultImg = 'data:image/png;base64,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';
    @endphp
    <div class="row">
        <div class="col-12">

            <div class="card" id="form-card">
                <div class="card-body">
                    <h5 style="Color: #132548" class="card-title">Driver Enlisted</h5>
                    <hr>
                    @include('mea.vms.driver.form')
                </div>
            </div>

            <div class="card">
                <!-- Table Start -->
                <div class="card-body">
                    <h4 class="card-title">Enlisted Driver List</h4><!---->
                    <hr>
                    <div class="table-responsive">
                        <table id="searchResultTable" class="table table-sm datatable mdl-data-table dataTable">
                            <thead>
                                    <tr>
                                        <th>Driver CPA Reg.No.\Employee Code</th>
                                        <th>Driver</th>
                                        <th>Driving Lic. No.</th>
                                        <th>Driver Type</th>
                                        <th>Lic. Exp. Date</th>
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

            $(document).on('keyup','#driver_cpa_no,#dl_no',function () {
                $(this).val($(this).val().toUpperCase());
            });

            districts('#present_division_id','#present_district_id',APP_URL+'/ajax/districts/','#present_thana_id');
            thanas('#present_district_id',APP_URL+'/ajax/thanas/','#present_thana_id');

            districts('#permanent_division_id','#permanent_district_id',APP_URL+'/ajax/districts/','#permanent_thana_id');
            thanas('#permanent_district_id',APP_URL+'/ajax/thanas/','#permanent_thana_id');
            function selectCpaEmployee(clsSelector, callBack)
            {
                $(clsSelector).each(function() {

                    let empDept = '';

                    $(this).select2({
                        placeholder: "Select",
                        allowClear: false,
                        ajax: {
                            delay: 250,
                            url: APP_URL+'/ajax/staffemployees/'+empDept,
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

                                var formattedResults = $.map(data, function(obj, idx) {
                                    obj.id = obj.emp_id;
                                    obj.text = obj.emp_code+' '+obj.emp_name;
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
                        //console.log('1 '+selectedEmployee+' '+selectedCode+' '+selectedId+' ');
                        if(selectedId) {
                            $.ajax({
                                type: "GET",
                                url: APP_URL+'/ajax/employee/'+selectedId,
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

            function setCpaEmployeeDetails(elem, data)
            {
                $('#driver_name').val(data[0].emp_name);
                $('#driver_name_bn').val(data[0].emp_name_bng);
                $('#driver_emp_code_as_cpa_no').val(data[0].emp_code);

                $('#driver_father_name').val(data[0].emp_father_name);
                $('#driver_mother_name').val(data[0].emp_mother_name);
                $('#marital_status_id').val(data[0].emp_maritial_status_id);
                //$('#driver_spouse_name').val();
                $('#gender_type_id').val(data[0].emp_gender_id);
                //alert(data[0].emp_dob);
                //$('#dob').val(data[0].emp_dob);
                $('#dob').val(moment(data[0].emp_dob).format("DD-MM-YYYY"));
                //$('#dob').val(moment(data[0].emp_dob).format("DD-MM-YYYY"));
                $('#nid_no').val(data[0].nid_no);
                $('#emargency_no').val(data[0].emp_emergency_contact_mobile);

                let photFromPmis = '';
                if(data[0].emp_photo) {
                     photFromPmis = '<img class="defaultImg" src="'+data[0].emp_photo+'" alt="" width="70" height="80"/>';
                }else{
                     photFromPmis = '<img class="defaultImg" src="{{$defaultImg}}" alt="" width="70" height="80"/>';
                    $('#driver_photo').val('');
                }

                $('.defaultImgDiv').html(photFromPmis);
                //address permanent
                let selectedPerDivision = '';
                let selectedPerDistrict = '';
                let selectedPerThana    = '';

                if(data[1].division_id){
                 //   selectedPerDivision =data[1].division_id;
                    selectedPerDivision = '<option value="'+data[1].division_id+'">'+data[1].division_name+'</option>';
                }
                if(data[1].district_id){
                    selectedPerDistrict = '<option value="'+data[1].district_id+'">'+data[1].district_name+'</option>';
                }
                if(data[1].thana_id){
                    selectedPerThana    = '<option value="'+data[1].thana_id+'">'+data[1].thana_name+'</option>';
                }
                //$('#permanent_division_id').val(selectedPerDivision).trigger('change');
                $('#permanent_division_id').html(selectedPerDivision);
                $('#permanent_district_id').html(selectedPerDistrict);
                $('#permanent_thana_id').html(selectedPerThana);
                $('#permanent_address_line1').val(data[1].address_line_1);
                $('#permanent_address_line2').val(data[1].address_line_2);

                // //address present
                let selectedPreDivision ='';
                let selectedPreDistrict ='';
                let selectedPreThana='';
                if(data[2].division_id) {
                //    selectedPreDivision = data[2].division_id;
                    selectedPreDivision = '<option value="'+data[2].division_id+'">'+data[2].division_name+'</option>';
                }
                if(data[2].district_id){
                    selectedPreDistrict = '<option value="'+data[2].district_id+'">'+data[2].district_name+'</option>';
                }
                if(data[2].thana_id){
                    selectedPreThana    = '<option value="'+data[2].thana_id+'">'+data[2].thana_name+'</option>';
                }
                //$('#present_division_id').val(selectedPreDivision).trigger('change');
                $('#present_division_id').html(selectedPreDivision);
                $('#present_district_id').html(selectedPreDistrict);
                $('#present_thana_id').html(selectedPreThana);
                $('#present_address_line1').val(data[2].address_line_1);
                $('#present_address_line2').val(data[2].address_line_2);
            }

            function changeMaritalReadOnlyStatus(){
                var marital_status_id = $('#marital_status_id').val();
                if(marital_status_id != '2'){
                    $('#driver_spouse_name').val('');
                    $('#driver_spouse_name').prop('readonly',true);
                }else{
                    $('#driver_spouse_name').val('');
                    $('#driver_spouse_name').prop('readonly',false);
                }
            }
            function changeEffectFunction(){
                let driver_type_id = $("#driver_type_id").val();

                if(driver_type_id == '1'){

                    // $('#driver_name').prop('readonly',true);
                    // $('#driver_name_bn').prop('readonly',true);
                    // $('#driver_father_name').prop('readonly',true);
                    // $('#driver_mother_name').prop('readonly',true);
                    // $('#marital_status_id').prop('readonly',true);
                    // $('#driver_spouse_name').prop('readonly',true);
                    // $('#gender_type_id').prop('readonly',true);
                    // $('#dob').prop('readonly',true);
                    // $('#nid_no').prop('readonly',true);

                    //$('#mobile_no').prop('readonly',true);
                    //$('#emargency_no').prop('readonly',true);
                    $('.driver_emp_id_as_cpa_no').prop("required", true);
                    $('#driver_cpa_no').prop('required',false);

                    //address for internal
                    $('#present_division_id').select2({ containerCssClass: "bg_color_loc " });
                    $('#present_district_id').select2({ containerCssClass: "bg_color_loc " });
                    $('#present_thana_id').select2({ containerCssClass: "bg_color_loc " });
                    $('#present_address_line1').prop('readonly',true);
                    $('#present_address_line2').prop('readonly',true);

                    $('#permanent_division_id').select2({ containerCssClass: "bg_color_loc " });
                    $('#permanent_district_id').select2({ containerCssClass: "bg_color_loc " });
                    $('#permanent_thana_id').select2({ containerCssClass: "bg_color_loc " });
                    $('#permanent_address_line1').prop('readonly',true);
                    $('#permanent_address_line2').prop('readonly',true);

                    $('.dropdownStatus').css('pointer-events','none');
                    $('.dropdownStatus input').css('background-color','#F6F6F6');
                    $('.dropdownStatus select ').css('background-color','#F6F6F6');

                    $('#internal').removeClass('displayNone');
                    $('#external').addClass('displayNone');
                    selectCpaEmployee('.driver_emp_id_as_cpa_no', setCpaEmployeeDetails);

                }else{
                    // $('#driver_name').prop('readonly',false);
                    // $('#driver_name_bn').prop('readonly',false);
                    // $('#driver_father_name').prop('readonly',false);
                    // $('#driver_mother_name').prop('readonly',false);
                    // $('#marital_status_id').prop('readonly',false);
                    // $('#driver_spouse_name').prop('readonly',false);
                    // $('#gender_type_id').prop('readonly',false);
                    // $('#dob').prop('readonly',false);
                    // $('#nid_no').prop('readonly',false);

                       //$('#mobile_no').prop('readonly',false);
                       //$('#emargency_no').prop('readonly',false);
                    $('.driver_emp_id_as_cpa_no').prop("required", false);
                    $('#driver_cpa_no').prop('required',true);

                    //address for internal
                    $('#present_division_id').select2({ containerCssClass: "bg_color_none " });
                    $('#present_district_id').select2({ containerCssClass: "bg_color_none " });
                    $('#present_thana_id').select2({ containerCssClass: "bg_color_none " });
                    $('#present_address_line1').prop('readonly',false);
                    $('#present_address_line2').prop('readonly',false);

                    $('#permanent_division_id').select2({ containerCssClass: "bg_color_none " });
                    $('#permanent_district_id').select2({ containerCssClass: "bg_color_none " });
                    $('#permanent_thana_id').select2({ containerCssClass: "bg_color_none " });
                    $('#permanent_address_line1').prop('readonly',false);
                    $('#permanent_address_line2').prop('readonly',false);

                    $('.dropdownStatus').css('pointer-events','auto');
                    $('.dropdownStatus input').css('background-color','#FFF');
                    $('.dropdownStatus select').css('background-color','#FFF');

                    $('#external').removeClass('displayNone');
                    $('#internal').addClass('displayNone');
                    sameAsPermanentAddress();

                }
                @if(isset($data['insertedData']))
                {{-- var insertedData = [{!! json_encode($data['insertedData'])!!}];
                 if( (insertedData == undefined) || (insertedData == null) || (insertedData.length == 0)) {
                    resetFormSpecificElements();
                 }--}}
                @else
                resetFormSpecificElements();
                setPresentAddressDivisionForInternal();
                setPermanentAddressDivisionForInternal();
                @endif
                //reset form
            }
            function setPresentAddressDivisionForInternal(){
                let option = '';
                @if(isset($data['loadPresentDivision']))
                        var loadPresentDivision = [{!! json_encode($data['loadPresentDivision'])!!}];
                        $.each(loadPresentDivision, function (rowIndex, rowValue) {
                            option += rowValue;
                        });
                        $('#present_division_id').html(option).trigger('change');
                @endif

            }

            function setPermanentAddressDivisionForInternal(){
                let option = '';
                @if(isset($data['loadPermanentDivision']))
                    var loadPermanentDivision = [{!! json_encode($data['loadPermanentDivision'])!!}];
                    $.each(loadPermanentDivision, function (rowIndex, rowValue) {
                        option += rowValue;
                    });
                    $('#permanent_division_id').html(option).trigger('change');
                @endif
            }

            function sameAsPermanentAddress() {
                $("#sameAsPermanentAddress").on("change", function () {
                    if ($(this).prop("checked") == true) {

                        //$('#present_division_id').val($('#permanent_division_id option:selected').val()).trigger('change');

                        var present_division_id = $('#permanent_division_id option:selected').val();
                        var present_division_name = $('#permanent_division_id option:selected').text();

                        var present_district_id = $('#permanent_district_id option:selected').val();
                        var present_district_name = $('#permanent_district_id option:selected').text();

                        var present_thana_id = $('#permanent_thana_id option:selected').val();
                        var present_thana_name = $('#permanent_thana_id option:selected').text();

                        // //address present
                        let selectedPreDivision = '';
                        let selectedPreDistrict = '';
                        let selectedPreThana = '';
                        if (present_division_id) {
                            selectedPreDivision = '<option value="' + present_division_id + '">' + present_division_name + '</option>';
                        }
                        if (present_district_id) {
                            selectedPreDistrict = '<option value="' + present_district_id + '">' + present_district_name + '</option>';
                        }
                        if (present_thana_id) {
                            selectedPreThana = '<option value="' + present_thana_id + '">' + present_thana_name + '</option>';
                        }

                        $('#present_division_id').html(selectedPreDivision);
                        $('#present_district_id').html(selectedPreDistrict);
                        $('#present_thana_id').html(selectedPreThana);
                        $('#present_address_line1').val($('#permanent_address_line1').val());
                        $('#present_address_line2').val($('#permanent_address_line2').val());

                    } else if ($(this).prop("checked") == false) {
                       /* $('#present_division_id').val($('#permanent_division_id').val()).trigger('change');
                        $('#present_district_id').val('').trigger('change');
                        $('#present_thana_id').val('').trigger('change');*/
                        setPresentAddressDivisionForInternal();
                        $('#present_address_line1').val('');
                        $('#present_address_line2').val('');
                    }

                });
            }

            $("#driver_type_id").on("change", function () {
                changeEffectFunction();
            });

            $("#marital_status_id").on("change", function () {
                changeMaritalReadOnlyStatus();
            });

            changeMaritalReadOnlyStatus();
            changeEffectFunction();
            //datePickerUsingDiv('#datetimepicker1','DD-MM-YYYY');
            datePicker('#datetimepicker1');
            //datePickerUsingDiv('#datetimepicker1');
            datePicker('#datetimepicker2');
            datePicker('#datetimepicker3');

           $('#searchResultTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: APP_URL+'/vms/driver-enlist-datatable',
                columns: [
                    { data: 'driver_cpa_no', name: 'driver_cpa_no',searchable: true },
                    { data: 'driver_name', name: 'driver_name', searchable: true },
                    { data: 'dl_no', name: 'dl_no',searchable: true },
                    { data: 'driver_type_name', name: 'driver_type_name',searchable: true },
                    { data: 'dl_expiry_date', name: 'dl_expiry_date',searchable: true },
                    { data: 'active_yn', name: 'active_yn',searchable: true },
                    { data: 'action', name: 'action'},
                ]
            });

            function resetFormSpecificElements(){
                $('#driver_name').val('');
                $('#driver_name_bn').val('');
                $('#driver_father_name').val('');
                $('#driver_mother_name').val('');
                $('#marital_status_id').val('');
                $('#driver_spouse_name').val('');
                $('#gender_type_id').val('');
                $('#dob').val('');
                $('#nid_no').val('');
                $('#mobile_no').val('');
                $('#emargency_no').val('');
                $('.driver_emp_id_as_cpa_no').val('');
                $('#driver_cpa_no').val('');

                let photFromPmis = '<img class="defaultImg" src="{{$defaultImg}}" alt="" width="70" height="80"/>';
                $('.defaultImgDiv').html(photFromPmis);
                $('#driver_photo').val('');
                //address for internal
                /*$('#present_division_id').val('').trigger('change');
                $('#present_district_id').val('').trigger('change');
                $('#present_thana_id').val('').trigger('change');*/
                $('#present_address_line1').val('');
                $('#present_address_line2').val('');

                /*$('#permanent_division_id').val('').trigger('change');
                $('#permanent_district_id').val('').trigger('change');
                $('#permanent_thana_id').val('').trigger('change');*/
                $('#permanent_address_line1').val('');
                $('#permanent_address_line2').val('');

                if ($('#sameAsPermanentAddress').prop("checked") == true) {
                    $('#sameAsPermanentAddress').prop("checked",false);
                }
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

            function filterDlNoNumber()
            {
                $('.dl_no').on('keyup', function() {
                    numericAndMaxDigit(this);
                });
            }

            filterMobileNumber();
            filterNidNumber();
            //filterDlNoNumber();
        });



    </script>
@endsection


