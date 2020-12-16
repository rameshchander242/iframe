<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Widget_Quote_Reply extends Authenticatable
{
    use Notifiable;
    protected $table = 'widget_quote_reply';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'iframe_id', 'quote_id', 'contact_type', 'name', 'email_phone', 'subject', 'message'
    ];

    public function user() {
        return $this->belongsto(User::class);
    }

    public function category() {
        return $this->belongsto(Category::class);
    }

    public function brand() {
        return $this->belongsto(Brand::class);
    }

    public function series() {
        return $this->belongsto(Series::class);
    }

    public function item() {
        return $this->belongsto(Item::class);
    }

    public function service() {
        return $this->belongsto(Service::class);
    }

    public function location() {
        return $this->belongsto(Location::class);
    }


}