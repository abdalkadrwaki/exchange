<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_type', 'currency_name', 'currency_name3',
        'amount', 'rate', 'total', 'transaction_code',
        'note', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
