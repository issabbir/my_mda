@extends('layouts.default')

@section('title')
    Pilotage invoice
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
                        <div class="row">
                            <div class="col-md-12">
                                <div id="print">
                                    <div class="row">
                                        <div class="col-md-2"></div>
                                        <div class="col-md-8">
                                            <h4 style="text-align: center">CHITTAGONG PORT AUTHORITY</h4>
                                        </div>
                                        <div class="col-md-2">
                                            {{--<p>@php echo date("d-M-Y"); @endphp</p>--}}
                                        </div>
                                    </div>
                                    <div style="text-align: center">
                                        <h5>Bandar Bhaban, Post Box-2013, Chittagong-4100</h5>
                                        <h4>Pilotage Invoice</h4>
                                        <h6>Invoice Date: @php echo date("d-M-Y"); @endphp</h6>
                                    </div>
                                    <div class="row" style="border: 1px solid gray; margin-top: 100px; margin-bottom: 100px;">
                                        <div class="col-md-12">
                                            <div class="row" style="border-bottom: 1px solid gray;">
                                                <div class="col-md-8" >
                                                    <h5 style="text-align: center;">Description</h5>
                                                </div>
                                                <div class="col-md-2" style="border-left:1px solid gray ">
                                                    <h5 style="text-align: center;">Fees</h5>
                                                </div>
                                                <div class="col-md-2" style="border-left:1px solid gray ">
                                                    <h5 class="float-right">Subtotal</h5>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-8" >
                                                    @foreach($data["PILOTAGE"] as $pilotage)
                                                    <h6 style="margin-bottom: 5px;"> {{ $pilotage["LABEL"] }} : {{ $pilotage["VALUE"] }}</h6>
                                                    @endforeach

                                                    <hr>
                                                    <table class="table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th><h6>{{ $data["TUGS"][0][0]["LABEL"] }}</h6></th>
                                                                <th><h6>{{ $data["TUGS"][0][1]["LABEL"] }}</h6></th>
                                                                <th><h6>{{ $data["TUGS"][0][2]["LABEL"] }}</h6></th>
                                                                <th><h6>{{ $data["TUGS"][0][3]["LABEL"] }}</h6></th>
                                                                <th><h6>{{ $data["TUGS"][0][4]["LABEL"] }}</h6></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($data["TUGS"] as $tug)
                                                            <tr>
                                                                <td style="padding: 3px;">
                                                                    {{ $tug[0]["VALUE"] }}
                                                                </td>
                                                                <td style="padding: 3px;" >
                                                                    {{ $tug[1]["VALUE"] }}
                                                                </td>
                                                                <td style="padding: 3px;" >
                                                                    {{ $tug[2]["VALUE"] }}
                                                                </td>
                                                                <td style="padding: 3px;" >
                                                                    {{ $tug[3]["VALUE"] }}
                                                                </td>
                                                                <td style="padding: 3px;" >
                                                                    {{ $tug[4]["VALUE"] }}
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-md-2" style="border-left:1px solid gray ">
                                                    <h6 style="margin-bottom: 50px;"> {{ $data["FEES"][0]["LABEL"] }}</h6>
                                                    <h6 style="margin-bottom: 50px;"> {{ $data["FEES"][1]["LABEL"] }}</h6>
                                                    <h6 style="margin-bottom: 50px;"> {{ $data["FEES"][2]["LABEL"] }}</h6>
                                                    <h6 style="margin-bottom: 50px;"> {{ $data["FEES"][3]["LABEL"] }}</h6>
                                                    <h6 style="margin-bottom: 50px;"> {{ $data["FEES"][5]["LABEL"] }}</h6>
                                                    <h6 style="margin-bottom: 50px;"> {{ $data["FEES"][6]["LABEL"] }}</h6>
                                                </div>
                                                <div class="col-md-2" style="border-left:1px solid gray; text-align: right; ">
                                                    <h6 style="margin-bottom: 50px;"> {{ $data["FEES"][0]["VALUE"] }}</h6>
                                                    <h6 style="margin-bottom: 70px;"> {{ $data["FEES"][1]["VALUE"] }}</h6>
                                                    <h6 style="margin-bottom: 70px;"> {{ $data["FEES"][2]["VALUE"] }}</h6>
                                                    <h6 style="margin-bottom: 70px;"> {{ $data["FEES"][3]["VALUE"] }}</h6>
                                                    <h6 style="margin-bottom: 50px;"> {{ $data["FEES"][5]["VALUE"] }}</h6>
                                                    <h6 style="margin-bottom: 50px;"> {{ $data["FEES"][6]["VALUE"] }}</h6>
                                                </div>
                                            </div>
                                            <div class="row" style="border-top: 1px solid gray;">
                                                <div class="col-md-8"></div>
                                                <div class="col-md-2" style=" border-left: 1px solid gray;"><h6 style="margin-top: 10px;">Total</h6></div>
                                                <div class="col-md-2" style="text-align: right;border-left: 1px solid gray;">
                                                    <h6 style="margin-top: 10px;"> {{ $data["FEES"][4]["VALUE"] }}</h6>
                                                    {{--<div class="" style="border-bottom: 1px solid black; width: 100%; margin-bottom: 5px;"></div>--}}
                                                    {{--<div class="" style="border-bottom: 1px solid black; width: 100%;  margin-bottom: 5px;"></div>--}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12" style="text-align: right">
                                        <a href="{{ route("invoice-pilotages") }}" class="btn btn-primary">Back</a>
                                        <a href="{{ route("invoice-pilotages-download", $traceId ) }}" target="_blank" class="btn btn-primary"><i class="bx bx-printer"></i></a>
                                    </div>
                                </div>
                            </div>
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
        $(".printInvoice").on("click", function () {
            var divToPrint=document.getElementById("print");

            newWin= window.open();
            newWin.document.write(divToPrint.outerHTML);
            newWin.print();
            newWin.close();
        });
    </script>

@endsection
