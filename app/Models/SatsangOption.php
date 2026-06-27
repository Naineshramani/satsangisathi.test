<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SatsangOption extends Model
{
    use SoftDeletes;

    protected $fillable = ['category', 'name'];

    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
