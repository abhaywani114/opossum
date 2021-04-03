@extends('common.web')
@include('common.header')

@section('styles')
<style>
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter,
.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_processing,
.dataTables_wrapper .dataTables_paginate {
	color: black;
}

th, td {
	vertical-align: middle !important;
	text-align: center
}

label, .dataTables_info,
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter,
.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_processing,
.dataTables_wrapper .dataTables_paginate {
	color: #000 !important;
}

.active_button {
	color: #ccc;
	border: 1px #ccc solid;
}

.active_button:hover,
.active_button:active,
.active_button_activated {
	background: transparent !important;
	color: #34dabb !important;
	border: 1px #34dabb solid !important;
	font-weight: bold;
}

.active_button_activated {
	background: transparent;
	color: #34dabb;
	border: 1px #34dabb solid;
	font-weight: bold;
}

.slim-cell {
	padding-top: 2px !important;
	padding-bottom: 2px !important;
}
</style>


@endsection

@include('common.menubuttons')

@section('content')
<div class="container-fluid">
	<div class="d-flex mt-0 p-0"
		 style="width:100%; margin-top:5px !important;margin-bottom:5px !important">
		<div style="padding:0" class="align-self-center col-sm-10">
			<div class="row mt-2">
				<div class="col-1">
					<img src="{{url('/images/product/' . $product->systemid . '/thumb/' .
                    $product->thumbnail_1)}}" style="object-fit:contain;width:60px;height: 60px;margin-right:0;" />
				</div>
				<div class="col-10" style="display: flex;vertical-align: middle;align-items: center;">
					{{$product->name}} <br/>
					{{$product->systemid}}
				</div>
			</div>
			<br/>
		</div>

		<div class="col-sm-2 pl-0">

				</button>
			</div>
		</div>
	</div>

	<div class="col-sm-12 pl-0 pr-0" style="">
		<table id="tableFLocaltionProduct"
			class="table table-bordered">
		<thead class="thead-dark">
		<tr>
			<th class="text-center">No</th>
			<th class="text-center">Barcode</th>
			<th class="text-center">QR&nbsp;Code</th>
			<th class="text-center">Colour</th>
			<th class="text-center">Matrix</th>
			<th class="text-center">Notes</th>
			<th class="text-center">Qty</th>
			<th class="text-center"></th>

		</tr>
		</thead>
		<tbody id="shows">
		</tbody>
		</table>
	</div>
</div>

<div class="modal fade" id="normalPriceModal" tabindex="-1"
	 role="dialog" aria-labelledby="staffNameLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered  mw-75 w-50"
		 role="document">
		<div class="modal-content modal-inside bg-purplelobster">
		<div class="modal-header">
			<h3 class="mb-0 modal-title text-white"
				id="statusModalLabel">Price
			</h3>
		</div>
		<div class="modal-body">
			<div class='text-center col-8' style="margin:auto">
				<input type="text" id="retail_price_normal_fk"
				   style="text-align:right" class="form-control"
				   placeholder='0.00'/>
			<input type="hidden" id='retail_price_normal'/>
			</div>
		</div>
		</ul>
		<!-- div class="modal-footer" style="border:0;">
		</div --->
		</div>
	</div>
</div>


@endsection
@section('script')
<script>
	var tableData = {};
	//tableData['systemid'] = '{{request()->systemid}}';
	//tableData['locationid'] = locationid;

	var franchiseTable = $('#tableFLocaltionProduct').DataTable({
		"processing": false,
		"serverSide": true,
		"autoWidth": false,
		"ajax": {
			"url": "{{route('franchise.location_price.barcode.datatable', request()->systemid )}}",
			"type": "POST",
			data: function (d) {
				return $.extend(d, tableData);
			},
			'headers': {
				'X-CSRF-TOKEN': '{{ csrf_token() }}'
			},
		},
		columns: [
			{data: 'DT_RowIndex', name: 'DT_RowIndex'},
			{data: 'product_barcode', name: 'product_barcode'},
			{data: 'product_qr', name: 'product_qr'},
			{data: 'product_color', name: 'product_color'},
			{data: 'product_matrix', name: 'product_matrix'},
			{data: 'product_notes', name: 'product_notes'},
			{data: 'product_qty', name: 'product_qty'},
			{data: 'product_print', name: 'product_print'},
		],
		"order": [0, 'desc'],
		"columnDefs": [
			{"className": "dt-left vt_middle", "targets": [2]},
			{"className": "dt-right vt_middle", "targets": [3, 4, 5, ]},
			{"className": "dt-center vt_middle", "targets": [0, 1, 3, 5, 6, 7, 6, 7]},
			{"className": "vt_middle", "targets": [2]},
			{"className": "slim-cell", "targets": [-1]},
			{'width':'30px', 'targets':[0]},	
			{'width':'280px', 'targets':[1]},	
			{'width':'60px', 'targets':[3,6,7]},
			{'width':'80px', 'targets':[2]},

			{orderable: false, targets: [-1]},
		],
	});

</script>
@endsection
@include('common.footer')
