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
}
