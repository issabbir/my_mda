@extends('layouts.default')
@section('title')
    Vessel Engine Mapping
@endsection

@section('header-style')
    <!--Load custom style link or css-->
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            @include('cms.vessel.partial.vessel_info')
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <h4 class="card-title"> {{ isset($data->vessel_engine_id)?'Edit':'Add' }} Vessel Engine</h4>
                        <form method="POST" action="" enctype="multipart/form-data">
                            {{ isset($data->vessel_engine_id)?method_field('PUT'):'' }}
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-md-4 mb-1">
                                    <label class="input-required" for="name">Name<span
                                            class="required"></span></label>
                                    <input type="hidden" name="cpa_vessel_id" id="cpa_vessel_id" value="{{(app('request')->get('vessel_id'))?app('request')->get('vessel_id'):$data->cpa_vessel_id}}">
                                    <input type="hidden" id="vessel_engine_id" name="vessel_engine_id" value="{{$data->vessel_engine_id}}">
                                    <select name="engine_type_id" id="engine_type_id" class="form-control engine_type_id select2" autocomplete="off" required>
                                        <option value="">Select One</option>
                                        @foreach($engine_type as $val)
                                            <option {{ (old("engine_type_id", $data->engine_type_id) == $val->engine_id) ? "selected" : "" }} value="{{ $val->engine_id }}">{{$val->engine_name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('engine_type_id'))
                                        <span class="help-block">{{ $errors->first('engine_type_id') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-4 mb-1">
                                    <label class="input-required" for="hourly_consumed_fuel">Hourly Consumed Fuel(Ltr)<span
                                            class="required"></span></label>
                                    <input type="text" name="hourly_consumed_fuel"
                                           value="{{ old('hourly_consumed_fuel', $data->hourly_consumed_fuel) }}"
                                           class="form-control"
                                           oninput="this.value = this.value.toUpperCase()" required autocomplete="off"/>
                                    @if ($errors->has('hourly_consumed_fuel'))
                                        <span class="help-block">{{ $errors->first('hourly_consumed_fuel') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-4 mb-1">
                                    <label class="input-required">Status<span class="required"></span></label>
                                    <ul class="list-unstyled mb-0">
                                        <li class="d-inline-block mr-2 mb-1">
                                            <fieldset>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" class="custom-control-input"
                                                           value="{{ old('status','A') }}"
                                                           {{isset($data->status) && $data->status == 'A' ? 'checked' : ''}} name="status"
                                                           id="customRadio1" checked="">
                                                    <label class="custom-control-label"
                                                           for="customRadio1">Active</label>
                                                </div>
                                            </fieldset>
                                        </li>
                                        <li class="d-inline-block mr-2 mb-1">
                                            <fieldset>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" class="custom-control-input"
                                                           value="{{ old('status','I') }}"
                                                           {{isset($data->status) && $data->status == 'I' ? 'checked' : ''}} name="status"
                                                           id="customRadio2">
                                                    <label class="custom-control-label"
                                                           for="customRadio2">Inactive</label>
                                                </div>
                                            </fieldset>
                                        </li>
                                    </ul>
                                    @if ($errors->has('status'))
                                        <span class="help-block">{{ $errors->first('status') }}</span>
                                    @endif
                                </div>
{{--                                <div class="col-md-3 mb-1">--}}
{{--                                    <label class="input-required" for="max_fuel_capacity">Max Fuel Capacity<span--}}
{{--                                            class="required"></span></label>--}}
{{--                                    <input type="text" name="max_fuel_capacity"--}}
{{--                                           value="{{ old('max_fuel_capacity', $data->max_fuel_capacity) }}"--}}
{{--                                            class="form-control"--}}
{{--                                           oninput="this.value = this.value.toUpperCase()" required autocomplete="off"/>--}}
{{--                                    @if ($errors->has('max_fuel_capacity'))--}}
{{--                                        <span class="help-block">{{ $errors->first('max_fuel_capacity') }}</span>--}}
{{--                                    @endif--}}
{{--                                </div>--}}
{{--                                <div class="col-md-3 mb-1">--}}
{{--                                    <label class="input-required" for="horse_power">Horse Power<span--}}
{{--                                            class="required"></span></label>--}}
{{--                                    <input type="text" name="horse_power"--}}
{{--                                           value="{{ old('horse_power', $data->horse_power) }}"--}}
{{--                                           placeholder="1200" class="form-control"--}}
{{--                                           oninput="this.value = this.value.toUpperCase()" required autocomplete="off"/>--}}
{{--                                    @if ($errors->has('horse_power'))--}}
{{--                                        <span class="help-block">{{ $errors->first('horse_power') }}</span>--}}
{{--                                    @endif--}}
{{--                                </div>--}}
{{--                                <div class="col-md-3 mb-1">--}}
{{--                                    <label class="input-required" for="fuel_type_id">Fuel Type<span--}}
{{--                                            class="required"></span></label>--}}
{{--                                    <select name="fuel_type_id" id="fuel_type_id" class="form-control fuel_type_id select2" autocomplete="off" required>--}}
{{--                                        <option value="">Select One</option>--}}
{{--                                        @foreach($fuel_type as $val)--}}
{{--                                            <option {{ (old("fuel_type_id", $data->fuel_type_id) == $val->fuel_type_id) ? "selected" : "" }} value="{{ $val->fuel_type_id }}">{{$val->fuel_type_name}}</option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                    @if ($errors->has('fuel_type_id'))--}}
{{--                                        <span class="help-block">{{ $errors->first('fuel_type_id') }}</span>--}}
{{--                                    @endif--}}
{{--                                </div>--}}
                            </div>

                            <div class="row">
{{--                                <div class="col-md-3 mb-1">--}}
{{--                                    <label class="input-required" for="reserved_fuel">Reserved Fuel<span--}}
{{--                                            class="required"></span></label>--}}
{{--                                    <input type="text" name="reserved_fuel"--}}
{{--                                           value="{{ old('reserved_fuel', $data->reserved_fuel) }}"--}}
{{--                                            class="form-control"--}}
{{--                                           oninput="this.value = this.value.toUpperCase()" required autocomplete="off"/>--}}
{{--                                    @if ($errors->has('reserved_fuel'))--}}
{{--                                        <span class="help-block">{{ $errors->first('reserved_fuel')}}</span>--}}
{{--                                    @endif--}}
{{--                                </div>--}}
                            </div>

                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button type="submit" name="save" id="submit"
                                            class="btn btn-dark shadow mr-1"><i
                                            class="bx bx-save"></i>{{ isset($data->vessel_engine_id)?' Update':' Save' }}
                                    </button>
                                    <a type="reset" href="{{route("cms.vessel")}}"
                                       class="btn btn-outline-dark {{($data->vessel_engine_id)?'mr-1':''}}"><i
                                            class="bx bx-window-close"></i> Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!--List-->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"> Vessel Engine Mapping List</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table table-sm datatable">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Engine Type</th>
{{--                                    <th>Max Fuel Capacity</th>--}}
{{--                                    <th>Fuel Type</th>--}}
{{--                                    <th>Reserved Fuel</th>--}}
                                    <th>Hourly Consumed Fuel</th>
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
        $(document).ready(function () {
            let vessel_id=GetParameterValues('vessel_id');
            let edit_vessel_id=$('#cpa_vessel_id').val();
            $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 20,
                lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
                initComplete: function(settings, json) {
                    $('body').find('.dataTables_scrollBody').css("height", "auto");
                    $('body').find('.dataTables_scrollBody').css("max-height", "300px");
                },
                ajax: {
                    url:'{{route("cms.vessel-engine-mapping.datatable")}}',
                    type:'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    'data':{
                        'vessel_id':(vessel_id)?vessel_id:edit_vessel_id
                    },
                },
                "columns": [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex' },
                    {"data": "engine_type"},
                    // {"data": "max_fuel_capacity"},
                    // {"data": "fuel_type"},
                    // {"data": "reserved_fuel"},
                    {"data": "hourly_consumed_fuel"},
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
            function GetParameterValues(param) {
                var url = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
                for (var i = 0; i < url.length; i++) {
                    var urlparam = url[i].split('=');
                    if (urlparam[0] == param) {
                        return urlparam[1].replace("#/","");
                    }
                }
            }
            $('#incharge_emp_id').select2({
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
        });
    </script>

@endsection



