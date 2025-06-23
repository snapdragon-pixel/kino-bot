<?php

function admin($category, $user_id){
    global $admin, $connect;
    $admin_status = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `admins` WHERE `user_id` = '$user_id'"));
    $status = json_decode($admin_status['status'],1);
    if($user_id == $admin){
        return "true";
    }
    if($category == null){
        if($admin_status['id']){
         return "true";
        }
    }
    if($status["$category"] == "on" or $user_id == $admin){
        return "true";
    }
}

#=================== < Panel > ===================

if(($text == "/panel" or $text == "⏪ Orqaga") and admin(null, $cid) == "true"){
sms($cid, "<b>Admin paneliga xush kelibsiz!</b>", $panel_menu);
step($cid,0);
exit();
}

#=================== < Statistics > ==============

function sana($day){
    global $connect;
$day = "-$day days";
$day = date('d.m.Y', strtotime($day));
return mysqli_num_rows(mysqli_query($connect, "SELECT * FROM users WHERE data = '$day'"));
}

echo date('d.m.Y', strtotime("-1 days"));


if($text == "📊 Statistika"){
if(admin("statistics", $cid) == "true"){
$sana = date("Y.m.d");
$load = sys_getloadavg();
$result = mysqli_query($connect, "SELECT * FROM users");
$films = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM films"));

$all = mysqli_num_rows($result);
$actives = 0;
$deactives = 0;
while($users=mysqli_fetch_assoc($result)){
if($users['status']=="active"){
$actives++;
}elseif($users['status']=="deactive"){
$deactives++;
}
}

$bugun = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM users WHERE data = '".date("Y.m.d")."'"));
$uch_kun = $bugun + sana(1) + sana(2);
$yetti_kun = $uch_kun + sana(3) + sana(4) + sana(5) + sana(6);
$on_kun = $yetti_kun + sana(7) + sana(8) + sana(9) + sana(10);
$yigirma_kun = $on_kun + sana(11) + sana(12) + sana(13) + sana(14) + sana(15) + sana(16) + sana(17) + sana(18) + sana(19) + sana(20);
$otiz_kun = $yigirma_kun + sana(21) + sana(22) + sana(23) + sana(24) + sana(25) + sana(26) + sana(27) + sana(28) + sana(29) + sana(30);

sms($cid, "💡 <b>O'rtacha yuklanish:</b> <code>$load[0]</code>

<b>• Obunachilar soni:</b> ".number_format($all, 0, ',', ' ')." ta

<b>• Oxirgi 24 soatda</b> — ".number_format($bugun, 0, ',', ' ')." ta obunachi qo'shildi
<b>• Oxirgi 3 kunda</b> — ".number_format($uch_kun, 0, ',', ' ')." ta obunachi qo'shildi
<b>• Oxirgi 7 kunda</b> — ".number_format($yetti_kun, 0, ',', ' ')." ta obunachi qo'shildi
<b>• Oxirgi 10 kunda</b> — ".number_format($on_kun, 0, ',', ' ')." ta obunachi qo'shildi
<b>• Oxirgi 20 kunda</b> — ".number_format($yigirma_kun, 0, ',', ' ')." ta obunachi qo'shildi
<b>• Oxirgi 30 kunda</b> — ".number_format($otiz_kun, 0, ',', ' ')." ta obunachi qo'shildi

🎬 <b>Yuklangan kinolar:</b> $films ta");
}else{
sms($cid, "<b>⚠️ Ushbu bo'lim siz uchun emas!</b>",null);
}
step($cid,0);
exit();
}


#=================== < Channels > ================

if($text == "📢 Kanallarni sozlash"){
if(admin("channel", $cid) == "true"){
sms($cid, "📢 <b>Quyidagilardan birini tanlang:</b>", json_encode([
'inline_keyboard'=>[
[['text'=>"📋 Roʻyhat",'callback_data'=>"channel"]],
[['text'=>"➕ Qo'shish",'callback_data'=>"add_channel"],['text'=>"🗑 O'chirish",'callback_data'=>"del_channel"]],
[['text'=>"🔢 Kino kodlar kanali",'callback_data'=>"movie_code"]],
]
]));
}else{
sms($cid, "<b>⚠️ Ushbu bo'lim siz uchun emas!</b>",null);
}
step($cid,0);
exit();
}

if($data == "movie_code" and admin("channel", $cid2) == "true"){
$channel = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `settings` WHERE `id` = '1'"))['movie_channel'];
edit($cid2,$mid, "📢 <b>Kanal:</b> " . (($channel) ? "@" . $channel : "Kiritilmagan"), json_encode([
'inline_keyboard'=>[
[['text'=>(($channel) ? "✏️ Tahrirlash" : "➕ Kiritish"),'callback_data'=>"edit_movie_code"]],
[['text'=>"⏪ Orqaga",'callback_data'=>"channels"]]
]
]));
step($cid2,0);
exit();
}

if($data == "edit_movie_code"){
del();
sms($cid2, "<b>Kanal havolasii kiriting:</b> \n\n@kanal", $back);
step($cid2,"edit_movie_code");
exit();
}

if($step == "edit_movie_code"){
sms($cid, "<b>✅ Qabul qilindi!</b>", $panel_menu);
$channel = str_replace(["@", "https://t.me/",],["", ""], $text);
mysqli_query($connect,"UPDATE `settings` SET `movie_channel` = '{$channel}' WHERE `id` = '1'");
step($cid, "");
exit();
}

if($data == "channels" and admin("channel", $cid2) == "true"){
edit($cid2,$mid, "📢 <b>Quyidagilardan birini tanlang:</b>", json_encode([
'inline_keyboard'=>[
[['text'=>"📋 Roʻyhat",'callback_data'=>"channel"]],
[['text'=>"➕ Qo'shish",'callback_data'=>"add_channel"],['text'=>"🗑 O'chirish",'callback_data'=>"del_channel"]],
[['text'=>"🔢 Kino kodlar kanali",'callback_data'=>"movie_code"]],
]
]));
step($cid2,0);
exit();
}




if($data == "add_channel"){
edit($cid2,$mid, "<b>⏬ Kanal turini tanlang:</b>",json_encode([
'inline_keyboard'=>[
[['text'=>"Omaviy",'callback_data'=>"popular"],['text'=>"Shaxsiy",'callback_data'=>"personal"]],
[['text'=>"Zayavka",'callback_data'=>"zayavka"]],
[['text'=>"Odiy havola",'callback_data'=>"link"]],
[['text'=>"⏪ Orqaga",'callback_data'=>"channels"]]
]
]));
}

if ($data == "popular" or $data == "personal"){
del();
sms($cid2, "<b>Majbur obunaga qo'shmoqchi bo'lgan kanaldan (forward) shaklida habar olib yuboring:</b>", $back);
step($cid2,"popular");
exit();
}

if ($data == "link"){
    del();
    sms($cid2, "<b>Havola kiriting:</b> \n\nNamuna: https://t.me/havola", $back);
    step($cid2,"link");
    exit();
}

if($step == "link" and admin("channel", $cid) == "true"){
    sms($cid, "<b>✅ Qabul qilindi!</b>", $panel_menu);
    mysqli_query($connect,"INSERT INTO channels (`url`,`id`,`type`,`members`) VALUES ('$text','','link','')");
    step($cid, "");
    exit();
}


if($step == "popular" and admin("channel", $cid) == "true"){
$name = bot('getChat',['chat_id'=>$forward_id])->result->title;
$username = bot('getChat',['chat_id'=>$forward_id])->result->username;
$invite_link = bot('getChat',['chat_id'=>$forward_id])->result->invite_link;
if($username){ $url = "https://t.me/$username"; }else{ $url = "$invite_link"; }
if($forward_id){
    
if(getAdmin($forward_id)!= true){
sms($cid, "<b>Bot ushbu kanalda admin emas!</b>", $back);
}else{
$channel = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM channels WHERE id = '$forward_id'"))['url'];
if(!$channel){
sms($cid, "<b>✅ $name - kanali qabul qilindi!</b>", json_encode([
'inline_keyboard'=>[
[['text'=>"$name",'url'=>$url]],
]
]));   
$mambers=bot('getChatMembersCount',[ 'chat_id'=>$forward_id])->result;
mysqli_query($connect,"INSERT INTO channels (`url`,`id`,`type`,`members`) VALUES ('$url','$forward_id','popular_personal','$mambers')");
step($cid,0);
}else{
sms($cid, "<b>⚠️ Ushbu kanal majbur obuna kanallar ro'yhatida mavjut!</b>", $back);
}
}

}else{
sms($cid, "<b>Majbur obunaga qo'shmoqchi bo'lgan kanaldan (forward) shaklida habar olib yuboring:</b>", $back);
}
exit();
}


if($data == "zayavka"){
del();
sms($cid2, "<b>Majbur obunaga qo'shmoqchi bo'lgan kanaldan (forward) shaklida habar olib yuboring:</b>", $back);
step($cid2,"zayavka");
exit();
}if($step == "zayavka" and admin("channel", $cid) == "true"){
$name = bot('getChat',['chat_id'=>$forward_id])->result->title;
$username = bot('getChat',['chat_id'=>$forward_id])->result->username;
$invite_link = bot('getChat',['chat_id'=>$forward_id])->result->invite_link;
if($username){ $url = "https://t.me/$username"; }else{ $url = "$invite_link"; }
if($forward_id){
    
if(getAdmin($forward_id)!= true){
sms($cid, "<b>⚠️ Bot ushbu kanalda admin emas!</b>\n\n<i>Botni kanalda admin qilib, qayta urinib ko'ring.</i>", $back);
}else{
$channel = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM channels WHERE id = '$forward_id'"))['url'];
if(!$channel){
sms($cid, "<b>✅ $name - kanali qabul qilindi!

🔗 Zayavka havolasini kiriting</b>
Namuna: https://t.me/+pCGPrlftcbRhMjVie", json_encode([
'inline_keyboard'=>[
[['text'=>"$name",'url'=>$url]],
]
]));   
$mambers=bot('getChatMembersCount',[ 'chat_id'=>$forward_id])->result;
mysqli_query($connect,"INSERT INTO channels (`url`,`id`,`type`,`members`) VALUES ('','$forward_id','zayavka','$mambers')");
step($cid,"zayavka_link=$forward_id");
}else{
sms($cid, "<b>⚠️ Ushbu kanal majbur obuna kanallar ro'yhatida mavjut!</b>", $back);
}
}

}else{
sms($cid, "<b>Majbur obunaga qo'shmoqchi bo'lgan kanaldan (forward) shaklida habar olib yuboring:</b>", $back);
}
exit();
}if(mb_stripos($step, "zayavka_link=")!==false){
$id = explode("=",$step)['1'];
$channel = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM channels WHERE id = '$id'"));
sms($cid, "<b>✅ Qabul qilindi!</b>", $panel_menu);
file_put_contents("zayavka/$id","");
mysqli_query($connect,"UPDATE channels SET url = '$text' WHERE id = $id");
step($cid,0);
exit();
}




