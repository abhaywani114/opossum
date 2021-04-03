<div class="modal fade" id="modal" tabindex="-1"
     role="dialog" aria-labelledby="staffNameLabel"
     aria-hidden="true" style="text-align: center;">

    <div class="modal-dialog modal-dialog-centered  mw-75 w-50"
         role="document" style="display: inline-flex;min-width: 43vw !important;">
        <div class="modal-content modal-inside bg-purplelobster"
             style="width: 100%;min-height: 390px;">
            <div class="modal-header" style="">
                <h3 class="text-white"
                    id="statusModalLabel" style="margin-bottom:0">
                    Product Details
                </h3>
            </div>
            <div class="modal-body text-center" style="padding-top: 20px;">
                <form action="#" id="updateProspectFields_edit" method="post"
                      enctype="multipart/form-data"
                      onsubmit="return false;" autocomplete="off">

                    <div class="row" style="padding-top: unset;">
                        <div class="col-md-6" style="padding-right: unset;padding-top: 0px;">
                            <div class="upload-area" id="uploadfile" style='border:
				unset;height:255px;background: grey;display:block;
				margin-left: 0.9%;overflow: hidden;width: 100%;'>
                                <input type="file" name="file" id="file" class="hidden"/>

                                @if (!empty($product_details->photo_1))
                                    <div id="thumbnail_1" class="thmb" style="">
                                        <a href='/{{\App\Http\Controllers\OpenitemController::$IMG_PRODUCT_LINK}}{{$product_details->systemid}}/{{$product_details->photo_1}}'
                                           target="_blank"> <img style="background-color:white;object-fit:contain"
                                                                 src="/{{\App\Http\Controllers\OpenitemController::$IMG_PRODUCT_LINK}}{{$product_details->systemid}}/{{$product_details->photo_1}}"
                                                                 width="100%" height="255px">
                                        </a>
                                    </div>
                                    <a href="javascript:void(0)" class="delete_pic"
                                       style="position: absolute;bottom: 20px;right: 68px;
					border: none; "
                                       onclick='del_picture("{{$product_details->systemid}}");return null'>
                                        <img width="25" src="{{asset('images/redcrab_50x50.png')}}" alt="">
                                    </a>

                                    <button class="btn "
                                            style="position: absolute;bottom: 10px;right:10px;
					font-size: 17px" id="uploadLogo">
                                        <i class="fa fa-camera green" id='logo_upload_cam'
                                           style="font-size: 40px" onclick="return false;">
                                        </i>
                                    </button>

                                @else
                                    <h1 id='upload_text' style="color:#015F0B;margin: 40px"></h1>
                                    <button class="btn"
                                            style="position: absolute;bottom: 10px;right: 10px;
					font-size: 17px" id="uploadLogo"
                                            onclick="return false;">
                                        <i class="fa fa-camera" id='logo_upload_cam'
                                           style="font-size: 40px"></i>
                                    </button>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" name="product_name" id="P_name"
                                       class="form-control" placeholder="Product Name"
                                       autocomplete="off"
                                       value="{{$product_details->name}}" required/>
                            </div>
						 
						 </div>
                    </div>

				   
					   <input type="hidden" name="systemid"
                           id="systemid" value="{{$product_details->systemid}}">
                           <input type="hidden" name="product_id"
                           id="product_id" value="{{$product_details->id}}">
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="fillFields" tabindex="-1" role="dialog">
    <div class="modal-dialog  modal-dialog-centered mw-75 w-50">

        <!-- Modal content-->
        <div class="modal-content  modal-inside bg-purplelobster">
            <div class="modal-header" style="border:none;">&nbsp;</div>
            <div class="modal-body text-center">
                <h5 style="mb-0" id="return_data">
                    Please fill all fields
                </h5>
            </div>
            <div class="modal-footer" style="border:none;">&nbsp;</div>
        </div>

    </div>
</div>

