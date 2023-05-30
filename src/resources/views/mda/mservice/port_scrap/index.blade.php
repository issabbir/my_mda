@extends('layouts.default')

@section('title')
    :: Port Dues/Scrap Vessel Service
@endsection

@section('header-style')
    <!--Load custom style link or css-->

@endsection
@section('content')

    <div class="content-body">
        <section id="form-repeater-wrapper">
            <!-- form default repeater -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        @if(Session::has('message'))
                            <div
                                class="alert {{Session::get('m-class') ? Session::get('m-class') : 'alert-danger'}} show"
                                role="alert">
                                {{ Session::get('message') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="card-content">
                            <div class="card-body">
                                <form enctype="multipart/form-data"
                                      @if(isset($data->ps_ser_id)) action="{{route('port-scrap-service-update',[$data->ps_ser_id])}}"
                                      @else action="{{route('port-scrap-service-store')}}" @endif method="post">
                                    @csrf
                                    @if (isset($data->ps_ser_id))
                                        @method('PUT')
                                        <input type="hidden" id="ps_ser_id" name="ps_ser_id"
                                               value="{{isset($data->ps_ser_id) ? $data->ps_ser_id : ''}}">
                                    @endif

                                    <h5 class="card-title">Port Dues/Scrap Vessel Service</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Vessel</label>
                                            <select class="custom-select select2 form-control vessel" required
                                                    style="width: 100%;"
                                                    id="vessel" name="vessel">
                                                <option value="">Select Vessel</option>
                                                @if(isset($vessels))
                                                    @foreach($vessels as $vessel)
                                                        <option value="{{$vessel->reg_no}}" {{ old('vessel', $data->reg_no) == $vessel->reg_no ? 'selected' : '' }} >{{$vessel->vessel_name}} ({{$vessel->circular_no}})</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <input type="hidden" name="vessel_name" class="vessel_name" value="{{ old('vessel_name', $data->vessel_name) }}">
                                        <div class="col-md-3">
                                            <div class="row my-1">
                                                <div class="col-md-12">
                                                    <label class="required">Arrival Date</label>
                                                    <div class="input-group date"
                                                         onfocusout="$(this).datetimepicker('hide')" id="arrival_date"
                                                         data-target-input="nearest">
                                                        <input type="text" name="arrival_date" required autocomplete="off"
                                                               value="{{ old('arrival_date', $data->arrival_date) }}"
                                                               class="form-control arrival_date" data-target="#arrival_date"
                                                               data-toggle="datetimepicker" placeholder="Arrival Date"
                                                               oninput="this.value = this.value.toUpperCase()"/>
                                                        <div class="input-group-append" data-target="#arrival_date"
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
                                            <div class="row my-1">
                                                <div class="col-md-12">
                                                    <label class="required">Departure Date</label>
                                                    <div class="input-group date"
                                                         onfocusout="$(this).datetimepicker('hide')" id="departure_date"
                                                         data-target-input="nearest">
                                                        <input type="text" name="departure_date" required autocomplete="off"
                                                               value="{{ old('departure_date', $data->departure_date) }}"
                                                               class="form-control departure_date" data-target="#departure_date"
                                                               data-toggle="datetimepicker" placeholder="Departure Date"
                                                               oninput="this.value = this.value.toUpperCase()"/>
                                                        <div class="input-group-append" data-target="#departure_date"
                                                             data-toggle="datetimepicker">
                                                            <div class="input-group-text">
                                                                <i class="bx bx-calendar"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Agent</label>
                                            <div class="input-group">
                                                <input type="text" required autocomplete="off"
                                                       value="{{ old('agent_name', $data->agent_name) }}"
                                                       class="form-control agent_name"
                                                       id="agent_name"
                                                       name="agent_name"
                                                       readonly
                                                />
                                            </div>
                                        </div>
                                        <input type="hidden" name="agent_id" class="agent_id" value="{{ old('agent_id', $data->agent_id) }}">
                                        <div class="col-md-3">
                                            <label class="required">Flag</label>
                                            <div class="input-group">
                                                <input type="text" required autocomplete="off"
                                                       value="{{old('flag_name', $data->flag_name)}}"
                                                       class="form-control flag_name"
                                                       id="flag_name"
                                                       name="flag_name"
                                                       @if(isset($data->flag_name)) readonly @endif
                                                />
                                            </div>
                                        </div>
                                        <input type="hidden" name="flag_id" class="flag_id" value="{{ old('flag_id', $data->flag_id) }}">
                                        <div class="col-md-3">
                                            <label class="required">GRT</label>
                                            <div class="input-group">
                                                <input type="text" required autocomplete="off"
                                                       value="{{ old('grt', $data->grt) }}"
                                                       class="form-control grt"
                                                       id="grt"
                                                       name="grt"
                                                       readonly
                                                />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="required">NRT</label>
                                            <div class="input-group">
                                                <input type="text" required autocomplete="off"
                                                       value="{{ old('nrt', $data->nrt) }}"
                                                       class="form-control nrt"
                                                       id="nrt"
                                                       name="nrt"
                                                       readonly
                                                />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="required">Remarks</label>
                                            <select class="custom-select select2 form-control remarks" required
                                                    id="remarks" name="remarks">
                                                <option value="">Select Remarks</option>
                                                @if(isset($remarks))
                                                    @foreach($remarks as $remark)
                                                        <option value="{{$remark->remarks_id}}" {{ old('remarks', $data->remarks_id) == $remark->remarks_id ? 'selected' : '' }}>{{$remark->remark_name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    @include('mda.mservice.partial.attachment')

                                    <div class="form-group mt-2">
                                        <div class="col-md-12 pr-0 d-flex justify-content-end">
                                            <div class="form-group">
                                                <button type="submit" name="save" onclick="return confirm('Are you sure?')" class="btn btn btn-dark shadow mr-1 mb-1">  {{ isset($data->ps_ser_id)?'Update':'Save' }} </button>
                                                <a type="reset" href="{{route("port-scrap-service")}}" class="btn btn btn-outline-dark  mb-1"> Cancel</a>
                                            </div>
                                        </div>
                                    </div>



                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ form default repeater -->

        </section>
    </div>

    @include('mda.mservice.port_scrap.list')

@endsection

@section('footer-script')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        function dateTime(selector) {
            var elem = $(selector);
            elem.datetimepicker({
                format: 'YYYY-MM-DD HH:mm',
                widgetPositioning: {
                    horizontal: 'left',
                    vertical: 'bottom'
                },
                icons: {
                    time: 'bx bx-time',
                    date: 'bx bxs-calendar',
                    up: 'bx bx-up-arrow-alt',
                    down: 'bx bx-down-arrow-alt',
                    previous: 'bx bx-chevron-left',
                    next: 'bx bx-chevron-right',
                    today: 'bx bxs-calendar-check',
                    clear: 'bx bx-trash',
                    close: 'bx bx-window-close'
                }
            });

            let preDefinedDate = elem.attr('data-predefined-date');

            if (preDefinedDate) {
                let preDefinedDateMomentFormat = moment(preDefinedDate, "YYYY-MM-DD HH:mm").format("YYYY-MM-DD HH:mm A");
                elem.datetimepicker('defaultDate', preDefinedDateMomentFormat);
            }
        }
        function portScrapServiceList() {
            let url = '{{route('port-scrap-service-datatable')}}';
            let oTable = $('#searchResultTable').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                    url: url,
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'reg_no', name: 'reg_no', searchable: true},
                    {data: 'vessel_name', name: 'vessel_name', searchable: true},
                    {data: 'agent_name', name: 'agent_name', searchable: true},
                    {data: 'arrival_date', name: 'arrival_date', searchable: true},
                    {data: 'departure_date', name: 'departure_date', searchable: true},
                    {data: 'flag_name', name: 'flag_name', searchable: true},
                    {data: 'grt', name: 'grt', searchable: true},
                    {data: 'nrt', name: 'nrt', searchable: true},
                    {data: 'remarks', name: 'remarks', searchable: true},
                    {data: 'action', name: 'action', searchable: false},
                ]
            });
        };

        $(document).ready(function () {
            dateTime("#arrival_date");
            dateTime("#departure_date");

            portScrapServiceList();
        });

        $(".vessel").on("change", function (e) {
            loadVesselInfo()
        });

        function loadVesselInfo(){
            let vessel_reg_no = $('.vessel').val();

            if(vessel_reg_no)
            {
                $.ajax({
                    // dataType: 'JSON',
                    type: 'get',
                    url: '/port-scrap-service/get-vessel-info',
                    data : { vessel_reg_no : vessel_reg_no },
                    cache: true,
                    // async: false,
                    success: function (data) {
                        console.log(data)
                        data = data[0]
                        if(data) {
                            var d = new Date(data.arrival_date),
                                month = '' + (d.getMonth() + 1),
                                day = '' + d.getDate(),
                                year = d.getFullYear(),
                                hour = d.getHours(),
                                minute = d.getMinutes();

                            if (month.length < 2)
                                month = '0' + month;
                            if (day.length < 2)
                                day = '0' + day;
                            if (hour && hour.length < 2) {
                                hour = '0' + hour;
                            } else {
                                hour = '00'
                            }
                            if (minute && minute.length < 2) {
                                minute = '0' + minute;
                            } else {
                                minute = '00'
                            }

                            $(".arrival_date").val(year + "-" + month + "-" + day + ' '+ hour +':'+minute);
                            // $(".arrival_date").val(day + "-" + month + "-" + year);

                            $(".vessel_name").val(data.vessel_name);
                            $(".agent_name").val(data.shipping_agent_name);
                            $(".agent_id").val(data.shipping_agent_id);
                            $(".flag_name").val(data.country);
                            $(".flag_id").val(data.vessel_flag);
                            $(".grt").val(data.grt);
                            $(".nrt").val(data.nrt);
                        }
                    },
                    error: function (data) {
                        console.log(data);
                    }
                });
            }
        }

        $(".add-row-doc").click(function () {

            let doc_name = $("#case_doc_name").val();
            let converted_file = $("#converted_file").val();

            let filePath = $("#attachedFile").val();
            let file_ext = filePath.substr(filePath.lastIndexOf('.') + 1, filePath.length);
            let fileName = document.getElementById('attachedFile').files[0].name;

            let markup = "<tr><td><input type='checkbox' name='record'>" +
                "<input type='hidden' name='doc_name[]' value='" + doc_name + "'>" +
                "<input type='hidden' name='doc_type[]' value='" + file_ext + "'>" +
                "<input type='hidden' name='doc[]' value='" + converted_file + "'>" +
                "</td><td>" + doc_name + "</td><td><i class='bx bxs-file cursor-pointer'></i></td></tr>";
            $("#case_doc_name").val("");
            $("#attachedFile").val("");
            $("#table-doc tbody").append(markup);
        });

        $(".delete-row-file").click(function () {
            $("#table-doc tbody").find('input[name="record"]').each(function () {
                if ($(this).is(":checked")) {
                    let doc_id = $(this).closest('tr').find('.doc_id').val();
                    if (doc_id != null) {
                        //$(this).parents("tr").remove();
                        let url = '{{route('docRemove')}}';
                        Swal.fire({
                            title: 'Are you sure?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                            if (result.value) {
                                $.ajax({
                                    type: 'GET',
                                    url: url,
                                    data: {doc_id: doc_id},
                                    success: function (msg) {//console.log(msg)
                                        if (msg == 'success') {
                                            $(this).parents("tr").remove();
                                            Swal.fire({
                                                title: 'Entry Successfully Deleted!',
                                                icon: 'success',
                                                confirmButtonText: 'OK'
                                            }).then(function () {
                                                $('td input:checked').closest('tr').remove();
                                            });
                                        } else {
                                            Swal.fire({
                                                title: 'Something Went Wrong!!.',
                                                icon: 'error',
                                                confirmButtonText: 'OK'
                                            });
                                        }
                                    }
                                });
                            }
                        });


                        /*$(this).parents("tr").remove();
                        let url = '{{route('docRemove')}}';
                        $.ajax({
                            type: 'GET',
                            url: url,
                            data: {doc_id: doc_id},
                            success: function (msg) {
                                $(this).parents("tr").remove();
                                Swal.fire({
                                    title: 'Successfully Deleted!',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(function () {
                                    //location.reload();

                                });
                            }
                        });*/
                    } else {
                        $(this).parents("tr").remove();
                    }
                    $("#attach_count").val('0');
                }
            });
        });

    </script>

@endsection

