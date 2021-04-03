<div class="contain er-fluid">
	<div id="pa-view">
		<table id="receipt-table" class="table table-bordered">
			<thead class="thead-dark">
			<tr>
				<th style="text-align:center;width:5%;" >No</th>
				<th style="text-align:center;width:30%">Login</th>
				<th style="text-align:center;width:auto">Staff</th>

			</tr>
			</thead>
			<tbody style="background: white">
                <?php $__currentLoopData = $pshift; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<tr>
				<td style="text-align: center;width:5%;" >
					<?php echo e($loop->index + 1); ?>

				</td>
                
                <td style="text-align: center;width: 40%">
					<a href="#" style="text-decoration: none;"
						onclick="pssReceiptPopup('<?php echo e(date('dMy H:i:s', strtotime($row->login??''))); ?>', 
							'<?php echo e(empty($row->logout) ? '':date('dMy H:i:s', strtotime($row->logout))); ?>','<?php echo e($row->systemid); ?>')"
							><?php echo e(date('dMy H:i:s', strtotime($row->login??''))); ?>

                    </a>
                </td>

				<td style="text-align: center;width: auto">
					<?php echo e($row->systemid); ?>


				</td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</tbody>
		</table>
	</div>
</div>

<?php /**PATH D:\Server_Files\oceania\trunk\oceania\resources\views/local_cabinet/personal_shift_table.blade.php ENDPATH**/ ?>