
@extends('layouts.default')

@section('title')
    Duties Employee
@endsection

@section('header-style')
    <!--Load custom style link or css-->
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <h4 class="card-title"> Search Duty Schedule</h4>
                           <div class="row">
                               <div class="col-md-3 mb-1">
                                   <label class="input-required" for="placement_type_id">Placement Type<span
                                           class="required"></span></label>
                                   <select name="placement_type_id" id="placement_type_id" class="form-control placement_type_id select2">
                                       <option value="">Select One</option>
                                       @foreach($placement_type as $val)
                                           <option {{ (old("placement_type_id", $data->placement_type_id) == $val->placement_type_id) ? "selected" : "" }} value="{{ $val->placement_type_id }}">{{$val->type_name}}</option>
                                       @endforeach
                                   </select>
                                   @if ($errors->has('placement_type_id'))
                                       <span class="help-block">{{ $errors->first('placement_type_id') }}</span>
                                   @endif
                               </div>
                               <div class="col-md-3 mb-1 placement">
                                   <label class="input-required" for="placement_id">Placement<span
                                           class="required"></span></label>
                                   <select name="placement_id" id="placement_id" class="form-control placement_id select2">
                                       <option value="">Select One</option>
                                       @foreach($placements as $val)
                                           <option {{ (old("placement_id", $data->placement_id) == $val->placement_id) ? "selected" : "" }} value="{{ $val->placement_id }}">{{$val->placement_name}}</option>
                                       @endforeach
                                   </select>
                                   @if ($errors->has('placement_id'))
                                       <span class="help-block">{{ $errors->first('placement_id') }}</span>
                                   @endif
                               </div>
                               <div class="col-md-3 mb-1 vessel">
                                   <label class="input-required" for="placement_vessel_id">Placement<span
                                           class="required"></span></label>
                                   <select name="placement_vessel_id" id="placement_vessel_id" class="form-control placement_vessel_id select2">
                                       <option value="">Select One</option>
                                       @foreach($vessels as $val)
                                           <option {{ (old("placement_id", $data->placement_id) == $val->id) ? "selected" : "" }} value="{{ $val->id }}">{{$val->name}}</option>
                                       @endforeach
                                   </select>
                                   @if ($errors->has('placement_vessel_id'))
                                       <span class="help-block">{{ $errors->first('placement_vessel_id') }}</span>
                                   @endif
                               </div>
                               <div class="col-md-3 mb-1">
                                   <label class="input-required" for="duty_year">Duty Year<span
                                           class="required"></span></label>
                                   <select name="duty_year" id="duty_year" class="form-control duty_year select2" required>
                                       <option value="">Select One</option>
                                       @foreach($year as $val)
                                           <option {{ (old("duty_year", $data->duty_year) == $val['value']) ? "selected" : "" }} value="{{ $val['value']}}">{{$val['text']}}</option>
                                       @endforeach
                                   </select>
                                   @if ($errors->has('duty_year'))
                                       <span class="help-block">{{ $errors->first('duty_year') }}</span>
                                   @endif
                               </div>
                               <div class="col-md-3 mb-1">
                                   <label class="input-required" for="duty_month">Duty Month<span
                                           class="required"></span></label>
                                   <select name="duty_month" id="duty_month" class="form-control duty_month select2" required>
                                       <option value="">Select One</option>
                                       @foreach($month as $key=>$val)
                                           <option {{ (old("duty_month", $data->duty_month) == $key) ? "selected" : "" }} value="{{$key }}">{{$val}}</option>
                                       @endforeach
                                   </select>
                                   @if ($errors->has('duty_month'))
                                       <span class="help-block">{{ $errors->first('duty_month') }}</span>
                                   @endif
                               </div>
                           </div>
                           <div class="row">
                               <div class="col-md-12 text-right">
                                   <button type="button" name="save" id="search"
                                           class="btn btn-dark shadow mr-1"><i
                                           class="bx bx-search"></i>Search
                                   </button>
                                   <a type="reset" href="{{route("cms.shifting.duties")}}"
                                      class="btn btn-outline-dark"><i
                                           class="bx bx-window-close"></i> Cancel</a>
                               </div>
                           </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Employee Duty Schedule
                    <a href="{{route('cms.shifting.duties-create')}}" class="btn btn-primary">Create New Schedule</a>
                    </h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
{{--                        <div class="row">--}}
{{--                            <div class="col-md-3 mb-1">--}}
{{--                                <label class="input-required" for="employee_id">Employee<span--}}
{{--                                        class="required"></span></label>--}}
{{--                                <select name="employee_id" id="employee_id" class="form-control employee_id select2">--}}
{{--                                    <option value="">Select One</option>--}}

