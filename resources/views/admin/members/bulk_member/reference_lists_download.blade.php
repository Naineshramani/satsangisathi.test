<div style="margin-left:auto;margin-right:auto;">
<style media="all">
    @page {
		margin: 0;
		padding:0;
	}
	*{
		margin: 0;
		padding: 0;
	}
	body{
		line-height: 1.5;
		font-family: 'DejaVuSans', 'sans-serif';
		color: #333542;
	}
	div{
		font-size: 1rem;
	}
	.gry-color *,
	.gry-color{
		color:#878f9c;
	}
	table{
		width: 100%;
	}
	table th{
		font-weight: normal;
	}
	table.padding th{
		padding: .5rem .7rem;
	}
	table.padding td{
		padding: .5rem .7rem;
	}
	.border-bottom td,
	.border-bottom th{
		border-bottom:1px solid #eceff4;
	}
	.text-left{
		text-align:left;
	}
	.text-center{
		text-align:center;
	}
	.small{
		font-size: .8rem;
	}
	.strong{
		font-weight: bold;
	}
	h2.section-title{
		background: #eceff4;
		padding: .4rem .7rem;
		font-size: .95rem;
		margin: 1rem 0 .3rem 0;
	}
	.note{
		font-size: .8rem;
		color: #878f9c;
		padding: 0 .7rem;
	}
</style>

	@php
		$logo = get_setting('header_logo');
	@endphp

	<div style="background: #eceff4;padding: 1.5rem;">
		<table>
			<tr>
				<td>
					@if($logo != null)
						<img src="{{ uploaded_asset($logo) }}" height="40" style="display:inline-block;">
					@else
						<img src="{{ static_asset('assets/img/logo.png') }}" height="40" style="display:inline-block;">
					@endif
				</td>
			</tr>
		</table>
	</div>

	<div style="border-bottom:1px solid #eceff4;margin: 0 1.5rem;"></div>

    <div style="padding: 0 1.5rem 1.5rem 1.5rem;">
		<p class="note">{{ translate('Type these values into the bulk-upload sheet exactly as spelled below (case does not matter). Present/Permanent Address Country, State, and City are matched directly by name and are not listed here.') }}</p>

		<h2 class="section-title">{{ translate('Marital Status') }}</h2>
		<table class="padding text-left small border-bottom">
			<tbody class="strong">
				@foreach ($maritalStatuses as $item)
					<tr><td>{{ $item->name }}</td></tr>
				@endforeach
			</tbody>
		</table>

		<h2 class="section-title">{{ translate('Mother Tongue / Known Languages') }}</h2>
		<table class="padding text-left small border-bottom">
			<tbody class="strong">
				@foreach ($memberLanguages as $item)
					<tr><td>{{ $item->name }}</td></tr>
				@endforeach
			</tbody>
		</table>

		<h2 class="section-title">{{ translate('Caste') }}</h2>
		<table class="padding text-left small border-bottom">
			<tbody class="strong">
				@foreach ($castes as $item)
					<tr><td>{{ $item->name }}</td></tr>
				@endforeach
			</tbody>
		</table>

		<h2 class="section-title">{{ translate('Degree (Education) -- use one of these exact spellings for consistency)') }}</h2>
		<table class="padding text-left small border-bottom">
			<tbody class="strong">
				@foreach ($degreeLevels as $item)
					<tr><td>{{ $item->name }}</td></tr>
				@endforeach
			</tbody>
		</table>

		@foreach ($satsangGroups as $label => $options)
			<h2 class="section-title">{{ $label }}</h2>
			<table class="padding text-left small border-bottom">
				<tbody class="strong">
					@foreach ($options as $item)
						<tr><td>{{ $item->name }}</td></tr>
					@endforeach
				</tbody>
			</table>
		@endforeach
	</div>

</div>
