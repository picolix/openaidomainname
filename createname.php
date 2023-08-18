<?php
include "config.php";

$qa = $_REQUEST['post_text'];
//$qa = "ECサイトで着物を扱います。ワールドワイドに売るサイトです。青色を強調してください。";
if(is_null($qa)){
        exit();
}

function getOpenAIKeywords($text) {
	global $OPENAI_KEY;
	$apiKey = $OPENAI_KEY;
	$model = 'text-davinci-003';

	$headers = [
		'Content-Type: application/json',
		'Authorization: Bearer ' . $apiKey,
	];

	$data = [
		'prompt' => $text,
		'max_tokens' => 300,
		'temperature' => 0.5,
	];

	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, 'https://api.openai.com/v1/engines/' . $model . '/completions');
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

	$response = curl_exec($curl);
	curl_close($curl);

	$responseData = json_decode($response, true);

	//print_r($responseData);

	$choices = $responseData['choices'];
	$keywords = [];

	foreach ($choices as $choice) {
		$keywords[] = $choice['text'];
	}

	return $keywords;
}

// 問い合わせ文章
$resnum = 5;
$rescreatenum = 3;
$header = "新しくドメイン名を付けたいです。
次のコンセプトの場合は何がいいでしょうか？
";

$footer = "
ドメイン名を" . $resnum . "個提案してください。
その結果と新たに造語で単語を短くして". $rescreatenum ."個提案してください。
返答はTLDがcomのドメイン名にしてください。
出力順は最初の" . $resnum ."個と造語の" . $resnum . "個を連続して表示してください。
出力データをプログラムで利用するのでjson形式で出力してください。キーの名前はdataにしてください。
";

//$qa = "ECサイトで着物を扱います。ワールドワイドに売るサイトです。";

$text = $header . $qa . $footer;

$keywords = getOpenAIKeywords($text);
//$keywords[0] = '{"data":["kimonoworld.com","kimoworld.com","kimoworl.com","kimowor.com","kimowo.com","kimow.com","kimo.com","kim.com","ki.com"]}';

echo $keywords[0];

?>
