<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
	<head>
	    <meta charset="utf-8">
	    <meta name="viewport" http-equiv="Content-type"
			content="width=device-width, initial-scale=1">

	    <!-- CSRF Token -->
	    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

	    <title><?php echo e(config('app.name', 'Oceania')); ?></title>
	    <link rel="icon" type="image/png"
			href="<?php echo e(asset('images/small_logo.png')); ?>">

	    <!-- Styles -->
	    <link href="<?php echo e(asset('css/all.css')); ?>" rel="stylesheet">
	    <link href="<?php echo e(asset('css/bootstrap.css')); ?>" rel="stylesheet">
	    <link href="<?php echo e(asset('css/bootstraptabs.css')); ?>" rel="stylesheet">
	    <link href="<?php echo e(asset('css/styles.css')); ?>" rel="stylesheet">
	    <link href="<?php echo e(asset('css/all.min.css')); ?>" rel="stylesheet">
	    <link href="<?php echo e(asset('css/ionicons.min.css')); ?>" rel="stylesheet">
		<link href="<?php echo e(asset('css/jquery.timepicker.css')); ?>" rel="stylesheet">
	    <link href="<?php echo e(asset('css/jquery.dataTables.css')); ?>" rel="stylesheet">
		<script src="<?php echo e(asset('js/jquery-3.4.1.min.js')); ?>"></script>
		<script src="<?php echo e(asset('js/bootstrap.js')); ?>"></script>
		<script src="<?php echo e(asset('js/jquery.timepicker.js')); ?>"></script>
		<script src="<?php echo e(asset('js/jquery.dataTables.js')); ?>"></script>
        <script src="<?php echo e(asset('js/jquery-ui.min.js')); ?>"></script>
        <script src="<?php echo e(asset('js/knockout-2.2.1.js')); ?>"></script>
        <script src="<?php echo e(asset('js/sevenSeg.js')); ?>"></script>

		<?php echo $__env->yieldContent('styles'); ?>
	</head>

<body>
	<?php echo $__env->yieldContent('content'); ?>
    <div class="modal fade"  id="modalMessage"  tabindex="-1" role="dialog"
		aria-labelledby="staffNameLabel" aria-hidden="true"
		style="text-align: center;">
		<div class="modal-dialog modal-dialog-centered  mw-75 w-50"
			role="document" style="display: inline-flex;">
			<div class="modal-content modal-inside bg-purplelobster"
				style="width: 100%;  background-color: <?php echo e(@$color); ?> !important" >
				<div class="modal-header" style="border:0">&nbsp;</div>
				<div class="modal-body text-center">
					<h5 class="modal-title text-white"
						id="statusModalLabelMsg">
					</h5>
				</div>
				<div class="modal-footer" style="border:0">&nbsp;</div>
			</div>
		</div>
	</div>
	<!--end of search Document--><!-- Modal Logout-->

<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog"
	style="padding-right:0 !important" aria-labelledby="logoutModalLabel"
	aria-hidden="true">
<div class="modal-dialog modal-dialog-centered  mw-75 w-50" role="document">
    <div class="modal-content modal-inside bg-purplelobster">
        <div style="border:0" class="modal-header"></div>
        <div class="modal-body text-center">
            <h5 class="modal-title text-white" id="logoutModalLabel">
            Do you really want to logout?</h5>
        </div>

        <div class="modal-footer"
			style="border-top:0 none; padding-left: 0px; padding-right: 0px;">
            <div class="row"
                style="width: 100%;
				padding-top:15px !important; padding-bottom:15px !important;
				padding-left: 0px; padding-right: 0px;">
                <div class="col col-m-12 text-center">
                    <a class="btn btn-primary" href="<?php echo e(route('logout')); ?>"
                        style="width:100px"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Yes
                    </a>
                    <button type="button" class="btn btn-danger"
                        data-dismiss="modal" style="width:100px">No
                    </button>
                </div>
            </div>

            <form id="logout-form" action="<?php echo e(route('logout')); ?>"
                method="POST" style="display: none;">
                <?php echo csrf_field(); ?>
            </form>
        </div>
    </div>
</div>
</div>
	<?php echo $__env->yieldContent('script'); ?>

<script>
function messageModal(msg) {
	$('#modalMessage').modal('show');
	$('#statusModalLabelMsg').html(msg);
	setTimeout(function(){
		$('#modalMessage').modal('hide');
	}, 2500);
}


function loadView(route) {
	$.ajax({
		url: route,
		type: 'GET',
		dataType: "html",
		success: function (response) {
			$("#landing-view").html(response);
		},
		error: function (e) {
			console.log('error', e);
		}
	});
}

</script>
</body>
</html>
<?php /**PATH D:\Server_Files\oceania\trunk\oceania\resources\views/oceania_svr/common/web.blade.php ENDPATH**/ ?>