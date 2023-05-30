
@extends('layouts.default')

@section('title')
    Fuel Types Setup
@endsection

@section('header-style')
    <!--Load custom style link or css-->
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <h4 class="card-title"> {{ isset($data->fuel_type_id)?'Edit':'Add' }} Fuel Type</h4>
                        <form method="POST" action="" enctype="multipart/form-data">
                            {{ isset($data->fuel_type_id)?method_field('PUT'):'' }}
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-md-4 mb-1">
                                    <label class="input-required" for="fuel_type_name">Fuel Type Name<span
                                            class="required"></span></label>
                                    <input type="text" name="fuel_type_name"
                                           value="{{ old('fuel_type_name', $data->fuel_type_name) }}"
                                           placeholder="Fuel type name" class="form-control"
                                           oninput="this.value = this.value.toUpperCase()" required/>
                                    @if ($errors->has('fuel_type_name'))
                                        <span class="help-block">{{ $errors->first('fuel_type_name') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-4 mb-1">
                                    <label for="fuel_type_name_bn">Fuel Type Name Bangla</label>
                                    <input type="text" name="fuel_type_name_bn"
                                           value="{{ old('fuel_type_name_bn', $data->fuel_type_name_bn) }}"
                                           placeholder="Fuel type name bangla" class="form-control"/>
                                    @if ($errors->has('fuel_type_name_bn'))
                                        <span class="help-block">{{ $errors->first('fuel_type_name_bn') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-4 mb-1">
                                    <label class="input-required">Status<span class="required"></span></label>
                                    <ul class="list-unstyled mb-0">
                                        <li class="d-inline-block mr-2 mb-1">
                                            <fieldset>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" class="custom-control-input"
                                                           value="{{ old('status','A') }}"
                                                           {{isset($data->status) && $data->status == 'A' ? 'checked' : ''}} name="status"
                                                           id="customRadio1" checked="">
                                                    <label class="custom-control-label"
                                                           for="customRadio1">Active</label>
                                                </div>
                                            </fieldset>
                                        </li>
                                        <li class="d-inline-block mr-2 mb-1">
                                            <fieldset>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" class="custom-control-input"
                                                           value="{{ old('status','I') }}"
                                                           {{isset($data->status) && $data->status == 'I' ? 'checked' : ''}} name="status"
                                                           id="customRadio2">
                                                    <label class="custom-control-label"
                                                           for="customRadio2">Inactive</label>
                                                </div>
                                            </fieldset>
                                        </li>
                                    </ul>
                                    @if ($errors->has('status'))
                                        <span class="help-block">{{ $errors->first('status') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button type="submit" name="save" id="submit"
                                            class="btn btn-dark shadow mr-1"><i
                                            class="bx bx-save"></i>{{ isset($data->fuel_type_id)?' Update':' Save' }}
                                    </button>
                                    <a type="reset" href="{{route("cms.setting.fuel")}}"
                                       class="btn btn-outline-dark {{($data->fuel_type_id)?'mr-1':''}}"><i
                                            class="bx bx-window-close"></i> Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!--List-->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"> Fuel Type List</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table table-sm datatable">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Fuel Type Name</th>
                                    <th>Fuel Type Name Bangla</th>
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
                    url:'{{ route('cms.setting.fuel-datatable', isset($data->fuel_type_id)?$data->fuel_type_id:0 ) }}',
                    type:'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                "columns": [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex' },
                    {"data": "fuel_type_name"},
                    {"data": "fuel_type_name_bn"},
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
        });
    </script>

@endsection



