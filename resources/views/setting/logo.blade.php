<div style='margin-top:10px' class='row'>
	<div class="col-md-12"
		style='margin-top:10px; color: #27a98a; font-weight: bold;'>
        <h4 style="padding-bottom:5px;
			border-bottom:1px solid #e0e0e0;">
			Company Logo
		</h4>
	</div>
	<div class="col-md-4 upload-area"
		id="uploadfile"
		style='height:200px;background: grey;display:block;
			margin-left: 0.9%;overflow: hidden;'>
		
		<input type="file" name="file" id="file" class="hidden" />

        @if ($this_company->corporate_logo != null)
			<div id="thumbnail_1" class="thmb" style="">
				<img style="object-fit:contain"
					src="/logo/{{$this_company->id}}/{{$this_company->corporate_logo}}"
					width="400px" height="200px">
			</div>

			<button class="redCrabShell"
				style="position: absolute;bottom: 20px;right: 63px;
					padding-bottom: 24px;padding-left: 5px;border: none;"
				onclick='del_logo()'>
				<i class="fa fa-times redCrab"
					style="padding: 0px;">
				</i>
			</button>

			<button class="btn btn-sm  btn-add "
				style="position: absolute;bottom: 10px;right: 10px;
					font-size: 17px" id="uploadLogo">
				<i class="fa fa-camera green"
					id='logo_upload_cam' style="font-size: 40px">
				</i>
			</button>

		@else 
			<h1 id='upload_text' style="color:#fff;margin: 40px"></h1>
			<button class="btn btn-sm  btn-add"
				style="position: absolute;bottom: 10px;
					right: 10px;font-size: 17px" id="uploadLogo">
				<i class="fa fa-camera" id='logo_upload_cam'
					style="font-size: 40px">
				</i>
			</button>
		@endif
	</div>
</div>

<style type="text/css">
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

<script type="text/javascript">

	async function del_logo() {
		await $.ajax({
			url: "{{route('settings.delLogo')}}",
			type: 'get',
			success: function (response) {
				$("#uploadfile div.thmb").remove();
				$("#uploadfile > .redCrabShell").remove();
				$("#logo_upload_cam").toggleClass('green');
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
        url: '{{route('companydetails.saveLogo')}}',
        type: 'post',
        data: formdata,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response){
            addThumbnail(response);
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
    $("#thumbnail_"+num).append('<img src="'+src+'" style="object-fit:contains" width="100%" height="78%">');
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
