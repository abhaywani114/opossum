<!-- FUEL PAGE CODE BEGIN -->
<script>
var access_code = "";
var usersystemid = "";
var username = "";
var keys = [];
var index = 0;
var pumpData = {};
var selected_pump = 0;
var payment_type = "";
var dis_cash = {};
var reset = {};
var receipt_id = 0;


for (i = 1; i <= <?php echo e(env('MAX_PUMPS')); ?>; i++) {
    pumpData['pump'+i] = {
		status:			'Offline',
		volume:			"0.00",
		amount:			'0.00',
		price:			'0.00',
		price_liter:	'0.00',
		dose:			'0.00',
		nozzle:			'',
		product:		'',
        product_id:		'',
		product_thumbnail: '',
		receipt_id: '',
		auth_id: '',
		preset_type: 'amount',
		paymentStatus:	'Not Paid',
		payment_type:	'Prepaid',
		isNozzleUp:	false,
		isAuth: false,
		is_slave: false
	};
}


for (i = 1; i <= <?php echo e(env('MAX_PUMPS')); ?>; i++) {
    dis_cash['pump'+i] = {
        dis_cash:		'',
		payment_type:	'',
   };
}


for (i = 1; i <= <?php echo e(env('MAX_PUMPS')); ?>; i++) {
    reset['pump'+i] = {
        status:		'',
		reset:	false,
   };
}


var authorizeData = {};
{
    for (i = 1; i <= <?php echo e(env('MAX_PUMPS')); ?>; i++) {
		authorizeData['pump'+i] = {
			transactionid:	'',
			volume:			"0.00",
			amount:			'0.00',
			price:			'0.00',
			nozzle:			'',
			product:		'',
			product_thumbnail: '',
			paymentStatus:	'Not Paid'
		};
	}
}


function messageModal(msg)
{
	$('#modalMessage').modal('show');
	$('#statusModalLabelMsg').html(msg);
	setTimeout(function(){
		$('#modalMessage').modal('hide');
	}, 2500);
}


window.addEventListener("keydown",function(e){
	if(e.keyCode != 17  && e.keyCode != 16 &&
		e.keyCode != 18 && e.keyCode != 20 ){
		if(e.keyCode != 13){
			keys[index++] = e.key;
		} else{
			scan_done();
		}
	}
}, false);


function login_form_toggle() {
	disp = $("#login_form").css('display');

	if (disp == 'block')
		$("#login_form").css('display','none');
	else
		$("#login_form").css('display','block');
}


function login_me() {
    hosting     = $("#hosting").val();
	email 		= $("#email").val();
	password	= $("#password").val();
	$.ajax({
		url: "<?php echo e(route('uPLogin')); ?>",
		type: "POST",
		data: {
            hosting:hosting,
			email:email,
			<?php if(!empty($ONLY_ONE_HOST)): ?>
			ONLY_ONE_HOST:'ONLY_ONE_HOST',
			<?php endif; ?>
			password:password
		},
		'headers': {
			'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
		},
		success: function (response) {
			if (response.landing != undefined) {
				window.location = response.landing;
			} else {
				$(".login_error").html(response.login_error);
				setInterval(function(){
					$(".login_error").html('');
				},6000);
			}
		},
		error: function (resp) {
		}
	});
}


function scan_done() {
	var b = keys.join('');
    access_code = b.toString();
    b = "";
    keys.length = 0
    $.ajax({
		url: "/authorizeDriver",
		type: "POST",
		data: {access_code: access_code},
		'headers': {
			'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
		},
		success: function (response) {
			if (response != null && typeof response != 'undefined' && response.systemid){
			usersystemid = response.systemid;
			username = response.fullname;
			console.log(response);
			$('#login-message').html(usersystemid+' is successfully authenticated');
			for (i = 1; i <= <?php echo e(env('MAX_PUMPS')); ?>; i++) {
				pumpData['pump'+i] = {
					status:'Offline',
					amount:'0.00',
					dose:'0.00',
				};
				$("#volume-liter-"+i).sevenSeg({
					digits: 7,
					value: "0.00",
				});
				$("#volume-iter-"+i).sevenSeg("destroy");
			}
			$('#user-id').text(usersystemid);
			$('#user-name').text(username);
			$('#pump-main-block-'+selected_pump).hide();

			$('#pump-main-block-0').show();
			selected_pump = 0;

			$('#authorize-button').attr('class', '');
			$('#authorize-button').addClass('btn poa-authorize-disabled');
			$('.button-number-amount').addClass('poa-button-number-disabled');
			$('.button-number-amount').removeClass('poa-button-number');
			setTimeout(function(){
				$('#userEditModal').modal('hide');
				$('#container-blur').css('opacity',' 1');
				window.location.reload();
				}, 3000);
			}
		},
		error: function (e) {
			console.log('error', e);
		}
	});
}


$(document).ready(function(){
	var sInterval = null;

    <?php if(auth()->check()): ?>

        $('#userEditModal').modal('hide');
        $('#container-blur').css('opacity','1');

        $('#pump-main-block-0').show();
        usersystemid = "<?php echo e(Auth::user()->systemid); ?>";
        username = "<?php echo e(Auth::user()->fullname); ?>";

		// Only activate punp-get-status AFTER logging in
		<?php if(env('PUMP_HARDWARE') != NULL && !empty(Auth::user())): ?>
			sInterval = setInterval(function(){

				getTerminalSyncData();
				mainGetStatus();
            }, 2000);

			/*
			// Getting master detection results
			log2laravel('info', selected_pump +
				': getTerminalSyncData: isAuth  =' +
				pumpData[`pump${selected_pump}`].isAuth);

			// Getting slave detection results
			log2laravel('info', selected_pump +
				': getTerminalSyncData: is_slave=' +
				pumpData[`pump${selected_pump}`].is_slave);
			*/
		<?php endif; ?>

    <?php else: ?>
		clearInterval(sInterval);
		$('#userEditModal').modal({backdrop: 'static', keyboard: false})
		$('#userEditModal').modal('show');
		$('#container-blur').css('opacity',' 0.25');
    <?php endif; ?>
});


var colorScheme = {
	colorOff: "#f0f0f0",
	colorOn: "#505050",
	colorBackground: "#f0f0f0",
	decimalPlaces: 2,
	slant: 5
};


function back() {
    var teamp = 0;
    for (i = 1; i <= <?php echo e(env('MAX_PUMPS')); ?>; i++) {
        if(pumpData['pump'+i].status == "Delivering"){
            teamp = 1;
        }
    }
    if(teamp == 0){
        access_code = "";
        usersystemid = "";
        username = "";
        //$('#driverfuelledger').modal('hide');
        $('#login-message').html('');
        $('#userEditModal').modal({backdrop: 'static', keyboard: false})
	    $('#userEditModal').modal('show');
	    $('#container-blur').css('opacity',' 0.25');
	    keys.length = 0
        index = 0;
        var ipaddr = "<?php echo e(env('PTS_IPADDR')); ?>";
        for (i = 1; i <= <?php echo e(env('MAX_PUMPS')); ?>; i++) {
            pumpData['pump'+i] = {
                status:'Offline',
                amount:'0.00',
                dose:'0.00',
				price:'0.00',
            };
            $("#volume-liter-"+i).sevenSeg("destroy");
			$.ajax({
				url: '/pump-cancel-authorize/' + i + '/' + ipaddr,
				type: "GET",
				dataType: "JSON",
				success: function (resp) {
				},
				error: function (resp) {
					console.log('ERROR: ' + JSON.stringify(resp));
				}
			});
        }

        $('#pump-main-block-'+selected_pump).hide();
        $('#pump-main-block-0').show();
        selected_pump = 0;
        $('#authorize-button').attr('class', '');
        $('#authorize-button').addClass('btn poa-authorize-disabled');
        $('.button-number-amount').addClass('poa-button-number-disabled');
        $('.button-number-amount').removeClass('poa-button-number');
    }else{
        messageModal("Delivering is in process, log out is not successful.");
    }
}


var isClickProcessEnter = false;
function process_finish()
{
	if (isClickProcessEnter == true)
		return
	isClickProcessEnter = true

    numpad_disable();
    disable_payment_btns()
	
	
    $("#payment-type-paid-right"+selected_pump).html("");
    $("#payment-type-amount"+selected_pump).html("");
    $("#payment-amount-card-amount"+selected_pump).html("");
    $("#payment-type-amount"+selected_pump).html("");
	$("#payment-type-message"+selected_pump).html('');
	$("#payment-type-amount"+selected_pump).html('');
    // $('.numpad-cancel-payment'+selected_pump).removeClass('poa-button-number-payment-cancel-disabled');
    // $('.numpad-cancel-payment'+selected_pump).addClass('poa-button-number-payment-cancel');
    pumpData['pump'+selected_pump].paymentStatus = "Paid";
    // pumpData['pump' + selected_pump].product = "";
    if (dis_cash['pump'+selected_pump].payment_type == "cash"){
		$("#payment-value"+selected_pump).css("color","#a0a0a0");
		$("#payment-value"+selected_pump).html("Cash Received");
		$("#payment-div-cash"+selected_pump+" div").addClass("justify-content-center");
    } else if (dis_cash['pump'+selected_pump].payment_type == "card"){
        $(".payment-div-card"+selected_pump).hide();
        $("#payment-div-cash"+selected_pump).show();
        $("#payment-value"+selected_pump).css("color","#a0a0a0");
		$("#payment-value"+selected_pump).html("Cash Received");
		$("#payment-div-cash"+selected_pump+" div").addClass("justify-content-center");
		$("#payment-value-card"+selected_pump).html("");
    }
    dis_cash_ = (parseFloat(dis_cash['pump'+selected_pump].dis_cash)/100).toFixed(2);

    if( pumpData['pump'+selected_pump].payment_type == "Postpaid"){
		var amount_total = pumpData['pump'+selected_pump].amount;
		// amount_total = ((5 * Math.round((amount_total*100) / 5))/100).toFixed(2);
		var dose =  amount_total;
		var change_amount = dis_cash_ - amount_total;
		amount_total = ((5 * Math.round((amount_total*100) / 5))/100).toFixed(2);
		pumpData['pump'+selected_pump].isAuth = false;

	 } else {
		var dose = $(`#total_amount-main-${selected_pump}`).text().replace(/,/g,''); 
	
		if(dis_cash['pump'+selected_pump].payment_type == "cash"){
			if (pumpData['pump'+selected_pump].preset_type == 'Litre') {
				var change_amount = dis_cash_ - parseFloat($("#total_amount-main-"+selected_pump).text().replace(/,/g,''));
			} else {
				var change_amount = dis_cash_ - pumpData['pump'+selected_pump].dose;
			}
		}
	}

	var filled = parseFloat(pumpData['pump'+selected_pump].amount);
	if( filled == dose){
		reset['pump'+selected_pump].reset = true;
	}

	var qty = pumpData['pump'+selected_pump].volume;
	var price = pumpData['pump'+selected_pump].price;
	var payment_type =  dis_cash['pump'+selected_pump].payment_type;
	var creditcard_no = "";
	var cash_received = 0.00;
	var product_id = pumpData['pump' + selected_pump].product_id;
	var product = pumpData['pump' + selected_pump].product;
	var auth_id = pumpData['pump' + selected_pump].auth_id;

	if(payment_type == "card"){
		creditcard_no = dis_cash['pump'+selected_pump].dis_cash;
	}else{
		cash_received =  (parseFloat(dis_cash['pump'+selected_pump].dis_cash)/100).toFixed(2);
	}

		qty = 0 ;
		if(price > 0){
			qty =parseFloat(pumpData['pump'+selected_pump].dose) / price;
			qty = qty.toFixed(2);
		}

		sum_of_raw_amount = parseFloat(pumpData['pump'+selected_pump].dose);
	
		sst = 0.00;
		item_amount = 0.00;

		sum_of_raw_amount = parseFloat(pumpData['pump'+selected_pump].dose);
		var amount_total = ((5 * Math.round((parseFloat(sum_of_raw_amount) * 100) / 5)) / 100);
		sst = parseFloat(sst) + parseFloat((sum_of_raw_amount) - ((sum_of_raw_amount) / (1 + (<?php echo e($terminal->tax_percent); ?>/100))));
		item_amount = parseFloat(sum_of_raw_amount) - parseFloat(((sum_of_raw_amount) - ((sum_of_raw_amount) / (1 + (<?php echo e($terminal->tax_percent); ?>/100)))));
		rounding = parseFloat(amount_total) - parseFloat(sum_of_raw_amount);
		rounding = parseFloat(rounding.toFixed(2));
		item_amount = item_amount.toFixed(2);
	var data = {
		"pump_no":selected_pump,
		"dose":dose,
		"cash_received":cash_received,
		"change_amount":change_amount,
		"payment_type":payment_type,
		"terminal_id":<?php echo $terminal->id??"''"; ?>,
		"tax_percent":<?php echo $terminal->tax_percent??"''"; ?>,
		"creditcard_no":creditcard_no,
		"company_id":<?php echo $company->id??"''"; ?>,
		"currency":"<?php echo e($company->currency->code??''); ?>",
		"mode":"<?php echo e($terminal->mode??''); ?>",
		"product_id":product_id,
		"product":product,
		"sst":sst.toFixed(2),
		"qty":qty,
		"price":price,
		"receipt_id":0,
		"item_amount":item_amount,
		"cal_rounding":rounding,
		"auth_id":auth_id,
	};

	'use strict';
	let _this = this;
	$.ajax({
        url: "<?php echo e(route('create-fuel-list')); ?>",
        type: 'post',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        data: data,
		dataType: 'json',
        success: function(response){
			console.log('PR fuel.receipt.create:');
			console.log('PR ***** SUCCESS *****');
			console.log('response='+JSON.stringify(response));
			//my ESCPOS printing function
			// data.receipt_id = response;
			// console.log('data='+JSON.stringify(data));
            receipt_id = data.receipt_id;

			// // Save receipt_id in pumpData[]
			pumpData['pump'+selected_pump].receipt_id = receipt_id;
			$(`#payment-status-${selected_pump}`).html('Paid');
			
			/* Need to have Qz.io running, otherwise print_receipt()
			 * will bomb out and will not execute lines after it. We
			 * trap the error so that we can still run even if Qz is
			 * NOT running!! */
			try {
				// Output receipt via thermal printer
				// DON'T COMMENT THIS!!! IT IS BEING USED IN PRODUCTION!!
				print_receipt(response);
				
				// Open cash drawer
				// DON'T COMMENT THIS!!! IT IS BEING USED IN PRODUCTION!!
				open_cashdrawer();

			} catch (error) {
				/* This will catch if Qz.io is not being run!! */
				//alert('ERROR! print_receipt(). Check Qz!!');
				//alert("ERROR print_receipt(): " + JSON.stringify(error));
				console.error("ERROR: "+ JSON.stringify(error));
			}
        },
        error: function(response){
			console.log('PR fuel.receipt.create:');
			console.log('PR ***** ERROR *****');
			console.log(JSON.stringify(response));
        }
	});
	
	pump_authorize(selected_pump, product_id);
	reset['pump'+selected_pump].reset = true;
	isClickProcessEnter = false;
	
	// NOTE: This is where you execute mainGetStatus()
}


