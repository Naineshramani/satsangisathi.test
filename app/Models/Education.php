<?php

namespace App\Models;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Education extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'degree', 'specialization', 'institution', 'start', 'end', 'present', 'is_highest_degree'];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
