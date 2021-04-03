<?php $__env->startSection('styles'); ?>

<style>
/* remove small icons from input number */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
	-webkit-appearance: none;
	margin: 0;
}

.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter,
.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_processing,
.dataTables_wrapper .dataTables_paginate {
	color: black !important;
	font-weight: normal !important;
}

/* Firefox */
input[type=number] {
	-moz-appearance: textfield;
}

.month_table > tr > th {
	font-size: 22px;
	color: white;
	background-color: rgba(255, 255, 255, 0.5);
}

.month_table > tr > td {
	color: #fff;
	font-weight: 600;
	border: unset;
	font-size: 20px;
	cursor: pointer;
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

table.dataTable tbody td {
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

.date_table1 > tbody > tr > th,
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
	width: 200px;
}

.greenshade {
	height: 30px;
	/* For browsers that do not support gradients */
	background-color: green;
	/* Standard syntax (must be last) */
	background-image: linear-gradient(-90deg, green, white);
}
.dt-button {
	display: none;
}

/* For calender short day */
.shortDay ul {
	llist-style: none;
	background-color: rgba(255, 255, 255, 0.5);
	position: relative;
	left: -75px;
	width: 124%;
	height: 55px;
	line-height: 42px;
}

.shortDay ul > li {
	font-size: 22px;
	color: white;
	font-weight: 700 !important;
	/* background-color: #2b1f1f; */
	padding: 5px 24px;
	text-align: left !important;
}

.list-inline-item:not(:last-child) {
	margin-right: 0 !important;
}

.modal-content {
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

.btn-custom-enable {
	background-color: #000 !important;
	color: #fff !important;
}

.btn-custom-enable:hover {
	background-color: green !important;
	color: #fff !important;
}

.btn-fuel-guide {
	width: 70px !important;
	height: 70px !important;
	border-radius: 10px;
	border-width: 0;
	background-image: linear-gradient(#e32803, #fd7848);
}
.butns {
	display: none
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('common.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('common.menubuttons', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->startSection('content'); ?>

<div class="container-fluid" style="overflow: auto;">

	<div class="d-flex mt-0" style="width: 100%;">
		<div class="col-sm-3 align-self-center" style="padding:0px">
			<h2 class="mb-0">Fuel Movement</h2>
		</div>

		<div class="col-sm-1 d-flex align-self-center" id="fuelThumbnail">
		</div>

		<div class="col-sm-2 d-flex"
			 style="align-self:center;float:left;padding:0px;">
			<a href="#" style="text-decoration: none;" id="selectFuelModal_btn"
			   href_fuel_prod_name="" href_fuel_prod_id="">
				<h4 style="margin-bottom:0px;padding-top:0;line-height:1.5;">
					Select Fuel
				</h4>
			</a>
		</div>

		<div class="col-sm-1" style="align-self:center;float:left;padding:0px;">
			<input type="hidden" id='startDate'>
			<h4 style="margin-bottom:0px;padding-top:0;line-height:1.5;">
				<a href="#" style="text-decoration: none; padding-top:10px;"
				   onclick="show_month_modal()"
				   id="month_from" name="froms">Month</a>
			</h4>
		</div>

		<div class="col-md-3 pr-5" style="align-self:center;padding:0px;
		text-align:right;left:0;z-index:100">
			<h5 class="mb-0"><?php echo e($location->name??''); ?> </h5>
		</div>

		<div class="col-sm-2 pl-0">
			<div class="row mb-0" style="float:right;">
				
				<button onclick="window.open('<?php echo e(route("fuel.guide")); ?>')"
						class="btn btn-success btn-fuel-guide sellerbutton m-0"
						style="padding:0px;float:right;"
						id="guide_btn">Guide
				</button>
				<button onclick="window.open('<?php echo e(route("fuel.stockIn")); ?>')"
						class="btn btn-success bg-stockin sellerbutton mr-0 mb-0"
						style="padding:0px;float:right;margin-left:5px;
					border-radius:10px;"
						id="stockin_btn">Stock<br>In
				</button>
				<button onclick="window.open('<?php echo e(route("fuel.stockOut")); ?>')"
						class="btn btn-success bg-stockout sellerbutton
				mb-0 mr-0"
						style="padding:0px;float:right;margin-left:5px;
					border-radius:10px"
						id="stockout_btn">Stock<br>Out
				</button>
			</div>
		</div>

	</div>
	<div style="padding-left:0;padding-right:0; margin-top:5px;"
		 class="col-sm-12">
		<table id="fuelMgt" class="table table-bordered">
			<thead class="thead-dark">
			<tr>
				<th style="width:10px">No</th>
				<th style="width:140px">Date</th>
				<th style="width:100px">C/Forward</th>
				<th style="width:100px">Sales&nbsp;(&ell;)</th>
				<th style="width: 100px">Receipt</th>
				<th style="width: 100px">Book</th>
				<th style="width: 100px">Tank Dip</th>
				<th style="width: 100px">Daily&nbsp;Variance</th>
				<th style="width: 150px">Cumulative</th>
				<th style="width: 50px">%</th>
			</tr>
			</thead>
			<tbody>

			</tbody>
		</table>
	</div>
	<div style="padding-left:0;padding-right:0; margin-top:30px;"
		 class="col-sm-12"></div>
</div>
<div class="modal fade" id="c_ForwardEditModal-"
	 tabindex="-1" role="dialog" style="padding-right:0 !important"
	 aria-labelledby="logoutModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-75 w-25" role="document">
		<div class="modal-content bg-purplelobster">
			<div class="modal-body text-center">
				<input type="hidden" name="chegeshappen"
					   value="0" id="chegeshappen-">
				<div class="mb-0 form-group">
					<input type="number"
						   style="margin-bottom:2px;text-align:right;"
						   id=""
						   class="form-control c_ForwardEditModal_input"
						   name="fullname" placeholder="0.00"
						   value="" autocomplete="off"/>
					<input type="hidden"
						   class="c_ForwardEditModal_buffer" value="0.00">
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="tank_dipEditModal-"
	 tabindex="-1" role="dialog" style="padding-right:0 !important"
	 aria-labelledby="logoutModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-75 w-25" role="document">
		<div class="modal-content bg-purplelobster">
			<div class="modal-body text-center">
				<input type="hidden" name="user_id"
					   value=""
					   id="userEditModalInput0-">

				<!-- WHAT KIND OF NAME IS chegeshappen?? THIS IS NOT ENGLISH -->
				<!-- And why are there 3 different names:
					 chegeshappen, chegeshappen-1 and chagehappen-??? -->

				<input type="hidden" name="chegeshappen"
					   value="0" id="chegeshappen-1">
				<div class="mb-0 form-group">
					<input type="number" style="text-align:right;"
						   class="form-control chagehappen- tank_dipEditModal_input"
						   name="fullname" placeholder="0.00"
						   value="" autocomplete="off"/>
					<input type="hidden"
						   class="tank_dipEditModal_buffer" value="0.00">
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Popup for select product start -->
<div id="productsModal" class="modal fade" tabindex="-1" role="dialog"
	 aria-hidden="true">
	<div class="modal-dialog modal modal-dialog-centered" style="margin: auto;">
		<div style="border-radius:10px"
			 class="modal-content bg-purplelobster">
			<div class="modal-header">
				<h3 style="margin-bottom:0">Select Fuel</h3>
			</div>
			<div class="modal-body" style="">
				<div class="row" style="width:100%">
					<div class="col-md-12" style="">
						<div id="productList" class="creditmodelDV"
							 style="display:flex; flex-wrap: wrap; justify-content: flex-start;">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Popup for select product end -->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script type="text/javascript">
row_id = "";
fuel_prod_id = "";
var dt = $('#fuelMgt').DataTable();

function c_ForwardEditModal(id) {
	row_id = id;
	$(".c_ForwardEditModal_input").val("");
	$(".c_ForwardEditModal_buffer").val("");
	$(".c_ForwardEditModal_buffer").attr("id", "c_ForwardEditModal_buffer" + id);
	$(".c_ForwardEditModal_input").attr("id", "c_ForwardEditModal_input" + id);
	$("#c_ForwardEditModal-").modal("show");
	filter_price("#c_ForwardEditModal_input" + id, "#c_ForwardEditModal_buffer" + id)
}


function tank_dipEditModal(id) {
	row_id = id;
	$(".tank_dipEditModal_input").val("");
	$(".tank_dipEditModal_buffer").val('');
	$(".tank_dipEditModal_buffer").attr("id", "tank_dipEditModal_buffer" + id);
	$(".tank_dipEditModal_input").attr("id", "tank_dipEditModal_input" + id);
	$("#tank_dipEditModal-").modal("show");
	filter_price("#tank_dipEditModal_input" + id, "#tank_dipEditModal_buffer" + id);
}


function filter_price(target_field, buffer_in) {
	$(target_field).on("keydown", function (event) {
		event.preventDefault()

		if (event.keyCode == 8) {
			$(buffer_in).val('')
			$(target_field).val('')
			return null
		}

		if (isNaN(event.key) || $.inArray(
			event.keyCode, [13, 38, 40, 37, 39]) !== -1 || event.keyCode == 13) {

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
}


function atm_money(num) {
	if (num == 0) {
		return '0.00';
	}
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

function getOrignalval(num) {
	if (!num) {
		return 0.00;
	} else {
		return num.split(',').join('');
	}
}

$('#c_ForwardEditModal-').on('hidden.bs.modal', function (e) {
	var c_Forward = $("#c_ForwardEditModal_input" + row_id).val();
	$("#c_ForwardEditModal_input" + row_id).off("keydown")
	if (!c_Forward) {
		c_Forward = "0.00"
	}
	$("#c_Forward" + row_id).html(parseFloat(c_Forward).toLocaleString('us', {
		minimumFractionDigits: 2,
		maximumFractionDigits: 2
	}));

	var sales = parseFloat(getOrignalval($("#sales" + row_id).html()));
	var receipt = parseFloat(getOrignalval($("#receipt" + row_id).html()));
	var tank_dip = parseFloat(getOrignalval($("#tank_dip" + row_id).html()));
	var book = c_Forward - sales + receipt;

	if (!book) {
		book = "0.00"
	}
	$("#book" + row_id).html(book.toLocaleString('us', {minimumFractionDigits: 2, maximumFractionDigits: 2}));

	var daily_variance = tank_dip - book;

	if (!daily_variance) {
		daily_variance = "0.00"
	}
	var cumulative = daily_variance;

	var percentage = cumulative / book * 100;

	$("#daily_variance" + row_id).html(daily_variance.toLocaleString('us', {
		minimumFractionDigits: 2,
		maximumFractionDigits: 2
	}));
	$("#cumulative" + row_id).html(cumulative.toLocaleString('us', {
		minimumFractionDigits: 2,
		maximumFractionDigits: 2
	}));
	$("#percentage" + row_id).html(percentage.toLocaleString('us', {
		minimumFractionDigits: 2,
		maximumFractionDigits: 2
	}));

	$.ajax({
		url: "<?php echo e(route('fuel_movement.cforward.update')); ?>",
		async: true,
		type: 'POST',
		'headers': {
			'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
		},
		"data": {
			"id": row_id,
			"c_Forward": isNaN(c_Forward) ? 0:c_Forward,
			"book": isNaN(book) ? 0:book,
			"daily_variance": isNaN(daily_variance) ? 0:daily_variance,
			"cumulative": isNaN(cumulative) ? 0:cumulative,
			"percentage": isNaN(percentage) ? 0:percentage,
		},
		success: function (response) {
			//console.log(response);
			getMainTable();
		},
		error: function (e) {
			console.log('error', e);
		}
	});
});


$('#tank_dipEditModal-').on('hidden.bs.modal', function (e) {
	var tank_dip = $("#tank_dipEditModal_input" + row_id).val();
	$("#tank_dipEditModal_input" + row_id).off("keydown")

	if (!tank_dip) {
		tank_dip = "0.00"
	}
	$("#tank_dip" + row_id).html(parseFloat(tank_dip).toLocaleString('us', {
		minimumFractionDigits: 2,
		maximumFractionDigits: 2
	}));

	var book = parseFloat(getOrignalval($("#book" + row_id).html()));
	if (!book) {
		book = "0.00"
	}
	var daily_variance = tank_dip - book;

	if (!daily_variance) {
		daily_variance = "0.00"
	}
	var lastrowid = ($("#cumulative" + row_id).attr("href_row_number_cumulative")) - 1;
	var cumulativelastday = parseFloat(getOrignalval($("[href_row_number_cumulative=" + lastrowid + "]").html()));
	if (!cumulativelastday) {
		cumulativelastday = "0.00"
	}
	cumulativelastday = parseFloat(cumulativelastday)
	var cumulative = daily_variance + cumulativelastday;
	if (book != "0.00")
		var percentage = cumulative / book * 100;
	else
		var percentage = cumulative

	$("#daily_variance" + row_id).html(daily_variance.toLocaleString('us', {
		minimumFractionDigits: 2,
		maximumFractionDigits: 2
	}));
	$("#cumulative" + row_id).html(cumulative.toLocaleString('us', {
		minimumFractionDigits: 2,
		maximumFractionDigits: 2
	}));
	$("#percentage" + row_id).html(percentage.toLocaleString('us', {
		minimumFractionDigits: 2,
		maximumFractionDigits: 2
	}));

	$.ajax({
		url: "<?php echo e(route('fuel_movement.tankdip.update')); ?>",
		type: 'POST',
		async: true,
		'headers': {
			'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
		},
		"data": {
			//"id": row_id,
			"date": row_id,
			"tank_dip": tank_dip,
			"daily_variance": daily_variance,
			"percentage": percentage,
			'ogfuel_id':fuel_prod_id,
			"cumulative": cumulative
		},
		success: function (response) {
			var pad = function (num) {
				return ('00' + num).slice(-2)
			};
			var tdate = dateToday.getUTCFullYear() + '-' +
				pad(dateToday.getUTCMonth() + 1) + '-' +
				pad(dateToday.getUTCDate());

			var rowdate = $("#c_Forward" + row_id).attr("href_date");
			if (rowdate != tdate) {
				var id = ($("#cumulative" + row_id).attr("href_row_number_cumulative"));
				//calculateCumulative(tank_dip, id, cumulative);
			}
			getMainTable();
		},
		error: function (e) {
			console.log('error', e);
		}
	});
});

function calculateCumulative(tank_dip_old, r_id, cumulativelastday) {
	r_id = parseInt(r_id);
	tank_dip_old = parseFloat(tank_dip_old);
	cumulativelastday = parseFloat(cumulativelastday);

//    var newid = ($("#cumulative"+).attr("href_row_number_cumulative"));
	var new_did = $("[href_row_number=" + (r_id + 1) + "]").attr("href_id");

	var c_Forward = tank_dip_old;
	if (!c_Forward) {
		c_Forward = "0.00"
	}
	$("#c_Forward" + new_did).html(parseFloat(c_Forward).toLocaleString('us', {
		minimumFractionDigits: 2,
		maximumFractionDigits: 2
	}));

	var sales = parseFloat(getOrignalval($("#sales" + new_did).html()));
	var receipt = parseFloat(getOrignalval($("#receipt" + new_did).html()));
	var tank_dip = parseFloat(getOrignalval($("#tank_dip" + new_did).html()));
	var book = c_Forward - sales + receipt;
	if (!book) {
		book = "0.00"
	}
	$("#book" + new_did).html(book.toLocaleString('us', {minimumFractionDigits: 2, maximumFractionDigits: 2}));

	var daily_variance = tank_dip - book;

	if (!daily_variance) {
		daily_variance = "0.00"
	}
	var cumulative = daily_variance + cumulativelastday;

	var percentage = cumulative / book * 100;

	$("#daily_variance" + new_did).html(parseFloat(daily_variance).toLocaleString('us', {
		minimumFractionDigits: 2,
		maximumFractionDigits: 2
	}));
	$("#cumulative" + new_did).html(parseFloat(cumulative).toLocaleString('us', {
		minimumFractionDigits: 2,
		maximumFractionDigits: 2
	}));
	$("#percentage" + new_did).html(parseFloat(percentage).toLocaleString('us', {
		minimumFractionDigits: 2,
		maximumFractionDigits: 2
	}));

	$.ajax({
		url: "<?php echo e(route('fuel_movement.cforward.update')); ?>",
		async: true,
		type: 'POST',
		'headers': {
			'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
		},
		"data": {
			"id": new_did,
			"c_Forward": c_Forward,
			"book": book,
			"daily_variance": daily_variance,
			"cumulative": cumulative,
			"percentage": percentage
		},
		success: function (response) {
			var pad = function (num) {
				return ('00' + num).slice(-2)
			};
			var tdate = dateToday.getUTCFullYear() + '-' +
				pad(dateToday.getUTCMonth() + 1) + '-' +
				pad(dateToday.getUTCDate());
			console.log(tdate);
			var rowdate = $("#c_Forward" + new_did).attr("href_date");
			if (rowdate != tdate) {
				var id = ($("#cumulative" + new_did).attr("href_row_number_cumulative"));
				calculateCumulative(tank_dip, id, cumulative)
			}
		},
		error: function (e) {
			console.log('error', e);
		}
	});

}

function select_month(month) {
	var cdate = $('#month_from').html();
	$("." + cdate.split(' ')[0]).addClass("btn-custom-enable");
	$("." + cdate.split(' ')[0]).removeClass("btn-success");
	$("." + cdate.split(' ')[0]).css("font-weight", " normal");
	var currYear = $("#main_year").find("h3").text();
	$('#month_from').html(month + ' ' + currYear);
	var cdate = $('#month_from').html();
	$("." + cdate.split(' ')[0]).removeClass("btn-custom-enable");
	$("." + cdate.split(' ')[0]).addClass("btn-success");
	$("." + cdate.split(' ')[0]).css("font-weight", " bold");
	// alert( dt.rows().count());
	rowcheck()
	if (fuel_prod_id) {
		getMainTable()
	}
}


function daysInMonth(month, year) {
	return new Date(year, month, 0).getDate();
}


function rowcheck() {
	var cdate = $('#month_from').html();
	var fields = cdate.split(' ');
	var month = fields[0];
	var currYear = fields[1];
	var monthArray = 'Jan Feb Mar Apr May Jun Jul Aug Sep Oct Nov Dec'.split(' ');

	month = monthArray.indexOf(month);
	var month = month + 1;
	var year = currYear;
	for (var i = 31; i >= 0; i--) {
		dt.row(i).remove().draw();
	}

	for (var i = dt.rows().count() + 1; i <= daysInMonth(month, year); i++) {
		if (i < 10) {
			day_custom = `0${i}`;
		} else {
			day_custom = i;
		}
		dt.row.add([i, day_custom + "" + monthArray[month - 1] + "" + year.slice(-2), "", "", "", "", "", "", "", ""
		]).draw();
	}

	for (var i = dt.rows().count(); i >= daysInMonth(month, year); i--) {
		dt.row(i).remove().draw();
	}
}


function openSales(odate) {
	$("#selectFuelModal_btn").attr("href_fuel_prod_id");
	window.open('fuel/movement/showproductledgerSale/' + fuel_prod_id + '/' + odate);
}


function openReceipt(e) {
	//href_date = parseInt($(e).attr('href_row_number')) + 1;
	//year = $("#main_year").find("h3").text();
//	month = $('#month_from').html().trim();
//	$("#selectFuelModal_btn").attr("href_fuel_prod_id");
	window.open(`fuel_movement/showproductledgerreceipt/${fuel_prod_id}/${e}`);
}


function showOgFuelProducts() {
	$.ajax({
		url: "<?php echo e(route('fuel_movement.showOgFuelProducts')); ?>",
		type: "GET",
		dataType: "JSON",
		success: function (response) {
			var productList = response.data;
			var procutsModal = $("#productsModal");
			var html = (productList.length) ? '' : 'No Products Available';

			$(procutsModal).find('#productList').html(response.output);
			var procutsModal = $("#productsModal");
			$(procutsModal).modal('show');
		},
		error: function (response) {
			// console.log('****** showProducts() ERROR! *****');
			//console.log(JSON.stringify(response));
		},
	});
}

Date.prototype.withoutTime = function () {
    var d = new Date(this);
    d.setHours(0, 0, 0, 0);
    return d;
}
var data_r;
function getMainTable() {
	var cdate = $('#month_from').html();
	var fields = cdate.split(' ');
	var month = fields[0];
	var currYear = fields[1];
	var monthArray = 'Jan Feb Mar Apr May Jun Jul Aug Sep Oct Nov Dec'.split(' ');
	var pad = function (num) {
		return ('00' + num).slice(-2)
	};
	/* var tdate = dateToday.getUTCFullYear() + '-' +
		 pad(dateToday.getUTCMonth() + 1) + '-' +
		 pad(dateToday.getUTCDate());*/
	var tdate = "<?php echo e($set_update); ?>"
	month = monthArray.indexOf(month) + 1;
	if (month < 10) {
		month = "0" + month;
	}
	var startmonth = currYear + "-" + month + "-01";
	// startmonth = "2020-11-27";
	var endmonth = currYear + "-" + month + "-" + daysInMonth(month, currYear);

	$.ajax({
		url: "<?php echo e(route('fuel_movement.fuelmovementmaintable')); ?>",
		type: 'POST',
		'headers': {
			'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
		},
		"data": {
			"fuel_prod_id": fuel_prod_id,
			"startmonth": startmonth,
			"endmonth": endmonth
		},
		success: function (response) {
	/*
		for (var k in response) {
			var fields = response[k]['date'].split(' ');
			var date = fields[0].split('-');
			var formateddate = date[2] + "" + monthArray[date[1] - 1] +
				"" + date[0].slice(-2);

			var comp_date_1 = new Date(companycreated);
			var comp_date_2 = new Date(fields[0]);
				
			if(comp_date_2.withoutTime().getTime() == comp_date_1.withoutTime().getTime()) {
				cF = `<a href="#" style="text-decoration: none;" onclick='c_ForwardEditModal(${response[k]['id']})'>
						${response[k]['cforward'].toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</a>`;
			} else {
				cF =  response[k]['cforward'].toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
			}

			if (comp_date_2 < comp_date_1) {
				dt.row(date[2] - 1).data([parseInt(date[2]), formateddate, 
					cF,'0.00','0.00','0.00','0.00','0.00','0.00','0.00'
				]).draw();
				continue;
			}

			if (fields[0] == tdate && response[k]['cforward'] == 0) {
				dt.row(date[2] - 1).data([parseInt(date[2]), formateddate,
					cF,	
					'<a href_row_number="' + (date[2] - 1) +
					'" href="#" id="sales' + response[k]['id'] +
					'" style="text-decoration: none;" onclick="openSales(\'' +
					fields[0] + '\')">' +
					response[k]['sales'].toLocaleString('us', {
						minimumFractionDigits: 2,
						maximumFractionDigits: 2
					}).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '</a>',
					'<a href_row_number="' + (date[2] - 1) +
					'" href="#" id="receipt' + response[k]['id'] +
					'" style="text-decoration: none;" onclick="openReceipt(this)">' +
					response[k]['receipt'].toLocaleString('us', {
						minimumFractionDigits: 2,
						maximumFractionDigits: 2
					}) + '</a>',
					'<span href_row_number="' + (date[2] - 1) +
					'" style="text-align: center" id="book' +
					response[k]['id'] + '">' +
					response[k]['book'].toLocaleString('us', {
						minimumFractionDigits: 2,
						maximumFractionDigits: 2
					}) + '</span>',
					'<a href_row_number="' + (date[2] - 1) +
					'" href="#" id="tank_dip' + response[k]['id'] +
					'" style="text-decoration: none;" onclick="tank_dipEditModal(' +
					response[k]['id'] + ')">' +
					response[k]['tank_dip'].toLocaleString('us', {
						minimumFractionDigits: 2,
						maximumFractionDigits: 2
					}) + '</a>',
					'<span href_row_number="' + (date[2] - 1) +
					'" style="text-align: center" id="daily_variance' +
					response[k]['id'] + '">' +
					response[k]['daily_variance'].toLocaleString('us', {
						minimumFractionDigits: 2,
						maximumFractionDigits: 2
					}) + '</span>',
					'<span href_row_number_cumulative="' + (date[2] - 1) +
					'" style="text-align: center" id="cumulative' +
					response[k]['id'] + '">' +
					response[k]['cumulative'].toLocaleString('us', {
						minimumFractionDigits: 2,
						maximumFractionDigits: 2
					}) + '</span>',
					'<span href_row_number="' + (date[2] - 1) +
					'" style="text-align: center" id="percentage' +
					response[k]['id'] + '">' +
					response[k]['percentage'].toLocaleString('us', {
						minimumFractionDigits: 2,
						maximumFractionDigits: 2
					}) + '</span>',
				]).draw();
			} else if (response[k]['cforward'] == 0 && response[k]['tank_dip'] == 0) {
				dt.row(date[2] - 1).data([parseInt(date[2]), formateddate,
					cF,
					'<a href_row_number="' + (date[2] - 1) +
					'" href="#" id="sales' + response[k]['id'] +
					'" style="text-decoration: none;" onclick="openSales(\'' +
					fields[0] + '\')">' +
					response[k]['sales'].toLocaleString('us', {
						minimumFractionDigits: 2,
						maximumFractionDigits: 2
					}).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '</a>',
					'<a href_row_number="' + (date[2] - 1) +
					'" href="#" id="receipt' + response[k]['id'] +
					'" style="text-decoration: none;" onclick="openReceipt(this)">' +
					response[k]['receipt'].toLocaleString('us', {
						minimumFractionDigits: 2,
						maximumFractionDigits: 2
					}) + '</a>',
					response[k]['book'].toFixed(2),
					'<a href_row_number="' + (date[2] - 1) +
					'" href="#" id="tank_dip' + response[k]['id'] +
					'" style="text-decoration: none;" onclick="tank_dipEditModal(' +
					response[k]['id'] + ')">' +
					response[k]['tank_dip'].toLocaleString('us', {
						minimumFractionDigits: 2,
						maximumFractionDigits: 2
					}) + '</a>',
					response[k]['daily_variance'].toFixed(2),
					response[k]['cumulative'].toFixed(2),
					response[k]['percentage'].toFixed(2),
				]).draw();
			} else {
				dt.row(date[2] - 1).data([parseInt(date[2]), formateddate,
					cF,
					'<a href_row_number="' + (date[2] - 1) +
					'" href="#" id="sales' + response[k]['id'] +
					'" style="text-decoration: none;" onclick="openSales(\'' +
					fields[0] + '\')">' +
					response[k]['sales'].toLocaleString('us', {
						minimumFractionDigits: 2,
						maximumFractionDigits: 2
					}).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '</a>',
					'<a href_row_number="' + (date[2] - 1) +
					'" href="#" id="receipt' + response[k]['id'] +
					'" style="text-decoration: none;" onclick="openReceipt(this)">' +
					response[k]['receipt'].toLocaleString('us', {
						minimumFractionDigits: 2,
						maximumFractionDigits: 2
					}) + '</a>',
					'<span href_row_number="' + (date[2] - 1) +
					'" style="text-align: center" id="book' +
					response[k]['id'] + '">' +
					response[k]['book'].toLocaleString('us', {
						minimumFractionDigits: 2,
						maximumFractionDigits: 2
					}) + '</span>',
					'<a href_row_number="' + (date[2] - 1) +
					'" href="#" id="tank_dip' + response[k]['id'] +
					'" style="text-decoration: none;" onclick="tank_dipEditModal(' +
					response[k]['id'] + ')">' +
					response[k]['tank_dip'].toLocaleString('us', {
						minimumFractionDigits: 2,
						maximumFractionDigits: 2
					}) + '</a>',
					'<span href_row_number="' + (date[2] - 1) +
					'" style="text-align: center" id="daily_variance' +
					response[k]['id'] + '">' +
					response[k]['daily_variance'].toLocaleString('us', {
						minimumFractionDigits: 2,
						maximumFractionDigits: 2
					}) + '</span>',
					'<span href_row_number_cumulative="' +
					(date[2] - 1) + '" style="text-align: center" id="cumulative' +
					response[k]['id'] + '">' +
					response[k]['cumulative'].toLocaleString('us', {
						minimumFractionDigits: 2,
						maximumFractionDigits: 2
					}) + '</span>',
					'<span href_row_number="' + (date[2] - 1) +
					'" style="text-align: center" id="percentage' +
					response[k]['id'] + '">' +
					response[k]['percentage'].toLocaleString('us', {
						minimumFractionDigits: 2,
						maximumFractionDigits: 2
					}) + '</span>',
				]).draw();
			}
		}
		
			/*/
			
			var comp_date_2 = new Date(startmonth);
			var	comp_date_1 = new Date(companycreated);
			for (var d = new Date(comp_date_2); d.getMonth() == comp_date_2.getMonth(); d.setDate(d.getDate() + 1)) {

				record = response.find( (f) => {
					d2 = new Date(f.date);
					return d.withoutTime().getTime() === d2.withoutTime().getTime();
				});
				
				formateddate = `${d.getDate() < 10 ? `0${d.getDate()}`:d.getDate() }${monthArray[d.getMonth()]}${d.getYear() - 100}`;
			
				if (d.withoutTime().getTime() < comp_date_1.withoutTime().getTime()) {
					dt.row(d.getDate() - 1).data([parseInt(d.getDate()), formateddate, 
						'0.00','0.00','0.00','0.00','0.00','0.00','0.00','0.00'
					]).draw();
					continue;
				}

				
				if(d.withoutTime().getTime() == comp_date_1.withoutTime().getTime()) {
					cF = `<a href="#" style="text-decoration: none;" onclick='c_ForwardEditModal(${record ? record.id:0})'>
						${record ? record.cforward.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","):'0.00'}</a>`;
				} else {
					cF = record ? record.cforward.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","):'0.00';	
				}

				sales = `<a href="#" style="text-decoration: none;" onclick='openSales("${formateddate}")'>
						${record ? record.sales.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","):'0.00'}</a>`;

				receipt = `<a href="#" style="text-decoration: none;" onclick='openReceipt("${formateddate}")'>
						${record ? record.receipt.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","):'0.00'}</a>`;

				tank_dip = `<a href="#" style="text-decoration: none;" onclick="tank_dipEditModal('${formateddate}')">
						${record ? record.tank_dip.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","):'0.00'}</a>`;

				book = record ? record.book.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","):'0.00';

				daily_variance = record ? record.daily_variance.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","):'0.00';

				cumulative = record ? record.cumulative.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","):'0.00';

				percentage = record ? record.percentage.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","):'0.00';

				dt.row(d.getDate() - 1).data([parseInt(d.getDate()), formateddate, 
					cF, sales, receipt, book, tank_dip, daily_variance, cumulative, percentage
				]).draw();
			}

		},
		error: function (e) {
			console.log('error', e);
		}
	});
}


$("#selectFuelModal_btn").on('click', function () {
	$("#productsModal").modal('show');
	showOgFuelProducts();
});


$('body').on('click', '.productselect', function () {
	let this_anchor_fuel = $(this);

	fuel_prod_id = this_anchor_fuel.attr("href_fuel_prod_id");
	var fuel_prod_name = this_anchor_fuel.attr("href_fuel_prod_name");
	var fuel_prod_thumbnail = this_anchor_fuel.attr("href_fuel_prod_thumbnail");
	var fuel_prod_systemid = this_anchor_fuel.attr("href_fuel_prod_systemid");
	var fuel_prod_price = this_anchor_fuel.attr("href_fuel_prod_price");
	if (typeof fuel_prod_thumbnail !== typeof undefined &&
		fuel_prod_systemid !== false) {

		var imagePath = "/images/product/" + fuel_prod_systemid + '/thumb/' + fuel_prod_thumbnail;
		var thumbnailHtml = "<img class='thumbnail align-self-center' href_fuel_thumbnail='' width='70px' height='70px' style='object-fit:contain;float:right;margin-left:0;margin-top:0;' src='" + imagePath + "'>";
		$("#fuelThumbnail").html(thumbnailHtml);
	}

	$("#selectFuelModal_btn").html('<h4 style="margin-bottom:0">' +
		fuel_prod_name + '</h4><p style="font-size:18px;margin-bottom:0;">'+
		fuel_prod_systemid + '</p>');
	$("#selectFuelModal_btn").attr("thumbnail", fuel_prod_thumbnail);
	$("#selectFuelModal_btn").attr("href_fuel_prod_name", fuel_prod_name);
	$("#selectFuelModal_btn").attr("href_fuel_prod_id", fuel_prod_id);

	$("#hrefBtn").removeClass("disabled");
	$("#productsModal").modal('hide');
	rowcheck()
	getMainTable()
});


function show_month_modal() {
	jQuery('#showDateModalFrom').modal('show');
}


var dateToday = new Date();
var companycreated = "<?php echo e($company->approved_at??date('Y-m-d')); ?>";
companycreated = companycreated.split(' ')[0];

var dateAccountCreated = companycreated.split('-')[1] +
	' ' + companycreated.split('-')[0]; //format = MONTH(space)Year
var currentYear = dateToday.getFullYear();
var yearSelected = currentYear;
var monthArray = 'Jan Feb Mar Apr May Jun Jul Aug Sep Oct Nov Dec'.split(' ');

$(document).ready(function () {
	$('#month_from').html(monthArray[dateToday.getMonth()] + ' ' + currentYear);
	// Bind Events
	$('.prev-year').click(function () {
		yearNavigationHandler(-1);
	});
	$('.next-year').click(function () {
		yearNavigationHandler(1);
	});
	$(".year").html("<h3>" + currentYear + "</h3>");
	displayCalendar();
	shoot_event_for_month();
	//
});


function yearNavigationHandler(dir) {
	if (dateAccountCreated.split(' ')[1] <= (yearSelected + dir) && currentYear >= (yearSelected + dir)) {
		yearSelected = yearSelected + dir;

		$(".year").html("<h3>" + yearSelected + "</h3>");
		displayCalendar()
		shoot_event_for_month();
	}
	var cdate = $('#month_from').html();
	if (cdate.split(' ')[1] == (yearSelected)) {

		$("." + cdate.split(' ')[0]).removeClass("btn-custom-enable");
		$("." + cdate.split(' ')[0]).addClass("btn-success");
		$("." + cdate.split(' ')[0]).css("font-weight", " bold");
	} else {
		$("." + cdate.split(' ')[0]).addClass("btn-custom-enable");
		$("." + cdate.split(' ')[0]).removeClass("btn-success");
		$("." + cdate.split(' ')[0]).css("font-weight", " normal");
	}
}


function displayCalendar() {
	clearMonths();
	var table = $("table.month_table");
	var monthNo = 0;

	for (var i = 0; i < 3; i++) {
		var row = $("<tr/>");
		for (var j = 0; j < 4; j++) {
			var td = $("<td/>");
			$(td).html(monthArray[monthNo]);
			addClassToMonth(td, monthNo, yearSelected);
			monthNo++;
			$(row).append(td);
		}
		$(table).append(row);
	}
	shoot_event_for_month();
}


function addClassToMonth(td, month, year) {
	if (year === currentYear) {
		if (month > dateToday.getMonth()) {
			$(td).css("cursor", "not-allowed");
			$(td).addClass("disabled");
		}
	} else if (year > currentYear) {
		$(td).css("cursor", "not-allowed");
		$(td).addClass("disabled");
	}

	//year = account creation year
	if (year == dateAccountCreated.split(' ')[1]) {

		//month <  account creation month
		if (month < dateAccountCreated.split(' ')[0] - 1) {
			$(td).css("cursor", "not-allowed");
			$(td).addClass("disabled");
		}

		//year < account creation year
	} else if (year < dateAccountCreated.split(' ')[1]) {
		$(td).css("cursor", "not-allowed");
		$(td).addClass("disabled");
	}
}


function clearMonths() {
	var table = $("table.month_table");
	$(table).empty();
}


function shoot_event_for_month() {

	$('.month_table > tr > td').click(function (e) {
		var target = e.target;
		if ($(target).hasClass('disabled')) {
			return false;
		} else {
			$('.month_table > tr > td').removeClass('selected_date1');
			$(target).addClass('selected_date1');
		}

		let month1 = $(target).html();
		let year1 = $('.year > h3').html();
		$('#startDate').attr('min', year1 + '-' + (monthArray.indexOf(month1) + 1));
		$('#month_from').html($(target).html() + ' ' + yearSelected);

		jQuery('#showMonthModalFrom').modal('hide');
	});
	if (dateAccountCreated.split(' ')[1] == (yearSelected) && currentYear == (yearSelected)) {
		for (i = 1; i <= 12; i++) {
			if (i < dateAccountCreated.split(' ')[0] || i > dateToday.getMonth() + 1) {
				$("." + monthArray[i - 1]).addClass("noClick");
				$("." + monthArray[i - 1]).addClass("btn-custom");
				$("." + monthArray[i - 1]).removeClass("btn-custom-enable");
			} else {
				$("." + monthArray[i - 1]).removeClass("noClick");
				$("." + monthArray[i - 1]).removeClass("btn-custom");
				$("." + monthArray[i - 1]).addClass("btn-custom-enable");
			}
		}
	} else if (dateAccountCreated.split(' ')[1] == (yearSelected)) {
		for (i = 1; i <= 12; i++) {
			if (i < dateAccountCreated.split(' ')[0]) {
				$("." + monthArray[i - 1]).addClass("noClick");
				$("." + monthArray[i - 1]).addClass("btn-custom");
				$("." + monthArray[i - 1]).removeClass("btn-custom-enable");
			} else {
				$("." + monthArray[i - 1]).removeClass("noClick");
				$("." + monthArray[i - 1]).removeClass("btn-custom");
				$("." + monthArray[i - 1]).addClass("btn-custom-enable");
			}
		}
	} else if (currentYear == (yearSelected)) {
		for (i = 1; i <= 12; i++) {
			if (i > dateToday.getMonth() + 1) {
				$("." + monthArray[i - 1]).addClass("noClick");
				$("." + monthArray[i - 1]).addClass("btn-custom");
				$("." + monthArray[i - 1]).removeClass("btn-custom-enable");
			} else {
				$("." + monthArray[i - 1]).removeClass("noClick");
				$("." + monthArray[i - 1]).removeClass("btn-custom");
				$("." + monthArray[i - 1]).addClass("btn-custom-enable");
			}
		}
	} else {
		for (i = 1; i <= 12; i++) {
			$("." + monthArray[i - 1]).removeClass("noClick");
			$("." + monthArray[i - 1]).removeClass("btn-custom");
			$("." + monthArray[i - 1]).addClass("btn-custom-enable");
		}
	}
}


$(function () {
	$(document).ready(function () {
		var todaysDate = new Date(); // Gets today's date
		// Max date attribute is in "YYYY-MM-DD".
		// Need to format today's date accordingly
		var year = todaysDate.getFullYear(); // YYYY
		var month = todaysDate.getMonth();  // MM
		var minDate = (year + "-" + (month + 1));
		//  +"-"+ display Results in "YYYY-MM" for today's date
		// Now to set the max date value for the calendar to be today's date
		$('#startDate').attr('min', minDate);
		var cdate = $('#month_from').html();
		$("." + cdate.split(' ')[0]).removeClass("btn-custom-enable");
		$("." + cdate.split(' ')[0]).addClass("btn-success");
		$("." + cdate.split(' ')[0]).css("font-weight", " bold");
		rowcheck()
	});
});
</script>

<?php echo $__env->make('fuel_movement.month_picker', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('common.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php echo $__env->make('common.web', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Server_Files\oceania\trunk\oceania\resources\views/fuel_movement/fuel_movement.blade.php ENDPATH**/ ?>