function update_payment_table(pump_no, amount = false) {
	sum_of_raw_amount = parseFloat(pumpData['pump'+pump_no].dose);
	
	if (amount != false)
		sum_of_raw_amount = parseFloat(amount);

	sst = 0.00;
	item_amount = 0.00;
	//sum_of_raw_amount = 0.00;
	
	var amount_total = ((5 * Math.round((parseFloat(sum_of_raw_amount) * 100) / 5)) / 100);
	sst = parseFloat(sst) + parseFloat((sum_of_raw_amount) - ((sum_of_raw_amount) / (1 + (<?php echo e($terminal->tax_percent); ?>/100))));
	item_amount = parseFloat(sum_of_raw_amount) - parseFloat(((sum_of_raw_amount) - ((sum_of_raw_amount) / (1 + (<?php echo e($terminal->tax_percent); ?>/100)))));
	rounding = parseFloat(amount_total) - parseFloat(sum_of_raw_amount);
	rounding = parseFloat(rounding.toFixed(2));

	$(`#table-MYR-${pump_no}`).text(sum_of_raw_amount.toFixed(2))
	$(`#item-amount-calculated-${pump_no}`).text(item_amount.toFixed(2));
	$(`#sst-val-calculated-${pump_no}`).text(sst.toFixed(2));
	$(`#rounding-val-calculated-${pump_no}`).text(rounding.toFixed(2));
	$(`#grand-total-val-calculated-${pump_no}`).text((sum_of_raw_amount+parseFloat(rounding)).toFixed(2));
	
	// pumpData['pump' + pump_no].price = 20;

	// console.log("price = "+pumpData['pump' + pump_no].price);
	// console.log("itemamount = "+item_amount);
	log2laravel('info', 'update_payment_table pump:'+
		JSON.stringify(pumpData['pump' + pump_no])+'');
	
	//if (pumpData['pump' + pump_no].product) {
		log2laravel('info', 'updatepaymenttable price:'+
			pumpData['pump' + pump_no].price+'');

		$(`#table-PRODUCT-${pump_no}`).text(pumpData['pump' + pump_no].product);
		price = parseFloat(pumpData['pump' + pump_no].price);
		$(`#table-PRICE-${pump_no}`).text(price.toFixed(2))
		//console.log(sum_of_raw_amount);
		if(price > 0){
			qty =parseFloat(sum_of_raw_amount) / price;
			qty = qty.toFixed(2);
			$(`#table-QTY-${pump_no}`).text(qty)
		}else{	
			$(`#table-QTY-${pump_no}`).text('0.00')
		}
			

	// } else {
	// 	console.log("correct2");
	// 	$(`#table-PRODUCT-${pump_no}`).text('');
	// }

}


function getPumpStatus(my_pump, insert_filled=true) {
    var ipaddr = get_hardwareip(my_pump, false);
	//console.log('my_pump='+my_pump+', ipaddr='+ipaddr);

	$.ajax({
		url: '/pump-get-status/' + my_pump + '/' + ipaddr,
		type: "GET",
		dataType: "JSON",
		success: function (resp) {

		log2laravel('info', 'getPumpStatus 0: '+ JSON.stringify(resp));

		if ((resp != null) && (typeof resp != 'undefined')) {
			resp = resp.data;

			
			log2laravel('info', 'getPumpStatus 1: '+ JSON.stringify(resp));
			log2laravel('info', 'getPumpStatus 1.1: '+ JSON.stringify(resp.response));
			

			if ((typeof resp.response != 'undefined') &&
				(resp.response != null)) {
				var response = resp.response;

				log2laravel('info', 'getPumpStatus 2: '+ JSON.stringify(response));
				log2laravel('info', 'getPumpStatus 2.1: '+ JSON.stringify(response.Packets));
				log2laravel('info', 'getPumpStatus 2.2: '+ JSON.stringify(response.Packets[0]));
				log2laravel('info', 'getPumpStatus 2.3: '+ (typeof response.Packets));
				log2laravel('info', 'getPumpStatus 2.4: '+ (typeof response.Packets[0]));
				log2laravel('info', 'getPumpStatus 2.5: '+ JSON.stringify(response.Packets));
				log2laravel('info', 'getPumpStatus 2.6: '+ JSON.stringify(response.Packets[0]));
			
				if ((typeof response.Packets != 'undefined') &&
					(response.Packets != null) &&
					(typeof response.Packets[0] != 'undefined') &&
					(response.Packets[0] != null)) {

					
					log2laravel('info',
						'getPumpStatus 3: ***** Before End Transaction detection *****');
					

					var packet	= response.Packets[0];
					var pump_no	= packet.Data.Pump;
					var volume	= packet.Data.Volume;
					var price	= packet.Data.Price;
					var amount	= packet.Data.Amount;

			
					log2laravel('info', 'getPumpStatus 3.1: pump_no='+
						pump_no+', volume='+volume+
						', price='+price+', amount='+amount);
				

					var LastTransaction = packet.Data.LastTransaction;
					var LastVolume	= packet.Data.LastVolume;
					var LastPrice	= packet.Data.LastPrice;
					var LastAmount	= packet.Data.LastAmount;
					var LastNozzle	= packet.Data.LastNozzle;
					var tx_id		= authorizeData['pump' + my_pump].transactionid;


					log2laravel('info',
						'getPumpStatus 3.2: ***** BEFORE Transaction end detection *****');

					log2laravel('info','getPumpStatus 3.3: tx_id='+tx_id+', LastTransaction='+
						LastTransaction);

					log2laravel('info','getPumpStatus 3.4: LastAmount='+
						LastAmount+', LastVolume='+LastVolume+
						', LastPrice='+LastPrice+', LastNozzle='+LastNozzle+
						', LastTransaction='+LastTransaction);

					log2laravel('info','getPumpStatus 3.5: packet.Type='+ packet.Type);
					log2laravel('info','getPumpStatus 3.6: tx_id='+ tx_id);
					log2laravel('info','getPumpStatus 3.7: LastTransaction='+ LastTransaction);


					// Test whether transaction has ended
					if ((packet.Type == 'PumpIdleStatus') &&
						(tx_id != "") &&
						(tx_id != 0) &&
						(typeof LastTransaction != "undefined") &&
						(LastTransaction != 0) &&
						(LastTransaction == tx_id)) {

						pumpData['pump' + my_pump].amount = LastAmount;
						pumpData['pump' + my_pump].volume = LastVolume;
						pumpData['pump' + my_pump].price = LastPrice;
						pumpData['pump' + my_pump].nozzle = LastNozzle;


						log2laravel('info', my_pump +
							': getPumpStatus 4.0 ***** DETECTED Transaction End *****');

						log2laravel('info', my_pump +
							': getPumpStatus 4.1: dose='+
							pumpData['pump' + my_pump].dose);

						log2laravel('info', my_pump +
							': getPumpStatus 4.2: LastAmount='+
							LastAmount+', LastVolume='+LastVolume+
							', LastPrice='+LastPrice+', LastNozzle='+
							LastNozzle+ ', LastTransaction='+
							LastTransaction + ' insert_filled='+insert_filled);


						// Update sevenSeg displays
						$("#amount-myr-" + my_pump).sevenSeg({
							value: parseFloat(LastAmount).toFixed(2)
						});
						$("#volume-liter-" + my_pump).sevenSeg({
							value: parseFloat(LastVolume).toFixed(2)
						});
						$("#price-meter-" + my_pump).sevenSeg({
							value: parseFloat(LastPrice).toFixed(2)
						});
						//process_refund(my_pump);
						if (pumpData['pump' + my_pump].auth_id != '' &&
							insert_filled == true) {
							auth_id = pumpData['pump' + my_pump].auth_id;
							store_last_filled(auth_id, LastAmount)
						}
					}
				}
			}
		}
		},
		error: function (resp) {
			console.log('ERROR: ' + JSON.stringify(resp));
		}
	});
}


function removeRefund(){
    $("#dCalc").css('display','none');
}


