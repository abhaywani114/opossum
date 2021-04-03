<div class="d-flex align-items-center justify-content-center">
	<div class="align-self-center value-button increase" id="increase_{{$product_id}}"
		onclick="increaseValue('{{$product_id}}')" value="Increase Value">
		<ion-icon class="ion-ios-plus-outline"
			style="font-size: 24px;margin-right:5px;">
		</ion-icon>
	</div>

	<input type="number" id="number_{{$product_id}}" oninput="changeValueOnBlur('{{$product_id}}')"
		class="number product_qty js-product-qty"
		value="0" min="0" required=""/>

	<div class="value-button decrease" id="decrease_{{$product_id}}"
		onclick="decreaseValue('{{$product_id}}')" value="Decrease Value">
		<ion-icon class="ion-ios-minus-outline"
			style="font-size: 24px;margin-left:5px;">
		</ion-icon>
	</div>
</div>
