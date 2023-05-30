<!--start head-->
<table>
    <tr>
        <td style="width: 25%"></td>
        <td style="width: 50%; text-align: center"> <h1>CHITTAGONG PORT AUTHORITY</h1>  </td>
        <td style="width: 25%"></td>
    </tr>
    <tr>
        <td style="width: 25%"></td>
        <td style="width: 50%; text-align: center"><h3>Bandar Bhaban, Post Box-2013, Chittagong-4100</h3></td>
        <td style="width: 25%"></td>
    </tr>
    <tr style="">
        <td style="width: 25%"></td>
        <td style="width: 50%; text-align: center"><h2>Pilotage Invoice</h2></td>
        <td style="width: 25%"></td>
    </tr>
    <tr>
        <td style="width: 25%"></td>
        <td style="width: 50%; text-align: center"><h5>Invoice Date: {{date("d-M-Y")}}</h5></td>
        <td style="width: 25%"></td>
    </tr>
</table>
<!--end head-->

<br />

<!--start body-->
<table style=" font-family: arial, sans-serif; border-collapse: collapse; width: 100%; padding-top: 5px; margin-top: 10px; color: #475F7B">
    <tr style="border: 1px solid #dddddd; text-align: left; padding: 8px;">
        <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Description</th>
        <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Fees</th>
        <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Subtotal</th>
    </tr>

    <tr style="border: 1px solid #dddddd; text-align: left; padding: 8px;">
        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">
            <!--start description-->
        @foreach($data["PILOTAGE"] as $pilotage)
                <h4 style="margin-bottom: 5px;"> {{ $pilotage["LABEL"] }} : {{ $pilotage["VALUE"] }}</h4>
            @endforeach
        <!--end description-->

            <!--start tags-->
            <br />
            <h4>TUG INFORMATION:</h4>
            <table style=" font-family: arial, sans-serif; border-collapse: collapse; width: 100%; padding-top: 5px; margin-top: 10px">
                <tr style="border: 1px solid #dddddd; text-align: left; padding: 8px;">
                    <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">
                        <h5>{{ $data["TUGS"][0][0]["LABEL"] }}</h5>
                    </th>
                    <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">
                        <h5>{{ $data["TUGS"][0][1]["LABEL"] }}</h5>
                    </th>
                    <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">
                        <h5>{{ $data["TUGS"][0][2]["LABEL"] }}</h5>
                    </th>
                    <<th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">
                        <h5>{{ $data["TUGS"][0][3]["LABEL"] }}</h5>
                    </th>
                    <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">
                        <h5>{{ $data["TUGS"][0][4]["LABEL"] }}</h5>
                    </th>
                </tr>

                @foreach($data["TUGS"] as $tug)
                    <tr style="border: 1px solid #dddddd; text-align: left; padding: 8px;">
                        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;" >
                            {{ $tug[0]["VALUE"] }}
                        </td>
                        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;" >
                            {{ $tug[1]["VALUE"] }}
                        </td>
                        <td  >
                            {{ $tug[2]["VALUE"] }}
                        </td>
                        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;" >
                            @if($tug[3]["VALUE"] == 'Y')
                                YES
                            @elseif($tug[3]["VALUE"] == 'N')
                                NO
                            @else
                                {{$tug[3]["VALUE"] }}
                            @endif
                        </td>
                        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">
                            {{ $tug[4]["VALUE"] }}
                        </td>
                    </tr>
                @endforeach
            </table>
            <!--end tags-->

        </td>
        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">
            <!--start fee-->
            <table class="table table-bordered">
                <tr>
                    <td > {{ $data["FEES"][0]["LABEL"] }}</td>
                </tr>
                <tr>
                    <td > {{ $data["FEES"][1]["LABEL"] }}</td>
                </tr>
                <tr>
                    <td  > {{ $data["FEES"][2]["LABEL"] }}</td>
                </tr>
                <tr>
                    <td  > {{ $data["FEES"][3]["LABEL"] }}</td>
                </tr>
                <tr>
                    <td  > {{ $data["FEES"][5]["LABEL"] }}</td>
                </tr>
                <tr>
                    <td  > {{ $data["FEES"][6]["LABEL"] }}</td>
                </tr>
            </table>
        </td>
        <!--end fee-->

        <!--start amount-->
        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">

            <table class="table table-bordered">
                <tr>
                    <td > {{ $data["FEES"][0]["VALUE"] }}</td>
                </tr>
                <tr>
                    <td  > {{ $data["FEES"][1]["VALUE"] }}</td>
                </tr>
                <tr>
                    <td  > {{ $data["FEES"][2]["VALUE"] }}</td>
                </tr>
                <tr>
                    <td  > {{ $data["FEES"][3]["VALUE"] }}</td>
                </tr>
                <tr>
                    <td  > {{ $data["FEES"][5]["VALUE"] }}</td>
                </tr>
                <tr>
                    <td  > {{ $data["FEES"][6]["VALUE"] }}</td>
                </tr>
            </table>
        </td>
        <!--end amount-->

    </tr>
    <!--start total-->
    <tr style="border: 1px solid #dddddd; text-align: left; padding: 8px;">
        <td colspan="2" style="border: 1px solid #dddddd; text-align: left; padding: 8px; text-align: right; font-weight: bold">Total </td>
        <td style="text-align: left; padding: 8px; font-weight: bold">{{ $data["FEES"][4]["VALUE"] }}</td>
    </tr>
    <!--end total-->

</table>
<!--end body-->
