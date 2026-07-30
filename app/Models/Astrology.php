<?php

namespace App\Models;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Astrology extends Model
{
    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    /**
     * Sun Sign is fully determined by date of birth, so it is always
     * (re)computed from birthday rather than trusted from user/admin input.
     * Shared by AstrologyController and the bulk member importer.
     */
    public static function calculateSunSign($birthday)
    {
        if (empty($birthday)) {
            return null;
        }

        $dob = Carbon::parse($birthday);
        $m   = (int) $dob->format('n');
        $d   = (int) $dob->format('j');

        return match(true) {
            ($m == 3 && $d >= 21) || ($m == 4 && $d <= 19) => 'aries',
            ($m == 4 && $d >= 20) || ($m == 5 && $d <= 20) => 'taurus',
            ($m == 5 && $d >= 21) || ($m == 6 && $d <= 20) => 'gemini',
            ($m == 6 && $d >= 21) || ($m == 7 && $d <= 22) => 'cancer',
            ($m == 7 && $d >= 23) || ($m == 8 && $d <= 22) => 'leo',
            ($m == 8 && $d >= 23) || ($m == 9 && $d <= 22) => 'virgo',
            ($m == 9 && $d >= 23) || ($m == 10 && $d <= 22) => 'libra',
            ($m == 10 && $d >= 23) || ($m == 11 && $d <= 21) => 'scorpio',
            ($m == 11 && $d >= 22) || ($m == 12 && $d <= 21) => 'sagittarius',
            ($m == 12 && $d >= 22) || ($m == 1 && $d <= 19) => 'capricorn',
            ($m == 1 && $d >= 20) || ($m == 2 && $d <= 18) => 'aquarius',
            default => 'pisces',
        };
    }
}
