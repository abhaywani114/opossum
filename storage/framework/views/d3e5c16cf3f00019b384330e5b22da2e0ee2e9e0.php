<div class="container-fluid" style="padding-left: 15px!important;">
    <div class="clearfix"></div>

    <div style="">
		<div style="right:200px;display:inline;padding-left:0;margin-bottom:20px; opacity: 0">
			<input class="to_date form-control btnremove"
				   style="display:inline;margin-top:10px;padding-top:0px !important;
				position:relative;top:2px;
				padding-bottom: 0px; width:110px;padding-right:0;padding-left:0px;
				text-align: center;"
				   value="End Date"
				   onclick=""
				   id="start_date" name="start_date" placeholder="Select" />
            <?php echo e(csrf_field()); ?>

            To
		</div>

        <div style="right:200px;display:inline;padding-left:0;
				margin-bottom:20px">
            <input class="date_excel form-control btnremove"
                   style="display:inline;margin-top:10px;padding-top:0px !important;
				position:relative;top:2px;
				padding-bottom: 0px; width:110px;padding-right:0;
				padding-left:0px; text-align: center;"
                   value="Date"
                   onclick="show_dialog6()"
                   id="date_excel" name="" placeholder="Select"/>
        </div>

        <div style="right:200px;display:inline;
				padding-left:40px;margin-bottom:20px">
            <button class="btn btn-success"
                    style="height:70px;width:70px;border-radius:10px;
		outline:none;font-size: 14px" onclick="excelFile()">Excel
            </button>
        </div>


    </div>
</div>

<script>

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),

        },
        statusCode: {
            440: function () {
                window.location = '/'
            },
        },
        async: false
    });
    let store_date = dateToYMDEmpty(new Date());
    $("#date_excel").val(store_date);
    let date_excel = new Date();

    function show_dialog6() {
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

        $('.prev-month').click(function () {
            end_date_dialog.pre_month()
        });
        $('.next-month').click(function () {
            end_date_dialog.next_month()
        });

        end_date_dialog.ON_SELECT_FUNC = function () {
            var date = osmanli_calendar.SELECT_DATE;
            var start_date = dateToYMDEmpty(date);
            $("#date_excel").val(start_date);
            date_excel = date;
            jQuery('#showDateModalFrom').modal('hide');
        }

        end_date_dialog.init()
    }

    function convertDate(inputFormat) {
        function pad(s) {
            return (s < 10) ? '0' + s : s;
        }

        var d = new Date(inputFormat)
        return [d.getFullYear(), pad(d.getMonth() + 1), pad(d.getDate())].join('-')
    }


    function excelFile() {
        date_excel = convertDate(date_excel);
        console.log(date_excel);
        $.ajax({
            method: "post",
            url: "<?php echo e(route('sales.storeExcel')); ?>",
            data: {date_excel: date_excel},
        })
            .done((data) => {
                console.log("data", data);
                window.open("/" + data, '_blank')
            })
            .fail((data) => {
                console.log("data", data)
            });
    }

    function dateToYMDEmpty(date) {
        var strArray = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        var d = date.getDate();
        var m = strArray[date.getMonth()];
        var y = date.getFullYear().toString().substr(-2);
        var currentHours = date.getHours();
        return '' + (d <= 9 ? '0' + d : d) + '' + m + '' + y + ' ';
    }

</script>
<?php /**PATH D:\Server_Files\oceania\trunk\oceania\resources\views/sales_report/fuel_usage_excel.blade.php ENDPATH**/ ?>