
@extends('layouts.default')

@section('title')
    Vessel
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
                        <h4 class="card-title"> {{ isset($data->id)?'Edit':'Add' }} Vessel</h4>
                        <form method="POST" action="" enctype="multipart/form-data">
                            {{ isset($data->id)?method_field('PUT'):'' }}
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-md-3 mb-1">
                                    <label class="input-required" for="name">Name<span
                                            class="required"></span></label>
                                    <input type="text" name="name"
                                           value="{{ old('name', $data->name) }}"
                                           placeholder="Name" class="form-control"
                                           oninput="this.value = this.value.toUpperCase()" required autocomplete="off"/>
                                    @if ($errors->has('name'))
                                        <span class="help-block">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label class="input-required" for="vessel_type_id">Vessel Type<span
                                            class="required"></span></label>
                                    <select name="vessel_type_id" id="vessel_type_id" class="form-control vessel_type_id select2" autocomplete="off" required>
                                        <option value="">Select One</option>
                                        @foreach($vessel_type as $val)
                                            <option {{ (old("vessel_type_id", $data->vessel_type_id) == $val->id) ? "selected" : "" }} value="{{ $val->id }}">{{$val->name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('vessel_type_id'))
                                        <span class="help-block">{{ $errors->first('vessel_type_id') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label class="input-required" for="bhp">BHP<span
                                            class="required"></span></label>
                                    <input type="text" name="bhp"
                                           value="{{ old('bhp', $data->bhp) }}"
                                           placeholder="BHP" class="form-control"
                                           oninput="this.value = this.value.toUpperCase()" required autocomplete="off"/>
                                    @if ($errors->has('bhp'))
                                        <span class="help-block">{{ $errors->first('bhp') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label class="input-required" for="loa">LOA<span
                                            class="required"></span></label>
                                    <input type="text" name="loa"
                                           value="{{ old('loa', $data->loa) }}"
                                           placeholder="LOA" class="form-control"
                                           oninput="this.value = this.value.toUpperCase()" required autocomplete="off"/>
                                    @if ($errors->has('loa'))
                                        <span class="help-block">{{ $errors->first('loa') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3 mb-1">
                                    <label class="input-required" for="breadth">Breadth<span
                                            class="required"></span></label>
                                    <input type="text" name="breadth"
                                           value="{{ old('breadth', $data->breadth) }}"
                                           placeholder="Breadth" class="form-control"
                                           oninput="this.value = this.value.toUpperCase()" required autocomplete="off"/>
                                    @if ($errors->has('breadth'))
                                        <span class="help-block">{{ $errors->first('breadth') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label for="depth">Depth</label>
                                    <input type="text" name="depth"
                                           value="{{ old('depth', $data->depth) }}"
                                           placeholder="Depth" class="form-control"
                                           oninput="this.value = this.value.toUpperCase()" autocomplete="off"/>
                                    @if ($errors->has('depth'))
                                        <span class="help-block">{{ $errors->first('depth') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label class="input-required" for="draft">Draft<span
                                            class="required"></span></label>
                                    <input type="text" name="draft"
                                           value="{{ old('draft', $data->draft) }}"
                                           placeholder="Draft" class="form-control"
                                           oninput="this.value = this.value.toUpperCase()" required autocomplete="off"/>
                                    @if ($errors->has('draft'))
                                        <span class="help-block">{{ $errors->first('draft') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label class="input-required" for="build_year">Build Year<span
                                            class="required"></span></label>
                                    <input type="text" name="build_year"
                                           value="{{ old('build_year', $data->build_year) }}"
                                           placeholder="1985" class="form-control"
                                           oninput="this.value = this.value.toUpperCase()" required autocomplete="off"/>
                                    @if ($errors->has('build_year'))
                                        <span class="help-block">{{ $errors->first('build_year') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3 mb-1">
                                    <label class="input-required" for="build_place">Build Place<span
                                            class="required"></span></label>
                                    <input type="text" name="build_place"
                                           value="{{ old('build_place', $data->build_place) }}"
                                           placeholder="Build place" class="form-control"
                                           oninput="this.value = this.value.toUpperCase()" required autocomplete="off"/>
                                    @if ($errors->has('build_place'))
                                        <span class="help-block">{{ $errors->first('build_place') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label class="input-required" for="grt">Grt<span
                                            class="required"></span></label>
                                    <input type="text" name="grt"
                                           value="{{ old('grt', $data->grt) }}"
                                           placeholder="GRT" class="form-control"
                                           oninput="this.value = this.value.toUpperCase()" required autocomplete="off"/>
                                    @if ($errors->has('grt'))
                                        <span class="help-block">{{ $errors->first('grt') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label class="input-required" for="fuel_type_id">Fuel Type<span
                                            class="required"></span></label>
                                    <select name="fuel_type_id" id="fuel_type_id" class="form-control fuel_type_id select2" autocomplete="off" required>
                                        <option value="">Select One</option>
                                        @foreach($fuel_type as $val)
                                            <option {{ (old("fuel_type_id", $data->fuel_type_id) == $val->fuel_type_id) ? "selected" : "" }} value="{{ $val->fuel_type_id }}">{{$val->fuel_type_name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('fuel_type_id'))
                                        <span class="help-block">{{ $errors->first('fuel_type_id') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label class="input-required" for="incharge_emp_id">incharge<span
                                            class="required"></span></label>
                                    <select name="incharge_emp_id" id="incharge_emp_id" class="form-control incharge_emp_id select2" autocomplete="off" required>
                                        @if(isset($incharge->emp_id))
                                            <option value="{{$incharge->emp_id}}" selected>{{isset($incharge->emp_name) ? $incharge->emp_name.', '.$incharge->emp_code : ''}}</option>
                                        @endif
                                        <option value="">Select One</option>
                                    </select>
                                    @if ($errors->has('incharge_emp_id'))
                                        <span class="help-block">{{ $errors->first('incharge_emp_id') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3 mb-1">
                                    <label class="input-required" for="department_id">Department<span
                                            class="required"></span></label>
                                    <select name="department_id" id="department_id" class="form-control department_id select2" autocomplete="off" required>
                                        <option value="">Select One</option>
                                        @foreach($depts as $val)
                                            <option {{ (old("department_id", $data->department_id) == $val->department_id) ? "selected" : "" }} value="{{ $val->department_id }}">{{$val->department_name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('department_id'))
                                        <span class="help-block">{{ $errors->first('department_id') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-3 mb-1">
                                        <label class="input-required" for="reserved_fuel">Reserved Fuel<span
                                                class="required"></span></label>
                                        <input type="text" name="reserved_fuel"
                                               value="{{ old('reserved_fuel', $data->reserved_fuel) }}"
                                               placeholder="reserved fuel" class="form-control"
                                               oninput="this.value = this.value.toUpperCase()" required autocomplete="off" {{($data->id)?'disabled':''}}/>
                                        @if ($errors->has('reserved_fuel'))
                                            <span class="help-block">{{ $errors->first('reserved_fuel') }}</span>
                                        @endif
                                </div>
                                <div class="col-md-3 mb-1">
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
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button type="submit" name="save" id="submit"
                                            class="btn btn-dark shadow mr-1"><i
                                            class="bx bx-save"></i>{{ isset($data->id)?' Update':' Save' }}
                                    </button>
                                    <a type="reset" href="{{route("cms.vessel")}}"
                                       class="btn btn-outline-dark {{($data->id)?'mr-1':''}}"><i
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
                    <h4 class="card-title"> Vessel List</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table table-sm datatable">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Name</th>
                                    <th>Vessel Type</th>
                                    <th>Fuel Type</th>
                                    <th>Build Year</th>
                                    <th>GRT</th>
                                    <th>incharge</th>
                                    <th>Reserved Fuel</th>
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
                    url:'{{ route('cms.vessel.datatable') }}',
                    type:'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                "columns": [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex' },
                    {"data": "name"},
                    {"data": "vessel_type"},
                    {"data": "fuel_type"},
                    {"data": "build_year"},
                    {"data": "grt"},
                    {"data": "incharge"},
                    {"data": "reserved_fuel"},
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



