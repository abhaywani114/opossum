<script>
$(document).ready(function(){
		$('#userEditModal').modal({backdrop: 'static', keyboard: false})
		// Clear all keys first
		$('#key_2').val = "";
		$('#key_3').val = "";
		$('#key_4').val = "";
		$('#key_5').val = "";
		$('#key_6').val = "";
		$('#key_7').val = "";
		$('#key_8').val = "";
		$('#key_9').val = "";
		$('#key_10').val = "";
		$('#key_11').val = "";
		$('#key_12').val = "";
		$('#key_13').val = "";
		$('#key_14').val = "";
		$('#key_15').val = "";
		$('#key_16').val = "";


		$('#key_1').on('keyup', function(e){
			if($('#key_1').val() != ""){
				$('#key_2').attr("disabled", false)
				$('#key_2').focus()
			}
		})
		$('#key_2').on('keyup', function(e){
			if($('#key_2').val() != ""){
				$('#key_3').attr("disabled", false)
				$('#key_3').focus()
			}
		})
		$('#key_3').on('keyup', function(e){
			if($('#key_3').val() != ""){
				$('#key_4').attr("disabled", false)
				$('#key_4').focus()  
			}
		})
		$('#key_4').on('keyup', function(e){
			if($('#key_4').val() != ""){
				$('#key_5').attr("disabled", false)
				$('#key_5').focus() 
			}
		})
		$('#key_5').on('keyup', function(e){
			if($('#key_5').val() != ""){
				$('#key_6').attr("disabled", false)
				$('#key_6').focus()
			}
		})
		$('#key_6').on('keyup', function(e){
			if($('#key_6').val() != ""){
				$('#key_7').attr("disabled", false)
				$('#key_7').focus()  
			}
		})
		$('#key_7').on('keyup', function(e){
			if($('#key_7').val() != ""){
				$('#key_8').attr("disabled", false)
				$('#key_8').focus() 
			}
		})
		$('#key_8').on('keyup', function(e){
			if($('#key_8').val() != ""){
				$('#key_9').attr("disabled", false)
				$('#key_9').focus()  
			}
		})
		$('#key_9').on('keyup', function(e){
			if($('#key_9').val() != ""){
				$('#key_10').attr("disabled", false)
				$('#key_10').focus()   
			}
		})
		$('#key_10').on('keyup', function(e){
			if($('#key_10').val() != ""){
				$('#key_11').attr("disabled", false)
				$('#key_11').focus() 
			}
		})
		$('#key_11').on('keyup', function(e){
			if($('#key_11').val() != ""){
				$('#key_12').attr("disabled", false)
				$('#key_12').focus() 
			}
		})
		$('#key_12').on('keyup', function(e){
			if($('#key_12').val() != ""){
				$('#key_13').attr("disabled", false)
				$('#key_13').focus()   
			}
		})
		$('#key_13').on('keyup', function(e){
			if($('#key_13').val() != ""){
				$('#key_14').attr("disabled", false)
				$('#key_14').focus()   
			}
		})
		$('#key_14').on('keyup', function(e){
			if($('#key_14').val() != ""){
				$('#key_15').attr("disabled", false)
				$('#key_15').focus()   
			}
		})
		$('#key_15').on('keyup', function(e){
			if($('#key_15').val() != ""){
				$('#key_16').attr("disabled", false)
				$('#key_16').focus()   
			}
		})

	});

	activateLicence = () => {
		/* Here we need to send the collected license to our cloud
		 * server for verification via Ajax */
	
		if($('#key_15').val() != ""){
			license_key = $('#key_1').val() + 
				$('#key_2').val() + 
				$('#key_3').val() + 
				$('#key_4').val() + 
				$('#key_5').val() + 
				$('#key_6').val() + 
				$('#key_7').val() + 
				$('#key_8').val() + 
				$('#key_9').val() + 
				$('#key_10').val() + 
				$('#key_11').val() + 
				$('#key_12').val() + 
				$('#key_13').val() + 
				$('#key_14').val() + 
				$('#key_15').val() + 
				$('#key_16').val()
		} else {
			messageModal("Please fill information correctly.")
			return false;
		}
		
		location_id =  $("#location_id_field").val();
		terminal_id =  $("#terminal_id_field").val();
		merchant_id =  $("#merchant_id_field").val();

		$.ajax({

			@if (empty($isLocationActive) && empty($isTerminalActive))
				url: "{{route('localaccess.interface.licence')}}",
			@elseif (!empty($isLocationActive) && empty($isTerminalActive))
				url: "{{route('localaccess.interface.licence-terminal')}}",
			@endif

			type: 'post',
			headers: {
				'X-CSRF-TOKEN': '{{ csrf_token() }}'
			},
			data: {
				'licensekey': license_key,
				'merchant_id': merchant_id,
				@if (empty($isLocationActive) && empty($isTerminalActive))
					'location_id':	location_id
				@elseif (!empty($isLocationActive) && empty($isTerminalActive))
					'terminal_id': terminal_id
				@endif
			},
			success: function (response) {
				if (response.status == true) {
					messageModal("Congratulations! You have successfully setup Oceania server.")
        			setTimeout(function(){
						window.location.reload()
					},5000);
				} else {
					messageModal(response.error)
				}

				console.log(response);
			},
			error: function (e) {
				console.error(e);
			}
		});
	}
	
	function messageModal(msg)
	{
		$('#login-message').html(msg);
		setInterval(function(){
			 $('#login-message').html('');
		},6000);
	}	
</script>
