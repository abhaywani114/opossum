@extends('landing.web')
@section('subheader')
@endsection

@section('content')
<script type="text/javascript" src="{{asset('js/qz-tray.js')}}"></script>
<script type="text/javascript" src="{{asset('js/opossum_qz.js')}}"></script>
<script type="text/javascript" src="{{asset('js/JsBarcode.all.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/console_logging.js')}}"></script>
<style>
::placeholder {
	text-align:center;
}

.keydigit {
	font-size:20px;
	width: 40px !important;
	height: 40px !important;
	padding: 6px !important;
	text-align:center;
	color: black;
	margin-right:5px;
    background-color: #ffffff00 !important;
    color: #fff !important;
    border: 1px solid #fff;
}
.pre_setup_field {
	width:100%;
	height:45px;
	margin:auto;
	font-size:20px;
    background: transparent;
    border: 1px solid #fff;
    color: white!important;
    border-radius: 10px;
    text-align: left !important;
}
.pre_setup_field:focus{
    background: transparent;
    border: 1px solid #fff;
    color: white!important;
    border-radius: 10px;
    outline-width: 0;
}
.pre_setup_label {
	margin: 10px 0px;
}
.custom_activate_btn {
    border-radius: 10px;
	padding-left:0;
	padding-right:0;
    margin: auto;
    width: 70px;
    height: 70px;
	font-size:16px;
    border-color: black;
	background-image:linear-gradient(#b4dd9f,#0be020);
}
.login_field {
	height: 40px;
	font-size:17px;
	border-width:0;
    margin-left:0;
    margin-right:0;
    margin-bottom:5px;
    background: transparent;
    border: 1px solid #fff;
	color: white!important;
    border-radius: 10px;
	text-align: left !important;

	@if (!empty($isLocationActive))
	@endif
}
.login_field:focus{
    background: transparent;
    border: 1px solid #fff;
    color: #fff !important;
    border-radius: 10px;
    outline-width: 0;
}
.custom_login_btn {
    width: 65%;
    height: 45px;
	font-size:20px;
    margin-top:10px;
    margin-left:auto;
    margin-right:auto;
    border-radius:10px;
	color:white;
	border:1px solid white;
	background-color:transparent !important;
}
.login_error {
	color: #fff;
}

#dataList li {
	list-style: none;
}

