<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');


/*Payment generation*/
Route::get('genpayment', function () {
    $payments = App\paymentHistory::where('month', date("m"))
                                  ->where('year', date("Y"))
                                  ->first();
    
    if ($payments)
    {
        $info = array('status' => 'payment already generated for this month');
        return response()->json([$info]);
    }
    
    else
    {
        $taxis = App\Taxi::all();
        foreach ($taxis as $taxi) {
            App\paymentHistory::create([
                'taxi_id' => $taxi->id,
                'month' => date("m"),
                'year' => date("Y"),
                'desc' => "Monthly Taxi Fee",
            ]);
            
        }
        
        return redirect()->route('payment')->with('success','Payment Recived Successfully.');
    }    

    
});

/*
|--------------------------------------------------------------------------
|Configure Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'configure'], function () {
    /*Company Configure Routes*/
    Route::get('company', 'CompanyController@index');
    Route::post('company', 'CompanyController@add');
    Route::get('company/view', 'CompanyController@view');
    Route::post('company/update', 'CompanyController@update');
    Route::post('company/delete', 'CompanyController@delete');
    /*End of Company Configure Routes*/

    /*Taxi Center Configure Routes*/
    Route::get('taxi-center', 'TaxicenterController@index');
    Route::post('taxi-center', 'TaxicenterController@add');
    Route::get('taxi-center/view/v2/', 'TaxiCenterController@view');
    Route::post('taxi-center/delete', 'TaxiCenterController@delete');
    /*End of Taxi Center Configure Routes*/

    /*Call Code Configure Routes*/
    Route::get('call-code', 'CallCodeController@index');
    Route::post('call-code', 'CallCodeController@add');
    Route::get('call-code/view', 'CallCodeController@view');
    Route::post('call-code/delete', 'CallCodeController@delete');
    /*End of Call Code Configure Routes*/

    /*Taxi Configure Routes*/
    Route::get('taxi', 'TaxiController@index');
    Route::post('taxi', 'TaxiController@add');
    Route::get('taxi/view', 'TaxiController@view');
    Route::post('taxi/delete', 'TaxiController@delete');
    /*End of Taxi Configure Routes*/

    /*Driver Configure Routes*/
    Route::get('driver', 'DriverController@index');
    Route::post('driver', 'DriverController@add');
    Route::get('driver/view', 'DriverController@view');
    Route::post('driver/delete', 'DriverController@delete');
    /*End of Driver Configure Routes*/
});



/*
|--------------------------------------------------------------------------
|Payment Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'payments'], function () {
    Route::get('taxi-payment', 'PaymentHistoryController@index')->name('payment');
    Route::post('taxi-payment', 'PaymentHistoryController@add');
    Route::get('taxi-payment/view', 'PaymentHistoryController@view');
});