<script type="text/javascript">
    var FORM_HAS_CHANGED = false;

    $('#modal').on('hidden.bs.modal', function (e) {
        if (FORM_HAS_CHANGED) {
            updateProduct();
        }
    });

    $("input, select, textarea, .delete_pic, #uploadLogo").change(function(){
        FORM_HAS_CHANGED = true;
    });

    function updateProduct() {
        const form = $('#updateProspectFields_edit')[0];
        console.log('updatecustom');
//		console.log(form)
        const formData = new FormData(form);
        $.ajax({
            url: "{{route('openitem.updatecustom')}}",
            type: "POST",
            enctype: 'multipart/form-data',
            processData: false,  // Important!
            contentType: false,
            cache: false,
            data: formData,
            success: function (response) {
                console.log("response", response);

                  payload = {
                    id: $('#product_id').val()

                };
                localStorage.removeItem('update_product');
                localStorage.setItem("update_product",JSON.stringify(payload));

                openitemtable.ajax.reload();
                $("#modal").modal('hide');
                $("#return_data").html("Product updated successfully");
                $("#fillFields").modal('show');
                setTimeout(function () {
                    $('#fillFields').modal('hide');
                }, 2000);
                //$("#productResponce").html(response);

            }, error: function (e) {
                $("#return_data").html("Please fill all fields");
                $("#fillFields").modal('show');
                setTimeout(function () {
                    $('#fillFields').modal('hide');
                }, 2000);
                //console.log(e.message)
            }
        });
    }
</script>

<style type="text/css">
    .upload-area {
        width: 70%;
        border: 2px solid lightgray;
        border-radius: 3px;
        margin: 0 auto;
        text-align: center;
        overflow: auto;
    }

    .upload-area:hover {
        cursor: pointer;
    }

    .upload-area h1 {
        text-align: center;
        font-weight: normal;
        font-family: sans-serif;
        line-height: 50px;
        color: darkslategray;
    }

    #file {
        display: none;
    }

    /* Thumbnail */
    .thumbnail {
        width: 180px;
        height: 185px;
        padding: 4px;
        border: 2px solid lightgray;
        border-radius: 3px;
        float: left;
    }

    .size {
        font-size: 17px;
        color: #fff;
    }

    #uploadfile > button > i {
        color: #fff
    }

    .green {
        color: #28a745 !important;
    }
