@extends('layouts.default')

@section('title')
    :: Tug Service
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
                                      @if(isset($data->t_ser_id)) action="{{route('tug-service-update',[$data->t_ser_id])}}"
                                      @else action="{{route('tug-service-store')}}" @endif method="post">
                                    @csrf
                                    @if (isset($data->t_ser_id))
                                        @method('PUT')
                                        <input type="hidden" id="t_ser_id" name="t_ser_id"
                                               value="{{isset($data->t_ser_id) ? $data->t_ser_id : ''}}">
                                    @endif

                                    <h5 class="card-title">Tug Service</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3 mt-1">
                                            <label>Serial No</label>
                                            <input type="text" readonly
                                                   name="ser_serial_no" autocomplete="off"
                                                   id="ser_serial_no"
                                                   class="form-control"
                                                   value="{{isset($data->ser_serial_no) ? $data->ser_serial_no : 'TG'.$gen_uniq_id}}"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Vessel</label>
                                            <select class="custom-select select2 form-control vessel_id" required
                                                    id="vessel_id" name="vessel_id">
                                                @if(isset($data))
                                                    <option
                                                        value="{{$data->vessel_id}}">{{$data->vessel_name}}</option>
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
                                            <label class="required">Tug</label>
                                            <select class="custom-select select2 form-control tug_id" required
                                                    id="tug_id" name="tug_id">
                                                @if(isset($data))
                                                    <option
                                                        value="{{$data->tug_id}}">{{$data->tug_name}}</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="row my-1">
                                                <div class="col-md-12">
                                                    <label class="required">Working Date</label>
                                                    <div class="input-group date"
                                                         onfocusout="$(this).datetimepicker('hide')" id="working_date"
                                                         data-target-input="nearest">
                                                        <input type="text" name="working_date" required autocomplete="off"
                                                               value="{{isset($data->working_date) ? $data->working_date : ''}}"
                                                               class="form-control workDate" data-target="#working_date"
                                                               data-toggle="datetimepicker" placeholder="Working date"
                                                               oninput="this.value = this.value.toUpperCase()"/>
                                                        <div class="input-group-append" data-target="#working_date"
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
                                            <label class="required">Location From</label>
                                            <select class="custom-select select2 form-control jetty_id" required
                                                    name="jetty_id_from">
                                                @if(isset($data))
                                                    <option
                                                        value="{{$data->loc_from_id}}">{{$data->loc_from_name}}</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Location To</label>
                                            <select class="custom-select select2 form-control jetty_id" required
                                                    name="jetty_id_to">
                                                @if(isset($data))
                                                    <option
                                                        value="{{$data->loc_to_id}}">{{$data->loc_to_name}}</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Working Type</label>
                                            <select class=" form-control form-control-sm select2 search-param" id="work_id" name="work_id" required>
                                                <option value="">Select One</option>
                                                @foreach($workTypes as $value)
                                                    <option {{isset($data) ? (($value->work_id == $data->work_id) ? 'selected' : '') : ''}}
                                                            value="{{$value->work_id}}">{{ $value->work_title}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="row my-1">
                                                <div class="col-md-12">
                                                    <label class="required">From Time</label>
                                                    <div class="input-group date"
                                                         onfocusout="$(this).datetimepicker('hide')" id="from_time"
                                                         data-target-input="nearest">
                                                        <input type="text" name="from_time" required
                                                               value="{{ old('from_time', isset($data->from_time) ? date('H:i',strtotime($data->from_time)) : '') }}"
                                                               class="form-control fromTime"
                                                               data-target="#from_time" data-toggle="datetimepicker"
                                                               placeholder="From time" autocomplete="off"
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
                                                    <label class="required">To Time</label>
                                                    <div class="input-group date"
                                                         onfocusout="$(this).datetimepicker('hide')" id="to_time"
                                                         data-target-input="nearest">
                                                        <input type="text" name="to_time" required
                                                               value="{{ old('to_time', isset($data->to_time) ? date('H:i',strtotime($data->to_time)) : '') }}"
                                                               class="form-control toTime"
                                                               data-target="#to_time" data-toggle="datetimepicker"
                                                               placeholder="To time" autocomplete="off"
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
                                            <label>Total(Hour)</label>
                                            <div class="input-group">
                                                <input type="text"
                                                       value="{{isset($data->total_time) ? $data->total_time : ''}}"
                                                       class="form-control"
                                                       id="total_time"
                                                       name="total_time" readonly
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Work Description</label>
                                                <textarea name="work_desc" placeholder="Work Description" class="form-control"
                                                          oninput="this.value = this.value.toUpperCase()">{{isset($data->work_desc) ? $data->work_desc : ''}}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Details</label>
                                                <textarea name="details" placeholder="Details" class="form-control"
                                                          oninput="this.value = this.value.toUpperCase()">{{isset($data->details) ? $data->details : ''}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    @include('mda.mservice.partial.attachment')

                                    <div class="form-group mt-1">
                                        <div class="col-md-12 pr-0 d-flex justify-content-end">
                                            <div class="form-group">
                                                <button type="submit" name="save" onclick="return confirm('Are you sure?')" class="btn btn btn-dark shadow mr-1 mb-1">  {{ isset($data->t_ser_id)?'Update':'Save' }} </button>
                                                <a type="reset" href="{{route("tug-service")}}" class="btn btn btn-outline-dark  mb-1"> Cancel</a>
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

    @include('mda.mservice.tug.list')

@endsection

@section('footer-script')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        let vtmisVessel = '{{route('get-foreign-vessel')}}';
        let jettyList = '{{route('get-jetty-list')}}';
        let tugList = '{{route('get-tug-list')}}';
        let shippingAgent = '{{route('get-shipping-agent')}}';

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

        function setExpire() {
            let fromTime = $('.fromTime').val();console.log(fromTime)
            let toTime = $('.toTime').val();
            let search = ':0';

            if ( fromTime.indexOf(search) > -1 ) {
                fromTime = fromTime.replace(':0',':');
            }

            if ( toTime.indexOf(search) > -1 ) {
                toTime = toTime.replace(':0',':');
            }

            var date1 = new Date($('.workDate').val()+" "+fromTime);
            var date2 = new Date($('.workDate').val()+" "+toTime);console.log(date2)
            $('#total_time').val(msToTime(date2-date1));
        }

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
                     console.log(data);
                    var formattedResults = $.map(data, function (obj, idx) {
                        // console.log(formattedResults);
                        // obj.id = obj.id;
                        obj.id = obj.v_r_id;
                        obj.text = obj.name+' - '+convertDate(obj.arrival_date);
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

        $('.tug_id').select2({
            placeholder: "Select one",
            ajax: {
                url: tugList,
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


        function navyServiceList() {
            let url = '{{route('tug-service-datatable')}}';
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
                    {data: 'tug_name', name: 'tug_name', searchable: true},
                    {data: 'working_date', name: 'working_date', searchable: true},
                    {data: 'loc_from_name', name: 'loc_from_name', searchable: true},
                    {data: 'loc_to_name', name: 'loc_to_name', searchable: true},
                    {data: 'from_time', name: 'from_time', searchable: true},
                    {data: 'to_time', name: 'to_time', searchable: true},
                    {data: 'action', name: 'action', searchable: false},
                ]
            });
        };

        $(document).ready(function () {
            datePicker("#working_date");
            timePicker24("#from_time");
            timePicker24("#to_time");
            $('#to_time').on('change keyup input paste', setExpire);
            window.setTimeout(function () {
                $(".alert").fadeTo(500, 0).slideUp(500, function () {
                    $(this).remove();
                });
            }, 4000);
            navyServiceList();
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

