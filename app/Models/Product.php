<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    
    protected $guarded = [
        'id'
    ];

    /**
     * Genre relation
     *
     * @return relationMethod
     */
    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    /**
     * 商品金額を税込みにして取得
     *
     * @return int
     */
    public function getPriceIncludingTaxAttribute()
    {
        return round($this->price + ($this->price * (config("cart.tax") / 100)));
    }
}
