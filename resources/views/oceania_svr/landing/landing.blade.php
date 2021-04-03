@extends('common.web')

@section('styles')

<style>
/* remove small icons from input number */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
	-webkit-appearance: none;
	margin: 0;
}
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter,
.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_processing,
.dataTables_wrapper .dataTables_paginate{
	color: black !important;
	font-weight: normal !important;
}

/* Firefox */
input[type=number] {
	-moz-appearance:textfield;
}

.month_table > tr > th {
	font-size: 22px;
	color: white;
	background-color: rgba(255, 255, 255, 0.5);
}

.slim {
	padding-top:2px !important;
	padding-bottom:2px !important;
}

.month_table  > tr > td {
	color: #fff;
	font-weight: 600;
	border: unset;
	font-size: 20px;
	cursor: pointer;
}


.date_table > tbody > tr > th {
	font-size: 22px;
	color: white;
	background-color: rgba(255, 255, 255, 0.5);
}

.date_table > tbody > tr > td {
	color: #fff;
	font-weight: 600;
	border: unset;
	font-size: 20px;
	cursor: pointer;
}

table.dataTable tbody td{
	border-left: 1px solid #dee2e6;
	border-right: 1px solid #dee2e6;
	border-top: none;
	/*border-bottom: none;*/
}

.btn-green {
	background-color: green !important;
	color: #fff !important;
	box-shadow: none !important;
	border: 0px !important;
}

.btn-green:focus {
	background-color: green !important;
	color: #fff !important;
	box-shadow: none !important;
	border: 0px !important;
}

.bg-blue {
	background-color: #007bff;
	color: #fff;
}

.date_table1 > tbody > tr > th ,
{
	font-size: 22px;
	color: white;
	background-color: rgba(255, 255, 255, 0.5);
}

.date_table1 > tbody > tr > td {
	color: #fff;
	font-weight: 600;
	border: unset;
	font-size: 20px;
	cursor: pointer;
}

.selected_date {
	color: #fff !important;
	background: #008000;
	font-weight: 600 !important;
}

.selected_date1 {
	color: #008000 !important;
	font-weight: 700 !important;
}

#Datepick .d-table {
	display: -webkit-flex !important;
	display: -ms-flexbox !important;
	display: flex !important;
}

.dataTables_filter input {
	width: 300px;
}

.greenshade {
	height: 30px;
	background-color: green; /* For browsers that do not support gradients */
	background-image: linear-gradient(-90deg, green, white); /* Standard syntax (must be last) */
}
.dt-button{
	display: none;
}
.dataTables_filter input { 
    width: 70%;
}

/* .bg-purplelobster{
	background-color: rgba(26, 188, 156, 0.7);
	border-color: rgba(26, 188, 156, 0.7);
} */

/*//for calender short day*/
.shortDay ul{
	llist-style: none;
	background-color: rgba(255, 255, 255, 0.5);
	position: relative;
	left: -75px;
	width: 124%;
	height: 55px;
	line-height: 42px;
}

.shortDay ul > li{
	font-size: 22px;
	color: white;
	font-weight: 700 !important;
	/* background-color: #2b1f1f; */
	padding: 5px 24px;
	text-align: left !important;
}
.list-inline-item:not(:last-child){
	margin-right: 0 !important;
}
.modal-content{
	overflow: hidden;
}
.modal-inside .row {
	margin: 0px;
	color: #fff;
	margin-top: 15px;
	padding: 0px !important;
}

.butns {
	display: none
}

th, td {
	vertical-align: middle !important;
	text-align: center
}

td {
	text-align: center;
}

.modalBtns {
	margin-top: 5px
}

.butns{
	display: none
}
.num_td{
	text-align: left;
}
.value-button {
	display: inline-block;
	font-size: 24px;
	line-height: 21px;
	text-align: center;
	vertical-align: middle;
	background: #fff;
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
	text-align: center;
	text-align: center;
}
input.number {
	text-align: center;
	border: none;
	border: 1px solid #e2dddd;
	margin: 0px;
	width: 90px;
	border-radius: 5px;
	height: 38px;
	border-radius: 5px;
	background-color: #d4d3d36b !important;
	vertical-align: text-bottom;
}
.value-button {
	cursor:pointer;
}
</style>
@endsection
@include('oceania_svr.common.header')
@include('oceania_svr.common.buttons')
@section('content')
<div id="landing-view" class="container-fluid">
</div>
@endsection
<!-- Modal Logout-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog"
	style="padding-right:0 !important"
	aria-labelledby="logoutModalLabel"
	aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered  mw-75 w-50" role="document">
        <div class="modal-content modal-inside bg-purplelobster">
            <div style="border:0" class="modal-header"></div>
            <div class="modal-body text-center">
                <h5 class="modal-title text-white" id="logoutModalLabel">
				Do you really want to logout?
				</h5>
            </div>
            <div class="modal-footer"
			style="border-top:0 none; padding-left: 0px; padding-right: 0px;">
                <div class="row p-0 m-0"
					style="padding-top:15px !important;
						padding-bottom:15px !important; width: 100%;">
                    <div class="col col-m-12 text-center">
                        <a class="btn btn-primary" href="{{ route('logout') }}"
							style="width:100px"
							onclick="event.preventDefault();
							document.getElementById('logout-form').submit();">
                            Yes
                        </a>
                        <button type="button" class="btn btn-danger"
							data-dismiss="modal" style="width:100px">No
                        </button>
                    </div>
                </div>

                <form id="logout-form" action="{{ route('logout') }}"
					method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>


@section('script')
<script>
function loadView(route) {
	$.ajax({
		url: route,
		type: 'GET',
		dataType: "html",
		success: function (response) {
			$("#landing-view").html(response);
		},
		error: function (e) {
			console.log('error', e);
		}
	});
}
</script>
@endsection
@include('oceania_svr.common.footer')

