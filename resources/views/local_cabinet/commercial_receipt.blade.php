<!--Modal EodPrint-->
<style>
    .receipt-item-l {
        text-align: left;
        padding-right: 0;
        font-size: 12px;
    }

    .receipt-item-c {
        padding-right: 0;
        padding-left: 0;
        font-size: 12px;
    }

    .receipt-item-discount {
        text-align: center;
        padding-right: 0;
        padding-left: 20px;
        font-size: 12px;
    }

    .receipt-item-r {
        text-align: right;
        padding-left: 0;
        font-size: 12px;
    }

    .void-stamp {
        font-size: 100px;
        color: red;
        position: absolute;
        z-index: 2;
        font-weight: 500;
        /* margin-top:50%; */
        margin-left: 10%;
        transform: rotate(45deg);

    }

    .col-pd {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

</style>

<!--Modal Body Starts-->
<div class="modal-body"
     style="font-size: 14px; font-weight: bold; padding:0px 15px 0px 15px">
    <!--Section 1 starts-->

    <div class="row" style="text-align:center; color: black">
        <div class="col-md-12 col-pd  text-center">
            @if (!empty($company->id)&&!empty($receipt->receipt_logo))
                <img src="{{ asset('images/company/'.$company->id.'/corporate_logo/'.$receipt->receipt_logo) }}" alt=""
                     style="object-fit:contain;width: 80px; height: 80px;"
                     srcset="" class="mr-1">
            @endif
        </div>
    </div>

    <div class="row" style="text-align:center; color: black!important;">
        <div class="col-md-12 col-pd  text-center pl-5 pr-5" style="font-size: 17px">
            <b>
                {{!empty($company->name)?$company->name:""}}
            </b>
            <b style="font-size:12px">
                ({{!empty($company->business_reg_no)?$company->business_reg_no:""}})
            </b><br>
            <b style="font-size:12px">
                {{!empty($company->gst_vat_sst)?"(SST No. ".$company->gst_vat_sst.")":""}}
            </b>
        </div>
    </div>
    <div class="row" style="color: black!important;">
        <div class="col-md-12 col-pd  text-center">
            <strong style="font-size: 14px">
                {{!empty($receipt->receipt_address)?$receipt->receipt_address:""}}
            </strong>
        </div>
    </div>

    <hr style="border: 0.5px solid #c0c0c0;margin-top:5px !important;
		font-weight:normal !important;"/>

    <div class="row align-items-center" style="color: black!important;">
        <div class="col-md-4  col-pd pr-0" style="font-weight:500 !important;">
            <strong>Description</strong>
        </div>
        <div class="text-center col-md-2  col-pd pl-3 pr-0">
            <strong>Qty</strong>
        </div>
        <div class="text-center col-md-2  col-pd pl-3 pr-0">
            <strong class="global_currency">Price</strong>
        </div>
        <div class="text-center col-md-1  col-pd pl-3 pr-0">
            <strong>Disc.</strong>
        </div>
        <div class="text-right col-md-3  col-pd pl-0" style="font-size:17px">
            <strong id="item_amount">
                {{empty($receipt->currency) ? 'MYR': $receipt->currency }}
            </strong>
        </div>
    </div>
    <hr style="border: 0.5px solid #c0c0c0;margin-top:5px !important">
    @if (!empty($receiptproduct))
        @foreach ($receiptproduct as $product)
            <div class="row align-items-center" style="font-weight: normal; color: black!important;">
                <div class="col-md-4  col-pd receipt-item-l">
                    {{!empty($product->name)?$product->name:"RON95"}}
                </div>
                <div class="pl-3 col-md-2  col-pd receipt-item-c text-center">
                    {{!empty($product->quantity)?number_format($product->quantity,2):"1"}}
                </div>
                <div class="pl-3 col-md-2  col-pd receipt-item-c text-center">
                    <span class="global_currency">{{!empty($product->price)?number_format($product->price/100,2):"9,311.45"}}</span>
                </div>
                <div class="col-md-1  col-pd receipt-item-discount">
                    {{!empty($product->discount)?$product->discount:"0"}}%
                </div>
                <div class="col-md-3  col-pd receipt-item-r">
			<span id="item_amount">
                {{number_format(($product->quantity*($product->price/100)),2)}}
                {{--number_format((($receipt->cash_received/100-$receipt->cash_change/100)??"2"),2)--}}
			</span>
                </div>
            </div>
        @endforeach
    @endif
    <hr style="border: 0.5px solid #c0c0c0; margin-top:5px !important"/>

    <div class="row" style="color: black!important;">
        <div class="col-md-6 col-pd" style="font-weight: normal">
            Product ID
        </div>
        <div class="col-md-6 col-pd" style="text-align: right;">
			<span style="font-weight:normal" id="item_amount">

			</span>
        </div>
    </div>
    <div class="row" style="color: black!important;">
        <div class="col-md-6 col-pd" style="font-weight: normal">
            Vehicle ID
        </div>
        <div class="col-md-6 col-pd" style="text-align: right;">
			<span style="font-weight:normal" id="item_amount">

			</span>
        </div>
    </div>

    <div class="row" style="color: black!important;">
        <div class="col-md-6 col-pd" style="font-weight: normal">
            Initial Totalizer
        </div>
        <div class="col-md-6 col-pd" style="text-align: right;">
			<span style="font-weight:normal" id="item_amount">

			</span>
        </div>
    </div>

    <div class="row" style="color: black!important;">
        <div class="col-md-6 col-pd" style="font-weight: normal">
            Final Totalizer
        </div>
        <div class="col-md-6 col-pd" style="text-align: right;">
			<span style="font-weight:normal" id="item_amount">

			</span>
        </div>
    </div>

    <div class="row" style="color: black!important;">
        <div class="col-md-6 col-pd" style="font-weight: normal">
            Time Started
        </div>
        <div class="col-md-6 col-pd" style="text-align: right;">
			<span style="font-weight:normal" id="item_amount">

			</span>
        </div>
    </div>

    <div class="row" style="color: black!important;">
        <div class="col-md-6 col-pd" style="font-weight: normal">
            Time Completed
        </div>
        <div class="col-md-6 col-pd" style="text-align: right;">
			<span style="font-weight:normal" id="item_amount">

			</span>
        </div>
    </div>


    <hr style="border: 0.5px solid #c0c0c0; margin-top:5px !important">
    <div class="row" style="color: black!important;">
        <div class="col-md-4  col-pd pr-0">
            <span style="font-weight: normal">Receipt No.</span>
        </div>
        <div class="col-md-8  col-pd pl-0 text-right">
            <span style="font-weight: normal">{{!empty($receipt->systemid)?$receipt->systemid:"7060000010000000014"}}</span>
        </div>
    </div>
    <div class="row" style="color: black!important;">
        <div class="col-md-6  col-pd text-left">
            <span style="font-weight: normal">Location</span>
        </div>
        <div class="col-md-6  col-pd text-right" style="font-weight: normal">
            {{$location->name??""}}
        </div>
    </div>
    <div class="row" style="color: black!important;">
        <div class="col-md-6  col-pd text-left">
            <span style="font-weight: normal">Terminal ID</span>
        </div>
        <div class="col-md-6  col-pd text-right" style="font-weight: normal">
            {{$terminal->systemid??''}}
        </div>
    </div>

    <div class="row" style="color: black!important;">
        <div class="col-md-6  col-pd text-left">
            <span style="font-weight: normal">Staff Name</span>
        </div>
        <div class="col-md-6  col-pd text-right" style="font-weight: normal">
            {{$user->fullname??''}}
        </div>
    </div>
    <div class="row" style="color: black!important;">
        <div class="col-md-6  col-pd text-left">
            <span style="font-weight: normal">Staff ID</span>
        </div>
        <div class="col-md-6  col-pd text-right" style="font-weight: normal">
            {{$user->systemid??''}}
        </div>
    </div>


    <div class="container-fluid mt-3" style="padding-left: 2%!important;">

        <div class="row mb-2">
                <button class="btn btn-success bg-receipt-print"
                        style="padding-left:0;padding-right:0; margin-right: 5px;
				font-size:13px;border-radius:10px; "
                        onclick="print_receipt()">
                    <strong>Print</strong>
                </button>


                <button class="btn btn-success btn-log bg-receipt-void
				sellerbutton void_true only_void_true only_void ml-3"
                        style="padding-left:0; padding-right:0;
                                font-size:13px;border-radius:10px;@if($refund || $receipt->status=='voided') background: #3a3535cc; cursor:not-allowed;@endif"

                        @if($refund || $receipt->status=='voided')
                        disabled="disabled"
                        @else
                        onclick="void_receipt({{$receipt->id}})" @endif>
                    <strong>Void</strong>
                </button>
        </div>

        {{-- <button class="nohover sellerbutton"
                 style="background-color:white;border:0;pointer-events:none">
         </button>

         <button class="nohover sellerbutton"
                 style="background-color:white;border:0;pointer-events:none">
         </button>--}}
    </div>
</div>

<div class="row d-flex" style="justify-content:center;color: black!important;">
    <div style="font-size:14px">
        Thank You!
    </div>
</div>
<!--- void by --->
<div id="void-div{{$receipt->id??''}}"
     style="color: black!important;display:@if($receipt->status=='voided') block @else none @endif">
    <div class="row" style="color: black!important;">
        <div class="col-md-3  col-pd text-left" style="color:red;">
            <strong>Void By</strong>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6  col-pd text-left" style="color:red;">
            <strong> {{$receipt->user->fullname??''}}</strong>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6  col-pd text-left" style="color:red;">
            <strong> {{$receipt->user->systemid??''}}</strong>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6  col-pd text-left" style="color:red;">
            <strong id="void-time{{$receipt->id??''}}">{{$receipt->voided_at??''}}</strong>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6  col-pd text-left" style="color:red;">
            <strong id="void-reason{{$receipt->id??''}}">{{$receipt->void_reason??''}}</strong>
        </div>
    </div>
</div>
{{--@if($refund)
    <div style="text-align:left; color:orange">
        Refunded By:
        <br>
        {{$refund->name}}
        <br>
        {{$refund->systemid}}
        <br>
        {{date('dMy', strtotime($refund->created_at))}}
        <br>
        MYR {{(number_format($refund->refund_amount,2))}}
        <br>
    </div>
@endif--}}

<div class="row" style="color: black!important;">
    <div class="col-md-12 col-pd   text-right">
        <div style="font-size: 10px" class="pr-3">
            <strong>Tetra Commercial 1.0</strong>
        </div>
        <br>
    </div>
</div>
<!--section 4 start-->

</div>
<!--Modal Body ends-->

<script type="text/javascript">

    $(document).ready(function () {
        $('.sorting_1').css('background-color', 'white');
    });


    /* Function to print receipt via 80mm thermal printer */
    function print_receipt() {
        console.log('PR print_receipt()');

        $.ajax({
            url: "/print_receipt",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'post',
            data: {
                'receipt_id':{!!$receipt->id!!},
            },
            success: function (response) {
                var error1 = false, error2 = false;
                console.log('PR ' + JSON.stringify(response));

                try {
                    eval(response);
                    console.log('eval working');
                } catch (exc) {
                    error1 = true;
                    console.error('ERROR eval(): ' + exc);
                }

                if (!error1) {
                    try {
                        escpos_print_template();
                        console.log('template working');
                    } catch (exc) {
                        error2 = true;
                        console.error('ERROR escpos_print_template(): ' + exc);
                    }
                }
            },
            error: function (e) {
                console.log('PR ' + JSON.stringify(e));
            }
        });
    }

</script>
