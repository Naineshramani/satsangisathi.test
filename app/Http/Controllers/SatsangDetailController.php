<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SatsangDetail;
use Redirect;

class SatsangDetailController extends Controller
{
    public function update(Request $request, $user_id)
    {
        $detail = SatsangDetail::where('user_id', $user_id)->first();
        if (!$detail) {
            $detail = new SatsangDetail;
            $detail->user_id = $user_id;
        }

        $detail->follower_of_sect_id       = $request->follower_of_sect_id ?: null;
        $detail->name_of_mandal            = $request->name_of_mandal;
        $detail->nitya_pooja_daily_id      = $request->nitya_pooja_daily_id ?: null;
        $detail->wear_kanthi_tilak_id      = $request->wear_kanthi_tilak_id ?: null;
        $detail->eat_onion_garlic_id       = $request->eat_onion_garlic_id ?: null;
        $detail->perform_aarti_id          = $request->perform_aarti_id ?: null;
        $detail->observe_fasts_id          = $request->observe_fasts_id ?: null;
        $detail->temple_visit_frequency_id = $request->temple_visit_frequency_id ?: null;
        $detail->volunteer_activities      = $request->volunteer_activities;
        $detail->define_yourself_satsangi  = $request->define_yourself_satsangi;

        if ($detail->save()) {
            flash(translate('Satsang Details updated successfully'))->success();
        } else {
            flash(translate('Sorry! Something went wrong.'))->error();
        }

        return back();
    }
}
