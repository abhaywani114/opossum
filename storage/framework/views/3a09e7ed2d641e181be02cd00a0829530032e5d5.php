<style>
    .btn-custom{
        background-color: #e2e2e2 !important;
        color: #676565 !important;
    }
    .btn-custom:hover{
        background-color: green !important;
        color: #fff !important;
    }
    .next-year{
        color: #727272 !important;
    }
    .prev-year{
        color: #727272 !important;
    }
    .noClick {
        pointer-events: none;
    }
</style>
<div class="modal fade" id="showDateModalFrom" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document"
		style="min-width: 700px;">

        <div class="modal-content bg-purplelobster">
            <div class="modal-header align-items-center">
            <h3 class="mb-0 modal-title">Month</h3>

			<div class="row" style="margin-right: 15px;align-items: center;">
                <div class="col-md-2">
                    <i class="prev-year fa fa-chevron-left fa-3x"
					  style="cursor:pointer;"></i>
                </div>
                <div class="col-md-8" style="transform: translateX(9%);">
                    <div class="year text-center text-white" id="main_year">
						<h3 class="mb-0" id="year_cal">2020</h3>
					</div>
                </div>
                <div class="col-md-2">
                    <i style="cursor:pointer"
						class="next-year fa fa-chevron-right fa-3x ">
					</i>
                </div>
            </div>
            </div>
            <div style="padding:20px !important">

            <div class="row" style="justify-content:center">

				<ul name="discountItemtLevel" id="discountItemtLevelId"
					style="margin-bottom:0;padding-left:40px;padding-right:35px"
					class="discountItemtLevel">
                    <?php
                    $months=\App\Classes\Helper::monthList();

                    ?>
			<?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$mm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<li class="btn  btn-log btn-custom discountbutton yearBtn <?php echo e($mm['sort_name']); ?>"
					style= "padding-left:0;padding-right:0;padding-top:20px;"
					onclick="select_month('<?php echo e($mm["sort_name"]); ?>')" value="<?php echo e($mm['sort_name']); ?>">
					<span><?php echo e($mm['sort_name']); ?></span>
				</li>

			<?php if($key== 5): ?>
				</ul>
            </div>

            <div class="row" style="justify-content:center">
				<ul name="discountItemtLevel" id="discountItemtLevelId"
					style="padding-left:40px;padding-right:35px"
					class="discountItemtLevel">
			<?php endif; ?>

			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</ul>
            </div>
            <input type="hidden" id='product_id' value=""/>
        </div>
        </div>
    </div>
</div>
<?php /**PATH D:\Server_Files\oceania\trunk\oceania\resources\views/fuel_movement/month_picker.blade.php ENDPATH**/ ?>