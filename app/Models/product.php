<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    use HasFactory;
    protected $fillable =[
        'name',
        'img',
        'description',
        'price',
        'quantity',
        'supplier_id'
    ];
    public function customer(){
        return $this->belongsTo(customer::class);
    }
    public function orders(){
        return $this->belongsToMany(order::class,'order_details','product_id','order_id')->withPivot('quantity','price');
    }
}
