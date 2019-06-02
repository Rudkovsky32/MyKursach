<?php
session_start();
if(isset($_GET['id'])&& !empty($_GET['id'])&& isset($_GET['mode'])&& $_GET['mode']=="add"){
    $exist=false;
    if(!isset($_SESSION['items'])){
        $_SESSION['items']=array();
    }
    for($i=0;$i<count($_SESSION['items']);$i++){
        if($_SESSION['items'][$i]['id']==$_GET['id']){
            $_SESSION['items'][$i]['kol_vo']++;
            $exist=true;
            break;
        }
    }

    if($exist==false) {

        require_once("param.php");

        $query = "select name,price,photo from thing WHERE id=" . $_GET['id'];

        $result = mysqli_query($dbc, $query) or die("err zap q");

        $next = mysqli_fetch_array($result);

        $_SESSION['items'][] = array("id" => $_GET['id'], "name" => $next['name'], "price" => $next['price'], "photo" => $next['photo'], "kol_vo" => 1);
    }
}

if(isset($_GET['mode'])&& $_GET['mode']=="clear"){
    unset($_SESSION['items']);
    $_SESSION['items']=array();
}

if(isset($_GET['id'])&& !empty($_GET['id'])&& isset($_GET['mode'])&& $_GET['mode']=="del") {
    for ($i = 0; $i < count($_SESSION['items']); $i++) {
        if ($_SESSION['items'][$i]['id'] == $_GET['id']) {
            unset($_SESSION['items'][$i]);
            break;
        }
    }

    $items = array();
    foreach ($_SESSION['items'] as $tmp) {
        if (!empty($tmp)) {
            $items[] = $tmp;
        }
    }

    unset($_SESSION['items']);
    $_SESSION['items'] = array();
    $_SESSION['items'] = $items;
    unset($items);
}
if(isset($_GET['script']) && $_GET['script'] == "order"){// проверяем если ссылка содержит слово ордер осуществляем редирект на файл ордер пхп, если бы такой проверки небыло нас бы возвращало в катаЛог пхп

    header("location:../order.php");

}else {
    if (isset($_GET['page'])) {

        $page = $_GET['page'];

        header("location:../out_user.php");//когда делается возврат на каталог передаем номер странички в листалку на которую нам нужно вернуться смотри в коталоге строку 27 иначе нас будет кидать на первую страницу
    } else {
        header("location:../out_user.php");
    }
}
    ?>