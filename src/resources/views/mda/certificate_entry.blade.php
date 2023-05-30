@extends('layouts.default')

@section('title')
    Certificate entry
@endsection

@section('header-style')
    <!--Load custom style link or css-->
    <style>
        .invalid {
            color: #F00;
            background-color: #FFF;
        }
    </style>
@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <h4 class="card-title">{{ isset($data->id) ? 'Edit' : 'Add' }} Approval Information and Pilotage
                            Service Certificate</h4>
                        <form id="certificateForm" method="POST" action="" autocomplete="off"
                              enctype="multipart/form-data">
                            {{ isset($data->id) ? method_field('PUT') : '' }}
                            {!! csrf_field() !!}

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{-- This div to handle custom error --}}
                                        <div>
                                            <label>Pilotage type<span class="required"></span></label>

                                            <select name="pilotage_type_id"
                                                    class="form-control select2 pilotageType customError">
                                                <option value="">Select one</option>
                                                @forelse($pilotageType as $type)
                                                    <option
                                                        {{ old('pilotage_type_id', $data->pilotage_type_id) == $type->id ? 'selected' : '' }}
                                                        value="{{ $type->id }}">{{ $type->name }}</option>
                                                @empty
                                                    <option value="">Pilotage empty</option>
                                                @endforelse
                                            </select>
                                            @if ($errors->has('pilotage_type_id'))
                                                <span class="help-block">{{ $errors->first('pilotage_type_id') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{--                                <div class="col-md-4 pilotageFromField pilotageCalcFrom"> --}}
                                {{--                                    <div class="form-group"> --}}
                                {{--                                        <label>Pilotage From Time <span class="required"></span></label> --}}
                                {{--                                        <div class="input-group date pilotageCalcFrom" --}}
                                {{--                                             id="pilotageFrom" --}}
                                {{--                                             data-target-input="nearest"> --}}
                                {{--                                            <input type="text" --}}
                                {{--                                                   id="pilotage_from_time" --}}
                                {{--                                                   name="pilotage_from_time" --}}
                                {{--                                                   value="{{ old('pilotage_from_time', isset($data->pilotage_from_time) ?  date('Y-m-d H:i', strtotime($data->pilotage_from_time)) : date('Y-m-d H:i')) }}" --}}
                                {{--                                                   class="form-control datetimepicker-input pilotage_from pilotageCalcFrom" --}}
                                {{--                                                   data-target="#pilotageFrom" --}}
                                {{--                                                   data-toggle="datetimepicker" --}}
                                {{--                                                   placeholder="Pilot from time"> --}}
                                {{--                                            <div class="input-group-append pilotageCalcFrom" data-target="#pilotageFrom" --}}
                                {{--                                                 data-toggle="datetimepicker"> --}}
                                {{--                                                <div class="input-group-text"> --}}
                                {{--                                                    <i class="bx bx-calendar"></i> --}}
                                {{--                                                </div> --}}
                                {{--                                            </div> --}}
                                {{--                                        </div> --}}
                                {{--                                        @if ($errors->has('pilotage_from_time')) --}}
                                {{--                                            <span class="help-block">{{ $errors->first('pilotage_from_time') }}</span> --}}
                                {{--                                        @endif --}}
                                {{--                                    </div> --}}
                                {{--                                </div> --}}


                                {{--                                <div class="col-md-4 pilotageToField pilotageCalcTo"> --}}
                                {{--                                    <div class="form-group"> --}}
                                {{--                                        <label>Pilotage To Time<span class="required"></span></label> --}}
                                {{--                                        <div class="input-group date pilotageCalcTo" --}}
                                {{--                                             onfocusout="$(this).datetimepicker('hide')" id="pilotageTo" --}}
                                {{--                                             data-target-input="nearest"> --}}
                                {{--                                            <input type="text" --}}
                                {{--                                                   name="pilotage_to_time" --}}
                                {{--                                                   value="{{ old('pilotage_to_time', isset($data->pilotage_to_time) ?  date('Y-m-d H:i', strtotime($data->pilotage_to_time)) : date('Y-m-d H:i') ) }}" --}}
                                {{--                                                   class="form-control datetimepicker-input pilotage_to pilotageCalcTo" --}}
                                {{--                                                   data-target="#pilotageTo" --}}
                                {{--                                                   data-toggle="datetimepicker" --}}
                                {{--                                                   placeholder="Pilotage to time"/> --}}
                                {{--                                            <div class="input-group-append pilotageCalcTo" data-target="#pilotageTo" --}}
                                {{--                                                 data-toggle="datetimepicker"> --}}
                                {{--                                                <div class="input-group-text"> --}}
                                {{--                                                    <i class="bx bx-calendar"></i> --}}
                                {{--                                                </div> --}}
                                {{--                                            </div> --}}
                                {{--                                        </div> --}}
                                {{--                                        <div class="totalPilotage"></div> --}}
                                {{--                                        @if ($errors->has('pilotage_to_time')) --}}
                                {{--                                            <span class="help-block">{{ $errors->first('pilotage_to_time') }}</span> --}}
                                {{--                                        @endif --}}
                                {{--                                    </div> --}}
                                {{--                                </div> --}}


                                <div class="col-md-4 shiftedFromField">
                                    <div class="form-group ">
                                        <label>Shifted from<span class="required"></span></label>
                                        <select name="shifted_from"
                                                class="form-control select2 shifted_from customError">
                                            <option value="">Select one</option>
                                            @forelse($jettyList as $jetty)
                                                <option
                                                    {{ old('shifted_from', $data->shifted_from) == $jetty->jetty_id ? 'selected' : '' }}
                                                    value="{{ $jetty->jetty_id }}">{{ $jetty->jetty_name }}</option>
                                            @empty
                                                <option value="">Jetty is empty</option>
                                            @endforelse
                                        </select>
                                        @if ($errors->has('shifted_from'))
                                            <span class="help-block">{{ $errors->first('shifted_from') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4 shiftedToField">
                                    <div class="form-group">
                                        <label>Shifted to<span class="required"></span></label>
                                        <select name="shifted_to" class="form-control select2 shifted_to customError">
                                            <option value="">Select one</option>
                                            @forelse($jettyList as $jetty)
                                                <option
                                                    {{ old('shifted_to', $data->shifted_to) == $jetty->jetty_id ? 'selected' : '' }}
                                                    value="{{ $jetty->jetty_id }}">{{ $jetty->jetty_name }}</option>
                                            @empty
                                                <option value="">Jetty is empty</option>
                                            @endforelse
                                        </select>
                                        @if ($errors->has('shifted_to'))
                                            <span class="help-block">{{ $errors->first('shifted_to') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- --------------- --}}

                            {{--                            <div class="row pilotage_location"> --}}
                            {{--                                <div class="col-md-4 pilotageFromField pilotage_from_loc"> --}}
                            {{--                                    <div class="form-group"> --}}
                            {{--                                        <label>Pilotage From Location </label> --}}
                            {{--                                        <input type="text" class="form-control" name="pilotage_from_loc" --}}
                            {{--                                               id="pilotage_from_loc" --}}
                            {{--                                               value="{{old('pilotage_from_loc',$data->pilotage_from_loc)}}"> --}}
                            {{--                                        @if ($errors->has('pilotage_from_loc')) --}}
                            {{--                                            <span class="help-block">{{ $errors->first('pilotage_from_loc') }}</span> --}}
                            {{--                                        @endif --}}
                            {{--                                    </div> --}}
                            {{--                                </div> --}}


                            <div class="row pilotage_location">
                                <div class="col-md-4 pilotageFromField pilotage_from_loc">
                                    <div class="form-group">
                                        <label>Pilotage From Location <span class="required"></span></label>
                                        <select name="pilotage_from_loc" id="pilotage_from_loc"
                                                class="form-control select2 pilotage_from_loc customError"
                                                oninput="this.value = this.value.toUpperCase()">
                                            <option value="">Select a location</option>
                                            @forelse($jettyList as $name)
                                                <option
                                                    {{ old('pilotage_from_loc', $data->pilotage_from_loc) == $name->jetty_id ? 'selected' : '' }}
                                                    value="{{ $name->jetty_id }}">{{ $name->jetty_name }}
                                                </option>
                                            @empty
                                                <option value="">No location found</option>
                                            @endforelse
                                        </select>
                                        @if ($errors->has('pilotage_from_loc'))
                                            <span class="help-block">{{ $errors->first('pilotage_from_loc') }}</span>
                                        @endif
                                    </div>
                                </div>


                                <div class="col-md-4 pilotageFromField pilotage_to_loc">
                                    <div class="form-group">
                                        <label>Pilotage To Location <span class="required"></span></label>
                                        <select name="pilotage_to_loc" id="pilotage_to_loc"
                                                class="form-control select2 pilotage_to_loc customError"
                                                oninput="this.value = this.value.toUpperCase()">
                                            <option value="">Select a location</option>
                                            @forelse($jettyList as $name)
                                                <option
                                                    {{ old('pilotage_to_loc', $data->pilotage_to_loc) == $name->jetty_id ? 'selected' : '' }}
                                                    value="{{ $name->jetty_id }}">{{ $name->jetty_name }}
                                                </option>
                                            @empty
                                                <option value="">No location found</option>
                                            @endforelse
                                        </select>
                                        @if ($errors->has('pilotage_to_loc'))
                                            <span class="help-block">{{ $errors->first('pilotage_to_loc') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>


                            {{--                                <div class="col-md-4 pilotageFromField pilotage_to_loc"> --}}
                            {{--                                    <div class="form-group"> --}}
                            {{--                                        <label>Pilotage To Location </label> --}}
                            {{--                                        <input type="text" class="form-control" name="pilotage_to_loc" --}}
                            {{--                                               id="pilotage_to_loc" --}}
                            {{--                                               value="{{old('pilotage_to_loc',$data->pilotage_to_loc)}}"> --}}
                            {{--                                        @if ($errors->has('pilotage_to_loc')) --}}
                            {{--                                            <span class="help-block">{{ $errors->first('pilotage_to_loc') }}</span> --}}
                            {{--                                        @endif --}}
                            {{--                                    </div> --}}
                            {{--                                </div> --}}
                            {{--                            </div> --}}


                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{-- This div to handle custom error --}}

                                        <div>
                                            <label>Vessel Name<span class="required"></span></label>
                                            <select name="vessel_id" class="form-control select2 vesselName customError"
                                                    oninput="this.value = this.value.toUpperCase()">
                                                <option value="">Select a vessel</option>
                                                @forelse($vesselName as $name)
                                                    <option
{{--                                                        {{ old('vessel_id', $data->vessel_id) == $name->id ? 'selected' : '' }}--}}
{{--                                                        value="{{ $name->id }}">{{ $name->name }}--}}
{{--                                                        ({{ date('d-m-Y', strtotime($name->arrival_date)) }})--}}
                                                        {{ ( old("vessel_id",$data->vessel_id ) == $name->id) ? "selected" : ""  }} value="{{ $name->id }}">
                                                        {{ (isset($name->arrival_date)) ? date('d-m-Y', strtotime($name->arrival_date)) : 'No arrival date' }} --
                                                        {{ $name->name }} {{ ($name->registration_info && $name->registration_info->new_reg_no) ? '('.$name->registration_info->new_reg_no.')' : '' }}
                                                    </option>
                                                @empty
                                                    <option value="">No type found</option>
                                                @endforelse
                                            </select>
                                            @if ($errors->has('vessel_id'))
                                                <span class="help-block">{{ $errors->first('vessel_id') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

{{--                                <div class="col-md-4">--}}
{{--                                    <div class="form-group">--}}
{{--                                        --}}{{--                                                                         This div to handle custom error--}}
{{--                                        <div>--}}
{{--                                            <label>Working type<span class="required"></span></label>--}}
{{--                                            <select name="working_type_id" placeholder="Working type id"--}}
{{--                                                    class="form-control workingType customError"--}}
{{--                                                    oninput="this.value = this.value.toUpperCase()">--}}
{{--                                                <option value="">Select a type</option>--}}
{{--                                                @forelse($workingType as $type)--}}
{{--                                                    <option--}}
{{--                                                        {{ old('working_type_id', $data->working_type_id) == $type->id ? 'selected' : '' }}--}}
{{--                                                        value="{{ $type->id }}">{{ $type->name }}</option>--}}
{{--                                                @empty--}}
{{--                                                    <option value="">No type found</option>--}}
{{--                                                @endforelse--}}
{{--                                            </select>--}}
{{--                                            @if ($errors->has('working_type_id'))--}}
{{--                                                <span class="help-block">{{ $errors->first('working_type_id') }}</span>--}}
{{--                                            @endif--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

                                <div class="col-md-4 motherVesselField">
                                    <div class="form-group">
                                        <label>Mother Vessel<span class="required"></span></label>
                                        <select name="mother_vessel_id" placeholder="Mother Vessel"
                                                class="form-control motherVessel"
                                                oninput="this.value = this.value.toUpperCase()">
                                            @if (isset($data->mother_vessel->id))
                                                <option value="{{ $data->mother_vessel->id }}" selected>
                                                    {{ $data->mother_vessel->name }}</option>
                                            @endif
                                        </select>
                                        @if ($errors->has('mother_vessel_id'))
                                            <span class="help-block">{{ $errors->first('mother_vessel_id') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>File No<span class=""></span></label>
                                        <input type="text" name="file_no" value="{{ old('file_no', $data->file_no) }}"
                                               placeholder="File No" class="form-control"
                                               oninput="this.value = this.value.toUpperCase()">
                                        @if ($errors->has('file_no'))
                                            <span class="help-block">{{ $errors->first('file_no') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{-- This div to handle custom error --}}
                                        <div>
                                            <label>Schedule type<span class="required"></span></label>
                                            <select name="schedule_type_id" id="schedule_type_id"
                                                    style="pointer-events: none"
                                                    class="form-control customError"
                                                    oninput="this.value = this.value.toUpperCase()">
                                                <option value="">Select a type</option>
                                                @forelse($scheduleType as $type)
                                                    <option
                                                        {{ old('schedule_type_id', $data->schedule_type_id) == $type->id ? 'selected' : '' }}
                                                        value="{{ $type->id }}">{{ $type->name }}</option>
                                                @empty
                                                    <option value="">No type found</option>
                                                @endforelse
                                            </select>
                                            @if ($errors->has('schedule_type_id'))
                                                <span class="help-block">{{ $errors->first('schedule_type_id') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{-- This div to handle custom error --}}
                                        <div>
                                            <label>Pilot Name<span class="required"></span></label>
                                            <select name="pilot_id" class="form-control select2 customError"
                                                    oninput="this.value = this.value.toUpperCase()">
                                                <option value="">Select a pilot</option>
                                                @forelse($pilotList as $list)
                                                    <option
                                                        {{ old('pilot_id', $data->pilot_id) == $list->id ? 'selected' : '' }}
                                                        value="{{ $list->id }}">{{ $list->name }}</option>
                                                @empty
                                                    <option value="">No type found</option>
                                                @endforelse
                                            </select>

                                            @if ($errors->has('pilot_id'))
                                                <span class="help-block">{{ $errors->first('pilot_id') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Local agent<span class="required"></span></label>
                                        <input readonly type="text" name="local_agent"
                                               value="{{ old('local_agent', $data->local_agent) }}"
                                               placeholder="Local agent" class="form-control shippingAgent"
                                               oninput="this.value = this.value.toUpperCase()">
                                        @if ($errors->has('local_agent'))
                                            <span class="help-block">{{ $errors->first('local_agent') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Last port<span class="required"></span></label>
                                        <input readonly type="text" name="last_port"
                                               value="{{ old('last_port', $data->last_port) }}" placeholder="Last port"
                                               class="form-control lastPort"
                                               oninput="this.value = this.value.toUpperCase()">
                                        @if ($errors->has('last_port'))
                                            <span class="help-block">{{ $errors->first('last_port') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Next port</label>
                                        <input readonly type="text" name="next_port"
                                               value="{{ old('next_port', $data->next_port) }}" placeholder="Next port"
                                               class="form-control nextPort"
                                               oninput="this.value = this.value.toUpperCase()">
                                        @if ($errors->has('next_port'))
                                            <span class="help-block">{{ $errors->first('next_port') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Master name<span class="required"></span></label>
                                        <input type="text" name="master_name"
                                               value="{{ old('master_name', $data->master_name) }}"
                                               placeholder="Master name" class="form-control master_name"
                                               oninput="this.value = this.value.toUpperCase()">
                                        @if ($errors->has('master_name'))
                                            <span class="help-block">{{ $errors->first('master_name') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Registration No<span class="required"></span></label>
                                        <input type="text" name="vessel_reg_no"
                                               value="{{ old('vessel_reg_no', $data->new_reg_no) }}"
                                               placeholder="REGISTRATION NO" class="form-control reg_no"
                                               readonly
                                               oninput="this.value = this.value.toUpperCase()">
                                        @if ($errors->has('vessel_reg_no'))
                                            <span class="help-block">{{ $errors->first('vessel_reg_no') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="">Arrival date<span class="required"></span></label>
                                        <input readonly type="text" name="arrival_date"
                                               value="{{ old('arrival_date', $data->arrival_date) }}"
                                               placeholder="Arrival date" class="form-control arrival_date"
                                               oninput="this.value = this.value.toUpperCase()">
                                        @if ($errors->has('arrival_date'))
                                            <span class="help-block">{{ $errors->first('arrival_date') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>GRT<span class="required"></span></label>
                                        <input readonly type="text" name="grt"
                                               value="{{ old('grt', $data->grt) }}" placeholder="GRT"
                                               class="form-control grt" oninput="this.value = this.value.toUpperCase()">
                                        @if ($errors->has('grt'))
                                            <span class="help-block">{{ $errors->first('grt') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>NRT<span class=""></span></label>
                                        <input readonly type="text" name="nrt"
                                               value="{{ old('nrt', $data->nrt) }}" placeholder="NRT"
                                               class="form-control nrt" oninput="this.value = this.value.toUpperCase()">
                                        @if ($errors->has('nrt'))
                                            <span class="help-block">{{ $errors->first('nrt') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{--                                        <label>Draught --}}{{-- <span class="required"></span> --}}{{-- </label> --}}
                                        <label>Draft{{-- <span class="required"></span> --}}</label>
                                        <input type="text"
                                               oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                               name="draught" value="{{ old('draught', $data->draught) }}"
                                               placeholder="Draught" class="form-control draught">
                                        @if ($errors->has('draught'))
                                            <span class="help-block">{{ $errors->first('draught') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{--                                        <label>Length --}}{{-- <span class="required"></span> --}}{{-- </label> --}}
                                        <label>Loa{{-- <span class="required"></span> --}}</label>
                                        <input type="text"
                                               oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                               name="length" value="{{ old('length', $data->length_value) }}"
                                               placeholder="Length" class="form-control length">
                                        @if ($errors->has('length'))
                                            <span class="help-block">{{ $errors->first('length') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Gupta khal stay<span class="required"></span></label>
                                            <ul class="list-unstyled mb-0">
                                                <li class="d-inline-block mr-2 mb-1">
                                                    <fieldset>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                   value="Y"
                                                                   {{ old('stay_guptakhal_yn', $data->stay_guptakhal_yn) == 'Y' ? 'checked' : '' }}
                                                                   name="stay_guptakhal_yn" id="guptaRadio1">
                                                            <label class="custom-control-label"
                                                                   for="guptaRadio1">Yes</label>
                                                        </div>
                                                    </fieldset>
                                                </li>
                                                <li class="d-inline-block mr-2 mb-1">
                                                    <fieldset>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                   value="N"
                                                                   {{ old('stay_guptakhal_yn', $data->stay_guptakhal_yn) == 'N' ? 'checked' : '' }}
                                                                   name="stay_guptakhal_yn" id="guptaRadio2">
                                                            <label class="custom-control-label"
                                                                   for="guptaRadio2">No</label>
                                                        </div>
                                                    </fieldset>
                                                </li>
                                            </ul>
                                            @if ($errors->has('stay_guptakhal_yn'))
                                                <span
                                                    class="help-block">{{ $errors->first('stay_guptakhal_yn') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div>
                                            <label>Additional Pilot 1</label>
                                            <select name="additional_pilot_one" class="form-control select2 customError"
                                                    oninput="this.value = this.value.toUpperCase()">
                                                <option value="">Select a pilot</option>
                                                @forelse($pilotList as $list)
                                                    <option
                                                        {{ old('additional_pilot_one', $data->additional_pilot_one) == $list->id ? 'selected' : '' }}
                                                        value="{{ $list->id }}">{{ $list->name }}</option>
                                                @empty
                                                    <option value="">No type found</option>
                                                @endforelse
                                            </select>

                                            @if ($errors->has('additional_pilot_one'))
                                                <span
                                                    class="help-block">{{ $errors->first('additional_pilot_one') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div>
                                            <label>Additional Pilot 2</label>
                                            <select name="additional_pilot_two" class="form-control select2 customError"
                                                    oninput="this.value = this.value.toUpperCase()">
                                                <option value="">Select a pilot</option>
                                                @forelse($pilotList as $list)
                                                    <option
                                                        {{ old('additional_pilot_two', $data->additional_pilot_two) == $list->id ? 'selected' : '' }}
                                                        value="{{ $list->id }}">{{ $list->name }}</option>
                                                @empty
                                                    <option value="">No type found</option>
                                                @endforelse
                                            </select>

                                            @if ($errors->has('additional_pilot_two'))
                                                <span
                                                    class="help-block">{{ $errors->first('additional_pilot_two') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Pilot borded at<span class="required"></span></label>
                                        <div class="input-group date" id="bordedAt" data-target-input="nearest">
                                            <div class="input-group-append" data-target="#bordedAt"
                                                 data-toggle="datetimepicker">
                                                <div class="input-group-text">
                                                    <i class="bx bx-calendar"></i>
                                                </div>
                                            </div>

                                            <input type="text" name="pilot_borded_at" id="pilot_borded_at"
                                                   value="{{ old('pilot_borded_at', isset($data->pilot_borded_at) ? date('Y-m-d H:i', strtotime($data->pilot_borded_at)) : '' ) }}"
                                                   {{-- : date('Y-m-d H:i')) }}"--}}
                                                   class="form-control datetimepicker-input customError"
                                                   data-target="#bordedAt" data-toggle="datetimepicker"
                                                   placeholder="Pilot borded at">
                                        </div>
                                        {{--d-m-Y H:i--}}
                                        @if ($errors->has('pilot_borded_at'))
                                            <span class="help-block">{{ $errors->first('pilot_borded_at') }}</span>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Pilot left at<span class="required"></span></label>
                                        <div class="input-group date" id="leftAt" data-target-input="nearest">
                                            <div class="input-group-append" data-target="#leftAt"
                                                 data-toggle="datetimepicker">
                                                <div class="input-group-text">
                                                    <i class="bx bx-calendar"></i>
                                                </div>
                                            </div>
                                            <input type="text" name="pilot_left_at" id="pilot_left_at"
                                                   value="{{ old('pilot_left_at', isset($data->pilot_left_at) ? date('Y-m-d H:i', strtotime($data->pilot_left_at)) : '') }}"
                                                   {{-- : date('Y-m-d H:i')) }}"--}}

                                                   class="form-control datetimepicker-input customError"
                                                   data-target="#leftAt" data-toggle="datetimepicker"
                                                   placeholder="Pilot left at">
                                        </div>
                                        @if ($errors->has('pilot_left_at'))
                                            <span class="help-block">{{ $errors->first('pilot_left_at') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <div>
                                                                                <label>Working location<span class="required"></span></label>
                                                                                <select name="work_location_id"
                                                                                        class="form-control select2 customError work_location_id"
                                                                                        oninput="this.value = this.value.toUpperCase()">
                                                                                    <option value="">Select a location</option>
                                                                                    @forelse($workLocation as $location)
                                                                                        <option
                                                                                            {{ (old("work_location_id", $data->work_location_id )== $location->id) ? "selected" : ""  }} value="{{ $location->id }}">{{ $location->name }}</option>
                                                                                    @empty
                                                                                        <option value="">No location found</option>
                                                                                    @endforelse
                                                                                </select>
                                                                                @if ($errors->has('work_location_id'))
                                                                                    <span class="help-block">{{ $errors->first('work_location_id') }}</span>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label id="mooringLabelFrom">Mooring from <span class="required"></span></label>
                                        <div class="input-group date" onfocusout="$(this).datetimepicker('hide')"
                                             id="mooringFrom" data-target-input="nearest">
                                            <div class="input-group-append" data-target="#mooringFrom"
                                                 data-toggle="datetimepicker">
                                                <div class="input-group-text">
                                                    <i class="bx bx-calendar"></i>
                                                </div>
                                            </div>

                                            <input type="text" name="mooring_from_time" id="mooringFromPLC"
                                                   value="{{ old('mooring_from_time', $data->mooring_from_time) }}"
                                                   class="form-control datetimepicker-input customError"
                                                   data-target="#mooringFrom" data-toggle="datetimepicker"
                                                   placeholder="Mooring from time"/>

                                        </div>

                                        @if ($errors->has('mooring_from_time'))
                                            <span class="help-block">{{ $errors->first('mooring_from_time') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label id="mooringLabelTo">Mooring to <span class="required"></span></label>
                                        <div class="input-group date" onfocusout="$(this).datetimepicker('hide')"
                                             id="mooringTo" data-target-input="nearest">
                                            <div class="input-group-append" data-target="#mooringTo"
                                                 data-toggle="datetimepicker">
                                                <div class="input-group-text">
                                                    <i class="bx bx-calendar"></i>
                                                </div>
                                            </div>
                                            <input type="text" id="mooringToPLC" name="mooring_to_time"
                                                   value="{{ old('mooring_to_time', $data->mooring_to_time) }}"
                                                   class="form-control datetimepicker-input customError"
                                                   data-target="#mooringTo" data-toggle="datetimepicker"
                                                   placeholder="Mooring to time">
                                        </div>
                                        @if ($errors->has('mooring_to_time'))
                                            <span class="help-block">{{ $errors->first('mooring_to_time') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Master sign date<span class="required"></span></label>
                                        <div class="input-group date" onfocusout="$(this).datetimepicker('hide')"
                                             id="signDate" data-target-input="nearest">
                                            <div class="input-group-append" data-target="#signDate"
                                                 data-toggle="datetimepicker">
                                                <div class="input-group-text">
                                                    <i class="bx bx-calendar"></i>
                                                </div>
                                            </div>
                                            <input type="text" name="master_sign_date" id="master_sign_date"
                                                   value="{{ old('master_sign_date', isset($data->master_sign_date) ? date('Y-m-d H:i', strtotime($data->master_sign_date)) : '' ) }}"
                                                   {{-- : date('Y-m-d H:i')) }}"--}}
                                                   class="form-control datetimepicker-input customError"
                                                   data-target="#signDate" data-toggle="datetimepicker"
                                                   placeholder="Master sign date">
                                        </div>
                                        @if ($errors->has('master_sign_date'))
                                            <span class="help-block">{{ $errors->first('master_sign_date') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4 display-hidden">
                                    <div class="form-group ">
                                        <label>Mooring line ford<span class="required"></span></label>
                                        <input type="number" min="0" name="mooring_line_ford"
                                               value="{{ old('mooring_line_ford', $data->mooring_line_ford) }}"
                                               placeholder="Mooring line ford" class="form-control">
                                        @if ($errors->has('mooring_line_ford'))
                                            <span class="help-block">{{ $errors->first('mooring_line_ford') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 display-hidden">
                                    <div class="form-group">
                                        <label>Mooring line aft<span class="required"></span></label>
                                        <input type="number" min="0" name="mooring_line_aft"
                                               value="{{ old('mooring_line_aft', $data->mooring_line_aft) }}"
                                               placeholder="Mooring line aft" class="form-control"
                                               oninput="this.value = this.value.toUpperCase()">
                                        @if ($errors->has('mooring_line_aft'))
                                            <span class="help-block">{{ $errors->first('mooring_line_aft') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4 display-hidden">
                                    <div class="form-group">
                                        <label>Stern power avail<span class="required"></span></label>
                                        <input type="text" name="stern_power_avail"
                                               value="{{ old('stern_power_avail', $data->stern_power_avail) }}"
                                               placeholder="Stern power avail" class="form-control"
                                               oninput="this.value = this.value.toUpperCase()">
                                        @if ($errors->has('stern_power_avail'))
                                            <span class="help-block">{{ $errors->first('stern_power_avail') }}</span>
                                        @endif
                                    </div>
                                </div>
                                {{--                                <div class="col-md-4"> --}}
                                {{--                                    <div class="form-group"> --}}
                                {{--                                        <label>Master sign date<span class="required"></span></label> --}}
                                {{--                                        <div class="input-group date" onfocusout="$(this).datetimepicker('hide')" id="signDate" data-target-input="nearest"> --}}
                                {{--                                            <div class="input-group-append" data-target="#signDate" data-toggle="datetimepicker"> --}}
                                {{--                                                <div class="input-group-text"> --}}
                                {{--                                                    <i class="bx bx-calendar"></i> --}}
                                {{--                                                </div> --}}
                                {{--                                            </div> --}}
                                {{--                                            <input type="text" --}}
                                {{--                                                   name="master_sign_date" --}}
                                {{--                                                   value="{{ old('master_sign_date', isset($data->master_sign_date) ?  date('Y-m-d H:i', strtotime($data->master_sign_date)) : date('Y-m-d H:i')) }}" --}}
                                {{--                                                   class="form-control datetimepicker-input customError" --}}
                                {{--                                                   data-target="#signDate" --}}
                                {{--                                                   data-toggle="datetimepicker" --}}
                                {{--                                                   placeholder="Master sign date"> --}}
                                {{--                                        </div> --}}
                                {{--                                        @if ($errors->has('master_sign_date')) --}}
                                {{--                                            <span class="help-block">{{ $errors->first('master_sign_date') }}</span> --}}
                                {{--                                        @endif --}}
                                {{--                                    </div> --}}
                                {{--                                </div> --}}
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group ">
                                        <label>Call Sign</label>
                                        <input type="text" name="call_sign"
                                               value="{{ old('call_sign', $data->call_sign) }}" placeholder="Call sign"
                                               class="form-control call_sign">
                                        @if ($errors->has('call_sign'))
                                            <span class="help-block">{{ $errors->first('call_sign') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group ">
                                        <label>Crew & Officer Inclusive No</label>
                                        <input type="number" min="0" name="crw_officer_incl_mst_num"
                                               value="{{ old('crw_officer_incl_mst_num', $data->crw_officer_incl_mst_num) }}"
                                               placeholder="Crew & Officer Inclusive No" class="form-control crw_officer_incl_mst_num">
                                        @if ($errors->has('crw_officer_incl_mst_num'))
                                            <span
                                                class="help-block">{{ $errors->first('crw_officer_incl_mst_num') }}</span>
                                        @endif
                                    </div>
                                </div>
                                {{--                                <div class="col-md-4">--}}
                                {{--                                    <div class="form-group ">--}}
                                {{--                                        <label>Good Mooring Line No</label>--}}
                                {{--                                        <input type="number" min="0" name="good_mooring_line_number"--}}
                                {{--                                               value="{{ old('good_mooring_line_number', $data->good_mooring_line_number) }}"--}}
                                {{--                                               placeholder="Good Mooring Line No" class="form-control">--}}
                                {{--                                        @if ($errors->has('good_mooring_line_number'))--}}
                                {{--                                            <span--}}
                                {{--                                                class="help-block">{{ $errors->first('good_mooring_line_number') }}</span>--}}
                                {{--                                        @endif--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                            </div>
                            {{--                            dom>--}}

                            {{--                            <div class="row">--}}
                            {{--                                <div class="col-md-12">--}}
                            {{--                                    <div class="form-group ">--}}

                            {{--                                        <label> NOS OF GOOD MOORING LINES : FORD </label>--}}
                            {{--                                        &nbsp;&nbsp; <input type="text" name="ford_good_mooring_number"--}}
                            {{--                                                            value="{{ old('ford_good_mooring_number', $data->ford_good_mooring_number) }}"--}}
                            {{--                                                            placeholder="Ford" class="form-control"--}}
                            {{--                                                            style="width:36% ; display: inline">--}}
                            {{--                                        @if ($errors->has('ford_good_mooring_number'))--}}
                            {{--                                            <span--}}
                            {{--                                                class="help-block">{{ $errors->first('ford_good_mooring_number') }}</span>--}}
                            {{--                                        @endif--}}

                            {{--                                        &nbsp;&nbsp;&nbsp;&nbsp; <label>AFT</label>--}}
                            {{--                                        &nbsp;&nbsp; <input type="text" name="aft" value="{{ old('aft', $data->aft) }}"--}}
                            {{--                                                            placeholder="AFT" class="form-control"--}}
                            {{--                                                            style="width:41% ; display: inline">--}}
                            {{--                                        @if ($errors->has('aft'))--}}
                            {{--                                            <span class="help-block">{{ $errors->first('aft') }}</span>--}}
                            {{--                                        @endif--}}
                            {{--                                    </div>--}}


                            {{--                                </div>--}}


                            {{--                                <div class="col-md-6">--}}
                            {{--                                    <div class="form-group ">--}}
                            {{--                                        <label>AFT</label>--}}
                            {{--                                        <input type="text" name="aft" value="{{ old('aft', $data->aft) }}"--}}
                            {{--                                               placeholder="AFT" class="form-control">--}}
                            {{--                                        @if ($errors->has('aft'))--}}
                            {{--                                            <span class="help-block">{{ $errors->first('aft') }}</span>--}}
                            {{--                                        @endif--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}









                            {{--                                <div class="col-md-4">--}}
                            {{--                                    <div class="form-group ">--}}
                            {{--                                        <label>STERN Available</label>--}}
                            {{--                                        <input type="text" name="stern_avl_power"--}}
                            {{--                                               value="{{ old('stern_avl_power', $data->stern_avl_power) }}"--}}
                            {{--                                               placeholder="STERN Available" class="form-control">--}}
                            {{--                                        @if ($errors->has('stern_avl_power'))--}}
                            {{--                                            <span class="help-block">{{ $errors->first('stern_avl_power') }}</span>--}}
                            {{--                                        @endif--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                            {{--                            <div class="row">--}}
                            {{--                                <div class="col-md-4">--}}
                            {{--                                    <div class="form-group ">--}}
                            {{--                                        <label>Immediately</label>--}}
                            {{--                                        <input type="text" name="immediately"--}}
                            {{--                                               value="{{ old('immediately', $data->immediately) }}"--}}
                            {{--                                               placeholder="Immediately" class="form-control">--}}
                            {{--                                        @if ($errors->has('immediately'))--}}
                            {{--                                            <span class="help-block">{{ $errors->first('immediately') }}</span>--}}
                            {{--                                        @endif--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}
                            {{--                                <div class="col-md-4">--}}
                            {{--                                    <div class="form-group">--}}
                            {{--                                        <label>UnMooring from </label>--}}
                            {{--                                        <div class="input-group date" onfocusout="$(this).datetimepicker('hide')"--}}
                            {{--                                             id="unmooring_from_time" data-target-input="nearest">--}}
                            {{--                                            <div class="input-group-append" data-target="#unmooring_from_time"--}}
                            {{--                                                 data-toggle="datetimepicker">--}}
                            {{--                                                <div class="input-group-text">--}}
                            {{--                                                    <i class="bx bx-calendar"></i>--}}
                            {{--                                                </div>--}}
                            {{--                                            </div>--}}
                            {{--                                            <input type="text" name="unmooring_from_time"--}}
                            {{--                                                   value="{{ old('unmooring_from_time', \App\Helpers\HelperClass::dateTimeFormat($data->unmooring_from_time)) }}"--}}
                            {{--                                                   class="form-control datetimepicker-input customError"--}}
                            {{--                                                   data-target="#unmooring_from_time" data-toggle="datetimepicker"--}}
                            {{--                                                   placeholder="UnMooring from time"/>--}}

                            {{--                                        </div>--}}
                            {{--                                        @if ($errors->has('unmooring_from_time'))--}}
                            {{--                                            <span class="help-block">{{ $errors->first('unmooring_from_time') }}</span>--}}
                            {{--                                        @endif--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}
                            {{--                                <div class="col-md-4">--}}
                            {{--                                    <div class="form-group">--}}
                            {{--                                        <label>UnMooring To</label>--}}
                            {{--                                        <div class="input-group date" onfocusout="$(this).datetimepicker('hide')"--}}
                            {{--                                             id="unmooring_to_time" data-target-input="nearest">--}}
                            {{--                                            <div class="input-group-append" data-target="#unmooring_to_time"--}}
                            {{--                                                 data-toggle="datetimepicker">--}}
                            {{--                                                <div class="input-group-text">--}}
                            {{--                                                    <i class="bx bx-calendar"></i>--}}
                            {{--                                                </div>--}}
                            {{--                                            </div>--}}
                            {{--                                            <input type="text" name="unmooring_to_time"--}}
                            {{--                                                   value="{{ old('unmooring_to_time', \App\Helpers\HelperClass::dateTimeFormat($data->unmooring_to_time)) }}"--}}
                            {{--                                                   class="form-control datetimepicker-input customError"--}}
                            {{--                                                   data-target="#unmooring_to_time" data-toggle="datetimepicker"--}}
                            {{--                                                   placeholder="UnMooring to time"/>--}}

                            {{--                                        </div>--}}
                            {{--                                        @if ($errors->has('unmooring_to_time'))--}}
                            {{--                                            <span class="help-block">{{ $errors->first('unmooring_to_time') }}</span>--}}
                            {{--                                        @endif--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group ">
                                        <label>Owner Address</label>
                                        <input type="text" name="owner_address"
                                               value="{{ old('owner_address', $data->owner_address) }}"
                                               placeholder="Owner Address" class="form-control owner_address">
                                        @if ($errors->has('owner_address'))
                                            <span class="help-block">{{ $errors->first('owner_address') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group ">
                                        <label class="required">Swing mooring</label>
                                        <select name="swing_mooring" id="swing_mooring"
                                                class="form-control select2 swing_mooring" required>
                                            <option value="">Select Swing Mooring</option>
                                            <option value="Y">Yes</option>
                                            <option selected value="N">No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group ">
                                        <label class="required">Fixed mooring</label>
                                        <select name="fixed_mooring" id="fixed_mooring"
                                                class="form-control select2 fixed_mooring" required>
                                            <option value="">Select Fixed Mooring</option>
                                            <option value="Y">Yes</option>
                                            <option selected value="N">No</option>
                                        </select>
                                    </div>
                                </div>

                            </div>


                            <div class="row">
                                <div class="col-md-12">
                                    <fieldset class="border p-1 mt-1 mb-1 col-sm-12">
                                        <legend class="w-auto" style="font-size: 18px;">Tug Information</legend>
                                        <table width="100%" id="tugTagTable" class=" tugTaggings">
                                            <thead>
                                            <tr>
                                                <th width="25%" class="pl-1">Tug<span class="required"></span>
                                                </th>
                                                <th width="25%" class="pl-1">Assistance from time<span
                                                        class="required"></span></th>
                                                <th width="23%" class="pl-1">Assistance to time<span
                                                        class="required"></span></th>
                                                <th width="18%" class="pl-1">Is primary<span
                                                        class="required"></span>
                                                </th>
                                                {{--                                                <th width="21%" class="pl-1">Working location<span--}}
                                                {{--                                                        class="required"></span></th>--}}
                                                <th width="9%">Action<span class="required"></span></th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            @forelse($data->pilotage_tug as $i=>$tug)

                                                <tr>
                                                    <td class="p-1">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <input type="hidden"
                                                                       name="tug[{{ $i }}][pTtId]"
                                                                       value="{{ isset($tug->id) ? $tug->id : '' }}">
                                                                <select name="tug[{{ $i }}][tugId]"
                                                                        class="form-control select2 customError"
                                                                        id="tugName">
                                                                    <option value="">Select a tug</option>
                                                                    @forelse($tugLists as $name)
                                                                        <option value="{{ $name->id }}"
                                                                            {{ old("tug.$i.tugId", isset($tug->tug_id) ? $tug->tug_id : '') == $name->id ? 'selected' : '' }}>
                                                                            {{ $name->name }}</option>
                                                                    @empty
                                                                        <option value="">No tug found</option>
                                                                    @endforelse
                                                                </select>
                                                                @if ($errors->has('tug.{{ $i }}.tugId'))
                                                                    <span
                                                                        class="help-block">{{ $errors->first("tug.$i.tugId") ? 'This tug field is required.' : '' }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>


                                                    <td class="p-1">
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="input-group date"
                                                                     onclick="callDateTimePicker(this)"
                                                                     id="assistanceFrom{{ $i }}"
                                                                     data-target-input="nearest">
                                                                    <div class="input-group-append"
                                                                         data-target="#assistanceFrom{{ $i }}"
                                                                         data-toggle="datetimepicker">
                                                                        <div class="input-group-text">
                                                                            <i class="bx bx-calendar"></i>
                                                                        </div>
                                                                    </div>
                                                                    <input type="text"
                                                                           name="tug[{{ $i }}][assistanceFrom]"
                                                                           value="{{ old("tug.$i.assistanceFrom", isset($tug->assitance_from_time)) ? date('Y-m-d H:i', strtotime($tug->assitance_from_time)) : '' }}"
                                                                           class="form-control datetimepicker-input"
                                                                           data-target="#assistanceFrom{{ $i }}"
                                                                           data-toggle="datetimepicker"
                                                                           id="tug_assitance_from_time"
                                                                           placeholder="Assistance from">
                                                                    @if ($errors->has('tug.{{ $i }}.assistanceFrom'))
                                                                        <span
                                                                            class="help-block">{{ $errors->first("tug.$i.assistanceFrom") ? 'This assistance form field is required.' : '' }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>


                                                    <td class="p-1">
                                                        <div class="input-group date"
                                                             onclick="callDateTimePicker(this)"
                                                             id="assistanceTo{{ $i }}"
                                                             data-target-input="nearest">
                                                            <div class="input-group-append"
                                                                 data-target="#assistanceTo{{ $i }}"
                                                                 data-toggle="datetimepicker">
                                                                <div class="input-group-text">
                                                                    <i class="bx bx-calendar"></i>
                                                                </div>
                                                            </div>
                                                            <input type="text"
                                                                   name="tug[{{ $i }}][assistanceTo]"
                                                                   value="{{ old("tug.$i.assistanceTo", isset($tug->assitance_to_time) ? date('Y-m-d H:i', strtotime($tug->assitance_to_time)) : '') }}"
                                                                   class="form-control datetimepicker-input"
                                                                   data-target="#assistanceTo{{ $i }}"
                                                                   data-toggle="datetimepicker"
                                                                   id="tug_assitance_to_time"
                                                                   placeholder="Assistance to">
                                                            @if ($errors->has('tug.{{ $i }}.assistanceTo'))
                                                                <span
                                                                    class="help-block">{{ $errors->first("tug.$i.assistanceTo") ? 'This assistance to field is required.' : '' }}</span>
                                                            @endif
                                                        </div>
                                                    </td>


                                                    <td class="p-1">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <select name="tug[{{ $i }}][isPrimary]"
                                                                        class="form-control select2 customError"
                                                                        oninput="this.value = this.value.toUpperCase()">
                                                                    <option value="">Select one</option>
                                                                    <option value="Y"
                                                                            {{ old("tug.$i.isPrimary", isset($tug->primary_yn) ? $tug->primary_yn : '') == 'Y' ? 'selected' : '' }} selected>
                                                                        Yes
                                                                    </option>
                                                                    <option value="N"
                                                                        {{ old("tug.$i.isPrimary", isset($tug->primary_yn) ? $tug->primary_yn : '') == 'N' ? 'selected' : '' }}>
                                                                        No
                                                                    </option>
                                                                </select>
                                                                @if ($errors->has('tug.{{ $i }}.isPrimary'))
                                                                    <span
                                                                        class="help-block">{{ $errors->first("tug.$i.isPrimary") ? 'This primary field is required.' : '' }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>

                                                    {{--                                                    -----------------------------working time 1 starts---------------------------------------------}}
                                                    {{--                                                    <td class="p-1">--}}
                                                    {{--                                                        <div class="row">--}}
                                                    {{--                                                            <div class="col-md-12">--}}
                                                    {{--                                                                <select name="tug[{{ $i }}][workLocation]"--}}
                                                    {{--                                                                        class="form-control select2 customError"--}}
                                                    {{--                                                                        oninput="this.value = this.value.toUpperCase()">--}}
                                                    {{--                                                                    <option value="">Select one..</option>--}}
                                                    {{--                                                                    @forelse($workLocation as $location)--}}
                                                    {{--                                                                        <option--}}
                                                    {{--                                                                            {{ old("tug.$i.workLocation", isset($tug->work_location_id) ? $tug->work_location_id : '') == $location->id ? 'selected' : '' }}--}}
                                                    {{--                                                                            value="{{ $location->id }}">--}}
                                                    {{--                                                                            {{ $location->name }}</option>--}}
                                                    {{--                                                                    @empty--}}
                                                    {{--                                                                        <option value="">No location found--}}
                                                    {{--                                                                        </option>--}}
                                                    {{--                                                                    @endforelse--}}
                                                    {{--                                                                </select>--}}
                                                    {{--                                                                @if ($errors->has('tug.{{ $i }}.workLocation'))--}}
                                                    {{--                                                                    <span--}}
                                                    {{--                                                                        class="help-block">{{ $errors->first("tug.$i.workLocation") ? 'This work location field is required.' : '' }}</span>--}}
                                                    {{--                                                                @endif--}}
                                                    {{--                                                            </div>--}}
                                                    {{--                                                        </div>--}}
                                                    {{--                                                    </td>--}}

                                                    {{--                                                    -----------------------------working time 1 ends-------------------------------------------}}
                                                    @if ($i == 0)
                                                        <td><span id="addrow" class="cursor-pointer"><i
                                                                    class="bx bx-plus-circle"></i></span>
                                                    @else
                                                        <td><span class=" rowDel text-danger cursor-pointer"><i
                                                                    class="bx bx-trash"></i></span></td>
                                                    @endif
                                                </tr>



                                            @empty
                                                @if (old('tug'))
                                                    @foreach (old('tug') as $key => $tug)
                                                        <tr>
                                                            <td class="p-1">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <input type="hidden"
                                                                               name="tug[{{ $key }}][pTtId]"
                                                                               value="{{ $tug['pTtId'] }}">
                                                                        <select
                                                                            name="tug[{{ $key }}][tugId]" id="tugName"
                                                                            class="form-control select2 customError">
                                                                            <option value="">Select a tug
                                                                            </option>
                                                                            @forelse($tugLists as $name)
                                                                                <option value="{{ $name->id }}"
                                                                                    {{ $tug['tugId'] == $name->id ? 'selected' : '' }}>
                                                                                    {{ $name->name }}</option>
                                                                            @empty
                                                                                <option value="">No tug found
                                                                                </option>
                                                                            @endforelse
                                                                        </select>
                                                                        @if ($errors->has("tug.$key.tugId"))
                                                                            <span
                                                                                class="help-block">{{ $errors->first("tug.$key.tugId") ? 'Select a tug' : '' }}</span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </td>


                                                            <td class="p-1">
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <div class="input-group date"
                                                                             onclick="callDateTimePicker(this)"
                                                                             id="assistanceFrom{{ $key }}"
                                                                             data-target-input="nearest">
                                                                            <div class="input-group-append"
                                                                                 data-target="#assistanceFrom0"
                                                                                 data-toggle="datetimepicker">
                                                                                <div class="input-group-text">
                                                                                    <i class="bx bx-calendar"></i>
                                                                                </div>
                                                                            </div>
                                                                            <input type="text"
                                                                                   name="tug[{{ $key }}][assistanceFrom]"
                                                                                   value="{{ isset($tug['assistanceFrom']) ? date('Y-m-d H:i A', strtotime($tug['assistanceFrom'])) : date('Y-m-d H:i A') }}"
                                                                                   class="form-control datetimepicker-input"
                                                                                   data-target="#assistanceFrom{{ $key }}"
                                                                                   data-toggle="datetimepicker"
                                                                                   id="tug_assitance_from_time"
                                                                                   placeholder="Assistance from">
                                                                            @if ($errors->has("tug.$key.assistanceFrom"))
                                                                                <span
                                                                                    class="help-block">{{ $errors->first("tug.$key.assistanceFrom") ? 'Assistance from must be equal or less then assistance to' : '' }}</span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>


                                                            <td class="p-1">
                                                                <div class="input-group date"
                                                                     onclick="callDateTimePicker(this)"
                                                                     id="assistanceTo{{ $key }}"
                                                                     data-target-input="nearest">
                                                                    <div class="input-group-append"
                                                                         data-target="#assistanceTo0"
                                                                         data-toggle="datetimepicker">
                                                                        <div class="input-group-text">
                                                                            <i class="bx bx-calendar"></i>
                                                                        </div>
                                                                    </div>
                                                                    <input type="text"
                                                                           name="tug[{{ $key }}][assistanceTo]"
                                                                           value="{{ isset($tug['assistanceTo']) ? date('Y-m-d H:i A', strtotime($tug['assistanceTo'])) : date('Y-m-d H:i A') }}"
                                                                           class="form-control datetimepicker-input"
                                                                           data-target="#assistanceTo{{ $key }}"
                                                                           data-toggle="datetimepicker"
                                                                           id="tug_assitance_to_time"
                                                                           placeholder="Assistance to"/>
                                                                    @if ($errors->has("tug.$key.assistanceTo"))
                                                                        <span
                                                                            class="help-block">{{ $errors->first("tug.$key.assistanceTo") ? 'Assistance to must be greater or equal then assistance from' : '' }}</span>
                                                                    @endif
                                                                </div>
                                                            </td>


                                                            <td class="p-1">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <select
                                                                            name="tug[{{ $key }}][isPrimary]"
                                                                            class="form-control select2 customError"
                                                                            oninput="this.value = this.value.toUpperCase()">
                                                                            <option value="">Select one</option>
                                                                            <option value="Y"
                                                                                {{ $tug['isPrimary'] == 'Y' ? 'selected' : '' }}>
                                                                                Yes
                                                                            </option>
                                                                            <option value="N"
                                                                                {{ $tug['isPrimary'] == 'N' ? 'selected' : '' }}>
                                                                                No
                                                                            </option>
                                                                        </select>
                                                                        @if ($errors->has("tug.$key.isPrimary"))
                                                                            <span
                                                                                class="help-block">{{ $errors->first("tug.$key.isPrimary") ? 'Select primary or not' : '' }}</span>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                            </td>

                                                            {{--                                                    -----------------------------working time 2 starts-------------------------------------------}}

                                                            {{--                                                            <td class="p-1">--}}
                                                            {{--                                                                <div class="row">--}}
                                                            {{--                                                                    <div class="col-md-12">--}}
                                                            {{--                                                                        <select--}}
                                                            {{--                                                                            name="tug[{{ $key }}][workLocation]"--}}
                                                            {{--                                                                            class="form-control select2 customError"--}}
                                                            {{--                                                                            oninput="this.value = this.value.toUpperCase()">--}}
                                                            {{--                                                                            <option value="">Select one..--}}
                                                            {{--                                                                            </option>--}}
                                                            {{--                                                                            @forelse($workLocation as $location)--}}
                                                            {{--                                                                                <option--}}
                                                            {{--                                                                                    {{ $tug['workLocation'] == $location->id ? 'selected' : '' }}--}}
                                                            {{--                                                                                    value="{{ $location->id }}">--}}
                                                            {{--                                                                                    {{ $location->name }}</option>--}}
                                                            {{--                                                                            @empty--}}
                                                            {{--                                                                                <option value="">No location--}}
                                                            {{--                                                                                    found--}}
                                                            {{--                                                                                </option>--}}
                                                            {{--                                                                            @endforelse--}}
                                                            {{--                                                                        </select>--}}
                                                            {{--                                                                        @if ($errors->has("tug.$key.workLocation"))--}}
                                                            {{--                                                                            <span--}}
                                                            {{--                                                                                class="help-block">{{ $errors->first("tug.$key.workLocation") ? 'Select a work location' : '' }}</span>--}}
                                                            {{--                                                                        @endif--}}
                                                            {{--                                                                    </div>--}}
                                                            {{--                                                                </div>--}}
                                                            {{--                                                            </td>--}}

                                                            {{--                                                    -----------------------------working time 2 ends-------------------------------------------}}
                                                            @if ($key == 0)
                                                                <td><span id="addrow" class="cursor-pointer"><i
                                                                            class="bx bx-plus-circle"></i></span>
                                                            @else
                                                                <td><span class=" rowDel text-danger cursor-pointer"><i
                                                                            class="bx bx-trash"></i></span></td>
                                                            @endif
                                                        </tr>
                                                    @endforeach


                                                @else
                                                    <tr>
                                                        <td class="p-1">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <input type="hidden" name="tug[0][pTtId]"
                                                                           value="{{ old('tug[0][pTtId]', isset($data->pilotage_tug[0]->id) ? $data->pilotage_tug[0]->id : '') }}">
                                                                    <select name="tug[0][tugId]" id="tugName"
                                                                            class="form-control select2 customError">
                                                                        <option value="">Select a tug</option>
                                                                        @forelse($tugLists as $name)
                                                                            <option value="{{ $name->id }}"
                                                                                {{ old('tug.0.tugId', isset($data->pilotage_tug[0]->tug_id) ? $data->pilotage_tug[0]->tug_id : '') == $name->id ? 'selected' : '' }}>
                                                                                {{ $name->name }}</option>
                                                                        @empty
                                                                            <option value="">No tug found
                                                                            </option>
                                                                        @endforelse
                                                                    </select>
                                                                    @if ($errors->has('tug.0.tugId'))
                                                                        <span
                                                                            class="help-block">{{ $errors->first('tug.0.tugId') ? 'This tug field is required.' : '' }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>


                                                        <td class="p-1">
                                                            <div class="row">
                                                                <div class="col">
                                                                    <div class="input-group date"
                                                                         onclick="callDateTimePicker(this)"
                                                                         id="assistanceFrom0"
                                                                         data-target-input="nearest">
                                                                        <div class="input-group-append"
                                                                             data-target="#assistanceFrom0"
                                                                             data-toggle="datetimepicker">
                                                                            <div class="input-group-text">
                                                                                <i class="bx bx-calendar"></i>
                                                                            </div>
                                                                        </div>
                                                                        <input type="text"
                                                                               name="tug[0][assistanceFrom]"
                                                                               value="{{ old('tug.0.assistanceFrom', isset($data->pilotage_tug[0]->assitance_from_time) ? date('Y-m-d H:i A', strtotime($data->pilotage_tug[0]->assitance_from_time)) : '') }}"
                                                                               class="form-control datetimepicker-input"
                                                                               data-target="#assistanceFrom0"
                                                                               data-toggle="datetimepicker"
                                                                               id="tug_assitance_from_time"
                                                                               placeholder="Assistance from">
                                                                        @if ($errors->has('tug.0.assistanceFrom'))
                                                                            <span
                                                                                class="help-block">{{ $errors->first('tug.0.assistanceFrom') }}</span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>


                                                        <td class="p-1">
                                                            <div class="input-group date"
                                                                 onclick="callDateTimePicker(this)" id="assistanceTo0"
                                                                 data-target-input="nearest">
                                                                <div class="input-group-append"
                                                                     data-target="#assistanceTo0"
                                                                     data-toggle="datetimepicker">
                                                                    <div class="input-group-text">
                                                                        <i class="bx bx-calendar"></i>
                                                                    </div>
                                                                </div>
                                                                <input type="text" name="tug[0][assistanceTo]"
                                                                       value="{{ old('tug.0.assistanceTo', isset($data->pilotage_tug[0]->assitance_to_time) ? date('Y-m-d H:i A', strtotime($data->pilotage_tug[0]->assitance_to_time)) : '') }}"
                                                                       class="form-control datetimepicker-input"
                                                                       data-target="#assistanceTo0"
                                                                       data-toggle="datetimepicker"
                                                                       id="tug_assitance_to_time"
                                                                       placeholder="Assistance to"/>
                                                                @if ($errors->has('tug.0.assistanceTo'))
                                                                    <span
                                                                        class="help-block">{{ $errors->first('tug.0.assistanceTo') }}</span>
                                                                @endif
                                                            </div>
                                                        </td>


                                                        <td class="p-1">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <select name="tug[0][isPrimary]"
                                                                            class="form-control select2 customError"
                                                                            oninput="this.value = this.value.toUpperCase()">
                                                                        <option value="">Select one</option>
                                                                        <option value="Y"
                                                                                {{ old('tug.0.isPrimary', isset($data->pilotage_tug[0]->primary_yn) ? $data->pilotage_tug[0]->primary_yn : '') == 'Y' ? 'selected' : '' }} selected>
                                                                            Yes
                                                                        </option>
                                                                        <option value="N"
                                                                            {{ old('tug.0.isPrimary', isset($data->pilotage_tug[0]->primary_yn) ? $data->pilotage_tug[0]->primary_yn : '') == 'N' ? 'selected' : '' }} >
                                                                            No
                                                                        </option>
                                                                    </select>
                                                                    @if ($errors->has('tug.0.isPrimary'))
                                                                        <span
                                                                            class="help-block">{{ $errors->first('tug.0.isPrimary') ? 'This primary field is required.' : '' }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                        </td>

                                                        {{--                                                    -----------------------------working time 3 starts-------------------------------------------}}

                                                        {{--                                                        <td class="p-1">--}}
                                                        {{--                                                            <div class="row">--}}
                                                        {{--                                                                <div class="col-md-12">--}}
                                                        {{--                                                                    <select name="tug[0][workLocation]"--}}
                                                        {{--                                                                            class="form-control select2 customError"--}}
                                                        {{--                                                                            oninput="this.value = this.value.toUpperCase()">--}}
                                                        {{--                                                                        <option value="">Select one..</option>--}}
                                                        {{--                                                                        @forelse($workLocation as $location)--}}
                                                        {{--                                                                            <option--}}
                                                        {{--                                                                                {{ old('tug.0.workLocation', isset($data->pilotage_tug[0]->work_location_id) ? $data->pilotage_tug[0]->work_location_id : '') == $location->id ? 'selected' : '' }}--}}
                                                        {{--                                                                                value="{{ $location->id }}">--}}
                                                        {{--                                                                                {{ $location->name }}</option>--}}
                                                        {{--                                                                        @empty--}}
                                                        {{--                                                                            <option value="">No location found--}}
                                                        {{--                                                                            </option>--}}
                                                        {{--                                                                        @endforelse--}}
                                                        {{--                                                                    </select>--}}
                                                        {{--                                                                    @if ($errors->has('tug.0.workLocation'))--}}
                                                        {{--                                                                        <span--}}
                                                        {{--                                                                            class="help-block">{{ $errors->first('tug.0.workLocation') ? 'This work location field is required.' : '' }}</span>--}}
                                                        {{--                                                                    @endif--}}
                                                        {{--                                                                </div>--}}
                                                        {{--                                                            </div>--}}
                                                        {{--                                                        </td>--}}

                                                        {{--                                                    -----------------------------working time 3 ends-------------------------------------------}}
                                                        <td><span id="addrow" class="cursor-pointer"><i
                                                                    class="bx bx-plus-circle"></i></span>
                                                    </tr>
                                                @endif
                                            @endforelse

                                            </tbody>
                                        </table>
                                    </fieldset>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <fieldset class="border p-1 mt-1 mb-1 col-sm-12">
                                        <legend class="w-auto" style="font-size: 18px;">Vessel Conditions</legend>
                                        @if (isset($vesselConditions))
                                            <div class="table-responsive">
                                                <table class="table table-hover border-0">
                                                    <thead>
                                                    <tr>
                                                        <th>Condition</th>
                                                        <th>Value</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @php $j=0; @endphp

                                                    {{-- All conditions in vesselConditions --}}

                                                    @foreach ($vesselConditions as $i => $condi)
                                                        @if($condi->id!=9 &&  $condi->id!=10  )
                                                            <tr>
                                                                <td class="p-0 pl-1">

                                                                    {{ ++$i . '. ' . $condi->title }}
                                                                    <input type="hidden"
                                                                           name="vesselCondition[{{ $i }}][conditionId]"
                                                                           value="{{ $condi->id }}">

                                                                </td>

                                                                {{--
                                                            This loop for updating conditions. to show previous entered or selected value if $data is not empty
                                                            --}}

                                                                @forelse($data->pilotage_vessel_condition as $pVcondi)
                                                                    @if ($pVcondi->vessel_condition_id == $condi->id)
                                                                        {{--
                                                                    Checking conditions input type and printing input field text area or radio
                                                                    --}}

                                                                        @if ($pVcondi->vessel_condition->value_type == 'TEXT')

                                                                            <td class="p-0 pl-1">
                                                                                <input type="hidden"
                                                                                       name="vesselCondition[{{ $i }}][pVcTid]"
                                                                                       value="{{ $pVcondi->id }}">
                                                                                <textarea
                                                                                    name="vesselCondition[{{ $i }}][value]"
                                                                                    class="form-control"
                                                                                    placeholder="Type condition status">{{ old('vesselCondition[$i][value]', $pVcondi->ans_value) }}</textarea>
                                                                            </td>
                                                                        @else
                                                                            <td class="p-0 pl-1 pt-1">
                                                                                <input type="hidden"
                                                                                       name="vesselCondition[{{ $i }}][pVcTid]"
                                                                                       value="{{ $pVcondi->id }}">
                                                                                <ul class="list-unstyled mb-0">
                                                                                    <li class="d-inline-block mr-2 mb-1">
                                                                                        <div
                                                                                            class="custom-control custom-radio">
                                                                                            <input type="radio"
                                                                                                   class="custom-control-input"
                                                                                                   value="Y"
                                                                                                   {{ old('vesselCondition[$i][value]', $pVcondi->ans_value) == 'Y' ? 'checked' : '' }}
                                                                                                   name="vesselCondition[{{ $i }}][value]"
                                                                                                   id="condRadio{{ $j }}">
                                                                                            <label
                                                                                                class="custom-control-label"
                                                                                                for="condRadio{{ $j++ }}">Yes</label>
                                                                                        </div>
                                                                                    </li>
                                                                                    <li class="d-inline-block mr-2 mb-1">
                                                                                        <div
                                                                                            class="custom-control custom-radio">
                                                                                            <input type="radio"
                                                                                                   class="custom-control-input"
                                                                                                   value="N"
                                                                                                   {{ old('vesselCondition[$i][value]', $pVcondi->ans_value) == 'N' ? 'checked' : '' }}
                                                                                                   name="vesselCondition[{{ $i }}][value]"
                                                                                                   id="condRadio{{ $j }}">
                                                                                            <label
                                                                                                class="custom-control-label"
                                                                                                for="condRadio{{ $j++ }}">No</label>
                                                                                        </div>
                                                                                    </li>
                                                                                </ul>
                                                                            </td>
                                                                        @endif
                                                                    @endif
                                                                @empty

                                                                    {{--
                                                                Checking conditions input type and printing input field text area or radio
                                                                --}}

                                                                    @if ($condi->value_type == 'TEXT')
                                                                        <td class="p-0 pl-1">
                                                                            <input type="hidden"
                                                                                   name="vesselCondition[{{ $i }}][pVcTid]"
                                                                                   value="">
                                                                            <textarea
                                                                                name="vesselCondition[{{ $i }}][value]"
                                                                                class="form-control"
                                                                                placeholder="Type condition status">{{ old('vesselCondition[$i][value]') }}</textarea>
                                                                        </td>
                                                                    @else
                                                                        <td class="p-0 pl-1 pt-1">
                                                                            <input type="hidden"
                                                                                   name="vesselCondition[{{ $i }}][pVcTid]"
                                                                                   value="">
                                                                            <ul class="list-unstyled mb-0">
                                                                                <li class="d-inline-block mr-2 mb-1">
                                                                                    <div
                                                                                        class="custom-control custom-radio">
                                                                                        <input type="radio"
                                                                                               class="custom-control-input"
                                                                                               value="Y"
                                                                                               name="vesselCondition[{{ $i }}][value]"
                                                                                               id="condRadio{{ $j }}"
                                                                                               checked="">
                                                                                        <label
                                                                                            class="custom-control-label"
                                                                                            for="condRadio{{ $j++ }}">Yes</label>
                                                                                    </div>
                                                                                </li>
                                                                                <li class="d-inline-block mr-2 mb-1">
                                                                                    <div
                                                                                        class="custom-control custom-radio">
                                                                                        <input type="radio"
                                                                                               class="custom-control-input"
                                                                                               value="N"
                                                                                               name="vesselCondition[{{ $i }}][value]"
                                                                                               id="condRadio{{ $j }}">
                                                                                        <label
                                                                                            class="custom-control-label"
                                                                                            for="condRadio{{ $j++ }}">No</label>
                                                                                    </div>
                                                                                </li>


                                                                            </ul>
                                                                        </td>

                                                                    @endif


                                                                @endforelse

                                                            </tr>
                                                        @endif
                                                    @endforeach

                                                    </tbody>


                                                </table>

                                                <div class="col-md-12">
                                                    <label style="font-size: 15px; color:#708090"> 8. NOS OF GOOD
                                                        MOORING LINES :
                                                        FORD </label>
                                                    &nbsp;&nbsp; <input type="number" name="ford_good_mooring_number"
                                                                        value="{{ old('ford_good_mooring_number', $data->ford_good_mooring_number) }}"
                                                                        placeholder="Ford" class="form-control"
                                                                        style="width:25% ; display: inline">
                                                    @if ($errors->has('ford_good_mooring_number'))
                                                        <span
                                                            class="help-block">{{ $errors->first('ford_good_mooring_number') }}</span>
                                                    @endif

                                                    &nbsp;&nbsp;&nbsp; <label
                                                        style="font-size: 15px; color:#708090">AFT</label>
                                                    &nbsp;&nbsp;&nbsp; <input type="text" name="aft"
                                                                              value="{{ old('aft', $data->aft) }}"
                                                                              placeholder="AFT" class="form-control"
                                                                              style="width:30% ; display: inline">
                                                    @if ($errors->has('aft'))
                                                        <span class="help-block">{{ $errors->first('aft') }}</span>
                                                    @endif
                                                </div>


                                                <div class="col-md-12 mt-2 ">
                                                    <label style="font-size: 15px; color:#708090"> 9. STERN POWER
                                                        AVAILABLE</label>
                                                    &nbsp;&nbsp;
                                                    <input type="text" name="stern_power_avail"
                                                           value="{{ old('stern_power_avail', $data->stern_power_avail) }}"
                                                           placeholder="Stern power avail" class="form-control"
                                                           style="width:32% ; display: inline"
                                                           oninput="this.value = this.value.toUpperCase()">
                                                    @if ($errors->has('stern_power_avail'))
                                                        <span
                                                            class="help-block">{{ $errors->first('stern_power_avail') }}</span>
                                                    @endif

                                                    &nbsp;&nbsp;&nbsp; <label
                                                        style="font-size: 15px; color:#708090">IMMEDIATELY</label>
                                                    &nbsp;&nbsp;&nbsp; <input type="text" name="immediately"
                                                                              value="{{ old('immediately', $data->immediately) }}"
                                                                              placeholder="Immediately"
                                                                              class="form-control"
                                                                              style="width:24% ; display: inline">
                                                    @if ($errors->has('immediately'))
                                                        <span
                                                            class="help-block">{{ $errors->first('immediately') }}</span>
                                                    @endif
                                                </div>

                                            </div>

                                        @endif
                                    </fieldset>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <fieldset class="border p-1">
                                        <legend class="w-auto" style="font-size: 18px;">File upload</legend>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group form-row">
                                                    <div class="col-md-5">
                                                        <label>Master sign</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input name="master_sign" class="" type="file">
                                                        @if ($errors->has('master_sign'))
                                                            <span
                                                                class="help-block">{{ $errors->first('master_sign') }}</span>
                                                        @endif
                                                    </div>
                                                    @if (isset($data->id))
                                                        <div class="col-md-3">
                                                            @foreach ($data->pilotage_files as $file)
                                                                @if ($file->title == 'MASTER_SIGN')
                                                                    @if ($file->files != '')
                                                                        <a target="_blank" class="form-control"
                                                                           style="text-align: center;"
                                                                           href="{{ route('ps-certificate-download-media', $file->id) }}"><i
                                                                                class="bx bx-download"></i></a>
                                                                        <input type="hidden" name="pre_master_sign_id"
                                                                               value="{{ isset($file->id) ? $file->id : '' }}">
                                                                    @else
                                                                        <span class="form-control"
                                                                              style="text-align: center;">No file
                                                                            uploaded</span>
                                                                        <input type="hidden" name="pre_master_sign_id"
                                                                               value="{{ isset($file->id) ? $file->id : '' }}">
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group form-row">
                                                    <div class="col-md-5">
                                                        <label>Assistant Harbor/Pilot sign</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input name="assistant_sign" class="" type="file">
                                                        @if ($errors->has('assistant_sign'))
                                                            <span
                                                                class="help-block">{{ $errors->first('assistant_sign') }}</span>
                                                        @endif
                                                    </div>
                                                    @if (isset($data->id))
                                                        <div class="col-md-3">
                                                            @foreach ($data->pilotage_files as $file)
                                                                @if ($file->title == 'ASSISTANT_SIGN')
                                                                    @if ($file->files)
                                                                        <a class="form-control"
                                                                           style="text-align: center;"
                                                                           href="{{ route('ps-certificate-download-media', $file->id) }}"><i
                                                                                class="bx bx-download"></i></a>
                                                                        <input type="hidden"
                                                                               name="pre_assistant_sign_id"
                                                                               value="{{ isset($file->id) ? $file->id : '' }}">
                                                                    @else
                                                                        <span class="form-control"
                                                                              style="text-align: center;">No file
                                                                            uploaded</span>
                                                                        <input type="hidden"
                                                                               name="pre_assistant_sign_id"
                                                                               value="{{ isset($file->id) ? $file->id : '' }}">
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group form-row">
                                                    <div class="col-md-5">
                                                        <label>Form upload</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input name="certificate_form" class="" type="file">
                                                        @if ($errors->has('certificate_form'))
                                                            <span
                                                                class="help-block">{{ $errors->first('certificate_form') }}</span>
                                                        @endif
                                                    </div>
                                                    @if (isset($data->id))
                                                        <div class="col-md-3">
                                                            @foreach ($data->pilotage_files as $file)
                                                                @if ($file->title == 'CERTIFICATE_FILE')
                                                                    @if ($file->files != '')
                                                                        <a class="form-control"
                                                                           style="text-align: center;"
                                                                           href="{{ route('ps-certificate-download-media', $file->id) }}"><i
                                                                                class="bx bx-download"></i></a>
                                                                        <input type="hidden"
                                                                               name="pre_certificate_file_id"
                                                                               value="{{ isset($file->id) ? $file->id : '' }}">
                                                                    @else
                                                                        <span class="form-control"
                                                                              style="text-align: center;">No file
                                                                            uploaded</span>
                                                                        <input type="hidden"
                                                                               name="pre_certificate_file_id"
                                                                               value="{{ isset($file->id) ? $file->id : '' }}">
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Remarks<span class=""></span></label>
                                        <textarea name="remarks" placeholder="Remarks" class="form-control"
                                                  oninput="this.value = this.value.toUpperCase()">{{ old('remarks', $data->remarks) }}</textarea>
                                        @if ($errors->has('remarks'))
                                            <span class="help-block">{{ $errors->first('remarks') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Status<span class="required"></span></label>
                                            <ul class="list-unstyled mb-0">
                                                <li class="d-inline-block mr-2 mb-1">
                                                    <fieldset>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                   value="{{ old('status', 'P') }}"
                                                                   {{ isset($data->status) && $data->status == 'P' ? 'checked' : '' }}
                                                                   name="status" id="customRadio1" checked="">
                                                            <label class="custom-control-label"
                                                                   for="customRadio1">Draft</label>
                                                        </div>
                                                    </fieldset>
                                                </li>
                                                <li class="d-inline-block mr-2 mb-1">
                                                    <fieldset>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                   value="{{ old('status', 'A') }}"
                                                                   {{ isset($data->status) && $data->status == 'A' ? 'checked' : '' }}
                                                                   name="status" id="customRadio2">
                                                            <label class="custom-control-label"
                                                                   for="customRadio2">Requesting
                                                                for Approval</label>
                                                        </div>
                                                    </fieldset>
                                                </li>
                                            </ul>
                                            @if ($errors->has('status'))
                                                <span class="help-block">{{ $errors->first('status') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label>&nbsp;</label>
                                    <div class="row">
                                        <div class="d-flex justify-content-end col">
                                            <button type="submit" name="save"
                                                    onclick="return confirm('Are you sure?')"
                                                    class="btn btn btn-dark shadow mr-1 mb-1">
                                                {{ isset($data->id) ? 'Update' : 'Save' }} </button>

                                            <a type="reset" href="{{ route('ps-certificate-entry') }}"
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
                    <h4 class="card-title"> Pilotage service certificate List</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table table-sm datatable">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Pilotage type</th>
                                    <th>Vessel name</th>
                                    <th>Pilot</th>
                                    <th>Pilot borded at</th>
                                    <th>Pilot left at</th>
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
        function dateTime(selector) {
            var elem = $(selector);
            elem.datetimepicker({
                format: 'YYYY-MM-DD HH:mm',
                widgetPositioning: {
                    horizontal: 'left',
                    vertical: 'bottom'
                },
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


        // dom>
        // on page refresh
        let pilotageVal = $('.pilotageType :selected').val()
        // alert(pilotageVal);
        if (pilotageVal == 1) {
            $("#mooringLabelFrom").text("MOORING FROM");
            $("#mooringLabelTo").text("MOORING TO");
            $("#mooringFromPLC").attr("placeholder", "Mooring from time");
            $("#mooringToPLC").attr("placeholder", "Mooring to time");
        } else if (pilotageVal == 2) {

            $("#mooringLabelFrom").html("MOORING/UNMOORING FROM");
            $("#mooringLabelTo").html("MOORING/UNMOORING TO");
            $("#mooringFromPLC").attr("placeholder", "Mooring/Unmooring From");
            $("#mooringToPLC").attr("placeholder", "Mooring/Unmooring To");
        } else if (pilotageVal == 3) {

            $("#mooringLabelFrom").text("UNMOORING FROM");
            $("#mooringLabelTo").text("UNMOORING TO");
            $("#mooringFromPLC").attr("placeholder", "Unmooring From");
            $("#mooringToPLC").attr("placeholder", "Unmooring To");
        }

        $('input:radio[name="stay_guptakhal_yn"][value="N"]').prop('checked', true);

        //On dropdown changes
        $(".pilotageType").on('change', function () {
            let pilotageVal = $('.pilotageType :selected').val();
            // alert(pilotageVal);

            if (pilotageVal == 1) {
                $("#mooringLabelFrom").text("MOORING FROM");
                $("#mooringLabelTo").text("MOORING TO");
                $("#mooringFromPLC").attr("placeholder", "Mooring from time");
                $("#mooringToPLC").attr("placeholder", "Mooring to time");
            } else if (pilotageVal == 2) {

                $("#mooringLabelFrom").html("MOORING/UNMOORING FROM");
                $("#mooringLabelTo").html("MOORING/UNMOORING TO");
                $("#mooringFromPLC").attr("placeholder", "Mooring/Unmooring From");
                $("#mooringToPLC").attr("placeholder", "Mooring/Unmooring To");
            } else if (pilotageVal == 3) {

                $("#mooringLabelFrom").text("UNMOORING FROM");
                $("#mooringLabelTo").text("UNMOORING TO");
                $("#mooringFromPLC").attr("placeholder", "Unmooring From");
                $("#mooringToPLC").attr("placeholder", "Unmooring To");
            }

        });


        $(".pilotageType").on('change', function () {
            let pilotageVal = $('.pilotageType :selected').val();

            if (pilotageVal == 1 || pilotageVal == '') {
                // $('.reg_no').prop('readonly', false);
                $('.master_name').prop('readonly', false);
            } else if (pilotageVal == 2 || pilotageVal == 3) {
                $('.reg_no').prop('readonly', true);
                $('.master_name').prop('readonly', true);
            }

        });


        function callDateTimePicker(e) {
            dateTime("#" + e.id);
        }

        $(document).ready(function () {

            // $("#pilotage_from_time").on('change paste keyup input', function () {
            $("#pilot_borded_at").on('change paste keyup input', function () {
                let time = $(this).val();

                let Time = time.split(" ");
                let getTime = Time[1];
                let hours = getTime.split(":");
                let h = hours[0];
                if (h > 18 || h < 6) {
                    $("#schedule_type_id").val(2).trigger('change');

                } else {
                    $("#schedule_type_id").val(1).trigger('change');
                }

            });

            $(document).on('focusout', '#pilot_borded_at', function () {
                $('#pilot_left_at').val($('#pilot_borded_at').val());
                $('#mooringFromPLC').val($('#pilot_borded_at').val());
                $('#mooringToPLC').val($('#pilot_borded_at').val());
                $('#master_sign_date').val($('#pilot_borded_at').val());

            });


            $("#tugName").on('change', function () {
                $('#tug_assitance_from_time').val($('#pilot_borded_at').val());
                $('#tug_assitance_to_time').val($('#pilot_left_at').val());

            });


            $(document).on('change', '.tugNameNewRow', function () {
                $('.tug_assitance_from_time_new_row').val($('#pilot_borded_at').val());
                $('.tug_assitance_to_time_new_row').val($('#pilot_left_at').val());
            });
// dom<

            //Time and date picker
            dateTime("#bordedAt");
            dateTime("#leftAt");
            dateTime("#pilotageFrom");
            dateTime("#pilotageTo");
            dateTime("#mooringFrom");
            dateTime("#unmooring_from_time");
            dateTime("#mooringTo");
            dateTime("#unmooring_to_time");
            dateTime("#signDate");

            $(".vesselName").select2();
            $(".workingType").select2();
            $(".motherVessel").select2();

            function show_hide_pilotage_shifted() {
                if ($(".pilotageType").find(":selected").text() === "SHIFTING") {
                    $(".shiftedFromField").prop("hidden", false);
                    $(".shiftedToField").prop("hidden", false);


                    $(".pilotage_from").val("");
                    $(".pilotage_to").val("");
                    $(".pilotageFromField").prop("hidden", true);
                    $(".pilotageToField").prop("hidden", true);
                } else if ($(".pilotageType").find(":selected").text() === "INWARD" || $(".pilotageType").find(
                    ":selected").text() === "OUTWARD") {
                    $(".pilotageFromField").prop("hidden", false);
                    $(".pilotageToField").prop("hidden", false);

                    $(".shifted_from").val("");
                    $(".shifted_to").val("");
                    $(".shiftedFromField").prop("hidden", true);
                    $(".shiftedToField").prop("hidden", true);
                } else {
                    $(".pilotage_from").val("");
                    $(".pilotage_to").val("");
                    $(".pilotageFromField").prop("hidden", true);
                    $(".pilotageToField").prop("hidden", true);

                    $(".shifted_from").val("");
                    $(".shifted_to").val("");
                    $(".shiftedFromField").prop("hidden", true);
                    $(".shiftedToField").prop("hidden", true);
                }
            }

            //At the time of update or there has data inside data varriable.
            show_hide_pilotage_shifted();

            $(".pilotageType").select2().on("change", function (e) {
                show_hide_pilotage_shifted();
            });

            //Mother vessel field and working type field
            $(".motherVesselField").prop("hidden", true);

            //Loading data inside mother vessel dropdown from server
            function set_mother_vessels(vesselId) {
                $(".motherVessel").select2({
                    placeholder: "Select a vessel",
                    allowClear: false,
                    ajax: {
                        delay: 250,
                        url: '/ps-certificate-entry/mother-vessels/' + vesselId,
                        dataType: 'json',
                        data: function (params) {
                            var queryParameters = {
                                q: params.term
                            };

                            return queryParameters;
                        },
                        processResults: function (data) {
                            return {
                                results: $.map(data.vesselsList, function (item) {
                                    return {
                                        text: item.name + "(" + item.reg_no + ")",
                                        id: item.id
                                    }
                                })
                            };
                        },
                        cache: true
                    }
                });
            }

            //Vessel name selected in vessel can't be show in mother vessel list. And mother vessel list shows when lighter is selected
            $(".vesselName").select2().on("change", function (e) {
                $(".workingType").val("").trigger("change");
                $(".motherVessel").empty();
                $(".motherVesselField").prop("hidden", true);


                if ($(this).find(':selected').val() != "") {
                    let vesselId = $(this).find(':selected').val();

                    $.ajax({
                        dataType: 'JSON',
                        url: '/ps-certificate-entry/foreign-vessels-detail/' + vesselId,
                        cache: true,
                        success: function (data) {

                            $(".shippingAgent").val(data["vesselDetail"][0]
                                .shipping_agent_name);
                            $(".lastPort").val(data["vesselDetail"][0].last_port_name);
                            $(".nextPort").val(data["vesselDetail"][0].next_port_name);
                            //$(".master_name").val(data["vesselDetail"][0].master_name);
                            //$(".vessel_reg_no").val(data["vesselDetail"][0].reg_no);

                            /*$(".draught").val(data["vesselDetail"][0].draft);
                            $(".length").val(data["vesselDetail"][0].loa);*/

                            var d = new Date(data["vesselDetail"][0].arrival_date),
                                month = '' + (d.getMonth() + 1),
                                day = '' + d.getDate(),
                                year = d.getFullYear();
                            if (month.length < 2)
                                month = '0' + month;
                            if (day.length < 2)
                                day = '0' + day;

                            $(".arrival_date").val(year + "-" + month + "-" + day);
                            $(".grt").val(data["vesselDetail"][0].grt);
                            $(".nrt").val(data["vesselDetail"][0].nrt);

                            $(".draught").val(data["vesselDetail"][0].draft);
                            $(".length").val(data["vesselDetail"][0].loa);


                            if ($(".pilotageType").val() == 1 || $(".pilotageType").val() == '') {
                                // $(".reg_no").val(data["noc_reg_info"].circular_no);
                                $(".reg_no").val(data["new_reg_no"]);
                                $(".master_name").val(data["noc_reg_info"].master_name);

                                // $('.reg_no').prop('readonly', false);
                                $('.master_name').prop('readonly', false);
                                if(data['is_channel']) {
                                    $('.work_location_id').val(21122715004018976).trigger('change') //channel selected
                                } else {
                                    $('.work_location_id').val(1).trigger('change') //outer anchorage selected
                                }
// dom
                            }
                            else if ($(".pilotageType").val() == 2 || $(".pilotageType").val() == 3) {
                                // $(".reg_no").val(data["noc_reg_info"].circular_no);
                                // $(".master_name").val(data["noc_reg_info"].master_name);
                                // $('.reg_no').prop('readonly', true);
                                // $('.master_name').prop('readonly', true);
                                changeShiftingOutwardValues()
                            }
                        }
                    });
                }
            });


// swing_mooring starts

            $(".pilotage_from_loc").on('change', function () {
                let pilotageVal = $('.pilotage_from_loc :selected').val();
                let pilotageText = $('.pilotage_from_loc :selected').text();

                if (pilotageVal == 22120616000028951 || pilotageText == 'BB') {
                    $(".swing_mooring option[value='Y']").prop('selected', true).change();
                    // alert($(".swing_mooring option[value='Y']").prop('selected', true).change().val());

                } else if (pilotageVal == 22112913000024295 || pilotageText == 'BUOY NO-1') {
                    $(".swing_mooring option[value='Y']").prop('selected', true).change();
                }
                // else {
                //     $(".swing_mooring option[value='N']").prop('selected', true).change();
                // }
            });

            $(".pilotage_to_loc").on('change', function () {
                let pilotageVal = $('.pilotage_to_loc :selected').val();
                let pilotageText = $('.pilotage_to_loc :selected').text();

                if (pilotageVal == 22120616000028951 || pilotageText == 'BB') {
                    $(".swing_mooring option[value='Y']").prop('selected', true).change();

                } else if (pilotageVal == 22112913000024295 || pilotageText == 'BUOY NO-1') {
                    $(".swing_mooring option[value='Y']").prop('selected', true).change();
                }
                // else {
                //     $(".swing_mooring option[value='N']").prop('selected', true).change();
                // }
            });

// swing_mooring ends

// fixed_mooring starts

            $(".pilotage_from_loc").on('change', function () {
                let pilotageVal = $('.pilotage_from_loc :selected').val();
                let pilotageText = $('.pilotage_from_loc :selected').text();
                // alert(pilotageVal + ' --- ' + pilotageText);

                if (pilotageVal == 35 || pilotageText == 'RM/1') {
                    $(".fixed_mooring option[value='Y']").prop('selected', true).change();
                } else if (pilotageVal == 36 || pilotageText == 'RM/2') {
                    $(".fixed_mooring option[value='Y']").prop('selected', true).change();
                } else if (pilotageVal == 37 || pilotageText == 'RM/3') {
                    $(".fixed_mooring option[value='Y']").prop('selected', true).change();
                } else if (pilotageVal == 38 || pilotageText == 'RM/4') {
                    $(".fixed_mooring option[value='Y']").prop('selected', true).change();
                    // alert($(".fixed_mooring option[value='Y']").prop('selected', true).change().val());
                } else if (pilotageVal == 40 || pilotageText == 'RM/5') {
                    $(".fixed_mooring option[value='Y']").prop('selected', true).change();
                } else if (pilotageVal == 23021320004093731 || pilotageText == 'RM/6') {
                    $(".fixed_mooring option[value='Y']").prop('selected', true).change();
                } else if (pilotageVal == 23021320001093732 || pilotageText == 'RM/7') {
                    $(".fixed_mooring option[value='Y']").prop('selected', true).change();
                } else if (pilotageVal == 45 || pilotageText == 'RM/8') {
                    $(".fixed_mooring option[value='Y']").prop('selected', true).change();
                } else if (pilotageVal == 23021320008093733 || pilotageText == 'RM/9') {
                    $(".fixed_mooring option[value='Y']").prop('selected', true).change();
                } else if (pilotageVal == 22110711005015461 || pilotageText == 'RM/10') {
                    $(".fixed_mooring option[value='Y']").prop('selected', true).change();
                }
                // else {
                //     $(".fixed_mooring option[value='N']").prop('selected', true).change();
                // }

            });

            $(".pilotage_to_loc").on('change', function () {
                let pilotageVal = $('.pilotage_to_loc :selected').val();
                let pilotageText = $('.pilotage_to_loc :selected').text();

                if (pilotageVal == 35 || pilotageText == 'RM/1') {
                    $(".fixed_mooring option[value='Y']").prop('selected', true).change();
                } else if (pilotageVal == 36 || pilotageText == 'RM/2') {
                    $(".fixed_mooring option[value='Y']").prop('selected', true).change();
                } else if (pilotageVal == 37 || pilotageText == 'RM/3') {
                    $(".fixed_mooring option[value='Y']").prop('selected', true).change();
                } else if (pilotageVal == 38 || pilotageText == 'RM/4') {
                    $(".fixed_mooring option[value='Y']").prop('selected', true).change();
                    // alert($(".fixed_mooring option[value='Y']").prop('selected', true).change().val());
                } else if (pilotageVal == 40 || pilotageText == 'RM/5') {
                    $(".fixed_mooring option[value='Y']").prop('selected', true).change();
                } else if (pilotageVal == 23021320004093731 || pilotageText == 'RM/6') {
                    $(".fixed_mooring option[value='Y']").prop('selected', true).change();
                } else if (pilotageVal == 23021320001093732 || pilotageText == 'RM/7') {
                    $(".fixed_mooring option[value='Y']").prop('selected', true).change();
                } else if (pilotageVal == 45 || pilotageText == 'RM/8') {
                    $(".fixed_mooring option[value='Y']").prop('selected', true).change();
                } else if (pilotageVal == 23021320008093733 || pilotageText == 'RM/9') {
                    $(".fixed_mooring option[value='Y']").prop('selected', true).change();
                } else if (pilotageVal == 22110711005015461 || pilotageText == 'RM/10') {
                    $(".fixed_mooring option[value='Y']").prop('selected', true).change();
                }
                // else {
                //     $(".fixed_mooring option[value='N']").prop('selected', true).change();
                // }

            });


// fixed_mooring ends
            $( "#certificateForm" ).submit(function( event ) {
                $(".work_location_id").attr( "disabled", false );
                // $(".fixed_mooring").attr( "disabled", false );
                $(".swing_mooring").attr( "disabled", false );
            });

// Draft & Loa readonly
            $(".pilotageType").on('change', function () {
                let pilotageVal = $('.pilotageType :selected').val();
                if (pilotageVal == 1 || pilotageVal == '') {
                    $('.draught').prop('readonly', false);
                    $('.length').prop('readonly', false);
                } else {
                    $('.draught').prop('readonly', true);
                    $('.length').prop('readonly', true);
                }
            });

// dom<


            $(".workingType").select2().on("change", function (e) {
                let text = $(this).find(':selected').text();

                if (text === "LIGHTER") {
                    $(".motherVesselField").prop("hidden", false);
                    $(".motherVessel").empty();

                    let vesselId = $(".vesselName").find(':selected').val();
                    set_mother_vessels(vesselId);
                } else {
                    $(".motherVesselField").prop("hidden", true);
                }
            });

            //For updating situation. When lighter vessel is selected need to load pre selected vessels inside mother vessel
            if ($(".workingType").find(':selected').text() == "LIGHTER") {

                let motherViD = '{{ isset($data->mother_vessel->id) ? $data->mother_vessel->id : '' }}';
                let motherVtext = '{{ isset($data->mother_vessel->name) ? $data->mother_vessel->name : '' }}';

                if (motherViD != '' && motherVtext != '') {
                    let data = [{
                        id: motherViD,
                        text: motherVtext
                    }];
                    $('.motherVessel').select2({
                        data: data
                    });
                }

                let vesselId = $(".vesselName").find(':selected').val();
                $(".motherVesselField").prop("hidden", false);
                //$(".motherVessel").empty();

                set_mother_vessels(vesselId);
            }

            //Tagg tugs
            let counter = parseInt($("table.tugTaggings >tbody >tr").length);

            //Adding new row
            $("#addrow").on("click", function () {

                let newRow = $("<tr>");
                let cols = "";

                /*cols +='<td class="p-1"><select name="tug['+counter+'][tugId]"  class="form-control tugsList'+counter+'"></select></td>';*/

                cols += '<td class="p-1">\n' +
                    '  <input type="hidden" name="tug[' + counter + '][pTtId]" value=""> \n' +
                    '   <select \n' +
                    '     name="tug[' + counter + '][tugId]" \n' +
                    '      class="form-control select2 tugNameNewRow"  \n' +
                    // '      id="tugNameNewRow" ' +
                    '       oninput="this.value = this.value.toUpperCase()">\n' +
                    '         <option value="">Select a tug</option>\n' +
                    '         @forelse($tugLists as $name)\n' +
                    '             <option value="{{ $name->id }}">{{ $name->name }}</option>\n' +
                    '         @empty\n' +
                    '             <option value="">No tug found</option>\n' +
                    '         @endforelse\n' +
                    '   </select>\n' +
                    '@if ($errors->has("tug.'+counter+'.tugId"))\n' +
                    '    <span class="help-block">{{ $errors->first("tug.'+counter+'.tugId") ? 'This tug field is required.' : '' }}</span>\n' +
                    '@endif' +
                    ' </td>';

                cols += '<td class="p-1">\n' +
                    '     <div class="row">\n' +
                    '         <div class="col">\n' +
                    '             <div class="input-group date" onclick="callDateTimePicker(this)" id="assistanceFrom' +
                    counter + '" data-target-input="nearest">\n' +
                    '                 <div class="input-group-append" data-target="#assistanceFrom' +
                    counter + '" data-toggle="datetimepicker">\n' +
                    '                     <div class="input-group-text">\n' +
                    '                         <i class="bx bx-calendar"></i>\n' +
                    '                     </div>\n' +
                    '                 </div>\n' +
                    '                 <input type="text"\n' +
                    '                        name="tug[' + counter + '][assistanceFrom]"\n' +
                    '                        class="form-control datetimepicker-input tug_assitance_from_time_new_row"\n' +
                    '                        data-target="#assistanceFrom' + counter + '"\n' +
                    '                        data-toggle="datetimepicker"\n' +
                    // '                        id="tug_assitance_from_time_new_row"\n' +
                    '                        placeholder="Assistance from" />\n' +
                    '@if ($errors->has("tug.'+counter+'.assistanceFrom"))\n' +
                    '    <span class="help-block">{{ $errors->first("tug.'+counter+'.assistanceFrom") ? 'This assistance form field is required.' : '' }}</span>\n' +
                    '@endif' +
                    '             </div>\n' +
                    '         </div>\n' +
                    '     </div>\n' +
                    ' </td>';

                cols += '<td class="p-1">\n' +
                    '     <div class="input-group date" onclick="callDateTimePicker(this)" id="assistanceTo' +
                    counter + '" data-target-input="nearest">\n' +
                    '         <div class="input-group-append" data-target="#assistanceTo' + counter +
                    '" data-toggle="datetimepicker">\n' +
                    '             <div class="input-group-text">\n' +
                    '                 <i class="bx bx-calendar"></i>\n' +
                    '             </div>\n' +
                    '         </div>\n' +
                    '         <input type="text"\n' +
                    '                name="tug[' + counter + '][assistanceTo]"\n' +
                    '                class="form-control datetimepicker-input tug_assitance_to_time_new_row"\n' +
                    '                data-target="#assistanceTo' + counter + '"\n' +
                    '                data-toggle="datetimepicker"\n' +
                    // '                id="tug_assitance_to_time_new_row"\n' +
                    '                placeholder="Assistance to" />\n' +
                    '@if ($errors->has("tug.'+counter+'.assistanceTo"))\n' +
                    '    <span class="help-block">{{ $errors->first("tug.'+counter+'.assistanceTo") ? 'This assistance to field is required.' : '' }}</span>\n' +
                    '@endif' +
                    '     </div>\n' +
                    ' </td>';

                cols += '<td class="p-1">\n' +
                    '    <select name="tug[' + counter +
                    '][isPrimary]" class="form-control select2"   oninput="this.value = this.value.toUpperCase()">\n' +
                    '        <option value="">Select one</option>\n' +
                    '        <option value="Y" >Yes</option>\n' +
                    '        <option value="N" selected >No</option>\n' +
                    '    </select>\n' +
                    '@if ($errors->has("tug.'+counter+'.isPrimary"))\n' +
                    '    <span class="help-block">{{ $errors->first("tug.'+counter+'.isPrimary") ? 'This primary field is required.' : '' }}</span>\n' +
                    '@endif' +
                    '</td>';

                {{--cols += '<td class="p-1">\n' +--}}
                    {{--    '    <select name="tug[' + counter +--}}
                    {{--    '][workLocation]"  class="form-control select2"   oninput="this.value = this.value.toUpperCase()">\n' +--}}
                    {{--    '        <option value="">Select one..</option>\n' +--}}
                    {{--    '        @forelse($workLocation as $location)\n' +--}}
                    {{--    '            <option  value="{{ $location->id }}">{{ $location->name }}</option>\n' +--}}
                    {{--    '        @empty\n' +--}}
                    {{--    '            <option value="">No location found</option>\n' +--}}
                    {{--    '        @endforelse\n' +--}}
                    {{--    '    </select>\n' +--}}
                    {{--    '@if ($errors->has("tug.'+counter+'.workLocation"))\n' +--}}
                    {{--    '    <span class="help-block">{{ $errors->first("tug.'+counter+'.workLocation") ? 'This work location field is required.' : '' }}</span>\n' +--}}
                    {{--    '@endif' +--}}
                    {{--    '</td>';--}}

                    cols +=
                    '<td><span class="rowDel text-danger cursor-pointer"><i class="bx bx-trash"></i></span></td></tr>';

                newRow.append(cols);
                //Appending new row
                $("table.tugTaggings").append(newRow);
                $(".tugsList" + counter).select2({
                    placeholder: "Select a tug"
                });
                counter++;
                /*let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    type: "POST",
                    url: "/ps-certificate-entry/tugs",
                    data:{_token:CSRF_TOKEN},
                    dataType:"JSON",
                    success: function (data) {
                        //Inserting data into select2
                        let tugs = $.map(data.tugs, function (item) {
                            return {
                                text: item.name,
                                id: item.id
                            }});

                        tugs.unshift({text: 'Select a tug', id: ''});
                        $(".tugsList"+counter).select2({data:tugs});
                        //         let newOption = new Option(data.tugs[0].name, data.tugs[0].id, false, false);
                        // $(".tugsList"+counter).append(newOption).trigger('change');

                    },
                    error: function (data) {
                        console.log(data);
                    }
                });*/
            });

            //Removing row
            $("table.tugTaggings").on("click", ".rowDel", function (event) {
                //$(this).closest("tr").remove();
                let index = $(this).closest("tr").index();
                let html = '<td><input type="hidden" name="tug[' + index + '][actionType]" value="D"></td>';
                $(this).closest("tr").append(html);
                // $(this).closest("tr").hide();
                $(this).closest("tr").remove();
                //counter -= 1
            });

            totalPilotageTime();

            $(".pilotageCalcFrom, .pilotageCalcTo").on("click", function () {
                totalPilotageTime();
            });

            function totalPilotageTime() {
                var tMin = 0;
                var fMin = 0;
                var fMin = 0;
                if ($(".pilotage_from").val() !== "" && $(".pilotage_to").val() !== "") {

                    //Formating date time
                    let startTime = $(".pilotage_from").val();
                    let time1 = new Date(startTime);


                    //Formating date time
                    let endTime = $(".pilotage_to").val();
                    let time2 = new Date(endTime);
                    let fHr = 0;


                    if (time2 > time1) {
                        let misec = time2.getTime() - time1.getTime();
                        let sec1 = Math.floor(misec / 1000);
                        //fHr =  Math.ceil(sec1/(60*60));
                        tMin = Math.abs(sec1 / 60);
                        fMin = Math.abs(tMin % 60);
                        fHr = Math.floor(tMin / 60);
                    }

                    tMin = (tMin != undefined) ? tMin : tMin;
                    $(".totalPilotage").html("Total pilotage time: " + tMin + " Minute");
                }
            }

            $(".vesselName, .pilotageType").on("change", function (e) {

                changeShiftingOutwardValues()
            });

            function changeShiftingOutwardValues(){
                let vessel = $('.vesselName').val();
                let type = $('.pilotageType').val();

                if(vessel && type && type != 1)
                {
                    $(".arrival_date").prop("readonly", true).attr('tabindex', '-1');
                    $(".draught").prop("readonly", true).attr('tabindex', '-1');
                    $(".length").prop("readonly", true).attr('tabindex', '-1');
                    $.ajax({
                        // dataType: 'JSON',
                        type: 'get',
                        url: '/ps-certificate-entry/get-master-name/',
                        data : { vessel : vessel },
                        cache: true,
                        async: false,
                        success: function (data) {
                            console.log(data)
                            if(data) {
                                $(".master_name").val(data.master_name).prop("readonly", true).attr('tabindex', '-1');
                                $(".work_location_id").val(data.work_location_id).trigger('change').attr("disabled", true).attr('tabindex', '-1');
                                $(".reg_no").val(data.vessel_reg_no).prop("readonly", true).attr('tabindex', '-1');
                                $(".deck_cargo").val(data.deck_cargo).prop("readonly", true).attr('tabindex', '-1');
                                $(".fixed_mooring").val(data.fixed_mooring).trigger('change').attr('tabindex', '-1');
                                $(".swing_mooring").val(data.swing_mooring).trigger('change').attr("disabled", true).attr('tabindex', '-1');
                                $(".draught").val(data.draught);
                                $(".length").val(data.length);
                                $(".call_sign").val(data.call_sign);
                                $(".crw_officer_incl_mst_num").val(data.crw_officer_incl_mst_num);
                                $(".owner_address").val(data.owner_address);
                            }
                            else
                            {
                                $(".master_name").prop("readonly", false).attr('tabindex', '0');
                                $(".work_location_id").attr("disabled", false).attr('tabindex', '0');
                                // $(".reg_no").prop("readonly", false).attr('tabindex', '0');
                                $(".deck_cargo").prop("readonly", false).attr('tabindex', '0');
                                $(".fixed_mooring").attr("disabled", false).attr('tabindex', '0');
                                $(".swing_mooring").attr("disabled", false).attr('tabindex', '0');
                            }
                        },
                        error: function (data) {
                            console.log(data);
                        }
                    });
                }
            }


            $("#certificateForms").validate({
                errorClass: "invalid",
                rules: {
                    pilotage_type_id: "required",
                    vessel_id: "required",
                    // working_type_id: "required",
                    // file_no: "required",
                    schedule_type_id: "required",
                    pilot_id: "required",
                    local_agent: "required",
                    last_port: "required",
                    next_port: "required",
                    master_name: "required",
                    arrival_date: "required",
                    grt: "required",
                    // nrt: "required",
                    pilot_borded_at: "required",
                    pilot_left_at: "required",
                    // work_location_id: "required",
                    mooring_from_time: "required",
                    mooring_to_time: "required",
                    mooring_line_ford: "required",
                    mooring_line_aft: "required",
                    stern_power_avail: "required",
                    master_sign_date: "required",

                    "tug[0][tugId]": "required",
                    "tug[0][assistanceFrom]": "required",
                    "tug[0][assistanceTo]": "required",
                    "tug[0][isPrimary]": "required",
                    // "tug[0][workLocation]": "required",

                    // remarks: "required",
                },
                errorPlacement: function (error, element) {
                    if (element.hasClass('customError')) {
                        error.insertAfter(element.closest('div'));
                    } else {
                        element.after(error); // default error placement
                    }

                }
            });

            $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 20,
                lengthMenu: [
                    [5, 10, 20, -1],
                    [5, 10, 20, 'All']
                ],
                initComplete: function (settings, json) {
                    $('body').find('.dataTables_scrollBody').css("height", "auto");
                    $('body').find('.dataTables_scrollBody').css("max-height", "300px");
                },
                ajax: {
                    url: '{{ route('ps-certificate-entry-datatable', isset($data->id) ? $data->id : 0) }}',
                    type: 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                "columns": [{
                    "data": 'DT_RowIndex',
                    "name": 'DT_RowIndex'
                },
                    {
                        "data": "pilotage_type"
                    },
                    {
                        "data": "vessel_name"
                    },
                    {
                        "data": "cpa_pilot"
                    },
                    {
                        "data": "pilot_borded_at"
                    },
                    {
                        "data": "pilot_left_at"
                    },
                    {
                        "data": "status"
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
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
