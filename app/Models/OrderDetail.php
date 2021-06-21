<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id', 'product_id', 'price_including_tax', 'qty'
    ];

    /**
     * Product relation
     *
     * @return relationMethod
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
