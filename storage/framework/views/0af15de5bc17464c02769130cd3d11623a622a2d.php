<html>
	<head>
		<link rel="stylesheet" href="<?php echo e(asset('css/styles.css')); ?>">
		<link rel="stylesheet" href="<?php echo e(asset('css/bootstrap.css')); ?>">
		<style>
			.main_div {
				width:100%;
				display:flex
			}
			
			.side_image_div {
				display:inline-flex;
				width: calc(100% - 500px);
				top: 0;
				bottom: 0;
				left: 0;
				right: 0;
				position: absolute;
				overflow: hidden;
			}

			.side_img {
				width: 100%;
				height: 100%;
				object-fit: cover;
			}
			.side_video {
				height: 100%;
				width: 100%;
				background-color: transparent;
				object-fit: cover;
			}
			.side_detail_div {
				width: 500px;
				background-color: <?php echo e($location->e_right_panel_color ?? '#000000'); ?>;
				height: 100%;
				right: 0;
				top: 0;
				position: absolute;
				border-left: 1px solid #fff;
				overflow: hidden;
				border-bottom: 20px solid <?php echo e($location->e_bottom_panel_color ?? '#000000'); ?>;
			}
			
			thead {
				background: <?php echo e($location->e_table_header_color ?? '#000000'); ?>;
				color: #fff;
			}
			tr {
				color: #fff;
				font-weight: 500;
				border: none;
			}
			td {
				border-top:none !important;
			}
			.footer_ {
				opacity: 0.9;
				position: absolute;
				padding: 0px 90px;
				bottom: 10%;
				left: 25%;
				/* display: inline-flex; */
				vertical-align: middle;
				align-items: center;
			}
			.footer-text {
				color: #fff;
				font-size: 70px;
				font-weight: 600;
			}
			.footer-span {
				color: #fff;
				display: block;
				/* transform: translateY(30px); */
				font-weight: 600;
				font-size: 36px;
				margin-bottom: -30px;
				margin-left: -20px;
			}
			.logo_div {
				position: absolute;
				bottom: 12%;
				right: 1%;
				width: 20%;
			}
		</style>
	</head>

	<body>
		<div class="main_div">
			<div class="side_image_div">
				<?php if(!empty($location->e_right_panel_image_file)): ?>
					<?php if(in_array(pathinfo($location->e_right_panel_image_file )['extension'],
							 ['mp4', '3gp', 'avi', 'flv', 'mpeg'])): ?>
						<video class="side_video"
							width="100%" height="auto" muted loop autoplay >
							<source src="/images/location/<?php echo e($location->id); ?>/<?php echo e($location->e_right_panel_image_file); ?>"> 
						</video>
					<?php else: ?>
						<img src="<?php echo e(asset('images/location/' . $location->id . '/' .
							 $location->e_right_panel_image_file )); ?>" class="side_img" />
					<?php endif; ?>
				<?php endif; ?>
			</div>
			<div class="side_detail_div">
				<table class="table" id="my_table">
				<thead>
				<tr class="screen-e-table-heading-text">
					<th scope="col" class="col-4">Product</th>
					<th scope="col" style>Qty</th>
					<th scope="col" class="col-4 text-right">
					
					<?php echo e(empty($terminal->currency) ? 'MYR': $terminal->currency); ?></th>
				</tr>
				</thead>
				<tbody>
				</tbody>
				</table>
			</div>
			
		<div class="footer_" id="total_amount"
			style="border-radius:10px;background-color: <?php echo e($location->e_bottom_panel_color ?? '#000000'); ?>;">
		</div>
		<?php if(!empty($logo)): ?>
			<div class="logo_div">
				<img src="<?php echo e(asset($logo)); ?>" style="width: 100%;" />
			</div>
		<?php endif; ?>
		</div>
	</body>
	<script src="<?php echo e(asset('js/jquery-3.4.1.min.js')); ?>"></script>

	<script>
	var product_ids = JSON.parse("<?php echo e($product_ids); ?>")

	var total_price = 0;
   
	document.addEventListener("DOMContentLoaded", function (event) {
		window.onstorage = function (e) {

			switch(e.key) {
				case "update-screen-e":
					products = JSON.parse(localStorage.getItem('update-screen-e'));
					html = '';
					total_price = 0;
					for( i in products) {
						p = products[i];
						html += `
							<tr id="product_row${p.product_id}">
									<td>
										<img
											src="/images/product/${p.product_systemid}/thumb/${p.product_thumbnail}"
											style="width:25px;height: 25px;margin-right:8px;">
										<span class="text-white">
											${p.name}
										</span>
									</td>
									
									<td>
										${p.qty}
									</td>
									<td style="text-align:right;" >
										${p.total_amount}
									</td>
									</tr>
						`;
						total_price += parseFloat(p.total_amount);
					}

					jQuery('#my_table').append('');
					jQuery('#my_table').append(html);
					$("#total_amount").text(total_price.toFixed(2));
					localStorage.removeItem('update-screen-e')
					break;
			}
		}
	});

	</script>
