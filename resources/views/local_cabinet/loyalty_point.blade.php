@extends('common.web')
@section('styles')

<script type="text/javascript" src="{{asset('js/console_logging.js')}}"></script>
<script type="text/javascript" src="{{asset('js/qz-tray.js')}}"></script>
<script type="text/javascript" src="{{asset('js/opossum_qz.js')}}"></script>

<style>
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter,
.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_processing,
.dataTables_wrapper .dataTables_paginate {
	color: black !important;
	font-weight: normal !important;
}

#receipt-table_length, #receipt-table_filter,
#receipt-table_info, .paginate_button {
	color: white !important;
}

#eodSummaryListModal-table_paginate,
#eodSummaryListModal-table_previous,
#eodSummaryListModal-table_next,
#eodSummaryListModal-table_length,
#eodSummaryListModal-table_filter,
#eodSummaryListModal-table_info {
	color: white !important;
}

.paging_full_numbers a.paginate_button {
	color: #fff !important;
}

.paging_full_numbers a.paginate_active {
	color: #fff !important;
}

table.dataTable th.dt-right, table.dataTable td.dt-right {
	text-align: right !important;
}

td {
	vertical-align: middle !important;
}

table.dataTable.display tbody tr.odd > .sorting_1,
table.dataTable.order-column.stripe tbody tr.odd >
.sorting_1 {
	background-color: white !important;
}
</style>
@endsection

@section('content')
@include('common.header')
@include('common.menubuttons')

<div id="landing-view">
	<!--div id="landing-content" style="width: 100%"-->
	<div class="container-fluid">
		<div class="d-flex mt-0 align-items-center" style="width:100%">
			<div class="align-self-center col-md-7" style="padding:0">
				<h2 class="mb-0">Loyalty Point Assignment</h2>
			</div>
		   
			<div class="col-md-2 ml-0"
				style="justify-content:flex-end;display:flex">
				<h5 class="mb-0 text-center">Total<br>
					<b>
					<span id="balance_pts">0</span>
					</b>Pts
				</h5>
			</div>

			<div class="col-md-2">
				<input  class="form-control" type="text"
					placeholder="NRIC" name="nric" id="nric">
			</div>
			<div class="col-md-1 pr-0">

				<button onclick="checkNricIfExist()"
					class="btn btn-success poa-bluecrab-button mb-0 mr-0"
					style="margin: 0 0 0; float:right; font-size:13px;
					opacity:0.65" id="addproduct_btn">Confirm
				</button>
			</div>
		</div>

		<div id="response"></div>
            <div id="responseeod"></div>
				<table class="table table-bordered display"
                   id="eodsummarylist" style="width:100%;">
                <thead class="thead-dark">
                <tr>
                    <th class="text-left" style="width:15% ; text-align: center !important;">Product ID</th>
                    <th class="text-left" style="width:50%;">Product Name</th>
                    <th class="text-left" style="width:10%; text-align: center !important;">Amount</th>
                    <th class="text-left" style="width:10%; text-align: center !important;">Pts</th>
                   <!--  <th class="text-left" style="width:15%; text-align: center !important;">Accumulated Pts</th> -->
                </tr>
                </thead>

                <tbody>  <?php if (!empty($loyatypoints)){

    foreach ($loyatypoints as $product){ ?>
                <tr>
                    <td style="text-align: center !important;">{{!empty($product['systemid'])?$product['systemid']:"0"}}</td>
                    <td> <img src='/images/product/{{$product["systemid"]}}/thumb/{{$product["thumb"]}}' 
            data-field='inven_pro_name'
            style=' width: 30px; height: 30px;
            display: inline-block;margin-right:5px;
            object-fit:contain;'>   {{!empty($product['name'])?$product['name']:"RON95"}}</td>
                    <td style="text-align: center !important;">{{!empty($product['price'])?number_format($product['price']/100,2):"0.00"}} </td>
                    <td style="text-align: center !important;"> {{!empty($product['loyalty']) && !empty($product['price']) ?$product['loyalty'] * $product['price'] :0}}</td>
                 
                </tr>
   <?php } 
}
     ?>
                    
                </tbody>
            </table>
        </div>
    </div>

    <div id="res"></div>
    <div class="modal fade bd-example-modal-lg" id="receiptoposModal"
         tabindex="-1" role="dialog" aria-labelledby="receiptoposModal"
         aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered"
             style="border-radius:10px;min-width: 1050px;">
            <div class="modal-content bg-purplelobster"
                 style="border-radius:10px;color:white !important">
                <div class="modal-header">
                    <h3 class="mb-0">Receipt List</h3>
                </div>
                <div class="modal-body" id="receiptoposModal-table"></div>
            </div>
        </div>
    </div>

    


    <style>
        .btn {
            color: #fff !Important;
        }

        .form-control:disabled, .form-control[readonly] {
            background-color: #e9ecef !important;
            opacity: 1;
        }

        #void_stamp {
            font-size: 100px;
            color: red;
            position: absolute;
            z-index: 2;
            font-weight: 500;
            margin-top: 130px;
            margin-left: 10%;
            transform: rotate(45deg);
            display: none;
        }
    </style>

    <script>
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });


        $('#eodsummarylist').DataTable({});


        function checkNricIfExist(){
        $.ajax({
                url: "{{ route('local_cabinet.checkNric') }}",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'post',
                data: {
                    'nric': $('#nric').val(),
                },
                success: function (response) {
                    console.log(response);
                    //$('#responseeod').html(response);
                    //$('#eodModal_1').modal('show').html();
                },
                error: function (e) {
                    //$('#responseeod').html(e);
                    //$("#msgModal").modal('show');
                }
            });
    }
       

    </script>
