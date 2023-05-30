<fieldset class="border mt-2 col-md-12">
    <legend class="w-auto" style="font-size: 18px;">Documents Upload</legend>

    <div class="row">
        <div class="col-md-3">
            <div id="start-no-field" class="form-group">
                <label for="seat_from">Document Name</label>
                <input type="text" id="case_doc_name"
                       class="form-control "
                       placeholder="Document Name" value="" autocomplete="off">
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label for="order_attachment" class="">Attachment</label>
                <input type="file" class="form-control" id="attachedFile"
                       onchange="encodeFileAsURL();"/>
            </div>
            <input type="hidden" id="converted_file">
        </div>

        <div class="col-md-1">
            <div id="start-no-field"
                 class="form-group">
                <label for="seat_to1">&nbsp;</label><br/>
                <button type="button" id="append"
                        class="btn btn btn-dark shadow mr-1 mb-1 btn-secondary add-row-doc">
                    ADD
                </button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">

                <table class="table table-striped table-bordered" id="table-doc">
                    <thead>
                    <tr>
                        <th style="height: 25px;text-align: left; width: 5%">#</th>
                        <th style="height: 25px;text-align: left; width: 50%">
                            Document Name
                        </th>
                        <th style="height: 25px;text-align: left; width: 40%">
                            Attachment
                        </th>
                    </tr>
                    </thead>

                    <tbody id="file_body">
                    @if(isset($docData))
                        @foreach($docData as $key=>$value)
                            <tr>
                                <td>
                                    <input type='checkbox' name='record'>
                                    <input type='hidden' name='doc_id[]'
                                           value='{{($value)?$value->id:'0'}}'
                                           class="doc_id">
                                </td>
                                <td aria-colindex="7"
                                    role="cell">{{$value->title}}
                                </td>
                                <td>@if(isset($value->files))
                                        <a href="{{ route('file-download', [$value->id]) }}"
                                           target="_blank"><i class='bx bxs-download cursor-pointer'></i></a>
                                    @endif</td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
                <button type="button"
                        class="btn btn btn-dark shadow mr-1 mb-1 btn-secondary delete-row-file">
                    Delete
                </button>
            </div>
        </div>
    </div>
</fieldset>


<script>
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


</script>
