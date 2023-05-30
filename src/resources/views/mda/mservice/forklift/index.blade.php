@extends('layouts.default')

@section('title')
    :: ForkLift Service
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
{{--                                      @dd($data)--}}
                                      @if(isset($data->fl_ser_mst_id)) action="{{route('forklift-service-update',[$data->fl_ser_mst_id])}}"
                                      @else action="{{route('forklift-service-store')}}" @endif method="post">
                                    @csrf
                                    @if (isset($data->fl_ser_mst_id))
                                        @method('PUT')
                                        <input type="hidden" id="fl_ser_mst_id" name="fl_ser_mst_id"
                                               value="{{isset($data->fl_ser_mst_id) ? $data->fl_ser_mst_id : ''}}">
                                    @endif

                                    <h5 class="card-title">Forklift Service</h5>
                                    <hr>
                                    <div class="row">
                                        {{--<div class="col-md-3 mt-1">
                                            <label class="required">Serial No</label>
                                            <div class="input-group">
                                                <input type="text" autocomplete="off" required
                                                       value="{{isset($data->ser_serial_no) ? $data->ser_serial_no : ''}}"
                                                       class="form-control"
                                                       id="ser_serial_no"
                                                       name="ser_serial_no"
                                                       autocomplete="off"
                                                       placeholder="Serial No"
                                                       @if(isset($data->ser_serial_no)) readonly @endif
                                                />
                                            </div>
                                        </div>--}}
                                        <div class="col-md-3 mt-1">
                                            <label>Serial No</label>
                                            <input type="text" readonly
                                                   name="ser_serial_no" autocomplete="off"
                                                   id="ser_serial_no"
                                                   class="form-control"
                                                   value="{{isset($data->ser_serial_no) ? $data->ser_serial_no : 'FL'.$gen_uniq_id}}"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1" id="vessel_id">
                                            <label class="required">Vessel</label>
                                            <select class="custom-select select2 form-control vessel_id" required
                                                    name="vessel_id">
                                                @if(isset($data))
                                                    <option
                                                        value="{{$data->vessel_id}}">{{$data->vessel_name}}</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>Arrival Date</label>
                                            <div class="input-group">
                                                <input type="text" readonly
                                                       value="{{ isset($data->arrival_date) ? date("Y-m-d", strtotime($data->arrival_date)) : "" }}"
                                                       class="form-control"
                                                       id="arrival_date"
                                                       autocomplete="off"
                                                       name="arrival_date"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>Registration No</label>
                                            <div class="input-group">
                                                <input type="text"
                                                       value="{{isset($data->registration_no) ? $data->registration_no : ''}}"
                                                       class="form-control"
                                                       id="registration_no"
                                                       name="registration_no"
                                                       autocomplete="off"
                                                />
                                            </div>
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
                                            <label>Company Name</label>
                                            <div class="input-group">
                                                <input type="text"
                                                       value="{{isset($data->company_name) ? $data->company_name : ''}}"
                                                       class="form-control"
                                                       id="company_name"
                                                       name="company_name"
                                                       autocomplete="off"
                                                       placeholder="Company Name"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Shipping Agent</label>
                                            <select class="custom-select select2 form-control agent_id" required
                                                    style="width: 100%;"
                                                    id="agent_id" name="agent_id">
                                                @if(isset($data))
                                                    <option
                                                        value="{{$data->agent_id}}">{{$data->agent_name}}</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <fieldset class="border p-1 mt-2 mb-1 col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <label>Serial No</label>
                                                <input type="text" autocomplete="off"
                                                       id="ser_no"
                                                       class="form-control"
                                                >
                                            </div>
                                            <div class="col-sm-2">
                                                <label>Date</label>
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
                                                <label>Type of M/E</label>
                                                <input type="text" autocomplete="off"
                                                       id="typ_me"
                                                       class="form-control"
                                                >
                                            </div>
                                            <div class="col-sm-2">
                                                <label>No of M/E</label>
                                                <input type="number" autocomplete="off"
                                                       id="me_no"
                                                       class="form-control"
                                                >
                                            </div>
                                            <div class="col-sm-2">
                                                <label>In Time</label>
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
                                                <label>Out Time</label>
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
                                            <div class="col-sm-8">
                                                <label>Remarks</label>
                                                <input type="text" autocomplete="off"
                                                       id="remarks_dtl"
                                                       class="form-control"
                                                >
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
                                                            aria-colindex="2" class="text-center" width="10%">Serial No
                                                        </th>
                                                        <th role="columnheader" scope="col"
                                                            aria-colindex="2" class="text-center" width="5%">Date
                                                        </th>
                                                        <th role="columnheader" scope="col"
                                                            aria-colindex="2" class="text-center" width="10%">TYPE OF
                                                            M/E
                                                        </th>
                                                        <th role="columnheader" scope="col"
                                                            aria-colindex="2" class="text-center" width="5%">NO OF M/E
                                                        </th>
                                                        <th role="columnheader" scope="col"
                                                            aria-colindex="2" class="text-center" width="5%">IN TIME
                                                        </th>
                                                        <th role="columnheader" scope="col"
                                                            aria-colindex="2" class="text-center" width="5%">OUT TIME
                                                        </th>
                                                        <th role="columnheader" scope="col"
                                                            aria-colindex="2" class="text-center" width="10%">Remarks
                                                        </th>
                                                    </tr>
                                                    </thead>

                                                    <tbody role="rowgroup" id="comp_body">
                                                    @if(!empty($dtldata))
                                                        @foreach($dtldata as $key=>$value)
                                                            <tr role="row">
                                                                <td aria-colindex="1" role="cell" class="text-center">
                                                                    <input type='checkbox' name='record'
                                                                           value="{{$value->fl_ser_dtl_id}}">
                                                                    <input type="hidden" name="tab_fl_ser_dtl_id[]"
                                                                           value="{{$value->fl_ser_dtl_id}}"
                                                                           class="fl_ser_dtl_id">
                                                                </td>
                                                                <td aria-colindex="7"
                                                                    role="cell">{{$value->serial_no}}
                                                                </td>
                                                                <td aria-colindex="7"
                                                                    role="cell">
                                                                    {{isset($value->dtl_date) ? date("Y-m-d", strtotime($value->dtl_date)) : "--"}}
                                                                </td>
                                                                <td aria-colindex="7"
                                                                    role="cell">{{$value->typ_of_me}}
                                                                </td>
                                                                <td aria-colindex="7"
                                                                    role="cell">{{$value->no_of_me}}
                                                                </td>
                                                                <td aria-colindex="7"
                                                                    role="cell">{{isset($value->in_time) ? date("H:i", strtotime($value->in_time)) : "--"}}
                                                                </td>
                                                                <td aria-colindex="7"
                                                                    role="cell">{{isset($value->out_time) ? date("H:i", strtotime($value->out_time)) : "--"}}
                                                                </td>
                                                                <td aria-colindex="7"
                                                                    role="cell">{{$value->dtl_remarks}}
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
                                                        class="btn btn btn-dark shadow mr-1 mb-1">  {{ isset($data->fl_ser_mst_id)?'Update':'Save' }} </button>
                                                <a type="reset" href="{{route("forklift-service")}}"
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


        </section>
    </div>

    @include('mda.mservice.forklift.list')

