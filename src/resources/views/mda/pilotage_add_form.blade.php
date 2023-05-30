@extends('layouts.default')

@section('title')
Add pilotage
@endsection

@section('header-style')
    <!--Load custom style link or css-->
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <!-- Table Start -->
                <div class="card-body">
                    <h4 class="card-title">Bill Preparation</h4>
                    <hr>
                    @if(Session::has('message'))
                        <div class="alert {{Session::get('m-class') ? Session::get('m-class') : 'alert-danger'}} show"
                             role="alert">
                            {{ Session::get('message') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <form id="bill-preparation-form" method="post" action="{{ route('pilotage.index') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="booking_mst_id" class="required">Booking No<span class="required"></span></label>
                                    <select class="custom-select form-control" required id="booking_mst_id" name="booking_mst_id"></select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="shipping_agent">Shipping Agent<span class="required"></span></label>
                                    <input type="text" class="form-control" id="shipping_agent" name="shipping_agent" disabled />
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="ship_name">Ship Name<span class="required"></span></label>
                                    <input type="text" class="form-control" id="ship_name" name="ship_name" disabled />
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="delivery_area">Delivery Area<span class="required"></span></label>
                                    <input type="text" class="form-control" id="delivery_area" name="delivery_area" disabled />
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="booking_date">Booking Date<span class="required"></span></label>
                                    <input type="text" class="form-control" id="booking_date" name="booking_date" disabled />
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="confirm_date">Proposed Supply Date<span class="required"></span></label>
                                    <input type="text" class="form-control" id="confirm_date" name="confirm_date" disabled />
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="req_supply_date">Demand Supply Date<span class="required"></span></label>
                                    <input type="text" class="form-control" id="req_supply_date" name="req_supply_date" disabled />
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="receive_on">Receive On<span class="required"></span></label>
                                    <input type="text" class="form-control" id="receive_on" name="receive_on" readonly />
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="total_qty">Total Qty (TON)<span class="required"></span></label>
                                    <input type="text" class="form-control" id="total_qty" name="total_qty" disabled />
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="invoice_no" class="required">Invoice No<span class="required"></span></label>
                                    <input required type="text" class="form-control" id="invoice_no" name="invoice_no" placeholder="Invoice No" />
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="invoice_date" class="required">Invoice Date<span class="required"></span></label>
                                    <input type="text" autocomplete="off" class="form-control datetimepicker-input" data-toggle="datetimepicker" id="invoice_date" data-target="#invoice_date" name="invoice_date" placeholder="YYYY-MM-DD" required data-predefined-date="" />
                                    <small class="text-muted form-text"> </small>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="remarks">Remarks<span class="required"></span></label>
                                    <textarea name="remarks" class="form-control" id="remarks" rows="2"></textarea>
                                </div>
                            </div>
                            <input type="hidden" class="form-control" id="delivery_area_id" name="delivery_area_id" />
                            <div class="col-md-12" id="booking_details_rows_form"></div>
                        </div>
                        <div class="row mt-1">
                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary mb-1" id="">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Table End -->
            </div>
            @include('mda.bill_preparation_list')
        </div>
    </div>

@endsection

@section('footer-script')
    <script type="text/javascript">
        function populateRelatedFields(that, data)
        {
            $(that).parent().parent().parent().find('#shipping_agent').val(data.booking.agency_name);
            $(that).parent().parent().parent().find('#ship_name').val(data.booking.vessel_name);
            $(that).parent().parent().parent().find('#delivery_area').val(data.booking.delivery_area);
            $(that).parent().parent().parent().find('#booking_date').val(data.booking.booking_date);
            $(that).parent().parent().parent().find('#confirm_date').val(data.booking.confirm_date);
            $(that).parent().parent().parent().find('#req_supply_date').val(data.booking.req_supply_date);
            $(that).parent().parent().parent().find('#receive_on').val(data.booking.receive_on);
            $(that).parent().parent().parent().find('#total_qty').val(data.booking.total_qty);
            $(that).parent().parent().parent().find('#delivery_area_id').val(data.booking.delivery_area_id);
            $('#booking_details_rows_form').html(data.bookingDetailsRowHtml);
        }

        function billPreparationList()
        {
            $('#bill-preparation-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: APP_URL + '/',
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', "name": 'DT_RowIndex'},
                    {data: 'invoice_no'},
                    {data: 'booking_no'},
                    {data: 'medium'},
                    {data: 'agency_name'},
                    {data: 'invoice_date'},
                    {data: 'invoice_bill_amt'},
                    {data: 'invoice_status'}
                ]
            });
        }
        $(document).ready(function () {
            selectBookings('#booking_mst_id', APP_URL+'/water/ajax/ack-bookings', APP_URL+'/water/ajax/ack-booking/', populateRelatedFields);
            datePicker('#invoice_date');
            billPreparationList();
        });
    </script>
@endsection
