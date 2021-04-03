<!--Modal EodPrint-->

<!--Modal Body Starts-->
<div class="modal-body" style="font-size: 14px;">
    <!--Section 1 starts-->
    <div class="row" style="text-align:center;">
        <div class="col-md-12 text-center  pr-5 pl-5" style="font-size: 15px">
            <strong>
                {{!empty($company->name)?$company->name:"Ocosystem Ltd"}}
                ({{!empty($company->business_reg_no)?$company->business_reg_no:"565565"}}
                ) {{!empty($company->gst_vat_sst)?"(SST No. ".$company->gst_vat_sst.")":""}}</strong>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center  pr-5 pl-5" style="font-size: 10px">
            <strong>
                {{!empty($company->office_address)?$company->office_address:"1, King Cross, Cheras, 56100 Kuala Lumpur, Malaysia"}}</strong>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-7 pr-0">
            <strong>Personal Shift Summary</strong>
        </div>
        <div class="col-md-5 pl-1 text-right">
            <strong>
		  	{{$login_time}} {{$logout_time}}
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
                id="item_amount"><b>{{empty($company->currency->code) ? 'MYR': $company->currency->code }}</b></strong>
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
                {{$sales}}</strong>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            {{!empty($terminal->taxtype)?strtoupper($terminal->taxtype):"SST"}} {{$terminal->tax_percent??"6"}}%
        </div>
        <div class="col-md-6" style="text-align: right;">
            <strong style="font-weight:normal" id="item_amount">
			   {{$tax}}</strong>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            Rounding
        </div>
        <div class="col-md-6" style="text-align: right;">
            <strong style="font-weight:normal" id="item_amount">
                {{$round}}
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
				{{$cash}}</strong>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            Credit Card
        </div>
        <div class="col-md-6" style="text-align: right;">
            <strong style="font-weight:normal" id="item_amount">
                {{$creditcard}}</strong>
        </div>
    </div>

	<div class="row">
        <div class="col-md-8">
			Wallet
        </div>
        <div class="col-md-4" style="text-align: right;">
            <strong style="font-weight:normal" id="item_amount">
				{{$wallet}}
			</strong>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            Outdoor Payment Terminal
        </div>
        <div class="col-md-4" style="text-align: right;">
            <strong style="font-weight:normal" id="item_amount">
                {{empty($opos_eoddetails->creditcard) ? '0.00':number_format(($opos_eoddetails->creditcard/100),2)}}</strong>
        </div>
    </div>
    @if($terminal_btype->btype??"" == 'petrol_station')
        <div class="row">
            <div class="col-md-6">
                Trade Debtor
            </div>
            <div class="col-md-2">
                <strong
                    class="global_currency">{{empty($company->currency->code) ? 'MYR': $company->currency->code }}</strong>
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
                    class="global_currency">{{empty($company->currency->code) ? 'MYR': $company->currency->code}}</strong>
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
                    class="global_currency">{{empty($company->currency->code) ? 'MYR': $company->currency->code}}</strong>
            </div>
            <div class="col-md-4" style="text-align: right;">
                <strong id="item_amount">{{number_format((@$OPT/100),2)}}</strong>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                Fleet Card
            </div>
            <div class="col-md-2">
                <strong
                    class="global_currency">{{empty($company->currency->code) ? 'MYR': $company->currency->code }}</strong>
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
                    class="global_currency">{{($company->currency->code) ? 'MYR': $company->currency->code }}</strong>
            </div>
            <div class="col-md-4" style="text-align: right;">
                <strong id="item_amount">0.00</strong>
            </div>
        </div>
@endif
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
            {{$location->name??""}}
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 text-left">
            <strong style="font-weight:normal">Location ID</strong>
        </div>
        <div class="col-md-6 text-right">
            {{$location->systemid??""}}
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 text-left">
            <strong style="font-weight:normal">Terminal ID</strong>
        </div>
        <div class="col-md-6 text-right">
            {{$terminal->systemid??""}}
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 text-left">
            <strong style="font-weight:normal">Staff Name</strong>
        </div>
        <div class="col-md-6 text-right">
            {{$user->fullname??''}}
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 text-left">
            <strong style="font-weight:normal">Staff ID</strong>
        </div>
        <div class="col-md-6 text-right">
            {{$user->systemid??''}}
        </div>
    </div>

    <div class="row align-items-center mt-3">
        <div class="col-md-6">
            <img src="{{asset('images/dispenser_icon.png')}}"
                 style="filter:invert(100%);transform:scaleX(-1);
						width:50px;height:50px;object-fit:contain;
						margin-left:0;"/>

            <img src="{{asset('images/basket_transparent.png')}}"
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
            url: "{{route('local_cabinet.pss.print')}}",
            type: 'post',
            data: {
				'login_time':'{{request()->login_time}}', 
				'user_systemid': '{{request()->user_systemid}}',
				@if (!empty(request()->logout_time))
					'logout_time':'{{request()->logout_time}}'
				@endif
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
