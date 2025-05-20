<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrderPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id',
        'amount',
        'payment_method',
        'transaction_id',
        'payment_proof',
        'notes',
        'status'
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'pending_approval' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            default => 'secondary'
        };
    }
    
    public function getReadablePaymentMethodAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->payment_method));
    }
}
