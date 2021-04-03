<!--Modal EodPrint-->

<!--Modal Body Starts-->
<div class="modal-body" style="font-size: 14px;">
    <!--Section 1 starts-->
    <div class="row" style="text-align:center;">
        <div class="col-md-12 text-center  pr-5 pl-5" style="font-size: 15px">
            <strong>
                <?php echo e(!empty($company->name)?$company->name:"Ocosystem Ltd"); ?>

                (<?php echo e(!empty($company->business_reg_no)?$company->business_reg_no:"565565"); ?>

                ) <?php echo e(!empty($company->gst_vat_sst)?"(SST No. ".$company->gst_vat_sst.")":""); ?></strong>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center  pr-5 pl-5" style="font-size: 10px">
            <strong>
                <?php echo e(!empty($company->office_address)?$company->office_address:"1, King Cross, Cheras, 56100 Kuala Lumpur, Malaysia"); ?></strong>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-7 pr-0">
            <strong>Personal Shift Summary</strong>
        </div>
        <div class="col-md-5 pl-1 text-right">
            <strong>
		  	<?php echo e($login_time); ?> <?php echo e($logout_time); ?>

		   </strong>
        </div>
    </div>

    <hr style="margin-top:5px !important;
					margin-bottom:5px !important;
					border: 0.5px solid #a0a0a0;">

    <div class="row">
        <div class="col-md-6">

        </div>
        <div class="col-md-2">
            <strong class="global_currency"></strong>
        </div>
        <div class="col-md-4" style="text-align: right; font-size:17px">
            <strong
                id="item_amount"><b><?php echo e(empty($company->currency->code) ? 'MYR': $company->currency->code); ?></b></strong>
        </div>
    </div>

    <hr style="margin-top:5px !important;
					margin-bottom:5px !important;
					border: 0.5px solid #a0a0a0;">

    <div class="row">
        <div class="col-md-6">
            Today Sales
        </div>
        <div class="col-md-6" style="text-align: right;">
            <strong style="font-weight:normal" id="item_amount">
                <?php echo e($sales); ?></strong>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?php echo e(!empty($terminal->taxtype)?strtoupper($terminal->taxtype):"SST"); ?> <?php echo e($terminal->tax_percent??"6"); ?>%
        </div>
        <div class="col-md-6" style="text-align: right;">
            <strong style="font-weight:normal" id="item_amount">
			   <?php echo e($tax); ?></strong>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            Rounding
        </div>
        <div class="col-md-6" style="text-align: right;">
            <strong style="font-weight:normal" id="item_amount">
                <?php echo e($round); ?>

            </strong>
        </div>
    </div>
    <!--section 1 ends-->
    <hr style="margin-top:5px !important;
					margin-bottom:5px !important;
					border: 0.5px solid #a0a0a0;">

    <!--section 2 starts-->
    <div class="row">
        <div style="font-weight:normal" class="col-md-6">
            Cash
        </div>
        <div class="col-md-6" style="text-align: right;">
            <strong style="font-weight:normal" id="item_amount">
				<?php echo e($cash); ?></strong>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            Credit Card
        </div>
        <div class="col-md-6" style="text-align: right;">
            <strong style="font-weight:normal" id="item_amount">
                <?php echo e($creditcard); ?></strong>
        </div>
    </div>

	<div class="row">
        <div class="col-md-8">
			Wallet
        </div>
        <div class="col-md-4" style="text-align: right;">
            <strong style="font-weight:normal" id="item_amount">
				<?php echo e($wallet); ?>

			</strong>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            Outdoor Payment Terminal
        </div>
        <div class="col-md-4" style="text-align: right;">
            <strong style="font-weight:normal" id="item_amount">
                <?php echo e(empty($opos_eoddetails->creditcard) ? '0.00':number_format(($opos_eoddetails->creditcard/100),2)); ?></strong>
        </div>
    </div>
    <?php if($terminal_btype->btype??"" == 'petrol_station'): ?>
        <div class="row">
            <div class="col-md-6">
                Trade Debtor
            </div>
            <div class="col-md-2">
                <strong
                    class="global_currency"><?php echo e(empty($company->currency->code) ? 'MYR': $company->currency->code); ?></strong>
            </div>
            <div class="col-md-4" style="text-align: right;">
                <strong id="item_amount">0.00</strong>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                Cheque
            </div>
            <div class="col-md-2">
                <strong
                    class="global_currency"><?php echo e(empty($company->currency->code) ? 'MYR': $company->currency->code); ?></strong>
            </div>
            <div class="col-md-4" style="text-align: right;">
                <strong id="item_amount">0.00</strong>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                Manual OPT
            </div>
            <div class="col-md-2">
                <strong
                    class="global_currency"><?php echo e(empty($company->currency->code) ? 'MYR': $company->currency->code); ?></strong>
            </div>
            <div class="col-md-4" style="text-align: right;">
                <strong id="item_amount"><?php echo e(number_format((@$OPT/100),2)); ?></strong>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                Fleet Card
            </div>
            <div class="col-md-2">
                <strong
                    class="global_currency"><?php echo e(empty($company->currency->code) ? 'MYR': $company->currency->code); ?></strong>
            </div>
            <div class="col-md-4" style="text-align: right;">
                <strong id="item_amount">0.00</strong>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                Cash Card
            </div>
            <div class="col-md-2">
                <strong
                    class="global_currency"><?php echo e(($company->currency->code) ? 'MYR': $company->currency->code); ?></strong>
            </div>
            <div class="col-md-4" style="text-align: right;">
                <strong id="item_amount">0.00</strong>
            </div>
        </div>
