<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Location extends Authenticatable
{
    use Notifiable;

    protected $table = 'locations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'store_name', 'address_1', 'address_2', 'city', 'phone', 'email', 'additional_email', 'ctm_code', 'map_url', 'description', 'hours', 'price_sheet', 'status'
    ];

    protected $casts = ['hours'=>'array'];


    public function user() {
        return $this->belongsTo(User::class);
    }
}