</style>
<script type="text/javascript">
    $("#selectcategory").on("change paste keyup", function () {
        $('#selectproduct option:first').prop('selected', true);
        $('#selectsubcategory option:first').prop('selected', true);

        if ($('#selectcategory').val() != 'cat') {
            enable_subcategories();
        } else {
            disable_all();
        }
    });


    $("#selectsubcategory").on("change paste keyup", function () {
        $('#selectproduct option:first').prop('selected', true);

        if ($('#selectsubcategory').val() != 'subcat') {
            enable_products();
        } else {
            disable_products();
        }
    });
    @if (empty($product_details->prdcategory_id))
    disable_all();

    @endif

    function disable_all() {
        $('#add_subcategory').attr("disabled", "on");
        $('#selectsubcategory').attr("disabled", "on");
        $('#add_Product').attr("disabled", "on");
        $('#selectproduct').attr("disabled", "on");
    }

    function enable_subcategories() {
        $('#add_subcategory').removeAttr("disabled");
        $('#selectsubcategory').removeAttr("disabled");
        get_dropDown('#selectsubcategory', 'subcat');
    }

    function disable_products() {
        $('#add_Product').attr("disabled", "on");
        $('#selectproduct').attr("disabled", "on");
    }

    function enable_products() {
        $('#add_Product').removeAttr("disabled");
        $('#selectproduct').removeAttr("disabled");
        get_dropDown('#selectproduct', 'product');
    }

            @if (!empty($matrix_ids))
    var marone_ids = [{{implode(',',$matrix_ids)}}]
            @else
    var marone_ids = [];

    @endif

    function get_dropDown(target, option) {
        let dropdown = $(target);

        dropdown.empty();

        if (option == 'subcat') {
            key = $('#selectcategory').val().toString();
            dropdown.append('<option class="form-control" value="subcat">Select Subcategory</option>');
        } else if (option == 'product') {
            key = $('#selectsubcategory').val().toString();
            dropdown.append('<option class="form-control" value="product">Select Product</option>');
        }

        dropdown.prop('selectedIndex', 0);


        const url = "{{route('openitem.get_dropDown',['OPTION'=>'OPTIONV','KEY'=>'KEYV'])}}".replace('OPTIONV', option).replace('KEYV', key);
        console.log("url", url);
        $.getJSON(url, function (data) {
            console.log(data);
            $.each(data, function (key, entry) {
                console.log("1");
                if (option == 'subcat') {
                    console.log("2");

                    if (entry.is_matrix == 1) {
                        console.log("3");
                        dropdown.append($('<option class="form-control" style="color: lawngreen !important;"></option>').attr('value', entry.id).text(entry.name));

                    } else if (marone_ids.includes(entry.id)) {

                        dropdown.append($('<option class="form-control" style="color: #7d2626;font-weight:500"></option>').attr('value', entry.id).text(entry.name));
                    } else {
                        console.log("4");
                        dropdown.append($('<option class="form-control"></option>').attr('value', entry.id).text(entry.name));
                    }

                } else {
                    console.log("5");
                    dropdown.append($('<option class="form-control"></option>').attr('value', entry.id).text(entry.name));
                }

            })
        });
    }


    //Image upload functions

    async function del_picture(systemid) {
        await $.ajax({
            url: "{{route('openitem.delPicture')}}",
            type: 'post',
            data: {
                "systemid": systemid
            },
            success: function (response) {
                $("#uploadfile div.thmb").remove();
                $("#uploadfile > .redCrabShell").remove();
                $("#logo_upload_cam").toggleClass('green');
                
            },
            error: function (e) {
                console.log('Error:' + e);
            }
        });
        return false
    }

    $(function () {
        // preventing page from redirecting
        $("html").on("dragover", function (e) {
            e.preventDefault();
            e.stopPropagation();
            $("h1").text("Drag here");
        });

        $("html").on("drop", function (e) {
            e.preventDefault();
            e.stopPropagation();
        });

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
        $("#uploadLogo").click(function () {
            $("#file").click();
        });

        // file selected
        $("#file").change(function () {
            var fd = new FormData();

            var files = $('#file')[0].files[0];

            fd.append('product_id', $('#systemid').val());
            fd.append('file', files);

            uploadData(fd);
        });
    });

    // Sending AJAX request and upload file
    async function uploadData(formdata) {
        await del_picture()
        $.ajax({
            url: '{{route('openitem.savePicture')}}',
            type: 'post',
            data: formdata,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (response) {
                addThumbnail(response);
                

            }
        });
    }

    // Added thumbnail
    function addThumbnail(data) {
        $("#uploadfile h1").remove();
        var len = $("#uploadfile div.thmb").remove();

        var num = Number(len);
        num = num + 1;

        var name = data.name;
        var src = data.src;
        console.log(data);

        // Creating an thumbnail
        $("#uploadfile").append('<div id="thumbnail_' + num + '" class="thmb" style=""></div>');
        $("#thumbnail_" + num).append('<img src="' + src + '" style="background-color:white;object-fit:contain" width="100%" height="255px">');
        $("#uploadfile").append("<button class='redCrabShell' style='position: absolute;bottom: 20px;right: 63px;padding-bottom: 24px;padding-left: 5px;border: none;'  onclick='del_picture(\"{{$product_details->systemid}}\")'><i class='fa fa-times redCrab'></i></button>")
        $("#logo_upload_cam").addClass('green');
    }
</script>