{{--                                </select>--}}
{{--                                @if ($errors->has('employee_id'))--}}
{{--                                    <span class="help-block">{{ $errors->first('employee_id') }}</span>--}}
{{--                                @endif--}}
{{--                            </div>--}}
{{--                            <div class="col-md-3 mb-1">--}}
{{--                                <label class="input-required" for="joining_date">Joining Date</label>--}}
{{--                                <div class="input-group date" onfocusout="$(this).datetimepicker('hide')" id="joining_date" data-target-input="nearest">--}}
{{--                                    <input type="text" name="joining_date" id="joining_date"  value="{{ old('joining_date', $data->joining_date) }}" class="form-control joining_date" data-target="#joining_date" data-toggle="datetimepicker"  autocomplete="off" />--}}
{{--                                    <div class="input-group-append" data-target="#joining_date" data-toggle="datetimepicker">--}}
{{--                                        <div class="input-group-text">--}}
{{--                                            <i class="bx bx-calendar"></i>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                @if ($errors->has('joining_date'))--}}
{{--                                    <span class="help-block">{{ $errors->first('joining_date') }}</span>--}}
{{--                                @endif--}}
{{--                            </div>--}}
{{--                            <div class="col-md-3 mb-1">--}}
{{--                                <label class="input-required" for="off_day">Off Day<span--}}
{{--                                        class="required"></span></label>--}}
{{--                                <select name="off_day[]" id="off_day" class="form-control off_day select2" multiple="multiple">--}}

