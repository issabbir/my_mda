
@extends('layouts.default')

@section('title')
    Work Setup
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
                        <h4 class="card-title"> {{ isset($data->work_id)?'Edit':'Add' }} Work</h4>
                        <form method="POST" action="" enctype="multipart/form-data">
                            {{ isset($data->work_id)?method_field('PUT'):'' }}
                            {!! csrf_field() !!}
                            <div class="row">
                                {{--<div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Work<span class="required"></span></label>
                                            <input type="text" name="name" id="name" class="form-control text-uppercase" value="{{ old('name', $data->name) }}" autocomplete="off">
                                            @if($errors->has("name"))
                                                <span class="help-block">{{$errors->first("name")}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>--}}
                                <div class="col-md-6">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Work<span class="required"></span></label>
                                            <textarea type="text" rows="3" name="name" placeholder="Work Description" class="form-control"   oninput="this.value = this.value.toUpperCase()">{{ old('description', $data->work_title) }}</textarea>
                                            @if($errors->has("description"))
                                                <span class="help-block">{{$errors->first("description")}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Status<span class="required"></span></label>
                                            <ul class="list-unstyled mb-0">
                                                <li class="d-inline-block mr-2 mb-1">
                                                    <fieldset>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input" value="{{ old('status','Y') }}" {{isset($data->active_yn) && $data->active_yn == "Y" ? 'checked' : ''}} name="status" id="customRadio2" checked="">
                                                            <label class="custom-control-label" for="customRadio2">Active</label>
                                                        </div>
                                                    </fieldset>
                                                </li>
                                                <li class="d-inline-block mr-2 mb-1">
                                                    <fieldset>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input" value="{{ old('status','N') }}" {{isset($data->active_yn) && $data->active_yn == "N" ? 'checked' : ''}} name="status" id="customRadio1">
                                                            <label class="custom-control-label" for="customRadio1">Inactive</label>
                                                        </div>
                                                    </fieldset>
                                                </li>
                                            </ul>
                                            @if ($errors->has('status'))
                                                <span class="help-block">{{ $errors->first('status') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-end">
                                <div   class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12" style="margin-top: 20px">
                                            <div class="d-flex justify-content-end col">
                                                <button type="submit" name="save" onclick="return confirm('Are you sure?')" class="btn btn btn-dark shadow mr-1 mb-1">  {{ isset($data->id)?'Update':'Save' }} </button>
                                                <a type="reset" href="{{route("mwe.setting.work-setup")}}" class="btn btn btn-outline-dark  mb-1"> Cancel</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!--List-->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Work List</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table table-sm datatable">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Name</th>
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
            datePicker("#last_maintenance_at");
            datePicker("#next_maintenance_at");

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
                    url: '{{ route('mwe.setting.work-setup-datatable', isset($data->id)?$data->id:0 ) }}',
                    type: 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                "columns": [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                    {"data": "work_title", orderable: true, searchable: true},
                    {"data": "status", orderable: true, searchable: true},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                language: {
                    paginate: {
                        next: '<i class="bx bx-chevron-right">',
                        previous: '<i class="bx bx-chevron-left">'
                    }
                }
            });
        })
    </script>

@endsection



