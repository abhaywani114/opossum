<?php $__env->startSection('styles'); ?>

<script type="text/javascript" src="<?php echo e(asset('js/console_logging.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('js/qz-tray.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('js/opossum_qz.js')); ?>"></script>

<style>
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter,
.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_processing,
.dataTables_wrapper .dataTables_paginate {
	color: black !important;
	font-weight: normal !important;
}

#receipt-table_length, #receipt-table_filter,
#receipt-table_info, .paginate_button {
	color: white !important;
}

#eodSummaryListModal-table_paginate,
#eodSummaryListModal-table_previous,
#eodSummaryListModal-table_next,
#eodSummaryListModal-table_length,
#eodSummaryListModal-table_filter,
#eodSummaryListModal-table_info {
	color: white !important;
}

.paging_full_numbers a.paginate_button {
	color: #fff !important;
}

.paging_full_numbers a.paginate_active {
	color: #fff !important;
}

table.dataTable th.dt-right, table.dataTable td.dt-right {
	text-align: right !important;
}

td {
	vertical-align: middle !important;
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('common.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('common.menubuttons', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div id="landing-view">
	<!--div id="landing-content" style="width: 100%"-->
	<div class="container-fluid">
		<div class="clearfix"></div>
		<div class="row py-2 align-items-center"
			style="display:flex;height:75px">
			<div class="col" style="width:70%">
				<h2 style="margin-bottom: 0;">
				Universal Summary List 
				</h2>
			</div>
			<div class="col-md-2">
				<h5 style="margin-bottom:0"><?php echo e($location->name??""); ?></h5>
				<h5 style="margin-bottom:0"><?php echo e($location->systemid??""); ?></h5>
			</div>
			<div class="middle;col-md-3">
				<h5 style="margin-bottom:0;">Terminal ID: <?php echo e($terminal->systemid??""); ?></h5>

			</div>
			<div class="col-md-2 text-right">
				<h5 style="margin-bottom:0;"></h5>
			</div>
		</div>

		<div id="response"></div>
		<div id="responseeod"></div>
		<table class="table table-bordered display"
			   id="eodsummarylist" style="width:100%;">
			<thead class="thead-dark">
			<tr>
				<th class="text-center" style="width:30px;">No.</th>
				<th class="text-center" style="">Start&nbsp;Date</th>
				<th class="text-center" style="width:250px;">Personal&nbsp;Shift</th>
				<th class="text-center" style="width:100px">Hydrogen</th>
				<th class="text-center" style="width:100px">EV</th>
				<th class="text-center" style="width:100px">OPT</th>
				<th class="text-center" style="width:100px;">Fuel</th>
				<th class="text-center" style="width:100px;">C-Store</th>
			</tr>
			</thead>
			<tbody>
			<?php $__currentLoopData = $receipt; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<tr id="cloningrow">
					<td style="text-align: center">
						<p style="margin:0;"><?php echo e($loop->index + 1); ?></p>
					</td>
					<td class="text-center">
						<a href="#" onclick="eod_summarylist('<?php echo e($r->day); ?>')"
						   style="margin:0; text-decoration: none;">
							<?php echo e($r->date); ?>

						</a>
					</td>
					<td style="text-align: center">
						<a href="#" style="text-decoration: none;"
						   onclick="showPSSReceipt('<?php echo e($r->day); ?>')">
							<?php echo e($r->shift); ?>

						</a>
					</td>
					<td class="text-center">
					</td>
					<td class="text-center">
						<a href="#" onclick="getEvReceiptlist('id')"
						   style="text-decoration: none;">
							<?php echo e($r->ev); ?>

						</a>
					</td>
					<td class="text-center">
						<a href="#" onclick="getOptlist('id')"
						   style="text-decoration: none;">
							<?php echo e($r->opt); ?>

						</a>
					</td>
					<td class="text-center">
						<a href="<?php echo e(route('fuel-receipt-list',  ['date'=>$r->created_at])); ?>" target="_blank"
						   style="text-decoration: none;">
						   <?php echo e($r->count_receipt_fuel); ?>

						</a>
					</td>
					<td class="text-center">
						<a href="<?php echo e(route('local_cabinet.cstore_receipt_landing', $r->day)); ?>"
							target="_blank" xonclick="receipt_list('<?php echo e($r->day); ?>')"
						   style="text-decoration: none;">
							<?php echo e($r->count_receipt_cstore); ?>

						</a>
					</td>
				</tr>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</tbody>
		</table>
	</div>
</div>

<div id="res"></div>
<div class="modal fade bd-example-modal-lg" id="receiptoposModal"
	 tabindex="-1" role="dialog" aria-labelledby="receiptoposModal"
	 aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered"
		 style="border-radius:10px;min-width: 1050px;">
		<div class="modal-content bg-purplelobster"
			 style="border-radius:10px;color:white !important">
			<div class="modal-header">
				<h3 class="mb-0">Receipt List</h3>
			</div>
			<div class="modal-body" id="receiptoposModal-table"></div>
		</div>
	</div>
</div>

<div class="modal fade" id="eodSummaryListModal" tabindex="-1" role="dialog"
	 aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-75"
		 style="width:370px" role="document">
		<div id="eodSummaryListModal-table-div"
			 class="modal-content bg-white" style="width:370px">
		</div>
	</div>
</div>

<div class="modal fade bd-example-modal-lg" id="optlistModal"
	 tabindex="-1" role="dialog" aria-labelledby="optlistModal"
	 aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-centered">
		<div class="modal-content bg-purplelobster"
			 style="color:white !important">
			<div class="modal-header">
				<h3 class="mb-0">Outdoor Payment Terminal Receipt List</h3>
			</div>
			<div class="modal-body" id="optlistModal-table"></div>
		</div>
	</div>
</div>


<div class="modal fade bd-example-modal-lg" id="evlistModal"
	 tabindex="-1" role="dialog" aria-labelledby="optlistModal"
	 aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-centered">
		<div class="modal-content bg-purplelobster"
			 style="color:white !important">
			<div class="modal-header">
				<h3 class="mb-0">Electric Vehicle Receipt List</h3>
			</div>
			<div class="modal-body" id="evlistModal-table"></div>
		</div>
	</div>
</div>


<div class="modal fade bd-example-modal-lg" id="eodpssModal_1" tabindex="-1" role="dialog"
	 aria-hidden="true" style="display:none">
	<div class="modal-dialog modal-dialog-centered modal-md"
		 role="document">
		<div class="modal-content bg-purplelobster" id="" style="color:#fff !important">
			<div class="modal-header">
				<h3 class="mb-0">Personal Shift</h3>
			</div>

			<div class="modal-body" id="eod-model-div"></div>
		</div>
	</div>
</div>

<div class="modal fade" id="eodModal_1" tabindex="-1" role="dialog"
	 style="overflow:auto;" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-75"
		 style="width:370px" role="document">
		<div id="receipt-model-div" class="modal-content bg-white">
		</div>
	</div>
</div>
<div class="modal fade" id="voidreceiptmodal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-dialog-centered  mw-75 w-50" role="document">
		<div class="modal-content modal-inside bg-purplelobster">
			<div style="border:0" class="modal-header"></div>
			<div class="modal-body text-center">
				<h5 class="modal-title text-white" id="logoutModalLabel">
					Do you want to void the receipt?
				</h5>
				<br/><input type="hidden" id="receiptid" name="receiptid">
				<textarea placeholder="Reason for void receipt" rows='4'
						  id="reason_void" class="form-control"></textarea>
			</div>
			<div class="modal-footer"
				 style="border-top:0 none; padding:0;padding-bottom: 15px;">
				<div class="row" style="width: 100%; padding:0">
					<div class="col col-m-12 text-center">
						<a class="btn btn-primary"
						   href="javascript:void(0)" style="width:100px"
						   data-dismiss="modal"
						   onclick="onConfirmReceiptVoid()">
							Confirm
						</a>
						<button type="button" class="btn btn-danger"
								data-dismiss="modal" style="width:100px">
							Cancel
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="refundreceiptmodal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-dialog-centered  mw-75 w-50" role="document">
		<div class="modal-content modal-inside bg-purplelobster">
			<div style="border:0" class="modal-header"></div>
			<div class="modal-body text-center">
				<h5 class="modal-title text-white" id="logoutModalLabel">
					Please fill in the amount to be refunded.
				</h5>
				<br/><input type="hidden" id="refund_receiptid" name="receiptid">
				<div class="row align-items-center">
					<div class="col-6">
						<span>Refund Amount</span>
					</div>
					<div class="col-6">
						<input class="form-control text-right" id="receipt_refund_amount" type="number" placeholder="0.00" onkeyup='enforceMinMax(this)' />
						<input id="receipt_refund_amount_buffer" type="hidden" />
					</div>
				</div>
				<textarea placeholder="Reason for refund receipt" rows='4'
					id="reason_refund" class="form-control">
				</textarea>
			</div>
			<div class="modal-footer"
				 style="border-top:0 none; padding:0;padding-bottom: 15px;">
				<div class="row" style="width: 100%; padding:0">
					<div class="col col-m-12 text-center">
						<a class="btn btn-primary"
						   href="javascript:void(0)" style="width:100px"
						   data-dismiss="modal"
						   onclick="onConfirmReceiptRefund()">
							Confirm
						</a>
						<button type="button" class="btn btn-danger"
								data-dismiss="modal" style="width:100px">
							Cancel
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="modalMessage" tabindex="-1" role="dialog"
	 aria-hidden="true" style="text-align: center;">
	<div class="modal-dialog modal-dialog-centered  mw-75 w-50" role="document"
		 style="display: inline-flex;">
		<div class="modal-content modal-inside bg-purplelobster"
			 style="width: 100%;  background-color: <?php echo e(@$color); ?> !important">
			<div class="modal-header" style="border:0">&nbsp;</div>
			<div class="modal-body text-center">
				<h5 class="modal-title text-white" id="statusModalLabelMsg"></h5>
			</div>
			<div class="modal-footer" style="border-top:0 none;">&nbsp;</div>
		</div>
	</div>
</div>


<style>
.btn {
	color: #fff !Important;
}

.form-control:disabled, .form-control[readonly] {
	background-color: #e9ecef !important;
	opacity: 1;
}

#void_stamp {
	font-size: 100px;
	color: red;
	position: absolute;
	z-index: 2;
	font-weight: 500;
	margin-top: 130px;
	margin-left: 10%;
	transform: rotate(45deg);
	display: none;
}
</style>
<?php $__env->startSection('script'); ?>
<script>
$.ajaxSetup({
	headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
});

function getOptlist(id) {
	$.ajax({
		method: "post",
		url: "<?php echo e(route('local_cabinet.optList')); ?>",
		data: {id: id}
	}).done((data) => {
		$("#optlistModal-table").html(data);
		$("#optlistModal").modal('show');
	})
	.fail((data) => {
		console.log("data", data)
	});
}


function getEvReceiptlist(id) {
	$.ajax({
		method: "post",
		url: "<?php echo e(route('ev_receipt.evList')); ?>",
		data: {id: id}
	}).done((data) => {
		$("#evlistModal-table").html(data);
		$("#evlistModal").modal('show');
	})
	.fail((data) => {
		console.log("data", data)
	});
}


$('#eodsummarylist').DataTable({
});

$('.sorting_1').css('background-color', 'white');

function showPSSReceipt(date) {
	$('#eodSummaryListModal').modal('hide').html();
	$('#optlistModal').modal('hide').html();
	$('#receiptoposModal').modal('hide');
	$.ajax({
		url: "<?php echo e(route('pshift.list')); ?>",
		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		type: 'post',
		data: {date},
		success: function (response) {
			// console.log(response);
			//res
			$('#eod-model-div').html(response);
			$('#eodpssModal_1').modal('show');
		},
		error: function (e) {
			$('#responseeod').html(e);
			$("#msgModal").modal('show');
		}
	});
}


function onConfirmReceiptVoid() {
	var receiptid = $('#receiptid').val();
	var reason_void = $('#reason_void').val();
	var dt = time_void = new Date();
	var months = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL",
		"AUG", "SEP", "OCT", "NOV", "DEC"];

	time_void = time_void.getDate() + " " + months[time_void.getMonth()] + " " + time_void.getFullYear().toString().substr(-2) + " " + time_void.getHours() + ":" + time_void.getMinutes() + ":" + time_void.getSeconds();

	var dtstring = dt.getFullYear() + "-" + (dt.getMonth() + 1) + "-" + dt.getDate() + " " + dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();

	$("#void-stamp" + receiptid).show();
	$("#void-div" + receiptid).show();
	$("#void-time" + receiptid).html(time_void);
	$("#void-reason" + receiptid).html(reason_void);
	$.ajax({
		url: "<?php echo e(route('local_cabinet.receipt.void')); ?>",
		type: 'post',
		headers: {
			'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
		},
		data: {
			"receiptid": receiptid,
			"reason_void": reason_void,
			"voitdatetime": dtstring
		},
		dataType: 'json',
		success: function (response) {
			$("#void-stamp" + receiptid).show();
			$("#void-div" + receiptid).show();
			$("#void-time" + receiptid).html(time_void);
			$("#void-reason" + receiptid).html(reason_void);
		}
	});
}


