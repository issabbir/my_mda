<?php
/**
 * Created by PhpStorm.
 * User: Sohel
 * Date: 17/12/2020
 * Time: 1:07 PM
 */
?>

<form id="searchResultPeriodGridList" method="post"
      @if(isset($data['insertedData']->fuel_limit_id))
      action="{{ route('fuel-limit-update', ['id' => $data['insertedData']->fuel_limit_id]) }}">
    <input name="_method" type="hidden" value="PUT">
    @else
        action="{{ route('fuel-limit-store') }}">
    @endif
    {{ csrf_field() }}
    <div class="row">
        <div class="col-12">
            <div class="row my-1">
                <div class="col-md-3">
                    <label for="office_order_no" class="required">OFFICE ORDER NO.:</label>
                    <input type="text"
                           value="{{isset($data['insertedData']->office_order_no) ? $data['insertedData']->office_order_no : ''}}"
                           class="form-control"
                           id="office_order_no"
                           name="office_order_no" placeholder="Office Order No."
                           required
                    />
                    <span class="text-danger"></span>
                </div>

                <div class="col-md-3">
                    <label for="office_order_date" class="required">OFFICE ORDER DATE:</label>
                    <div class="input-group date" id="officeOrderDatePicker" data-target-input="nearest">
                        <input type="text"
                               value="{{isset($data['insertedData']->office_order_date) ? date('d-m-Y h:i A', strtotime($data['insertedData']->office_order_date)) : ''}}"
                               class="form-control datetimepicker-input pickadate"
                               data-toggle="datetimepicker" data-target="#officeOrderDatePicker"
                               id="office_order_date" placeholder="Select Date"
                               name="office_order_date"
                               autocomplete="off"
                               required
                        />
                        <div class="form-control-position">
                            <i class="bx bx-calendar"></i>
                        </div>
                    </div>
                    <span class="text-danger"></span>
                </div>

                <div class="col-md-3">
                    <label for="ministry_order">MINISTRY ORDER:</label>
                    <input type="text"
                           value="{{isset($data['insertedData']->ministry_order) ? $data['insertedData']->ministry_order : ''}}"
                           class="form-control"
                           id="ministry_order" placeholder="Ministry Order No."
                           name="ministry_order"
                    />
                    <span class="text-danger"></span>
                </div>

                <div class="col-md-3">
                    <label for="ministry_order_date">MINISTRY ORDER DATE:</label>
                    <div class="input-group date" id="ministryOrderDatePicker" data-target-input="nearest">
                        <input type="text"
                               value="{{isset($data['insertedData']->ministry_order_date) ? date('d-m-Y h:i A', strtotime($data['insertedData']->ministry_order_date)) : ''}}"
                               class="form-control datetimepicker-input"
                               data-toggle="datetimepicker" data-target="#ministryOrderDatePicker"
                               id="ministry_order_date"
                               name="ministry_order_date" placeholder="Select Date"
                               autocomplete="off"
                        />
                        <div class="form-control-position">
                            <i class="bx bx-calendar"></i>
                        </div>
                    </div>
                    <span class="text-danger"></span>
                </div>
            </div>

            <div class="row my-1">
                <div class="col-md-3">
                    <label for="board_meeting_no">Board Meeting No.:</label>
                    <input type="text"
                           value="{{isset($data['insertedData']->board_meeting_no) ? $data['insertedData']->board_meeting_no : ''}}"
                           class="form-control"
                           id="board_meeting_no" placeholder="Board Meeting No."
                           name="board_meeting_no"
                    />
                    <span class="text-danger"></span>
                </div>

                <div class="col-md-3">
                    <label for="office_order_date">Board Meeting Date:</label>
                    <div class="input-group date" id="boardMeetingDatePicker" data-target-input="nearest">
                        <input type="text"
                               value="{{isset($data['insertedData']->board_meeting_date) ? date('d-m-Y h:i A', strtotime($data['insertedData']->board_meeting_date)) : ''}}"
                               class="form-control datetimepicker-input"
                               data-toggle="datetimepicker" data-target="#boardMeetingDatePicker"
                               id="board_meeting_date"
                               name="board_meeting_date" placeholder="Select Date"
                               autocomplete="off"
                        />
                        <div class="form-control-position">
                            <i class="bx bx-calendar"></i>
                        </div>
                    </div>
                    <span class="text-danger"></span>
                </div>

                <div class="col-md-3">
                    <label for="work_type_id" class="required">WORK TYPE:</label>
                    <select required
                            class="custom-select select2"
                            name="work_type_id"
                            id="work_type_id">
                        @if(isset($data['get_work_type_list']))
                            @foreach($data['get_work_type_list'] as $option)
                                {!!$option!!}
                            @endforeach
                        @endif
                    </select>
                    <span class="text-danger"></span>
                </div>

                <div class="col-md-3">
                    <label for="engine_type_id" class="required">Engine TYPE:</label>
                    <select required
                            class="custom-select select2"
                            name="engine_type_id"
                            id="engine_type_id">
                        @if(isset($data['get_engine_type_list']))
                            @foreach($data['get_engine_type_list'] as $option)
                                {!!$option!!}
                            @endforeach
                        @endif
                    </select>
                    <span class="text-danger"></span>
                </div>
            </div>

            <div class="row my-1">
                <div class="col-md-3">
                    <label for="main_fuel_id" class="required">Fuel Type:</label>
                    <select required
                            class="custom-select select2"
                            name="main_fuel_id"
                            id="main_fuel_id">
                        @if(isset($data['get_fuel_types']))
                            @foreach($data['get_fuel_types'] as $option)
                                {!!$option!!}
                            @endforeach
                        @endif
                    </select>
                    <span class="text-danger"></span>
                </div>

                <div class="col-md-3">
                    <label for="qty" class="required">Fuel Quantity:</label>
                    <input type="number"
                           value="{{isset($data['insertedData']->qty) ? $data['insertedData']->qty : 0}}"
                           class="form-control"
                           id="qty"
                           name="qty"
                           placeholder="Fuel Quantity"
                           required
                    />
                    <span class="text-danger"></span>
                </div>

                <div class="col-md-3">
                    <label for="qty_unit_id" class="required">Fuel Quantity Unit:</label>
                    <select required
                            class="custom-select select2"
                            name="qty_unit_id"
                            id="qty_unit_id">
                        @if(isset($data['get_fuel_unit_list']))
                            @foreach($data['get_fuel_unit_list'] as $option)
                                {!!$option!!}
                            @endforeach
                        @endif
                    </select>
                    <span class="text-danger"></span>
                </div>

                <div class="col-md-3">
                    <label for="refuel_frequency_id" class="required">REFUEL FREQUENCY:</label>
                    <select required
                            class="custom-select select2"
                            name="refuel_frequency_id"
                            id="refuel_frequency_id">
                        @if(isset($data['get_refuel_frequency_list']))
                            @foreach($data['get_refuel_frequency_list'] as $option)
                                {!!$option!!}
                            @endforeach
                        @endif
                    </select>
                    <span class="text-danger"></span>
                </div>
            </div>

            <div class="row my-1">
                <div class="col-md-3">
                    <label for="active_from" class="required">ACTIVE FROM:</label>
                    <div class="input-group date" id="activeFromDatePicker" data-target-input="nearest">
                        <input type="text"
                               value="{{isset($data['insertedData']->active_from) ? date('d-m-Y h:i A', strtotime($data['insertedData']->active_from)) : ''}}"
                               class="form-control datetimepicker-input"
                               data-toggle="datetimepicker" data-target="#activeFromDatePicker"
                               id="active_from"
                               name="active_from"  placeholder="Select Date"
                               autocomplete="off"
                               required
                        />
                        <div class="form-control-position">
                            <i class="bx bx-calendar"></i>
                        </div>
                    </div>
                    <span class="text-danger"></span>
                </div>

                <div class="col-md-3">
                    <label for="active_to">ACTIVE TO:</label>
                    <div class="input-group date" id="activeToDatePicker" data-target-input="nearest">
                        <input type="text"
                               value="{{isset($data['insertedData']->active_to) ? date('d-m-Y h:i A', strtotime($data['insertedData']->active_to)) : ''}}"
                               class="form-control datetimepicker-input"
                               data-toggle="datetimepicker" data-target="#activeToDatePicker"
                               id="active_to"
                               name="active_to"  placeholder="Select Date"
                               autocomplete="off"
                        />
                        <div class="form-control-position">
                            <i class="bx bx-calendar"></i>
                        </div>
                    </div>
                    <span class="text-danger"></span>
                </div>

                <div class="col-md-3">
                    <label for="single_shift_yn_yes" class="required">SINGLE SHIFT:</label>
                    <div>
                        </br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="single_shift_yn"
                                   required id="single_shift_yn_yes" value="Y" checked
                                   @if(isset($data['insertedData']->single_shift_yn) && $data['insertedData']->single_shift_yn == "Y")
                                   checked
                                @endif
                            />
                            <label class="form-check-label"
                                   for="single_shift_yn_yes">YES</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="single_shift_yn"
                                   required id="single_shift_yn_no" value="N"
                                   @if(isset($data['insertedData']->single_shift_yn) && $data['insertedData']->single_shift_yn == "N")
                                   checked
                                @endif
                            />
                            <label class="form-check-label"
                                   for="single_shift_yn_no">NO</label>
                        </div>
                        <span class="text-danger"></span>
                    </div>
                </div>

                <div class="col-md-3">
                    <label>&nbsp;</label>
                    <div class="d-flex justify-content-end col">
                        <button type="submit" id="submit" class="btn btn btn-dark shadow mr-1 mb-1 btn-secondary">
                            @if(empty($data['insertedData']->fuel_limit_id))
                                Save
                            @else
                                Update
                            @endif
                        </button> &nbsp;

                        @if(empty($data['insertedData']->fuel_limit_id))
                            <button type="reset" class="btn btn-light-secondary mb-1">
                                Reset
                            </button>
                        @else
                            <a href="{{route('fuel-limit-index')}}">
                                <button type="button" class="btn btn-light-secondary mb-1">
                                    Reset
                                </button>
                            </a>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</form>


