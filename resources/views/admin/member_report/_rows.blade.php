@forelse ($rows as $r)
    <tr>
        <td>{{ $r->code }}</td>
        <td>{{ $r->name }}</td>
        <td>{{ $r->gender_label }}</td>
        <td>{{ $r->age }}</td>
        <td>{{ $r->marital_status }}</td>
        <td>{{ $r->sect }}</td>
        <td>{{ $r->caste }}</td>
        <td>{{ $r->city }}</td>
        <td>{{ $r->state }}</td>
        <td>{{ $r->country }}</td>
        <td>{{ $r->education }}</td>
        <td>{{ $r->profession }}</td>
        <td>{{ $r->phone }}</td>
        <td>{{ $r->last_login }}</td>
    </tr>
@empty
    <tr><td colspan="14" class="text-center">{{ translate('No members match the selected filters') }}</td></tr>
@endforelse
