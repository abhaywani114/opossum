<!-- PUMPING LOOP BEGIN -->
<script>


function pump_authorize(pump_no, product_id) {

	log2laravel('info', '***** pump_authorize : ' +
		pump_no + ', product_id=' + product_id); 
	
	var nozzle = getNozzleNo(pump_no, product_id, true);
	var fuel_grade_id = getFuelGradeId(product_id);

	if (nozzle && fuel_grade_id) {
		var type = "Amount";
		var dose = pumpData['pump'+pump_no].dose;
    	var ipaddr = "{{env('PTS_IPADDR')}}";

		$.ajax({
			url: '/pump-authorize-fuel-grade/' + pump_no + '/' + type +
				'/' + dose + '/' + ipaddr + '/null/' + fuel_grade_id,
			type: "GET",
			dataType: "JSON",
			success: function (response) {

				log2laravel('info', pump_no +
					': ***** v3_pump_auth: SUCCESS from pump-authorize-fuel-grade*****');

				store_txid(response, dose);
		
				pumpData['pump'+pump_no].amount = "0.00";
				$("#amount-myr-"+pump_no).sevenSeg("destroy");
				$("#volume-liter-"+pump_no).sevenSeg("destroy");
				$("#price-meter-"+pump_no).sevenSeg("destroy");
				isClickPumpAuth = false;

				mainGetStatus();
				
			
			},
			error: function (response) {
				console.log(JSON.stringify(response));
				log2laravel('error', pump_no +
					': *****v3_pump_auth: ERROR: ' +
					JSON.stringify(response));
			}
		});
	}
}


function mainGetStatus() {
	for (i = 1; i <= {{env('MAX_PUMPS')}}; i++) {
		var ipaddr = "{{env('PTS_IPADDR')}}";
    	//var ipaddr = get_hardwareip(i, false);
		$.ajax({
			url: '/pump-get-status/' + i + '/' + ipaddr,
			type: "GET",
			dataType: "JSON",
			success: function (resp) {

			if (resp != null && typeof resp != 'undefined') {
				resp = resp.data;
				if (typeof resp.response != 'undefined' &&
					resp.response != null) {
					var response = resp.response;

					if (response.hasOwnProperty('Packets') &&
						typeof response.Packets[0] != 'undefined') {
						var packet = response.Packets[0];

						var pump_no = packet.Data.Pump;
						var volume = packet.Data.Volume;
						var price = packet.Data.Price;
						var amount = packet.Data.Amount;
						var nozzle = packet.Data.Nozzle;

						var LastTransaction = packet.Data.LastTransaction;
						var LastVolume  =	packet.Data.LastVolume;
						var LastPrice	=	packet.Data.LastPrice;
						var LastAmount	=	packet.Data.LastAmount;

						if(volume){
							pumpData['pump'+pump_no].volume = volume;
						}

						if (amount) {
							pumpData['pump'+pump_no].amount = amount;
						}

						if (price) {
							pumpData['pump'+pump_no].price = price;
						}

						if (nozzle) {
							pumpData['pump'+pump_no].nozzle = nozzle;
							initFuelProduct(pump_no, nozzle)
						};

						updatePumpStatus(packet.Type, pump_no);
					}
				}
			}
			},
			error: function (resp) {
				console.log('ERROR: ' + JSON.stringify(resp));
			}
		});
	}
}


function updatePumpStatus(type, my_pump) {
	pump_number_main = parseInt(selected_pump);

	if (type === 'PumpFillingStatus') {
        if(pumpData['pump'+my_pump].status == 'Idle'){
			//nozzle up
			$("#amount-myr-"  +my_pump).sevenSeg("destroy");
			$("#volume-liter-"+my_pump).sevenSeg("destroy");
			$("#price-meter-" +my_pump).sevenSeg("destroy");

			pumpData['pump'+my_pump].isNozzleUp = true;
		}

        pumpData['pump'+my_pump].status = 'Delivering';
		$('#pump-status-'+my_pump).text('Delivering');
		$('#pump-button-'+my_pump).attr('class', '');
		$('#pump-button-'+my_pump).addClass('btn poa-button-pump-delivering');
		$('#pump-status-main-'+pump_number_main).text("Delivering");

	} else if (type === 'PumpIdleStatus') {
        if(pumpData['pump'+my_pump].status == 'Delivering') {
			//nozzle down
			getPumpStatus(my_pump);
			pumpData['pump'+my_pump].isNozzleUp = false;
		}

        pumpData['pump'+my_pump].status = 'Idle';
		$('#pump-status-'+my_pump).text('Idle');
		$('#pump-button-'+my_pump).attr('class', '');
		$('#pump-button-'+my_pump).addClass('btn poa-button-pump-idle');
       $('#pump-status-main-'+pump_number_main).text("Idle");
	} else if (type === 'PumpOfflineStatus') {
        pumpData['pump'+my_pump].status = 'Offline';
		$('#pump-status-'+my_pump).text('Offline');
		$('#pump-button-'+my_pump).attr('class', '');
		$('#pump-button-'+my_pump).addClass('btn poa-button-pump-offline');
        $('#pump-status-main-'+my_pump).text("Offline");
	}

     if(my_pump == pump_number_main){

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

	if (pumpData['pump'+my_pump].is_slave == true)
		$(`#pump-auth-warn-${my_pump}`).css('display','block');
	else
		$(`#pump-auth-warn-${my_pump}`).css('display','none');

	if (pumpData['pump'+my_pump].paymentStatus != undefined)
		$(`#payment-status-${my_pump}`).
			html(pumpData['pump'+my_pump].paymentStatus)
}
</script>
<!-- PUMPING LOOP END -->
