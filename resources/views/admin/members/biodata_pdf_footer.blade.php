@php
    $footer_phones = collect(json_decode(get_setting('footer_phones') ?? '[]'))->implode(' / ');
@endphp
<div style="width:100%; background:#ffffff; border-top:1px solid #c9a24b; padding-top:2mm;">
    <table style="width:100%;">
        <tr>
            <td style="width:16mm; text-align:left; vertical-align:middle;">
                @if (get_setting('header_logo'))
                    <img src="{{ uploaded_asset(get_setting('header_logo')) }}" width="138" height="38">
                @endif
            </td>
            <td style="text-align:center; vertical-align:middle;">
                <div style="font-weight:bold; font-size:9.5pt; color:#8b0000;">{{ translate('For more info and more profiles visit www.satsangisathi.in') }}</div>
                <div style="font-size:8pt; color:#4a2e00;">
                    {{ get_setting('footer_email') ?: 'Info@satsangisathi.in' }}
                    @if ($footer_phones) &nbsp;|&nbsp; {{ $footer_phones }} @endif
                </div>
            </td>
            <td style="width:16mm;"></td>
        </tr>
    </table>
</div>
