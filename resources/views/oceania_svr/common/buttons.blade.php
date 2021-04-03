<div class="container-fluid">
<div class="row">
	<div class="col-md-12 mt-1 mb-1 pt-1 bg-light"
		style="padding-left:30px">

		<button class="btn poa-bluecrab-button mb-1"
			style="float: left !important;"
			id="bluecrab_btn" onclick="window.open('{{route('screen.d.oceania')}}')">
			<i style="top:2px;margin-left:-2px;font-size:48px"
				class="far fa-circle">
			</i>
		</button>
		<button class="btn btn-success screend-button
			bg-server-users"
			onclick="(loadView('{{route('user_management.landing')}}'))"
			style="margin-left:5px;font-size: 12px">
			<span class="ml-0 mr-0 pl-0 pr-0">
				User
			</span>
		</button>
		<button class="btn btn-success screend-button-lg
			bg-server-opossum-term"
			onclick="(loadView('{{route('terminal.landing')}}'))"
			style="font-size: 12px">OPOSsum Terminal
		</button>
		<button class="btn btn-success screend-button-lg
			bg-server-opossum-term"
			onclick="loadView('{{route('outdoor_payment.landing')}}')"
			style="font-size: 12px">Outdoor Payment<br>Terminal
		</button>
	</div>
</div>
