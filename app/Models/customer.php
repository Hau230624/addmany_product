<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customer extends Model
{
    use HasFactory;
    protected $fillable =[
        'name',
        'address',
        'phone',
        'email'
    ];
    public function orders(){
        return $this->hasMany(order::class);
    }
}
