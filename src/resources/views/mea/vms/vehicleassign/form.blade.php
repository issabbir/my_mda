<?php
/**
 * Created by PhpStorm.
 * User: salman
 * Date: 6/6/2020
 * Time: 9:00 PM
 */
?>
<form id="searchResultPeriodGridList" method="post" enctype="multipart/form-data"
      @if(isset($data['insertedData']->assignment_id))
      action="{{ route('vehicle-assign-update', ['id' => $data['insertedData']->assignment_id]) }}">
    <input name="_method" type="hidden" value="PUT">
    @else
        action="{{ route('vehicle-assign-store') }}">
    @endif
    {{ csrf_field() }}
    <div class="row justify-content-center">
        <div class="col-12">

            <div class="row">
                <div class="col-sm-6">

                    <fieldset class="border col-sm-12">
                        <legend class="w-auto required" style="font-size: 14px;"> Vehicle Information</legend>

                        <div class="row my-1">
                            <div class="col-md-6">
                                <label class="required">VEHICLE REG NO.:</label>
                                <select required
                                        class="custom-select select2"
                                        name="vehicle_reg_no"
                                        id="vehicle_reg_no">
                                    @if(isset($data['get_vehicle_reg_no_list']))
                                        @foreach($data['get_vehicle_reg_no_list'] as $option)
                                            {!!$option!!}
                                        @endforeach
                                    @endif
                                </select>
                                <span class="text-danger"></span>
                            </div>
                            <div class="col-md-6">
                                <label class="required">Chassis No.:</label>
                                <input type="text"
                                       value="{{isset($data['insertedData']->chassis_no) ? $data['insertedData']->chassis_no : ''}}"
                                       class="form-control"
                                       id="chassis_no"
                                       name="chassis_no"
                                       placeholder="Chassis No."
                                       required
                                />
                                <span class="text-danger"></span>
                            </div>
                        </div>
                        <div class="row my-1">
                            <div class="col-md-6">
                                <label class="required">Engine No.:</label>
                                <input type="text"
                                       value="{{isset($data['insertedData']->engine_no) ? $data['insertedData']->engine_no : ''}}"
                                       class="form-control"
                                       id="engine_no"
                                       name="engine_no"
                                       placeholder="Engine No."
                                       required
                                />
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-6">
                                <label>Vehicle CPA Reg. No.:</label>
                                <input type="text"
                                       value="{{isset($data['insertedData']->vehicle_cpa_no) ? $data['insertedData']->vehicle_cpa_no : ''}}"
                                       class="form-control"
                                       id="vehicle_cpa_no"
                                       name="vehicle_cpa_no"
                                       placeholder="Vehicle CPA No."

                                />
                                <span class="text-danger"></span>
                            </div>

                        </div>
                        <div class="row my-1">
                            <div class="col-md-6">
                                <label>Vehicle Model Name:</label>
                                <input type="text"
                                       value="{{isset($data['insertedData']->model_name) ? $data['insertedData']->model_name : ''}}"
                                       class="form-control"
                                       id="model_name"
                                       name="model_name"
                                       placeholder="Vehicle Model Name"

                                />
                                <span class="text-danger"></span>
                            </div>
                            <div class="col-md-6">
                                <label class="required">No. of Seats:</label>
                                <input type="text"
                                       value="{{isset($data['insertedData']->no_of_seats) ? $data['insertedData']->no_of_seats : ''}}"
                                       class="form-control"
                                       id="no_of_seats"
                                       name="no_of_seats"
                                       placeholder="No. of Seats"
                                       required
                                />
                                <span class="text-danger"></span>
                            </div>

                        </div>
                        <div class="row my-1">
                            <div class="col-md-6">
                                <label>Vehicle Class:</label>
                                {{--<select
                                        class="custom-select select2"
                                        name="vehicle_class_id"
                                        id="vehicle_class_id">
                                    @if(isset($data['get_vehicle_class']))
                                        @foreach($data['get_vehicle_class'] as $option)
                                            {!!$option!!}
                                        @endforeach
                                    @endif
                                </select>--}}
                                <input type="text"
                                       value="{{isset($data['insertedData']->vehicle_class_name) ? $data['insertedData']->vehicle_class_name : ''}}"
                                       class="form-control"
                                       id="vehicle_class_name"
                                       name="vehicle_class_name"
                                       placeholder="Vehicle Class "
                                       required
                                />
                                <span class="text-danger"></span>
                            </div>
                            <div class="col-md-6">
                                <label>Vehicle Type:</label>
                                {{--<select
                                        class="custom-select select2"
                                        name="vehicle_type_id"
                                        id="vehicle_type_id">
                                    @if(isset($data['get_vehicle_type']))
                                        @foreach($data['get_vehicle_type'] as $option)
                                            {!!$option!!}
                                        @endforeach
                                    @endif
                                </select>--}}
                                <input type="text"
                                       value="{{isset($data['insertedData']->vehicle_type_name) ? $data['insertedData']->vehicle_type_name : ''}}"
                                       class="form-control"
                                       id="vehicle_type_name"
                                       name="vehicle_type_name"
                                       placeholder="Vehicle type"
                                       required
                                />
                                <span class="text-danger"></span>
                            </div>

                        </div>
                    </fieldset>
                </div>


                <div class="col-sm-6">

                    <fieldset class="border col-sm-12">
                        <legend class="w-auto required" style="font-size: 14px;"> Driver Information</legend>

                        <div class="row my-1">
                            <div class="col-md-6">
                                <label class="required">Driver CPA Reg. No:</label>
                                <select required
                                        class="custom-select select2"
                                        name="driver_id"
                                        id="driver_id">
                                    @if(isset($data['get_driver_reg_list']))
                                        @foreach($data['get_driver_reg_list'] as $option)
                                            {!!$option!!}
                                        @endforeach
                                    @endif
                                </select>
                                <span class="text-danger"></span>
                            </div>
                            <div class="col-md-6">
                                <label class="required">Driver Name:</label>
                                <input type="text"
                                       value="{{isset($data['insertedData']->driver_name) ? $data['insertedData']->driver_name : ''}}"
                                       class="form-control"
                                       id="driver_name"
                                       name="driver_name"
                                       placeholder="Driver Name"
                                       required
                                />
                                <span class="text-danger"></span>
                            </div>
                        </div>
                        <div class="row my-1">
                            <div class="col-md-6">
                                <label class="required">Driving Lic. No.:</label>
                                <input type="text"
                                       value="{{isset($data['insertedData']->dl_no) ? $data['insertedData']->dl_no : ''}}"
                                       class="form-control"
                                       id="dl_no"
                                       name="dl_no"
                                       placeholder="Driving Lic. No."
                                       required
                                />
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-6">
                                <label class="required">Driver Type</label>
                                {{--<select required
                                        class="custom-select"
                                        name="driver_type_id"
                                        id="driver_type_id">
                                    @if(isset($data['get_driver_type']))
                                        @foreach($data['get_driver_type'] as $option)
                                            {!!$option!!}
                                        @endforeach
                                    @endif
                                </select>--}}
                                <input type="text"
                                       value="{{isset($data['insertedData']->driver_type_name) ? $data['insertedData']->driver_type_name : ''}}"
                                       class="form-control"
                                       id="driver_type_name"
                                       name="driver_type_name"
                                       placeholder="Driver type"
                                       required
                                />
                                <span class="text-danger"></span>
                            </div>

                        </div>

                    </fieldset>
                    <div class="row my-1">
                        <div class="col-md-6">
                            <label for="start_date">Start Date:</label>
                            <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                                <input type="text"
                                       value="{{isset($data['insertedData']->start_date) ? date('d-m-Y', strtotime($data['insertedData']->start_date)) : ''}}"
                                       class="form-control datetimepicker-input"
                                       data-toggle="datetimepicker" data-target="#datetimepicker2"
                                       id="start_date"
                                       name="start_date"
                                       autocomplete="off"
                                />
                            </div>
                            <span class="text-danger"></span>
                        </div>

                        <div class="col-md-6">
                            <label>End Date:</label>
                            <div class="input-group date" id="datetimepicker3" data-target-input="nearest">
                                <input type="text"
                                       value="{{isset($data['insertedData']->end_date) ? date('d-m-Y', strtotime($data['insertedData']->end_date)) : ''}}"
                                       class="form-control datetimepicker-input"
                                       data-toggle="datetimepicker" data-target="#datetimepicker3"
                                       id="end_date"
                                       name="end_date"
                                       autocomplete="off"
                                />
                            </div>
                            <span class="text-danger"></span>
                        </div>
                    </div>

                    <div class="row my-1">
                        <div class="col-md-6">
                            <label class="required">Assignment Type:</label>
                            <select required
                                    class="custom-select"
                                    name="assignment_type_id"
                                    id="assignment_type_id">
                                @if(isset($data['get_assignment_type']))
                                    @foreach($data['get_assignment_type'] as $option)
                                        {!!$option!!}
                                    @endforeach
                                @endif
                            </select>
                            <span class="text-danger"></span>
                        </div>
                        <div class="col-md-6">
                            <label class="required">Status :</label>
                            <div>
                                </br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="active_yn"
                                           required id="active_yn" value="Y" checked
                                           @if(isset($data['insertedData']->active_yn) && $data['insertedData']->active_yn == "Y")
                                           checked
                                        @endif
                                    />
                                    <label class="form-check-label"
                                           for="reporter_cpa_yes">Active</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="active_yn"
                                           required id="active_yn" value="N"
                                           @if(isset($data['insertedData']->active_yn) && $data['insertedData']->active_yn == "N")
                                           checked
                                        @endif
                                    />
                                    <label class="form-check-label"
                                           for="reporter_cpa_no">In-Active</label>
                                </div>
                                <span class="text-danger"></span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- -->
            <div class="row">
                <div class="col-sm-6">
                    <fieldset class="border col-sm-12">
                        <legend class="w-auto required" style="font-size: 14px;"> Schedule Information</legend>
                        <div class="row my-1">
                            <div class="col-md-12">
                                <label class="required">Schedule :</label>
                                </br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="schedule_yn"
                                           required id="schedule_yn_yes" value="Y" checked
                                           @if(isset($data['insertedData']->schedule_yn) && $data['insertedData']->schedule_yn == "Y")
                                           checked
                                        @endif
                                    />
                                    <label class="form-check-label"
                                           for="schedule_yn_yes">YES</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="schedule_yn"
                                           required id="schedule_yn_no" value="N"
                                           @if(isset($data['insertedData']->schedule_yn) && $data['insertedData']->schedule_yn == "N")
                                           checked
                                        @endif
                                    />
                                    <label class="form-check-label"
                                           for="schedule_yn_no">NO</label>
                                </div>
                                <span class="text-danger"></span>
                            </div>


                            <div id="schedule_div"
                                 @if(isset($data['insertedData']->schedule_yn) && $data['insertedData']->schedule_yn == "N")
                                 class="col-md-6 displayNone"
                                 @else
                                 class="col-md-6"
                                @endif
                            >
                                <label>Schedule Name:</label>
                                <select
                                    class="custom-select select2"
                                    name="schedule_id"
                                    id="schedule_id">
                                    @if(isset($data['get_schedule_list']))
                                        @foreach($data['get_schedule_list'] as $option)
                                            {!!$option!!}
                                        @endforeach
                                    @endif
                                </select>
                                <span class="text-danger"></span>
                            </div>


                            <div id="used_employee_div"
                                 @if(isset($data['insertedData']->schedule_yn) && $data['insertedData']->schedule_yn == "Y")
                                 class="col-md-6 displayNone"
                                 @elseif(isset($data['insertedData']->schedule_yn) && $data['insertedData']->schedule_yn == "N")
                                 class="col-md-6"
                                 @else
                                 class="col-md-6 displayNone"
                                @endif
                            >
                                <label>Employee Name:</label>
                                <select
                                    class="custom-select used_employee_id select2"
                                    name="used_employee_id"
                                    id="used_employee_id">
                                    @if(isset($data['insertedData']->used_employee_id))
                                        @if($data['insertedData']->schedule_yn == 'N')
                                            <option
                                                value="{{$data['insertedData']->used_employee_id}}">{{$data['insertedData']->used_employee_name}}</option>
                                        @endif
                                    @endif
                                </select>
                                <span class="text-danger"></span>
                            </div>


                            <div class="col-md-6">
                                <label>Work Type:</label>
                                <select class="custom-select work_type_id select2"
                                        name="work_type_id"
                                        id="work_type_id">
                                    @if(isset($data['insertedData']->work_type_id))
                                        <option
                                            value="{{$data['insertedData']->work_type_id}}">{{$data['insertedData']->work_type}}</option>
                                    @endif
                                </select>
                                <span class="text-danger"></span>
                            </div>
                        </div>
                    </fieldset>
                </div>

                <div class="col-sm-6"></div>
            </div>

        </div>
    </div>


    <div class="row my-1">
        <div class="col-sm-9">
            <div class="form-group">
                <label for="remarks">Remarks</label>
                <textarea placeholder="Remarks"
                          rows="2" wrap="soft"
                          name="remarks"
                          class="form-control"
                          id="remarks">{!! old('remarks',isset($data['insertedData']->note) ? $data['insertedData']->note : '')!!}</textarea>

                <small class="text-muted form-text"> </small>
            </div>
        </div>

        <div class="col-md-3">
            <label>&nbsp;</label>
            <div class="d-flex justify-content-end col">
                <button type="submit" id="submit" class="btn btn btn-dark shadow mr-1 mb-1 btn-secondary">
                    @if(empty($data['insertedData']->assignment_id))
                        Save
                    @else
                        Update
                    @endif
                </button> &nbsp;

                @if(empty($data['insertedData']->assignment_id))
                    <button type="reset" class="btn btn-light-secondary mb-1">
                        Reset
                    </button>
                @else
                    <a href="{{route('vehicle-assign-index')}}">
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

