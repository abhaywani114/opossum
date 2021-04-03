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
		console.log("Printing Using default")
		printer  = printdev;
	}

	<?php if($receipt->receipt_logo == '' ||
		 $receipt->receipt_logo =='Null'): ?>
		image_url = '';
	<?php else: ?>
		image_url = "<?php echo e(asset('images/company/'.$company->id.'/corporate_logo/'.$receipt->receipt_logo)); ?>";
	<?php endif; ?>

	console.log('image_url='+image_url);
	//http://ocosystem/images/opos_terminal/27/1566554765.JPG

  	var imgconfig = qz.configs.create(printer, {
		rasterize:false,
		altPrinting:true,
		//units: "mm",
		//density: "300",
	});

	//barcode data
	var code = '<?php echo e($receipt->systemid); ?>';
	//var base64 = textToBase64Barcode(code);
	//console.log(base64);

	//var barcodeImg = base64.split(",")[1];

	//convenience method
	var chr = function(n) { return String.fromCharCode(n); };

	var barcode = '\x1D' + 'h' + chr(100) +		//barcode height
		'\x1D' + 'f' + chr(2) +					//font for printed number
		'\x1D' + 'k' + chr(70) + chr(code.length) + code + chr(0); //72=code93, 69=code39

   	var escconfig = qz.configs.create(printer, {
		encoding:'GBK',			// Ronta RP326
		//rasterize:false,
		altPrinting:true,
	});

	var addy1 = '';
	var addy2 = '';

	<?php if(!empty($receipt->receipt_address)): ?>
		var str = "<?php echo e($receipt->receipt_address); ?>";

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

	var escdata =  [
	'\x1B' + '\x40',          	// init
	'\x1D' + '\x4C' + '\x14' + '\x00',	// shift left margin
	'\x1B' + '\x61' + '\x31',	// center align
	<?php if(!empty($receipt->receipt_logo)): ?>
	{ type:'raw', format:'image', flavor:'file', data: image_url, options:{language:'escp', dotDensity:'double'}},
	<?php endif; ?>
	'\x1B' + '\x21' + '\x08',	// emphasized mode on
	'<?php echo e(!empty($company->name)?$company->name:""); ?>' + ' (<?php echo e(!empty($company->business_reg_no)?$company->business_reg_no:""); ?>)' + '\x0A',
	'\x1B' + '\x21' + '\x00',	// emphasized mode off
	'<?php echo e(!empty($company->gst_vat_sst)? "(SST No. ".$company->gst_vat_sst.")" : ""); ?>' + '\x0A',
	((addy1 != '') ? addy1 : '') + '\x0A',
	((addy2 != '') ? addy2 : '') + '\x0A',
	'\x1B' + '\x61' + '\x30',	// left align
	'\x1B' + '\x21' + '\x08',	// emphasized mode on
	'---------------------------------------------' + '\x0A',
	'Description           Qty  Price  Disc   <?php echo e(empty($receipt->currency) ? "MYR": $receipt->currency); ?>' + '\x0A',
//	'                    999.99 99.99 100% 9999.99'
	'---------------------------------------------' + '\x0A',
//	'123456789012345678901234567890123456789012345'
	'\x1B' + '\x21' + '\x00',	// emphasized mode off
	// We need 4 tabstops: 21th, 28th, 34rd, 39th
	'\x1B' + '\x44' + '\x15' + '\x1C' + '\x22' + '\x28' + '\x00',
	<?php $__currentLoopData = $receiptproduct; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		'<?php echo e((strlen($i->name) > 18) ? substr($i->name,0,18) : str_pad($i->name, 18, " ", STR_PAD_RIGHT)); ?>' + '\x09' +
		'<?php echo e(str_pad(number_format($i->quantity,2), 6, " ", STR_PAD_LEFT)); ?>' + '\x09' +
		'<?php echo e(str_pad(number_format(($i->price/100),2), 5, " ", STR_PAD_LEFT)); ?>' + '\x09' +
		'<?php echo e(str_pad($i->discount."%", 4, " ", STR_PAD_LEFT)); ?>' + '\x09' +
		'<?php echo e(str_pad(number_format((($receipt->cash_received/100-$receipt->cash_change/100)+((5 * round(($receipt->cash_received-$receipt->cash_change) / 5))-($receipt->cash_received-$receipt->cash_change))/100??"2"),2), 7, " ", STR_PAD_LEFT)); ?>' + '\x0A',
	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
//	'123456789012345678901234567890123456789012345'
	'---------------------------------------------' + '\x0A',
	'Item Amount                        '+ '<?php echo e(str_pad(number_format((($receipt->cash_received/100-$receipt->cash_change/100)/(1+($terminal->tax_percent/100))),2), 10, " ", STR_PAD_LEFT)); ?>' + '\x0A',

	'<?php echo e(!empty($terminal->taxtype)?strtoupper($terminal->taxtype):"SST"); ?> <?php echo e($terminal->tax_percent); ?><?php echo e(str_pad("%",20," ",STR_PAD_RIGHT)); ?><?php echo e(str_pad(number_format((($receipt->cash_received/100-$receipt->cash_change/100)-(($receipt->cash_received/100-$receipt->cash_change/100)/(1+($terminal->tax_percent/100)))),2), 20, " ", STR_PAD_LEFT)); ?>' + '\x0A',

//	'123456789012345678901234567890123456789012345'
	'<?php echo e(str_pad("Rounding",25," ",STR_PAD_RIGHT)); ?>' + '<?php echo e(str_pad(($receipt->cash_received-$receipt->cash_change)%5==0?"0.00":((5 * round(($receipt->cash_received-$receipt->cash_change) / 5))-($receipt->cash_received-$receipt->cash_change))/100, 20, " ", STR_PAD_LEFT)); ?>' + '\x0A',
	'---------------------------------------------' + '\x0A',
	'\x1B' + '\x21' + '\x08',	// emphasized mode on
	'Total                    '+'<?php echo e(str_pad(number_format((($receipt->cash_received/100-$receipt->cash_change/100)+((5 * round(($receipt->cash_received-$receipt->cash_change) / 5))-($receipt->cash_received-$receipt->cash_change))/100??"2"),2), 20, " ", STR_PAD_LEFT)); ?>' + '\x0A',
	'\x1B' + '\x21' + '\x00',	// emphasized mode off

//	'123456789012345678901234567890123456789012345'
	'Cash Received            '+<?php if($receipt->payment_type == "cash"): ?>'<?php echo e(str_pad(!empty($receipt->cash_received)?number_format(($receipt->cash_received/100),2):"0.00", 20, " ", STR_PAD_LEFT)); ?>'<?php else: ?>'<?php echo e(str_pad("0.00", 20, " ", STR_PAD_LEFT)); ?>'<?php endif; ?> + '\x0A',

	'Credit Card              '+ <?php if($receipt->payment_type == "creditcard"): ?>'<?php echo e(str_pad(!empty($receipt->cash_received)?number_format((($receipt->cash_received/100)+((5 * round(($receipt->cash_received-$receipt->cash_change) / 5))-($receipt->cash_received-$receipt->cash_change))/100),2):"0", 20, " ", STR_PAD_LEFT)); ?>' <?php else: ?>'<?php echo e(str_pad("0.00", 20, " ", STR_PAD_LEFT)); ?>' <?php endif; ?> + '\x0A',

//	'123456789012345678901234567890123456789012345'
	'Wallet                   '+ <?php if($receipt->payment_type == "wallet"): ?>'<?php echo e(str_pad(!empty($receipt->cash_received)?number_format((($receipt->cash_received/100)+((5 * round(($receipt->cash_received-$receipt->cash_change) / 5))-($receipt->cash_received-$receipt->cash_change))/100),2):"0", 20, " ", STR_PAD_LEFT)); ?>' <?php else: ?>'<?php echo e(str_pad("0.00", 20, " ", STR_PAD_LEFT)); ?>' <?php endif; ?> + '\x0A',

	'---------------------------------------------' + '\x0A',
	'Change                   '+'<?php echo e(str_pad(!empty($receipt->cash_change)?number_format((($receipt->cash_change/100)-((5 * round(($receipt->cash_received-$receipt->cash_change) / 5))-($receipt->cash_received-$receipt->cash_change))/100),2):"0.00", 20, " ", STR_PAD_LEFT)); ?>' + '\x0A',

    '---------------------------------------------' + '\x0A',
    'Receipt No.              '+'<?php echo e(str_pad(!empty($receipt->systemid)?$receipt->systemid:"7060000010000000014", 20, " ", STR_PAD_LEFT)); ?>' + '\x0A',
    'Location                 '+'<?php echo e(str_pad($location->name??"", 20, " ", STR_PAD_LEFT)); ?>' + '\x0A',
    'Terminal ID              '+'<?php echo e(str_pad($terminal->systemid??'', 20, " ", STR_PAD_LEFT)); ?>' + '\x0A',
	'Staff Name               '+'<?php echo e(str_pad($user->fullname??'', 20, " ", STR_PAD_LEFT)); ?>' + '\x0A',
	'Staff ID                 '+'<?php echo e(str_pad($user->systemid??'', 20, " ", STR_PAD_LEFT)); ?>' +'\x0A',
	'Date                     '+'<?php echo e(str_pad(date("dMy H:i:s", strtotime($receipt->created_at??'')), 20, " ", STR_PAD_LEFT)); ?>' +'\x0A',
	'Pump No.                 '+'<?php echo e(str_pad(!empty($receipt->pump_no)?$receipt->pump_no:"15", 20, " ", STR_PAD_LEFT)); ?>' + '\x0A',
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
<?php /**PATH D:\Server_Files\oceania\trunk\oceania\resources\views/printing/receipt_template_escpos.blade.php ENDPATH**/ ?>