<?php
/**
 * Created by VScode.
 * User: Munna
 * Date: 22/7/2020
 * Time: 01:27 PM
 */
?>
<form id="searchResultPeriodGridList" method="post" enctype="multipart/form-data"
      @if(isset($data['insertedData']->v_supplier_id))
      action="{{ route('supplier-update', ['id' => $data['insertedData']->v_supplier_id]) }}">
    <input name="_method" type="hidden" value="PUT">
    @else
        action="{{ route('supplier-store') }}">
    @endif
    {{ csrf_field() }}
    <div class="row">
        <div class="col-12">
            <fieldset class="border col-sm-12">
                <legend class="w-auto required" style="font-size: 14px;"> Information</legend>

                <div class="col-12">
                    <div class="row my-1">

                        <div class="col-md-3">
                            <label for="v_supplier_name" class="required">Supplier Name :</label>
                            <input type="text"
                                   value="{{isset($data['insertedData']->v_supplier_name) ? $data['insertedData']->v_supplier_name : ''}}"
                                   class="form-control"
                                   id="v_supplier_name"
                                   name="v_supplier_name"
                                   placeholder="Supplier Name"
                                   autocomplete="off"
                                   required
                            >
                            <span class="text-danger"></span>
                        </div>
                        <div class="col-md-3">
                            <label for="v_supplier_name_bn">Supplier Bangla :</label>
                            <input type="text"
                                   value="{{isset($data['insertedData']->v_supplier_name_bn) ? $data['insertedData']->v_supplier_name_bn : ''}}"
                                   class="form-control"
                                   id="v_supplier_name_bn"
                                   name="v_supplier_name_bn"
                                   placeholder="Suplier Bangla"
                                   autocomplete="off"

                            >
                            <span class="text-danger"></span>
                        </div>
                        <div class="col-md-3">
                            <label for="v_supplier_contact_no">Supplier Contact No</label>
                            <input type="text"
                                   value="{{isset($data['insertedData']->v_supplier_contact_no) ? $data['insertedData']->v_supplier_contact_no : ''}}"
                                   class="form-control mobile"
                                   {{-- minlength="10"--}}
                                   maxlength="11"
                                   id="v_supplier_contact_no"
                                   name="v_supplier_contact_no"
                                   placeholder="Supplier Contact Number"
                                   autocomplete="off"

                            >
                            <span class="text-danger"></span>
                        </div>
                        <div class="col-md-3">
                            <label for="supplier_tender_reff">Tender Reference:</label>
                            <input type="text"
                                   value="{{isset($data['insertedData']->supplier_tender_reff) ? $data['insertedData']->supplier_tender_reff : ''}}"
                                   class="form-control"
                                   id="supplier_tender_reff"
                                   name="supplier_tender_reff"
                                   placeholder="Tender Reference"
                                   autocomplete="off"

                            />
                            <span class="text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row my-1">

                        <div class="col-md-3">
                            <label for="contact_person_name" class="required">Contact Person Name :</label>
                            <input type="text"
                                   value="{{isset($data['insertedData']->contact_person_name) ? $data['insertedData']->contact_person_name : ''}}"
                                   class="form-control"
                                   id="contact_person_name"
                                   name="contact_person_name"
                                   placeholder="Contact Person Name"
                                   autocomplete="off"
                                   required
                            >
                            <span class="text-danger"></span>
                        </div>

                        <div class="col-md-3">
                            <label for="contact_person_mobile" class="required">Contact Person Mobile :</label>
                            <input type="text"
                                   value="{{isset($data['insertedData']->contact_person_mobile) ? $data['insertedData']->contact_person_mobile : ''}}"
                                   class="form-control mobile"
                                   minlength="10"
                                   maxlength="11"
                                   id="contact_person_mobile"
                                   name="contact_person_mobile"
                                   placeholder="Contact Person Mobile"
                                   autocomplete="off"
                                   required
                            >
                            <span class="text-danger"></span>
                        </div>

                        <div class="col-md-3">
                            <label for="contact_start_dt" class="required">Contact Start Date</label>
                            <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                                <input type="text"
                                       value="{{isset($data['insertedData']->contact_start_dt) ? date('d-m-Y', strtotime($data['insertedData']->contact_start_dt)) : ''}}"
                                       class="form-control datetimepicker-input"
                                       data-toggle="datetimepicker" data-target="#datetimepicker1"
                                       id="contact_start_dt"
                                       name="contact_start_dt"
                                       autocomplete="off"
                                       required
                                />
                            </div>
                            <span class="text-danger"></span>
                        </div>

                        <div class="col-md-3">
                            <label for="contact_start_dt">Contact Expiry Date</label>
                            <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                                <input type="text"
                                       value="{{isset($data['insertedData']->contact_expiry_dt) ? date('d-m-Y', strtotime($data['insertedData']->contact_expiry_dt)) : ''}}"
                                       class="form-control datetimepicker-input"
                                       data-toggle="datetimepicker" data-target="#datetimepicker2"
                                       id="contact_expiry_dt"
                                       name="contact_expiry_dt"
                                       autocomplete="off"
                                />
                            </div>
                            <span class="text-danger"></span>

                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="row my-1">
                        <div class="col-sm-9">
                            <div class="form-group">
                                <label for="v_supplier_address">Address</label>
                                <textarea placeholder="Address Here"
                                          rows="2" wrap="soft"
                                          name="v_supplier_address"
                                          class="form-control"
                                          id="v_supplier_address">{!! old('supplier_address',isset($data['insertedData']->v_supplier_address) ? $data['insertedData']->v_supplier_address : '')!!}</textarea>

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
                    </div>
                </div>

                <div class="col-sm-12">
                    <fieldset class="border col-sm-12">
                        <legend class="w-auto required" style="font-size: 14px;"> Documents Attachment </legend>

                        <div class="row setup-content my-2" id="education">
                            <div class="col-md-12">
                                @include('mea.vms.supplier.attachmentdetails')
                            </div>
                        </div>

                    </fieldset>
                </div>


                <div class="col-md-12">
                    <label>&nbsp;</label>
                    <div class="d-flex justify-content-end col">
                        <button type="submit" id="submit" class="btn btn btn-dark shadow mr-1 mb-1 btn-secondary">
                            @if(empty($data['insertedData']->v_supplier_id))
                                Save
                            @else
                                Update
                            @endif
                        </button> &nbsp;

                        @if(empty($data['insertedData']->v_supplier_id))
                            <button type="reset" class="btn btn-light-secondary mb-1">
                                Reset
                            </button>
                        @else
                            <a href="{{route('supplier-index')}}">
                                <button type="button" class="btn btn-light-secondary mb-1">
                                    Reset
                                </button>
                            </a>
                        @endif
                    </div>
                </div>
            </fieldset>

        </div>

    </div>
</form>


