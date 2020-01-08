<?php
	require "database.php";
    require "Classes/User.php";
    require "Classes/But.php";
	require "Classes/QuaDeBan.php";
    require "Classes/QuaTangPhaLe.php";
    require "Classes/GauBong.php";
    require "Classes/SanhSu.php";
    require "Classes/TienIch.php";
    require "Classes/MocKhoa.php";
    require "Classes/DoMyNghe.php";

	session_start();
	$sql = "SELECT * from Product";
    $result = $db->query($sql)->fetch_all() ;   
    if ($_SESSION['name']!="") {
        $sql1 = "SELECT * from cart where cart.idUser=(select id from User where username ='".$_SESSION['name']."')";
        $result1 = $db->query($sql1)->fetch_all();
    }
/*===================================================Admin xoa san pham================================================*/
	if(isset($_POST['dele'])){
		$del='DELETE FROM Product WHERE id='.$_POST['dele'];
		$db->query($del);
	}
/*===================================================Admin sua san pham================================================*/
    if(isset($_POST['edit'])){
       echo $_POST['edit'];
       $name_edit=$_POST['namePr'];
       $type_edit=$_POST['typePr'];
	   $price_edit=$_POST['pricePr'];
       $image_edit=$_POST['imagePr'];
       echo $name_edit;
       echo $type_edit;
	   $stm='UPDATE Product set name="'.$name_edit.'", type="'.$type_edit.'",price='.$price_edit.', image="img/'.$image_edit.'" WHERE id='.$_POST['edit'];
	   $db->query($stm);
   }
/*========================================================Log out======================================================*/   
    if(isset($_POST['logout'])){
    unset($_SESSION['name']);
   }
/*===================================================Them vao doi tuong================================================*/
	$Products = array();
	for($i = 0; $i < count($result); $i++) {
		$Product = $result[$i];
		if($Product[4] == 'QUÀ ĐỂ BÀN'){
			array_push($Products, new QuaDeBan($Product[0], $Product[1], $Product[2], $Product[3], $Product[5]));
		}
		else 
		if($Product[4] == 'QUÀ TẶNG PHA LÊ'){
			array_push($Products, new QuaTangPhaLe($Product[0], $Product[1], $Product[2], $Product[3], $Product[5]));
        }
        else 
		if($Product[4] == 'GẤU BÔNG'){
			array_push($Products, new GauBong($Product[0], $Product[1], $Product[2], $Product[3], $Product[5]));
        }
        else 
		if($Product[4] == 'MÓC KHÓA'){
			array_push($Products, new MocKhoa($Product[0], $Product[1], $Product[2], $Product[3], $Product[5]));
        }
        else 
		if($Product[4] == 'BÚT'){
			array_push($Products, new But($Product[0], $Product[1], $Product[2], $Product[3], $Product[5]));
        }
        else 
		if($Product[4] == 'TIỆN ÍCH'){
			array_push($Products, new TienIch($Product[0], $Product[1], $Product[2], $Product[3], $Product[5]));
        }
        else 
		if($Product[4] == 'ĐỒ MỸ NGHỆ'){
			array_push($Products, new DoMyNghe($Product[0], $Product[1], $Product[2], $Product[3], $Product[5]));
        }
        else 
		if($Product[4] == 'SÀNH SỨ'){
			array_push($Products, new SanhSu($Product[0], $Product[1], $Product[2], $Product[3], $Product[5]));
		}
	}
	/*===============================================User them san pham vao gio hang======================================*/    
    if(isset($_POST["insert_cart"])){
        if(empty($_SESSION['name'])){
         ?>
          <script>
            alert("Bạn chưa đăng nhập! Đăng nhập để thêm sản phẩm vào giỏ hàng!");
          </script>
         <?php
        }
        else{
            $i=$_POST["insert_cart"]-1;     
            $id=$i+1;
            $check=false;
            $sql0 = "SELECT id from User where username ='".$_SESSION['name']."'";
            $ktraUser=$db->query($sql0)->fetch_all();
            $idUser= $ktraUser[0][0];
            echo $idUser;

            for($j = 0; $j < count($result1); $i++) {
                if ($result1[$j][1]==$id){
                    $check=true;
                    $sql1 = "UPDATE cart SET quantity = ".($result1[$j][5]+1).", total=".($result1[$j][5]+1)*($result1[$j][4])." WHERE id_pr=".$id;
                    $db->query($sql1);
                }
                else{
                    break;
                    }
            }      

            if($check==false){
                $img=$result[$i][2];
                $price=$result[$i][3];
                $name=$result[$i][1];
                $quantity=1;
                $total=$price*$quantity;
                ECHO $idUser;
                $sql1 = "INSERT into cart values(null,".$id.",'".$img."','".$name."',".$price.",".$quantity.",".$total.",".$idUser.")";
                $db->query($sql1);  
            }
        }
    }
