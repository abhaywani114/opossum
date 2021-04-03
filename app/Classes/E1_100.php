<?php
namespace App\Classes;
use Log;
use GuzzleHttp;
use Illuminate\Support\Facades\DB;

/* This is the protocol implementation of:
Invenco E1-100
Payment API Specification
Version 1.X
June 2020
*/

class E1_100
{
	private $url;
	private $ipaddr;
	private $ch = null;

    public function __construct($ipaddr) {
		$this->ipaddr = (!empty($ipaddr)) ? $ipaddr : env('OPT_IPADDR');
		$this->url = "http://".$this->ipaddr;

		Log::debug('ipaddr='.$this->url);
		Log::debug('url='.$this->ipaddr);
    }


	/* $api_suffix = ":8189/api/1.0/terminals" */
	public function set_channel($api_suffix) {
		$url = $this->url.$api_suffix;
		$this->ch = curl_init($url);

		dump('url='.$url);

		//curl_setopt($this->ch,CURLOPT_CONNECTTIMEOUT, 10);
		//curl_setopt($this->ch,CURLOPT_TIMEOUT, 10);

		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, TRUE);
	}

	/*
	GET : http://<host>:8189/api/1.0/transactions/sale/{transactionId}? vtid={vtid}
&format={format}

	POST http://<host>:8189/api/1.0/transactions/sale/?vtid={vtid}
	POST http://<host>:8189/api/1.0/transactions/authorise/?vtid={vtid}
	GET : http://<host>:8189/api/1.0/transactions/sale/{transactionId}? vtid={vtid}&format={format}
	GET : http://<host>:8189/api/1.0/transactions/ authorise/{transactionId}?vtid={vtid}&format={format}
	DELETE http://<host>:8189/api/1.0/transactions/authorise/{transactionId}?vtid={vtid}
	POST http://<host>:8189/api/1.0/reconciliation/dayclose
	POST http://<host>:8189/api/1.0/reconciliation/dayclose?vtid=2
	GET : http://<host>:8189/api/1.0/reconciliation/dayclose/{transactionId}?format={format}
	GET : http://<host>:8189/api/1.0/terminals
	GET : http://<host>:8189/api/1.1/sales/new{/noOfSales}?vtid={vtid}&format={format}
	POST : http://<host>:8189/api/1.1/sales/processed/{transactionId}
	POST : http://<host>:8189/api/1.1/sales/processed?date={date}&time={time}
	POST http://<host>:8189/api/1.0/transactions/balance?vtid={vtid}
	GET : http://<host>:8189/api/1.0/transactions/balance/{transactionId}?vtid={vtid}
&format={format}
	POST http://<host>:8189/api/1.0/transactions/reprint/?vtid={vtid}
	GET : http://<host>:8189/api/1.0/transactions/reprint/{transactionId}?vtid={vtid}&format={format}
	*/


	/*
	GET : http://<host>:8189/api/1.0/terminals
	*/
	public function get_all_terminal_status() {

		$api_suffix = ":8189/api/1.0/terminals";
		$this->set_channel($api_suffix);
		curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "GET");

		$response = json_decode(curl_exec($this->ch));
		$http_code = curl_getinfo($this->ch, CURLINFO_HTTP_CODE); 

		$ret['response'] = $response;
		$ret['http_code'] = $http_code;

		return $ret;
	}


	/* Request sent to IPT by the POS application to start a
	   Pre Auth transaction. */
	public function preauth_req($vtid, $items) {
		/*
		{
			"amount":30.0,
			"transactionType":"PREAUTH",
			"saleItems":[
				{
					"amount":20.0,
					"productId":"101",
					"quantity":1.0,
					"unitPrice":20.0,
					"pumpId":1
				},
				{
					"amount":10.0,
					"productId":"202",
					"quantity":2.0,
					"unitPrice":5.0,
					"pumpId":2
				}
			]
		}
		*/

		$api_suffix = ":8189/api/1.0/transactions/authorise/?vtid=";
		$data = array(
			'amount' => 0.0,				// need to sum total amount
			'transactionType' => "PREAUTH",
			'salesItems' => $items			// contains an array of items
		);

		$this->set_channel();
		//dump($this->ch);

		//dump(json_encode($data));
		Log::debug(json_encode($data));

		curl_setopt($this->ch, CURLOPT_POSTFIELDS, json_encode($data));

		$response = json_decode(curl_exec($this->ch));
		$http_code = curl_getinfo($this->ch, CURLINFO_HTTP_CODE); 

		$ret['response'] = $response;
		$ret['http_code'] = $http_code;

		return $ret;
	}


	public function close_channel() {
		if (!empty($this->ch)) {
			curl_close($this->ch);
		}
	}
}
?> 
