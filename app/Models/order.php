<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    use HasFactory;
    public $fillable = [

        'total',
        'customer_id',
    ];
    public function customer()
    {
        return $this->belongsTo(customer::class);
    }
    public function details()
    {
        return $this->belongsToMany(product::class, 'order_details', 'order_id', 'product_id')->withPivot('quantity', 'price');
    }
}
