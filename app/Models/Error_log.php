<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Error_log extends Authenticatable
{
    use Notifiable;

    protected $table = 'error_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        's_no', 'iframe_id', 'client_name', 'location', 'browser', 'date_time', 'description', 'step'
    ];

    

    public function iframe() {
        return $this->belongsto(Iframe::class);
    }
    

}
