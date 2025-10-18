<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use App\Helper\SendMessage;

class MessageController extends Controller {

    public function index(Request $request) 
    {

	
		$phone = array(
			628165482220,
			6281936722835,
			6288228591132,
			6282234062633,
			6282139438823,
			626281314757146,
			6282232575285,
			628113180102,
			6285161696262,
			6287761838999,
			6285161984568,
			6281330889929,
			6282132905008,
			6282139781453,
			6281314733360,
			6282247155547,
			6281234538924,
			6285606715771,
			6282234478400,
			6285648453165,
			6287722203611,
			6281234605251,
			62818524800,
			6281804307030,
			6281331834831,
	
			);
	
			$api_key   = env('WATSAP_KEY'); // API KEY Anda
			$id_device = env('WATSAP_ID'); // ID DEVICE yang di SCAN (Sebagai pengirim)
			$url   = 'https://wa.srv1.wapanels.com/send-message'; // URL API
			$message = '*UNDANGAN INTERVIEW*
			
	Selamat Siang, 
	Kami dari PT. Perwira Tamaraya Abadi  mengundang Sdr./i untuk melakukan INTERVIEW posisi SECRETARY/SEKRETARIS
	pada : 
	Hari              : Rabu
	Tanggal        : 22 November 2023
	Pukul            : 10:00 (Pagi)
	Tempat         : Modern Ceramics 
	Jl. Baliwerti No 119-121 Kav 10 ( Depan Alun-alun contong ) Bubutan , Surabaya 60174
	
	Adapun hal-hal yang perlu diperhatikan antara lain : 
	 1. Membawa Surat Lamaran Kerja dan Curriculum Vitae. 
	 2. Membawa alat tulis. 
	
	Jika  Sdr./i  berkenan untuk hadir, harap melakukan *konfirmasi* Kehadiran dengan membalas pesan ini.
	
	*NB* : Pada hari H harap menemui Bapak Hoodiantoro untuk interview.
	
	PT. Perwira Tamaraya Abadi';
			$curl = curl_init();
	
			foreach ($phone as $x) {
		   
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_HEADER, 0);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
				curl_setopt($curl, CURLOPT_TIMEOUT, 0); // batas waktu response
				curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($curl, CURLOPT_POST, 1);
	
				$data_post = [
					'api_key' => $api_key,
					'sender'  => $id_device,
					'number'  => $x,
					'message' => $message
				];
	
				curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data_post));
				curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
				$response = curl_exec($curl);
			
			}
			curl_close($curl);
// 		curl_close($curl);
		//echo SendMessage::send('081330074432','Test');
		//echo SendMessage::send('081330074432','Test');
		
		$hp = '6285607226218';
		$message = 'Test';
		$api_key   = env('WATSAP_KEY'); // API KEY Anda
		$id_device = env('WATSAP_ID'); // ID DEVICE yang di SCAN (Sebagai pengirim)
		$url   = 'https://wa.srv1.wapanels.com/send-message'; // URL API
		
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
		curl_setopt($curl, CURLOPT_TIMEOUT, 0); // batas waktu response
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_POST, 1);
		
		// $data_post = [
		//    'id_device' 	=> $id_device,
		//    'api-key' 	=> $api_key,
		//    'no_hp'   	=> $hp,
		//    'pesan'  	=> $message
		// ];

		$data_post = [
			'api_key' => $api_key,
			'sender'  => $id_device,
			'number'  => $hp,
			'message' => $message
		];
		
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data_post));
		curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
		$response = curl_exec($curl);
		curl_close($curl);
		
		echo $response;
	}
}