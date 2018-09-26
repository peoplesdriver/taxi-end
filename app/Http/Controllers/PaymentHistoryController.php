<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\paymentHistory;
use App\Taxi;
use App\Driver;
use App\CallCode;
use App\TaxiCenter;
use Illuminate\Support\Facades\Auth;

// use Twilio\Rest\Client;

class PaymentHistoryController extends Controller
{
    // public function __construct(Client $client)
    // {
    //     $this->middleware('auth');
    //     $this->client = $client;
    // }

    public function index()
    {
        $payments = paymentHistory::all();
        return view('payment.index', compact('payments'));
    }

    public function view(Request $request)
    {
        //if($request->ajax()){
            $id = $request->id;
            $info = paymentHistory::find($id);
            $info2 = Taxi::find($info->taxi_id);
            $info3 = Driver::find($info2->driver->taxi_id);
            $info3 = CallCode::find($info2->callcode_id);
            $info->center = TaxiCenter::find($info3->center_id);
            $info->taxi = $info2;
            $info->driver = $info3;
            $info->callcode = $info3;
            //echo json_decode($info);
            return response()->json($info);
        //}
    }

    public function add(Request $request)
    {
        $payment = paymentHistory::find($request->idPayment);
        $payment->qty = '1';
        $payment->total = $request->total;
        $payment->subtotal = $request->subtotal;
        $payment->totalAmount = $request->total + $request->subtotal;
        $payment->user_id = Auth::user()->id;
        $payment->paymentStatus = '1';
        $payment->save();

        // Check if all payments are made OR make the taxi state 0 
        $checkP = paymentHistory::where('taxi_id', $payment->taxi_id)->where('paymentStatus', '0')->get();
        if ($checkP->count() >= 1) {
            $taxi = Taxi::find($payment->taxi_id);
            $taxi->state = '0';
            $taxi->save();    
        } else {
            $taxi = Taxi::find($payment->taxi_id);
            $taxi->state = '1';
            $taxi->save(); 
        }

        //Send SMS
        $phone_number_owner = '9607774713';
        $message = $request->smsText;

        $this->sendMessage($phone_number_owner, $message);

        if ($request->send_sms == "1") {
            $code = $this->sendMessage($taxi->driver->driverMobile, $message);

            if ($code == '200') {
                return back()->with('alert-success', 'Payment Recived Successfully - Message Success');
            }
            if ($code == '422') {
                return back()->with('alert-danger', 'Payment Recived Successfully - Message failed - Required fields are missing.');
            }
            if ($code == '400') {
                return back()->with('alert-danger', 'Payment Recived Successfully - Message failed - Bad Request - Invalid sender_id.');
            }
            if ($code == '401') {
                return back()->with('alert-danger', 'Payment Recived Successfully - Message failed - Unauthorized - Invalid authorization key.');
            }
            if ($code == '403') {
                return back()->with('alert-danger', 'Payment Recived Successfully - Message failed - Forbidden - Authorization header is missing.');
            }
        } else {
            return back()->with('success','Payment Recived Successfully.');
        }            
        // return back()->with('success','Payment Recived Successfully.');

    }

    // private function sendMessageTwilio($phoneNumber, $message)
    // {
    //     $twilioPhoneNumber = config('services.twilio')['phoneNumber'];
    //     $messageParams = array(
    //         'from' => 'Taviyani',
    //         'body' => $message
    //     );

    //     $this->client->messages->create(
    //         $phoneNumber,
    //         $messageParams
    //     );
    // }

    private function sendMessage($phoneNumber, $message)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://rest.msgowl.com/messages");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "body={$message}&sender_id=Taviyani&recipients={$phoneNumber}");
        $header = array(
            'Authorization: AccessKey 82df3162fa9d0d9b0721163'
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close ($ch);
        return $code;
    }
}
