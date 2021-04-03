<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
	    <meta charset="utf-8">
	    <meta name="viewport" http-equiv="Content-type" content="width=device-width, initial-scale=1">

	    <!-- CSRF Token -->
	    <meta name="csrf-token" content="{{ csrf_token() }}">

	    <title>{{ config('app.name', 'Oceania') }}</title>
	    <link rel="icon" type="image/png"
			href="{{ asset('images/small_logo.png') }}">

	    <!-- Styles -->
	    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
	    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
	    <link href="{{ asset('css/bootstraptabs.css') }}" rel="stylesheet">
	    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
	    <link href="{{ asset('css/jquery.dataTables.css') }}" rel="stylesheet">

		<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
		<script src="{{ asset('js/bootstrap.js') }}"></script>
		<script src="{{ asset('js/jquery.dataTables.js') }}"></script>
        <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
        <script src="{{ asset('js/knockout-2.2.1.js') }}"></script>
        <script src="{{ asset('js/sevenSeg.js') }}"></script>
	</head>

	<style>
    body {
        background-color: black;
    }
	</style>

	@stack('styles')

	<body >
	@yield('content')
	@yield('script')
	</body>
</html>
