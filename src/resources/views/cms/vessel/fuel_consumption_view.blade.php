@extends('layouts.default')

@section('title')
    Fuel Consumption
@endsection

@section('header-style')
    <style>
        :root {
            --prm-color: #039aff;
            --prm-cur-color: #ffc003;
            --prm-pen-color: #b6b1a4;
            --prm-gray: rgba(177, 177, 177, 0.35);
        }
        /*  unnecessary */

        section{
            width:100%;
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
            transition: .4s;020102
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
            -webkit-appearance:none;
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
            @include('cms.vessel.partial.vessel_info')
            <form method="Post" action="{{route('cms.fuel-consumption.send-to-approval.store')}}" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <div class="row">
                    @include('cms.vessel.partial.prv_fuel_receive')
                    @include('cms.vessel.partial.new_fuel_consumption')
                </div>
                <div class="row mb-1">
                    <div class="col-md-12 text-right">
                        <button type="submit" name="save" id="submit"
                                class="btn btn-dark shadow mr-1" {{(in_array($data->status,['P','A','R']))?'disabled':''}}><i
                                class="bx bx-save"></i>Send To Approval
                        </button>
                        <a type="reset" href="{{route("cms.fuel-consumption",['vessel_id'=>$data->cpa_vessel_id])}}"
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
            datePicker("#received_date");
            let vessel_id = GetParameterValues('vessel_id');
            let edit_vessel_id = $('#cpa_vessel_id').val();
            $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 20,
                lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
                initComplete: function (settings, json) {
                    $('body').find('.dataTables_scrollBody').css("height", "auto");
                    $('body').find('.dataTables_scrollBody').css("max-height", "300px");
                },
                ajax: {
                    url: '{{route("cms.fuel-consumption.datatable")}}',
                    type: 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    'data': {
                        'vessel_id': (vessel_id) ? vessel_id : edit_vessel_id
                    },
                },
                "columns": [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
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

            // $('#consumption_from').datetimepicker({
            //     format: 'DD/MM/YYYY',
            //     widgetPositioning: {
            //         horizontal: 'left',
            //         vertical: 'bottom'
            //     },
            //     icons: {
            //         date: 'bx bxs-calendar',
            //         previous: 'bx bx-chevron-left',
            //         next: 'bx bx-chevron-right'
            //     }
            // });
            //
            // $('#consumption_to').datetimepicker({
            //     format: 'DD/MM/YYYY',
            //     widgetPositioning: {
            //         horizontal: 'left',
            //         vertical: 'bottom'
            //     },
            //     icons: {
            //         date: 'bx bxs-calendar',
            //         previous: 'bx bx-chevron-left',
            //         next: 'bx bx-chevron-right'
            //     }
            // });

            function GetParameterValues(param) {
                var url = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
                for (var i = 0; i < url.length; i++) {
                    var urlparam = url[i].split('=');
                    if (urlparam[0] == param) {
                        return urlparam[1].replace("#/", "");
                    }
                }
            }

            // $("#consumption_from").on("change.datetimepicker", function (e) {
            //     $('#consumption_to').datetimepicker('minDate', e.date);
            // });
            //
            // $("#consumption_to").on("change.datetimepicker", function (e) {
            //     $('#consumption_from').datetimepicker('maxDate', e.date);
            // });

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
            function calculatedTotalConsumedFuel(affectedRow) {
                let total_consumed_fuel = 0;
                let hourly_consumed_fuel = $(affectedRow).closest('table tr').find('.hourly_consumed_fuel').val();
                let working_hours = $(affectedRow).closest('table tr').find('.working_hours').val();
                total_consumed_fuel = hourly_consumed_fuel * working_hours;
                $(affectedRow).closest('table tr').find('.total_consumed_fuel').val(total_consumed_fuel.toFixed(2));
            }

            /************calculated total  fuel  function*********/
            function calculatedTotalFuel() {
                let total_fuel = 0;
                let received_fuel = parseInt(($(document).find('.received_fuel').val()) ? $(document).find('.received_fuel').val() : 0);
                let reserved_fuel = parseInt(($(document).find('.reserved_fuel').val()) ? $(document).find('.reserved_fuel').val() : 0);
                total_fuel = received_fuel + reserved_fuel;
                $("#total_fuel").val(parseFloat(received_fuel + reserved_fuel).toFixed(2));
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
                $("#total_fuel_consumption").text(sum_of_total_fuel_consumption);
            }
        });
    </script>

@endsection



