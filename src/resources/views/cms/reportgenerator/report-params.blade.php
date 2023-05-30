<div class="col-12">
    <div class="row">
        @if($report)

            @if($report->params)
{{--                {{dd($report->params)}}--}}
                @foreach($report->params as $reportParam)
                    @if($reportParam->component == 'datepicker')
                        <div class="col-md-3">
                            <label for="{{$reportParam->param_label}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                            <div class="input-group date datePiker" id="{{$reportParam->param_name}}"
                                 data-target-input="nearest">
                                <input type="text" autocomplete="off"
                                       class="form-control datetimepicker-input {{ $reportParam->param_name }}"
                                       value="" name="{{$reportParam->param_name}}"
                                       data-toggle="datetimepicker"
                                       data-target="#{{$reportParam->param_name}}"
                                       @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required
                                       @endif onautocomplete="off"/>
                                <div class="input-group-append" data-target="#{{$reportParam->param_name}}"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="bx bxs-calendar"></i></div>
                                </div>
                            </div>
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
                    @elseif($reportParam->component == 'duty_year')
                        <div class="col-md-3">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                            <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                    class="form-control"
                                    @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                                <option value="">Select One</option>
                                @forelse($duty_years  as $duty_year)
                                    <option value="{{ $duty_year['value'] }}">{{$duty_year['value']}}</option>
                                @empty
                                    <option value="">Year list empty</option>
                                @endforelse
                            </select>
                        </div>
                    @elseif($reportParam->component == 'duty_month')
                        <div class="col-md-3">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                            <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                    class="form-control"
                                    @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                                <option value="">Select a month</option>
                                @forelse($duty_months  as $key=>$val)
                                    <option value="{{ $key}}">{{$val}}</option>
                                @empty
                                    <option value="">Month list empty</option>
                                @endforelse
                            </select>
                        </div>

                    @elseif($reportParam->component == 'placement_type')
                        <div class="col-md-3">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                            <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                    class="form-control"
                                    @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                                <option value="">Select a month</option>
                                @forelse($placement_types  as $val)
                                    <option value="{{$val->placement_type_id}}">{{$val->type_name}}</option>
                                @empty
                                    <option value="">Placement list empty</option>
                                @endforelse
                            </select>
                        </div>

                    @elseif($reportParam->component == 'placement')
                        <div class="col-md-3 placement">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                            <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                    class="form-control select2"
                                    @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                                <option value="">Select One</option>
                            @forelse($placements  as $placement)
                                <option value="{{$placement->placement_id }}">{{$placement->placement_name}}</option>
                            @empty
                                <option value="">Placement list empty</option>
                            @endforelse
                            </select>
                        </div>

                        <div class="col-md-3 vessel">
                            <label for="vessel"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">vessel</label>
                            <select name="p_placement_id" id="p_vessel_id"
                                    class="form-control"
                                    @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                            </select>
                        </div>

                    @elseif($reportParam->component == 'vessels')
                        <div class="col-md-3">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                            <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                    class="form-control"
                                    @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                            </select>
                        </div>

                    @elseif($reportParam->component == 'fuel_consumptions')
                        <div class="col-md-3">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                            <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                    class="form-control"
                                    @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                            </select>
                        </div>

                    @elseif($reportParam->component == 'status')
                        <div class="col-md-3">
                            <label for="{{$reportParam->param_name}}" class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                            <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}" class="form-control" @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                                <option value="">ALL</option>
                                <option value="A">ACTIVE</option>
                                <option value="P">PENDING</option>
                                <option value="C">APPROVED</option>
                            </select>
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
<script type="text/javascript">
    function datepicker(elem)
    {
        $(elem).datetimepicker({
            format: 'MM-DD-YYYY',
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
    }
    function fuel_consumption_no_by_vessel(id){
        $('#p_fuel_consumption_mst_id').select2({
            placeholder: "Select",
            allowClear: true,
            ajax: {
                url: '/cms/reports/fuel-consumption-by-vessel/'+id,
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
                        obj.id = obj.fuel_consumption_mst_id;
                        obj.text = obj.consumption_ref_no+', From Date:'+obj.consumption_from;
                        return obj;
                    });
                    return {
                        results: formattedResults,
                    };
                }
            }
        });
    }

    function vessels(){
        $('#p_vessel_id').select2({
            placeholder: "Select a vessel",
            allowClear: true,
            ajax: {
                url: '/cms/reports/get-vessel-data',
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
                        obj.id = obj.id;
                        obj.text = obj.name;
                        return obj;
                    });
                    return {
                        results: formattedResults,
                    };
                }
            }
        });
    }

    $(document).ready(function() {
        //TODO: Berthing schedule starts
        $("#p_vessel_id").change(function(){
            fuel_consumption_no_by_vessel(this.value);
        });
        vessels();
        datepicker('.datePiker');
        $("#p_placement_type_id").change(function(){
            show_hide(this.value);
        });
        var placement_type = $('#p_placement_type_id').val();
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
    });
</script>
