@extends('oceania_svr.common.web')

@section('styles')
<style>

body { background-color: black; }
label {
	color: white;
	margin-top: 10px;
	margin-left: 10px;
	margin-bottom: 5px;
}

</style>

@endsection
@php
  $pagename="screen_d";
@endphp
@include('oceania_svr.common.header')
@section('content')

<div class="container-fluid" id="" style="margin-top:5px">
    <div class="row justify-content-center" style="height:94%">
        <div class="col-md-8 hidden-md-down"
			style="display: inline-block;">

			<div>
		
			</div>
        </div>

        <div class="col-md-4 hidden-md-down" style="">
			<div class="pl-3"
				style="border-left: 2px solid  #a0a0a0; color:white;
					height:95%">
				<div>
					<div class="mt-2 mb-2 align-items-center">
						<h3><b>Oceania</b>
						<span style="font-size:10px">v1.0</span>
						<span onclick="logout()" class="mt-1"
							style="font-size:20px;color:red;
							float:right;cursor:pointer">Log Out
							</span>
						</h3>
					</div>
				</div>

				<span>Log In</span>
                    <span class="float-right">
					{{date('dMy H:i:s', strtotime($user->last_login??''))}}</span><br>
					<span>Merchant</span>
						<span class="float-right" id="merchant_name">
							{{$merchant->name??''}}
						</span><br>
					<span>Merchant ID</span>
						<span class="float-right" id="merchant_id">
							{{$merchant->systemid??''}}
						</span><br>
					<span>Location</span>
						<span class="float-right"
						id="location_name">{{$location->name??''}}</span><br>
					<span>Location ID</span>
						<span class="float-right" id="location_id">
                            {{$location->systemid??''}}</span><br>
					<span>Segment</span>
						<span class="float-right" id="segment">Franchise</span><br>
                <hr>
				<div class="d-flex align-items-center"
					style="justify-content:center">
					<!--
					<img src="{{asset('images/vertical_doughnut_spin.gif')}}"
						style="width:350px;height:350px;object-fit:contain"/>
					-->
				</div>
			</div>
        </div>
    </div>
</div>


<div style="position: absolute; bottom: 30px; right:10px">
    <img style="object-fit:contain;width:200px !important"
		src="{{ asset('images/logo2.png') }}">
</div>

@include('oceania_svr.common.footer')

<!--
<div class="fixed-bottom">
<nav class="navbar navbar-light bg-light p-0"
	style=" background-image:linear-gradient(rgb(38, 8, 94),rgb(86, 49, 210));">
    <span class="navbar-text m-0"></span>
</nav>
</div>
-->

<!--Currency Popup Start-->
<div class="modal fade currencyModal"
	id="currencyModal" tabindex="-1"
	role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-purplelobster">
            <div class="modal-header">
                <h3 style="margin-bottom:0">Currency</h3>
            </div>
            <div class="modal-body modalTypeBody" id="currencyModalTable">

            </div>
        </div>
    </div>
</div>
<!--End of Currency Popup -->
<!--Rate Popup Start-->
<div class="modal fade rateModal"
	id="rateModal" tabindex="-1"
	role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content bg-purplelobster">
            <div class="modal-header">
                <h3 style="margin-bottom:0">Rate</h3>
            </div>
            <div class="modal-body" id="rateModal-body">
            </div>
            <script>
                var data = {
                    ratetype:"",
                    textype:""
                };

                function select_rate_type(ratetype){
                    if(ratetype == "exclusive"){
                        $("#exclusive_rate").css("cursor","default");
                        $("#inclusive_rate").css("color","white");
                        $("#exclusive_rate").css("color","#34dabb");
                        $("#inclusive_rate").css("cursor","pointer");

                    } else {
                        $("#exclusive_rate").css("color","white");
                        $("#inclusive_rate").css("color","#34dabb");
                        $("#exclusive_rate").css("cursor","pointer");
                        $("#inclusive_rate").css("cursor","default");
                    }

                    data.ratetype = ratetype;
                }

                function select_text_type(textype) {
                    if(textype == "sst") {
                        $("#idofSST").css("cursor","default");
                        $("#idofGST").css("color","white");
                        $("#idofSST").css("color","#34dabb");
                        $("#idofGST").css("cursor","pointer");
                        $("#idofVAT").css("cursor","pointer");
                        $("#idofVAT").css("color","white");

                    } else if(textype == "gst") {
                        $("#idofGST").css("cursor","default");
                        $("#idofSST").css("color","white");
                        $("#idofGST").css("color","#34dabb");
                        $("#idofSST").css("cursor","pointer");
                        $("#idofVAT").css("cursor","pointer");
                        $("#idofVAT").css("color","white");

                    } else {
                        $("#idofVAT").css("cursor","default");
                        $("#idofSST").css("color","white");
                        $("#idofVAT").css("color","#34dabb");
                        $("#idofSST").css("cursor","pointer");
                        $("#idofGST").css("cursor","pointer");
                        $("#idofGST").css("color","white");
                    }

                    data.textype = textype;
                }
            </script>
        </div>
    </div>
