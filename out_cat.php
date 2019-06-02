<?php
session_start();
?>
//<?php
//if(isset($_COOKIE['user_id'])&& isset($_COOKIE['user_name'])&& isset($_COOKIE['user_avatar'])){
//    $_SESSION['user_id']=$_COOKIE['user_id'];
//    $_SESSION['user_name']=$_COOKIE['user_name'];
//    $_SESSION['user_avatar']=$_COOKIE['user_avatar'];
//}
//if(isset($_SESSION['user_id'])&& isset($_SESSION['user_name'])&& isset($_SESSION['user_avatar'])) {
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <title>Free CSS template by ChocoTemplates.com</title>
        <link rel="stylesheet" href="admin/dizayn/html/css/style.css" type="text/css" media="all" />
        <!--[if lte IE 6]><link rel="stylesheet" href="admin/dizayn/html/css/ie6.css" type="text/css" media="all" /><![endif]-->

        <meta name="keywwords" content="Shop Around - Great free html template for on-line shop. Use it as a start point for your on line business. The template can be easily implemented in many open source E-commerce platforms" />
        <meta name="description" content="Shop Around - Great free html template for on-line shop. Use it as a start point for your on line business. The template can be easily implemented in many open source E-commerce platforms" />

        <!-- JS -->
        <script src="admin/dizayn/html/js/jquery-1.4.1.min.js" type="text/javascript"></script>
        <script src="admin/dizayn/html/js/jquery.jcarousel.pack.js" type="text/javascript"></script>
        <script src="admin/dizayn/html/js/jquery-func.js" type="text/javascript"></script>
        <!-- End JS -->

        <link href="admin/dizayn/html/css/style_table.css" rel="stylesheet">

    </head>
    <body>

    <div class="shell">

        <!-- Header -->
        <div id="header">
            <h1 id="logo"><a href="full_kursach_2.php">shoparound</a></h1>

            <!-- basket -->
            <div id="cart">
                <a href="out_user.php" class="cart-link">Your Shopping Basket</a>
                <div class="cl">&nbsp;</div>
                <?php

                require_once ("admin/param.php");

                if(!empty($_SESSION['items'])) {

                    foreach ($_SESSION['items'] as $tmp) {

                    }
                    echo "<span>Count: <strong> " . $tmp['kol_vo'] . " </strong></span>";
                }else{
                    echo"<span>Count: <strong> 0 </strong></span>";
                }
                ?>
                <?php

                require_once("admin/param.php");

                if (!empty($_SESSION['items'])) {

                    $totalsum = 0;

                    foreach ($_SESSION['items'] as $tmp) {

                        $stoimost = $tmp['price'] * $tmp['kol_vo'];

                        $totalsum = $totalsum + $stoimost;
                    }
                    echo "  <span>Cost: <strong>$totalsum</strong></span>";
                } else {
                    echo "  <span>Cost: <strong>0</strong></span>";
                }

                ?>
            </div>
            <!-- End basket -->

            <!-- Navigation -->
            <div id="navigation">
                <ul>
                    <li><a href="full_kursach_2.php">Home</a></li>
                    <li><a href="registr.php">Registration</a></li>
                    <li><a href="login_copy.php">My Account</a></li>
                    <li><a href="Store.php">The Store</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
            <!-- End Navigation -->
        </div>
        <!-- End Header -->



        <!-- Main -->
        <div id="main">
            <div class="cl">&nbsp;</div>

            <!-- Content помни о нем!!!!!!!!!!!!!!!!!!!-->
            <div id="content">

                <?php

                require_once("admin/param.php");

                    $id_cat=$_GET['idC'];

                    $query = "select id,name,price,dv,count,opisanie,photo,firma from thing WHERE firma='$id_cat'";

                    $result=mysqli_query($dbc,$query) or die("err zap q");

                    echo "<table border='1'><tr>