if($data == "channel"){
$channels = mysqli_query($connect, "SELECT * FROM channels");
if(mysqli_num_rows($channels)){
while($channel = mysqli_fetch_assoc($channels)){
$name = bot('getChat',['chat_id'=>$channel['id']])->result->title ?? "❌ Xatolik!";
$name = $channel['type'] == "link" ? $channel['url'] : $name;
$ky[]=['text'=>$name,'callback_data'=>"channel=".$channel['id']];
}
$keyboard=array_chunk($ky,1);
$keyboard[]=[['text'=>"⏪ Orqaga",'callback_data'=>"channels"]];
$kb=json_encode([
'inline_keyboard'=>$keyboard,
]);  
edit($cid2,$mid, "<b>📢 Quyidagilardan birini tanlang:</b>",$kb);
}else{
query($qid, "❌ Hech qanday kanallar ulanmagan!");
}
exit();
}


if(mb_stripos($data, "channel=")!==false){
edit($cid2,$mid, "<b>⏱ Yuklanmoqda...</b>");
$id = explode("=",$data)['1'];
$channel = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM channels WHERE id = '$id'"));
$mambers=bot('getChatMembersCount',[ 'chat_id'=>$id])->result;
$name = bot('getChat',['chat_id'=>$channel['id']])->result->title ?? "❌ Xatolik!";
$name = $channel['type'] == "link" ? $channel['url'] : $name;
$otdi = $mambers - $channel['members'];
if($channel['type'] == "popular_personal"){
$text = "<b>👤 Obunachilar:</b> \n- Oldin: $channel[members] ta \n- Hozir: $mambers ta \n- Qo'shildi: $otdi ta";
}else if($channel['type'] == "zayavka"){
$jami = substr_count(file_get_contents("zayavka/$id"),"\n");
$text = "<b>👤 Zayavkalar:</b> \n- Jami: $jami ta";
}
edit($cid2,$mid, "<b>🔔 Kanal malumotlari:</b>

$text",json_encode([
'inline_keyboard'=>[
[['text'=>"$name",'url'=>$channel['url']]],
[['text'=>"⏪ Orqaga",'callback_data'=>"channel"]]
]
]));
exit();
}


