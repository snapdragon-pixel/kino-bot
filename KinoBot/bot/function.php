<?php


function getAdmin($chat){
$url = "https://api.telegram.org/bot".API_TOKEN."/getChatAdministrators?chat_id=$chat";
$result = file_get_contents($url);
$result = json_decode ($result);
return $result->ok;
}

#=================== Get kino ===================#

function downloads($film){
global $connect;
$film = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM films WHERE id = '$film'"));
$downloads = $film['downloads'] + 1;
mysqli_query($connect,"UPDATE films SET downloads = '$downloads' WHERE id = $film[id]"); 
}


function kino($id,$film){
global $connect, $bot, $admin, $advertising, $name, $setting;
$film = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM films WHERE id = '$film'"));
$key = json_encode([
'inline_keyboard'=>[
[['text'=> ($id == $admin) ? "🗑 O'chirish" : "",'callback_data'=>"del-kino-$film[id]"]],
]
]);
if($film){
$channels=mysqli_query($connect,"SELECT * FROM channels");
if(mysqli_num_rows($channels) > 0){
while($channel = mysqli_fetch_assoc($channels)){
$channel_id .= $channel['id']."\n";
$channel_url .= $channel['url']."\n";
$channel_type .= $channel['type']."\n";
$ids=explode("\n",$channel_id);
$soni = substr_count($channel_id,"-");
}
foreach($ids as $iid){
$keyboards = [];
$k = [];
for ($for = 0; $for <= $soni-1; $for++) {
$kanalurls = explode("\n",$channel_url)[$for];
$kanal_type = explode("\n",$channel_type)[$for];
$k = $for + 1;
$status = bot('getChatMember',['chat_id'=>$ids[$for],'user_id'=>$id])->result->status;
if($kanal_type == "zayavka"){
$get = file_get_contents("zayavka/$ids[$for]");
if(mb_stripos($get,$id)!==false){
$status = "member";
}
}
$channelss = mysqli_query($connect, "SELECT * FROM channels WHERE type = 'link'");
if(!$status){$status == "member";}
if($status == "member" or $status == "administrator" or $status  == "creator"){
}else{
$keyboards[]=["text"=>"➕ Obuna bo'lish","url"=>$kanalurls];
while($channell = mysqli_fetch_assoc($channelss)){
    $keyboards[]=["text"=>"➕ Obuna bo'lish","url"=>$channell['url']];
}
$keyboard2=array_chunk($keyboards, 1);
$key_soni = substr_count($kanalurls,"https://t.me/");
$keyboard2[]=[["text"=>"✅ Tekshirish","callback_data"=>"check-$film[id]"]];
$keyboard=json_encode([
'inline_keyboard'=>$keyboard2,
]);
}
}
if($keyboard==true){
sms($id,"<b>❌ Kechirasiz botimizdan foydalanishdan oldin ushbu kanallarga obuna bo'lishingiz kerak.</b>",$keyboard);
exit();
}else{
bot('sendChatAction', [
'chat_id'=>$id,
'action'=>'upload_video'
]);
downloads($film['id']);
return video($id,$film['film_id'],"<b>🖐 Salom <a href='tg://user?id=$id'>$name</a>

<blockquote>/rand - 🔄 Random kinolar
/help - ☎️ Qo'llab quvvatlash
/dev - 🧑‍💻 Dasturchi</blockquote>

<a href='https://t.me/{$setting['movie_channel']}'>📥 Kanalimiz</a> - orqali ko'proq kodlarni olishingiz mumkin!</b>", ($id == $admin) ? $key : null);

}}}else{
bot('sendChatAction', [
'chat_id'=>$id,
'action'=>'upload_video'
]);
downloads($film['id']);
return video($id,$film['film_id'],"<b>🖐 Salom <a href='tg://user?id=$id'>$name</a>

/rand - 🔄 Random kinolar
/help - ☎️ Qo'llab quvvatlash
/dev - 🧑‍💻 Dasturchi

<a href='https://t.me/{$setting['movie_channel']}'>📥 Kanalimiz</a> - orqali ko'proq kodlarni olishingiz mumkin!</b>", ($id == $admin) ? $key : null);
}
}else{
return sms($id,"<b>❌ Kino kodini hato yubordingiz!</b>");   
}
}

function joinchat($id){
global $connect, $bot;
$channels=mysqli_query($connect,"SELECT * FROM channels");
if(mysqli_num_rows($channels) > 0){
while($channel = mysqli_fetch_assoc($channels)){
$channel_id .= $channel['id']."\n";
$channel_url .= $channel['url']."\n";
$channel_type .= $channel['type']."\n";
$ids=explode("\n",$channel_id);
$soni = substr_count($channel_id,"-100");
}
foreach($ids as $iid){
$keyboards = [];
$k = [];
for ($for = 0; $for <= $soni-1; $for++) {
$kanalurls = explode("\n",$channel_url)[$for];
$kanal_type = explode("\n",$channel_type)[$for];
$k = $for+1;
$status = bot('getChatMember',['chat_id'=>$ids[$for],'user_id'=>$id])->result->status;
$name = bot('getChat',['chat_id'=>$ids[$for]])->result->title;
if($kanal_type == "zayavka"){
$get = file_get_contents("zayavka/$ids[$for]");
if(mb_stripos($get,$id)!==false){
$status = "member";
}
}
$channelss = mysqli_query($connect, "SELECT * FROM channels WHERE type = 'link'");
if(!$status){$status == "member";}
if($status == "member" or $status == "administrator" or $status  == "creator"){
}else{
$keyboards[]=["text"=>"➕ Obuna bo'lish","url"=>$kanalurls];
while($channell = mysqli_fetch_assoc($channelss)){
    $keyboards[]=["text"=>"➕ Obuna bo'lish","url"=>$channell['url']];
}
$keyboard2=array_chunk($keyboards, 1);
$key_soni = substr_count($kanalurls,"https://t.me/");
$keyboard2[]=[["text"=>"✅ Tekshirish","callback_data"=>"Check"]];
$keyboard=json_encode([
'inline_keyboard'=>$keyboard2,
]);
}
}
if($keyboard){
sms($id,"<b>❌ Kechirasiz botimizdan foydalanishdan oldin ushbu kanallarga obuna bo'lishingiz kerak.</b>", $keyboard);
exit();
}else{
return true;
}
    
}
    
}else{
return true;
}
}



#=================== Function ===================#

function sms($id,$text, $key = null){
return bot('sendMessage',[
'chat_id'=>$id,
'text'=>$text,
'parse_mode'=>'html',
'disable_web_page_preview'=>true,
'protect_content' => (status($id) ? false : true),
'reply_markup'=>$key
]);
}

#================================================#

function document($cid, $f_id, $text, $key = null){
return bot('sendDocument',[
'chat_id'=>$cid,
'document'=>$f_id,
'caption'=>$text,
'parse_mode'=>'html',
'protect_content' => (status($cid) ? false : true),
'reply_markup'=>$key
]);
}

#================================================#

function photo($cid, $f_id, $text, $key = null){
return bot('sendPhoto',[
'chat_id'=>$cid,
'photo'=>$f_id,
'caption'=>$text,
'parse_mode'=>'html',
'protect_content' => (status($cid) ? false : true),
'reply_markup'=>$key
]);
}

#================================================#


function video($cid, $f_id, $text, $key = null){
return bot('sendVideo',[
'chat_id'=>$cid,
'video'=>$f_id,
'caption'=>$text,
'parse_mode'=>'html',
'protect_content' => (status($cid) ? false : true),
'reply_markup'=>$key
]);
}

#================================================#

function edit($cid, $mid, $text, $key = null){
return bot('editMessageText',[
'chat_id'=>$cid,
'message_id'=>$mid,
'text'=>$text,
'parse_mode'=>'html',
'disable_web_page_preview'=>true,
'reply_markup'=>$key
]);
}

#================================================#

function query($qid, $text){
return bot('answerCallbackQuery',[
'callback_query_id'=>$qid,
'text'=>$text,
'show_alert'=>true,
]);
}

#================================================#
/*
function copyMessage($id, $from_chat_id, $message_id){
return bot('copyMessage',[
'chat_id'=>$id,
'from_chat_id'=>$from_chat_id,
'message_id'=>$message_id
]);
}

#================================================#

function forwardMessage($id, $cid, $mid){
return bot('forwardMessage',[
'from_chat_id'=>$id,
'chat_id'=>$cid,
'message_id'=>$mid
]);
}

#================================================#

function del($cid,$mid){
return bot('deleteMessage',[
'chat_id'=>$cid,
'message_id'=>$mid
]);
}
}*/
#================================================#

function del(){
global $cid,$mid,$cid2,$message_id;
return bot('deleteMessage',[
'chat_id'=>$cid.$cid2,
'message_id'=>$mid.$message_id,
]);
}

#================================================#

function status($id) {
    global $admin, $connect;
    $admin = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `admins` WHERE `user_id` = '{$id}'"));
    if ($id == $admin || $admin['id']) {
        return true;
    }
}

function step($id,$step){
global $connect;
mysqli_query($connect,"UPDATE users SET step = '$step' WHERE user_id = $id");
exit();
}

#================================================#

$update = json_decode(file_get_contents('php://input'));



$message = $update->message;
$message_id = $message->message_id;
$cid = $message->chat->id;
$cid2 = $update->callback_query->message->chat->id;
$mid = $update->callback_query->message->message_id;
$qid = $update->callback_query->id;
$text = $message->text;
$data = $update->callback_query->data;
$step = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM users WHERE user_id = $cid"))['step'];
$name_mes = $message->from->first_name;
$name_call = $update->callback_query->from->first_name;
$name = ($name_mes) ? $name_mes : $name_call;
$type=$message->chat->type;
$reply_markup = $update->message->reply_markup;
$forward_id=$update->message->forward_from_chat->id;

$document = $message->document->file_id;
$document_id = $message->document->file_id;
$document_name = $message->document->file_name;

$photo = $message->photo;
$photo_id = $message->photo[count($message->photo) - 1]->file_id;

$video = $message->video;
$video_id = $video->file_id;
$video_name = $video->file_name;
$video_size = $video->file_size;
$video_size2 = $video_size/1000;
$video_type = $video->mime_type;

$bot=bot(getMe)->result->username;
$time = date("d.m.Y | H:i");
$soat = date("H:i:s");
$sana = date("Y.m.d");

if($text == "/time"){
sms($cid, "<code>$time</code>");
}

mkdir("settings");
mkdir("file");
mkdir("chennels");
mkdir("zayavka");

$botdel = $update->my_chat_member->new_chat_member;
$botdelid = $update->my_chat_member->from->id;
$userstatus= $botdel->status;


$join_chatid = $update->chat_join_request->chat->id;
$join_userid = $update->chat_join_request->from->id;
$join_userstatus = $update->chat_join_request->chat->type;
if($join_userstatus == "channel" or $join_userstatus == "supergroup"){
$get = file_get_contents("zayavka/$join_chatid");
if(mb_stripos($get,$join_userid)==false){
file_put_contents("zayavka/$join_chatid", "$get\n$join_userid");
}
}

if(!file_get_contents('instagram.link')){
    file_put_contents('instagram.link', "https://instagram.com");
}

if($botdel){
if($userstatus=="kicked"){
mysqli_query($connect,"UPDATE users SET status = 'deactive' WHERE user_id = $botdelid");
} 
}

#=================== < Add user > ================#

if($message){
$result = mysqli_query($connect,"SELECT * FROM users WHERE user_id = $cid");
$rew = mysqli_fetch_assoc($result);
if(!$rew){
$data = date("Y.m.d");
mysqli_query($connect,"INSERT INTO users (user_id,status,data,step) VALUES ('$cid','active','$data','0')");
}else{
if($rew['status']!=="active"){
mysqli_query($connect,"UPDATE users SET status = 'active' WHERE user_id = $cid");
}
}
}

#================================================#



