<!-- START OPOS Prepaid -->

<script>

	
function update_payment_table(pump_no, amount = false) {
	sum_of_raw_amount = parseFloat(pumpData['pump'+pump_no].dose);
	
	if (amount != false)
		sum_of_raw_amount = parseFloat(amount);

	sst = 0.00;
	item_amount = 0.00;
	//sum_of_raw_amount = 0.00;
	
	var amount_total = ((5 * Math.round((parseFloat(sum_of_raw_amount) * 100) / 5)) / 100);
	sst = parseFloat(sst) + parseFloat((sum_of_raw_amount) - ((sum_of_raw_amount) / (1 + ({{$terminal->tax_percent}}/100))));
	item_amount = parseFloat(sum_of_raw_amount) - parseFloat(((sum_of_raw_amount) - ((sum_of_raw_amount) / (1 + ({{$terminal->tax_percent}}/100)))));
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
	log2laravel('info', 'updatepaymenttable pump:'+JSON.stringify(pumpData['pump' + pump_no])+'');
	
	if (pumpData['pump' + pump_no].product) {
		log2laravel('info', 'updatepaymenttable price:'+pumpData['pump' + pump_no].price+'');
		$(`#table-PRODUCT-${pump_no}`).text(pumpData['pump' + pump_no].product)
		$(`#table-PRICE-6`).text(pumpData['pump' + pump_no].price)
		
		if(pumpData['pump' + pump_no].price > 0){
			
			qty = parseFloat(sum_of_raw_amount) / parseFloat(pumpData['pump' + pump_no].price);
			qty = qty.toFixed(2);
			$(`#table-QTY-${pump_no}`).text(qty)
		}else{	
			$(`#table-QTY-${pump_no}`).text('0.00')
		}
			

	} else {
		console.log("correct2");
		$(`#table-PRODUCT-${pump_no}`).text('');
	}

}

function process_enter(){
	pump_no = selected_pump;
	/*
	dis_cash_ = (parseFloat(dis_cash) / 100).toFixed(2);
	var change_amount = Math.abs(dis_cash_ - total_amount).toFixed(2);
	
	dis_cash_ = isNaN(dis_cash_) ? total: dis_cash_;
	change_amount = isNaN(change_amount) ? 0:change_amount;
	 */

	cal_item_amount = $(`#item-amount-calculated-${pump_no}`).text();	
	cal_sst 		= $(`#sst-val-calculated-${pump_no}`).text();
	cal_rounding 	= $(`#rounding-val-calculated-${pump_no}`).text();
	cal_total		= $(`#table-MYR-${pump_no}`).text();
	cal_change		= $(`change-val-calculated-${pump_no}`).text();

	product_id		= pumpData['pump' + pump_no].product_id;
	product_name	= $(`#table-PRODUCT-${pump_no}`).text();
	product_qty		= $(`#table-QTY-${pump_no}`).text();
	product_price	= $(`#table-PRICE-${pump_no}`).text();
	product_amount	= $(`#table-MYR-${pump_no}`).text();

	payment_type = dis_cash['pump'+pump_no].payment_type;

    if (dis_cash['pump'+selected_pump].payment_type == "cash"){
		cash_received =  (parseFloat(dis_cash['pump'+selected_pump].dis_cash)/100).toFixed(2);
	} else {
		cash_received = cal_total;
	}

	auth_id = pumpData['pump' + selected_pump].auth_id;

	$.post("{{route('local_cabinet.receipt.create')}}", {
			cash_received,
			payment_type,
			cal_item_amount,
			cal_sst,
			cal_rounding,
			cal_total,
			product_id,
			product_name,
			product_qty,
			product_amount,
			product_price,
			pump_no,
			auth_id
		})
		.done( (response) => {
	
			console.log('PR local_cabinet.receipt.create:');
			console.log('PR ***** SUCCESS *****');
			console.log('response='+JSON.stringify(response));
			//my ESCPOS printing function
			receipt_id = response;
			//console.log('data='+JSON.stringify(data));

			// Save receipt_id in pumpData[]
			pumpData['pump'+selected_pump].receipt_id = receipt_id;

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
		})
		.fail( (e) => console.error(e));

	if (pumpData['pump'+selected_pump].product) 
		v3_pump_auth(selected_pump, pumpData['pump'+selected_pump].product_id);
}

</script>
<!-- END OPOS Prepaid -->