function updatePumpStatus_old(type, my_pump) {
    pump_number_main = parseInt(selected_pump);

	if (type === 'PumpFillingStatus') {

		$('#pump-status-'+my_pump).text('Delivering');
		$('#pump-button-'+my_pump).attr('class', '');
		$('#pump-button-'+my_pump).addClass('btn poa-button-pump-delivering');
        if(pumpData['pump'+my_pump].status == 'Idle'){
            $('.numpad-cancel-payment'+my_pump).removeClass('poa-button-number-payment-cancel');
            $('.numpad-cancel-payment'+my_pump).addClass('poa-button-number-payment-cancel-disabled');
            $("#product-select-pump-"+my_pump).css('display','none');
			$("#payment-div-cash"+my_pump+" div").addClass("justify-content-center");
			$("#payment-value"+my_pump).html("Cash Received");
			$("#payment-value"+my_pump).css("color","#a0a0a0");
			$("#payment-div-cash-card"+my_pump).hide();
			$(".payment-div-refund"+my_pump).hide();
			$(".payment-div-card"+my_pump).hide();
			$("#payment-div-cash"+my_pump).show();
			$("#payment-type-message"+my_pump).html("");
			$("#payment-type-amount"+my_pump).html("");
			$("#payment-amount-card-amount"+my_pump).html("");
    		$("#payment-type-amount"+selected_pump).html("");
			numpad_disable();

			log2laravel('info', my_pump +
				": updatePumpStatus: paymentStatus=" +
				pumpData['pump'+my_pump].paymentStatus);

			if (pumpData['pump'+my_pump].paymentStatus == "Not Paid") {
				pumpData['pump'+my_pump].payment_type = "Postpaid";
			}

			log2laravel('info', my_pump +
				": updatePumpStatus: payment_type=" +
				pumpData['pump'+my_pump].payment_type);
	
		
			pumpData['pump'+my_pump].isNozzleUp = true;

			pumpData[`pump${my_pump}`].amount = '0.00';
			pumpData[`pump${my_pump}`].volume = '0.00';
			pumpData[`pump${my_pump}`].price  = '0.00';
			$("#amount-myr-"  +my_pump).sevenSeg("destroy");
			$("#volume-liter-"+my_pump).sevenSeg("destroy");
			$("#price-meter-" +my_pump).sevenSeg("destroy");

        }

        if(my_pump == pump_number_main){
            $('#pump-status-main-'+pump_number_main).text("Delivering");

			var volume = parseFloat(pumpData['pump'+my_pump].volume).toFixed(2);
            $("#volume-liter-"+my_pump).sevenSeg({
				digits: 7,
				value: volume,
				colorOff: colorScheme.colorOff,
				colorOn: colorScheme.colorOn,
				colorBackground: colorScheme.colorBackground,
				slant: colorScheme.slant,
				decimalPlaces: colorScheme.decimalPlaces
			});

			var price = parseFloat(pumpData['pump'+my_pump].price).toFixed(2);
            $("#price-meter-"+my_pump).sevenSeg({
				digits: 7,
				value: price,
				colorOff: colorScheme.colorOff,
				colorOn: colorScheme.colorOn,
				colorBackground: colorScheme.colorBackground,
				slant: colorScheme.slant,
				decimalPlaces: colorScheme.decimalPlaces
			});

			var amount = parseFloat(pumpData['pump'+my_pump].amount).toFixed(2);
            $("#amount-myr-"+my_pump).sevenSeg({
				digits: 7,
				value: amount,
				colorOff: colorScheme.colorOff,
				colorOn: colorScheme.colorOn,
				colorBackground: colorScheme.colorBackground,
				slant: colorScheme.slant,
				decimalPlaces: colorScheme.decimalPlaces
			});

			numpad_disable();
        }

		$('.numpad-cancel-payment'+selected_pump).
			removeClass('poa-button-number-payment-cancel');

		$('.numpad-cancel-payment'+selected_pump).
			addClass('poa-button-number-payment-cancel-disabled');

		// Display Delivering when pump is not stopped
        pumpData['pump'+my_pump].status = 'Delivering';

	} else if (type === 'PumpIdleStatus') {

        if(pumpData['pump'+my_pump].status == 'Delivering') {
		
			log2laravel('info', my_pump + ': updatePumpStatus 1823 running getPumpStatus');
			getPumpStatus(my_pump);

            $('#begin-fuel-message').html("");
            $('#begin-fuel-message').html("<br>");

			if(pumpData['pump'+my_pump].paymentStatus == "Paid"){

				var dose  = pumpData['pump'+my_pump].dose.toString();
				var price = pumpData['pump'+my_pump].price.toString();

				log2laravel('info', my_pump + ': updatePumpStatus: dose='+
					dose+', price='+price);
			
                $('#button-cash-payment'+my_pump).addClass('poa-button-cash-disabled');
                $('#button-cash-payment'+my_pump).removeClass('poa-button-cash');
                $('#button-card-payment'+my_pump).addClass('poa-button-credit-card-disabled');
                $('#button-card-payment'+my_pump).removeClass('poa-button-credit-card');
                $('.numpad-number-payment'+my_pump).removeClass('poa-button-number-payment');
                $('.numpad-number-payment'+my_pump).addClass('poa-button-number-payment-disabled');
                $('.numpad-zero-payment'+my_pump).removeClass('poa-button-number-payment-zero');
                $('.numpad-zero-payment'+my_pump).addClass('poa-button-number-payment-zero-disabled');
                $('.numpad-refund-payment'+my_pump).removeClass('poa-button-number-payment-refund');
                $('.numpad-refund-payment'+my_pump).addClass('poa-button-number-payment-refund-disabled');
                $('.numpad-enter-payment'+my_pump).removeClass('poa-button-number-payment-enter');
                $('.numpad-enter-payment'+my_pump).addClass('poa-button-number-payment-enter-disabled');
				
			} else if( dis_cash['pump'+my_pump].payment_type == "card"){
				pumpData['pump'+my_pump].payment_type =  "Postpaid";

				log2laravel('info', my_pump +
					": updatePumpStatus: Credit Card payment_type=" +
					pumpData['pump'+my_pump].payment_type);

				select_credit_card();
				check_enter()

			} else if( dis_cash['pump'+my_pump].payment_type == "cash"){
                pumpData['pump'+my_pump].payment_type = "Postpaid";

				log2laravel('info', my_pump +
					": updatePumpStatus: Cash payment_type=" +
					pumpData['pump'+my_pump].payment_type);

                select_cash();
                check_enter();
			} 

			if (pumpData['pump'+my_pump].paymentStatus != "Paid") {
				enable_payment_btns();
			}
		}

		$('#pump-status-'+my_pump).text('Idle');
		$('#pump-button-'+my_pump).attr('class', '');
		$('#pump-button-'+my_pump).addClass('btn poa-button-pump-idle');

        if(my_pump == pump_number_main){
            $('#pump-status-main-'+pump_number_main).text("Idle");

			var volume = parseFloat(pumpData['pump'+my_pump].volume).toFixed(2);
            $("#volume-liter-"+my_pump).sevenSeg({
				digits: 7,
				value: volume,
				colorOff: colorScheme.colorOff,
				colorOn: colorScheme.colorOn,
				colorBackground: colorScheme.colorBackground,
				slant: colorScheme.slant,
				decimalPlaces: colorScheme.decimalPlaces
			});

			var price = parseFloat(pumpData['pump'+my_pump].price).toFixed(2);
            $("#price-meter-"+my_pump).sevenSeg({
				digits: 7,
				value: price,
				colorOff: colorScheme.colorOff,
				colorOn: colorScheme.colorOn,
				colorBackground: colorScheme.colorBackground,
				slant: colorScheme.slant,
				decimalPlaces: colorScheme.decimalPlaces
			});

			var amount = parseFloat(pumpData['pump'+my_pump].amount).toFixed(2);
            $("#amount-myr-"+my_pump).sevenSeg({
				digits: 7,
				value: amount,
				colorOff: colorScheme.colorOff,
				colorOn: colorScheme.colorOn,
				colorBackground: colorScheme.colorBackground,
				slant: colorScheme.slant,
				decimalPlaces: colorScheme.decimalPlaces
			});

        }
		//hello
        pumpData['pump'+my_pump].status = 'Idle';

	} else if (type === 'PumpOfflineStatus') {
		$('#pump-status-'+my_pump).text('Offline');
		$('#pump-button-'+my_pump).attr('class', '');
		$('#pump-button-'+my_pump).addClass('btn poa-button-pump-offline');

        if(my_pump == pump_number_main){
            $('#pump-status-main-'+pump_number_main).text("Offline");
        }

        pumpData['pump'+my_pump].status = 'Offline';
	}

	if (pumpData['pump'+my_pump].is_slave == true)
		$(`#pump-auth-warn-${my_pump}`).css('display','block');
	else
		$(`#pump-auth-warn-${my_pump}`).css('display','none');

	if (pumpData['pump'+my_pump].paymentStatus != undefined)
		$(`#payment-status-${my_pump}`).
			html(pumpData['pump'+my_pump].paymentStatus)
}

	
var process_refund = function(my_pump) {
	//SAMPLE OF filled less than dose
	if (pumpData['pump'+my_pump].preset_type == 'Litre') {
		re_dose  = parseFloat($("#total_amount-main-"+my_pump).text())

		log2laravel('info', my_pump +
			': process_refund: Litre re_dose='+ re_dose);

	} else {
		re_dose  = parseFloat(pumpData['pump'+my_pump].dose)

		log2laravel('info', my_pump +
			': process_refund: MYR re_dose='+ re_dose);
	}


	log2laravel('info', my_pump +
		': process_refund: preset_type='+
		pumpData['pump'+my_pump].preset_type);

	log2laravel('info', my_pump +
		': process_refund: final re_dose='+
		re_dose);



	// This is kaput. Sometimes gets mixed-up with price
	var dose = re_dose;

	//var filled = parseFloat(pumpData['pump'+my_pump].amount);
	//var refund = dose - filled;

	var filled = parseFloat(pumpData['pump'+my_pump].volume);
	log2laravel('info', my_pump +
		': process_refund: filled (volume) = '+ filled);

	log2laravel('info', my_pump +
		': process_refund: BEFORE payment_type detection: payment_type='+
		pumpData['pump'+my_pump].payment_type);

	var is_slave = pumpData['pump'+my_pump].is_slave;
	var isAuth = pumpData['pump'+my_pump].isAuth;

	log2laravel('info', my_pump +
		': process_refund: is_slave=' +
		is_slave + ', isAuth=' + isAuth);

	/* Refund detection: This is where you detect if there is a
		refund condition. Refund only happens during PREPAID! */
	/*
	if (pumpData['pump'+my_pump].payment_type != "Postpaid" &&
		pumpData['pump'+my_pump].is_slave != true &&
		pumpData['pump'+my_pump].isAuth == true) {

		log2laravel('info', my_pump +
			': process_refund: BEFORE refund detection: dose='+
			dose.toString()+
			', filled='+filled.toString()+
			', refund='+refund.toString());

		
		if( filled < dose) {
			log2laravel('info', my_pump +
				': process_refund: Detected refund='+
				refund.toString());

			$('#dose').text(dose);
			$('#change').text(refund.toString());
			display_refund(my_pump, refund)
		}
	}
	 */
	if (pumpData['pump'+my_pump].payment_type != "Postpaid") {
		display_refund(my_pump, filled)
	}
	
	/* Finished processing refund, reset auth if paid */
	if(pumpData['pump'+my_pump].paymentStatus == "Paid"){
		pumpData['pump'+my_pump].isAuth = false;

		log2laravel('info', my_pump +
			': process_refund: isAuth=' +
			pumpData['pump'+my_pump].isAuth);

		deleteTerminalSyncData(my_pump)
		reset['pump'+my_pump].reset = true;

		log2laravel("info", my_pump +
			': process_refund: reset=' +
			reset['pump'+my_pump].reset);
	}

}

