{{--{{dd($task)}}--}}
@foreach($task as $val)
    <div class="bs-example">
        <div class="accordion" id="accordionExample">
            <div class="card">
                <div class="card-header" id="headingOne">
                    <h2 class="mb-0">
                        <button
                            class="btn btn-link btn-block text-left btn-secondary"
                            type="button"
                            {{--@if($val->tp_req_yn=='N' && $val->status!='C')
                            data-toggle="collapse"
                            @endif--}}
                            data-toggle="collapse"
                            id="show_task_details_{{$val->id}}"
                            data-target="#collapse-{{$val->id}}"
                            aria-expanded="true"
                            aria-controls="collapse-{{$val->id}}">
                            # {{$val->name}}
                            <i class='bx bxs-plus-circle bx-pull-right bx-md' style="top: auto"></i>
                            @if($val->tp_req_yn=='Y')
                                <span id="requisition_status_{{$val->id}}" style="margin-top: 6px;"
                                      class="bx-pull-right mr-5"> STATUS :
                                <strong> REQUESTED FOR WORKS</strong>
                            </span>
                            @elseif($val->tp_req_yn=='C')
                                <span id="requisition_status_{{$val->id}}" style="margin-top: 6px;"
                                      class="bx-pull-right mr-5"> STATUS :
                                <strong> COMPLETED</strong>
                            </span>
                            @elseif($val->status=='A')
                                <span id="requisition_status_{{$val->id}}" style="margin-top: 6px;"
                                      class="bx-pull-right mr-5"> STATUS :
                                <strong> APPROVED</strong>
                            </span>
                            @else
                                <span id="requisition_status_{{$val->id}}" style="margin-top: 6px"
                                      class="bx-pull-right mr-5"> STATUS :
                                <strong> {{(!$val->status)?'NOT INITIALIZED':App\Helpers\HelperClass::getRequisitionStatus($val->status)}}</strong>
                            </span>
                            @endif
                            {{--@if($val->tp_req_yn=='Y')
                                <span id="requisition_status_{{$val->id}}" style="margin-top: 6px; color:red;"
                                      class="bx-pull-right mr-5">
                                <strong> REQUESTED FOR THIRD PARTY</strong>
                            </span>
                            @endif--}}
                        </button>
                    </h2>
                </div>
                <div id="collapse-{{$val->id}}" class="collapse" aria-labelledby="headingOne"
                     data-parent="#accordionExample">
                    <div class="card-body">
                        @include('mwe.partial.requisition_item')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach


