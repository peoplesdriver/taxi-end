<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Twilio\Rest\Client;

class SmsController extends Controller
{
    // public function __construct(Client $client)
    // {
    //     $this->middleware('auth');
    //     $this->client = $client;
    // }

    public function index()
    {
        return view('sms.index');
    }

    public function send(Request $request)
    {
        $message = $request->input('message');
        $phoneNumbers = $request->input('phoneNumber');
        
        $from = $request->input('senderId');
        
        // Testing Numbers
        // (Pass Validation)
        // $from = '+15005550006';
        // (Invalid Number)
        // $from = '+15005550001';
        // (Not available for the account)
        // $from = '+15005550007';
        //dd($from);

        $phoneNumber = '960'.$phoneNumbers;

        // try {
        //     $this->sendMessage($phoneNumber, $message, $from);
        //     return redirect('sms')->with('alert-success', 'SMS successfully send');

        // } catch ( \Exception  $e ) {
        //     return redirect('sms')->with('alert-danger', $e->getMessage());
        // }

        $sendMessage = $this->sendMessage($phoneNumber, $message, $from);
        
        return $sendMessage;

    }

    // private function sendMessage($phoneNumber, $message, $from)
    // {
    //     $twilioPhoneNumber = config('services.twilio')['phoneNumber'];
    //     $messageParams = array(
    //         'from' => $from,
    //         'body' => $message
    //     );

    //     $this->client->messages->create(
    //         $phoneNumber,
    //         $messageParams
    //     );
    // }

    // public function call()
    // {
    //     return view('call');
    // }

    private function sendMessage($phoneNumber, $message, $from)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://rest.msgowl.com/messages");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(
            array([
                'body' => $message,
                'sender_id' => $from,
                'recipients' => $phoneNumber
            ])
        ));
        $header = array(
            'Authorization: AccessKey 82df3162fa9d0d9b0721163'
        );

        // ----------------------------------------------------------------
        // pass header variable in curl method
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close ($ch);

        return $server_output;
    }
}
