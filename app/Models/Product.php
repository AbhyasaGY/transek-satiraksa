<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Secure by Design: Mencegah Mass Assignment
    protected $fillable = ['sku', 'name', 'description', 'price', 'stock'];

    public function transaction_details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}