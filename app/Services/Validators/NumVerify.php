<?php
namespace App\Services\Validators;

class NumVerify {
	//Key
	private $private_api_key = '';

	public function singleCheck($phone, $countryCode){
		$url = 'https://apilayer.net/api/validate?';
		$url_send = $url.'access_key='.$this->private_api_key.'&number='.$phone.'&country_code='.$countryCode.'&format=1';
		
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url_send);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 0); 
		curl_setopt($curl, CURLOPT_TIMEOUT, 400); //timeout in seconds
		set_time_limit(0);
		$response = curl_exec($curl);
		curl_close($curl);

		$validationResult = json_decode($response,true);
		return $validationResult ? $validationResult['valid'] : false;
	}

	public function getCurrencyCode($currencyId)
	{
		if($currencyId == 1)
			return 'ID';
		elseif($currencyId == 2)
			return 'CN';
		elseif($currencyId == 3)
			return 'TH';
		elseif($currencyId == 4)
			return 'MY';
		elseif($currencyId == 5)
			return 'VN';
	}
	
}