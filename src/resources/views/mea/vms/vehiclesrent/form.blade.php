<?php
/**
 * Created by PhpStorm.
 * User: salman
 * Date: 6/7/2020
 * Time: 10:27 AM
 */
?>
<form id="searchResultPeriodGridList" method="post" enctype="multipart/form-data"
      @if(isset($data['insertedData']->vehicle_rent_id))
      action="{{ route('vehicle-rent-update', ['id' => $data['insertedData']->vehicle_rent_id]) }}">
    <input name="_method" type="hidden" value="PUT">
    @else
        action="{{ route('vehicle-rent-store') }}">
    @endif
    {{ csrf_field() }}
    <div class="row">
        <div class="col-12">
            <div class="row my-1">
                <div class="col-md-1">&nbsp;</div>
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
                    <label class="required">Enlistment Date:</label>
                    <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                        <input type="text"
                               value="{{isset($data['insertedData']->rent_start_date) ? date('d-m-Y', strtotime($data['insertedData']->rent_start_date)) : ''}}"
                               class="form-control datetimepicker-input"
                               data-toggle="datetimepicker" data-target="#datetimepicker2"
                               id="rent_start_date"
                               name="rent_start_date"
                               autocomplete="off"
                               required
                        />
                    </div>
                    <span class="text-danger"></span>
                </div>

                <div class="col-md-3">
                    <label class="required">Expiry Date:</label>
                    <div class="input-group date" id="datetimepicker3" data-target-input="nearest">
                        <input type="text"
                               value="{{isset($data['insertedData']->rent_end_date) ? date('d-m-Y', strtotime($data['insertedData']->rent_end_date)) : ''}}"
                               class="form-control datetimepicker-input"
                               data-toggle="datetimepicker" data-target="#datetimepicker3"
                               id="rent_end_date"
                               name="rent_end_date"
                               autocomplete="off"
                               required
                        />
                    </div>
                    <span class="text-danger"></span>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="row my-1">
                <div class="col-md-1">&nbsp;</div>
                <div class="col-md-3">
                    <label>Supplier Name:</label>
                    <input type="text"
                           class="form-control"
                           id="v_supplier_name"
                           readonly
                    />
                    <span class="text-danger"></span>
                </div>

                <div class="col-md-3">
                    <label>SUPPLIER ADDRESS:</label>
                    <textarea rows="1" wrap="soft"
                              class="form-control"
                              id="v_supplier_address" readonly></textarea>
                    <span class="text-danger"></span>
                </div>

                <div class="col-md-3">
                    <label>CONTACT START DATE:</label>
                    <input type="text"
                           class="form-control"
                           id="contact_start_dt"
                           readonly
                    />
                    <span class="text-danger"></span>
                </div>

            </div>
        </div>

        <div class="col-12">
            <div class="row my-1">
                <div class="col-md-1">&nbsp;</div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea placeholder="Remarks"
                                  rows="2" wrap="soft"
                                  name="remarks"
                                  class="form-control"
                                  id="remarks">{!! old('remarks',isset($data['insertedData']->remarks) ? $data['insertedData']->remarks : '')!!}</textarea>

                        <small class="text-muted form-text"> </small>
                    </div>
                </div>

                <div class="col-md-3">
                    <label>&nbsp;</label>
                    <div class="d-flex justify-content-end col">
                        <button type="submit" id="submit" class="btn btn btn-dark shadow mr-1 mb-1 btn-secondary">
                            @if(empty($data['insertedData']->vehicle_rent_id))
                                Save
                            @else
                                Update
                            @endif
                        </button> &nbsp;

                        @if(empty($data['insertedData']->vehicle_rent_id))
                            <button type="reset" class="btn btn-light-secondary mb-1">
                                Reset
                            </button>
                        @else
                            <a href="{{route('vehicle-rent-index')}}">
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


