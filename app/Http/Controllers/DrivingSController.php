<?php

namespace App\Http\Controllers;

use App\DrivingS;
use App\Instructors;
use Illuminate\Http\Request;

// use Twilio\Rest\Client;

class DrivingSController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = DrivingS::orderBy('created_at', 'desc')->get();
        return view('drivingschool.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $instructors = Instructors::all();
        return view('drivingschool.add', compact('instructors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $student = DrivingS::create([
            'name' => request('name'),
            'id_card' => request('id_card'),
            'phone' => request('phone'),
            'category' => request('category'),
            'c_address' => request('c_address'),
            'p_address' => request('p_address'),
            'instructor' => request('instructor'),
            'rate' => request('rate'),
            'remarks' => request('remarks'),
            'finisheddate' => request('finisheddate'),
            'theorydate' => request('theorydate'),
            'user_id' => auth()->id(),
        ]);

        $student->month = $student->created_at->format('m'); 
        $student->year = $student->created_at->format('Y');
        $student->save();
        
        $message = "Welcome ". $student->name .", to Taviyani Driving School. You will be receving further updatest through sms";
        $phoneNumbers = $student->phone;
        $phoneNumber = '960'.$phoneNumbers;

        $code = $this->sendMessage($phoneNumber, $message);

        if ($code == '200') {
            return redirect('driving-school')->with('alert-success', 'Successfully Registered a new Student');
        }
        if ($code == '422') {
            return redirect('driving-school')->with('alert-danger', 'Student added but - Message failed - Required fields are missing.');
        }
        if ($code == '400') {
            return redirect('driving-school')->with('alert-danger', 'Student added but - Message failed - Bad Request - Invalid sender_id.');
        }
        if ($code == '401') {
            return redirect('driving-school')->with('alert-danger', 'Student added but - Message failed - Unauthorized - Invalid authorization key.');
        }
        if ($code == '403') {
            return redirect('driving-school')->with('alert-danger', 'Student added but - Message failed - Forbidden - Authorization header is missing.');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DrivingS  $drivingS
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $payment = DrivingS::findOrFail($id);
        return view('receipt.drivingschool',compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DrivingS  $drivingS
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $student = DrivingS::findorfail($id);
        $instructors = Instructors::all();
        return view('drivingschool.edit', compact('student', 'instructors'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DrivingS  $drivingS
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DrivingS $drivingS)
    {
        $drivingS->name = $request->name;
        $drivingS->id_card = $request->id_card;
        $drivingS->phone = $request->phone;
        $drivingS->c_address = $request->c_address;
        $drivingS->p_address = $request->p_address;
        $drivingS->instructor = $request->instructor;
        $drivingS->rate = $request->rate;
        $drivingS->finisheddate = $request->finisheddate;
        $drivingS->theorydate = $request->theorydate;
        $drivingS->save();
        return redirect('/driving-school')->with('alert-success','Successfully edited the Student');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DrivingS  $drivingS
     * @return \Illuminate\Http\Response
     */
    public function destroy(DrivingS $drivingS)
    {
        $drivingS->delete();
        return redirect('/driving-school')->with('alert-success','Successfully deleted the Student');
    }

    private function sendMessage($phoneNumber, $message)
    {
        $from = "TDS";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://rest.msgowl.com/messages");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "body={$message}&sender_id={$from}&recipients={$phoneNumber}");
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
