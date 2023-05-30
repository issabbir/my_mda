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
      action="{{ route('workshop-service-update', ['id' => $data['insertedData']->work_ser_map_id]) }}">
    <input name="_method" type="hidden" value="PUT">
    @else
        action="{{ route('workshop-service-store') }}">
    @endif
    {{ csrf_field() }}
    <div class="row">
        <div class="col-12">
            <div class="row my-1">

                <div class="col-md-3">
                    <label class="required">Workshop Type:</label>
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
                    <label class="required">Workshop:</label>
                    <select required
                        class="custom-select select2"
                        name="workshop_id"
                        id="workshop_id">
                        <option value="">Select a option</option>
                        @if(isset($data['get_workshop']))
                            @foreach($data['get_workshop'] as $option)
                                {!!$option!!}
                            @endforeach
                        @endif
                    </select>
                    <span class="text-danger"></span>
                </div>
                <div class="col-md-3">
                    <label class="required">Service:</label>
                    <select required
                        class="custom-select select2"
                        name="service_id"
                        id="service_id">
                        @if(isset($data['get_workshop_service']))
                            @foreach($data['get_workshop_service'] as $option)
                                {!!$option!!}
                            @endforeach
                        @endif
                    </select>
                    <span class="text-danger"></span>
                </div>

            </div>
        </div>

        <div class="col-12">
            <div class="row my-1">


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



                <div class="col-md-9">
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
      </div>
    </div>
</form>


