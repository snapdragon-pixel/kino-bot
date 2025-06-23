<?php
require ("../setting.php");
require ("../sql.php");

date_default_timezone_set("Asia/Tashkent");

$hour = date('H:i');
$send = mysqli_query($connect, "SELECT * FROM send"); 
$send1 = mysqli_fetch_assoc($send); 
$xabar = $send1['message']; 
$types = $send1['type']; 
$member = $send1['start']; 
$limit = $send1['limit']; 
$succes = $send1['succes']; 
$left = $send1['left']; 
$time1 = $send1['time1']; 
$time2 = $send1['time2']; 
$time3 = $send1['time3']; 
$time4 = $send1['time4']; 
$time5 = $send1['time5']; 
$infosend = $send1['mesid']; 
if($send1['button'] == "bnVsbA=="){ $button = null; }else{ $button = base64_decode($send1['button']); }
if($hour==$time1 or $hour==$time2 or $hour==$time3 or $hour==$time4 or $hour==$time5){ 
$sql="SELECT * FROM users LIMIT $succes,$limit"; 
$res = mysqli_query($connect,$sql); 
while($a = mysqli_fetch_assoc($res)){ 
$id =  $a['user_id']; 
if($id==$left){ 
bot('deleteMessage',[ 
'chat_id'=>$admin, 
'message_id'=>$infosend, 
]); 
bot("$types",[ 
'from_chat_id'=>$admin, 
'chat_id'=>$left, 
'message_id'=>$xabar, 
'disable_web_page_preview'=>true, 
'reply_markup'=>$button
]); 
bot('sendMessage',[ 
'chat_id'=>$admin, 
'text'=>"<b>Xabar yuborish jarayoni:</b> Yakunlandi! 
 
<b>Yuborildi:</b> ($member) - <b>Kutilmoqda:</b> (0)", 
'parse_mode'=>'html', 
]); 
mysqli_query($connect, "DELETE FROM send"); 
exit(); 
}else{ 
bot("$types",[ 
'from_chat_id'=>$admin, 
'chat_id'=>$id, 
'message_id'=>$xabar, 
'disable_web_page_preview'=>true, 
'reply_markup'=>$button
]); 
} 
$time1 = date('H:i', strtotime('+1 minutes')); 
$time2 = date('H:i', strtotime('+2 minutes')); 
$time3 = date('H:i', strtotime('+3 minutes')); 
$time4 = date('H:i', strtotime('+4 minutes')); 
$time5 = date('H:i', strtotime('+5 minutes')); 
mysqli_query($connect, "UPDATE send SET time1 = '$time1', time2 = '$time2', time3 = '$time3', time4 = '$time4', time5 = '$time5'"); 
$plus=$succes+$limit; 
mysqli_query($connect, "UPDATE send SET succes = '$plus'"); 
$minus=$member-$plus; 
} 
bot('deleteMessage',[ 
'chat_id'=>$admin, 
'message_id'=>$infosend, 
]); 
if($infosend=="null"){ 
$info=bot('SendMessage',[ 
'chat_id'=>$admin, 
'text'=>"<b>Xabar yuborish jarayoni:</b> Boshlandi! 
 
<b>Yuborildi:</b> ($plus) - <b>Kutilmoqda:</b> ($minus)", 
'parse_mode'=>'html', 
])->result->message_id; 
mysqli_query($connect, "UPDATE send SET mesid = '$info'"); 
}else{ 
$minus=$member-$plus; 
bot('editMessageText',[ 
'chat_id'=>$admin, 
'message_id'=>$infosend+1, 
'text'=>"<b>Xabar yuborish jarayoni:</b> Jarayonda.. 
 
<b>Yuborildi:</b> ($plus) - <b>Kutilmoqda:</b> ($minus)", 
'parse_mode'=>'html', 
])->result->message_id; 
mysqli_query($connect, "UPDATE send SET mesid = '$info'"); 
}}

echo json_encode(["status"=>true,"cron"=>"send message"]);

?>