<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Category_Iframe extends Authenticatable
{
    use Notifiable;

    protected $table = 'category_iframe';
    
    protected $fillable = [
        'iframe_id', 'category_id'
    ];

    

    public function iframes() {
        return $this->hasMany(Iframe::class);
    }

    public function category_services() {
        return $this->belongsToMany(Service::class, 'category_iframe_service')->withPivot(['category_id']);
    }

    
    // $product->categories()->attach($category);
}
