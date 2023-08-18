<?php
include "config.php";

$domainlist = $_GET["domainnames"];

$params = [
	'domainnames' => $domainlist
];

$url = 'https://api.value-domain.com/v1';   // APIのエンドポイントURLを指定
$token = $VALUEDOMAIN_KEY;
$path = "/domainsearch";
$method = "GET";
$headers = array();

$headers[] = "Content-Type: application/json";
$headers[] = 'Authorization: Bearer ' . $token;

$ch = curl_init();
$url .= $path;
if (count($params) > 0){
	$url .= '?' .  http_build_query($params);
}

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);
curl_close($ch);

// レスポンスの処理
if ($response === false) {
// エラーハンドリング
	die('APIリクエストが失敗しました: ' . curl_error($ch));
} else {
	echo $response;
}
?>


