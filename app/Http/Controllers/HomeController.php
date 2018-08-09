<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DrivingS;
use App\FlashMessage;
use App\paymentHistory;
use Carbon\Carbon;
use App\Taxi;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $flashmessage = Flashmessage::find(1);

        $students = \App\DrivingS::latest()->take(5)->get();
        $payments = \App\paymentHistory::where('paymentStatus', '1')->orderBy('updated_at', 'DESC')->take(15)->get();

        $now = Carbon::now();

        // $taxiPayments = \App\paymentHistory::getTotalEstPrice('7', '2018');
        // return $taxiPayments;

        if ($request->taxiNo) {
            $taxi = Taxi::where('taxiNo', $request->taxiNo)->where('active', '1')->first();
            if ($taxi) {
                $quick_payments = $taxi->payment;
            } else {
                $quick_payments = [];
            }
        } else {
            $quick_payments = [];
        }
    
        // return $quick_payments;
        // return $taxi;
        return view('home', compact('flashmessage', 'students', 'payments', 'quick_payments'));
    }

    public function FlashMessagePost()
    {
        $flashmessage = Flashmessage::find(1);
        $flashmessage->message = $request->message;
        $flashmessage->save();
        return redirect()->back();
    }
}
