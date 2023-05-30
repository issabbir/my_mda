@extends('layouts.default')

@section('title')
    Slip Generation
@endsection

@section('header-style')
    <!--Load custom style link or css-->
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <h4 class="card-title"> {{ isset($data->id)?'Edit':'Add' }} Slip Generation</h4>
                        <form method="POST" @if(isset($data->id)) action="{{ route('cc-slip-generation-update', ['id' => $data->id]) }}" @else action="{{ route('cc-slip-generation-store') }}" @endif>
{{--                            {{ isset($data->id)?method_field('PUT'):'' }}--}}
                            {!! csrf_field() !!}
                            <div class="row">
{{--                                <div class="col-md-3">--}}
{{--                                    <div class="row my-1">--}}
{{--                                        <div class="col-md-12">--}}
{{--                                            <label class="input-required">Book No</label>--}}
{{--                                            <input type="text"--}}
{{--                                                   name="book_no" autocomplete="off"--}}
{{--                                                   value="{{ old("book_no", $data->book_no) }}"--}}
{{--                                                   placeholder="Book No"--}}
{{--                                                   class="form-control"--}}
{{--                                                   oninput="this.value=this.value.toUpperCase()"/>--}}
{{--                                            @if($errors->has("book_no"))--}}
{{--                                                <span class="help-block">{{$errors->first("book_no")}}</span>--}}
{{--                                            @endif--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Slip No</label>
                                            <input type="text" autocomplete="off"
                                                   name="form_no"
                                                   @if(isset($slip_no))
                                                   value="{{ $slip_no }}"
                                                   @else
                                                   value="{{ old("form_no", $data->form_no) }}"
                                                   @endif
                                                   placeholder="Slip No"
                                                   class="form-control"
                                                   readonly
                                                   oninput="this.value=this.value.toUpperCase()"/>
                                            @if($errors->has("form_no"))
                                                <span class="help-block">{{$errors->first("form_no")}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Collection Date<span class="required"></span></label>
                                            <input type="date"
                                                   name="collection_date" id="collection_date"
                                                   class="form-control bg-white" required
                                                   {{--value="{{ old('collection_date',  ($data->collection_date)?(date('Y-m-d', strtotime($data->collection_date))):date("Y-m-d")) }}"--}}
                                                   value="{{ old('collection_date',  ($data->collection_date)?(date('Y-m-d', strtotime($data->collection_date))): date('Y-m-d')) }}"
                                                   autocomplete="off">
                                            @if($errors->has("collection_date"))
                                                <span class="help-block">{{$errors->first("collection_date")}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 office_div">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Office<span class="required"></span></label>
                                            <select name="office_id" class="select2">
                                                <option value="">Select one</option>
                                                @if(isset($offices) && $offices!='')
                                                    @forelse($offices as $office)
                                                        <option
                                                            @if(isset($office_id)) {{ ($office_id == $office->office_id) ? "selected" : ""   }} @else {{ ($data->office_id == $office->office_id) ? "selected" : ""  }} @endif value="{{$office->office_id}}">{{$office->office_name}}</option>
                                                    @empty
                                                        <option value="">Office</option>
                                                    @endforelse
                                                @endif
                                            </select>
                                            @if ($errors->has('office_id'))
                                                <span class="help-block">{{ $errors->first('office_id') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
{{--                                <div class="col-md-3">--}}
{{--                                    <div class="row my-1">--}}
{{--                                        <div class="col-md-12">--}}
{{--                                            <label class="input-required">Slip Type<span--}}
{{--                                                    class="required"></span></label>--}}
{{--                                            <select name="slip_type_id" class="select2">--}}
{{--                                                <option value="">Select one</option>--}}
{{--                                                @forelse($slip_types as $slip_type)--}}
{{--                                                    <option--}}
{{--                                                        {{ ($data->slip_type_id == $slip_type->id) ? "selected" : ""  }} value="{{$slip_type->id}}">{{$slip_type->name}}</option>--}}
{{--                                                @empty--}}
{{--                                                    <option value="">Slip Type empty</option>--}}
{{--                                                @endforelse--}}
{{--                                            </select>--}}
{{--                                            @if ($errors->has('slip_type_id'))--}}
{{--                                                <span class="help-block">{{ $errors->first('slip_type_id') }}</span>--}}
{{--                                            @endif--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-md-3">--}}
{{--                                    <div class="row my-1">--}}
{{--                                        <div class="col-md-12">--}}
{{--                                            <label class="input-required">Period From<span--}}
{{--                                                    class="required"></span></label>--}}
                                            <input type="hidden"
                                                   name="period_from" id="period_from"
                                                   class="form-control bg-white" required
                                                   value="{{ old('period_from', ($data->period_from)?(date('Y-m-d', strtotime($data->period_from))):'') }}"
                                                   autocomplete="off">
{{--                                            @if ($errors->has('period_from'))--}}
{{--                                                <span class="help-block">{{ $errors->first('period_from') }}</span>--}}
{{--                                            @endif--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-md-3">--}}
{{--                                    <div class="row my-1">--}}
{{--                                        <div class="col-md-12">--}}
{{--                                            <label class="input-required">Period To<span--}}
{{--                                                    class="required"></span></label>--}}
                                            <input type="hidden"
                                                   name="period_to" id="period_to"
                                                   class="form-control bg-white" required
                                                   value="{{ old('period_to', ($data->period_to)?(date('Y-m-d', strtotime($data->period_to))):'') }}"
                                                   autocomplete="off">
{{--                                            @if ($errors->has('period_to'))--}}
{{--                                                <span class="help-block">{{ $errors->first('period_to') }}</span>--}}
{{--                                            @endif--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Local Vessel<span
                                                    class="required"></span></label>
                                            <select name="local_vessel_id" class="select2 local_vessel_id">
                                                <option value="">Select one</option>
                                                @forelse($localVesselNames as $localVesselName)
                                                    <option
                                                        {{ ($data->local_vessel_id == $localVesselName->id) ? "selected" : ""  }} value="{{$localVesselName->id}}" data-owner="{{$localVesselName->owner_name}}">@if($localVesselName->reg_no)({{$localVesselName->reg_no}}) - @endif {{$localVesselName->name}}</option>
                                                @empty
                                                    <option value="">Local Vessel Name empty</option>
                                                @endforelse
                                            </select>
                                            @if ($errors->has('local_vessel_id'))
                                                <span class="help-block">{{ $errors->first('local_vessel_id') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Owner Name</label>
                                            <input type="text" readonly
                                                   name="grt"
                                                   value="{{ old("owner_name") }}"
                                                   placeholder="Owner Name"
                                                   class="form-control owner_name"
                                                   oninput="this.value=this.value.toUpperCase()"/>
                                            @if($errors->has("owner_name"))
                                                <span class="help-block">{{$errors->first("owner_name")}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required grt_label">GRT<span class="required"></span></label>
                                            <input type="text" readonly
                                                   name="grt"
                                                   value="{{ old("grt", $data->grt) }}"
                                                   placeholder="GRT"
                                                   class="form-control grt"
                                                   oninput="this.value=this.value.toUpperCase()"/>
                                            @if($errors->has("form_no"))
                                                <span class="help-block">{{$errors->first("form_no")}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="dues_select" class="dues_select" @if(isset($dues_type)) value="{{ $dues_type }}" @elseif(isset($data->dues_select)) value="{{ $data->dues_select }}" @endif>
{{--                                <div class="col-md-3">--}}
{{--                                    <div class="row my-1">--}}
{{--                                        <div class="col-md-12">--}}
{{--                                            <label class="input-required">Select Dues<span--}}
{{--                                                    class="required"></span></label>--}}
{{--                                            <select name="dues_select" class="select2 dues_select">--}}
{{--                                                <option value="1" @if(isset($dues_type) && $dues_type == 1) selected @elseif($data->dues_select == 1) selected @endif>PORT--}}
{{--                                                    DUES--}}
{{--                                                </option>--}}
{{--                                                <option value="2" @if(isset($dues_type) && $dues_type == 2) selected @elseif($data->dues_select == 2) selected @endif>RIVER--}}
{{--                                                    DUES--}}
{{--                                                </option>--}}
{{--                                                <option value="3" @if(isset($dues_type) && $dues_type == 3) selected @elseif($data->dues_select == 3) selected @endif>BARGE--}}
{{--                                                    FEE--}}
{{--                                                </option>--}}
{{--                                                <option value="4" @if(isset($dues_type) && $dues_type == 4) selected @elseif($data->dues_select == 4) selected @endif>LICENSE--}}
{{--                                                    BILL--}}
{{--                                                </option>--}}
{{--                                            </select>--}}

{{--                                            @if ($errors->has('port_dues_amount'))--}}
{{--                                                <span class="help-block">{{ $errors->first('port_dues_amount') }}</span>--}}
{{--                                            @endif--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Amount<span
                                                    class="required"></span></label>
                                            <div class="input-group" data-target-input="nearest">
                                                <input type="text" readonly
                                                       id="dues_amount"
                                                       name="dues_amount"
                                                       value="@if($data->dues_select == 1) {{$data->port_dues_amount}}
                                                       @elseif($data->dues_select == 2) {{$data->river_dues_amount}}
                                                       @elseif($data->dues_select == 3) {{$data->barge_fee_amount}}
                                                       @elseif($data->dues_select == 4) {{$data->license_bill_amount}} @endif"
                                                       placeholder="Amount" class="form-control bg-white"
                                                       min="0"
                                                       oninput="vatCalculation()"/>
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        Taka
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($errors->has('other_dues_amount'))
                                                <span
                                                    class="help-block">{{ $errors->first('other_dues_amount') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label>Others Dues Title</label>
                                            <input type="text"
                                                   name="other_dues_title"
                                                   value="{{ old('other_dues_title', empty($data->other_dues_title)?'FINE':$data->other_dues_title) }}"
                                                   placeholder="Other Dues Title" class="form-control other_dues_title"
                                                   oninput="this.value = this.value.toUpperCase()"/>

                                            @if ($errors->has('other_dues_title'))
                                                <span class="help-block">{{ $errors->first('other_dues_title') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label>Other Dues Amount</label>
                                            <div class="input-group" data-target-input="nearest">
                                                <input type="text" autocomplete="off"
                                                       id="other_dues_amount"
                                                       name="other_dues_amount"
                                                       value="{{ old('other_dues_amount', $data->other_dues_amount) }}"
                                                       placeholder="Other Dues Amount" class="form-control"
                                                       min="0"
                                                       oninput="vatCalculation()"/>
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        Taka
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($errors->has('other_dues_amount'))
                                                <span
                                                    class="help-block">{{ $errors->first('other_dues_amount') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">VAT (<span class="text-danger">{{\App\Helpers\HelperClass::getCashCollectionVatPercentage()}}%</span>)<span
                                                    class="required"></span></label>
                                            <div class="input-group" id="leftAt" data-target-input="nearest">

                                                <input type="text"
                                                       id="vat_amount"
                                                       name="vat_amount"
                                                       value="{{ old('vat_amount', ($data->vat_amount)? $data->vat_amount:0.0) }}"
                                                       required readonly class="form-control bg-white"
                                                       aria-invalid="false">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        Taka
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Total<span class="required"></span></label>
                                            <div class="input-group" id="leftAt" data-target-input="nearest">
                                                @php
                                                    $total = $data->port_dues_amount + $data->river_dues_amount + $data->license_bill_amount + $data->barge_fee_amount + $data->other_dues_amount + $data->vat_amount;

                                                @endphp
                                                <input type="text" name="total_amount" id="total_amount"
                                                       readonly required
                                                       value="{{old('total_amount', $total)}}"
                                                       class="form-control bg-white"
                                                       aria-invalid="false">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        Taka
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Status<span class="required"></span></label>
                                            <ul class="list-unstyled mb-0">
                                                <li class="d-inline-block mr-2 mb-1">
                                                    <fieldset>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                   value="{{ old('status','P') }}"
                                                                   {{isset($data->status) && $data->status == 'P' ? 'checked' : ''}} name="status"
                                                                   id="customRadio1" checked="">
                                                            <label class="custom-control-label" for="customRadio1">Prepared</label>
                                                        </div>
                                                    </fieldset>
                                                </li>
                                                <li class="d-inline-block mr-2 mb-1">
                                                    <fieldset>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                   value="{{ old('status','A') }}"
                                                                   {{isset($data->status) && $data->status == 'A' ? 'checked' : ''}} name="status"
                                                                   id="customRadio2">
                                                            <label class="custom-control-label" for="customRadio2">Collected</label>
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
                            </div>
                            <div class="row">


                                {{--<div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Dues Amount<span
                                                    class="required"></span></label>
                                            <div class="input-group" data-target-input="nearest">
                                                <input type="number"
                                                       id="dues_amount"
                                                       name="dues_amount"
                                                       value="{{ old('dues_amount', $data->port_dues_amount) }}"
                                                       placeholder="Dues Amount"
                                                       class="form-control"
                                                       min="0"
                                                       oninput="vatCalculation()"/>
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        Taka
                                                    </div>
                                                </div>
                                            </div>

                                            @if ($errors->has('port_dues_amount'))
                                                <span class="help-block">{{ $errors->first('port_dues_amount') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">River Dues<span
                                                    class="required"></span></label>
                                            <div class="input-group" data-target-input="nearest">
                                                <input type="text"
                                                       id="river_dues_amount"
                                                       name="river_dues_amount"
                                                       value="{{ old('river_dues_amount', $data->river_dues_amount) }}"
                                                       placeholder="River Dues Amount" class="form-control"
                                                       min="0"
                                                       oninput="vatCalculation()"/>
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        Taka
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($errors->has('river_dues_amount'))
                                                <span
                                                    class="help-block">{{ $errors->first('river_dues_amount') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">License Bill<span
                                                    class="required"></span></label>
                                            <div class="input-group" data-target-input="nearest">
                                                <input type="text"
                                                       id="license_bill_amount"
                                                       name="license_bill_amount"
                                                       value="{{ old('license_bill_amount', $data->license_bill_amount) }}"
                                                       placeholder="License Bill" class="form-control"
                                                       min="0"
                                                       oninput="vatCalculation()"/>
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        Taka
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($errors->has('license_bill_amount'))
                                                <span
                                                    class="help-block">{{ $errors->first('license_bill_amount') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>--}}

                            </div>
                            {{--<div class="row">
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Others Dues Title<span
                                                    class="required"></span></label>
                                            <input type="text"
                                                   name="other_dues_title"
                                                   value="{{ old('other_dues_title', empty($data->other_dues_title)?'FINE':$data->other_dues_title) }}"
                                                   placeholder="Other Dues Title" class="form-control"
                                                   oninput="this.value = this.value.toUpperCase()"/>

                                            @if ($errors->has('other_dues_title'))
                                                <span class="help-block">{{ $errors->first('other_dues_title') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Others Dues Amount<span
                                                    class="required"></span></label>
                                            <div class="input-group" data-target-input="nearest">
                                                <input type="text"
                                                       id="other_dues_amount"
                                                       name="other_dues_amount"
                                                       value="{{ old('other_dues_amount', $data->other_dues_amount) }}"
                                                       placeholder="Other Dues Amount" class="form-control"
                                                       min="0"
                                                       oninput="vatCalculation()"/>
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        Taka
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($errors->has('other_dues_amount'))
                                                <span
                                                    class="help-block">{{ $errors->first('other_dues_amount') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>--}}
                            <div class="row">

                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row my-1">
                                        <div class="d-flex justify-content-end col">
                                            <button type="submit" name="save" onclick="return confirm('Are you sure?')"
                                                    class="btn btn btn-dark shadow mr-1 mb-1">  {{ isset($data->id)?'Update':'Save' }} </button>
                                            <a type="reset" href="{{route("cc-slip-generation")}}"
                                               class="btn btn btn-outline-dark  mb-1"> Cancel</a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!--List-->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"> Slip List</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <form method="POST" action="" id="search-form">
                            {{ isset($data->id)?method_field('PUT'):'' }}
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Collection From Date<span class="required"></span></label>
                                            <input type="date"
                                                   name="from_date" id="from_date" class="form-control" required
                                                   value=""
                                                   autocomplete="off">
                                            @if($errors->has("from_date"))
                                                <span class="help-block">{{$errors->first("from_date")}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Collection To Date<span
                                                    class="required"></span></label>
                                            <input type="date"
                                                   name="to_date" id="to_date" class="form-control" required
                                                   value=""
                                                   autocomplete="off">
                                            @if($errors->has("to_date"))
                                                <span class="help-block">{{$errors->first("to_date")}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">&nbsp;</label>
                                            <div class="d-flex justify-content-end col">
                                                <button type="button" name="search" id="search"
                                                        class="btn btn btn-dark shadow mr-1 mb-1"> Search
                                                </button>
                                                <a href="javascript:void(0)"
                                                   class="btn btn btn-outline-dark mb-1"> Reset</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-sm datatable">
                                <thead>
                                <tr>
{{--                                    <th>SL</th>--}}
                                    <th>SL</th>
{{--                                    <th>TYPE</th>--}}
                                    <th>VESSEL</th>
                                    <th>OWNER NAME</th>
{{--                                    <th>OFFICE</th>--}}
                                    <th class="dues_name">PORT DUES</th>
{{--                                    <th>CARGO DUES</th>--}}
                                    <th>OTHER DUES TITLE</th>
                                    <th>OTHER DUES AMOUNT</th>
                                    <th>VAT AMOUNT</th>
                                    <th>TOTAL AMOUNT</th>
                                    <th>PERIOD FORM</th>
                                    <th>PERIOD TO</th>
                                    <th>COLLECTION DATE</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection


@section('footer-script')
    <!--Load custom script-->
    <script>
        $(".local_vessel_id").on('change', function (e) {
            let vessel_id = $(this).val();
            let dues_type = $('.dues_select').val();
            if(dues_type != 2) {
                let vdata = '{{route('vessel-data')}}';
                $.ajax({
                    type: 'get',
                    url: vdata,
                    data: {vessel_id: vessel_id},
                    success: function (msg) {
                        $(".grt").val(msg);
                        // portDueCalculation(msg);
                        $('.dues_select').trigger('change')
                        vatCalculation();
                    }
                });
            }
        });

        $(".grt").on('keyup', function (e) {
            let grt = $(this).val();
            let dues_type = $('.dues_select').val();
            if(dues_type == 2) {
                $("#dues_amount").val(Number($(".grt").val()) * 10);
                vatCalculation();
            }
        });

        function portDueCalculation(msg) {
            if (msg <= 9) {
                $("#dues_amount").val(0);
            } else if (msg <= 10 || msg <= 100) {
                let str = $('#collection_date').val(); // this would be your date
                let res = str.split("-"); // turn the date into a list format (Split by / if needed)
                let months = ["Jan", "Feb", "March", "April", "May", "June", "July",
                    "August", "September", "October", "November", "December"];
                if (months[res[1] - 1] == 'Jan' || months[res[1] - 1] == 'July') {
                    $("#dues_amount").val(200);
                } else {
                    $("#dues_amount").val(300);
                }
            } else if (msg <= 101 || msg <= 200) {
                let str = $('#collection_date').val(); // this would be your date
                let res = str.split("-"); // turn the date into a list format (Split by / if needed)
                let months = ["Jan", "Feb", "March", "April", "May", "June", "July",
                    "August", "September", "October", "November", "December"];
                if (months[res[1] - 1] == 'Jan' || months[res[1] - 1] == 'July') {
                    $("#dues_amount").val(500);
                } else {
                    $("#dues_amount").val(750);
                }
            } else if (msg >= 201) {
                let str = $('#collection_date').val(); // this would be your date
                let res = str.split("-"); // turn the date into a list format (Split by / if needed)
                let months = ["Jan", "Feb", "March", "April", "May", "June", "July",
                    "August", "September", "October", "November", "December"];
                if (months[res[1] - 1] == 'Jan' || months[res[1] - 1] == 'July') {
                    $("#dues_amount").val(msg * 5);
                } else {
                    $("#dues_amount").val(msg * 7.5);
                }
            }
        }

        $(".dues_select").on('change', function (e) {
            let dues_select = $(this).val();
            if (dues_select == 2) {
                $("#dues_amount").val(Number($(".grt").val()) * 10);
                vatCalculation();
            } else if (dues_select == 3) {
                $("#dues_amount").val(Number($(".grt").val()) + 1);
                vatCalculation();
            } else if (dues_select == 1) {
                portDueCalculation(Number($(".grt").val()));
                vatCalculation();
            } else if (dues_select == 4) {
                $("#dues_amount").val(Number($(".grt").val()));
                vatCalculation();
            }
        });

        $('#collection_date').change(function () {
            let getdate = $(this).val();
            const date = new Date(getdate);

            const start = new Date('2023-01-01');
            const end = new Date('2023-06-30');

            if (date > start && date < end) {
                $('#period_from').val('2023-01-01');
                $('#period_to').val('2023-06-30');
            } else {
                $('#period_from').val('2023-07-01');
                $('#period_to').val('2023-12-31');
            }
        });

        $(document).ready(function () {
            $('#collection_date').trigger('change')
            $('.office_div').hide()
            let dues_type = '';
            @if(isset($dues_type))
                dues_type = {{ $dues_type }}
            @elseif(isset($data->dues_select))
                dues_type = {{ $data->dues_select }}
            @endif
            if(dues_type == 1){
                $('.dues_name').text('Port Dues')
            }else if(dues_type == 2){
                $('.grt_label').text('Cargo').after('<span class="required"></span>')
                $('.grt').prop('readonly', false).attr('placeholder', 'Cargo')
                $('.dues_name').text('River Dues')
            }else if(dues_type == 3){
                $('.dues_name').text('Barge Fee')
            }else if(dues_type == 4){
                $('.dues_name').text('License Bill')
            }
            var oTable = $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 20,
                ordering: false,
                lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
                initComplete: function (settings, json) {
                    $('body').find('.dataTables_scrollBody').css("height", "auto");
                    $('body').find('.dataTables_scrollBody').css("max-height", "300px");
                },
                ajax: {
                    url: '{{ route('cc-slip-generation-datatable', isset($data->id)?$data->id:0 ) }}',
                    data: function (d) {
                        d.from_date = $('input[name=from_date]').val();
                        d.to_date = $('input[name=to_date]').val();
                        d.dues_type = $('.dues_select').val();
                    },
                    type: 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                "columns": [
                    // {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                    {"data": "form_no"},
                    // {"data": "slip_type"},
                    {"data": "local_vessel_name"},
                    {"data": "owner_name"},
                    // {"data": "office_name"},
                    // {"data": "port_dues_amount"},
                    // {"data": "river_dues_amount"},
                    {"data": "dues_amount"},
                    {"data": "other_dues_title"},
                    {"data": "other_dues_amount"},
                    {"data": "vat_amount"},
                    {"data": "total"},
                    {"data": "period_form"},
                    {"data": "period_to"},
                    {"data": "collection_date"},
                    {"data": "status"},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                language: {
                    paginate: {
                        next: '<i class="bx bx-chevron-right">',
                        previous: '<i class="bx bx-chevron-left">'
                    }
                }
            });
            $('#search').on('click', function (e) {
                oTable.draw();
                e.preventDefault();
            });
        });

        $('.local_vessel_id').on('change', function (e) {
            let owner_name = $(this).find(':selected').data('owner')
            if (owner_name) {
                $(".owner_name").val(owner_name);
            } else {
                $(".owner_name").val('');
            }
        });

        function vatCalculation() {
            var vatPercentage = Number('{{\App\Helpers\HelperClass::getCashCollectionVatPercentage()}}');
            //var portDues = $('#port_dues_amount').val();
            var allDues = $('#dues_amount').val();
            //var reviewDues = $('#river_dues_amount').val();
            //var licenseBill = $('#license_bill_amount').val();
            var otherDues = $('#other_dues_amount').val();
            //var excludeVatTotalAmt = Number(portDues) + Number(reviewDues) + Number(otherDues) + Number(licenseBill);
            var excludeVatTotalAmt = Number(otherDues) + Number(allDues);
            var vat_amount = parseFloat((vatPercentage / 100) * excludeVatTotalAmt).toFixed(2);
            $('#vat_amount').val(vat_amount);
            $('#total_amount').val(parseFloat(Number(excludeVatTotalAmt) + Number(vat_amount)).toFixed(2));
        }
    </script>

@endsection
