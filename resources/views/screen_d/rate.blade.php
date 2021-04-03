@php
	// Setting default to inclusive
	$terminal->mode = 'inclusive';
@endphp

<div class="row">
	<div class="col-md-12 rate_mg d-flex">
		<div id="inclusive_rate" onclick="select_rate_type('inclusive')"
			class="m-0"
			style="width:80px;font-weight:bold;
			@if(isset($terminal->mode))
				@if($terminal->mode == 'inclusive')
					color:#34dabb;
				@else
					cursor: pointer;
				@endif
			@endif">Inclusive</div> |

		<!--
		<div id="exclusive_rate" onclick="select_rate_type('exclusive')"
			class="ml-3"
			style="width:80px;font-weight:bold;
			@if(isset($terminal->mode))
				@if($terminal->mode == 'exclusive')
					color:#34dabb;
				@else
					cursor: pointer;
				@endif
			@endif">Exclusive</div>
		-->

		<p id="select_rate_type" style="display:none">
            {{$terminal->mode??""}}
            <input type="hidden" id="old_mode"  value="{{$terminal->mode??""}}">
            <input type="hidden" id="old_taxtype"  value="{{$terminal->taxtype??""}}">
		</p>
	</div>

	<div class="mt-2 col-md-12 rate_mg d-flex align-items-center">
	<div id="idofSST" class="rateval" onclick="select_text_type('sst')"
			style="width:40px;font-weight:bold;
			@if(isset($terminal->taxtype))
				@if($terminal->taxtype == 'sst')
					color:#34dabb;
				@else
					cursor: pointer;
				@endif
			@endif">SST</div>|

		<div id="idofGST" class="ml-2 rateval" onclick="select_text_type('gst')"
			style="width:40px;font-weight:bold;
			@if(isset($terminal->taxtype))
				@if($terminal->taxtype == 'gst')
					color:#34dabb;
				@else
					cursor: pointer;
				@endif
			@endif">GST</div> |

		<div id="idofVAT" class="ml-2 rateval" onclick="select_text_type('vat')"
			style="width:40px;font-weight:bold;
			@if(isset($terminal->taxtype))
				@if($terminal->taxtype == 'vat')
					color:#34dabb;
				@else
					cursor: pointer;
				@endif
			@endif">VAT</div>

        <div style="float:right;text-align: center;">
            <input type="hidden" id="old_sst_gst_vat_field"  value="{{number_format($terminal->tax_percent,2)}}">
			<input type="text" id="sst_gst_vat_field"
			   name="" size="7"
			   class="text-center"
			   style="width:80px;border-radius:5px;"
			   value="{{number_format($terminal->tax_percent,2)}}"> %
			<input type="hidden" id="sst_gst_vat_field_buffer" />
		</div>
	</div>
</div>


<script>
filter_price('#sst_gst_vat_field','#sst_gst_vat_field_buffer'); 
function filter_price(target_field,buffer_in) {
	$(target_field).off();
	$(target_field).on( "keydown", function( event ) {
		event.preventDefault()
		if (event.keyCode == 8) {
			$(buffer_in).val('')
			$(target_field).val('')
			return null
		}	
		if (isNaN(event.key) ||
		$.inArray( event.keyCode, [13,38,40,37,39] ) !== -1 ||
		event.keyCode == 13) {
			if ($(buffer_in).val() != '') {
				$(target_field).val(atm_money(parseInt($(buffer_in).val())))
			} else {
				$(target_field).val('')
			}
			return null;
		}

		const input =  event.key;
		old_val = $(buffer_in).val()
		if (old_val === '0.00') {
			$(buffer_in).val('')
			$(target_field).val('')
			old_val = ''
		}
		$(buffer_in).val(''+old_val+input)
		$(target_field).val(atm_money(parseInt($(buffer_in).val())))
		});
    }


	function atm_money(num) {
		if (num.toString().length == 1) {
			return '0.0' + num.toString()
		} else if (num.toString().length == 2) {
			return '0.' + num.toString()
		} else if (num.toString().length == 3) {
			return  num.toString()[0] + '.' + num.toString()[1] +
				num.toString()[2];
		} else if (num.toString().length >= 4) {
			return num.toString().slice(0, (num.toString().length - 2)) +
				'.' + num.toString()[(num.toString().length - 2)] +
				num.toString()[(num.toString().length - 1)];
		}
    }



</script>
