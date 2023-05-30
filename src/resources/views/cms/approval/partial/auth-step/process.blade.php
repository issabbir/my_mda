<div class="accordion" id="accordionExample">
    <div class="steps">
        @if(count($mapping_data['allActiveWorkFlow'])>0)
        @php
            $stepValue=(100*$mapping_data['proceed_step'])/count($mapping_data['allActiveWorkFlow']);
        @endphp
        <progress id="progress" value={{$stepValue}} max=100></progress>
        @foreach($mapping_data['allActiveWorkFlow'] as $i=>$step)
            <div class="step-item">
                @if($mapping_data['curWorkFlowStep']['seq']==$step->seq && $stepValue!=100)
                    <button
                        class="step-cur-button text-center"
                        type="button" data-toggle="collapse"
                        data-target="#collapse_{{$step->workflow_mapping_id}}" aria-expanded="true"
                        aria-controls="collapse_{{$step->workflow_mapping_id}}">
{{--                        <span class="bx bxs-pin">{{$step->seq}}</span>--}}
                        <span class="bx bxs-pin">{{++$i}}</span>
                    </button>
                @elseif($mapping_data['curWorkFlowStep']['seq']<$step->seq)
                    <button
                        class="step-pen-button text-center"
                        type="button" data-toggle="collapse"
                        data-target="#collapse_{{$step->workflow_mapping_id}}" aria-expanded="true"
                        aria-controls="collapse_{{$step->workflow_mapping_id}}">
{{--                        {{$step->seq}}--}}
                        {{++$i}}
                    </button>
                @else
                    <button
                        class="step-button text-center"
                        type="button" data-toggle="collapse"
                        data-target="#collapse_{{$step->workflow_mapping_id}}" aria-expanded="true"
                        aria-controls="collapse_{{$step->workflow_mapping_id}}">
{{--                        <span class="bx bx-check">{{$step->seq}}</span>--}}
                        <span class="bx bx-check">{{++$i}}</span>
                    </button>
                @endif
                <div class="step-title">
                    {{isset($step->workflow_detail)?$step->workflow_detail->step_name:''}}
                    {{($mapping_data['curWorkFlowStep']['seq']==$step->seq)?'(CURRENT STEP)':''}}
                </div>
            </div>
        @endforeach
            <div class="step-item">
                    <button
                        class="{{($stepValue==100)?'step-button':'step-pen-button'}} text-center"
                        type="button" data-toggle="collapse"
                        data-target="#collapse_{{$step->workflow_mapping_id}}" aria-expanded="true"
                        aria-controls="collapse_{{$step->workflow_mapping_id}}">
                        <span>END</span>
                    </button>
                <div class="step-title">
                    APPROVED
                </div>
            </div>
        @endif
    </div>
</div>