@endsection


@section('scripts')
    <!-- This is from Qz -->
    <script type="text/javascript" src="{{asset('js/rsvp-3.1.0.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/sha-256.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {

            var directoryTable = $('#eodsummarylist').DataTable({
                "processing": false,
                "serverSide": true,
                "ajax": {
                    "url": "local_cabinet.loyaltyPointsList",
                    "type": "POST",
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    "data": {
                        "terminal_id": "{{$terminal_id??""}}",
                        "date": "{{$date??""}}"
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'eod_date', name: 'Date'},
                    {data: 'shift', name: 'Shift'},
                    {data: 'ev', name: 'EV'},
                    {data: 'opt', name: 'OPT'},
                    {data: 'today_sales', name: 'Sales'},
                ],
                "order": [],
                "columnDefs": [
                    {"className": "dt-center", "targets": [0, 1, 2]},
                    {"className": "dt-right", "targets": [3, 4, 5]},
                    {"targets": -1, 'orderable': false}
                ],
            });
        });

    </script>

    <script type="text/javascript">
        $('table').on('click', 'tr p.rackFileNameOutput', function (e) {
            e.preventDefault();
            $(".rackFileNameOutput").removeClass("rackFileNameOutputVal");
            $(this).addClass('rackFileNameOutputVal');
            document.getElementById("rackFileNameInput").value = $(this).text();
            $("#rackFileNameInput").keyup(function () {
                var currentText = $(this).val();
                $(".rackFileNameOutputVal").text(currentText);
            });
        });
    </script>


    <script type="text/javascript">
   

        function show_month_eod(eod_id) {
            console.log('***** show_month_end(' + eod_id + ') *****');

            $.ajax({
                url: "'eod.eod_month'",
                //headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'post',
                data: {
                    'eod_id': eod_id,
                    'terminal_id': {{$terminal_id??""}}
                },
                success: function (response) {
                    console.log(response);
                    $('#responseeod').html(response);
                    $('#eodModal_1').modal('show').html();
                },
                error: function (e) {
                    $('#responseeod').html(e);
                    $("#msgModal").modal('show');
                }
            });
        }

        function receipt_list() {
            $.ajax({
                url: "{{route('local_cabinet.receipt.list')}}",
                // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'get',
                success: function (response) {
                    // console.log(response);
                    $('#receiptoposModal-table').html(response);
                    $('#receiptoposModal').modal('show').html();
                },
                error: function (e) {
                    $('#responseeod').html(e);
                    $("#msgModal").modal('show');
                }
            });
        }


        function shift_list(eod_id) {
            $.ajax({
                url: "eod.shift_list'",
                //headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'post',
                data: {
                    'eod_id': eod_id
                },
                success: function (response) {
                    console.log(response);
                    $('#responseeod').html(response);
                    $('#shiftModal').modal('show').html();
                },
                error: function (e) {
                    $('#responseeod').html(e);
                    $("#msgModal").modal('show');
                }
            });
        }
    </script>

@endsection
@extends('common.footer')
