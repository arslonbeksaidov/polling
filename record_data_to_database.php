<?php

// BAZAGA ULANISH UCHUN
function connect()
{
$servername = "";
$username = "";
$password = "";
$dbname = "";
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}{
    return $conn;
}    
}



// UNIQUE RAQAM YARATISH UCHUN

function UniqueRandomNumbersWithinRange($min, $max, $quantity) {
    $numbers = range($min, $max);
    shuffle($numbers);
    return array_slice($numbers, 0, $quantity);
}



// NUMBERS TABLITSAZIGA RAQAMLARNI YOZISH UCHUN  

 function insertGeneratedNumber()
{
    $conn  = connect();
$numbers = UniqueRandomNumbersWithinRange(0,100,100);    

foreach($numbers as $number)
{
 $sql = "INSERT INTO numbers (number)
VALUES ('$number')";

if ($conn->query($sql) === TRUE) {
  echo "New record created successfully";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}
}
    
}

// CONTACT TABLITSASIGA YOZISH UCHUN

function insertContacts($number,$tel)
{
    $conn  = connect();
 $sql = "INSERT INTO contacts (number,tel)
VALUES ('$number','$tel')";

if ($conn->query($sql) === TRUE) {
return true;
} else {
return false;
}   
}


// BAZADAGI  MALUMOTLARNI OCHIRISH

function deleteAllRowNumbers()
{
    $conn  = connect();
    $sql = 'DELETE  FROM numbers'; 
 if ($conn->query($sql) === TRUE) {
  echo "New record created successfully";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}   
}

// RAQAM TANLASH

function chooseNumbers()
{

    
  $conn  = connect();
  $sql = "SELECT number FROM numbers";
  $sql1 = "SELECT number FROM contacts";
  $result = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
  $result1 = $conn->query($sql1)->fetch_all(MYSQLI_ASSOC);
  shuffle($result1);
    $array_one = array_slice($result1, 0, 3);
    $array_two = $result;
    $a = [];
    $b = [];
    foreach($array_one as $array)
    {
        $a[] = $array['number'];
    }
    foreach($array_two as $array)
    {
        $b[] = $array['number'];
    }
    $javob =array_intersect($a, $b); 
    return $javob;
    
}

// if(insertContacts(133,9) == true){
//     echo 'yes';
// }else{ echo 'no';}

//------------------------------------------------------------------------------
//   bot uchun kodlar
//-------------------------------------------------------------------------------


class TelegramBot{

const API_URL = 'https://api.telegram.org/bot<API_TOKEN>';

public function setWebhook($url)
{
return $this->request('setWebhook',[
    'url' => $url
    ]);    
}

 // so'rov telegram ga so'rov yuborish uchun request
 
public function request($method, $data)
{
    
    $ch = curl_init();
    $url = self::API_URL.'/'.$method;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST,1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    
    $headers = array();
    $headers[] = 'Content-Type: application/x-www-form-urlencoded';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
    return $result;
}
 
    // kelayotgan o'zgarishlarni olish uchun hammasini 
    
    public function Updates()
    {
    $update = json_decode(file_get_contents('php://input'));
    return $update;
    }
 
 // --------------------------------------------------------------  Obektlar telegam
 
 
 // message obekti
 
 public function Message()
 {
    return  $this->Updates()->message;
 }
 
 public function CallbackQuery()
 {
     return  $this->Updates()->callback_query;
 }
 
 public $inline_keyboard = [
                  [
                  
                  [
                  'text' =>'Bossang malumot o\'zgaradi',
                  'callback_data' =>'data1',
                  
                  ],
                  
                  
                  ],
                  
                              ];
                  public $inline_keyboard1 = [
                  [
                  
                  [
                  'text' =>'qqq',
                  'callback_data' =>'callback1',
                  
                  ],
                  
                  
                  ],
                  
                              ];
 
  public $keyboard = [
                  [
                  [
                  'text' =>'raqam telefon',
                  'request_contact' =>true,
                  
                  ],
                  [
                  'text' =>'/start',
                  'request_contact' =>false,
                  
                  ],
                  [
                  'text' =>'location',
                  'request_location' =>true,
                  
                  ],
                  
                  ],
                  
                              ];
    public function sendMessage($chat_id, $text)
    {
        return $this->request("sendMessage",[
            'chat_id' => $chat_id,
            'text' => $text,
            'parse_mode'=>"markdown",
            // 'reply_to_message_id' =>$message_id ,
            'reply_markup' => json_encode(
                [
                // 'resize_keyboard'=>true,
                'keybard' => $this->inline_keyboard
                ]
                
                ) 
            ]);
    }
    
    public function editMessageText($chat_id,$message_id,$text)
    {
        return $this->request("editMessageText",[
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'text' => $text,
                'reply_markup' => json_encode(
                [
                // 'resize_keyboard'=>true,
                'inline_keyboard' => [
                    [['text' => 'cc', 'callback_data' => 'data2']]
                    ]
                ]
                
                )
            ]);
    }
    
    
    public function sendContact($chat_id, $phone_number, $first_name)
    {
        
        return $this->request('sendContact',[
            'chat_id'=> $chat_id,
            'phone_number'=>$phone_number,
            'first_name' => $first_name
            ]); 
    }
       
}


    
 

$bot = new TelegramBot();


$data = $bot->Message();
$dataCallback = $bot->CallbackQuery();                                                              

// -----  user malumotlari 
$user_data = [];
$user_data[] = 'user id: '. $data->from->id;  
$user_data[] =  $data->from->is_bot;
$user_data[] =  $data->from->first_name;
$user_data[] =  $data->from->last_name;
$user_data[] =  $data->from->username;
$user_data[] =  $data->from->language_code;
$user_data[] =  $data->from->can_join_groups;
$user_data[] =  $data->from->can_read_all_group_messages;
$user_data[] =  $data->from->supports_inline_queries;

$contact = $data->contact->phone_number;
$text = $data->text;
$reply_markup = $data->reply_markup->text;
$message_id=  $data->message_id;
$chat_id = $data->chat->id;
$username = $data->from->username;
$callback = $dataCallback->data;
$callback_query_chat_id = $dataCallback->message->chat->id;
$cmessage_id = $dataCallback-> message->message_id;
 if($text == '/start')
 {
   
    $bot->sendMessage($chat_id,"Salom");    
}

if($contact)
{
 $number = $contact;
 
 $bot->sendMessage('$chat_id',$number);
insertContacts($number,$number);
}  

// if($callback == 'callback1')
// {
//     $bot->sendMessage($callback_query_chat_id,'done');
// }


if($callback == 'data1')
{
    $bot->editMessageText($callback_query_chat_id,$cmessage_id,'ozgardi');
}
if($callback == 'data2')
{
    $bot->editMessageText($callback_query_chat_id,$cmessage_id,' yanaozgardi');
}


// webhook ni set qilish void
// echo $bot->setWebhook("https://dubaiuniversity.uz/tanlovvbot2020/bot.php");









?>
