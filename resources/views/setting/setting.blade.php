@extends('common.web')
@section('styles')
<style>
    .ui-timepicker-wrapper {
        width: 7.5em !important;
    }
.upload-area{
    width: 70%;
    border: 2px solid lightgray;
    border-radius: 3px;
    margin: 0 auto;
    text-align: center;
    overflow: auto;
}

.upload-area:hover{
    cursor: pointer;
}

.upload-area h1{
    text-align: center;
    font-weight: normal;
    font-family: sans-serif;
    line-height: 50px;
    color: darkslategray;
}

#file{
    display: none;
}

/* Thumbnail */
.thumbnail{
width: 180px;
    height: 185px;
    padding: 4px;
    border: 2px solid lightgray;
    border-radius: 3px;
    float: left;
}

.size{
    font-size:17px;
    color: #fff;
}
#uploadfile > button > i {color: #fff}
.green {
    color: #28a745 !important;
}

</style>
@endsection
@section('content')
@include('common.header')
@include('common.menubuttons')

<div class="container-fluid">
	<div class="align-items-center d-flex" style="height:75px">
		<h2 class="mb-0" style="color:black">Setting</h2>
	</div>
	<div class="align-items-center">
		<h3 class="mt-2">Company Details
			<button type="button" id="edit-save" style="width: 70px !important"
				class="btn btn-prawn float-right">Edit</button>
		</h3>
	</div>
    <hr>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row" style="margin-bottom: 5px;">
                    <label for="inputPassword" class="col-sm-4 col-form-label">Company Name</label>
                    <div class="col-sm-8">
                    <input type="text" class="form-control company-details" id="name" value="{{$company->name ?? ''}}" placeholder="Company Name">
                    </div>
                  </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row" style="margin-bottom: 5px;">
                    <label for="inputPassword"
						class="col-sm-4 col-form-label">Business Reg. No.
					</label>
                    <div class="col-sm-8">
						<input type="text" class="form-control company-details"
							id="business_reg_no"
							value="{{$company->business_reg_no??''}}"
							placeholder="Business Reg. No.">
                    </div>
                  </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row" style="margin-bottom: 5px;">
                    <label for="inputPassword" class="col-sm-4 col-form-label">GST/SST/VAT No</label>
                    <div class="col-sm-8">
                    <input type="text" class="form-control company-details" id="gst_vat_sst" value="{{$company->gst_vat_sst??''}}" placeholder="GST/SST/VAT No">
                    </div>
                  </div>
            </div>

        </div>
        @if(!$companydirector->isEmpty())
        @foreach($companydirector as $row)
        <div class="row director-div{{$row->id??''}}" @if($loop->last) id="director-row" @endif>
            <div class="col-md-6">
                <div class="form-group row" style="margin-bottom: 5px;">
                    <label for="inputPassword" class="col-sm-4 col-form-label">Director</label>
                    <div class="col-sm-8">
                    <input type="text" class="form-control company-details" id="director_name{{$row->id??''}}" value="{{$row->name??''}}" placeholder="Director">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row" style="margin-bottom: 5px;">
                    <label for="inputPassword"
						class="text-right col-sm-4 col-form-label">NRIC
					</label>
                    <div class="col-sm-7">
						<input type="text" class="form-control company-details"
							id="director_nric{{$row->id??''}}"
							value="{{$row->nric??''}}"
							placeholder="NRIC">
                    </div>

                    <div class="col-sm-1">
                        @if(!$loop->first)
                        <div id="dlt-director" onclick="dltdirector_old({{$row->id??''}})"
                        class="btn company-add-button float-right" style="float: left">
                        <img class=""
                            src="/images/redcrab_50x50.png"
                            style="width:25px;height:25px;cursor:pointer"/>
                            </div>
                       @else
                        <div id="add-director"

                        class="btn float-right company-add-button">
                        <img class=""
                            src="/images/greencrab_50x50.png"
                            style="width:25px;height:25px;cursor:pointer"/>
                            </div>
                            @endif
                    </div>

                  </div>
            </div>
        </div>
        @endforeach
        @else
        <div class="row" id="director-row">
            <div class="col-md-6">
                <div class="form-group row" style="margin-bottom: 5px;">
                    <label for="inputPassword" class="col-sm-4 col-form-label">Director</label>
                    <div class="col-sm-8">
                    <input type="text" class="form-control company-details" id="director_name1" value="" placeholder="Director">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row" style="margin-bottom: 5px;">
                    <label for="inputPassword"
						class="text-right col-sm-4 col-form-label">NRIC
					</label>
                    <div class="col-sm-7">
						<input type="text" class="form-control company-details"
							id="director_nric1"
							value=""
							placeholder="NRIC">
                    </div>
                    <div class="col-sm-1">

                        <div id="add-director"
                        class="btn float-right company-add-button">
                        <img class=""
                            src="/images/greencrab_50x50.png"
                            style="width:25px;height:25px;cursor:pointer"/>
                            </div>

					</div>
                  </div>
            </div>
        </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="form-group row" style="margin-bottom: 5px;">
                    <label for="inputPassword" class="col-sm-2 col-form-label">Company Address</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control company-details" id="office_address" value="{{$company->office_address??''}}" placeholder="Company Address">
                    </div>
                  </div>
            </div>
        </div>
        @if(!$companycontact->isEmpty())
        @foreach($companycontact as $rowc)
        <div class="row contact-div{{$rowc->id}}" @if($loop->last) id="contact-row" @endif>
            <div class="col-md-6">
                <div class="form-group row" style="margin-bottom: 5px;">
                    <label for="inputPassword" class="col-sm-4 col-form-label">Contact Person</label>
                    <div class="col-sm-8">
                    <input type="text" id="contact_person{{$rowc->id}}" value="{{$rowc->name}}" class="form-control company-details" placeholder="Name">
                    </div>
                  </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row" style="margin-bottom: 5px;">
                    <label for="inputPassword"
						class="text-right col-sm-4 col-form-label">Mobile
					</label>
                    <div class="col-sm-7">
						<input type="text" id="mobile{{$rowc->id}}" value="{{$rowc->mobile}}" class="form-control company-details"
							placeholder="Mobile"/>
                    </div>

                    <div class="col-sm-1">
                        @if(!$loop->first)
                        <div id="dlt-director" onclick="dltcontact_old({{$rowc->id??''}})"
                        class="btn company-add-button float-right">
                        <img class=""
                            src="/images/redcrab_50x50.png"
                            style="width:25px;height:25px;cursor:pointer"/>
                            </div>
                        @else
						<div id="add-contact"
							class="btn float-right company-add-button">
							<img class="add-button"
								src="/images/greencrab_50x50.png"
								style="width:25px;height:25px;cursor:pointer"/>
                        </div>
                        @endif
					</div>

                  </div>
            </div>
        </div>
        @endforeach
        @else
        <div class="row" id="contact-row">
            <div class="col-md-6">
                <div class="form-group row" style="margin-bottom: 5px;">
                    <label for="inputPassword" class="col-sm-4 col-form-label">Contact Person</label>
                    <div class="col-sm-8">
                      <input type="text" id="contact_person1" class="form-control company-details" placeholder="Name">
                    </div>
                  </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row" style="margin-bottom: 5px;">
                    <label for="inputPassword"
						class="text-right col-sm-4 col-form-label">Mobile
					</label>
                    <div class="col-sm-7">
						<input type="text" id="mobile1" class="form-control company-details"
							placeholder="Mobile"/>
                    </div>
                    <div class="col-sm-1">

						<div id="add-contact"
							class="btn float-right company-add-button">
							<img class="add-button"
								src="/images/greencrab_50x50.png"
								style="width:25px;height:25px;cursor:pointer"/>
						</div>
					</div>

                  </div>
            </div>
        </div>
        @endif
    <h3>Company Logo</h3>
    <hr>
	<div style='margin-top:10px' class='row'>

	<div class="col-md-4 upload-area" id="uploadfile"
		style='height:200px;background: grey;display:block;
		margin-left: 0.9%;overflow: hidden;'>

		<input type="file" name="file" id="file" class="hidden" />

		@if ($company->corporate_logo??"" != null)
			<div id="thumbnail_1" class="thmb" style="">
				<img style="object-fit:contain"
				src="{{ asset('images/company/'.$company->id.'/corporate_logo/'.$company->corporate_logo) }}" width="400px" height="200px">
			</div>

			<button class="redCrabShell"
				style="position: absolute;bottom: 20px;right: 63px;
				padding-bottom: 24px;padding-left: 5px;border: none;"
				onclick='del_logo()'>
				<i class="fa fa-times redCrab" style="padding-top: 2.5px;"></i>
			</button>

			<button class="btn btn-sm"
				style="position: absolute;bottom: 10px;right: 10px;
				font-size: 17px" id="uploadLogo">
				<i class="fa fa-camera green" id='logo_upload_cam'
					style="font-size: 40px">
				</i>
			</button>
		@else
			<h1 id='upload_text' style="color:#fff;margin: 40px"></h1>
			<button class="btn btn-sm"
				style="position: absolute;bottom: 10px;right: 10px;
				font-size: 17px" id="uploadLogo">
				<i class="fa fa-camera" id='logo_upload_cam'
					style="font-size: 40px">
				</i>
			</button>
        @endif
	</div>
