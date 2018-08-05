<?php
    function getMonthName($monthNumber)
    {
        return date("F", mktime(0, 0, 0, $monthNumber, 1));
    }
?>

@extends('layouts.app-home')

@section('content')
<div class="container">
    <div class="row">
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    Welcome To Taviyani Admin Portal
                </div>
                <div class="panel-body" style="font-size: 15px;">
                    @if (Request::is('/'))
                        your home
                    @endif
                    <p>You are logged in <strong>{{ Auth::user()->name }}</strong>!. Your Role is {{ Auth::user()->getRoleNames() }}</p>
                    <p><div id="todaysDate"></div></p>
                    <p>-Taviyani-</p>
                    <hr>
                    @role('super-admin|admin')
                    <form action="{{ url('/flash-message') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="message">Flash Message:</label>
                            <input type="text" class="form-control dhivehi-font dhivehi-rtl" id="message" name="message"
                            @if ($flashmessage)
                                value="{{ $flashmessage->message }}"
                            @endif
                            >
                        </div>
                        <button type="submit" class="btn btn-default btn-success">Save</button>
                    </form>
                    @endrole
                </div>
            </div>
        </div>
    </div>
</div>
@role('super-admin|admin|officer')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        Quick Pay
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form class="form-inline" method="GET" style="width: 100%" autocomplete="off">
                                    <div class="form-group">
                                        <input type="text" class="typeahead form-control mb-2 mr-sm-2" id="taxiNo" name="taxiNo" placeholder="Enter taxi no" autocomplete="off" data-provide="typeahead"
                                            @if (request()->taxiNo)
                                            value="{{ request()->taxiNo }}"
                                        @endif
                                        >
                                        <button type="submit" class="btn btn-primary mb-2">Search</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <br>
                        <table id="payments" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Call Code</th>
                                    <th>Driver Name</th>
                                    <th>Taxi Number</th>
                                    <th>Center Name</th>
                                    <th>Slip Number</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Recive Payment</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($quick_payments as $payment)
                                    <tr>   
                                        <td>{{ $payment->taxi->callcode->callCode }}</td>
                                        @if (is_null($payment->taxi->driver))
                                            <td>No Driver Assigned</td>
                                        @else
                                            <td>{{ $payment->taxi->driver->driverName  }}</td>
                                        @endif
                                        <td>{{ $payment->taxi->taxiNo }}</td>
                                        <td>{{ $payment->taxi->callcode->taxicenter->name }}</td>
                                        <td>TPL/{{ $payment->year }}/{{ $payment->month }}/{{ $payment->id }}</td>
                                        <td>{{ getMonthName($payment->month) . ' ' . $payment->year }}</td>
                                        <td>
                                            @if ($payment->paymentStatus == "0")
                                                <button id="status" style="display: block; margin: auto;"  class="btn-danger" disabled>Not Paid</button>
                                            @endif
                                            @if ($payment->paymentStatus == "1")
                                                <button id="status" style="display: block; margin: auto;"  class="btn-success" disabled>Paid</button>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($payment->paymentStatus == "0")
                                                <button style="display: block; margin: auto;" class="btn btn-info" data-toggle="modal" data-target="#paymentModal" onclick="c_payment('{{ $payment->id }}')">Recive Payment</button>
                                            @endif
                                            @if ($payment->paymentStatus == "1")
                                                <a href="{{ url()->current() }}/receipt/{{ $payment->id }}" style="display: block; margin: auto;" class="btn btn-info">View</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endrole
