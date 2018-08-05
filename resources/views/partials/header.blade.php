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
                        $user_roles = Auth::user()->getRoleNames()->toArray(); 
                        $user_role = $user_roles[0];
                        // echo $user_role;
                    ?>
                </a>
                <ul class="dropdown-menu">
                    @foreach ($taxi_centers as $center)
                        @if ($user_role == $center->cCode)
                        <li><a target="_blank" href="/display/{{ $center->cCode }}/three">Display {{ $center->name }}</a></li>    
                        @endif
                        @if ($user_role == 'super-admin' OR $user_role == 'admin' OR $user_role == 'officer')
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