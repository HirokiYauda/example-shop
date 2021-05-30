<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model
{
    use SoftDeletes;
    
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