<!--  document.addEventListener("DOMContentLoaded", function (event) {
		window.onstorage = function (e) {

		switch(e.key) {
			case "update-count-id":
				data = JSON.parse(localStorage.getItem('update-count-id'));
				row_id = data[0];
				row_qty = data[1];
				row_amount = data[2] / 100;

				jQuery('#my_table').find('tr#' + row_id).find('td:eq(1)').
					empty();
				jQuery('#my_table').find('tr#' + row_id).find('td:eq(1)').
					html(row_qty);

				jQuery('#my_table').find('tr#' + row_id).find('td:eq(2)').
					html(row_amount.toFixed(2));

				localStorage.removeItem('update-count-id');
				break;
		
			case "update-discount":
				data = JSON.parse(localStorage.getItem('update-discount'));
				row_id = data[0];
				row_amount = parseInt(data[1]) / 100;
				jQuery('#my_table').find('tr#' + row_id).find('td:eq(2)').
					html(row_amount.toFixed(2));
				localStorage.removeItem('update-discount');
				break;


			case "delete":
				var row_id = (JSON.parse(localStorage.getItem('delete')));
				jQuery('table#my_table tr#' + row_id).remove();
				break;

			case "amount":
				var amount = localStorage.getItem('amount');
				var amount_total = JSON.parse(amount / 100).toFixed(2);
				if (amount_total > 0) {
					// alert(amount_total);
					jQuery('#total_amount').empty().append(
						`<span class="footer-span"
						style="font-size:28px">Total</span>
						<p class="footer-text p-0 m-0" >
						<?php echo e(empty($terminal->currency) ? 
						'MYR': $terminal->currency); ?> ${amount_total}
						</p>`);
				} else {
					/* This is a hack, used to clear screen E consistently
					 * as claerall doesn't seem to work on ALL systems. So 
					 * we clear screen E as soon as we get total_amount=0 */
					jQuery('#my_table > tbody').empty();
					jQuery('#total_amount').empty();
					/*
					var myTable = jQuery("#my_table");
					jQuery('<thead class="five-V">\n' +
					'    <tr class="screen-e-table-heading-text">\n' +
					'        <th scope="col" class="col-4">Product</th>\n' +
					'        <th scope="col">Qty</th>\n' +
					'        <th scope="col">Price(MYR)</th>\n' +
					'        <th scope="col">Discount</th>\n' +
					'        <th scope="col">Amount(MYR)</th>\n' +
					'    </tr>\n' +
					'    </thead>').appendTo(myTable);
						*/
				}
				localStorage.removeItem('amount')
				break;

			case "order":
				var order = JSON.parse(localStorage.getItem('order'));
				console.log(order);
				if(product_ids.includes(order.id)){
					var qty = order.quantity;
					var price = (order.price / 100).
						toFixed(2);
					var discount = 0 + "%";
					var amount = (order.price / 100).
						toFixed(2);

					var row_id = order.row_id;

					var row = '<tr id=' + row_id + '><td>' +
						'<img src="/images/product/PRD_ID/thumb/PRD_IMGSCR"style="width:25px;height: 25px;margin-right:8px;">PRD_NAME</td>'.
						replace('PRD_ID', order.id).
						replace('PRD_IMGSCR', order.thumbnail_1).
						replace('PRD_NAME', order.name) +
						'<td  style="text-align: center;" id=qty >' + qty +
						"</td>" +
						//'<ddtd style="text-align: center;">' + price + "</td>" +
						//'<td style="text-align: center;">' + discount + "</td>" +
						'<td style="text-align: right;">' + amount + "</td></tr>";
						total_price += order.price;
					jQuery('#my_table').append(row);
					localStorage.removeItem('order')
				}
				break;

			default:
		}
	}
	});
</script --> 
</html>
<?php /**PATH D:\Server_Files\oceania\trunk\oceania\resources\views/screen_e/opossum_e.blade.php ENDPATH**/ ?>