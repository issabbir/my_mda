<option value="">Select One</option>
    @if(isset($get_workshop_services))
            @foreach($get_workshop_services as $get_workshop_service)
                <option value="{{$get_workshop_service->service_id}}"
                >{{$get_workshop_service->service_name}}</option>
            @endforeach
    @endif
