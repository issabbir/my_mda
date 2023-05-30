<div class="col-12">
    <div class="row">
        @if($report)
            @if($report->params)
                @foreach($report->params as $reportParam)

                    @if($reportParam->component == 'vehicle_id')
                        <div class="col-md-3">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                            <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}" class="form-control select2"
                                    @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif >
                                    @if($get_vehicle_reg_no_list)
                                            @foreach($get_vehicle_reg_no_list as $option)
                                                {!!$option!!}
                                            @endforeach
                                    @endif
                            </select>
                        </div>
                    @elseif($reportParam->component == 'driver_id')
                        <div class="col-md-3">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                            <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                    class="form-control select2"
                                    @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>

                                @if($get_driver_reg_list)
                                    @foreach($get_driver_reg_list as $option)
                                        {!!$option!!}
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    @elseif($reportParam->component == 'maintenance_id')
                        <div class="col-md-3">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                            <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                    class="form-control select2"
                                    @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>

                                @if($get_maintain_master_list)
                                    @foreach($get_maintain_master_list as $option)
                                        {!!$option!!}
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    @elseif($reportParam->component == 'workshop_id')
                        <div class="col-md-3">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                            <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                    class="form-control select2"
                                    @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>

                                @if($get_workshop_list)
                                    @foreach($get_workshop_list as $option)
                                        {!!$option!!}
                                    @endforeach
                                @endif
                            </select>
                        </div>
                   @elseif($reportParam->component == 'driver_type_id')

                        <div class="col-md-3">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}
                            </label>

                            <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                    class="form-control select2"
                                    @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                                @if($get_driver_type)
                                    @foreach($get_driver_type as $option)
                                        {!!$option!!}
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    @elseif($reportParam->component == 'job_by')
                        <div class="col-md-3">
                            <label for="{{$reportParam->param_name}}" class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                            <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                   class="form-control job_by select2"
                                   @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                           </select>
                        </div>
                    @elseif($reportParam->component == 'date_range')
                        <div class="col-md-3">
                            <label for="p_start_date"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">From
                                Date</label>
                            <div class="input-group date datePiker" id="p_start_date"
                                 data-target-input="nearest">
                                <input type="text" autocomplete="off"
                                       class="form-control datetimepicker-input"
                                       value="" name="p_start_date"
                                       data-toggle="datetimepicker"
                                       data-target="#p_start_date"
                                       @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required
                                       @endif onautocomplete="off"/>
                                <div class="input-group-append" data-target="#p_start_date"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="bx bxs-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="p_end_date"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">To
                                Date</label>
                            <div class="input-group date datePiker" id="p_end_date"
                                 data-target-input="nearest">
                                <input type="text" autocomplete="off"
                                       class="form-control datetimepicker-input"
                                       value="" name="p_end_date"
                                       data-toggle="datetimepicker"
                                       data-target="#p_end_date"
                                       @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required
                                       @endif onautocomplete="off"/>
                                <div class="input-group-append" data-target="#p_end_date"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="bx bxs-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                            <input type="text" name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                   class="form-control"
                                   @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif />
                        </div>
                    @endif
                @endforeach
            @endif
            <div class="col-md-3">
                <label for="type">Report Type</label>
                <select name="type" id="type" class="form-control">
                    <option value="pdf">PDF</option>
                    <option value="xlsx">Excel</option>
                </select>
                <input type="hidden" value="{{$report->report_xdo_path}}" name="xdo"/>
                <input type="hidden" value="{{$report->report_id}}" name="rid"/>
                <input type="hidden" value="{{$report->report_name}}" name="filename"/>
            </div>
            <div class="col-md-3 mt-2">
                <button type="submit" class="btn btn btn-dark shadow mr-1 mb-1 btn-secondary">Generate Report</button>
            </div>
        @endif
    </div>
</div>
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/css/forms/select/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/css/pickers/pickadate/pickadate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/css/pickers/daterange/daterangepicker.css')}}">
<script src="{{asset('assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/scripts/forms/select/form-select2.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        selectCpaEmployee('.job_by', '/ajax/employee-details/', 18);

    });

    function selectCpaEmployee(clsSelector,targetUrl,empDept = '')
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

        });
    }

    $('.datePiker').datetimepicker({
        format: 'YYYY-MM-DD',
        widgetPositioning: {
            horizontal: 'left',
            vertical: 'bottom'
        },
        icons: {
            date: 'bx bxs-calendar',
            previous: 'bx bx-chevron-left',
            next: 'bx bx-chevron-right'
        }
    });
</script>
