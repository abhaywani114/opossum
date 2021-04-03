@extends('landing.web')
@section('subheader')
@endsection

@section('content')
<script type="text/javascript" src="{{asset('js/qz-tray.js')}}"></script>
<script type="text/javascript" src="{{asset('js/opossum_qz.js')}}"></script>
<script type="text/javascript" src="{{asset('js/JsBarcode.all.min.js')}}"></script>
<style>

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
</style>

@auth
@include("common.header")
<div class="fixed-bottom">
<div class="container-fluid" id="container-blur" style="margin-top:3%">

<div class="row pt-2 pb-0 m-0 pl-0 pr-0" style="">
	<div class="col-md-10 pt-2 pb-0 m-0 pl-0 pr-0"
		style="display:flex;align-items:flex-end">

		<!-- pump-main-block-0 is the default. This will be displayed
        before user clicks on any pump -->

		<div class="row m-0 pt-2 pb-0 pl-0 pr-0"
			id="pump-main-block-0" style="display:flex;align-items:flex-end">

			<!-- Start Section -->
			<div class="col-md-4 pt-0 pb-0 m-0 pr-0"
                style="padding-left:15px">

				<div class="row" style="margin-bottom:5px !important">
					<div style="padding-right:20px"
						class="col-md-12 pl-0 mt-1">
						<div id="payment-div"
							class="justify-content-center align-items-center"
							style="display:flex; background-color:#f0f0f0;
							width:368px !important;
							border-radius:10px;height: 50px !important">

							<span id="payment-value"
								class="pr-2"
								style="color:#a0a0a0;font-weight:500;
								font-size: 30px;">
								Cash Received
							</span>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12 pr-0 pl-0" style="position: relative">
						<button class="btn btn-success btn-sq-lg
							poa-button-number-payment-disabled"
							onclick="set_cash(1)">1
						</button>
						<button class="btn btn-sq-lg
							poa-button-number-payment-disabled"
							onclick="set_cash(2)">2
						</button>
						<button class="btn btn-sq-lg
							poa-button-number-payment-disabled"
							onclick="set_cash(3)">3
						</button>
						<button class="btn btn-sq-lg
							poa-button-number-payment-disabled"
							onclick="set_cash(4)">4
						</button>
						<button id="cancel_btn_numpad"
							 class="btn btn-sq-lg
							 poa-button-number-payment-cancel-disabled">Cancel
						</button>
						<button class="btn btn-sq-lg
							poa-button-number-payment-disabled"
							onclick="set_cash(5)">5
						</button>
						<button class="btn btn-sq-lg
							poa-button-number-payment-disabled"
							onclick="set_cash(6)">6
						</button>
						<button class="btn btn-sq-lg
							poa-button-number-payment-disabled"
							onclick="set_cash(7)">7
						</button>
						<button class="btn btn-sq-lg
							poa-button-number-payment-disabled"
							onclick="set_cash(8)">8
						</button>

						<button class="btn btn-success btn-sq-lg screend-button
							bg-virtualcabinet"
							onclick="window.open('{{route('local_cabinet')}}','_blank')"
							style="margin-left:0 !important;outline:none;
								font-size: 14px">
						<span style="">Local Cabinet</span>
						</button>

						<button class="btn btn-sq-lg
							poa-button-number-payment-disabled"
							onclick="set_cash(9)">9
						</button>
						<button class="btn btn-sq-lg
							poa-button-number-payment-disabled"
							onclick="set_cash(0)">0
						</button>
						<button class="btn btn-sq-lg
							poa-button-number-payment-zero-disabled"
							onclick="set_cash('zero')">Zero
						</button>
						<button class="btn btn-success btn-sq-lg
							poa-button-number-payment-enter-disabled"
							onclick="">Enter
						</button>
					</div>
				</div>
            </div>


			<div class="col-md-6 pt-2 pb-0 m-0 pr-0 pl-5"
                style="position:relative;left:70px;bottom:0">
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
								<b> {{empty($company->currency->code) ? 'MYR': $company->currency->code }}</b>
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
						{{empty($company->currency->code) ? 'MYR': $company->currency->code }}
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
						<b id="pump-number-main"></b>
					</div>
					<div class="mr-2 ml-2 mt-2"
						style="position:relative;left:15px;
							line-height:1.2; color:white;
							font-size:20px;text-align: center;">
						<b>Price</b><br>
						{{empty($company->currency->code) ? 'MYR': $company->currency->code }}
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
				style="position:relative;left:50px;top:-35px;">

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
					{{empty($company->currency->code) ? 'MYR': $company->currency->code }}
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
		<!-- end section -->


		@for($i = env('MAX_PUMPS'); $i>=1; $i--)
		<div class="row m-0 pt-2 pb-0 pl-0 pr-0"
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

			<!-- Start Section -->
			<div class="col-md-4 pt-0 pb-0 m-0 pr-0"
                style="padding-left:15px">
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
					<div class="justify-content-center align-items-center"
						style="display:flex; background-color:#f0f0f0;
						width:368px !important;justify-content: flex-end;
						border-radius:10px;height: 50px !important">
						<span id="payment-value{{$i}}"
							class="text-right pr-2 float-right"
							style="color:#a0a0a0;font-weight:500;
							font-size:30px">
							Cash Received
						</span>
					</div>
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

				<div class="justify-content-center mt-1 align-items-center
					pr-2 col-md-8 payment-div-card{{$i}}"
					style="display:flex; background-color:#f0f0f0;
					padding-top:6px; border-radius:10px;
					height: 50px !important; display:none">
					<span id=""
						class="text-center"
						style="color:#a0a0a0;font-weight:500;
						font-size:25px"><b>XXXX-XXXX-XXXX</b>
					</span>
				</div>

				<div class="justify-content-center mt-1 align-items-center
					ml-1 payment-div-card{{$i}}"
					style="display:flex;padding-top:3px;
					width:97px !important;
					background-color:#f0f0f0; border-radius:10px;
					height: 50px !important;display:none;">

					<span id="payment-value-card{{$i}}"
						class="text-center"
						style="color:black;font-weight:500;
						padding-top:5px;
						font-size:28px;padding-left: 18px;
						padding-right: 18px;">
					</span>
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
					<button class="btn btn-sq-lg
						numpad-number-payment{{$i}}
						poa-button-number-payment-disabled"
						onclick="set_cash(1)" > 1
					</button>
					<button class="btn btn-sq-lg
						numpad-number-payment{{$i}}
						poa-button-number-payment-disabled"
						onclick="set_cash(2)" > 2
					</button>
					<button class="btn btn-sq-lg
						numpad-number-payment{{$i}}
						poa-button-number-payment-disabled"
						onclick="set_cash(3)" > 3
					</button>
					<button class="btn btn-sq-lg
						numpad-number-payment{{$i}}
						poa-button-number-payment-disabled"
						onclick="set_cash(4)" > 4
					</button>
					<button	onclick="pumpCancel({{$i}})"
					class="btn btn-sq-lg
						numpad-cancel-payment{{$i}}
						poa-button-number-payment-cancel-disabled">Cancel
					</button>
					<button class="btn btn-sq-lg
						numpad-number-payment{{$i}}
						poa-button-number-payment-disabled"
						onclick="set_cash(5)" > 5
					</button>
					<button class="btn btn-sq-lg
						numpad-number-payment{{$i}}
						poa-button-number-payment-disabled"
						onclick="set_cash(6)" > 6
					</button>
					<button class="btn btn-sq-lg
						numpad-number-payment{{$i}}
						poa-button-number-payment-disabled"
						onclick="set_cash(7)" > 7
					</button>
					<button class="btn btn-sq-lg
						numpad-number-payment{{$i}}
						poa-button-number-payment-disabled"
						onclick="set_cash(8)" > 8
					</button>

					<button class="btn btn-success btn-sq-lg screend-button
						bg-virtualcabinet"
						onclick="window.open('{{route('local_cabinet')}}','_blank')"
						style="margin-left:0 !important;outline:none;
							font-size: 14px">
					<span style="">Local Cabinet</span>
					</button>

					<button class="btn btn-sq-lg
						numpad-number-payment{{$i}}
						poa-button-number-payment-disabled"
						onclick="set_cash(9)" > 9
					</button>
					<button class="btn btn-sq-lg
						numpad-number-payment{{$i}}
						poa-button-number-payment-disabled"
						onclick="set_cash(0)" > 0
					</button>
					<button class="btn btn-sq-lg
						numpad-zero-payment{{$i}}
						poa-button-number-payment-zero-disabled"
						onclick="set_cash('zero')" > Zero
					</button>
					<button class="btn btn-sq-lg
						numpad-enter-payment{{$i}}
						poa-button-number-payment-enter-disabled"
						onclick="process_enter()" > Enter
					</button>
				</div>
			</div>
		</div>

		<div class="col-md-6 pt-2 pb-0 m-0 pr-0 pl-5"
			style="position:relative;left:70px;">

			<div class="row mt-auto" style="">
				<div style="color:white;font-size:20px;
					position:relative;left:-20px; line-height:1.2;
					margin-top:7px;border-radius:10px;
					padding-left:-20px; text-align: center;">
					<b>Total<br>Amount</b><br>
					{{empty($company->currency->code) ? 'MYR': $company->currency->code }}
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
					<b>Price</b><br>{{empty($company->currency->code) ? 'MYR': $company->currency->code }}
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
							<img src='/images/product/{{$product->systemid}}/thumb/{{$product->thumbnail_1}}'
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

		<div class="col-md-2 pt-2 pb-0 m-0 mb-3 pl-0 pr-0"
			style="position:relative;left:50px;top:-35px">

			<div id="disp_ltr-{{$i}}"
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
			</div>

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
				{{empty($company->currency->code) ? 'MYR': $company->currency->code }}
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
					onclick="set_amount(2)" > 2
				</button>

				<button class="btn btn-sq-lg poa-button-number-disabled
					button-number-amount"
					onclick="set_amount(5)"> 5
				</button>
			</div>
		</div>

		<div class="row float-right">
			<div style="justify-content:flex-end;display:flex"
				class="col-md-12 float-right">
				<button class="btn btn-sq-lg poa-button-number-disabled
					button-number-amount"
					onclick="set_amount(10)"> 10
				</button>

				<button class="btn btn-sq-lg poa-button-number-disabled
					button-number-amount"
					onclick="set_amount(20)"> 20
				</button>
			</div>
		</div>
		<div class="row float-right">
			<div style="justify-content:flex-end;display:flex"
				class="col-md-12 float-right">
				<button class="btn btn-sq-lg poa-button-number-disabled
					button-number-amount"
					onclick="set_amount(50)"> 50
				</button>

				<button class="btn btn-sq-lg poa-button-number-disabled
					button-number-amount"
					onclick="set_amount(100)"> 100
				</button>
			</div>
		</div>
		<div class="row float-right">
			<div style="justify-content:flex-end;display:flex"
				class="col-md-12 float-right">
				<button class="btn btn-sq-lg poa-button-number-disabled
					button-number-amount"
					onclick="set_amount(150)"> 150
				</button>

				<button class="btn btn-sq-lg poa-button-number-disabled
					button-number-amount"
					onclick="set_amount(800)"> 800
				</button>
			</div>
		</div>
		<div class="row float-right">
			<div class="col-md-12 float-right pl-0">
				<button class="btn poa-authorize-disabled"
				style="" id="authorize-button" >Authorize
				</button>
				</div>
			</div>
		</div>
	</div>

	<hr class="" style="margin-top:0px !important;
		margin-bottom:5px !important; border:0.5px solid #a0a0a0"/>

	<div class="row" style="margin-bottom:12px">
		<div class="col-md-4">
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

				<!--
				<button class="btn opos-plain-button"
					style="float: left !important;
					pointer-events:none;cursor:normal;" id="" >
					<img src=""
					style="width:45px;height:auto;object-fit:contain;"/>
				</button>
				-->

				<button class="btn btn-success btn-sq-lg poa-button-drawer"
					style="margin-left:4px !important;
					margin-right:3px !important; font-size:15px"
					onclick="open_cashdrawer()">Drawer
				</button>

                @for($i=1 ; $i<=env('MAX_PUMPS'); $i++)
				<button class="btn btn-success poa-button-cash
					poa-button-cash-disabled"
					id="button-cash-payment{{$i}}"
					onclick="select_cash()"
					style="float:right;display:none">Cash
                </button>
                @endfor

                <button class="btn btn-success poa-button-cash
					poa-button-cash-disabled"
					id="button-cash-payment0" onclick="select_cash()"
					style="float:right">Cash
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
					border-width:0;font-size:20px;
					border-radius:10px;height: 70px !important;
					width: 145px;outline: none;text-align:center;"
					placeholder='0.00' id="custom_litre_input_0" disabled />

                @for($i=1 ; $i<=env('MAX_PUMPS'); $i++)
				<input class="hide" type="number"
					style="background-color:#f0f0f0;
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
					padding-left:0 !important;padding-right:0 !important;
					margin-left:0 !important;
					margin-right:5px !important;cursor:pointer !important;
					/*background:linear-gradient(rgb(94, 213, 60),rgb(4, 162, 249))*/">
					Preset<br>Litre
				</button>

                @for($i=1 ; $i<=env('MAX_PUMPS'); $i++)
				<button class="btn btn-success poa-button-credit-card
					poa-button-credit-card-disabled"
                    id="button-card-payment{{$i}}"
					onclick="select_credit_card()"
					style="float:right;display:none">Credit Card
                </button>
                @endfor

                <button class="btn btn-success poa-button-credit-card
					poa-button-credit-card-disabled"
					id="button-card-payment0"
					onclick="select_credit_card()"
					style="float:right">Credit Card
                </button>
			</div>

			<div style="float:left;">
			<!---
				<button class="btn phantom-button0"></button>
				<button class="btn phantom-button0"></button>
				<button class="btn phantom-button1"></button>
			-->

			<!--
				<span style="color:#fff;width: 55px !important;
					display: inline-block;">Amount
				</span>
			-->

				<input class="" type="number" style="background-color:#f0f0f0;
					border-radius:10px;height: 70px !important;
					border-width:0;font-size:20px;
					width: 145px;outline: none;text-align:center;"
					placeholder='0.00' id="custom_amount_input_0" disabled />

                @for($i=1 ; $i<=env('MAX_PUMPS'); $i++)
				<input class="hide" type="number"
					style="background-color:#f0f0f0;
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
					padding-left:0 !important;padding-right:0 !important;
					margin-left:0 !important;
					margin-right:5px !important;cursor:pointer !important;
					/*background:linear-gradient(rgb(94, 213, 60),rgb(4, 162, 249))*/">
					Preset<br>
					{{empty($company->currency->code) ? 'MYR': $company->currency->code }}
				</button>


                @for($i=1 ; $i<=env('MAX_PUMPS'); $i++)

				<button class="btn btn-success
                opos-button-combine opos-button-combine-disabled"
                    id="button-combine-payment{{$i}}"
					onclick="select_combine({{$i}})"
					style="float:right;display:none">Combine
                </button>

                @endfor

				<button class="btn btn-success
					opos-button-combine opos-button-combine-disabled"
					id="button-combine-payment0"
					onclick="select_combine()"
					style="float:right">Combine
				</button>
			</div>

		</div>
		<div class="col-md-1"></div>
		<div class="col-md-7">
		<div class="float-right mr-0 ">
			@for($i=1 ; $i<=env('MAX_PUMPS'); $i++)
			<button class="btn poa-button-pump-idle mb-1" style="float: right !important;"
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
				<div style="display:flex;border:1px none red"
					class="col-md-3 align-items-center pl-0 pr-0">
					<div id="login-message"
						style="font-size:20px;color:yellow;line-height:1.3">
					</div>
					<div style="font-size:20px;color:yellow;line-height:1.3"
						class="text-center login_error">
					</div>
				</div>

				<div style="border:1px none yellow;"
					class="col-md-6 pl-0 pr-0 text-center">
					<img style="position:relative;top:-10px;
						cursor:pointer;
						width:100%;height:390px;object-fit:contain"
						onclick="login_form_toggle()"
						src="{{ asset('images/anim_torus.gif') }}"/>
				</div>
				<div style="border:1px none green"
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

