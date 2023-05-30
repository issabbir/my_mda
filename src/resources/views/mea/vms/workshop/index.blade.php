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
                    <h5 style="Color: #132548" class="card-title">Workshop Setup </h5>
                    <hr>
                    @include('mea.vms.workshop.form')
                </div>
            </div>

            <div class="card">
                <!-- Table Start -->
                <div class="card-body">
                    <h4 class="card-title">Workshop List</h4><!---->
                    <hr>
                    <div class="table-responsive">
                        <table id="searchResultTable" class="table table-sm datatable mdl-data-table dataTable">
                            <thead>
                                    <tr>
                                        <th>Workshop No.</th>
                                        <th>Workshop Name</th>
                                        <th>Workshop Type</th>
                                        <th>Department</th>
                                        <th>Workshop Contact</th>
                                        <th>Location</th>
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

            $('#searchResultTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: APP_URL+'/vms/datatable-workshop',
                columns: [
                    { data: 'workshop_no', name: 'workshop_no',searchable: true },
                    { data: 'workshop_name', name: 'workshop_name',searchable: true },
                    { data: 'workshop_type_name', name: 'workshop_type_name',searchable: true },
                    { data: 'department_name', name: 'department_name',searchable: true },
                    { data: 'emp_name', name: 'emp_name',searchable: true },
                    { data: 'location_name', name: 'location_name',searchable: true },
                    { data: 'active_yn', name: 'active_yn',searchable: true },
                    { data: 'action', name: 'action'},
                ]
            });


            let empDept = $('#department_id').val();
            if(empDept){
                selectCpaEmployee('.employee_id',setJobByCpaEmployeeDetails,'/ajax/employee-details/',empDept);
            }else{
                alert('Please select department');
                $('#department_id').focus();
            }

            //selectCpaEmployee('.employee_id',setJobByCpaEmployeeDetails,'/ajax/employee-details/');
            /*$(document).on("change",'#department_id', function () {
                //let empDept = '1,2'; //$('#department_id').val();
                let empDept = $('#department_id').val();
                if(empDept){
                    selectCpaEmployee('.employee_id',setJobByCpaEmployeeDetails,'/ajax/employee-details/',empDept);
                }else{
                    alert('Please select department');
                    $('#department_id').focus();
                }
            });*/
            function selectCpaEmployee(clsSelector,callBack,targetUrl,empDept)
            {
                if(empDept){
                    $(clsSelector).each(function() {

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
                }else{
                    alert('Please select department');
                    $('#department_id').focus();
                }

            }

            function setJobByCpaEmployeeDetails(elem, data)
            {
                //console.log(data);
                $('#employee_in').val(data.emp_id).trigger('change');
                $('#employee_name').val(data.emp_name);
            }

        });

    </script>
@endsection


