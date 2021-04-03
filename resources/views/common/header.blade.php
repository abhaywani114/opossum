<nav class="navbar navbar-light bg-light p-0"
	style="background-image:linear-gradient(rgb(38, 8, 94),rgb(86, 49, 210));">

    <div class="navbar-text ml-0 pl-3 align-items-center w-100"
		style="color: white;display:flex">
        <img src="{{ asset('images/small_logo.png') }}" alt=""
			style="object-fit:contain;width: 20px; height: 20px;
			cursor: pointer;"
			srcset="" class="mr-1"

@if (request()->session()->has('ONLY_ONE_HOST'))
			onclick="location.href='{{route('main.view.onehost')}}';">
		<span onclick="location.href='{{route('main.view.onehost')}}';"
@else
			onclick="location.href='{{route('main.view')}}';">
		<span onclick="location.href='{{route('main.view')}}';"
@endif
			style="position:relative;top:2px;cursor: pointer;">
			<b>OPOSsum</b>
		</span>

        <span style="position:relative;top:2px;margin-left:50px">
			<b></b>
		</span>

        @if (isset($pagename))
            @if ($pagename=="screen_d")
            <button style="position:relative;right:10px;top:0"
				class="navbar-nav ml-auto mb-0 mr-0
				poa-closetab-button-sm">
   			<i onclick="window.close()"
				style="position:relative;top:-2px;
				font-size:25px" class="closetab fa fa-times-thin">
			</i>
			</button>
            @endif
        @endif
    </div>
</nav>
