<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    const PAYMENT_STATUSES = [
        'unpaid' => 'Unpaid',
        'partial' => 'Partially Paid',
        'paid' => 'Fully Paid'
    ];

    const ORDER_STATUSES = [
        'pending' => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'completed' => 'Completed'
    ];
    
    const VENDOR_STATUSES = [
        'pending' => 'Pending Receipt',
        'received' => 'Items Received',
        'partial' => 'Partial Receipt',
        'damaged' => 'Damaged Items'
    ];

    protected $fillable = [
        'store_id', 
        'status',
        'vendor_status',
        'total_amount', 
        'paid_amount', 
        'payment_status',
        'notes',
        'admin_notes',
        'shipping_method',
        'estimated_delivery'
    ];
    
    protected $casts = [
        'estimated_delivery' => 'date',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2'
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
    
    public function payments()
    {
        return $this->hasMany(PurchaseOrderPayment::class);
    }
    
    public function stockTransactions()
    {
        return $this->hasMany(ItemStockTransaction::class);
    }
    
    public function getRemainingBalanceAttribute()
    {
        return $this->total_amount - $this->paid_amount;
    }
    
    public function calculateTotalAmount()
    {
        return $this->items->sum(function($item) {
            return $item->quantity * $item->superAdminStock->price;
        });
    }
    
    public function updatePaymentStatus()
    {
        // Calculate paid amount based on approved payments
        $approvedPaymentsTotal = $this->payments()
            ->where('status', 'approved')
            ->sum('amount');
            
        $this->paid_amount = $approvedPaymentsTotal;
        
        if ($this->paid_amount >= $this->total_amount) {
            $this->payment_status = 'paid';
        } elseif ($this->paid_amount > 0) {
            $this->payment_status = 'partial';
        } else {
            $this->payment_status = 'unpaid';
        }
        
        $this->save();
        return $this->payment_status;
    }
    
    public function processStockTransactions()
    {
        throw_if($this->vendor_status === 'received', 
            new \Exception('Stock already processed for this order'));

        throw_if($this->status !== 'completed',
            new \Exception('Purchase order not completed by admin'));
            
        \DB::transaction(function () {
            foreach ($this->items as $poItem) {
                $superAdminStock = $poItem->superAdminStock;
                
                // Get or create vendor item with full field mapping
                $vendorItem = Item::firstOrCreate(
                    [
                        'store_id' => $this->store_id,
                        'super_admin_stock_id' => $superAdminStock->id
                    ],
                    $this->mapSuperAdminStockFields($superAdminStock)
                );

                // Capture stock values
                $oldStock = $vendorItem->stock;
                $newStock = $oldStock + $poItem->quantity;

                // Create stock transaction with history
                ItemStockTransaction::create([
                    'item_id' => $vendorItem->id,
                    'purchase_order_id' => $this->id,
                    'user_id' => auth()->id(),
                    'quantity' => $poItem->quantity,
                    'old_stock' => $oldStock,
                    'new_stock' => $newStock,
                    'type' => 'credit',
                    'notes' => 'PO #'.$this->id
                ]);

                // Update stock with exact value
                $vendorItem->stock = $newStock;
                $vendorItem->save();
            }
        });
        
        return true;
    }
    
    public function getPaymentStatusBadgeAttribute()
    {
        return match($this->payment_status) {
            'paid' => 'success',
            'partial' => 'info',
            default => 'warning'
        };
    }
    
    protected function mapSuperAdminStockFields($superAdminStock): array
    {
        return [
            'name' => $superAdminStock->name,
            'description' => $superAdminStock->description ?? '',
            'image' => $superAdminStock->image,
            'category_id' => $superAdminStock->category_id,
            'category_ids' => $superAdminStock->category_ids,
            'price' => $superAdminStock->price,
            'tax' => 0.00, // Explicit default
            'discount' => $superAdminStock->discount,
            'discount_type' => $superAdminStock->discount_type,
            'available_time_starts' => null,
            'available_time_ends' => null,
            'veg' => $superAdminStock->veg,
            'module_id' => $superAdminStock->module_id,
            'unit_id' => $superAdminStock->unit_id,
            'stock' => 0, // Initialize before increment
            'status' => 1,
            'is_approved' => 1,
            'images' => $superAdminStock->images ?? [],
            'choice_options' => json_encode($superAdminStock->choice_options ?? [], JSON_THROW_ON_ERROR),
            'variations' => json_encode($superAdminStock->variations ?? [], JSON_THROW_ON_ERROR),
            'add_ons' => json_encode($superAdminStock->add_ons ?? [], JSON_THROW_ON_ERROR),
            'attributes' => json_encode($superAdminStock->attributes ?? [], JSON_THROW_ON_ERROR),
            'is_halal' => $superAdminStock->is_halal ?? 0
        ];
    }
}
