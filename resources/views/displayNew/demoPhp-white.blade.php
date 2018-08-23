<?php
    function checkColor($state, $feeDate, $roadDate, $insDate, $permDate) {
        $paid = ($state == '1') ? true : false;
        $feeExpired = (strtotime($feeDate) > time()) ? true : false;
        $roadExpired = (strtotime($roadDate) > time()) ? true : false;
        $insuranceExpired = (strtotime($insDate) > time()) ? true : false;
        $permitExpired = (strtotime($permDate) > time()) ? true : false;
        // dd($paid, $feeDate, $roadDate, $insDate, $feeExpired, $roadExpired, $insuranceExpired);
        if(!$paid) {
            return 'red';
        }
        if($paid AND !$feeExpired OR !$roadExpired OR !$insuranceExpired OR !$permitExpired) {
            return 'purple';
        } 
        elseif($paid AND $feeExpired AND $roadExpired AND $insuranceExpired OR !$permitExpired) {
            return 'green';
        }

    }
    function checkStatus($status, $color) {
        if($status == NULL) {
            return true;
        }
        if($status == 'paid') {
            return ($color == 'green' OR $color == 'purple') ? true : false;
        }
        if($status == 'unpaid') {
            return ($color == 'red') ? true : false;
        }
        if($status == 'expired') {
            return ($color == 'purple') ? true : false;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="refresh" content="30; URL={{ url()->current() }}">

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="57x57" href="{{asset('Taviyani_Logo.png')}}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{asset('Taviyani_Logo.png')}}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{asset('Taviyani_Logo.png')}}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{asset('Taviyani_Logo.png')}}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{asset('Taviyani_Logo.png')}}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{asset('Taviyani_Logo.png')}}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{asset('Taviyani_Logo.png')}}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{asset('Taviyani_Logo.png')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('Taviyani_Logo.png')}}">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{asset('Taviyani_Logo.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('Taviyani_Logo.png')}}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{asset('Taviyani_Logo.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('Taviyani_Logo.png')}}">

    <title>{{ $title }}</title>

    <!-- Styles and Scripts -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/display-white.css">
    <link rel="stylesheet" href="/css/dhivehi.css">
</head>
<body>
    <div id="app">
        <div class="row no-gutters">
            @foreach ($taxis as $taxi)
                <?php $color = checkColor($taxi->state, $taxi->anualFeeExpiry, $taxi->roadWorthinessExpiry, $taxi->insuranceExpiry, $taxi->driver->driverPermitExp) ?>
                @if (checkStatus(request()->status, $color))
                    <div class="col-lg-1 col-md-2 col-sm-6 col-xs-6">
                        <div class="box {{ $color }}">
                            <div class="callCode circle" style="color: black;">
                                {{ $taxi->callcode->callCode }}
                            </div>
                            <div class="taxiNo">
                                {{ $taxi->taxiNo }}
                            </div>
                            <div class="phoneNumber">
                                @if ($taxi->driver)
                                    {{ $taxi->driver->driverMobile }}
                                @else
                                    No Number
                                @endif
                            </div>
                        </div>
                    </div>    
                @endif  
            @endforeach
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="/js/display.js"></script>
    <script src="//cdn.jsdelivr.net/npm/jquery.marquee@1.5.0/jquery.marquee.min.js" type="text/javascript"></script>
    <script>
        function checkDate(date) {
            var selectedDate = new Date(date);
            var now = new Date();
            now.setHours(0,0,0,0);
            if (selectedDate < now) {
                console.log("Selected date is in the past");                
                return false;
            } else {
                console.log("Selected date is NOT in the past");
                return true;
            }
        }

        function formatDate(date) {
            var d = new Date(date);
            var monthNames = [
                "January", "February", "March",
                "April", "May", "June", "July",
                "August", "September", "October",
                "November", "December"
            ];

            var day = d.getDate();
            var monthIndex = d.getMonth();
            var year = d.getFullYear();

            return day + ' ' + monthNames[monthIndex] + ' ' + year;
        }
    </script>

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

            str +=  addZero(now.getDay()) +"/"+ addZero(now.getMonth() + 1) +"/"+ now.getFullYear() + " - " + addZero(now.getHours()) +":" + addZero(now.getMinutes()) + ":" + addZero(now.getSeconds());
            document.getElementById("todaysDate").innerHTML = str;
        }

        // setInterval(updateDate, 1000);
        updateDate();
    </script>
</body>
</html>