@foreach ( $product_data as $p)
<div id="show_hide_single_product{{$p->id}}" class="row col-md-12 text-white py-2 align-items-center"
	style="
	padding-top:0 !important;
	padding-bottom:0 !important;
	font-size:20px;
	cursor:pointer;
	margin-left: 24%;
	"
	onclick="add_product('{{$p->id}}',
		'{{$p->systemid}}',
		'{{$p->thumbnail_1}}',
		'{{$p->name}}',
		1,
		'{{number_format($p->recommended_price/100,2)}}')">

	<div class="col-1 text-left" style="margin-left: -115px;">
		{{$loop->index + 1}}
	</div>

	<div class="col-8 mt-1 mb-1">
		<img src='/images/product/{{$p->systemid}}/thumb/{{$p->thumbnail_1}}' 
			data-field='inven_pro_name'
			style=' width: 30px; height: 30px;
			display: inline-block;margin-right:5px;
			object-fit:contain;'>
		<span style="">{{$p->name}}</span>
	</div>

	<div style="padding-right:0px;margin-left: 100px;"
		class="col-3 text-right pl-0">
		{{number_format($p->recommended_price/100,2)}}
	</div>
</div>
@endforeach