.opos-button-credit-ac {
    margin-top: 0 !important;
    margin-right: 0 !important;
    margin-left: 5px !important;
    margin-bottom: 5px !important;
    width: 70px !important;
    height: 70px !important;
    font-size: 16px;
    color: #ffffff;
    border-width: 0;
    border-radius: 10px;
    background-image: linear-gradient(#49f300, #bcf68c);
}
.opos-button-credit-disabled {
    pointer-events: none !important;
    margin-top: 0 !important;
    margin-right: 0 !important;
    margin-left: 5px !important;
    margin-bottom: 5px !important;
	width: 70px !important;
    height: 70px !important;
    font-size: 16px;
    color: #ffffff;
    border-width: 0;
    border-radius: 10px;
    background-color: rgb(146, 146, 146);
    background-image: none;
}


.poa-finish-button-disabled {
    pointer-events: none !important;
    margin-top: 0 !important;
    margin-right: 0 !important;
    margin-left: 2px !important;
    margin-bottom: 5px !important;
    width: 145px !important;
    height: 70px !important;
    font-size: 16px;
    color: #ffffff;
    border-width: 0;
    border-radius: 10px;
    background-color: rgb(146, 146, 146);
    background-image: none;
}
</style>

@auth
@include("common.header")
<div class="fixed-bottom">
<div class="container-fluid" id="container-blur" style="margin-top:3%">

<div class="row pt-2 pb-0 m-0 pl-0 pr-0" style="">
	<div class="col-md-10 pt-2 pb-0 m-0 pl-0 pr-0"
		style="display:flex;align-items:flex-end">

		<!--
		pump-main-block-0 is the default. This will be displayed 
        before user clicks on any pump. 
		-->

		<div class="row m-0 pt-2 pb-0 pl-0 pr-0 col-md-12"
			id="pump-main-block-0" style="display:flex;align-items:flex-end">

			<!-- Start pump-0 section -->
			<div class="col-md-4 pt-0 pb-0 m-0 pr-0" style="
					padding-left:15px">
			<div class="row">
				<span id="payment-amount-card-amount2" class="" style="color:white;font-weight:500;
					font-size:30px;font-weight: bold;padding-right:5px">
				</span>
				<span id="payment-type-amount2" class="float-right" style="color:white;font-weight:500;
					font-size:30px;font-weight: bold;padding-right:25px">
				</span>
			</div>

			<div class="row" style="margin-bottom:5px !important">
				<div style="padding-right:20px;" class="col-md-12 pl-0 mt-1"
					id="payment-div-cash2">

					<div class="col-md-12" style="position:absolute;
						width:96%; left:0;bottom:0;padding-left:0px;">
					<div class="col-md-12 mt-auto w-100 mr-0 pr-1 pl-0"
						style="height: 115px;">

					<div class="row p-0 m-0 text-white" style="font-weight:bold">
						<div class="col-6 p-0 m-0">
							Description
						</div>
						<div class="col-2 text-center">
							Price
						</div>
						<div class="col-2 text-center">
							Qty
						</div>
						<div class="col-2 p-0 m-0 text-right">
							{{$currency}}
						</div>
					</div>

					<div class="row p-0 m-0 text-white">
						<div class="col-6 p-0 m-0" id="table-PRODUCT-2"></div>
						
						<div class="col-2 text-center" id="table-PRICE-2"></div>

						<div class="col-2 text-center" id="table-QTY-2"></div>

						<div class="col-2 p-0 m-0 text-right"
							id="table-MYR-2">0.00
						</div>
					</div>

					<hr class="" style="margin-top:5px !important;
						margin-bottom:5px !important;
						border:0.5px solid #a0a0a0">

					<div class="d-flex bd-highlight">
						<div class="mr-auto bd-highlight text-white">
							Item Amount
						</div>
						<div class="bd-highlight text-white">
							<span id="item-amount-calculated-2">0.00</span>
						</div>
					</div>
					<div class="d-flex bd-highlight">
						<div class="mr-auto bd-highlight text-white">
							{{!empty($terminal->taxtype)?strtoupper($terminal->taxtype):"SST"}} {{$terminal->tax_percent}}%
						</div>
						<div class="bd-highlight text-white">
							<span id="sst-val-calculated-2">0.00</span>
						</div>
					</div>
					<div class="d-flex bd-highlight">
						<div class="mr-auto bd-highlight text-white">
							Rounding
						</div>
						<div class="bd-highlight text-white">
							<span id="rounding-val-calculated-2">0.00</span>
						</div>
					</div>

					<hr class="" style="margin-top:5px !important;
						margin-bottom:5px !important;
						border:0.5px solid #a0a0a0">

					<div class="d-flex bd-highlight">
						<div class="mr-auto bd-highlight text-white">
							Total
						</div>
						<div class="bd-highlight text-white">
							<span id="grand-total-val-calculated-2">0.00</span>
						</div>
					</div>
					
					<div class="d-flex bd-highlight">
						<div class="mr-auto bd-highlight text-white">
							Change
						</div>
						<div class="bd-highlight text-white">
							<span id="change-val-calculated-2">0.00</span>
						</div>
					</div>
					</div>

					<div class="" style="height:140px;display: flex;align-items: flex-end;">
					<div class="col-md-12 pr-0">
						<div class="row mr-0 ml-0" style="display: flex;
							align-items: flex-end;
							margin-bottom: 5px;">

						<!-- Display product image -->
						<div style="position:relative;left:-30px;" class="col-md-3 text-center">
							<img src="http://127.0.0.1:8001/images/DKOrecast.png" alt="" style="width:100px; height:100px;
								object-fit:contain;display:none;" id="display-product-thumb">
						</div>

						<div class="col-md-4 pl-0">
							<div class="text-white" id="display-product-name" style="line-height: 1.5em;
								height: 3em;
								overflow: hidden;"> 
							</div>
							<span class="text-white" id="display-product-systemid">
							</span>
						</div>

						<div class="col-md-3 row pr-0">
							<div class="text-right col-md-12 pr-0">
								<span class="text-white" id="display-product-price">
								</span>
							</div>
						</div>

						<div class="col-md-2 ml-0 pr-0"
							style="position:relative;left:30px">
							<div style="float:right;padding-right:0;">
							<button class="btn btn-sq-lg btn-success
								cstore-redcrab-button" id="cstore-redcrab-btn"
									style="z-index:9999;display:none;
									margin-bottom:0px !important;" onclick="">
								<i style="margin-top:-8px;
									padding-left:0;padding-right:0;
									font-size:80px" class="fa fa-times-thin">
								</i>
							</button>
						</div>
						</div>
						<!--
						<div style="height:145px;
							padding-right:0 !important;
							align-items:flex-end;justify-content:flex-end"
							class="d-flex col-md-3 pr-0 mt-1">
							<button class="btn btn-sq-lg btn-success
								cstore-redcrab-button"
								id="cstore-redcrab-btn"
								style="display:none;" onclick="">
								<i style="margin-top:-8px;
									padding-left:0;padding-right:0;
									font-size:80px"
									class="fa fa-times-thin">
								</i>
							</button>
						</div>
						-->
						</div>
					</div>
					</div>

					<!-- padding-left:15px or -->
					<div class="row ml-0 mr-0" style="padding-left:0; margin-bottom:5px">

						<!-- <div class="col-md-12 pl-0 pr-0 payment_btns" style="">
						</div> -->

						<div class="col-md-12 row p-0 ml-0 mr-0
							payment_btns align-items-center" style="display:none; text-align: center;">

							<!--
							<button style="margin-bottom:0 !important"
								class="btn btn-success lg-custom-button
								poa-cash-btn
								poa-button-cash w-100"
								onclick="select_cash()" id="">Cash
							</button>
							<button style="margin-bottom:0 !important"
								class="btn btn-success lg-custom-button
								poa-button-credit-card w-100 poa-card-btn"
								onclick="select_credit_card()">Credit Card
							</button>
							<button style="margin-bottom:0 !important"
								class="btn btn-success lg-custom-button
								bg-point poa-button-cash-card w-100" id="">
								Discount
							</button>
							-->
						
							<input class="" type="number" style="background-color:#f0f0f0;
								border-width:0;font-size:16px;border-radius:10px;
								height: 70px !important; width:145px;outline: none;
								text-align:center; float: left;"
								placeholder="Item  Discount" id="item_disc"
								min="1" max="100" maxlength="3" size="3"
								onkeypress="if(this.value.length==3) return false;"> 

							<button class="" id="show_discount_percent"
								onclick="" style="margin-left:0;margin-right:0;
								display:inline;color:white;font-size:40px;
								background:transparent;
								border:none;
								pointer-events:none;
								width:auto;height:70px;">
								0
							</button> 
				
							<button class="btn btn-sq-lg btn-success
								cstore-redcrab-button"
								id="cstore-redcrab-btn-end" style="
								border-radius:10px;
								margin-bottom:0 !important;
								text-align: center;
								font-size: 12px;
								width: 70px !important;
								margin-left: 5px !important;
								float: right;
								">
								Clear Discount
							</button>

							<button class="btn btn-success
								cstore-button-receiptdisc"
								id="cstore-button-receiptdisc-end"
								onclick="" style="border-radius:10px;
								margin-bottom:0 !important;
								font-size:36px;width:70px;height:70px;
								float:right;">%
							</button> 
						</div>
					</div>
					</div>

					<input class="justify-content-center align-items-center"
						style="display:flex; background-color:#f0f0f0;
						width:97% !important;
						border-radius:10px;height: 50px !important;
						font-weight:500; font-size:30px;text-align:right"
						id="input-cash2" placeholder="Cash Received"
						disabled="">
					<input id="buffer-input-cash2" type="hidden">
				</div>
				<div style="padding-right:20px;display:none"
					class="col-md-12 pl-0 mt-1" id="payment-div-cash-card2">
					<div class="justify-content-center align-items-center"
					style="display:flex; background-color:black;
						border-radius:10px;height: 50px !important">
						<span class="text-center pl-3"
							style="color:#a0a0a0;font-weight:500;
							font-size:30px">
							<b>Scan QR</b>
						</span>
					</div>
				</div>

		
				<!--
				<div class="justify-content-center mt-1 align-items-center
					pt-2 pr-2 col-md-8 payment-div-refund2"
					style="display:flex; background-color:black;
					border-radius:10px;height:50px !important;
					display:none">
					<span id="payment-div-refund-amount-bl-message2"
						class="text-center" style="font-weight:bold;
						color:white;font-size:25px">
					</span>
				</div>

				<div class="justify-content-center mt-1 align-items-center
					ml-1 payment-div-refund2"
					style="display:flex;padding-top:3px;
					width:104px !important; background-color:black;
					border-radius:10px; height: 50px !important;
					display:none;">

					<span id="payment-div-refund-amount2"
						class="text-center float-right"
						style="color:white;font-weight:bold;
						font-size:30px;">
					</span>
				</div>
				-->
			</div>

			<div class="row" style="">
				<div class="col-md-12 pr-0 pl-0">
					<button class="btn btn-success btn-sq-lg screend-button
						bg-virtualcabinet"
						onclick="window.open('{{route('fuel-receipt-list' , ['date'=>date('Y-m-d',strtotime(now()))] )}}','_blank')"
						style="margin-left:0 !important;outline:none;
						font-size: 14px">
					<span style="">Today's<br>Cabinet</span>
					</button>
					<button onclick="pumpCancel(2)" class="btn btn-sq-lg
						numpad-cancel-payment2
						poa-button-number-payment-cancel-disabled">Cancel
					</button>
					<button style="margin-left:0 !important"
						class="btn btn-sq-lg
						numpad-zero-payment2
						poa-button-number-payment-zero-disabled"
						onclick="set_cash('zero')"> Zero
					</button>
					<button class="btn poa-finish-button-disabled
						finish-button-0" onclick="process_finish(0)"
						style="margin-left:0px !important;width: 145px !important;">
						Finish
					</button> 
				</div>
			</div>
		</div>


			<div class="col-md-6 pt-2 pb-0 m-0 pr-0 pl-5"
                style="
				position:relative;left:70px;bottom:0">
                <div class="row mt-auto">

				<div class="col-md-8 mx-auto pr-1 pl-1"
					id="dCalc" style="font-size:20px;
					/*position:absolute*/; top: -30%;
					line-height:1.2; z-index: 9999;
					margin-bottom:10px; padding-left:-20px;
					text-align: center;display:none;">

					<div id="refundCustomer"
						style="border:orange;background:orange;
						border-style:solid;padding:5px;
						border-radius: 15px;">

						<div class="" style="width:100%;padding-left:0px;
							padding-right:0px;padding-bottom:5px">
							<div class="col col-md-12 text-center"
								style="font-size: large; color:white">
							   The refund amount is as follow <br>
								<b id="change"></b>
								<b> {{$currency}}</b>
								<!--b id="dose"></b-->
							</div>
						</div>

						<div class="" style="width: 100%; padding-top:5px;
							padding-left: 0px; padding-right: 0px;">
							<div class="col col-md-12 text-center">

							<!--- button type="button" class="btn btn-success"
								onclick="display_refund()"
								removeRefund()
								style="width:100px">Confirm
							</button --->

							<button type="button" class="btn"
								onclick="removeRefund()"
								style="width:100px;
								background: #000;color: #fff;vertical-align: bottom;">Close
							</button>
							</div>
						</div>
					</div>
				</div>
                </div>

				<div class="row mt-auto" style="">
					<div style="color:white;font-size:20px;
						position:relative;left:-20px; line-height:1.2;
						margin-top:7px;
						padding-left:-20px; text-align: center;">
						<b>Total<br>Amount</b><br>
						{{$currency}}
					</div>
					<div style="margin-bottom:5px"
                        class="col-md-8 ml-0 pr-0 pl-0">

						<div id="amount-myr"
							class="pl-1 pr-1" style="
							background-color:#f0f0f0;width:385px;
							border-radius:10px;height: 85px !important">
						</div>
					</div>
				</div>

				<div class="row" style="">
					<div style="position:relative;left:-10px;top:20px"
						class="text-right pr-4 pt-0 mr-1">
						<img src="{{asset('images/dispenser_icon.png')}}"
						style="transform:scaleX(-1);
						width:50px;height:auto;object-fit:contain"/>
					</div>
					<div style="margin-bottom:5px" class="col-md-8 pr-0 pl-0">
						<div id="volume-liter"
							class="pl-1 pr-1"
							style="background-color:#f0f0f0;width:385px;
							border-radius:10px;height:85px !important">
						</div>
					</div>
				</div>

				<div class="row">
					<div class="ml-0 p-0"
						style="width:60px; height:60px;text-align:center;
							position:relative;left:-10px;top:3px;
							font-size:40px;color:white;"
							id="pump-number">
						<b id="pump-number-main"></b>
					</div>
					<div class="mr-2 ml-2 mt-2"
						style="position:relative;left:15px;
							line-height:1.2; color:white;
							font-size:20px;text-align: center;">
						<b>Price</b><br>
						{{$currency}}
					</div>
					<div class="col-md-6 pr-0 pl-4">
						<div id="price-meter"
							class="pl-8 pr-1 pt-1 pb-1"
							style="
							padding-top:10px;padding-bottom:10px;
							background-color:#f0f0f0;
							border-radius:10px;height: 60px !important">
						</div>
					</div>
					<div class="text-center col-md-2 pl-0"
						style="color:white;padding-top:15px;font-size:20px;">
						<b>/Litre</b>
					</div>
				</div>

				<div style="min-height:100px;max-height:100px;display:flex">
				<div class="row"></div></div>
			</div>

			<div class="col-md-2 pt-2 pb-0 m-0 mb-3 pl-0 pr-0"
				style="
				position:relative;left:50px;top:-35px;">

				<div id="disp_ltr-0" style="display:none;">
					<h1 class="text-center"
						style="margin-bottom:0 !important;color:white;
						font-size:20px">
						Litre
					</h1>
					<div class="text-center mb-2"
						style="color:white;font-size:2em; display: block;">
						<b id="total_volume-main-0">0.00</b>
					</div>
				</div>

				<div class="text-center"
					style="margin-bottom:0 !important;color:white;
					font-size:20px" >
					<b id="payment-status"
						style="margin: 30px auto;display: block;">Not Paid
					</b>
				</div>

				<h1 class="text-center"
					style="margin-bottom:0 !important;color:white;
					font-size:20px">
					{{$currency}}
				</h1>
				<div class="text-center mb-2"
					style="color:white;font-size:2em; display: block;">
					<b id="total_amount-main">0.00</b>
				</div>
				<h1 class="mb-0 text-center"
					style="color:white;font-size:20px">Status
				</h1>
				<div class="text-center"
					style="color:white;font-size:20px">
					<b id="pump-status-main">Online</b>
				</div>

				<div class="text-center"
					style="position:relative;top:10px;color:white;font-size:20px">
					<img src="{{asset('images/delivering_spinball.gif')}}"
						style="width:80px;height:80px;object-fit:contain"/>
				</div>
			</div>
		</div>
		<!-- End pump-0 section -->


		<!-- Start common pump-N section -->
		@for($i = env('MAX_PUMPS'); $i>=1; $i--)
		<div class="row m-0 pt-2 pb-0 pl-0 pr-0 col-md-12"
			id="pump-main-block-{{$i}}"
			style="display: none;align-items:flex-end">
            <div class="col-md-4 pt-0 mt-0 pb-0 m-0 pr-0"
                style="padding-left:15px;line-height:1.05">
                <span id="payment-type-message{{$i}}"
					class=""
					style="color:white;
					font-size:30px;font-weight: bold;">
                </span>
                <span  id="payment-type-paid-right{{$i}}"
					class="float-right"
					style="color:white;font-weight:500;
					font-size:30px;font-weight: bold;padding-right:25px">
				</span>
            </div>
            <div  class="col-md-8 pt-2 pb-0 m-0 pr-0"><br></div>

			<div class="col-md-4 pt-0 pb-0 m-0 pr-0"
                style="
					padding-left:15px">
			<div class="row">
				<span  id="payment-amount-card-amount{{$i}}"
					class=""
					style="color:white;font-weight:500;
					font-size:30px;font-weight: bold;padding-right:5px">
				</span>
				<span  id="payment-type-amount{{$i}}"
					class="float-right"
					style="color:white;font-weight:500;
					font-size:30px;font-weight: bold;padding-right:25px">
				</span>
			</div>

			<div class="row" style="margin-bottom:5px !important">
				<div style="padding-right:20px"
					class="col-md-12 pl-0 mt-1"
					id="payment-div-cash{{$i}}">

					<div class="col-md-12"
						style="position:absolute;left:0; bottom: 0;
						width:96%; padding-left:0px; ">
					<div class="col-md-12 mt-auto w-100 mr-0 pr-1 pl-0"
						style="height: 115px;">
						<div style="font-weight:bold" class="row p-0 m-0 text-white">
							<div class="col-6 p-0 m-0">
								Description
							</div>
							
							<div class="col-2 text-center">
								Price
							</div>

							<div class="col-2 text-center">
								Qty
							</div>

							<div class="col-2 p-0 m-0 text-right">
								
								{{$currency}}
							</div>
						</div>

						<div class="row p-0 m-0 text-white">
							<div class="col-6 p-0 m-0" id="table-PRODUCT-{{$i}}">
							</div>
							
							<div class="col-2 text-center" id="table-PRICE-{{$i}}">
							</div>

							<div class="col-2 text-center" id="table-QTY-{{$i}}">
							</div>

							<div class="col-2 p-0 m-0 text-right" id="table-MYR-{{$i}}">
							</div>
						</div>

							<hr class="" style="margin-top:5px !important;
							margin-bottom:5px !important;
							border:0.5px solid #a0a0a0">
						<div class="d-flex bd-highlight">
							<div class="mr-auto bd-highlight text-white">
								Item Amount
							</div>
							<div class="bd-highlight text-white">
								<span id="item-amount-calculated-{{$i}}">0.00</span>
							</div>
						</div>
						<div class="d-flex bd-highlight">
							<div class="mr-auto bd-highlight text-white">
								SST {{$terminal->tax_percent}}%
							</div>
							<div class="bd-highlight text-white">
								<span id="sst-val-calculated-{{$i}}">0.00</span>
							</div>
						</div>
						<div class="d-flex bd-highlight">
							<div class="mr-auto bd-highlight text-white">
								Rounding
							</div>
							<div class="bd-highlight text-white">
								<span id="rounding-val-calculated-{{$i}}">0.00</span>
							</div>
						</div>

						<hr class="" style="margin-top:5px !important;
							margin-bottom:5px !important;
							border:0.5px solid #a0a0a0" />

						<div class="d-flex bd-highlight">
							<div class="mr-auto bd-highlight text-white">
								Total
							</div>
							<div class="bd-highlight text-white">
								<span id="grand-total-val-calculated-{{$i}}">0.00</span>
							</div>
						</div>
						
						<div class="d-flex bd-highlight">
							<div class="mr-auto bd-highlight text-white">
								Change
							</div>
							<div class="bd-highlight text-white">
								<span id="change-val-calculated-{{$i}}">0.00</span>
							</div>
						</div>
					</div>

					<div class="" style="height:140px;display: flex;align-items: flex-end;">
						<div class="col-md-12 pr-0">
							<div class="row mr-0 ml-0" style="display: flex;
								align-items: flex-end;
								margin-bottom: 5px;">

							<!-- Display product image -->
							<div style="position:relative;left:-30px;" class="col-md-3 text-center">
								<img src="http://127.0.0.1:8001/images/DKOrecast.png" alt="" style="width:100px; height:100px;
									object-fit:contain;display:none;" id="display-product-thumb">
							</div>

							<div class="col-md-4 pl-0">
								<div class="text-white" id="display-product-name" style="line-height: 1.5em;
									height: 3em;
									overflow: hidden;"> 
								</div>
								<span class="text-white" id="display-product-systemid">
								</span>
							</div>

							<div class="col-md-3 row pr-0">
								<div class="text-right col-md-12 pr-0">
									<span class="text-white" id="display-product-price">
									</span>
								</div>
							</div>

							<div class="col-md-2 ml-0 pr-0" style="position:relative;left:30px">
								<div style="float:right;padding-right:0;">
								<button class="btn btn-sq-lg btn-success
									cstore-redcrab-button" id="cstore-redcrab-btn" style="z-index:9999;display:none;
										margin-bottom:0px !important;" onclick="">
									<i style="margin-top:-8px;
										padding-left:0;padding-right:0;
										font-size:80px" class="fa fa-times-thin">
									</i>
								</button>
							</div>
							</div>
							<!--
							<div style="height:145px;
								padding-right:0 !important;
								align-items:flex-end;justify-content:flex-end"
								class="d-flex col-md-3 pr-0 mt-1">
								<button class="btn btn-sq-lg btn-success
									cstore-redcrab-button"
									id="cstore-redcrab-btn"
									style="display:none;" onclick="">
									<i style="margin-top:-8px;
										padding-left:0;padding-right:0;
										font-size:80px"
										class="fa fa-times-thin">
									</i>
								</button>
							</div>
							-->
							</div>
						</div>
					</div>

					<!-- padding-left:15px or -->
					<div class="row ml-0 mr-0"
						style="padding-left:0; margin-bottom:5px">

						<!-- <div class="col-md-12 pl-0 pr-0 payment_btns" style="">
						</div> -->

						<div class="col-md-12 row p-0 ml-0 mr-0
							payment_btns align-items-center"
							style="display:none; text-align: center;">

							<!--
							<button style="margin-bottom:0 !important"
								class="btn btn-success lg-custom-button
								poa-cash-btn
								poa-button-cash w-100"
								onclick="select_cash()" id="">Cash
							</button>
							<button style="margin-bottom:0 !important"
								class="btn btn-success lg-custom-button
								poa-button-credit-card w-100 poa-card-btn"
								onclick="select_credit_card()">Credit Card
							</button>
							<button style="margin-bottom:0 !important"
								class="btn btn-success lg-custom-button
								bg-point poa-button-cash-card w-100" id="">
								Discount
							</button>
							-->
						
							<input class="" type="number" style="background-color:#f0f0f0;
								border-width:0;font-size:16px;border-radius:10px;
								height: 70px !important; width:145px;outline: none;
								text-align:center; float: left;"
								placeholder="Item  Discount"
								id="item_disc" min="1" max="100"
								maxlength="3" size="3"
								onkeypress="if(this.value.length==3) return false;"> 

							<button class="" id="show_discount_percent"
								onclick="" style="margin-left:0;margin-right:0;
								display:inline;color:white;font-size:40px;
								background:transparent;
								border:none;
								pointer-events:none;
								width:auto;height:70px;">
								0
							</button> 
				
							<button class="btn btn-sq-lg btn-success
								cstore-redcrab-button"
								id="cstore-redcrab-btn-end" style="
								border-radius:10px;
								margin-bottom:0 !important;
								text-align: center;
								font-size: 12px;
								width: 70px !important;
								margin-left: 5px !important;
								float: right;
								">
								Clear Discount
							</button>

							<button class="btn btn-success
								cstore-button-receiptdisc"
								id="cstore-button-receiptdisc-end"
								onclick="" style="border-radius:10px;
								margin-bottom:0 !important;
								font-size:36px;width:70px;height:70px;
								float:right;">%
							</button> 
						</div>
					</div>
					</div>

					<input class="justify-content-center align-items-center"
						style="display:flex; background-color:#f0f0f0;
						width:97% !important;
						border-radius:10px;height: 50px !important;
						font-weight:500; font-size:30px;text-align:right"
						id="input-cash{{$i}}" placeholder="Cash Received"
						disabled>
					<input id="buffer-input-cash{{$i}}" type="hidden" />
				</div>
				<div style="padding-right:20px;display:none"
					class="col-md-12 pl-0 mt-1" id="payment-div-cash-card{{$i}}">
					<div class="justify-content-center align-items-center"
						style="display:flex; background-color:black;
						border-radius:10px;height: 50px !important">
						<span
							class="text-center pl-3"
							style="color:#a0a0a0;font-weight:500;
							font-size:30px">
							<b>Scan QR</b>
						</span>
					</div>
				</div>

		
				<!--
				<div class="justify-content-center mt-1 align-items-center
					pt-2 pr-2 col-md-8 payment-div-refund{{$i}}"
					style="display:flex; background-color:black;
					border-radius:10px;height:50px !important;
					display:none">
					<span id="payment-div-refund-amount-bl-message{{$i}}"
						class="text-center" style="font-weight:bold;
						color:white;font-size:25px">
					</span>
				</div>

				<div class="justify-content-center mt-1 align-items-center
					ml-1 payment-div-refund{{$i}}"
					style="display:flex;padding-top:3px;
					width:104px !important; background-color:black;
					border-radius:10px; height: 50px !important;
					display:none;">

					<span id="payment-div-refund-amount{{$i}}"
						class="text-center float-right"
						style="color:white;font-weight:bold;
						font-size:30px;">
					</span>
				</div>
				-->
			</div>

			<div class="row" style="">
				<div class="col-md-12 pr-0 pl-0">
					<button class="btn btn-success btn-sq-lg screend-button
						bg-virtualcabinet"
						onclick="window.open('{{route('fuel-receipt-list'  , ['date'=>strtotime(now())])}}','_blank')"
						style="margin-left:0 !important;outline:none;
							font-size: 14px">
					<span style="">Today's<br>Cabinet</span>
					</button>

					{{--
					onclick="window.open('{{route('local_cabinet')}}','_blank')"
					--}}

					<button	onclick="pumpCancel({{$i}})"
					class="btn btn-sq-lg
						numpad-cancel-payment{{$i}}
						poa-button-number-payment-cancel-disabled">Cancel
					</button>
					<button style="margin-left:0px !important"
						class="btn btn-sq-lg
						numpad-zero-payment{{$i}}
						poa-button-number-payment-zero-disabled"
						onclick="set_cash('zero')" > Zero
					</button>
					<button  class="btn poa-finish-button-disabled
						finish-button-{{$i}}" onclick="process_finish({{$i}})"
						style="margin-left:0px!important;width:145px !important;">
						Finish
					</button> 
				</div>
			</div>
		</div>

		<div class="col-md-6 pt-2 pb-0 m-0 pr-0 pl-5"
			style="position:relative;left:70px;">

			<div id='pump-auth-warn-{{$i}}'
				class="text-center"
				style="padding-left:0;display:none;">
				<h5 style='margin-bottom:15px;color:yellow;'>
					Pump already authorized by the other terminal.
					Due to pump being locked, payment status and
					MYR do not reflect the actual values.
				</h5>
			</div>
			<div class="row mt-auto" style="">
				<div style="color:white;font-size:20px;
					position:relative;left:-20px; line-height:1.2;
					margin-top:7px;border-radius:10px;
					padding-left:-20px; text-align: center;">
					<b>Total<br>Amount</b><br>
					{{$currency}}
				</div>
				<div style="margin-bottom:5px"
					class="col-md-8 ml-0 pr-0 pl-0">
					<div id="amount-myr-{{$i}}"
						class="pl-1 pr-1" style="
						padding-top:10px; padding-bottom:10px;
						background-color:#f0f0f0;width:385px;
						border-radius:10px;height: 85px !important">
					</div>
				</div>
			</div>

			<div class="row" style="">
				<div style="position:relative;left:-10px;top:20px"
					class="text-right pr-4 pt-0 mr-1">
					<img src="{{asset('images/dispenser_icon.png')}}"
					style="transform:scaleX(-1);
					width:50px;height:auto;object-fit:contain"/>
				</div>
				<div style="margin-bottom:5px" class="col-md-8 pr-0 pl-0">
					<div id="volume-liter-{{$i}}"
						class="pl-1 pr-1"
						style="background-color:#f0f0f0;width:385px;
						padding-top:10px;padding-bottom:10px;
						border-radius:10px;height:85px !important">
					</div>
				</div>
				<div class="text-center col-md-2 mt-auto mb-4 pl-0"
					style="position:relative;top:15px;left:25px;
					color:white; font-size:20px;">
					<b>Litre</b>
				</div>
			</div>

			<div class="row">
				<div class="ml-0 p-0"
					style="width:60px; height:60px;text-align:center;
						position:relative;left:-10px;top:3px;
						font-size:40px;color:white;"
						id="pump-number">
					<b id="pump-number-main-{{$i}}">{{$i}}</b>
				</div>
				<div class="mr-2 ml-2 mt-2"
					style="position:relative;left:15px;
						line-height:1.2; color:white;
						font-size:20px;text-align: center;">
					<b>Price</b><br>{{$currency}}
				</div>
				<div class="col-md-6 pr-0 pl-4">
					<div id="price-meter-{{$i}}"
						class="pl-8 pr-1"
						style="
						padding-top:10px; padding-bottom:10px;
						background-color:#f0f0f0;
						border-radius:10px;height: 60px !important">
					</div>
				</div>
				<div class="text-center col-md-2 pl-0"
					style="color:white;padding-top:15px;font-size:20px;">
					<b>/Litre</b>
				</div>
			</div>

			<!-- Display selected product -->
			<div style="min-height:100px;max-height:100px;display:flex"
				class="align-items-center">
				<div class="row text-white"
					id="product-display-pump-{{$i}}"
					style="position:relative;top:0;left:0;
					align-items:center;">
					<img src='' id="fuel-grad-thumb-{{$i}}"
						style='width:70px;height:70px;display:inline-block;
						border-radius:10px;
						margin-right:12px;object-fit:contain;display:none'>
					<p class='m-0 p-0' id="fuel-grad-name-{{$i}}"
						style="text-align: center;font-size: 25px;
						font-weight: 600;"></p>
				</div>

				<!-- Display product buttons -->
				<div class="row text-white" id="product-select-pump-{{$i}}"
					style="margin-left:15px;margin-top:0;
					align-items: center; display:none;">
					@if (!empty($productData))
					@foreach ($productData as $product)

					@if (!empty($nozzleFuelData->
						where("product_id",$product->id)->
						where("pump_no",$i)->first()))
						<img  src='/images/product/{{$product->systemid}}/thumb/{{$product->thumbnail_1}}'
							id="fuel-grad-thumb-{{$i}}-option-{{$product->systemid}}"
							onclick="selectProduct('{{$i}}', '{{$product->id}}', '{{$product->name}}',
							'/images/product/{{$product->systemid}}/thumb/{{$product->thumbnail_1}}')"
							style='width:70px;height:70px;display:inline-block;cursor:pointer;object-fit: contain;
							border-radius:10px;margin-right: 8px;' />
					@endif

					@endforeach
					@endif
				</div>
			</div>
		</div>
		<!-- End common pump-N section -->


		<div class="col-md-2 pt-2 pb-0 m-0 mb-3 pl-0 pr-0"
			style="position:relative;left:50px;top:-35px">

			<!--div id="disp_ltr-{{$i}}"
				style="display:none">
				<h1 class="text-center"
					style="margin-bottom:0 !important;color:white;
					font-size:20px">
					Litre
				</h1>
				<div class="text-center mb-2"
					style="color:white;font-size:2em; display: block;">
					<b id="total_volume-main-{{$i}}">0.00</b>
				</div>
			</div-->

			<div class="text-center"
				style="margin-bottom:0 !important;color:white;
				font-size:20px">
				<b id="payment-status-{{$i}}"
					style="margin: 30px auto;display: block;">
					Not Paid
				</b>
			</div>

			<h1 class="text-center"
				id="preset-type-main-{{$i}}"
				style="margin-bottom:0 !important;color:white;
				font-size:20px">
				{{$currency}}
			</h1>
			<div class="text-center mb-2"
				style="color:white;font-size:2em; display: block;">
				<b id="total_amount-main-{{$i}}">0.00</b>
			</div>
			<h1 class="mb-0 text-center"
				style="color:white;font-size:20px">Status
			</h1>
			<div class="text-center"
				style="color:white;font-size:20px">
				<b id="pump-status-main-{{$i}}">Online</b>
			</div>

			<div class="text-center"
				style="position:relative;top:10px;color:white;font-size:20px">
				<img id="pump-delivering-spinball-{{$i}}"
					src="{{asset('images/delivering_spinball.gif')}}"
					style="width:80px;height:80px;object-fit:contain"/>
			</div>
		</div>
		</div>
		@endfor
	</div>

	<div class="col-md-2 mt-auto pt-2 pb-0 m-0 pl-0 pr-0"
		style="">

		<div class="row float-right">
			<div style="justify-content:flex-end;display:flex"
				class="col-md-12 float-right">
				<button class="btn btn-sq-lg poa-button-number-disabled
					button-number-amount"
					onclick="set_amount(2)" >2
				</button>

				<button class="btn btn-sq-lg poa-button-number-disabled
					button-number-amount"
					onclick="set_amount(5)">5
				</button>
			</div>
		</div>

		<div class="row float-right">
			<div style="justify-content:flex-end;display:flex"
				class="col-md-12 float-right">
				<button class="btn btn-sq-lg poa-button-number-disabled
					button-number-amount"
					onclick="set_amount(10)">10
				</button>

				<button class="btn btn-sq-lg poa-button-number-disabled
					button-number-amount"
					onclick="set_amount(20)">20
				</button>
			</div>
		</div>
		<div class="row float-right">
			<div style="justify-content:flex-end;display:flex"
				class="col-md-12 float-right">
				<button class="btn btn-sq-lg poa-button-number-disabled
					button-number-amount"
					onclick="set_amount(50)">50
				</button>

				<button class="btn btn-sq-lg poa-button-number-disabled
					button-number-amount"
					onclick="set_amount(100)">100
				</button>
			</div>
		</div>
		<div class="row float-right">
			<div style="justify-content:flex-end;display:flex"
				class="col-md-12 float-right">
				<button class="btn btn-sq-lg poa-button-number-disabled
					button-number-amount"
					onclick="set_amount(150)">150
				</button>

				<button class="btn btn-sq-lg poa-button-number-disabled
					button-number-amount"
					onclick="set_amount(800)">800
				</button>
			</div>
		</div>
		<div class="row float-right">
			<div class="col-md-12 float-right pl-0">
				{{-- <button class="btn poa-authorize-disabled"
				style="" id="authorize-button__" >Authorize
				</button> --}}
				</div>
			</div>
		</div>
	</div>

	<hr class="" style="margin-top:0px !important;
		margin-bottom:5px !important; border:0.5px solid #a0a0a0"/>

	<div class="row" style="margin-bottom:12px">
		<div class="col-md-5 mb-1">
			<div style="float:left;">
				<a href="{{route('screen.d')}}" target="_blank"
					rel="noopener noreferrer">
					<button class="btn poa-bluecrab-button mb-1"
						style="float: left !important;"
						id="bluecrab_btn" onclick="">
						<i style="top:2px;margin-left:2px;margin-right:0;
							padding-left:0;padding-right:0;font-size:48px"
							class="far fa-circle"></i>
					</button>
				</a>

				<!-- This to replace cstore and the plain button
				<button style="float:left !important;
					pointer-events:none;
					margin-right:5px !important"
					class="btn btn-sql-lg phantom-button1">
				</button>
				-->

				<button class="btn opos-cstore-button"
					style="float: left !important" id="cstore_btn"
					onclick="window.open('{{ route("index.cstore") }}','_blank')">
					<img src="{{asset('images/basket_transparent.png')}}"
					style="width:45px;height:auto;object-fit:contain"/>
				</button>

				<button class="btn opos-h2-button"
					style="float: left !important" id="h2_btn"
					onclick="window.open('{{ route("h2-landing") }}','_blank')">
					<img src="{{asset('images/h2_logo4.png')}}"
					style="margin-left:0;maring-right:0;
						margin-top:0;width:40px;
						height:auto;object-fit:contain"/>
				</button>

				<button class="btn opos-ev-button"
					style="float: left !important" id="ev_btn"
					onclick="window.open('{{ route("car_park_landing") }}','_blank')">
					
					<img src="{{asset('images/ev_transparent.png')}}"
					style="margin-left:-2px;margin-top:-2px;width:50px;
						height:auto;object-fit:contain"/>
				</button>

				<!--
				<button class="btn opos-plain-button"
					style="float: left !important;
					pointer-events:none;cursor:normal;" id="" >
					<img src=""
					style="width:45px;height:auto;object-fit:contain;"/>
				</button>
				-->

				<!--
				<button class="btn btn-success btn-sq-lg poa-button-drawer"
					style="margin-left:4px !important;
					margin-right:3px !important; font-size:15px"
					onclick="open_cashdrawer()">Drawer
				</button>
				-->

                @for($i=1 ; $i<=env('MAX_PUMPS'); $i++)
				<button class="btn btn-success poa-button-cash
					poa-button-cash-disabled"
					id="button-cash-payment{{$i}}"
					onclick="select_cash()"
					style="margin-left:5px !important;display:none">Cash
                </button>
                @endfor

                <button class="btn btn-success poa-button-cash
					poa-button-cash-disabled"
					id="button-cash-payment0" onclick="select_cash()"
					style="margin-left:5px !important">Cash
                </button>
			</div>
			<div style="float:left;">
				<!--
				<button class="btn btn-success btn-sq-lg poa-button-drawer"
					style="float: left !important; font-size:15px"
					onclick="open_cashdrawer()">Drawer
				</button>

				<button class="btn btn-sql-lg phantom-button0"></button>
				<button class="btn btn-sql-lg phantom-button0"></button>
                <button class="btn btn-sql-lg phantom-button1"></button>
				-->

				<!--
				<span style="color:#fff;width: 55px !important;
					display: inline-block;">Litre
				</span>
				-->

				<input class="" type="number"
					style="background-color:#f0f0f0;
					float:left !important;
					border-width:0;font-size:20px;
					border-radius:10px;height: 70px !important;
					width: 145px;outline: none;text-align:center;"
					placeholder='0.00' id="custom_litre_input_0" disabled />

                @for($i=1 ; $i<=env('MAX_PUMPS'); $i++)
				<input class="hide" type="number"
					style="background-color:#f0f0f0;
					float:left !important;
					border-width:0;font-size:20px;
					border-radius:10px;height: 70px !important;
					width: 145px;outline: none;text-align:center;"
					placeholder='0.00' id="custom_litre_input_{{$i}}"/>

				<input class="hide" type="number"
					id="custom_litre_input_{{$i}}_buffer"/>

				@endfor

				<button class="btn mb-1 custom-preset-disable"
					id="custom_litre_btn"
					onclick="select_custom_litre()"
					style="margin-left: 5px !important; font-size:16px;
					float:left !important;
					padding-left:0 !important;padding-right:0 !important;
					cursor:pointer !important;">
					Preset<br>Litre
				</button>

				<button class="btn opos-topup-button"
					style="float-left !important" id="topup_btn"
					onclick="">
					<img src="{{asset('images/topup_transparent.png')}}"
					style="margin-top:-2px;height:40px;
						object-fit:contain"/>
				</button>

				{{--
                @for($i=1 ; $i<=env('MAX_PUMPS'); $i++)
				<button class="btn opos-button-combine
					opos-button-combine-disabled"
					id="button-combine{{$i}}"
					style="font-size:13px;display:none"
					onclick="">
					Combine
				</button>
                @endfor

				<button class="btn opos-button-combine
					opos-button-combine-disabled"
					id="button-combine0"
					style="font-size:13px"
					onclick="">
					Combine
				</button>
				--}}

                @for($i=1 ; $i<=env('MAX_PUMPS'); $i++)
				<button class="btn btn-success poa-button-credit-card
					poa-button-credit-card-disabled"
                    id="button-card-payment{{$i}}"
					onclick="select_credit_card()"
					style="float:right;display:none;">
					Credit Card
                </button>
                @endfor

                <button class="btn btn-success poa-button-credit-card
					poa-button-credit-card-disabled"
					id="button-card-payment0"
					onclick="select_credit_card()"
					style="float:right;">
					Credit Card
                </button>
			</div>

			<div style="clear:both">
				<input class="" type="number"
					style="background-color:#f0f0f0;
					float:left !important;
					border-radius:10px;height: 70px !important;
					border-width:0;font-size:20px;
					width: 145px;outline: none;text-align:center;"
					placeholder='0.00' id="custom_amount_input_0" disabled />

                @for($i=1 ; $i<=env('MAX_PUMPS'); $i++)
				<input class="hide" type="number"
					style="background-color:#f0f0f0;
					float:left !important;
					border-width:0;font-size:20px;
					border-radius:10px;height: 70px !important;
					width: 145px;outline: none;text-align:center;"
					placeholder='0.00' id="custom_amount_input_{{$i}}"/>

				<input class="hide" type="number"
					id="custom_amount_input_{{$i}}_buffer"/>
				@endfor

				<button class="btn mb-1 custom-preset-disable"
					id="custom_amount_btn"
					onclick="select_custom_amount()"
					style="margin-left: 5px !important; font-size:16px;
					float:left !important;
					padding-left:0 !important;padding-right:0 !important;
					margin-left:5px !important;
					cursor:pointer !important;">
					Preset<br>
					{{$currency}}
				</button>

				<button class="btn btn-success btn-sq-lg poa-button-drawer"
					style="margin-left:5px !important;
					float:left !important;
					margin-right:0 !important; font-size:15px"
					onclick="open_cashdrawer()">Drawer
				</button>

                @for($i=1 ; $i<=env('MAX_PUMPS'); $i++)
				<button class="btn btn-success opos-button-credit-disabled
					"
                    id="button-credit-ac{{$i}}"
					onclick="select_credit_ac()"
					style="float:left !important;display:none;
						border-radius:10px;font-size:13px;
						width:145px;height:70px">Credit<br>A/C
                </button>
                @endfor

                <button  class="btn btn-success opos-button-credit-disabled
					 "
					id="button-credit-ac0"
					style="float:left !important;width:145px;
						border-radius:10px;font-size:13px;
						height:70px; cursor: pointer">Credit<br>A/C
                </button>


                @for($i=1 ; $i<=env('MAX_PUMPS'); $i++)
				<button class="btn btn-success opos-button-wallet
					opos-button-wallet-disabled"
                    id="button-wallet{{$i}}"
					onclick="select_wallet()"
					style="float:left !important;display:none;
						border-radius:10px;">Wallet
                </button>
                @endfor

                <button class="btn btn-success opos-button-wallet
					opos-button-wallet-disabled"
					id="button-wallet0"
					onclick=""
					style="float:left !important;
						border-radius:10px;">Wallet
                </button>
			</div>
		</div>
		<div class="col-md-7">

		<div class="float-right mr-0 ">
			@for($i=1 ; $i<=env('MAX_PUMPS'); $i++)
			<button class="btn poa-button-pump-idle mb-1"
				style="float: right !important;"
				id="pump-button-{{$i}}" onclick="pump_selected({{$i}})" >
				<img src="{{asset('images/dispenser_icon.png')}}"
					style="transform:scaleX(-1);width:32px;height:32px;object-fit:contain;margin-left:0"
				/>
				<br>
				<div class="text-center pl-0 pr-0"
					style="font-size: 18px;">
					{{$i}}
				</div>
				<p style="font-size: 0.7em;"
					id="pump-status-{{$i}}">Offline
				</p>
			</button>
			@endfor
		</div>
		</div>
	</div>

