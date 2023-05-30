@extends('layouts.default')

@section('title')
    Vessel conditions
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
                        <h4 class="card-title"> {{ isset($data->id)?'Edit':'Add' }} Employee Office Setup</h4>
                        <form method="POST" action="">
                            {{ isset($data->id)?method_field('PUT'):'' }}
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-md-4 mt-1">
                                    <div id="start-no-field" class="form-group">
                                        <label class="required">Office</label>
                                        <select id="office_id" name="office_id" class="form-control select2">
                                            <option value="">Select one</option>
                                            @forelse($offices as $office)
                                                <option
                                                    {{ ($data->office_id == $office->office_id) ? "selected" : ""  }} value="{{$office->office_id}}">{{$office->office_name}}</option>
                                            @empty
                                                <option value="">Office</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 mt-1">
                                    <label class="required">Employee</label>
                                    <select class="custom-select select2 form-control emp_id" required
                                            style="width: 100%;"
                                            id="emp_id" name="emp_id">
                                        @if(isset($data))
                                            <option
                                                value="{{$data->emp_id}}">{{$data->emp_name}}</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-4 mt-3">
                                    <div class="d-flex justify-content-end col">
                                        <button type="submit" name="save" onclick="return confirm('Are you sure?')" class="btn btn btn-dark shadow mr-1 mb-1">  {{ isset($data->id)?'Update':'Save' }} </button>
                                        <a type="reset" href="{{route("mwe.setting.office-setup")}}" class="btn btn btn-outline-dark  mb-1"> Cancel</a>
                                    </div>
                                </div>
                                {{--<div class="col-md-4 mt-3">
                                    <div class="row my-1">
                                        <div class="col-md-2"><label class="input-required">Status<span class="required"></span></label></div>
                                        <div class="col-md-10">
                                            <ul class="list-unstyled mb-0">
                                                <li class="d-inline-block mr-2 mb-1">
                                                    <fieldset>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input" value="{{ old('status','A') }}" {{isset($data->status) && $data->status == 'A' ? 'checked' : ''}} name="status" id="customRadio1" checked="">
                                                            <label class="custom-control-label" for="customRadio1">Active</label>
                                                        </div>
                                                    </fieldset>
                                                </li>
                                                <li class="d-inline-block mr-2 mb-1">
                                                    <fieldset>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input" value="{{ old('status','I') }}" {{isset($data->status) && $data->status == 'I' ? 'checked' : ''}} name="status" id="customRadio2">
                                                            <label class="custom-control-label" for="customRadio2">Inactive</label>
                                                        </div>
                                                    </fieldset>
                                                </li>
                                            </ul>
                                            @if ($errors->has('status'))
                                                <span class="help-block">{{ $errors->first('status') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>--}}
                            </div>
                            {{--<div class="row my-1">
                                <div class="d-flex justify-content-end col">
                                    <button type="submit" name="save" onclick="return confirm('Are you sure?')" class="btn btn btn-dark shadow mr-1 mb-1">  {{ isset($data->id)?'Update':'Save' }} </button>
                                    <a type="reset" href="{{route("vessel-condition")}}" class="btn btn btn-outline-dark  mb-1"> Cancel</a>
                                </div>
                            </div>--}}
                        </form>
                    </div>
                </div>
            </div>

            <!--List-->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">EMployee Office Setup List</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table table-sm datatable">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Office</th>
                                    <th>Employee</th>
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
        let vtmisVessel = '{{route('get-all-employee')}}';
        $('.emp_id').select2({
            placeholder: "Select one",
            ajax: {
                url: vtmisVessel,
                data: function (params) {
                    if (params.term) {
                        if (params.term.trim().length < 1) {
                            return false;
                        }
                    } else {
                        return false;
                    }

                    return params;
                },
                dataType: 'json',
                processResults: function (data) {
                    var formattedResults = $.map(data, function (obj, idx) {
                        obj.id = obj.emp_id;
                        obj.text = obj.emp_name;
                        return obj;
                    });
                    return {
                        results: formattedResults,
                    };
                }
            }
        });

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
                    url:'{{ route('mwe.setting.office-setup-datatable', isset($data->id)?$data->id:0 ) }}',
                    type:'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                "columns": [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex' },
                    {"data": "office_name", orderable: true, searchable: true},
                    {"data": "emp_name", orderable: true, searchable: true},
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



