<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['invoice_number', 'user_id', 'customer_name', 'total_amount', 'status', 'snap_token'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction_details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}