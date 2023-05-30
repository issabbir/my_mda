@extends('layouts.default')

@section('title')
    Assign Third Party
@endsection

@section('header-style')
    <!--Load custom style link or css-->

@endsection
@section('content')

    <div class="content-body">
        <section id="form-repeater-wrapper">
            <!-- form default repeater -->
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
                                <form enctype="multipart/form-data" action="{{route('mwe.operation.assign-party')}}"
                                      method="post">
                                    {!! csrf_field() !!}

                                    <h5 class="card-title">Assign Third Party</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="row my-1">
                                                <div class="col-md-12">
                                                    <label class="input-required">Request Number</label>
                                                    <input type="text" class="form-control" disabled
                                                           value="{{isset($mData)?$mData->request_number:''}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row my-1">
                                                <div class="col-md-12">
                                                    <label class="input-required">Job Number</label>
                                                    <input type="text" class="form-control" disabled
                                                           value="{{isset($mData)?$mData->inspector_job_number:''}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row my-1">
                                                <div class="col-md-12">
                                                    <label class="input-required">VESSEL NAME</label>
                                                    <input type="text" class="form-control" disabled
                                                           value="{{isset($mData)?$mData->vessel_name:''}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row my-1">
                                                <div class="col-md-12">
                                                    <label class="input-required">DEPARTMENT</label>
                                                    <input type="text" class="form-control" disabled
                                                           value="{{isset($mData)?$mData->department_name:''}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row my-1">
                                                <div class="col-md-12">
                                                    <label class="input-required">Task</label>
                                                    <input type="text" class="form-control" disabled
                                                           value="{{isset($mData)?$mData->name:''}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row my-1">
                                                <div class="col-md-12">
                                                    <label class="input-required">REQUEST DATE</label>
                                                    <input type="text" class="form-control" disabled
                                                           value="{{isset($mData)?date('d-m-Y', strtotime($mData->request_date)):''}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-9 mt-1">
                                            <label for="description"
                                                   class="form-control-label text-uppercase">Remarks</label>
                                            <textarea name="remarks"
                                                      {{--@if(isset($data->remarks))
                                                      @if($data->remarks != null) readonly
                                                      @endif
                                                      @endif--}}
                                                      id="remarks"
                                                      oninput="this.value = this.value.toUpperCase()"
                                                      class="form-control" style="height:200px;"
                                                      rows="4"
                                                      cols="200">{{isset($data->remarks) ? $data->remarks : ''}}</textarea>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <div class="row-md-3 mt-1">
                                                <label class="required">Third Party</label>
                                                <select class="custom-select select2 form-control" required
                                                        id="thirdparty_id" name="thirdparty_id">
                                                    <option value="">Select One</option>
                                                    @foreach($thirdpartyList as $value)
                                                        <option value="{{$value->thirdparty_id}}"
                                                            {{isset($data->thirdparty_id) && $data->thirdparty_id == $value->thirdparty_id ? 'selected' : ''}}
                                                        >{{$value->thirdparty_name}}</option>
                                                    @endforeach
                                                </select>
                                                <input type='hidden' name='thirdparty_req_id'
                                                       value='{{isset($thirdparty_req_id) ? $thirdparty_req_id : ''}}'>
                                                <input type='hidden' name='assign_id'
                                                       value='{{isset($data) ? $data->assign_id : ''}}'>
                                            </div>
                                            <div class="row-md-3 mt-1">
                                                <label class="required">Forward To</label>
                                                <select class="custom-select select2 form-control" required
                                                        id="forward_to_sae" name="forward_to_sae">
                                                    <option value="">Select One</option>
                                                    @foreach($users as $value)
                                                        <option value="{{$value->emp_id}}"
                                                            {{isset($data->forward_to_sae) && $data->forward_to_sae == $value->emp_id ? 'selected' : ''}}
                                                        >{{$value->emp_code.'-'.$value->emp_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-12 pr-0 d-flex justify-content-end">
                                            <div class="form-group">
                                                @if(empty($data) && $data == null)
                                                    <button id="boat-employee-save" type="submit"
                                                            class="btn btn-secondary mb-1 mr-1 hvr-underline-reveal mt-2">
                                                        <i
                                                            class="bx bx-chevrons-down"></i> Assign
                                                    </button>
                                                @else
                                                    {{--@if(isset($data->active_yn) && $data->active_yn == "Y")--}}
                                                    <button id="boat-employee-save" type="submit"
                                                            class="btn btn-secondary mb-1 mr-1 hvr-underline-reveal mt-2"><i
                                                            class="bx bx-upvote"></i>
                                                        Update
                                                    </button>
                                                    {{--@endif--}}
                                                @endif
                                                <button onclick="$('#new_party').toggle('slow')"
                                                        class="btn btn-secondary mb-1 mr-1 hvr-underline-reveal mt-2"
                                                        type="button">
                                                    <i class="bx bxs-add-to-queue"></i> Add New
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                </form>

                                <form enctype="multipart/form-data" style="display: none;padding: 1rem 0"
                                      action="{{route('mwe.operation.add-new-party')}}" method="post" id="new_party">
                                    {!! csrf_field() !!}
                                    <h5 class="card-title">Add Third Party Details</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-4 mt-1">
                                            <label class="required">Third Party Name</label>
                                            <input type="text"
                                                   placeholder="Third Party Name"
                                                   name="thirdparty_name" autocomplete="off"
                                                   class="form-control" required
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="50"
                                            >
                                            <input type="hidden" name="thirdparty_req_id"
                                                   value="{{isset($thirdparty_req_id) ? $thirdparty_req_id : ''}}">
                                        </div>
                                        <div class="col-md-4 mt-1">
                                            <label>Third Party Name (Bangla)</label>
                                            <input type="text"
                                                   placeholder="Third Party Name (Bangla)"
                                                   name="thirdparty_name_bn" autocomplete="off"
                                                   class="form-control"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="500"
                                            >
                                        </div>
                                        <div class="col-md-4 mt-1">
                                            <label>Mobile</label>
                                            <input type="text" autocomplete="off"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="11"
                                                   placeholder="Mobile"
                                                   name="contact_no"
                                                   class="form-control"
                                            >
                                        </div>
                                        <div class="col-md-12 mt-1">
                                            <label>Address</label>
                                            <input type="text"
                                                   placeholder="Address"
                                                   name="contact_address" autocomplete="off"
                                                   class="form-control"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="500"
                                            >
                                        </div>
                                    </div>

                                    <div class="row">
                                        <fieldset class="border mt-2 mb-2 col-md-12">
                                            <legend class="w-auto" style="font-size: 18px;">Documents
                                            </legend>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div id="start-no-field" class="form-group">
                                                        <label class="required">Document Name</label>
                                                        <input type="text" id="case_doc_name"
                                                               class="form-control"
                                                               oninput="this.value = this.value.toUpperCase()"
                                                               placeholder="Case Document Name" value=""
                                                               autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label class="required">Attachment</label>
                                                        <input type="file" class="form-control" id="attachedFile"
                                                               onchange="encodeFileAsURL();"/>
                                                    </div>
                                                    <input type="hidden" id="converted_file" name="converted_file">
                                                </div>

                                                <div class="col-md-4">
                                                    <div id="start-no-field"
                                                         class="form-group">
                                                        <button type="button" id="append"
                                                                class="btn btn btn-dark shadow mr-1 mt-2 btn-secondary add-row-doc">
                                                            <i class="bx bxs-folder-plus"></i> ADD
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="table-responsive">

                                                        <table class="table table-striped table-bordered"
                                                               id="table-doc">
                                                            <thead>
                                                            <tr>
                                                                <th style="height: 25px;text-align: left; width: 5%">#
                                                                </th>
                                                                <th style="height: 25px;text-align: left; width: 40%">
                                                                    Document Name
                                                                </th>
                                                                <th style="height: 25px;text-align: left; width: 60%">
                                                                    Attachment
                                                                </th>
                                                            </tr>
                                                            </thead>

                                                            <tbody id="file_body">
                                                            {{--@if(!empty($caseDocData))
                                                                @foreach($caseDocData as $key=>$value)
                                                                    <tr>
                                                                        <td>
                                                                            <input type='checkbox' name='record'>
                                                                            <input type='hidden' name='case_doc_id[]'
                                                                                   value='{{($value)?$value->case_doc_id:'0'}}'
                                                                                   class="case_doc_id">
                                                                            <input type='hidden' name='case_doc_name[]'
                                                                                   value='{{($value)?$value->case_doc_name:''}}'
                                                                                   class="case_doc_name">
                                                                            <input type='hidden' name='case_doc[]'
                                                                                   value='{{($value)?$value->case_doc:''}}'
                                                                                   class="case_doc">
                                                                            <input type='hidden' name='case_doc_type[]'
                                                                                   value='{{($value)?$value->case_doc_type:''}}'
                                                                                   class="case_doc_type">
                                                                        </td>
                                                                        <td><input type="text" class="form-control"
                                                                                   name="doc_description[]"
                                                                                   value="{{$value->doc_description}}">
                                                                        </td>
                                                                        <td>@if(isset($value->case_doc))
                                                                                <a href="{{ route('cms.case-info.case-info-file-download', [$value->case_doc_id]) }}"
                                                                                   target="_blank">{{$value->case_doc_name}}</a>
                                                                            @endif</td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif--}}
                                                            </tbody>
                                                        </table>
                                                        <button type="button"
                                                                class="btn btn btn-dark shadow mr-1 mb-1 btn-secondary delete-row-file">
                                                            <i class="bx bxs-folder-minus"></i> Delete
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>

                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-12 pr-0 d-flex justify-content-end">
                                            <div class="form-group">
                                                <button id="boat-employee-save" type="submit"
                                                        class="btn btn-secondary mb-1 mr-1 hvr-underline-reveal mt-2"><i
                                                        class="bx bxs-save"></i> Save
                                                </button>

                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <div class="form-group">
                                    <div class="col-md-12 pr-0 d-flex justify-content-end">
                                        <div class="form-group">
                                            <a type="reset" href="{{route("mwe.operation.third-party-requests")}}"
                                               class="btn btn-light-secondary mb-1"><i
                                                    class="bx bxs-left-arrow"></i> Back</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ form default repeater -->

        </section>
    </div>

@endsection

@section('footer-script')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">

        function encodeFileAsURL() {
            var file = document.querySelector('input[type=file]')['files'][0];
            var reader = new FileReader();
            var baseString;
            reader.onloadend = function () {
                baseString = reader.result;
                $("#converted_file").val(baseString);
                //console.log(baseString);
            };
            reader.readAsDataURL(file);
        }

        $(".add-row-doc").click(function () {
            let doc_name = $("#case_doc_name").val();
            let converted_file = $("#converted_file").val();
            let filePath = $("#attachedFile").val();
            let file_ext = '';
            let fileName = '';

            if (filePath) {
                file_ext = filePath.substr(filePath.lastIndexOf('.') + 1, filePath.length);
                fileName = document.getElementById('attachedFile').files[0].name;
            }

            if (doc_name && converted_file) {
                let markup = "<tr><td><input type='checkbox' name='record'>" +
                    "<input type='hidden' name='tab_title[]' value='" + doc_name + "'>" +
                    "<input type='hidden' name='tab_doc_type[]' value='" + file_ext + "'>" +
                    "<input type='hidden' name='tab_doc[]' value='" + converted_file + "'>" +
                    "</td><td>" + doc_name + "</td><td>" + fileName + "</td></tr>";
                $("#case_doc_name").val("");
                $("#attachedFile").val("");
                $("#table-doc tbody").append(markup);
            } else {
                Swal.fire({
                    title: 'Fill required value.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });

        $(".delete-row-file").click(function () {
            $("#table-doc tbody").find('input[name="record"]').each(function () {
                if ($(this).is(":checked")) {
                    let case_doc_id = $(this).closest('tr').find('.case_doc_id').val();
                    if (case_doc_id) {
                        $.ajax({
                            type: 'GET',
                            url: '/case-doc-remove',
                            data: {case_doc_id: case_doc_id},
                            success: function (msg) {
                                $(this).parents("tr").remove();
                                Swal.fire({
                                    title: 'Entry Successfully Deleted!',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(function () {
                                    $('td input:checked').closest('tr').remove();
                                });
                            }
                        });
                    } else {
                        $(this).parents("tr").remove();
                    }
                }
            });
        });

        $(document).ready(function () {

        });

    </script>

@endsection

