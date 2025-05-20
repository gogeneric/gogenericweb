<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['purchase_order_id', 'super_admin_stock_id', 'quantity'];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function superAdminStock()
    {
        return $this->belongsTo(SuperAdminStock::class);
    }
}
