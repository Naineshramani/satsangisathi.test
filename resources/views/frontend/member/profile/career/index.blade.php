<div class="card" id="sec-career">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('Career')}}</h5>
    </div>
    <div class="card-body">
        <div class="text-right mb-2">
            <a onclick="career_add_modal('{{$member->id}}');" href="javascript:void(0);" class="btn btn-sm btn-add-new">
                <i class="las mr-1 la-plus"></i>
                {{translate('Add New')}}
            </a>
        </div>
        <table class="table aiz-table">
            <tr>
                <th>{{translate('Type')}}</th>
                <th>{{translate('Role / Nature')}}</th>
                <th>{{translate('Company / Business')}}</th>
                <th data-breakpoints="md">{{translate('Currency')}}</th>
                <th data-breakpoints="md">{{translate('Monthly Income')}}</th>
                <th data-breakpoints="md">{{translate('Yearly Income')}}</th>
                <th data-breakpoints="md">{{translate('Is Current?')}}</th>
                <th data-breakpoints="md" class="text-right">{{translate('Options')}}</th>
            </tr>

            @php $careers = \App\Models\Career::where('user_id',$member->id)->get(); @endphp
            @foreach ($careers as $key => $career)
            @php
                $typeLabel = match($career->employment_type ?? 'job') {
                    'business'     => 'Business',
                    'self_employed' => 'Self-Employed',
                    'not_working'  => 'Not Working',
                    default        => 'Job',
                };
                $roleDisplay = $career->employment_type === 'business'
                    ? $career->nature_of_business
                    : $career->designation;
            @endphp
            <tr>
                <td><span class="badge badge-soft-primary">{{ $typeLabel }}</span></td>
                <td>{{ $roleDisplay }}</td>
                <td>{{ $career->company }}</td>
                <td>{{ $career->currency ?? 'INR' }}</td>
                <td>{{ $career->start }}</td>
                <td>{{ $career->end }}</td>
                <td>
                    <label class="aiz-switch aiz-switch-success mb-0">
                        <input type="checkbox" id="status.{{ $key }}" onchange="update_career_present_status(this)" value="{{ $career->id }}" @if($career->present == 1) checked @endif data-switch="success"/>
                        <span></span>
                    </label>
                </td>
                <td class="text-right">
                    <a href="javascript:void(0);" class="btn btn-soft-info btn-icon btn-circle btn-sm" onclick="career_edit_modal('{{$career->id}}');" title="{{ translate('Edit') }}">
                        <i class="las la-edit"></i>
                    </a>
                    <a href="javascript:void(0);" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('career_destroy', $career->id)}}" title="{{ translate('Delete') }}">
                        <i class="las la-trash"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
