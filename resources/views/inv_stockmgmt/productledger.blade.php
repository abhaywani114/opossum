@extends('common.web')

@section('styles')

<style>
.butns{
	display: none
}
th{
vertical-align: middle !important;
	text-align: center
}
td{
	vertical-align: middle !important;
}
.bg-primary:hover{
	color:white;
}
</style>

<div id="landing-view">
<!--white abalone-->
<style media="screen">
a:link{
	text-decoration: none!important;
}
@media (min-width: 1025px) {
	#ogProductLeger{
		table-layout: fixed;
	}
	.remarks {
		white-space: nowrap;
		overflow-x: hidden;
		text-overflow: ellipsis;
	}
}

.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter,
.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_processing,
.dataTables_wrapper .dataTables_paginate{
    color: black !important;
    font-weight: normal !important;
}

#void_stamp{
	font-size:100px;
	color:red;
	position:absolute;
	z-index:2;
	font-weight:500;
	margin-top:130px;
	margin-left:15%;
	transform:rotate(45deg);
	display:none;
}
</style>
@endsection

@section('content')
@include('common.header')
@include('common.menubuttons')

{{-- modal remarks --}}
<div class="container-fluid">
<div class="row"
	style="padding-top:0;height:75px;margin-top:0px;margin-bottom:0">
	<div class="col-sm-4" style="align-self:center">
		<h2 class="mb-0 pt-0">
			Product Ledger
		</h2>
	</div>

	<div class="col-sm-1" style="align-self:center">
	@if (!empty($product->thumbnail_1))
		<img src="/images/product/{{$product->systemid}}/thumb/{{$product->thumbnail_1}}"
			alt="Logo" width="70px" height="70px" alt="Logo"
			style="object-fit:contain;float:right;margin-left:0;margin-top:0;">
	@endif
	</div>

	<div class="col-sm-7" style="align-self:center;float:left;padding-left:0">
		<h4 style="margin-bottom:0px;padding-top: 0;line-height:1.5;">
		@if($product->name??"")
			{{$product->name??""}}
		@else
			Product Name
		@endif
		</h4>
		<p style="font-size:18px;margin-bottom:0">
			{{$product->systemid??""}}
		</p>
	</div>
	</div>

	<div class="table-responsive mb-5" style="overflow-x: hidden;">
	<table class="table table-bordered" id="ogProductLeger" style="width: 100%;">
	<thead class="thead-dark">
		<tr>
		<th class="text-center" style="width:30px;">No</th>
		<th class="text-center" style="width:15%;">Document&nbsp;No</th>
		<th class="text-center" style="width:11%">Type</th>
		<th class="text-center" style="width:120px">Last&nbsp;Update</th>
		<th class="text-center" style="width:auto;">Location</th>
		<th class="text-center" style="width:80px">Qty</th>
		</tr>
	</thead>

	<!--
	Types of ledger data (table names):
	1. opos_receiptproduct
	2. stockreportproduct
	3. opos_refund
	 -->

	<tbody>
		@foreach ($data as $row)
		<tr>
			<td style="text-align: center;">{{ $loop->index + 1 }}</td>

			@if ($row->doc_type == "Cash Sales")
			<td style="text-align: center;
				@if($row->status=='voided')
					background-color:red;color:white;font-weight:bold;
				@endif">
				<a href="#" style="text-decoration: none;"
					onclick="showReceipt({{$row->id}})">{{$row->systemid}}
				</a>
			  </td>
			@else  
			<td style="text-align: center;">
				<a href="javascript:window.open('{{route('stocking.stock_report', $row->systemid)}}')" 
				style="text-decoration: none;">{{$row->systemid}}
				</a>
			</td>
			@endif

			<td style="text-align: center;" nowrap>
				@if ($row->doc_type == 'Stockin')
					Stock In
				@elseif ($row->doc_type == 'Stockout')
					Stock Out
				@else
					{{ucwords($row->doc_type)}}
				@endif
			</td>
			<td style="text-align: center;" nowrap>
				@if ($row->status=='voided')
					{{date('dMy H:i:s', strtotime($row->voided_at??''))}}
				@else
					{{date('dMy H:i:s', strtotime($row->created_at??''))}}
				@endif
			</td>
			<td style="text-align: center;" nowrap>
				{{$location->name}}
			</td>
			{{-- <td style="text-align: center;" nowrap></td> --}}
			<td style="text-align: center;">
				@if ($row->status=='voided')
					0
				@else
					{{--number_format(($row->quantity),2)--}}
					{{($row->quantity)}}
				@endif
			</td>
		</tr>
		@endforeach
	</tbody>
	</table>
	</div>

	<div class="modal fade" id="eodModal_1" tabindex="-1" role="dialog"
	style="overflow:auto;" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-75"
		style="width:370px" role="document">
	<div id="recipt-model-div" class="modal-content bg-white"></div>
  </div>
</div>
</div>
{{--@include('inventory.inventoryqtypdtlocation')--}}
@endsection
@section('script')
<div id="productResponce"></div>
<div id="response"></div>

<script type="text/javascript">
function showReceipt(id){
	$('#eodSummaryListModal').modal('hide').html();
	$('#optlistModal').modal('hide').html();
	$('#receiptoposModal').modal('hide');
	$.ajax({
		url: "/local_cabinet/eodReceiptPopup/"+id,
		// headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		type: 'get',
		success: function (response) {
			// console.log(response);
			$('#recipt-model-div').html(response);
			$('#eodModal_1').modal('show');
		},
		error: function (e) {
			$('#responseeod').html(e);
			$("#msgModal").modal('show');
		}
	});
}


$(document).ready(function () {
	var tableinventory =  $('#ogProductLeger').DataTable();
});


</script>
</div>

@include('common.footer')
@endsection

