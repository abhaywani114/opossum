<div class="container-fluid">
	<div id="pa-view">
		<table id="receipt-table" class="table table-bordered">
			<thead class="thead-dark">
			<tr>
				<th style="text-align:center;width:30px;" >No</th>
				<th style="text-align:center;width:120px">Date</th>
				<th style="text-align:center;width:auto">Receipt&nbsp;ID</th>
				<th style="text-align:center;width:60px;">Total</th>
				<th style="text-align:center;width:60px;
					background-color:#ff7e30;border-color:ff7e30">Fuel</th>
				<th style="text-align:center;width:60px;
					background-color:#ff7e30;border-color:#ff7e30">Filled</th>
				<th style="text-align:center;width:60px;
					background-color:#ff7e30;border-color:#ff7e30">Refund</th>
				<th style="text-align:center;width:30px;
					background-color:#ff7e30;border-color:#ff7e30"></th>
			</tr>
			</thead>
			<tbody>
                @foreach ($receipt as $row)
			<tr>
				<td style="text-align: center;width:30px;" >
					{{ $loop->index + 1 }}
				</td>
				<td style="text-align: center;width: 100px">
					{{date('dMy H:i:s', strtotime($row->created_at??''))}}
				</td>
				<td style="text-align: center;width: auto">
					<a href="#" style="text-decoration: none;"
						onclick="showReceipt({{$row->id}})">{{$row->systemid}}
					</a>
				</td>
				<td class="text-center" style="width:50px;@if($row->status=='voided')background-color:red;color:white;font-weight:bold;
							@elseif($row->is_refunded) background-color:orange;color:white;font-weight:bold; @endif">
					{{number_format(((($row->cash_received/100-$row->cash_change/100+((5 * round(($row->cash_received-$row->cash_change) / 5))-($row->cash_received-$row->cash_change))/100))??"2"),2)}}
				</td>

				<td class="text-center">
					{{number_format($row->fuel,2)}}
				</td>

				<td class="text-center">
					{{number_format($row->filled,2)}}
				</td>
				<td class="text-center">
					@if($row->status=='voided')
						0.00
					@else
					{{number_format($row->refund,2)}}
					@endif
				</td>
				<td class="text-center"
					style="padding-top:2px;padding-bottom:2px;width:30px">
					<img src="{{asset('/images/bluecrab_50x50.png')}}"
						id="crab_{{$row->id}}"
						@if (!$row->is_refunded && ((float) number_format($row->refund,2) > 0 ) && $row->status !='voided') 
							onclick="generate_refund('{{$row->id}}',
							'{{number_format($row->refund,2)}}',this)"
							style="cursor:pointer;width:25px;height:25px"
						@else 
							style="cursor:text;width:25px;height:25px;
							filter: grayscale(1) brightness(1.5);"
							disabled="disabled" @endif/>
				</td>
            </tr>
            @endforeach
			</tbody>
		</table>
	</div>
</div>

