<?php echo $__env->make('common.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->startSection('styles'); ?>
<style>
    .dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter, .dataTables_wrapper .dataTables_info, .dataTables_wrapper .dataTables_processing, .dataTables_wrapper .dataTables_paginate{
    color: black ;
}
#pump_popup-table_paginate,#pump_popup-table_previous,#pump_popup_table_next,#pump_popup-table_length, #pump_popup-table_filter,#pump_popup-table_info{
    color: white !important;
}
.butns{
	display: none
}
th,td{
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
</style>
<style>
.dataTable > thead > tr > th[class*="sort"]:after{
	content: "" !important;
}
.dataTable > thead > tr > th[class*="sort"]:before{
	content: "" !important;
}

li{
	list-style: none
}
.modal-add-style {
	text-decoration: underline blue;
	cursor: pointer;
}
.table td {
	vertical-align: middle;
}

#inventoryCogsModal >  .modal-dialog, #inventoryCostModal > .modal-dialog, #inventoryLoyaltyModal > .modal-dialog {width: 250px;}

#inventoryCostInput, #inventoryCogsInput {text-align: right !important;}

#inventoryLoyaltyInput {text-align: center !important;}

.date_table >  tbody > tr > th {
 	font-size:22px;
 	color:white;
 	background-color: rgba(255, 255,255, 0.5);
}

.date_table > tbody > tr > td {
	color:#fff;
 	font-weight: 600;
 	border:unset;
 	font-size: 20px;
 	cursor:pointer;
}

 .selected_date {
 	color:#008000 !important;
 	font-weight: bold !important;
 }

 #Datepickk .d-table {
     display: -webkit-flex !important;
     display: -ms-flexbox !important;
     display: flex !important;
 }
 .date_table > tbody > tr > th {
    font-size: 22px;
    color: white;
    background-color: rgba(255, 255,255, 0.5);
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

.btn.bg-primary.primary-button:hover {
    color:  white;
}

a.foodcourt-link:hover {
	text-decoration: none;
}

.dt-valign {
	vertical-align:middle !important;
}

/*drop down*/
.dropbtn {
	background-color: #4CAF50;
	color: white;
	padding: 7px;
	font-size: 16px;
	border: none;
	width: 150px;
	border-radius: 5px;
}
#dropdown {
	width: 150px;
}
p {
	margin-bottom: auto !important;
	margin-top: auto !important;
}
#pump-id, #pump-no, .pump-no {
	color: #007bff !important;
	cursor: pointer;
}
.active_loc {color:darkgreen;}

