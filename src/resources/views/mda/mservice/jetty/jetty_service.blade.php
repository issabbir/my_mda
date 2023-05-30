@extends('layouts.default')

@section('title')
    Jetty Service Entry
@endsection

@section('header-style')
    <!--Load custom style link or css-->
    <style>
        .invalid {
            color: #F00;
            background-color: #FFF;
        }
    </style>
@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
{{--                        @dd($vesselName)--}}
                        <h4 class="card-title">{{ isset($data->id) ? 'Edit' : 'Add' }} Jetty Service</h4>
                        <form id="certificateForm" method="POST" action="" autocomplete="off"
                              enctype="multipart/form-data">
                            {{ isset($data->id) ? method_field('PUT') : '' }}
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <label> Vessel Name <span class="required"></span></label>

                                            <select name="vessel_no" id="vessel" class="form-control select2 customError vessel">
                                                <option value="">Select a vessel</option>
                                                @forelse($vesselName as $name)
                                                    <option
                                                        {{ ( old("vessel_id",$data->vessel_no ) == $name->id) ? "selected" : ""  }} value="{{ $name->id }}" data-new-reg-no="{{ $name->new_reg_no ? $name->new_reg_no : '' }}">
                                                        {{ $name->new_reg_no? $name->vessel_name.' ('.$name->new_reg_no.')':$name->vessel_name }}
                                                    </option>

                                                @empty
                                                    <option value="registration_no">No type found</option>

                                                @endforelse
                                                <option value="">Select One</option>

                                            </select>
                                        </div>

                                    </div>

                                </div>
                                <div class="col-md-3">
                                   <div class="row">
                                           <div class="col-md-12">
                                               <label for="">Agency</label>
                                               <input type="text" required autocomplete="off" class="form-control agent_name" value="{{old('agent_name', $data->agent_name)}}" id="agents_id" readonly>
                                           </div>
                                   </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="input-required">Arrival Date<span class="required"></span></label>
                                            <div class="input-group date" onfocusout="$(this).datetimepicker('hide')" id="arrival_date" data-target-input="nearest">
                                                <input type="text" readonly name="arival_date" value="{{ old('arrival_at', $data->arival_at) ? date("Y-m-d", strtotime(old('arrival_at', $data->arival_at))) : "" }}"
                                                       class="form-control arrival_at"
                                                       data-target="#arrival_date" data-toggle="datetimepicker"
                                                       placeholder="Arrival date" id="arrival_dates"
                                                       oninput="this.value = this.value.toUpperCase()" />
                                                <div class="input-group-append" data-target="#arrival_date" data-toggle="datetimepicker">
                                                    <div class="input-group-text">
                                                        <i class="bx bx-calendar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            @if($errors->has("arrival_at"))
                                                <span class="help-block">{{$errors->first("arrival_at")}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="input-required">Berthing Date<span class="required"></span></label>
                                            <div class="input-group date" onfocusout="$(this).datetimepicker('hide')" id="berthing_at" data-target-input="nearest">
                                                <input type="text"  name="berthing_at" value="{{ old('berthing_at', $data->berthing_date) ? date("Y-m-d", strtotime(old('berthing_at', $data->berthing_date))) : "" }}"
                                                       class="form-control arrival_at"
                                                       data-target="#berthing_at" data-toggle="datetimepicker"
                                                       placeholder="Berthing date" id="berthing_ats"
                                                       oninput="this.value = this.value.toUpperCase()" />
                                                <div class="input-group-append" data-target="#berthing_at" data-toggle="datetimepicker">
                                                    <div class="input-group-text">
                                                        <i class="bx bx-calendar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            @if($errors->has("berthing_at"))
                                                <span class="help-block">{{$errors->first("berthing_at")}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="agent_id" id="agent_id" value="{{ old('agent_id', $data->agent_id) }}">
                                <input type="hidden"  name="reg_date" id="reg_date" value="{{ old('reg_date', $data->registration_date) }}">
                                <input type="hidden"  name="new_reg_no" id="new_reg_no" value="{{ old('new_reg_no', $data->new_reg_no) }}">
                                <input type="hidden"  name="reg_no" id="reg_no" value="{{ old('reg_no', $data->reg_no) }}">
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <label> Jetty Name <span class="required"></span></label>

                                            <select name="jetty_id" id="jetty_id" class="form-control select2 customError">
                                                <option value="">Select One</option>
                                                @forelse($jetty_types as $jetty)
                                                    <option
                                                        {{( old("jetty_id",$data->jetty_id) == $jetty->jetty_id) ? "selected" : ""  }} value="{{ $jetty->jetty_id}}">
                                                        {{$jetty->jetty_name}}
                                                    </option>
                                                @empty
                                                    <option value="">No type found</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="required">Sailing Date</label>
                                            <div class="input-group date"
                                                 onfocusout="$(this).datetimepicker('hide')" id="depar_date"
                                                 data-target-input="nearest">
                                                <input type="text" name="depar_date" required autocomplete="off"
                                                       value="{{ old('depar_date', $data->depar_date) }}"
                                                       class="form-control depar_date" data-target="#depar_date"
                                                       data-toggle="datetimepicker" placeholder="Sailing Date"
                                                       oninput="this.value = this.value.toUpperCase()"/>
                                                <div class="input-group-append" data-target="#depar_date"
                                                     data-toggle="datetimepicker">
                                                    <div class="input-group-text">
                                                        <i class="bx bx-calendar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label >Status</label>
                                            <select class="form-control select2 customError" name="jetty_status" id="jetty_status">
                                                <option value="">Select an option</option>
                                                @if(Auth::user()->hasPermission('CAN_INSERT_CONTAINER_HANDLING'))
                                                    <option value="1" {{ $data->jetty_status == 1 ? 'selected' : '' }}>Container Handling</option>
                                                @endif
                                                @if(Auth::user()->hasPermission('CAN_INSERT_JETTY_CRANE_NOT_USED'))
                                                    <option value="2" {{ $data->jetty_status == 2 ? 'selected' : '' }}>Jetty Crane Not Used</option>
                                                @endif
                                                @if(Auth::user()->hasPermission('CAN_INSERT_JETTY_CRANE_USED'))
                                                    <option value="3" {{ $data->jetty_status == 3 ? 'selected' : '' }}>Jetty Crane Used</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3" id="derrick_used" style="display: none">
                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <label >DERRICK USED</label>
                                            <input type="number" value="{{ old('derrick_used', $data->derrick_used) }}" class="form-control" id="derrick_used" name="derrick_used" autocomplete="off">
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-3" id="m_crane_used" style="display: none">
                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <label >CRANE USED</label>
                                            <input type="number" value="{{ old('m_crane_used', $data->m_crane_used) }}" class="form-control" id="m_crane_used" name="m_crane_used" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <label class="input-required">Remarks</label>
                                            <input type="text" name="remarks" id="remarks" class="form-control text-uppercase" value="{{ old('remarks', $data->remarks) }}" autocomplete="off">
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col-md-12" id="Import" style="display: none;">
                                        <fieldset class="border col-md-12 import_field">
                                            <legend class="w-auto " style="font-size: 14px;">Import:</legend>

                                            <div class="row">
                                                <div class="col-md-3 mb-1">
                                                    <label class="text-nowrap" for="qty_l_2">Loaded 20</label>
                                                    <input type="number"  value="{{old('qty_l_2', $data->qty_l_2)}}" class="form-control" id="qty_l_2" name="qty_l_2" max="1500" maxlength="1000" title="Loaded 20">
                                                </div>

                                                <div class="col-md-3 mb-1">
                                                    <label class="text-nowrap" for="qty_l_2_h">Loaded 20 OT</label>
                                                    <input type="number" value="{{old('qty_l_2_h', $data->qty_l_2_h)}}" class="form-control" id="qty_l_2_h" name="qty_l_2_h" max="1500" maxlength="1000" title="Loaded 20 OT">
                                                </div>

                                                <div class="col-md-3 mb-1">
                                                    <label class="text-nowrap" for="qty_l_4">Loaded 40</label>
                                                    <input type="number" value="{{old('qty_l_4', $data->qty_l_4)}}" class="form-control" id="qty_l_4" name="qty_l_4" max="1500" maxlength="1000" title="Loaded 40">
                                                </div>

                                                <div class="col-md-3 mb-1">
                                                    <label class="text-nowrap" for="qty_l_4_h">Loaded 40 OT</label>
                                                    <input type="number" value="{{old('qty_l_4_h', $data->qty_l_4_h)}}" class="form-control" id="qty_l_4_h" name="qty_l_4_h" max="1500" maxlength="1000" title="Loaded 40 OT">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3 mb-1">
                                                    <label class="text-nowrap" for="qty_e_2">Empty 20</label>
                                                    <input type="number" value="{{old('qty_e_2', $data->qty_e_2)}}" class="form-control" id="qty_e_2" name="qty_e_2" max="1500" maxlength="1000" title="Empty 20">
                                                </div>
                                                <div class="col-md-3 mb-1">
                                                    <label class="text-nowrap" for="qty_e_2_h">Empty 20 OT</label>
                                                    <input type="number" value="{{old('qty_e_2_h', $data->qty_e_2_h)}}" class="form-control" id="qty_e_2_h" name="qty_e_2_h" max="1500" maxlength="1000" title="Empty 20 OT">
                                                </div>
                                                <div class="col-md-3 mb-1">
                                                    <label class="text-nowrap" for="qty_e_4">Empty 40</label>
                                                    <input type="number" value="{{old('qty_e_4', $data->qty_e_4)}}" class="form-control" id="qty_e_4" name="qty_e_4" max="1500" maxlength="1000" title="Empty 40">
                                                </div>

                                                <div class="col-md-3 mb-1">
                                                    <label class="text-nowrap" for="qty_e_4_h">Empty 40 OT</label>
                                                    <input type="number" value="{{old('qty_e_4_h', $data->qty_e_4_h)}}" class="form-control" id="qty_e_4_h" name="qty_e_4_h" max="1500" maxlength="1000" title="Empty 40 OT">
                                                </div>
                                            </div>

                                        </fieldset>

                                    </div>

                                </div>

                                <div class="col-md-6">
                                    <div class="form-group col-md-12" id="Export" style="display: none;">
                                        <fieldset class="border col-md-12 import_field">
                                            <legend class="w-auto " style="font-size: 14px;">Export:</legend>

                                            <div class="row">
                                                <div class="col-md-3 mb-1">
                                                    <label class="text-nowrap" for="qty_l_2">Loaded 20</label>
                                                    <input type="number" value="{{old('qty_l_2_ex', $data->qty_l_2_ex)}}" class="form-control" id="qty_l_2_ex" name="qty_l_2_ex" max="1500" maxlength="1000" title="Loaded 20">
                                                </div>

                                                <div class="col-md-3 mb-1">
                                                    <label class="text-nowrap" for="qty_l_2_h">Loaded 20 OT</label>
                                                    <input type="number" value="{{old('qty_l_2_h_ex', $data->qty_l_2_h_ex)}}" class="form-control" id="qty_l_2_h_ex" name="qty_l_2_h_ex" max="1500" maxlength="1000" title="Loaded 20 OT">
                                                </div>

                                                <div class="col-md-3 mb-1">
                                                    <label class="text-nowrap" for="qty_l_4">Loaded 40</label>
                                                    <input type="number" value="{{old('qty_l_4_ex', $data->qty_l_4_ex)}}" class="form-control" id="qty_l_4_ex" name="qty_l_4_ex" max="1500" maxlength="1000" title="Loaded 40">
                                                </div>

                                                <div class="col-md-3 mb-1">
                                                    <label class="text-nowrap" for="qty_l_4_h">Loaded 40 OT</label>
                                                    <input type="number" value="{{old('qty_l_4_h_ex', $data->qty_l_4_h_ex)}}" class="form-control" id="qty_l_4_h_ex" name="qty_l_4_h_ex" max="1500" maxlength="1000" title="Loaded 40 OT">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3 mb-1">
                                                    <label class="text-nowrap" for="qty_e_2">Empty 20</label>
                                                    <input type="number" value="{{old('qty_e_2_ex', $data->qty_e_2_ex)}}" class="form-control" id="qty_e_2_ex" name="qty_e_2_ex" max="1500" maxlength="1000" title="Empty 20">
                                                </div>
                                                <div class="col-md-3 mb-1">
                                                    <label class="text-nowrap" for="qty_e_2_h">Empty 20 OT</label>
                                                    <input type="number" value="{{old('qty_e_2_h_ex', $data->qty_e_2_h_ex)}}" class="form-control" id="qty_e_2_h_ex" name="qty_e_2_h_ex" max="1500" maxlength="1000" title="Empty 20 OT">
                                                </div>
                                                <div class="col-md-3 mb-1">
                                                    <label class="text-nowrap" for="qty_e_4">Empty 40</label>
                                                    <input type="number" value="{{old('qty_e_4_ex', $data->qty_e_4_ex)}}" class="form-control" id="qty_e_4_ex" name="qty_e_4_ex" max="1500" maxlength="1000" title="Empty 40">
                                                </div>

                                                <div class="col-md-3 mb-1">
                                                    <label class="text-nowrap" for="qty_e_4_h">Empty 40 OT</label>
                                                    <input type="number" value="{{old('qty_e_4_h_ex', $data->qty_e_4_h_ex)}}" class="form-control" id="qty_e_4_h_ex" name="qty_e_4_h_ex" max="1500" maxlength="1000" title="Empty 40 OT">
                                                </div>
                                            </div>

                                        </fieldset>

                                    </div>

                                </div>
                            </div>


                            <div class="row justify-content-end">
{{--                                <div class="col-md-3">--}}
{{--                                </div>--}}
{{--                                <div class="col-md-3">--}}
{{--                                </div>--}}
{{--                                <div class="col-md-3">--}}
{{--                                    <div class="row mt-1">--}}
{{--                                            <div class="col-md-12">--}}
{{--                                                <label class="input-required">Bill Status<span class="required"></span></label>--}}
{{--                                                <ul class="list-unstyled mb-0">--}}
{{--                                                    <li class="d-inline-block mr-2 mb-1">--}}
{{--                                                        <fieldset>--}}
{{--                                                            <div class="custom-control custom-radio">--}}
{{--                                                                <input type="radio" class="custom-control-input"--}}
{{--                                                                       value="{{ old('status','P') }}"--}}
{{--                                                                       {{isset($data->status) && $data->status == 'P' ? 'checked' : ''}} name="status"--}}
{{--                                                                       id="customRadio1" checked="">--}}
{{--                                                                <label class="custom-control-label" for="customRadio1">Draft</label>--}}
{{--                                                            </div>--}}
{{--                                                        </fieldset>--}}
{{--                                                    </li>--}}
{{--                                                    <li class="d-inline-block mr-2 mb-1">--}}
{{--                                                        <fieldset>--}}
{{--                                                            <div class="custom-control custom-radio">--}}
{{--                                                                <input type="radio" class="custom-control-input"--}}
{{--                                                                       value="{{ old('status','A') }}"--}}
{{--                                                                       {{isset($data->status) && $data->status == 'A' ? 'checked' : ''}} name="status"--}}
{{--                                                                       id="customRadio2">--}}
{{--                                                                <label class="custom-control-label" for="customRadio2">Send to Accounts</label>--}}
{{--                                                            </div>--}}
{{--                                                        </fieldset>--}}
{{--                                                    </li>--}}
{{--                                                </ul>--}}
{{--                                                @if ($errors->has('status'))--}}
{{--                                                    <span class="help-block">{{ $errors->first('status') }}</span>--}}
{{--                                                @endif--}}
{{--                                            </div>--}}
{{--                                        </div>--}}

{{--                                    </div>--}}
                                <div class="col-md-3 mt-1">
                                    <div class="row my-1">
                                        <div class="d-flex justify-content-end col">
                                            <button type="submit" name="save" onclick="return confirm('Are you sure?')" class="btn btn btn-dark shadow mr-1 mb-1">  {{ isset($data->id)?'Update':'Save' }} </button>
                                            <a type="reset" href="{{ route('jetty-service') }}" class="btn btn btn-outline-dark  mb-1"> Cancel</a>
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
                    <h4 class="card-title"> Jetty Service List</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table table-sm datatable">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Vessel name</th>
                                    <th>Agency</th>
                                    <th>Arrival at</th>
                                    <th>Sailing at</th>
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
        function dateTime(selector) {
            var elem = $(selector);
            elem.datetimepicker({
                format: 'YYYY-MM-DD',
                widgetPositioning:{
                    horizontal: 'left',
                    vertical: 'bottom'
                },
                icons:{
                    time: 'bx bx-time',
                    date: 'bx bxs-calendar',
                    up :'bx bx-up-arrow-alt',
                    down: 'bx bx-down-arrow-alt',
                    previous: 'bx bx-chevron-left',
                    next: 'bx bx-chevron-right',
                    today: 'bx bxs-calendar-check',
                    clear: 'bx bx-trash',
                    close: 'bx bx-window-close'
                }
            });
        }



        $("#vessel").select2().on("change",function () {
            $(".arrival_at").val("");
            let vessel_id = $('#vessel').find(":selected").val();
            let new_reg_no = $(this).find(':selected').data('new-reg-no')
            if(vessel_id !== null){
                $.ajax({
                    dataType:'JSON',
                    url:'/jetty-service/get-vessel-info/'+vessel_id,
                    cache: true,
                    success:function (data) {

                        // console.log(data.reg_no)
                        $("#agents_id").val(data.shipping_agent_name);
                        $("#agent_id").val(data.shipping_agent_id);
                        $("#reg_date").val(data.reg_date);
                        $("#arrival_dates").val(data.arrival_date);
                        $("#berthing_ats").val(data.arrival_date);
                        // $("#berthing_at").val(data.berthing_at);
                        $("#new_reg_no").val(new_reg_no);
                        $("#reg_no").val(data.reg_no);
                    }
                })
            }
        })

        dateTime('#arrival_date');
        dateTime('#berthing_at');
        dateTime('#depar_date');




        $(document).ready(function() {

            let vessel_id = $('#vessel').find(":selected").val();
             console.log(vessel_id)
            if(vessel_id !== null){
                $.ajax({
                    dataType:'JSON',
                    url:'/jetty-service/get-vessel-info'+'/'+vessel_id,
                    cache: true,
                    success:function (data) {

                         //console.log(data.berthing.berthing_date)
                        $("#agents_id").val(data.shipping_agent_name);
                        $("#agent_id").val(data.shipping_agent_id);
                        $("#arrival_dates").val(data.arrival_date);
                        $("#berthing_ats").val(data.berthing.berthing_date);

                    }
                })
            }


            $('#jetty_status').on('change', function() {
                let val = $(this).val();
                //console.log(val)
                if (val == '1') {
                    $('#Import').show();
                    $('#Export').show();
                    $('#derrick_used').hide();
                    $('#m_crane_used').hide();
                } else if (val == '2') {
                    $('#Import').hide();
                    $('#derrick_used').show();
                    $('#m_crane_used').hide();
                    $('#Export').hide();
                } else if(val =='3'){
                    $('#Import').hide();
                    $('#derrick_used').hide();
                    $('#m_crane_used').show();
                    $('#Export').hide();
                }
                else {
                    $('#Import').hide();
                    $('#derrick_used').hide();
                    $('#Export').hide();
                    $('#m_crane_used').hide();
                }
            });




            $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
                initComplete: function(settings, json) {
                    $('body').find('.dataTables_scrollBody').css("height", "auto");
                    $('body').find('.dataTables_scrollBody').css("max-height", "300px");
                },
                ajax: {
                    url:'{{ route('jetty-service-datatable', isset($data->transaction_id)?$data->transaction_id:0 ) }}',
                    type:'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                "columns": [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex' },
                    {"data": "vessel_name"},
                    {"data": "shipping_agent_name"},
                    {"data": "arrival_at"},
                    {"data": "depar_date"},
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

            @if(isset($data->id))
                $('#jetty_status').trigger('change');
            @endif
        });
    </script>
@endsection

