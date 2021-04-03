<?php $__env->startSection('styles'); ?>
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

#eodSummaryListModal-table_paginate, #eodSummaryListModal-table_previous,
#eodSummaryListModal-table_next, #eodSummaryListModal-table_length,
#eodSummaryListModal-table_filter, #eodSummaryListModal-table_info {
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
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('common.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('common.menubuttons', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div id="landing-view">
	<!--div id="landing-content" style="width: 100%"-->
	<div class="container-fluid">
		<div class="clearfix"></div>
		<div class="row py-2 align-items-center" style="display:flex;height:75px">
			<div class="col" style="width:70%">
				<h2 style="margin-bottom: 0;">Sales Report</h2>
			</div>

			<div class="col-md-2 text-right">
				<h5 style="margin-bottom:0;"></h5>
			</div>
		</div>


		<div>
		<form action="<?php echo e(route('sales.report.print.pdf')); ?>" method="POST">
			<h5 class="mb-0">Convenience Store Sales</h5>
			<hr class="mt-0 mb-2" style="border-color:#c0c0c0">
			<div style="right:200px;display:inline;padding-left:0;margin-bottom:20px">
				<input class="to_date form-control btnremove"
				style="display:inline;margin-top:10px;padding-top:0px !important;
				position:relative;top:2px;
				padding-bottom: 0px; width:110px;padding-right:0;padding-left:0px;
				text-align: center;"
				value="End Date"
				onclick="show_dialog4()"
				id="start_date" name="start_date" placeholder="Select" />
			</div>
			<?php echo e(csrf_field()); ?>

			To
			<div style="right:200px;display:inline;padding-left:0;
				margin-bottom:20px">
				<input class="to_date form-control btnremove"
				style="display:inline;margin-top:10px;padding-top:0px !important;
				position:relative;top:2px;
				padding-bottom: 0px; width:110px;padding-right:0;
				padding-left:0px; text-align: center;"
				value="End Date"
				onclick="show_dialog5()"
				id="end_date" name="end_date" placeholder="Select" />
			</div>

			<div style="right:200px;display:inline;
				padding-left:40px;margin-bottom:20px">
				<button class="btn btn-success bg-download"
					style="height:70px;width:70px;border-radius:10px;
					outline:none;font-size: 14px">PDF
				</button>
			</div>
			</div>
		</form>
		</div>


		<div style="padding-left:15px">
		<form action="<?php echo e(route('sales.fuel.report.print.pdf')); ?>" method="POST">
			<h5 class="mb-0">Fuel Sales</h5>
			<hr class="mt-0 mb-2" style="border-color:#c0c0c0">
			<div style="right:200px;display:inline;padding-left:0;margin-bottom:20px">
				<input class="to_date form-control btnremove"
				style="display:inline;margin-top:10px;padding-top:0px !important;
				position:relative;top:2px;
				padding-bottom: 0px; width:110px;padding-right:0;padding-left:0px;
				text-align: center;"
				value="Start Date"
				onclick="show_dialog7()"
				id="fuel_start_date" name="fuel_start_date" placeholder="Select" />
			</div>
			<?php echo e(csrf_field()); ?>

			To
			<div style="right:200px;display:inline;padding-left:0;
				margin-bottom:20px">
				<input class="to_date form-control btnremove"
				style="display:inline;margin-top:10px;padding-top:0px !important;
				position:relative;top:2px;
				padding-bottom: 0px; width:110px;padding-right:0;
				padding-left:0px; text-align: center;"
				value="End Date"
				onclick="show_dialog8()"
				id="fuel_end_date" name="fuel_end_date" placeholder="Select" />
			</div>

			<div style="right:200px;display:inline;
				padding-left:40px;margin-bottom:20px">
				<button class="btn btn-success bg-download"
					style="height:70px;width:70px;border-radius:10px;
					outline:none;font-size: 14px">PDF
				</button>
			</div>
			</div>
		</form>
		<?php echo $__env->make('sales_report.fuel_usage_excel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
		</div>

		<div style="padding-left:15px">
		<form action="" method="">
			<h5 class="mb-0">Electric Vehicle Charger Sales</h5>
			<hr class="mt-0 mb-2" style="border-color:#c0c0c0">
			<div style="right:200px;display:inline;padding-left:0;margin-bottom:20px">
				<input class="to_date form-control btnremove"
				style="display:inline;margin-top:10px;padding-top:0px !important;
				position:relative;top:2px;
				padding-bottom: 0px; width:110px;padding-right:0;padding-left:0px;
				text-align: center;"
				value="End Date"
				onclick="show_dialog4()"
				id="start_date" name="start_date" placeholder="Select" />
			</div>
			<?php echo e(csrf_field()); ?>

			To
			<div style="right:200px;display:inline;padding-left:0;
				margin-bottom:20px">
				<input class="to_date form-control btnremove"
				style="display:inline;margin-top:10px;padding-top:0px !important;
				position:relative;top:2px;
				padding-bottom: 0px; width:110px;padding-right:0;
				padding-left:0px; text-align: center;"
				value="End Date"
				onclick="show_dialog5()"
				id="end_date" name="end_date" placeholder="Select" />
			</div>

			<div style="right:200px;display:inline;
				padding-left:40px;margin-bottom:20px">
				<button class="btn btn-success bg-download"
					style="height:70px;width:70px;border-radius:10px;
					outline:none;font-size: 14px">PDF
				</button>
			</div>
			</div>
		</form>
		</div>

		<div style="padding-left:30px">
		<form action="" method="">
			<h5 class="mb-0">Hydrogen Sales</h5>
			<hr class="mt-0 mb-2" style="border-color:#c0c0c0">
			<div style="right:200px;display:inline;padding-left:0;margin-bottom:20px">
				<input class="to_date form-control btnremove"
				style="display:inline;margin-top:10px;padding-top:0px !important;
				position:relative;top:2px;
				padding-bottom: 0px; width:110px;padding-right:0;padding-left:0px;
				text-align: center;"
				value="End Date"
				onclick="show_dialog4()"
				id="start_date" name="start_date" placeholder="Select" />
			</div>
			<?php echo e(csrf_field()); ?>

			To
			<div style="right:200px;display:inline;padding-left:0;
				margin-bottom:20px">
				<input class="to_date form-control btnremove"
				style="display:inline;margin-top:10px;padding-top:0px !important;
				position:relative;top:2px;
				padding-bottom: 0px; width:110px;padding-right:0;
				padding-left:0px; text-align: center;"
				value="End Date"
				onclick="show_dialog5()"
				id="end_date" name="end_date" placeholder="Select" />
			</div>

			<div style="right:200px;display:inline;
				padding-left:40px;margin-bottom:20px">
				<button class="btn btn-success bg-download"
					style="height:70px;width:70px;border-radius:10px;
					outline:none;font-size: 14px">PDF
				</button>
			</div>
			</div>
		</form>
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

<script src="<?php echo e(asset('/js/osmanli_calendar.js')); ?>?version=<?php echo e(date("hmis")); ?>"></script>

<script>
store_date = dateToYMDEmpty(new Date());
$("#end_date").val(store_date);
$("#fuel_end_date").val(store_date);
$("#start_date").val(store_date);
$("#fuel_start_date").val(store_date);
var terminal_date;

$(document).ready(function(){
	$.ajax({
		url: "<?php echo e(route('sales.terminal.date')); ?>",
		type: "get",
		success(response){
			console.log(response);
			terminal_date = new Date(response);
			console.log("Terminal: "+terminal_date);
		}
	})
});


function sum(input) {
    var total = 0;
    for (var i = 0; i < input.length; i++) {
    	total += Number(input[i]);
	}
    return total;
}


function formatNumber(num) {
	return num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
}


function reinit(destory) {
	if (destory == true) {
		$('#product_sales_pdt_table').DataTable().clear().destroy();
	} else {
		dt = $('#product_sales_pdt_table').DataTable({
			order:[]
		});
	}
}


var start_date_dialog = osmanli_calendar;
var completion_date_dialog = osmanli_calendar;


function show_dialog4(e) {
    var start_date_dialog = osmanli_calendar;
    jQuery('#showDateModalFrom').modal('show');

    date = new Date();
    start_date_dialog.CURRENT_DATE = new Date();
    start_date_dialog.SELECT_DATE = new Date();
    start_date_dialog.MIN_DATE = terminal_date;
    start_date_dialog.MAX_DATE = date;
    start_date_dialog.DAYS_DISABLE_MIN = "ON";
    start_date_dialog.DAYS_DISABLE_MAX = "ON";

    console.log(terminal_date);


    //start_date_dialog.MIN_DATE = new Date();
    $('.next-month').off();
    $('.prev-month').off();

    $('.prev-month').click(function () {start_date_dialog.pre_month()});
    $('.next-month').click(function () {start_date_dialog.next_month()});

    start_date_dialog.ON_SELECT_FUNC = function(){
        var date = osmanli_calendar.SELECT_DATE;
        var start_date = dateToYMDEmpty(date);
        $("#start_date").val(start_date);
        jQuery('#showDateModalFrom').modal('hide');
	}

    start_date_dialog.init()
}
function show_dialog7(e) {
    var start_date_dialog = osmanli_calendar;
    jQuery('#showDateModalFrom').modal('show');

    date = new Date();
    start_date_dialog.CURRENT_DATE = new Date();
    start_date_dialog.SELECT_DATE = new Date();
    start_date_dialog.MIN_DATE = terminal_date;
    start_date_dialog.MAX_DATE = date;
    start_date_dialog.DAYS_DISABLE_MIN = "ON";
    start_date_dialog.DAYS_DISABLE_MAX = "ON";

    console.log(terminal_date);


    //start_date_dialog.MIN_DATE = new Date();
    $('.next-month').off();
    $('.prev-month').off();

    $('.prev-month').click(function () {start_date_dialog.pre_month()});
    $('.next-month').click(function () {start_date_dialog.next_month()});

    start_date_dialog.ON_SELECT_FUNC = function(){
        var date = osmanli_calendar.SELECT_DATE;
        var start_date = dateToYMDEmpty(date);
        $("#fuel_start_date").val(start_date);
        jQuery('#showDateModalFrom').modal('hide');
	}

    start_date_dialog.init()
}
function show_dialog8(e) {
    var start_date_dialog = osmanli_calendar;
    jQuery('#showDateModalFrom').modal('show');

    date = new Date();
    start_date_dialog.CURRENT_DATE = new Date();
    start_date_dialog.SELECT_DATE = new Date();
    start_date_dialog.MIN_DATE = terminal_date;
    start_date_dialog.MAX_DATE = date;
    start_date_dialog.DAYS_DISABLE_MIN = "ON";
    start_date_dialog.DAYS_DISABLE_MAX = "ON";

    console.log(terminal_date);


    //start_date_dialog.MIN_DATE = new Date();
    $('.next-month').off();
    $('.prev-month').off();

    $('.prev-month').click(function () {start_date_dialog.pre_month()});
    $('.next-month').click(function () {start_date_dialog.next_month()});

    start_date_dialog.ON_SELECT_FUNC = function(){
        var date = osmanli_calendar.SELECT_DATE;
        var start_date = dateToYMDEmpty(date);
        $("#fuel_end_date").val(start_date);
        jQuery('#showDateModalFrom').modal('hide');
	}

    start_date_dialog.init()
}


function dateToYMDEmpty(date) {
	var strArray=['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
	var d = date.getDate();
	var m = strArray[date.getMonth()];
	var y = date.getFullYear().toString().substr(-2);
	var currentHours = date.getHours();
	return '' + (d <= 9 ? '0' + d : d) + '' + m + '' + y + ' ';
}

var end_date_dialog = osmanli_calendar;


function show_dialog5() {
    jQuery('#showDateModalFrom').modal('show');

    var end_date_dialog = osmanli_calendar;
    jQuery('#showDateModalFrom').modal('show');

    date = new Date();
    end_date_dialog.CURRENT_DATE = new Date();
    end_date_dialog.SELECT_DATE = new Date();
    end_date_dialog.MIN_DATE = terminal_date;
    end_date_dialog.MAX_DATE = date;
    end_date_dialog.DAYS_DISABLE_MIN = "ON";
    end_date_dialog.DAYS_DISABLE_MAX = "ON";


    //end_date_dialog.MIN_DATE = new Date();
    $('.next-month').off();
    $('.prev-month').off();

    $('.prev-month').click(function () {end_date_dialog.pre_month()});
    $('.next-month').click(function () {end_date_dialog.next_month()});

    end_date_dialog.ON_SELECT_FUNC = function(){
        var date = osmanli_calendar.SELECT_DATE;
        var start_date = dateToYMDEmpty(date);
        $("#end_date").val(start_date);
        jQuery('#showDateModalFrom').modal('hide');
    	}

    end_date_dialog.init()
}


function onDateSelect_to(selectedDate) {
	if (selectedDate == null) {
		return false;
	}

	const todaysDate = new Date();
	var selectedFinalDate = (selectedDate.getDate() < 10 ? '0' : '') + selectedDate.getDate();
	var selectedFullYear = selectedDate.getFullYear().toString();
	selectedFullYear = selectedFullYear.match(/\d{2}$/);
	$('#date_to').val(selectedFinalDate + selectedDate.toLocaleString('en-us',
	{month: 'short'}) + selectedFullYear);
	jQuery('#showDateModalFrom').modal('hide');
	date_filter();
}
</script>


<div class="clearfix"></div>
<br><br>

<div class="modal fade" id="showDateModalFrom" tabindex="-1"
  role="dialog" aria-labelledby="staffNameLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered  mw-75 w-50" role="document">
    <div class="modal-content modal-inside bg-purplelobster">
      <div class="modal-body text-center" style="min-height: 485px;max-height:485px">
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
    <form id="status-form" action="<?php echo e(route('logout')); ?>"
      method="POST" style="display: none;">
      <?php echo csrf_field(); ?>
    </form>
  </div>

<style type="text/css">
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
	border-bottom: none;
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

.date_table1 > tbody > tr > th {
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
	color: #008000 !important;
	font-weight: bold !important;
}

.selected_date1 {
	color: #008000 !important;
	font-weight: bold !important;
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

.bg-purplelobster{
	color:white;
	border-color:rgba(0,0,255,0.5);
	background-color:rgba(0,0,255,0.5)
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
.selected-button {
	background-color: green;;
	color: #fff;
}

.selected-button:hover {
	color: #fff !important;
}

.un-selected-button {
	background-color: #007bff;
	color: #fff;
}

.un-selected-button:hover {
	background: green;;
	color: white;
}

.disabled {
	color: gray!important;
   cursor: not-allowed !important;
}
.active {
	color:darkgreen;
	font-weight:700;
}
</style>

<script type="text/javascript">
    $(function() {
        $(document).ready(function () {
            var todaysDate = new Date(); // Gets today's date
            // Max date attribute is in "YYYY-MM-DD".
            // Need to format today's date accordingly
            var year = todaysDate.getFullYear(); // YYYY
            var month = ("01");  // MM
            var day = ("01");           // DD
            var minDate = (year +"-"+ month );
            //  +"-"+ display Results in "YYYY-MM" for today's date
            // Now to set the max date value for the calendar to be today's date
            $('#startDate').attr('min',minDate);
        });
    });

    <?php echo $__env->yieldContent('current_year'); ?>

    function show_dialog2(ogFuelId) {
        jQuery('#showDateModal').modal('show');
        $("#ogFuelPriceId").val(ogFuelId);
    }

    $('#showDateModal').on('hidden.bs.modal', function (e) {
        onDateSelect();
    });


    var CURRENT_DATE = new Date();
    var d = new Date();

    var content = 'January February March April May June July August September October November December'.split(' ');
    var contentMonth = '1 2 3 4 5 6 7 8 9 10 11 12'.split(' ');
    var weekDayName = 'SUN MON TUES WED THURS FRI'.split(' ');
    var daysOfMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

    // Returns the day of week which month starts (eg 0 for Sunday, 1 for Monday, etc.)
    function getCalendarStart(dayOfWeek, currentDate) {
        var date = currentDate - 1;
        var startOffset = (date % 7) - dayOfWeek;
        if (startOffset > 0) {
            startOffset -= 7;
        }
        return Math.abs(startOffset);
    }


    // Render Calendar
    function renderCalendar(startDay, totalDays, currentDate) {
        var currentRow = 1;
        var currentDay = startDay;
        var $table = $('table');
        var $week = getCalendarRow();
        var $day;
        var i = 1;

        for (; i <= totalDays; i++) {
            $day = $week.find('td').eq(currentDay);
            $day.text(i);
            if (i === currentDate) {
                $day.addClass('today');
            }

            // +1 next day until Saturday (6), then reset to Sunday (0)
            currentDay = ++currentDay % 7;

            // Generate new row when day is Saturday, but only if there are
            // additional days to render
            if (currentDay === 0 && (i + 1 <= totalDays)) {
                $week = getCalendarRow();
                currentRow++;
            }
        }
    }


    // Clear generated calendar
    var ACTIVE_DATE  = [];


    function clearCalendar() {
        if($('td.selected_date').length){
             ACTIVE_DATE  = [];
            ACTIVE_DATE.push($('td.selected_date').text());
            ACTIVE_DATE.push($('#currMonth').val());
            // console.log(ACTIVE_DATE);
        }
        var $trs = $('.picker tr').not(':eq(0)');
        $trs.remove();
        $('.month-year').empty();
    }

    // Generates table row used when rendering Calendar
    function getCalendarRow() {
        var $table = $('table.date_table');
        var $tr = $('<tr/>');
        for (var i = 0, len = 7; i < len; i++) {
            $tr.append($('<td/>'));
        }
        $table.append($tr);
        return $tr;
    }


    function myCalendar() {
        var month = d.getUTCMonth();
        var day = d.getUTCDay();
        var year = d.getUTCFullYear();
        var date = d.getUTCDate();
        var totalDaysOfMonth = daysOfMonth[month];
        var counter = 1;

        var $h3 = $('<h3>');

        $h3.html(content[month] + ' ' + year );
        $h3.appendTo('.month-year');
        var $div = $('<div>');
        $div.html('<input type="hidden" id="currMonth" name="currMonth" value="'+contentMonth[month]+'">');
        $div.appendTo('.month-year');

        var dateToHighlight = 0;

        // Determine if Month && Year are current for Date Highlight
        if (CURRENT_DATE.getUTCMonth() === month &&
            CURRENT_DATE.getUTCFullYear() === year) {

            dateToHighlight = date;
        }

        //Getting February Days Including The Leap Year
        if (month === 1) {
            if ((year % 100 !== 0) && (year % 4 === 0) || (year % 400 === 0)) {
                totalDaysOfMonth = 29;
            }
        }

        // Get Start Day
        renderCalendar(getCalendarStart(day, date), totalDaysOfMonth,
            dateToHighlight);
    };


    function pdfPrint(){
        var startDate = $('#start_date').val();
        var endDate = $('#end_date').val();

        data = {
			"startDate":startDate,
			"endDate":endDate
        };

        console.log(data);

        $.ajax({
			url: "<?php echo e(route('sales.report.print.pdf')); ?>",
            type: "post",
            'headers': {
			  'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
			},
            data:data,

		}).done(downloadFile);
    }


    function downloadFile(response) {
        var blob = new Blob([response], {type: 'application/pdf'})
        var url = URL.createObjectURL(blob);
        location.assign(url);
    }


    function navigationHandler(dir) {
        d.setUTCMonth(d.getUTCMonth() + dir);
        clearCalendar();
        myCalendar();
        shoot_event();
    }


    $(document).ready(function() {
        // Bind Events
        $('.prev-month').click(function() {
            if($(this).hasClass('disabled')) return false;
            navigationHandler(-1);
        });
        $('.next-month').click(function() {
            navigationHandler(1);
        });
        // Generate Calendar
        myCalendar();
        shoot_event();
    });

    var CURRENT_DATE = new Date();
    function shoot_event () {
        var year = d.getUTCFullYear();
        var currentMOnth = parseInt($('#currMonth').val()) -1;

        // if(currentMOnth ==  CURRENT_DATE.getMonth()  && year <=CURRENT_DATE.getFullYear()){
        //     $('.prev-month').addClass('disabled');
        //     $('.prev-month').css("cursor", "not-allowed");
        // } else{
        //     $('.prev-month').removeClass('disabled');
        //     $('.prev-month').css("cursor", "default");
        // }

        $('.date_table > tbody > tr > td').click(function(e) {
            console.log("Date clicked");

            var target = e.target;
            $('.date_table > tbody > tr > td').removeClass('selected_date');

            $(target).addClass('selected_date');

            var act = {"day" : $(this).text(), "month" : $('#currMonth').val() , "year" : year};
            sessionStorage.setItem('activeDate', JSON.stringify(act));

            let day = $(target).html();
            let month  = $('.month-year > h3').html();
            $('#startDate').val(day+' '+month);
            jQuery('#showDateModal').modal('hide');
        });

        $('.date_table tbody tr td').each(function () {
            if(ACTIVE_DATE.length){
                if($(this).text() == ACTIVE_DATE[0] &&
                    $('#currMonth').val() == ACTIVE_DATE[1] &&
                    year <=CURRENT_DATE.getFullYear()){

                    $(this).addClass('selected_date');
                }
            }
            var s = sessionStorage.getItem('activeDate');
            if(s != null || s != undefined){
                s = JSON.parse(s);
                //console.log(s);
                if(s.day == $(this).text() &&
                    s.month == $('#currMonth').val() && s.year == year) {
                    $(this).addClass('selected_date');
                }
            }

            // var currentMOnth = parseInt($('#currMonth').val()) -1;

            if ((parseInt($(this).text()) < CURRENT_DATE.getDate() &&
                currentMOnth <=  CURRENT_DATE.getMonth() &&
                year <=CURRENT_DATE.getFullYear())){

                // $(this).closest('td').addClass('disabled');
                // // $(this).closest('tr').css("pointer-events", "none");
                // $(this).closest('td').css("cursor", "not-allowed");
                // $(this).closest('td').unbind('click');
            }
        });
    }


    function overideFY() {
        $('#overide').val('true');
        //onDateSelect();
    }


    function reset_dialog() {
        $('#confirmation').val('false');
        $('#overide').val('false');
    }


    function onDateSelect() {
		const val = $('#startDate').val();
		const selectedDate = new Date(val);

		if (selectedDate == 'Invalid Date') {
			return false;
		}

		const todaysDate = new Date();
		$('#date_from').val(selectedDate.getDate()+selectedDate.toLocaleString('en-us',
			{ month: 'short' })+selectedDate.getFullYear().toString().substr(2,2));

		//  $('#from_year').html(selectedDate.getFullYear()+ ' from');

		if (todaysDate.getFullYear() > selectedDate.getFullYear()) {
			alert('Error: You can only select from this year!');
			$('#startDate').val('');
			return false;

		} else {
			console.log("date")
		}
	}

</script>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('common.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('common.web', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Server_Files\oceania\trunk\oceania\resources\views/sales_report/sales_report.blade.php ENDPATH**/ ?>