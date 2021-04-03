<?php

namespace App\Console\Commands;

use \Log;
use \DB;
use Illuminate\Console\Command;

class syncTerminalRCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'syncTerminalRCount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncs reciept count with mothership';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
		$company_data	= DB::table('company')->first();
		$location_data	= DB::table('location')->first();

		$terminalcount = DB::table('terminalcount')->
			join('terminal','terminal.id','terminalcount.terminal_id')->
			select("terminalcount.*", "terminal.systemid")->
			get();

		$terminalcount->map(function($z) {
			$z->current_rcount = DB::table('receipt')->
				where('terminal_id',$z->terminal_id)->count();
		});
		
		$data = [];
		$data['api_key']			= env('APP_KEY');
		$data['company_systemid'] 	= $company_data->systemid;	
		$data['location_systemid']	= $location_data->systemid;
		$data['terminalcount']		= json_encode($terminalcount);

		$this->info("Sending data");	
		print_r($this->sendData($data));

        return 0;
    }

	private function sendData($post) {
		
		$url = env('MOTHERSHIP_URL') . '/terminal-master/update-terminal-r-count';
		Log::debug("url=" . $url);

		$cURLConnection = curl_init($url);
		curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $post);
		curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($cURLConnection, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($cURLConnection, CURLOPT_SSL_VERIFYHOST, false);
		$apiResponse = curl_exec($cURLConnection);
		curl_close($cURLConnection);
		$data = json_decode($apiResponse, true);
		Log::debug('Curl Response: '.$apiResponse);
		return $data;
	}
}
