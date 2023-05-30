@if(!empty($schedule_items))
    @foreach($schedule_items as $items)
{{--        <tr class="quick-item-row" id="new_item">--}}
{{--            <td class="hidden">--}}
{{--                <input type="hidden" name="employee_duty_id"  class="form-control employee_duty_id" value="{{$items->employee_duty_id}}">--}}
{{--            </td>--}}
{{--            <td class="hidden">--}}
{{--                <input type="hidden" name="employee_id"  class="form-control employee_id" value="{{$items->employee_id}}">--}}
{{--            </td>--}}
{{--            <td>--}}
{{--                <input type="text" name="employee_code"  class="form-control employee_code" value="{{($items->employee)?$items->employee->emp_code:''}}" readonly>--}}
{{--            </td>--}}
{{--            <td>--}}
{{--                <input type="text" name="employee_name"  class="form-control employee_name" value="{{($items->employee)?$items->employee->emp_name:''}}" readonly>--}}
{{--            </td>--}}
{{--            <td class="hidden">--}}
{{--                <input type="hidden" name="designation_id"  class="form-control designation_id" value="{{$items->designation_id}}">--}}
{{--            </td>--}}
{{--            <td>--}}
{{--                <input type="text" name="designation" class="form-control designation" value="{{($items->designation)?$items->designation->designation:''}}"  readonly>--}}
{{--            </td>--}}
{{--            <td class="hidden">--}}
{{--                <input type="hidden" name="placement_id"  class="form-control placement_id" value="{{$items->placement_id}}">--}}
{{--            </td>--}}
{{--            <td>--}}
{{--                @if($items->placement_type_id=='1')--}}
{{--                    <input type="text" name="placement"  class="form-control placement" value="{{($items->vessel_placement)?$items->vessel_placement->name:''}}" readonly>--}}
{{--                @else--}}
{{--                    <input type="text" name="placement"  class="form-control placement" value="{{($items->placement)?$items->placement->placement_name:''}}" readonly>--}}
{{--                @endif--}}
{{--            </td>--}}
{{--            <td>--}}
{{--                <input type="text" name="joining_date"  class="form-control joining_date" value="{{\App\Helpers\HelperClass::defaultDateTimeFormat($items->joining_date,'LOCALDATE')}}" readonly>--}}
{{--            </td>--}}
{{--            <td>--}}
{{--                <input type="text" name="off_day"  class="form-control off_day" value="{{$items->off_day}}" readonly>--}}
{{--            </td>--}}
{{--            <td>--}}
{{--                <div style="display: inline-flex">--}}
{{--                    <a size="sm"--}}
{{--                       href="{{route('cms.shifting.duties-edit',$items->employee_duty_id)}}"--}}
{{--                       href=""--}}
{{--                       target="_blank"--}}
{{--                       data-url="{{route('cms.shifting.duties-edit',$items->employee_duty_id)}}"--}}
{{--                       data-employee_duty_id="{{$items->employee_duty_id}}"--}}
{{--                       class="btn btn-secondary text-right mr-1 edit_item_btn" id="edit_item_btn">--}}
{{--                        <i class="bx bx-edit"></i></a>--}}
{{--                    <a size="sm" class="btn btn-primary text-right mr-1" target="_blank" data-toggle="tooltip" data-placement="top" title="Click to add shift"--}}
{{--                       href="{{route('cms.shifting.shift',['employee_duty_id'=>$items->employee_duty_id])}}">--}}
{{--                        <i class="bx bxs-timer cursor-pointer"></i></a>--}}
{{--                    <a size="sm" href="javascript:void(0);"--}}
{{--                       class="quick_item_remove_button btn btn-danger text-right" id="remove_item_btn">--}}
{{--                        <i class="bx bx-x"></i></a>--}}
{{--                </div>--}}
{{--            </td>--}}
{{--        </tr>--}}
        <tr class="quick-item-row">
            <td class="hidden">
                {{$items->employee_duty_id}}
            </td>
            <td class="hidden">
                {{$items->employee_id}}
            </td>
            <td>
                {{($items->employee)?$items->employee->emp_code:''}}
            </td>
            <td>
                {{($items->employee)?$items->employee->emp_name:''}}
            </td>
            <td class="hidden">
                {{$items->designation_id}}
            </td>
            <td>
                {{($items->designation)?$items->designation->designation:''}}
            </td>
            <td class="hidden">
                {{$items->placement_id}}
            </td>
            <td>
                @if($items->placement_type_id=='1')
                    {{($items->vessel_placement)?$items->vessel_placement->name:''}}
                @else
                    {{($items->placement)?$items->placement->placement_name:''}}
                @endif
            </td>
            <td>
                {{\App\Helpers\HelperClass::defaultDateTimeFormat($items->joining_date,'LOCALDATE')}}
            </td>
            <td>
                {{$items->off_day}}
            </td>
            <td>
                <div style="display: inline-flex">
                    <a size="sm"
                       href="{{route('cms.shifting.duties-edit',$items->employee_duty_id)}}"
                       {{--                       href=""--}}
                       target="_blank"
                       data-url="{{route('cms.shifting.duties-edit',$items->employee_duty_id)}}"
                       data-employee_duty_id="{{$items->employee_duty_id}}"
                       class="btn btn-secondary text-right mr-1 edit_item_btn" id="edit_item_btn">
                        <i class="bx bx-edit"></i></a>
                    <a size="sm" class="btn btn-primary text-right mr-1" target="_blank" data-toggle="tooltip" data-placement="top" title="Click to add shift"
                       href="{{route('cms.shifting.shift',['employee_duty_id'=>$items->employee_duty_id])}}">
                        <i class="bx bxs-timer cursor-pointer"></i></a>
                    {{--                    <a size="sm" href="javascript:void(0);"--}}
                    {{--                       class="quick_item_remove_button btn btn-danger text-right" id="remove_item_btn">--}}
                    {{--                        <i class="bx bx-x"></i></a>--}}
                </div>
            </td>
        </tr>
    @endforeach
@endif



