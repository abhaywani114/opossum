<div style="padding-top:0 !important;padding-bottom:0 !important;" class="row py-2">
    <div class="col-md-6 align-items-center d-flex" style="height:75px">
        <h2 class="mb-0">OPOSsum Terminal Management</h2>
    </div>
    <div class="col-md-6 align-self-center">
    </div>
</div>

<table class="table table-bordered" id="opossumtable">
    <thead class="thead-dark">
    <tr>
        <th style="width:30px; ">No</th>
        <th style="width:100px;">Location&nbsp;ID</th>
        <th>Branch</th>
		<th style="width:100px;">Terminal ID</th>
        <th class="text-center" style="width:100px;">Count</th>
        <th class="text-center" style="width:100px;">Threshold</th>
        <th class="text-center" style="width:200px;">Hardware&nbsp;Address</th>
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<script>
   $.ajaxSetup({
    headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        statusCode : {
			440: function() {
			   window.location = '/'
			},
        },
        async: false
    });

var opossumtable = $('#opossumtable').DataTable({
	"processing": false,
	"serverSide": true,
	"autoWidth": false,
	"ajax": {
		"url": "{{route('terminal.datatable' )}}",
		"type": "POST",
	},
	columns: [
		{data: 'DT_RowIndex', name: 'DT_RowIndex'},
		{data: 'loc_id', name: 'loc_id'},
		{data: 'name', name: 'name'},
		{data: 'term_id', name: 'term_id'}, 
		{data: 'count', name: 'count'},
		{data: 'threshold', name: 'threshold'},
		{data: 'hw', name:'hw',},
	],
	"order": [0, 'desc'],
	"columnDefs": [
		{"className": "dt-center", "targets": [0, 1, 3, 4]},
		{"className": "dt-left", "targets": [2]},
		{ orderable: false, targets: [] }
	],
});
</script>