@include("common.footer")

	<!--
	<nav class="navbar navbar-light bg-light p-0"
		style="background-image:linear-gradient(rgb(38, 8, 94),rgb(86, 49, 210));">
	<nav class="navbar navbar-light bg-light p-0">
		<span class="navbar-text m-0"></span>
	</nav>
	-->
</div>
</div>
@endauth

<div class="modal fade @guest show @endguest " id="userEditModal" tabindex="-1"
	role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true"
	style="padding-right:0 !important; @auth display:none @endauth ">

	<div class="modal-dialog modal-dialog-centered" role="document"
		style=" width: 100% !important; height: 100% !important; margin: 0;
		padding: 0;max-width:100%;max-hight:100%">

		<div class="modal-content modal-inside bg-black"
			style="height: auto; min-height: 100% !important;border-radius:0;">
			<div class="modal-body text-center"
				style="vertical-align: middle !important ;margin-top: 3%;">
				<img style="width:180px;height:180px;object-fit:contain"
					src="{{ asset('images/small_logo.png') }}">
				<br>
				<p class="mb-0" style="margin-bottom:0;margin-top:20px;
					font-size:80px;font-weight:550;line-height:1.0">
				OPOSsum
				</p>
				<div class="row align-items-center">



				@if (!empty($isLocationActive) &&
					(!empty($isTerminalActive) || $isServerEnd) )

				<!-- This is only for OPOSsum Terminal Login -->
				<div style="display:flex;"
					class="col-md-3 align-items-center pl-0 pr-0">
					<div id="login-message"
						style="font-size:20px;color:yellow;line-height:1.3">
					</div>
					<div style="font-size:20px;color:yellow;line-height:1.3"
						class="text-center login_error">
					</div>
				</div>

				<div style=""
					class="col-md-6 pl-0 pr-0 text-center">
					<img style="position:relative;top:-10px;
						cursor:pointer;
						width:100%;height:390px;object-fit:contain"
						onclick="login_form_toggle()"
						src="{{ asset('images/anim_torus.gif') }}"/>
				</div>
				<div style=""
					class="col-md-3 pl-0 pr-0">
					<div id="login_form"
						style="padding:20px auto;display:none;">
						<form autocomplete="off">
                            <input type="hidden" name="hosting" value="opossum" id="hosting"/>
							<input autofocus
								class="text-center form-control login_field"
								style="width:100%"
								id="email" name="email"
								autocomplete="off"
								type="text" placeholder="Email"/>

							<input autofocus
								class="text-center form-control login_field"
								style="width:100%"
								id="password" name="password"
								type="password" placeholder="Password"/>
						</form>
						<button style="width:100%"
							class="btn-primary btn-md
							custom_login_btn" onclick="login_me()">
							<span style="position:relative;top:-1px">
							Log In
							</span>
						</button>
					</div>
				</div>

				@else
				<!-- This is only for OPOSsum Terminal Setup -->
				<div class="mt-4 col-sm-12">
				<div style="display: flex; justify-content: center;">
					<input autofocus class="form-control keydigit" type="text" id="key_1" maxlength="1">
					<input disabled class="form-control keydigit" type="text" id="key_2" maxlength="1">
					<input disabled class="form-control keydigit" type="text" id="key_3" maxlength="1">
					<input disabled class="form-control keydigit" type="text" id="key_4" maxlength="1">&nbsp;&nbsp;

					<input disabled class="form-control keydigit" type="text" id="key_5" maxlength="1">
					<input disabled class="form-control keydigit" type="text" id="key_6" maxlength="1">
					<input disabled class="form-control keydigit" type="text" id="key_7" maxlength="1">
					<input disabled class="form-control keydigit" type="text" id="key_8" maxlength="1">&nbsp;&nbsp;

					<input disabled class="form-control keydigit" type="text" id="key_9" maxlength="1">
					<input disabled class="form-control keydigit" type="text" id="key_10" maxlength="1">
					<input disabled class="form-control keydigit" type="text" id="key_11" maxlength="1">
					<input disabled class="form-control keydigit" type="text" id="key_12" maxlength="1">&nbsp;&nbsp;

					<input disabled class="form-control keydigit" type="text" id="key_13" maxlength="1">
					<input disabled class="form-control keydigit" type="text" id="key_14" maxlength="1">
					<input disabled class="form-control keydigit" type="text" id="key_15" maxlength="1">
					<input disabled class="form-control keydigit" type="text" id="key_16" maxlength="1">
				</div>

				<div class="mt-3 row align-items-center"
					style="width: 50%;margin:auto;justify-content: center;">

					<div class="col-5">
						<input autofocus
							class="text-center form-control pre_setup_field"
							id="terminal_id_field"
							type="text" placeholder="Terminal ID"/>
					</div>

					<div class="col-2">
						<button onclick="activateLicence()"
							class="btn-primary btn-md
							custom_activate_btn">Set Up
						</button>
					</div>
				</div>
				</div>

				<div class="col-md-10 align-items-center m-auto">
					<div id="login-message"
						style="font-size:20px;color:yellow;width: 100%;text-align: center;">
					</div>
					<div style="font-size:20px;color:yellow;width: 100%;text-align: center;"
						class="pl-5 login_error">
					</div>
				</div>
				@endif
				</div>

			</div>
		</div>
	</div>
