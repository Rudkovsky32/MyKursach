<?php
session_start();
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<?php

require_once ("param.php");

if(isset($_COOKIE['user_id']))
{
setcookie("user_id","",time()+60*60*2);
}
if(isset($_COOKIE['user_name']))
{
    setcookie("user_name","",time()+60*60*2);
}
if(isset($_COOKIE['user_avatar']))
{
setcookie("user_avatar","",time()+60*60*2);
}
if(isset($_COOKIE[session_name()])){
setcookie(session_name(),"",time()-7200);
}
$_SESSION=array();

session_destroy();

echo"Вы успешно вышли, для проверки нажмите на ссылку<a href='../out_user.php'>...</a>";

?>
</body>
</html>
