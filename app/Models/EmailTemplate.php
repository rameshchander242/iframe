<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class EmailTemplate extends Authenticatable
{
    use Notifiable;

    protected $table = 'email_templates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subject', 'body', 'sms_message', 'iframe_id', 'status'
    ];


    public function iframe() {
        return $this->belongsTo(Iframe::class);
    }
}
