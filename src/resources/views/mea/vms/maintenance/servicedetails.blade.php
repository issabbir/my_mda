
    <table id="tbl_service_taken" role="table" aria-busy="false" aria-colcount="7"
           class="table b-table table-hover table-bordered">
        <thead role="rowgroup" class="">
        <tr role="row">
            <th role="columnheader" scope="col" aria-colindex="1" class="required">Service/ Repair</th>
            <th role="columnheader" scope="col" aria-colindex="2" class="">Service/ Repair Date</th>
            {{--<th role="columnheader" scope="col" aria-colindex="3" class="">Service Cost</th>--}}
            <th role="columnheader" scope="col" aria-colindex="4" class="">Comments</th>
            <th role="columnheader" scope="col" aria-colindex="7" class="">Action</th>
        </tr>
        </thead>
        <tbody role="rowgroup" id="serviceForm">
{{--{!! $data['insertedCommitteeData'][0]->emp_name !!}--}}
        </tbody>
    </table>
    <button type="button" class="btn mr-2 btn-secondary btn-sm" id="addServiceForm">Add</button>


{{--
<div class="d-none">
    <select class="custom-select select2" name="service-template" id="service-template">
           @if($data['get_workshop_service'])
                @foreach($data['get_workshop_service'] as $option)
                    {!!$option!!}
                @endforeach
            @endif
    </select>
</div>
--}}