if($data == "del_channel"){
$channels = mysqli_query($connect, "SELECT * FROM channels");
if(mysqli_num_rows($channels)){
while($channel = mysqli_fetch_assoc($channels)){
$name = bot('getChat',['chat_id'=>$channel['id']])->result->title ?? "❌ Xatolik!";
$name = $channel['type'] == "link" ? $channel['url'] : $name;
$ky[]=['text'=>$name,'callback_data'=>"del_".$channel['number']];
}
$keyboard=array_chunk($ky,1);
$keyboard[]=[['text'=>"⏪ Orqaga",'callback_data'=>"channels"]];
$kb=json_encode([
'inline_keyboard'=>$keyboard,
]);  
edit($cid2,$mid, "<b>🗑 O'chirish kerak bo'lgan kanalni tanlang!</b>",$kb);
}else{
query($qid, "❌ Hech qanday kanallar ulanmagan!");
}
exit();
}


if(mb_stripos($data, "del_")!==false){
del();
$id = explode("_",$data)['1'];
$name = bot('getChat',['chat_id'=>$id])->result->title ?? "❌ Xatolik!";
sms($cid2,"<b>🗑️ Kanal muvafiqiyatli ochirildi.</b>", $panel_menu);
mysqli_query($connect,"DELETE FROM channels WHERE number=$id");
unlink("zayavka/".$id."");
}


