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
        'email_type', 'category_id', 'brand_id', 'series_id', 'item_id', 'subject', 'body', 'sms_message', 'iframe_id', 'status', 'user_id', 'email_default'
    ];


    public function iframe() {
        return $this->belongsTo(Iframe::class);
    }


    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    public function series() {
        return $this->belongsTo(Series::class);
    }
}
