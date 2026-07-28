<?php

namespace App\Models;
use App\Models\Member;
use App\Models\Address;
use App\Models\Education;
use App\Models\Career;
use App\Models\PhysicalAttribute;
use App\Models\Hobby;
use App\Models\Attitude;
use App\Models\Recidency;
use App\Models\Lifestyle;
use App\Models\Astrology;
use App\Models\Family;
use App\Models\PartnerExpectation;
use App\Models\SpiritualBackground;
use App\Models\PackagePayment;
use App\Models\HappyStory;
use App\Models\Shortlist;
use App\Models\IgnoredUser;
use App\Models\ReportedUser;
use App\Models\Staff;
use App\Models\GalleryImage;
use App\Models\ExpressInterest;
use App\Models\ProfileMatch;
use App\Models\ProfileViewer;
use App\Models\SatsangDetail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use App\Notifications\EmailVerificationNotification;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use SoftDeletes;
    use Notifiable;
    use HasRoles;

    const TRIAL_DAYS = 7;

    public function sendEmailVerificationNotification()
    {
        $this->notify(new EmailVerificationNotification());
    }

    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = $value !== null ? \Illuminate\Support\Str::title(trim($value)) : $value;
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = $value !== null ? \Illuminate\Support\Str::title(trim($value)) : $value;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'code', 'phone','membership','approved', 'verification_code','fcm_token','referred_by','email_verified_at', 'match_refresh_updated_at', 'refresh_updated_at', 'whatsapp_consent', 'father_mobile', 'mother_mobile', 'primary_contact'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    /**
     * True while the member is inside their post-approval trial window
     * (approved, no active package, still within TRIAL_DAYS of approval).
     */
    public function onTrial(): bool
    {
        if ($this->approved != 1 || $this->approved_at === null) {
            return false;
        }

        if ($this->member && !is_null($this->member->current_package_id)) {
            return false;
        }

        return $this->approved_at->copy()->addDays(self::TRIAL_DAYS)->isFuture();
    }

    /**
     * True if $candidateId has sent an interest to this user.
     */
    public function hasReceivedInterestFrom(int $candidateId): bool
    {
        return \App\Models\ExpressInterest::where('user_id', $this->id)
            ->where('interested_by', $candidateId)
            ->exists();
    }

    public function member()
    {
        return $this->hasOne(Member::class);
    }

    public function addresses()
    {
        return $this->hasmany(Address::class);
    }

    public function education()
    {
        return $this->hasmany(Education::class);
    }

    public function career()
    {
        return $this->hasmany(Career::class);
    }

    public function physical_attributes()
    {
        return $this->hasOne(PhysicalAttribute::class);
    }

    public function hobbies()
    {
        return $this->hasOne(Hobby::class);
    }

    public function attitude()
    {
        return $this->hasOne(Attitude::class);
    }

    public function recidency()
    {
        return $this->hasOne(Recidency::class);
    }

    public function lifestyles()
    {
        return $this->hasOne(Lifestyle::class);
    }

    public function astrologies()
    {
        return $this->hasOne(Astrology::class);
    }

    public function families()
    {
        return $this->hasOne(Family::class);
    }

    public function partner_expectations()
    {
        return $this->hasOne(PartnerExpectation::class);
    }

    public function spiritual_backgrounds()
    {
        return $this->hasOne(SpiritualBackground::class);
    }

    public function payckage_payments()
    {
        return $this->hasmany(PackagePayment::class);
    }

    public function happy_story()
    {
        return $this->hasOne(HappyStory::class);
    }

    public function staff()
    {
        return $this->hasOne(Staff::class);
    }

    public function shortlist()
    {
        return $this->hasmany(Shortlist::class);
    }

    public function ignored_users()
    {
        return $this->hasmany(IgnoredUser::class);
    }

    public function reported_users()
    {
        return $this->hasmany(ReportedUser::class);
    }

    public function gallery_images()
    {
        return $this->hasmany(GalleryImage::class);
    }

    public function express_interests()
    {
        return $this->hasmany(ExpressInterest::class);
    }
    public function profile_match()
    {
        return $this->hasmany(ProfileMatch::class);
    }
    public function uploads(){
        return $this->hasMany(Upload::class);
    }

    public function profile_views(){
        return $this->hasMany(ProfileViewer::class);
    }

    public function satsang_details(){
        return $this->hasOne(SatsangDetail::class);
    }
}
