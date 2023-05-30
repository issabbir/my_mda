@if(!empty($fuel_items))
    @foreach($fuel_items as $items)
        <tr class="fuel_consumption_items">
            <td class="hidden">
                <input type="hidden" name="fuel_consumption_dtl_id[]" id="fuel_consumption_dtl_id" class="form-control fuel_consumption_dtl_id" value="{{$items->fuel_consumption_dlt_id}}">
            </td>
            <td class="hidden">
                <input type="hidden" name="vessel_engine_id[]" id="vessel_engine_id" class="form-control vessel_engine_id" value="{{$items->vessel_engine_id}}">
            </td>
            <td colspan="4">
                <input type="text" name="engine_name[]" id="engine_name" class="form-control engine_name" value="{{($items->engine)?$items->engine->engine->engine_name:''}}" readonly>
            </td>
{{--            <td colspan="4">--}}
{{--                <input type="text" name="engine_fuel_type[]" id="engine_fuel_type" class="form-control engine_fuel_type" value="{{($items->engine)?$items->engine->fuel_type->fuel_type_name:''}}" readonly>--}}
{{--            </td>--}}
            <td>
                <input type="text" name="working_hours[]" id="working_hours" class="form-control working_hours" value="{{$items->working_hours}}" autocomplete="off" >
            </td>
            <td>
                <input type="text" name="hourly_consumed_fuel[]" id="hourly_consumed_fuel" class="form-control hourly_consumed_fuel" value="{{$items->hourly_consumed_fuel}}" readonly>
            </td>
            <td>
                <input type="text" name="total_consumed_fuel[]" id="total_consumed_fuel" class="form-control total_consumed_fuel" style="text-align: right" value="{{$items->total_consumed_fuel}}" readonly>
            </td>
            <td>
                <input type="text" name="item_remarks[]" id="item_remarks" class="form-control item_remarks" value="{{$items->remarks}}">
            </td>
        </tr>
    @endforeach
@else
    @foreach($vessel_engine as $val)
        <tr class="fuel_consumption_items">
            <td class="hidden">
                <input type="hidden" name="fuel_consumption_dtl_id[]" id="fuel_consumption_dtl_id" class="form-control fuel_consumption_dtl_id" value="">
            </td>
            <td class="hidden">
                <input type="hidden" name="vessel_engine_id[]" id="vessel_engine_id" class="form-control vessel_engine_id" value="{{$val->vessel_engine_id}}">
            </td>
            <td colspan="4">
                <input type="text" name="engine_name[]" id="engine_name" class="form-control engine_name" value="{{($val->engine)?$val->engine->engine_name:''}}" readonly>
            </td>
{{--            <td colspan="4">--}}
{{--                <input type="text" name="engine_fuel_type[]" id="engine_fuel_type" class="form-control engine_fuel_type" value="{{($val->fuel_type)?$val->fuel_type->fuel_type_name:''}}" readonly>--}}
{{--            </td>--}}
            <td>
                <input type="text" name="working_hours[]" id="working_hours" class="form-control working_hours" value=""  autocomplete="off">
            </td>
            <td>
                <input type="text" name="hourly_consumed_fuel[]" id="hourly_consumed_fuel" class="form-control hourly_consumed_fuel" value="{{$val->hourly_consumed_fuel}}" readonly>
            </td>
            <td>
                <input type="text" name="total_consumed_fuel[]" id="total_consumed_fuel" class="form-control total_consumed_fuel" style="text-align: right" value="" readonly>
            </td>
            <td>
                <input type="text" name="item_remarks[]" id="item_remarks" class="form-control item_remarks" value="">
            </td>
        </tr>
    @endforeach
@endif

