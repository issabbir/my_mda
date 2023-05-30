@extends('layouts.default')

@section('title')
    Authorization For
@endsection
@section('header-style')
    <style>
        :root {
            --prm-color: #039aff;
            --prm-cur-color: #99895e;
            --prm-pen-color: #b6b1a4;
            --prm-gray: rgba(177, 177, 177, 0.35);
        }

        /*  unnecessary */

        section {
            width: 100%;
        }

        /*  unnecessary finished*/

        /* CSS */
        .steps {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            position: relative;
        }

        .step-button {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: none;
            background-color: var(--prm-gray);
            transition: .4s;
        }

        .step-cur-button {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: none;
            background-color: var(--prm-gray);
            transition: .4s;
        }

        .step-pen-button {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: none;
            background-color: var(--prm-gray);
            transition: .4s;
            020102
        }

        .step-button[aria-expanded="true"] {
            width: 60px;
            height: 60px;
            background-color: var(--prm-color);
            color: #fff;
        }

        .step-cur-button[aria-expanded="true"] {
            width: 60px;
            height: 60px;
            background-color: var(--prm-cur-color);
            color: #fff;
        }

        .step-pen-button[aria-expanded="true"] {
            width: 60px;
            height: 60px;
            background-color: #b6b1a4;
            color: #fff;
        }

        .done {
            background-color: var(--prm-color);
            color: #fff;
        }

        .step-item {
            z-index: 10;
            text-align: center;
        }

        #progress {
            -webkit-appearance: none;
            position: absolute;
            width: 95%;
            z-index: 5;
            height: 10px;
            margin-left: 18px;
            margin-bottom: 18px;
        }

        /* to customize progress bar */
        #progress::-webkit-progress-value {
            background-color: var(--prm-color);
            transition: .5s ease;
        }

        #progress::-webkit-progress-bar {
            background-color: var(--prm-gray);

        }
    </style>

