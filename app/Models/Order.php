<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;
    protected $primaryKey = 'order_id';

    protected $fillable = [
        'user_id',
        'merchant_id',
        'status_id',
        'order_time',
        'invoice_number',
        'gross_amount',
    ];

    protected static function booted()
    {
        static::creating(function ($order) {
            if (empty($order->invoice_number)) {
                $order->invoice_number = 'INV-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4));
            }
        });
    }

    protected function casts(): array
    {
        return [
            'order_time' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function payment()
    {
        return $this->hasMany(Payment::class, 'order_id');
    }

    public function getTotalPriceAttribute()
    {
        return $this->orderItems->sum(function ($item) {
            return $item->quantity * $item->menu_item->price;
        });
    }

    public function getRouteKeyName(): string
    {
        return 'invoice_number';
    }
}
