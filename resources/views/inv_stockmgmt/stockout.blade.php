@extends('common.web')
@section('styles')

<style>
/* remove small icons from input number */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
	-webkit-appearance: none;
	margin: 0;
}
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_processing,
.dataTables_wrapper .dataTables_paginate{
	color: black !important;
	font-weight: normal !important;
}
.dataTables_wrapper .dataTables_filter input {
	color: black !important;
	font-weight: normal !important;
    width: 200px;
}



/* Firefox */
input[type=number] {
	-moz-appearance:textfield;
}

.month_table > tr > th {
	font-size: 22px;
	color: white;
	background-color: rgba(255, 255, 255, 0.5);
}

.month_table  > tr > td {
	color: #fff;
	font-weight: 600;
	border: unset;
	font-size: 20px;
	cursor: pointer;
}

.slim {
	padding-top:2px !important;
	padding-bottom:2px !important;
}

.date_table > tbody > tr > th {
	font-size: 22px;
	color: white;
	background-color: rgba(255, 255, 255, 0.5);
}

.date_table > tbody > tr > td {
	color: #fff;
	font-weight: 600;
	border: unset;
	font-size: 20px;
	cursor: pointer;
}

table.dataTable tbody td{
	border-left: 1px solid #dee2e6;
	border-right: 1px solid #dee2e6;
	border-top: none;
	/*border-bottom: none;*/
}

.btn-green {
	background-color: green !important;
	color: #fff !important;
	box-shadow: none !important;
	border: 0px !important;
}

.btn-green:focus {
	background-color: green !important;
	color: #fff !important;
	box-shadow: none !important;
	border: 0px !important;
}

.bg-blue {
	background-color: #007bff;
	color: #fff;
}

.date_table1 > tbody > tr > th ,
{
	font-size: 22px;
	color: white;
	background-color: rgba(255, 255, 255, 0.5);
}

.date_table1 > tbody > tr > td {
	color: #fff;
	font-weight: 600;
	border: unset;
	font-size: 20px;
	cursor: pointer;
}

.selected_date {
	color: #fff !important;
	background: #008000;
	font-weight: 600 !important;
}

.selected_date1 {
	color: #008000 !important;
	font-weight: 700 !important;
}

#Datepick .d-table {
	display: -webkit-flex !important;
	display: -ms-flexbox !important;
	display: flex !important;
}

.dataTables_filter input {
	width: 300px;
}

.greenshade {
	height: 30px;
	background-color: green; /* For browsers that do not support gradients */
	background-image: linear-gradient(-90deg, green, white); /* Standard syntax (must be last) */
}
.dt-button{
	display: none;
}

/* .bg-purplelobster{
	background-color: rgba(26, 188, 156, 0.7);
	border-color: rgba(26, 188, 156, 0.7);
} */

/*//for calender short day*/
.shortDay ul{
	llist-style: none;
	background-color: rgba(255, 255, 255, 0.5);
	position: relative;
	left: -75px;
	width: 124%;
	height: 55px;
	line-height: 42px;
}

.shortDay ul > li{
	font-size: 22px;
	color: white;
	font-weight: 700 !important;
	/* background-color: #2b1f1f; */
	padding: 5px 24px;
	text-align: left !important;
}
.list-inline-item:not(:last-child){
	margin-right: 0 !important;
}
.modal-content{
	overflow: hidden;
}
.modal-inside .row {
	margin: 0px;
	color: #fff;
	margin-top: 15px;
	padding: 0px !important;
}

.butns {
	display: none
}

th, td {
	vertical-align: middle !important;
	text-align: center
}

td {
	text-align: center;
}

.modalBtns {
	margin-top: 5px
}

