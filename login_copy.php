<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>22 лучших формы входа и регистрации | Vladmaxi.net</title>
    <link rel="icon" href="http://vladmaxi.net/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="http://vladmaxi.net/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="login/css/style.css" media="screen" type="text/css" />
    <script src="login/js/prefixfree.min.js"></script>

    <link href="admin/dizayn/html/css/style_table.css" rel="stylesheet">

</head>

<body>

<?php
require_once ("admin/param.php");

if(!isset($_POST['send'])){


    echo"<div class='content'>
    <div class='form-wrapper'>
        <div class='linker'>
            <span class='ring'></span>
            <span class='ring'></span>
            <span class='ring'></span>
            <span class='ring'></span>
            <span class='ring'></span>
        </div>
        <form class='login-form' action='login_copy.php' method='post'>
            <input type='text' name='login' placeholder='Логин' />
            <input type='password' name='password' placeholder='Пароль' />
            <button type='submit' name='send'>ВОЙТИ</button>
        </form>
    </div>
</div>
";


}else if(isset($_POST['send'])&&!empty($_POST['login'])&&!empty($_POST['password']))
{
    $login=$_POST['login'];
    $password=$_POST['password'];

    $query="select id,name,avatar from user WHERE login='$login' and password=SHA1('$password')";

    $result=mysqli_query($dbc,$query) or die("err zap q");

    if(mysqli_num_rows($result)==1)
    {
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

        echo"Вы успешно вошли $name:
id=$id<br>
name=$name<br>
avatar=<img width='100px' src='admin/img/$avatar'>";
        header("refresh:5;url=out_user.php");
    }else{
        echo"Неверный логин или пароль<a href='registr.php'>На регистрацию</a>";
    }
}else{
    echo"Не достаточно данных для входа<a href='login_copy.php'>Попробовать снова</a>";
}
mysqli_close($dbc);
?>
</body>
</html>
