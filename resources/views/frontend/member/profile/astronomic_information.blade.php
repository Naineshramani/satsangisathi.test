<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('Astronomic & Horoscope Information')}}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('astrologies.update', $member->id) }}" method="POST">
            <input name="_method" type="hidden" value="PATCH">
            @csrf
            <div class="form-group row">
                <div class="col-md-6">
                    <label>{{translate('Time Of Birth')}}</label>
                    @php
                        $tob_raw = $member->astrologies->time_of_birth ?? '';
                        // Parse stored value e.g. "10:30 AM" or "10:30AM"
                        preg_match('/(\d{1,2}):(\d{2})\s*(AM|PM)/i', $tob_raw, $tob_parts);
                        $tob_hour   = isset($tob_parts[1]) ? (int)$tob_parts[1] : '';
                        $tob_min    = $tob_parts[2] ?? '';
                        $tob_ampm   = isset($tob_parts[3]) ? strtoupper($tob_parts[3]) : '';
                    @endphp
                    <input type="hidden" name="time_of_birth" id="time_of_birth_hidden" value="{{ $tob_raw }}">
                    <div class="d-flex" style="gap:6px;">
                        <select id="tob_hour" class="form-control" style="width:80px;">
                            <option value="">HH</option>
                            @for($h = 1; $h <= 12; $h++)
                                <option value="{{ $h }}" {{ $tob_hour === $h ? 'selected' : '' }}>
                                    {{ str_pad($h, 2, '0', STR_PAD_LEFT) }}
                                </option>
                            @endfor
                        </select>
                        <select id="tob_min" class="form-control" style="width:80px;">
                            <option value="">MM</option>
                            @for($m = 0; $m < 60; $m += 5)
                                <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}"
                                    {{ $tob_min === str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                    {{ str_pad($m, 2, '0', STR_PAD_LEFT) }}
                                </option>
                            @endfor
                        </select>
                        <select id="tob_ampm" class="form-control" style="width:110px;">
                            <option value="">AM/PM</option>
                            <option value="AM" {{ $tob_ampm === 'AM' ? 'selected' : '' }}>AM</option>
                            <option value="PM" {{ $tob_ampm === 'PM' ? 'selected' : '' }}>PM</option>
                        </select>
                    </div>
                    @error('time_of_birth')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="city_of_birth">{{translate('City Of Birth')}}</label>
                    <input type="text" name="city_of_birth" value="{{ $member->astrologies->city_of_birth ?? "" }}" placeholder="{{ translate('City Of Birth') }}" class="form-control" required>
                    @error('moon_sign')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-6">
                    <label for="sun_sign">{{translate('Sun Sign')}}</label>
                    @php $user_sun_sign = !empty($member->astrologies->sun_sign) ? $member->astrologies->sun_sign : ""; @endphp
                    <select class="form-control aiz-selectpicker" name="sun_sign" data-live-search="true">
                        <option value="">{{translate('Select Sun Sign')}}</option>
                        <option value="aries" @if($user_sun_sign == 'aries') selected @endif>{{translate('Aries (Mar 21 – Apr 19)')}}</option>
                        <option value="taurus" @if($user_sun_sign == 'taurus') selected @endif>{{translate('Taurus (Apr 20 – May 20)')}}</option>
                        <option value="gemini" @if($user_sun_sign == 'gemini') selected @endif>{{translate('Gemini (May 21 – Jun 20)')}}</option>
                        <option value="cancer" @if($user_sun_sign == 'cancer') selected @endif>{{translate('Cancer (Jun 21 – Jul 22)')}}</option>
                        <option value="leo" @if($user_sun_sign == 'leo') selected @endif>{{translate('Leo (Jul 23 – Aug 22)')}}</option>
                        <option value="virgo" @if($user_sun_sign == 'virgo') selected @endif>{{translate('Virgo (Aug 23 – Sep 22)')}}</option>
                        <option value="libra" @if($user_sun_sign == 'libra') selected @endif>{{translate('Libra (Sep 23 – Oct 22)')}}</option>
                        <option value="scorpio" @if($user_sun_sign == 'scorpio') selected @endif>{{translate('Scorpio (Oct 23 – Nov 21)')}}</option>
                        <option value="sagittarius" @if($user_sun_sign == 'sagittarius') selected @endif>{{translate('Sagittarius (Nov 22 – Dec 21)')}}</option>
                        <option value="capricorn" @if($user_sun_sign == 'capricorn') selected @endif>{{translate('Capricorn (Dec 22 – Jan 19)')}}</option>
                        <option value="aquarius" @if($user_sun_sign == 'aquarius') selected @endif>{{translate('Aquarius (Jan 20 – Feb 18)')}}</option>
                        <option value="pisces" @if($user_sun_sign == 'pisces') selected @endif>{{translate('Pisces (Feb 19 – Mar 20)')}}</option>
                    </select>
                    @error('sun_sign')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="moon_sign">{{translate('Moon Sign')}}</label>
                    @php $user_moon_sign = !empty($member->astrologies->moon_sign) ? $member->astrologies->moon_sign : ""; @endphp
                    <select class="form-control aiz-selectpicker" name="moon_sign" data-live-search="true">
                        <option value="">{{translate('Select Moon Sign')}}</option>
                        <option value="aries" @if($user_moon_sign == 'aries') selected @endif>{{translate('Aries (Mesha)')}}</option>
                        <option value="aquarius" @if($user_moon_sign == 'aquarius') selected @endif>{{translate('Aquarius (Kumbha)')}}</option>
                        <option value="cancer" @if($user_moon_sign == 'cancer') selected @endif>{{translate('Cancer (Karka)')}}</option>
                        <option value="capricorn" @if($user_moon_sign == 'capricorn') selected @endif>{{translate('Capricorn (Makara)')}}</option>
                        <option value="gemini" @if($user_moon_sign == 'gemini') selected @endif>{{translate('Gemini (Mithuna)')}}</option>
                        <option value="leo" @if($user_moon_sign == 'leo') selected @endif>{{translate('Leo (Simha)')}}</option>
                        <option value="libra" @if($user_moon_sign == 'libra') selected @endif>{{translate('Libra (Tula)')}}</option>
                        <option value="pisces" @if($user_moon_sign == 'pisces') selected @endif>{{translate('Pisces (Meena)')}}</option>
                        <option value="scorpio" @if($user_moon_sign == 'scorpio') selected @endif>{{translate('Scorpio (Vrishchika)')}}</option>
                        <option value="sagittarius" @if($user_moon_sign == 'sagittarius') selected @endif>{{translate('Sagittarius (Dhanu)')}}</option>
                        <option value="taurus" @if($user_moon_sign == 'taurus') selected @endif>{{translate('Taurus (Vrishabha)')}}</option>
                        <option value="virgo" @if($user_moon_sign == 'virgo') selected @endif>{{translate('Virgo (Kanya)')}}</option>
                    </select>
                    @error('moon_sign')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-6">
                    <label for="nakshatra">{{translate('Nakshatra')}}</label>
                    @php $user_nakshatra = !empty($member->astrologies->nakshatra) ? $member->astrologies->nakshatra : ""; @endphp
                    <select class="form-control aiz-selectpicker" name="nakshatra" data-live-search="true">
                        <option value="" >{{translate('Select Nakshatra')}}</option>
                        <option value="anuradha" @if($user_nakshatra ==  'anuradha') selected @endif >{{translate('Anuradha')}}</option>
                        <option value="ardra" @if($user_nakshatra ==  'ardra') selected @endif >{{translate('Ardra')}}</option>
                        <option value="ashlesha" @if($user_nakshatra ==  'ashlesha') selected @endif >{{translate('Ashlesha')}}</option>
                        <option value="ashwini" @if($user_nakshatra ==  'ashwini') selected @endif >{{translate('Ashwini')}}</option>
                        <option value="bharani" @if($user_nakshatra ==  'bharani') selected @endif >{{translate('Bharani')}}</option>
                        <option value="chitra" @if($user_nakshatra ==  'chitra') selected @endif >{{translate('Chitra')}}</option>
                        <option value="dhanishta" @if($user_nakshatra ==  'dhanishta') selected @endif >{{translate('Dhanishta')}}</option>
                        <option value="hasta" @if($user_nakshatra ==  'hasta') selected @endif >{{translate('Hasta')}}</option>
                        <option value="jyeshtha" @if($user_nakshatra ==  'jyeshtha') selected @endif >{{translate('Jyeshtha')}}</option>
                        <option value="krittika" @if($user_nakshatra ==  'krittika') selected @endif >{{translate('Krittika')}}</option>
                        <option value="magha" @if($user_nakshatra ==  'magha') selected @endif >{{translate('Magha')}}</option>
                        <option value="mrigashira" @if($user_nakshatra ==  'mrigashira') selected @endif >{{translate('Mrigashira')}}</option>
                        <option value="mula" @if($user_nakshatra ==  'mula') selected @endif >{{translate('Mula')}}</option>
                        <option value="punarvasu" @if($user_nakshatra ==  'punarvasu') selected @endif >{{translate('Punarvasu')}}</option>
                        <option value="purva_ashadha" @if($user_nakshatra ==  'purva_ashadha') selected @endif >{{translate('Purva Ashadha')}}</option>
                        <option value="purva_bhadrapada" @if($user_nakshatra ==  'purva_bhadrapada') selected @endif >{{translate('Purva Bhadrapada')}}</option>
                        <option value="purva_phalguni" @if($user_nakshatra ==  'purva_phalguni') selected @endif >{{translate('Purva Phalguni')}}</option>
                        <option value="pushya" @if($user_nakshatra ==  'pushya') selected @endif >{{translate('Pushya')}}</option>
                        <option value="revati" @if($user_nakshatra ==  'revati') selected @endif >{{translate('Revati')}}</option>
                        <option value="rohini" @if($user_nakshatra ==  'rohini') selected @endif >{{translate('Rohini')}}</option>
                        <option value="shatabhisha" @if($user_nakshatra ==  'shatabhisha') selected @endif >{{translate('Shatabhisha')}}</option>
                        <option value="shravana" @if($user_nakshatra ==  'shravana') selected @endif >{{translate('Shravana')}}</option>
                        <option value="swati" @if($user_nakshatra ==  'swati') selected @endif >{{translate('Swati')}}</option>
                        <option value="uttara_ashadha" @if($user_nakshatra ==  'uttara_ashadha') selected @endif >{{translate('Uttara Ashadha')}}</option>
                        <option value="uttara_bhadrapada" @if($user_nakshatra ==  'uttara_bhadrapada') selected @endif >{{translate('Uttara Bhadrapada')}}</option>
                        <option value="uttara_phalguni" @if($user_nakshatra ==  'uttara_phalguni') selected @endif >{{translate('Uttara Phalguni')}}</option>
                        <option value="vishakha" @if($user_nakshatra ==  'vishakha') selected @endif >{{translate('Vishakha')}}</option>
                    </select>
                    @error('nakshatra')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="gana">{{translate('Gana')}}</label>
                    @php $user_gana = !empty($member->astrologies->gana) ? $member->astrologies->gana : ""; @endphp
                    <select class="form-control aiz-selectpicker" name="gana">
                        <option value="" >{{translate('Select Gana')}}</option>
                        <option value="deva" @if($user_gana ==  'deva') selected @endif >{{translate('Deva')}}</option>
                        <option value="manushya" @if($user_gana ==  'manushya') selected @endif >{{translate('Manushya')}}</option>
                        <option value="rakshasa" @if($user_gana ==  'rakshasa') selected @endif >{{translate('Rakshasa')}}</option>
                    </select>
                    @error('gana')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-6">
                    <label for="nadi">{{translate('Nadi')}}</label>
                    @php $user_nadi = !empty($member->astrologies->nadi) ? $member->astrologies->nadi : ""; @endphp
                    <select class="form-control aiz-selectpicker" name="nadi">
                        <option value="" >{{translate('Select Nadi')}}</option>
                        <option value="aadi" @if($user_nadi ==  'aadi') selected @endif >{{translate('Aadi')}}</option>
                        <option value="antya" @if($user_nadi ==  'antya') selected @endif >{{translate('Antya')}}</option>
                        <option value="madhya" @if($user_nadi ==  'madhya') selected @endif >{{translate('Madhya')}}</option>
                    </select>
                    @error('nadi')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="manglik">{{translate('Manglik')}}</label>
                    @php $user_manglik = !empty($member->astrologies->manglik) ? $member->astrologies->manglik : ""; @endphp
                    <select class="form-control aiz-selectpicker" name="manglik">
                        <option value="" >{{translate('Select Manglik')}}</option>
                        <option value="yes" @if($user_manglik ==  'yes') selected @endif >{{translate('Yes')}}</option>
                        <option value="no" @if($user_manglik ==  'no') selected @endif >{{translate('No')}}</option>
                    </select>
                    @error('manglik')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="text-right">
                <button type="submit" class="btn btn-primary btn-sm">{{translate('Update')}}</button>
            </div>
        </form>
    </div>
</div>
<script>
(function () {
    function buildTimeValue() {
        var h = $('#tob_hour').val();
        var m = $('#tob_min').val();
        var a = $('#tob_ampm').val();
        if (h && m && a) {
            $('#time_of_birth_hidden').val(h.padStart(2,'0') + ':' + m + ' ' + a);
        } else {
            $('#time_of_birth_hidden').val('');
        }
    }
    $('#tob_hour, #tob_min, #tob_ampm').on('change', buildTimeValue);
})();
</script>
