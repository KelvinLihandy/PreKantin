<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $primaryKey = 'order_id';

    protected $fillable = [
        'user_id',
        'merchant_id',
        'status_id',
        'order_time'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function merchant() {
        return $this->belongsTo(Merchant::class, 'merchant_id');
    }

    public function status() {
        return $this->belongsTo(Status::class, 'status_id');
    }
}
