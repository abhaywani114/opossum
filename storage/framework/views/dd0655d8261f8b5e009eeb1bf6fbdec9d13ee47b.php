/*
function textToBase64Barcode(text){
	var canvas = document.createElement("canvas");
	JsBarcode(canvas, text, {format: "CODE128"});
	return canvas.toDataURL("image/png");
}
*/

function escpos_print_template() {
	//alert('escpos_print_template()');

	var image_url = '';
	var printer = 'DEFAULT_SYSTEM_PRINTER';
	// Create a default config for the found printer
	//var config = qz.configs.create(printer);
	if (printer == 'DEFAULT_SYSTEM_PRINTER') {
		console.log("Printing Using default");
		printer  = printdev;
	}

  	var imgconfig = qz.configs.create(printer, {
		rasterize:false,
		altPrinting:true,
		//units: "mm",
		//density: "300",
	});

   	var escconfig = qz.configs.create(printer, {
		encoding:'GBK',			// Ronta RP326
		//rasterize:false,
		altPrinting:true,
	});

	var addy1 = '';
	var addy2 = '';

	<?php if(!empty($company->office_address)): ?>
		var str = "<?php echo e($company->office_address); ?>";

		console.log('str='+str);

		// Check if address can fit in a line
		if (str.length < 46) {
			// There is only 1 line
			addy1 = str;
		} else {
			// There is more than 1 line
			var lim = str.slice(0,46);
			var n   = lim.split(/ /);
			var len = n.length - 2;
			addy1 = str.slice(0, str.indexOf(n[len])).trim();
			addy2 = str.slice(str.indexOf(n[len]));
		}
	<?php endif; ?>

	console.log('addy1='+addy1);
	console.log('addy2='+addy2);

	<?php
        $today = date('Y-m-d');
        $recDate = \Carbon\Carbon::parse($eoddetailsdata->created_at)->toDateString();
    ?>

	var escdata =  [
	'\x1B' + '\x40',          	// init
	'\x1D' + '\x4C' + '\x14' + '\x00',	// shift left margin
	'\x1B' + '\x61' + '\x31',	// center align
    '\x1B' + '\x21' + '\x08',	// emphasized mode on
	'<?php echo e(!empty($company->name)?$company->name:""); ?>' + ' (<?php echo e(!empty($company->business_reg_no)?$company->business_reg_no:""); ?>)' + '\x0A',
	'\x1B' + '\x21' + '\x00',	// emphasized mode off
	'<?php echo e(!empty($company->gst_vat_sst)? "(SST No. ".$company->gst_vat_sst.")" : ""); ?>' + '\x0A',
	((addy1 != '') ? addy1 : '') + '\x0A',
	((addy2 != '') ? addy2 : '') + '\x0A',
	'\x1B' + '\x61' + '\x30',	// left align
	'\x0A',
    '\x1B' + '\x21' + '\x08',	// emphasized mode on
    'End of The Day Summary    '+ '<?php if(!empty($recDate)): ?> <?php if($today == $recDate): ?> <?php echo e(\Carbon\Carbon::parse($eoddetailsdata->created_at)->format("dMy")); ?> <?php echo e(date("H:i:s")); ?><?php else: ?> <?php echo e(\Carbon\Carbon::parse($eoddetailsdata->created_at)->format("dMy")); ?> 23:59:59 <?php endif; ?> <?php endif; ?>'+ '\x0A',
	'---------------------------------------------' + '\x0A',
	'                                       <?php echo e(str_pad(empty($company->currency->code) ? "MYR": $company->currency->code, 5," ",STR_PAD_LEFT )); ?>' + '\x0A',
//
	'---------------------------------------------' + '\x0A',
//	'123456789012345678901234567890123456789012345'
	'\x1B' + '\x21' + '\x00',	// emphasized mode off
	'Branch Sales                       '+ '<?php echo e(str_pad(number_format(((($eoddetailsdata->sales - $reverseAmount)??"0.00")/100),2), 10, " ", STR_PAD_LEFT)); ?>' + '\x0A',

    'Branch ' +'<?php echo e(!empty($terminal->taxtype)?strtoupper($terminal->taxtype):"SST"); ?> <?php echo e($terminal->tax_percent??"6"); ?><?php echo e(str_pad("%",20," ",STR_PAD_RIGHT)); ?><?php echo e(str_pad(number_format(((($eoddetailsdata->sst - $reverseTax)??"0.00")/100),2), 13, " ", STR_PAD_LEFT)); ?>' + '\x0A',

//	'123456789012345678901234567890123456789012345'
    'Branch Rounding          ' +'<?php echo e(str_pad(number_format($round,2), 20, " ", STR_PAD_LEFT)); ?>' + '\x0A',

//	'123456789012345678901234567890123456789012345'
    '---------------------------------------------' + '\x0A',
//   '123456789012345678901234567890123456789012345'
	'\x1B' + '\x21' + '\x00',	// emphasized mode off
	'Today Sales                        '+ '<?php echo e(str_pad(number_format(((($eoddetailsdata->sales - $reverseAmount)??"0.00")/100),2), 10, " ", STR_PAD_LEFT)); ?>' + '\x0A',

    '<?php echo e(!empty($terminal->taxtype)?strtoupper($terminal->taxtype):"SST"); ?> <?php echo e($terminal->tax_percent??"6"); ?><?php echo e(str_pad("%",20," ",STR_PAD_RIGHT)); ?><?php echo e(str_pad(number_format(((($eoddetailsdata->sst - $reverseTax)??"0.00")/100),2), 20, " ", STR_PAD_LEFT)); ?>' + '\x0A',

    'Rounding                 ' +'<?php echo e(str_pad(number_format($round,2), 20, " ", STR_PAD_LEFT)); ?>' + '\x0A',

//	'123456789012345678901234567890123456789012345'
    '---------------------------------------------' + '\x0A',
//    '123456789012345678901234567890123456789012345'
	'\x1B' + '\x21' + '\x00',	// emphasized mode off

	'Cash                               '+ '<?php echo e(str_pad(number_format(((($eoddetailsdata->cash-$eoddetailsdata->cash_change) - $reverseCash??"0.00")/100),2), 10, " ", STR_PAD_LEFT)); ?>' + '\x0A',

    'Credit Card                        ' +'<?php echo e(str_pad(number_format(((($eoddetailsdata->creditcard - $reverseCard)??"0.00")/100),2), 10, " ", STR_PAD_LEFT)); ?>' + '\x0A',


	'Wallet                        ' +'<?php echo e(str_pad(number_format(((($eoddetailsdata->wallet - $reverseWallet)??"0.00")/100),2), 10, " ", STR_PAD_LEFT)); ?>' + '\x0A',


    'Outdoor Payment Terminal ' +'<?php echo e(str_pad(empty($opos_eoddetails->creditcard) ? "0.00":number_format(($opos_eoddetails->creditcard/100),2), 20, " ", STR_PAD_LEFT)); ?>' + '\x0A',

//	'123456789012345678901234567890123456789012345'
    <?php if($terminal_btype->btype??"" == 'petrol_station'): ?>
    '---------------------------------------------' + '\x0A',
//    '123456789012345678901234567890123456789012345'
    '\x1B' + '\x21' + '\x00',	// emphasized mode off

    'Trade Debtor     '+ '<?php echo e(str_pad(empty($company->currency->code) ? "MYR": $company->currency->code , 10, " ", STR_PAD_LEFT)); ?>' + '<?php echo e(str_pad("0.00" , 20, " ", STR_PAD_LEFT)); ?>'+'\x0A',

    'Cheque     '+ '<?php echo e(str_pad(empty($company->currency->code) ? "MYR": $company->currency->code , 20, " ", STR_PAD_LEFT)); ?>' + '<?php echo e(str_pad("0.00" , 20, " ", STR_PAD_LEFT)); ?>'+ '\x0A',

    'Manual OPT     '+ '<?php echo e(str_pad(empty($company->currency->code) ? "MYR": $company->currency->code , 20, " ", STR_PAD_LEFT)); ?>' + '<?php echo e(str_pad(number_format((@$OPT/100),2) , 20, " ", STR_PAD_LEFT)); ?>'+ '\x0A',

    'Fleet Card' +'<?php echo e(str_pad(number_format((($eoddetailsdata->rounding??"0.00")/100),2), 20, " ", STR_PAD_LEFT)); ?>' + '<?php echo e(str_pad("0.00" , 20, " ", STR_PAD_LEFT)); ?>'+ '\x0A',

    'Cash Card' +'<?php echo e(str_pad(number_format((($eoddetailsdata->rounding??"0.00")/100),2), 20, " ", STR_PAD_LEFT)); ?>' + '<?php echo e(str_pad("0.00" , 20, " ", STR_PAD_LEFT)); ?>'+ '\x0A',

    //'123456789012345678901234567890123456789012345'
    <?php endif; ?>
    '---------------------------------------------' + '\x0A',
    'Location                 '+'<?php echo e(str_pad($location->name??"", 20, " ", STR_PAD_LEFT)); ?>' + '\x0A',
    'Location ID              '+'<?php echo e(str_pad($location->systemid??"", 20, " ", STR_PAD_LEFT)); ?>' + '\x0A',
    'Terminal ID              '+'<?php echo e(str_pad($terminal->systemid??" ", 20, " ", STR_PAD_LEFT)); ?>' + '\x0A',
	'Staff Name               '+'<?php echo e(str_pad($user->fullname??" ", 20, " ", STR_PAD_LEFT)); ?>' + '\x0A',
	'Staff ID                 '+'<?php echo e(str_pad($user->systemid??" ", 20, " ", STR_PAD_LEFT)); ?>' +'\x0A',
	'\x1B' + '\x61' + '\x31',	// center align
	'Thank You!' + '\x0A',
    '\x0A',
	'\x1D' + '\x56'  + '\x01'	// partial cut (new syntax)
	];

	return qz.print(escconfig, escdata).catch(function(e) {
		console.log('ERROR qz.print()!!');
		console.error(e);
	});
}
<?php /**PATH D:\Server_Files\oceania\trunk\oceania\resources\views/printing/eod_template_escpos.blade.php ENDPATH**/ ?>