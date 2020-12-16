<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Brand extends Authenticatable
{
    use Notifiable;

    protected $table = 'brands';
    
    protected $fillable = [
        'name', 'description', 'image', 'timeframe', 'warranty', 'category_id', 'position', 'status'
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function items() {
        return $this->hasMany(Item::class);
    }

    public function series() {
        return $this->hasMany(Series::class);
    }
}
