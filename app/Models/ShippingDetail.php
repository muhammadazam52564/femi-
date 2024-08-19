<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingDetail extends Model
{

    use HasFactory;

    protected $fillable = [ "user_id",
    'first_name',
    'last_name',
    'address',
    'city',
    'state',
    'apt',
    'country',
    'postal_code',
    'card_number',
    'expiry',
    'cvc'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
