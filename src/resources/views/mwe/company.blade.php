
@extends('layouts.default')

@section('title')
    Company Setup
@endsection

@section('header-style')
    <!--Load custom style link or css-->
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                @if(Session::has('message'))
                    <div
                        class="alert {{Session::get('m-class') ? Session::get('m-class') : 'alert-danger'}} show"
                        role="alert">
                        {{ Session::get('message') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <div class="card-content">
                    <div class="card-body">
                        <h4 class="card-title"> {{ isset($data->comp_id)?'Edit':'Add' }} Company</h4>
                        <form method="POST" action="" enctype="multipart/form-data">
                            {{ isset($data->comp_id)?method_field('PUT'):'' }}
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Company<span class="required"></span></label>
                                            <input type="text" class="form-control" name="company_name" placeholder="Company Name" oninput="this.value = this.value.toUpperCase()"
                                            value="{{ old('description', $data->company_name) }}" @if($data->company_name!=null) readonly @endif>
                                            @if($errors->has("description"))
                                                <span class="help-block">{{$errors->first("description")}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label>Address</label>
                                            <textarea type="text" rows="3" name="company_address" placeholder="Company Address" class="form-control"   oninput="this.value = this.value.toUpperCase()">{{ old('company_address', $data->company_address) }}</textarea>
                                            @if($errors->has("description"))
                                                <span class="help-block">{{$errors->first("description")}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label>Status</label>
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
                                                <button type="submit" name="save" onclick="return confirm('Are you sure?')" class="btn btn btn-dark shadow mr-1 mb-1">  {{ isset($data->comp_id)?'Update':'Save' }} </button>
                                                <a type="reset" href="{{route("company-setup")}}" class="btn btn btn-outline-dark  mb-1"> Cancel</a>
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
                    <h4 class="card-title">Company List</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table table-sm datatable">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Active?</th>
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
        function companyList() {
            let url = '{{route('mwe.setting.company-setup-datatable')}}';
            let oTable = $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                    url: url,
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'company_name', name: 'company_name', searchable: true},
                    {data: 'company_address', name: 'company_address', searchable: true},
                    {data: 'status', name: 'status', searchable: true},
                    {data: 'action', name: 'action', searchable: false},
                ]
            });
        };

        $(document).ready(function () {
            companyList();
        });
    </script>

@endsection



