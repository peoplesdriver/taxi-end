<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Taxi;

class TaxiOwnerExport implements FromView
{
    public function view(): View
    {
        $taxis = \App\Taxi::where('active', '1')->where('taxiOwnerMobile', '!=', '-')->pluck('taxiOwnerMobile')->toArray();
        $taxi_numbers = Helper::validate_numbers($taxis);

        return view('exports.taxiOwner', [
            'taxi_numbers' => $taxi_numbers
        ]);
    }
}