var  pump_selected = function (pump_id, forced = false) {
    if(selected_pump==pump_id && forced == false)
		return;

    $('#pump-main-block-'+selected_pump).hide();
    $('#pump-main-block-'+pump_id).show();
    $('#button-cash-payment'+selected_pump).hide();
    $('#button-cash-payment'+pump_id).show();
    $('#button-card-payment'+selected_pump).hide();
    $('#button-card-payment'+pump_id).show();
	$(`#button-wallet${selected_pump}`).hide();
	$(`#button-wallet${pump_id}`).show();
	$(`#button-credit-ac${selected_pump}`).hide();
	$(`#button-credit-ac${pump_id}`).show();
    $('#custom_amount_input_'+selected_pump).hide();
    $('#custom_amount_input_'+pump_id).show();

    $('#custom_litre_input_'+selected_pump).hide();
    $('#custom_litre_input_'+pump_id).show();

	selected_pump = pump_id;

	<?php if(env('PUMP_HARDWARE') != NULL && !empty(Auth::user())): ?>
	try {
		console.log("****** Destroying sevenSeg indicators: "+selected_pump);
		$("#amount-myr-"+selected_pump).sevenSeg("destroy");
		$("#volume-liter-"+selected_pump).sevenSeg("destroy");
		$("#price-meter-"+selected_pump).sevenSeg("destroy");
	}
	catch(err) {
		//do nothing because sevenSeg is not init yet!
		console.log('sevenSeg indicators are not initialized yet!');
	}

 	<?php endif; ?>

    if(selected_pump==0){
		return;
	}

	var volume = parseFloat(pumpData['pump'+pump_id].volume).toFixed(2);
	$("#volume-liter-"+pump_id).sevenSeg({
		digits: 7,
		value: volume,
		colorOff: colorScheme.colorOff,
		colorOn: colorScheme.colorOn,
		colorBackground: colorScheme.colorBackground,
		slant: colorScheme.slant,
		decimalPlaces: colorScheme.decimalPlaces
	});

	var price = parseFloat(pumpData['pump'+pump_id].price).toFixed(2);
	$("#price-meter-"+pump_id).sevenSeg({
		digits: 7,
		value: price,
		colorOff: colorScheme.colorOff,
		colorOn: colorScheme.colorOn,
		colorBackground: colorScheme.colorBackground,
		slant: colorScheme.slant,
		decimalPlaces: colorScheme.decimalPlaces
	});

	var amount = parseFloat(pumpData['pump'+pump_id].amount).toFixed(2);
	$("#amount-myr-"+pump_id).sevenSeg({
		digits: 7,
		value: amount,
		colorOff: colorScheme.colorOff,
		colorOn: colorScheme.colorOn,
		colorBackground: colorScheme.colorBackground,
		slant: colorScheme.slant,
		decimalPlaces: colorScheme.decimalPlaces
	});



	var status = pumpData['pump'+pump_id].status;
	$('#pump-number-main-'+pump_id).text(pump_id);
	$('#pump-status-main-'+pump_id).text(status);
	
	if (pumpData['pump'+pump_id].preset_type == 'Litre') {
		console.log(`PRESET_TYPE ${pumpData['pump'+pump_id].preset_type}`);
		$('#total_volume-main-'+pump_id).text(pumpData['pump'+pump_id].dose);
		price  = pumpData['pump'+pump_id].price_liter;
		$('#total_amount-main-'+pump_id).text( parseFloat(pumpData['pump'+pump_id].dose * price).toFixed(2) );
		display_litre_preset(true, pump_id)

	} else {
		console.log(`PRESET_TYPE ${pumpData['pump'+pump_id].preset_type}`);
		display_litre_preset(false, pump_id)
		$('#total_amount-main-'+pump_id).text((pumpData['pump'+pump_id].dose));
	}

	if(pumpData['pump'+pump_id].dose == 0 || status == 'Delivering' || true) {
        $('#authorize-button').attr('class', '');
	    $('#authorize-button').addClass('btn poa-authorize-disabled');
	}

	if(reset['pump'+selected_pump].reset == true) {
		$('#authorize-button').attr('class', '');
		$('#authorize-button').addClass('btn poa-authorize-disabled');
		$('.button-number-amount').removeClass('poa-button-number-disabled');
		$('.button-number-amount').addClass('poa-button-number');
		$("#payment-type-message"+selected_pump).html('');
		$("#payment-type-amount"+selected_pump).html('');
		$("#payment-value"+selected_pump).html("Cash Received");
		$("#payment-value"+selected_pump).css("color","#a0a0a0");
		$("#payment-div-cash-card"+selected_pump).hide();
		$(".payment-div-refund"+selected_pump).hide();
		$(".payment-div-card"+selected_pump).hide();
		$("#payment-div-cash"+selected_pump).show();
		$('.numpad-enter-payment'+selected_pump).removeClass('poa-button-number-payment-enter');
		$('.numpad-enter-payment'+selected_pump).addClass('poa-button-number-payment-enter-disabled');
		//pumpData['pump'+selected_pump].auth_id = "";
		reset['pump'+selected_pump].reset = false;
		reset['pump'+selected_pump].status = "";

		/*
		//pumpData['pump'+selected_pump].dose = "0.00";
		//$('#total_amount-main-'+selected_pump).text(pumpData['pump'+pump_id].dose);
		//$("#product-select-pump-"+selected_pump).css('display','none');
		//$('#fuel-grad-name-' + selected_pump).text("");
		//$('#fuel-grad-thumb-' + selected_pump).css('display', 'none');
		//pumpData['pump' + selected_pump].product = "";
		*/

		$("#payment-type-paid-right"+selected_pump).html("");
		$("#payment-type-amount"+selected_pump).html("");
		$("#payment-amount-card-amount"+selected_pump).html("");
    	$("#payment-type-amount"+selected_pump).html("");
		pumpData['pump'+selected_pump].payment_type = "Prepaid";

		/*
		//pumpData['pump'+selected_pump].receipt_id = '';
		//display_litre_preset(false, selected_pump);
		//removePaymentState(selected_pump)
		*/

		disable_payment_btns();

		/*
		//pumpData['pump'+selected_pump].preset_type = "<?php echo e(empty($company->currency->code) ? 'MYR': $company->currency->code); ?>"
		*/

		$(`#custom_litre_input_${selected_pump}`).removeAttr('disabled');
		$(`#custom_amount_input_${selected_pump}`).removeAttr('disabled');
		pumpData['pump'+selected_pump].isNozzleUp = false;
		pumpData['pump'+selected_pump].isAuth = false;
		deleteTerminalSyncData(selected_pump)
	}

	log2laravel('info', selected_pump +
		': pump_selected: RESET isAuth=' +
		pumpData['pump'+selected_pump].isAuth);

	log2laravel('info',  selected_pump +
		': pump_selected: RESET isNozzleUp=' +
		pumpData['pump'+selected_pump].isNozzleUp);

	log2laravel('info',  selected_pump +
		': pump_selected: RESET paymentStatus=' +
		pumpData['pump'+selected_pump].paymentStatus);

	if (status == 'Delivering' ||  
		(pumpData['pump'+selected_pump].isAuth == true && 
		pumpData['pump'+selected_pump].isNozzleUp == false &&  
		pumpData['pump' + selected_pump].paymentStatus == "Paid") ||
		(pumpData['pump'+selected_pump].isNozzleUp == true && 
		(pumpData['pump'+selected_pump].isAuth == true &&
		pumpData['pump' + selected_pump].paymentStatus != "Not Paid" ))) {

		$('.button-number-amount').removeClass('poa-button-number');
		$('.button-number-amount').addClass('poa-button-number-disabled');

		$(`#custom_litre_input_${selected_pump}`).attr('disabled', true);
		$(`#custom_amount_input_${selected_pump}`).attr('disabled', true);

		console.log("PS Preset disabled");


	} else {
		$('.button-number-amount').removeClass('poa-button-number-disabled');
		$('.button-number-amount').addClass('poa-button-number');

		$(`#custom_litre_input_${selected_pump}`).removeAttr('disabled');
		$(`#custom_amount_input_${selected_pump}`).removeAttr('disabled');
		console.log("PS preset enabled");
	}

	if (status != 'Delivering' && pumpData['pump'+selected_pump].isAuth == true &&
		pumpData['pump' + selected_pump].paymentStatus == "Not Paid" &&
		pumpData['pump' + selected_pump].product)
		//enable_payment_btns();
	
	if(pumpData['pump'+selected_pump].status == "Delivering") {
		$('.button-number-amount').removeClass('poa-button-number');
		$('.button-number-amount').addClass('poa-button-number-disabled');
		$('#authorize-button').attr('class', '');
		$('#authorize-button').addClass('btn poa-authorize-disabled');
		$(`#custom_litre_input_${selected_pump}`).attr('disabled', true);
		$(`#custom_amount_input_${selected_pump}`).attr('disabled', true);
	}

	$("#custom_amount_btn").addClass('custom-preset-disable');
	$("#custom_amount_btn").removeClass('poa-button-preset');
	$("#custom_litre_btn").addClass('custom-preset-disable');
	$("#custom_litre_btn").removeClass('poa-button-preset');

	$(`#custom_amount_input_${selected_pump}_buffer`).val('');
	$(`#custom_amount_input_${selected_pump}`).val('');
	$(`#custom_litre_input_${selected_pump}_buffer`).val('');
	$(`#custom_litre_input_${selected_pump}`).val('');


	if (pumpData['pump' + selected_pump].product) {
		$('#fuel-grad-name-' + selected_pump).text(pumpData['pump' + selected_pump].product );
		$('#fuel-grad-thumb-' + selected_pump).attr('src', pumpData['pump'+ selected_pump].product_thumbnail );
		$('#fuel-grad-thumb-' + selected_pump).css('display', 'inline-flex');
	} else {
		$('#fuel-grad-name-' + selected_pump).text('');
		$('#fuel-grad-thumb-' + selected_pump).css('display', 'none');
	}

	if (status != 'Delivering' && pumpData['pump'+selected_pump].isAuth == true &&
		pumpData['pump' + selected_pump].paymentStatus == "Not Paid" &&
			!pumpData['pump' + selected_pump].product)
		$("#product-select-pump-"+selected_pump).css('display','flex');

	update_payment_table(selected_pump);
	$(`#payment-status-${selected_pump}`).html(pumpData['pump'+selected_pump].paymentStatus)
		/// pumpData['pump'+selected_pump].paymentStatus
}


function set_amount(amount)
{

	if (debounce_pump_auth(selected_pump))
		return

    var total_amount = $('#total_amount-main-'+selected_pump).text();
    amount = parseFloat(amount);
    total_amount = parseFloat(total_amount);
    amount = amount.toFixed(2);

    //if(total_amount == 0) {
    //}

	pumpData['pump'+selected_pump].preset_type = "<?php echo e(empty($company->currency->code) ? 'MYR': $company->currency->code); ?>"
    pumpData['pump'+selected_pump].dose = amount;

	reset_previous_tx_history();

	display_litre_preset(false, selected_pump)
    $('#total_amount-main-'+selected_pump).text(amount);

	console.log('AUTH set_amount('+amount+')');
	update_payment_table(selected_pump, amount)

	if( !pumpData['pump' + selected_pump].product){
		$('#fuel-grad-name-' + selected_pump).text("");
		$('#fuel-grad-thumb-' + selected_pump).css('display', 'none');
		$("#product-select-pump-"+selected_pump).css('display','flex');
	}
	$('#change-val-calculated-'+selected_pump).html('0.00');
	$('#table-PRICE-'+selected_pump).html('');
	$('#table-QTY-'+selected_pump).html('');
	$(`#input-cash${selected_pump}`).show();
}


var isClickPumpAuth = false;

//function 
var pump_authorize = debounce(function () {
	if (isClickPumpAuth == true)
		return

	if (debounce_pump_auth(selected_pump))
		return

	isClickPumpAuth = true;
   
	pumpData['pump'+selected_pump].isAuth = true;

	log2laravel('debug', selected_pump + ': pump_authorize: isAuth=' +
		pumpData['pump'+selected_pump].isAuth);

    $('#begin-fuel-message').text('Please begin fueling');

    $('#authorize-button').attr('class', '');
	$('#authorize-button').addClass('btn poa-authorize-disabled');
    $('.button-number-amount').removeClass('poa-button-number');
    $('.button-number-amount').addClass('poa-button-number-disabled');

	$("#custom_litre_btn").addClass('custom-preset-disable');
	$("#custom_litre_btn").removeClass('poa-button-preset');

	$("#custom_amount_btn").addClass('custom-preset-disable');
	$("#custom_amount_btn").removeClass('poa-button-preset');

    $('#pump-status-main-'+selected_pump).text("Authorizing...");
	
	console.log('AUTH pump_authorize: selected_pump='+selected_pump);

	console.log('AUTH pump_authorize: pumpData='+
		JSON.stringify(pumpData['pump'+selected_pump]));

    pump_number = parseInt(selected_pump);
    $('#pump-status-'+pump_number).text('Authorizing');
    reset['pump'+selected_pump].status = "Delivering";

    if(!pump_number) {
        //alert("select Pump");

    } else {

		product_id = pumpData['pump'+selected_pump].product_id;
		v3_pump_auth(selected_pump, product_id);
    }

	pumpData['pump'+selected_pump].volume = "0.00";
	pumpData['pump'+selected_pump].amount = "0.00";
	pumpData['pump'+selected_pump].price = "0.00";
	pumpData['pump'+selected_pump].auth_id = "";
	pumpData['pump'+selected_pump].paymentStatus = "Not Paid";
	
	enable_payment_btns();
	
    $('.numpad-cancel-payment'+selected_pump).removeClass('poa-button-number-payment-cancel-disabled');
    $('.numpad-cancel-payment'+selected_pump).addClass('poa-button-number-payment-cancel');
	$(`#custom_litre_input_${selected_pump}`).attr('disabled', true);
	$(`#custom_amount_input_${selected_pump}`).attr('disabled', true);

	generate_auth_id(selected_pump);
	terminalSyncData(selected_pump);

},  1000);



function v3_pump_auth(pump_no, product_id) {
	log2laravel('info', '***** v3_pump_auth: ' +
		pump_no + ', product_id=' + product_id); 
	
	var nozzle = getNozzleNo(pump_no, product_id, true);
	var fuel_grade_id = getFuelGradeId(product_id)


	if (nozzle && fuel_grade_id) {
		var type = "Amount";
		var dose = pumpData['pump'+pump_no].dose;
    	var ipaddr = "<?php echo e(env('PTS_IPADDR')); ?>";

		$.ajax({
			url: '/pump-authorize-fuel-grade/' + pump_no + '/' + type +
				'/' + dose + '/' + ipaddr + '/null/' + fuel_grade_id,
			type: "GET",
			dataType: "JSON",
			success: function (response) {

				log2laravel('info', pump_no +
					': ***** v3_pump_auth: SUCCESS from pump-authorize-fuel-grade *****');

				store_txid(response, dose);
		
				pumpData['pump'+pump_no].amount = "0.00";
				$("#amount-myr-"+pump_no).sevenSeg("destroy");
				$("#volume-liter-"+pump_no).sevenSeg("destroy");
				$("#price-meter-"+pump_no).sevenSeg("destroy");
				isClickPumpAuth = false;

			},
			error: function (response) {
				console.log(JSON.stringify(response));
				log2laravel('error', pump_no +
					': ***** v3_pump_auth: ERROR: ' +
					JSON.stringify(response));
			}
		});
	}
}



function debounce(func, wait, immediate) {
  var timeout;
  return function executedFunction() {
    var context = this;
    var args = arguments;

    var later = function() {
      timeout = null;
      if (!immediate) func.apply(context, args);
    };

    var callNow = immediate && !timeout;

    clearTimeout(timeout);

    timeout = setTimeout(later, wait);

    if (callNow) func.apply(context, args);
  };
};


function select_cash(){
    if(selected_pump!=0){
        numpad_enable();
		$('.finish-button-'+selected_pump).removeClass('opos-topup-button');
		$('.finish-button-'+selected_pump).addClass('poa-finish-button-disabled');
		$(`#input-cash${selected_pump}`).show();
		$('#change-val-calculated-'+selected_pump).html('0.00');
        $("#payment-div-cash"+selected_pump+" div").not(`#payment-div-cash${selected_pump} > div > div.col-md-12.mt-auto.w-100.mr-0.pr-1.pl-0 > div.d-flex.justify-content-end`).addClass("justify-content-center");
        dis_cash['pump'+selected_pump].payment_type = "cash";
        dis_cash['pump'+selected_pump].dis_cash = "";
		$(`#buffer-input-cash${selected_pump}`).val('');
		$(`#input-cash${selected_pump}`).val('');
        $("#payment-value"+selected_pump).html("Cash Received");
        $("#payment-value"+selected_pump).css("color","#a0a0a0");
        $("#payment-type-paid-right"+selected_pump).html("");
        $("#payment-div-cash-card"+selected_pump).hide();
        $(".payment-div-refund"+selected_pump).hide();
        $(".payment-div-card"+selected_pump).hide();
        $("#payment-div-cash"+selected_pump).show();
        $("#payment-type-message"+selected_pump).html("");
        $("#payment-type-amount"+selected_pump).html("");
        $("#payment-amount-card-amount"+selected_pump).html("");
    	$("#payment-type-amount"+selected_pump).html("");
        $('.numpad-enter-payment'+selected_pump).removeClass('poa-button-number-payment-enter');
        $('.numpad-enter-payment'+selected_pump).addClass('poa-button-number-payment-enter-disabled');
		$(`#input-cash${selected_pump}`).focus();
		$(`#input-cash${selected_pump}`).click();

        if( !pumpData['pump' + selected_pump].product){
        	$('#fuel-grad-name-' + selected_pump).text("");
			$('#fuel-grad-thumb-' + selected_pump).css('display', 'none');
			$("#product-select-pump-"+selected_pump).css('display','flex');
			if (pumpData['pump'+selected_pump].preset_type == 'Litre' && pumpData['pump'+selected_pump].payment_type == "Prepaid" ) 
				numpad_disable();
        }
    }
}
function select_credit_ac(){
	if(selected_pump!=0){
	$('.finish-button-'+selected_pump).removeClass('poa-finish-button-disabled');
	$('.finish-button-'+selected_pump).addClass('opos-topup-button');
	$(`#buffer-input-cash${selected_pump}`).val('');
	$(`#input-cash${selected_pump}`).val('');
	$(`#input-cash${selected_pump}`).hide();
	dis_cash['pump'+selected_pump].dis_cash = "";
	dis_cash['pump'+selected_pump].payment_type = "creditac";
	$(`#change-val-calculated-${selected_pump}`).text('0.00');
	//$("#payment-div-cash"+selected_pump).hide();
	$(".payment-div-refund"+selected_pump).hide();
	$("#payment-div-cash-card"+selected_pump).hide();
		check_enter()
	}
}
function select_wallet() {

	if(selected_pump!=0){
		$('.finish-button-'+selected_pump).removeClass('poa-finish-button-disabled');
		$('.finish-button-'+selected_pump).addClass('opos-topup-button');
		$(`#buffer-input-cash${selected_pump}`).val('');
		$(`#input-cash${selected_pump}`).val('');
		$(`#input-cash${selected_pump}`).hide();
        dis_cash['pump'+selected_pump].dis_cash = "";
        dis_cash['pump'+selected_pump].payment_type = "wallet";
		$(`#change-val-calculated-${selected_pump}`).text('0.00');
		//$("#payment-div-cash"+selected_pump).hide();
        $(".payment-div-refund"+selected_pump).hide();
        $("#payment-div-cash-card"+selected_pump).hide();

		check_enter()
	}
}

