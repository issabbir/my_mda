<form id="searchResultPeriodGridList" method="post" autocomplete="off"
      @if(isset($data['insertedData']->schedule_id))
      action="{{ route('schedule-update', ['id' => $data['insertedData']->schedule_id]) }}">
    <input name="_method" type="hidden" value="PUT">
    @else
        action="{{ route('schedule-store') }}">
    @endif

    {{ csrf_field() }}

    <div class="row">
        <div class="col-12">
            <fieldset class="border col-sm-12">
                <legend class="w-auto required" style="font-size: 14px;"> Information</legend>
                <div class="col-12">
                    <div class="row my-1">
                        <div class="col-md-4">
                            <label for="schedule_no" class="required">Schedule No. :</label>
                            <input type="text"
                                   value="{{isset($data['insertedData']->schedule_no) ? $data['insertedData']->schedule_no : ''}}"
                                   class="form-control"
                                   id="schedule_no"
                                   name="schedule_no"
                                   placeholder="Schedule No."
                                   autocomplete="off"
                                   required
                            >
                            <span class="text-danger"></span>
                        </div>

                        <div class="col-md-4">
                            <label for="schedule" class="required">Schedule Name :</label>
                            <input type="text"
                                   value="{{isset($data['insertedData']->schedule) ? $data['insertedData']->schedule : ''}}"
                                   class="form-control"
                                   id="schedule"
                                   name="schedule"
                                   placeholder="Schedule Name"
                                   autocomplete="off"
                                   required
                            >
                            <span class="text-danger"></span>
                        </div>

                        <div class="col-md-4">
                            <label for="schedule_bn">Schedule Name(Bangla):</label>
                            <input type="text"
                                   value="{{isset($data['insertedData']->schedule_bn) ? $data['insertedData']->schedule_bn : ''}}"
                                   class="form-control"
                                   id="schedule_bn"
                                   name="schedule_bn"
                                   placeholder="Schedule Name(Bangla)"
                                   autocomplete="off"
                            />
                            <span class="text-danger"></span>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="row my-1">
                        <div class="col-sm-9">
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea placeholder="Description"
                                          rows="2" wrap="soft"
                                          name="description"
                                          class="form-control"
                                          id="description">{!! old('description',isset($data['insertedData']->description) ? $data['insertedData']->description : '')!!}</textarea>
                                <small class="text-muted form-text"> </small>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label class="required">Status :</label>
                            <div>
                                </br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="active_yn"
                                           required id="active_yn_yes" value="Y" checked
                                           @if(isset($data['insertedData']->active_yn) && $data['insertedData']->active_yn == "Y")
                                           checked
                                        @endif
                                    />
                                    <label class="form-check-label"
                                           for="active_yn_yes">Active</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="active_yn"
                                           required id="active_yn_no" value="N"
                                           @if(isset($data['insertedData']->active_yn) && $data['insertedData']->active_yn == "N")
                                           checked
                                        @endif
                                    />
                                    <label class="form-check-label"
                                           for="active_yn_no">In-Active</label>
                                </div>
                                <span class="text-danger"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="row my-1">
                        <div class="col-sm-9">
                            <div class="form-group">
                                <label for="description_bn">Description(Bangla)</label>
                                <textarea placeholder="Description(Bangla)"
                                          rows="2" wrap="soft"
                                          name="description_bn"
                                          class="form-control"
                                          id="description_bn">{!! old('description_bn',isset($data['insertedData']->description_bn) ? $data['insertedData']->description_bn : '')!!}</textarea>

                                <small class="text-muted form-text"> </small>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label>&nbsp;</label>
                            <div class="d-flex justify-content-end col">
                                <button type="submit" id="submit"
                                        class="btn btn btn-dark shadow mr-1 mb-1 btn-secondary">
                                    @if(empty($data['insertedData']->schedule_id))
                                        Save
                                    @else
                                        Update
                                    @endif
                                </button> &nbsp;

                                @if(empty($data['insertedData']->schedule_id))
                                    <button type="reset" class="btn btn-light-secondary mb-1">
                                        Reset
                                    </button>
                                @else
                                    <a href="{{route('schedule-index')}}">
                                        <button type="button" class="btn btn-light-secondary mb-1">
                                            Reset
                                        </button>
                                    </a>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </fieldset>

        </div>

    </div>
</form>