#==================== Add Movie =============================

if(admin("movie", $cid) == "true" && $video){
    $time = date("d.m.Y");
    $save = mysqli_query($connect,"INSERT INTO films (`film_id`,`film_name`,`film_date`,`downloads`) VALUES ('$video_id','','$time','0')");
    if ($save) {
        $id = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `films` ORDER BY id DESC LIMIT 1"))['id'];
        bot('sendMessage',[
            'chat_id'=>$cid,
            'text'=>"<b>✅ Kino yuklandi!</b>\n\n<b>🔎 Kino kodi:</b> <code>$id</code>",
            'parse_mode'=>'html',
            'reply_to_message_id'=>$message_id
        ]);
    }
    mysqli_query($connect,"UPDATE users SET step = '0' WHERE user_id = $cid");
exit();
}

if(mb_stripos($data, "del-kino-")!==false){
del();
$id = explode("-",$data)['2'];
sms($cid2, "<b>✅ Kino o'chirildi!</b>", $panel_menu);
mysqli_query($connect,"DELETE FROM films WHERE id=$id");
mysqli_query($connect,"UPDATE users SET step = '0' WHERE user_id = $cid2");  
exit();
}

#======================== EDIT KINO ================================

if($data == "edit"){
del();
sms($cid2,"<b>📝 Tahrirlamoqchi bo'lgan kino kodini yuboring:</b>",$back);
mysqli_query($connect,"UPDATE users SET step = 'edit' WHERE user_id = $cid2");
exit();
}

if(mb_stripos($data, "edit-")!==false){
del();
$type = explode("-",$data)['1'];
$id = explode("-",$data)['2'];
if($type == "caption"){
sms($cid2,"✍🏻 Yangi malumot kiriting...",null);
}else if($type == "kino"){
sms($cid2,"🎥 Yngi kino yuboring...",null);
}
mysqli_query($connect,"UPDATE users SET step = 'edit-$type-$id' WHERE user_id = $cid2");  
exit();
}

if(mb_stripos($step, "edit-")!==false){
$type = explode("-",$step)['1'];
$id = explode("-",$step)['2'];
if($type == "caption"){
$film = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM films WHERE id = '$id'"));
video($cid,$film['film_id'],"<b>✅ Tahrirlandi!</b>\n\n<b>🔎 Kino kodi:</b> <code>".$film['id']."</code>\n\n<b>$text</b>\n\n<b>🤖 Botimiz:</b> @$bot\n\n<b>👁 Ko'rishlar:</b> $film[downloads] ta",json_encode([
'inline_keyboard'=>[
[['text'=>"🗑 O'chirish",'callback_data'=>"del-kino-$film[id]"]],
[['text'=>"🔎 Boshqa kodlar",'url'=>"https://t.me/kino_kodlari_olish"]],
[['text'=>"↗️ Do'stlarga yuborish",'url'=>"https://t.me/share/url/?url=https://t.me/$bot?start=$film[id]"]],
]
]));
$name = base64_encode($text);
mysqli_query($connect,"UPDATE films SET film_name = '$name' WHERE id = $id"); 
}else{
if(isset($video)){
$film = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM films WHERE id = '$id'"));
$name = base64_decode($film['film_name']);
video($cid,$video_id,"<b>✅ Tahrirlandi!</b>\n\n<b>🔎 Kino kodi:</b> <code>".$film['id']."</code>\n\n<b>$name</b>\n\n<b>🤖 Botimiz:</b> @$bot\n\n<b>👁 Ko'rishlar:</b> $film[downloads] ta",json_encode([
'inline_keyboard'=>[
[['text'=>"🗑 O'chirish",'callback_data'=>"del-kino-$film[id]"]],
[['text'=>"🔎 Boshqa kodlar",'url'=>"https://t.me/kino_kodlari_olish"]],
[['text'=>"↗️ Do'stlarga yuborish",'url'=>"https://t.me/share/url/?url=https://t.me/$bot?start=$film[id]"]],
]
])); 
mysqli_query($connect,"UPDATE films SET film_id = '$video_id' WHERE id = $id"); 
}else{
sms($cid,"<b>🎥 Kinoni (.mp4) farmatda yuboring:</b>");
}
}
mysqli_query($connect,"UPDATE users SET step = '0' WHERE user_id = $cid");  
}

