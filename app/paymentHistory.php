<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class paymentHistory extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['taxi_id', 'month', 'year', 'qty', 'total', 'subtotal', 'gstAmount', 'totalAmount', 'slipNo', 'desc', 'user_id', 'paymentStatus'];

    public function taxi()
    {
        return $this->belongsTo('App\Taxi', 'taxi_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function scopeGetTotalPrice($query, $month, $year) {
        return $query->where('month', $month)->where('year', $year)->sum('totalAmount');
    }

    public function scopeGetTotalEstPrice($query, $month, $year) {
        $q = $query->where('month', $month)->where('year', $year)->count();
        return $q === null ? 0 : ($q * 600);
    }
    
}
