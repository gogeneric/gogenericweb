<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemStockTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'purchase_order_id',
        'user_id',
        'quantity',
        'old_stock',
        'new_stock',
        'type',
        'notes'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function getFormattedQuantityAttribute()
    {
        return ($this->type === 'credit' ? '+' : '-') . $this->quantity;
    }
}
