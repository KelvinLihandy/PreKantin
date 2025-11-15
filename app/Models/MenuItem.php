<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'merchant_id',
        'image',
        'price',
        'is_available',
    ];

    public function merchant() {
        return $this->belongsTo(Merchant::class, 'merchant_id');
    }
}
