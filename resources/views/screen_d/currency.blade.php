<div class="row">
	<div class="col-md-1 currencyall">
		@foreach ($currencys as $currency)
		<p class="currencyid" id="{{$currency->id}}" style="cursor: pointer;
			@if(isset($company->currency_id))
				@if($currency->id == $company->currency_id)
					font-weight: bold; color:#34dabb;
				@endif
			@endif"
			>
			{{$currency->code}}
		</p>
	</div>

	<div class="col-md-1 currencyall">
		@endforeach
</div>

