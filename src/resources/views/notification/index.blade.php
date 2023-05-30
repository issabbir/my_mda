@extends('layouts.default')

@section('title')
    Notification
@endsection

@section('header-style')
    <!--Load custom style link or css-->

@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 activity-card">
            <div class="card widget-notification">
                <div class="card-header border-bottom">
                    <h4 class="card-title d-flex align-items-center">Notification</h4>
                </div>
                <div class="card-content">
                    <div class="card-body mt-2">
                        @if($data)
                            @foreach($data as $key=>$d)
                                    <div class="alert border-warning alert-dismissible mb-2 mt-2" role="alert">
                                        <div class="d-flex align-items-center">
                                            <i class="bx bxs-calendar text-warning font-medium-5"></i>
                                            <span class="text-muted">{{$d->web_message }}</span>
                                        </div>
                                    </div>
                            @endforeach
                        @else
                            <div class="alert border-danger alert-dismissible mb-2" role="alert">
                                <div class="d-flex align-items-center">
                                    <span class="text-muted ">No new notification found.</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('footer-script')
    <!--Load custom script-->


@endsection
