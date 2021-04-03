<!--Modal EoD Summary-->

<!--Modal Body Starts-->
<div class="modal-body" style="font-size: 14px; font-weight: bold;">
    <!--Section 1 starts-->
    <div class="row" style="text-align:center;">
        <div class="col-md-12 text-center pr-5 pl-5" style="font-size: 15px">
            <strong>
                <?php echo e(!empty($company->name)?$company->name:"Ocosystem Ltd"); ?>

                (<?php echo e(!empty($company->business_reg_no)?$company->business_reg_no:""); ?>)
                <?php echo e(!empty($company->gst_vat_sst)?" (".$company->gst_vat_sst.")":""); ?>

            </strong>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center pr-5 pl-5" style="font-size: 10px">
            <strong>
                <?php echo e(!empty($company->office_address)?$company->office_address:""); ?>

            </strong>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-7 pr-0">
            <strong>End Of The Day Summary</strong>
        </div>
        <div class="col-md-5 pl-1 text-right">
            <strong>
			<?php if(!empty($eoddetailsdata)): ?>
                <?php
                    $today = date('Y-m-d');
                    $recDate = \Carbon\Carbon::parse($eoddetailsdata->created_at)->toDateString();
                ?>
                <?php if(!empty($recDate)): ?>
                    <?php if($today == $recDate): ?>
                        <?php echo e(\Carbon\Carbon::parse($eoddetailsdata->created_at)->format('dMy')); ?> <?php echo e(date('H:i:s')); ?>

                    <?php else: ?>
                        <?php echo e(\Carbon\Carbon::parse($eoddetailsdata->created_at)->format('dMy')); ?> 23:59:59
                    <?php endif; ?>
				<?php endif; ?>
			<?php endif; ?>
            </strong>
        </div>
    </div>

    <hr style="border: 0.5px solid #a0a0a0;
		margin-bottom:5px !important;
		margin-top:5px !important"/>

    <div class="row">
        <div class="col-md-6"></div>
        <div class="col-md-2">
            <strong class="global_currency"></strong>
        </div>
        <div class="col-md-4" style="text-align: right; font-size:17px">
            <strong id="item_amount">
                <?php echo e(empty($company->currency->code) ? 'MYR': $company->currency->code); ?>

            </strong>
        </div>
    </div>

    <hr style="border: 0.5px solid #a0a0a0;
		margin-bottom:5px !important;
		margin-top:5px !important"/>

    <div class="row">
        <div class="col-md-6" style="font-weight: normal">
            Branch Sales
        </div>
        <div class="col-md-6" style="text-align: right;">
            <strong style="font-weight:normal" id="item_amount">
                <?php echo e(number_format(((( ($eoddetailsdata->sales ?? 0) - $reverseAmount)??"0.00")/100),2)); ?>

                
            </strong>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6" style="font-weight: normal">
            Branch <?php echo e(!empty($terminal->taxtype)?strtoupper($terminal->taxtype):"SST"); ?> <?php echo e($terminal->tax_percent??"6"); ?>%
        </div>
        <div class="col-md-6" style="text-align: right;">
            <strong id="item_amount" style="font-weight: normal;">
                <?php echo e(number_format((((($eoddetailsdata->sst ?? 0) - $reverseTax)??"0.00")/100),2)); ?>

            </strong>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6" style="font-weight: normal">
            Branch Rounding
        </div>
        <div class="col-md-6" style="text-align: right;">
            <strong id="item_amount" style="font-weight: normal">
				
				<?php echo e(number_format($round/100,2)); ?>

            </strong>
        </div>
    </div>

    <hr style="border: 0.5px solid #a0a0a0;
		margin-bottom:5px !important;
		margin-top:5px !important"/>

    <div class="row">
        <div class="col-md-6" style="font-weight: normal">
            Today Sales
        </div>
        <div class="col-md-6" style="text-align: right;">
            <strong style="font-weight:normal" id="item_amount">
                <?php echo e(number_format((((($eoddetailsdata->sales ?? 0) - $reverseAmount)??"0.00")/100),2)); ?>

            </strong>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6" style="font-weight: normal;">
            <?php echo e(!empty($terminal->taxtype)?strtoupper($terminal->taxtype):"SST"); ?> <?php echo e($terminal->tax_percent??"6"); ?>%
        </div>
        <div class="col-md-6" style="text-align: right;">
            <strong id="item_amount" style="font-weight: normal;">
                <?php echo e(number_format((((($eoddetailsdata->sst ?? 0 )- $reverseTax)??"0.00")/100),2)); ?>

            </strong>
        </div>
    </div>

    <div class="row" style="font-weight: normal">
        <div class="col-md-6">
            Rounding
        </div>
        <div class="col-md-6" style="text-align: right;">
            <strong id="item_amount" style="font-weight: normal">
				<?php echo e(number_format($round/100,2)); ?>

            </strong>
        </div>
    </div>


    <!--section 1 ends-->
    <hr style="border: 0.5px solid #a0a0a0;
		margin-bottom:5px !important;
		margin-top:5px !important"/>

    <!--section 2 starts-->
    <div class="row">
        <div class="col-md-6" style="font-weight: normal;">
            Cash
        </div>
        <div class="col-md-6" style="text-align: right;">
            <strong id="item_amount" style="font-weight: normal;">
                <?php echo e(number_format(((( ($eoddetailsdata->cash ?? 0)- ($eoddetailsdata->cash_change ?? 0)) - $reverseCash??"0.00")/100),2)); ?>

            </strong>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6" style="font-weight: normal;">
            Credit Card
        </div>
        <div class="col-md-6" style="text-align: right;">
            <strong id="item_amount" style="font-weight: normal;">
                <?php echo e(number_format((((($eoddetailsdata->creditcard ?? 0)- $reverseCard)??"0.00")/100),2)); ?>

            </strong>
        </div>
    </div>

	<div class="row">
        <div class="col-md-6" style="font-weight: normal;">
			Wallet
        </div>
        <div class="col-md-6" style="text-align: right;">
            <strong id="item_amount" style="font-weight: normal;">
                <?php echo e(number_format((((($eoddetailsdata->wallet ?? 0)- $reverseWallet)??"0.00")/100),2)); ?>

            </strong>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8" style="font-weight: normal;">
            Outdoor Payment Terminal
        </div>
        <div class="col-md-4" style="text-align: right;">
            <strong id="item_amount"
                    style="font-weight: normal;"><?php echo e(empty($opos_eoddetails->creditcard) ? '0.00':number_format(($opos_eoddetails->creditcard/100),2)); ?></strong>
        </div>
    </div>
    <?php if($terminal_btype->btype??"" == 'petrol_station'): ?>
        <div class="row">
            <div class="col-md-6">
                Trade Debtor
            </div>
            <div class="col-md-2">
                <strong class="global_currency">
                    <?php echo e(empty($company->currency->code) ? 'MYR': $company->currency->code); ?>

                </strong>
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
                <strong class="global_currency">
                    <?php echo e(empty($company->currency->code) ? 'MYR': $company->currency->code); ?>

                </strong>
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
                <strong class="global_currency">
                    <?php echo e(empty($company->currency->code) ? 'MYR': $company->currency->code); ?>

                </strong>
            </div>
            <div class="col-md-4" style="text-align: right;">
                <strong id="item_amount">
                    <?php echo e(number_format((@$OPT/100),2)); ?>

                </strong>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                Fleet Card
            </div>
            <div class="col-md-2">
                <strong class="global_currency">
                    <?php echo e(empty($company->currency->code) ? 'MYR': $company->currency->code); ?>

                </strong>
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
                <strong class="global_currency">
                    <?php echo e(($company->currency->code) ? 'MYR': $company->currency->code); ?>

                </strong>
            </div>
            <div class="col-md-4" style="text-align: right;">
                <strong id="item_amount">0.00</strong>
            </div>
        </div>
