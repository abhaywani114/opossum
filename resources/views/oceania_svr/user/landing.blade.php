
<div style="padding-top:20px !important;padding-bottom:10px !important;" class="row py-2">
    <div class="col align-self-center" style="width:80%">
        <h2>User Management</h2>
    </div>
    <div class="col col-auto align-self-center">
    </div>
</div>

<table class="table table-bordered" id="tablestaff">
    <thead class="thead-dark">
    <tr>
        <th style="width:30px; ">No</th>
        <th style="width:100px;">User&nbsp;ID</th>
        <th>User Name</th>
		<th style="width:80px;">Location</th>
        <th style="width:100px;">Status</th>
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<script>

	$(document).ready(function () {
		staffTable.draw();    
	});

	var staffTable = $('#tablestaff').DataTable({
		"processing": false,
		"serverSide": true,
		"autoWidth":  false,
		"ajax": "{{route('user_management.datatable')}}",
		columns: [
			{data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'sysid', name: 'sysid'},
			{data: 'name', name: 'name'},
			{data: 'location', name: 'location'},
			{data: 'status', name: 'status'},
		],
		"order": [],
		"columnDefs": [
			{"className": "dt-center", "targets": [0, 1, 3, 4]},
			{"className": "dt-left", "targets": [2]},
			{width: '80px', targets: [3]},
			{"orderable": false, "targets": []},
		],
        "initComplete": function(settings, json) {
           
        }, 
        "drawCallback": function( settings ) {
            $('.King > td:nth(0)').html('K');
            $('.G_King > td:nth(0)').html('K');
        }
	});

</script>
