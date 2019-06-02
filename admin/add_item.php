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
/*
1)подкл БД
2)ставим условие если не нажата кнопка тогда выдаем форму
3)выбираем категорию привязываем адд_кат
4)создаем запрос категории
5)выдаем результат категории
6)создаем цыкл
7)делаем фото и кнопку закрываем форму
*/
require_once("param.php");
if (!isset($_POST['send'])) {

    ?>
    <form action="add_item.php" method="POST" enctype="multipart/form-data">
        <!-- параметр  multipart/form-data работает только с методом пост и указывает на то что форма модет передавать файлы разных типов многими частями -->
       <p>Указываем  название товара</p>
        <input type="text" name="name" placeholder="Введите название"><br>
        <p>Указываем цену товара</p>
        <input type="text" name="price" placeholder="Цена"><br>
        <p>Указываем дату изготовления</p>
        <input type="date" name="date"><br>
        <p>Указываем количество товаров на складе</p>
        <input type="text" name="count" placeholder="Введите количество"><br>
        <p>Указываем описание товара</p>
        <textarea name="opisanie" placeholder="Описание товара"></textarea><br>

        <p>Выберите категорию добавляемого товара</p>

        <select name="firma">
            <?php
            $queryC = "select id,name from categories";

            $resultC = mysqli_query($dbc, $queryC) or die("error zapros cat");

            while ($nextC = mysqli_fetch_array($resultC)) {

                $idC = $nextC['id'];
                $nameC = $nextC['name'];

                echo "<option value='" . $idC . "'>$nameC</option>";
            }
            ?>
        </select><br>
        <p>Выберите файл</p>
        <input type="file" name="photo"><br>
        <input type="submit" name="send" value="Добавить"><br>
    </form>
    <?php
}else if(isset($_POST['send'])&& !empty($_POST['name'])&& !empty($_POST['price'])&& !empty($_POST['date'])&& !empty($_POST['count'])&& !empty($_POST['opisanie'])){

    $name=$_POST['name'];
    $price=$_POST['price'];
    $date=$_POST['date'];
    $count=$_POST['count'];
    $opisanie=$_POST['opisanie'];
$firma=$_POST['firma'];
/*
 * Работа с файлами
 * при загрузке файла для него создается супер глобальный массив $_FILES который имеет элемент ['photo']-- название которого получаем   <input type="file" name="photo"> то есть $_FILE['photo']--массив который имеет 5 состояний загружаемого файла
 * 1) $_FILES['photo']['type']--возвращает мемо тип загружаемого файла например, image/jpg, text/doc
 * 2)$_FILES['photo']['size']--возвращает размер загружаемого файла
 * 3)$_FILES['photo']['name']--возвращает название файла как он назывался на пк клиента
 * 4)$_FILES['photo']['tmp_name']--возвращает временное местоположение и название файла на сервере
 * 5)$_FILES['photo']['error']--возвращает ошибку загрузки файла на сервер то есть если по какой то причине файл не загрузился мы получим код ошибки если файл на сервер загрузиося успешно то код ошибки будет 0
 */
if($_FILES['photo']['error']==0) {//если код ошибки равен 0 то файл на сервер загружен успешно это значит что мы должны переместить его на хостинг папочку сайта
    $filenameTMP = $_FILES['photo']['tmp_name'];//в переменную сохр временное расположение и временное название файла на сервере
    $filename = time() . $_FILES['photo']['name'];//в переменную сохр название файла как он назывался на ПК клиента и модефицируем название функции тайм
    //функц тайм возвращает время в м/с
    move_uploaded_file($filenameTMP, "img/$filename");//функция move_uploaded_file берет файл с временного расположения на сервере и перемещает в указаннуб папку сайта переименовувая файл
    //move_uploaded_file -- имеет параметры 1)где файл взять на сервере и как он называется 2)куда файл положить и как его назвать
    $query = "insert into thing(name,price,dv,count,opisanie,firma,photo) VALUES ('$name','$price','$date','$count','$opisanie','$firma','$filename')";//запрос добавляет в БД название файла
}
else{//иначе запрос без названия файла
    $query="insert into thing(name,price,dv,count,opisanie,firma) VALUES ('$name','$price','$date','$count','$opisanie','$firma')";
}
//echo"$query";

    mysqli_query($dbc,$query) or die("error zapros");

    echo"Товар успешно добавлена<a href = 'add_item.php'>Добавить еще</a>";


}
else{
    echo"Не достаточно данных<a href='add_item.php'><br>Снова</a>";
}
mysqli_close($dbc);
?>


</body>
</html>