#===================================================================

$sresult = mysqli_query($connect, "SELECT * FROM `send`");
$srow = mysqli_fetch_assoc($sresult);
if(!$srow){
$send_key = json_encode([
'inline_keyboard'=>[
  [['text'=>"📬 Oddiy xabar",'callback_data'=>"send-CopyMessage"]],
  [['text'=>"📬 Foward xabar",'callback_data'=>"send-ForwardMessage"]],
]]);
}else{
$send_key = json_encode([
'inline_keyboard'=>[
  [['text'=>"⏳ Xabar holati",'callback_data'=>"holaat"]],
  [['text'=>"🔴 Xabarni yakunlash",'callback_data'=>"off"]],
]]);
}



if($text == "📬 Xabar Yuborish"){
if(admin("smessage", $cid) == "true"){
sms($cid,"<b>📨 Xabar turini tanlang!</b>",$send_key);
}else{
sms($cid, "<b>⚠️ Ushbu bo'lim siz uchun emas!</b>",null);
}
step($cid,0);
exit();
}


if($data == "boshqarish" and admin(null, $cid2) == "true"){
edit($cid2,$mid,"<b>📨 Xabar turini tanlang!</b>",$send_key);
}

if($data=="holaat"){
$result = mysqli_query($connect, "SELECT * FROM `send`");
$row = mysqli_fetch_assoc($result);
if(!$row){
query($qid,"Xabar mavjud emas ❗");
}else{
query($qid,"📊 Yangilandi");
$send = mysqli_query($connect, "SELECT * FROM send"); 
$send1 = mysqli_fetch_assoc($send); 
$xabar = $send1['message']; 
$member = $send1['start']; 
$limit = $send1['limit']; 
$succes = $send1['succes']; 
$time1 = $send1['time1']; 
$holatiii = $send1['holat']; 
$types = $send1['type']; 
edit($cid2,$mid, "<b>🗒️ Xabar xaqida:
🕛 Boshlangan vaqti: $time1
⤴️ Kimga: Userlarga
📤 Yuborildi: $succes ta
⚙️ Xabar turi: $types
📈 Status: $holatiii </b>",json_encode([
'inline_keyboard'=>[
[['text'=>"🔄 Yangilash",'callback_data'=>"gov"],['text'=>"❌ Bosh Menu", 'callback_data'=>"boshqarish"]],
]])
);
exit();
}
}


if($data=="gov"){
$result = mysqli_query($connect, "SELECT * FROM `send`");
$row = mysqli_fetch_assoc($result);
if(!$row){
query($qid,"Xabar mavjud emas ❗");
}else{
query($qid,"📊 Yangilandi");
$send = mysqli_query($connect, "SELECT * FROM send"); 
$send1 = mysqli_fetch_assoc($send); 
$xabar = $send1['message']; 
$member = $send1['start']; 
$limit = $send1['limit']; 
$succes = $send1['succes']; 
$time1 = $send1['time1']; 
$holatiii = $send1['holat']; 
$types = $send1['type']; 
bot('editMessageText',[
'chat_id'=>$cid2,
'message_id'=>$mid,

'text'=>"<b>🗒️ Xabar xaqida:
🕛 Boshlangan vaqti: $time1
⤴️ Kimga: Userlarga
📤 Yuborildi: $succes ta
⚙️ Xabar turi: $types
📈 Status: $holatiii </b>",
'parse_mode'=>'html',
'reply_markup'=>json_encode([
  'inline_keyboard'=>[
  [['text'=>"🔄 Yangilash",'callback_data'=>"holaat"],['text'=>"❌ Bosh Menu", 'callback_data'=>"boshqarish"]],
  ]])
]); exit();
}
}

if($data =="off"){
$result = mysqli_query($connect, "SELECT * FROM `send`");
$row = mysqli_fetch_assoc($result);
if(!$row){
bot ('answerCallbackQuery', [
'callback_query_id'=> $qid,
'text'=>"Xabar mavjud emas ❗",
'show_alert'=>true,
]);
exit();
}else{
bot('editMessageText',[
'chat_id'=>$cid2,
'message_id'=>$mid,
'text'=>"<b>😳 Xabar yuborish toʻxtatilsinmi </b>",
'parse_mode'=>'html',
'reply_markup'=>json_encode([
  'inline_keyboard'=>[
[['text'=>"⛔ Toʻxtatish", 'callback_data'=>"sendP"]],
]]),
]); exit ();
}
}