function showReceipt(id) {
	$('#eodSummaryListModal').modal('hide').html();
	$('#optlistModal').modal('hide').html();
	$('#receiptoposModal').modal('hide');
	$.ajax({
		url: "/local_cabinet/eodReceiptPopup/" + id,
		// headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		type: 'get',
		success: function (response) {
			// console.log(response);
			$('#receipt-model-div').html(response);
			$('#eodModal_1').modal('show');
		},
		error: function (e) {
			$('#responseeod').html(e);
			$("#msgModal").modal('show');
		}
	});
}


function eod_summarylist(eod_date) {
	$.ajax({
		url: "<?php echo e(route('local_cabinet.eodsummary.popup')); ?>/" + eod_date,
		// headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		type: 'get',
		success: function (response) {
			// console.log(response);
			$('#eodSummaryListModal-table-div').html(response);
			$('#eodSummaryListModal').modal('show').html();
		},
		error: function (e) {
			$('#responseeod').html(e);
			$("#msgModal").modal('show');
		}
	});
}


function receipt_list(date) {
	$.ajax({
		url: "<?php echo e(route('local_cabinet.receipt.list')); ?>",
		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		type: 'post',
		data: {
			date
		},
		success: function (response) {
			//console.log(response);
			$('#receiptoposModal-table').html(response);
			$('#receiptoposModal').modal('show').html();

			$('#receipt-table').DataTable({
				"order": [],
				"columnDefs": [
					{"targets": -1, 'orderable': false}
				],
				"autoWidth": false,
			})
		},
		error: function (e) {
			$('#responseeod').html(e);
			$("#msgModal").modal('show');
		}
	});
}


