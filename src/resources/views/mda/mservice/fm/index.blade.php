@extends('layouts.default')

@section('title')
    :: Fixed Mooring Service
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
                                      @if(isset($data->fm_ser_id)) action="{{route('fixed-mooring-service-update',[$data->fm_ser_id])}}"
                                      @else action="{{route('fixed-mooring-service-store')}}" @endif method="post">
                                    @csrf
                                    @if (isset($data->fm_ser_id))
                                        @method('PUT')
                                        <input type="hidden" id="fm_ser_id" name="fm_ser_id"
                                               value="{{isset($data->fm_ser_id) ? $data->fm_ser_id : ''}}">
                                    @endif

                                    <h5 class="card-title">Fixed Mooring Service</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Reference No</label>
                                            <div class="input-group">
                                                <input type="text" required
                                                       value="{{isset($data->serial_no) ? $data->serial_no : ''}}"
                                                       class="form-control"
                                                       id="ser_serial_no" autocomplete="off"
                                                       name="ser_serial_no"
                                                       @if(isset($data->serial_no)) readonly @endif
                                                />
                                            </div>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Tanker</label>
                                            <select class="custom-select select2 form-control vessel_id" required
                                                    id="vessel_id" name="vessel_id">
                                                @if(isset($data))
                                                    <option
                                                        value="{{$data->vessel_id}}">{{$data->vessel_name}}</option>
                                                @endif
                                            </select>
                                        </div>
                                        {{-- <div class="col-md-3 mt-1">
                                             <label class="required">Owner</label>
                                             <select class="custom-select select2 form-control ship_agent" required
                                                     style="width: 100%;"
                                                     id="ship_agent" name="ship_agent">
                                                 @if(isset($data))
                                                     <option
                                                         value="{{$data->agent_id}}">{{$data->agent_name}}</option>
                                                 @endif
                                             </select>
                                         </div>

                                         {{--<div class="col-md-3 mt-1">
                                             <label>Owner Name</label>
                                             <div class="input-group">
                                                 <input type="text"
                                                        value="{{isset($data->vessel_owner_name) ? $data->vessel_owner_name : ''}}"
                                                        class="form-control"
                                                        id="vessel_owner_name" autocomplete="off"
                                                        name="vessel_owner_name" readonly placeholder="Owner Name"
                                                 />
                                             </div>
                                         </div>--}}
                                        {{--<div class="col-md-3 mt-1">
                                            <label>Owner Address</label>
                                            <div class="input-group">
                                                <textarea type="text"
                                                       class="form-control" placeholder="Owner Address"
                                                       id="vessel_owner_address" autocomplete="off"
                                                       name="vessel_owner_address" readonly
                                                >{{isset($data->agent_address) ? $data->agent_address : ''}}</textarea>
                                            </div>
                                        </div>--}}
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Jetty</label>
                                            <select class="custom-select select2 form-control jetty_id" required
                                                    name="jetty_id">
                                                @if(isset($data))
                                                    <option
                                                        value="{{$data->jetty_id}}">{{$data->jetty_name}}</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="row my-1">
                                                <div class="col-md-12">
                                                    <label class="required">Alongside Date</label>
                                                    <div class="input-group date"
                                                         onfocusout="$(this).datetimepicker('hide')" id="alongside_date"
                                                         data-target-input="nearest">
                                                        <input type="text" name="alongside_date" autocomplete="off" required
                                                               value="{{isset($data->alongside_date_time) ? $data->alongside_date_time : ''}}"
                                                               class="form-control alongsideDate" data-target="#alongside_date"
                                                               data-toggle="datetimepicker" placeholder="Alongside date"
                                                               oninput="this.value = this.value.toUpperCase()"/>
                                                        <div class="input-group-append" data-target="#alongside_date"
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
                                                    <label class="required">Alongside Time</label>
                                                    <div class="input-group date"
                                                         onfocusout="$(this).datetimepicker('hide')" id="from_time"
                                                         data-target-input="nearest">
                                                        <input type="text" name="alongside_time" id="from_time" required
                                                               value="{{ old('alongside_time', isset($data->alongside_date_time) ? date('H:i',strtotime($data->alongside_date_time)) : '') }}"
                                                               class="form-control fromTime"
                                                               data-target="#from_time" data-toggle="datetimepicker"
                                                               placeholder="Alongside Time" autocomplete="off"
                                                               oninput="this.value = this.value.toUpperCase()"/>
                                                        <div class="input-group-append" data-target="#from_time"
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
                                                    <label class="required">Sail Date</label>
                                                    <div class="input-group date"
                                                         onfocusout="$(this).datetimepicker('hide')" id="sail_date"
                                                         data-target-input="nearest">
                                                        <input type="text" name="sail_date" autocomplete="off" required
                                                               value="{{isset($data->sail_date_time) ? $data->sail_date_time : ''}}"
                                                               class="form-control sailDate" data-target="#sail_date"
                                                               data-toggle="datetimepicker" placeholder="Sail date"
                                                               oninput="this.value = this.value.toUpperCase()"/>
                                                        <div class="input-group-append" data-target="#sail_date"
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
                                                    <label class="required">Sail Time</label>
                                                    <div class="input-group date"
                                                         onfocusout="$(this).datetimepicker('hide')" id="to_time"
                                                         data-target-input="nearest">
                                                        <input type="text" name="sail_time" required
                                                               value="{{ old('alongside_time', isset($data->sail_date_time) ? date('H:i',strtotime($data->sail_date_time)) : '') }}"
                                                               class="form-control toTime"
                                                               data-target="#to_time" data-toggle="datetimepicker"
                                                               placeholder="Sail Time" autocomplete="off"
                                                               oninput="this.value = this.value.toUpperCase()"/>
                                                        <div class="input-group-append" data-target="#to_time"
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
                                            <label>Total Used(Hour)</label>
                                            <div class="input-group">
                                                <input type="text"
                                                       value="{{isset($data->total_used_time) ? $data->total_used_time : ''}}"
                                                       class="form-control"
                                                       id="total_used_time"
                                                       name="total_used_time" readonly
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    @include('mda.mservice.partial.attachment')

                                    <div class="form-group mt-1">
                                        <div class="col-md-12 pr-0 d-flex justify-content-end">
                                            <div class="form-group">
                                                <button type="submit" name="save" onclick="return confirm('Are you sure?')" class="btn btn btn-dark shadow mr-1 mb-1">  {{ isset($data->fm_ser_id)?'Update':'Save' }} </button>
                                                <a type="reset" href="{{route("fixed-mooring-service")}}" class="btn btn btn-outline-dark  mb-1"> Cancel</a>
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

    @include('mda.mservice.fm.list')

@endsection

@section('footer-script')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        let cpaVessel = '{{route('get-local-vessel')}}';
        let jettyList = '{{route('get-jetty-list')}}';
        let shippingAgent = '{{route('get-shipping-agent')}}';

    {{--$('.ship_agent').select2({
        placeholder: "Select one",
        ajax: {
            url: shippingAgent,
            data: function (params) {
                if (params.term) {
                    if (params.term.trim().length < 1) {
                        return false;
                    }
                } else {
                    return false;
                }

                return params;
            },
            dataType: 'json',
            processResults: function (data) {
                var formattedResults = $.map(data, function (obj, idx) {
                    obj.id = obj.agency_id;
                    obj.text = obj.agency_name;
                    return obj;
                });
                return {
                    results: formattedResults,
                };
            }
        }
    });--}}

    $('.vessel_id').select2({
        placeholder: "Select one",
        ajax: {
            url: cpaVessel,
            data: function (params) {
                if (params.term) {
                    if (params.term.trim().length < 1) {
                        return false;
                    }
                } else {
                    return false;
                }

                return params;
            },
            dataType: 'json',
            processResults: function (data) {
                var formattedResults = $.map(data, function (obj, idx) {
                    obj.id = obj.id;
                    obj.text = obj.name;
                    return obj;
                });
                return {
                    results: formattedResults,
                };
            }
        }
    });

    {{--$('.ship_agent').on('change', function () {
        let url = '{{route('get-cpa-vessel-info')}}';
        let ship_agent = $(this).find(":selected").val();
        $.ajax({
            type: 'GET',
            url: url,
            data: {ship_agent: ship_agent},
            success: function (msg) {
                /*let data = msg.split('+');

                let owner_name = data[0];
                let owner_address = data[1];
                $('#vessel_owner_name').val(owner_name);
                $('#vessel_owner_address').val(owner_address);
                $('#vessel_owner_address').val(msg);*/
            }
        });
    });--}}

    $('.jetty_id').select2({
        placeholder: "Select one",
        ajax: {
            url: jettyList,
            data: function (params) {
                if (params.term) {
                    if (params.term.trim().length < 1) {
                        return false;
                    }
                } else {
                    return false;
                }

                return params;
            },
            dataType: 'json',
            processResults: function (data) {
                var formattedResults = $.map(data, function (obj, idx) {
                    obj.id = obj.jetty_id;
                    obj.text = obj.jetty_name;
                    return obj;
                });
                return {
                    results: formattedResults,
                };
            }
        }
    });

    function fmServiceList() {
        let url = '{{route('fixed-mooring-service-datatable')}}';
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
                {data: 'serial_no', name: 'serial_no', searchable: true},
                {data: 'vessel_name', name: 'vessel_name', searchable: true},
                {data: 'jetty_name', name: 'jetty_name', searchable: true},
                {data: 'alongside_date', name: 'alongside_date', searchable: true},
                {data: 'alongside_time', name: 'alongside_time', searchable: true},
                {data: 'sail_date', name: 'sail_date', searchable: true},
                {data: 'sail_time', name: 'sail_time', searchable: true},
                {data: 'total_used_time', name: 'total_used_time', searchable: true},
                {data: 'action', name: 'action', searchable: false},
            ]
        });
    };

    function msToTime(duration) {
        var milliseconds = parseInt((duration % 1000) / 100),
            seconds = Math.floor((duration / 1000) % 60),
            minutes = Math.floor((duration / (1000 * 60)) % 60),
            hours = Math.floor((duration / (1000 * 60 * 60)) % 24);

        hours = (hours < 10) ? "0" + hours : hours;
        minutes = (minutes < 10) ? "0" + minutes : minutes;
        seconds = (seconds < 10) ? "0" + seconds : seconds;

        return hours + ":" + minutes;
    }

    function setExpire() {
        var date1 = new Date($('.alongsideDate').val()+" "+$('.fromTime').val());
        var date2 = new Date($('.sailDate').val()+" "+$('.toTime').val());console.log((date2-date1));
        $('#total_used_time').val(msToTime(date2-date1));
    }


    $(document).ready(function () {
        datePicker("#alongside_date");
        datePicker("#sail_date");
        timePicker24("#from_time");
        timePicker24("#to_time");
        $('#to_time').on('change keyup input paste', setExpire);
        window.setTimeout(function () {
            $(".alert").fadeTo(500, 0).slideUp(500, function () {
                $(this).remove();
            });
        }, 4000);
        fmServiceList();
    });

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