function select_credit_card(){
	
    if(selected_pump!=0){
        numpad_enable();
		$('.finish-button-'+selected_pump).removeClass('poa-finish-button-disabled');
		$('.finish-button-'+selected_pump).addClass('opos-topup-button');
		$(`#input-cash${selected_pump}`).hide();
		rev_cash  = parseFloat(pumpData['pump'+selected_pump].dose)
		$(`#change-val-calculated-${selected_pump}`).text('0.00');
		$(`#buffer-input-cash${selected_pump}`).val('');
		$(`#input-cash${selected_pump}`).val('');
        dis_cash['pump'+selected_pump].dis_cash = "";
        dis_cash['pump'+selected_pump].payment_type = "card";
        $("#payment-value-card"+selected_pump).html("");
        //$("#payment-div-cash"+selected_pump).hide();
        $(".payment-div-refund"+selected_pump).hide();
        $("#payment-div-cash-card"+selected_pump).hide();
        $(".payment-div-card"+selected_pump).show();
        if( pumpData['pump'+selected_pump].payment_type == "Postpaid"){
            var amount = pumpData['pump'+selected_pump].amount;
            amount = ((5 * Math.round((amount*100) / 5))/100).toFixed(2);

        }else{
        }

        $('.numpad-enter-payment'+selected_pump).removeClass('poa-button-number-payment-enter');
        $('.numpad-enter-payment'+selected_pump).addClass('poa-button-number-payment-enter-disabled');
        $("#payment-amount-card-amount"+selected_pump).show();

		if(!pumpData['pump' + selected_pump].product){
			$('#fuel-grad-name-' + selected_pump).text("");
			$('#fuel-grad-thumb-' + selected_pump).css('display', 'none');	
			$("#product-select-pump-"+selected_pump).css('display','flex');
			if (pumpData['pump'+selected_pump].preset_type == 'Litre') 
				numpad_disable();
		}

		check_enter()
    }
}


function select_cash_card(){
    if(selected_pump!=0){
        numpad_enable();
        dis_cash['pump'+selected_pump].dis_cash = "";
        dis_cash['pump'+selected_pump].payment_type = "card";
        $("#payment-div-cash"+selected_pump).hide();
        $(".payment-div-card"+selected_pump).hide();
        $(".payment-div-refund"+selected_pump).hide();
        $("#payment-div-cash-card"+selected_pump).show();
        $("#payment-type-message"+selected_pump).html("More Payment");
        $("#payment-type-amount"+selected_pump).html(pumpData['pump'+selected_pump].dose);
        $("#payment-amount-card-amount"+selected_pump).hide();
    	$("#payment-type-amount"+selected_pump).html("");

        $('#fuel-grad-name-' + selected_pump).text("");
		$('#fuel-grad-thumb-' + selected_pump).css('display', 'none');
        $("#product-select-pump-"+selected_pump).css('display','flex');
        //}
    }
}


function set_cash(amount){
	console.log(`PT tx type: ${pumpData['pump'+selected_pump].payment_type}`);
	console.log(`PT payment type: ${dis_cash['pump'+selected_pump].payment_type}`);

    if(amount=="zero"){
        if( dis_cash['pump'+selected_pump].payment_type == "cash"){
            $('.numpad-number-payment'+selected_pump).removeClass('poa-button-number-payment-disabled');
            $('.numpad-number-payment'+selected_pump).addClass('poa-button-number-payment');
            $("#payment-div-cash"+selected_pump+" div").addClass("justify-content-center");
            $("#payment-value"+selected_pump).css("color","#a0a0a0");
            dis_cash['pump'+selected_pump].dis_cash =  "";
            $("#payment-value"+selected_pump).html("Cash Received");
            $("#payment-type-message"+selected_pump).html("");
            $("#payment-type-amount"+selected_pump).html("");
            $("#payment-type-paid-right"+selected_pump).html("");
            $("#payment-amount-card-amount"+selected_pump).html("");
    		$("#payment-type-amount"+selected_pump).html("");
            $('.numpad-enter-payment'+selected_pump).removeClass('poa-button-number-payment-enter');
            $('.numpad-enter-payment'+selected_pump).addClass('poa-button-number-payment-enter-disabled');
		} else if ( dis_cash['pump'+selected_pump].payment_type == "card") {
            dis_cash['pump'+selected_pump].dis_cash = "";
            $("#payment-value-card"+selected_pump).html("");
            $('.numpad-enter-payment'+selected_pump).removeClass('poa-button-number-payment-enter');
            $('.numpad-enter-payment'+selected_pump).addClass('poa-button-number-payment-enter-disabled');
            $('.numpad-number-payment'+selected_pump).removeClass('poa-button-number-payment-disabled');
            $('.numpad-number-payment'+selected_pump).addClass('poa-button-number-payment');
        }

		$(`#buffer-input-cash${selected_pump}`).val('');
		$(`#input-cash${selected_pump}`).val('');
		$(`#input-cash${selected_pump}`).removeAttr('disabled');
		$('.finish-button-'+selected_pump).removeClass('opos-topup-button');
		$('.finish-button-'+selected_pump).addClass('poa-finish-button-disabled');
		$(`#change-val-calculated-${selected_pump}`).text('0.00');
    } else {
        if (dis_cash['pump'+selected_pump].payment_type == "cash"){
            $("#payment-div-cash"+selected_pump+" div").removeClass("justify-content-center");
            $("#payment-value"+selected_pump).css("color","black");
            dis_cash['pump'+selected_pump].dis_cash =  dis_cash['pump'+selected_pump].dis_cash+amount;
            $("#payment-value"+selected_pump).html((parseFloat(dis_cash['pump'+selected_pump].dis_cash)/100).toFixed(2));
			calculate_change();

            dis_cash_ = (parseFloat(dis_cash['pump'+selected_pump].dis_cash)/100).toFixed(2);
            if (pumpData['pump'+selected_pump].payment_type == "Postpaid"){
				console.log("PT postpaid matched");
				console.log(`PT amount ${pumpData['pump'+selected_pump].amount}`);
				var amount_total = pumpData['pump'+selected_pump].amount;
				amount_total = ((5 * Math.round((amount_total*100) / 5))/100).toFixed(2);
				if(dis_cash_>=parseFloat(amount_total)){
					$('.numpad-number-payment'+selected_pump).
						removeClass('poa-button-number-payment');
					$('.numpad-number-payment'+selected_pump).
						addClass('poa-button-number-payment-disabled');
					if(pumpData['pump' + selected_pump].product){
						$('.numpad-enter-payment'+selected_pump).
							removeClass('poa-button-number-payment-enter-disabled');
						$('.numpad-enter-payment'+selected_pump).
							addClass('poa-button-number-payment-enter');
					}
				}

            } else {

				rev_cash  = parseFloat(pumpData['pump'+selected_pump].dose)
                if(dis_cash_>=rev_cash){
					$('.numpad-number-payment'+selected_pump).
						removeClass('poa-button-number-payment');
					$('.numpad-number-payment'+selected_pump).
						addClass('poa-button-number-payment-disabled');
					if(pumpData['pump' + selected_pump].product){
						$('.numpad-enter-payment'+selected_pump).
							removeClass('poa-button-number-payment-enter-disabled');
						$('.numpad-enter-payment'+selected_pump).
							addClass('poa-button-number-payment-enter');
					}
				}
            }

        } else if ( dis_cash['pump'+selected_pump].payment_type == "card"){
            if(dis_cash['pump'+selected_pump].dis_cash.length<4){
				dis_cash['pump'+selected_pump].dis_cash =  dis_cash['pump'+selected_pump].dis_cash+amount;
				$("#payment-value-card"+selected_pump).html( dis_cash['pump'+selected_pump].dis_cash);
            }

            if(dis_cash['pump'+selected_pump].dis_cash.length==4){
                $('.numpad-number-payment'+selected_pump).removeClass('poa-button-number-payment');
                $('.numpad-number-payment'+selected_pump).addClass('poa-button-number-payment-disabled');
                if(pumpData['pump' + selected_pump].product){
					$('.numpad-enter-payment'+selected_pump).removeClass('poa-button-number-payment-enter-disabled');
					$('.numpad-enter-payment'+selected_pump).addClass('poa-button-number-payment-enter');
                }
            }
        }
    }




}


function check_enter(){
    if( dis_cash['pump'+selected_pump].payment_type == "cash"){
        dis_cash_ = (parseFloat(dis_cash['pump'+selected_pump].dis_cash)/100).toFixed(2);
        if( pumpData['pump'+selected_pump].payment_type == "Postpaid"){
            var amount_total = pumpData['pump'+selected_pump].amount;
            amount_total = ((5 * Math.round((amount_total*100) / 5))/100).toFixed(2);
            if(dis_cash_>=parseFloat(amount_total)){
				if(pumpData['pump' + selected_pump].product){
					$('.numpad-enter-payment'+selected_pump).
						removeClass('poa-button-number-payment-enter-disabled');

					$('.numpad-enter-payment'+selected_pump).
						addClass('poa-button-number-payment-enter');

					$(`#input-cash${selected_pump}`).attr('disabled', true);
				}
			}

        } else {

			if (pumpData['pump'+selected_pump].preset_type == 'Litre') {
				rev_cash  = parseFloat($("#total_amount-main-"+selected_pump).text())
			} else {
				rev_cash  = parseFloat(pumpData['pump'+selected_pump].dose)
			}
            if(dis_cash_>=rev_cash){
				if(pumpData['pump' + selected_pump].product){
					$('.numpad-enter-payment'+selected_pump).
						removeClass('poa-button-number-payment-enter-disabled');

					$('.numpad-enter-payment'+selected_pump).
						addClass('poa-button-number-payment-enter');
					
					$(`#input-cash${selected_pump}`).attr('disabled', true);
				}
            }
        }

	} else if ( dis_cash['pump'+selected_pump].payment_type == "card" ||
				 dis_cash['pump'+selected_pump].payment_type == "wallet" ||
				 dis_cash['pump'+selected_pump].payment_type == "creditac"){
			if(pumpData['pump' + selected_pump].product){
			$('.numpad-enter-payment'+selected_pump).
				removeClass('poa-button-number-payment-enter-disabled');

			$('.numpad-enter-payment'+selected_pump).
				addClass('poa-button-number-payment-enter');
			}
    }
}

var isClickProcessEnter = false;

