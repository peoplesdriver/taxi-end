@extends('layouts.app')

@section('content')
<ul class="breadcrumb">
    <li><a href="{{ url('home') }}">Home</a></li>
    <li class="active">SMS</li>
    
</ul>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Send SMS</h3>
            </div>
            <div class="panel-body">
                <div class="row">   
                    <div class="col-md-12">
                        <div class="flash-message">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))

                                <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                                
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-12">
                        <hr>
                    </div>
                    <div class="col-md-12">
                        <form class="form-horizontal" method="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <fieldset>
                                <!-- <div class="form-group">
                                    <label class="col-md-4 control-label" for="senderId">Sender Id</label>
                                    <div class="col-md-4">
                                        <input id="senderId" value="Taviyani" maxlength="11" pattern="^(?=.*[a-zA-Z])(?=.*[a-zA-Z0-9])([a-zA-Z0-9 ]{1,11})$" name="senderId" type="text" placeholder="Taviyani" class="form-control input-md" required="" title="Cannot Be Loner than 11 letter. Only letters and numbers allowed">
                                        <span class="help-block">Enter a sender Id here. It must be a combination of letters and numbers, It cannot be more than 11 characters.</span>
                                    </div>
                                </div> -->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="senderId">Select Sender id from list:</label>
                                    <div class="col-md-4">
                                        <select class="form-control" id="senderId" name="senderId" required>
                                            <option value="+12017780998" selected>Taviyani</option>
                                            <option value="TDS">TDS</option>
                                            <option value="JR Taxi">JR Taxi</option>
                                            <option value="City Cab">City Cab</option>
                                            <option disabled="disabled">---------------------------------------</option>
                                            <option value="MyRide">MyRide</option>
                                            <option value="My Ride">My Ride</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="phoneNumber">Phone Number</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-addon">+960</span>
                                            <input id="phoneNumber" min="7" name="phoneNumber" type="tel" required>
                                        </div>
                                        <p class="help-block">Enter the number here, Must be a valid dhiraagu or ooredoo number.</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="message">Message</label>
                                    <div class="col-md-6">
                                        <textarea style="font-size: 15px" class="form-control" rows="4" id="message" name="message" required></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-11" style="text-align: center;">
                                        <ul id="sms-counter" style="list-style: none;">
                                            <li style="display: inline-block;"><b>Length:</b> <span class="length"></span></li>
                                            <li style="display: inline-block;"><b>Messages:</b> <span class="messages"></span></li>
                                            <li style="display: inline-block;"><b>Per Message:</b> <span class="per_message"></span></li>
                                            <li style="display: inline-block;"><b>Remaining:</b> <span class="remaining"></span></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="submit"></label>
                                    <div class="col-md-8">
                                        <button type="submit" class="btn btn-success">Submit</button>
                                        <button type="reset" class="btn btn-danger">Reset</button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                        <hr>
                        <div class="row">
                            <label class="col-md-4" for=""></label>
                            <div class="col-md-8">
                                <button class="btn btn-secondary" onclick="autofillMessage('Your theory test is {{ date('d/m/Y') }} @ 11:00 am. Please collect your slip from the office before going. For more information call 7672002')">Theory</button>
                                <button class="btn btn-secondary" onclick="autofillMessage('Your driving test is on {{ date('d F Y') }} / 4:00pm @ Wamco Area. For more info call 7672002 or your instructor - Taviyani Driving School')">Driving</button>
                                <button class="btn btn-secondary" onclick="autofillMessage('Your license card is ready, Complete any pending payments and collect the License from the office. For more information call 7672002 - Taviyani Driving School')">License</button>
                            </div>
                        </div>
                    </div>
                </div>    
            </div>        
        </div> 
    </div>
</div>

@endsection

@section('js')
    <script src="/js/sms_counter.min.js"></script>
    <script>
        $('#message').countSms('#sms-counter')

        function autofillMessage(message) {
            $('#message').text('');
            $('#message').text(message);
        }
    </script>
@endsection