if($data =="sendP"){
$result = mysqli_query($connect, "SELECT * FROM `send`");
$row = mysqli_fetch_assoc($result);
if(!$row){
bot ('answerCallbackQuery', [
'callback_query_id'=> $qid,
'text'=>"Xabar mavjud emas ❗",
'show_alert'=>true,
]);exit ();}else{
mysqli_query($connect, "DELETE FROM send"); 
bot('editMessageText',[
'chat_id'=>$cid2,
'message_id'=>$mid,
'text'=>"<b>🛑 Muofaqyatli yakunlandi. </b>",
'parse_mode'=>'html',
'reply_markup'=>json_encode([
  'inline_keyboard'=>[
[['text'=>"⛔ Bosh Menu", 'callback_data'=>"boshqarish"]],
  ]]),
]); exit ();
}
}


if(mb_stripos($data,"send-")!==false){
	$ex = explode("-",$data)[1];
	$result = mysqli_query($connect, "SELECT * FROM `send`");
$row = mysqli_fetch_assoc($result);
if(!$row){
$memSQL = mysqli_query($connect, "SELECT * FROM users"); 
$mem_ta = mysqli_num_rows($memSQL); 
del();
bot('sendMessage',[
'chat_id'=>$cid2,
'text'=>"<b>$mem_ta ta foydalanuvchiga yuboriladigan xabarni yuboring:</b>", 
'parse_mode'=>'html', 
'reply_markup'=>$back, 
]); 
mysqli_query($connect,"UPDATE users SET step = 'send' WHERE user_id = $cid2");  
file_put_contents("send.type","$ex");
}else{
del();
bot('sendMessage',[
'chat_id'=>$cid2,
'text'=>"<b>🔵 Botda habar yuborish davom etmoqda..</b>", 
'parse_mode'=>'html', 
'reply_markup'=>$panel,
]);
}}

if($step == "send"){ 
if($text=="🎛 Boshqaruv"){ 
mysqli_query($connect,"UPDATE users SET step = '0' WHERE user_id = $cid");   
}else{ 
$res = mysqli_query($connect,"SELECT * FROM users"); 
$mem_ta = mysqli_num_rows($res); 
$resmem = mysqli_query($connect,"SELECT * FROM users WHERE id = '$mem_ta'"); 
$row = mysqli_fetch_assoc($resmem); 
$left = $row['user_id'];
$button = json_encode($update->message->reply_markup);
$button = base64_encode($button);
bot('sendMessage',[ 
'chat_id'=>$cid, 
'text'=>"<b>Qabul qilindi. 
 
Minutiga yuboriladi:</b> 100 ta(odamga) 
 
Diqqat, Xabar yuborish jarayonida muammo chiqmasligi uchun miqdorni kamroq belgilash tavsiya etiladi!", 
'parse_mode'=>'html', 
"reply_markup"=>json_encode([  
'inline_keyboard'=>[  
[['text'=>"50",'callback_data'=>"limit-50"],['text'=>"✅ 100",'callback_data'=>"limit-100"],['text'=>"150",'callback_data'=>"limit-150"]], 
[['text'=>"✉️ Xabarni boshlash",'callback_data'=>"startsend"]], 
]]) 
]); 
mysqli_query($connect, "INSERT INTO send (`message`,`start`,`limit`,`succes`,`left`,`time1`,`time2`,`time3`,`time4`,`time5`,`mesid`,`mesid2`,`holat`,`type`,`button`) VALUES ('$message_id','$mem_ta','100','0','$left','null','null','null','null','null','null','null','active','null','$button')"); 
mysqli_query($connect,"UPDATE users SET step = '0' WHERE user_id = $cid");  
exit(); 
}} 
 
if(mb_stripos($data,"limit-")!==false){
$ex = explode("-",$data);
$limit = $ex[1];
if($limit=="50"){$i50="✅ 50";}else{$i50="50";} 
if($limit=="100"){$i100="✅ 100";}else{$i100="100";} 
if($limit=="150"){$i150="✅ 150";}else{$i150="150";} 
mysqli_query($connect, "UPDATE send SET limit = '$limit'"); 
bot('editMessageText',[ 
'chat_id'=>$cid2, 
'message_id'=>$mid, 
'text'=>"<b>Muvaffaqiyatli o'zgartirildi. 
 
Minutiga yuboriladi:</b> $limit ta(odamga) 
 
Ma'lumotlarni sozlab chiqing hamda tayyor bo'lgach tekshirib (<b>✉️ Xabarni boshlash</b>) tugmasini bosing!", 
'parse_mode'=>'html', 
"reply_markup"=>json_encode([  
'inline_keyboard'=>[ 
[['text'=>"$i50",'callback_data'=>"limit-50"],['text'=>"$i100",'callback_data'=>"limit-100"],['text'=>"$i150",'callback_data'=>"limit-150"]], 
[['text'=>"✉️ Xabarni boshlash",'callback_data'=>"startsend"]], 
]]) 
]); 
} 
 
