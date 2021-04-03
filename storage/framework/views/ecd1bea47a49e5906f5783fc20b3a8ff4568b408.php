<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
	<head>
	    <meta charset="utf-8">
	    <meta name="viewport" http-equiv="Content-type" content="width=device-width, initial-scale=1">

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
	    <link href="<?php echo e(asset('css/jquery.dataTables.css')); ?>" rel="stylesheet">

		<script src="<?php echo e(asset('js/jquery-3.4.1.min.js')); ?>"></script>
		<script src="<?php echo e(asset('js/bootstrap.js')); ?>"></script>
		<script src="<?php echo e(asset('js/jquery.dataTables.js')); ?>"></script>
        <script src="<?php echo e(asset('js/jquery-ui.min.js')); ?>"></script>
        <script src="<?php echo e(asset('js/knockout-2.2.1.js')); ?>"></script>
        <script src="<?php echo e(asset('js/sevenSeg.js')); ?>"></script>
	</head>

	<style>
    body {
        background-color: black;
    }
	</style>

	<?php echo $__env->yieldPushContent('styles'); ?>

	<body >
	<?php echo $__env->yieldContent('content'); ?>
	<?php echo $__env->yieldContent('script'); ?>
	</body>
</html>
<?php /**PATH D:\Server_Files\oceania\trunk\oceania\resources\views/oceania_svr/landing/web.blade.php ENDPATH**/ ?>