function void_receipt(id) {
	$('#receiptid').val(id);
	$('#voidreceiptmodal').modal('show');
}

function refund_receipt(id) {
	$('#refund_receiptid').val(id);
	max = $("span#total_amount_unq").text().trim()
	$("#receipt_refund_amount").attr('min', 0);
	$("#receipt_refund_amount").attr('max', max);
	$("#receipt_refund_amount").val('');
	$("#receipt_refund_amount_buffer").val('');
	$("#refundreceiptmodal").modal('show');
}

function onConfirmReceiptRefund() {
	receipt_id = $('#refund_receiptid').val();
	$('#refund_receiptid').val('');
	amount = $("#receipt_refund_amount").val();
	$("#receipt_refund_amount").val('');
	$("#receipt_refund_amount_buffer").val('');
	description	= $("#reason_refund").val();
	$("#reason_refund").val('');
	
	$.post("<?php echo e(route('local_cabinet.cstore.refund')); ?>", {
		receipt_id, amount,description
	}).done((res) => {
		$('#eodSummaryListModal').modal('hide').html();
		$('#optlistModal').modal('hide').html();
		$('#receiptoposModal').modal('hide');
		$('#eodpssModal_1').modal('hide');
		$("#refundreceiptmodal").modal('hide');
		$("#eodModal_1").modal('hide');
		messageModal('Refund is successful');
	});
}

