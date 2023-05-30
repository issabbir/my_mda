@foreach($data as $val)
    <tr>
        <td class="hidden"><input type="hidden"
                                  name="workshopReqItem_{{$val->vessel_inspection_id}}_req_item_id"  value={{$val->id}}>
        </td>
        <td class="hidden"><input type="hidden"
            name="workshopReqItem_{{$val->vessel_inspection_id}}_requisition_id" value={{$val->requisition_id}}>
        </td>
        <td class="hidden"><input type="hidden"
                                  name="workshopReqItem_{{$val->vessel_inspection_id}}_vessel_inspection_id" value={{$val->vessel_inspection_id}}>
        </td>
        <td class="hidden"><input type="hidden"
            name="workshopReqItem_{{$val->vessel_inspection_id}}_product_id" value={{$val->product_id}}>
        </td>
        <td>{{--<input type="text" class="form-control input-sm"
            name="workshopReqItem_{{$val->vessel_inspection_id}}_product_name" value="{{isset($val->product)?$val->product->name:''}}"
            readonly="readonly">--}}
            <textarea type="text" name="workshopReqItem_{{$val->vessel_inspection_id}}_product_name"
                      class="form-control input-sm" readonly>{{isset($val->product)?$val->product->name:''}}</textarea>
        </td>
        <td class="hidden"><input type="hidden" class="form-control input-sm"
                   name="workshopReqItem_{{$val->vessel_inspection_id}}_unit_id" value="{{$val->unit_id}}">
        </td>
        <td><input type="text" class="form-control input-sm"
                   name="workshopReqItem_{{$val->vessel_inspection_id}}_unit_name" value="{{isset($val->unit)?$val->unit->name:''}}" readonly="readonly">
        </td>
        <td><input type="text" class="form-control input-sm" oninput="this.value=this.value.replace(/[^0-9]/g,'');"
                   name="workshopReqItem_{{$val->vessel_inspection_id}}_demand_qty" value="{{$val->demand_qty}}" readonly="readonly">
        </td>
        <td><input type="text" class="form-control input-sm" oninput="this.value=this.value.replace(/[^0-9]/g,'');"
                   name="workshopReqItem_{{$val->vessel_inspection_id}}_received_qty" value="{{$val->received_qty}}" @if($val->status=='A') readonly @endif>
        </td>
        <td><input type="text" class="form-control input-sm" oninput="this.value=this.value.replace(/[^0-9]/g,'');"
                   name="workshopReqItem_{{$val->vessel_inspection_id}}_collected_qty" value="{{$val->collected_qty}}" readonly="readonly"  >
        </td>
        <td><input type="text" class="form-control input-sm" oninput="this.value=this.value.replace(/[^0-9]/g,'');"
                   name="workshopReqItem_{{$val->vessel_inspection_id}}_old_return_qty" value="{{$val->old_return_qty}}" readonly="readonly" >
        </td>
        <td><input type="text" class="form-control input-sm" oninput="this.value=this.value.replace(/[^0-9]/g,'');"
                   name="workshopReqItem_{{$val->vessel_inspection_id}}_used_qty" value="{{$val->used_qty}}" readonly="readonly"  >
        </td>
        <td class="hidden"><input type="hidden" class="form-control input-sm"
                                  name="workshopReqItem_{{$val->vessel_inspection_id}}_status" value="{{$val->status}}" >
        </td>
{{--
{{--        <td style="text-align: right">--}}
{{--            <button class="remove btn btn-sm btn-danger" id="remove_workshop_req_item_{{$val->vessel_inspection_id}}" name="remove_workshop_req_item_{{$val->vessel_inspection_id}}"--}}
{{--                    data-workshop_req_item_id={{$val->id}} type="button">X--}}
{{--            </button>--}}
{{--        </td>--}}
    </tr>
    @endforeach




