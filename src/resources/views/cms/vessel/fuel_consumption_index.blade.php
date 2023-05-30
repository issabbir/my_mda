
@extends('layouts.default')

@section('title')
    Fuel Consumption
@endsection

@section('header-style')
    <!--Load custom style link or css-->
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            @include('cms.vessel.partial.vessel_info')
            <form method="POST" action="" id="fuel_consumption" enctype="multipart/form-data">
                {{ isset($data->fuel_consumption_mst_id)?method_field('PUT'):'' }}
                {!! csrf_field() !!}
                <div class="row">
                @include('cms.vessel.partial.prv_fuel_receive')
                @include('cms.vessel.partial.new_fuel_consumption')
                </div>
                <div class="row mb-1">
                    <div class="col-md-12 text-right">
                        <button type="submit" name="save" id="submit"
                                class="btn btn-dark shadow mr-1"><i
                                class="bx bx-save"></i>{{ isset($data->fuel_consumption_mst_id)?' Update':' Save' }}
                         </button>
                        <button type="button" name="save_send_to_approval" id="save_send_to_approval"
                                value="save_send_to_approval"
                                class="btn btn-primary shadow mr-1" {{isset($data->fuel_consumption_mst_id)?'disabled':''}}><i
                                class="bx bx-save"></i>Save & Send To Approval
                        </button>
                        <a type="reset" href="{{route("cms.user-wise-vessel")}}"
                           class="btn btn-outline-dark"><i
                                class="bx bx-window-close"></i> Cancel</a>
                    </div>
                </div>
            </form>
            <!--List-->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"> Fuel Consumption List</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table table-sm datatable">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Consumption Ref No.</th>
                                    <th>Consumption From Date</th>
                                    <th>Consumption To Date</th>
                                    <th>Fuel Received Date</th>
                                    <th>Received Fuel</th>
                                    <th>Previous Reserved Fuel</th>
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
        $(document).ready(function () {
            // datePicker("#consumption_from");
            // datePicker("#consumption_to");
            // datePicker("#received_date");

            $('#consumption_from').datetimepicker({
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


            $("#consumption_from").on("change.datetimepicker", function (e) {
                $('#consumption_from').datetimepicker('maxDate', e.date);
                // $('#consumption_from').datetimepicker('maxDate', $('#consumption_to').data().date);
            });

            $("#consumption_to").on("change.datetimepicker", function (e) {
                // $('#consumption_to').datetimepicker('maxDate', e.date);
                $('#consumption_to').datetimepicker('minDate', $('#consumption_from').data().date);
            });


            let vessel_id=GetParameterValues('vessel_id');
            let edit_vessel_id=$('#cpa_vessel_id').val();
                $('.datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    pageLength: 20,
                    lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
                    initComplete: function(settings, json) {
                        $('body').find('.dataTables_scrollBody').css("height", "auto");
                        $('body').find('.dataTables_scrollBody').css("max-height", "300px");
                    },
                    ajax: {
                        url:'{{route("cms.fuel-consumption.datatable")}}',
                        type:'POST',
                        'headers': {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        'data':{
                            'vessel_id':(vessel_id)?vessel_id:edit_vessel_id
                        },
                    },
                    "columns": [
                        {"data": 'DT_RowIndex', "name": 'DT_RowIndex' },
                        {"data": "consumption_ref_no"},
                        {"data": "formatted_consumption_from_date"},
                        {"data": "formatted_consumption_to_date"},
                        {"data": "formatted_received_date"},
                        {"data": "received_fuel"},
                        {"data": "prv_reserved_fuel"},
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
            function GetParameterValues(param) {
                var url = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
                for (var i = 0; i < url.length; i++) {
                    var urlparam = url[i].split('=');
                    if (urlparam[0] == param) {
                        return urlparam[1].replace("#/","");
                    }
                }
            }
            /************calculated total consumed fuel depends on working hours*********/
            $(document).on('keyup', '.working_hours', function () {
                let that = $(this);
                calculatedTotalConsumedFuel(that)
            });

            /************calculated total consumed fuel depends on working hours*********/
            $(document).on('keydown', '.working_hours', function () {
                let that = $(this);
                calculatedTotalConsumedFuel(that)
            });

            /************calculated total fuel depends on receiving *********/
            $(document).on('input keyup keypress blur change', ".received_fuel", function () {
                calculatedTotalFuel()
            });

            /************calculated total fuel depends on reserved*********/
            $(document).on('input keyup keypress blur change', ".reserved_fuel", function () {
                calculatedTotalFuel()
            });

            /************calculated total consumed fuel  function*********/
            function calculatedTotalConsumedFuel(affectedRow){
                let total_consumed_fuel=0;
                let hourly_consumed_fuel=$(affectedRow).closest('table tr').find('.hourly_consumed_fuel').val();
                let working_hours=$(affectedRow).closest('table tr').find('.working_hours').val();
                total_consumed_fuel=hourly_consumed_fuel*working_hours;
                 $(affectedRow).closest('table tr').find('.total_consumed_fuel').val(total_consumed_fuel.toFixed(2));
                }

            /************calculated total  fuel  function*********/
            function calculatedTotalFuel(){
                let total_fuel=0;
                let  received_fuel=parseInt(($(document).find('.received_fuel').val())?$(document).find('.received_fuel').val():0);
                let  reserved_fuel=parseInt(($(document).find('.reserved_fuel').val())?$(document).find('.reserved_fuel').val():0);
                total_fuel=received_fuel+reserved_fuel;
                $("#total_fuel").val(parseFloat(received_fuel+reserved_fuel).toFixed(2));
            }
            /************fuel consumption sum by working hours*********/
            $(document).on('input keyup keypress blur change', ".working_hours", function () {
                fuelConsumeSum();
            });
            fuelConsumeSum();
            /************fuel consumption sum function*********/
            function fuelConsumeSum() {
                let sum_of_total_fuel_consumption = 0;
                $("tbody tr").each(function() {
                    sum_of_total_fuel_consumption += parseInt(($(this).find('.total_consumed_fuel').val())?$(this).find('.total_consumed_fuel').val():0);
                });
                $("#total_fuel_consumption").text(parseFloat(sum_of_total_fuel_consumption).toFixed(2));
            }

            $("#save_send_to_approval").click(function() {
                var consumption_from= $('#consumption_from').data().date;
                if ($("button[type='button']").val() == "save_send_to_approval") {
                    if(!consumption_from){
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Consumption from date is required',
                        })
                        return;
                    }
                    var consumption_to= $('#consumption_to').data().date;
                    if(!consumption_to){
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Consumption to date is required',
                        })
                        return;
                    }
                    var consumption_ref_no= $('#consumption_ref_no').val();
                    if(!consumption_ref_no){
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Consumption ref no is required',
                        })
                        return;
                    }
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You want to save and send to approval this consumption",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, do it!'
                    }).then((result) => {
                        let consumption_data={};
                        var cpa_vessel_id= $('#cpa_vessel_id').val();
                        var vessel_engine_id=[];
                        var fuel_consumption_dtl_id=[];
                        var working_hours=[];
                        var engine_name=[];
                        var hourly_consumed_fuel=[];
                        var total_consumed_fuel=[];
                        var item_remarks=[];
                        $("tbody tr").each(function() {
                            var engine_name_Data = $(this).find('.engine_name').val();
                            var vessel_engine_id_Data = $(this).find('.vessel_engine_id').val();
                            var fuel_consumption_dtl_id_Data = $(this).find('.fuel_consumption_dtl_id').val();
                            var working_hours_Data = $(this).find('.working_hours').val();
                            var hourly_consumed_fuel_Data = $(this).find('.hourly_consumed_fuel').val();
                            var total_consumed_fuel_Data = $(this).find('.total_consumed_fuel').val();
                            var item_remarks_Data = $(this).find('.item_remarks').val();
                            engine_name.push(engine_name_Data);
                            vessel_engine_id.push(vessel_engine_id_Data);
                            fuel_consumption_dtl_id.push(fuel_consumption_dtl_id_Data);
                            hourly_consumed_fuel.push(hourly_consumed_fuel_Data);
                            working_hours.push(working_hours_Data);
                            total_consumed_fuel.push(total_consumed_fuel_Data);
                            item_remarks.push(item_remarks_Data);
                        });
                        consumption_data.cpa_vessel_id=cpa_vessel_id;
                        consumption_data.consumption_from=consumption_from;
                        consumption_data.consumption_to=consumption_to;
                        consumption_data.consumption_ref_no=consumption_ref_no;
                        consumption_data.engine_name=engine_name;
                        consumption_data.vessel_engine_id=vessel_engine_id;
                        consumption_data.fuel_consumption_dtl_id=fuel_consumption_dtl_id;
                        consumption_data.hourly_consumed_fuel=hourly_consumed_fuel;
                        consumption_data.working_hours=working_hours;
                        consumption_data.total_consumed_fuel=total_consumed_fuel;
                        consumption_data.item_remarks=item_remarks;
                        var url = '{{route('cms.fuel-consumption.store-send-to-approval')}}';
                        $.ajax({
                            url: url,
                            type: 'POST',
                            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                            data: consumption_data,
                            beforeSend: function () {
                                $('#ajaxLoader').show();
                            },
                            complete: function () {
                                $("#ajaxLoader").hide();
                            },
                            success: function (res) {
                                if (res.status == false) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: res.status_message,
                                    })
                                    return;
                                }else{
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Great!',
                                        text: res.status_message,
                                    })

                                    $('.datatable').DataTable().ajax.reload();
                                }

                            }
                        });
                    })
                }
            });

        });
    </script>

@endsection



