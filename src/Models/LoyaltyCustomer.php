<?php

namespace KN\WalletCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class LoyaltyCustomer extends Model
{

    use Notifiable;
    protected $fillable = [
        'name',
        'email',
        'qr_code',
        'wallet_type',
        'loyalty_points',
    ];

    public function appleDevices()
    {
        return $this->hasMany(AppleDevices::class);
    }

    public function PointsHistory(){
        return $this->hasMany(PointsHistory::class);
    }

    // creater uuid for qr_code field on creation
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->qr_code)) {
                $model->qr_code = uuid_create(UUID_TYPE_RANDOM);
            }
        });

    }

}
