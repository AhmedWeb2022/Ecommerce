<?php

namespace App\Models\OrderProduct;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderProduct extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    protected $table = 'order_products';
}
