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
        $data = $this->getBalance();
        $balance = json_decode($data);
        return view('sms.index', compact('balance'));
    }

    public function send(Request $request)
    {
        $message = $request->input('message');
        $phoneNumbers = $request->input('phoneNumber');
        
        $from = $request->input('senderId');
        $phoneNumber = '60'.$phoneNumbers;

        // try {
        //     $this->sendMessage($phoneNumber, $message, $from);
        //     return redirect('sms')->with('alert-success', 'SMS successfully send');

        // } catch ( \Exception  $e ) {
        //     return redirect('sms')->with('alert-danger', $e->getMessage());
        // }

        $code = $this->sendMessage($phoneNumber, $message, $from);

        // return redirect('sms')->with('alert-success', 'SMS successfully send');

        if ($code == '200') {
            return redirect('sms')->with('alert-success', 'Success - Message has been sent successfully.');
        }
        if ($code == '422') {
            return redirect('sms')->with('alert-danger', 'Required fields are missing.');
        }
        if ($code == '400') {
            return redirect('sms')->with('alert-danger', 'Bad Request - Invalid sender_id.');
        }
        if ($code == '401') {
            return redirect('sms')->with('alert-danger', 'Unauthorized - Invalid authorization key.');
        }
        if ($code == '403') {
            return redirect('sms')->with('alert-danger', 'Forbidden - Authorization header is missing.');
        }

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

    private function sendMessage($phoneNumber, $message, $sender_id)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://rest.msgowl.com/messages");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "body={$message}&sender_id={$sender_id}&recipients={$phoneNumber}");
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

    private function getBalance()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://rest.msgowl.com/balance");
        $header = array(
            'Authorization: AccessKey 82df3162fa9d0d9b0721163'
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $server_output = curl_exec($ch);
        curl_close ($ch);

        return $server_output;
    }
}
