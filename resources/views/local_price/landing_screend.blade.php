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
			<h2 class="mb-0">Location Product</h2>
		</div>

		<div class="col-sm-2 pl-0">
			<div class="row mb-0 align-items-center"
				style="float:right;">
				<div style="margin-right:15px">
					<span style="font-size:15px" class="mb-0">
						Franchise
					</span>
				</div>

				<button onclick="window.open('{{route("franchise.location_price.stock_in")}}')"
						class="btn btn-success bg-stockin sellerbutton mr-0 mb-0"
						style="padding:0px;float:right;margin-left:5px;
				border-radius:10px;" id="stockin_btn">Stock<br>In
				</button>

				<button onclick="window.open('{{route("franchise.location_price.stock_out")}}')"
						class="btn btn-success bg-stockout sellerbutton
				mb-0 mr-0" style="padding:0px;float:right;margin-left:5px;
				border-radius:10px" id="stockout_btn">Stock<br>Out
				</button>
			</div>
		</div>
	</div>

	<div class="col-sm-12 pl-0 pr-0" style="">
		<table id="tableFLocaltionProduct"
			class="table table-bordered">
		<thead class="thead-dark">
		<tr>
			<th style="width:30px;text-align: center;">No</th>
			<th style="width:100px;text-align: center;">Product&nbsp;ID</th>
			<th>Product&nbsp;Name</th>
			<th class="text-center">Min</th>
			<th class="text-center">Price</th>
			<th class="text-center">Max</th>
			<th class="text-center">Loyalty</th>
			<th class="text-center"
				style="background-color:#ff735f">
			   Qty 
			</th>
			<th class="text-center">Value</th>
			<th style="width:80px;margin:auto;text-align:center;padding:2px;">
				<button type="button" onclick="select_all_btn(this)"
					class="active_product highlight-off btn
					btn12 btns active_button
					{{$is_all_active == true ? 'active_button_activated':''}}"
					data-state="{{$is_all_active}}"
					data-status="none" id="all" style="width:75px">
					All
				</button>
			</th>
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
	//tableData['franchiseid'] = franchiseid;
	//tableData['locationid'] = locationid;

	var franchiseTable = $('#tableFLocaltionProduct').DataTable({
		"processing": false,
		"serverSide": true,
		"autoWidth": false,
		"ajax": {
			"url": "{{route('franchise.location_price.datatable')}}",
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
			{data: 'product_systemid', name: 'systemid'},
			{data: 'product_name', name: 'product_name'},
			{data: 'product_lower', name: 'product_lower'},
			{data: 'product_price', name: 'product_price'},
			{data: 'product_upper', name: 'product_upper'},
			{data: 'product_loyalty', name: 'product_loyalty'},
			{data: 'product_stock', name: 'product_stock'},
			{data: 'product_value', name: 'product_value'},
			{data: 'active', name: 'active'},
		],
		"order": [0, 'desc'],
		"columnDefs": [
			{"width": "30px", "targets": 0},
			{"width": "120px", "targets": 1},
			{"width": "50px", "targets": [3, 4, 5, 7, 8]},
			{"width": "100px", "targets": [6, 9]},
			{"className": "dt-left vt_middle", "targets": [2]},
			{"className": "dt-right vt_middle", "targets": [3, 4, 5, 8]},
			{"className": "dt-center vt_middle", "targets": [0, 1, 3, 5, 6, 7, 8, 6, 7]},
			{"className": "vt_middle", "targets": [2]},
			{"className": "slim-cell", "targets": [-1]},
			{orderable: false, targets: [-1]},
		],
	});


	var f_pid = null;

	low_price = null;
	high_price = null;
	validation = null;

	updatePrice = function (val, fr_pid, lp, hp, v) {

		f_pid = fr_pid;
		low_price = parseInt(lp);
		high_price = parseInt(hp);
		validation = v;

		if (val != '')
			$("#retail_price_normal_fk").val((val / 100).toFixed(2));
		else
			$("#retail_price_normal_fk").val('');

		$("#retail_price_normal").val(val);
		$("#normalPriceModal").modal('show');
	}

	$('#normalPriceModal').on('hidden.bs.modal', function (e) {
		val_ = $("#retail_price_normal").val();
		updateFieldAjax('price', val_, f_pid);
		f_pid = null;
		low_price = null;
		high_price = null;
		validation = null;
	});
	activate_func = function (id ,  e) {

		 var payload = {};
   			
		if ($(e).hasClass("active_button_activated")) {
		  payload = {
				id: id,
				type:'hide',
			};
		}else{
			payload = {
				id: id,
				type:'show',
			};
		}
		localStorage.removeItem('hide_show_product');
 		localStorage.setItem("hide_show_product",JSON.stringify(payload));
 		
		updateFieldAjax('active', 0, id);
	}
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': '{{ csrf_token() }}',
		}
	});

	updateFieldAjax = function (key, val, f_id) {
		$.post('{{route('franchise.location_price.price_update')}}', {
			"field": key,
			"data": val,
			"product_id": f_id
		}).done(function (res) {
			franchiseTable.ajax.reload();
			$("#res").html(res.output);
		});
	}


	function atm_money(num) {
		if (num.toString().length == 1) {
			return '0.0' + num.toString()
		} else if (num.toString().length == 2) {
			return '0.' + num.toString()
		} else if (num.toString().length == 3) {
			return num.toString()[0] + '.' + num.toString()[1] +
				num.toString()[2];
		} else if (num.toString().length >= 4) {
			return num.toString().slice(0, (num.toString().length - 2)) +
				'.' + num.toString()[(num.toString().length - 2)] +
				num.toString()[(num.toString().length - 1)];
		}
	}

	select_all_btn = function (e) {
		tableData['all_btn_state'] = $(e).attr('data-state');
		$.post('{{route('franchise.location_price.activate_all')}}', tableData).done(function (res) {
			franchiseTable.ajax.reload();
		});
		$(e).attr('data-state', ($(e).attr('data-state') == 0 ? 1 : 0));
		if ($(e).attr('data-state') == 0)
			messageModal("All local prices deactivated")
		else if ($(e).attr('data-state') == 1)
			messageModal("All local prices activated")

		$(e).toggleClass('active_button_activated');
	}

	filter_price('#retail_price_normal_fk', '#retail_price_normal')

	function filter_price(target_field, buffer_in) {
		$(target_field).off();
		$(target_field).on("keydown", function (event) {
			event.preventDefault()
			if (event.keyCode == 8) {
				$(buffer_in).val('')
				$(target_field).val('')
				return null
			}
			if (isNaN(event.key) ||
				$.inArray(event.keyCode, [13, 38, 40, 37, 39]) !== -1 ||
				event.keyCode == 13) {
				if ($(buffer_in).val() != '') {
					$(target_field).val(atm_money(parseInt($(buffer_in).val())))
				} else {
					$(target_field).val('')
				}
				return null;
			}

			const input = event.key;
			old_val = $(buffer_in).val()


			if (old_val === '0.00') {
				$(buffer_in).val('')
				$(target_field).val('')
				old_val = ''
			}


			$(buffer_in).val('' + old_val + input)
			$(target_field).val(atm_money(parseInt($(buffer_in).val())))

		});

		$(target_field).focusout(function (event) {
			valdate = validation_input_price(parseInt($(buffer_in).val()));
			console.log("Validate_result", valdate);
			if (valdate == false) {
				messageModal("Price out of range")
				$(buffer_in).val(0)
				$(target_field).val(atm_money(0))
			}
		});
	}

	function validation_input_price(val) {
		//low_price = null;
		//high_price = null;

		if (validation == 'bypass') {
			return true;
		}

		if (low_price <= val && high_price >= val) {
			return true;
		}

		return false;
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

			} else {
				franchiseTable.ajax.reload();
			}

			document_hidden = document[hidden];
		}
	});
});

</script>
@endsection
@include('common.footer')
