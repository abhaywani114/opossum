<div class="container-fluid">
    <div id="pa-view">
        <table id="ev-table" class="table table-bordered">
            <thead class="thead-dark">
            <tr>
                <th style="text-align:center;width:30px;">No</th>
                <th style="text-align:center;width:120px">Date</th>
                <th style="text-align:center;width:auto">Receipt&nbsp;ID</th>
                <th style="text-align:center;width:60px;">Total</th>
                <th style="text-align:center;width:30px;
					background-color:#ff7e30;border-color:#ff7e30">
					Refund
				</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>
<div class="modal fade" id="evReceiptDetailModal" tabindex="-1" role="dialog">
    <div class="modal-dialog  modal-dialog-centered" style="width: 366px; margin-top: 0!important;margin-bottom: 0!important;">

        <!-- Modal content-->
        <div class="modal-content  modal-inside detail_view" >

        </div>

    </div>
</div>
<style>
label, #ev-table_info {
    color: white !important;
}

.modal-inside .row{
    padding: 0px!important;
}
</style>
<script src="{{asset('js/number_format.js')}}"></script>

<script>

    var tableData = {};
    var evTable = $('#ev-table').DataTable({
        "processing": false,
        "serverSide": true,
        "autoWidth": false,
        "ajax": {
            /* This is just a sample route */
            "url": "{{route('local_cabinet.evListData')}}",
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
            {data: 'systemid', name: 'systemid'},
            {
                data: 'systemid', name: 'systemid', render: function (data) {
                    return "<a href='javascript:void(0)' style='text-decoration: none;' onclick='popupReceipt(" +data + ")'>" + data + "</a>"
                }
            },
            {data: 'systemid', name: 'systemid', render: function (data) {
                    return number_format((data)/100,2);
                }},
            {data: 'action', name: 'action'},
        ],
        "order": [0, 'desc'],
        "columnDefs": [
            {"width": "5%", "targets": [0]},
            {"width": "10%", "targets": 1},
            {"width": "60%", "targets": 2},
            {"width": "15%", "targets": [3]},
            {"width": "10px", "targets": [ 4]},
            {"className": "dt-left vt_middle", "targets": []},
            {"className": "dt-right vt_middle", "targets": []},
            {"className": "dt-center vt_middle", "targets": [0, 1, 2, 3,4]},
            {"className": "vt_middle", "targets": [2]},
            {"className": "vt_middle slim-cell", "targets": []},
            {orderable: false, targets: [-1]},
        ],
    });

    function popupReceipt(data) {
        $.ajax({
            method: "post",
            url: "{{route('local_cabinet.evReceiptDetail')}}",
            data: {id: data}
        }).done((data) => {
            $(".detail_view").html(data);
            $("#evReceiptDetailModal").modal('show');
        })
            .fail((data) => {
                console.log("data", data)
            });
    }


</script>
