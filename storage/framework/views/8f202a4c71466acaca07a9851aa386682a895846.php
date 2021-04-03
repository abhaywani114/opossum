<div class="d-flex align-items-center justify-content-center">
	<div class="align-self-center value-button increase" id="increase_<?php echo e($product_id); ?>"
		onclick="increaseValue('<?php echo e($product_id); ?>')" value="Increase Value">
		<ion-icon class="ion-ios-plus-outline"
			style="font-size: 24px;margin-right:5px;">
		</ion-icon>
	</div>

	<input type="number" id="number_<?php echo e($product_id); ?>" oninput="changeValueOnBlur('<?php echo e($product_id); ?>')"
		class="number product_qty js-product-qty"
		value="0" min="0" required=""/>

	<div class="value-button decrease" id="decrease_<?php echo e($product_id); ?>"
		onclick="decreaseValue('<?php echo e($product_id); ?>')" value="Decrease Value">
		<ion-icon class="ion-ios-minus-outline"
			style="font-size: 24px;margin-left:5px;">
		</ion-icon>
	</div>
</div>
<?php /**PATH D:\Server_Files\oceania\trunk\oceania\resources\views/fuel_stockmgmt/inven_qty.blade.php ENDPATH**/ ?>