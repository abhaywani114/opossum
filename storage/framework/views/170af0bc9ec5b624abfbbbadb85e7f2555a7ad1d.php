<nav class="navbar navbar-light bg-light p-0" style="">

    <div class="navbar-text ml-0 pl-3 align-items-center w-100 bg-server-header"
		style="color: white;display:flex">
        <img src="<?php echo e(asset('images/small_logo.png')); ?>" alt=""
			style="object-fit:contain;width: 20px; height: 20px;
			cursor: pointer;"
			srcset="" class="mr-1">

		<span onclick="location.href='<?php echo e(route('main.view')); ?>';"
			style="position:relative;top:1px;cursor: pointer;">
			<b>Oceania</b>
		</span>

        <span style="position:relative;top:2px;margin-left:50px">
			<b></b>
		</span>

		<span style="position:relative;top:2px;margin-left:auto;left:0;">
			Hardware Address:&nbsp;
			<?php echo e($verifyHardware->hw_addr ?? null); ?>

		</span>

		<span style="position:relative;top:0;margin-left:auto;
			<?php if( request()->route()->getName() != 'main.view'): ?> margin-right:60px; <?php else: ?> margin-right:15px; <?php endif; ?>">
			<b><?php echo e(Auth::User()->fullname ?? ''); ?></b>
		</span>

		<?php if( request()->route()->getName() != 'main.view'): ?>
		<button style="position:relative;right:10px;top:-2px"
			class="navbar-nav mb-0 mr-0
			poa-closetab-button-sm">
		<i onclick="window.close()"
			style="font-size:25px"
			class="closetab fa fa-times-thin">
		</i>
		</button>
		<?php endif; ?>
	</div>
</nav>
<?php /**PATH D:\Server_Files\oceania\trunk\oceania\resources\views/oceania_svr/common/header.blade.php ENDPATH**/ ?>