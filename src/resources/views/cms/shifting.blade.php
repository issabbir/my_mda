
@extends('layouts.default')

@section('title')
    Shift Setting
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
                        <h4 class="card-title"> {{ isset($data->shifting_id)?'Edit':'Add' }} Shift</h4>
                        <form method="POST" action="" enctype="multipart/form-data">
                            {{ isset($data->shifting_id)?method_field('PUT'):'' }}
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-md-4 mb-1">
                                    <label class="input-required" for="shift_name">Shift Name<span
                                            class="required"></span></label>
                                    <input type="text" name="shift_name"
                                           value="{{ old('shift_name', $data->shift_name) }}"
                                           placeholder="Shift Name" class="form-control"
                                           oninput="this.value = this.value.toUpperCase()" autocomplete="off"/>
                                    @if ($errors->has('shift_name'))
                                        <span class="help-block">{{ $errors->first('shift_name') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-4 mb-1">
                                    <label class="input-required" for="effective_from_date">Shifting From Date<span
                                            class="required"></span></label>
                                    <div class="input-group date" onfocusout="$(this).datetimepicker('hide')" id="effective_from_date" data-target-input="nearest">
                                        <input type="text" name="effective_from_date"  value="{{ old('effective_from_date', $data->effective_from_date) }}" class="form-control effective_from_date" data-target="#effective_from_date" data-toggle="datetimepicker" placeholder="Shifting Form Date" autocomplete="off" />
                                        <div class="input-group-append" data-target="#effective_from_date" data-toggle="datetimepicker">
                                            <div class="input-group-text">
                                                <i class="bx bx-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                    @if($errors->has("effective_from_date"))
                                        <span class="help-block">{{$errors->first("effective_from_date")}}</span>
                                    @endif
                                </div>
                                <div class="col-md-4 mb-1">
                                    <label class="input-required" for="effective_to_date">Shifting To Date<span
                                            class="required"></span></label>
                                    <div class="input-group date" onfocusout="$(this).datetimepicker('hide')" id="effective_to_date" data-target-input="nearest">
                                        <input type="text" name="effective_to_date"  value="{{ old('effective_to_date', $data->effective_to_date) }}" class="form-control effective_from_date" data-target="#effective_to_date" data-toggle="datetimepicker" placeholder="Shifting To Date" autocomplete="off"  />
                                        <div class="input-group-append" data-target="#effective_to_date" data-toggle="datetimepicker">
                                            <div class="input-group-text">
                                                <i class="bx bx-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                    @if($errors->has("effective_to_date"))
                                        <span class="help-block">{{$errors->first("effective_to_date")}}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-1">
                                    <label class="input-required" for="shifting_start_time">Shifting Start time<span
                                            class="required"></span></label>
                                    <div class="input-group date" onfocusout="$(this).datetimepicker('hide')" id="shifting_start_time" data-target-input="nearest">
                                        <input type="text" name="shifting_start_time"  value="{{ old('shifting_start_time', $data->shifting_start_time) }}" class="form-control shifting_start_time" data-target="#shifting_start_time" data-toggle="datetimepicker" placeholder="Shifting start time" autocomplete="off"  />
                                        <div class="input-group-append" data-target="#shifting_start_time" data-toggle="datetimepicker">
                                            <div class="input-group-text">
                                                <i class="bx bx-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                    @if($errors->has("shifting_start_time"))
                                        <span class="help-block">{{$errors->first("shifting_start_time")}}</span>
                                    @endif
                                </div>
                                <div class="col-md-4 mb-1">
                                    <label class="input-required" for="shifting_end_time">Shifting end time<span
                                            class="required"></span></label>
                                    <div class="input-group date" onfocusout="$(this).datetimepicker('hide')" id="shifting_end_time" data-target-input="nearest">
                                        <input type="text" name="shifting_end_time"  value="{{ old('shifting_end_time', $data->shifting_end_time) }}" class="form-control shifting_end_time" data-target="#shifting_end_time" data-toggle="datetimepicker" placeholder="Shifting end time" autocomplete="off" />
                                        <div class="input-group-append" data-target="#shifting_end_time" data-toggle="datetimepicker">
                                            <div class="input-group-text">
                                                <i class="bx bx-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                    @if($errors->has("shifting_end_time"))
                                        <span class="help-block">{{$errors->first("shifting_end_time")}}</span>
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
                                            class="bx bx-save"></i>{{ isset($data->shifting_id)?' Update':' Save' }}
                                    </button>
                                    <a type="reset" href="{{route("cms.setting.shifting")}}"
                                       class="btn btn-outline-dark {{($data->shifting_id)?'mr-1':''}}"><i
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
                    <h4 class="card-title">Shifting List</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table table-sm datatable">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Shift Name</th>
                                    <th>Effective From Date</th>
                                    <th>Effective To Date</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
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
            datePicker("#effective_from_date");
            datePicker("#effective_to_date");
            timePicker("#shifting_start_time");
            timePicker("#shifting_end_time");
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
                    url: '{{ route('cms.setting.shifting-datatable', isset($data->id)?$data->id:0 ) }}',
                    type: 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                "columns": [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                    {"data": "shift_name"},
                    {"data": "formatted_effective_from_date"},
                    {"data": "formatted_effective_end_date"},
                    {"data": "shifting_start_time"},
                    {"data": "shifting_end_time"},
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
        })
    </script>

@endsection



