<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected $primaryKey = 'order_item_id';

    protected $fillable = [
        'order_id',
        'menu_item_id',
        'quantity'
    ];

    public function order() {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function menu_item() {
        return $this->belongsTo(MenuItem::class, 'menu_item_id');
    }
}
