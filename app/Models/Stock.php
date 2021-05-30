<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $guarded = [
        'id'
    ];

    /**
     * Category relation
     *
     * @return relationMethod
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
