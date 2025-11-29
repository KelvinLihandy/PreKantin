<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;
    protected $primaryKey = 'menu_item_id';

    protected $fillable = [
        'merchant_id',
        'image',
        'name',
        'price',
        'is_available',
    ];

    public function merchant() {
        return $this->belongsTo(Merchant::class, 'merchant_id');
    }
}
