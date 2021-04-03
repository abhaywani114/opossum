<?php

namespace App\Http\Controllers;

use \App\Classes\E1_100;
use Illuminate\Http\Request;

class TestController extends Controller
{
	public function get_all_terminal_status() {
		dump('TestController@get_all_terminal_status()');

		$e1 = new E1_100('127.0.0.1');
		$res = $e1->get_all_terminal_status();

		dump($res);

		$e1->close_channel();
	}


	public function preauth_req() {
		dump('TestController@preauth_req()');

		$vtid = 2;
		$items = array(
			array (
				'amount' => 1.95,
				"productId" => "1050000000432",
				"quantity" => 3,
				"unitPrice" => 3.43
			),
			array (
				'amount' => 43.39,
				"productId" => "1050000009293",
				"quantity" => 32,
				"unitPrice" => 9.14
			)
		);

		dump($vtid);
		dump($items);
		dump($res);

		$e1 = new E1_100('127.0.0.1');
		$res = $e1->preauth_req($vtid, $items);


		$e1->close_channel();
	}
}