@endsection

@section('script')

@if (!empty($isLocationActive) && ( !empty($isTerminalActive) ))
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

/*
$("#dCalc").hide();
var filled = 30;
var dose = 40
if( filled < dose) {
	$("#dose").text(dose);
	$('change').text(dose - filled);
	$("#dCalc").show();
}
*/

for (i = 1; i <= {{env('MAX_PUMPS')}}; i++) {
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
		preset_type: 'amount',
		paymentStatus:	'Not Paid',
		payment_type:	'Prepaid',
		isNozzleUp:	false,
		isAuth: false
   };
}

for (i = 1; i <= {{env('MAX_PUMPS')}}; i++) {
    dis_cash['pump'+i] = {
        dis_cash:		'',
		payment_type:	'',
   };
}

for (i = 1; i <= {{env('MAX_PUMPS')}}; i++) {
    reset['pump'+i] = {
        status:		'',
		reset:	false,
   };
}

var authorizeData = {};
{
    for (i = 1; i <= {{env('MAX_PUMPS')}}; i++) {
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
		url: "{{route('uPLogin')}}",
		type: "POST",
		data: {
            hosting:hosting,
			email:email,
			@if (!empty($ONLY_ONE_HOST))
			ONLY_ONE_HOST:'ONLY_ONE_HOST',
			@endif
			password:password
		},
		'headers': {
			'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
			'X-CSRF-TOKEN': '{{ csrf_token() }}'
		},
		success: function (response) {
			if (response != null && typeof response != 'undefined' && response.systemid){
			usersystemid = response.systemid;
			username = response.fullname;
			console.log(response);
			$('#login-message').html(usersystemid+' is successfully authenticated');
			for (i = 1; i <= {{env('MAX_PUMPS')}}; i++) {
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

    @if(auth()->check())

        $('#userEditModal').modal('hide');
        $('#container-blur').css('opacity','1');

        $('#pump-main-block-0').show();
        usersystemid = "{{Auth::user()->systemid}}";
        username = "{{Auth::user()->fullname}}";

		// Only activate punp-get-status AFTER logging in
		@if (env('PUMP_HARDWARE') != NULL && !empty(Auth::user()))
			sInterval = setInterval(function(){
			  mainGetStatus();
            }, 2000);
		@endif

    @else
		clearInterval(sInterval);
		$('#userEditModal').modal({backdrop: 'static', keyboard: false})
		$('#userEditModal').modal('show');
		$('#container-blur').css('opacity',' 0.25');
    @endif
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
    for (i = 1; i <= {{env('MAX_PUMPS')}}; i++) {
        if(pumpData['pump'+i].status == "Delivering"){
            teamp = 1;
        }
    }
    if(teamp == 0){
        access_code = "";
        usersystemid = "";
        username = "";
        $('#driverfuelledger').modal('hide');
        $('#login-message').html('');
        $('#userEditModal').modal({backdrop: 'static', keyboard: false})
	    $('#userEditModal').modal('show');
	    $('#container-blur').css('opacity',' 0.25');
	    keys.length = 0
        index = 0;
        var ipaddr = "{{env('PTS_IPADDR')}}";
        for (i = 1; i <= {{env('MAX_PUMPS')}}; i++) {
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


function getPumpStatus(my_pump) {
    var ipaddr = get_hardwareip(my_pump, false);
	console.log('my_pump='+my_pump+', ipaddr='+ipaddr);

	$.ajax({
		url: '/pump-get-status/' + my_pump + '/' + ipaddr,
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

					var packet	= response.Packets[0];
					var pump_no	= packet.Data.Pump;
					var volume	= packet.Data.Volume;
					var price	= packet.Data.Price;
					var amount	= packet.Data.Amount;

					var LastTransaction = packet.Data.LastTransaction;
					var LastVolume	= packet.Data.LastVolume;
					var LastPrice	= packet.Data.LastPrice;
					var LastAmount	= packet.Data.LastAmount;
					var LastNozzle	= packet.Data.LastNozzle;
					var tx_id		= authorizeData['pump' + my_pump].transactionid;

					console.log('DD tx_id='+tx_id+', LastTransaction='+
						LastTransaction);

					// Test whether transaction has ended
					if (packet.Type == 'PumpIdleStatus' &&
						tx_id != "" &&
						tx_id != 0 &&
						LastTransaction != "undefined" &&
						LastTransaction != 0 &&
						LastTransaction == tx_id) {

						pumpData['pump' + my_pump].amount = LastAmount;
						pumpData['pump' + my_pump].volume = LastVolume;
						pumpData['pump' + my_pump].price = LastPrice;
						pumpData['pump' + my_pump].nozzle = LastNozzle;

						console.log(`AMT-${my_pump} Tx End LastAmount=${LastAmount}`);

						// Update sevenSeg displays
						$("#amount-myr-" + my_pump).sevenSeg({
							value: LastAmount
						});
						$("#volume-liter-" + my_pump).sevenSeg({
							value: LastVolume
						});
						$("#price-meter-" + my_pump).sevenSeg({
							value: LastPrice
						});
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


						if (LastAmount)
							console.log(`AMT-${pump_no} Status LastAmount=${LastAmount}`);
						
						console.log(`AMT-${pump_no} Status amount=${amount}`);

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


function removeRefund(){
    $("#dCalc").css('display','none');
}


function updatePumpStatus(type, my_pump) {
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

			if (!pumpData['pump'+my_pump].product) {
				pumpData['pump'+my_pump].payment_type = "Postpaid";
			}
		
			pumpData['pump'+my_pump].isNozzleUp = true;

		/*	
			if(pumpData['pump'+my_pump].paymentStatus == "Paid"){
				pumpData['pump'+selected_pump].payment_type == "Postpaid";
			} else {
				pumpData['pump'+selected_pump].payment_type == "Prepaid";
		}*/

			$("#amount-myr-"+my_pump).sevenSeg("destroy");
			$("#volume-liter-"+my_pump).sevenSeg("destroy");
			$("#price-meter-"+my_pump).sevenSeg("destroy");
        }

        if(my_pump == pump_number_main){
            $('#pump-status-main-'+pump_number_main).text("Delivering");

            var volume = (pumpData['pump'+my_pump].volume);
            $("#volume-liter-"+my_pump).sevenSeg({
				digits: 7,
				value: volume,
				colorOff: colorScheme.colorOff,
				colorOn: colorScheme.colorOn,
				colorBackground: colorScheme.colorBackground,
				slant: colorScheme.slant,
				decimalPlaces: colorScheme.decimalPlaces
			});

			var price = (pumpData['pump'+my_pump].price);
            $("#price-meter-"+my_pump).sevenSeg({
				digits: 7,
				value: price,
				colorOff: colorScheme.colorOff,
				colorOn: colorScheme.colorOn,
				colorBackground: colorScheme.colorBackground,
				slant: colorScheme.slant,
				decimalPlaces: colorScheme.decimalPlaces
			});

			var amount = (pumpData['pump'+my_pump].amount);
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

			getPumpStatus(my_pump);

            $('#begin-fuel-message').html("");
            $('#begin-fuel-message').html("<br>");

			if(pumpData['pump'+my_pump].paymentStatus == "Paid"){

				var dose  = pumpData['pump'+my_pump].dose;
				var price = pumpData['pump'+my_pump].price;
			
               // $('#button-cash-payment'+my_pump).addClass('poa-button-cash-disabled');
               //$('#button-cash-payment'+my_pump).removeClass('poa-button-cash');
               // $('#button-card-payment'+my_pump).addClass('poa-button-credit-card-disabled');
               // $('#button-card-payment'+my_pump).removeClass('poa-button-credit-card');
                $('#button-combine-payment'+my_pump).addClass('opos-button-combine-disabled');
                $('#button-combine-payment'+my_pump).removeClass('opos-button-combine');
                $('.numpad-number-payment'+my_pump).removeClass('poa-button-number-payment');
                $('.numpad-number-payment'+my_pump).addClass('poa-button-number-payment-disabled');
                $('.numpad-zero-payment'+my_pump).removeClass('poa-button-number-payment-zero');
                $('.numpad-zero-payment'+my_pump).addClass('poa-button-number-payment-zero-disabled');
                $('.numpad-refund-payment'+my_pump).removeClass('poa-button-number-payment-refund');
                $('.numpad-refund-payment'+my_pump).addClass('poa-button-number-payment-refund-disabled');
                $('.numpad-enter-payment'+my_pump).removeClass('poa-button-number-payment-enter');
                $('.numpad-enter-payment'+my_pump).addClass('poa-button-number-payment-enter-disabled');
				pumpData['pump'+selected_pump].isAuth = false;
                reset['pump'+my_pump].reset = true;
				console.log("RESET", reset['pump'+selected_pump].reset);

			} else if( dis_cash['pump'+my_pump].payment_type == "card"){
				pumpData['pump'+my_pump].payment_type = "Postpaid";
				select_credit_card();
				check_enter()

			} else if( dis_cash['pump'+my_pump].payment_type == "cash"){
                pumpData['pump'+my_pump].payment_type = "Postpaid";
                select_cash();
                check_enter()
			} 
			
			/*
			if(pumpData['pump'+my_pump].paymentStatus == "Paid"){

				var dose  = pumpData['pump'+my_pump].dose;
				var price = pumpData['pump'+my_pump].price;
				$("#total_amount-main-"+my_pump).
					text(parseFloat(price * dose).toFixed(2))

				//$("#total_amount-main-"+my_pump).text(parseFloat(response.price * pumpData['pump'+my_pump].dose).toFixed(2))
			}
			*/

			/*
			$("#product-display-pump-"+selected_pump).css('display','none');
			$('#fuel-grad-name-' + selected_pump).text("");
			$('#fuel-grad-thumb-' + selected_pump).css('display', 'none');
			 */
            //SAMPLE OF filled less than dose
			if (pumpData['pump'+selected_pump].preset_type == 'Litre') {
				re_dose  = parseFloat($("#total_amount-main-"+selected_pump).text())
			} else {
				re_dose  = parseFloat(pumpData['pump'+selected_pump].dose)
			}

			//re_dose  = parseFloat($("#total_amount-main-"+selected_pump).text())
			//var dose = parseFloat(pumpData['pump'+my_pump].dose);

			var dose = re_dose;
			var filled = parseFloat(pumpData['pump'+my_pump].amount);
			var refund = dose - filled;

			console.log('dose='+dose.toString()+
				', filled='+filled.toString()+
				', refund='+refund.toString());

			/* Refund detection: This is where you detect if there is a
			   refund condition */

            if (pumpData['pump'+my_pump].payment_type != "Postpaid") {
				if( filled < dose) {
					console.log('refund='+refund.toString());
					$('#dose').text(dose);
					$('#change').text(refund.toString());
					display_refund(my_pump)
				}
			}
        }

		$('#pump-status-'+my_pump).text('Idle');
		$('#pump-button-'+my_pump).attr('class', '');
		$('#pump-button-'+my_pump).addClass('btn poa-button-pump-idle');

        if(my_pump == pump_number_main){
            $('#pump-status-main-'+pump_number_main).text("Idle");

            var volume = (pumpData['pump'+my_pump].volume);
            $("#volume-liter-"+my_pump).sevenSeg({
				digits: 7,
				value: volume,
				colorOff: colorScheme.colorOff,
				colorOn: colorScheme.colorOn,
				colorBackground: colorScheme.colorBackground,
				slant: colorScheme.slant,
				decimalPlaces: colorScheme.decimalPlaces
			});

			var price = (pumpData['pump'+my_pump].price);
            $("#price-meter-"+my_pump).sevenSeg({
				digits: 7,
				value: price,
				colorOff: colorScheme.colorOff,
				colorOn: colorScheme.colorOn,
				colorBackground: colorScheme.colorBackground,
				slant: colorScheme.slant,
				decimalPlaces: colorScheme.decimalPlaces
			});

			var amount = (pumpData['pump'+my_pump].amount);
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

	$(`#payment-status-${my_pump}`).html(pumpData['pump'+my_pump].paymentStatus)
}


function pump_selected(pump_id)
{
	console.log("pump_selected");
	disable_payment_btns();
    $('#pump-main-block-'+selected_pump).hide();
    $('#pump-main-block-'+pump_id).show();
    //$('#button-cash-payment'+selected_pump).hide();
    //$('#button-cash-payment'+pump_id).show();
    //$('#button-card-payment'+selected_pump).hide();
    //$('#button-card-payment'+pump_id).show();
    $('#button-combine-payment'+selected_pump).hide();
    $('#button-combine-payment'+pump_id).show();

    $('#custom_amount_input_'+selected_pump).hide();
    $('#custom_amount_input_'+pump_id).show();

    $('#custom_litre_input_'+selected_pump).hide();
    $('#custom_litre_input_'+pump_id).show();
    if(selected_pump==0){
	}

	selected_pump = pump_id;

	var status = pumpData['pump'+pump_id].status;

	
	@if (env('PUMP_HARDWARE') != NULL && !empty(Auth::user()))
		$("#amount-myr-"+selected_pump).sevenSeg("destroy");
		$("#volume-liter-"+selected_pump).sevenSeg("destroy");
		$("#price-meter-"+selected_pump).sevenSeg("destroy");
 	@endif

	var volume = (pumpData['pump'+pump_id].volume);
	$("#volume-liter-"+pump_id).sevenSeg({
		digits: 7,
		value: volume,
		colorOff: colorScheme.colorOff,
		colorOn: colorScheme.colorOn,
		colorBackground: colorScheme.colorBackground,
		slant: colorScheme.slant,
		decimalPlaces: colorScheme.decimalPlaces
	});

	var price = (pumpData['pump'+pump_id].price);
	$("#price-meter-"+pump_id).sevenSeg({
		digits: 7,
		value: price,
		colorOff: colorScheme.colorOff,
		colorOn: colorScheme.colorOn,
		colorBackground: colorScheme.colorBackground,
		slant: colorScheme.slant,
		decimalPlaces: colorScheme.decimalPlaces
	});

	var amount = (pumpData['pump'+pump_id].amount);
	$("#amount-myr-"+pump_id).sevenSeg({
		digits: 7,
		value: amount,
		colorOff: colorScheme.colorOff,
		colorOn: colorScheme.colorOn,
		colorBackground: colorScheme.colorBackground,
		slant: colorScheme.slant,
		decimalPlaces: colorScheme.decimalPlaces
	});

	$('#pump-number-main-'+pump_id).text(pump_id);
	$('#pump-status-main-'+pump_id).text(status);

	
	if (pumpData['pump'+pump_id].preset_type == 'Litre') {
		console.log(`PRESET_TYPE ${pumpData['pump'+pump_id].preset_type}`);
		$('#total_volume-main-'+pump_id).text(pumpData['pump'+pump_id].dose);
		price  = pumpData['pump'+pump_id].price_liter;
		$('#total_amount-main-'+pump_id).text( parseFloat(pumpData['pump'+pump_id].dose * price).toFixed(2) );
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
		reset['pump'+selected_pump].reset = false;
		reset['pump'+selected_pump].status = "";
		//pumpData['pump'+selected_pump].dose = "0.00";
		//$('#total_amount-main-'+selected_pump).text(pumpData['pump'+pump_id].dose);
		//$("#product-select-pump-"+selected_pump).css('display','none');
		//$('#fuel-grad-name-' + selected_pump).text("");
		//$('#fuel-grad-thumb-' + selected_pump).css('display', 'none');
		//pumpData['pump' + selected_pump].product = "";
		$("#payment-type-paid-right"+selected_pump).html("");
		$("#payment-type-amount"+selected_pump).html("");
		$("#payment-amount-card-amount"+selected_pump).html("");
    	$("#payment-type-amount"+selected_pump).html("");
		pumpData['pump'+selected_pump].payment_type = "Prepaid";
		pumpData['pump'+selected_pump].receipt_id = '';
		//display_litre_preset(false, selected_pump);
		//removePaymentState(selected_pump)
		disable_payment_btns();
		//pumpData['pump'+selected_pump].preset_type = "{{empty($company->currency->code) ? 'MYR': $company->currency->code }}"
		$(`#custom_litre_input_${selected_pump}`).removeAttr('disabled');
		$(`#custom_amount_input_${selected_pump}`).removeAttr('disabled');
		pumpData['pump'+selected_pump].isNozzleUp = false;
		pumpData['pump'+selected_pump].isAuth = false;
	}

	console.log('PS isAuth', pumpData['pump'+selected_pump].isAuth);
	console.log('PS isNozzleUp', pumpData['pump'+selected_pump].isNozzleUp);
	console.log('PS paymentStatus', pumpData['pump'+selected_pump].paymentStatus);

	if (status == 'Delivering' ||  
			(pumpData['pump'+selected_pump].isAuth == true && 
			pumpData['pump'+selected_pump].isNozzleUp == false &&  
			pumpData['pump' + selected_pump].paymentStatus == "Paid") ||
			(pumpData['pump'+selected_pump].isNozzleUp == true && 
			(pumpData['pump'+selected_pump].isAuth == true &&
			pumpData['pump' + selected_pump].paymentStatus != "Not Paid" ))) {

		$('.button-number-amount').removeClass('poa-button-number');
		$('.button-number-amount').addClass('poa-button-number-disabled');

		cnsole.log("PS Preset disabled");


	} else {
		$('.button-number-amount').removeClass('poa-button-number-disabled');
		$('.button-number-amount').addClass('poa-button-number');
		console.log("PS preset enabled");
	}

	if (status != 'Delivering' && pumpData['pump'+selected_pump].isAuth == true &&
		pumpData['pump' + selected_pump].paymentStatus == "Not Paid")
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
	
	
}


function set_amount(amount)
{
    var total_amount = $('#total_amount-main-'+selected_pump).text();
    amount = parseFloat(amount);
    total_amount = parseFloat(total_amount);
    amount = amount.toFixed(2);

    //if(total_amount == 0) {
        $('#authorize-button').attr('class', '');
	    $('#authorize-button').addClass('btn poa-authorize');
    //}

	pumpData['pump'+selected_pump].preset_type = "{{empty($company->currency->code) ? 'MYR': $company->currency->code }}"
    pumpData['pump'+selected_pump].dose = amount;

	reset_previous_tx_history();

	display_litre_preset(false, selected_pump)
    $('#total_amount-main-'+selected_pump).text(amount);

	console.log('AUTH set_amount('+amount+')');
    $("#authorize-button").click(pump_authorize);
}


var isClickPumpAuth = false;
//function 
var pump_authorize = debounce(function () {
	if (isClickPumpAuth == true)
		return

	isClickPumpAuth = true;
   
	pumpData['pump'+selected_pump].isAuth = true;
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
	
	if (pumpData['pump'+selected_pump].preset_type == 'Litre')
		$("#total_amount-main-"+selected_pump).text('0.00');

	console.log('AUTH pump_authorize: selected_pump='+selected_pump);

    var dose = pumpData['pump'+selected_pump].dose;

	pumpData['pump'+selected_pump].product =	'';
	pumpData['pump'+selected_pump].product_id	=	'';
	pumpData['pump'+selected_pump].product_thumbnail = '';

	$('#fuel-grad-name-' + selected_pump).text(pumpData['pump' + selected_pump].product );
	$('#fuel-grad-thumb-' + selected_pump).attr('src', pumpData['pump'+ selected_pump].product_thumbnail );
	$('#fuel-grad-thumb-' + selected_pump).css('display', 'none');

	console.log('AUTH pump_authorize: pumpData='+
		JSON.stringify(pumpData['pump'+selected_pump]));

	if (pumpData['pump'+selected_pump].preset_type == 'Litre')
		var type = 'Volume';
	else
 	   var type = "Amount";

    var ipaddr = "{{env('PTS_IPADDR')}}";
    //var ipaddr = get_hardwareip(selected_pump, true);

	console.log('pump_authorize: BEFORE dose='+dose);
    dose = parseFloat(dose);
	console.log('pump_authorize: AFTER  dose='+dose);

    pump_number = parseInt(selected_pump);
    $('#pump-status-'+pump_number).text('Authorizing');
    reset['pump'+selected_pump].status = "Delivering";

	var url = '/pump-authorize/' + pump_number + '/' + type + '/' + dose + '/' + ipaddr;

	console.log('AUTH pump_authorize: url='+url);

	if (ipaddr == undefined) {
		return;
	}

    if(!pump_number) {
        //alert("select Pump");

    }else{
	//addPaymentState(dose, pump_number);
	$.ajax({
		url: url,
		type: "GET",
		dataType: "JSON",
		success: function (resp) {
			if (resp != null && typeof resp != 'undefined') {
				resp = resp.data;
				if (typeof resp.response != 'undefined' &&
					resp.response != null) {
					var response = resp.response;

					//console.log(response);

					if (response.hasOwnProperty('Packets') &&
						typeof response.Packets[0] != 'undefined') {
						var packet = response.Packets[0];
						var pump_no = packet.Data.Pump;
						var transactionid = packet.Data.Transaction;
						if(transactionid){
							authorizeData['pump'+pump_no].transactionid = transactionid;
							authorizeData['pump'+pump_no].amount = dose;
						}
					}
				}
			}
			pumpData['pump'+selected_pump].amount = "0.00";
			$("#amount-myr-"+selected_pump).sevenSeg("destroy");
			$("#volume-liter-"+selected_pump).sevenSeg("destroy");
			$("#price-meter-"+selected_pump).sevenSeg("destroy");
			console.log(JSON.stringify(resp));
			isClickPumpAuth = false;
		},
		error: function (resp) {
			console.log('ERROR: ' + JSON.stringify(resp));
		}
	});
    }

	pumpData['pump'+selected_pump].volume = "0.00";
	pumpData['pump'+selected_pump].amount = "0.00";
	pumpData['pump'+selected_pump].price = "0.00";
	pumpData['pump'+selected_pump].paymentStatus = "Not Paid";
	enable_payment_btns();
    $('.numpad-cancel-payment'+selected_pump).removeClass('poa-button-number-payment-cancel-disabled');
    $('.numpad-cancel-payment'+selected_pump).addClass('poa-button-number-payment-cancel');
	$(`#custom_litre_input_${selected_pump}`).attr('disabled', true);
	$(`#custom_amount_input_${selected_pump}`).attr('disabled', true);
	//terminalSyncData(selected_pump);
},  1000);

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
        $("#payment-div-cash"+selected_pump+" div").addClass("justify-content-center");
        dis_cash['pump'+selected_pump].payment_type = "cash";
        dis_cash['pump'+selected_pump].dis_cash = "";
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
		
        if( !pumpData['pump' + selected_pump].product){
        	$('#fuel-grad-name-' + selected_pump).text("");
			$('#fuel-grad-thumb-' + selected_pump).css('display', 'none');
			$("#product-select-pump-"+selected_pump).css('display','flex');
			if (pumpData['pump'+selected_pump].preset_type == 'Litre' && pumpData['pump'+selected_pump].payment_type == "Prepaid" ) 
				numpad_disable();
        }
    }
}


function select_credit_card(){
    if(selected_pump!=0){
        numpad_enable();
	
		if (pumpData['pump'+selected_pump].preset_type == 'Litre') {
			rev_cash  = parseFloat($("#total_amount-main-"+selected_pump).text())
		} else {
			rev_cash  = parseFloat(pumpData['pump'+selected_pump].dose)
		}

        dis_cash['pump'+selected_pump].dis_cash = "";
        dis_cash['pump'+selected_pump].payment_type = "card";
        $("#payment-value-card"+selected_pump).html("");
        $("#payment-div-cash"+selected_pump).hide();
        $(".payment-div-refund"+selected_pump).hide();
        $("#payment-div-cash-card"+selected_pump).hide();
        $(".payment-div-card"+selected_pump).show();
        if( pumpData['pump'+selected_pump].payment_type == "Postpaid"){
            var amount = pumpData['pump'+selected_pump].amount;
            amount = ((5 * Math.round((amount*100) / 5))/100).toFixed(2);
            $("#payment-type-amount"+selected_pump).html(amount);
            $("#payment-type-message"+selected_pump).html("Rounding");
            $("#payment-type-paid-right"+selected_pump).html(( amount-pumpData['pump'+selected_pump].amount).toFixed(2));
        }else{
            $("#payment-type-amount"+selected_pump).html(rev_cash.toFixed(2));
            //$("#payment-type-amount"+selected_pump).html(pumpData['pump'+selected_pump].dose);
        }
        $('.numpad-enter-payment'+selected_pump).removeClass('poa-button-number-payment-enter');
        $('.numpad-enter-payment'+selected_pump).addClass('poa-button-number-payment-enter-disabled');
        $("#payment-amount-card-amount"+selected_pump).show();
        $("#payment-amount-card-amount"+selected_pump).html("Amount");
		
		//if(pumpData['pump'+selected_pump].amount == "0.00" && !pumpData['pump' + selected_pump].product){
		
		if(!pumpData['pump' + selected_pump].product){
			$('#fuel-grad-name-' + selected_pump).text("");
			$('#fuel-grad-thumb-' + selected_pump).css('display', 'none');	
			$("#product-select-pump-"+selected_pump).css('display','flex');
			if (pumpData['pump'+selected_pump].preset_type == 'Litre') 
				numpad_disable();
        }
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
        //if(pumpData['pump'+selected_pump].amount == "0.00" && !pumpData['pump' + selected_pump].product){
       // if(!pumpData['pump' + selected_pump].product){
        $('#fuel-grad-name-' + selected_pump).text("");
		$('#fuel-grad-thumb-' + selected_pump).css('display', 'none');
        $("#product-select-pump-"+selected_pump).css('display','flex');
        //}
    }
}


function set_cash(amount){
	console.log(`PT tx type: ${pumpData['pump'+selected_pump].payment_type}`);
	console.log(`PT payment type: ${dis_cash['pump'+selected_pump].payment_type}`);

	if (pumpData['pump'+selected_pump].preset_type == 'Litre') {
		rev_cash  = parseFloat($("#total_amount-main-"+selected_pump).text())
		if (rev_cash == 0 && !pumpData['pump'+selected_pump].product) {
			return;	
		}
	}


	
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
				if (pumpData['pump'+selected_pump].preset_type == 'Litre') {
					rev_cash  = parseFloat($("#total_amount-main-"+selected_pump).text().replace(/,/g,''))
				} else {
					rev_cash  = parseFloat(pumpData['pump'+selected_pump].dose)
				}

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
				}
            }
        }

    } else if ( dis_cash['pump'+selected_pump].payment_type == "card"){
        if(dis_cash['pump'+selected_pump].dis_cash.length==4){
			if(pumpData['pump' + selected_pump].product){
			$('.numpad-enter-payment'+selected_pump).
				removeClass('poa-button-number-payment-enter-disabled');

			$('.numpad-enter-payment'+selected_pump).
				addClass('poa-button-number-payment-enter');
			}
		}
    }
}

var isClickProcessEnter = false;
function process_enter()
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

	if(payment_type == "card"){
		creditcard_no = dis_cash['pump'+selected_pump].dis_cash;
	}else{
		cash_received =  (parseFloat(dis_cash['pump'+selected_pump].dis_cash)/100).toFixed(2);
	}

	if (pumpData['pump'+selected_pump].preset_type == 'Litre' && pumpData['pump'+selected_pump].payment_type != "Postpaid") {
		qty = parseFloat(pumpData['pump'+selected_pump].dose);;
		amount = $(`#total_amount-main-${selected_pump}`).text().replace(/,/g,'');
		if (price == 0) {
			price =  pumpData['pump'+selected_pump].price_liter
		}
	}

	var data = {
		"pump_no":selected_pump,
		"dose":dose,
		"cash_received":cash_received,
		"change_amount":change_amount,
		"payment_type":payment_type,
		"terminal_id":{!!$terminal->id??"''"!!},
		"tax_percent":{!!$terminal->tax_percent??"''"!!},
		"creditcard_no":creditcard_no,
		"company_id":{!!$company->id??"''"!!},
		"currency":"{{$company->currency->code??''}}",
		"mode":"{{$terminal->mode??''}}",
		"product_id":product_id,
		"product":product,
		"qty":qty,
		"price":price,
		"receipt_id":0,
	};

	if (pumpData['pump'+selected_pump].preset_type == 'Litre')
		url = "{{route('local_cabinet.receipt.create.L')}}"
	else
		url = "{{route('local_cabinet.receipt.create')}}"

	'use strict';
	let _this = this;
	$.ajax({
        url: "{{route('local_cabinet.receipt.create')}}",
        type: 'post',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
	
	reset['pump'+selected_pump].reset = true;
	isClickProcessEnter = false;
}


function void_receipt(pump_no) {
	receipt_id = pumpData['pump'+pump_no].receipt_id;
	
	if (receipt_id == '') {
		return;
	}

	$.ajax({
        url: "{{route('local_cabinet.receipt.void')}}",
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


function select_combine(){
    if( pumpData['pump'+selected_pump].payment_type == "Postpaid"){
		var dose = pumpData['pump'+selected_pump].amount;
		// amount_total = ((5 * Math.round((amount_total*100) / 5))/100).toFixed(2);
	}else{
		var dose = parseFloat(pumpData['pump'+selected_pump].dose);
	}

    var product_id = pumpData['pump' + selected_pump].product_id;
    if(product_id){
        window.open('{{route("index.cstore")}}'+'/'+dose+'/'+product_id, '_blank');
    }
}


function calculate_change() {
	dis_cash_ = (parseFloat(dis_cash['pump'+selected_pump].dis_cash)/100).toFixed(2);
	if (pumpData['pump'+selected_pump].payment_type == "Postpaid"){
		var amount_total = pumpData['pump'+selected_pump].amount;
		amount_total = ((5 * Math.round((amount_total*100) / 5))/100).toFixed(2);
		var change_amount = dis_cash_ - amount_total;
		$("#payment-type-message"+selected_pump).html("Rounding");
		$("#payment-type-paid-right"+selected_pump).html(( amount_total-pumpData['pump'+selected_pump].amount).toFixed(2));
	} else {
		var change_amount = dis_cash_ - parseFloat($('#total_amount-main-'+selected_pump).text().replace(/,/g,''))
	}

	$("#payment-amount-card-amount"+selected_pump).show();
	$("#payment-amount-card-amount"+selected_pump).html("Change");
	$("#payment-type-amount"+selected_pump).html(parseFloat(change_amount).toFixed(2));
}


function numpad_enable(){
    $('.numpad-number-payment'+selected_pump).removeClass('poa-button-number-payment-disabled');
    $('.numpad-number-payment'+selected_pump).addClass('poa-button-number-payment');
    $('.numpad-zero-payment'+selected_pump).removeClass('poa-button-number-payment-zero-disabled');
    $('.numpad-zero-payment'+selected_pump).addClass('poa-button-number-payment-zero');
    // $('.numpad-cancel-payment'+selected_pump).removeClass('poa-button-number-payment-cancel-disabled');
    // $('.numpad-cancel-payment'+selected_pump).addClass('poa-button-number-payment-cancel');
    //$('.numpad-refund-payment'+selected_pump).removeClass('poa-button-number-payment-refund-disabled');
    //$('.numpad-refund-payment'+selected_pump).addClass('poa-button-number-payment-refund');
    $('.numpad-enter-payment'+selected_pump).removeClass('poa-button-number-payment-enter-disabled');
    $('.numpad-enter-payment'+selected_pump).addClass('poa-button-number-payment-enter');
}


function numpad_disable(){
    $('.numpad-number-payment'+selected_pump).removeClass('poa-button-number-payment');
    $('.numpad-number-payment'+selected_pump).addClass('poa-button-number-payment-disabled');
    $('.numpad-zero-payment'+selected_pump).removeClass('poa-button-number-payment-zero');
    $('.numpad-zero-payment'+selected_pump).addClass('poa-button-number-payment-zero-disabled');
   // $('.numpad-cancel-payment'+selected_pump).removeClass('poa-button-number-payment-cancel');
   // $('.numpad-cancel-payment'+selected_pump).addClass('poa-button-number-payment-cancel-disabled');
    $('.numpad-refund-payment'+selected_pump).removeClass('poa-button-number-payment-refund');
    $('.numpad-refund-payment'+selected_pump).addClass('poa-button-number-payment-refund-disabled');
    $('.numpad-enter-payment'+selected_pump).removeClass('poa-button-number-payment-enter');
    $('.numpad-enter-payment'+selected_pump).addClass('poa-button-number-payment-enter-disabled');
}


function enable_payment_btns() {
	console.log('enable');
	$('#button-cash-payment'+selected_pump).removeClass('poa-button-cash-disabled');
	$('#button-cash-payment'+selected_pump).addClass('poa-button-cash');
	$('#button-card-payment'+selected_pump).removeClass('poa-button-credit-card-disabled');
	$('#button-card-payment'+selected_pump).addClass('poa-button-credit-card');
	$('#button-combine-payment'+selected_pump).removeClass('opos-button-combine-disabled');
	$('#button-combine-payment'+selected_pump).addClass('opos-button-combine');
	$('#button-cash-card-payment').removeClass('poa-button-cash-card-disabled');
	$('#button-cash-card-payment').addClass('poa-button-cash-card');

}


function disable_payment_btns() {
	console.log('disable');
	$('#button-cash-payment'+selected_pump).addClass('poa-button-cash-disabled');
	$('#button-cash-payment'+selected_pump).removeClass('poa-button-cash');
	$('#button-card-payment'+selected_pump).addClass('poa-button-credit-card-disabled');
	$('#button-card-payment'+selected_pump).removeClass('poa-button-credit-card');
	$('#button-combine-payment'+selected_pump).addClass('opos-button-combine-disabled');
	$('#button-combine-payment'+selected_pump).removeClass('opos-button-combine');
	$('#button-cash-card-payment').addClass('poa-button-cash-card-disabled');
	$('#button-cash-card-payment').removeClass('poa-button-cash-card');
	numpad_disable();
}


//Click on cancel button event
function pumpCancel(my_pump){
	cancelAuthorize(my_pump);
    set_amount(0);
    disable_payment_btns();
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

	void_receipt(my_pump);

    reset['pump'+my_pump].reset = true;
}


async function cancelAuthorize(pumpNo) {
    var ipaddr = "{{env('PTS_IPADDR')}}";
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


const pumpHardwareIp = Object.values({!!json_encode($pump_hardware->toArray(), true)!!});

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
			returnData = response;
			$("#product-select-pump-"+my_pump).css('display','none');
			$('#fuel-grad-name-' + my_pump).text(product);
			$('#fuel-grad-thumb-' + my_pump).attr('src', thumb);
			$('#fuel-grad-thumb-' + my_pump).css('display', 'inline-flex');
			pumpData['pump' + my_pump].product = product;
            pumpData['pump' + my_pump].product_id = productid;
			pumpData['pump'	+ my_pump].product_thumbnail = thumb;
			
			console.log(`PL preset_type ${pumpData['pump'+selected_pump].preset_type}`);
			if (pumpData['pump'+selected_pump].preset_type == 'Litre') {
				console.log(`PL price ${response.price}`);
				pumpData['pump'+my_pump].price_liter = response.price; 
				$("#total_amount-main-"+my_pump).text(parseFloat(response.price * pumpData['pump'+my_pump].dose).toFixed(2))
				if (pumpData['pump'+selected_pump].payment_type == "Prepaid") {

					if (pumpData['pump'+selected_pump].status != "Delivering") {
						if (dis_cash['pump'+selected_pump].payment_type == "card") {
							$("#payment-type-amount"+selected_pump).html($("#total_amount-main-"+my_pump).text());
						}

						console.log("NUM_PAD enable");
						numpad_enable();
					} else {
						$("#payment-type-amount"+selected_pump).html('');
					}
					
						$('.numpad-enter-payment'+selected_pump).
							addClass('poa-button-number-payment-enter-disabled');

						$('.numpad-enter-payment'+selected_pump).
							removeClass('poa-button-number-payment-enter');

				}
			}

			//terminalSyncData(selected_pump);
			combine_btn(true);
		},
		error: function (response) {
			console.log(JSON.stringify(response));
		}
	});

	return returnData;
}

function getNozzleNo(pump_no, product_id, isAlert = false) {
	@if (!empty($nozzleFuelData))
		const nozzleFuelData = Object.values(
			{!!json_encode($nozzleFuelData->toArray(), true)!!});

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
	@else
		return false;
	@endif
}

function getFuelGradeId(product_id) {
	@if (!empty($fuel_grade_string))
	
		const fuelGradeData = Object.values(
			{!!json_encode($fuel_grade_string, true)!!});

		let find_product = fuelGradeData.find( (e) => e.og_f_id == product_id);
		if (find_product) {
			return find_product.Id
		} else {
			return false;
		}

	@else
			return false;
	@endif
}

function selectProduct(pump_no, product_id, product, thumb) {

	var nozzle = getNozzleNo(pump_no, product_id, true);
	var fuel_grade_id = getFuelGradeId(product_id)

	if (nozzle && fuel_grade_id) {
		pumpData['pump' + pump_no].product = product;
        pumpData['pump' + pump_no].product_id = product_id;
		pumpData['pump'	+ pump_no].product_thumbnail = thumb;
        check_enter();

		if (pumpData['pump'+selected_pump].preset_type == 'Litre')
			var type = "Volume";
		else
			var type = "Amount";

		var dose = pumpData['pump'+pump_no].dose;
    	var ipaddr = "{{env('PTS_IPADDR')}}";

        $("#fuel-grad-thumb-"+pump_no).attr('src', thumb);

		nozzle = JSON.stringify(nozzle.map((e) => e.nozzle_no));
			
/*
		tx_id =	authorizeData['pump'+pump_no].transactionid
		$.ajax({
			// This is the real Emergency Stop
			url: `/pump-close-transaction/${pump_no}/${tx_id}`,
			type: "GET",
			dataType: "JSON",
			success: function (data) {
				console.log('KK ***** cancelAuthorize() *****');
			},
			error: function (e) {
				console.error('KK error:'+JSON.stringify(e));
			}
		});
*/
		$.ajax({
			url: '/pump-authorize-fuel-grade/' + pump_no + '/' + type +
				'/' + dose + '/' + ipaddr + '/null/' + fuel_grade_id,
			type: "GET",
			dataType: "JSON",
			success: function (response) {
                $("#product-select-pump-"+pump_no).css('display','none');
				$('#fuel-grad-name-' + pump_no).text(product);
				$('#fuel-grad-thumb-' + pump_no).css('display', 'inline-flex');
			},
			error: function (response) {
				console.log(JSON.stringify(response));
			}
		});

		product_info = initFuelProduct(pump_no, nozzle).price;

	}
}


/*
function refundactive(filled, dose, my_pump){
    if(pumpData['pump'+my_pump].paymentStatus == "Paid"){
        filled = parseFloat(filled).toFixed(2);
        dose = parseFloat(dose).toFixed(2);
        $('.numpad-refund-payment'+my_pump).addClass('poa-button-number-payment-refund');
        $('.numpad-refund-payment'+my_pump).removeClass('poa-button-number-payment-refund-disabled');
        $("#payment-div-cash"+my_pump).hide();
        $(".payment-div-card"+my_pump).hide();
        $(".payment-div-refund"+my_pump).show();
        $("#payment-type-message"+my_pump).html("Paid");
        $("#payment-type-paid-right"+my_pump).html(dose);
        $("#payment-amount-card-amount"+my_pump).show();
        $("#payment-amount-card-amount"+my_pump).html("Filled");
        $("#payment-type-amount"+my_pump).html(filled);

        var amount = dose - filled;
        amount = parseFloat(amount).toFixed(2);
        $("#payment-div-refund-amount-bl-message"+my_pump).html("Refund");
        $("#payment-div-refund-amount"+my_pump).html(amount);
        $('#authorize-button').attr('class', '');
        $('#authorize-button').addClass('btn poa-authorize-disabled');
        $('.button-number-amount').removeClass('poa-button-number');
        $('.button-number-amount').addClass('poa-button-number-disabled');
    }
}
*/


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


function combine_btn(isEnable) {
	if (isEnable)
		$("#button-combine-payment").removeClass("opos-button-combine-disabled");
	else
		$("#button-combine-payment").addClass("opos-button-combine-disabled");
}


function display_refund(my_pump) {
    var refund_amt =  $(this).attr('change').innerHTML;
	var r_id =  pumpData['pump'+my_pump].receipt_id;

    console.log('display_refund: r_id='+r_id);

	// Protect receipt_id for being empty
	if (r_id == '' || r_id == undefined) {
		return;
	}

    var data = {
		'receipt_id':r_id,
		'refund_amt': refund_amt
	}

    console.log('display_refund: data='+JSON.stringify(data));

    $.ajax({
        url: "{{route('local_cabinet.nozzle.down.refund')}}",
        type: 'post',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
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

		for (i = 1; i <= {{env('MAX_PUMPS')}}; i++) {
			pumpData['pump'+i].amount =
				String(parseFloat(pd['pump'+i].amount).toFixed(1));
		}
	}

	if (localStorage.reset != undefined)
		reset = JSON.parse(localStorage.reset);
}


var pumpStateInterval;


function select_custom_amount() {
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
	pumpData['pump'+selected_pump].dose = 0;

	var total_litre = $('#total_amount-main-'+selected_pump).text();
    total_litre = parseFloat(total_litre);
    litre = litre.toFixed(2);

    if(litre == 0) {
		$("#product-select-pump-"+selected_pump).css('display','none');
     } else if (litre > 0) {
		pumpData['pump'+selected_pump].amount = "0.00"
		pumpData['pump'+selected_pump].preset_type = "Litre"
		pumpData['pump'+selected_pump].dose = litre;
		
		$('#total_volume-main-'+selected_pump).text(litre);
		$("#custom_litre_input_"+selected_pump).val('');
		$("#custom_litre_input_"+selected_pump+"_buffer").val('');
		display_litre_preset(true, selected_pump);

        $('#authorize-button').attr('class', '');
	    $('#authorize-button').addClass('btn poa-authorize');

		console.log('AUTH set_litre('+litre+')');
    	$("#authorize-button").click(pump_authorize);

	 } else {
		 $("#custom_litre_input_"+selected_pump).val('');
		 $("#custom_litre_input_"+selected_pump+"_buffer").val('');
	   	 display_litre_preset(false, selected_pump)
	 }
	
	reset_previous_tx_history();
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

}

@for($i=1 ; $i<=env('MAX_PUMPS'); $i++)
	filter_price("#custom_litre_input_{{$i}}","#custom_litre_input_{{$i}}_buffer");
	filter_price("#custom_amount_input_{{$i}}","#custom_amount_input_{{$i}}_buffer");
	
	$("#custom_amount_input_{{$i}}").on("keyup", (e)  => {

		val = $("#custom_amount_input_{{$i}}").val();

		$("#custom_litre_input_{{$i}}_buffer").val('');
		$("#custom_litre_input_{{$i}}").val('');
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
	
	$("#custom_litre_input_{{$i}}").on("keyup", (e)  => {

		val = $("#custom_litre_input_{{$i}}").val();

		$("#custom_amount_input_{{$i}}_buffer").val('');
		$("#custom_amount_input_{{$i}}").val('');
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

@endfor
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
		 pump_selected(pump_no);
	}


	function clear_local_storage() {
		clearInterval(pumpStateInterval);
		localStorage.removeItem('reset');
		localStorage.removeItem('pumpDataState');
		window.location.reload();
	}


	function suspend_tab() {
		clearInterval(pumpStateInterval)
	}


	function activate_tab() {
		restoreOldState()
		pumpStateInterval = setInterval( () => {
			localStorage.pumpDataState = JSON.stringify(pumpData);
			localStorage.reset = JSON.stringify(reset);
			//getTerminalSyncData(selected_pump);
		}, 250);
		if (selected_pump != 0)
			pump_selected(selected_pump);
	}

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
					console.log("NM Tab Active");
					console.log("NM pumpdata", pumpData)

					if( selected_pump != 0) {
						if(pumpData['pump'+selected_pump].status == "Delivering") {
							getPumpStatus(selected_pump) 
						}
					}

					activate_tab();
				}

				document_hidden = document[hidden];
			}
		});
	});

	activate_tab();


	function truncateToDecimals(num, dec = 2) {
	/*	num = String(num).trim();
		length = num.split('.')[0].length;
		return Number(num).toPrecision(length + 2);
	 */

		return num;
	}


	var isTerminalSyncData = false;
	function terminalSyncData(pump_no) {
		data = {};
		
		isTerminalSyncData = true;
		if (pumpData[`pump${pump_no}`].product_id) {
			data['product_id'] = pumpData[`pump${pump_no}`].product_id;
		}

		data['pump_no'] = pump_no;
		data['payment_status'] = pumpData[`pump${pump_no}`].paymentStatus;
		data['dose'] = pumpData[`pump${pump_no}`].dose;
		data['price'] = pumpData[`pump${pump_no}`].price;

		if (pumpData[`pump${pump_no}`].preset_type == "Litre")
			data['litre'] = 1;
		else
			data['litre'] = 0;

		$.post( '{{route('sync_data')}}', data).
			done( () => isTerminalSyncData = false).
			fail( (e) => console.log(e));
	}

 getTerminalSyncData = debounce(function (pump_no) {
		if (isTerminalSyncData)
			return;
		$.post('{{route('get_sync_data')}}', {
			pump_no: pump_no
		}).done( (res) => {
			if (!res)
				return;

			dose = res.dose;
			price = res.price;
			litre = res.litre;
			
			image = `/images/product/${res.psystemid}/thumb/${res.thumbnail_1}`;

			pumpData[`pump${pump_no}`].price 		= price;
			pumpData[`pump${pump_no}`].price_liter	= price;
			pumpData[`pump${pump_no}`].dose 		= dose;
			pumpData[`pump${pump_no}`].product_thumbnail = images;
			pumpData[`pump${pump_no}`].product_id = res.product_id;
			pumpData[`pump${pump_no}`].product = res.pname;

			if (litre == 1)
				pumpData[`pump${pump_no}`].preset_type = "Litre";
			else
				pumpData[`pump${pump_no}`].preset_type = "amount";

		});
	},700);
</script>
@else
@include('landing.license')
@endif

@endsection
