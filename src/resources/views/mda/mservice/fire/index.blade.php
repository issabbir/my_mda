@extends('layouts.default')

@section('title')
    :: Fire Service
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
                                      @if(isset($data->f_ser_mst_id)) action="{{route('fire-service-update',[$data->f_ser_mst_id])}}"
                                      @else action="{{route('fire-service-store')}}" @endif method="post">
                                    @csrf
                                    @if (isset($data->f_ser_mst_id))
                                        @method('PUT')
                                        <input type="hidden" id="f_ser_mst_id" name="f_ser_mst_id"
                                               value="{{isset($data->f_ser_mst_id) ? $data->f_ser_mst_id : ''}}">
                                    @endif

                                    <h5 class="card-title">Fire Service</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3 mt-1">
                                            <label class="required">receipt No</label>
                                            <div class="input-group">
                                                <input type="text" autocomplete="off" required
                                                       value="{{isset($data->ser_serial_no) ? $data->ser_serial_no : ''}}"
                                                       class="form-control"
                                                       id="ser_serial_no"
                                                       name="ser_serial_no"
                                                       @if(isset($data->ser_serial_no)) readonly @endif
                                                />
                                            </div>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Flag</label>
                                            <select class="custom-select select2 form-control " id="vessel_flag"
                                                    required
                                                    name="vessel_flag">
                                                <option value="">Please select</option>
                                                <option @if(isset($data->vessel_flag) && $data->vessel_flag==1) selected
                                                        @endif value="1">BD
                                                </option>
                                                <option @if(isset($data->vessel_flag) && $data->vessel_flag==2) selected
                                                        @endif value="2">FOREIGN
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 mt-1" id="show_bd"
                                             style="@if(isset($data->bd_vessel_id)) display: block; @else display: none; @endif">
                                            <label class="required">Vessel</label>
                                            <select class="custom-select select2 form-control bd_vessel_id"
                                                    id="bd_vessel_id" name="bd_vessel_id" style="width: 100%;">
                                                @if(isset($data))
                                                    <option
                                                        value="{{$data->bd_vessel_id}}">{{$data->vessel_name}}</option>
                                                @endif
                                            </select>
                                        </div>

                                        <div class="col-md-3 mt-1" id="show_fr"
                                             style="@if(isset($data->fr_vessel_id)) display: block; @else display: none; @endif">
                                            <label class="required">Vessel</label>
                                            <select class="custom-select select2 form-control fr_vessel_id"
                                                    style="width: 100%;"
                                                    id="fr_vessel_id" name="fr_vessel_id">
                                                @if(isset($data))
                                                    <option
                                                        value="{{$data->fr_vessel_id}}">{{$data->vessel_name}}</option>
                                                @endif
                                            </select>
                                        </div>

                                        <div class="col-md-3 mt-1">
                                            <label class="required">Shipping Agent</label>
                                            <select class="custom-select select2 form-control ship_agent" required
                                                    style="width: 100%;"
                                                    id="ship_agent" name="ship_agent">
                                                @if(isset($data))
                                                    <option
                                                        value="{{$data->agent_id}}">{{$data->agent_name}}</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Jetty </label>
                                            <select class="custom-select select2 form-control jetty_id" required
                                                    name="jetty_id">
                                                @if(isset($data))
                                                    <option
                                                        value="{{$data->jetty_id}}">{{$data->jetty_name}}</option>
                                                @endif
                                            </select>
                                        </div>

                                        <div class="col-md-3 mt-1">
                                            <label>Equipment Quantity</label>
                                            <div class="input-group">
                                                <input type="number" autocomplete="off"
                                                       value="{{isset($data->eqp_quantity) ? $data->eqp_quantity : ''}}"
                                                       class="form-control"
                                                       id="eqp_quantity"
                                                       name="eqp_quantity"
                                                />
                                            </div>
                                        </div>

                                        <div class="col-md-3 mt-1">
                                            <label>No of Working Fire Fighter</label>
                                            <div class="input-group">
                                                <input type="number" autocomplete="off"
                                                       value="{{isset($data->fire_fighter_no) ? $data->fire_fighter_no : ''}}"
                                                       class="form-control"
                                                       id="fire_fighter_no"
                                                       name="fire_fighter_no"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mt-1">
                                            <div class="form-group">
                                                <label>Work Description</label>
                                                <textarea name="work_desc" placeholder="Work Description"
                                                          class="form-control"
                                                          oninput="this.value = this.value.toUpperCase()">{{isset($data->work_desc) ? $data->work_desc : ''}}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-1">
                                            <div class="form-group">
                                                <label>Details</label>
                                                <textarea name="details" placeholder="Details"
                                                          class="form-control"
                                                          oninput="this.value = this.value.toUpperCase()">{{isset($data->details) ? $data->details : ''}}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <fieldset class="border p-1 mt-2 mb-1 col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <label>Duty Date</label>
                                                <div class="input-group date"
                                                     onfocusout="$(this).datetimepicker('hide')" id="dtl_date"
                                                     data-target-input="nearest">
                                                    <input type="text" id="date_dtl" autocomplete="off"
                                                           class="form-control" data-target="#dtl_date"
                                                           data-toggle="datetimepicker" placeholder="Date"
                                                           oninput="this.value = this.value.toUpperCase()"/>
                                                    <div class="input-group-append" data-target="#dtl_date"
                                                         data-toggle="datetimepicker">
                                                        <div class="input-group-text">
                                                            <i class="bx bx-calendar"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <label>From Time</label>
                                                <div class="input-group date"
                                                     onfocusout="$(this).datetimepicker('hide')" id="from_time"
                                                     data-target-input="nearest">
                                                    <input type="text" id="from_time" autocomplete="off"
                                                           class="form-control fromTime"
                                                           data-target="#from_time" data-toggle="datetimepicker"
                                                           placeholder="In time"
                                                           oninput="this.value = this.value.toUpperCase()"/>
                                                    <div class="input-group-append" data-target="#from_time"
                                                         data-toggle="datetimepicker">
                                                        <div class="input-group-text">
                                                            <i class="bx bx-calendar"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <label>To Time</label>
                                                <div class="input-group date"
                                                     onfocusout="$(this).datetimepicker('hide')" id="to_time"
                                                     data-target-input="nearest">
                                                    <input type="text" id="to_time" autocomplete="off"
                                                           class="form-control toTime"
                                                           data-target="#to_time" data-toggle="datetimepicker"
                                                           placeholder="Out time"
                                                           oninput="this.value = this.value.toUpperCase()"/>
                                                    <div class="input-group-append" data-target="#to_time"
                                                         data-toggle="datetimepicker">
                                                        <div class="input-group-text">
                                                            <i class="bx bx-calendar"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label for="seat_to1">&nbsp;</label><br/>
                                                    <button type="button" id="append"
                                                            class="btn btn-primary mb-1 add-row">
                                                        ADD
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 mt-1">
                                            <div class="table-responsive">
                                                <table class="table table-sm table-striped table-bordered"
                                                       id="table-operator">
                                                    <thead>
                                                    <tr>
                                                        <th role="columnheader" scope="col"
                                                            aria-colindex="1" class="text-center" width="1%">#
                                                        </th>
                                                        <th role="columnheader" scope="col"
                                                            aria-colindex="2" class="text-center" width="5%">Date
                                                        </th>
                                                        <th role="columnheader" scope="col"
                                                            aria-colindex="2" class="text-center" width="5%">IN TIME
                                                        </th>
                                                        <th role="columnheader" scope="col"
                                                            aria-colindex="2" class="text-center" width="5%">OUT TIME
                                                        </th>
                                                    </tr>
                                                    </thead>

                                                    <tbody role="rowgroup" id="comp_body">
                                                    @if(!empty($dData))
                                                        @foreach($dData as $key=>$value)
                                                            <tr role="row">
                                                                <td aria-colindex="1" role="cell" class="text-center">
                                                                    <input type='checkbox' name='record'
                                                                           value="{{$value->f_ser_dtl_id}}">
                                                                    <input type="hidden" name="tab_f_ser_dtl_id[]"
                                                                           value="{{$value->f_ser_dtl_id}}"
                                                                           class="f_ser_dtl_id">
                                                                </td>
                                                                <td aria-colindex="7"
                                                                    role="cell">
                                                                    {{isset($value->duty_from_date_time) ? date("d-m-Y", strtotime($value->duty_from_date_time)) : "--"}}
                                                                </td>
                                                                <td aria-colindex="7"
                                                                    role="cell">{{isset($value->duty_from_date_time) ? date("H:i", strtotime($value->duty_from_date_time)) : "--"}}
                                                                </td>
                                                                <td aria-colindex="7"
                                                                    role="cell">{{isset($value->duty_to_date_time) ? date("H:i", strtotime($value->duty_to_date_time)) : "--"}}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>

                                        <div class="col-12 d-flex justify-content-start">

                                            <button type="button"
                                                    class="btn btn-primary mb-1 delete-row">
                                                Delete
                                            </button>
                                        </div>
                                    </fieldset>

                                    @include('mda.mservice.partial.attachment')
                                    <div class="form-group mt-1">
                                        <div class="col-md-12 pr-0 d-flex justify-content-end">
                                            <div class="form-group">
                                                <button type="submit" name="save"
                                                        onclick="return confirm('Are you sure?')"
                                                        class="btn btn btn-dark shadow mr-1 mb-1">  {{ isset($data->f_ser_mst_id)?'Update':'Save' }} </button>
                                                <a type="reset" href="{{route("fire-service")}}"
                                                   class="btn btn btn-outline-dark  mb-1"> Cancel</a>
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

    @include('mda.mservice.fire.list')

