<?php


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
    $channel_name = '@speaking99';
    $update = json_decode(file_get_contents('php://input'));
    $message = $update->message;
    $chat_id = $message->chat->id;
    $text = $message->text;
    
    $options =  array("my","name","is","Arslon");
    $first_name = $message->user->firs_name;
    $last_name = $message->user->last_name;
    $username_user = $message->from->username;
    $options =  array("😕5.5","😊6.0","😄6.5","🤩7.0+");
    if ($message->voice)
    {
        bot('sendVoice',[
            'chat_id'=>$channel_name,
            'voice'=>$message->voice->file_id,
            'caption'=>'by'.' '.'https://t.me/'.$username_user,
            'disable_notification'=>true,
            
        ]);
    bot('sendPoll',[
        'chat_id'=>$channel_name,
        'question'=>'Poll for '.$message->chat->first_name.'`speech',
        'options' => json_encode($options)
    ]);
    }
    if ($message->audio)
    {
        bot('sendAudio',[
            'chat_id'=>$channel_name,
            'audio'=>$message->audio->file_id,
            'caption'=>'by'.' '.'https://t.me/'.$username_user,
            'duration'=>60,
            
        ]);
    bot('sendPoll',[
        'chat_id'=>$channel_name,
        'question'=>'Poll for '.$message->chat->first_name.'`speech',
        'options' => json_encode($options)
    ]);
    }
    if($message->audio)
    {
        bot('getFile',[
            'file_id'=>$message->audio->file_id,
            ]);
    }
    
    
