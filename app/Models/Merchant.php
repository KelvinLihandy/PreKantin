<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    use HasFactory;
    protected $primaryKey = 'merchant_id';
    
    protected $fillable = [
        'user_id',
        'image',
        'open',
        'close',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function menuItems() {
        return $this->hasMany(MenuItem::class, 'merchant_id');
    }

    public function orders() {
        return $this->hasMany(Order::class, 'merchant_id');
    }
}
