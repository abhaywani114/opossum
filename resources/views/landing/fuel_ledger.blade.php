<div class="container-fluid">
	<div id="pa-view">
		<table id="tablefuel" class="table table-bordered">
			<thead class="thead-dark">
				<tr>
					<th style="text-align: center;width:5%;" >No</th>
					<th style="text-align: center;width: 20%">Date</th>
					<th style="text-align: left;width: auto">User</th>
					<th class="text-left" style="width: 20%;">Location</th>
					<th style="text-align: center;width: 10%">Litre</th>
				</tr>
			</thead>
			<tbody>
                @if($Fuelledgers)
                @foreach ($Fuelledgers as $Fuelledger)
                <tr>
					<td style="text-align: center;width:5%;" >
						{{ $loop->index + 1  }}</td>
					<td style="text-align: center;width: 20%">
						{{date('dMy H:i:s', strtotime($Fuelledger->created_at))}}</td>
					<td style="text-align: left;width: auto">
						{{$user->fullname}},&nbsp;{{$user->systemid}}</td>
					<td class="text-left" style="width: 20%;">
						{{$Fuelledger->location->name??""}}</td>
					<td style="text-align: right;width: 10%; padding-right:10px">
						{{number_format($Fuelledger->volume,2)}}</td>
				</tr>
                @endforeach
                @endif
			</tbody>
		</table>
	</div>
</div>