function process_enter_old()
{
	if (isClickProcessEnter == true)
		return
	isClickProcessEnter = true

    numpad_disable();
    disable_payment_btns()
    $("#payment-type-paid-right"+selected_pump).html("");
    $("#payment-type-amount"+selected_pump).html("");
    $("#payment-amount-card-amount"+selected_pump).html("");
    $("#payment-type-amount"+selected_pump).html("");
	$("#payment-type-message"+selected_pump).html('');
	$("#payment-type-amount"+selected_pump).html('');
    // $('.numpad-cancel-payment'+selected_pump).removeClass('poa-button-number-payment-cancel-disabled');
    // $('.numpad-cancel-payment'+selected_pump).addClass('poa-button-number-payment-cancel');
    pumpData['pump'+selected_pump].paymentStatus = "Paid";
    // pumpData['pump' + selected_pump].product = "";
    if (dis_cash['pump'+selected_pump].payment_type == "cash"){
		$("#payment-value"+selected_pump).css("color","#a0a0a0");
		$("#payment-value"+selected_pump).html("Cash Received");
		$("#payment-div-cash"+selected_pump+" div").addClass("justify-content-center");
    } else if (dis_cash['pump'+selected_pump].payment_type == "card"){
        $(".payment-div-card"+selected_pump).hide();
        $("#payment-div-cash"+selected_pump).show();
        $("#payment-value"+selected_pump).css("color","#a0a0a0");
		$("#payment-value"+selected_pump).html("Cash Received");
		$("#payment-div-cash"+selected_pump+" div").addClass("justify-content-center");
		$("#payment-value-card"+selected_pump).html("");
    }
	

	$(`#buffer-input-cash${selected_pump}`).val('');
	$(`#input-cash${selected_pump}`).val('');

    dis_cash_ = (parseFloat(dis_cash['pump'+selected_pump].dis_cash)/100).toFixed(2);

	var dose = 0;
    if( pumpData['pump'+selected_pump].payment_type == "Postpaid"){
		var amount_total = pumpData['pump'+selected_pump].amount;
		// amount_total = ((5 * Math.round((amount_total*100) / 5))/100).toFixed(2);
		dose =  amount_total;

		var change_amount = dis_cash_ - amount_total;

		amount_total = ((5 * Math.round((amount_total*100) / 5))/100).toFixed(2);
		pumpData['pump'+selected_pump].isAuth = false;
		deleteTerminalSyncData(selected_pump)

	 } else {
		dose = $(`#total_amount-main-${selected_pump}`).text().replace(/,/g,''); 

		// Calculation of change_amount
		if(dis_cash['pump'+selected_pump].payment_type == "cash" || dis_cash['pump'+selected_pump].payment_type == "wallet" || dis_cash['pump'+selected_pump].payment_type == "creditac" ){
			if (pumpData['pump'+selected_pump].preset_type == 'Litre') {
				//var change_amount = dis_cash_ - parseFloat($("#total_amount-main-"+selected_pump).text().replace(/,/g,''));
				var change_amount = dis_cash_ - parseFloat(dose);
			} else {
				var change_amount = dis_cash_ - pumpData['pump'+selected_pump].dose;
			}
		}
		
		terminalSyncData(selected_pump)
	}

	var filled = parseFloat(pumpData['pump'+selected_pump].amount);
	if( filled == dose){
		reset['pump'+selected_pump].reset = true;
	}

	var qty = pumpData['pump'+selected_pump].volume;
	var price = pumpData['pump'+selected_pump].price;
	var payment_type =  dis_cash['pump'+selected_pump].payment_type;
	var creditcard_no = "";
	var cash_received = 0.00;
	var product_id = pumpData['pump' + selected_pump].product_id;
	var product = pumpData['pump' + selected_pump].product;
	var auth_id = pumpData['pump' + selected_pump].auth_id;

	if(payment_type == "card"){
		creditcard_no = dis_cash['pump'+selected_pump].dis_cash;
	}else{
		cash_received =  (parseFloat(dis_cash['pump'+selected_pump].dis_cash)/100).toFixed(2);
	}

	if (pumpData['pump'+selected_pump].preset_type == 'Litre' && pumpData['pump'+selected_pump].payment_type != "Postpaid") {
		qty = parseFloat(pumpData['pump'+selected_pump].dose);;
		amount = $(`#total_amount-main-${selected_pump}`).text().replace(/,/g,'');
		if (price == 0) {
			price =  pumpData['pump'+selected_pump].price_liter;
		}
	}

	/* Squidster: We guard against change_amount for values like:
	   -0.010000000000001563 */

	if (change_amount < 0 || isNaN(change_amount)) {
		change_amount = 0;
	}


	var data = {
		"pump_no":selected_pump,
		"dose":dose,
		"cash_received":cash_received,
		"change_amount":change_amount,
		"payment_type":payment_type,
		"terminal_id":<?php echo $terminal->id??"''"; ?>,
		"tax_percent":<?php echo $terminal->tax_percent??"''"; ?>,
		"creditcard_no":creditcard_no,
		"company_id":<?php echo $company->id??"''"; ?>,
		"currency":"<?php echo e($company->currency->code??''); ?>",
		"mode":"<?php echo e($terminal->mode??''); ?>",
		"product_id":product_id,
		"product":product,
		"qty":qty,
		"price":price,
		"system_payment_type": pumpData['pump'+selected_pump].payment_type,
		"receipt_id":0,
		"auth_id":auth_id
	};

	url = "<?php echo e(route('local_cabinet.receipt.create')); ?>"

	'use strict';
	let _this = this;
	$.ajax({
        url: "<?php echo e(route('local_cabinet.receipt.create')); ?>",
        type: 'post',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        data: data,
		dataType: 'json',
        success: function(response){
			console.log('PR local_cabinet.receipt.create:');
			console.log('PR ***** SUCCESS *****');
			console.log('response='+JSON.stringify(response));
			//my ESCPOS printing function
			data.receipt_id = response;
			console.log('data='+JSON.stringify(data));
            receipt_id = data.receipt_id;

			// Save receipt_id in pumpData[]
			pumpData['pump'+selected_pump].receipt_id = receipt_id;

			/*
			if( pumpData['pump'+selected_pump].payment_type == "Postpaid") {
				log2laravel('info', selected_pump + ': process_enter 2929 running getPumpStatus');
				getPumpStatus(selected_pump)
			}*/

			/* Need to have Qz.io running, otherwise print_receipt()
			 * will bomb out and will not execute lines after it. We
			 * trap the error so that we can still run even if Qz is
			 * NOT running!! */
			try {
				// Output receipt via thermal printer
				print_receipt(response);

				// Open cash drawer
				open_cashdrawer();

			} catch (error) {
				/* This will catch if Qz.io is not being run!! */
				//alert('ERROR! print_receipt(). Check Qz!!');
				//alert("ERROR print_receipt(): " + JSON.stringify(error));
				console.error("ERROR! Check if Qz.io is being run!!");
				console.error("ERROR: "+ JSON.stringify(error));
			}
        },
        error: function(response){
			console.log('PR local_cabinet.receipt.create:');
			console.log('PR ***** ERROR *****');
			console.log(JSON.stringify(response));
        }
	});

	
	if (pumpData['pump'+selected_pump].product) 
		v3_pump_auth(selected_pump, pumpData['pump'+selected_pump].product_id);
	
	reset['pump'+selected_pump].reset = true;
	isClickProcessEnter = false;
}


function void_receipt(pump_no) {
	receipt_id = pumpData['pump'+pump_no].receipt_id;
	
	if (receipt_id == '') {
		log2laravel('info', pump_no +
			':  void_receipt: receipt_id IS BLANK! ABORTING!');
		return;
	}

	$.ajax({
        url: "<?php echo e(route('local_cabinet.receipt.void')); ?>",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'post',
        data: {
        	'receiptid':receipt_id,
        },
        success: function (response) {
		},
		error: function (e) {
        	console.log('VR '+JSON.stringify(e));
        }

	});

}

