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
require_once ("admin/param.php");

if (!isset($_POST['send'])) {

    echo "<form action='login.php' method='post'>
<input type='text' name='login' placeholder='Введите логин'><br>
<input type='password' name='password' placeholder='Введите пароль'><br>
<input type='submit' name='send' value='Войти'><br>
</form>";

}else if(isset($_POST['send'])&& !empty($_POST['login'])&& !empty($_POST['password'])){

$login=$_POST['login'];
$password=$_POST['password'];

$query="select id,name,avatar from user WHERE login='$login' and password=SHA1('$password')";

$result=mysqli_query($dbc,$query) or die("err zap q");

if(mysqli_num_rows($result)){
$next=mysqli_fetch_array($result);

$id=$next['id'];
$name=$next['name'];
$avatar=$next['avatar'];
    if(empty($avatar)){
        $avatar="nofoto.png";
    }

setcookie("user_id",$id,time()+60*60*24*30*3);
    setcookie("user_name",$name,time()+60*60*24*30*3);
    setcookie("user_avatar",$avatar,time()+60*60*24*30*3);

$_SESSION['user_id']=$id;
$_SESSION['user_name']=$name;
$_SESSION['user_avatar']=$avatar;

echo"Вы успешно вошли:
$name
<img width='100px' src='admin/img/$avatar'>";
header("refresh:5;url=out_user.php");
}else{
    echo"Неверный логин или пароль<a href='registr_copy.php'>На регистрацию</a>";
}
}else{
    echo"Не достаточно данных для входа<a href='login.php'>Попробовать снова</a>";
}
mysqli_close($dbc);
?>
</body>
</html>
