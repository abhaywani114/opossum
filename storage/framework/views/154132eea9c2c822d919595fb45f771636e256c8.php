<?php $__env->startSection('styles'); ?>
<style>

body {
	background-color: black;
}

label {
	color: white;
	margin-top: 10px;
	margin-left: 10px;
	margin-bottom: 5px;
}

.green {
	color: green !important;
}
</style>

<?php $__env->stopSection(); ?>
<?php
$pagename="screen_d";
?>

<?php echo $__env->make('common.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->startSection('content'); ?>

    <div class="container-fluid" id="" style="margin-top:5px">
        <div class="row justify-content-center" style="height:94%">
            <div class="col-md-8 hidden-md-down"
                 style="display: inline-block;">

                <div>
                    <label>General</label><br>
                    <button class="btn btn-success btn-sq-lg screend-button
						bg-screend-purple"
						onclick="window.open('<?php echo e(route('settin.index')); ?>','_blank')"
						style="font-size: 14px">Setting
                    </button>
                    <button class="btn btn-success btn-sq-lg screend-button
						bg-screend-purple"
						onclick="currencyModal()"
						style="font-size: 14px">€£$¥
                    </button>
                    <button class="btn btn-success btn-sq-lg screend-button
						bg-screend-purple"
						onclick="rateModal()"
						style="font-size: 14px">Rate
                    </button>
                    <button class="btn btn-success btn-sq-lg screend-button
						bg-screend-purple"
						onclick="window.open('<?php echo e(route('vehicleMgmt')); ?>','_blank')"
						style="font-size: 14px">Vehicle
                    </button>

					<button class="btn btn-success btn-sq-lg screend-button
                        bg-virtualcabinet"
                        onclick="window.open('<?php echo e(route('local_cabinet')); ?>','_blank')"
                        style="margin-left:2px !important;outline:none;
						font-size: 14px">
						<span style="">Local<br>Cabinet</span>
                    </button>

                    <button class="btn btn-success btn-sq-lg screend-button
						bg-analytics"
						onclick="window.open('<?php echo e(route('sales.report')); ?>','_blank')"
						style="font-size: 14px">
						<span style="">
							Sales Report
						</span>
                    </button>

                    <input type="hidden" name="" id="comm_cabinet"
                           value="<?php echo e(route('comm_cabinet.commCabinet')); ?>">
                    <input type="hidden" name="" id="creditac"
                           value="<?php echo e(route('creditaccount.get')); ?>">
                    <input type="hidden" name="" id="carpark"
                           value="<?php echo e(route('carpark.car_park')); ?>">

                    <button class="btn btn-success btn-sq-lg
						bg-blue screend-button p-0"
						onclick="goTo('creditac')"
						style="font-size: 14px">Credit<br>A/C
                    </button>

                    <button class="btn btn-success btn-sq-lg
						bg-blue screend-button"
						onclick="goTo('carpark')"
						style="font-weight:1000;font-size: 34px">P
                    </button>
                </div>

                <div>
                    <label>Media Management</label><br>
                    <button class="btn btn-success
						screend-button-lg bg-screend-purple"
						onclick="window.open('<?php echo e(route('screen.e')); ?>')"
						style="font-size: 14px">Screen E
                    </button>
                    <button class="btn btn-success
						screend-button-lg bg-screend-purple"
						onclick="show_screen_e()"
						style="font-size: 14px">Screen E Image
                    </button>
                </div>

                <div>
                    <label>Fuel Management</label><br>
                    <button class="btn btn-success btn-sq-lg
						screend-button-lg bg-screend-night-black"
						onclick="window.open('<?php echo e(route('local.fuelprice')); ?>','_blank')"
						style="font-size: 14px">Local Fuel Price
                    </button>

                    <button class="btn btn-success btn-sq-lg
						screend-button-lg bg-screend-night-black"
						onclick="window.open('<?php echo e(route('fuel.movement')); ?>','_blank')"
						style="font-size: 14px">Fuel Movement
                    </button>

                    <!-- Don't move this!!
                    <button class="btn btn-success btn-sq-lg
                        screend-button-lg bg-screend-opt-mgmt"
                        onclick=""
                        style="font-size: 14px">OPT Management
                    </button>
                    -->
                </div>

                <div>
                    <label>Tank Management</label><br>
                    <button class="btn btn-success btn-sq-lg
						screend-button-lg bg-screend-night-black"
						onclick="window.open('<?php echo e(route('tank.mgmt')); ?>','_blank')"
						style="font-size: 14px">Tank Management
                    </button>
                    <input type="hidden" name="" id="tank_monitoring"
						value="<?php echo e(route('tank.tankmonitoring')); ?>">

                    <button class="btn btn-success btn-sq-lg
						screend-button-lg bg-screend-night-black"
						onclick="goTo('tank_monitoring')"
						style="font-size: 14px">Tank Monitoring
                    </button>
                </div>

                <div>
                    <label>Convenience Store</label><br>
                    <button class="btn btn-success btn-sq-lg
						screend-button-lg bg-screend-product"
						onclick="window.open('<?php echo e(route('local_price.landing')); ?>')"
						style="font-size: 14px">Location Price
                    </button>

                    <button class="btn btn-success btn-sq-lg
						screend-button bg-screend-product"
						onclick="window.open('<?php echo e(route('openitem.openitem')); ?>','_blank')"
						style="font-size: 14px">Open<br>Item
                    </button>

                    <!-- Don't move these 2!!!
                    <button class="btn btn-success btn-sq-lg
                        screend-button bg-stockin"
                        onclick=""
                        style="font-size: 14px">Stock<br>In
                    </button>
                    <button class="btn btn-success btn-sq-lg
                        screend-button bg-stockout"
                        onclick=""
                        style="font-size: 14px">Stock<br>Out
                    </button>
                    -->
                </div>

            </div>

            <div class="col-md-4 hidden-md-down" style="">
                <div class="pl-3"
					style="border-left: 2px solid  #a0a0a0; color:white;
					height:95%">
                    <div>
                        <div class="mt-2 mb-2 align-items-center">
                            <h3><b>OPOSsum</b>
                                <span style="font-size:10px">v2.1</span>
                                <span onclick="logout()" class="mt-1"
									style="font-size:20px;color:red;
									float:right;cursor:pointer">Log Out
								</span>
                            </h3>
                        </div>
                    </div>

                    <span>Log In</span>
                    <span class="float-right">
						<?php echo e(date('dMy H:i:s', strtotime($user->last_login??''))); ?>

					</span><br>
                    <span>Merchant</span>
                    <span class="float-right" id="merchant_name">
						<?php echo e($merchant->name??''); ?>

					</span><br>
                    <span>Merchant ID</span>
                    <span class="float-right" id="merchant_id">
						<?php echo e($merchant->systemid??''); ?>

					</span><br>
                    <span>Location</span>
                    <span class="float-right" id="location_name">
						<?php echo e($location->name??''); ?>

					</span><br>
                    <span>Location ID</span>
                    <span class="float-right" id="location_id">
						<?php echo e($location->systemid??''); ?>

					</span><br>
                    <span>Terminal ID</span>
                    <!-- WARNING: Hardcoded value -->
                    <span class="float-right" id="terminal_id">
						<?php echo e($terminal->systemid??''); ?>

					</span><br>
                    <span>Staff Name</span>
                    <span class="float-right" id="staff_name">
						<?php echo e($user->fullname??''); ?>

					</span><br>
                    <span>Staff ID</span>
                    <span class="float-right" id="staff_id">
						<?php echo e($user->systemid??''); ?>

					</span><br>
                    <span>Segment</span>
                    <span class="float-right" id="segment">
						Franchise
					</span><br>
                    <span>Operation Hour</span>
                    <span class="float-right" id="operation_hours">
						<?php echo e($location->start_work??''); ?> - <?php echo e($location->close_work??''); ?>

					</span><br>
                    <hr>
                    <div class="d-flex align-items-center"
                         style="justify-content:center">
                        <img src="<?php echo e(asset('images/vertical_doughnut_spin.gif')); ?>"
                             style="width:350px;height:350px;object-fit:contain"/>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div style="position: absolute; bottom: 30px; right:10px">
        <img style="object-fit:contain;width:200px !important"
             src="<?php echo e(asset('images/logo2.png')); ?>">
    </div>

    <div class="fixed-bottom">

        <nav class="navbar navbar-light bg-light p-0"
             style=" background-image:linear-gradient(rgb(38, 8, 94),rgb(86, 49, 210));">
            <span class="navbar-text m-0"></span>
        </nav>
    </div>


    <!--Currency Popup Start-->
    <div class="modal fade currencyModal" id="currencyModal"
         tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content bg-purplelobster">
                <div class="modal-header">
                    <h3 style="margin-bottom:0">Currency</h3>
                </div>
                <div class="modal-body modalTypeBody"
                     id="currencyModalTable">
                </div>
            </div>
        </div>
    </div>
    <!--End of Currency Popup -->
    <!--Rate Popup Start-->
    <div class="modal fade rateModal" id="rateModal"
		tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content bg-purplelobster">
                <div class="modal-header">
                    <h3 style="margin-bottom:0">Rate</h3>
                </div>
                <div class="modal-body" id="rateModal-body">
                </div>

                <script>
				var data = {
					ratetype: "",
					textype: ""
				};

				function select_rate_type(ratetype) {
					if (ratetype == "exclusive") {
						$("#exclusive_rate").css("cursor", "default");
						$("#inclusive_rate").css("color", "white");
						$("#exclusive_rate").css("color", "#34dabb");
						$("#inclusive_rate").css("cursor", "pointer");

					} else {
						$("#exclusive_rate").css("color", "white");
						$("#inclusive_rate").css("color", "#34dabb");
						$("#exclusive_rate").css("cursor", "pointer");
						$("#inclusive_rate").css("cursor", "default");
					}

					data.ratetype = ratetype;
				}


				function select_text_type(textype) {
					if (textype == "sst") {
						$("#idofSST").css("cursor", "default");
						$("#idofGST").css("color", "white");
						$("#idofSST").css("color", "#34dabb");
						$("#idofGST").css("cursor", "pointer");
						$("#idofVAT").css("cursor", "pointer");
						$("#idofVAT").css("color", "white");

					} else if (textype == "gst") {
						$("#idofGST").css("cursor", "default");
						$("#idofSST").css("color", "white");
						$("#idofGST").css("color", "#34dabb");
						$("#idofSST").css("cursor", "pointer");
						$("#idofVAT").css("cursor", "pointer");
						$("#idofVAT").css("color", "white");

					} else {
						$("#idofVAT").css("cursor", "default");
						$("#idofSST").css("color", "white");
						$("#idofVAT").css("color", "#34dabb");
						$("#idofSST").css("cursor", "pointer");
						$("#idofGST").css("cursor", "pointer");
						$("#idofGST").css("color", "white");
					}

					data.textype = textype;
				}
                </script>
            </div>
        </div>
    </div>


    <!--End of Rate Popup -->
    <!--EoD Popup Start-->
    <div class="modal fade" id="eodSummaryListModal"
         tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-75"
             style="width:370px" role="document">
            <div id="eodSummaryListModal-div"
                 class="modal-content bg-white" style="width:370px">
            </div>
        </div>
    </div>
    <!--End of EoD Popup -->

    <div class="modal fade" id="locationModal" tabindex="-1"
         role="dialog" aria-labelledby="staffNameLabel"
         aria-hidden="true" style="text-align: center;">

        <div class="modal-dialog modal-dialog-centered  mw-75 w-50"
             role="document"
             style="display: inline-flex;min-width: 43vw !important;">
            <div class="modal-content modal-inside bg-purplelobster"
                 style="width: 100%;min-height:380px;">
                <div class="modal-header" style="">
                    <h3 class="text-white"
                        id="statusModalLabel" style="margin-bottom:0">
                        Screen E Details
                    </h3>
                </div>
                <div class="modal-body text-center" style="padding-top: 20px;">
                    <form action="#" id="updateProspectFields" method="post"
                          enctype="multipart/form-data"
                          onsubmit="return false;" autocomplete="off">
                        <div class="row" style="padding-top: unset;">
                            <div class="col-md-6" style="padding-right: unset;
							padding-top: 0px;">
                                <div class="upload-area" id="uploadfile" style='border:
								unset;height:255px;background: grey;display:block;
								margin-left: 0.9%;overflow: hidden;width: 100%;'>

                                    <input type="file" name="file" id="file"
                                           style="display:none;" class="hidden"/>

                                    <?php if(!empty($location->e_right_panel_image_file)): ?>
                                        <div id="thumbnail_1" class="thmb" style="">

                                            <?php if(in_array(pathinfo($location->e_right_panel_image_file )['extension'], ['mp4', '3gp', 'avi', 'flv', 'mpeg'])): ?>
                                                <a href='/images/location/<?php echo e($location->id); ?>/<?php echo e($location->e_right_panel_image_file); ?>'
                                                   target="_blank">
                                                    <video style="background-color:white;object-fit:cover"
                                                           width="100%" height="255px" controls>
                                                        <source src="/images/location/<?php echo e($location->id); ?>/<?php echo e($location->e_right_panel_image_file); ?>"
                                                                type="video/mp4">
                                                    </video>
                                                </a>

                                            <?php else: ?>
                                                <a href='/images/location/<?php echo e($location->id); ?>/<?php echo e($location->e_right_panel_image_file); ?>'
                                                   target="_blank">
                                                    <img style="background-color:white;object-fit:contain"
                                                         src="/images/location/<?php echo e($location->id); ?>/<?php echo e($location->e_right_panel_image_file); ?>"
                                                         width="100%" height="255px">
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                        <img class="rcb"
                                             style="position: absolute;bottom: 12px;right: 63px;
										padding-bottom: 6px;padding-left: 5px;border: none;
										z-index: 10;width:35px;"
                                             src="<?php echo e(asset('images/redcrab_50x50.png')); ?>"
                                             onclick='del_picture("<?php echo e($location->id); ?>");return null'>

                                        <button class="btn btn-sm  btn-add"
                                                style="position: absolute;bottom: 10px;right:10px;
										z-index: 10;background: transparent;"
                                                id="uploadLogo">
                                            <i class="fa fa-camera green" id='logo_upload_cam'
                                               style="font-size: 40px;" onclick="return false;">
                                            </i>
                                        </button>

                                    <?php else: ?>
                                        <h1 id='upload_text' style="color:#fff;margin: 40px"></h1>
                                        <button class="btn btn-sm  btn-add"
                                                style="position: absolute;bottom: 10px;right: 10px;
										z-index: 10;background: transparent;" id="uploadLogo"
                                                onclick="return false;">
                                            <i class="fa fa-camera" id='logo_upload_cam'
                                               style="font-size: 40px;"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="color" name="e_table_header_color"
                                           id="e_table_header_color"
                                           class="form-control" placeholder="Header color"
                                           autocomplete="off"
                                           value="<?php echo e($location->e_table_header_color); ?>" required/>
                                </div>
                                <div class="form-group">
                                    <input type="color" name="e_right_panel_color"
                                           id="e_right_panel_color"
                                           class="form-control" placeholder="Right color"
                                           autocomplete="off"
                                           value="<?php echo e($location->e_right_panel_color); ?>" required/>
                                </div>
                                <div class="form-group">
                                    <input type="color" name="e_bottom_panel_color"
                                           id="e_bottom_panel_color"
                                           class="form-control" placeholder="Bottom color"
                                           autocomplete="off"
                                           value="<?php echo e($location->e_bottom_panel_color); ?>" required/>
                                </div>
                            </div>
                            <input type="hidden" name="location_id" value="<?php echo e($location->id); ?>"/>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div id="currencyModal-div">
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

    <script>
        $(document).ready(function () {
            /*
            $('html, body').css('height',' 95%');
            */

        });

        function currencyModal() {
            //$('.currencyModal').modal('show');
            $.ajax({
                url: "<?php echo e(route('screen.d.currency')); ?>",
                // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'get',
                success: function (response) {
                    // console.log(response);
                    $('#currencyModalTable').html(response);
                    $('#currencyModal').modal('show').html();
                    $('.currencyid').click(function () {
                        var a = $(this).attr('id');
                        Setcurrency(a);
                    });

                },
                error: function (e) {
                    $('#responseeod').html(e);
                    $("#msgModal").modal('show');
                }
            });
        }

        function rateModal() {
            //$('.currencyModal').modal('show');
            $.ajax({
                url: "<?php echo e(route('screen.d.rate')); ?>",
                type: 'get',
                success: function (response) {
                    // console.log(response);
                    $('#rateModal-body').html(response);
                    $('#rateModal').modal('show').html();


                },
                error: function (e) {
                    $('#responseeod').html(e);
                    $("#msgModal").modal('show');
                }
            });
            $('#rateModal').modal('show');
        }

        function Setcurrency(currency_id) {
            $('#currencyModal').modal('hide');
            $.ajax({
                url: "<?php echo e(route('screen.d.currency')); ?>",
                type: 'post',
                data: {
                    "_token": "<?php echo e(csrf_token()); ?>", "currency_id": currency_id
                },
                success: function (response) {
                    messageModal("Currency details saved successfully");
                },
                error: function (e) {
                    $('#response').html(e);
                }
            });
        }

        $('#rateModal').on('hidden.bs.modal', function (e) {
            var sst_gst_vat_field = $('#sst_gst_vat_field').val();
            var old_sst_gst_vat_field = $('#old_sst_gst_vat_field').val();
            var old_mode = $('#old_mode').val();
            var old_textype = $('#old_taxtype').val();
            if (data.ratetype || data.textype != old_textype && data.textype || old_sst_gst_vat_field != sst_gst_vat_field) {
                if (!data.textype) {
                    data.textype = $('#old_taxtype').val();
                }
                // var sc_field = $('#sc_field').val();
                $.ajax({
                    url: "<?php echo e(route('screen.d.rate')); ?>",
                    type: 'post',
                    data: {
                        "_token": "<?php echo e(csrf_token()); ?>", "taxtype": data.textype,
                        "mode": old_mode, "tax_percent": sst_gst_vat_field,
                    },
                    success: function (response) {
                        messageModal("Rate details saved successfully");
                    },
                    error: function (e) {
                        $('#response').html(e);
                    }
                });
            }
        })


        function eod_summarymodel() {
            $.ajax({
                url: "<?php echo e(route('local_cabinet.eodsummary.popup')); ?>",
                // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'get',
                success: function (response) {
                    // console.log(response);
                    $('#eodSummaryListModal-div').html(response);
                    $('#eodSummaryListModal').modal('show').html();
                },
                error: function (e) {
                    $('#responseeod').html(e);
                    $("#msgModal").modal('show');
                }
            });
        }


        function Setrate(currency_id) {
            $('#currencyModal').modal('hide');
        }


        function logout() {
            /*
            alert('logout: sInterval='+ Session['sInterval']);
            closeInterval(Session['sInterval']);

            Session['sInterval'] = '';
            */

            $('#logoutModal').modal('show');
        }

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


        function show_screen_e() {
            $("#locationModal").modal('show');
        }

        $('#locationModal').on('hidden.bs.modal', function (e) {
            updateField();
        });

        function updateField() {
            const form = document.getElementById('updateProspectFields');//$('#updateProspectFields');
            const formData = new FormData(form);
            console.log(formData)
            $.ajax({
                url: "<?php echo e(route('location.post.update')); ?>",
                type: "POST",
                enctype: 'multipart/form-data',
                processData: false,  // Important!
                contentType: false,
                cache: false,
                data: formData,
                success: function (response) {
                    $("#locationModal").modal('hide');
                    messageModal("Screen E details updated successfully")
                }, error: function (e) {
                    console.log(e.message)
                }
            });
        }

        //////////////////////////////////////////////////////

        $("#uploadLogo").click(function () {
            $("#file").click();
        });
        // file selected
        $("#file").change(function () {
            var fd = new FormData();

            var files = $('#file')[0].files[0];

            fd.append('location_id', $('#id').val());
            fd.append('file', files);

            uploadData(fd);
        });
        // preventing page from redirecting
        $("html").on("dragover", function (e) {
            e.preventDefault();
            e.stopPropagation();
            $("h1").text("Drag here");
        });

        $("html").on("drop", function (e) {
            e.preventDefault();
            e.stopPropagation();
        });

        // Drag enter
        $('.upload-area').on('dragenter', function (e) {
            e.stopPropagation();
            e.preventDefault();
            $("h1").text("Drop");
        });

        // Drag over
        $('.upload-area').on('dragover', function (e) {
            e.stopPropagation();
            e.preventDefault();
            $("upload_text").text("Drop");
        });

        // Drop
        $('.upload-area').on('drop', function (e) {
            e.stopPropagation();
            e.preventDefault();

            $("#upload_text").text("Upload");

            var file = e.originalEvent.dataTransfer.files;
            var fd = new FormData();

            fd.append('file', file[0]);

            uploadData(fd);
        });

        // Sending AJAX request and upload file
        async function uploadData(formdata) {
            await del_picture()
            $.ajax({
                url: "<?php echo e(route('location.saveLocationImage')); ?>",
                type: 'post',
                data: formdata,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function (response) {
                    addThumbnail(response);
                }
            });
        }

        //Image upload functions
        async function del_picture(id) {
            await $.ajax({
                url: "<?php echo e(route('location.deleteLocationImage')); ?>",
                type: 'post',
                data: {
                    "location_id": id
                },
                success: function (response) {
                    $("#uploadfile div.thmb").remove();
                    $("#uploadfile > .rcb").remove();
                    $("#logo_upload_cam").toggleClass('green');
                },
                error: function (e) {
                    console.log('Error:' + e);
                }
            });
            return false
        }

        // Added thumbnail
        function addThumbnail(data) {
            $("#uploadfile h1").remove();
            var len = $("#uploadfile div.thmb").remove();

            var num = Number(len);
            num = num + 1;

            var name = data.name;
            var src = data.src;


            var video_format = ['mp4', '3gp', 'avi', 'flv', 'mpeg'];
            var image_ft = name.slice((Math.max(0, name.lastIndexOf(".")) || Infinity) + 1);
            // Creating an thumbnail

            $("#uploadfile").append('<div id="thumbnail_' + num + '" class="thmb" style=""></div>');

            if (video_format.includes(image_ft)) {
                $("#thumbnail_" + num).append('<a target="_blank" style="color:#fff" href="' +
                    src + '"><video style="background-color:white;object-fit:contain" width="100%" height="255px" controls > <source src="' + src + '" /></video></a>');

            } else {

                // Creating an thumbnail
                $("#thumbnail_" + num).append('<img src="' + src + '" style="background-color:white;object-fit:contain" width="100%" height="255px">');
            }
            $("#uploadfile").append(`<img class="rcb"
				style="position: absolute;bottom: 12px;right: 63px;
				padding-bottom: 6px;padding-left: 5px;border: none;
				z-index: 10;width:35px;"
				src="<?php echo e(asset('images/redcrab_50x50.png')); ?>"
				onclick='del_picture("<?php echo e($location->id); ?>");return null'>`)
            $("#logo_upload_cam").addClass('green');

        }


        function goTo(route) {
            window.open($("#" + route).val(), '_blank');
        }

    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('common.web', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Server_Files\oceania\trunk\oceania\resources\views/screen_d/screen_d.blade.php ENDPATH**/ ?>