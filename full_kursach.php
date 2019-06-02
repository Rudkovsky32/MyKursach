<!doctype html>
<html lang="ru">
<link>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <link href="glavnaya.css" rel="stylesheet">

</head>
<body>

<div id="menu">
    <?php
    echo"<table align='right'><tr>";
    echo"<th><a href='login.php'>Войти</a></th></tr></table>";
    echo"<br>
<br>";
    echo"<table align='right'><tr><th><a href='admin/registr.php'>Регистрация</a></th></tr></table>";
    ?>

</div>
<div id="content">
<?php
require_once ("admin/param.php");
?>


<form method="get" action="full_kursach.php">
<input type="text" name="search" placeholder="Поиск"><br>
    <br>
<input type="text" name="ot" placeholder="ОТ">
<input type="text" name="do" placeholder="ДО"><br>
    <br>

    <select name="idC">
        <option value="0">-----</option>
        <?php
        $queryC="select id,name from categories";

        $resultC=mysqli_query($dbc,$queryC) or die("err zap qC");

        while($nextC=mysqli_fetch_array($resultC)) {

            $idC = $nextC['id'];
            $nameC = $nextC['name'];

            echo "<option value='".$idC."'>$nameC</option>";
        }
        ?>
    </select>

    <input type="submit" name="send" value="Поиск"><br>
    <br>
