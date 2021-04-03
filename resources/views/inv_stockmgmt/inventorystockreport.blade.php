@extends('common.web')

@section('styles')



<div id="landing-view">
 <style type="text/css">
    .inside_qty{
       margin-top: -3px;
    }
    #prodstockreport tbody td{
        display: table-cell;
        vertical-align: inherit;
        padding-bottom: 2px !important;
        padding-top: 2px !important;
    }
    div.col-sm-3 p {
        margin-bottom: 0px;
    }

element.style {
}
label {
    display: inline-block;
    margin-bottom: 0.5rem;
}
*, *::before, *::after {
    box-sizing: border-box;
}
user agent stylesheet
label {
    cursor: default;
}
.dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter, .dataTables_wrapper .dataTables_info, .dataTables_wrapper .dataTables_processing, .dataTables_wrapper .dataTables_paginate {
    color: #333 !important;
}
 </style>
@endsection

@section('content')
@include('common.header')
@include('common.menubuttons')

@section('content')
<div class="container-fluid">
<div class="row py-2" style="padding-bottom:0px !important">
    <div class="col align-self-center" style="width:80%">
        <h2>@if($stockreport_data->refund_type == 'stockin') Stock In @else Stock Out @endif</h2>
    </div>
    <div class="col-sm-1" style="align-self:center">
      
    </div>
    <div class="col-sm-5" style="align-self:center;float:left;padding-left:0">
<h4 style="margin-bottom:0px;padding-top: 0;line-height:1.5;">Report ID : {{$stockreport_data->document_no}}</h4>
    <p>Location : {{$stockreport_data->location}} </p>
    </div>
    <div class="col-sm-3" style="float: right;">
        <p>Staff Name: {{$stockreport_data->staff_name}}</p>
        <p>Staff ID: {{$stockreport_data->staff_id}}</p>
        <p>Date: <?php echo date('dMy H:i:s',strtotime($stockreport_data->last_update)); ?></p>
    </div>
</div>

<table class="table table-bordered" id="prodstockreport" style="width:100%;">
    <thead class="thead-dark">
    <tr>
        <th style="width:30px;text-align: center;">No</th>
        <th style="width:100px;text-align: center;">Product&nbsp;ID</th>
        <th>Product Name</th>
        <th style="text-align: center;">Colour</th>
        <th>Matrix</th>
        <th style="text-align: center;width:50px">Rack</th>
        <th style="text-align: center; width: 80px;">@if($stockreport_data->refund_type == 'stockin') Qty In @else Qty Out @endif</th>
    </tr>
    </thead>
    <tbody>

    @foreach($stockreport as $key => $value)
		<tr>
            <td style="width:30px;text-align: center;">{{$key+1}}</td>
            <td style="width:100px;text-align: center;">
				{{$value->systemid}}
			</td>
            <td><img src="{{ asset('images/product/'.$value->systemid.'/thumb/'.$value->thumbnail_1) }}" style="height:40px;width:40px;object-fit:contain;margin-right:8px;">{{$value->name}}</td>
            <td style="text-align: center;">@if(!empty($value->color)) <div style="padding:10px 20px; background:{{$value->color}}"></div> @else - @endif</td>
	    <td style="">  -</td>
		<td style="text-align:center;">{{$value->rack_no ?? '-'}}</td>
        <td style="text-align: center;">@if($value->quantity) {{$value->quantity}} @else - @endif</td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>
</div>  
@endsection
@section('script')
 <script type="text/javascript">
      $(document).ready(function () {
        // prodstockreportTable.draw();
            tableinventory =  $('#prodstockreport').DataTable({
              // "order": [[ 3, "desc" ]]
            });
    	@if ($isWarehouse != 1) 
		tableinventory.column(5).visible(false);
	@endif
     });

   </script>

@include('common.footer')
@endsection