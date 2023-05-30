<?php
/**
 * Created by PhpStorm.
 * User: salman
 * Date: 6/6/2020
 * Time: 9:00 PM
 */
?>
<form id="searchResultPeriodGridList" method="post" enctype="multipart/form-data"
        @if(isset($data['insertedData']->maintanance_id))
          action="{{ route('maintenance-update', ['id' => $data['insertedData']->maintanance_id]) }}">
        <input name="_method" type="hidden" value="PUT">
        @else
            action="{{ route('maintenance-store') }}">
        @endif
        {{ csrf_field() }}
    <div class="row justify-content-center">
        <div class="col-12">

            <div class="row">
                <div class="col-sm-12">

                    <fieldset class="border col-sm-12">
                        <legend class="w-auto required" style="font-size: 14px;">Information </legend>

                        <div class="row my-1">
                            <div class="col-md-3">
                                <label class="required">VEHICLE REG NO.:</label>
                                <select required
                                        class="custom-select select2"
                                        name="vehicle_id"
                                        id="vehicle_id">
                                    @if(isset($data['get_vehicle_reg_no_list']))
                                        @foreach($data['get_vehicle_reg_no_list'] as $option)
                                            {!!$option!!}
                                        @endforeach
                                    @endif
                                </select>
                                <span class="text-danger"></span>
                            </div>
                            <div class="col-md-3">
                                <label class="required">Driver CPA Reg. No:</label>
                                <select required
                                        class="custom-select driver_id select2"
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
                            <div class="col-md-3">
                                <label>Driver Name:</label>
                                <input type="text"
                                       value="{{isset($data['insertedData']->driver_name) ? $data['insertedData']->driver_name : ''}}"
                                       class="form-control"
                                       id="driver_name"
                                       name="driver_name"
                                       readonly
                                />
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-3">
                                <label class="required">Job No.:</label>
                                <input type="text"
                                       value="{{isset($data['insertedData']->job_no) ? $data['insertedData']->job_no : ''}}"
                                       class="form-control"
                                       id="job_no"
                                       name="job_no"
                                       placeholder="Job No."
                                       required
                                />
                                <span class="text-danger"></span>
                            </div>

                        </div>
                        <div class="row my-1">

                            <div class="col-md-3">
                                <label class="required">Job Start Date:</label>
                                <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                                    <input type="text"
                                           value="{{isset($data['insertedData']->job_date) ?  date('d-m-Y', strtotime($data['insertedData']->job_date)) : ''}}"
                                           class="form-control datetimepicker-input"
                                           data-toggle="datetimepicker" data-target="#datetimepicker2"
                                           id="job_date"
                                           name="job_date"
                                           autocomplete="off"
                                           required
                                    />
                                </div>
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-3">
                                <label class="required">Job End Date:</label>
                                <div class="input-group date" id="datetimepicker3" data-target-input="nearest">
                                    <input type="text"
                                           value="{{isset($data['insertedData']->job_end_date) ?  date('d-m-Y', strtotime($data['insertedData']->job_end_date)) :  date('d-m-Y')}}"
                                           class="form-control datetimepicker-input"
                                           data-toggle="datetimepicker" data-target="#datetimepicker3"
                                           id="job_end_date"
                                           name="job_end_date"
                                           autocomplete="off"
                                           required
                                    />
                                </div>
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-3">
                                <label class="required">Work shop type:</label>
                                <select required
                                        class="custom-select select2"
                                        name="workshop_type_id"
                                        id="workshop_type_id">
                                    @if(isset($data['get_workshop_type']))
                                        @foreach($data['get_workshop_type'] as $option)
                                            {!!$option!!}
                                        @endforeach
                                    @endif
                                </select>
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-3">
                                <label class="required">Work shop:</label>
                                <select required
                                        class="custom-select select2"
                                        name="workshop_id"
                                        id="workshop_id">

                                   @if(isset($data['get_workshop_list']))
                                        @foreach($data['get_workshop_list'] as $option)
                                            {!!$option!!}
                                        @endforeach
                                    @else
                                        <option value="">Select One</option>
                                    @endif
                                </select>
                                <span class="text-danger"></span>
                            </div>

                        </div>
                        <div class="row my-1">
                            <input type="hidden"
                                   class="form-control"
                                   id="job_request_id"
                                   name="job_request_id"
                                   value="@if(isset($data["insertedData"]->job_request_by))  {{ $data["insertedData"]->job_request_by }} @endif"
                            />

                            <div class="col-md-3">
                                <label>Job Requested  By</label>
                                <input type="text"
                                       class="form-control"
                                       id="job_request_id_name"
                                       name="job_request_id_name"
                                       value="@if(isset($data["insertedData"]->emp_code_name))  {{ $data["insertedData"]->emp_code_name }} @endif"
                                       readonly
                                />
                                <span class="text-danger"></span>
                            </div>
                           {{-- <div class="col-md-3">
                                <label>Job Request By</label>
                                <select
                                        class="custom-select job_request_id select2"
                                        name="job_request_id"
                                        id="job_request_id">
                                    @if(isset($data['insertedData']->job_request_by))

                                        <option value="{{$data['insertedJobReqByData']->job_request_by}}">{{$data['insertedJobReqByData']->job_request_by_emp_code}}</option>

                                    @endif
                                </select>
                                <span class="text-danger"></span>
                            </div>--}}
                            <div class="col-md-3">
                                <label>Employee:</label>
                                <input type="text"
                                       value="{{isset($data['insertedJobReqByData']->job_request_by_name) ? $data['insertedJobReqByData']->job_request_by_name : ''}}"
                                       class="form-control"
                                       id="job_request_by_name"
                                       name="job_request_by_name"
                                       readonly
                                />
                                <span class="text-danger"></span>
                            </div>
                            <div class="col-md-3">
                                <label>Designation:</label>
                                <input type="text"
                                       value="{{isset($data['insertedJobReqByData']->job_request_by_designation) ? $data['insertedJobReqByData']->job_request_by_designation : ''}}"
                                       class="form-control"
                                       id="job_request_by_designation"
                                       name="job_request_by_designation"
                                       readonly
                                />
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-3">
                                <label>Department:</label>
                                <input type="text"
                                       value="{{isset($data['insertedJobReqByData']->job_request_by_department) ? $data['insertedJobReqByData']->job_request_by_department : ''}}"
                                       class="form-control"
                                       id="job_request_by_department"
                                       name="job_request_by_department"
                                       readonly
                                />
                                <span class="text-danger"></span>
                            </div>

                        </div>
                        <div class="row my-1">
                            <div class="col-md-3">
                                <label>Job Done By (Mechanical)</label>
                                <select
                                        class="custom-select job_by select2"
                                        name="job_by"
                                        id="job_by">
                                    @if(isset($data['insertedJobByData']->job_by))

                                        <option value="{{$data['insertedJobByData']->job_by}}">{{$data['insertedJobByData']->job_by_emp_code}}</option>

                                    @endif
                                </select>
                                <span class="text-danger"></span>
                            </div>
                            <div class="col-md-3">
                                <label>Employee:</label>
                                <input type="text"
                                       value="{{isset($data['insertedJobByData']->job_by_name) ? $data['insertedJobByData']->job_by_name : ''}}"
                                       class="form-control"
                                       id="job_by_name"
                                       name="job_by_name"
                                       readonly
                                />
                                <span class="text-danger"></span>
                            </div>
                            <div class="col-md-3">
                                <label>Designation:</label>
                                <input type="text"
                                       value="{{isset($data['insertedJobByData']->job_by_designation) ? $data['insertedJobByData']->job_by_designation : ''}}"
                                       class="form-control"
                                       id="job_by_designation"
                                       name="job_by_designation"
                                       readonly
                                />
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-3">
                                <label>Department:</label>
                                <input type="text"
                                       value="{{isset($data['insertedJobByData']->job_by_department) ? $data['insertedJobByData']->job_by_department : ''}}"
                                       class="form-control"
                                       id="job_by_department"
                                       name="job_by_department"
                                       readonly
                                />
                                <span class="text-danger"></span>
                            </div>

                        </div>
                        <div class="row my-1">
                            <div class="col-md-3">
                                <label>Location</label>
                                <select
                                        class="custom-select"
                                        name="location_id"
                                        id="location_id">
                                    @if(isset($data['get_location']))
                                        @foreach($data['get_location'] as $option)
                                            {!!$option!!}
                                        @endforeach
                                    @endif
                                </select>
                                <span class="text-danger"></span>
                            </div>

                            {{--<div class="col-md-3 dropdownStatus">
                                <label class="required">Job Cost:</label>
                                <input type="text"
                                       value="{{isset($data['insertedData']->job_cost) ? $data['insertedData']->job_cost : ''}}"
                                       class="form-control"
                                       id="job_cost"
                                       name="job_cost"
                                       placeholder="Job Cost"
                                       required
                                />
                                <span class="text-danger"></span>
                            </div>--}}

                        </div>
                    </fieldset>
                </div>


                <div class="col-sm-12">

                    <fieldset class="border col-sm-12">
                        <legend class="w-auto required" style="font-size: 14px;"> Service Taken </legend>

                        <div class="row setup-content my-2" id="education">
                            <div class="col-md-12">
                                @include('mea.vms.maintenance.servicedetails')
                            </div>
                        </div>

                    </fieldset>

                </div>
            </div>
            <div class="row my-1">
                <div class="col-sm-9">
                    <div class="form-group">
                        <label for="remarks">Note</label>
                        <textarea placeholder="Remarks"
                                  rows="2" wrap="soft"
                                  name="remarks"
                                  class="form-control"
                                  id="remarks" >{!! old('remarks',isset($data['insertedData']->comments) ? $data['insertedData']->comments : '')!!}</textarea>

                        <small class="text-muted form-text"> </small>
                    </div>
                </div>

                <div class="col-md-3">
                    <label>&nbsp;</label>
                    <div class="d-flex justify-content-end col">
                        <button type="submit" id="submit" class="btn btn btn-dark shadow mr-1 mb-1 btn-secondary">
                            Save
                        </button> &nbsp;
                    </div>
                </div>

            </div>


        </div>
    </div>
</form>