</form>


 <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ссылка</title>
    <style>
        .c {
     border: 1px solid #333; /* Рамка */
            display: inline-block;
            padding: 5px 15px; /* Поля */
            text-decoration: none; /* Убираем подчёркивание */
            color: #000; /* Цвет текста */
        }
        .c:hover {
     box-shadow: 0 0 5px rgba(0,0,0,0.3); /* Тень */
            background: linear-gradient(to bottom, #fcfff4, #e9e9ce); /* Градиент */
            color: powderblue;
        }
    </style>
</head>
<body>
<a href="full_kursach.php" class="c">На Главную</a>
</body>
</html>

    <?php
 if(isset($_GET['ot'])&& !empty($_GET['ot'])){
     $ot=$_GET['ot'];
 }else{
     $ot=0;
 }
 if(isset($_GET['do'])&& !empty($_GET['do'])){
     $do=$_GET['do'];
 }else {
     $querydo = "select MAX(price) as max_price from thing";

     $resultdo = mysqli_query($dbc, $querydo) or die("err zap qdo");

     $nextdo = mysqli_fetch_array($resultdo);

     $do = $nextdo['max_price'];
 }

 $querysort=" WHERE price >= $ot and price <= $do";//Повтори завтра эту строку и запомни что пишется она после скобки

 if(isset($_GET['search'])&& !empty($_GET['search'])){

     $search1=$_GET['search'];

     $search=str_replace(",", " ",$search1);

     $word=explode(" ",$search);
 }

 $final_word=array();
 if(count($word)>0){
     foreach ($word as $tmp){
         if(!empty($tmp)){
             $final_word[]=$tmp;
         }
     }
 }

 $word_WHERE=array();
 if(count($final_word)>0){
     foreach ($final_word as $tmp){
         $word_WHERE[]="name like '%$tmp%'";
     }
 }

 $WHERE_result=implode(" OR ",$word_WHERE);
 if(!empty($WHERE_result)){
     $querys=" and ($WHERE_result)";
 }

 $zapis=3;

if(!empty($querys)) {
    $query = "select id from thing" . $querysort . $querys;
}else{
    $query="select id from thing".$querysort;
}

if(!empty($_GET['idC'])){

    $firma=$_GET['idC'];

    $query.=" and firma=$firma";
}else{
    $query.="";
}
 //echo"$query";

 $result=mysqli_query($dbc,$query) or die("err zap q");

 $count_zap=mysqli_num_rows($result);

 $count_pages=ceil($count_zap/$zapis);

 if(isset($_GET['page'])){
     $active_page=$_GET['page'];
 }else {
     $active_page = 1;
 }

 $skip=($active_page-1)*$zapis;

 if(!empty($querys)) {

     $query1 = "select id,name,price,dv,count,opisanie,photo,firma from thing" . $querysort . $querys;

 }else{

     $query1="select id,name,price,dv,count,opisanie,photo,firma from thing".$querysort;

 }

 if(!empty($firma)){

     $query1.=" and firma=$firma limit $skip, $zapis";//

 }else{

     $query1.=" limit $skip, $zapis";

 }
//echo"$query1";

     $result1 = mysqli_query($dbc,$query1) or die("err zap q1");

     echo "<table border='1'><tr>
<th>#</th>
<th>Фото</th>
<th>Товар</th>
<th>Описание</th>
<th>Цена</th>
<th>Д/В</th>
<th>К-во</th>
</tr>";

     while ($next = mysqli_fetch_array($result1)) {
         $id = $next['id'];
         $photo = $next['photo'];
         if (empty($photo)) {
             $photo = "nofoto.png";
         }
         $name = $next['name'];
         $price = $next['price'];
         $opisanie = $next['opisanie'];
         $dv = $next['dv'];
         $count = $next['count'];

         echo "<tr>
<td>$id</td>
<td><img width='100px' src='admin/img/$photo'></td>
<td>$name</td>
<td>$opisanie</td>
<td>$price</td>
<td>$dv</td>";

         if(!empty($count)){
             echo"<td>$count<a href='admin/basket.php?id=".$id."&mode=add&page=".$active_page."'>Купить</a></td></tr>";
         }

     }

     echo "</table>";

     echo "<table><tr>";

     if ($active_page == 1) {
         echo "<td> <== </td>";
         echo "<td> << </td>";
     } else {
         if (!empty($firma)) {
             echo "<td><a href='full_kursach.php?page=1&search=" . $search1 . "&idC=" . $firma . "&ot=" . $ot . "&do=" . $do . "'> <== </a></td>";
             echo "<td><a href='full_kursach.php?page=" . ($active_page - 1) . "&search=" . $search1 . "&idC=" . $firma . "&ot=" . $ot . "&do=" . $do . "'> << </a></td>";
         } else {
             echo "<td><a href='full_kursach.php?page=1&search=" . $search1 . "&ot=" . $ot . "&do=" . $do . "'> <== </a></td>";
             echo "<td><a href='full_kursach.php?page=" . ($active_page - 1) . "&search=" . $search1 . "&ot=" . $ot . "&do=" . $do . "'> << </a></td>";
         }
     }
     for ($i = 1; $i <= $count_pages; $i++) {
         if ($i == $active_page) {
             echo "<td>$i</td>";
         } else {
             if (!empty($search1)&& !empty($firma)) {
                 echo "<td><a href='full_kursach.php?page=" . $i . "&search=" . $search1 . "&idC=".$firma."&ot=" . $ot . "&do=" . $do . "'>$i</a></td>";
             } else {
                 echo "<td><a href='full_kursach.php?page=" . $i . "&ot=" . $ot . "&do=" . $do . "'>$i</a></td>";
             }
         }
     }
     if ($active_page == $count_pages) {
         echo "<td> >> </td>";
         echo "<td> ==> </td>";
     } else {
         if (!empty($firma)) {
             echo "<td><a href='full_kursach.php?page=" . ($active_page + 1) . "&search=" . $search1 . "&idC=" . $firma . "&ot=" . $ot . "&do=" . $do . "'> >> </a></td>";
             echo "<td><a href='full_kursach.php?page=" . $count_pages . "&search=" . $search1 . "&idC=" . $firma . "&ot=" . $ot . "&do=" . $do . "'> ==> </a></td>";
         } else {
             echo "<td><a href='full_kursach.php?page=" . ($active_page + 1) . "&search=" . $search1 . "&ot=" . $ot . "&do=" . $do . "'> >> </a></td>";
             echo "<td><a href='full_kursach.php?page=" . $count_pages . "&search=" . $search1 . "&ot=" . $ot . "&do=" . $do . "'> ==> </a></td>";
         }
     }
     echo "</tr></table>";

mysqli_close($dbc);
?>
</div>
</body>
</html>