@endsection

@section('footer-script')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">

        let cpaVessel = '{{route('get-cpa-vessel')}}';
        let jettyList = '{{route('get-jetty-list')}}';
        let vtmisVessel = '{{route('get-foreign-vessel')}}';
        let shippingAgent = '{{route('get-shipping-agent')}}';
        let dataRemove = '{{route('dtl-data-remove')}}';

        $('.vessel_id').on('change', function () {
            let url = '{{route('get-vessel-info')}}';
            let vessel_id = $(this).find(":selected").val();
            $.ajax({
                type: 'GET',
                url: url,
                data: {vessel_id: vessel_id},
                success: function (msg) {//console.log(msg.result.new_reg_no)
                    $('#arrival_date').val(convertDate(msg.result.arrival_date));
                    $('#registration_no').val(msg.result.new_reg_no);
                }
            });
        });

        $('.vessel_id').select2({
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
                    // console.log(data);
                    var formattedResults = $.map(data, function (obj, idx) {
                         //console.log(obj.new_reg_no);
                        // obj.id = obj.id;
                        obj.id = obj.v_r_id;
                        // obj.text = obj.name + ' - ' + convertDate(obj.arrival_date);
                        obj.text = obj.name +(obj.new_reg_no ? (' - ( ' + obj.new_reg_no +')')  : '');

                        return obj;
                    });
                    return {
                        results: formattedResults,
                    };
                }
            }
        });

        $('.agent_id').select2({
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

        function forklistServiceList() {
            let url = '{{route('forklift-service-datatable')}}';
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

                    {data: 'vessel_name', name: 'vessel_name', searchable: true},
                    {data: 'agent_name', name: 'agent_name', searchable: true},
                    {data: 'jetty_name', name: 'jetty_name', searchable: true},

                    {data: 'company_name', name: 'company_name', searchable: true},

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
            forklistServiceList();
        });

        $(".add-row").click(function () {
            let ser_no = $("#ser_no").val();
            let date_dtl = $("#date_dtl").val();
            let typ_me = $("#typ_me").val();
            let me_no = $("#me_no").val();
            let to_time = $(".toTime").val();
            let from_time = $(".fromTime").val();//alert(from_time)
            let remarks_dtl = $("#remarks_dtl").val();

            if (ser_no == '') {
                Swal.fire(
                    'Please input serial no.',
                    '',
                    'error'
                )
            } else {
                let markup = "<tr role='row'>" +
                    "<td aria-colindex='1' role='cell' class='text-center'>" +
                    "<input type='checkbox' name='record' value=''>" +
                    "<input type='hidden' name='tab_ser_no[]' value='" + ser_no + "'>" +
                    "<input type='hidden' name='tab_date_dtl[]' value='" + date_dtl + "'>" +
                    "<input type='hidden' name='tab_typ_me[]' value='" + typ_me + "'>" +
                    "<input type='hidden' name='tab_me_no[]' value='" + me_no + "'>" +
                    "<input type='hidden' name='tab_to_time[]' value='" + to_time + "'>" +
                    "<input type='hidden' name='tab_from_time[]' value='" + from_time + "'>" +
                    "<input type='hidden' name='tab_remarks_dtl[]' value='" + remarks_dtl + "'>" +
                    "</td><td aria-colindex='2' role='cell'>" + ser_no + "</td><td aria-colindex='2' role='cell'>" + date_dtl + "</td><td aria-colindex='2' role='cell'>" + typ_me + "</td><td aria-colindex='2' role='cell'>" + me_no + "</td><td aria-colindex='2' role='cell'>" + from_time + "</td><td aria-colindex='2' role='cell'>" + to_time + "</td><td aria-colindex='2' role='cell'>" + remarks_dtl + "</td></tr>";
                $("#ser_no").val('');
                $("#date_dtl").val('');
                //$("#typ_me").val('');
                $("#me_no").val('');
                $(".toTime").val('');
                $(".fromTime").val('');
                $("#remarks_dtl").val('');
                $("#table-operator tbody").append(markup);
            }

        });

        $(".delete-row").click(function () {
            let fl_ser_dtl_id = [];
            $(':checkbox:checked').each(function (i) {
                fl_ser_dtl_id[i] = $(this).val();
            });

            if (fl_ser_dtl_id) {
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
                            url: dataRemove,
                            data: {fl_ser_dtl_id: fl_ser_dtl_id},
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
                    if (doc_id !== null) {
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

