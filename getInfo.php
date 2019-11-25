<?php



error_reporting(E_ALL);
ini_set('display_errors', 1);

$api = '';
define('API_KEY', $api);
function bot($method, $datas)
{
    $url = "https://api.telegram.org/bot" . API_KEY . "/" . $method;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
    $result = curl_exec($ch);
    if (curl_error($ch)) {
        var_dump(curl_error($ch));
    } else {
        return json_decode($result);
    }
}
    $update = json_decode(file_get_contents('php://input'));
    $message = $update->message;
    $chat_id = $message->chat->id;
    $text = $message->text;

    $first_name = $message->user->firs_name;
    $last_name = $message->user->last_name;
    $username_user = $message->from->username;
$nomer = $message->contact->phone_number;
$name = $message->contact->first_name;

$arslon = $message->contact;
if($text == "/start"){
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"*Assalomu alaykum hurmatli foydalanuvchi botga xush kelibsiz botdan foydalanishingiz uchun ro'yxatdan o'tishingiz kerak*",
        'parse_mode'=>"markdown",
        'reply_markup'=>json_encode(
['resize_keyboard'=>true,
'keyboard' => [
[["text"=>"🤖Ro'yxatdan o'tish",'request_contact' =>true],],
] //By @Coderbola
])
]);
}
if($arslon){
bot('sendmessage',[
    'chat_id'=>"", //o'zizni id raqamiz
    'text'=>"User nomi: [$first_name](tg://user?id=)\nUseri: @$username_user\nNomeri: $nomer\nNomer nomi: $name\n",
    'parse_mode'=>"markdown"
        ]);
bot("sendmessage",[
    'chat_id'=>$chat_id,
    'text'=>"Yaxshi ro'yxatdan omadli o'tdingiz endi botdan 101% foydalanishingiz mumkin",
    'reply_markup'=>json_encode(
[
'resize_keyboard'=>true,
'selective'=>true,
'one_time_keyboard'=>true,
'keyboard' => [
[["text"=>"⚜️ Boshlash ⚜️"],],
] //By @CoderBola
])
]);
}
$button = $message->keyboardbutton->text;
?>