// For printing ESCPOS
function print_receipt(receipt_id){

	console.log('PR print_receipt()');
	console.log('PR receipt_id='+JSON.stringify(receipt_id));

	$.ajax({
        url: "/print_receipt",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'post',
        data: {
        	'receipt_id':receipt_id,
        },
        success: function (response) {
			var error1=false, error2=false;
        	console.log('PR '+JSON.stringify(response));

			try {
				eval(response);
			} catch (exc) {
				error1 = true;
				console.error('ERROR eval(): '+exc);
			}

			if (!error1) { try {
					escpos_print_template();
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



function calculate_change() {
	dis_cash_ = (parseFloat(dis_cash['pump'+selected_pump].dis_cash)/100).toFixed(2);

	if (pumpData['pump'+selected_pump].payment_type == "Postpaid"){
	
		var amount_total = pumpData['pump'+selected_pump].amount;
		amount_total = ((5 * Math.round((amount_total*100) / 5))/100).toFixed(2);
		var change_amount = dis_cash_ - amount_total;
		
	} else {
		var change_amount = dis_cash_ - parseFloat($('#total_amount-main-'+selected_pump).text().replace(/,/g,''))	
	}
	$(`#change-val-calculated-${selected_pump}`).text(change_amount.toFixed(2));

	if(change_amount>=0){
		$('.finish-button-'+selected_pump).removeClass('poa-finish-button-disabled');
		$('.finish-button-'+selected_pump).addClass('opos-topup-button');
	}else{
		$('.finish-button-'+selected_pump).removeClass('opos-topup-button');
		$('.finish-button-'+selected_pump).addClass('poa-finish-button-disabled');
	}

}


function numpad_enable(){
    $('.numpad-number-payment'+selected_pump).removeClass('poa-button-number-payment-disabled');
    $('.numpad-number-payment'+selected_pump).addClass('poa-button-number-payment');
    $('.numpad-zero-payment'+selected_pump).removeClass('poa-button-number-payment-zero-disabled');
    $('.numpad-zero-payment'+selected_pump).addClass('poa-button-number-payment-zero');

    $('.numpad-enter-payment'+selected_pump).removeClass('poa-button-number-payment-enter-disabled');
    $('.numpad-enter-payment'+selected_pump).addClass('poa-button-number-payment-enter');
	$(`#input-cash${selected_pump}`).removeAttr('disabled');
	
	
}


function numpad_disable(){
    $('.numpad-number-payment'+selected_pump).removeClass('poa-button-number-payment');
    $('.numpad-number-payment'+selected_pump).addClass('poa-button-number-payment-disabled');
    $('.numpad-zero-payment'+selected_pump).removeClass('poa-button-number-payment-zero');
    $('.numpad-zero-payment'+selected_pump).addClass('poa-button-number-payment-zero-disabled');
    $('.numpad-refund-payment'+selected_pump).removeClass('poa-button-number-payment-refund');
    $('.numpad-refund-payment'+selected_pump).addClass('poa-button-number-payment-refund-disabled');
    $('.numpad-enter-payment'+selected_pump).removeClass('poa-button-number-payment-enter');
    $('.numpad-enter-payment'+selected_pump).addClass('poa-button-number-payment-enter-disabled');
	$(`#input-cash${selected_pump}`).attr('disabled',true);
	
}


function enable_payment_btns() {
	$('#button-cash-payment'+selected_pump).removeClass('poa-button-cash-disabled');
	$('#button-cash-payment'+selected_pump).addClass('poa-button-cash');
	$('#button-card-payment'+selected_pump).removeClass('poa-button-credit-card-disabled');
	$('#button-card-payment'+selected_pump).addClass('poa-button-credit-card');
	$('#button-cash-card-payment').removeClass('poa-button-cash-card-disabled');
	$('#button-cash-card-payment').addClass('poa-button-cash-card');	
	$(`#button-wallet${selected_pump}`).removeClass('opos-button-wallet-disabled')
	
	$('#button-credit-ac'+selected_pump).removeClass('opos-button-credit-disabled');
	$('#button-credit-ac'+selected_pump).addClass('opos-button-credit-ac');
	
	$(`#input-cash${selected_pump}`).show();
}


function disable_payment_btns() {
	$('#button-cash-payment'+selected_pump).addClass('poa-button-cash-disabled');
	$('#button-cash-payment'+selected_pump).removeClass('poa-button-cash');
	$('#button-card-payment'+selected_pump).addClass('poa-button-credit-card-disabled');
	$('#button-card-payment'+selected_pump).removeClass('poa-button-credit-card');
	$('#button-cash-card-payment').addClass('poa-button-cash-card-disabled');
	$('#button-cash-card-payment').removeClass('poa-button-cash-card');
	$(`#button-wallet${selected_pump}`).addClass('opos-button-wallet-disabled')
	
	$('#button-credit-ac'+selected_pump).removeClass('opos-button-credit-ac');
	$('#button-credit-ac'+selected_pump).addClass('opos-button-credit-disabled');
	$('.finish-button-'+selected_pump).removeClass('opos-topup-button');
	$('.finish-button-'+selected_pump).addClass('poa-finish-button-disabled');
	numpad_disable();
}


//Click on cancel button event
function pumpCancel(my_pump){

	void_receipt(my_pump);
	cancelAuthorize(my_pump);
	
	try {
 		set_amount(0);
    	disable_payment_btns();
	} catch (e) {
	
	}

    $("#payment-amount-card-amount"+my_pump).html("");
    $("#payment-type-amount"+selected_pump).html("");
    $("#payment-type-paid-right"+my_pump).html("");
    $("#payment-type-message"+my_pump).html('');
	$("#payment-type-amount"+my_pump).html('');
	$('.numpad-cancel-payment'+my_pump).removeClass('poa-button-number-payment-cancel');
	$('.numpad-cancel-payment'+my_pump).addClass('poa-button-number-payment-cancel-disabled');
    if(dis_cash['pump'+my_pump].payment_type == "cash"){
		$("#payment-value"+my_pump).css("color","#a0a0a0");
		$("#payment-value"+my_pump).html("Cash Received");
		$("#payment-div-cash"+my_pump+" div").addClass("justify-content-center");
    } else if (dis_cash['pump'+my_pump].payment_type == "card"){
        $(".payment-div-card"+my_pump).hide();
        $("#payment-div-cash"+my_pump).show();
        $("#payment-value"+my_pump).css("color","#a0a0a0");
		$("#payment-value"+my_pump).html("Cash Received");
		$("#payment-div-cash"+my_pump+" div").addClass("justify-content-center");
        $("#payment-value-card"+my_pump).html("");
        $("#payment-type-amount"+my_pump).html("");
        $("#payment-amount-card-amount"+my_pump).html("");
    	$("#payment-type-amount"+selected_pump).html("");
    }


	$('#authorize-button').attr('class', '');
	$('#authorize-button').addClass('btn poa-authorize-disabled');

    $("#product-select-pump-"+my_pump).css('display','none');
    $('#fuel-grad-name-' + my_pump).text("");
    $('#fuel-grad-thumb-' + my_pump).css('display', 'none');


    reset['pump'+my_pump].reset = true;
}


async function cancelAuthorize(pumpNo) {
    var ipaddr = "<?php echo e(env('PTS_IPADDR')); ?>";
	$.ajax({
		// This is the real Emergency Stop
		url: "/pump-cancel-authorize/" + pumpNo + '/' + ipaddr,
		type: "GET",
		dataType: "JSON",
		success: function (data) {
			console.log('KK ***** cancelAuthorize() *****');
			console.log('KK data:'+JSON.stringify(data));
			$('#pump-stop-button-' + pumpNo).show();
			$('#resume-stop-button-' + pumpNo).hide();
			pumpData['pump' + pumpNo]['stop'] = true;
			$("#product-select-pump-"+pumpNo).css('display','none');
		},
		error: function (e) {
			console.error('KK error:'+JSON.stringify(e));
		}
	});
}


const pumpHardwareIp = Object.values(<?php echo json_encode($pump_hardware->toArray(), true); ?>);

function get_hardwareip(pumpNo, modal_display) {
	pumpNo = parseInt(pumpNo);
	find_pumpIp = pumpHardwareIp.find( ({ pump_no }) => pump_no === pumpNo );

	if (find_pumpIp == undefined) {
		if (modal_display == true) {
			 messageModal("No IP defined for this pump");
		}
		return undefined;
	} else {
		//if IP found
	}

	return find_pumpIp.ipaddress;
}


async function initFuelProduct(my_pump, nozzle) {
	var returnData;
	 $.ajax({
		url:"/pump-product-by-nozzle/" + my_pump + '/' + nozzle,
		type: "GET",
		dataType: "JSON",
		success: function (response) {
			var product = response.product;
            var productid = response.pid;
			var thumb = response.thumbnail;
			var price = response.price;
			log2laravel('info', 'getControllerPrice price:'+price+'');
			returnData = response;
			$("#product-select-pump-"+my_pump).css('display','none');
			$('#fuel-grad-name-' + my_pump).text(product);
			$('#fuel-grad-thumb-' + my_pump).attr('src', thumb);
			$('#fuel-grad-thumb-' + my_pump).css('display', 'inline-flex');
			pumpData['pump' + my_pump].product = product;
            pumpData['pump' + my_pump].product_id = productid;
			pumpData['pump'	+ my_pump].product_thumbnail = thumb;
			pumpData['pump'	+ my_pump].price = price;
			
			console.log(`PL preset_type ${pumpData['pump'+selected_pump].preset_type}`);
			if (pumpData['pump'+my_pump].preset_type == 'Litre') {
				console.log(`PL price ${response.price}`);
				
				pumpData['pump'+my_pump].price_liter = response.price; 
				pumpData['pump'+my_pump].dose = (response.price * pumpData['pump'+my_pump].dose).toFixed(2);
					
				$("#total_amount-main-"+my_pump).
					text(parseFloat(response.price * pumpData['pump'+my_pump].dose).
					toFixed(2))

				if (pumpData['pump'+my_pump].payment_type == "Prepaid") {

					if (pumpData['pump'+my_pump].status != "Delivering") {
						if (dis_cash['pump'+my_pump].payment_type == "card") {
							$("#payment-type-amount"+my_pump).
								html($("#total_amount-main-"+my_pump).text());
						}

						console.log("NUM_PAD enable");
						//numpad_enable();

					} else {
						$("#payment-type-amount"+my_pump).html('');
					}
					
					$('.numpad-enter-payment'+my_pump).
						addClass('poa-button-number-payment-enter-disabled');

					$('.numpad-enter-payment'+my_pump).
						removeClass('poa-button-number-payment-enter');
				}
			}
			
			update_payment_table(my_pump);
		},
		error: function (response) {
			console.log(JSON.stringify(response));
		}
	});

	return returnData;
}


function getNozzleNo(pump_no, product_id, isAlert = false) {
	<?php if(!empty($nozzleFuelData)): ?>
		const nozzleFuelData = Object.values(
			<?php echo json_encode($nozzleFuelData->toArray(), true); ?>);

		let find_nozzle = nozzleFuelData.filter((nozzle) => {
			return nozzle.product_id == product_id && nozzle.pump_no == pump_no;
		});
	
		console.log("NN all nozzles=", JSON.stringify(find_nozzle));
		console.log(`NN nozzles found for product ${product_id}=`, JSON.stringify(find_nozzle));
		if (find_nozzle.length == 0 || find_nozzle == undefined) {
			if (isAlert) {
				messageModal("No nozzle assigned for this fuel");
			}

			return false;
		} else {
			return find_nozzle;
		}
	<?php else: ?>
		return false;
	<?php endif; ?>
}


function getFuelGradeId(product_id) {
	<?php if(!empty($fuel_grade_string)): ?>
	
		const fuelGradeData = Object.values(
			<?php echo json_encode($fuel_grade_string, true); ?>);

		let find_product = fuelGradeData.find( (e) => e.og_f_id == product_id);
		if (find_product) {
			return find_product.Id
		} else {
			return false;
		}

	<?php else: ?>
			return false;
	<?php endif; ?>
}

function selectProduct(pump_no, product_id, product, thumb) {

	 var nozzle = getNozzleNo(pump_no, product_id, true);
	 var fuel_grade_id = getFuelGradeId(product_id)

	if (nozzle && fuel_grade_id) {
		pumpData['pump' + pump_no].product = product;
        pumpData['pump' + pump_no].product_id = product_id;
		pumpData['pump'	+ pump_no].product_thumbnail = thumb;
        check_enter();

		nozzle = JSON.stringify(nozzle.map((e) => e.nozzle_no));

		// $('#authorize-button').attr('class', '');
		// $('#authorize-button').addClass('btn poa-authorize');
		// $("#authorize-button").click(pump_authorize);

		product_info = initFuelProduct(pump_no, nozzle).price;
		enable_payment_btns();
		generate_auth_id(pump_no);
	}
}


/* Function to store transaction ID after getting
   authorization confirmation */
function store_txid(resp, dose) {
	log2laravel('info', 'store_txid:'+
		JSON.stringify(resp)+', dose='+dose);

	if (resp != null && typeof resp != 'undefined') {
		resp = resp.data;
		if (typeof resp.response != 'undefined' &&
			resp.response != null) {
			var response = resp.response;

			log2laravel('info', 'store_txid 1: '+
				JSON.stringify(response));

			/*
			log2laravel('info', 'store_txid 1.1: '+
				(typeof response.Packets));

			log2laravel('info', 'store_txid 1.2: '+
				(typeof response.Packets[0]));

			if ((typeof response.Packets != 'undefined') &&
				(response.Packets != null) &&
				(response.Packets[0] != null) &&
				(typeof response.Packets[0] != 'undefined')) {

				log2laravel('info', 'store_txid 1.3: YAY!');
			}
			*/

			if ((typeof response.Packets != 'undefined') &&
				(response.Packets != null) &&
				(typeof response.Packets[0] != 'undefined') &&
				(response.Packets[0] != null)) {

				log2laravel('info', 'store_txid 2: YAY!');

				var packet = response.Packets[0];
				var my_pump = packet.Data.Pump;
				var transactionid = parseInt(packet.Data.Transaction);

				log2laravel('info', 'store_txid 2.1: packet='+
					JSON.stringify(packet));

				log2laravel('info', 'store_txid 2.2: my_pump='+
					my_pump);

				log2laravel('info', 'store_txid 2.3: transactionid='+
					transactionid);

				if(transactionid != '' &&
				   transactionid != null &&
				   transactionid > 0) {

					log2laravel('info', 'store_txid 3: YAY!');

					authorizeData['pump'+my_pump].transactionid = transactionid;
					authorizeData['pump'+my_pump].amount = dose;

					// Squidster: assign new dose to pumpData
					//pumpData['pump'+my_pump].amount = dose;

					log2laravel('info', 'store_txid 3.1: '+
						JSON.stringify(authorizeData['pump'+my_pump]));
				}
			}
		}
	}
}


function refund_enter(){
    $("#payment-type-message"+selected_pump).html("");
    $("#payment-amount-card-amount"+selected_pump).html("");
    $("#payment-type-amount"+selected_pump).html("");
	$(".payment-div-refund"+selected_pump).show();
	$("#payment-type-amount"+selected_pump).html("");
	$("#payment-amount-card-amount"+selected_pump).html("");
    $("#payment-div-refund-amount-bl-message"+selected_pump).html("Refunded");
    reset['pump'+selected_pump].reset = true;
    $("#payment-type-amount"+selected_pump).html("");
    $("#payment-type-paid-right"+selected_pump).html("");
    $('.numpad-refund-payment'+selected_pump).removeClass('poa-button-number-payment-refund');
    $('.numpad-refund-payment'+selected_pump).addClass('poa-button-number-payment-refund-disabled');

}



function display_refund(my_pump, filled) {
	amt = pumpData[`pump${my_pump}`].amount;
	log2laravel('info', my_pump +
		': display_refund: filled_volume=' +filled+
		', filled_amount=' + amt);

	var r_id =  pumpData['pump'+my_pump].receipt_id;

	// Protect receipt_id for being empty
	if (r_id == '' || r_id == undefined) {
		log2laravel('info', my_pump +
			': display_refund: r_id IS BLANK! ABORTING!');
		return;
	}

    var data = {
		'receipt_id':r_id,
		'filled': filled
	}

    log2laravel('info',
		'display_refund: data='+JSON.stringify(data));

    $.ajax({
        url: "<?php echo e(route('local_cabinet.nozzle.down.refund')); ?>",
        type: 'post',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        data: data,
		dataType: 'json',
        success: function(response){
			console.log('PR local_cabinet.nozzle.down.refund:');
			console.log('PR ***** SUCCESS *****');
			console.log('response='+JSON.stringify(response));
			data.receipt_id = response;
			console.log('data='+JSON.stringify(data));
        },
        error: function(response){
			console.error('PR local_cabinet.nozzle.down.refund:');
			console.error('PR ***** ERROR *****');
			console.error(JSON.stringify(response));
        }
    });
}


function restoreOldState() {
	console.log("OLDSTATE => init");

	if (localStorage.pumpDataState != undefined) {
		var pd = JSON.parse(localStorage.pumpDataState);
		pumpData = JSON.parse(localStorage.pumpDataState);

		/* This is the cause of the rounding bug */
	}

	if (localStorage.reset != undefined)
		reset = JSON.parse(localStorage.reset);

	if (localStorage.authorizeData != undefined)
		authorizeData = JSON.parse(localStorage.authorizeData)
}


var pumpStateInterval;


function select_custom_amount() {

	if (debounce_pump_auth(selected_pump))
		return

	display_litre_preset(false, selected_pump)
	amount = parseFloat($("#custom_amount_input_"+selected_pump).val());

	if (amount > 0) {
		set_amount(amount);
		$("#custom_amount_input_"+selected_pump).val('');
		$("#custom_amount_input_"+selected_pump+'_buffer').val('');
	}

	$("#custom_amount_btn").addClass('custom-preset-disable');
	$("#custom_amount_btn").removeClass('poa-button-preset');
	$("#custom_litre_btn").addClass('custom-preset-disable');
	$("#custom_litre_btn").removeClass('poa-button-preset');
}


function select_custom_litre() {

	if (debounce_pump_auth(selected_pump))
		return

	litre = parseFloat($("#custom_litre_input_"+selected_pump).val());

	if (litre > 0) {
		set_litre(litre);
		$("#custom_amount_input_"+selected_pump).val('');
	}
	
	$("#custom_amount_input_"+selected_pump+'_buffer').val('');
	$("#custom_amount_input_"+selected_pump).val('');

	$("#custom_amount_btn").addClass('custom-preset-disable');
	$("#custom_amount_btn").removeClass('poa-button-preset');
	$("#custom_litre_btn").addClass('custom-preset-disable');
	$("#custom_litre_btn").removeClass('poa-button-preset');
}


function set_litre(litre) {
	 $("#productsModal").modal('show');
}


function display_litre_preset(disp, pump_no) {
	var preset_type = pumpData['pump'+pump_no].preset_type;
	
	if (disp == true)
		$(`#disp_ltr-${pump_no}`).css('display','block')
	else
		$(`#disp_ltr-${pump_no}`).css('display','none')

}

function reset_previous_tx_history() {
	// reset old tx state
	pumpData['pump'+selected_pump].volume = "0.00";
	pumpData['pump'+selected_pump].amount = "0.00";
	pumpData['pump'+selected_pump].price = "0.00";

	pumpData['pump'+selected_pump].paymentStatus = "Not Paid";
	pumpData['pump'+selected_pump].receipt_id = ''

	pumpData['pump'+selected_pump].product_id = "";
	pumpData['pump'+selected_pump].product = "";
	pumpData['pump'+selected_pump].product_thumbnail = "";

	$("#product-select-pump-"+selected_pump).css('display','none');
	$('#fuel-grad-name-' + selected_pump).text("");
	$('#fuel-grad-thumb-' + selected_pump).css('display', 'none');
	$(`#total_amount-main-${selected_pump}`).text('0.00');
	$("#amount-myr-"+selected_pump).sevenSeg("destroy");
	$("#volume-liter-"+selected_pump).sevenSeg("destroy");
	$("#price-meter-"+selected_pump).sevenSeg("destroy");

	
	dis_cash['pump'+selected_pump] = {
        dis_cash:		'',
		payment_type:	'',
   };
  
	var volume = '0.00';
	$("#volume-liter-"+selected_pump).sevenSeg({
		digits: 7,
		value: volume,
		colorOff: colorScheme.colorOff,
		colorOn: colorScheme.colorOn,
		colorBackground: colorScheme.colorBackground,
		slant: colorScheme.slant,
		decimalPlaces: colorScheme.decimalPlaces
	});

	var price = '0.00';
	$("#price-meter-"+selected_pump).sevenSeg({
		digits: 7,
		value: price,
		colorOff: colorScheme.colorOff,
		colorOn: colorScheme.colorOn,
		colorBackground: colorScheme.colorBackground,
		slant: colorScheme.slant,
		decimalPlaces: colorScheme.decimalPlaces
	});

	var amount = '0.00';
	$("#amount-myr-"+selected_pump).sevenSeg({
		digits: 7,
		value: amount,
		colorOff: colorScheme.colorOff,
		colorOn: colorScheme.colorOn,
		colorBackground: colorScheme.colorBackground,
		slant: colorScheme.slant,
		decimalPlaces: colorScheme.decimalPlaces
	});

	disable_payment_btns();
}


<?php for($i=1 ; $i<=env('MAX_PUMPS'); $i++): ?>
	filter_price("#custom_litre_input_<?php echo e($i); ?>","#custom_litre_input_<?php echo e($i); ?>_buffer");
	filter_price("#custom_amount_input_<?php echo e($i); ?>","#custom_amount_input_<?php echo e($i); ?>_buffer");
	filter_price("#input-cash<?php echo e($i); ?>","#buffer-input-cash<?php echo e($i); ?>");

	$("#input-cash<?php echo e($i); ?>").on("keyup", (e)  => {
		console.log("field", $(`#buffer-input-cash${selected_pump}`).val());
		dis_cash['pump'+selected_pump].dis_cash =
			$(`#buffer-input-cash${selected_pump}`).val() != '' ? $(`#buffer-input-cash${selected_pump}`).val():0;
		calculate_change();
		check_enter();
	});

	$("#custom_amount_input_<?php echo e($i); ?>").on("keyup", (e)  => {

		val = $("#custom_amount_input_<?php echo e($i); ?>").val();

		$("#custom_litre_input_<?php echo e($i); ?>_buffer").val('');
		$("#custom_litre_input_<?php echo e($i); ?>").val('');
		$("#custom_litre_btn").addClass('custom-preset-disable');
		$("#custom_litre_btn").removeClass('poa-button-preset');

		if (val == '') {
			$("#custom_amount_btn").addClass('custom-preset-disable');
			$("#custom_amount_btn").removeClass('poa-button-preset');
		} else {
			$("#custom_amount_btn").removeClass('custom-preset-disable');
			$("#custom_amount_btn").addClass('poa-button-preset');
		}
	});
	
	$("#custom_litre_input_<?php echo e($i); ?>").on("keyup", (e)  => {

		val = $("#custom_litre_input_<?php echo e($i); ?>").val();

		$("#custom_amount_input_<?php echo e($i); ?>_buffer").val('');
		$("#custom_amount_input_<?php echo e($i); ?>").val('');
		$("#custom_amount_btn").addClass('custom-preset-disable');
		$("#custom_amount_btn").removeClass('poa-button-preset');

		if (val == '') {
			$("#custom_litre_btn").addClass('custom-preset-disable');
			$("#custom_litre_btn").removeClass('poa-button-preset');
		} else {
			$("#custom_litre_btn").removeClass('custom-preset-disable');
			$("#custom_litre_btn").addClass('poa-button-preset');
		}
	});
<?php endfor; ?>

///////////////////////////////
function filter_price(target_field,buffer_in) {
	$(target_field).off();
	$(target_field).on( "keydown", function( event ) {
		event.preventDefault()
		if (event.keyCode == 8) {
			$(buffer_in).val('')
			$(target_field).val('')
			return null
		}	
		if (isNaN(event.key) ||
		$.inArray( event.keyCode, [13,38,40,37,39] ) !== -1 ||
		event.keyCode == 13) {
			if ($(buffer_in).val() != '') {
				$(target_field).val(atm_money(parseInt($(buffer_in).val())))
			} else {
				$(target_field).val('')
			}
			return null;
		}

		const input =  event.key;
		old_val = $(buffer_in).val()
		if (old_val === '0.00') {
			$(buffer_in).val('')
			$(target_field).val('')
			old_val = ''
		}
		$(buffer_in).val(''+old_val+input)
		$(target_field).val(atm_money(parseInt($(buffer_in).val())))
	});
}


function atm_money(num) {
	if (num.toString().length == 1) {
		return '0.0' + num.toString()
	} else if (num.toString().length == 2) {
		return '0.' + num.toString()
	} else if (num.toString().length == 3) {
		return  num.toString()[0] + '.' + num.toString()[1] +
			num.toString()[2];
	} else if (num.toString().length >= 4) {
		return num.toString().slice(0, (num.toString().length - 2)) +
			'.' + num.toString()[(num.toString().length - 2)] +
			num.toString()[(num.toString().length - 1)];
	}
}


function resetAuth_flag(pump_no) {
	 pumpData['pump'+pump_no].isAuth = false;
	deleteTerminalSyncData = (pump_no);
	 pump_selected(pump_no);
}


function clear_local_storage() {
	clearInterval(pumpStateInterval);
	localStorage.removeItem('reset');
	localStorage.removeItem('pumpDataState');
	localStorage.removeItem('authorizeData');
	window.location.reload();
}


function suspend_tab() {
	clearInterval(pumpStateInterval)
	isVisible = false;
}


function activate_tab() {
	log2laravel('activate_tab: BEFORE restoreOldstate:');
	log2laravel(JSON.stringify(pumpData));

	restoreOldState();

	log2laravel('activate_tab: AFTER  restoreOldstate:');
	log2laravel(JSON.stringify(pumpData));

	pumpStateInterval = setInterval( () => {
		localStorage.pumpDataState = JSON.stringify(pumpData);
		localStorage.reset = JSON.stringify(reset);
		localStorage.authorizeData = JSON.stringify(authorizeData);
	}, 250);

	if (selected_pump != 0) {
		pump_selected(selected_pump, true);
	}

	isVisible = true;
}


var isVisible;
$(document).ready(function() {
	var hidden, visibilityState, visibilityChange;

	if (typeof document.hidden !== "undefined") {
		hidden = "hidden";
		visibilityChange = "visibilitychange";
		ovisibilityState = "visibilityState";

	} else if (typeof document.msHidden !== "undefined") {
		hidden = "msHidden";
		visibilityChange = "msvisibilitychange";
		visibilityState = "msVisibilityState";
	}

	var document_hidden = document[hidden];

	document.addEventListener(visibilityChange, function() {
		if(document_hidden != document[hidden]) {
			if(document[hidden]) {
			// Document hidden

				console.log("NM Tab suspended");
				console.log("NM pumpdata", pumpData)
				suspend_tab();

			} else {
			// Document shown
				//
					activate_tab();
				if( selected_pump != 0) {
					
					if(pumpData['pump'+selected_pump].status != "Delivering") {
						try {
							$("#amount-myr-"+selected_pump).sevenSeg("destroy");
							$("#volume-liter-"+selected_pump).sevenSeg("destroy");
							$("#price-meter-"+selected_pump).sevenSeg("destroy");
						} catch {}

						getPumpStatus(selected_pump, false);
					}

					pump_selected(0)
				}
			//	window.location.reload();
			}

			document_hidden = document[hidden];
		}
	});
});

activate_tab();


function truncateToDecimals(num, dec = 2) {
	return num;
}


var isTerminalSyncData = false;
function terminalSyncData(pump_no) {

	if (isVisible == false)
		return;

	data = {};
	console.log(pumpData[`pump${pump_no}`]);
	isTerminalSyncData = true;
	if (pumpData[`pump${pump_no}`].product_id) {
		data['product_id'] = pumpData[`pump${pump_no}`].product_id;
	}

	data['pump_no'] = pump_no;
	data['payment_status'] = pumpData[`pump${pump_no}`].paymentStatus;
	data['dose'] = pumpData[`pump${pump_no}`].dose;
	data['price'] = pumpData[`pump${pump_no}`].price;
	data['receipt_id'] = pumpData[`pump${pump_no}`].receipt_id;
	data['name'] = pumpData[`pump${pump_no}`].product;
	data['product_thumbnail'] = pumpData[`pump${pump_no}`].product_thumbnail;

	if (pumpData[`pump${pump_no}`].preset_type == "Litre")
		data['litre'] = 1;
	else
		data['litre'] = 0;


	localStorage.setItem("update-screen-e-landing", JSON.stringify(data));

	$.post( '<?php echo e(route('sync_data')); ?>', data).
		done( () => isTerminalSyncData = false).
		fail( (e) => console.log(e));
}


getTerminalSyncData = debounce(function () {
	if (isTerminalSyncData)
		return;

	$.post('<?php echo e(route('get_sync_data')); ?>').done( (res) => {
		if (!res)
			return;

		//console.log("getTerminalSyncData: res => " + JSON.stringify(res))
		terminal_id = <?php echo e($terminal->id); ?>;

		for(i = 1; i <= <?php echo e(env('MAX_PUMPS')); ?>; i++) {
			find_record = res.find( (d) => d.pump_no == i);
			old_is_slave = pumpData[`pump${i}`].is_slave

			// Slave detection
			if (find_record) {
				if (find_record.master_terminal_id == terminal_id)
					pumpData[`pump${i}`].is_slave = false;
				else
					pumpData[`pump${i}`].is_slave = true;
			} else {
				pumpData[`pump${i}`].is_slave = false;
			}	
			
			if (find_record && pumpData[`pump${i}`].is_slave == true) {
				dose = find_record.dose;
				price = find_record.price;
				litre = find_record.litre;
				pump_no = find_record.pump_no;

			//	console.log("getTerminalSyncData: pump_no => " + pump_no )
			//	console.log("getTerminalSyncData: is_slave => " + pumpData[`pump${pump_no}`].is_slave )
				
				if (litre == 1) {
					pumpData[`pump${pump_no}`].preset_type = "Litre";
					if (old_is_slave == false) {	
						$(`#total_amount-main-${pump_no}`).text( (price * dose).toFixed(2) );
					}
					$('#total_volume-main-'+pump_no).text(dose.toFixed(2));

					//console.log( "getTerminalSyncData: amount => (type: ltr) " + (price * dose).toFixed(2) )
				//	console.log("getTerminalSyncData: dose => (type: ltr)" + dose.toFixed(2) )
					display_litre_preset(true,pump_no)
				} else {
					pumpData[`pump${pump_no}`].preset_type = "amount";
					$(`#total_amount-main-${pump_no}`).text( dose.toFixed(2) )

					display_litre_preset(false,pump_no)
				//	console.log("getTerminalSyncData: dose (type: AMT) => " + dose.toFixed(2) )
				}

				if (old_is_slave == false) {	
					$("#amount-myr-"+pump_no).sevenSeg("destroy");
					$("#volume-liter-"+pump_no).sevenSeg("destroy");
					$("#price-meter-"+pump_no).sevenSeg("destroy");

					image = `/images/product/${find_record.psystemid}/thumb/${find_record.thumbnail_1}`;
					pumpData[`pump${pump_no}`].price 		= price.toFixed(2);
					pumpData[`pump${pump_no}`].price_liter	= price.toFixed(2);
					pumpData[`pump${pump_no}`].dose 		= dose.toFixed(2);
					pumpData[`pump${pump_no}`].product_thumbnail = image;
					pumpData[`pump${pump_no}`].product_id = find_record.product_id;
					pumpData[`pump${pump_no}`].product = find_record.pname;

				}
				pumpData[`pump${pump_no}`].paymentStatus = find_record.payment_status;
			}
		}
	});
},100);


var deleteTerminalSyncData = debounce(function (pump_no)  {
	$.post('<?php echo e(route('delete_sync_data')); ?>', {
		pump_no:pump_no
	}).
		fail( (e) => console.log(e));
}, 300);


var debounce_pump_auth = (pump_no) => {
	if (pumpData[`pump${pump_no}`].is_slave == true) {
		return true
	} else {
		return false;
	}
}


function store_last_filled(auth_id, filled)	{
	$.post( '<?php echo e(route('local_cabinet.store_last_filled')); ?>', {auth_id, filled }).
		done((e) => console.log(e)).
		fail((e) => console.log(e));
}


function calculate_fuel_price(price) {
	liter = $(`#custom_litre_input_${selected_pump}`).val();
	amount = liter * price;
	$(`#custom_litre_input_${selected_pump}`).val('')

	if (amount > 0) {
		$("#custom_amount_btn").removeClass('custom-preset-disable');
		$("#custom_amount_btn").addClass('poa-button-preset');
	}

	amount = amount.toFixed(2);
	$(`#custom_amount_input_${selected_pump}`).val(amount);
	$("#productsModal").modal('hide');
}


function generate_auth_id(pump_no) {
	$.post('<?php echo e(route('local_cabinet.generate_auth_id')); ?>').done( (res) => { 
		console.log('auth_id', res);
		pumpData[`pump${pump_no}`].auth_id = res;
	});
}
</script>

<!-- FUEL PAGE CODE ENDS -->
<?php /**PATH D:\Server_Files\oceania\trunk\oceania\resources\views/landing/opos_fuelpage.blade.php ENDPATH**/ ?>