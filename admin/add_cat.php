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

if(!isset($_POST['send'])){

    ?>

    <form action="add_cat.php" method="post">
        <p>Укажите название категории</p>
        <input type="text" name="name" placeholder=""><br>
        <input type="submit" name="send" value="Добавить">
    </form>

<?php
}else if(isset($_POST['send'])&& !empty($_POST['name'])){

    $name=$_POST['name'];

    $query="insert into categories (name) VALUES ('$name')";

mysqli_query($dbc,$query) or die("ERR ZAP q");

echo"Добавление категории прошло успешно<a href='add_cat.php'>Добавить ещё</a>";
}else{
    echo"Добавление отменено или невозможно";
}
mysqli_close($dbc);

?>
</body>
</html>
