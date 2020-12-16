<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Iframe_Info extends Authenticatable
{
    use Notifiable;

    protected $table = 'iframe_info';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'iframe_id', 'category_id', 'brand_id', 'series_id', 'item_id', 'timeframe', 'warranty', 'description', 'status'
    ];

    public function iframe() {
        return $this->belongsTo(Iframe::class);
    }

    public function location() {
        return $this->belongsTo(Location::class);
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

    public function item() {
        return $this->belongsTo(Item::class);
    }
}