{{--                                </select>--}}
{{--                                @if ($errors->has('off_day'))--}}
{{--                                    <span class="help-block">{{ $errors->first('off_day') }}</span>--}}
{{--                                @endif--}}
{{--                            </div>--}}
{{--                            <div class="col-md-3 mb-1">--}}
{{--                                <div class="justify-content-start mt-2">--}}
{{--                                    <button id="add_new_employee" type="button" name="save"--}}
{{--                                            class="btn btn btn-dark shadow mr-1">Add</button>--}}
{{--                                    <a type="reset" id="reset_new_employee" class="btn btn btn-outline-dark"> Cancel</a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

                        <div class="table-responsive">
                            <table class="table table-sm dataTable" id="employee_duties_schedule">
                                <thead>
                                <tr>
                                    <th class="hidden">Employee Duty Id</th>
                                    <th class="hidden">Employee id</th>
                                    <th>Employee Code</th>
                                    <th>Employee Name</th>
                                    <th class="hidden">Designation Id/th>
                                    <th>Designation</th>
                                    <th class="hidden">Placement Id</th>
                                    <th>Placement</th>
                                    <th>Joining Date</th>
                                    <th>Off Day</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody class="employee_duties_schedule_items" id="employee_duties_schedule_items">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('footer-script')
    <!--Load custom script-->
    <script>
        $(document).ready(function () {
            datePicker("#joining_date");
            $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 20,
                lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
                initComplete: function (settings, json) {
                    $('body').find('.dataTables_scrollBody').css("height", "auto");
                    $('body').find('.dataTables_scrollBody').css("max-height", "300px");
                },
                ajax: {
                    url: '{{ route('cms.shifting.duties-datatable', isset($data->id)?$data->id:0 ) }}',
                    type: 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                "columns": [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                    {"data": "emp_code"},
                    {"data": "emp_name"},
                    {"data": "designation"},
                    {"data": "placement"},
                    {"data": "duty_year"},
                    {"data": "formatted_month"},
                    {"data": "formatted_joining_date"},
                    {"data": "status"},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                language: {
                    paginate: {
                        next: '<i class="bx bx-chevron-right">',
                        previous: '<i class="bx bx-chevron-left">'
                    }
                }
            });



            $("#search").click(function (e) {
                e.preventDefault();
                // $('#generate_transaction_btn').show();
                search_employee_duties();

            });

            function search_employee_duties() {
                var placement_type_id = $('#placement_type_id').val();
                var placement_id = $('#placement_id').val();
                var placement_vessel_id = $('#placement_vessel_id').val();
                var duty_year = $('#duty_year').val();
                var duty_month = $('#duty_month').val();
                if(!placement_type_id){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Please select a placement type',
                    })
                    return;
                }
                // if(!placement_id){
                //     Swal.fire({
                //         icon: 'error',
                //         title: 'Oops...',
                //         text: 'Please select a placement',
                //     })
                //     return;
                // }
                if(!duty_year){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Please select a duty year',
                    })
                    return;
                }
                if(!duty_month){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Please select a duty month',
                    })
                    return;
                }
                var searchFormData = {};
                searchFormData.placement_type_id = placement_type_id;
                searchFormData.placement_id = placement_id;
                searchFormData.placement_vessel_id = placement_vessel_id;
                searchFormData.duty_year = duty_year;
                searchFormData.duty_month = duty_month;
                var url = '{{route('cms.shifting.search-duty-schedule')}}';
                $.ajax({
                    url: url,
                    type: 'POST',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    data: searchFormData,
                    beforeSend: function () {
                        $('#ajaxLoader').show();
                    },
                    complete: function () {
                        $("#ajaxLoader").hide();
                    },
                    success: function (resp) {
                        if(resp.html){
                            $('#employee_duties_schedule_items').html(resp.html);
                        }else{
                            $('#employee_duties_schedule_items').html(resp.html);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Sorry! No data available',
                                })
                                return;
                        }
                        getOffDayList(searchFormData.duty_year,searchFormData.duty_month);

                    }
                });
                $('.employee_id').select2({
                    // minimumInputLength: 1,
                    ajax: {
                        url: '{{route('cms.ajax.search-employee')}}',
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                search_param: params.term
                            };
                        },
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (obj) {
                                    return {
                                        id: obj.emp_id,
                                        text: obj.emp_name.concat(', ',obj.emp_code),
                                    };
                                })
                            };
                        },
                        cache: false
                    },
                });

            }

            function getOffDayList(duty_year,duty_month) {
                $.ajax(
                    {
                        type: 'GET',
                        url: '{{route('cms.ajax.show-offday-by-year-month')}}',
                        data: {
                            duty_year: duty_year,
                            duty_month: duty_month
                        },
                        success: function (data) {
                            $("#off_day").html(data);
                        }
                    }
                );
            }

            $("#add_new_employee").click(function (e) {
                e.preventDefault();
                var placement_type_id = $('#placement_type_id').val();
                var placement_id = $('#placement_id').val();
                var placement_vessel_id = $('#placement_vessel_id').val();
                var duty_year = $('#duty_year').val();
                var duty_month = $('#duty_month').val();
                var employee_id = $('#employee_id').val();
                var joining_date = $('.joining_date').val();
                var off_day = $('#off_day').val();
                if(!employee_id){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Please select an employee',
                    })
                    return;
                }

                if(!off_day){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Please select a offday',
                    })
                    return;
                }
                if(!placement_type_id){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Please select a placement type',
                    })
                    return;
                }
                // if(!placement_id){
                //     Swal.fire({
                //         icon: 'error',
                //         title: 'Oops...',
                //         text: 'Please select a placement',
                //     })
                //     return;
                // }
                if(!duty_year){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Please select a duty year',
                    })
                    return;
                }
                if(!duty_month){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Please select a duty month',
                    })
                    return;
                }

                var addFormData = {};
                addFormData.employee_id = employee_id;
                addFormData.joining_date = joining_date;
                addFormData.off_day = off_day;
                addFormData.placement_type_id = placement_type_id;
                addFormData.placement_id = placement_id;
                addFormData.placement_vessel_id = placement_vessel_id;
                addFormData.duty_year = duty_year;
                addFormData.duty_month = duty_month;
                var url = '{{route('cms.shifting.duties-store')}}';
                $.ajax({
                    url: url,
                    type: 'POST',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    data: addFormData,
                    beforeSend: function () {
                        $('#ajaxLoader').show();
                    },
                    complete: function () {
                        $("#ajaxLoader").hide();
                    },
                    success: function (res) {
                        if (res.data.status == false) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: res.data.status_message,
                            })
                            return;
                        }else{
                            Swal.fire({
                                icon: 'success',
                                title: 'Great!',
                                text: res.data.status_message,
                            })
                            search_employee_duties();

                        }

                    }
                });

            });

            $("#placement_type_id").change(function(){
                show_hide(this.value);
            });
            var placement_type = $('#placement_type_id').val();
            show_hide(placement_type);
            function show_hide(placement_type) {
                if(placement_type=='1'){
                    $('.placement').hide();
                    $('.vessel').show();
                }else if(placement_type=='2'){
                    $('.placement').show();
                    $('.vessel').hide();
                }else{
                    $('.placement').show();
                    $('.vessel').hide();
                }
            }
        })

        // $('.employee_duties_schedule_items').on('click', '.edit_item_btn', function(e){
        //     e.preventDefault();
        //     let employee_duty_id =  $(this).data('employee_duty_id');
        //     let url=$(this).data('url');
        //     $.ajax(
        //         {
        //             type: 'GET',
        //             url: url,
        //             data: {
        //                 employee_duty_id: employee_duty_id,
        //             },
        //             success: function (data) {
        //                 $("#show_data").html(data.html);
        //             }
        //         }
        //     );
        //
        //
        // });
    </script>

@endsection



