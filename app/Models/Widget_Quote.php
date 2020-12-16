<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Widget_Quote extends Authenticatable
{
    use Notifiable;
    protected $table = 'widget_quote';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'iframe_id', 'location_id', 'category_id', 'brand_id', 'series_id', 'item_id', 'service_id', 'contact', 'fullname', 'email', 'phone', 'price', 'message', 'ip_address'
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