@endsection

@section('footer-script')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">

        function showDays() {
            let start = $('.start_date').val();
            let end = $('.end_date').val();
            let startDay = new Date(start);
            let endDay = new Date(end);
            let millisecondsPerDay = 1000 * 60 * 60 * 24;
            let millisBetween = endDay.getTime() - startDay.getTime();
            let days = millisBetween / millisecondsPerDay;
            $('#total_days').val(Math.floor(days + 1));
        }

        let cpaVessel = '{{route('get-cpa-vessel')}}';
        let jettyList = '{{route('get-jetty-list')}}';
        let vtmisVessel = '{{route('get-foreign-vessel')}}';
        let shippingAgent = '{{route('get-shipping-agent')}}';


        $('#vessel_flag').change(function () {
            if ($(this).val() == 1) {
                $("#show_bd").show();
                $("#show_fr").hide();
            } else if ($(this).val() == 2) {
                $("#show_bd").hide();
                $("#show_fr").show();
            } else {
                $("#show_bd").hide();
                $("#show_fr").hide();
            }
        });

        $('.fr_vessel_id').select2({
            placeholder: "Select one",
            ajax: {
                url: vtmisVessel,
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
                        obj.id = obj.v_r_id;
                        obj.text = obj.name;
                        return obj;
                    });
                    return {
                        results: formattedResults,
                    };
                }
            }
        });

        $('.bd_vessel_id').select2({
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
                        obj.text = obj.vessel_name;
                        return obj;
                    });
                    return {
                        results: formattedResults,
                    };
                }
            }
        });

        $('.ship_agent').select2({
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
        });

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

        function fireServiceList() {
            let url = '{{route('fire-service-datatable')}}';
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
                    {data: 'ser_serial_no', name: 'ser_serial_no', searchable: true},
                    {data: 'flag', name: 'flag', searchable: true},
                    {data: 'vessel_name', name: 'vessel_name', searchable: true},
                    {data: 'agent_name', name: 'agent_name', searchable: true},
                    {data: 'jetty_name', name: 'jetty_name', searchable: true},
                    {data: 'eqp_quantity', name: 'eqp_quantity', searchable: true},
                    {data: 'fire_fighter_no', name: 'fire_fighter_no', searchable: true},
                    {data: 'action', name: 'action', searchable: false},
                ]
            });
        };

        $(document).ready(function () {
            datePicker("#dtl_date");
            timePicker24("#from_time");
            timePicker24("#to_time");
            window.setTimeout(function () {
                $(".alert").fadeTo(500, 0).slideUp(500, function () {
                    $(this).remove();
                });
            }, 4000);
            fireServiceList();
        });

        $(".add-row").click(function () {
            let date_dtl = $("#date_dtl").val();
            let to_time = $(".toTime").val();
            let from_time = $(".fromTime").val();

            if (date_dtl == '') {
                Swal.fire(
                    'Please input Date',
                    '',
                    'error'
                )
            } else if (to_time == '') {
                Swal.fire(
                    'Please input To time.',
                    '',
                    'error'
                )
            } else if (from_time == '') {
                Swal.fire(
                    'Please input From time.',
                    '',
                    'error'
                )
            } else {
                let markup = "<tr role='row'>" +
                    "<td aria-colindex='1' role='cell' class='text-center'>" +
                    "<input type='checkbox' name='record' value=''>" +
                    "<input type='hidden' name='tab_date_dtl[]' value='" + date_dtl + "'>" +
                    "<input type='hidden' name='tab_to_time[]' value='" + to_time + "'>" +
                    "<input type='hidden' name='tab_from_time[]' value='" + from_time + "'>" +
                    "</td><td aria-colindex='2' role='cell'>" + date_dtl + "</td><td aria-colindex='2' role='cell'>" + from_time + "</td><td aria-colindex='2' role='cell'>" + to_time + "</td></tr>";
                $("#date_dtl").val('');
                $(".toTime").val('');
                $(".fromTime").val('');
                $("#table-operator tbody").append(markup);
            }

        });

        $(".delete-row").click(function () {
            let url = '{{route('fire-service.dtl-data-remove')}}';
            let f_ser_dtl_id = [];
            $(':checkbox:checked').each(function (i) {
                f_ser_dtl_id[i] = $(this).val();
            });

            if (f_ser_dtl_id) {
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
                            data: {f_ser_dtl_id: f_ser_dtl_id},
                            success: function (msg) {
                                if (msg == 0) {
                                    Swal.fire({
                                        title: 'Something Went Wrong!!.',
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Entry Successfully Deleted!',
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then(function () {
                                        $('td input:checked').closest('tr').remove();
                                    });
                                }
                            }
                        });
                    }
                });
            } else {
                $('td input:checked').closest('tr').remove();
            }
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
                    } else {
                        $(this).parents("tr").remove();
                    }
                    $("#attach_count").val('0');
                }
            });
        });

    </script>

@endsection

