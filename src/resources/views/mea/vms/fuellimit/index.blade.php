@extends('layouts.default')

@section('title')

@endsection

@section('header-style')

    <!--Load custom style link or css-->
@endsection
@section('content')
    <div class="row">
        <div class="col-12">

            <div class="card" id="form-card">
                <div class="card-body">
                    <h5 style="Color: #132548" class="card-title">Fuel Limit Details </h5>
                    <hr>
                    @include('mea.vms.fuellimit.form')
                </div>
            </div>

            <div class="card">
                <!-- Table Start -->
                <div class="card-body">
                    <h4 class="card-title">Fuel Limit Details List</h4><!---->
                    <hr>
                    <div class="table-responsive">
                        <table id="searchResultTable" class="table table-sm datatable mdl-data-table dataTable">
                            <thead>
                                    <tr>
                                        <th>office order no.</th>
                                        <th>office order date</th>
                                        <th>work type</th>
                                        <th>engine type</th>
                                        <th>fuel type</th>
                                        <th>fuel quantity</th>
                                        <th>fuel quantity unit</th>
                                        <th>refuel frequency</th>
                                        <th>active from</th>
                                        <th>single shift</th>
                                        <th>action</th>
                                    </tr>
                            </thead>

                            <tbody id="resultDetailsBody">

                            </tbody>
                        </table>
                    </div>
                    <br> <br>
                </div>
                <!-- Table End -->
            </div>

        </div>
    </div>
@endsection

@section('footer-script')
    <!--Load custom script-->
    <script type="text/javascript">

        $(document).ready(function() {
            datePicker('#officeOrderDatePicker');
            datePicker('#ministryOrderDatePicker');
            datePicker('#boardMeetingDatePicker');
            datePicker('#activeFromDatePicker');
            datePicker('#activeToDatePicker');

            $('#searchResultTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: APP_URL+'/vms/datatable-fuel-limit',
                columns: [
                    { data: 'office_order_no', name: 'office_order_no',searchable: true },
                    { data: 'office_order_date', name: 'office_order_date',searchable: true },
                    { data: 'work_type_name', name: 'work_type_name', searchable: true },
                    { data: 'engine_type_name', name: 'engine_type_name', searchable: true },
                    { data: 'fuel_type_name', name: 'fuel_type_name',searchable: true },
                    { data: 'qty', name: 'qty',searchable: true },
                    { data: 'fuel_unit', name: 'fuel_unit',searchable: true },
                    { data: 'refuel_frequency_type', name: 'refuel_frequency_type',searchable: true },
                    { data: 'active_from', name: 'active_from',searchable: true },
                    { data: 'single_shift_yn', name: 'single_shift_yn',searchable: true },
                    { data: 'action', name: 'action'},
                ]
            });

        });


    </script>
@endsection


