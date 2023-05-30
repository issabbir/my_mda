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
                    <h5 style="Color: #132548" class="card-title">Service Setup </h5>
                    <hr>
                    @include('mea.vms.services.form')
                </div>
            </div>

            <div class="card">
                <!-- Table Start -->
                <div class="card-body">
                    <h4 class="card-title">Service List</h4><!---->
                    <hr>
                    <div class="table-responsive">
                        <table id="searchResultTable" class="table table-sm datatable mdl-data-table dataTable">
                            <thead>
                                    <tr>
                                        {{--<th>SL.</th>--}}
                                        <th>Service Name</th>
                                        {{--<th>Service Name Bangla</th>--}}
                                        <th>Description</th>
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
                ajax: APP_URL+'/vms/datatable-services',
                columns: [
                    // { data: 'service_id', name: 'service_id',searchable: true },
                    { data: 'service_name', name: 'service_name',searchable: true },
                    // { data: 'service_name_bn', name: 'service_name_bn',searchable: true },
                    { data: 'description', name: 'description',searchable: true },
                    { data: 'active_yn', name: 'active_yn',searchable: true },
                    { data: 'action', name: 'action'},
                ]
            });

        }); 

    </script>
@endsection