.butns{
	display: none
}
.num_td{
	text-align: left;
}
.value-button {
	display: inline-block;
	font-size: 24px;
	line-height: 21px;
	text-align: center;
	vertical-align: middle;
	background: #fff;
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
	text-align: center;
	text-align: center;
}
input.number {
	text-align: center;
	border: none;
	border: 1px solid #e2dddd;
	margin: 0px;
	width: 90px;
	border-radius: 5px;
	height: 38px;
	border-radius: 5px;
	background-color: #d4d3d36b !important;
	vertical-align: text-bottom;
}
.value-button {
	cursor:pointer;
}
</style>
@endsection
@include('common.header')
@include('common.menubuttons')
@section('content')
    <div class="container-fluid">

        <div class="row py-2"
             style="padding-bottom:0px !important;padding-top:0px !important;">
            <div class="col align-self-center" style="width:80%">
                <h2 style="margin-bottom:0">Stock Out</h2>
            </div>

            <div class="col align-self-center text-right" style="">
                <h5 style="margin-bottom:0">{{$location->name??''}} </h5>
            </div>

            <input type="hidden" name="location_id" id="location_id" value="" >
            <div class="col col-auto align-self-center">
                <button class="btn btn-success bg-receipt-loyalty sellerbutton" 
				id="confirm_update" onclick="update_quantity()"
				style="padding-left:10px;margin-right:0px;display:block;
				cursor: pointer;border-radius:10px">
				<span>Confirm</span>
                </button>
            </div>
        </div>

        <div style="padding-left:0;padding-right:0;" class="col-sm-12">
            <table id="fuelMgt" class="table table-bordered">
                <thead class="thead-dark">
                <tr>
                    <th style="width:30px;text-align: center;">No</th>
                    <th style="width:100px;text-align: center;">Product&nbsp;ID</th>
                    <th class="text-left">Product Name</th>
                    <th style="width:100px;!important" nowrap>Qty (&ell;)</th>
                    <th style="width:140px;text-align: center;" nowrap>Qty In (&ell;)</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
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
					<h5 class="modal-title text-white"
						id="statusModalLabelMsg">
					</h5>
				</div>
				<div class="modal-footer" style="border-top:0 none;">&nbsp;</div>
			</div>
		</div>
	</div>

    <script>
		var tablestockin ={};
		var location_id = "{{$location->id}}";
		var	isConfirmEnabled = 0;
		var tablestockin = $('#fuelMgt').DataTable({
			"serverSide": true,
			"retrieve": true,
			"autoWidth": false,
			"ajax": {
				"url": "{{route('franchise.location_price.prd_ajaxJson')}}",
				"type": "GET",
				"data": {"location_id": "{{$location->id}}", 'type': 'out'},
				'headers': {
					'X-CSRF-TOKEN': '{{ csrf_token() }}'
				},
			},
			columns: [
				{data: 'DT_RowIndex', name: 'DT_RowIndex'},
				{data: 'systemid', name: 'systemid'},
				{data: 'product_name', name: 'product_name'},
				{data: 'inven_existing_qty', name: 'inven_existing_qty'},
				{data: 'inven_qty', name: 'inven_qty'},
			],
			"order": [],
			"columnDefs":[
				{"targets": [1], "orderable": true},
				{"className":"dt-center", "targets": [0,1,3]},
				{"className":"dt-left", "targets": 2},
				{"className":"slim", "targets": 4},
			],
		});
		function increaseValue(id) {
			var num_element = document.getElementById('number_'+id);
			var existing_qty = parseInt($(`#qty_${id}`).text())
			var value = parseFloat(num_element.value);
			value = isNaN(value) ? 0 : value;
			value++;
			if (existing_qty >= value)	{
				num_element.value = value;
				isConfirmEnabled++;
			}
		}


		function decreaseValue(id) {
			var num_element = document.getElementById('number_'+id);
			var existing_qty = parseInt($(`#qty_${id}`).text())
			var value = parseFloat(num_element.value);
				value = isNaN(value) ? 0 : value;
				value < 1 ? value = 1 : '';
				value--;

			if (existing_qty >= value) {
				num_element.value = value;
				isConfirmEnabled--;
				if (isConfirmEnabled < 0)
					isConfirmEnabled = 0;
			}
		}

		function changeValueOnBlur(id) {
			x = 0;
			ele = document.querySelectorAll('.number');
			ele.forEach( (e) => x += parseInt(e.value));
			isConfirmEnabled = x;
		}

		function update_quantity() {
			var table_data = [];
			total_qty = 0;
			tablestockin.rows().eq(0).each( function ( index ) {
				let row = tablestockin.row( index );
				let data = row.data();
				qty = $('#number_'+data.id).val();
			if (qty > 0)
					table_data.push({'product_id': data.id,'location_id': location_id,'qty': qty});
			});
			
			if (table_data.length < 1)
				return;
		  
			$.ajax({
			  url: "{{route('franchise.location_price.stockUpdate')}}",
			  type: "POST",
			  'headers': {
				'X-CSRF-TOKEN': '{{ csrf_token() }}'
				},
			  data: {table_data : table_data, stock_type:"out"},
			  cache: false,
		  success: function(dataResult){
			messageModal(`Stock out successful`);
			tablestockin.ajax.reload();
		  }
		});
	}

	function messageModal(msg) {
		$('#modalMessage').modal('show');
		$('#statusModalLabelMsg').html(msg);
		setTimeout(function(){
			$('#modalMessage').modal('hide');
		}, 3500);
	}

	setInterval(() => {
		if (isConfirmEnabled > 0) {
			$("#confirm_update").removeAttr('disabled');	
			$("#confirm_update").css('background','');
			$("#confirm_update").css('cursor','pointer');
		} else {
			$("#confirm_update").attr('disabled', true);	
			$("#confirm_update").css('background','gray');
			$("#confirm_update").css('cursor','not-allowed');
		}
	}, 1500);

</script>
@endsection
@section('script')
    <script>

    </script>
@endsection
@include('common.footer')

