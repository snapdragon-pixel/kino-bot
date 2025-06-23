<?php
date_default_timezone_set("Asia/Tashkent");

require ("../setting.php");
require ("../sql.php");
require ("function.php");
require ("buttons.php");

#=================== < WebHook > ================#

echo file_get_contents("https://api.telegram.org/bot".API_TOKEN."/setwebhook?url=".$_SERVER['SERVER_NAME']."".$_SERVER['SCRIPT_NAME']);

#=================== < Start > ==================#

$setting = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `settings` WHERE `id` = '1'"));

if($text == "/rand"){
$film = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM films ORDER BY RAND() LIMIT 1"));
$key = json_encode([
'inline_keyboard'=>[
[['text'=> ($cid == $admin) ? "🗑 O'chirish" : "",'callback_data'=>"del-kino-$film[id]"]],
]
]);    
bot('sendChatAction', [
'chat_id'=>$cid,
'action'=>'upload_video'
]);
downloads($film['id']);
video($cid, $film['film_id'],"<b>🖐 Salom <a href='tg://user?id=$cid'>$name</a> 

<blockquote>/rand - 🔄 Random kinolar
/help - ☎️ Qo'llab quvvatlash
/dev - 🧑‍💻 Dasturchi</blockquote>

<a href='https://t.me/{$setting['movie_channel']}'>📥 Kanalimiz</a> - orqali ko'proq kodlarni olishingiz mumkin!</b>", 
($cid == $admin) ? $key : json_encode([
'inline_keyboard'=>[
[['text'=>"🔎 Kino kodlari",'url'=>"https://t.me/{$setting['movie_channel']}"]],
]
]));
exit();
}


if($text == "/start" and $type == "private" and joinchat($cid)){
sms($cid, "<b>🖐 Salom <a href='tg://user?id=$cid'>$name</a> 

<blockquote>/rand - 🔄 Random kinolar
/help - ☎️ Qo'llab quvvatlash
/dev - 🧑‍💻 Dasturchi</blockquote>

<a href='https://t.me/{$setting['movie_channel']}'>📥 Kanalimiz</a> - orqali ko'proq kodlarni olishingiz mumkin!</b>", json_encode([
'inline_keyboard'=>[
[['text'=>"🔎 Kino kodlari",'url'=>"https://t.me/{$setting['movie_channel']}"]],
]
]));
step($cid,0);
exit();
}

if($text == "/help" and $type == "private" and joinchat($cid)){
    sms($cid, "🧑‍💻 <b>Savol va Takliflar bo'lsa pastdagi manzilimizga murojaat qilishingiz mumkin!</b>", json_encode([
    'inline_keyboard'=>[
    [['text'=>"☎️ Qo'llab quvvatlash",'url'=>"tg://user?id=$support"]],
    ]
    ]));
    step($cid, "");
    exit();
}

if($text == "/dev" and $type == "private" and joinchat($cid)){
    sms($cid, "<b>🧑‍💻 Botning dasturchisi: Olloyor web

❗️ Eslatma: Bot dasturchisini bu botga hech qanday aloqasi yo'q!

<blockquote>💵 Bot yaratish: 200.000 so'm.
📆 Oylik toʻlov: 50.000 so'm.
🎁 Bonus sifatida birinchi oy bepul.</blockquote>

⬇️ Sizga ham shunday turdagi bot kerak boʻlsa bizga murojaat qilishingiz mumkin!</b>", json_encode([
    'inline_keyboard'=>[
    [['text'=>"🧑‍💻 Dasturchi",'url'=>"tg://user?id=$admin"]],
    ]
    ]));
    step($cid, "");
    exit();
}


if($data == "Check" and joinchat($cid2)){
del();
sms($cid2, "<b>🖐 Salom <a href='tg://user?id=$cid'>$name</a> 

<blockquote>/rand - 🔄 Random kinolar
/help - ☎️ Qo'llab quvvatlash
/dev - 🧑‍💻 Dasturchi</blockquote>

📥 Kanalimiz - orqali ko'proq kodlarni olishingiz mumkin!</b>", json_encode([
'inline_keyboard'=>[
[['text'=>"🔎 Kino kodlari",'url'=>"https://t.me/kino_kodlari_olish"]],
]
]));
step($cid2,0);
exit();
}



if(mb_stripos($data,"check-")!==false){
$id = explode("-",$data)[1];
del();
kino($cid2,$id);
exit();
}
#================================================#

require ("panel.php");

#================================================#

if($text and $step == 0  and $type == "private"){
kino($cid,$text);
}

#================================================#

if(!$connect){
echo "Ulanmadi";
}else{
echo "Ulandi";
}




?>