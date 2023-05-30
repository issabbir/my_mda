@extends('layouts.default')

@section('title')
    :: Tug Cancellation Service
@endsection

@section('header-style')
    <!--Load custom style link or css-->

@endsection
@section('content')

    <div class="content-body">
        <section id="form-repeater-wrapper">
            <!-- form default repeater -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        @if(Session::has('message'))
                            <div
                                class="alert {{Session::get('m-class') ? Session::get('m-class') : 'alert-danger'}} show"
                                role="alert">
                                {{ Session::get('message') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="card-content">
                            <div class="card-body">
                                <form enctype="multipart/form-data"
                                      @if(isset($data->tc_ser_id)) action="{{route('tug-cancel-service-update',[$data->tc_ser_id])}}"
                                      @else action="{{route('tug-cancel-service-store')}}" @endif method="post">
                                    @csrf
                                    @if (isset($data->tc_ser_id))
                                        @method('PUT')
                                        <input type="hidden" id="tc_ser_id" name="tc_ser_id"
                                               value="{{isset($data->tc_ser_id) ? $data->tc_ser_id : ''}}">
                                    @endif

                                    <h5 class="card-title">Tug Cancellation Service</h5>
                                    <hr>
                                    <div class="row">
                                        {{--<div class="col-md-3 mt-1">
                                            <label class="required">Serial No</label>
                                            <div class="input-group">
                                                <input type="text" required
                                                       value="{{isset($data->serial_no) ? $data->serial_no : ''}}"
                                                       class="form-control"
                                                       id="ser_serial_no" autocomplete="off"
                                                       name="ser_serial_no"
                                                       @if(isset($data->serial_no)) readonly @endif
                                                />
                                            </div>
                                        </div>--}}
                                        <div class="col-md-3 mt-1">
                                            <label>Serial No</label>
                                            <input type="text" readonly
                                                   name="ser_serial_no" autocomplete="off"
                                                   id="ser_serial_no"
                                                   class="form-control"
                                                   value="{{isset($data->serial_no) ? $data->serial_no : 'TC'.$gen_uniq_id}}"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Vessel</label>
                                            <select class="custom-select select2 form-control vessel_id" required
                                                    name="vessel_id">
                                                @if(isset($data))
                                                    <option
                                                        value="{{$data->vessel_id}}">{{$data->vessel_name}}</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>Call Sign</label>
                                            <div class="input-group">
                                                <input type="text"
                                                       value="{{isset($data->call_sign) ? $data->call_sign : ''}}"
                                                       class="form-control"
                                                       id="call_sign" autocomplete="off"
                                                       name="call_sign" readonly
                                                />
                                            </div>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>Flag</label>
                                            <div class="input-group">
                                                <input type="text"
                                                       value="{{isset($data->flag) ? $data->flag : ''}}"
                                                       class="form-control"
                                                       id="flag" autocomplete="off"
                                                       name="flag" readonly
                                                />
                                                <input type="hidden" id="flag_id" name="flag_id"
                                                       value="{{isset($data->flag_id) ? $data->flag_id : ''}}">
                                            </div>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>Master Name</label>
                                            <div class="input-group">
                                                <input type="text"
                                                       value="{{isset($data->vessel_master) ? $data->vessel_master : ''}}"
                                                       class="form-control"
                                                       id="vessel_master" autocomplete="off"
                                                       name="vessel_master" readonly
                                                />
                                            </div>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>GRT</label>
                                            <div class="input-group">
                                                <input type="text"
                                                       value="{{isset($data->grt) ? $data->grt : ''}}"
                                                       class="form-control"
                                                       id="grt" autocomplete="off"
                                                       name="grt" readonly
                                                />
                                            </div>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>NRT</label>
                                            <div class="input-group">
                                                <input type="text"
                                                       value="{{isset($data->nrt) ? $data->nrt : ''}}"
                                                       class="form-control"
                                                       id="nrt" autocomplete="off"
                                                       name="nrt" readonly
                                                />
                                            </div>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>Max Fresh Water Draft</label>
                                            <div class="input-group">
                                                <input type="text"
                                                       value="{{isset($data->max_fresh_water_drft) ? $data->max_fresh_water_drft : ''}}"
                                                       class="form-control"
                                                       id="max_fresh_water_drft" autocomplete="off"
                                                       name="max_fresh_water_drft"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>DECK CARGO</label>
                                            <div class="input-group">
                                                <input type="text"
                                                       value="{{isset($data->deck_cargo) ? $data->deck_cargo : ''}}"
                                                       class="form-control"
                                                       id="deck_cargo" autocomplete="off"
                                                       name="deck_cargo"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Shipping Agent</label>
                                            <select class="custom-select select2 form-control agent_id" required
                                                    style="width: 100% !important;"
                                                    id="agent_id" name="agent_id">
                                                @if(isset($data))
                                                    <option
                                                        value="{{$data->agent_id}}">{{$data->agent_name}}</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Pilot</label>
                                            <select class="custom-select select2 form-control pilot_id" required
                                                    style="width: 100% !important;"
                                                    id="pilot_id" name="pilot_id">
                                                @if(isset($data))
                                                    <option
                                                        value="{{$data->pilot_id}}">{{$data->pilot_name}}</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <div class="form-group">
                                                <label class="required">borded</label>
                                                <div class="input-group date" id="bordedAt" data-target-input="nearest">
                                                    <div class="input-group-append" data-target="#bordedAt"
                                                         data-toggle="datetimepicker">
                                                        <div class="input-group-text">
                                                            <i class="bx bx-calendar"></i>
                                                        </div>
                                                    </div>
                                                    <input type="text" required
                                                           name="pilot_borded_at" autocomplete="off"
                                                           value="{{ old('pilot_borded_at', isset($data->boarded_at) ?  date('Y-m-d H:i', strtotime($data->boarded_at)) : '' ) }}"
                                                           class="form-control datetimepicker-input customError"
                                                           data-target="#bordedAt"
                                                           data-toggle="datetimepicker">
                                                </div>
                                                @if($errors->has('pilot_borded_at'))
                                                    <span
                                                        class="help-block">{{ $errors->first('pilot_borded_at') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <div class="form-group">
                                                <label class="required">left</label>
                                                <div class="input-group date" id="leftAt" data-target-input="nearest">
                                                    <div class="input-group-append" data-target="#leftAt"
                                                         data-toggle="datetimepicker">
                                                        <div class="input-group-text">
                                                            <i class="bx bx-calendar"></i>
                                                        </div>
                                                    </div>
                                                    <input type="text" required
                                                           name="pilot_left_at" autocomplete="off"
                                                           value="{{ old('pilot_left_at', isset($data->left_at) ?  date('Y-m-d H:i', strtotime($data->left_at)) : '' ) }}"
                                                           {{--value="{{ old('pilot_left_at', isset($data->pilot_left_at) ?  date('Y-m-d H:i', strtotime($data->pilot_left_at)) : date('Y-m-d H:i') ) }}"--}}
                                                           class="form-control datetimepicker-input customError"
                                                           data-target="#leftAt"
                                                           data-toggle="datetimepicker">
                                                </div>
                                                @if($errors->has('pilot_left_at'))
                                                    <span
                                                        class="help-block">{{ $errors->first('pilot_left_at') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Canceled Movement From Jetty</label>
                                            <select class="custom-select select2 form-control canceled_from_id" required
                                                    name="canceled_from_id">
                                                @if(isset($data))
                                                    <option
                                                        value="{{$data->canceled_from_id}}">{{$data->canceled_from_name}}</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Canceled Movement To Jetty</label>
                                            <select class="custom-select select2 form-control canceled_to_id" required
                                                    name="canceled_to_id">
                                                @if(isset($data))
                                                    <option
                                                        value="{{$data->canceled_to_id}}">{{$data->canceled_to_name}}</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <div class="form-group">
                                                <label class="required">Canceled At</label>
                                                <div class="input-group date" id="canceledAt"
                                                     data-target-input="nearest">
                                                    <div class="input-group-append" data-target="#canceledAt"
                                                         data-toggle="datetimepicker">
                                                        <div class="input-group-text">
                                                            <i class="bx bx-calendar"></i>
                                                        </div>
                                                    </div>
                                                    <input type="text" required
                                                           name="canceled_at" autocomplete="off"
                                                           value="{{ old('canceled_at', isset($data->canceled_at) ?  date('Y-m-d H:i', strtotime($data->canceled_at)) : '' ) }}"
                                                           class="form-control datetimepicker-input customError"
                                                           data-target="#canceledAt"
                                                           data-toggle="datetimepicker">
                                                </div>
                                                @if($errors->has('canceled_at'))
                                                    <span class="help-block">{{ $errors->first('canceled_at') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-1">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label>Whether appropriate Port Authority informed of the
                                                        cancellation</label>
                                                    <ul class="list-unstyled mb-0">
                                                        <li class="d-inline-block mr-2 mb-1">
                                                            <fieldset>
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" class="custom-control-input"
                                                                           value="Y"
                                                                           {{isset($data->whether_app_port) && $data->whether_app_port == 'Y' ? 'checked' : ''}} name="whether_app_port"
                                                                           id="customRadio1">
                                                                    <label class="custom-control-label"
                                                                           for="customRadio1">Yes</label>
                                                                </div>
                                                            </fieldset>
                                                        </li>
                                                        <li class="d-inline-block mr-2 mb-1">
                                                            <fieldset>
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" class="custom-control-input"
                                                                           value="N"
                                                                           {{isset($data->whether_app_port) && $data->whether_app_port == 'N' ? 'checked' : ''}} name="whether_app_port"
                                                                           id="customRadio2">
                                                                    <label class="custom-control-label"
                                                                           for="customRadio2">No</label>
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
                                        <div class="col-md-6 mt-1">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label>Whether movement cancelled after the Pilot boarded the
                                                        Vessel</label>
                                                    <ul class="list-unstyled mb-0">
                                                        <li class="d-inline-block mr-2 mb-1">
                                                            <fieldset>
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" class="custom-control-input"
                                                                           value="Y"
                                                                           {{isset($data->whether_move_cancel) && $data->whether_move_cancel == 'Y' ? 'checked' : ''}} name="whether_move_cancel"
                                                                           id="customRadio3">
                                                                    <label class="custom-control-label"
                                                                           for="customRadio3">Yes</label>
                                                                </div>
                                                            </fieldset>
                                                        </li>
                                                        <li class="d-inline-block mr-2 mb-1">
                                                            <fieldset>
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" class="custom-control-input"
                                                                           value="N"
                                                                           {{isset($data->whether_move_cancel) && $data->whether_move_cancel == 'N' ? 'checked' : ''}} name="whether_move_cancel"
                                                                           id="customRadio4">
                                                                    <label class="custom-control-label"
                                                                           for="customRadio4">No</label>
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
                                        <div class="col-md-3 mt-1">
                                            <div class="row my-1">
                                                <div class="col-md-12">
                                                    <label>Date of last visit of this Port</label>
                                                    <div class="input-group date"
                                                         onfocusout="$(this).datetimepicker('hide')"
                                                         id="date_of_last_visit"
                                                         data-target-input="nearest">
                                                        <input type="text" name="date_of_last_visit" autocomplete="off"
                                                               value="{{isset($data->date_of_last_visit) ? $data->date_of_last_visit : ''}}"
                                                               class="form-control alongsideDate"
                                                               data-target="#date_of_last_visit"
                                                               data-toggle="datetimepicker"
                                                               oninput="this.value = this.value.toUpperCase()"/>
                                                        <div class="input-group-append"
                                                             data-target="#date_of_last_visit"
                                                             data-toggle="datetimepicker">
                                                            <div class="input-group-text">
                                                                <i class="bx bx-calendar"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <fieldset class="border mt-2 col-md-12">
                                            <legend class="w-auto" style="font-size: 15px;">As a result of the above,
                                                the following arrangement made were also cancelled
                                                (Particulars to be provided by the Dy. Conservator's Office.)
                                            </legend>
                                            <div class="row">
                                                <div class="col-md-4 mb-1">
                                                    <label>Port Authority Tug Name</label>
                                                    <select
                                                        class="custom-select select2 form-control tug_id"
                                                        id="tug_id" name="tug_id">
                                                        @if(isset($data))
                                                            <option
                                                                value="{{$data->port_auth_tug_id}}">{{$data->port_auth_tug_name}}</option>
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                        <div class="col-md-12">
                                                            <label>From Time</label>
                                                            <div class="input-group date"
                                                                 onfocusout="$(this).datetimepicker('hide')" id="port_auth_from_hrs"
                                                                 data-target-input="nearest">
                                                                <input type="text" name="port_auth_from_hrs"
                                                                       value="{{ old('port_auth_from_hrs', isset($data->port_auth_from_hrs) ? date('H:i',strtotime($data->port_auth_from_hrs)) : '') }}"
                                                                       class="form-control pilotageTime"
                                                                       data-target="#port_auth_from_hrs" data-toggle="datetimepicker"
                                                                       placeholder="From time" autocomplete="off"
                                                                       oninput="this.value = this.value.toUpperCase()"/>
                                                                <div class="input-group-append" data-target="#port_auth_from_hrs"
                                                                     data-toggle="datetimepicker">
                                                                    <div class="input-group-text">
                                                                        <i class="bx bx-calendar"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                        <div class="col-md-12">
                                                            <label>To Time</label>
                                                            <div class="input-group date"
                                                                 onfocusout="$(this).datetimepicker('hide')" id="port_auth_to_hrs"
                                                                 data-target-input="nearest">
                                                                <input type="text" name="port_auth_to_hrs"
                                                                       value="{{ old('port_auth_to_hrs', isset($data->port_auth_to_hrs) ? date('H:i',strtotime($data->port_auth_to_hrs)) : '') }}"
                                                                       class="form-control pilotageTime"
                                                                       data-target="#port_auth_to_hrs" data-toggle="datetimepicker"
                                                                       placeholder="To time" autocomplete="off"
                                                                       oninput="this.value = this.value.toUpperCase()"/>
                                                                <div class="input-group-append" data-target="#port_auth_to_hrs"
                                                                     data-toggle="datetimepicker">
                                                                    <div class="input-group-text">
                                                                        <i class="bx bx-calendar"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mb-1">
                                                    <label>Launches Name</label>
                                                    <div class="input-group">
                                                        <input type="text"
                                                               value="{{isset($data->launches_name) ? $data->launches_name : ''}}"
                                                               class="form-control"
                                                               id="launches_name" autocomplete="off"
                                                               name="launches_name"
                                                        />
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="col-md-12">
                                                        <label>From Time</label>
                                                        <div class="input-group date"
                                                             onfocusout="$(this).datetimepicker('hide')" id="launches_from_hrs"
                                                             data-target-input="nearest">
                                                            <input type="text" name="launches_from_hrs"
                                                                   value="{{ old('launches_from_hrs', isset($data->launches_from_hrs) ? date('H:i',strtotime($data->launches_from_hrs)) : '') }}"
                                                                   class="form-control pilotageTime"
                                                                   data-target="#launches_from_hrs" data-toggle="datetimepicker"
                                                                   placeholder="From time" autocomplete="off"
                                                                   oninput="this.value = this.value.toUpperCase()"/>
                                                            <div class="input-group-append" data-target="#launches_from_hrs"
                                                                 data-toggle="datetimepicker">
                                                                <div class="input-group-text">
                                                                    <i class="bx bx-calendar"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="col-md-12">
                                                        <label>To Time</label>
                                                        <div class="input-group date"
                                                             onfocusout="$(this).datetimepicker('hide')" id="launches_to_hrs"
                                                             data-target-input="nearest">
                                                            <input type="text" name="launches_to_hrs"
                                                                   value="{{ old('launches_to_hrs', isset($data->launches_to_hrs) ? date('H:i',strtotime($data->launches_to_hrs)) : '') }}"
                                                                   class="form-control pilotageTime"
                                                                   data-target="#launches_to_hrs" data-toggle="datetimepicker"
                                                                   placeholder="To time" autocomplete="off"
                                                                   oninput="this.value = this.value.toUpperCase()"/>
                                                            <div class="input-group-append" data-target="#launches_to_hrs"
                                                                 data-toggle="datetimepicker">
                                                                <div class="input-group-text">
                                                                    <i class="bx bx-calendar"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mb-1">
                                                    <label>Hawser Boats</label>
                                                    <div class="input-group">
                                                        <input type="text"
                                                               value="{{isset($data->hawser_boats_name) ? $data->hawser_boats_name : ''}}"
                                                               class="form-control"
                                                               id="hawser_boats_name" autocomplete="off"
                                                               name="hawser_boats_name"
                                                        />
                                                        <div class="input-group-text">
                                                            Nos.
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="col-md-12">
                                                        <label>From Time</label>
                                                        <div class="input-group date"
                                                             onfocusout="$(this).datetimepicker('hide')" id="hawser_boats_from_hrs"
                                                             data-target-input="nearest">
                                                            <input type="text" name="hawser_boats_from_hrs"
                                                                   value="{{ old('hawser_boats_from_hrs', isset($data->hawser_boats_from_hrs) ? date('H:i',strtotime($data->hawser_boats_from_hrs)) : '') }}"
                                                                   class="form-control pilotageTime"
                                                                   data-target="#hawser_boats_from_hrs" data-toggle="datetimepicker"
                                                                   placeholder="From time" autocomplete="off"
                                                                   oninput="this.value = this.value.toUpperCase()"/>
                                                            <div class="input-group-append" data-target="#hawser_boats_from_hrs"
                                                                 data-toggle="datetimepicker">
                                                                <div class="input-group-text">
                                                                    <i class="bx bx-calendar"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="col-md-12">
                                                        <label>To Time</label>
                                                        <div class="input-group date"
                                                             onfocusout="$(this).datetimepicker('hide')" id="hawser_boats_to_hrs"
                                                             data-target-input="nearest">
                                                            <input type="text" name="hawser_boats_to_hrs"
                                                                   value="{{ old('hawser_boats_to_hrs', isset($data->hawser_boats_to_hrs) ? date('H:i',strtotime($data->hawser_boats_to_hrs)) : '') }}"
                                                                   class="form-control pilotageTime"
                                                                   data-target="#hawser_boats_to_hrs" data-toggle="datetimepicker"
                                                                   placeholder="To time" autocomplete="off"
                                                                   oninput="this.value = this.value.toUpperCase()"/>
                                                            <div class="input-group-append" data-target="#hawser_boats_to_hrs"
                                                                 data-toggle="datetimepicker">
                                                                <div class="input-group-text">
                                                                    <i class="bx bx-calendar"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mb-1">
                                                    <label>Mooring Gangs</label>
                                                    <div class="input-group">
                                                        <input type="text"
                                                               value="{{isset($data->mooring_gangs_name) ? $data->mooring_gangs_name : ''}}"
                                                               class="form-control"
                                                               id="mooring_gangs_name" autocomplete="off"
                                                               name="mooring_gangs_name"
                                                        />
                                                        <div class="input-group-text">
                                                            Nos.
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="col-md-12">
                                                        <label>From Time</label>
                                                        <div class="input-group date"
                                                             onfocusout="$(this).datetimepicker('hide')" id="mooring_gangs_from_hrs"
                                                             data-target-input="nearest">
                                                            <input type="text" name="mooring_gangs_from_hrs"
                                                                   value="{{ old('mooring_gangs_from_hrs', isset($data->mooring_gangs_from_hrs) ? date('H:i',strtotime($data->mooring_gangs_from_hrs)) : '') }}"
                                                                   class="form-control pilotageTime"
                                                                   data-target="#mooring_gangs_from_hrs" data-toggle="datetimepicker"
                                                                   placeholder="From time" autocomplete="off"
                                                                   oninput="this.value = this.value.toUpperCase()"/>
                                                            <div class="input-group-append" data-target="#mooring_gangs_from_hrs"
                                                                 data-toggle="datetimepicker">
                                                                <div class="input-group-text">
                                                                    <i class="bx bx-calendar"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="col-md-12">
                                                        <label>To Time</label>
                                                        <div class="input-group date"
                                                             onfocusout="$(this).datetimepicker('hide')" id="mooring_gangs_to_hrs"
                                                             data-target-input="nearest">
                                                            <input type="text" name="mooring_gangs_to_hrs"
                                                                   value="{{ old('mooring_gangs_to_hrs', isset($data->mooring_gangs_to_hrs) ? date('H:i',strtotime($data->mooring_gangs_to_hrs)) : '') }}"
                                                                   class="form-control pilotageTime"
                                                                   data-target="#mooring_gangs_to_hrs" data-toggle="datetimepicker"
                                                                   placeholder="To time" autocomplete="off"
                                                                   oninput="this.value = this.value.toUpperCase()"/>
                                                            <div class="input-group-append" data-target="#mooring_gangs_to_hrs"
                                                                 data-toggle="datetimepicker">
                                                                <div class="input-group-text">
                                                                    <i class="bx bx-calendar"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                        @include('mda.mservice.partial.attachment')
                                    </div>
                                    <div class="form-group mt-1">
                                        <div class="col-md-12 pr-0 d-flex justify-content-end">
                                            <div class="form-group">
                                                <button type="submit" name="save"
                                                        onclick="return confirm('Are you sure?')"
                                                        class="btn btn btn-dark shadow mr-1 mb-1">  {{ isset($data->tc_ser_id)?'Update':'Save' }} </button>
                                                <a type="reset" href="{{route("fixed-mooring-service")}}"
                                                   class="btn btn btn-outline-dark  mb-1"> Cancel</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ form default repeater -->

        </section>
    </div>

    @include('mda.mservice.tc.list')

@endsection

@section('footer-script')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        let vtmisVessel = '{{route('get-tug-vessel')}}';
        let jettyList = '{{route('get-jetty-list')}}';
        let shippingAgent = '{{route('get-shipping-agent')}}';
        let pilotList = '{{route('get-pilot-list')}}';
        let tugList = '{{route('get-tug-list')}}';

        $('.vessel_id').select2({
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
                    console.log(data);
                    var formattedResults = $.map(data, function (obj, idx) {
                        // console.log(formattedResults);
                        // obj.id = obj.id;
                        obj.id = obj.vessel_id;
                        obj.text = obj.vessel_name;
                        return obj;
                    });
                    return {
                        results: formattedResults,
                    };
                }
            }
        });

        $('.vessel_id').on('change', function () {
            let url = '{{route('get-vessel-info')}}';
            let vessel_id = $(this).find(":selected").val();
            $.ajax({
                type: 'GET',
                url: url,
                data: {vessel_id: vessel_id},
                success: function (msg) {console.log(msg.result)
                    $('#call_sign').val(msg.result.vessel_call_sign);
                    $('#flag').val(msg.result.country);
                    $('#vessel_master').val(msg.result.master_name);
                    $('#grt').val(msg.result.grt);
                    $('#nrt').val(msg.result.nrt);
                    //$('#deck_cargo').val(msg.result.deck_cargo);
                }
            });
        });

        $('.agent_id').select2({
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

        $('.pilot_id').select2({
            placeholder: "Select one",
            ajax: {
                url: pilotList,
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

        $('.canceled_from_id').select2({
            placeholder: "Select one",
            ajax: {
                url: jettyList,
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
                        obj.text = obj.jetty_name;
                        return obj;
                    });
                    return {
                        results: formattedResults,
                    };
                }
            }
        });

        $('.canceled_to_id').select2({
            placeholder: "Select one",
            ajax: {
                url: jettyList,
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
                        obj.text = obj.jetty_name;
                        return obj;
                    });
                    return {
                        results: formattedResults,
                    };
                }
            }
        });

        $('.tug_id').select2({
            placeholder: "Select one",
            ajax: {
                url: tugList,
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

        function tcServiceList() {
            let url = '{{route('tug-cancel-service-datatable')}}';
            let oTable = $('#searchResultTable').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                    url: url,
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'serial_no', name: 'serial_no', searchable: true},
                    {data: 'vessel_name', name: 'vessel_name', searchable: true},
                    {data: 'agent_name', name: 'agent_name', searchable: true},
                    {data: 'pilot_name', name: 'pilot_name', searchable: true},
                    {data: 'action', name: 'action', searchable: false},
                ]
            });
        };

        function msToTime(duration) {
            var milliseconds = parseInt((duration % 1000) / 100),
                seconds = Math.floor((duration / 1000) % 60),
                minutes = Math.floor((duration / (1000 * 60)) % 60),
                hours = Math.floor((duration / (1000 * 60 * 60)) % 24);

            hours = (hours < 10) ? "0" + hours : hours;
            minutes = (minutes < 10) ? "0" + minutes : minutes;
            seconds = (seconds < 10) ? "0" + seconds : seconds;

            return hours + ":" + minutes;
        }

        function setExpire() {
            var date1 = new Date($('.alongsideDate').val() + " " + $('.fromTime').val());
            var date2 = new Date($('.sailDate').val() + " " + $('.toTime').val());
            console.log((date2 - date1));
            $('#total_used_time').val(msToTime(date2 - date1));
        }

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

        $(document).ready(function () {
            dateTime("#bordedAt");
            dateTime("#leftAt");
            dateTime("#canceledAt");
            datePicker("#date_of_last_visit");
            timePicker24("#port_auth_from_hrs");
            timePicker24("#port_auth_to_hrs");
            timePicker24("#launches_from_hrs");
            timePicker24("#launches_to_hrs");
            timePicker24("#hawser_boats_from_hrs");
            timePicker24("#hawser_boats_to_hrs");
            timePicker24("#mooring_gangs_from_hrs");
            timePicker24("#mooring_gangs_to_hrs");
            //$(".customError").val('');
            window.setTimeout(function () {
                $(".alert").fadeTo(500, 0).slideUp(500, function () {
                    $(this).remove();
                });
            }, 4000);
            tcServiceList();
        });

        $(".add-row-doc").click(function () {

            let doc_name = $("#case_doc_name").val();
            let converted_file = $("#converted_file").val();

            let filePath = $("#attachedFile").val();
            let file_ext = filePath.substr(filePath.lastIndexOf('.') + 1, filePath.length);
            let fileName = document.getElementById('attachedFile').files[0].name;

            let markup = "<tr><td><input type='checkbox' name='record'>" +
                "<input type='hidden' name='doc_name[]' value='" + doc_name + "'>" +
                "<input type='hidden' name='doc_type[]' value='" + file_ext + "'>" +
                "<input type='hidden' name='doc[]' value='" + converted_file + "'>" +
                "</td><td>" + doc_name + "</td><td><i class='bx bxs-file cursor-pointer'></i></td></tr>";
            $("#case_doc_name").val("");
            $("#attachedFile").val("");
            $("#table-doc tbody").append(markup);
        });

        $(".delete-row-file").click(function () {
            $("#table-doc tbody").find('input[name="record"]').each(function () {
                if ($(this).is(":checked")) {
                    let doc_id = $(this).closest('tr').find('.doc_id').val();
                    if (doc_id != null) {
                        //$(this).parents("tr").remove();
                        let url = '{{route('docRemove')}}';
                        Swal.fire({
                            title: 'Are you sure?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                            if (result.value) {
                                $.ajax({
                                    type: 'GET',
                                    url: url,
                                    data: {doc_id: doc_id},
                                    success: function (msg) {//console.log(msg)
                                        if (msg == 'success') {
                                            $(this).parents("tr").remove();
                                            Swal.fire({
                                                title: 'Entry Successfully Deleted!',
                                                icon: 'success',
                                                confirmButtonText: 'OK'
                                            }).then(function () {
                                                $('td input:checked').closest('tr').remove();
                                            });
                                        } else {
                                            Swal.fire({
                                                title: 'Something Went Wrong!!.',
                                                icon: 'error',
                                                confirmButtonText: 'OK'
                                            });
                                        }
                                    }
                                });
                            }
                        });


                        /*$(this).parents("tr").remove();
                        let url = '{{route('docRemove')}}';
                        $.ajax({
                            type: 'GET',
                            url: url,
                            data: {doc_id: doc_id},
                            success: function (msg) {
                                $(this).parents("tr").remove();
                                Swal.fire({
                                    title: 'Successfully Deleted!',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(function () {
                                    //location.reload();

                                });
                            }
                        });*/
                    } else {
                        $(this).parents("tr").remove();
                    }
                    $("#attach_count").val('0');
                }
            });
        });
    </script>

@endsection

