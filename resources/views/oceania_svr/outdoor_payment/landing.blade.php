<div style="padding-top:20px !important;padding-bottom:10px !important;" class="row py-2">
    <div class="col align-self-center" style="width:80%">
        <h2>Outdoor Payment Terminal Management</h2>
    </div>
    <div class="col col-auto align-self-center">
    </div>
</div>

<table class="table table-bordered" id="otptable">
    <thead class="thead-dark">
    <tr>
        <th style="width:30px; ">No</th>
        <th style="width:auto;">Pump&nbsp;ID</th>
        <th style="width:auto;">Pump&nbsp;No</th>
		<th style="width:auto;">OPT&nbsp;ID</th>
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<script>
	var otptable = $('#otptable').DataTable({
			"processing": false,
			"serverSide": true,
			"autoWidth": false,
			"ajax": {
				"url": "{{route('outdoor_payment.datatable' )}}",
				"type": "GET",
			},
			columns: [
				{data: 'DT_RowIndex', name: 'DT_RowIndex'},
				{data: 'pump_id', name: 'pump_id'},
				{data: 'pump_no', name: 'pump_no'},
				{data: 'otp', name: 'otp'}, 
			],
			"order": [0, 'desc'],
			"columnDefs": [
				{ orderable: false, targets: [] }
			],
		});

</script>
