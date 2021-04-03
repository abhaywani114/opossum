<nav class="navbar navbar-light bg-light p-0" style="">

    <div class="navbar-text ml-0 pl-3 align-items-center w-100 bg-server-header"
		style="color: white;display:flex">
        <img src="{{ asset('images/small_logo.png') }}" alt=""
			style="object-fit:contain;width: 20px; height: 20px;
			cursor: pointer;"
			srcset="" class="mr-1">

		<span onclick="location.href='{{route('main.view')}}';"
			style="position:relative;top:1px;cursor: pointer;">
			<b>Oceania</b>
		</span>

        <span style="position:relative;top:2px;margin-left:50px">
			<b></b>
		</span>

		<span style="position:relative;top:2px;margin-left:auto;left:0;">
			Hardware Address:&nbsp;
			{{$verifyHardware->hw_addr ?? null }}
		</span>

		<span style="position:relative;top:0;margin-left:auto;
			@if ( request()->route()->getName() != 'main.view') margin-right:60px; @else margin-right:15px; @endif">
			<b>{{Auth::User()->fullname ?? ''}}</b>
		</span>

		@if ( request()->route()->getName() != 'main.view')
		<button style="position:relative;right:10px;top:-2px"
			class="navbar-nav mb-0 mr-0
			poa-closetab-button-sm">
		<i onclick="window.close()"
			style="font-size:25px"
			class="closetab fa fa-times-thin">
		</i>
		</button>
		@endif
	</div>
</nav>
