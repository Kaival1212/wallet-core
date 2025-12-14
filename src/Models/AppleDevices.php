<?php

namespace KN\WalletCore\Models;

use Illuminate\Database\Eloquent\Model;

class AppleDevices extends Model
{
    protected $fillable = [
        'loyalty_customer_id',
        'device_id',
        'pass_type_id',
        'serial_number',
        'push_token',
    ];

    public function loyaltyCustomer()
    {
        return $this->belongsTo(LoyaltyCustomer::class);
    }
}