</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('common.menubuttons', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
	<div class="d-flex mt-0" style="width: 100%">
		<div style="padding:0" class="align-self-center col-sm-5">
			<h2 class="mb-0">Local Fuel Price/Litre</h2>
		</div>
		<div class="col-sm-2 d-flex">
			<div class="col-sm-2 align-self-center">
			</div>
			<div class="col-sm-8 align-self-center"
				style="margin-bottom:5px">
				<ul style="padding-left:0;margin-bottom:0; list-style:none">
					<li>
						<h4 style="margin-bottom:0">
						<?php echo e($location->branch??""); ?>

						</h4>
					</li>
					<li><?php echo e($location->systemid??""); ?></li>
				</ul>
			</div>
		</div>

        <div style="padding-right:0" class="col-sm-5">

			<button class="btn btn-success sellerbutton"
				data-toggle="modal" data-target="#pump_modal"
				style="float: right; margin: 0px 0px 0;border-radius:10px">
				Pump
			</button>

			<button class="btn btn-success bg-save sellerbutton"
				onclick="trigger_push()"
				style="float: right; margin: 0px 5px 5px;border-radius:10px">
				Push
            </button>
            <span style="float: right;  margin: 0px 10px 10px;border-radius:10px" class="text">
                <b>Push Date:</B>&nbsp;<?php echo e($date??""); ?>

            </span>
		</div>
	</div>
	</div>

	<div class="col-sm-12" style="padding-left:20px;padding-right:20px">
		<table id="tableFuelLocalPrice" class="table table-bordered">
			<thead class="thead-dark">
				<tr>
					<th style="">No</th>
					<th style="">Product ID</th>
					<th style="">Product Name</th>
                    <th style="text-align:center;" style="">Price/&ell;</th>
                    <th style="">Controller Price/&ell;</th>
					<th class="" style="">User</th>
				<th class="text-center" style="">User&nbsp;Date</th>
				</tr>
			</thead>
			<tbody id="shows">
			</tbody>
		</table>
	</div>
</div>

	<div class="modal" id="inventoryCogsModal">
		<div class="modal-dialog modal-dialog-centered">
		  <div class="modal-content">
			<!-- Modal body -->
			<div class="modal-body">
				<input id="inventoryCogsInput" type="text" class="pl-1"
					style="width: 100%; border: 1px solid #ddd;">
				<input id="ogFuelPriceId" value="" type="hidden" class="pl-1">
				<input type="hidden" id="buffer_main_price" value="0.00">
			</div>
		  </div>
		</div>
	</div>

	<input type="hidden" id='startDate'>
	<div id="productResponce"></div>
	<div id="showEditInventoryModal"></div>
	<div id="showEditInputInventoryModal"></div>
	<div class="modal fade" id="showDateModalFrom" tabindex="-1"
		role="dialog" aria-labelledby="staffNameLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered  mw-75 w-50"
			role="document">
		<div class="modal-content modal-inside bg-greenlobster">
		<div class="modal-body text-center"
		  	style="min-height: 485px;max-height:485px">
			<div class="row">
			  <div class="col-md-2">
				<i class="prev-month fa fa-chevron-left fa-3x"
				style="cursor:pointer;display: inline-flex;"></i>
			  </div>
			  <div class=" col-md-8">
				<div class="month-year text-center text-white"></div>
			  </div>
			  <div class="col-md-2">
				<i style="cursor:pointer"
				class="next-month fa fa-chevron-right fa-3x"></i>
			  </div>
			</div>
			<div class="row">
			  <div class="shortDay">
				<ul>
				  <li class="list-inline-item">S</li>
				  <li class="list-inline-item">M</li>
				  <li class="list-inline-item">T</li>
				  <li class="list-inline-item">W</li>
				  <li class="list-inline-item">T</li>
				  <li class="list-inline-item">F</li>
				  <li class="list-inline-item">S</li>
				</ul>
			  </div>
			</div>
			<table class="table date_table">
			  <tr style="display: none;">
				<th>S</th>
				<th>M</th>
				<th>T</th>
				<th>W</th>
				<th>T</th>
				<th>F</th>
				<th>S</th>
			  </tr>
			</table>
		  </div>
		</div>
		<form id="status-form" action="'logout'"
		  method="POST" style="display: none;">
		  <?php echo csrf_field(); ?>
		</form>
	  </div>
	</div>
	</div>

	 <div class="modal fade" id="price_set_modal" aria-modal="true" >
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="bg-purplelobster modal-content">
                <!-- Modal body -->
                <div class="modal-body">
					<input class="form-control input-30"
						placeholder="0.00" id="payment_input_1"
						style="margin-left:2px;width:100%;
							display:inline-flex;text-align:right;" />
					<input type="hidden" id="input_price" />
					<input type='hidden' id='input_fk' />
                </div>
            </div>
        </div>
	</div>

	<div class="modal fade" id="pump_modal"  tabindex="-1"
		role="dialog"  aria-hidden="true">

	<div class="modal-dialog modal-dialog-centered modal-md  mw-75 w-50"
		role="document">
	<div class="modal-content modal-inside bg-purplelobster" >
		<div style="padding-top:5px; padding-bottom:5px"
			class="modal-header align-items-center" >
			<h3 class="mb-0 modal-title text-white" id="statusModalLabel">
				Pump
			</h3>
		</div>
		<div class="modal-body">
			<table class="table table-bordered align-content-center"
				id="pump_popup_table" style="width:100%">
				<thead class="thead-dark">
					<tr>
						<!--
						<th style="width:30px;">No</th>
						-->
						<th style="">Pump No</th>
					</tr>
				</thead>
			<tbody class="tablebody"></tbody>
			</table>
		</div>
	</div>
</div>
</div>

<div class="modal fade"  id="modalMessage"  tabindex="-1" role="dialog"
 	aria-hidden="true" style="text-align: center;">
    <div class="modal-dialog modal-dialog-centered  mw-75 w-50" role="document"
     style="display: inline-flex;">
        <div class="modal-content modal-inside bg-purplelobster"
        style="width: 100%;  background-color: <?php echo e(@$color); ?> !important" >
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


<div id="res"></div>
<script src="<?php echo e(asset('/js/osmanli_calendar.js')); ?>"></script>
<script>

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),

		},
		statusCode : {
			440: function() {
			   window.location = '/'
			},
		},
		async: false
	});


	var tableFuelLocalPrice_table = $('#tableFuelLocalPrice').DataTable({

	"destroy": true,
	"processing": false,
	"serverSide": true,
	"autoWidth": false,
	"ajax": {
		url:"<?php echo e(route('get_industry_oil_gas_fuel_local_price_datatable')); ?>",
		type: "POST",
		"data": {
		}
	},
	columns: [
		{data: 'DT_RowIndex', name: 'DT_RowIndex'},
		{data: 'product_systemid', name: 'product_systemid'},
		{data: 'product_name', name: 'product_name'},
		{data:	'price',	name:	'price'},
		{data:	'controller_price',	name: 'controller_price'},
		{data:	'user',		name:	'user'},
		{data: 'user_date', name: 'user_date'}
	],
	"order": [],
	"columnDefs": [
		{"className": "dt-center vt_middle slimcell", "targets": [0,1,4]},
		{"className": "dt-left vt_middle slimcell", "targets": [2,5]},
		{"width":"30px","targets":[0]},
		{"width":"120px","targets":[1]},
		{"width":"auto","targets":[2]},
		{"width":"90px","targets":[3]},
		{"width":"150px","targets":[4]},
		{"width":"auto","targets":[5]},
		{"width":"auto","targets":[6]},
		{ orderable: false, targets: [3]}
	],
	"drawCallback": function(settings, json) {
	},
	});


	var table_pump_popup_table = $('#pump_popup_table')
		.DataTable({
		"destroy": true,
		"processing": false,
		"serverSide": true,
		"autoWidth": false,
		"ajax": {
			url:"<?php echo e(route('fuel_local_price_pump_detail_datatable')); ?>",
			type: "POST",
		},
		columns: [
			//{data: 'DT_RowIndex', name: 'DT_RowIndex'},
			{data: 'pump_no', name: 'pump_no'},
		],
		"order": [],
		"columnDefs": [
			{"className": "dt-center vt_middle slimcell", "targets": [0]},
			{ orderable: false, targets: [0]}
		],
	});


	function openDetailModal(pump_id) {
		$.post("<?php echo e(route('fuel_local_price_pump_detail_modal')); ?>", {
			pump_id:pump_id,
		}).done( res =>{
			$('#res').html(res);
			$("#pumpConfiguration_").modal('show');
		});
	}

	function trigger_push() {
		$.post("<?php echo e(route('fuel_local_price.push.hardware')); ?>").done( res =>{
			messageModal("Local fuel price has been changed successfully")
		});
	}

	function messageModal(msg)
	{
		$('#modalMessage').modal('show');
		$('#statusModalLabelMsg').html(msg);
		setTimeout(function(){
			$('#modalMessage').modal('hide');
		}, 3500);
	}

