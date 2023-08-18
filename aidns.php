<?php


if(is_null($argv[1])){
        print "どのようなコンセプトか、どんなサイトなのか自由に記述\n";
        exit();
}else{
        $qa = $argv[1];
}

function getOpenAIKeywords($text) {
    $apiKey = 'sk-openai-key';  // OpenAI APIキーを設定してください
    $model = 'text-davinci-003';

    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey,
    ];

    $data = [
        'prompt' => $text,
        'max_tokens' => 130,
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

// サンプル文章
$resnum = "５";
$rescreatenum = "３";
$header = "あたらしくドメイン名を付けたいです。
次のコンセプトの場合は何がいいでしょうか？
";
$footer = "
ドメイン名を" . $resnum . "つ提案してください。その結果と新たに造語で単語を短くして". $rescreatenum ."つ提案してください。返答はドメイン名だけでいいです。
出力順は最初の" . $resnum ."つと造語の" . $resnum . "つで連続して表示してください。その時の連番の初期値は０にしてください。
";

//$qa = "ECサイトで着物を扱います。ワールドワイドに売るサイトです。";

$text = $header . $qa . $footer;

$keywords = getOpenAIKeywords($text);

//echo "入力文章: " . $text . "\n";
//echo "連想されたキーワード:\n";

echo $qa;

foreach ($keywords as $keyword) {
    echo $keyword . "\n";
}

