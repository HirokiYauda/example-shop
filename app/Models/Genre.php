<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Genre extends Model
{
    use SoftDeletes;

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