<th>#</th>
<th>name</th>
<th>price</th>
<th>dv</th>
<th>count</th>
<th>opisanie</th>
<th>photo</th>
<th>firma</th>
</tr>";

                    while($next=mysqli_fetch_array($result)) {
                        $id = $next['id'];
                        $name = $next['name'];
                        $price=$next['price'];
                        $dv=$next['dv'];
                        $count=$next['count'];
                        $opisanie=$next['opisanie'];
                        $photo=$next['photo'];
                        if(empty($photo)){
                            $photo="nofoto.png";
                        }
                        $firma=$next['firma'];

                        echo "<tr>
<td>$id</td>
<td>$name</td>
<td>$price</td>
<td>$dv</td>
<td>$count</td>
<td>$opisanie</td>
<td><img width='50px' src='admin/img/$photo'</td>
<td>$firma</td>

</tr>";
                    }

echo"</table>";

                ?>

                <!-- BuBod -->

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


                ?>


                <div class="cl">&nbsp;</div>
            </div>
            <!-- End Products -->

        </div>
        <!-- End Content -->


        <!-- Sidebar -->
        <div id="sidebar">

            <!-- Search -->
            <div class="box search">
                <h2>Search by <span></span></h2>
                <div class="box-content">
                    <form action="Store.php" method="get">

                        <label>Keyword</label>
                        <input type="text" name="search" placeholder="search" class="field" />

                        <!-- Category -->

                        <label>Category</label>
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

                        <div class="inline-field">
                            <br>
                            <label>Price</label><br>
                            <input type="text" name="ot" placeholder="ОТ"><br>
                            <input type="text" name="do" placeholder="ДО"><br>
                        </div>

                        <!-- <input type="submit"  name="send" class="search-submit" value="Search" />-->

                        <input type="submit" name="send" class="search-submit" value="Найти"><br>
                        <br>

                        <p>
                            <a href="#" class="bul">Advanced search</a><br />
                            <a href="#" class="bul">Contact Customer Support</a>
                        </p>

                    </form>
                </div>
            </div>
            <!-- End Search -->

            <!-- Categories -->


            <div class="box categories">
                <h2>Categories <span></span></h2>
                <div class="box-content">
                    <ul>
                        <?php


                        $query2="select id,name from categories";

                        $result2=mysqli_query($dbc,$query2) or die("err zap q");


                        while($next2=mysqli_fetch_array($result2)){

                            $id_cat=$next2['id'];
                            $name_cat=$next2['name'];

                            echo"<li><a href='out_cat.php?idC=".$id_cat."'>$name_cat</a></li>";
                        }
                        echo"<li><a href='out_cat.php'>BCE</a></li>";

                        ?>
                        <!--<li><a href="out_cat.php">Nike</a></li>
                        <li><a href="out_cat.php">Adidas</a></li>
                        <li><a href="out_cat.php">Adidas</a></li>
                        <li><a href="out_cat.php">Reeboke</a></li>
                        <li><a href="out_cat.php">Under Armor</a></li>
                        <li><a href="out_cat.php">Puma</a></li>-->

                    </ul>
                </div>
            </div>


            <!-- End Categories --></div>





        <div class="cl">&nbsp;</div>
    </div>
    <!-- End Main -->

    <!-- Side Full -->

    <!-- End Side Full -->

    <!-- Footer -->
    <div id="footer">
        <p class="left">
            <a href="#">Home</a>
            <span>|</span>
            <a href="#">Support</a>
            <span>|</span>
            <a href="#">My Account</a>
            <span>|</span>
            <a href="#">The Store</a>
            <span>|</span>
            <a href="#">Contact</a>
        </p>
        <p class="right">
            &copy; 2010 Shop Around.
            Design by <a href="http://chocotemplates.com" target="_blank" title="The Sweetest CSS Templates WorldWide">Chocotemplates.com</a>
        </p>
    </div>
    <!-- End Footer -->

    </div>
    <!-- End Shell -->
    <div id="menu">

    </div>

    <div id="content">

    </body>
    </html>
    <?php

?>


