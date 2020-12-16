<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Series extends Authenticatable
{
    use Notifiable;

    protected $table = 'series';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'timeframe', 'warranty', 'description', 'image', 'position', 'status', 'category_id', 'brand_id'
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    public function items() {
        return $this->hasMany(Item::class);
    }
}
