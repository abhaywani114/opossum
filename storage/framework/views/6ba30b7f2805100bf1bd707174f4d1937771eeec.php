<!--Modal EodPrint-->
<style>
.receipt-item-l {
	text-align:left;
	padding-right:0;
	font-size:12px;
}
.receipt-item-c {
	padding-right:0;
	padding-left:0;
	font-size:12px;
}
.receipt-item-discount {
	text-align:center;
	padding-right:0;
	padding-left:20px;
	font-size:12px;
}
.receipt-item-r {
	text-align:right;
	padding-left:0;
	font-size:12px;
}
.void-stamp{
	 font-size:100px;
	 color:red;
	 position:absolute;
	 z-index:2;
	 font-weight:500;
	 /* margin-top:50%; */
	 margin-left:10%;
	 transform:rotate(45deg);

}
</style>

<!--Modal Body Starts-->
<div class="modal-body"
	style="font-size: 14px; font-weight: bold;">
    <!--Section 1 starts-->

	<div class="row" style="text-align:center;">
		<div class="col-md-12 text-center">
            <?php if(!empty($company->id)&&!empty($receipt->receipt_logo)): ?>
            <img src="<?php echo e(asset('images/company/'.$company->id.'/corporate_logo/'.$receipt->receipt_logo)); ?>" alt=""
			style="object-fit:contain;width: 80px; height: 80px;"
			srcset="" class="mr-1">
            <?php endif; ?>
		</div>
	</div>

	<div class="row" style="text-align:center;">
		<div class="col-md-12 text-center pl-5 pr-5" style="font-size: 17px">
			<b>
			<?php echo e(!empty($receipt->company_name)?$receipt->company_name:""); ?>

			</b>
			<b style="font-size:12px">
            (<?php echo e(!empty($receipt->business_reg_no)?$receipt->business_reg_no:""); ?>)
			</b><br>
			<b style="font-size:12px">
			<?php echo e(!empty($receipt->gst_vat_sst)?"(SST No. ".$receipt->gst_vat_sst.")":""); ?>

			</b>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 text-center">
			<strong style="font-size: 14px">
			<?php echo e(!empty($receipt->receipt_address)?$receipt->receipt_address:""); ?>

			</strong>
		</div>
	</div>

	<hr style="border: 0.5px solid #c0c0c0;margin-top:5px !important;
		font-weight:normal !important;"/>

	<div class="row align-items-center">
		<div class="col-md-4 pr-0" style="font-weight:500 !important">
			<strong>Description</strong>
		</div>
		<div class="text-center col-md-2 pl-3 pr-0">
			<strong>Qty</strong>
		</div>
		<div class="text-center col-md-2 pl-3 pr-0">
			<strong class="global_currency">Price</strong>
		</div>
		<div class="text-center col-md-1 pl-3 pr-0">
			<strong>Disc.</strong>
		</div>
		<div class="text-right col-md-3 pl-0" style="font-size:17px">
			<strong id="item_amount">
			<?php echo e(empty($receipt->currency) ? 'MYR': $receipt->currency); ?>

			</strong>
		</div>
    </div>
    <hr style="border: 0.5px solid #c0c0c0;margin-top:5px !important">
    <?php if(!empty($receiptproduct)): ?>
    <?php $__currentLoopData = $receiptproduct; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	<div class="row align-items-center" style="font-weight: normal">
		<div class="col-md-4 receipt-item-l">
			<?php echo e(!empty($product->name)?$product->name:"RON95"); ?>

		</div>
		<div class="pl-3 col-md-2 receipt-item-c text-center">
			<?php echo e(!empty($product->quantity)?number_format($product->quantity,2):"1"); ?>

		</div>
		<div class="pl-3 col-md-2 receipt-item-c text-center">
			<span class="global_currency"><?php echo e(!empty($product->price)?number_format($product->price/100,2):"0.00"); ?></span>
		</div>
		<div class="col-md-1 receipt-item-discount">
			<?php echo e(!empty($product->discount)?$product->discount_pct:"0"); ?>%
		</div>
		<div class="col-md-3 receipt-item-r">
			<span id="item_amount">
                <?php echo e(number_format(($product->quantity*($product->price/100)) - ($product->discount / 100),2)); ?>

                
			</span>
		</div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
	<hr style="border: 0.5px solid #c0c0c0; margin-top:5px !important"/>
	<div class="row">
		<div class="col-md-6" style="font-weight: normal">
			Item Amount
		</div>
		<div class="col-md-6" style="text-align: right;" >
			<span style="font-weight:normal" id="item_amount">
			<?php echo e(number_format($receiptdetails->item_amount / 100, 2)); ?>

            
			</span>
		</div>
	</div>

	<div class="row" style="font-weight: normal;">
		<div class="col-md-6">
			<?php echo e(!empty($terminal->taxtype)?strtoupper($terminal->taxtype):"SST"); ?> <?php echo e((float) $receipt->service_tax??"6"); ?>%
		</div>
		<div class="col-md-6" style="text-align: right;font-weight: normal;">
			<strong id="item_amount" style="font-weight: normal">
				<?php echo e(number_format($receiptdetails->sst / 100, 2)); ?>

                
			</strong>
		</div>
	</div>

	<div class="row" style="font-weight: normal">
		<div class="col-md-6">
			Rounding
		</div>
		<div class="col-md-6" style="text-align: right;">
            <strong id="rounding_item_amount" style="font-weight: normal">
			<?php echo e(number_format($receiptdetails->rounding / 100, 2)); ?>

			  
			</strong>
		</div>
	</div>
	<div class="void-stamp" id="void-stamp<?php echo e($receipt->id??""); ?>"
		style="display:
			<?php if($receipt->status=='voided'): ?>
				block;
			<?php else: ?>
				none ;
			<?php endif; ?>">
		VOID
	</div>

	<!--section 1 ends-->
	<hr style="border: 0.5px solid #c0c0c0;margin-top:5px !important">
	<!--section 2 starts-->
	<div class="row">
		<div style="font-weight:normal" class="col-md-6">
			Total
		</div>
		<div class="col-md-6" style="text-align: right;">
			<span style="font-weight:normal" id="total_amount_unq">
				<?php echo e(number_format( ($receiptdetails->item_amount / 100) + ($receiptdetails->sst / 100) + ($receiptdetails->rounding /100) , 2)); ?>

			</span>
		</div>
	</div>
	<div class="row">
		<div style="font-weight:normal" class="col-md-6">
			Cash Received
		</div>
		<div class="col-md-6" style="text-align: right;">
            <span style="font-weight:normal" id="item_amount">
                <?php if($receipt->payment_type == "cash"): ?>
                <?php echo e(!empty($receipt->cash_received)?number_format(($receipt->cash_received/100),2):"0"); ?>

                <?php else: ?> 0.00
                <?php endif; ?>

			</span>
		</div>
	</div>
	<div class="row">
		<div style="font-weight:normal" class="col-md-6">
			Credit Card
		</div>
		<div class="col-md-6" style="text-align: right;">
			<span style="font-weight:normal" id="item_amount">
                <?php if($receipt->payment_type == "creditcard"): ?>
                <?php echo e(!empty($receipt->cash_received)?number_format((($receipt->cash_received/100)+((5 * round(($receipt->cash_received-$receipt->cash_change) / 5))-($receipt->cash_received-$receipt->cash_change))/100),2):"0"); ?>

                <?php else: ?> 0.00
                <?php endif; ?>
			</span>
		</div>
	</div>

	<div class="row">
		<div style="font-weight:normal" class="col-md-6">
			Wallet
		</div>
		<div class="col-md-6" style="text-align: right;">
			<span style="font-weight:normal" id="item_amount">
				<?php if($receipt->payment_type == "wallet"): ?>
					<?php echo e(!empty($receipt->cash_received)?number_format((($receipt->cash_received/100)+((5 * round(($receipt->cash_received-$receipt->cash_change) / 5))-($receipt->cash_received-$receipt->cash_change))/100),2):"0"); ?>

                <?php else: ?> 0.00
                <?php endif; ?>
			</span>
		</div>
	</div>


	<!--section 2 ends-->
	<hr style="border: 0.5px solid #c0c0c0; margin-top:5px !important">
	<!--section 3 starts-->
	<div class="row" style="font-weight: normal">
		<div class="col-md-6 text-left" >
			<span style="font-weight: normal">Change</span>
		</div>
		<div class="col-md-6 text-right" >
			<?php echo e(!empty($receipt->cash_change)?number_format((($receipt->cash_change/100)-((5 * round(($receipt->cash_received-$receipt->cash_change) / 5))-($receipt->cash_received-$receipt->cash_change))/100),2):"0.00"); ?>

		</div>
	</div>
	<!--section 3 ends-->
	<hr style="border: 0.5px solid #c0c0c0; margin-top:5px !important">
	<div class="row">
		<div class="col-md-4 pr-0" >
			<span style="font-weight: normal">Receipt No.</span>
		</div>
		<div class="col-md-8 pl-0 text-right">
			<span style="font-weight: normal"><?php echo e(!empty($receipt->systemid)?$receipt->systemid:"7060000010000000014"); ?></span>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6 text-left" >
			<span style="font-weight: normal">Location</span>
		</div>
		<div class="col-md-6 text-right" style="font-weight: normal">
			<?php echo e($location->name??""); ?>

		</div>
	</div>
	<div class="row">
		<div class="col-md-6 text-left" >
			<span style="font-weight: normal">Terminal ID</span>
		</div>
		<div class="col-md-6 text-right" style="font-weight: normal">
			<?php echo e($terminal->systemid??''); ?>

		</div>
	</div>

	<div class="row">
		<div class="col-md-6 text-left" >
			<span style="font-weight: normal">Staff Name</span>
		</div>
		<div class="col-md-6 text-right" style="font-weight: normal">
            <?php echo e($user->fullname??''); ?>

		</div>
	</div>
	<div class="row">
		<div class="col-md-6 text-left" >
			<span style="font-weight: normal">Staff ID</span>
		</div>
		<div class="col-md-6 text-right" style="font-weight: normal">
		    <?php echo e($user->systemid??''); ?>

		</div>
	</div>

	<div class="row">
		<div class="col-md-12 pl-1 text-right">
			<span style="font-weight: normal">
			<?php echo e(date('dMy H:i:s', strtotime($receipt->created_at??''))); ?>

			</span>
		</div>
	</div>


	<div class="row" style="text-align: center;margin: 10px auto;">
		<div class="col-md-12 d-flex p-0"
			style="justify-content:space-around;margin-bottom:5px">

			<!--
            <button class="nohover sellerbutton"
				style="background-color:white;border:0;pointer-events:none">
			</button>
			-->
			<a target="_blank" href="<?php echo e(route('local_cabinet.loyaltyPoints',$receipt->id)); ?>" >
			<button class="btn btn-success bg-receipt-loyalty"
				style="padding-left:0;padding-right:0; margin-right: 5px;
				font-size:13px;border-radius:10px; "
				onclick="">
				<b>Loyalty</b>
			</button>
		</a>
			<button class="nohover sellerbutton"
				style="position:relative;left:-5px;
					background-color:white;border:0;pointer-events:none">
				<img src="<?php echo e(asset('images/dispenser_icon.png')); ?>"
					style="filter:invert(100%);transform:scaleX(-1);
						width:50px;height:50px;object-fit:contain;
						margin-right:15px"/>
			</button>

			<button class="nohover sellerbutton"
				style="position:relative;left:-7px;
					background-color:white;border:0;pointer-events:none">
				<span style="position:relative;left:-5px;
					background-color:white;
					font-weight:bold;color:black;font-size:40px;">
					<?php echo e(!empty($receipt->pump_no)?$receipt->pump_no:""); ?>

				</span>
			</button>

			<img src="<?php echo e(asset('images/qr.png')); ?>"
				style="width: 70px;height: 70px;
				float: left;margin-bottom: 5px; border-radius: 10px;">
		</div>

		<div class="col-md-12 d-flex p-0"
			style="justify-content:space-around">

			<button class="btn btn-success bg-receipt-print"
				style="padding-left:0;padding-right:0; margin-right: 5px;
				font-size:13px;border-radius:10px; "
				onclick="print_receipt()">
				<strong>Print</strong>
			</button>

			<button class="btn btn-success btn-log bg-receipt-void
				sellerbutton void_true only_void_true only_void"
				style="padding-left:0; padding-right:0;  margin-right: 0;
				font-size:13px;border-radius:10px;
				<?php if($refund || $receipt->status=='voided'): ?>
					background: #3a3535cc; cursor:not-allowed;
				<?php endif; ?>"

				<?php if($refund || $receipt->status=='voided'): ?>
					disabled="disabled"
				<?php else: ?>
				onclick="void_receipt(<?php echo e($receipt->id); ?>)" <?php endif; ?>>
				<strong>Void</strong>
			</button>

			<button class="btn btn-success bg-receipt-cstore-refund"
				style="padding-left:0;padding-right:0; margin-right: 5px;
				font-size:13px;border-radius:10px; <?php if($refund || $receipt->status=='voided'): ?>
					background: #999696; cursor:not-allowed;
				<?php endif; ?>"
     			<?php if(!$refund || $receipt->status !='voided'): ?>
					onclick="refund_receipt(<?php echo e($receipt->id); ?>)"
				<?php endif; ?>>
				<b>C-Store<br>Refund</b>
			</button>

			<!--
			<button class="nohover sellerbutton"
				style="background-color:white;border:0;pointer-events:none">
			</button>
			-->

			<button class="nohover sellerbutton"
				style="background-color:white;border:0;pointer-events:none">
			</button>
		</div>
	</div>

	<div class="row d-flex" style="justify-content:center">
		<div style="font-size:14px">
			Thank You!
		</div>
	</div>
     <!--- void by --->
     <div id="void-div<?php echo e($receipt->id??''); ?>"  style="display:<?php if($receipt->status=='voided'): ?> block <?php else: ?> none <?php endif; ?>" >
     <div class="row">
		<div class="col-md-3 text-left" style="color:red;">
			<strong>Void By</strong>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6 text-left"  style="color:red;">
            <strong> <?php echo e($receipt->user->fullname??''); ?></strong>
		</div>
    </div>
    <div class="row">
		<div class="col-md-6 text-left"  style="color:red;">
            <strong> <?php echo e($receipt->user->systemid??''); ?></strong>
		</div>
    </div>
    <div class="row">
		<div class="col-md-6 text-left"  style="color:red;">
            <strong id="void-time<?php echo e($receipt->id??''); ?>"><?php echo e($receipt->voided_at??''); ?></strong>
		</div>
    </div>
    <div class="row">
		<div class="col-md-6 text-left"  style="color:red;">
            <strong id="void-reason<?php echo e($receipt->id??''); ?>"><?php echo e($receipt->void_reason??''); ?></strong>
		</div>
    </div>
     </div>

     <?php if($refund): ?>
            <div style = "text-align:left; color:orange">
                Refunded By:
                <br>
                <?php echo e($refund->name); ?>

                <br>
                <?php echo e($refund->systemid); ?>

                <br>
                <?php echo e(date('dMy', strtotime($refund->created_at))); ?>

                <br>
                MYR <?php echo e((number_format($refund->refund_amount,2))); ?>

                <br>
				<?php echo e($refund->description ??''); ?>

            </div>
            <?php endif; ?>

	<div class="row">
		<div class="col-md-12 text-right">
		   <div style="font-size: 10px">
			   <strong >Tetra Forecourt v1.0<strong>
		   </div> <br>
		</div>
	</div>
	<!--section 4 start-->

</div>
<!--Modal Body ends-->

<script type="text/javascript">

$(document).ready(function () {
	$('.sorting_1').css('background-color','white');
});


/* Function to print receipt via 80mm thermal printer */
function print_receipt(){
	console.log('PR print_receipt()');

	$.ajax({
		url: "/print_receipt",
		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		type: 'post',
		data: {
			'receipt_id':<?php echo $receipt->id; ?>,
		},
		success: function (response) {
			var error1=false, error2=false;
			console.log('PR '+JSON.stringify(response));

			try {
				eval(response);
				console.log('eval working');
			} catch (exc) {
				error1 = true;
				console.error('ERROR eval(): '+exc);
			}

			if (!error1) { try {
					escpos_print_template();
					console.log('template working');
				} catch (exc) {
					error2 = true;
					console.error('ERROR escpos_print_template(): '+exc);
				}
			}
		},
		error: function (e) {
			console.log('PR '+JSON.stringify(e));
		}
	});
}

</script>
<?php /**PATH D:\Server_Files\oceania\trunk\oceania\resources\views/local_cabinet/receipt.blade.php ENDPATH**/ ?>