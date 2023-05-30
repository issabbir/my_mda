
<label class="input-required">Product<span class="required"></span></label>
<select name="product_id" id="product_id_{{$val->id}}" class="form-control select2 " style="width: 100%">
    <option value="">Select one</option>
</select>
@if ($errors->has('product_id'))
    <span class="help-block">{{ $errors->first('product_id') }}</span>
@endif


@section('footer-script')
    @parent
    <script>
        $(document).ready(function () {
            $('#product_id_{{$val->id}}').select2({
                // minimumInputLength: 1,
                ajax: {
                    url: '{{route('mwe.ajax.search-product')}}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            search_param: params.term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (obj) {
                                return {
                                    id: obj.id,
                                    text: obj.name,
                                };
                            })
                        };
                    },
                    cache: false
                },
            });
        });
    </script>

@endsection