@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            @if(strtolower(app('request')->input('ref_ob'))==strtolower('CM_FUEL_CONSUMPTION_MST'))
                @include('cms.approval.partial.fuel_consumption_view')
            @else
                <div class="alert alert-danger" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <div class="d-flex align-items-center">
                        <i class="bx bx-error"></i>
                        <span>
                Sorry! Invalid Operation.
              </span>
                    </div>
                </div>
            @endif
            <div class="card">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="card-content">
                    <div class="card-body">
                        @include('cms.approval.partial.auth-step.process')
                        <h4 class="card-title">Authorization For # Fuel Consumption
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#authHisModal">
                                Show authorization history
                            </button>
                            <a href="" name="print_pdf" id="print_pdf" class="btn btn btn-info"> <i
                                    class="bx bxs-file-pdf"></i> Print</a>
                        </h4>
                        <form method="POST" action="{{route('approval.store')}}" enctype="multipart/form-data">
                            {!! csrf_field() !!}
                            <div class="row">
                                <input type="hidden" name="workflow_mapping_id" id="workflow_mapping_id"
                                       value="{{app('request')->input('id')}}">
                                <input type="hidden" name="cur_workflow_role" id="cur_workflow_role"
                                       value="{{$mapping_data['curWorkFlowStep']['workflow_detail']['role']}}">
                                <div class="col-md-3 mb-1">
                                    <label>is Authorized?</label>
                                    <ul class="list-unstyled mb-0">
                                        <li class="d-inline-block mr-2 mb-1">
                                            <div class="radio">
                                                <input type="radio" name="auth_status" id="approved" class="" checked
                                                       value="Y">
                                                <label for="approved">Approved</label>
                                            </div>
                                        </li>
                                        <li class="d-inline-block mr-2 mb-1">
                                            <div class="radio">
                                                <input type="radio" name="auth_status" id="reject" class="" value="N">
                                                <label for="reject">Reject</label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                @if($mapping_data['curWorkFlowStep']['workflow_detail']['role']=='CM_VESSEL_INSPECTOR' || $mapping_data['curWorkFlowStep']['workflow_detail']['role']=='SENIOR_HYDROGRAPHER_FIELD')
                                    <div class="col-md-3 mb-1">
                                        <label for="received_fuel">Assigning Fuel(Ltr)</label>
                                        <input type="text" name="received_fuel" id="received_fuel" class="form-control"
                                               value="" autocomplete="off">
                                        <input type="hidden" name="fuel_consumption_mst_id" id="fuel_consumption_mst_id"
                                               class="form-control" value="{{$data->fuel_consumption_mst_id}}">
                                        @if ($errors->has('received_fuel'))
                                            <span class="error">{{ $errors->first('received_fuel') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-md-3 mb-1">
                                        <label class="input-required" for="received_date">Assigning Date</label>
                                        <div class="input-group date" onfocusout="$(this).datetimepicker('hide')"
                                             id="received_date" data-target-input="nearest">
                                            <input type="text" name="received_date" value=""
                                                   class="form-control consumption_to" data-target="#received_date"
                                                   data-toggle="datetimepicker" autocomplete="off"/>
                                            <div class="input-group-append" data-target="#consumption_to"
                                                 data-toggle="datetimepicker">
                                                <div class="input-group-text">
                                                    <i class="bx bx-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <label>Remark</label>
                                    <textarea class="form-control" id="comment" name="comment"
                                              rows="1"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button type="submit" name="save"
                                            class="btn btn-dark shadow mr-1 mb-1"><i class="bx bxs-save"></i>Submit
                                    </button>
                                    <a type="reset" href="{{route("approval.list")}}"
                                       class="btn btn-outline-dark mb-1"><i class="bx bx-window-close"></i> Cancel</a>
                                </div>
                            </div>
                        </form>
                        @if(strtolower(app('request')->input('ref_ob'))==strtolower('CM_FUEL_CONSUMPTION_MST'))
                            <form name="report-generator" id="report-generator" method="post" target="_blank"
                                  action="{{route('report', ['title' => 'fuel consumption report'])}}">
                                {{csrf_field()}}
                                <input type="hidden" name="xdo"
                                       value="/~weblogic/MDA/CRAFT_MANAGEMENT/RPT_VESSEL_WISE_FUEL_CONSUMPTION.xdo"/>
                                <input type="hidden" name="type" id="type" value="pdf"/>
                                <input type="hidden" name="p_fuel_consumption_mst_id"
                                       id="report_fuel_consumption_mst_id" value="{{$data->fuel_consumption_mst_id}}"/>
                                <input type="hidden" name="p_vessel_id" id="report_vessel_id"
                                       value="{{$data->cpa_vessel_id}}"/>
                            </form>
                    @endif
                    <!-- Auth history  modal-->
                        <div class="modal fade" id="authHisModal" tabindex="-1" role="dialog"
                             aria-labelledby="authHisModalTitle" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="authHisModalTitle">History</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <table class="table table-sm">
                                            @foreach($mapping_data['workflowHistory'] as $log)
                                                <thead>
                                                <tr>
                                                    <th scope="col">
                                                        #{{isset($log->workflow_detail)?$log->workflow_detail->seq:''}}</th>
                                                    <th scope="col">Data</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr class="table-active">
                                                    <td>Step</td>
                                                    {{--                                                    <td>{{isset($log->workflow_detail)?$log->workflow_detail->step_name:''}}-{{$log->seq}}</td>--}}
                                                    <td>{{isset($log->workflow_detail)?$log->workflow_detail->step_name:''}}</td>
                                                </tr>
                                                <tr class="table-primary">
                                                    <td>Authorized By</td>
                                                    <td>{{isset($log->auth_related_emp)?$log->auth_related_emp->emp_name:''}}
                                                        ({{isset($log->auth_related_emp)?$log->auth_related_emp->emp_code:''}}
                                                        )
                                                    </td>
                                                </tr>
                                                <tr class="table-secondary">
                                                    <td>Authorized Date</td>
                                                    <td>{{\App\Helpers\HelperClass::defaultDateTimeFormat($log->insert_date)}}</td>
                                                </tr>
                                                <tr class="{{($log->reference_status=='Y')?'table-success':'table-danger'}}">
                                                    <td>Authorized Type</td>
                                                    <td>{{($log->reference_status=='Y')?'Approved':'Reject'}}</td>
                                                </tr>
                                                <tr class="table-warning">
                                                    <td>Comments</td>
                                                    <td>{{$log->reference_comment}}</td>
                                                </tr>
                                                {{--                                                <p>Stage:  {{isset($log->workflow_detail)?$log->workflow_detail->step_name:''}}</p>--}}
                                                {{--                                                <p>Authorized By: {{isset($log->auth_related_emp)?$log->auth_related_emp->emp_name:''}}({{isset($log->auth_related_emp)?$log->auth_related_emp->emp_code:''}})</p>--}}
                                                {{--                                                <p>Authorized Date: {{\App\Helpers\HelperClass::defaultDateTimeFormat($log->insert_date)}}</p>--}}
                                                {{--                                                <p>Authorized Type: {{($log->reference_status=='Y')?'Approved':'Reject'}}</p>--}}
                                                {{--                                                <p>Comments: {{$log->reference_comment}}</p>--}}
                                                </tbody>
                                            @endforeach
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                                        </button>
                                    </div>
                                </div>
                            </div>
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
            $('#consumption_to').datetimepicker({
                format: 'DD/MM/YYYY',
                widgetPositioning: {
                    horizontal: 'left',
                    vertical: 'bottom'
                },
                icons: {
                    date: 'bx bxs-calendar',
                    previous: 'bx bx-chevron-left',
                    next: 'bx bx-chevron-right'
                }
            });
            $('#received_date').datetimepicker({
                format: 'DD/MM/YYYY',
                widgetPositioning: {
                    horizontal: 'left',
                    vertical: 'bottom'
                },
                icons: {
                    date: 'bx bxs-calendar',
                    previous: 'bx bx-chevron-left',
                    next: 'bx bx-chevron-right'
                }
            });

            // $("#consumption_to").on("change.datetimepicker", function (e) {
            //     $('#received_date').datetimepicker('maxDate', e.date);
            // });
            $("#received_date").on("change.datetimepicker", function (e) {
                $('#received_date').datetimepicker('maxDate', $('#consumption_to').data().date);
            });
            /************setting modal title when clicking add button*********/
            $("#authHisModal").bind().on("click", function (e) {
                e.preventDefault();
            });
            $("#print_pdf").on('click', function (e) {
                e.preventDefault();
                var report_form = $("#report-generator");
                report_form.submit();
            });
            const stepButtons = document.querySelectorAll('.step-buttons');
            const progress = document.querySelector('#progress');
            Array.from(stepButtons).forEach((button, index) => {
                button.addEventListener('click', () => {
                    progress.setAttribute('value', index * 100 / (stepButtons.length - 1));//there are 3 buttons. 2 spaces.
                    stepButtons.forEach((item, secindex) => {
                        if (index > secindex) {
                            item.classList.add('done');
                        }
                        if (index < secindex) {
                            item.classList.remove('done');
                        }
                    })
                })
            })
            /************fuel consumption sum by working hours*********/
            $(document).on('input keyup keypress blur change', ".working_hours", function () {
                fuelConsumeSum();
            });
            fuelConsumeSum();

            /************fuel consumption sum function*********/
            function fuelConsumeSum() {
                let sum_of_total_fuel_consumption = 0;
                $("tbody tr").each(function () {
                    sum_of_total_fuel_consumption += parseInt(($(this).find('.total_consumed_fuel').val()) ? $(this).find('.total_consumed_fuel').val() : 0);
                });
                $("#total_fuel_consumption").text(sum_of_total_fuel_consumption);
            }

        });
    </script>
@endsection





