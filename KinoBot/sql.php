<?php


#================== MySQL =======================#

define("DB_SERVER","localhost"); 
define("DB_USERNAME","saf175_kinobot"); 
define("DB_PASSWORD","yE6zW5tV0w"); 
define("DB_NAME","saf175_kinobot");
$connect = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
mysqli_set_charset($connect,"utf8mb4");

mysqli_query($connect,"CREATE TABLE users(
`id` int(20) auto_increment primary key,
`user_id` varchar(256),
`status` varchar(256),
`data` varchar(256),
`step` varchar(256)
)");


mysqli_query($connect,"CREATE TABLE films(
`id` int(20) auto_increment primary key,
`film_id` varchar(256),
`film_name` varchar(256),
`film_date` varchar(256),
`downloads` varchar(256)
)");


mysqli_query($connect,"CREATE TABLE `send` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`message` text NOT NULL,
`start` text NOT NULL,
`limit` text NOT NULL,
`succes` text NOT NULL,
`left` text NOT NULL,
`time1` text NOT NULL,
`time2` text NOT NULL,
`time3` text NOT NULL,
`time4` text NOT NULL,
`time5` text NOT NULL,
`mesid` text NOT NULL,
`mesid2` text NOT NULL,
`holat` text NOT NULL,
`type` text NOT NULL,
`button` text NOT NULL,
 PRIMARY KEY (`id`)
)");

mysqli_query($connect,"CREATE TABLE channels(
`number` int(20) auto_increment primary key,
`url` varchar(256),
`id` varchar(256),
`type` varchar(256),
`members` varchar(256)
)");

mysqli_query($connect,"CREATE TABLE admins(
`id` int(20) auto_increment primary key,
`user_id` varchar(256),
`status` varchar(256)
)");

mysqli_query($connect,"CREATE TABLE settings(
`id` int(20) auto_increment primary key,
`movie_channel` varchar(256)
)");

mysqli_query($connect,"INSERT INTO `settings`(`id`, `movie_channel`) VALUES ('1', '');");

#================================================#