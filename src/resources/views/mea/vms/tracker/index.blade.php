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
                    <h5 style="Color: #132548" class="card-title">Tracker Device </h5>
                    <hr>
                     @include('mea.vms.tracker.form')
                </div>
            </div>

            <div class="card">
                <!-- Table Start -->
                <div class="card-body">
                    <h4 class="card-title">Tracker Device List</h4><!---->
                    <hr>
                    <div class="table-responsive">
                        <table id="searchResultTable" class="table table-sm datatable mdl-data-table dataTable">
                            <thead>
                                    <tr>
                                        {{--<th>SL</th>--}}
                                        <th>Device IMEI Number</th>
                                        <th>Tracker Device Company Name</th>
                                        <th>Tracker assigned Vehicle</th>
                                        <th>Status</th>
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

        $(document).ready(function() {

        $('#searchResultTable').DataTable({ 
                processing: true,
                serverSide: true,
                ajax: APP_URL+'/vms/datatable-tracker',
                columns: [
                   // { data: 'tracker_id', name: 'tracker_id',searchable: true },
                    { data: 'tracker_device_imei', name: 'tracker_device_imei',searchable: true },
                    { data: 'tracker_company_name', name: 'tracker_company_name',searchable: true },
                    { data: 'vehicle_reg_no', name: 'vehicle_reg_no',searchable: true },
                    { data: 'active_yn', name: 'active_yn',searchable: true },
                    { data: 'action', name: 'action'},
                ]
            
         });

     });

</script>
@endsection


