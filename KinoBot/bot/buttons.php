<?php



#=================== < Back > ===================#

$back=json_encode([
'resize_keyboard'=>true,
'keyboard'=>[
[['text'=>"⏪ Orqaga"]],
]]);

#=================== < Back > ===================#

$panel = null;

$panel2=json_encode([
'resize_keyboard'=>true,
'keyboard'=>[
[['text'=>"🎛 Boshqaruv"]],
]]);

#=================== < Panel Menu > =============#

$panel_menu=json_encode([
'resize_keyboard'=>true,
'keyboard'=>[
[['text'=>"📢 Kanallarni sozlash"]],
[['text'=>"📊 Statistika"],['text'=>"📬 Xabar Yuborish"]],
[['text'=>"👤 Adminlar"]],
]]);

#================================================#

$kinolar=json_encode([
'resize_keyboard'=>true,
'keyboard'=>[
[['text'=>"🎬 Botda mavjud kinolar roʻyxati"]],
]]);