<?php endif; ?>


<!--section 2 ends-->
    <hr style="border: 0.5px solid #a0a0a0;
		margin-bottom:5px !important;
		margin-top:5px !important"/>


    <!--section 3 starts-->
    <div class="row">
        <div class="col-md-6 text-left">
            <strong style="font-weight: normal;">Location</strong>
        </div>
        <div class="col-md-6 text-right" style="font-weight: normal;">
            <?php echo e($location->name??""); ?>

        </div>
    </div>
    <div class="row">
        <div class="col-md-6 text-left">
            <strong style="font-weight: normal;">Location ID</strong>
        </div>
        <div class="col-md-6 text-right" style="font-weight: normal;">
            <?php echo e($location->systemid??""); ?>

        </div>
    </div>
    <div class="row">
        <div class="col-md-6 text-left">
            <strong style="font-weight: normal;">Terminal ID</strong>
        </div>
        <div class="col-md-6 text-right" style="font-weight: normal;">
            <?php echo e($terminal->systemid??""); ?>

        </div>
    </div>

    <div class="row">
        <div class="col-md-6 text-left">
            <strong style="font-weight: normal;">Staff Name</strong>
        </div>
        <div class="col-md-6 text-right" style="font-weight: normal;">
            <?php echo e($user->fullname??''); ?>

        </div>
    </div>
    <div class="row">
        <div class="col-md-6 text-left" style="font-weight: normal;">
            <strong style="font-weight: normal;">Staff ID</strong>
        </div>
        <div class="col-md-6 text-right" style="font-weight: normal;">
            <?php echo e($user->systemid??""); ?>

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
                    onclick="print_eod()">
                <strong>Print</strong>
            </button>
        </div>
    </div>
    <div class="row float-right"
         style="font-size:10px;padding-right:15px;margin-top:6px">
        <strong>Betta Forecourt v1.0<strong>
    </div>
    <!--section 3 ends-->

	   <?php if($refund_data != 0): ?>

		 <hr style="border: 0.5px solid #a0a0a0;
			margin-bottom:5px !important;
			margin-top:5px !important"/>


            <div style = "text-align:left; color:orange">
				<strong>Refund</strong>
            </div>

		<!--section 3 starts-->
		<div class="row">
			<div class="col-md-6 text-left">
				<strong style="font-weight: normal;color:orange">Branch Refund</strong>
			</div>
			<div class="col-md-6 text-right" style="font-weight: normal;color:orange">
				<?php echo e(number_format(( $refund_data ??"0.00"),2)); ?>

			</div>
		</div>

		<!--section 3 starts-->
		<div class="row">
			<div class="col-md-6 text-left">
				<strong style="font-weight: normal;color:orange">Tax</strong>
			</div>
			<div class="col-md-6 text-right" style="font-weight: normal;color:orange">
				<?php echo e(number_format(( $refund_sst ??"0.00"),2)); ?>

			</div>
		</div>

	 <!--section 3 starts-->
		<div class="row">
			<div class="col-md-6 text-left">
				<strong style="font-weight: normal;color:orange">Rounding</strong>
			</div>
			<div class="col-md-6 text-right" style="font-weight: normal;color:orange">
				<?php echo e(number_format(( $refund_round ??"0.00"),2)); ?>

			</div>
		</div>

		<?php endif; ?>



</div>
<!--Modal Body ends-->


<script type="text/javascript">



function print_eod() {
    $.ajax({
        url: "/eod_print",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'post',
        data:{
            'eod_date':'<?php echo $eod_date; ?>',
        },
        success: function (response) {
            var error1=false, error2=false;
            console.log('PR '+JSON.stringify(response));

            try {
                eval(response);
                console.log('print_eod: eval() working');
            } catch (exc) {
                error1 = true;
                console.error('ERROR eval(): '+
					JSON.stringify(exc));
            }

            if (!error1) {
				try {
                    escpos_print_template();
                    console.log('print_eod: escpos_print_template() working');
                } catch (exc) {
                    error2 = true;
                    console.error('ERROR escpos_print_template(): '+
						JSON.stringify(exc));
                }
            }
        },
        error: function (e) {
            console.log('PR '+JSON.stringify(e));
        }
    });
}

</script>
<?php /**PATH D:\Server_Files\oceania\trunk\oceania\resources\views/local_cabinet/eod_summarylist.blade.php ENDPATH**/ ?>