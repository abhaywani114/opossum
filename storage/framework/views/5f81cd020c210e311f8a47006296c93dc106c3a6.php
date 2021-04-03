<?php $__env->startSection('subheader'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<script type="text/javascript" src="<?php echo e(asset('js/qz-tray.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('js/opossum_qz.js')); ?>"></script>
<style>
.keydigit {
	font-size:20px;
	width: 40px !important;
	height: 40px !important;
	padding: 6px !important;
	text-align:center;
	color: black;
	margin-right:5px;
    background-color: #ffffff00 !important;
    color: #fff !important;
    border: 1px solid #fff;
}
.pre_setup_field {
	width:100%;
	height:45px;
	margin:auto;
	font-size:20px;
    background: transparent;
    border: 1px solid #fff;
    color: white!important;
    border-radius: 10px;
    text-align: left !important;
}
.pre_setup_field:focus{
    background: transparent;
    border: 1px solid #fff;
    color: white!important;
    border-radius: 10px;
    outline-width: 0;
}
.pre_setup_label {
	margin: 10px 0px;
}
.custom_activate_btn {
    border-radius: 10px;
	padding-left:0;
	padding-right:0;
    margin: auto;
    width: 70px;
    height: 70px;
	font-size:16px;
    border-color: black;
	background-image:linear-gradient(#b4dd9f,#0be020);
}
.login_field {
    width: 65%;
	height: 40px;
	font-size:20px;
	border-width:0;
    margin-left:auto;
    margin-right:auto;
    margin-bottom:5px;
    background: transparent;
    border: 1px solid #fff;
	color: white!important;
    border-radius: 10px;
	text-align: left !important;
}
.login_field:focus{
    background: transparent;
    border: 1px solid #fff;
    color: #fff !important;
    border-radius: 10px;
    outline-width: 0;
}
.oceania_login_btn {
    width: 65%;
    height: 45px;
	font-size:20px;
    margin-top:10px;
    margin-left:auto;
    margin-right:auto;
    border-radius:10px;
	border:1px solid white;
	color: white;
	background-color:transparent !important;
}
.custom_login_btn {
    width: 65%;
    height: 45px;
	border-width:0;
	font-size:20px;
    margin-top:10px;
    margin-left:auto;
    margin-right:auto;
    border-radius:5px;
	background-image:linear-gradient(#0ee022,#bcdda5);
}
.login_error {
	color: #fff;
}
</style>
<?php echo $__env->make("oceania_svr.common.header", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="modal fade show" id="userEditModal" tabindex="-1" role="dialog"
	style="padding-right:0 !important;display:block;" aria-labelledby="logoutModalLabel"
	aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document"
		style=" width: 100% !important; height: 100% !important; margin: 0;
		padding: 0;max-width:100%;max-hight:100%">

		<div class="modal-content modal-inside bg-black"
			style="height: auto; min-height: 100% !important;border-radius: 0;
			<?php if(!empty($isLocationActive)): ?>
				background: url('<?php echo e(asset('images/underwater_bubbling.gif')); ?>');
				background-repeat: no-repeat;
				background-size: cover;
			<?php endif; ?> ">
			<div class="modal-body text-center"
				style="vertical-align: middle !important ;margin-top: 4%;">
				<img style="width:180px;height:180px;object-fit:contain"
					src="<?php echo e(asset('images/small_logo.png')); ?>">
				<br>
				<p class="mb-0" style="margin-bottom:0;margin-top:20px;
					font-size:80px;font-weight:600;line-height:1.0">
				Oceania
				</p>
				<p class="" style="z-index:10;position:relative;top:50px;
					margin-top:-50px;font-size:35px;font-weight:800">
					Server 
				</p>
				<div class="row align-items-center">

				<?php if(!empty($isLocationActive) && ($isServerEnd) ): ?>
					<div class="col-md-4" style="margin:auto;">
					<br/>
					<div id="login_form"
						style="padding:20px auto;">
					<form autocomplete="off">
						<input autofocus
							class="text-center form-control login_field"
							id="email" name="email"
							autocomplete="off"
							type="text" placeholder="Email"/>

						<input autofocus
							class="text-center form-control login_field"
							id="password" name="password"
							type="password" placeholder="Password"/>

			
					</form>
						<button class="btn-primary btn-md
							oceania_login_btn" onclick="login_me()">
							<span style="position:relative;top:-1px">
							Log In
							</span>
						</button>
					<br/>
					<div style="font-size:20px;color:yellow;width: 100%;text-align: center;"
						class="login_error">
						<?php if(empty($verifyHardware)): ?>
							Invalid hardware configuration detected.<br/>Please contact administrator.
						<?php endif; ?>
					</div>

					</div>
				</div>


				<?php else: ?>
				<div class="mt-4 col-sm-12">
				<div style="display: flex; justify-content: center;">
					<input autofocus class="form-control keydigit" type="text" id="key_1" maxlength="1">
					<input disabled class="form-control keydigit" type="text" id="key_2" maxlength="1">
					<input disabled class="form-control keydigit" type="text" id="key_3" maxlength="1">
					<input disabled class="form-control keydigit" type="text" id="key_4" maxlength="1">&nbsp;&nbsp;

					<input disabled class="form-control keydigit" type="text" id="key_5" maxlength="1">
					<input disabled class="form-control keydigit" type="text" id="key_6" maxlength="1">
					<input disabled class="form-control keydigit" type="text" id="key_7" maxlength="1">
					<input disabled class="form-control keydigit" type="text" id="key_8" maxlength="1">&nbsp;&nbsp;

					<input disabled class="form-control keydigit" type="text" id="key_9" maxlength="1">
					<input disabled class="form-control keydigit" type="text" id="key_10" maxlength="1">
					<input disabled class="form-control keydigit" type="text" id="key_11" maxlength="1">
					<input disabled class="form-control keydigit" type="text" id="key_12" maxlength="1">&nbsp;&nbsp;

					<input disabled class="form-control keydigit" type="text" id="key_13" maxlength="1">
					<input disabled class="form-control keydigit" type="text" id="key_14" maxlength="1">
					<input disabled class="form-control keydigit" type="text" id="key_15" maxlength="1">
					<input disabled class="form-control keydigit" type="text" id="key_16" maxlength="1">
				</div>

				<div class="mt-3 row align-items-center"
					style="width: 55%;margin:auto;justify-content: center;">

					<p class="" style="z-index:10;position:relative;top:50px;
						margin-top:10px;font-size:20px;font-weight:800">
					</p>

				<?php if(empty($isLocationActive) && empty($isTerminalActive)): ?>
					<div class="col-5">
						<input autofocus
							class="text-center form-control pre_setup_field"
							id="merchant_id_field"
							type="text" placeholder="Franchisee Merchant ID"/>
					</div>
					<div class="col-5">
						<input autofocus
							class="text-center form-control pre_setup_field"
							id="location_id_field"
							type="text" placeholder="Franchise Location ID"/>
					</div>
				<?php elseif(!empty($isLocationActive) && empty($isTerminalActive)): ?>

					<div class="col-5">
						<input autofocus
							class="text-center form-control pre_setup_field"
							id="terminal_id_field"
							type="text" placeholder="Terminal ID"/>
					</div>
				<?php endif; ?>
					<div class="col-2">
						<button onclick="activateLicence()"
							class="btn-primary btn-md
							custom_activate_btn">Set Up
						</button>
					</div>
				</div>
				</div>
				<?php endif; ?>
                </div>

                <div style="position:absolute;bottom:20px;right:20px"
					class="row float-right">

                    <div class="col-md-12 float-right" style="justify-content:flex-end;display:flex">
					<!--
                    <span class=" float-right" style="padding-bottom:2px;
						font-size:25px;">OPOSsum
					</span>
					-->
                    </div>
                    <div class="col-md-12 float-right" style="justify-content:flex-end;display:flex">
					<?php if(!empty($isLocationActive)): ?>
                        <button onclick="window.open('<?php echo e(route("main.view.onehost")); ?>','_blank')"
							class="btn btn-sq-lg pl-0 pr-0" target="_blank"
							style="margin-right:22px;text-align:center;
							width:140px;height:70px;border:1px solid white;color:
							white;border-radius:10px">POS<br>OPOSsum
						</button>
					<?php endif; ?>
                    </div>
                </div>

				<div class="col-md-10 align-items-center m-auto">
					<div id="login-message"
						style="font-size:20px;color:yellow;width: 100%;text-align: center;">
					</div>
					<div style="font-size:20px;color:yellow;width: 100%;text-align: center;"
						class="pl-5 login_error">
					</div>
				</div>
			</div>
		</div>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script>
	$("#userEditModal").modal('show');
</script>
<?php if(!empty($isLocationActive) && ( $isServerEnd )): ?>
<script>
var keys = [];
var index = 0;



function login_me() {
	email 		= $("#email").val();
	password	= $("#password").val();
	$.ajax({
		url: "<?php echo e(route('uPLogin')); ?>",
		type: "POST",
		data: {
			email:email,
			password:password
		},
		'headers': {
			'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
		},
		success: function (response) {
			if (response.landing != undefined){
				window.location = response.landing;
            } else {
				$('#login-message').html(response.login_error);
				setInterval(function(){
					$('#login-message').html('');
				},6000);
			}
		},
		error: function (resp) {
			console.error('ERROR: uPLogin()');
			console.error(resp);
		}
	});
}

function scan_done() {
	var b = keys.join('');
    access_code = b.toString();
    b = "";
    keys.length = 0
    $.ajax({
		url: "/authorizeDriver",
		type: "POST",
		data: {access_code: access_code},
		'headers': {
			'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
		},
		success: function (response) {
			if (response != null && typeof response != 'undefined' && response.systemid){

			usersystemid = response.systemid;
			username = response.fullname;

			console.log(response);

			$('#login-message').html(usersystemid+' is successfully authenticated');

			/*
			for (i = 1; i <= <?php echo e(env('MAX_PUMPS')); ?>; i++) {
				pumpData['pump'+i] = {
					status:'Offline',
					amount:'0.00',
					dose:'0.00',
				};
				$("#volume-liter-"+i).sevenSeg({
					digits: 7,
					value: "0.00",
				});
				$("#volume-liter-"+i).sevenSeg("destroy");
			}

			$('#user-id').text(usersystemid);
			$('#user-name').text(username);
			$('#pump-main-block-'+selected_pump).hide();

			$('#pump-main-block-0').show();
			selected_pump = 0;

			$('#authorize-button').attr('class', '');
			$('#authorize-button').addClass('btn poa-authorize-disabled');
			$('.button-number-amount').addClass('poa-button-number-disabled');
			$('.button-number-amount').removeClass('poa-button-number');
			 */

			setTimeout(function(){
				$('#userEditModal').modal('hide');
				$('#container-blur').css('opacity',' 1');
				window.location.reload();
				}, 3000);
			}
		},
		error: function (e) {
			console.log('error', e);
		}
	});
}


window.addEventListener("keydown",function(e){
	if(e.keyCode != 17  && e.keyCode != 16 &&
		e.keyCode != 18 && e.keyCode != 20 ){
		if(e.keyCode != 13){
			keys[index++] = e.key;
		} else{
			scan_done();
		}
	}
}, false);

</script>
<?php else: ?>
	<?php echo $__env->make('landing.license', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('oceania_svr.landing.web', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Server_Files\oceania\trunk\oceania\resources\views/oceania_svr/landing/login.blade.php ENDPATH**/ ?>