<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Category extends Authenticatable
{
    use Notifiable;
    protected $table = 'categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'timeframe', 'warranty', 'image', 'position', 'status'
    ];

    public function brands() {
        return $this->hasMany(Brand::class);
    }

    public function items() {
        return $this->hasMany(Item::class);
    }

    public function widget_quote() {
        return $this->hasMany(Widget_Quote::class);
    }

}