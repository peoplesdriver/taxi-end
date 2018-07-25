<?php

use Illuminate\Http\Request;
use App\TaxiCenter;
use App\CallCode;
use App\Taxi;
use App\Driver;
use App\Company;
use App\news;
use App\paymentHistory;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'v2'], function () {
    Route::group(['prefix' => 'configure'], function () {
        Route::get('company/view', function (Request $request) {
            $id = $request->id;
            $info = Company::find($id);
            //echo json_decode($info);
            return response()->json($info);
        });
        Route::get('taxi-center/view', function (Request $request) {
            $id = $request->id;
            $info = TaxiCenter::find($id);
            $info2 = Company::find($info->company_id);
            $info->company = $info2;
            //echo json_decode($info);
            return response()->json($info);
        });
        Route::get('call-code/view', function (Request $request) {
            $id = $request->id;
            $info = CallCode::find($id);
            $info2 = TaxiCenter::find($info->center_id);
            $info->center = $info2;
            //echo json_decode($info);
            return response()->json($info);
        });
        Route::get('taxi/view', function (Request $request) {
            $id = $request->id;
            $info = Taxi::find($id);
            $info2 = CallCode::find($info->callcode_id);
            $info3 = TaxiCenter::find($info2->center_id);
            $info4 = Company::find($info3->company_id);
            $info->callcode = $info2;
            $info->texicenter = $info3;
            $info->company = $info4;
            //echo json_decode($info);
            return response()->json($info);
        });
    });

    Route::group(['prefix' => 'display'], function ($id) {
        Route::get('/taxis/{center_name}', function($center_name) {
            $taxis = \App\Taxi::where('active', '1')
                    ->where('center_name', $center_name)
                    ->where('taxiNo', '!=', '-')
                    ->with('callcode')
                    ->with('driver')
                    ->orderBy('cc')
                    ->get();

            function checkThreeMonths($id) {
                $now = Carbon::now();
                // Current Month
                $day = $now->format('d');
                $month = $now->format('m');
                $year = $now->format('Y');
                // Last 3 Month
                $month_3 = Carbon::now()->subMonth(3)->format('m');
                $year_3 = Carbon::now()->subMonth(3)->format('Y');
                // Next Month
                $next_month = Carbon::now()->addMonth(1)->format('m');
                $next_year = Carbon::now()->addMonth(1)->format('Y');

                // dd($month, $year, $month_3, $year_3, $next_month, $next_year);

                if ($day < 25) {
                    $payment_history = paymentHistory::where('taxi_id', $id)->where('month', '>', $month_3)->where('year', '=', $year_3)->where('paymentStatus', 0)->get();
                    // before payment generation
                    if ($payment_history->isEmpty()) {
                        return false;
                    } else {
                        return true;
                    }
                }
                
                if ($day >= 25) {
                    // assume payment generated (probably)
                    return false;
                }
            }
                    
            foreach ($taxis as $key => $taxi) {
                if (!is_null($taxi->driver)) {
                    if ($taxi->driver->driverName == '-'){
                        $taxis->pull($key);
                    }
                } else {
                    $taxis->pull($key);
                }
                if (checkThreeMonths($taxi->id)) {
                    $taxis->pull($key);
                }
            }

            $new_taxis = [];
            
            function getColor($state, $feeDate, $roadDate, $insDate, $permDate) {
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

            foreach ($taxis as $taxi) {
                array_push($new_taxis, [
                    'id' => $taxi->id,
                    'callCode' => $taxi->callcode->callCode,
                    'taxiNo' => $taxi->taxiNo,
                    'phoneNumber' => ($taxi->driver ? $taxi->driver->driverMobile : 'No Number'),
                    'color' => getColor($taxi->state, $taxi->anualFeeExpiry, $taxi->roadWorthinessExpiry, $taxi->insuranceExpiry, $taxi->driver->driverPermitExp)
                ]);
            }

            return $new_taxis;
        });

        Route::get('play/taxi/{id}', function($id) {
            $now = Carbon::now();
            // Current Month
            $day = $now->format('d');
            $month = $now->format('m');
            $year = $now->format('Y');
            // Last 3 Month
            $month_3 = Carbon::now()->subMonth(3)->format('m');
            $year_3 = Carbon::now()->subMonth(3)->format('Y');
            // Next Month
            $next_month = Carbon::now()->addMonth(1)->format('m');
            $next_year = Carbon::now()->addMonth(1)->format('Y');

            // dd($month, $year, $month_3, $year_3, $next_month, $next_year);

            if ($day < 25) {
                $payment_history = paymentHistory::where('taxi_id', $id)
                                                ->where('month', '>', $month_3)
                                                ->where('year', '=', $year_3)
                                                ->where('paymentStatus', 0)
                                                ->get();
                                                
                // before payment generation
                if (count($payment_history) < 3) {
                    return $payment_history;
                } else {
                    return $payment_history;
                }
            }
            
            if ($day >= 25) {
                // assume payment generated (probably)
                $payment_history = paymentHistory::where('taxi_id', $id)
                                                ->where('month', '>', $month_3)
                                                ->where('month', '<', $next_month)
                                                ->where('year', '=', $next_year)
                                                ->where('paymentStatus', 0)
                                                ->get();
                                                
                if (count($payment_history) < 3) {
                    return $payment_history;
                } else {
                    return $payment_history;
                }
            }
        });

        Route::get('/taxi/three-month/{id}', function($id) {
            $now = Carbon::now();
            // Current Month
            $month = Carbon::now()->format('m');
            $year = Carbon::now()->format('Y');
            // Last 3 Month
            $month_3 = Carbon::now()->subMonth(3)->format('m');
            $year_3 = Carbon::now()->subMonth(3)->format('Y');
            // Next Month
            $next_month = Carbon::now()->addMonth(1)->format('m');
            $next_year = Carbon::now()->addMonth(1)->format('Y');

            // dd($month, $year, $month_3, $year_3, $next_month, $next_year);

            $payment_history = paymentHistory::where('taxi_id', $id)->where('month', '>', $month_3)->where('year', '=', $year_3)->where('paymentStatus', 0)->get();

            if ($month < 25) {
                // before payment generation
                if ($payment_history->isEmpty()) {
                    return 'false';
                } else {
                    return 'true';
                }
            }
            
            if ($month >= 25) {
                // payment generated (probably)
                return 'true';
            }
        });

        Route::get('/driver/{id}', function($id) {
            return Driver::where('id', $id)->with('taxi')->first();
        });
    });

    Route::group(['prefix' => 'verifications'], function () {
        Route::get('/', function () {
            //
        });
    });

    Route::group(['prefix' => 'news'], function () {
        Route::get('/', function () {
            return news::orderBy('created_at', 'desc')->take('3')->get();
        });

        Route::post('/add', function (Request $request) {
            $news = new news;
            $news->heading = $request->heading;
            $news->desc = $request->desc;
            $news->detail = $request->detail;
            $news->save();

            return $news;
        });
    });
});          