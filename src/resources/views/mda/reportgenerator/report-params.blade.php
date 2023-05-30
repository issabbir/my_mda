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
            @elseif($reportParam->component == 'agency')
                <div class="col-md-3">
                    <label for="{{$reportParam->param_name}}"
                           class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                    <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                            class="form-control"
                            @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                    </select>
                </div>


            @elseif($reportParam->component == 'slip_type')
                <div class="col-md-3">
                    <label for="{{$reportParam->param_name}}"
                           class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                    <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                            class="form-control select2"
                            @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                        <option value="">Select One</option>
                                    @forelse($slipType  as $vessel)
                                                    <option value="{{ $vessel->id }}">{{$vessel->name}}</option>
                                                @empty
                                                    <option value="">Slip Type list empty</option>
                                                @endforelse
                    </select>
                </div>


            @elseif($reportParam->component == 'select_dues')
                <div class="col-md-3">
                    <label for="{{$reportParam->param_name}}"
                           class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                    <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                            class="form-control"
                            @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                        <option value="">Select One</option>
                        <option value="1">Port Dues</option>
                        <option value="2">River Dues</option>
                        <option value="3">Barge Fee</option>
                        <option value="4">License Bill</option>

                    </select>
                </div>




            @elseif($reportParam->component == 'fr_vessel')
                <div class="col-md-3">
                    <label for="{{$reportParam->param_name}}"
                           class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                    <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                            class="custom-select select2 form-control foreign_vessel">
                        <option value="">Select One</option>
                        {{--@forelse($localV  as $vessel)
                            <option value="{{ $vessel->id }}">{{$vessel->name}}</option>
                        @empty
                            <option value="">Vessel list empty</option>
                        @endforelse--}}
                        @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                    </select>
                </div>

            @elseif($reportParam->component == 'local_vessel')
                <div class="col-md-3">
                    <label for="{{$reportParam->param_name}}"
                           class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                    <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                            class="form-control select2 vessel_id">
                        <option value="">Select One</option>
                        {{--@forelse($localV  as $vessel)
                            <option value="{{ $vessel->id }}">{{$vessel->name}}</option>
                        @empty
                            <option value="">Vessel list empty</option>
                        @endforelse--}}
                        @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                    </select>
                </div>
            @elseif($reportParam->component == 'agent_dropdown')
                <div class="col-md-3">
                    <label for="{{$reportParam->param_name}}"
                           class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                    <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                            class="form-control select2 fr_agent_id">
                        <option value="">Select One</option>
                        {{--@forelse($localV  as $vessel)
                            <option value="{{ $vessel->id }}">{{$vessel->name}}</option>
                        @empty
                            <option value="">Vessel list empty</option>
                        @endforelse--}}
                        @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                    </select>
                </div>
            @elseif($reportParam->component == 'comp_info')
                <div class="col-md-3">
                    <label for="{{$reportParam->param_name}}"
                           class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                    <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                            class="form-control select2">
                        <option value="">Select One</option>
                        @forelse($companyList  as $vessel)
                            <option value="{{ $vessel->comp_id }}">{{$vessel->company_name}}</option>
                        @empty
                            <option value="">Company list empty</option>
                        @endforelse
                        @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                    </select>
                </div>
            @elseif($reportParam->component == 'swingmooring_start')
                <div class="col-md-3">
                    <label for="{{$reportParam->param_name}}"
                           class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                    {{--<select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                            class="form-control select2">
                        <option value="">Select One</option>
                        <option value="1">Mooring (1-16)</option>
                        <option value="17">Mooring (17-28)</option>
                        @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                    </select>--}}
                    <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                            class="form-control select2">
                        <option value="">Select One</option>
                        @forelse($sm  as $value)
                            <option value="{{ $value->name }}">{{$value->name}}</option>
                        @empty
                            <option value="">List empty</option>
                        @endforelse
                        @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                    </select>
                </div>

            @elseif($reportParam->component == 'swingmooring_end')
                <div class="col-md-3">
                    <label for="{{$reportParam->param_name}}"
                           class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                    {{--<select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                            class="form-control select2">
                        <option value="">Select One</option>
                        <option value="1">Mooring (1-16)</option>
                        <option value="17">Mooring (17-28)</option>
                        @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                    </select>--}}
                    <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                            class="form-control select2">
                        <option value="">Select One</option>
                        @forelse($sm as $value)
                            <option value="{{ $value->name }}">{{$value->name}}</option>
                        @empty
                            <option value="">List empty</option>
                        @endforelse
                        @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                    </select>
                </div>


            @elseif($reportParam->component == 'dropdown')
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
                    <label for="{{$reportParam->param_name}}"
                           class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                    <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                            class="form-control"
                            @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
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
<script type="text/javascript">
    let vtmisVessel = '{{route('get-foreign-vessel')}}';
    let shippingAgent = '{{route('get-shipping-agent')}}';
    $('.foreign_vessel').select2({
        placeholder: "Select one",
        ajax: {
            url: vtmisVessel,
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
                console.log(data); //asif
                var formattedResults = $.map(data, function (obj, idx) {
                    // console.log(formattedResults);
                    // obj.id = obj.id;
                    obj.id = obj.v_r_id;
                    obj.text = obj.name;
                    return obj;
                });
                return {
                    results: formattedResults,
                };
            }
        }
    });

    $('.fr_agent_id').select2({
        placeholder: "Select one",
        ajax: {
            url: shippingAgent,
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
                    obj.id = obj.agency_id;
                    obj.text = obj.agency_name;
                    return obj;
                });
                return {
                    results: formattedResults,
                };
            }
        }
    });

    $('.vessel_id').select2({
        //minimumInputLength: 1,
        ajax: {
            url: '{{route('mwe.setting.search-vessel')}}',
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
                            id: obj.id,
                            text: obj.name,
                        };
                    })
                };
            },
            cache: false
        },
    });

    /*$('.vessel_id').on('change', function() {
        alert( this.value );
    });*/

    function datepicker(elem) {
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

    /*function agencies(){
        $('#p_agency_id').select2({
            placeholder: "Select",
            allowClear: true,
            ajax: {
                url: APP_URL+'/ajax/agencies',
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
                        obj.id = obj.agency_id;
                        obj.text = obj.agency_name;
                        return obj;
                    });
                    return {
                        results: formattedResults,
                    };
                }
            }
        });
    }*/
    function vessels() {
        $('#P_VESSEL_ID').select2({
            placeholder: "Select",
            allowClear: true,
            ajax: {
                url: APP_URL + '/reports/get-foreign-vessel-data',
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
                        obj.id = obj.foreign_vessel_id;
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

    function jetties() {
        $('#P_JETTY_ID').select2({
            placeholder: "Select",
            allowClear: true,
            ajax: {
                url: APP_URL + '/reports/get-jetty-data',
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
                        obj.id = obj.jetty_id;
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

    function cargos() {
        $('#P_CURGO_ID').select2({
            placeholder: "Select",
            allowClear: true,
            ajax: {
                url: APP_URL + '/reports/get-cargo-data',
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
                        obj.id = obj.cargo_id;
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

    function pilotage_types() {
        $('#P_PILOTAGE_TYPE_ID').select2({
            placeholder: "Select",
            allowClear: true,
            ajax: {
                url: APP_URL + '/reports/get-pilotage-types',
                data: function (params) {
                    console.log(params);
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
                        obj.id = obj.pilotage_id;
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

    function cpaVessels() {
        $('#P_CPA_VESSEL_ID').select2({
            placeholder: "Select",
            allowClear: true,
            ajax: {
                url: APP_URL + '/reports/get-cpa-vessel-data',
                data: function (params) {
                    console.log(params);
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
                    console.log('data', data);
                    var formattedResults = $.map(data, function (obj, idx) {
                        obj.id = obj.vessel_id;
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

    function localVessels() {
        $('#P_LOCAL_VESSEL_ID').select2({
            placeholder: "Select",
            allowClear: true,
            ajax: {
                url: APP_URL + '/reports/get-local-vessel-data',
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
                        obj.id = obj.local_vessel_id;
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

    function swingMoorings() {
        $('#P_SWING_MOORING_ID').select2({
            placeholder: "Select",
            allowClear: true,
            ajax: {
                url: APP_URL + '/reports/get-swing-mooring-data',
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
                        obj.id = obj.mooring_id;
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

    function pilots() {
        $('#P_PILOT_NAME').select2({
            placeholder: "Select",
            allowClear: true,
            ajax: {
                url: APP_URL + '/reports/get-cpa-pilots-data',
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
                        obj.id = obj.pilot_id;
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


    $(document).ready(function () {
        //TODO: Berthing schedule starts
        //vessels();
        jetties();
        cargos();
        pilotage_types();

        $("#P_IMPORT_DISCH").prop("type", "number");
        $("#P_B_ON_BOARD").prop("type", "number");
        $("#P_T_ON_BOARD").prop("type", "number");
        $("#P_IMPORT_DISCH").attr("min", "0");
        $("#P_B_ON_BOARD").attr("min", "0");
        $("#P_T_ON_BOARD").attr("min", "0");

        $("#P_VESSEL_ID").on("select2:select", function () {
            //console.log("hello");
            $("#P_LOCAL_AGENT").val("");
            $("#P_ARIVAL_AT").val("");

            let vessel_id = $("#P_VESSEL_ID").find(":selected").val();
            if (vessel_id !== null) {
                $.ajax({
                    dataType: 'JSON',
                    url: APP_URL + '/reports/foreign-vessel-detail/' + vessel_id,
                    cache: true,
                    success: function (data) {
                        let obj = data[0];

                        $("#P_LOCAL_AGENT").val(obj.shipping_agent_name);

                        let d = new Date(obj.arrival_date),
                            month = '' + (d.getMonth() + 1),
                            day = '' + d.getDate(),
                            year = d.getFullYear();
                        if (month.length < 2)
                            month = '0' + month;
                        if (day.length < 2)
                            day = '0' + day;

                        $(".P_ARIVAL_AT").val(day + "-" + month + "-" + year);
                    }
                });
            }
        });
        // Berthing schedule ends

        //TODO: License duty starts
        cpaVessels();
        localVessels();
        swingMoorings();
        $("#P_SL_NO").prop("type", "number");
        $("#P_SL_NO").attr("min", "0");

        // License duty ends
        pilots();
        datepicker('.datePiker');

        /*$('#P_COLLECTION_FORM').datetimepicker({
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
        });*/
        /*$('#P_COLLECTION_TO').datetimepicker({
            useCurrent: false,
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
        });*/
        $("#P_COLLECTION_FORM").on("change.datetimepicker", function (e) {
            $('#P_COLLECTION_TO').datetimepicker('minDate', e.date);
        });
        $("#P_COLLECTION_TO").on("change.datetimepicker", function (e) {
            $('#P_COLLECTION_FORM').datetimepicker('maxDate', e.date);
        });


    });
</script>
