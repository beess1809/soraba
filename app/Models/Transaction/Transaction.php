<?php

namespace App\Models\Transaction;

use App\Models\Master\PaymentType;
use App\Models\Master\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public function paymentType()
    {
        return $this->hasOne(PaymentType::class, 'id', 'payment_type_id');
    }
    public function status()
    {
        return $this->hasOne(Status::class, 'id', 'status_id');
    }
    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id', 'id');
    }
}
