<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SatsangDetail extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'follower_of_sect_id', 'name_of_mandal',
        'nitya_pooja_daily_id', 'wear_kanthi_tilak_id', 'eat_onion_garlic_id',
        'perform_aarti_id', 'observe_fasts_id', 'temple_visit_frequency_id',
        'volunteer_activities', 'define_yourself_satsangi',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function followerOfSect()
    {
        return $this->belongsTo(SatsangOption::class, 'follower_of_sect_id')->withTrashed();
    }

    public function nityaPooja()
    {
        return $this->belongsTo(SatsangOption::class, 'nitya_pooja_daily_id')->withTrashed();
    }

    public function wearKanthiTilak()
    {
        return $this->belongsTo(SatsangOption::class, 'wear_kanthi_tilak_id')->withTrashed();
    }

    public function eatOnionGarlic()
    {
        return $this->belongsTo(SatsangOption::class, 'eat_onion_garlic_id')->withTrashed();
    }

    public function performAarti()
    {
        return $this->belongsTo(SatsangOption::class, 'perform_aarti_id')->withTrashed();
    }

    public function observeFasts()
    {
        return $this->belongsTo(SatsangOption::class, 'observe_fasts_id')->withTrashed();
    }

    public function templeVisitFrequency()
    {
        return $this->belongsTo(SatsangOption::class, 'temple_visit_frequency_id')->withTrashed();
    }
}
