<html>
	<head>
		<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
		<link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
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
				background-color: {{ $location->e_right_panel_color ?? '#000000' }};
				height: 100%;
				right: 0;
				top: 0;
				position: absolute;
				border-left: 1px solid #fff;
				overflow: hidden;
				border-bottom: 20px solid {{ $location->e_bottom_panel_color ?? '#000000' }};
			}
			
			thead {
				background: {{ $location->e_table_header_color ?? '#000000' }};
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
    z-index: 999;
    bottom: 14%;
    left: 44%;
    vertical-align: middle;
    align-items: center;
    display: none;
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
				@if (!empty($location->e_right_panel_image_file))
					@if (in_array(pathinfo($location->e_right_panel_image_file )['extension'],
							 ['mp4', '3gp', 'avi', 'flv', 'mpeg']))
						<video class="side_video"
							width="100%" height="auto" muted loop autoplay >
							<source src="/images/location/{{$location->id}}/{{$location->e_right_panel_image_file}}"> 
						</video>
					@else
						<img src="{{asset('images/location/' . $location->id . '/' .
							 $location->e_right_panel_image_file )}}" class="side_img" />
					@endif
				@endif
			</div>
			<div class="side_detail_div">
				<table class="table" id="my_table">
				<thead>
				<tr class="screen-e-table-heading-text">
					<th scope="col" class="col-4">Product</th>
					<th scope="col" style>Qty</th>
					<th scope="col" class="col-4 text-right">
					{{--
					Amount&nbsp;({{empty($cu->currency) ? 'MYR': $cu->currency }})</th>
					--}}
					{{empty($cu->currency) ? 'MYR': $cu->currency }}</th>
				</tr>

				
				</thead>
				<tbody>

				</tbody>
				</table>
			</div>

		

			
		<div class="footer_" id="total_amount"
			style=" border-radius: 6px;padding: 18px 0px 30px;text-align: center;color: white;line-height: 0.2;font-weight: 600;width: 403;height: 83;letter-spacing: 2px;font-size: 50px;display: none;background-color: {{ $location->e_bottom_panel_color ?? '#000000' }};">
		</div>
		@if(!empty($logo))
			<div class="logo_div">
				<img src="{{asset($logo)}}" style="width: 100%;" />
			</div>
		@endif
		</div>
		<input type="hidden" name="total_amount_landing" id="total_amount_landing" value="0">
		<input type="hidden" name="total_amount_pro" id="total_amount_pro" value="0">
	</body>
	<script src="{{asset('js/jquery-3.4.1.min.js')}}"></script>

	<script>

		function calc_total_val(){
			full_amount = parseFloat($('#total_amount_landing').val())+parseFloat($('#total_amount_pro').val());
			var rowCount = $('#my_table tr').length;
			if(rowCount>1){


			$("#total_amount").html( '<p style="font-size:20px;letter-spacing: normal;text-align: center;margin-left: -214px;font-weight: normal;">Total</p>{{empty($cu->currency) ? 'MYR': $cu->currency }} '+full_amount.toFixed(2));
					$("#total_amount").show();
				}else{
					$("#total_amount").hide();
				}

		}
	var product_ids = JSON.parse("{{ $product_ids }}")

	var total_price = 0;
   
	document.addEventListener("DOMContentLoaded", function (event) {

		window.onstorage = function (e) {
			
			switch(e.key) {

				case "clear_products":
					 $(`table tr.cstore_product`).remove();
					 $('#total_amount_pro').val(0);
					 calc_total_val();
					 localStorage.removeItem('clear_products');
					break;

				case "delete_product":
			
					 row_id = JSON.parse(localStorage.getItem('delete_product'));
					
					 $(`table tr#product_row`+row_id.id).remove();
					 $('#total_amount_pro').val(row_id.total);
					 calc_total_val();
					 localStorage.removeItem('delete_product');
					break;
				case "update-screen-e-landing":
					products = JSON.parse(localStorage.getItem('update-screen-e-landing'));
					console.log(products);
					html = '';
					total_price = 0;
					
						p = products;
						//$(`table tr#product_row${p.product_id}`).remove();
						html += `
							<tr  id="product_row${p.product_id}">
									<td>
										<img
											src="${p.product_thumbnail}"
											style="width:25px;height: 25px;margin-right:8px;">
										<span class="text-white">
											${p.name}
										</span>
									</td>
									
									<td>
										1
									</td>
									<td style="text-align:right;" >
										${p.dose}
									</td>
									</tr>
						`;
						total_price += parseFloat(p.dose);
					
					$('#total_amount_landing').val(total_price+ parseFloat($('#total_amount_landing').val()));
					jQuery('#my_table').append(html);
					calc_total_val();
					localStorage.removeItem('update-screen-e-landing')
				break;
				case "update-screen-e":
					products = JSON.parse(localStorage.getItem('update-screen-e'));
					console.log(products);
					html = '';
					total_price = 0;
					for( i in products) {
						p = products[i];
						$(`table tr#product_row${p.product_id}`).remove();
						html += `
							<tr class="cstore_product" id="product_row${p.product_id}">
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
					//console.log(html);
					jQuery('#my_table').append('');
					jQuery('#my_table').append(html);
					$('#total_amount_pro').val(total_price);
					calc_total_val();
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
						{{empty($terminal->currency) ? 
						'MYR': $terminal->currency }} ${amount_total}
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