</div><br>

    <h3>Location
	<!--
	<button type="button" id="edit-save-location" style="width: 70px !important" class="btn btn-prawn float-right">Edit</button>
	-->
	</h3>
    <hr>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
                <label for="inputPassword" class="col-sm-4 col-form-label">Location Name</label>
                <div class="col-sm-8">
                <input type="text" class="form-control company-details-location" id="location-name" value="{{$location->name??''}}" placeholder="Name">
                </div>
              </div>
        </div>
    </div>

    <h3>Operation Hour<button type="button" id="edit-save-time" style="width: 70px !important" class="btn btn-prawn float-right">Edit</button></h3>
    <hr>

    <div class="row">
		<div class="col-md-2">
			<div class="form-group row">
				<label for="inputPassword" class="col-sm-4 col-form-label">Start</label>
				<div class="col-sm-8">
				  <input type="text" class="form-control company-details-time" id="time-start" value="{{$location->start_work??''}}" placeholder="Start">
				</div>
			  </div>
		</div>
		<div class="col-md-2">
			<div class="form-group row">
				<label for="inputPassword" class="col-sm-4 col-form-label">-End</label>
				<div class="col-sm-8">
				  <input type="text" class="form-control company-details-time" id="time-end" value="{{$location->close_work??''}}" placeholder="End">
				</div>
			  </div>
		</div>
    </div><br>
