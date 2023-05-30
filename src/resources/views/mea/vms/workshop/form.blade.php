<?php
/**
 * Created by PhpStorm.
 * User: salman
 * Date: 6/7/2020
 * Time: 10:27 AM
 */
?>
<form id="searchResultPeriodGridList" method="post" enctype="multipart/form-data"
      @if(isset($data['insertedData']->workshop_id))
      action="{{ route('workshop-update', ['id' => $data['insertedData']->workshop_id]) }}">
    <input name="_method" type="hidden" value="PUT">
    @else
        action="{{ route('workshop-store') }}">
    @endif
    {{ csrf_field() }}
    <div class="row">
        <div class="col-12">
            <div class="row my-1">
                <div class="col-md-3">
                    <label class="required">Workshop No.:</label>
                    <input type="text"
                           value="{{isset($data['insertedData']->workshop_no) ? $data['insertedData']->workshop_no : ''}}"
                           class="form-control"
                           id="workshop_no"
                           name="workshop_no"
                           placeholder="Workshop No."
                           required
                    >
                    <span class="text-danger"></span>
                </div>
                <div class="col-md-3">
                    <label class="required">Workshop Name :</label>
                    <input type="text"
                           value="{{isset($data['insertedData']->workshop_name) ? $data['insertedData']->workshop_name : ''}}"
                           class="form-control"
                           id="workshop_name"
                           name="workshop_name"
                           placeholder="Workshop Name"
                           required
                    >
                    <span class="text-danger"></span>
                </div>
                <div class="col-md-3">
                    <label>Workshop Name Bangla:</label>
                    <input type="text"
                           value="{{isset($data['insertedData']->workshop_name_bn) ? $data['insertedData']->workshop_name_bn : ''}}"
                           class="form-control"
                           id="workshop_name_bn"
                           name="workshop_name_bn"
                           placeholder="Workshop Bangla Name"
                    />
                    <span class="text-danger"></span>
                </div>

                <div class="col-md-3">
                    <label>Workshop Type:</label>
                    <select
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

            </div>
            <div class="row my-1">

                <div class="col-md-3">
                    <label class="required"> Department :</label>
                    <select required
                            class="custom-select select2"
                            name="department_id"
                            id="department_id">
                        @if(isset($data['get_department']))
                            @foreach($data['get_department'] as $option)
                                {!!$option!!}
                            @endforeach
                        @endif
                    </select>
                    <span class="text-danger"></span>
                </div>

                <div class="col-md-3">
                    <label class="required">Contact Person Code:</label>
                    <select required
                            class="custom-select employee_id select2"
                            name="employee_id"
                            id="employee_id">
                        @if(isset($data['insertedData']->employee_id))
                            <option value="{{$data['insertedData']->employee_id}}">{{$data['insertedData']->emp_code}}</option>

                        @endif
                    </select>
                    <span class="text-danger"></span>
                </div>

                <div class="col-md-3">
                    <label>Contact Person Name:</label>
                    <input type="text"
                           value="{!! old('remarks',isset($data['insertedData']->emp_name) ? $data['insertedData']->emp_name : '')!!}"
                           class="form-control"
                           id="employee_name"
                           name="employee_name"
                           placeholder="Contact Person Name"
                           readonly
                    />
                    <span class="text-danger"></span>
                </div>

                <div class="col-md-3">
                    <label class="required">Location :</label>
                    <input type="text"
                           value="{{isset($data['insertedData']->location_name) ? $data['insertedData']->location_name : ''}}"
                           class="form-control"
                           id="location_name"
                           name="location_name"
                           placeholder="Location Name"
                           required
                    >
                    <span class="text-danger"></span>
                </div>

                {{--<div class="col-md-3">
                    <label>Location :</label>
                    <select required
                            class="custom-select select2"
                            name="location_id"
                            id="location_id">
                        @if(isset($data['get_location']))
                            @foreach($data['get_location'] as $option)
                                {!!$option!!}
                            @endforeach
                        @endif
                    </select>
                    <span class="text-danger"></span>
                </div>--}}

            </div>
        </div>

        <div class="col-12">
            <div class="row my-1">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea placeholder="Remarks"
                                  rows="2" wrap="soft"
                                  name="remarks"
                                  class="form-control"
                                  id="remarks" >{!! old('remarks',isset($data['insertedData']->remarks) ? $data['insertedData']->remarks : '')!!}</textarea>

                        <small class="text-muted form-text"> </small>
                    </div>
                </div>

               <div class="col-md-3">
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



                <div class="col-md-3">
                    <label>&nbsp;</label>
                    <div class="d-flex justify-content-end col">
                        <button type="submit" id="submit" class="btn btn btn-dark shadow mr-1 mb-1 btn-secondary">
                            @if(empty($data['insertedData']->workshop_id))
                                Save
                            @else
                                Update
                            @endif
                        </button> &nbsp

                        @if(empty($data['insertedData']->workshop_id))
                            <button type="reset" class="btn btn-light-secondary mb-1">
                                Reset
                            </button>
                        @else
                            <a href="{{route('workshop-index')}}">
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
      </div>
    </div>
</form>


