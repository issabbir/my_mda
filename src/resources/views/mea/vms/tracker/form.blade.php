<?php
/**
 * Created by PhpStorm.
 * User: salman
 * Date: 6/7/2020
 * Time: 10:27 AM
 */
?>
<form id="searchResultPeriodGridList" method="post" enctype="multipart/form-data"
      @if(isset($data['insertedData']->tracker_id))
      action="{{ route('tracker-update', ['id' => $data['insertedData']->tracker_id]) }}">
    <input name="_method" type="hidden" value="PUT">
    @else
        action="{{ route('tracker-store') }}">
    @endif
    {{ csrf_field() }}
    <div class="row">
        <div class="col-12">
        <fieldset class="border col-sm-12">
            <legend class="w-auto required" style="font-size: 14px;"> Information </legend>

            <div class="col-12">
                <div class="row my-1">
                   <div class="col-md-4">
                        <label class="required">Device IMEI:</label>
                        <input type="text"
                               value="{{isset($data['insertedData']->tracker_device_imei) ? $data['insertedData']->tracker_device_imei : ''}}"
                               class="form-control"
                               id="tracker_device_imei"
                               name="tracker_device_imei"
                               placeholder="Device IMEI"
                               autocomplete="off"
                               minlength="15"
                               maxlength="15"
                               required
                        />
                        <span class="text-danger"></span>
                    </div>
                    <div class="col-md-5">
                        <label class="required">Tracker Company Name:</label>
                        <input type="text"
                               value="{{isset($data['insertedData']->tracker_company_name) ? $data['insertedData']->tracker_company_name : ''}}"
                               class="form-control"
                               id="tracker_company_name"
                               name="tracker_company_name"
                               placeholder="Tracker Company Name"
                               autocomplete="off"
                               required
                        >
                        <span class="text-danger"></span>
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
                </div>
            </div>

            <div class="col-12">
                <div class="row my-1">
                    <div class="col-sm-9">

                    </div>

                    <div class="col-md-3">
                        <label>&nbsp;</label>
                        <div class="d-flex justify-content-end col">
                            <button type="submit" id="submit" class="btn btn btn-dark shadow mr-1 mb-1 btn-secondary">
                                @if(empty($data['insertedData']->tracker_id))
                                    Save
                                @else
                                    Update
                                @endif
                            </button>

                            @if(empty($data['insertedData']->tracker_id))
                                <button type="reset" class="btn btn-light-secondary mb-1">
                                    Reset
                                </button>
                            @else
                                <a href="{{route('tracker-index')}}">
                                    <button type="button" class="btn btn-light-secondary mb-1">
                                        Reset
                                    </button>
                                </a>
                            @endif&nbsp;
                        </div>
                    </div>

                </div>
            </div>
        </fieldset>

        </div>

    </div>
</form>


