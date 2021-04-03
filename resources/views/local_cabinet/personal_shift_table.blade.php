<div class="contain er-fluid">
	<div id="pa-view">
		<table id="receipt-table" class="table table-bordered">
			<thead class="thead-dark">
			<tr>
				<th style="text-align:center;width:5%;" >No</th>
				<th style="text-align:center;width:30%">Login</th>
				<th style="text-align:center;width:auto">Staff</th>

			</tr>
			</thead>
			<tbody style="background: white">
                @foreach ($pshift as $row)
			<tr>
				<td style="text-align: center;width:5%;" >
					{{ $loop->index + 1 }}
				</td>
                {{--
                <td style="text-align: center;width: 40%">
					<a href="#" style="text-decoration: none;"
                        onclick="pShiftReceiptModal({{$row->systemid}}, '{{$row->created_at}}')">{{date('dMy H:i:s', strtotime($row->created_at??''))}}
                    </a>
                </td>
                --}}
                <td style="text-align: center;width: 40%">
					<a href="#" style="text-decoration: none;"
						onclick="pssReceiptPopup('{{date('dMy H:i:s', strtotime($row->login??''))}}', 
							'{{empty($row->logout) ? '':date('dMy H:i:s', strtotime($row->logout))}}','{{$row->systemid}}')"
							>{{date('dMy H:i:s', strtotime($row->login??''))}}
                    </a>
                </td>

				<td style="text-align: center;width: auto">
					{{$row->systemid}}

				</td>
            </tr>
            @endforeach
			</tbody>
		</table>
	</div>
</div>

