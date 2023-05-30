@extends('layouts.default')

@section('title')
    License duty
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
                        <h4 class="card-title"> Swing Mooring
                            Service {{--{{ isset($data->id)?'Edit':'Add' }} License Duty Entry--}}</h4>
                        <form method="POST" action="">
                            {{ isset($data->id)?method_field('PUT'):'' }}
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label {{--class="input-required"--}}>Reporting Vessel
                                                Name{{--<span class="required"></span>--}}</label>
                                            <select name="cpa_vessel" class="form-control select2">
                                                <option value="">Select one</option>
                                                @forelse($cpaVesselNames as $cpaVesselName)
                                                    <option
                                                        {{ (old("cpa_vessel", $data->cpa_vessel_id) == $cpaVesselName->id) ? "selected" : "" }} value="{{ $cpaVesselName->id }}">{{$cpaVesselName->name}}</option>
                                                @empty
                                                    <option value="">CPA Vessel Name empty</option>
                                                @endforelse
                                            </select>
                                            @if ($errors->has('cpa_vessel'))
                                                <span class="help-block">{{ $errors->first('cpa_vessel') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label {{--class="input-required"--}}>Mooring license
                                                representative{{--<span class="required"></span>--}}</label>
                                            <select name="lm_rep" class="form-control lmRep"
                                                    oninput="this.value = this.value.toUpperCase()">
                                                {{--@if(old("lm_rep", $data->employee) != "")--}}
                                                @if(isset($data->employee))
                                                    <option selected
                                                            value="{{ old('lm_rep', $data->employee->emp_id) }}">{{$data->employee->emp_name}}</option>
                                                @endif
                                            </select>
                                            @if ($errors->has('lm_rep'))
                                                <span class="help-block">{{ $errors->first('lm_rep') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label>Designation: </label>
                                            <input type="text"
                                                   class="form-control"
                                                   id="p_desig"
                                                   readonly
                                            >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label>Department: </label>
                                            <input type="text"
                                                   class="form-control"
                                                   id="p_dept"
                                                   readonly
                                            >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="input-required">Mooring Use date<span
                                                        class="required"></span></label>
                                                <div class="input-group date"
                                                     onfocusout="$(this).datetimepicker('hide')" id="visit_date"
                                                     data-target-input="nearest">
                                                    <input type="text" autocomplete="off"
                                                           name="visit_date"
                                                           value="{{ old('visit_date', $data->visit_date) }}"
                                                           class="form-control datetimepicker-input "
                                                           data-target="#visit_date"
                                                           data-toggle="datetimepicker"
                                                           placeholder="Visit date"/>
                                                    <div class="input-group-append" data-target="#visit_date"
                                                         data-toggle="datetimepicker">
                                                        <div class="input-group-text">
                                                            <i class="bx bx-calendar"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if ($errors->has('visit_date'))
                                                    <span class="help-block">{{ $errors->first('visit_date') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Swing Mooring No<span class="required"></span></label>
                                            <select name="swing_moorings" class="select2">
                                                <option value="">Select one</option>
                                                @forelse($swingMooringsNames as $swingMooringsName)
                                                    <option
                                                        {{ (old("swing_moorings", $data->swing_mooring_id) == $swingMooringsName->id) ? "selected" : "" }} value="{{$swingMooringsName->id}}">{{$swingMooringsName->name}}</option>
                                                @empty
                                                    <option value="">Swing Moorings Name empty</option>
                                                @endforelse
                                            </select>
                                            @if ($errors->has('swing_moorings'))
                                                <span class="help-block">{{ $errors->first('swing_moorings') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                {{--                                <div class="col-md-4 displayNone">--}}
                                {{--                                    <div class="row my-1">--}}
                                {{--                                        <div class="col-md-12">--}}
                                {{--                                            <label class="">Serial No<span class=""></span></label>--}}
                                {{--                                            <input type="number" name="sl_no"  min="1" value="{{ old("sl_no", $data->sl_no) }}" placeholder="Serial No" class="form-control" />--}}
                                {{--                                            @if($errors->has("sl_no"))--}}
                                {{--                                            <span class="help-block">{{$errors->first("sl_no")}}</span>--}}
                                {{--                                            @endif--}}
                                {{--                                        </div>--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Local Vessel<span
                                                    class="required"></span></label>
                                            <select name="local_vessel" class="select2 l_vessel_id">
                                                <option value="">Select one</option>
                                                @forelse($localVesselNames as $localVesselName)
                                                    <option
                                                        {{ (old("local_vessel", $data->local_vessel_id) == $localVesselName->id) ? "selected" : "" }} value="{{$localVesselName->id}}">@if($localVesselName->reg_no!=null){{$localVesselName->name.' ('.$localVesselName->reg_no.')'}} @else {{$localVesselName->name}} @endif</option>
                                                @empty
                                                    <option value="">Local Vessel Name empty</option>
                                                @endforelse
                                            </select>
                                            <input type="hidden" id="agent_id" name="agent_id" class="form-control"/>
                                        </div>
                                    </div>
                                </div>
                                {{--<div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="">Agent<span class=""></span></label>
                                            <input type="text"
                                                   value="{{isset($data->agency_name)?$data->agency_name:''}}"
                                                   id="agent_name" name="agent_name" readonly class="form-control"/>
                                            <input type="hidden" id="agent_id" name="agent_id" class="form-control"/>
                                        </div>
                                    </div>
                                </div>--}}
                                {{--<div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label id="change_label">Shipping Agent</label>
                                            <select class="custom-select select2 form-control ship_agent"
                                                    style="width: 100%;"
                                                    id="ship_agent" name="ship_agent">
                                                @if(isset($data))
                                                    <option
                                                        value="{{$data->agency_id}}">{{$data->agency_name}}</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>--}}
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row my-1">
                                        <div class="d-flex justify-content-end col">
                                            <button type="submit" name="save" onclick="return confirm('Are you sure?')"
                                                    class="btn btn btn-dark shadow mr-1 mb-1">  {{ isset($data->id)?'Update':'Save' }} </button>
                                            <a type="reset" href="{{route("sm-license-duty-entry")}}"
                                               class="btn btn btn-outline-dark  mb-1"> Cancel</a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!--List-->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">License Duty Entry List</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <form method="POST" action="" id="search-form">
                            {{ isset($data->id)?method_field('PUT'):'' }}
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Visit From Date<span class="required"></span></label>
                                            <input type="date"
                                                   name="from_date" id="from_date" class="form-control" required
                                                   value=""
                                                   autocomplete="off">
                                            @if($errors->has("from_date"))
                                                <span class="help-block">{{$errors->first("from_date")}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Visit To Date<span
                                                    class="required"></span></label>
                                            <input type="date"
                                                   name="to_date" id="to_date" class="form-control" required
                                                   value=""
                                                   autocomplete="off">
                                            @if($errors->has("to_date"))
                                                <span class="help-block">{{$errors->first("to_date")}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">&nbsp;</label>
                                            <div class="d-flex justify-content-end col">
                                                <button type="button" name="search" id="search"
                                                        class="btn btn btn-dark shadow mr-1 mb-1"> Search
                                                </button>
                                                <a type="reset" href="{{route("sm-license-duty-entry")}}"
                                                   class="btn btn btn-outline-dark  mb-1"> Reset</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <hr>
                        <div class="">
                            <table class="table table-sm datatable">
                                <thead>
                                <tr>
                                    <th>Visit Date</th>
                                    <th>Swing Mooring</th>
                                    {{--                                    <th>Serial No.</th>--}}
                                    <th>Local Vessel</th>
                                    <th>CPA Vessel</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
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
        let shippingAgent = '{{route('get-shipping-agent')}}';
        let lastAgent = '{{route('get-last-agent')}}';

        $('.ship_agent').select2({
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

        $('.l_vessel_id').change(function () {
            let val = $(this).val();
            $.ajax({
                type: 'get',
                url: lastAgent,
                data: {vessel_id: val},
                success: function (msg) {
                    console.log(msg);
                    let data = msg.split('+');

                    $('#agent_id').val(data[0]);
                    $('#agent_name').val(data[1]);
                    if (data[0] == '') {
                        $("#change_label").addClass("required");
                        $('#ship_agent').prop('required', true);
                    } else {
                        $("#change_label").removeClass("required");
                        $("#ship_agent").removeAttr("required");
                    }

                }
            });
        });
        $(document).ready(function () {
            datePicker("#visit_date");
            datePicker("#fromDate");
            datePicker("#toDate");

            $('.lmRep').change(function () {
                let val = $(this).val();
                $.ajax({
                    type: 'get',
                    url: '/sm-license-duty-entry/sm-pilot-dtl',
                    data: {pid: val},
                    success: function (msg) {
                        //console.log(msg);
                        $('#p_desig').val(msg.designation);
                        $('#p_dept').val(msg.department_name);
                    }
                });
            });

            $(".lmRep").select2({
                placeholder: "Select one",
                allowClear: false,
                minimumInputLength: 1,
                ajax: {
                    delay: 250,
                    url: '/sm-license-duty-entry/sm-pilot-list',
                    dataType: 'json',
                    data: function (params) {
                        var queryParameters = {
                            q: params.term
                        };

                        return queryParameters;
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.emp_name,
                                    id: item.emp_id
                                }
                            })
                        };
                    },
                    cache: true
                }
            });


            var oTable = $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 20,
                lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
                initComplete: function (settings, json) {
                    $('body').find('.dataTables_scrollBody').css("height", "auto");
                    $('body').find('.dataTables_scrollBody').css("max-height", "300px");
                },
                ajax: {
                    url: '{{ route('sm-license-duty-entry-datatable', isset($data->id)?$data->id:0 ) }}',
                    type: 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: function (d) {
                        d.from_date = $('input[name=from_date]').val();
                        d.to_date = $('input[name=to_date]').val();
                    }
                },
                /*"columns": [
                    {"data": "visit_date"},
                    {"data": "swing_moorings.name"},
                    // {"data": "sl_no"},
                    {"data": "local_vessel.name"},
                    {"data": "cpa_vessel.name"},
                    {"data": "status"},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],*/
                "columns": [
                    {"data": "visit_date"},
                    {"data": "swing_mooring_name"},
                    // {"data": "sl_no"},
                    {"data": "local_vessel"},
                    {"data": "cpa_vessel"},
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

            $('#search').on('click', function (e) {
                oTable.draw();
                e.preventDefault();
            });
        });
    </script>

@endsection