if($data=="startsend"){ 
$time1 = date('H:i', strtotime('+1 minutes')); 
$time2 = date('H:i', strtotime('+2 minutes')); 
$time3 = date('H:i', strtotime('+3 minutes')); 
$time4 = date('H:i', strtotime('+4 minutes')); 
$time5 = date('H:i', strtotime('+5 minutes')); 
mysqli_query($connect, "UPDATE `send` SET time1 = '$time1', time2 = '$time2', time3 = '$time3', time4 = '$time4', time5 = '$time5'"); 
$type = file_get_contents("send.type");
mysqli_query($connect, "UPDATE `send` SET type = '$type'"); 
unlink("send.type");
del();
$send = mysqli_query($connect, "SELECT * FROM `send`"); 
$send1 = mysqli_fetch_assoc($send); 
$member = $send1['start']; 
$info=bot('SendMessage',[ 
'chat_id'=>$admin, 
'text'=>"<b>Xabar yuborish jarayoni:</b> Kutilmoqda.. 
 
<b>Yuborildi:</b> (0) - <b>Kutilmoqda:</b> ($member)", 
'parse_mode'=>'html', 
"reply_markup"=>$panel_menu,
])->result->message_id; 
mysqli_query($connect, "UPDATE `send` SET mesid2 = '$info'"); 
}

if($text == "👤 Adminlar"){
    if($admin == $cid){
        sms($cid, "<b>Admin sozlamalaridasiz:</b>", json_encode([  
            'inline_keyboard'=>[ 
                [['text'=>"➕ Qo'shish",'callback_data'=>"addadmin"],['text'=>"🗑 O'chirish",'callback_data'=>"deladmin"]], 
                [['text'=>"📋 Ro'yhat",'callback_data'=>"admins"]], 
            ]]));
        exit();
    }else{
        sms($cid, "<b>⚠️ Ushbu bo'lib faqat asosiy admin uchun!</b>", null);
        exit();
 }}


if($data == "addadmin" and $admin == $cid2){
    del();
    sms($cid2, "<b>🆔 Admin ID yuboring:</b>", $back);
    step($cid2, "addadmin");
}

if($step == "addadmin" and $admin == $cid){
    $admin = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `admins` WHERE `user_id` = '$text'")); 
    if(!$admin['id']){
        $status['channel'] = 'on';
        $status['statistics'] = 'on';
        $status['smessage'] = 'on';
        $status['movie'] = 'on';
        $status = json_encode($status);
        mysqli_query($connect, "INSERT INTO admins (`user_id`,`status`) VALUES ('$text','$status')");  
        sms($cid, "<b>✅ Admin qo'shildi!</b>", $panel_menu);
        step($cid, 0);
        exit();
    }else{
        sms($cid, "<b>⚠️ Ushbu foydalanuvchi adminlar ro'yhati mavjut!</b>");
        exit();
    }
}

if($data == "deladmin" and $admin == $cid2){
$admins = mysqli_query($connect, "SELECT * FROM admins");
if(mysqli_num_rows($admins)){
del();
while($admin = mysqli_fetch_assoc($admins)){
$ky[]=['text'=>$admin['user_id'],'callback_data'=>"deladmi-".$admin['id']];
}
$keyboard=array_chunk($ky,1);
$kb=json_encode([
'inline_keyboard'=>$keyboard,
]);  
sms($cid2, "📋 <b>Adminlar ro'yhati:</b>", $kb);
}else{
bot ('answerCallbackQuery', [
'callback_query_id'=> $qid,
'text'=>"⚠️ Adminlar qo'shilmagan!",
'show_alert'=>true,
]);
}
exit();
}

if((stripos($data,"deladmi-")!==false)){
$id = explode("-", $data)[1];
del();
mysqli_query($connect, "DELETE FROM admins WHERE id = '$id'"); 
sms($cid2, "<b>✅ Admin o'chirildi!</b>", $panel_menu);
exit();
}

if($data == "admins" and $admin == $cid2){
$admins = mysqli_query($connect, "SELECT * FROM admins");
if(mysqli_num_rows($admins)){
del();
while($admin = mysqli_fetch_assoc($admins)){
$ky[]=['text'=>$admin['user_id'],'callback_data'=>"editadmin-".$admin['id']];
}
$keyboard=array_chunk($ky,1);
$kb=json_encode([
'inline_keyboard'=>$keyboard,
]);  
sms($cid2, "📋 <b>Adminlar ro'yhati:</b>", $kb);
}else{
bot ('answerCallbackQuery', [
'callback_query_id'=> $qid,
'text'=>"⚠️ Adminlar qo'shilmagan!",
'show_alert'=>true,
]);
}
exit();
}

