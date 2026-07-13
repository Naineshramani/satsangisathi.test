<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Astrology;
use App\Models\User;
use Carbon\Carbon;
use Validator;
Use Redirect;

class AstrologyController extends Controller
{
    /**
     * Sun Sign is fully determined by date of birth, so it is always
     * (re)computed here rather than trusted from user/admin input.
     */
    private function calculateSunSign($birthday)
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function update(Request $request, $id)
     {
         $this->rules = [
             'time_of_birth'    => [ 'max:10'],
             'city_of_birth'    => [ 'max:20'],
             'sun_sign'         => [ 'max:255'],
             'moon_sign'        => [ 'max:255'],
             'nakshatra'        => [ 'max:255'],
             'gana'        => [ 'max:255'],
             'nadi'        => [ 'max:255'],
             'manglik'        => [ 'max:255'],
         ];
         $this->messages = [
             'time_of_birth.max'  => translate('Max 10 characters'),
             'city_of_birth.max'  => translate('Max 20 characters'),
             'sun_sign.max'       => translate('Max 255 characters'),
             'moon_sign.max'      => translate('Max 255 characters'),
             'nakshatra.max'      => translate('Max 255 characters'),
             'gana.max'      => translate('Max 255 characters'),
             'nadi.max'      => translate('Max 255 characters'),
             'manglik.max'      => translate('Max 255 characters'),
         ];

         $rules = $this->rules;
         $messages = $this->messages;
         $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
             flash(translate('Something went wrong'))->error();
             return Redirect::back()->withErrors($validator);
         }

         $user                 = User::where('id',$id)->first();
         $astrologies = Astrology::where('user_id', $id)->first();
         if(empty($astrologies)){
             $astrologies           = new Astrology;
             $astrologies->user_id  = $id;
         }

         $astrologies->time_of_birth    = $request->time_of_birth;
         $astrologies->city_of_birth    = $request->city_of_birth;
         $astrologies->sun_sign         = $this->calculateSunSign($user->member->birthday ?? null);
         $astrologies->moon_sign        = $request->moon_sign;
         $astrologies->nakshatra    = $request->nakshatra;
         $astrologies->gana    = $request->gana;
         $astrologies->nadi                       = $request->nadi;
         $astrologies->manglik                    = $request->manglik;
         $astrologies->horoscope_match_preference = $request->horoscope_match_preference ?: null;

         if($astrologies->save()){
            if($astrologies->user->member->auto_horoscope_profile_match ==  1){
              $ProfileMatchController = new HoroscopeProfileMatchController;
              $ProfileMatchController->match_profiles($user->id);
            }
             flash(translate('Astronomic & Horoscope Info has been updated successfully'))->success();
             return back();
         }
         else {
             flash(translate('Sorry! Something went wrong.'))->error();
             return back();
         }

     }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
