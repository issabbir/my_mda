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
                    <h5 style="Color: #132548" class="card-title">Vehicle Rent Details </h5>
                    <hr>
                    @include('mea.vms.vehiclesrent.form')
                </div>
            </div>

            <div class="card">
                <!-- Table Start -->
                <div class="card-body">
                    <h4 class="card-title">Vehicle Rent List</h4><!---->
                    <hr>
                    <div class="table-responsive">
                        <table id="searchResultTable" class="table table-sm datatable mdl-data-table dataTable">
                            <thead>
                            <tr>
                                <th>Vehicle Reg. No.</th>
                                <th>Rent Start Date</th>
                                <th>Rent End Date</th>
                                <th>Action</th>
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

        $(document).ready(function () {
            {{--@if(isset($data['insertedData']->vehicle_rent_id))--}}
            searchResultGridList();
            {{--@endif--}}

            datePicker('#datetimepicker2');
            datePicker('#datetimepicker3');


            let vehicle_id = $('#vehicle_id').val();
            if (vehicle_id) {
                getSupplierDetails(vehicle_id);
            }


            $('#vehicle_id').on('change', function () {
                var vehicle_id = $('#vehicle_id').val();
                if (vehicle_id) {
                    searchResultGridList();
                    getSupplierDetails(vehicle_id);
                }
            });

            function searchResultGridList(e) {
                var tblPreventivi = $('#searchResultTable').DataTable({
                    processing: true,
                    serverSide: true,
                    bDestroy: true,
                    pageLength: 20,
                    bFilter: true,
                    lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
                    ajax: {
                        url: APP_URL + '/vms/datatable-vehicle-rent',
                        data: {
                            "vehicle_id": $("#vehicle_id").val(),
                        },
                    },
                    columns: [
                        {data: 'vehicle_reg_no', name: 'vehicle_reg_no', searchable: true},
                        {data: 'rent_start_date', name: 'rent_start_date', searchable: true},
                        {data: 'rent_end_date', name: 'rent_end_date', searchable: true},
                        {data: 'action', name: 'action'},
                    ],

                });

            }

            function getSupplierDetails(id) {
                $.ajax({
                    type: "GET",
                    url: APP_URL + '/ajax/supplier-details/' + id,
                    success: function (data) {
                        $('#v_supplier_name').val(data.v_supplier_name);
                        $('#v_supplier_address').val(data.v_supplier_address);
                        $('#contact_start_dt').val(data.contact_start_dt);
                    },
                    error: function (data) {
                        alert('error');
                    }
                });
            }
        });

    </script>
@endsection