</div>

<div class="modal fade"  id="modalMessage"  tabindex="-1" role="dialog"
 	aria-hidden="true" style="text-align: center;">
    <div class="modal-dialog modal-dialog-centered  mw-75 w-50" role="document"
     style="display: inline-flex;">
        <div class="modal-content modal-inside bg-purplelobster"
        style="width: 100%;  background-color: {{@$color}} !important" >
            <div class="modal-header" style="border:0">&nbsp;</div>
            <div class="modal-body text-center">
                <h5 class="modal-title text-white" id="statusModalLabelMsg"></h5>
            </div>
            <div class="modal-footer" style="border-top:0 none;">&nbsp;</div>
        </div>
    </div>
</div>


<div class="modal fade" id="listMerchantModal" tabindex="-1"
	 role="dialog" aria-labelledby="staffNameLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered  mw-75 w-50"
		 role="document">
		<div class="modal-content modal-inside bg-purplelobster">

			<div class="modal-header" style="font-size: 15pt">Credit Account</div>
            <hr>
			<div class="modal-body text-center">
				<div id="dataList" class="" style="widows: 100%; height: 300px; overflow-y: auto">

				</div>
			</div>

		</div>
		</ul>
	</div>
</div>


<!--
<div class="modal fade bd-example-modal-lg" id="driverfuelledger"
	tabindex="-1" role="dialog" aria-labelledby="driverfuelledger"
	aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered "
		style="max-width: 75% !important;">
		<div class="modal-content bg-purplelobster" >
			<div class="modal-header">
				<h3 class="mb-0">Local Fuel Ledger</h3>
			</div>
			<div class="modal-body" id="driverfuelledger-table"></div>
		</div>
    </div>
