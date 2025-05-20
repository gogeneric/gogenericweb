<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuperAdminStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'image', 'category_ids', 'price', 'discount',
        'discount_type', 'stock', 'unit_id', 'module_id', 'status', 'veg'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
