<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'order_number', 'zip', 'pref_id', 'address1', 'address2', 'total'
    ];

    /**
     * OrderDetail relation
     *
     * @return relationMethod
     */
    public function orderDetail()
    {
        return $this->hasMany(OrderDetail::class);
    }

    /**
     * Pref relation
     *
     * @return relationMethod
     */
    public function pref()
    {
        return $this->belongsTo(Pref::class);
    }


    /**
     * 全ての住所を取得
     *
     * @return string
     */
    public function getFullAddressAttribute()
    {
        $pref_name = $this->pref->name ?? null;
        if (!empty($this->zip) && !empty($pref_name) && !empty($this->address1) && !empty($this->address2)) {
            return "{$this->zip} {$pref_name} {$this->address1} {$this->address2}";
        }
        return "";
    }
}
