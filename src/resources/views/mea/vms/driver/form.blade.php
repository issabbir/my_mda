<?php
/**
 * Created by PhpStorm.
 * User: salman
 * Date: 2/6/2020
 * Time: 9:27 AM
 */

?>
<form id="searchResultPeriodGridList" method="post" enctype="multipart/form-data"
      @if(isset($data['insertedData']->driver_id))
      action="{{ route('driver-enlist-update', ['id' => $data['insertedData']->driver_id]) }}">
    <input name="_method" type="hidden" value="PUT">
    @else
        action="{{ route('driver-enlist-store') }}">
    @endif
    {{ csrf_field() }}
    <div class="row justify-content-center">
        <div class="col-12">

            <div class="row">
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="driver_type_id" class="required">Driver Type</label>
                                <select required
                                        class="custom-select"
                                        name="driver_type_id"
                                        id="driver_type_id">
                                        @if(isset($data['get_driver_type']))
                                            @foreach($data['get_driver_type'] as $option)
                                                {!!$option!!}
                                            @endforeach
                                        @endif
                                </select>
                                <span class="text-danger"></span>
                            </div>
                            <div class="col-md-4">
                                <label for="driver_cpa_no" class="required">Employee/CPA Reg. Code:</label>
                                <div id="external">
                                    <input type="text"
                                           value="@if(isset($data['insertedData']->driver_cpa_no)){{ $data['insertedData']->driver_type_id == '2' ? $data['insertedData']->driver_cpa_no : ''}}@endif"
                                           class="form-control"
                                           id="driver_cpa_no"
                                           name="driver_cpa_no"
                                           placeholder="Employee Code"
                                    />

                                </div>
                                <div id="internal" class="displayNone">

                                    <select
                                            class=" custom-select select2 driver_emp_id_as_cpa_no "
                                            name="driver_emp_id_as_cpa_no"
                                            id="driver_emp_id_as_cpa_no">
                                            @if(isset($data['insertedData']->driver_cpa_no))
                                                    @if($data['insertedData']->driver_type_id == '1')
                                                <option value="{{$data['insertedData']->cpa_emp_id}}">{{$data['insertedData']->driver_cpa_no}}</option>
                                                @endif
                                            @endif

                                    </select>
                                    <input type="hidden" name="driver_emp_code_as_cpa_no" id="driver_emp_code_as_cpa_no"  value="{{isset($data['insertedData']->driver_cpa_no) ? $data['insertedData']->driver_cpa_no : ''}}" />
                                </div>

                                <span class="text-danger"></span>
                            </div>
                            <div class="col-md-4 dropdownStatus">
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
                            <div class="col-md-4 dropdownStatus">
                                <label>Driver Bangla Name:</label>
                                <input type="text"
                                       value="{{isset($data['insertedData']->driver_name_bn) ? $data['insertedData']->driver_name_bn : ''}}"
                                       class="form-control"
                                       id="driver_name_bn"
                                       name="driver_name_bn"
                                       placeholder="Driver Bangla Name "

                                />
                                <span class="text-danger"></span>
                            </div>
                            <div class="col-md-4 dropdownStatus">
                                <label>Fathers Name:</label>
                                <input type="text"
                                       value="{{isset($data['insertedData']->driver_father_name) ? $data['insertedData']->driver_father_name : ''}}"
                                       class="form-control"
                                       id="driver_father_name"
                                       name="driver_father_name"
                                       placeholder="Fathers Name"

                                />
                                <span class="text-danger"></span>
                            </div>
                            <div class="col-md-4 dropdownStatus">
                                <label>Mothers Name:</label>
                                <input type="text"
                                       value="{{isset($data['insertedData']->driver_mother_name) ? $data['insertedData']->driver_mother_name : ''}}"
                                       class="form-control"
                                       id="driver_mother_name"
                                       name="driver_mother_name"
                                       placeholder="Mothers Name"

                                />
                                <span class="text-danger"></span>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-3 ">
                        <div class="row">
                            <div class="col-md-12">
                                    @if(isset($data['insertedData']->driver_photo))
                                        <div class="col defaultImgDiv">
                                            <img    class="defaultImg"
                                                    src="data:{{$data['insertedData']->photo_doc_type}};base64, {{$data['insertedData']->driver_photo}}"
                                                    alt="{{$data['insertedData']->photo_doc_name}}"
                                                    width="70"
                                                    height="80"/>
                                            @if(isset($data['insertedData']->photo_doc_name))
                                                <a href="{{ route('driver-photo', ['id' => $data['insertedData']->driver_id]) }}" target="_blank">Download</a>
                                            @endif
                                        </div>
                                    @else
                                        <div id="defaultImgDiv" class="col defaultImgDiv">
                                            <img class="defaultImg"  src="{{$defaultImg}}"
                                                 alt="" width="70" height="80"/>
                                        </div>
                                    @endif
                                    <label>Upload Photo</label>
                                    <div class="custom-file b-form-file form-group dropdownStatus">
                                        <input type="file" id="driver_photo" name="driver_photo"
                                               class="custom-file-input"  accept="image/gif, image/jpeg,image/png,image/jpg"
                                               @if(isset($data['insertedData']->driver_photo)) value="data:{{$data['insertedData']->photo_doc_type}};base64, {{$data['insertedData']->driver_photo}}"

                                                @else
                                                    {{--required --}}
                                                @endif>
                                        <label for="driver_photo" data-browse="Browse" accept="image/gif, image/jpeg,image/png,image/jpg"
                                               class="custom-file-label  @if(isset($data['insertedData']->driver_photo)) required @endif">Photo
                                            Attached</label>
                                    </div>
                            </div>
                        </div>
                    </div>

            </div>

            <div class="row ">
                <div class="col-md-3 dropdownStatus">
                    <label>Married:</label>
                    <select
                            class="custom-select"
                            name="marital_status_id"
                            id="marital_status_id">
                        @if(isset($data['get_marital_status']))
                            @foreach($data['get_marital_status'] as $option)
                                {!!$option!!}
                            @endforeach
                        @endif
                    </select>


                    <span class="text-danger"></span>
                </div>
                <div class="col-md-3 dropdownStatus">
                    <label>Spouse Name:</label>
                    <input type="text"
                           value="{{isset($data['insertedData']->driver_spouse_name) ? $data['insertedData']->driver_spouse_name : ''}}"
                           class="form-control"
                           id="driver_spouse_name"
                           name="driver_spouse_name"
                           placeholder="Spouse Name"

                    />
                    <span class="text-danger"></span>
                </div>
                <div class="col-md-3 dropdownStatus">
                    <label class="required">Gender:</label>
                    <select required
                            class="custom-select"
                            name="gender_type_id"
                            id="gender_type_id">
                        @if(isset($data['get_gender_type']))
                            @foreach($data['get_gender_type'] as $option)
                                {!!$option!!}
                            @endforeach
                        @endif
                    </select>

                    <span class="text-danger"></span>
                </div>

                <div class="col-md-3 dropdownStatus">
                    <label class="required">Date of Birth</label>
                    <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                        <input type="text"
                               value="{{isset($data['insertedData']->dob) ? date('d-m-Y', strtotime($data['insertedData']->dob))  : ''}}"
                               class="form-control datetimepicker-input"
                               data-toggle="datetimepicker" data-target="#datetimepicker1"
                               id="dob"
                               name="dob"
                               autocomplete="off"
                               required
                        />
                    </div>
                    <span class="text-danger"></span>
                </div>

            </div>

            <div class="row my-1">

                <div class="col-md-3 dropdownStatus">
                    <label class="required">NID:</label>
                    <input type="text"
                           value="{{isset($data['insertedData']->nid_no) ? $data['insertedData']->nid_no : ''}}"
                           class="form-control nid"
                           minlength="10"
                           maxlength="17"
                           id="nid_no"
                           name="nid_no"
                           placeholder="NID"
                           required
                    />
                    <span class="text-danger"></span>
                </div>
                <div class="col-md-3">
                    <label class="required">Mobile No.:</label>
                    <input type="text"
                           value="{{isset($data['insertedData']->mobile_no) ? $data['insertedData']->mobile_no : ''}}"
                           class="form-control mobile"
                           minlength="10"
                           maxlength="11"
                           id="mobile_no"
                           name="mobile_no"
                           placeholder="Mobile No."
                           required
                    />
                    <span class="text-danger"></span>
                </div>

                <div class="col-md-3 dropdownStatus">
                    <label class="required">Emergency Mobile No.:</label>
                    <input type="text"
                           value="{{isset($data['insertedData']->emargency_no) ? $data['insertedData']->emargency_no : ''}}"
                           class="form-control mobile"
                           minlength="10"
                           maxlength="11"
                           id="emargency_no"
                           name="emargency_no"
                           placeholder="Emergency Mobile No."
                           required
                    />
                    <span class="text-danger"></span>
                </div>

                <div class="col-md-3">
                    <label>Licence status:</label>
                    <select
                            class="custom-select"
                            name="license_status_id"
                            id="license_status_id">
                        @if(isset($data['get_license_status']))
                            @foreach($data['get_license_status'] as $option)
                                {!!$option!!}
                            @endforeach
                        @endif
                    </select>
                    <span class="text-danger"></span>
                </div>

            </div>

            <div class="row my-1">
                <div class="col-md-3">
                    <label class="required">Driving Lic. No.:</label>
                    <input type="text"
                           value="{{isset($data['insertedData']->dl_no) ? $data['insertedData']->dl_no : ''}}"
                           class="form-control dl_no"
                           minlength="10"
                           maxlength="15"
                           id="dl_no"
                           name="dl_no"
                           placeholder="Driving Lic. No."
                           required
                    />
                    <span class="text-danger"></span>
                </div>
                <div class="col-md-3">
                    <label class="required">Driving Lic. Issue Date:</label>
                    <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                        <input type="text"
                               value="{{isset($data['insertedData']->dl_issue_date) ? date('d-m-Y', strtotime($data['insertedData']->dl_issue_date))  : ''}}"
                               class="form-control datetimepicker-input"
                               data-toggle="datetimepicker" data-target="#datetimepicker2"
                               id="dl_issue_date"
                               name="dl_issue_date"
                               autocomplete="off"

                               required
                        />
                    </div>
                    <span class="text-danger"></span>
                </div>

                <div class="col-md-3">
                    <label class="required">Driving Lic. Expiry Date:</label>
                    <div class="input-group date" id="datetimepicker3" data-target-input="nearest">
                        <input type="text"
                               value="{{isset($data['insertedData']->dl_expiry_date) ? date('d-m-Y', strtotime($data['insertedData']->dl_expiry_date)) : ''}}"
                               class="form-control datetimepicker-input"
                               data-toggle="datetimepicker" data-target="#datetimepicker3"
                               id="dl_expiry_date"
                               name="dl_expiry_date"
                               autocomplete="off"

                               required
                        />
                    </div>
                    <span class="text-danger"></span>
                </div>

                <div class="col-md-3">
                      <label class="required">Profile Status :</label>
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


            <div class="row my-1">

                <div class="col-md-6">
                    <fieldset class="border col-sm-12">
                        <legend class="w-auto required" style="font-size: 14px;"> Permanent Address </legend>

                        <div class="row">

                            <div class="col-sm-4">
                                <div class="form-group dropdownStatus">
                                    <label for="permanent_division_id" class="required">Division</label>
                                    <select required class="form-control select2" id="permanent_division_id" name="permanent_division_id">
                                        @if(isset($data['loadPermanentDivision']))
                                            @foreach($data['loadPermanentDivision'] as $option)
                                                {!! $option !!}
                                            @endforeach
                                        @endif
                                    </select>
                                    <small class="text-muted form-text"> </small>
                                </div>
                            </div>


                            <div class="col-sm-4">
                                <div class="form-group dropdownStatus">
                                    <label for="permanent_district_id" class="required">District</label>
                                    <select required class="form-control select2" id="permanent_district_id" name="permanent_district_id">
                                        @if(isset($data['loadPermanentDistricts']))
                                            @foreach($data['loadPermanentDistricts'] as $option)
                                                    {!! $option !!}
                                            @endforeach
                                        @else
                                            <option value="">Select One</option>
                                        @endif
                                    </select>
                                    <small class="text-muted form-text"> </small>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group dropdownStatus">
                                    <label for="permanent_thana_id" class="required">Thana</label>
                                    <select required class="form-control select2" id="permanent_thana_id" name="permanent_thana_id">
                                        @if(isset($data['loadPermanentThanas']))
                                            @foreach($data['loadPermanentThanas'] as $option)
                                                {!! $option !!}
                                            @endforeach
                                        @else
                                            <option value="">Select One</option>
                                        @endif
                                    </select>
                                    <small class="text-muted form-text"> </small>
                                </div>
                            </div>

                         </div>

                         <div class="row my-1">
                            <div class="col-md-12">
                                <input type="text"
                                       value="{{isset($data['insertedPermAddData']->address_line_1) ? $data['insertedPermAddData']->address_line_1 : ''}}"
                                       class="form-control"
                                       id="permanent_address_line1"
                                       name="permanent_address_line1"
                                       max="250"
                                       maxlength="250"
                                       placeholder="Flat/House No./Holding No./House Name"
                                       {{--required--}}
                                />
                            </div>
                            <div class="col-md-12 my-1">
                                <input type="text"
                                       value="{{isset($data['insertedPermAddData']->address_line_2) ? $data['insertedPermAddData']->address_line_2 : ''}}"
                                       class="form-control"
                                       id="permanent_address_line2"
                                       name="permanent_address_line2"
                                       max="250"
                                       maxlength="250"
                                       placeholder="Area/LAN/Road"
                                       {{--required--}}
                                />
                                <span class="text-danger"></span>
                            </div>
                             <input name="permanent_address_id" type="hidden"
                                    value="{{isset($data['insertedPermAddData']->address_id) ? $data['insertedPermAddData']->address_id : ''}}"
                             />
                        </div>
                    </fieldset>
                </div>

                <div class="col-md-6">
                    <fieldset class="border col-sm-12">
                        <legend class="w-auto required" style="font-size: 14px;"> Present Address
                        (  &nbsp;
                            <div class="form-check-inline dropdownStatus" >
                                <label for="sameAsPermanentAddress" class="form-check-label">
                                    <input id="sameAsPermanentAddress" type="checkbox"
                                           name="sameAsPermanentAddress" class="form-check-input" value="1" />
                                    Same as Permanent Address
                                </label>
                            </div>
                         )
                        </legend>
                        <div class="row">
                            <div class="col-sm-4 dropdownStatus">
                                <div class="form-group">
                                    <label for="present_division_id" class="required">Division</label>
                                    <select required class="form-control select2" id="present_division_id" name="present_division_id">
                                        @if(isset($data['loadPresentDivision']))
                                            @foreach($data['loadPresentDivision'] as $option)
                                                {!! $option !!}
                                            @endforeach
                                        @endif
                                    </select>
                                    <small class="text-muted form-text"> </small>
                                </div>
                            </div>


                            <div class="col-sm-4 dropdownStatus">
                                <div class="form-group">
                                    <label for="present_district_id" class="required">District</label>
                                    <select required class="form-control select2" id="present_district_id" name="present_district_id">
                                        @if(isset($data['loadPresentDistricts']))
                                            @foreach($data['loadPresentDistricts'] as $option)
                                                {!! $option !!}
                                            @endforeach
                                        @else
                                            <option value="">Select One</option>
                                        @endif
                                    </select>
                                    <small class="text-muted form-text"> </small>
                                </div>
                            </div>

                            <div class="col-sm-4 dropdownStatus">
                                <div class="form-group">
                                    <label for="present_thana_id" class="required">Thana</label>
                                    <select required class="form-control select2" id="present_thana_id" name="present_thana_id">
                                        @if(isset($data['loadPresentThanas']))
                                            @foreach($data['loadPresentThanas'] as $option)
                                                {!! $option !!}
                                            @endforeach
                                        @else
                                            <option value="">Select One</option>
                                        @endif
                                    </select>
                                    <small class="text-muted form-text"> </small>
                                </div>
                            </div>

                        </div>

                        <div class="row my-1">
                            <div class="col-md-12">
                                <input type="text"
                                       value="{{isset($data['insertedPresAddData']->address_line_1) ? $data['insertedPresAddData']->address_line_1 : ''}}"
                                       class="form-control"
                                       id="present_address_line1"
                                       name="present_address_line1"
                                       max="250"
                                       maxlength="250"
                                       placeholder="Flat/House No./Holding No./House Name"
                                        {{--required--}}
                                />
                            </div>
                            <div class="col-md-12 my-1">
                                <input type="text"
                                       value="{{isset($data['insertedPresAddData']->address_line_2) ? $data['insertedPresAddData']->address_line_2 : ''}}"
                                       class="form-control"
                                       id="present_address_line2"
                                       name="present_address_line2"
                                       max="250"
                                       maxlength="250"
                                       placeholder="Area/LAN/Road"
                                        {{--required--}}
                                />
                                <span class="text-danger"></span>
                            </div>
                            <input type="hidden" name="present_address_id"
                                   value="{{isset($data['insertedPresAddData']->address_id) ? $data['insertedPresAddData']->address_id : ''}}"
                            />
                        </div>
                    </fieldset>
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
                                  id="remarks" >{!! old('remarks',isset($data['insertedData']->remarks) ? $data['insertedData']->remarks : '')!!}</textarea>

                        <small class="text-muted form-text"> </small>
                    </div>
                </div>

                <div class="col-md-3">
                    <label>&nbsp;</label>
                    <div class="d-flex justify-content-end col">
                        <button type="submit" id="submit" class="btn btn btn-dark shadow mr-1 mb-1 btn-secondary">
                        @if(empty($data['insertedData']->driver_id))
                            Save
                        @else
                            Update
                            @endif
                            </button> &nbsp;

                            <a href="{{route('driver-enlist-index')}}">
                                <button type="button" class="btn btn-light-secondary mb-1">
                                    Reset
                                </button>
                            </a>
                    </div>
                </div>

            </div>

        </div>
    </div>
</form>