/*
	var start_date_dialog = osmanli_calendar;
	function dateDialog(from_input, fk_id) {
		jQuery('#showDateModalFrom').modal('show');

		if (from_input != ''){
			start_date_dialog.CURRENT_DATE = new Date(from_input)
			start_date_dialog.SELECT_DATE = new Date(from_input)
		}

		start_date_dialog.DAYS_DISABLE_MIN = "On";
		start_date_dialog.MIN_DATE = new Date();
		$('.next-month').off();
		$('.prev-month').off();

		$('.prev-month').click(function () {start_date_dialog.pre_month()});
		$('.next-month').click(function () {start_date_dialog.next_month()});

		start_date_dialog.ON_SELECT_FUNC = (date_) => {
			jQuery('#showDateModalFrom').modal('hide');
			new_date = `${date_.getDate()}-${
					(date_.getMonth() + 1 )> 9 ? (date_.getMonth() + 1 ): '0' + (date_.getMonth() + 1)
				}-${date_.getFullYear()}`
			update_field('start', new_date,fk_id);
		}

		start_date_dialog.init()
	}
 */

	function update_field(field, value, id) {
		$.post('<?php echo e(route('get_industry_oil_gas_fuel_local_price_update')); ?>', {
			field:field,
			value:value,
			id:id
		}).done( (res) => {
			tableFuelLocalPrice_table.ajax.reload();
			console.log(res);
		});
	}

	function price_set_modal (price, id) {
		price = price == '0.00' ? '':price;
		$('#payment_input_1').val(price);
		$('#input_fk').val(id);
		$('#input_price').val('');
		$("#price_set_modal").modal('show');
	}


	$("#price_set_modal").on('hidden.bs.modal', function (e) {
		price = $('#input_price').val();
		id = $('#input_fk').val();
		update_field('price', price, id);
	});


	filter_price("#payment_input_1","#input_price");
	function filter_price(target_field,buffer_in) {
		$(target_field).off();
		$(target_field).on( "keydown", function( event ) {
			event.preventDefault()
			if (event.keyCode == 8) {
				$(buffer_in).val('')
				$(target_field).val(' ')
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
				old_val = ' '
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
			return num.toString()[0] + '.' + num.toString()[1] +
				num.toString()[2];
		} else if (num.toString().length >= 4) {
			return num.toString().slice(0, (num.toString().length - 2)) +
				'.' + num.toString()[(num.toString().length - 2)] +
				num.toString()[(num.toString().length - 1)];
		}
	}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('common.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php echo $__env->make('common.web', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Server_Files\oceania\trunk\oceania\resources\views/local_fuelprice/local_fuelprice.blade.php ENDPATH**/ ?>