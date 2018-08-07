<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\Helper;
use App\paymentHistory;
use App\CallCode;
use App\Taxi;
use Carbon\Carbon;

class PaymentGeneration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Taxi Payments for the month';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $now = Carbon::now();
        // dd($now);
        $next_month = $now->addMonth();
        $day = $now->day;
        $taxis = App\Taxi::has('driver')->where('taxiNo', '!=', '-')->where('active', '1')->get();

        foreach ($taxis as $key => $taxi) {
            if (!is_null($taxi->driver)) {
                if ($taxi->driver->driverName == '-') {
                    $taxis->pull($key);
                }
            } else {
                $taxis->pull($key);
            }
        }

        if ($day < 25) {
            if (checkPaymentGeneration($now->month, $now->year)) {
                return $this->info('Payment Already generated for this month.');
            } else {
                echo 'Generating payment for this month. <br>';
                $now = Carbon::now();
                foreach ($taxis as $taxi) {
                    generatePayment($taxi->id, $now->month, $now->year);
                }
                $taxiUp = App\Taxi::where('state', '1')->update(['state' => 0]);
                
                return $this->info('Generated payment for this month');
            }

            return $this->info('Before 25th');
        } elseif ($day == 25 or $day > 25) {
            $now = Carbon::now();
            if (checkPaymentGeneration($now->month, $now->year)) {
                if (checkPaymentGeneration($next_month->month, $next_month->year)) {
                    return $this->info('Payment generated for this and the next month.');
                } else {
                    $this->info('Payment already generated for this month, generating payment for next month.');
                    foreach ($taxis as $taxi) {
                        generatePayment($taxi->id, $next_month->month, $next_month->year);
                    }
                    $taxiUp = App\Taxi::where('state', '1')->update(['state' => 0]);

                    return $this->info('Generated payment for the next month');
                }
            } else {
                $this->info('Generating payment for this month.');
                $now = Carbon::now();
                // dd($now);
                // dd($next_month);
                foreach ($taxis as $taxi) {
                    generatePayment($taxi->id, $now->month, $now->year);
                }
                $taxiUp = App\Taxi::where('state', '1')->update(['state' => 0]);
                
                return $this->info('Generated payment for this month');
            }
            return $this->info('25 or later');
        }
    }

    public function checkPaymentGeneration($month, $year)
    {
        $payments = paymentHistory::where('month', $month)->where('year', $year)->first();
        if ($payments) {
            return true;
        } else {
            return false;
        }
    }

    public function generatePayment($id, $month, $year)
    {
        App\paymentHistory::create([
            'taxi_id' => $id,
            'month' => $month,
            'year' => $year,
            'desc' => "Monthly Taxi Fee",
        ]);
        return true;
    }
}
