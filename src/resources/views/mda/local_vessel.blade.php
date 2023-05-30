
@extends('layouts.default')

@section('title')
    Local vessel
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
                        <h4 class="card-title"> {{ isset($data->id)?'Edit':'Add' }} Local Vessel</h4>
                        <form method="POST" action="" enctype="multipart/form-data">
                            {{ isset($data->id)?method_field('PUT'):'' }}
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Name<span class="required"></span></label>
                                            <input type="text" name="name" value="{{ old('name', $data->name) }}" placeholder="Name" class="form-control"   oninput="this.value = this.value.toUpperCase()" />
                                            @if ($errors->has('name'))
                                                <span class="help-block">{{ $errors->first('name') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label>call sign</label>
                                            <input type="text" name="call_sign" value="{{old('call_sign', $data->call_sign) }}" placeholder=" Call Sign" class="form-control" oninput="this.value = this.value.toUpperCase()"  />
                                            @if ($errors->has('call_sign'))
                                                <span class="help-block">{{ $errors->first('call_sign') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label>flag</label>
                                            <input type="text" name="flag" value="{{ old('flag', $data->flag) }}" placeholder=" Flag" class="form-control"  oninput="this.value = this.value.toUpperCase()"   />
                                            @if ($errors->has('flag'))
                                                <span class="help-block">{{ $errors->first('flag') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label>grt</label>
                                            <input type="number" min="0" name="grt" value="{{ old('grt', $data->grt) }}" placeholder="Grt" class="form-control"   />
                                            @if ($errors->has('grt'))
                                                <span class="help-block">{{ $errors->first('grt') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label>nrt</label>
                                            <input type="number" min="0" name="nrt" value="{{ old('nrt', $data->nrt) }}" placeholder=" Nrt" class="form-control" />
                                            @if ($errors->has('nrt'))
                                                <span class="help-block">{{ $errors->first('nrt') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label>loa</label>
                                            <input type="number" min="0" name="loa" value="{{ old('loa', $data->loa) }}" placeholder="Loa" class="form-control"   />
                                            @if ($errors->has('loa'))
                                                <span class="help-block">{{ $errors->first('loa') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label>max draught</label>
                                            <input type="text" name="max_draught" value="{{ old('max_draught', $data->max_draught) }}" placeholder="Max Draught" class="form-control"   />
                                            @if ($errors->has('max_draught'))
                                                <span class="help-block">{{ $errors->first('max_draught') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label>total crew officer</label>
                                            <input type="number" min="0" name="total_crew_officer" value="{{ old('total_crew_officer', $data->total_crew_officer) }}" placeholder="Total Crew Officer" class="form-control"    />
                                            @if ($errors->has('total_crew_officer'))
                                                <span class="help-block">{{ $errors->first('total_crew_officer') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label>owner</label>
                                            {{--<input type="text" name="owner_name" value="{{ old('owner_name', $data->owner_name) }}" placeholder="Owner Name" class="form-control"   oninput="this.value = this.value.toUpperCase()" />--}}
                                            <select name="agent_id" class="select2 agent_id">
                                                <option value="">Select a type</option>
                                                @forelse($agencyList as $type)
                                                    <option {{ ($data->agency_id == $type->agency_id) ? "selected" : ""  }} value="{{$type->agency_id}}">{{$type->agency_name}}</option>
                                                @empty
                                                    <option value="">Owner types empty</option>
                                                @endforelse
                                            </select>
                                            @if ($errors->has('owner_name'))
                                                <span class="help-block">{{ $errors->first('owner_name') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label>Owner Address</label>
                                            <textarea type="text" id="agent_address" name="agent_address" class="form-control" readonly>{{$data->owner_address}}</textarea>
                                            @if ($errors->has('agent_address'))
                                                <span class="help-block">{{ $errors->first('agent_address') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label>registration file</label>
                                            <div class="row">
                                                <div class="{{ isset($data->id) ? 'col-md-9' : 'col-md-12' }}">
                                                    <input type="file" name="reg_file" value="{{ old('reg_file', $data->reg_file) }}" placeholder="Registration File" class="form-control"   />
                                                </div>
                                                @if(isset($data->id))
                                                    <div class="col-md-3">
                                                        @if(isset($data->vessel_file->title) && $data->vessel_file->title == "LOCAL_VESSEL")
                                                            @if($data->vessel_file->files != "")
                                                                <a target="_blank" class="form-control" style="text-align: center;" href="{{ route('local-vessel-download-media',$data->vessel_file->id) }}" ><i class="bx bx-download"></i></a>
                                                                <input type="hidden" name="pre_reg_file_id" value="{{ isset($data->vessel_file->id) ? $data->vessel_file->id : '' }}">
                                                            @else
                                                                <span class="form-control" style="text-align: center;"  >No file found</span>
                                                                <input type="hidden" name="pre_reg_file_id" value="{{ isset($data->vessel_file->id) ? $data->vessel_file->id : '' }}">
                                                            @endif
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                            @if ($errors->has('reg_file'))
                                                <span class="help-block">{{ $errors->first('reg_file') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label>registration no</label>
                                            <input type="text" name="reg_no" value="{{ old('total_crew_officer', $data->reg_no) }}" placeholder="Registration No" class="form-control" oninput="this.value = this.value.toUpperCase()"/>
                                            @if ($errors->has('reg_no'))
                                                <span class="help-block">{{ $errors->first('reg_no') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Registration exp date</label>
                                                <div class="input-group date" id="expDate" data-target-input="nearest">
                                                    <div class="input-group-append" data-target="#expDate" data-toggle="datetimepicker">
                                                        <div class="input-group-text">
                                                            <i class="bx bx-calendar"></i>
                                                        </div>
                                                    </div>
                                                    <input type="text"
                                                           name="reg_exp_date"
                                                           value="{{ old('reg_exp_date', $data->reg_exp_date) }}"
                                                           class="form-control datetimepicker-input "
                                                           data-target="#expDate"
                                                           data-toggle="datetimepicker"
                                                           placeholder="Reg exp date">
                                                </div>
                                            </div>
                                            @if($errors->has('reg_exp_date'))
                                                <span class="help-block">{{ $errors->first('reg_exp_date') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Status<span class="required"></span></label>
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
                                </div>
                            </div>
                            <div class="row">
                                {{--<div class="col-md-5">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label>owner address</label>
                                            <textarea type="text" name="owner_address" placeholder="Owner Address" class="form-control" oninput="this.value = this.value.toUpperCase()">{{ old('owner_address', $data->owner_address) }}</textarea>
                                            @if ($errors->has('owner_address'))
                                                <span class="help-block">{{ $errors->first('owner_address') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>--}}

                                <div class="col-md-12 float-right">
                                    <div class="row my-1 ">
                                        <div class="col-md-12">
                                            <label></label>
                                            <div class="d-flex justify-content-end col">
                                                <button type="submit" name="save" onclick="return confirm('Are you sure?')" class="btn btn btn-dark shadow mr-1 mb-1">  {{ isset($data->id)?'Update':'Save' }} </button>
                                                <a type="reset" href="{{route("local-vessel")}}" class="btn btn btn-outline-dark  mb-1"> Cancel</a>
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
                    <h4 class="card-title"> Local Vessel List</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table table-sm datatable">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Name</th>
                                    <th>Call Sign</th>
                                    <th>Owner Name</th>
                                    <th>Owner Address</th>
                                    <th>Reg No</th>
                                    <th>Reg Exp Date</th>
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
        $('.agent_id').on('change', function () {
            let url = '{{route('get-agent-info')}}';
            let agent_id = $(this).find(":selected").val();
            $.ajax({
                type: 'GET',
                url: url,
                data: {agent_id: agent_id},
                success: function (msg) {console.log(msg)
                    $('#agent_address').val(msg);
                }
            });
        });
        $(document).ready(function () {
            datePicker("#expDate");

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
                    url:'{{ route('local-vessel-datatable', isset($data->id)?$data->id:0 ) }}',
                    type:'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                "columns": [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex' },
                    {"data": "name", orderable: true, searchable: true},
                    {"data": "call_sign", orderable: true, searchable: true},
                    // {"data": "owner_name", orderable: true, searchable: true},
                    // {"data": "owner_address", orderable: true, searchable: true},
                    {"data": "agency_name", orderable: true, searchable: true},
                    {"data": "agency_address", orderable: true, searchable: true},
                    {"data": "reg_no", orderable: true, searchable: true},
                    {"data": "reg_exp_date", orderable: true, searchable: true},
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
        });
    </script>

@endsection