<?php endif; ?>
<!--section 2 ends-->
    <hr style="margin-top:5px !important;
					margin-bottom:5px !important;
					border: 0.5px solid #a0a0a0;">

    <!--section 3 starts-->
    <div class="row">
        <div class="col-md-6 text-left">
            <strong style="font-weight:normal">Location</strong>
        </div>
        <div class="col-md-6 text-right">
            <?php echo e($location->name??""); ?>

        </div>
    </div>
    <div class="row">
        <div class="col-md-6 text-left">
            <strong style="font-weight:normal">Location ID</strong>
        </div>
        <div class="col-md-6 text-right">
            <?php echo e($location->systemid??""); ?>

        </div>
    </div>
    <div class="row">
        <div class="col-md-6 text-left">
            <strong style="font-weight:normal">Terminal ID</strong>
        </div>
        <div class="col-md-6 text-right">
            <?php echo e($terminal->systemid??""); ?>

        </div>
    </div>

    <div class="row">
        <div class="col-md-6 text-left">
            <strong style="font-weight:normal">Staff Name</strong>
        </div>
        <div class="col-md-6 text-right">
            <?php echo e($user->fullname??''); ?>

        </div>
    </div>
    <div class="row">
        <div class="col-md-6 text-left">
            <strong style="font-weight:normal">Staff ID</strong>
        </div>
        <div class="col-md-6 text-right">
            <?php echo e($user->systemid??''); ?>

        </div>
    </div>

    <div class="row align-items-center mt-3">
        <div class="col-md-6">
            <img src="<?php echo e(asset('images/dispenser_icon.png')); ?>"
                 style="filter:invert(100%);transform:scaleX(-1);
						width:50px;height:50px;object-fit:contain;
						margin-left:0;"/>

            <img src="<?php echo e(asset('images/basket_transparent.png')); ?>"
                 style="filter:invert(100%);
						width:55px;height:55px;object-fit:contain;
						margin-left:10px;"/>
        </div>
        <div class="col-md-6 text-right">
            <button class="btn btn-success bg-receipt-print"
                    id="print_eod"
                    style="font-size:13px;"
                    onclick="print_pss()">
                <strong>Print</strong>
            </button>
        </div>
    </div>
    <div class="row float-right"
         style="font-size:10px;padding-right:15px;margin-top:6px">
        <strong>Betta Forecourt v1.0<strong>
    </div>
    <!--section 3 ends-->
</div>
<!--Modal Body ends-->

<script type="text/javascript">

    /*
    $('#print_eod').attr('disabled', 'on');
	 */
    function print_pss() {
        $.ajax({
            url: "<?php echo e(route('local_cabinet.pss.print')); ?>",
            type: 'post',
            data: {
				'login_time':'<?php echo e(request()->login_time); ?>', 
				'user_systemid': '<?php echo e(request()->user_systemid); ?>',
				<?php if(!empty(request()->logout_time)): ?>
					'logout_time':'<?php echo e(request()->logout_time); ?>'
				<?php endif; ?>
		},
		success: function (response) {
			console.log("Printing PSS")
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
			$('#response').html(e);
		}
	});
}

</script>
<?php /**PATH D:\Server_Files\oceania\trunk\oceania\resources\views/local_cabinet/pss.blade.php ENDPATH**/ ?>