</div>

@endsection
@section('script')

<script>
var d_count = {{$row->id??"1"}}+1;
var cp_count = {{$rowc->id??"1"}}+1;

$( document ).ready(function() {
	$('#time-start').timepicker({ 'timeFormat': 'H:i','step': 5});
	$('#time-end').timepicker({ 'timeFormat': 'H:i','step': 5 });
	// $('#ui-timepicker-wrapper').css('top', inp.offset().top + inp.outerHeight());
	@if($company['msg'])
		messageModal("{{$company['msg']}}");
	@endif
	$(".company-details").prop('disabled', true);
	$(".company-details-time").prop('disabled', true);
	$(".company-add-button").hide();
	$(".company-details-location").prop('disabled', true);
});


$("#edit-save").click(function(){
	var buttontype = $("#edit-save").html();
	if(buttontype == "Edit"){
		$(".company-add-button").show();
		$("#edit-save").html('Save');
		$(".company-details").prop('disabled', false);

	}else{
		$("#edit-save").html('Edit');
		$(".company-add-button").hide();
		$(".company-details").prop('disabled', true);
		var cname = $('#name').val();
		var business_reg_no = $('#business_reg_no').val();
		var gst_vat_sst = $('#gst_vat_sst').val();
		var director_new = [];
		var director_old = [];
		var contact_new = [];
		var contact_old = [];
	   // var director_nric = $('#director_nric').val();
		var office_address = $('#office_address').val();
		for(var i = d_count; i> {{$row->id??"0"}};i--){
			var director = $('#director_name'+i).val();
			var director_nric = $('#director_nric'+i).val();
			 director_new.push({name: director,nric:director_nric});
		}

		for(var i = {{$row->id??"0"}}; i>= 1;i--){
			var director = $('#director_name'+i).val();
			var director_nric = $('#director_nric'+i).val();
			 director_old.push({id:i,name: director,nric:director_nric});
		}

		for(var i = cp_count; i> {{$rowc->id??"0"}};i--){
			var name = $('#contact_person'+i).val();
			var mobile = $('#mobile'+i).val();
			contact_new.push({name: name,mobile:mobile});
		}

		for(var i = {{$rowc->id??"0"}}; i>= 1;i--){
			var name = $('#contact_person'+i).val();
			var mobile = $('#mobile'+i).val();
			contact_old.push({id:i,name: name,mobile:mobile});
		}

		$.ajax({
			url: "{{route('screen.d.setting.saveCompanydetails')}}",
			type: 'post',
			headers: {
				'X-CSRF-TOKEN': '{{ csrf_token() }}'
			},
			data: {
				"name":cname,
				"business_reg_no":business_reg_no,"gst_vat_sst":gst_vat_sst,
				"director_new":director_new,"director_nric":director_nric,
				"office_address":office_address,"director_old":director_old,
				"contact_new":contact_new,"contact_old":contact_old
			},
			dataType: 'json',
			success: function(response){
				messageModal("Company details saved successfully");
			}
		});
	}
});


