<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Item extends Authenticatable
{
    use Notifiable;

    protected $table = 'items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'timeframe', 'warranty', 'category_id', 'brand_id', 'series_id', 'description', 'image', 'position', 'status'
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

}
