<div class="col-md-3">
    <div class="card mb-2">
        <div class="card-body">
            <h4 class="card-title">Description Of Previous Fuel Receiving</h4>
            @if(!$prv_consumption_data)
                <div class="alert alert-warning" role="alert">Sorry! there is no previous fuel consumption statement found
                </div>
            @endif
            <div class="row">
                <div class="col-md-12 mb-1">
                    <label class="input-required" for="last_received_date">Last Consumption Ref. No</label>
                    <input type="text" name="last_fuel_consumption_no" id="last_fuel_consumption_no" class="form-control" value="{{($prv_consumption_data)?$prv_consumption_data->last_fuel_consumption_ref_no:''}}" readonly>
                </div>
                <div class="col-md-12 mb-1">
                    <label class="input-required" for="last_received_date">Received Date</label>
                    <input type="text" name="last_received_date" id="last_received_date" class="form-control" value="{{($prv_consumption_data)?$prv_consumption_data->last_received_date:''}}" readonly>
                </div>
                <div class="col-md-12 mb-1">
                    <label  for="reserved_fuel">Reserved Fuel(Ltr) on received time</label>
                    <input type="text" name="reserved_fuel" id="reserved_fuel"
                           value="{{($prv_consumption_data)?$prv_consumption_data->prv_reserved_fuel:''}}" class="form-control reserved_fuel" readonly/>
                </div>
                <div class="col-md-12 mb-1">
                    <label for="received_fuel">Received Fuel(Ltr)</label>
                    <input type="text" name="received_fuel" id="received_fuel"
                           value="{{($prv_consumption_data)?$prv_consumption_data->received_fuel:''}}" class="form-control received_fuel"  readonly/>
                </div>
                {{--                <div class="col-md-12 mb-1">--}}
                {{--                    <label for="total_fuel">Total Fuel(Ltr)</label>--}}
                {{--                    <input type="text" name="total_fuel" id="total_fuel"--}}
                {{--                           value="{{($prv_consumption_data)?($prv_consumption_data->received_fuel)+($prv_consumption_data->reserved_fuel):''}}"--}}
                {{--                           class="form-control total_fuel"  autocomplete="off" readonly/>--}}
                {{--                </div>--}}
            </div>
        </div>
    </div>
</div>
