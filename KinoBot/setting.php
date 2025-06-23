<?php

define('API_TOKEN',"8012338614:AAGhXxY4NAW_Rjcvm_RfTXPIc4kS9oKz9Zo");

$admin = "6566152502";

$support = "6566152502";

// $admin = "6566152502";
#================================================#

function bot($method,$datas=[]){
    $url = "https://api.telegram.org/bot".API_TOKEN."/".$method;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
    $res = curl_exec($ch);
    if(curl_error($ch)){
        var_dump(curl_error($ch));
    }else{
        return json_decode($res);
    }
}

