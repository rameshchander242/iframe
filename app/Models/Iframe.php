<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Iframe extends Authenticatable
{
    use Notifiable;

    protected $table = 'iframes';
    
    protected $fillable = [
        'name', 'user_id', 'location', 'category', 'brand', 'series', 'item', 'service', 'description', 'success_page', 'google_analytic', 'iframe_color', 'status'
    ];

    protected $casts = [
        'location' => 'array', 
        'category' => 'array', 
        'brand' => 'array', 
        'series' => 'array', 
        'item' => 'array', 
        'service' => 'array', 
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function categories() {
        return $this->belongsToMany(Category::class);
    }

    // public function error_logs() {
    //     return $this->hasMany(Error_log::class);
    // }

    // public function category_services() {
    //     return $this->belongsToMany(Service::class, 'category_iframe_service')->withPivot('category_id');
    // }
    
    // $product->categories()->attach($category);
}
