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

	@if (!empty($company->office_address))
		var str = "{{$company->office_address}}";

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
	@endif

	console.log('addy1='+addy1);
	console.log('addy2='+addy2);

	@php
        $today = date('Y-m-d');
        $recDate = \Carbon\Carbon::parse($eoddetailsdata->created_at)->toDateString();
    @endphp

	var escdata =  [
	'\x1B' + '\x40',          	// init
	'\x1D' + '\x4C' + '\x14' + '\x00',	// shift left margin
	'\x1B' + '\x61' + '\x31',	// center align
    '\x1B' + '\x21' + '\x08',	// emphasized mode on
	'{{!empty($company->name)?$company->name:""}}' + ' ({{!empty($company->business_reg_no)?$company->business_reg_no:""}})' + '\x0A',
	'\x1B' + '\x21' + '\x00',	// emphasized mode off
	'{{!empty($company->gst_vat_sst)? "(SST No. ".$company->gst_vat_sst.")" : ""}}' + '\x0A',
	((addy1 != '') ? addy1 : '') + '\x0A',
	((addy2 != '') ? addy2 : '') + '\x0A',
	'\x1B' + '\x61' + '\x30',	// left align
	'\x0A',
    '\x1B' + '\x21' + '\x08',	// emphasized mode on
    'End of The Day Summary    '+ '@if(!empty($recDate)) @if($today == $recDate) {{ \Carbon\Carbon::parse($eoddetailsdata->created_at)->format("dMy") }} {{ date("H:i:s") }}@else {{ \Carbon\Carbon::parse($eoddetailsdata->created_at)->format("dMy") }} 23:59:59 @endif @endif'+ '\x0A',
	'---------------------------------------------' + '\x0A',
	'                                       {{str_pad(empty($company->currency->code) ? "MYR": $company->currency->code, 5," ",STR_PAD_LEFT )}}' + '\x0A',
//
	'---------------------------------------------' + '\x0A',
//	'123456789012345678901234567890123456789012345'
	'\x1B' + '\x21' + '\x00',	// emphasized mode off
	'Branch Sales                       '+ '{{str_pad(number_format(((($eoddetailsdata->sales - $reverseAmount)??"0.00")/100),2), 10, " ", STR_PAD_LEFT)}}' + '\x0A',

    'Branch ' +'{{!empty($terminal->taxtype)?strtoupper($terminal->taxtype):"SST"}} {{ $terminal->tax_percent??"6"}}{{str_pad("%",20," ",STR_PAD_RIGHT)}}{{str_pad(number_format(((($eoddetailsdata->sst - $reverseTax)??"0.00")/100),2), 13, " ", STR_PAD_LEFT)}}' + '\x0A',

//	'123456789012345678901234567890123456789012345'
    'Branch Rounding          ' +'{{str_pad(number_format($round,2), 20, " ", STR_PAD_LEFT)}}' + '\x0A',

//	'123456789012345678901234567890123456789012345'
    '---------------------------------------------' + '\x0A',
//   '123456789012345678901234567890123456789012345'
	'\x1B' + '\x21' + '\x00',	// emphasized mode off
	'Today Sales                        '+ '{{str_pad(number_format(((($eoddetailsdata->sales - $reverseAmount)??"0.00")/100),2), 10, " ", STR_PAD_LEFT)}}' + '\x0A',

    '{{!empty($terminal->taxtype)?strtoupper($terminal->taxtype):"SST"}} {{ $terminal->tax_percent??"6"}}{{str_pad("%",20," ",STR_PAD_RIGHT)}}{{str_pad(number_format(((($eoddetailsdata->sst - $reverseTax)??"0.00")/100),2), 20, " ", STR_PAD_LEFT)}}' + '\x0A',

    'Rounding                 ' +'{{str_pad(number_format($round,2), 20, " ", STR_PAD_LEFT)}}' + '\x0A',

//	'123456789012345678901234567890123456789012345'
    '---------------------------------------------' + '\x0A',
//    '123456789012345678901234567890123456789012345'
	'\x1B' + '\x21' + '\x00',	// emphasized mode off

	'Cash                               '+ '{{str_pad(number_format(((($eoddetailsdata->cash-$eoddetailsdata->cash_change) - $reverseCash??"0.00")/100),2), 10, " ", STR_PAD_LEFT)}}' + '\x0A',

    'Credit Card                        ' +'{{str_pad(number_format(((($eoddetailsdata->creditcard - $reverseCard)??"0.00")/100),2), 10, " ", STR_PAD_LEFT)}}' + '\x0A',

    'Outdoor Payment Terminal ' +'{{str_pad(empty($opos_eoddetails->creditcard) ? "0.00":number_format(($opos_eoddetails->creditcard/100),2), 20, " ", STR_PAD_LEFT)}}' + '\x0A',

//	'123456789012345678901234567890123456789012345'
    @if($terminal_btype->btype??"" == 'petrol_station')
    '---------------------------------------------' + '\x0A',
//    '123456789012345678901234567890123456789012345'
    '\x1B' + '\x21' + '\x00',	// emphasized mode off

    'Trade Debtor     '+ '{{str_pad(empty($company->currency->code) ? "MYR": $company->currency->code , 10, " ", STR_PAD_LEFT)}}' + '{{str_pad("0.00" , 20, " ", STR_PAD_LEFT)}}'+'\x0A',

    'Cheque     '+ '{{str_pad(empty($company->currency->code) ? "MYR": $company->currency->code , 20, " ", STR_PAD_LEFT)}}' + '{{str_pad("0.00" , 20, " ", STR_PAD_LEFT)}}'+ '\x0A',

    'Manual OPT     '+ '{{str_pad(empty($company->currency->code) ? "MYR": $company->currency->code , 20, " ", STR_PAD_LEFT)}}' + '{{str_pad(number_format((@$OPT/100),2) , 20, " ", STR_PAD_LEFT)}}'+ '\x0A',

    'Fleet Card' +'{{str_pad(number_format((($eoddetailsdata->rounding??"0.00")/100),2), 20, " ", STR_PAD_LEFT)}}' + '{{str_pad("0.00" , 20, " ", STR_PAD_LEFT)}}'+ '\x0A',

    'Cash Card' +'{{str_pad(number_format((($eoddetailsdata->rounding??"0.00")/100),2), 20, " ", STR_PAD_LEFT)}}' + '{{str_pad("0.00" , 20, " ", STR_PAD_LEFT)}}'+ '\x0A',

    //'123456789012345678901234567890123456789012345'
    @endif
    '---------------------------------------------' + '\x0A',
    'Location                 '+'{{str_pad($location->name??"", 20, " ", STR_PAD_LEFT)}}' + '\x0A',
    'Location ID              '+'{{str_pad($location->systemid??"", 20, " ", STR_PAD_LEFT)}}' + '\x0A',
    'Terminal ID              '+'{{str_pad($terminal->systemid??" ", 20, " ", STR_PAD_LEFT)}}' + '\x0A',
	'Staff Name               '+'{{str_pad($user->fullname??" ", 20, " ", STR_PAD_LEFT)}}' + '\x0A',
	'Staff ID                 '+'{{str_pad($user->systemid??" ", 20, " ", STR_PAD_LEFT)}}' +'\x0A',
	'\x1B' + '\x61' + '\x31',	// center align
	'Thank You!' + '\x0A',
	'\x1B' + '\x61' + '\x30',	// left align
    '---------------------------------------------' + '\x0A',
    '\x1B' + '\x21' + '\x08',	// emphasized mode on
    'Refund '+'\x0A',
	'\x1B' + '\x21' + '\x00',	// emphasized mode off
    'Branch Refund            '+'{{str_pad(number_format($refundAmount,2), 20, " ", STR_PAD_LEFT)}}'+'\x0A',
    'Tax                      '+'{{str_pad(number_format($refund_sst,2), 20, " ", STR_PAD_LEFT)}}'+'\x0A',
    'Rounding                 '+'{{str_pad(number_format($refund_round,2), 20, " ", STR_PAD_LEFT)}}'+'\x0A',

    '\x0A',
	'\x1D' + '\x56'  + '\x01'	// partial cut (new syntax)
	];

	return qz.print(escconfig, escdata).catch(function(e) {
		console.log('ERROR qz.print()!!');
		console.error(e);
	});
}