</div>
-->

<div id="productsModal" class="modal fade" tabindex="-1" role="dialog"
	 aria-hidden="true">
<div class="modal-dialog modal modal-dialog-centered" style="margin: auto;">
	<div style="border-radius:10px"
		 class="modal-content bg-purplelobster">
	<div class="modal-header">
		<h3 style="margin-bottom:0">Calculation: Litre to MYR</h3>
	</div>
	<div class="modal-body" style="">
		<div class="row" style="width:100%">
		<div class="col-md-12" style="">
			<div id="productList" class="creditmodelDV"
				 style="display:flex; flex-wrap: wrap;
				 justify-content: flex-start;">
			
			<div class="row" style="width:100%">
				<div class="col-md-12" style="">
				<div id="productList" class="creditmodelDV" 
					style="display:flex; flex-wrap: wrap;
					justify-content: flex-start;">

					@foreach ($productData as $product)
					<div class="col-md-12 ml-0 pl-0">
						<div class="row align-items-center d-flex">
						<div class="col-md-2">
							<img class="thumbnail productselect sellerbutton" 
								style="padding-top:0;object-fit:contain;
								float:right;width:30px !important;
								height:30px !important;margin-left:0;
								margin-top:2px;margin-right:0;margin-bottom:2px" 
								src="/images/product/{{$product->systemid}}/thumb/{{$product->thumbnail_1}}">
						</div>
						<div class="col-md-10 pl-0 productselect"
							style="cursor:pointer;line-height:1.2;
							margin-left:0;font-size:20px;
							padding-top:0;text-align: left;"
							onclick="calculate_fuel_price({{($product->price ?? 0)}})">
							{{$product->name}}
						</div>
						</div>
					</div>
					@endforeach

				</div>
				</div>
			</div>
			</div>
		</div>
		</div>
	</div>
	</div>
</div>
</div>

@endsection

@section('script')

@if (!empty($isLocationActive) && ( !empty($isTerminalActive) ))
@include('landing.opos_prepaid')
@include('landing.opos_fuelpage')
@include('landing.opos_pumpingloop')
@else
@include('landing.license')
@endif

<script>
	$.ajaxSetup({
		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
	});
	function listMerchantData() {
		$.ajax({
			method: "post",
			url: "{{route('creditaccount.listMerchantActive')}}",
		}).done((data) => {

			let dataList = data.data;
			console.log(data.data);

			$("#dataList").html("");
			for (let i = 0; i < dataList.length; i++) {
				$("#dataList").append('<li class="p-2 text-left" style="width: 100%;">'+dataList[i]["name_company"]+'</li>');
			}
			$("#listMerchantModal").modal("show");
		}).fail((data) => {
			console.log("data", data)
		});
	}
</script>
@endsection
