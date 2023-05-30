@extends('layouts.default')

@section('title')
    Pilotage certificate detail
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
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="card-title" style="text-align: center">ARRIVAL REPORT OF VESSEL <br> AND <br>
                                    <u>
                                        @if($data->pilotage_type_id == 1)
                                            INWARD
                                        @elseif($data->pilotage_type_id == 2)
                                            SHIFTING
                                        @else
                                            OUTWARD
                                        @endif
                                        PILOTAGE CERTIFICATE
                                    </u></h4>
                                <br>
                                <div class="mx-3">
                                    @php $i=1; @endphp
                                    <p>{{$i++}}. VESSELS NAME: <b><u>{{$data->foreign_vessel->name}}</u></b>. CALL SIGN:
                                        <b>{{ $data->call_sign }}</b>. FLAG:
                                        <b>{{ $data->foreign_vessel->country }}</b></p>
                                    <p>{{$i++}}. NAME OF MASTER: <b><u>{{$data->master_name}}</u></b>
                                    </p>
                                    <p>{{$i++}}. GRT: <b><u>{{$data->foreign_vessel->grt}}</u></b>. NRT:
                                        <b><u>{{$data->foreign_vessel->nrt}}</u></b>. DECK CARGO:
                                        <b><u>{{$data->foreign_vessel->deck_cargo}}</u></b></p>
                                    <p>{{$i++}}. LENGTH:
                                        <b>{{isset($data->length_value) ? $data->length_value."  " : "___  "}}</b>MAX.
                                        FW DRAUGHT: <b>{{isset($data->draught) ? $data->draught."  " : "___"}}</b>.</p>
                                    {{--<p>{{$i++}}. NUMBER OF CREW & OFFICER INCLUSIVE MASTER: <b>_ _ _</b></p>
                                    <p>{{$i++}}. NAME AND ADDRESS OF OWNERS: <b>_ _ _</b></p>--}}
                                    <p>{{$i++}}. LOCAL AGENT: <b><u>{{$data->local_agent}}</u></b>. LAST PORT:
                                        <b><u>{{$data->last_port}}</u></b>. NEXT PORT:
                                        <b><u>{{$data->next_port}}</u></b></p>
                                    <p>{{$i++}}. NAME OF PILOT: <b><u>{{$data->cpa_pilot->name}}</u></b>. BOARDED:
                                        <b><u>{{$data->pilot_borded_at}}</u></b>. LEFT:
                                        <b><u>{{$data->pilot_left_at}}</u></b></p>
                                    <p>{{$i++}}. PILOTAGE FROM: <b><u>{{$data->pilotage_from_time}}</u></b>. TO:
                                        <b><u>{{$data->pilotage_to_time}}</u></b>.</p>
                                    @if($data->pilotage_type_id == 2)
                                    <p>{{$i++}}. LOCATION FROM: <b><u>{{\App\Helpers\MdaClass::getJettyName($data->shifted_from)}}</u></b>. TO:
                                        <b><u>{{\App\Helpers\MdaClass::getJettyName($data->shifted_to)}}</u></b>.</p>
                                    @else
                                        <p>{{$i++}}. LOCATION FROM: <b><u>{{\App\Helpers\MdaClass::getJettyName($data->pilotage_from_loc)}}</u></b>. TO:
                                            <b><u>{{\App\Helpers\MdaClass::getJettyName($data->pilotage_to_loc)}}</u></b>.</p>
                                    @endif
                                    <p>{{$i++}}. TIME OF MOORING FROM: <b><u>{{$data->mooring_from_time}}</u></b>. TO:
                                        <b><u>{{$data->mooring_to_time}}</u></b>.</p>

                                    @forelse($data->pilotage_tug as $pTug)
                                        <p>{{$i++}}. CPA TUG/TUGS (NAME): <b><u>{{$pTug->tugs->name}}</u></b>. ASSITANCE
                                            FROM: <b><u>{{$pTug->assitance_from_time}}</u></b>. TO:
                                            <b><u>{{$pTug->assitance_to_time}}</u></b>.</p>
                                    @empty
                                        <p>{{$i++}}. CPA TUG/TUGS (NAME): <b>_ _ _</b>. ASSITANCE FROM: <b>_ _ _</b>.
                                            TO: <b>_ _ _</b>.</p>
                                    @endforelse

                                    <p>{{$i++}}. ARRIVAL AT OUTER ANCHORAGE DATE:
                                        <b><u>{{$data->foreign_vessel->arrival_date}}</u></b>.{{-- FW. DRAFT (MAX): <b>_ _ _</b>.</p>--}}

                                    <p>{{$i++}}. IF WORKED AS LIGHTER, NAME OF MOTHER VESSEL:
                                        <b><u>{{ ($data->working_type_id == "1") ? $data->mother_vessel->name : "_ _ _" }}</u></b>.
                                    </p>
                                    <p>{{$i++}}. WORKED AT: <b><u>{{ $data->work_location->name }}</u></b>.</p>
                                    {{--<p>{{$i++}}. DENGEROUS CARGO IF ANY: <b>_ _ _</b>.</p>--}}

                                    {{--All conditions in vesselConditions--}}
                                    @forelse($data->pilotage_vessel_condition as $j=>$condi)
                                        <div class="row">
                                            @if($condi->vessel_condition->value_type == "TEXT")
                                                <div class="col-md-6">
                                                    {{ $i++.'. '.$condi->vessel_condition->title}}
                                                </div>
                                                <div class="col-md-6">
                                                    <textarea class="form-control" readonly
                                                              style="background-color: white;">{{ $condi->ans_value }}</textarea>
                                                </div>
                                            @else
                                                <div class="col-md-6">
                                                    {{ $i++.'. '.$condi->vessel_condition->title}}
                                                </div>
                                                <div class="col-md-6">
                                                    <ul class="list-unstyled mb-0">
                                                        @if($condi->ans_value == "Y")
                                                            <li class="d-inline-block mr-2 mb-1">
                                                                <fieldset>
                                                                    <div class="custom-control custom-radio disabled">
                                                                        <input type="radio" class="custom-control-input"
                                                                               value="Y" checked>
                                                                        <label class="custom-control-label"
                                                                               for="condRadio{{$j++}}">Yes</label>
                                                                    </div>
                                                                </fieldset>
                                                            </li>
                                                        @else
                                                            <li class="d-inline-block mr-2 mb-1">
                                                                <fieldset>
                                                                    <div class="custom-control custom-radio disabled">
                                                                        <input type="radio" class="custom-control-input"
                                                                               value="N" checked>
                                                                        <label class="custom-control-label"
                                                                               for="condRadio{{$j++}}">No</label>
                                                                    </div>
                                                                </fieldset>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    @empty
                                        <p>Empty</p>
                                    @endforelse
                                    <br>
                                    <p>{{$i++}}. NOS. OF GOOD MOORING LINES: FORD:
                                        <b><u>{{$data->ford_good_mooring_number}}</u></b>. AFT: <b><u>{{$data->aft}}</u></b></p>
                                    <p>{{$i++}}. STERN POWER AVAILABLE:
                                        <b><u>@if($data->stern_power_avail){{$data->stern_power_avail}} @else{{$data->stern_avl_power}} @endif</u></b>. IMMEDIATELY: <b><u>{{$data->immediately}}</u></b> SECS. LATER</p>
                                    <p>{{$i++}}. REMARKS IF ANY: </p>
                                    <p><textarea readonly class="form-control">{{$data->remarks}}</textarea></p>
                                    <br>
                                    <p>CERTIFIED THAT THE ABOVE PARTICULARS ARE CORRECT AND CHARGES THEREOF WILL BE PAID
                                        BY US/LOCAL AGENT INCLUSIVE OF OTHER PORT CHARGES</p>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Harbour remark:</label>
                                            <textarea readonly class="form-control">{{$data->verify_remarks}}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        @if($data->status == "A")
                                            <button onclick="approve_disapprove(this)" data-status="C"
                                                    data-pilotageid="{{$data->id}}" data-toggle="tooltip"
                                                    data-placement="top" title="Approve certificate"
                                                    class="btn btn-lg btn-success bx-pull-right"><i
                                                    class='bx bx-check-circle'></i></button>
                                            {{--
                                                                                        <button onclick="approve_disapprove(this)" data-status="R" data-pilotageid="{{$data->id}}" data-toggle="tooltip" data-placement="top" title="Edit certificate" class="btn btn-lg btn-danger bx-pull-right"><i class='bx bx-edit-alt'></i></button>
                                            --}}
                                            <a href="{{ route('ps-certificate-entry-edit', $data->id) }}"
                                               data-toggle="tooltip" data-placement="top" title="Edit certificate"
                                               class="btn btn-lg btn-danger bx-pull-right"><i
                                                    class='bx bx-edit-alt'></i></a>
                                        @endif
                                        <a href="{{route("ps-verify-certificate")}}" data-toggle="tooltip"
                                           data-placement="top" title="Go to list"
                                           class="btn btn-lg btn-primary bx-pull-right"><i class="bx bx-arrow-back"></i></a>
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
    <script>
        function approve_disapprove(e) {
            let pilotageId = $(e).data("pilotageid");
            let rowId = "#" + $(e).attr("id");
            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            let status = $(e).data("status");
            let msg;
            if (status == "C") {
                msg = "approve";
            } else {
                msg = "reject";
            }
            swal({
                title: "Are you sure want to " + msg + " this?",
                text: "You won't be able to revert this!",
                type: "warning",
                html: "<textarea id='remark' class='form-control' placeholder='Add remarks..'></textarea>",
                preConfirm: function () {
                    return new Promise(function (response) {
                        response({remarks: $('#remark').val()})
                    })
                },
                showCancelButton: !0,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, " + msg + " it!",
                confirmButtonClass: "btn btn-primary",
                cancelButtonClass: "btn btn-danger ml-1",
                buttonsStyling: !1
            }).then(function (e) {
                if (e.value || e.value.remarks) {
                    let remark = e.value.remarks;
                    $.ajax({
                        type: "POST",
                        url: "/ps-verify-certificate/approve",
                        data: {_token: CSRF_TOKEN, remark: remark, "status": status, "pilotage_id": pilotageId},
                        dataType: "JSON",
                        success: function (data) {
                            //TODO:successmessage
                            if (data.success === true) {
                                swal("Done!", data.message, "success").then(function (isConfirm) {
                                    location.reload();
                                });
                            } else {
                                swal("Error!", data.message, "error");
                            }
                        },
                        error: function (data) {
                            console.log(data);
                        }
                    });
                } else {
                    e.dismiss;
                }
            }, function (dismiss) {
                return false;
            })
        }
    </script>
@endsection
