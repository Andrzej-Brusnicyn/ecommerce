<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Cart;
use App\Models\Service;

class CartService extends Model
{

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
}
