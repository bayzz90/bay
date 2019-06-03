<?php  
error_reporting(0);
function post($url, $data, $headers = null, $proxy = null) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

	if ($proxy != "") {
		curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, true);
		curl_setopt($ch, CURLOPT_PROXY, $proxy);
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS4);	
	}

	if ($headers != "" ) {
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	}

	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}

function string($length) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

function register($name, $proxy) {
	$domain = array('postfach2go.de', 'mailbox2go.de', 'briefkasten2go.de', 'email-paul.de', 'schreib-mir.tk');
	$rand = array_rand($domain);
	$email = string(10).'@'.$domain[$rand];

	$url = 'https://sea-m.newchic.com/en/api/account/registernew/';
	$data = 'email='.$email.'&pwd=yudha123&re_pwd=yudha123&regcaptcha=';

	// taro cookies disini
	$cookies = 'Cookie: newchic_SID=0726e0ae06a89b5f2cef512e6babbe7f; _bgLang=en-GB; default_rule_country=100; generalAbTest=54; _abtest=1; AKA_A2=A; currency=IDR; gdpr_is_eu=0; gdpr_status=1|1|1|1|1; gdpr_is_agree=1; isCloseTip=1; route=03da1a83460a312c30bb32a66a5bb46e; _gcl_au=1.1.236054592.1559024014; aff_p_code=7d5a761da9baca51dce2ac54e81c620e; _scid=540e6806-f037-436c-ae07-5c87b7110aaf; LCUTS_UID_900899=900899; _ga=GA1.2.750930841.1559024016; _gid=GA1.2.1276717527.1559024016; affiliate_code=IX052727988308201956; __bgqueue=1559024015645|bg_affiliate|aff|m-fashion-collection/special-3063|27988308|0|2|0|; __bgcookie=0|; __bguser=1559024015645|2540516237|2540516237|1559024015645; ncim-bypass=false; _ym_uid=1559024017957623932; _ym_d=1559024017; _fbp=fb.1.1559024017570.854906272; _ym_visorc_46321287=b; _ym_isad=2; _freeshipText=%3Cfont%20color%3D%22%23F85184%22%3EFree%20shipping%3C%2Ffont%20%3E%20to%20Indonesia%20after%20ordering%20Rp723%2C180.00; nc-country-code=NL; __ACCESS=1; affiliate_record_id=116258; __bgvisit=1559024041786|bg_affiliate|aff|m-fashion-collection/special-3063|27988308|0|2|null; RT="dm=newchic.com&si=872cf1f4-e5cf-4edd-8c3a-0d8f4369c509&ss=1559024007113&sl=3&tt=74495&obo=0&sh=1559024110545%3D3%3A0%3A74495%2C1559024037893%3D2%3A0%3A18279%2C1559024012811%3D1%3A0%3A5674&bcn=%2F%2F60062f09.akstat.io%2F&ld=1559024110550"';

	$headers = array(
		'Origin: https://sea-m.newchic.com',
		'Accept-Language: en-US,en;q=0.9',
		'User-Agent: Mozilla/5.0 (Linux; Android 8.0; Pixel 2 Build/OPD3.170816.012) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.103 Mobile Safari/537.36',
		'Content-Type: application/x-www-form-urlencoded',
		'Accept: application/json, text/plain, */*',
		''.$cookies.'',
		'Connection: keep-alive',
	);

	$register = post($url, $data, $headers, $proxy);

	if (strpos($register, '"message":"succeed"')) {
		echo $proxy." | Success register | Email: ".$email."\n";
		// Save result email
		$data =  $email."\r\n";
		$fh = fopen("list_email_".$name.".txt", "a");
		fwrite($fh, $data);
		fclose($fh);
	} elseif (strpos($register, '"message":"Register frequently, please enter verification code."')) {
		echo $proxy." | Captcha detected\n";
	} else {
		echo $proxy." | Proxy die\n";
	}
}


echo "Newchic Referral\n";
echo "Created by yudha tira pamungkas\n";
echo "Facebook: https://facebook.com/yudha.t.pamungkas.3\n\n";

echo "Proxy list (Ex: proxy.txt): ";
$proxy = trim(fgets(STDIN));

if ($proxy == "") {
	die("Cannot be blank!\n");
} else {
	$file = file_get_contents($proxy) or die ($proxy." not found!\n");
	$explode = explode("\n", $file);
	echo "Total proxy: ".count($explode)."\n";
	$name = string(8);
	
	foreach ($explode as $prxy) {
		register($name, $prxy);
	}
}

?>