if((stripos($data,"editadmin-")!==false)){
$id = explode("-", $data)[1];
$admin = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `admins` WHERE `id` = '$id'"));
$status = json_decode($admin['status'],1);

if($status['channel'] == "on"){
$channel_btn = "✅" ;
$channel_data = "off" ;
}else{
$channel_btn = "⛔️";
$channel_data = "on" ;  
}

if($status['statistics'] == "on"){
$statistics_btn = "✅" ;
$statistics_data = "off" ;
}else{
$statistics_btn = "⛔️";
$statistics_data = "on" ;  
}

if($status['smessage'] == "on"){
$send_btn = "✅" ;
$send_data = "off" ;
}else{
$send_btn = "⛔️";
$send_data = "on" ;  
}

if($status['movie'] == "on"){
$movie_btn = "✅" ;
$movie_data = "off" ;
}else{
$movie_btn = "⛔️";
$movie_data = "on" ;  
}

del();
sms($cid2, "<b>👤 Admin imkoniyatlari:</b>", json_encode([  
'inline_keyboard'=>[
[['text'=>"📢 Kanallarni sozlash | $channel_btn",'callback_data'=>"east-channel-$channel_data-$id"]], 
[['text'=>"📊 Statistika | $statistics_btn",'callback_data'=>"east-statistics-$statistics_data-$id"]], 
[['text'=>"📬 Xabar Yuborish | $send_btn",'callback_data'=>"east-smessage-$send_data-$id"]], 
[['text'=>"🎥 Kino yuklash | $movie_btn",'callback_data'=>"east-movie-$movie_data-$id"]], 
[['text'=>"⏪ Orqaga",'callback_data'=>"admins"]], 
]]) );
exit();
}

if((stripos($data,"east-")!==false)){
$statuss = explode("-", $data)[1];
$on_off = explode("-", $data)[2];
$id = explode("-", $data)[3];
$admin = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `admins` WHERE `id` = '$id'"));
$statu = json_decode($admin['status'],1);

if($statuss == "channel"){
$status['channel'] = $on_off;
$status['statistics'] = $statu['statistics'];
$status['smessage'] = $statu['smessage'];
$status['movie'] = $statu['movie'];   
}else if($statuss == "statistics"){
$status['channel'] = $statu['channel'];
$status['statistics'] = $on_off;
$status['smessage'] = $statu['smessage'];
$status['movie'] = $statu['movie'];   
}else if($statuss == "smessage"){
$status['channel'] = $statu['channel'];
$status['statistics'] = $statu['statistics'];
$status['smessage'] = $on_off;
$status['movie'] = $statu['movie'];   
}else if($statuss == "movie"){
$status['channel'] = $statu['channel'];
$status['statistics'] = $statu['statistics'];
$status['smessage'] = $statu['smessage'];
$status['movie'] = $on_off;   
}
$status = json_encode($status);
mysqli_query($connect, "UPDATE `admins` SET `status` = '$status' WHERE `id` = '$id'"); 
$status = json_decode($status,1);
if($status['channel'] == "on"){
$channel_btn = "✅" ;
$channel_data = "off" ;
}else{
$channel_btn = "⛔️";
$channel_data = "on" ;  
}

if($status['statistics'] == "on"){
$statistics_btn = "✅" ;
$statistics_data = "off" ;
}else{
$statistics_btn = "⛔️";
$statistics_data = "on" ;  
}

if($status['smessage'] == "on"){
$send_btn = "✅" ;
$send_data = "off" ;
}else{
$send_btn = "⛔️";
$send_data = "on" ;  
}

if($status['movie'] == "on"){
$movie_btn = "✅" ;
$movie_data = "off" ;
}else{
$movie_btn = "⛔️";
$movie_data = "on" ;  
}

del();
sms($cid2, "<b>👤 Admin imkoniyatlari:</b>", json_encode([  
'inline_keyboard'=>[
[['text'=>"📢 Kanallarni sozlash | $channel_btn",'callback_data'=>"east-channel-$channel_data-$id"]], 
[['text'=>"📊 Statistika | $statistics_btn",'callback_data'=>"east-statistics-$statistics_data-$id"]], 
[['text'=>"📬 Xabar Yuborish | $send_btn",'callback_data'=>"east-smessage-$send_data-$id"]], 
[['text'=>"🎥 Kino yuklash | $movie_btn",'callback_data'=>"east-movie-$movie_data-$id"]], 
[['text'=>"⏪ Orqaga",'callback_data'=>"admins"]], 
]]) );
exit();
}

if($text == "📆 To'lov sanasi" and admin(null, $cid) == "true"){
$get = json_decode(file_get_contents("https://capitalsmm.uz/MyControllersBot/day.php?bot=$bot"),1);
    sms($cid, "<b>📆 To'lov ma'lumoti:</b> \n\n- ".$get['day']." kun qoldi.

<b>🤖 Bot:</b> @$bot");
    exit();
}

