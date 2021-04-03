<div class="container-fluid">
    <div id="pa-view">
        <table id="opt-table" class="table table-bordered">
            <thead class="thead-dark">
            <tr>
                <th style="text-align:center;width:30px;">No</th>
                <th style="text-align:center;width:120px">Date</th>
                <th style="text-align:center;width:auto">Receipt&nbsp;ID</th>
                <th style="text-align:center;width:60px;">Total</th>
                <th style="text-align:center;width:60px;
					background-color:#ff7e30;border-color:ff7e30">Fuel
                </th>
                <th style="text-align:center;width:60px;
					background-color:#ff7e30;border-color:#ff7e30">Filled
                </th>
                <th style="text-align:center;width:60px;
					background-color:#ff7e30;border-color:#ff7e30">Refund
                </th>
                <th style="text-align:center;width:30px;
					background-color:#ff7e30;border-color:#ff7e30"></th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>

<style>
label, #opt-table_info {
    color: white !important;
}
</style>

<script>

    var tableData = {};
    var table = $('#opt-table').DataTable({
        "processing": false,
        "serverSide": true,
        "autoWidth": false,
        "ajax": {
            /* This is just a sample route */
            "url": "{{route('local_cabinet.optListData')}}",
            "type": "POST",
            data: function (d) {

                return $.extend(d, tableData);
            },
            'headers': {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: '', name: ''},
            {data: '', name: ''},
            {data: '', name: ''},
            {data: '', name: ''},
            {data: '', name: ''},
            {data: '', name: ''},

            {data: 'action', name: 'action'},
        ],
        "order": [0, 'desc'],
        "columnDefs": [
            {"width": "30px", "targets": [0, 6]},
            {"width": "180px", "targets": 1},
            {"width": "50px", "targets": [3, 4, 5]},
            {"className": "dt-left vt_middle", "targets": [2]},
            {"className": "dt-right vt_middle", "targets": [4,]},
            {"className": "dt-center vt_middle", "targets": [0, 1, 3, 5]},
            {"className": "vt_middle", "targets": [2]},
            {"className": "vt_middle slim-cell", "targets": [6]},
            {orderable: false, targets: [-1]},
        ],
    });


</script>