</div>
<!--End of Rate Popup -->
<!--EoD Popup Start-->
<div class="modal fade" id="eodSummaryListModal" tabindex="-1" role="dialog"
	aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-75"
		style="width:370px" role="document">
        <div id="eodSummaryListModal-div" class="modal-content bg-white" style="width:370px">

        </div>
    </div>
</div>
<!--End of EoD Popup -->

<div class="modal fade" id="locationModal" tabindex="-1"
     role="dialog" aria-labelledby="staffNameLabel"
     aria-hidden="true" style="text-align: center;">

    <div class="modal-dialog modal-dialog-centered  mw-75 w-50"
         role="document" style="display: inline-flex;min-width: 43vw !important;">
        <div class="modal-content modal-inside bg-purplelobster"
             style="width: 100%;min-height: 530px;">
        <div class="modal-header" style="">
            <h3 class="text-white"
                id="statusModalLabel" style="margin-bottom:0">
                Screen E Details
            </h3>
        </div>
        <div class="modal-body text-center" style="padding-top: 20px;">
        <form action="#" id="updateProspectFields" method="post"
              enctype="multipart/form-data"
              onsubmit="return false;" autocomplete="off">
            <div class="row" style="padding-top: unset;">
            <div class="col-md-6" style="padding-right: unset;
				padding-top: 0px;">
            <div class="upload-area" id="uploadfile" style='border:
                unset;height:255px;background: grey;display:block;
                margin-left: 0.9%;overflow: hidden;width: 100%;'>

                <input type="file" name="file" id="file" class="hidden"/>

                @if (!empty($location->e_right_panel_image_file))
                <div id="thumbnail_1" class="thmb" style="">

					@if (in_array(pathinfo($location->e_right_panel_image_file )['extension'], ['mp4', '3gp', 'avi', 'flv', 'mpeg']))
						<a href='/images/location/{{$location->id}}/{{$location->e_right_panel_image_file}}'
							target="_blank">
						<video style="background-color:white;object-fit:contain"
                        	width="100%" height="255px"  controls>
							<source src="/images/location/{{$location->id}}/{{$location->e_right_panel_image_file}}" type="video/mp4">
						</video>
					</a>

					@else
						<a href='/images/location/{{$location->id}}/{{$location->e_right_panel_image_file}}'
							target="_blank">
						<img style="background-color:white;object-fit:contain"
                        	src="/images/location/{{$location->id}}/{{$location->e_right_panel_image_file}}"
                     	 	width="100%" height="255px">
					</a>
					@endif
                </div>
                <button class="redCrabShell"
                    style="position: absolute;bottom: 20px;right: 63px;
                    padding-bottom: 24px;padding-left: 5px;border: none;    z-index: 10;"
                    onclick='del_picture("{{$location->id}}");return null'>
                    <i class="fa fa-times redCrab" style="padding: 0px;"></i>
                </button>

                <button class="btn btn-sm  btn-add"
                    style="position: absolute;bottom: 10px;right:10px;
                    font-size: 17px;    z-index: 10;" id="uploadLogo">
                    <i class="fa fa-camera green" id='logo_upload_cam'
                       style="font-size: 40px" onclick="return false;">
                    </i>
                </button>

                @else
                <h1 id='upload_text' style="color:#fff;margin: 40px"></h1>
                <button class="btn btn-sm  btn-add"
                    style="position: absolute;bottom: 10px;right: 10px;
                    font-size: 17px;    z-index: 10;" id="uploadLogo"
                    onclick="return false;">
                    <i class="fa fa-camera" id='logo_upload_cam'
                       style="font-size: 40px"></i>
                </button>
                @endif
            </div>
            </div>

            <div class="col-md-6">
                <!-- <div class="form-group">
                    <input type="text" name="branch" id="branch"
                        class="form-control" placeholder="Branch"
                        autocomplete="off" value="{{ $location->branch }}" required/>
                </div> -->
                <div class="form-group">
                    <input type="color" name="e_table_header_color" id="e_table_header_color"
                        class="form-control" placeholder="Header color"
                        autocomplete="off" value="{{ $location->e_table_header_color }}" required/>
                </div>
                <div class="form-group">
                    <input type="color" name="e_right_panel_color" id="e_right_panel_color"
                        class="form-control" placeholder="Right color"
                        autocomplete="off" value="{{ $location->e_right_panel_color }}" required/>
                </div>
                <div class="form-group">
                    <input type="color" name="e_bottom_panel_color" id="e_bottom_panel_color"
                        class="form-control" placeholder="Bottom color"
                        autocomplete="off" value="{{ $location->e_bottom_panel_color }}" required/>
                </div>
            </div>
				<input type="hidden" name="location_id" value="{{$location->id}}" />
        </form>
        </div>
        </div>
    </div>
</div>
</div>



<div id="currencyModal-div">
</div>

@endsection

@section('script')

<script>
$(document).ready(function(){
	/*
	$('html, body').css('height',' 95%');
	*/

});

function logout(){
	/*
	alert('logout: sInterval='+ Session['sInterval']);
	closeInterval(Session['sInterval']);

	Session['sInterval'] = '';
	*/

    $('#logoutModal').modal('show');
}
function show_screen_e() {
	$("#locationModal").modal('show');
}
</script>

@endsection
