<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'beneficiary', 'transaction_type', 'currency_name',
        'amount', 'transaction_code', 'note', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