function pssReceiptPopup(login_time, logout_time, user_systemid) {
	$('#eodSummaryListModal').modal('hide').html();
	$('#optlistModal').modal('hide').html();
	$('#receiptoposModal').modal('hide');
	$('#eodpssModal_1').modal('hide');

	$.ajax({
		url: "<?php echo e(route('pshift.details')); ?>",
		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		type: 'post',
		data: {login_time, logout_time, user_systemid},
		success: function (response) {
			// console.log(response);
			//res
			$('#receipt-model-div').html(response);
			$('#eodModal_1').modal('show');
		},
		error: function (e) {
			$('#responseeod').html(e);
			$("#msgModal").modal('show');
		}
	});
}


function generate_refund(r_id, filled, e) {
	var data = {
		'receipt_id': r_id,
		'filled': filled
	};

	log2laravel('debug', 'generate_refund: data=' +
		JSON.stringify(data));


	crab = '#crab_' + r_id;
	$(crab).attr('style', 'width:30px;filter:grayscale(1) brightness(1.5);');
	$(crab).attr('disabled', true);

	$.ajax({
		url: "<?php echo e(route('local_cabinet.nozzle.down.refund')); ?>",
		type: 'post',
		headers: {
			'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
		},
		data: data,
		dataType: 'json',
		success: function (response) {

			log2laravel('info', 'generate_refund: ' +
				'local_cabinet.nozzle.down.refund SUCCESS: ' +
				JSON.stringify(response));

			if (response.responseText == "VOID") {
				messageModal('The receipt is void')
			}

		},
		error: function (response) {
			console.error(JSON.stringify(response));

			log2laravel('error', 'generate_refund: ' +
				'local_cabinet.nozzle.down.refund ERROR: ' +
				JSON.stringify(response));

			if (response.responseText == "VOID") {
				messageModal('The receipt is void')
			}

		}
	});
}


function messageModal(msg) {
	$('#modalMessage').modal('show');
	$('#statusModalLabelMsg').html(msg);
	setTimeout(function () {
		$('#modalMessage').modal('hide');
	}, 2500);
}

function enforceMinMax(el){
  if(el.value != ""){
    if(parseFloat(el.value) < parseFloat(el.min)){
      el.value = el.min;
    }
    if(parseFloat(el.value) > parseFloat(el.max)){
		el.value = el.max;
		$("#receipt_refund_amount_buffer").val(el.value.replace('.',''));
	}
  }
}

/////////////////////////////////
filter_price("#receipt_refund_amount", "#receipt_refund_amount_buffer");
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


</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('common.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('common.web', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Server_Files\oceania\trunk\oceania\resources\views/local_cabinet/local_cabinet.blade.php ENDPATH**/ ?>