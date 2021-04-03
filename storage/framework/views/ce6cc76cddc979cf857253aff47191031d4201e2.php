<div class="container-fluid">
	<div id="pa-view">
		<table id="receipt-table" class="table table-bordered">
			<thead class="thead-dark">
			<tr>
				<th style="text-align:center;width:30px;" >No</th>
				<th style="text-align:center;width:120px">Date</th>
				<th style="text-align:center;width:auto">Receipt&nbsp;ID</th>
				<th style="text-align:center;width:60px;">Total</th>
				<th style="text-align:center;width:60px;
					background-color:#ff7e30;border-color:ff7e30">Fuel</th>
				<th style="text-align:center;width:60px;
					background-color:#ff7e30;border-color:#ff7e30">Filled</th>
				<th style="text-align:center;width:60px;
					background-color:#ff7e30;border-color:#ff7e30">Refund</th>
				<th style="text-align:center;width:30px;
					background-color:#ff7e30;border-color:#ff7e30"></th>
			</tr>
			</thead>
			<tbody>
                <?php $__currentLoopData = $receipt; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<tr>
				<td style="text-align: center;width:30px;" >
					<?php echo e($loop->index + 1); ?>

				</td>
				<td style="text-align: center;width: 100px">
					<?php echo e(date('dMy H:i:s', strtotime($row->created_at??''))); ?>

				</td>
				<td style="text-align: center;width: auto">
					<a href="#" style="text-decoration: none;"
						onclick="showReceipt(<?php echo e($row->id); ?>)"><?php echo e($row->systemid); ?>

					</a>
				</td>
				<td class="text-center" style="width:50px;<?php if($row->status=='voided'): ?>background-color:red;color:white;font-weight:bold;
							<?php elseif($row->is_refunded): ?> background-color:orange;color:white;font-weight:bold; <?php endif; ?>">
					<?php echo e(number_format(((($row->cash_received/100-$row->cash_change/100+((5 * round(($row->cash_received-$row->cash_change) / 5))-($row->cash_received-$row->cash_change))/100))??"2"),2)); ?>

				</td>

				<td class="text-center">
					<?php echo e(number_format($row->fuel,2)); ?>

				</td>

				<td class="text-center">
					<?php echo e(number_format($row->filled,2)); ?>

				</td>
				<td class="text-center">
					<?php if($row->status=='voided'): ?>
						0.00
					<?php else: ?>
					<?php echo e(number_format($row->refund,2)); ?>

					<?php endif; ?>
				</td>
				<td class="text-center"
					style="padding-top:2px;padding-bottom:2px;width:30px">
					<img src="<?php echo e(asset('/images/bluecrab_50x50.png')); ?>"
						id="crab_<?php echo e($row->id); ?>"
						<?php if(!$row->is_refunded && ((float) number_format($row->refund,2) > 0 ) && $row->status !='voided'): ?> 
							onclick="generate_refund('<?php echo e($row->id); ?>',
							'<?php echo e(number_format($row->refund,2)); ?>',this)"
							style="cursor:pointer;width:25px;height:25px"
						<?php else: ?> 
							style="cursor:text;width:25px;height:25px;
							filter: grayscale(1) brightness(1.5);"
							disabled="disabled" <?php endif; ?>/>
				</td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</tbody>
		</table>
	</div>
</div>

<?php /**PATH D:\Server_Files\oceania\trunk\oceania\resources\views/local_cabinet/receipt_table.blade.php ENDPATH**/ ?>