$("#edit-save-location").click(function(){
	var buttontype = $("#edit-save-location").html();
	if(buttontype == "Edit"){
		$("#edit-save-location").html('Save');
		$(".company-details-location").prop('disabled', false);

	}else{
		$("#edit-save-location").html('Edit');
		$(".company-details-location").prop('disabled', true);
		var location_name = $('#location-name').val();
		var data =
		$.ajax({
			url: "{{route('screen.d.setting.saveLocationName')}}",
			type: 'post',
			headers: {
				'X-CSRF-TOKEN': '{{ csrf_token() }}'
			},
			data: {
				"name":location_name
			},
			dataType: 'json',
			success: function(response){
				messageModal("Location details saved successfully");
			}
		});
	}
});


$("#edit-save-time").click(function(){
	var buttontype = $("#edit-save-time").html();
	if(buttontype == "Edit"){
		$("#edit-save-time").html('Save');
		$(".company-details-time").prop('disabled', false);

	}else{
		$("#edit-save-time").html('Edit');
		$(".company-details-time").prop('disabled', true);
		var time_start = $('#time-start').val();
		var time_end = $('#time-end').val();
		var data =
		$.ajax({
			url: "{{route('screen.d.setting.savetime')}}",
			type: 'post',
			headers: {
				'X-CSRF-TOKEN': '{{ csrf_token() }}'
			},
			data: {
				"start_work":time_start,
				"close_work":time_end
			},
			dataType: 'json',
			success: function(response){
				messageModal("Time details saved successfully");
			}
		});
	}
});


function dltdirector_old(d_id){
	$.ajax({
		url: "{{route('screen.d.setting.dltdirector')}}",
		type: 'post',
		headers: {
			'X-CSRF-TOKEN': '{{ csrf_token() }}'
		},
		data: {
			"d_id":d_id,
		},
		dataType: 'json',
		success: function(response){
			$(".director-div"+d_id).hide();
		}
	});
}


function dltcontact_old(c_id){
	$.ajax({
		url: "{{route('screen.d.setting.dltcontact')}}",
		type: 'post',
		headers: {
			'X-CSRF-TOKEN': '{{ csrf_token() }}'
		},
		data: {
			"c_id":c_id,
		},
		dataType: 'json',
		success: function(response){
			$(".contact-div"+c_id).hide();
		}
	});
};


function dltcontact_new(c_id){
	$(".contact-div"+c_id).remove();
}


function dltdirector_new(d_id){
	$(".director-div"+d_id).remove();
}


$('#add-director').click(function(){
    $("#director-row").after(`<div class="row director-div`+d_count+`">
		<div class="col-md-6">
			<div class="form-group row" style="margin-bottom: 5px;">
				<label for="inputPassword" class="col-sm-4 col-form-label">Director</label>
				<div class="col-sm-8">
				  <input type="text" class="form-control company-details" id="director_name`+d_count+`" value="" placeholder="Director" >
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group row" style="margin-bottom: 5px;">
				<label for="inputPassword"
					class="text-right col-sm-4 col-form-label">NRIC
				</label>
				<div class="col-sm-7">
					<input type="text" class="form-control company-details"
						id="director_nric`+d_count+`"
						value=""
						placeholder="NRIC" >
				</div>
				<div class="col-sm-1">
				<div id="dlt-director" onclick="dltdirector_new(`+d_count+`)"
					class="btn company-add-button float-right">
					<img class=""
						src="/images/redcrab_50x50.png"
						style="width:25px;height:25px;cursor:pointer"/>
						</div>
				</div
			  </div>
		</div>
	</div>`);
	d_count++;
});


