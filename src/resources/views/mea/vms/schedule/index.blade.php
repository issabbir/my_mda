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
                    <h5 style="Color: #132548" class="card-title">Schedule Setup </h5>
                    <hr>
                    @include('mea.vms.schedule.form')
                </div>
            </div>

            <div class="card">
                <!-- Table Start -->
                <div class="card-body">
                    <h4 class="card-title">Schedule List</h4><!---->
                    <hr>
                    <div class="table-responsive">
                        <table id="searchResultTable" class="table table-sm datatable mdl-data-table dataTable">
                            <thead>
                                    <tr>
                                        {{--<th>SL.</th>--}}
                                        <th>Schedule No</th>
                                        <th>Schedule Name</th>
                                        <th>Schedule Name Bangla</th>
                                        <th>Description</th>
                                        <th>Description Bangla</th>
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
                ajax: APP_URL+'/vms/datatable-schedule',
                columns: [
                    // { data: 'schedule_id', name: 'schedule_id',searchable: true },
                    { data: 'schedule_no', name: 'schedule_no',searchable: true },
                    { data: 'schedule', name: 'schedule',searchable: true },
                    { data: 'schedule_bn', name: 'schedule_bn',searchable: true },
                    { data: 'description', name: 'description',searchable: true },
                    { data: 'description_bn', name: 'description_bn',searchable: true },
                    { data: 'active_yn', name: 'active_yn',searchable: true },
                    { data: 'action', name: 'action'},
                ]
            });

        });

    </script>
@endsection


