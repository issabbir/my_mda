@extends('layouts.default')

@section('title')
    Schedule type
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
                        <h4 class="card-title"> {{ isset($data->id)?'Edit':'Add' }} PS Pilotage Schedule Type</h4>
                        <form method="POST" action="">
                            {{ isset($data->id)?method_field('PUT'):'' }}
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row my-1">
                                        <div class="col-md-2">
                                            <label class="input-required">Name<span class="required"></span></label></div>
                                        <div class="col-md-10">
                                            <input type="text" name="name" value="{{ old('name', $data->name) }}" placeholder=" Name" class="form-control"   oninput="this.value = this.value.toUpperCase()" />
                                            @if ($errors->has('name'))
                                                <span class="help-block">{{ $errors->first('name') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
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
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row my-1">
                                        <div class="col-md-2">
                                            <label class="input-required">Start Time<span class="required"></span></label></div>
                                        <div class="col-md-10">
                                            <div class="input-group date" onfocusout="$(this).datetimepicker('hide')" id="startTime" data-target-input="nearest">
                                                <input readonly style="background-color: white;" type="text" name="start_time" value="{{ old('start_time', $data->start_time) }}" class="form-control" data-target="#startTime" data-toggle="datetimepicker" placeholder="Start time"  oninput="this.value = this.value.toUpperCase()" />
                                                <div class="input-group-append" data-target="#startTime" data-toggle="datetimepicker">
                                                    <div class="input-group-text">
                                                        <i class="bx bx-time"></i>
                                                    </div>
                                                </div>
                                            </div>

                                            @if ($errors->has('start_time'))
                                                <span class="help-block">{{ $errors->first('start_time') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="row my-1">
                                        <div class="col-md-2">
                                            <label class="input-required">End Time<span class="required"></span></label>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="input-group date" onfocusout="$(this).datetimepicker('hide')" id="endTime" data-target-input="nearest">
                                                <input readonly style="background-color: white;" type="text" name="end_time" value="{{ old('end_time', $data->end_time) }}" placeholder="End time" class="form-control" data-target="#endTime" data-toggle="datetimepicker"   oninput="this.value = this.value.toUpperCase()" />
                                                <div class="input-group-append" data-target="#endTime" data-toggle="datetimepicker">
                                                    <div class="input-group-text">
                                                        <i class="bx bx-time"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($errors->has('end_time'))
                                                <span class="help-block">{{ $errors->first('end_time') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row my-1">
                                        <div class="col-md-2">
                                            <label class="input-required">Description<span class="required"></span></label>
                                        </div>
                                        <div class="col-md-10">
                                            <textarea type="text" name="description" placeholder=" Description" class="form-control"   oninput="this.value = this.value.toUpperCase()">{{ old('description', $data->description) }}</textarea>
                                            @if ($errors->has('description'))
                                                <span class="help-block">{{ $errors->first('description') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row my-2">
                                        <div class="d-flex justify-content-end col my-1">
                                            <button type="submit" name="save" onclick="return confirm('Are you sure?')" class="btn btn btn-dark shadow mr-1 mb-1">  {{ isset($data->id)?'Update':'Save' }} </button>
                                            <a type="reset" href="{{route("ps-schedule-type")}}" class="btn btn btn-outline-dark  mb-1"> Cancel</a>
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
                    <h4 class="card-title"> PS Pilotage Schedule List</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table table-sm datatable">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Name</th>
                                    <th>Start time</th>
                                    <th>End time</th>
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

            //TODO:Time picker for start time and end time fileds
            /*timePicker("#startTime");
            timePicker("#endTime");*/
            function timePicker(Elem1, Elem2){
                let minElem = $(Elem1);
                let maxElem = $(Elem2);

                minElem.datetimepicker({
                    format: 'HH:mm',
                    ignoreReadonly: true,
                    widgetPositioning: {
                        horizontal: 'left',
                        vertical: 'bottom'
                    },
                    icons: {
                        time: 'bx bx-time',
                        date: 'bx bxs-calendar',
                        up: 'bx bx-up-arrow-alt',
                        down: 'bx bx-down-arrow-alt',
                        previous: 'bx bx-chevron-left',
                        next: 'bx bx-chevron-right',
                        today: 'bx bxs-calendar-check',
                        clear: 'bx bx-trash',
                        close: 'bx bx-window-close'
                    }
                });

                maxElem.datetimepicker({
                    useCurrent: false,
                    format: 'HH:mm',
                    ignoreReadonly: true,
                    widgetPositioning: {
                        horizontal: 'left',
                        vertical: 'bottom'
                    },
                    icons: {
                        time: 'bx bx-time',
                        date: 'bx bxs-calendar',
                        up: 'bx bx-up-arrow-alt',
                        down: 'bx bx-down-arrow-alt',
                        previous: 'bx bx-chevron-left',
                        next: 'bx bx-chevron-right',
                        today: 'bx bxs-calendar-check',
                        clear: 'bx bx-trash',
                        close: 'bx bx-window-close'
                    }
                });
                minElem.on("change.datetimepicker", function (e) {
                    maxElem.datetimepicker('minDate', e.date);
                });
                maxElem.on("change.datetimepicker", function (e) {
                    minElem.datetimepicker('maxDate', e.date);
                });

                let preDefinedDateMin = minElem.attr('data-predefined-date');
                let preDefinedDateMax = maxElem.attr('data-predefined-date');

                if (preDefinedDateMin) {
                    let preDefinedDateMomentFormat = moment(preDefinedDateMin, "YYYY-MM-DD HH:mm").format("HH:mm A");
                    minElem.datetimepicker('defaultDate', preDefinedDateMomentFormat);
                }

                if (preDefinedDateMax) {
                    let preDefinedDateMomentFormat = moment(preDefinedDateMax, "YYYY-MM-DD HH:mm").format("HH:mm A");
                    maxElem.datetimepicker('defaultDate', preDefinedDateMomentFormat);
                }
            }

            timePicker("#startTime", "#endTime");

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
                    url:'{{ route('ps-schedule-type-datatable', isset($data->id)?$data->id:0 ) }}',
                    type:'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                "columns": [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex' },
                    {"data": "name"},
                    {"data":"start_time"},
                    {"data":"end_time"},
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
