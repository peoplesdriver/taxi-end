<?php

namespace App\Http\Controllers;

use App\Contact;
use App\GroupSms;
use App\GroupSmsStatus;
use App\Jobs\SendGroupSms;
use Illuminate\Http\Request;
use Twilio\Rest\Client;

class GroupSmsController extends Controller
{
    public function __construct(Client $client)
    {
        $this->middleware('auth');
        $this->client = $client;
    }

    public function index()
    {
        $contacts = Contact::all();   
        return view('sms.group.index', compact('contacts'));
    }

    public function store(Request $request)
    {
        $senderId = $request->senderId;
        $contact = Contact::find($request->contact_id);
        $message = $request->message;
        $numbers = $contact->numbers->pluck('number')->toArray();

        // dd($contact, $numbers);

        $groupSms = new GroupSms;
        $groupSms->senderId = $senderId;
        $groupSms->message = $message;
        $groupSms->save();

        foreach ($numbers as $number) {
            $status = new GroupSmsStatus;
            $status->groupsms_id = $groupSms->id;
            $status->phone_number = $number;
            $status->save();
        }

        $groupSmsStatuses = GroupSmsStatus::where('groupsms_id', $groupSms->id)->get();

        // dd($groupSmsStatuses);

        foreach ($groupSmsStatuses as $groupSmsStatus) {
            try {
                $this->sendMessage($groupSmsStatus->number, $message, $senderId);
                $groupSmsStatus->status = "Message sent";
            } catch ( \Twilio\Exceptions\RestException  $e ) {
                $groupSmsStatus->status = $e;
            }
        }

        $url = "sms/group/status/".$groupSms->id;

        return redirect($url);
    }

    public function status($id)
    {
        $groupSms = GroupSms::with('numbers')->where('id', $id)->first();
        return view('sms.group.status', compact('groupSms'));
    }

    private function sendMessage($phoneNumber, $message, $from)
    {
        $twilioPhoneNumber = config('services.twilio')['phoneNumber'];
        $messageParams = array(
            'from' => $from,
            'body' => $message
        );

        $this->client->messages->create(
            $phoneNumber,
            $messageParams
        );
    }
}
