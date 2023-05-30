@extends('layouts.default')

@section('title')
    :: Water Service
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
                                      @if(isset($data->w_ser_id)) action="{{route('water-service-update',[$data->w_ser_id])}}"
                                      @else action="{{route('water-service-store')}}" @endif method="post">
                                    @csrf
                                    @if (isset($data->w_ser_id))
                                        @method('PUT')
                                        <input type="hidden" id="w_ser_id" name="w_ser_id"
                                               value="{{isset($data->w_ser_id) ? $data->w_ser_id : ''}}">
                                    @endif

                                    <h5 class="card-title">Water Service</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Receipt No</label>
                                            <div class="input-group">
                                                <input type="text" autocomplete="off" required
                                                       value="{{isset($data->receipt_no) ? $data->receipt_no : ''}}"
                                                       class="form-control receipt_no"
                                                       id="receipt_no"
                                                       name="receipt_no"
                                                       placeholder="Receipt No"
                                                       @if(isset($data->receipt_no)) readonly @endif
                                                />
                                            </div>
                                        </div>

                                        <div class="col-md-3 mt-1">
                                            <label class="required">Received From</label>
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
                                                    <label class="required">Supply Date</label>
                                                    <div class="input-group date"
                                                         onfocusout="$(this).datetimepicker('hide')" id="supply_date"
                                                         data-target-input="nearest">
                                                        <input type="text" name="supply_date" autocomplete="off"
                                                               required
                                                               value="{{isset($data->supply_date) ? $data->supply_date : ''}}"
                                                               class="form-control supply_date"
                                                               data-target="#supply_date"
                                                               data-toggle="datetimepicker" placeholder="Supply date"
                                                               oninput="this.value = this.value.toUpperCase()"/>
                                                        <div class="input-group-append" data-target="#supply_date"
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
                                            <label class="required">Vessel</label>
                                            <select class="custom-select select2 form-control vessel_id" required
                                                    style="width: 100% !important;"
                                                    id="vessel_id" name="vessel_id">
                                                @if(isset($data))
                                                    <option
                                                        value="{{$data->vessel_id}}">{{$data->vessel_name.' - '.date("d-m-Y", strtotime($data->arrival_date))}}</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Shipping Agent</label>
                                            <select class="custom-select select2 form-control agent_id" required
                                                    style="width: 100% !important;"
                                                    id="agent_id" name="agent_id">
                                                @if(isset($data))
                                                    <option
                                                        value="{{$data->agent_id}}">{{$data->agent_name}}</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Received Quantity</label>
                                            <div class="input-group">
                                                <input type="number" required
                                                       value="{{isset($data->received_qty) ? $data->received_qty : ''}}"
                                                       class="form-control"
                                                       id="received_qty"
                                                       name="received_qty"
                                                       autocomplete="off"
                                                       placeholder="Received Quantity"
                                                />
                                            </div>
                                        </div>
                                    </div>

                                    @include('mda.mservice.partial.attachment')

                                    <div class="form-group mt-1">
                                        <div class="col-md-12 pr-0 d-flex justify-content-end">
                                            <div class="form-group">
                                                <button type="submit" name="save"
                                                        onclick="return confirm('Are you sure?')"
                                                        class="btn btn btn-dark shadow mr-1 mb-1">  {{ isset($data->w_ser_id)?'Update':'Save' }} </button>
                                                <a type="reset" href="{{route("water-service")}}"
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

    @include('mda.mservice.water.list')

@endsection

@section('footer-script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script type="text/javascript">

        let cpaVessel = '{{route('get-cpa-vessel')}}';
        let jettyList = '{{route('get-jetty-list')}}';
        let vtmisVessel = '{{route('get-foreign-vessel')}}';
        let shippingAgent = '{{route('get-shipping-agent')}}';

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
                    console.log(data); //asif
                    var formattedResults = $.map(data, function (obj, idx) {
                        obj.id = obj.v_r_id;
                        obj.text = obj.name + ' - ' + convertDate(obj.arrival_date);
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

        function waterServiceList() {
            let url = '{{route('water-service-datatable')}}';
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
                    {data: 'receipt_no', name: 'receipt_no', searchable: true},
                    {data: 'supply_date', name: 'supply_date', searchable: true},
                    {data: 'jetty_name', name: 'jetty_name', searchable: true},
                    {data: 'vessel_name', name: 'vessel_name', searchable: true},
                    {data: 'agent_name', name: 'agent_name', searchable: true},
                    {data: 'received_qty', name: 'received_qty', searchable: true},
                    {data: 'action', name: 'action', searchable: false},
                ]
            });
        };

        $(document).ready(function () {
            datePicker("#supply_date");
            window.setTimeout(function () {
                $(".alert").fadeTo(500, 0).slideUp(500, function () {
                    $(this).remove();
                });
            }, 4000);
            waterServiceList();
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