$('#add-contact').click(function(){
    $("#contact-row").after(`<div class="row contact-div`+cp_count+`">
		<div class="col-md-6">
			<div class="form-group row" style="margin-bottom: 5px;">
				<label for="inputPassword" class="col-sm-4 col-form-label">Contact Person</label>
				<div class="col-sm-8">
				  <input type="text" id="contact_person`+cp_count+`" class="form-control company-details" placeholder="Name" >
				</div>
			  </div>
		</div>
		<div class="col-md-6">
			<div class="form-group row" style="margin-bottom: 5px;">
				<label for="inputPassword"
					class="text-right col-sm-4 col-form-label">Mobile
				</label>
				<div class="col-sm-7">
					<input type="text" id="mobile`+cp_count+`" class="form-control company-details"
						placeholder="Mobile" >
				</div>
				<div class="col-sm-1">
				<div id="dlt-director" onclick="dltcontact_new(`+cp_count+`)"
					class="btn company-add-button float-right">
					<img class=""
						src="/images/redcrab_50x50.png"
						style="width:25px;height:25px;cursor:pointer"/>
						</div>
				</div
			  </div>
		</div>
	</div>`);
	cp_count++;
});


async function del_logo() {
	await $.ajax({
		url: "{{route('settings.delLogo')}}",
		type: 'get',
		success: function (response) {
			$("#uploadfile div.thmb").remove();
			$("#uploadfile > .redCrabShell").remove();
			$("#logo_upload_cam").toggleClass('green');
			messageModal("Logo delete successfully");
		},
		error: function (e) {
			console.log('Error:'+e);
		}
	});
}


$(function() {
    // preventing page from redirecting
    $("html").on("dragover", function(e) {
        e.preventDefault();
        e.stopPropagation();
        $("h1").text("Drag here");
    });

    $("html").on("drop", function(e) { e.preventDefault(); e.stopPropagation(); });

    // Drag enter
    $('.upload-area').on('dragenter', function (e) {
        e.stopPropagation();
        e.preventDefault();
        $("h1").text("Drop");
    });

    // Drag over
    $('.upload-area').on('dragover', function (e) {
        e.stopPropagation();
        e.preventDefault();
        $("upload_text").text("Drop");
    });

    // Drop
    $('.upload-area').on('drop', function (e) {
        e.stopPropagation();
        e.preventDefault();

        $("#upload_text").text("Upload");

        var file = e.originalEvent.dataTransfer.files;
        var fd = new FormData();

        fd.append('file', file[0]);
        uploadData(fd);
    });

    // Open file selector on div click
    $("#uploadLogo").click(function(){
        $("#file").click();
    });

    // file selected
    $("#file").change(function(){
        var fd = new FormData();
        var files = $('#file')[0].files[0];
        fd.append('file',files);
        uploadData(fd);
    });
});


// Sending AJAX request and upload file
async function uploadData(formdata){
	await del_logo();
	$.ajax({
        url: "{{route('screen.d.setting.savelogo')}}",
        headers: {
			'X-CSRF-TOKEN': '{{ csrf_token() }}'
		},
        type: 'post',
        data: formdata,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response){
            addThumbnail(response);
            messageModal("Logo update successfully");
        },
        error: function (e) {
			console.log('Error:'+e);
		}
    });
}


// Added thumbnail
function addThumbnail(data){

    $("#uploadfile h1").remove();
    var len = $("#uploadfile div.thmb").remove();

    var num = Number(len);
    num = num + 1;

    var name = data.name;
    var size = convertSize(data.size);

    var src = data.src;

    // Creating an thumbnail
    $("#uploadfile").append('<div id="thumbnail_'+num+'" class="thmb" style=""></div>');
    $("#thumbnail_"+num).append('<img src="'+src+'" style="object-fit:contains" width="auto" height="200px">');
    $("#uploadfile").append("<button class='redCrabShell' style='position: absolute;bottom: 20px;right: 63px;padding-bottom: 24px;padding-left: 5px;border: none;'  onclick='del_logo()'><i class='fa fa-times redCrab'></i></button>")
    $("#logo_upload_cam").addClass('green');
}

// Bytes conversion
function convertSize(size) {
    var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    if (size == 0) return '0 Byte';
    var i = parseInt(Math.floor(Math.log(size) / Math.log(1024)));
    return Math.round(size / Math.pow(1024, i), 2) + ' ' + sizes[i];
}

</script>
@include('common.footer')
@endsection
