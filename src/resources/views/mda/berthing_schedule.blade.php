@extends('layouts.default')

@section('title')
    Berthing schedule
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
                        <h4 class="card-title"> {{ isset($data->id)?'Edit':'Add ' }}Berthing Schedule</h4>
                        <form method="POST" action="">
                            {{ isset($data->id)?method_field('PUT'):'' }}
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Vessel Name<span class="required"></span></label>
                                            <select name="vessel_id" id="vessel_id" class="form-control select2">
                                                <option value="">Select one</option>
                                                @foreach($vesselNames as $vesselName)
                                                    <option {{ ( old("vessel_id", $data->vessel_id) == $vesselName->id) ? "selected" : ""  }} value="{{$vesselName->id}}">{{$vesselName->name.'('.date('d-M-Y', strtotime($vesselName->arrival_date)).') '}}</option>
                                                @endforeach
                                            </select>
                                            @if($errors->has("vessel_id"))
                                                <span class="help-block">{{$errors->first("vessel_id")}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
{{--                                <div class="col-md-4">--}}
{{--                                    <div class="row my-1">--}}
{{--                                        <div class="col-md-12">--}}
{{--                                            <label class="input-required">MOTHER Vessel Name</label>--}}
{{--                                            <select name="mother_vessel_id" id="mother_vessel_id" class="form-control select2">--}}
{{--                                                <option value="">Select one</option>--}}
{{--                                                @foreach($vesselNames as $vesselName)--}}
{{--                                                    <option {{ ( old("mother_vessel_id", $data->mother_vessel_id) == $vesselName->id) ? "selected" : ""  }} value="{{$vesselName->id}}">{{$vesselName->name.'('.$vesselName->reg_no.') '}}</option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                            @if($errors->has("mother_vessel_id"))--}}
{{--                                                <span class="help-block">{{$errors->first("mother_vessel_id")}}</span>--}}
{{--                                            @endif--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Shipping Agent<span class="required"></span></label>
                                            <input type="text"
                                                   readonly="true"
                                                   id="local_agent1"
                                                   name="local_agent"
                                                   value="{{ old('local_agent', $data->local_agent) }}"
                                                   placeholder="Shipping Agent"
                                                   class="form-control"
                                                   oninput="this.value=this.value.toUpperCase()" />
                                            @if($errors->has("local_agent"))
                                                <span class="help-block">{{$errors->first("local_agent")}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label>LOA</label>
                                            <input type="text" readonly
                                                   id="length"
                                                   name="length"
                                                   value="{{ old('length', $data->length) }}"
                                                   placeholder="Loa" class="form-control"
                                                   min="0" />
                                            @if ($errors->has('length'))
                                                <span class="help-block">{{ $errors->first('length') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label>Draft</label>
                                            <input type="text" readonly
                                                   id="draft"
                                                   name="draft"
                                                   value="{{ old('draft', $data->draft) }}"
                                                   placeholder="Draft" class="form-control"
                                                   min="0" />
                                            @if ($errors->has('draft'))
                                                <span class="help-block">{{ $errors->first('draft') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Arrival Date<span class="required"></span></label>
                                            <div class="input-group date" onfocusout="$(this).datetimepicker('hide')" id="arrival_date" data-target-input="nearest">
                                                <input type="text" readonly name="arrival_at" value="{{ old('arrival_at', $data->arival_at) ? date("Y-m-d", strtotime(old('arrival_at', $data->arival_at))) : "" }}"
                                                       class="form-control arrival_at"
                                                       data-target="#arrival_date" data-toggle="datetimepicker"
                                                       placeholder="Arrival date"
                                                       oninput="this.value = this.value.toUpperCase()" />
                                                <div class="input-group-append" data-target="#arrival_date" data-toggle="datetimepicker">
                                                    <div class="input-group-text">
                                                        <i class="bx bx-calendar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            @if($errors->has("arrival_at"))
                                                <span class="help-block">{{$errors->first("arrival_at")}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row my-1 berthingField">
                                        <div class="col-md-12">
                                            <label class="input-required">Berthing Date<span class="required"></span></label>
                                            <div class="input-group date" onfocusout="$(this).datetimepicker('hide')" id="berthing_date" data-target-input="nearest">
                                                <input type="text" name="berthing_at" value="{{ old('berthing_at', $data->berthing_at) }}" class="form-control berthing_at" data-target="#berthing_date" data-toggle="datetimepicker" placeholder="Berthing Date"  oninput="this.value = this.value.toUpperCase()" />
                                                <div class="input-group-append" data-target="#berthing_date" data-toggle="datetimepicker">
                                                    <div class="input-group-text">
                                                        <i class="bx bx-calendar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            @if($errors->has("berthing_at"))
                                                <span class="help-block">{{$errors->first("berthing_at")}}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row my-1 shiftingField">
                                        <div class="col-md-12">
                                            <label class="input-required">Shifting Date<span class="required"></span></label>
                                            <div class="input-group date" onfocusout="$(this).datetimepicker('hide')" id="shifting_date" data-target-input="nearest">
                                                <input type="text" name="shifting_at" value="{{ old('shifting_at', $data->shifting_at) }}" class="form-control shifting_at" data-target="#shifting_date" data-toggle="datetimepicker" placeholder="Shifting date"  oninput="this.value = this.value.toUpperCase()" />
                                                <div class="input-group-append" data-target="#shifting_date" data-toggle="datetimepicker">
                                                    <div class="input-group-text">
                                                        <i class="bx bx-calendar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            @if($errors->has("shifting_at"))
                                                <span class="help-block">{{$errors->first("shifting_at")}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Proposed Departure<span class="required"></span></label>

                                            <div class="input-group date" onfocusout="$(this).datetimepicker('hide')" id="leaving_date" data-target-input="nearest">
                                                <input type="text" name="leaving_at" value="{{ old('leaving_at', $data->leaving_at) }}" class="form-control" data-target="#leaving_date" data-toggle="datetimepicker" placeholder="Leaving Date"  oninput="this.value = this.value.toUpperCase()" />
                                                <div class="input-group-append" data-target="#leaving_date" data-toggle="datetimepicker">
                                                    <div class="input-group-text">
                                                        <i class="bx bx-calendar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            @if($errors->has("leaving_at"))
                                                <span class="help-block">{{$errors->first("leaving_at")}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Pilotage Type<span class="required"></span></label>
                                            <select name="pilotage_type_id" class="form-control select2 pilotageType">
                                                <option value="">Select one</option>
                                                @forelse($pilotage_types as $pilotage_type)
                                                    <option {{ ( old("pilotage_type_id", $data->pilotage_type_id) == $pilotage_type->id) ? "selected" : ""  }} value="{{$pilotage_type->id}}">{{$pilotage_type->name}}</option>
                                                @empty
                                                    <option value=""> Pilotage type Name empty</option>
                                                @endforelse
                                            </select>
                                            @if ($errors->has('pilotage_type_id'))
                                                <span class="help-block">{{ $errors->first('pilotage_type_id') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Lying On<span class="required"></span></label>
                                            <select name="lying_on" class="form-control select2">
                                                <option value="">Select one</option>
                                                @forelse($tidal_cycles as $tidal_cycle)
                                                    <option {{ ( old("lying_on", $data->lying_on) == $tidal_cycle->tidal_cycle_id) ? "selected" : ""  }} value="{{$tidal_cycle->tidal_cycle_id}}">{{$tidal_cycle->tidal_cycle_name}}</option>
                                                @empty
                                                    <option value="">Lying On empty</option>
                                                @endforelse
                                            </select>
                                            @if ($errors->has('lying_on'))
                                                <span class="help-block">{{ $errors->first('lying_on') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
{{--                                <div class="col-md-4">--}}
{{--                                    <div class="row my-1">--}}
{{--                                        <div class="col-md-12">--}}
{{--                                            <label class="input-required">Pilotage Time<span class="required"></span></label>--}}
{{--                                            <div class="input-group date" onfocusout="$(this).datetimepicker('hide')" id="pilotage_time" data-target-input="nearest">--}}
{{--                                                <input type="text" name="pilotage_time" value="{{ old('pilotage_time', date('H:i',strtotime($data->pilotage_time))) }}" class="form-control pilotageTime" data-target="#pilotage_time" data-toggle="datetimepicker" placeholder="Start time"  oninput="this.value = this.value.toUpperCase()" />--}}
{{--                                                <div class="input-group-append" data-target="#pilotage_time" data-toggle="datetimepicker">--}}
{{--                                                    <div class="input-group-text">--}}
{{--                                                        <i class="bx bx-calendar"></i>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}

{{--                                            @if ($errors->has('pilotage_time'))--}}
{{--                                                <span class="help-block">{{ $errors->first('pilotage_time') }}</span>--}}
{{--                                            @endif--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">From location<span class="required"></span></label>
                                            <select name="jetty_id" class="form-control select2">
                                                <option value="">Select one</option>
                                                @forelse($jetty_types as $jetty_type)
                                                    <option {{ ( old("jetty_id", $data->jetty_id) == $jetty_type->jetty_id) ? "selected" : ""  }} value="{{$jetty_type->jetty_id}}">{{$jetty_type->jetty_name}}</option>
                                                @empty
                                                    <option value=""> Jetty Name empty</option>
                                                @endforelse
                                            </select>
                                            @if ($errors->has('jetty_id'))
                                                <span class="help-block">{{ $errors->first('jetty_id') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">To location<span class="required"></span></label>
                                            <select name="jetty_id_to" class="form-control select2">
                                                <option value="">Select one</option>
                                                @forelse($jetty_types as $jetty_type)
                                                    <option {{ ( old("jetty_id_to", $data->jetty_id_to) == $jetty_type->jetty_id) ? "selected" : ""  }} value="{{$jetty_type->jetty_id}}">{{$jetty_type->jetty_name}}</option>
                                                @empty
                                                    <option value=""> Jetty Name empty</option>
                                                @endforelse
                                            </select>
                                            @if ($errors->has('jetty_id_to'))
                                                <span class="help-block">{{ $errors->first('jetty_id_to') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Moor To<span class="required"></span></label>
                                            <select name="moor_to" class="form-control select2">
                                                <option value="">Select one</option>
                                                @forelse($tidal_cycles as $tidal_cycle)
                                                    <option {{ ( old("moor_to", $data->moor_to) == $tidal_cycle->tidal_cycle_id) ? "selected" : ""  }} value="{{$tidal_cycle->tidal_cycle_id}}">{{$tidal_cycle->tidal_cycle_name}}</option>
                                                @empty
                                                    <option value="">Moor To empty</option>
                                                @endforelse
                                            </select>
                                            @if ($errors->has('pilotage_type_id'))
                                                <span class="help-block">{{ $errors->first('pilotage_type_id') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
{{--                                <div class="col-md-4">--}}
{{--                                    <div class="row my-1">--}}
{{--                                        <div class="col-md-12">--}}
{{--                                            <label class="input-required">Cargo<span class="required"></span></label>--}}
{{--                                            <select name="cargo_id" class="form-control select2 cargo">--}}
{{--                                                <option value="">Select one</option>--}}
{{--                                                @forelse($cpa_cargos as $cpa_cargo)--}}
{{--                                                    <option {{ (old("cargo_id", $data->curgo_id) == $cpa_cargo->id) ? "selected" : ""  }} value="{{$cpa_cargo->id}}">{{$cpa_cargo->name}}</option>--}}
{{--                                                @empty--}}
{{--                                                    <option value=""> Cargo Name empty</option>--}}
{{--                                                @endforelse--}}
{{--                                            </select>--}}
{{--                                            @if ($errors->has('cargo_id'))--}}
{{--                                                <span class="help-block">{{ $errors->first('cargo_id') }}</span>--}}
{{--                                            @endif--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-md-4">--}}
{{--                                    <div class="row my-1">--}}
{{--                                        <div class="col-md-12">--}}
{{--                                            <label>Import Disch</label>--}}
{{--                                            <input type="number"--}}
{{--                                                   id="import_disch"--}}
{{--                                                   name="import_disch"--}}
{{--                                                   value="{{ old('import_disch', $data->import_disch) }}"--}}
{{--                                                   placeholder="Insert import disch"--}}
{{--                                                   class="form-control"--}}
{{--                                                   min="0"/>--}}
{{--                                            @if ($errors->has('import_disch'))--}}
{{--                                                <span class="help-block">{{ $errors->first('import_disch') }}</span>--}}
{{--                                            @endif--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-md-4">--}}
{{--                                    <div class="row my-1">--}}
{{--                                        <div class="col-md-12">--}}
{{--                                            <label>B ON Board</label>--}}
{{--                                            <input type="number"--}}
{{--                                                   min="0"--}}
{{--                                                   id="b_on_board"--}}
{{--                                                   name="b_on_board"--}}
{{--                                                   value="{{ old('b_on_board', $data->b_on_board) }}"--}}
{{--                                                   placeholder="Insert B on board" class="form-control"--}}
{{--                                                   min="0"/>--}}
{{--                                            @if ($errors->has('b_on_board'))--}}
{{--                                                <span class="help-block">{{ $errors->first('b_on_board') }}</span>--}}
{{--                                            @endif--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                            </div>

{{--                            <div class="row">--}}
{{--                                <div class="col-md-4">--}}
{{--                                    <div class="row my-1">--}}
{{--                                        <div class="col-md-12">--}}
{{--                                            <label>T ON Board</label>--}}
{{--                                            <input type="number"--}}
{{--                                                   id="T_on_board"--}}
{{--                                                   name="t_on_board"--}}
{{--                                                   value="{{ old('t_on_board', $data->t_on_board) }}"--}}
{{--                                                   placeholder="Insert T on board" class="form-control"--}}
{{--                                                   min="0"/>--}}
{{--                                            @if ($errors->has('t_on_board'))--}}
{{--                                                <span class="help-block">{{ $errors->first('t_on_board') }}</span>--}}
{{--                                            @endif--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-md-4">--}}
{{--                                    <div class="row my-1">--}}
{{--                                        <div class="col-md-12">--}}
{{--                                            <label>Exp Lefted</label>--}}
{{--                                            <input type="text"--}}
{{--                                                   id="exp_lefted"--}}
{{--                                                   name="exp_lefted"--}}
{{--                                                   value="{{ old('exp_lefted', $data->exp_lefted) }}"--}}
{{--                                                   placeholder="Exp lefted" class="form-control"--}}
{{--                                                   min="0" />--}}
{{--                                            @if ($errors->has('exp_lefted'))--}}
{{--                                                <span class="help-block">{{ $errors->first('exp_lefted') }}</span>--}}
{{--                                            @endif--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-md-4">--}}
{{--                                    <div class="row my-1">--}}
{{--                                        <div class="col-md-12">--}}
{{--                                            <label>Length</label>--}}
{{--                                            <input type="text"--}}
{{--                                                   id="length"--}}
{{--                                                   name="length"--}}
{{--                                                   value="{{ old('length', $data->length) }}"--}}
{{--                                                   placeholder="length" class="form-control"--}}
{{--                                                   min="0" />--}}
{{--                                            @if ($errors->has('length'))--}}
{{--                                                <span class="help-block">{{ $errors->first('length') }}</span>--}}
{{--                                            @endif--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row my-1">
                                        <div class="col-md-12" style="margin-top: 20px">
                                            <div class="d-flex justify-content-end col">
                                                <button type="submit" name="save" onclick="return confirm('Are you sure?')" class="btn btn btn-dark shadow mr-1 mb-1">  {{ isset($data->id)?'Update':'Save' }} </button>
                                                <a type="reset" href="{{route("berthing-schedule")}}" class="btn btn btn-outline-dark  mb-1"> Cancel</a>
                                            </div>
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
                    <h4 class="card-title"> Berthing List</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table table-sm datatable">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>JETTY</th>
                                    <th>VESSEL</th>
                                    <th>LOCAL AGENT</th>
                                    <th>ARRIVAL </th>
                                    <th>P. TYPE</th>
                                    <th>P. TIME</th>
                                    <th>CARGO </th>
                                    <th>BERTHING  </th>
                                    <th>SHIFTING  </th>
                                    <th>LEAVING  </th>
                                    {{--<th>IMPORT DISCH</th>--}}
                                    {{--<th>B ON BOARD</th>--}}
                                    {{--<th>T ON BOARD</th>--}}
                                    {{--<th>EXP LEFTED</th>--}}
                                    {{--<th>Status</th>--}}
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
            //Date time picker;
            function picker(selector) {
                var elem = $(selector);
                elem.datetimepicker({
                    format: 'HH:mm',
                    icons: {
                        time: 'bx bx-time',
                        date: 'bx bxs-calendar',
                        up: 'bx bx-up-arrow-alt',
                        down: 'bx bx-down-arrow-alt',
                        previous: 'bx bx-chevron-left',
                        next: 'bx bx-chevron-right',
                        today: 'bx bxs-calendar-check',
                        clear: 'bx bx-trash',
                        close: 'bx bx-window-close'
                    }
                });

                let preDefinedDate = elem.attr('data-predefined-date');

                if (preDefinedDate) {
                    let preDefinedDateMomentFormat = moment(preDefinedDate, "YYYY-MM-DD HH:mm").format("YYYY-MM-DD HH:mm A");
                    elem.datetimepicker('defaultDate', preDefinedDateMomentFormat);
                }
            }

            picker("#startTime");
            picker("#pilotage_time");
            datePicker("#leaving_date");
            datePicker("#berthing_date");
            datePicker("#shifting_date");

            $(".cargo").val(6).trigger('change');

            function show_hide_birthing_shifting() {
                if ($(".pilotageType").select2().find(":selected").text() === "SHIFTING"){
                    $(".berthingField").prop("hidden", true);
                    $(".berthing_at").prop("disabled", true);

                    $(".shiftingField").prop("hidden", false);
                    $(".shifting_at").prop("disabled", false);

                }else if ($(".pilotageType").select2().find(":selected").text() === "INWARD" || $(".pilotageType").select2().find(":selected").text() === "OUTWARD"){
                    $(".berthingField").prop("hidden", false);
                    $(".berthing_at").prop("disabled", false);

                    $(".shiftingField").prop("hidden", true);
                    $(".shifting_at").prop("disabled", true);
                }else{
                    $(".berthingField").prop("hidden", false);
                    $(".berthing_at").prop("disabled", true);

                    $(".shiftingField").prop("hidden", true);
                    $(".shifting_at").prop("disabled", true);
                }
            };

            $(".pilotageType").select2().on("change", function (e) {
                $(".berthing_at").val("");
                $(".shifting_at").val("");

                show_hide_birthing_shifting();
            });

            //At the time of update or redirect to this page with input need to show or hide berthing or shifting depending on pre selected pilotage type
            show_hide_birthing_shifting();


            $("#vessel_id").select2().on("change", function () {
                $("#local_agent1").val("");
                $(".arrival_at").val("");

                let vessel_id = $("#vessel_id").find(":selected").val();
                if (vessel_id !== null) {
                    $.ajax({
                        dataType:'JSON',
                        url: '/berthing-schedule/get-foreign-vessel-data/' + vessel_id,
                        cache: true,
                        success:function (data) {
                            $("#local_agent1").val(data["vesselDetail"].shipping_agent_name);

                            var d = new Date(data["vesselDetail"].arrival_date),
                                month = '' + (d.getMonth() + 1),
                                day = '' + d.getDate(),
                                year = d.getFullYear();
                            if (month.length < 2)
                                month = '0' + month;
                            if (day.length < 2)
                                day = '0' + day;

                            $(".arrival_at").val(year+"-"+month+"-"+day);
                            $("#length").val(data["vesselDetail"].loa);
                            $("#draft").val(data["vesselDetail"].draft);
                        }
                    });
                }
            });


            $(".pilotageType").select2().on("change", function (e) {

                if ($(this).find(":selected").text() === "SHIFTING"){

                    $(".berthing_at").prop("disabled", true);
                    $(".shifting_at").prop("disabled", false);
                }else if ($(this).find(":selected").text() === "INWARD" || $(this).find(":selected").text() === "OUTWARD"){
                    $("#shifting_date").val("");

                    $(".berthing_at").prop("disabled", false);
                    $(".shifting_at").prop("disabled", true);
                }else{
                    $(".berthing_at").prop("disabled", true);
                    $(".shifting_at").prop("disabled", true);
                }
            });


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
                    url:'{{ route('berthing-schedule-datatable', isset($data->id)?$data->id:0 ) }}',
                    type:'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                "columns": [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex' },
                    {"data": "jetty"},
                    {"data": "vessel_name"},
                    {"data": "local_agent"},
                    {"data": "arrival_at"},
                    {"data": "pilotage_type"},
                    {"data": "pilotage_time"},
                    {"data": "cpa_cargo"},
                    {"data": "berthing_date"},
                    {"data": "shifting_date"},
                    {"data": "leaving_at"},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                language: {
                    paginate: {
                        next: '<i class="bx bx-chevron-right">',
                        previous: '<i class="bx bx-chevron-left">'
                    }
                }
            });

        });
    </script>

@endsection