/*===================================================Admin them san pham================================================*/
    if(isset($_POST["addpr"])){
        $name=$_POST["name"];
		$price=$_POST["price"];
		$detail=$_POST["detail"];
        $type=$_POST["select"];

        /*---------------------------*/
        $target_dir = "img/";
        $target_file = $target_dir.basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        if(isset($_POST["addpr"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }
        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }
        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
                $sql = "INSERT into Product values(null,'".$name."','".$target_file."',".$price.",'".$type."','".$detail."')";
                $db->query($sql);
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
                /*---------------------------*/        
    }

        /*==========================================================Dang nhap=================================================*/
            $sql2 = "SELECT * from user";
            $result2 = $db->query($sql2)->fetch_all();
            $check=true;

            if (isset($_POST['login'])){
                $username = addslashes($_POST['accountLogin']);
                $password = addslashes($_POST['passLogin']);
                if (!$username || !$password) {
                    ?>
                    <script>
                        alert("Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu!");
                    </script>
                    <?php
                }
                for($i = 0; $i < count($result2); $i++) { 
                    if($username==$result2[$i][2] && $password==$result2[$i][3]){
                        $check=true;
                        if($result2[$i][4]=="admin"){
                            $log=true;
                            $_SESSION['log'] = $log;
                            $_SESSION['name'] = $username;
                            break;
                        }
                        else{
                            $log=false;
                            $_SESSION['log'] = $log;
                            $_SESSION['name'] = $username;
                        }
                    }
                    else{
                        $check=false;
                    }
                }
                if ($check==false){
                    ?>
                    <script>
                        alert("Tên đăng nhập hoặc mật khẩu không đúng!");
                    </script>
                    <?php
                }
            }

/*===========================================================Dang ki===================================================*/
    if (isset($_POST['logup'])){
        $username = addslashes($_POST['nameLogup']);
        $useraccount = addslashes($_POST['accountLogup']);
        $password = addslashes($_POST['passLogup']);
        if (!$username || !$useraccount || !$password) {
            echo "Please input all information. <a href='javascript: history.go(-1)'>Trở lại</a>";
            exit;
        }
        for($i = 0; $i < count($result2); $i++) { 
            if($username==$result2[$i][1] && $useraccount==$result2[$i][2] && $password==$result2[$i][3]){
                $check=true;
                echo "Account exit! Log in!<a href='javascript: history.go(-1)'>Trở lại</a>";
                exit;
            }
            else{
                $check=false;
            }
        }
        if ($check==false){
            $pos="user";
            $sql2 = "INSERT into User values(null,'".$username."','".$useraccount."','".$password."','".$pos."')";
            $db->query($sql2);
            echo "Log up successful!<a href='javascript: history.go(-1)'>Log in your account</a>";
            exit;
        }
    } 
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Souvenir Shop</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous">
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        <style>
            *{
            margin: 0;
            padding: 0;
            }
            .header-container {
                background: brown;
                height: 40px;
            }  
            .container_header {
                margin: 0 100px 20px 100px;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }           
            .header_left {
                align-content: center;
                align-items: center;
                color: white;
                margin-top: 5px;
            }          
            .header_right {
                display: flex;
                justify-content: space-between;
                align-content: center;
            }           
            .items img {
                width: 20px;
                height: 20px;
                margin-top: 5px;
                margin-bottom: 5px;
            }
            /*============================*/
            .clearfix:after {
                display: block;
                clear: both;
            } 
            .wrapper {
                width: 100%;
                background: white;
                display: flex;
                justify-content: space-between;
                box-shadow: 0px 3px 3px #ccc;
            }
            /*----- Phần menu -----*/           
            .menu {
                position: relative;
                margin: 0px auto;
                background: white;
                height: 50px;
                margin-top: 20px;
                margin-bottom: 5px;
            }          
            .menu li {
                margin: 0px;
                list-style: none;
                font-family: 'Ek Mukta';
            }            
            .menu a {
                transition: all linear 0.15s;
                color: black;
                text-decoration: none;
            }           
            .menu .arrow {
                font-size: 11px;
                line-height: 0%;
            }
            /*----- css cho phần menu cha -----*/           
            .menu > ul > li {
                display: inline-block;
                position: relative;
                font-size: 15px;
            }
            .menu > ul > li > a {
                padding: 10px 40px;
                display: inline-block;
                color: black;
            }        
            .menu > ul > li:hover > a,
            .menu > ul > .current-item > a {
                color: mediumblue;
            }
            /*----css cho menu con----*/
            .menu li:hover .sub-menu {
                z-index: 1;
                opacity: 1;
            }        
            .sub-menu {
                width: 160%;
                padding: 5px 0px;
                position: absolute;
                top: 100%;
                left: 0px;
                z-index: -1;
                opacity: 0;
                transition: opacity linear 0.15s;
                box-shadow: 0px 2px 3px rgba(0, 0, 0, 0.2);
                background: white;
            }          
            .sub-menu li {
                display: block;
                font-size: 15px;
            }         
            .sub-menu li a {
                padding: 10px 30px;
                color: orangered;
                display: block;
            }  
            .sub-menu li button {
                padding: 10px 30px;
                color: orangered;
                display: block;
            }   
            .sub-menu li a:hover,
            .sub-menu .current-item a,
            .sub-menu li button:hover {
                color: mediumblue;
            }
            /*===========================*/
            #searchbox {
                width: 240px;
            }          
            #searchbox input {
                outline: none;
            }         
            input:focus::-webkit-input-placeholder {
                color: transparent;
            }           
            input:focus:-moz-placeholder {
                color: transparent;
            }          
            input:focus::-moz-placeholder {
                color: transparent;
            }
                        
            #searchbox input[type="text"] {
                background: url(http://2.bp.blogspot.com/-xpzxYc77ack/VDpdOE5tzMI/AAAAAAAAAeQ/TyXhIfEIUy4/s1600/search-dark.png) no-repeat 10px 13px #f2f2f2;
                border: 2px solid #f2f2f2;
                font: bold 12px Arial, Helvetica, Sans-serif;
                color: #6A6F75;
                width: 160px;
                padding: 14px 17px 12px 30px;
                -webkit-border-radius: 5px 0px 0px 5px;
                -moz-border-radius: 5px 0px 0px 5px;
                border-radius: 5px 0px 0px 5px;
                text-shadow: 0 2px 3px #fff;
                -webkit-transition: all 0.7s ease 0s;
                -moz-transition: all 0.7s ease 0s;
                -o-transition: all 0.7s ease 0s;
                transition: all 0.7s ease 0s;
            }    
            #searchbox input[type="text"]:focus {
                background: #f7f7f7;
                border: 2px solid #f7f7f7;
                width: 200px;
                padding-left: 10px;
            }      
            #button-submit {
                background: url(http://4.bp.blogspot.com/-slkXXLUcxqg/VEQI-sJKfZI/AAAAAAAAAlA/9UtEyStfDHw/s1600/slider-arrow-right.png) no-repeat;
                margin-left: -40px;
                border-width: 0px;
                width: 43px;
                height: 45px;
            }
            /*==============================*/        
            .head_content {
                display: flex;
                justify-content: space-between;
                margin-top: 100px;
                margin-left: 50px;
                margin-right: 50px;
                align-items: center;
            }          
            .item_left {
                align-content: center;
                align-items: center;
                margin: 0 auto;
            }       
            .item_left_rotate {
                align-content: center;
                align-items: center;
                box-shadow: 3px 3px 3px darkgrey;
            }       
            .item_left h2 {
                text-align: center;
                color: red;
            }         
            .item_left p {
                text-align: center;
            }       
            .item_left a {
                text-decoration: none;
                color: red;
                font-weight: bold;
            }       
            .item_left img {
                width: 500px;
                height: 300px;
            }         
            .item_left_rotate:hover {
                transform: rotate(-5deg);
                -moz-transform: rotate(-5deg);
                -webkit-transform: rotate(-5deg);
                -o-transform: rotate(-5deg);
                -ms-transform: rotate(-5deg);
            }
            /*==============================*/    
            .top_body_content {
                display: flex;
                justify-content: space-between;
                margin-top: 100px;
                margin-left: 50px;
                margin-right: 50px;
                align-items: center;
            }  
            .top_body_content a {
                text-decoration: none;
            }
            /*==============================*/  
            .grid-container {
                display: grid;
                grid-template-columns: auto auto auto auto;
                grid-gap: 10px;
                margin-left: 50px;
                margin-right: 50px;
            }        
            .grid-item {
                font-family: 'Playfair Display', Arial, sans-serif;
                position: relative;
                overflow: hidden;
                margin: 10px;
                min-width: 230px;
                max-width: 315px;
                max-height: 220px;
                width: 100%;
                color: #000000;
                text-align: right;
                font-size: 16px;
                background-color: #000000;
                box-shadow: 3px 3px 3px darkgrey;
            }       
            .grid-item * {
                -webkit-box-sizing: border-box;
                box-sizing: border-box;
                -webkit-transition: all 0.35s ease;
                transition: all 0.35s ease;
            }          
            .grid-item img {
                max-width: 100%;
                backface-visibility: hidden;
            }                     
            .grid-item figcaption {
                position: absolute;
                top: 0;
                bottom: 0;
                right: 0;
                z-index: 1;
                opacity: 1;
                padding: 30px 0 30px 10px;
                background-color: #ffffff;
                width: 40%;
                -webkit-transform: translateX(150%);
                transform: translateX(150%);
                border-style: solid;
                border-color: white;
            }                    
            .grid-item figcaption:before {
                position: absolute;
                top: 50%;
                -webkit-transform: translateY(-50%);
                transform: translateY(-50%);
                right: 100%;
                content: '';
                width: 0;
                height: 0;
                border-style: solid;
                border-width: 120px 120px 120px 0;
                border-color: transparent #ffffff transparent transparent;
            }                       
            .grid-item:after {
                position: absolute;
                bottom: 50%;
                right: 40%;
                content: '';
                width: 0;
                height: 0;
                border-style: solid;
                border-width: 120px 120px 0 120px;
                border-color: rgba(255, 255, 255, 0.5) transparent transparent transparent;
                -webkit-transform: translateY(-50%);
                transform: translateY(-50%);
                -webkit-transition: all 0.35s ease;
                transition: all 0.35s ease;
            }                    
            .grid-item h3,
            .grid-item p {
                line-height: 1.0em;
                -webkit-transform: translateX(-30px);
                transform: translateX(-30px);
                margin: 0;
            }           
            .grid-item h3 {
                margin: 0 0 5px;
                line-height: 1.1em;
                font-weight: 900;
                font-size: 1.4em;
                opacity: 0.75;
            }                 
            .grid-item p {
                font-size: 0.8em;
            }        
            .grid-item button i {
                position: absolute;
                bottom: 0;
                left: 0;
                padding: 20px 30px;
                font-size: 44px;
                color: #ffffff;
                opacity: 0;
            }                       
            .grid-item button {
                border: none;
                background: black;
            }     
            .grid-item a {
                position: absolute;
                top: 0;
                bottom: 0;
                left: 0;
                right: 0;
                z-index: 1;
            }                     
            .grid-item:hover img,
            .grid-item.hover img {
                zoom: 1;
                filter: alpha(opacity=50);
                -webkit-opacity: 0.5;
                opacity: 0.5;
            }                    
            .grid-item:hover:after,
            .grid-item.hover:after,
            .grid-item:hover figcaption,
            .grid-item.hover figcaption,
            .grid-item:hover i,
            .grid-item.hover i {
                -webkit-transform: translateX(0);
                transform: translateX(0);
                opacity: 1;
            }
           /*===========Doi tac va lien he=====================*/
                        
            .dt-grid-container {
                display: grid;
                grid-template-columns: auto auto auto auto auto auto;
                grid-gap: 5px;
                margin-left: 50px;
                margin-right: 50px;
            }          
            .thumbnail {
                width: 180px;
                height: 150px;
                overflow: hidden;
                border: none;
            }          
            .thumbnail img {
                width: 100%;
                height: 100%;
                transition-duration: 0.3s;
            }                       
            .thumbnail img:hover {
                transform: scale(1.2);
            }
            /*================================*/
                        
            .numbertext {
                display: flex;
            }
        </style>
    </head>
    <body>      
        <div class="header-container">
            <!---------------------------------------------HEADER--------------------------------------------------->
            <div class="container_header">
                <div class="header_left">
                    <p>
                        <span>Địa chỉ:</span>101B Lê Hữu Trác, Phước Mỹ, Sơn Trà, Đà Nẵng
                    </p>
                </div>
                <div class="header_right">
                    <div class="items">
                        <img src="Souvernir/fb.png">
                    </div>
                    &emsp;
                    <div class="items">
                        <img src="Souvernir/gplus.png">
                    </div>
                    &emsp;
                    <div class="items">
                        <img src="Souvernir/linkedin.png">
                    </div>
                    &emsp;
                    <div class="items">
                        <img src="Souvernir/stumbleupon.png">
                    </div>
                    &emsp;
                    <div class="items">
                        <img src="Souvernir/twitter.png">
                    </div>
                    &emsp;
                    <div class="items">
                        <img src="Souvernir/zing.png">
                    </div>                              
                </div>
            </div>
            <!-------------------------------------------------------BODY----------------------------------------------->
            <?php 
                error_reporting(0);
                if($_SESSION['log']==true){
            ?>
                    <!-- ===============================================Ten Shop========================================================   -->
                    <nav class="navbar navbar-dark primary-color">
                        <div style="position:relative; margin: auto;">
                            <img src="Souvernir/souvernirlogo.png" style="width: 300px; height: 80px;">
                        </div>
                    </nav>
                    <!-- ===============================================Menu Bar========================================================   -->
                    <div class="wrapper">
                        <nav class="menu" style="float: left;">
                            <ul class="clearfix">
                                <li>
                                    <a href="#">TRANG CHỦ</a>
                                </li>
                                <li>
                                    <a href="#">SẢN PHẨM</a>
                                </li>
                                <li>
                                    <a href="#">ĐƠN HÀNG</a>
                                </li>
                            </ul>
                        </nav>
                        <div class="menu" style="float: right;">
                            <ul class="clearfix">
                                <li>
                                    <img src="Souvernir/taikhoan.png" style="width: 30px; height: 30px;">
                                    <ul class="sub-menu" style="width: 200px;">
                                        <?php 
                                        error_reporting(0);
                                        if($_SESSION['name']==''){
                                        ?>
                                            <li>
                                                <button data-toggle="modal" data-target="#modalLoginForm" style="border: none; font-size: 16px;">ĐĂNG NHẬP</button>
                                            </li>
                                            <li>
                                                <button data-toggle="modal" data-target="#modalRegisterForm" style="border: none; font-size: 16px;">ĐĂNG KÍ</button>
                                            </li>
                                        <?php }
                                        else{?>
                                            <li>
                                                <p style="text-align: center; background:dodgerblue;"><i class="fas fa-user" style="font-size:25px;color:tomato"></i>
                                                    <?php echo $_SESSION['name'];?>
                                                </p>
                                            </li>
                                            <li>
                                                <form action="" method="POST">
                                                    <button style="border: none; font-size: 16px;text-align: center;" name="logout">ĐĂNG XUẤT</button>
                                                </form>
                                            </li>
                                        <?php }?>
                                    </ul>
                                </li>
                                &emsp;
                                <li>
                                    <form id="searchbox" method="get" action="/search" autocomplete="off">
                                        <input name="q" type="text" size="15" placeholder="Enter keywords..." />
                                        <input id="button-submit" type="submit" value=" " />
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- ===============================================Slide Show=====================================================   -->
                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                        </ol>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="Souvernir/slideshow/banner1.jpg" style="width:100%">
                            </div>
                            <div class="carousel-item">
                                <img src="Souvernir/slideshow/baner2.jpg" style="width:100%">
                            </div>
                            <div class="carousel-item">
                                <img src="Souvernir/slideshow/banner3.jpg" style="width:100%">
                            </div>
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                    <!-- ===================================================PRODUCT MANAGEMENT============================================-->
                    <div class="top_body_content">
                        <hr width="30%" height="10px" align="center; color:black;">
                        <h2 style="color:red">PRODUCT MANAGEMENT</h2>
                        <hr width="30%" height="10px" align="center; color:black;">
                    </div>
                    <!--=============================================Tao Form them sp========================================-->
                    <form action="" method="post" enctype="multipart/form-data">
                        <!------------------------------------------------------------------------------->
                        <div class="form-row">
                            <div class="col">
                                <input type="text" class="form-control" name="name" placeholder="Name ">
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" name="price" placeholder="Price">
                            </div>
                        </div>
                        <br/>
                        <!------------------------------------------------------------------------------->
                        <div class="form-row">
                            <input type="text" class="form-control" name="detail" placeholder="Detail ">
                        </div>
                        </br>
                        <!------------------------------------------------------------------------------->
                        <div class="form-row">
                            <div class="col">
                                <select name="select" class="form-control col-md-4">
                                    <option value="QUÀ ĐỂ BÀN">QUÀ ĐỂ BÀN</option>
                                    <option value="QUÀ TẶNG PHA LÊ">QUÀ TẶNG PHA LÊ</option>
                                    <option value="GẤU BÔNG">GẤU BÔNG</option>
                                    <option value="MÓC KHÓA">MÓC KHÓA</option>
                                    <option value="BÚT">BÚT</option>
                                    <option value="TIỆN ÍCH">TIỆN ÍCH</option>
                                    <option value="ĐỒ MĨ NGHỆ">ĐỒ MĨ NGHỆ</option>
                                    <option value="SẢN PHẨM SÀNH SỨ">SẢN PHẨM SÀNH SỨ</option>
                                </select>
                            </div>
                            <div class="col">
                                <input type="file" class="form-control-file col-md-3" name="fileToUpLoad" id="img">
                            </div>
                        </div>
                        <br/>
                        <!------------------------------------------------------------------------------->
                        <div class="form-group">
                            <button class="btn btn-danger" name="addpr">Add Product</button>
                        </div>
                    </form>

                    <!--====================================Form sua san pham===============================================-->
                    <?php
                    for($i=0;$i<count($result);$i++){ ?>
                        <div id="modal<?php echo $result[$i][0]?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <form action="" method="post">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="my-modal-title">EDIT</h5>
                                            <button class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="">Name</label>
                                                <input type="text" class="form-control" name="namePr" id="" value="<?php echo $Products[$i]->name?>" placeholder="">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Type</label>
                                                <select class="form-control col-md-4" name="typePr" id="" value="<?php echo $Products[$i]->getType()?>" placeholder="">
                                                    <option value="QUÀ ĐỂ BÀN">QUÀ ĐỂ BÀN</option>
                                                    <option value="QUÀ TẶNG PHA LÊ">QUÀ TẶNG PHA LÊ</option>
                                                    <option value="GẤU BÔNG">GẤU BÔNG</option>
                                                    <option value="MÓC KHÓA">MÓC KHÓA</option>
                                                    <option value="BÚT">BÚT</option>
                                                    <option value="TIỆN ÍCH">TIỆN ÍCH</option>
                                                    <option value="ĐỒ MĨ NGHỆ">ĐỒ MĨ NGHỆ</option>
                                                    <option value="SẢN PHẨM SÀNH SỨ">SẢN PHẨM SÀNH SỨ</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Price</label>
                                                <input type="number" class="form-control" name="pricePr" id="" value="<?php echo $Products[$i]->getPrice()?>" placeholder="">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Picture</label>
                                                <input type="file" class="form-control" name="imagePr" id="" value="<?php echo $Products[$i]->getImagePath()?>" placeholder="">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" name="edit" value="<?php echo $result[$i][0];?>" class="btn btn-primary">OK</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    <?php }?>
                    <!--================================================Bang tat ca san pham==================================-->
                    <table align="center" width="600px" border="1" cellspacing="0" cellpadding="3" class="table table-hover table-bordered" id="mytable">
                        <tr class="bg-success" style="text-align: center;">
                            <th>ID</th>
                            <th>Name Product</th>
                            <th>Image</th>
                            <th>Type</th>
                            <th>Price Product</th>
                            <th>Repair</th>
                            <th>Delete</th>
                        </tr>
                        <?php 
                        for($i = 0; $i < count($Products); $i++) { 
                            echo '<tr>';
                            echo '<td style="text-align: center;">'.$Products[$i]->id.'</td>';
                            echo '<td style="text-align: center;">'.$Products[$i]->name.'</td>';
                            echo '<td style="text-align: center;"><img src="'.$Products[$i]->getImagePath().'" style="width: 50px; height: 50px;" ></td>';
                            echo '<td style="text-align: center;">'.$Products[$i]->getType().'</td>';
                            echo '<td style="text-align: center;">'.$Products[$i]->getPrice().'</td>';
                            echo '<td style="text-align: center;">'.'<button class="edit" data-toggle="modal" data-target="#modal'.$result[$i][0].'" name="id" value="'.$result[$i][0].'"  style="border: none"><i class="fa fa-edit"></i></button>'.'</td>';
                            echo '<td style="text-align: center;">'.'<form method="post"><button type="submit" class="del" name="dele" value="'.$result[$i][0].'" style="border: none; color:red;"> <i class="fa fa-trash"></i></button></form>'.'</td>';
                            echo '</tr>';
                        }?>
                    </table>

                <?php }
                else{?>
                    <!--==============================================CUSTOMER===========================================================-->
                    <nav class="navbar navbar-dark primary-color">
                        <div style="position:relative; margin: auto;">
                            <img src="Souvernir/souvernirlogo.png" style="width: 300px; height: 80px;">
                        </div>
                    </nav>
                    <!--==========================================Menu Bar==========================================================-->
                    <div class="wrapper">
                        <nav class="menu" style="float: left;">
                            <ul class="clearfix">
                                <li>
                                    <a href="#">TRANG CHỦ</a>
                                </li>
                                <li>
                                    <a href="#">SẢN PHẨM <span class="arrow">&#9660;</span></a>
                                    <ul class="sub-menu">
                                        <li><a href="#">QUÀ ĐỂ BÀN</a></li>
                                        <li><a href="#">QUÀ TẶNG PHA LÊ</a></li>
                                        <li><a href="#">GẤU BÔNG</a></li>
                                        <li><a href="#">MÓC KHÓA</a></li>
                                        <li><a href="#">BÚT</a></li>
                                        <li><a href="#">TIỆN ÍCH</a></li>
                                        <li><a href="#">ĐỒ MĨ NGHỆ</a></li>
                                        <li><a href="#">SẢN PHẨM SÀNH SỨ</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="#">GIỚI THIỆU</a>
                                </li>
                                <li>
                                    <a href="#">LIÊN HỆ</a>
                                </li>
                            </ul>
                            </nav>
                                <div class="menu" style="float: right;">
                                <ul class="clearfix">
                                    <li>
                                        <img src="Souvernir/taikhoan.png" style="width: 30px; height: 30px;">
                                        <ul class="sub-menu" style="width: 200px;">
                                        <?php 
                                        error_reporting(0);
                                        if($_SESSION['name']==''){ ?>
                                            <li>
                                                <button data-toggle="modal" data-target="#modalLoginForm" style="border: none; font-size: 16px;">ĐĂNG NHẬP</button>
                                            </li>
                                            <li>
                                                <button data-toggle="modal" data-target="#modalRegisterForm" style="border: none; font-size: 16px;">ĐĂNG KÍ</button>
                                            </li>
                                        <?php }
                                        else{?>
                                            <li>
                                                <p style="text-align: center; background:dodgerblue;"><i class="fas fa-user" style="font-size:25px;color:tomato"></i>
                                                    <?php echo $_SESSION['name'];?>
                                                </p>
                                            </li>
                                            <li>
                                                <form action="" method="POST">
                                                    <button style="border: none; font-size: 16px;text-align: center;" name="logout">ĐĂNG XUẤT</button>
                                                </form>
                                            </li>
                                        <?php }?>
                                    </ul>
                                </li>
                                &emsp;
                                <li>
                                    <img src="Souvernir/giohang.png">
                                    <ul class="sub-menu" style="width: 200px;">
                                        <li>
                                            <form action="cart.php" method="" style="text-align: right;">
                                                <button style="border: none; "><i class="fas fa-shopping-cart" style="color:firebrick;"></i>GIỎ HÀNG</button>
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <form id="searchbox"  action="search.php" method="get">
                                        <input name="search" type="text" size="15" placeholder="Enter keywords..." />
                                        <input id="button-submit" name="ok" type="submit"/>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!--================================================Slideshow====================================================-->
                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                        </ol>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="Souvernir/slideshow/banner1.jpg" style="width:100%">
                            </div>
                            <div class="carousel-item">
                                <img src="Souvernir/slideshow/baner2.jpg" style="width:100%">
                            </div>
                            <div class="carousel-item">
                                <img src="Souvernir/slideshow/banner3.jpg" style="width:100%">
                            </div>
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                    <!--=============================================Voucher gioi thieu===============================================-->
                    <div class="head_content">
                        <div class="item_left">
                            <h2>LƯU NIỆM ĐÀ NẴNG</h2>
                            <p>Trao quà tặng, trao yêu thương cùng <a href=#>Souvernir</a>.</p>
                            <div class="item_left_rotate">
                                <img src="Souvernir/vtines_sticky.jpg">
                            </div>
                        </div>
                        <div class="item_left">
                            <h2>CƠN MƯA QUÀ TẶNG</h2>
                            <p>Phiên chợ Sale 10/1 đến 26/1: Giảm giá khủng, “săn” quà đầy tay.</p>
                            <div class="item_left_rotate">
                                <img src="Souvernir/happynewyear.jpg">
                            </div>
                        </div>
                    </div>

                    <!--==============================================San pham moi nhat===============================================-->
                    <div class="top_body_content">
                        <hr width="30%" height="10px" align="center; color:black;">
                        <h2>SẢN PHẨM <a href=# style="color: red;">MỚI NHẤT</a></h2>
                        <hr width="30%" height="10px" align="center; color:black;">
                    </div>
                    <!---------------------------------Hien thi san pham---------------------------------->
                    <div class="grid-container">
                        <?php for($i = 0; $i < count($Products); $i++){  ?>
                            <figure class="grid-item">
                                <img class="img-fluid d-block mx-auto" src="<?php echo $Products[$i]->getImagePath() ?>" style="padding: 5px 5px 5px 5px">
                                <figcaption>
                                    <h3 style="font-size: 13px; text-align: center; color: brown">
                                        <?php 
                                        echo $Products[$i]->name;
                                        ?>
                                    </h3>
                                    <p style="font-size: 11px; color:midnightblue">
                                        <?php 
                                        echo $Products[$i]->detail;
                                        ?>
                                    </p>
                                    <form action="detail.php" method="get">
                                        <button name="detail" value="<?php echo $i?>"><i class="fa fa-info" style="font-size:20px;color:blue"></i></button>
                                    </form>
                                </figcaption>
                                <form action="" method="post">
                                    <button name="insert_cart" value="<?php echo $Products[$i]->id;?>"> <i class="fa fa-shopping-cart" style="font-size:20px;color:red"></i></button>
                                </form>             
                            </figure>
                        <?php }?>
                    </div>
                    <!--==============================================San pham noi bat===============================================-->
                    <div class="top_body_content">
                        <hr width="30%" height="10px" align="center; color:black;">
                        <h2>SẢN PHẨM <a href=# style="color: red;">NỔI BẬT</a></h2>
                        <hr width="30%" height="10px" align="center; color:black;">
                    </div>
                    <!---------------------------------Hien thi san pham---------------------------------->
                    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <div class="numbertext">
                                    <figure class="grid-item">
                                        <img class="img-fluid d-block mx-auto" src="<?php echo $Products[0]->getImagePath() ?>" style="padding: 5px 5px 5px 5px">
                                        <figcaption>
                                            <h3 style="font-size: 13px; text-align: center; color: brown">
                                                <?php 
                                                echo $Products[0]->name;
                                                ?>
                                            </h3>
                                            <p style="font-size: 11px; color:midnightblue">
                                                <?php 
                                                echo $Products[0]->detail;
                                                ?>
                                            </p>
                                        </figcaption>
                                        <form action="" method="post">
                                            <button name="insert_cart" value="<?php echo $Products[0]->id;?>"> <i class="fa fa-shopping-cart" style="font-size:20px;color:red"></i></button>
                                        </form>
                                    </figure>
                                    <figure class="grid-item">
                                        <img class="img-fluid d-block mx-auto" src="<?php echo $Products[1]->getImagePath() ?>" style="padding: 5px 5px 5px 5px">
                                    <figcaption>
                                        <h3 style="font-size: 13px; text-align: center; color: brown">
                                            <?php 
                                            echo $Products[1]->name;
                                            ?>
                                        </h3>
                                        <p style="font-size: 11px; color:midnightblue">
                                            <?php 
                                            echo $Products[1]->detail;
                                            ?>
                                        </p>
                                    </figcaption>
                                    <form action="" method="post">
                                        <button name="insert_cart" value="<?php echo $Products[1]->id;?>"> <i class="fa fa-shopping-cart" style="font-size:20px;color:red"></i></button>
                                    </form>
                                </figure>
                                <figure class="grid-item">
                                    <img class="img-fluid d-block mx-auto" src="<?php echo $Products[2]->getImagePath() ?>" style="padding: 5px 5px 5px 5px">
                                    <figcaption>
                                        <h3 style="font-size: 13px; text-align: center; color: brown">
                                            <?php 
                                            echo $Products[2]->name;
                                            ?>
                                        </h3>
                                        <p style="font-size: 11px; color:midnightblue">
                                            <?php 
                                            echo $Products[2]->detail;
                                            ?>
                                        </p>
                                    </figcaption>
                                    <form action="" method="post">
                                        <button name="insert_cart" value="<?php echo $Products[2]->id;?>"> <i class="fa fa-shopping-cart" style="font-size:20px;color:red"></i></button>
                                    </form>
                                </figure>
                                <figure class="grid-item">
                                    <img class="img-fluid d-block mx-auto" src="<?php echo $Products[3]->getImagePath() ?>" style="padding: 5px 5px 5px 5px">
                                        <figcaption>
                                            <h3 style="font-size: 13px; text-align: center; color: brown">
                                                <?php 
                                                echo $Products[3]->name;
                                                ?>
                                            </h3>
                                            <p style="font-size: 11px; color:midnightblue">
                                                <?php 
                                                echo $Products[3]->detail;
                                                ?>
                                            </p>
                                        </figcaption>
                                        <form action="" method="post">
                                            <button name="insert_cart" value="<?php echo $Products[3]->id;?>"> <i class="fa fa-shopping-cart" style="font-size:20px;color:red"></i></button>
                                        </form>
                                    </figure>
                                </div>
                            </div>
                            <!------------------------------------------------------------------------------------------------------------>
                            <div class="carousel-item">
                                <div class="numbertext">
                                    <figure class="grid-item">
                                        <img class="img-fluid d-block mx-auto" src="<?php echo $Products[6]->getImagePath() ?>" style="padding: 5px 5px 5px 5px">
                                        <figcaption>
                                            <h3 style="font-size: 13px; text-align: center; color: brown">
                                                <?php 
                                                echo $Products[6]->name;
                                                ?>
                                            </h3>
                                            <p style="font-size: 11px; color:midnightblue">
                                                <?php 
                                                echo $Products[6]->detail;
                                                ?>
                                            </p>
                                        </figcaption>
                                        <form action="" method="post">
                                            <button name="insert_cart" value="<?php echo $Products[6]->id;?>"> <i class="fa fa-shopping-cart" style="font-size:20px;color:red"></i></button>
                                        </form>
                                    </figure>
                                    <figure class="grid-item">
                                        <img class="img-fluid d-block mx-auto" src="<?php echo $Products[8]->getImagePath() ?>" style="padding: 5px 5px 5px 5px">
                                        <figcaption>
                                            <h3 style="font-size: 13px; text-align: center; color: brown">
                                                <?php 
                                                echo $Products[8]->name;
                                                ?>
                                            </h3>
                                            <p style="font-size: 11px; color:midnightblue">
                                                <?php 
                                                echo $Products[8]->detail;
                                                ?>
                                            </p>
                                        </figcaption>
                                        <form action="" method="post">
                                            <button name="insert_cart" value="<?php echo $Products[8]->id;?>"> <i class="fa fa-shopping-cart" style="font-size:20px;color:red"></i></button>
                                        </form>
                                    </figure>
                                    <figure class="grid-item">
                                        <img class="img-fluid d-block mx-auto" src="<?php echo $Products[10]->getImagePath() ?>" style="padding: 5px 5px 5px 5px">
                                        <figcaption>
                                            <h3 style="font-size: 13px; text-align: center; color: brown">
                                                <?php 
                                                echo $Products[10]->name;
                                                ?>
                                            </h3>
                                            <p style="font-size: 11px; color:midnightblue">
                                                <?php 
                                                echo $Products[10]->detail;
                                                ?>
                                            </p>
                                        </figcaption>
                                        <form action="" method="post">
                                            <button name="insert_cart" value="<?php echo $Products[10]->id;?>"> <i class="fa fa-shopping-cart" style="font-size:20px;color:red"></i></button>
                                        </form>
                                    </figure>
                                    <figure class="grid-item">
                                        <img class="img-fluid d-block mx-auto" src="<?php echo $Products[5]->getImagePath() ?>" style="padding: 5px 5px 5px 5px">
                                        <figcaption>
                                            <h3 style="font-size: 13px; text-align: center; color: brown">
                                                <?php 
                                                echo $Products[5]->name;
                                                ?>
                                            </h3>
                                            <p style="font-size: 11px; color:midnightblue">
                                                <?php 
                                                echo $Products[5]->detail;
                                                ?>
                                            </p>
                                        </figcaption>
                                        <form action="" method="post">
                                            <button name="insert_cart" value="<?php echo $Products[5]->id;?>"> <i class="fa fa-shopping-cart" style="font-size:20px;color:red"></i></button>
                                        </form>
                                    </figure>
                                </div>
                            </div>
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                <?php }?>
                <!--============================================DOI TAC VA LIEN KET===============================================-->
                <div class="top_body_content">
                    <hr width="30%" height="10px" align="center; color:black;">
                    <h2>ĐỐI TÁC & <a href=# style="color: red;">LIÊN KẾT</a></h2>
                    <hr width="30%" height="10px" align="center; color:black;">
                </div>
                <div class="dt-grid-container">
                    <div class="thumbnail">
                        <img src="Souvernir/Doitac/1.jpg">
                    </div>
                    <div class="thumbnail">
                        <img src="Souvernir/Doitac/2.jpg">
                    </div>
                    <div class="thumbnail">
                        <img src="Souvernir/Doitac/3.jpg">
                    </div>
                    <div class="thumbnail">
                        <img src="Souvernir/Doitac/4.jpg">
                    </div>
                    <div class="thumbnail">
                        <img src="Souvernir/Doitac/5.jpg">
                    </div>
                    <div class="thumbnail">
                        <img src="Souvernir/Doitac/6.jpg">
                    </div>
                </div>
                <!-- ==================================================FOOTER======================================================== -->
                <hr style="color: white; margin-top: 0px; margin-bottom: 0px;">
                <footer class="page-footer font-small special-color-dark pt-4" style="background-color:cornsilk;">
                    <div class="container">
                        <ul class="list-unstyled list-inline text-center">
                            <li class="list-inline-item">
                                <a class="btn-floating btn-fb mx-1" style="color:royalblue;">
                                    <i class="fab fa-facebook-f"> </i>
                                </a>
                            </li>
                        <li class="list-inline-item">
                            <a class="btn-floating btn-tw mx-1" style="color:skyblue;">
                                <i class="fab fa-twitter"> </i>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a class="btn-floating btn-gplus mx-1">
                                <i class="fab fa-google-plus-g" style="color:tomato;"> </i>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a class="btn-floating btn-li mx-1">
                                <i class="fab fa-linkedin-in" style="color:navy;"> </i>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a class="btn-floating btn-dribbble mx-1">
                                <i class="fab fa-dribbble" style="color:mediumvioletred;"> </i>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="footer-copyright text-center py-3">© 2019 Dinh Hoa:
                    <a href="index.php"> SouvernirShop.com</a>
                </div>
            </footer>
            <div class="header-container">
            </div>
            <!----------------------------------------------------------------------------------------------------------------->
        </div>
        <!--===================================================FORM DANG NHAP================================================-->
        <div class="modal fade" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="" method="post">
                        <div class="modal-header text-center">
                            <h4 class="modal-title w-100 font-weight-bold">Đăng nhập</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>      
                        <div class="modal-body mx-3">
                            <div class="md-form mb-5">
                                <i class="fas fa-user"></i>
                                <input type="text" id="defaultForm-user" class="form-control validate" name="accountLogin">
                                <label data-error="wrong" data-success="right" for="defaultForm-email">Account</label>
                            </div>
                            <div class="md-form mb-4">
                                <i class="fas fa-lock prefix grey-text"></i>
                                <input type="password" id="defaultForm-pass" class="form-control validate" name="passLogin">
                                <label data-error="wrong" data-success="right" for="defaultForm-pass">Password</label>
                            </div>
                        </div>
                        <div class="modal-footer d-flex justify-content-center">
                            <button class="btn btn-default" style="background-color:dodgerblue;" name="login">OK</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--=============================================================FORM DANG KI=========================================-->
        <div class="modal fade" id="modalRegisterForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="" method="post">
                        <div class="modal-header text-center">
                            <h4 class="modal-title w-100 font-weight-bold">Đăng kí</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body mx-3">
                            <div class="md-form mb-5">
                                <i class="fas fa-user prefix grey-text"></i>
                                <input type="text" id="orangeForm-name" class="form-control validate" name="nameLogup">
                                <label data-error="wrong" data-success="right" for="orangeForm-name">Tên</label>
                            </div>
                            <div class="md-form mb-5">
                                <i class="fas fa-user"></i>
                                <input type="text" id="defaultForm-user" class="form-control validate" name="accountLogup">
                                <label data-error="wrong" data-success="right" for="defaultForm-email">Account</label>
                            </div>
                            <div class="md-form mb-4">
                                <i class="fas fa-lock prefix grey-text"></i>
                                <input type="password" id="defaultForm-pass" class="form-control validate" name="passLogup">
                                <label data-error="wrong" data-success="right" for="defaultForm-pass">Password</label>
                            </div>
                        </div>
                        <div class="modal-footer d-flex justify-content-center">
                            <button class="btn btn-default" style="background-color:dodgerblue;" name="logup">OK</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
    </body>
</html>