<?php $__env->startSection('styles'); ?>

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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('common.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('common.menubuttons', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


<!--
<div class="modal fade" id="remarks_qty" tabindex="-1"
	role="dialog" aria-labelledby="productcontModallabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered" role="document" >
	<div class="modal-content">

		<form  class="m-form  m-form--state m-form--label-align-right " >
			<div class="modal-body">
        		<div class="m-form__content">
                <input type="hidden" id="modal-item_id" name="item_id" value="">
          			<input type="hidden" id="modal-remark_type"
						name="remark_type" value="">
					<textarea id="modal-item_remark" placeholder="Remarks"
						name="remark" class="form-control m-input"
						rows="3"></textarea>
				</div>
			</div>
		</form>
	</div>
</div>
</div>
-->

<div class="row"
	style="padding-top:0;height:75px;margin-top:0px;margin-bottom:0">
	<div class="col-sm-5" style="align-self:center">
		<h2 class="mb-0 pt-0">
			Open Item: Product Ledger
		</h2>
	</div>

	<div class="col-sm-1" style="align-self:center">
	<?php if(!empty($product->thumbnail_1)): ?>
		<img src="/images/product/<?php echo e($product->systemid); ?>/thumb/<?php echo e($product->thumbnail_1); ?>"
			alt="Logo" width="70px" height="70px" alt="Logo"
			style="object-fit:contain;float:right;margin-left:0;margin-top:0;">
	<?php endif; ?>
	</div>

	<div class="col-sm-4" style="align-self:center;float:left;padding-left:0">
       <h4 style="margin-bottom:0px;padding-top: 0;line-height:1.5;">
	   <?php if($product->name??""): ?><?php echo e($product->name??""); ?>

	   <?php else: ?> Product Name <?php endif; ?></h4>
       <p style="font-size:18px;margin-bottom:0"><?php echo e($product->systemid??""); ?></p>
	</div>
	<div class="col-sm-3" style="float: right;">
	</div>
	</div>

	<div class="table-responsive mb-5" style="overflow-x: hidden;">
	<table class="table table-bordered" id="ogProductLeger" style="width: 100%;">
		<thead class="thead-dark">
			<tr>
				<th class="text-center" style="width:30px;text-align: center;">No</th>
				<th class="text-center" style="width:15%;text-align: center;">Document&nbsp;No</th>
				<th class="text-center" style="width: 11%">Type</th>
				<th class="text-center" style="width: 120px" nowrap>Last&nbsp;Update</th>
				<th class="text-center" style="width: auto;">Location</th>
				<th class="text-center" style="width: 80px">Qty</th>
			</tr>
		</thead>
		<tbody>

        <!--
        Types of ledger data (table names):
        1. opos_receiptproduct
        2. stockreportproduct
        3. opos_refund
         -->
		<?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		<tr>
			<td style="text-align: center;"><?php echo e($loop->index + 1); ?></td>

			<?php if($row->doc_type == "Cash Sales"): ?>
			<td style="text-align: center;
				<?php if($row->status=='voided'): ?>
					background-color:red;color:white;font-weight:bold;
				<?php endif; ?>">
				<a href="#" style="text-decoration: none;"
					onclick="showReceipt(<?php echo e($row->id); ?>)"><?php echo e($row->systemid); ?>

				</a>
			  </td>
			<?php else: ?>  
			<td style="text-align: center;">
				<a href="javascript:window.open('<?php echo e(route('stocking.stock_report', $row->systemid)); ?>')" 
				style="text-decoration: none;"><?php echo e($row->systemid); ?>

				</a>
			</td>
			<?php endif; ?>

			<td style="text-align: center;" nowrap>
				<?php if($row->doc_type == 'Stockin'): ?>
					Stock In
				<?php elseif($row->doc_type == 'Stockout'): ?>
					Stock Out
				<?php else: ?>
					<?php echo e(ucwords($row->doc_type)); ?>

				<?php endif; ?>
			</td>
			<td style="text-align: center;" nowrap>
				<?php if($row->status=='voided'): ?>
					<?php echo e(date('dMy H:i:s', strtotime($row->voided_at??''))); ?>

				<?php else: ?>
					<?php echo e(date('dMy H:i:s', strtotime($row->created_at??''))); ?>

				<?php endif; ?>
			</td>
			<td style="text-align: center;" nowrap>
				<?php echo e($location->name); ?>

			</td>
			
			<td style="text-align: center;">
				<?php if($row->status=='voided'): ?>
					0.00
				<?php else: ?>
					
					<?php echo e(($row->quantity)); ?>

				<?php endif; ?>
			</td>
		</tr>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

		</tbody>
	</table>
  </div>
  <div class="modal fade" id="eodModal_1" tabindex="-1" role="dialog"
  style="overflow:auto;" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered mw-75"
      style="width:370px" role="document">
      <div id="recipt-model-div" class="modal-content bg-white">
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

<input id='receiptid' type='hidden' />

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
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


	function void_receipt(id) {
		$('#receiptid').val(id);
		$('#voidreceiptmodal').modal('show');
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
					$("#qty_l").text('0.00');
                }
            });
        }


</script>



</div>
<?php echo $__env->make('common.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('common.web', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Server_Files\oceania\trunk\oceania\resources\views/openitem/openitem_productledger.blade.php ENDPATH**/ ?>