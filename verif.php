<?php  
error_reporting(0);
function get($url, $headers =  null) {

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US) AppleWebKit/530.1 (KHTML, like Gecko) Chrome/2.0.164.0 Safari/530.1');

	if ($headers != "" ) {
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	}

	curl_setopt($ch, CURLOPT_HEADER, true);

	$result = curl_exec($ch);
	curl_close($ch);
	return $result;

}

function fetch_value($str,$find_start,$find_end) {
	$start = @strpos($str,$find_start);
	if ($start === false) {
		return "";
	}
	$length = strlen($find_start);
	$end    = strpos(substr($str,$start +$length),$find_end);
	return trim(substr($str,$start +$length,$end));
}

function verif($email) {

$headers = array(
	'Authority: www.mailbox2go.de',
	'Cache-Control: max-age=0',
	'Upgrade-Insecure-Requests: 1',
	'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36 OPR/58.0.3135.118',
	'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
	'Accept-Language: en-US,en;q=0.9',
	'Cookie: prefer_email_as=HTML; html2plaintext=false',
);

$url = 'https://www.mailbox2go.de/?'.$email;
$mail = get($url, $headers);

if (strpos($mail, 'Newchic.com')) {
	
	$link = fetch_value($mail, '<p align="center" style="line-height:30px;"><a href="','" style="color:#a93c3f;text-decoration:none;" rel="nofollow">You');
	$jad = str_replace('amp;', '', $link);

	$verif = get($jad, false);
	$a = fetch_value($verif, 'Location: ','
Vary: Accept-Encoding');
	$b = get($a, false);

	if (strpos($b, 'Location: https://sea-m.newchic.com/index.php?com=account&t=points')) {
		echo "Success verif | ".$jad."\n";
	} else {
		echo "Failed to verif!\n";
	}

} else {
	echo "Cannot find verification link!\n";
}

}

echo "Newchic Verification Link\n";
echo "Created by yudha tira pamungkas\n";
echo "Facebook: https://facebook.com/yudha.t.pamungkas.3\n\n";

echo "List email (Ex: list_email.txt): ";
$email = trim(fgets(STDIN));

if ($email == "") {
	die("Cannot be blank!\n");
} else {

	$file = file_get_contents($email) or die ($email." not found!\n");
	$explode = explode("\n", $file);
	echo "Total email: ".count($explode)."\n";

	foreach ($explode as $mail) {
		verif($mail);
	}

}


?>