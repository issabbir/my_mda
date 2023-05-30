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
                    <h5 style="Color: #132548" class="card-title">Supplier Setup </h5>
                    <hr>
                    @include('mea.vms.supplier.form')
                </div>
            </div>

            <div class="card">
                <!-- Table Start -->
                <div class="card-body">
                    <h4 class="card-title">Supplier List</h4><!---->
                    <hr>
                    <div class="table-responsive">
                        <table id="searchResultTable" class="table table-sm datatable mdl-data-table dataTable">
                            <thead>
                            <tr>
                            <!--  {{--<th>SL.</th>--}} -->
                                <th>Supplier Name</th>
                                <th>Service Name Bangla</th>
                                <th>Supplier Contact</th>
                                <th>Contact Person</th>
                                <th>Contact Person Mobile</th>
                                <th>Tender Reference</th>
                                <th>Contact Start Date</th>
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

        $(document).ready(function () {
            // @if(isset($data['insertedData']->cpa_depot_yn))
            //     changeDepot('{{$data['insertedData']->cpa_depot_yn}}');
            // @endif

            datePicker('#datetimepicker1');
            datePicker('#datetimepicker2');

            $('#searchResultTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: APP_URL + '/vms/datatable-supplier',
                columns: [
                    {data: 'v_supplier_name', name: 'v_supplier_name', searchable: true},
                    {data: 'v_supplier_name_bn', name: 'v_supplier_name_bn', searchable: true},
                    {data: 'v_supplier_contact_no', name: 'v_supplier_contact_no', searchable: true},
                    {data: 'contact_person_name', name: 'contact_person_name', searchable: true},
                    {data: 'contact_person_mobile', name: 'contact_person_mobile', searchable: true},
                    {data: 'supplier_tender_reff', name: 'supplier_tender_reff', searchable: true},
                    {data: 'contact_start_dt', name: 'contact_start_dt', searchable: true},
                    {data: 'active_yn', name: 'active_yn', searchable: true},
                    {data: 'action', name: 'action'},
                ]
            });


            var serviceFormIndex = 1;
                @if(isset($data['insertedData']))
            var insertedDocsData = [{!! json_encode($data['insertedDocsData'])!!}];
            $.each(insertedDocsData, function (rowIndex, rowValue) {
                $.each(rowValue, function (index, value) {
                    var doc_master_id_selected = value.doc_master_id;
                    var doc_file_selected = value.doc_file;
                    var doc_file_name_selected = value.doc_file_name;
                    var doc_file_path_selected = value.doc_file_path;
                    var doc_type_selected = value.doc_type;
                    var doc_type_id_selected = value.doc_type_id;
                    rowGenerate(index + 1, doc_master_id_selected, doc_file_selected, doc_file_name_selected, doc_file_path_selected, doc_type_selected, doc_type_id_selected); //, value.emp_id is the insertedData To make Selected
                });
            });
            @else
            rowGenerate(serviceFormIndex);
            @endif

            if ($('#serviceForm tr')) {
                serviceFormIndex = $('#serviceForm tr').length + 1;
            }
            $('#addServiceForm').on('click', function (e) {
                e.preventDefault();
                rowGenerate(serviceFormIndex);
            });

            $("#serviceForm").on('click', '.remove-education-form', function (e) {
                var rowNumArr = e.target.id.split('removeId-');
                let details_id = $('#doc_master_id' + rowNumArr[1]).val();
                let rowRemoveFlag = 0;

                if (confirm("Are you sure you want to delete this?")) {
                    if (
                        (details_id !== undefined) && (details_id !== null) && (details_id !== '')
                    ) {

                        if (details_id) {

                            $.ajax({
                                type: "GET",
                                url: APP_URL + '/ajax/doc-supplier-delete/' + details_id,
                                success: function (data) {
                                    alert(data.o_status_message);
                                    if (data.o_status_code == '1') {
                                        rowRemoveFlag = 1;
                                        // sumServiceJobCost();
                                    }
                                },
                                error: function (data) {
                                    alert('error');
                                }
                            });

                            //if(rowRemoveFlag)
                            $(this).parent().parent().remove();

                        } else {
                            e.preventDefault();
                            return false;
                        }
                    } else {
                        $(this).parent().parent().remove();
                        //sumServiceJobCost();
                    }

                } else {
                    e.preventDefault();
                    return false;
                }
                e.preventDefault();

            });


            function rowGenerate(index, doc_master_id_selected = 0, doc_file_selected = '', doc_file_name_selected = '', doc_file_path_selected = '', doc_type_selected = '', doc_type_id_selected = 1) {
                serviceFormIndex = index;
                //console.log(serviceFormIndex);
                let dynamicServiceHiddenId = '<input type="hidden" name="doc_master_id[' + serviceFormIndex + ']" id="doc_master_id' + serviceFormIndex + '" value="" >';
                let dynamicDocsFileName = '<input type="text" required name="doc_file_name[' + serviceFormIndex + ']" id="doc_file_name' + serviceFormIndex + '" value="' + doc_file_name_selected + '" placeholder="File Name" class="form-control" >';
                let dynamicDocsFile = '<div class="col-sm-12"><div class="row">' +
                    '<div class="custom-file b-form-file form-group col-sm-7">' +
                    '<input type="file" value=""' +
                    'class="custom-file-input"' +
                    //'data-toggle="datetimepicker" data-target="#serviceDatePick'+ serviceFormIndex +'"'+
                    'name="doc_file[' + serviceFormIndex + ']"' +
                    'id="doc_file' + serviceFormIndex + '"' +
                    ' />' +
                    '<label for="driver_photo" data-browse="Browse"' +
                    'class="custom-file-label required">Attached</label>' +
                    '</div>';
                if (doc_file_selected) {
                    dynamicDocsFile += '<div class="col-sm-5 defaultImgDiv">' +
                        '<img class="defaultImg"' +
                        'src="data:' + doc_type_selected + ';base64,' + doc_file_selected + '"' +
                        'alt="' + doc_file_name_selected + '"' +
                        'width="70"' +
                        'height="80"/> &nbsp;' +
                        '<a href="' + APP_URL + '/vms/supplier-attachments/download/' + doc_master_id_selected + '" target="_blank">' + doc_file_name_selected + ' Download</a>' +
                        '</div>';
                }
                dynamicDocsFile += '</div></div>';

                let removeButton = '';
                if (serviceFormIndex == 1) {
                    removeButton = '';
                } else {
                    removeButton = '<button id="removeId-' + serviceFormIndex + '" type="button" class="btn mr-2 btn-secondary btn-sm remove-education-form" name="removeServiceForm">Remove</button>';
                }

                let dynamicServiceFormTemplate = '<tr role="row">' +
                    '<td aria-colindex="1" role="cell">' + dynamicServiceHiddenId + dynamicDocsFileName + '</td>' +
                    '<td aria-colindex="2" role="cell">' + dynamicDocsFile + '</td>' +
                    '<td aria-colindex="7" role="cell">' + removeButton + '</td>' +
                    '</tr>';
                $(dynamicServiceFormTemplate).fadeIn("slow").appendTo('#serviceForm');

                if (doc_master_id_selected)
                    $('#doc_master_id' + serviceFormIndex).val(doc_master_id_selected);

                serviceFormIndex++;
            }

        });

    </script>
@endsection
