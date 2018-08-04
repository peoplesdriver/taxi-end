<?php
    function getMonthName($monthNumber)
    {
        return date("F", mktime(0, 0, 0, $monthNumber, 1));
    }
?>

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    Welcome To Taviyani Admin Portal
                </div>
                <div class="panel-body" style="font-size: 15px;">
                    <p>You are logged in <strong>{{ Auth::user()->name }}</strong>!. Your Role is {{ Auth::user()->getRoleNames() }}</p>
                    <p><div id="todaysDate"></div></p>
                    <p>-Taviyani-</p>
                    <hr>
                    @role('super-admin|admin')
                    <form action="{{ url('/flash-message') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="message">Flash Message:</label>
                            <input type="text" class="form-control" id="message" name="message"
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
@role('super-admin|admin')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    Quick Pay
                </div>
                <div class="panel-body">
                    <div class="">
                        <form method="GET">
                            <label>Taxi No:</label>
                            <input type="text" name="taxiNo"
                            @if (request()->taxiNo)
                                value="{{ request()->taxiNo }}"
                            @endif
                            >
                        </form>
                    </div>
                    <br>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif
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
                                    <td>{{ $payment->taxi->rate }}</td>
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
                    Earnings Summary
                </div>
                <div class="panel-body" style="font-size: 15px;">
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

                    <canvas id="myChart" width="400" height="400"></canvas>

                </div>
            </div>
        </div>
    </div>
</div>
@endrole
@endsection

<?php

$dataPoints = array();
for ($i = 1; $i < 13; $i++) {
    array_push($dataPoints, array(
        "label" => Carbon\Carbon::createFromFormat('m', $i)->format('F'),
        "price" => \App\paymentHistory::getTotalPrice($i, '2018'),
    ));
}
// dd(json_encode($dataPoints));
?>

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
        var jsonfile = {
            "jsonarray": <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
        }
        var labels = jsonfile.jsonarray.map(function(e) {
            return e.label;
        });
        var data = jsonfile.jsonarray.map(function(e) {
            return e.price;
        });
        console.log(jsonfile);
        var ctx = document.getElementById("myChart").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Earnings',
                    data: data,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                    ]
                }]
            }
        });
    </script>
    
@endsection