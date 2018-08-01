<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

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
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Taviyani Admin Portal</title>

    <!-- Styles -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Kaushan+Script|Lato|Open+Sans" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs-3.3.7/jszip-3.1.3/pdfmake-0.1.27/dt-1.10.15/b-1.3.1/b-colvis-1.3.1/b-html5-1.3.1/b-print-1.3.1/r-2.1.1/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css"/>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #EEEEEE;
        }
    </style>    
    @yield('css')
</head>
<body>
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container-narrow">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/home') }}">
                    <img src="{{asset('Taviyani_Logo.png')}}" width="100" height="60" style="margin-top:-17px;">
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    @if (Auth::guest())
                        <li class="disabled"><a href="#">Not Logged In</a></li>
                    @else
                        <li><a href="{{ url('/home') }}">Dashboard</a></li>
                        @role('super-admin|admin|officer')
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                Payments <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ url('payments/taxi-payment') }}">Taxi Payments</a></li>
                                <li><a href="https://invoice.taviyani.xyz/">Other Payments</a></li> 
                            </ul>
                        </li>
                        @endrole
                        <!-- <li><a href="#">Taxi Log</a></li> -->
                        @role('super-admin|admin|officer')
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                Message Center <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ url('sms') }}">Single SMS</a></li>
                                <li><a href="{{ url('sms/group') }}">Group SMS</a></li>
                                <li><a href="{{ url('groups') }}">Create Sms Groups</a></li>
                            </ul>
                        </li>
                        @endrole
                        @role('super-admin|admin')
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                Report <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ url('report/driver') }}">Driver Report</a></li>
                                <li><a href="{{ url('report/taxi') }}">Taxi Report</a></li>
                                <li><a href="{{ url('report/payment-history') }}">Payment History Report</a></li>
                                <li><a href="{{ url('report/payment-history-unpaid') }}">Payment History Report (Unpaid)</a></li>
                                <li><a href="{{ url('report/driving-school') }}">Driving School History Report</a></li>
                            </ul>
                        </li>
                        @endrole
                        @role('super-admin|admin')
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                Master Data <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ url('configure/company') }}">Configure Company</a></li>
                                <li><a href="{{ url('configure/taxi-center') }}">Configure Taxi Centers</a></li>
                                <li><a href="{{ url('configure/call-code') }}">Configure Call Codes</a></li>
                                <li><a href="{{ url('configure/taxi') }}">Configure Taxis</a></li>
                                <li><a href="{{ url('configure/driver') }}">Configure Drivers</a></li>
                                {{--  <hr>
                                <li><a href="{{ url('test-taxi') }}">Taxis with no drivers</a></li>
                                <li><a href="{{ url('test-driver') }}">Drivers with no taxis</a></li>  --}}
                            </ul>
                        </li>
                        @endrole
                        @role('super-admin|admin|officer')
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                Driving School <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ url('driving-school') }}">All Driving School Students</a></li>
                                <li><a href="{{ url('driving-school/create') }}">Register a new user to the Driving School</a></li>
                            </ul>
                        </li>
                        @endrole
                        @role('super-admin|admin')
                        <li class="dropdown" disabled>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                Manage Users <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li disabled><a href="{{ url('users/all') }}">View All</a></li>
                                <li disabled><a href="{{ url('users/add') }}">Add New</a></li>
                            </ul>
                        </li>
                        @endrole
                        @role('super-admin|admin|officer|JRMM|CBMM')
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                Displays <span class="caret"></span>
                                <?php 
                                    $user_role = Auth::user()->getRoleNames()->toArray(); 
                                    echo $user_role[0];
                                ?>
                            </a>
                            <ul class="dropdown-menu">
                                @foreach ($taxi_centers as $center)
                                    @if ($user_role == $center->cCode OR $user_role == 'super-admin' OR $user_role == 'admin' OR $user_role == 'officer')
                                        <li><a target="_blank" href="/display/{{ $center->cCode }}">Display {{ $center->name }}</a></li>    
                                        <li><a target="_blank" href="/display/{{ $center->cCode }}/three">Display {{ $center->name }} (three months)</a></li>    
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                        @endrole
                        @role('super-admin|admin|officer|customer')
                        <li class="dropdown" disabled>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                Theory <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ url('theory/all') }}">Theory (All Questions)</a></li>
                                <li><a href="{{ url('theory') }}">Theory Test</a></li>
                            </ul>
                        </li>
                        @endrole
                    @endif
                </ul>
                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right" style="margin-right: 10px;">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ route('login') }}">Login</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            Username:    {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="container">
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-92445511-3', 'auto');
        ga('send', 'pageview');
    </script>
    
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs-3.3.7/jszip-3.1.3/pdfmake-0.1.27/dt-1.10.15/b-1.3.1/b-colvis-1.3.1/b-html5-1.3.1/b-print-1.3.1/r-2.1.1/datatables.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>
    @yield('js')
</body>
</html>
