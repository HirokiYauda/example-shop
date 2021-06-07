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
}
