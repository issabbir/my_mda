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
                        <h4 class="card-title"> {{ isset($data->mooring_charge_id)?'Edit':'Add' }} Mooring Charge</h4>
                        <form method="POST" action="">
                            {{ isset($data->mooring_charge_id)?method_field('PUT'):'' }}
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Form No<span class="required"></span></label>
                                            <input type="text"
                                                   name="form_no"
                                                   value="{{ old("form_no", $data->form_no) }}"
                                                   placeholder="Form No"
                                                   class="form-control"
                                                   oninput="this.value=this.value.toUpperCase()" />
                                            @if($errors->has("form_no"))
                                                <span class="help-block">{{$errors->first("form_no")}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Collection Date<span class="required"></span></label>
                                            <input type="date"
                                                   name="collection_date" id="collection_date"
                                                   class="form-control bg-white" required
                                                   value="{{ old('collection_date',  ($data->collection_date)?(date('Y-m-d', strtotime($data->collection_date))):date("Y-m-d")) }}"
                                                   autocomplete="off">
                                            @if($errors->has("collection_date"))
                                                <span class="help-block">{{$errors->first("collection_date")}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Slip Type<span class="required"></span></label>
                                            <select name="slip_type_id" class="select2">
                                                <option value="">Select one</option>
                                                @forelse($slip_types as $slip_type)
                                                    <option {{ ($data->slip_type_id == $slip_type->id) ? "selected" : ""  }} value="{{$slip_type->id}}">{{$slip_type->name}}</option>
                                                @empty
                                                    <option value="">Local Vessel Name empty</option>
                                                @endforelse
                                            </select>
                                            @if ($errors->has('slip_type_id'))
                                                <span class="help-block">{{ $errors->first('slip_type_id') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Local Vessel<span class="required"></span></label>
                                            <select name="local_vessel_id" class="select2">
                                                <option value="">Select one</option>
                                                @forelse($localVesselNames as $localVesselName)
                                                    <option {{ ($data->local_vessel_id == $localVesselName->id) ? "selected" : ""  }} value="{{$localVesselName->id}}">{{$localVesselName->name}}</option>
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

                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Period From<span class="required"></span></label>
                                            <input type="date"
                                                   name="period_from" id="period_from"
                                                   class="form-control bg-white" required
                                                   value="{{ old('period_from', ($data->period_from)?(date('Y-m-d', strtotime($data->period_from))):date("Y-m-d")) }}"
                                                   autocomplete="off">
                                            @if ($errors->has('period_from'))
                                                <span class="help-block">{{ $errors->first('period_from') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Period To<span class="required"></span></label>
                                            <input type="date"
                                                   name="period_to" id="period_to"
                                                   class="form-control bg-white" required
                                                   value="{{ old('period_to', ($data->period_to)?(date('Y-m-d', strtotime($data->period_to))):date("Y-m-d")) }}"
                                                   autocomplete="off">
                                            @if ($errors->has('period_to'))
                                                <span class="help-block">{{ $errors->first('period_to') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Mooring Charge<span class="required"></span></label>
                                            <div class="input-group" data-target-input="nearest">
                                                <input type="number"
                                                       id="mooring_charge_amnt"
                                                       name="mooring_charge_amnt"
                                                       value="{{ old('mooring_charge_amnt', $data->mooring_charge_amnt) }}"
                                                       placeholder=" Mooring Charge"
                                                       class="form-control"
                                                       min="0"
                                                       oninput="vatCalculation()"/>
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        Taka
                                                    </div>
                                                </div>
                                            </div>

                                            @if ($errors->has('mooring_charge_amnt'))
                                                <span class="help-block">{{ $errors->first('mooring_charge_amnt') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">VAT (<span class="text-danger">{{\App\Helpers\HelperClass::getCashCollectionVatPercentage()}}%</span>)<span class="required"></span></label>
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

                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Total<span class="required"></span></label>
                                            <div class="input-group" id="leftAt" data-target-input="nearest">
                                                @php
                                                    $total = $data->mooring_charge_amnt + $data->vat_amount;
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

                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Status<span class="required"></span></label>
                                            <ul class="list-unstyled mb-0">
                                                <li class="d-inline-block mr-2 mb-1">
                                                    <fieldset>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input" value="{{ old('status','P') }}" {{isset($data->status) && $data->status == 'P' ? 'checked' : ''}} name="status" id="customRadio1" checked="">
                                                            <label class="custom-control-label" for="customRadio1">Prepared</label>
                                                        </div>
                                                    </fieldset>
                                                </li>
                                                <li class="d-inline-block mr-2 mb-1">
                                                    <fieldset>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input" value="{{ old('status','A') }}" {{isset($data->status) && $data->status == 'A' ? 'checked' : ''}} name="status" id="customRadio2">
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
                                <div class="col-md-12">
                                    <div class="row my-1">
                                        <div class="d-flex justify-content-end col">
                                            <button type="submit" onclick="return confirm('Are you sure?')" name="save" class="btn btn btn-dark shadow mr-1 mb-1">  {{ isset($data->id)?'Update':'Save' }} </button>
                                            <a type="reset" href="{{route("cc-slip-generation")}}" class="btn btn btn-outline-dark  mb-1"> Cancel</a>
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
                        <div class="table-responsive">
                            <table class="table table-sm datatable">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>FORM NO</th>
                                    <th>TYPE</th>
                                    <th>VESSEL</th>
                                    <th>Mooring Charge</th>
                                    <th>VAT AMOUNT</th>
                                    <th>TOTAL AMOUNT</th>
                                    <th>PERIOD FORM</th>
                                    <th>PERIOD TO</th>
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
        $(document).ready(function () {
            $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 20,
                lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
                initComplete: function(settings, json) {
                    $('body').find('.dataTables_scrollBody').css("height", "auto");
                    $('body').find('.dataTables_scrollBody').css("max-height", "300px");
                },
                ajax: {
                    url:'{{ route('mooring-charge-datatable', isset($data->id)?$data->id:0 ) }}',
                    type:'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                "columns": [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex' },
                    {"data": "form_no"},
                    {"data": "slip_type.name"},
                    {"data": "local_vessel.name"},
                    {"data": "mooring_charge_amnt"},
                    {"data": "vat_amount"},
                    {"data": "total"},
                    {"data": "period_form"},
                    {"data": "period_to"},
                    {"data":"status"},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                language: {
                    paginate: {
                        next: '<i class="bx bx-chevron-right">',
                        previous: '<i class="bx bx-chevron-left">'
                    }
                }
            });
        });

        function vatCalculation() {
            var vatPercentage = Number('{{\App\Helpers\HelperClass::getCashCollectionVatPercentage()}}');
            var dues = $('#mooring_charge_amnt').val();
            var excludeVatTotalAmt = Number(dues);
            var vat_amount = parseFloat((vatPercentage/100)*excludeVatTotalAmt).toFixed(2) ;
            $('#vat_amount').val(vat_amount);
            $('#total_amount').val(parseFloat(Number(excludeVatTotalAmt) + Number(vat_amount)).toFixed(2));
        }
    </script>

@endsection