@role('super-admin|admin')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        Last Joined Student
                    </div>
                    <div class="panel-body" style="font-size: 15px;">
                        <table class="table table-bordered">
                            <thead>
                                <tr>                            
                                    <th>Name</th>
                                    <th>ID Card</th>
                                    <th>Phone</th>
                                    <th>Category</th>
                                    <th>Instructor</th>
                                    <th>Remarks</th>
                                    <th>Driving Test</th>
                                    <th>Theory Test</th>
                                    <th>Joined on</th>
                                    <th>Registered By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($students as $student)
                                    <tr>
                                        <td class="verticalAlign">{{ $student->name }}</th>
                                        <td class="verticalAlign">{{ $student->id_card }}</td>
                                        <td class="verticalAlign">{{ $student->phone }}</td>
                                        <td class="verticalAlign">{{ $student->category }}</td>
                                        <td class="verticalAlign">{{ $student->instructor }}</td>
                                        <td class="verticalAlign">{{ $student->remarks }}</td>
                                        <td class="verticalAlign">{{ $student->finisheddate }}</td>
                                        <td class="verticalAlign">{{ $student->theorydate }}</td>
                                        <td class="verticalAlign">{{ $student->created_at->toFormattedDateString() }}</td>
                                        <td class="verticalAlign">{{ $student->user->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        Last paid taxi fee
                    </div>
                    <div class="panel-body" style="font-size: 15px;">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Center Name - Call Code ( Taxi No )</th>
                                    <th>Slip Number</th>
                                    <th>Taxi Fee</th>
                                    <th>Driver Name</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Paid Date and Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments as $payment)
                                    <tr>   
                                        <td>{{ $payment->taxi->callcode->taxicenter->name }} - {{ $payment->taxi->callcode->callCode }} ( {{ $payment->taxi->taxiNo }} )</td>
                                        <td>TPL/{{ $payment->updated_at->format("Y") }}/{{ $payment->updated_at->format("m") }}/{{ $payment->id }}</td>
                                        <td>{{ $payment->totalAmount }}</td>
                                        <td>{{ $payment->taxi->driver->driverName  }}</td>
                                        <td>{{ getMonthName($payment->month) . ' ' . $payment->year }}</td>
                                        <td>
                                            @if ($payment->paymentStatus == "0")
                                                <button id="status" style="display: block; margin: auto;"  class="btn-danger" disabled>Not Paid</button>
                                            @endif
                                            @if ($payment->paymentStatus == "1")
                                                <button id="status" style="display: block; margin: auto;"  class="btn-success" disabled>Paid</button>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $payment->updated_at->format('d/m/Y h:i:s a') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title pull-left">
                            Earnings Summary
                        </div>
                        <div class="panel-title pull-right">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="1">
                                <label class="form-check-label" for="inlineCheckbox1">Show Table</label>
                            </div>    
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body" style="font-size: 15px;">
                        <div id="hideMe" style="display: none;">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Taxi Fee</th>
                                        <th>Driving School</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($i = 1; $i < 13; $i++)
                                        <tr>
                                            <td>{{ Carbon\Carbon::createFromFormat('m', $i)->format('F') }}</td>
                                            <td>
                                                <?php
                                                    echo "MVR " . \App\paymentHistory::getTotalPrice($i, '2018');
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    echo "MVR " . \App\DrivingS::getTotalPrice($i, '2018');
                                                ?>
                                            </td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                            <hr>
                        </div>

                        <canvas id="myChart"></canvas>
                        <hr>
                        <canvas id="line"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endrole

<input type="hidden" name="hidden_view" id="hidden_view" value="{{ url('payments/taxi-payment/view') }}">

<div class="modal fade" id="paymentModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Recive Payment</h4>
            </div>
            <div class="modal-body" style="font-size: 15px;">
            <p>Taxi Number: <span id="view_taxiNo" class="text-success"></span></p>
            <p>Call Code: <span id="view_callCode" class="text-success"></span></p>
            <p>Center Name: <span id="view_centerName" class="text-success"></span></p>
            <p>Driver Name: <span id="view_driverName" class="text-success"></span></p>
            <p>Recipt Number: <span id="view_reciptNo" class="text-success"></span></p>
            <form action="/payments/taxi-payment" method="POST" role="form">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="total">Total Amount</label>
                    <input type="number" name="total" class="form-control" id="view_total" placeholder="Enter Amount">
                </div>
                
                <div class="form-group">
                    <label for="total">Fine on Late Payment</label>
                    <input type="number" name="subtotal" class="form-control" id="view_subtotal" placeholder="Enter Amount">
                </div>
                
                <input type="hidden" name="idPayment" id="idPayment" class="form-control" value="">
                <div class="form-group">
                    <div class="radio">
                        <label>
                            <input type="radio" onclick="javascript:yesnoCheck();" name="send_sms" id="noCheck" value="0" checked>
                            Dont Send Sms
                        </label>
                        <label>
                            <input type="radio" onclick="javascript:yesnoCheck();" name="send_sms" id="yesCheck" value="1">
                            Send Sms
                        </label>
                    </div>    
                </div>
                
                <div id="ifYes" style="display: none">
                    <div class="form-group">
                        <label for="total">Driver Number</label>
                        <input type="text" name="driverNumber" id="view_driverNumber" class="form-control" placeholder="Enter Driver Phone Number">
                    </div>
                   
                    <div class="form-group">
                        <label for="total">SMS Text</label>
                        <textarea name="smsText" class="form-control" maxlength="180" id="smsText">A Payment of MVR 600 on 22/22/2222 was recieved for 12/2017</textarea>
                        <script>
                            $('#smsText').keyup(function () {
                            var max = 180;
                            var len = $(this).val().length;
                            if (len >= max) {
                                $('#charNum').text(' you have reached the limit');
                            } else {
                                var char = max - len;
                                $('#charNum').text(char + ' characters left');
                            }
                            });
                        </script>
                        <div id="charNum"></div>
                    </div>
                </div>
                <script>
                    function yesnoCheck() {
                        if (document.getElementById('yesCheck').checked) {
                            document.getElementById('ifYes').style.display = 'block';
                        }
                        else document.getElementById('ifYes').style.display = 'none';

                    }
                </script>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
    <script>
        function addZero(i) {
            if (i < 10) {
                i = "0" + i;
            }
            return i;
        }

        function updateDate()
        {
            var str = "";

            var days = new Array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
            var months = new Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

            var now = new Date();

            str += "Today is: <strong>" + days[now.getDay()] + ", " + now.getDate() + " " + months[now.getMonth()] + " " + now.getFullYear() + " " + addZero(now.getHours()) +":" + addZero(now.getMinutes()) + ":" + addZero(now.getSeconds()) + '</strong>';
            document.getElementById("todaysDate").innerHTML = str;
        }

        setInterval(updateDate, 1000);
        updateDate();
    </script>
    <script>
        var totalValue = 0;

        function c_payment(id) {
            var view_url = $("#hidden_view").val();
            $.ajax({
                url: view_url,
                type:"GET", 
                data: {"id":id}, 
                success: function(result){
                    $("#idPayment").val(result.id);
                    $("#view_taxiNo").text(result.taxi.taxiNo);
                    $("#view_callCode").text(result.callcode.callCode);
                    $("#view_centerName").text(result.center.name);
                    $("#view_driverName").text(result.taxi.driver.driverName);
                    $("#view_total").val(result.taxi.rate);
                    $("#view_subtotal").val(result.subtotal);
                    $("#view_driverNumber").val(result.taxi.driver.driverMobile);

                    var z = 0;
                    var x = $("#view_total").val();
                    var y = $("#view_subtotal").val();
                    var z = x + y;
                    $("#totalAmount").val(z);
                    $("#totalAmountA").text(z);

                    var today = new Date();
                    var dd = today.getDate();
                    var mm = today.getMonth() + 1; //January is 0!

                    var yyyy = today.getFullYear();
                    if(dd<10) {
                        dd='0'+dd;
                    } 
                    if(mm<10) {
                        mm='0'+mm;
                    } 
                    var today = dd+'/'+mm+'/'+yyyy;
                    
                    var paymentMonth = result.month;
                    var paymentYear = result.year;

                    var smsGeText = "A Payment of MVR 600 on "+ today +" was received for "+ paymentMonth + "/" + paymentYear + '. Taxi number: T-'+ result.taxi.taxiNo;
                    $('#smsText').html(smsGeText);
                }
            });
        }
    </script>

    <script>
    
    var taxiNos = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        prefetch: '/api/v2/taxiNo/all'
    });

    console.log(taxiNos);

    $(document).ready(function(){
        $('.typeahead').typeahead(null, {
            source: taxiNos,
            minLength: 1
        });
    });

    </script>
    <?php
        $taxiData = array();
        $drivingData = array();
        for ($i = 1; $i < 13; $i++) {
            $month = Carbon\Carbon::createFromFormat('m', $i)->format('F');
            array_push($taxiData, array(
                "label" => $month,
                "price" => \App\paymentHistory::getTotalPrice($i, '2018'),
                "est" => \App\paymentHistory::getTotalEstPrice($i, '2018'),
                "color" => 'rgba(255, 71, 87,0.2)'
            ));
            array_push($drivingData, array(
                "label" => $month,
                "price" => \App\DrivingS::getTotalPrice($i, '2018'),
                "color" => 'rgba(112, 161, 255,0.2)'
            ));
        }

    ?>

    <script>
        var jsonfile = {
            "taxiData": {!! json_encode($taxiData, JSON_NUMERIC_CHECK) !!},
            "drivingData": {!! json_encode($drivingData, JSON_NUMERIC_CHECK) !!}
        }
        var labels_taxi = jsonfile.taxiData.map(function(e) {
            return e.label;
        });
        var data_taxi = jsonfile.taxiData.map(function(e) {
            return e.price;
        });
        var data_taxi_est = jsonfile.taxiData.map(function(e) {
            return e.est;
        });
        var data_driving = jsonfile.drivingData.map(function(e) {
            return e.price;
        });
        var data_color_taxi = jsonfile.taxiData.map(function(e) {
            return e.color;
        });
        var data_color_driving = jsonfile.drivingData.map(function(e) {
            return e.color;
        });
        console.log(jsonfile);
        var ctx = document.getElementById("myChart").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            responsive: true,
            data: {
                labels: labels_taxi,
                datasets: [{
                    label: 'Taxi Fees',
                    data: data_taxi,
                    backgroundColor: data_color_taxi,
                }, {
                    label: 'Driving School',
                    data: data_driving,
                    backgroundColor: data_color_driving,
                }]
            },
            options: {
                title: {
                    display: true,
                    text: 'Earnigs report for 2018'
                }
            }
        });

        var line = document.getElementById("line").getContext('2d');
        var lineChart = new Chart(line, {
            type: 'line',
            responsive: true,
            data: {
                labels: labels_taxi,
                datasets: [{
                    label: 'Taxi Fees Recived',
                    data: data_taxi,
                    borderColor: data_color_taxi,
                    fill: false
                }, {
                    label: 'Estimated Earnings',
                    data: data_taxi_est,
                    borderColor: data_color_driving,
                    fill: false
                }]
            },
            options: {
                title: {
                    display: true,
                    text: 'Estimate vs Real earnings (Taxi fee) for 2018'
                }
            }
        })

        $(function () {
            $("#inlineCheckbox1").click(function () {
                if ($(this).is(":checked")) {
                    $("#hideMe").show();
                } else {
                    $("#hideMe").hide();
                }
            });
        });
    </script>
@endsection

@section('css')

<style>
    .form-inline .twitter-typeahead {
        width: auto;
        float: none;
        vertical-align: middle;
    }
</style>

@endsection