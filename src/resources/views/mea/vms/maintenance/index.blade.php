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
                    <h5 style="Color: #132548" class="card-title">Vehicle Maintenance</h5>
                    <hr>
                    @include('mea.vms.maintenance.form')
                </div>
            </div>

            <div class="card">
                <!-- Table Start -->
                <div class="card-body">
                    <h4 class="card-title">Vehicle Maintenance List</h4><!---->
                    <hr>
                    <div class="table-responsive">
                        <table id="searchResultTable" class="table table-sm datatable mdl-data-table dataTable">
                            <thead>
                                    <tr>
                                        <th>Job No</th>
                                        <th>Vehicle Reg. No.</th>
                                        <th>Driver Name</th>
                                        <th>Driver Mobile</th>
                                        <th>Job Date</th>
                                        <th>Job End Date</th>
                                        <th>Workshop</th>
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



            //maxMinDatePickerUsingDiv('#datetimepicker3',new Date(),'','DD-MM-YYYY');


            var previous_workshop_id = '';
            var dynamic_drop_down_content = '';

            function populate_dropdown_with_workshop_id(workshop_id){
                let url = APP_URL+'/ajax/getWorkShopWiseService/';
                $.ajax({
                    type: "GET",
                    url: url+workshop_id,
                    async: false,
                    success: function (data) {
                        console.log(data);
                        dynamic_drop_down_content = data.html;
                        previous_workshop_id = workshop_id;
                    },
                    error: function (data) {
                        alert('error');
                    }
                });
            }

            @if(isset($data['get_workshop_list']))

                previous_workshop_id = $('#workshop_id').val();
            //alert(previous_workshop_id);
                populate_dropdown_with_workshop_id (previous_workshop_id);

            @endif


            $('#workshop_id').change(function () {
                var workshop_id = $(this).val();

                if(previous_workshop_id == ''){
                    previous_workshop_id = workshop_id;
                }
                var tbl_service_taken_tr_count = $('#tbl_service_taken > tbody >tr').length;
                console.log('tbl_service_taken_tr_count',tbl_service_taken_tr_count);

                if(previous_workshop_id != workshop_id && tbl_service_taken_tr_count > 0){

                    if(confirm('Are you sure to change ?')){
                        $('#tbl_service_taken > tbody').empty();
                        populate_dropdown_with_workshop_id(workshop_id);
                    }else{
                        $('#workshop_id').val(previous_workshop_id).trigger('change');
                    }

                }else{
                    populate_dropdown_with_workshop_id(workshop_id);
                }




            });




            $('.dropdownStatus').css('pointer-events','none');
            $('.dropdownStatus input').css('background-color','#F6F6F6');
            $('.dropdownStatus select ').css('background-color','#F6F6F6');

            /*datePickerUsingDiv('#datetimepicker2','DD-MM-YYYY');*/

             maxMinDatePickerUsingDiv('#datetimepicker2','',new Date(),'DD-MM-YYYY');
           // $('#job_date').val('');

            $("#datetimepicker2").on("change.datetimepicker", function (e) {
                //alert(1);
                $('#job_end_date').val('');
                console.log($('#job_date').val());

               // maxMinDatePickerUsingDiv('#datetimepicker2','',new Date(),'YYYY-MM-DD');

                let lowLimit    = moment($('#job_date').val(), "DD-MM-YYYY").format("DD-MM-YYYY");
                let heighLimit  = '';

                console.log('lowLimit',lowLimit);

                maxMinDatePickerUsingDiv('#datetimepicker3',lowLimit,heighLimit,'DD-MM-YYYY');


//APPLICATION DEADLINE
            });

          //  datePickerUsingDiv('#datetimepicker3','DD-MM-YYYY');

            var serviceFormIndex = 1;
            @if(isset($data['insertedData']))
            var insertedDetailsData = [{!! json_encode($data['insertedDetailsData'])!!}];
            console.log('insertedDetailsData',insertedDetailsData);
           // alert('HI');
            $.each(insertedDetailsData, function (rowIndex, rowValue) {
                $.each(rowValue, function (index, value) {

                    var maintanance_details_id_selected = value.maintanance_details_id;
                    var service_id_selected = value.service_id;
                    var service_date_selected = value.service_date;
                    var service_cost_selected = value.service_cost;
                    var comments_selected = value.comments;

                    rowGenerate(index + 1,service_id_selected,service_date_selected,service_cost_selected,comments_selected,maintanance_details_id_selected); //, value.emp_id is the insertedData To make Selected
                });
            });
            @endif

            if ($('#serviceForm tr')) {
                serviceFormIndex = $('#serviceForm tr').length + 1;
            }
            $('#addServiceForm').on('click', function (e) {
                //datePicker('#serviceDatePick'+serviceFormIndex);
                e.preventDefault();



                rowGenerate(serviceFormIndex);
                sumServiceJobCost();
            });

            $("#serviceForm").on('click', '.remove-education-form', function (e) {
                var rowNumArr = e.target.id.split('removeId-');
                let details_id = $('#maintanance_details_id'+rowNumArr[1]).val();
                let rowRemoveFlag =0;



                alertify.confirm('Confirm', 'Are you sure you want to delete this?',
                    function () {
                        if(
                            (details_id !== undefined) && (details_id !== null) && (details_id !== '')
                        ){

                            if(details_id){

                                $.ajax({
                                    type: "GET",
                                    url: APP_URL+'/ajax/maintain-details-delete/'+details_id,
                                    success: function (data) {
                                        alert(data.o_status_message);
                                        if(data.o_status_code == '1') {
                                            rowRemoveFlag =1;
                                            sumServiceJobCost();
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
                            sumServiceJobCost();
                        }

                    }
                    , function () {

                        alertify.error('Cancel');
                       // e.preventDefault();
                       // return false;

                    }
                );



            });

            function rowGenerate(index, service_id_selected = 0,service_date_selected = '',service_cost_selected=0,comments_selected='',maintanance_details_id_selected =0) {
                serviceFormIndex = index;
                var serviceFormDateIndex = '#serviceDatePick_'+index;

                let job_start_date = $('#job_date').val();
                let job_end_date   = $('#job_end_date').val();

                if(false == dateChecking()){
                    return;
                }

                var work_shop_id = $('#workshop_id').val();

                if(work_shop_id == null || work_shop_id == "" || work_shop_id == undefined){
                    alertify.alert('Error','Please select work shop id');
                    return;
                }

                job_start_date = moment(job_start_date,"DD-MM-YYYY").format("DD-MM-YYYY");
                job_end_date  = moment(job_end_date,"DD-MM-YYYY").format("DD-MM-YYYY");

                let dynamicServiceHiddenId = '<input type="hidden" name="maintanance_details_id['+serviceFormIndex+']" id="maintanance_details_id'+serviceFormIndex +'" value="" >';
                let dynamicServiceElement = '<select class="custom-select form-group" name="service_id['+ serviceFormIndex +']" id="service_id'+serviceFormIndex+'" required>' + dynamic_drop_down_content + '</select>';
               /* let dynamicServiceCostElement = '<input type="text" value="0" class="form-control service_cost" '+
                    'id="service_cost'+ serviceFormIndex +'"'+
                    'name="service_cost['+ serviceFormIndex +']"'+
                    'placeholder="Service cost."'+
                    '/>';*/

                let dynamicServiceCommentsElement = '<input type="text" value="" class="form-control" '+
                    'id="comments'+ serviceFormIndex +'"'+
                    'name="comments['+ serviceFormIndex +']"'+
                    'placeholder="comments"'+
                    '/>';

                let dynamicwatchmanInvoiceFormTemplate = '<tr role="row">' +
                    '<td aria-colindex="1" role="cell">'+dynamicServiceHiddenId+ dynamicServiceElement +'</td>'+
                    '<td aria-colindex="2" role="cell"><div class="input-group date " id="serviceDatePick_'+serviceFormIndex+'" data-target-input="nearest"><input type="text" value="" readonly class="form-control datetimepicker-input" data-toggle="datetimepicker" data-target="#serviceDatePick_'+serviceFormIndex+'" id="service_date_'+serviceFormIndex+'" name="service_date['+serviceFormIndex+']" autocomplete="off" placeholder="DD-MM-YYYY"/></div>' +
                    /*'<td aria-colindex="4" role="cell">'+dynamicServiceCostElement+'</td>' +*/
                    '<td aria-colindex="5" role="cell">'+dynamicServiceCommentsElement+'</td>' +
                    '<td aria-colindex="7" role="cell"><button id="removeId-'+serviceFormIndex+'" type="button" class="btn mr-2 btn-secondary btn-sm remove-education-form" name="removeServiceForm">Remove</button></td>' +
                    '</tr>'+
                    '<script>'+
                    'maxMinDatePickerUsingDiv("'+serviceFormDateIndex+'","'+job_start_date+'","'+job_end_date+'")' +
                    '<\/script>';

                $(dynamicwatchmanInvoiceFormTemplate).fadeIn("slow").appendTo('#serviceForm');
                $('#service_id' + serviceFormIndex).addClass(' select2 ');
                $('#service_id' + serviceFormIndex).select2({ width: '100%' });

                if (service_id_selected)
                    $('#service_id' + serviceFormIndex).val(service_id_selected).trigger('change');
                if (service_date_selected)
                    $('#service_date_' + serviceFormIndex).val(moment(service_date_selected).format("DD-MM-YYYY"));
                if (service_cost_selected)
                    $('#service_cost' + serviceFormIndex).val(service_cost_selected);
                if (comments_selected)
                    $('#comments' + serviceFormIndex).val(comments_selected);
                if (maintanance_details_id_selected)
                    $('#maintanance_details_id' + serviceFormIndex).val(maintanance_details_id_selected);

                serviceFormIndex++;
            }

            function dateChecking(){
                let job_start_date = $('#job_date').val();
                let job_end_date   = $('#job_end_date').val();

                if((job_start_date == undefined) || (job_start_date == null) || (job_start_date == '')){
                    alertify.alert('Error','Please fill up Job Start date');
                    $('#job_date').val('');
                    $('#job_date').focus();
                    return false;
                }
                if((job_end_date == undefined) || (job_end_date == null) || (job_end_date == '')){
                    alertify.alert('Error','Please fill up Job End date');
                    $('#job_end_date').val('');
                    $('#job_end_date').focus();
                    return false;
                }

                if(job_end_date<job_start_date){
                    alertify.alert('Error','Start Date can not be larger than End Date');
                    $('#job_date').val('');
                    $('#job_date').focus();
                    return false;
                }
                return true;
            }

            function sumServiceJobCost(){
                var index = 0;
                var sum=0;
                let service_cost =0;
                while (index<=serviceFormIndex){
                    //console.log($('#service_cost' + index).val());
                    service_cost = Number($('#service_cost' + index).val())? Number($('#service_cost' + index).val()): 0 ;
                    sum = service_cost+sum;
                    index++;
                }
                if(sum >0){
                    $('#job_cost').val(sum);
                }else{
                    $('#job_cost').val('');
                }
            }

            $(document).on('keyup', '.service_cost', function(){
                sumServiceJobCost();
            });

            $('#searchResultTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: APP_URL+'/vms/datatable-maintenance',
                columns: [
                    { data: 'job_no', name: 'job_no',searchable: true },
                    { data: 'vehicle_reg_no', name: 'vehicle_reg_no',searchable: true },
                    // { data: 'driver_cpa_no', name: 'driver_cpa_no', searchable: true },
                    { data: 'driver_name', name: 'driver_name',searchable: true },
                    { data: 'mobile_no', name: 'mobile_no',searchable: true },
                    { data: 'job_date', name: 'job_date',searchable: true },
                    { data: 'job_end_date', name: 'job_end_date',searchable: true },
                    { data: 'workshop_name', name: 'workshop_name',searchable: true },
                    { data: 'action', name: 'action'},
                ]
            });

            selectCpaEmployee('.job_request_id',setJobRequestByCpaEmployeeDetails,'/ajax/employee-details/','');
            selectCpaEmployee('.job_by',setJobByCpaEmployeeDetails,'/ajax/employee-details/',18);
            function selectCpaEmployee(clsSelector,callBack,targetUrl,empDept = '')
            {
                $(clsSelector).each(function() {

                    //let empDept = '18';

                    $(this).select2({
                        placeholder: "Select",
                        allowClear: false,
                        ajax: {
                            delay: 250,
                            url: APP_URL+'/ajax/employees/'+empDept,
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

            function setJobRequestByCpaEmployeeDetails(elem, data)
            {
                //console.log(data);
                $('#job_request_id').val(data.emp_id).trigger('change');
                $('#job_request_by_name').val(data.emp_name);
                $('#job_request_by_designation').val(data.designation);
                $('#job_request_by_department').val(data.department_name);
            }
            function setJobByCpaEmployeeDetails(elem, data)
            {
                //console.log(data);
                $('#job_by').val(data.emp_id).trigger('change');
                $('#job_by_name').val(data.emp_name);
                $('#job_by_designation').val(data.designation);
                $('#job_by_department').val(data.department_name);
            }

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

            $("#vehicle_id").on("change", function () {
                let vehicle_id = $("#vehicle_id").val();
                let url = APP_URL+'/ajax/driverdetails-by-vehicles/';
                if( ((vehicle_id !== undefined) || (vehicle_id != null)) && vehicle_id) {
                    $.ajax({
                        type: "GET",
                        url: url+vehicle_id,
                        success: function (data) {

                            //job_request_id
                            console.log(data);

                            $('#job_request_id').val(data.assigned_emp_id);
                            $('#job_request_id_name').val(data.emp_code_name);
                            $('#job_request_by_name').val(data.emp_name);
                            $('#job_request_by_designation').val(data.designation);
                            $('#job_request_by_department').val(data.department);

                            $('#driver_id').val(data.driver_id).trigger('change');
                        },
                        error: function (data) {
                            alert('error');
                        }
                    });
                } else {
                    $('#driver_name').val('');
                }
            });


            //function loadAndSelectWorkshop(){}

            $("#workshop_type_id").on("change", function () {
                let workshop_type_id = $(this).val();
                let url = APP_URL+'/ajax/workshops-by-workshopTypeId/';
                if( ((workshop_type_id !== undefined) || (workshop_type_id != null)) && workshop_type_id) {
                    $.ajax({
                        type: "GET",
                        url: url+workshop_type_id,
                        success: function (data) {
                            var newArr = [];

                            var option = {id: '' , text: "Select a option"};
                            newArr.push(option);

                            if(!jQuery.isEmptyObject(data)){
                                $.each(data,function(index,element){
                                    var option = {id: element.id , text: element.text};
                                    newArr.push(option);
                                })
                            }

                            $("#workshop_id").html('').select2({
                                placeholder: {
                                    id: "-1",
                                    text: '--- Please select a user ---',
                                    selected:'selected'
                                },
                                data : newArr
                            });

                           /* $("#workshop_id").select2({

                                data : data
                            });*/
                          /*  $('#workshop_id').val('1');
                            $('#workshop_id').trigger('change');
                           */


                           /* $( "#workshop_id" ).val(1).trigger('change.select2').select2({
                                placeholder: "Select One",
                                data : data
                            });*/
                            console.log(data);

                        },
                        error: function (data) {
                            alert('error');
                        }
                    });
                } else {
                    console.log('error');
                }
            });


            //Dummy
          //  $('#job_date').val('04-12-2020');
          //  $('#job_end_date').val('05-12-2020');
           // $('#workshop_type_id').val('20062817000347').trigger('change');
            //$('#vehicle_id').val('20062312080299').trigger('change');

        });

    </script>
@endsection


