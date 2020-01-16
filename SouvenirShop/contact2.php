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

    /*==========================================================Dang nhap=================================================*/
    $sql2 = "SELECT * from user";
    $result2 = $db->query($sql2)->fetch_all();
    $Accounts = array();
    for ($i = 0; $i < count($result2); $i++) {
        $Product = $result[$i];
        $user= new User($result2[$i][0],$result2[$i][1],$result2[$i][2],$result2[$i][3],$result2[$i][4] );
        array_push($Accounts,$user);
    }    

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
        for($i = 0; $i < count($Accounts); $i++) { 
            if($username==$Accounts[$i]->account && $password==$Accounts[$i]->password){
                $check=true;
                if($Accounts[$i]->role=="admin"){
                    $log=true;
                    $_SESSION['log'] = $log;
                    $_SESSION['name'] = $username;
                    break;
                }
                else{
                    $log=false;
                    $_SESSION['log'] = $log;
                    $_SESSION['name'] = $username;
                    break;
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
if (isset($_POST['register'])){
$username = addslashes($_POST['nameRegister']);
$useraccount = addslashes($_POST['accountRegister']);
$password = addslashes($_POST['passRegister']);
if (!$username || !$useraccount || !$password) {
    echo "Please input all information. <a href='javascript: history.go(-1)'>Trở lại</a>";
    exit;
}
for($i = 0; $i < count($Accounts); $i++) { 
    if($username==$Accounts[$i]->fullName && $useraccount==$Accounts[$i]->account && $password==$Accounts[$i]->password){
        $check=true;
        ?>
<script>
alert("Tài khoản đã tồn tại!");
</script>
<?php
    }
    else{
        $check=false;
    }
}
if ($check==false){
    $pos="user";
    $sql2 = "INSERT into User values(null,'".$username."','".$useraccount."','".$password."','".$pos."')";
    $db->query($sql2);
    ?>
<script>
alert("Đăng kí thành công!");
</script>
<?php
}
} 
    /*========================================================Log out======================================================*/   
    if(isset($_POST['logout'])){
        unset($_SESSION['name']);
        $_SESSION['log']=false;
        header("index.php");
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
            $sql1 = "SELECT * from cart where cart.idUser=(select id from User where username ='".$_SESSION['name']."')";
            $result1 = $db->query($sql1)->fetch_all();
            $i=$_POST["insert_cart"]-1;     
            $id=$i+1;
            $check=false;
            $sql0 = "SELECT id from User where username ='".$_SESSION['name']."'";
            $ktraUser=$db->query($sql0)->fetch_all();
            $idUser= $ktraUser[0][0];
            for($j = 0; $j < count($result1); $j++) {
                if ($result1[$j][1]==$id){
                    $check=true;
                    $updateCart = "UPDATE cart SET quantity = ".($result1[$j][5]+1).", total=".(($result1[$j][5]+1)*($result1[$j][4]))." WHERE idPr=".$id;
                    $db->query($updateCart);
                    break;
                }
            }      

            if($check==false){
                $img=$result[$i][2];
                $price=$result[$i][3];
                $name=$result[$i][1];
                $quantity=1;
                $total=$price*$quantity;
                $sql1 = "INSERT into cart values(null,".$id.",'".$img."','".$name."',".$price.",".$quantity.",".$total.",".$idUser.")";
                $db->query($sql1);  
            }
        }
    }
/*===========================================================================================================*/
$_SESSION['type']="";
    if(isset($_POST['type1'])){   
    $type=$_POST['type1'];
    $_SESSION['type']=$type;
    }
    if(isset($_POST['type2'])){
        $type=$_POST['type2'];  
        $_SESSION['type']=$type;
    }
    if(isset($_POST['type3'])){
        $type=$_POST['type3'];  
        $_SESSION['type']=$type;
    }
    if(isset($_POST['type4'])){
        $type=$_POST['type4']; 
        $_SESSION['type']=$type; 
    } 
    if(isset($_POST['type5'])){
        $type=$_POST['type5']; 
        $_SESSION['type']=$type; 
    } 
    if(isset($_POST['type6'])){
        $type=$_POST['type6']; 
        $_SESSION['type']=$type; 
    } 
    if(isset($_POST['type7'])){
        $type=$_POST['type7']; 
        $_SESSION['type']=$type; 
    } 
    if(isset($_POST['type8'])){
        $type=$_POST['type8']; 
        $_SESSION['type']=$type; 
    } 

    $ht = "SELECT * from Product where type='".$_SESSION['type']."'";
    $searchType = $db->query($ht)->fetch_all() ;  

// <!-- --------------------------------contact-------------------------------------- -->
$idContact = "SELECT id from User where username ='".$_SESSION['name']."'";
		$resultId=$db->query($idContact)->fetch_all();
		$idInbox=$resultId[0][0];
$sql = "SELECT * from chatbox where idUser in (1,".$idInbox.") and idChat in (1,".$idInbox.") ORDER BY timeInbox ASC";
$result = $db->query($sql)->fetch_all();

    if(isset($_POST['send'])){
        $text=$_POST['text'];
		$today = date("G:i:s");
        $insert = "INSERT into chatbox values(null,'".$text."','".$today."',$idInbox,1)";
		$db->query($insert);
		header("Location:contact2.php");
    }
    ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>CONTACT</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css"
        integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css"
        integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="souvenir.css">
    <style>
    .chat {
        margin-top: auto;
        margin-bottom: auto;
    }

    .card {
        height: 500px;
        width: 700px;
        margin-bottom: 20px;
        margin-top: 20px;
        margin-left: 10px;
        border-radius: 15px !important;
        background-color: rgba(0, 0, 0, 0.4) !important;
    }

    .contacts_body {
        padding: 0.75rem 0 !important;
        overflow-y: auto;
        white-space: nowrap;
    }

    .msg_card_body {
        overflow-y: auto;
    }

    .card-header {
        border-radius: 15px 15px 0 0 !important;
        border-bottom: 0 !important;
        background-color: mediumseagreen;
    }

    .card-footer {
        border-radius: 0 0 15px 15px !important;
        border-top: 0 !important;
        background-color: white;
    }

    .container {
        align-content: center;
    }

    .search {
        border-radius: 15px 0 0 15px !important;
        background-color: rgba(0, 0, 0, 0.3) !important;
        border: 0 !important;
        color: white !important;
    }

    .search:focus {
        box-shadow: none !important;
        outline: 0px !important;
    }

    .type_msg {
        background-color: rgba(0, 0, 0, 0.3) !important;
        border: 0 !important;
        color: white !important;
        height: 60px !important;
        overflow-y: auto;
    }

    .type_msg:focus {
        box-shadow: none !important;
        outline: 0px !important;
    }

    .attach_btn {
        border-radius: 15px 0 0 15px !important;
        background-color: rgba(0, 0, 0, 0.3) !important;
        border: 0 !important;
        color: white !important;
        cursor: pointer;
    }

    .send_btn {
        border-radius: 0 15px 15px 0 !important;
        background-color: rgba(0, 0, 0, 0.3) !important;
        border: 0 !important;
        color: white !important;
        cursor: pointer;
    }

    .search_btn {
        border-radius: 0 15px 15px 0 !important;
        background-color: rgba(0, 0, 0, 0.3) !important;
        border: 0 !important;
        color: white !important;
        cursor: pointer;
    }

    .contacts {
        list-style: none;
        padding: 0;
    }

    .contacts li {
        width: 100% !important;
        padding: 5px 10px;
        margin-bottom: 15px !important;
    }

    .active {
        background-color: rgba(0, 0, 0, 0.3);
    }

    .user_img {
        height: 70px;
        width: 70px;
        border: 1.5px solid #f5f6fa;

    }

    .user_img_msg {
        height: 40px;
        width: 40px;
        border: 1.5px solid #f5f6fa;

    }

    .img_cont {
        position: relative;
        height: 70px;
        width: 70px;
    }

    .img_cont_msg {
        height: 40px;
        width: 40px;
    }

    .online_icon {
        position: absolute;
        height: 15px;
        width: 15px;
        background-color: #4cd137;
        border-radius: 50%;
        bottom: 0.2em;
        right: 0.4em;
        border: 1.5px solid white;
    }

    .offline {
        background-color: #c23616 !important;
    }

    .user_info {
        margin-top: auto;
        margin-bottom: auto;
        margin-left: 15px;
    }

    .user_info span {
        font-size: 20px;
        color: white;
    }

    .user_info p {
        font-size: 10px;
        color: rgba(255, 255, 255, 0.6);
    }

    .video_cam {
        margin-left: 315px;
        margin-top: 5px;
    }

    .video_cam span {
        color: white;
        font-size: 20px;
        cursor: pointer;
        margin-right: 20px;
    }

    .msg_cotainer {
        margin-top: auto;
        margin-bottom: auto;
        margin-left: 10px;
        border-radius: 25px;
        background-color: #82ccdd;
        padding: 10px;
        position: relative;
    }

    .msg_cotainer_send {
        margin-top: auto;
        margin-bottom: auto;
        margin-right: 10px;
        border-radius: 25px;
        background-color: #78e08f;
        padding: 10px;
        position: relative;
    }

    .msg_time {
        position: absolute;
        left: 0;
        bottom: -15px;
        color: black;
        font-size: 10px;
        border: none;
    }

    .msg_time_send {
        position: absolute;
        right: 0;
        bottom: -15px;
        color: gray;
        font-size: 13px;
    }

    .msg_head {
        position: relative;
    }

    #action_menu_btn {
        position: absolute;
        right: 10px;
        top: 10px;
        color: white;
        cursor: pointer;
        font-size: 20px;
    }

    .action_menu {
        z-index: 1;
        position: absolute;
        padding: 15px 0;
        background-color: rgba(0, 0, 0, 0.5);
        color: white;
        border-radius: 15px;
        top: 30px;
        right: 15px;
        display: none;
    }

    .action_menu ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .action_menu ul li {
        width: 100%;
        padding: 10px 15px;
        margin-bottom: 5px;
    }

    .action_menu ul li i {
        padding-right: 10px;

    }

    .action_menu ul li:hover {
        cursor: pointer;
        background-color: rgba(0, 0, 0, 0.2);
    }

    @media(max-width: 576px) {
        .contacts_card {
            margin-bottom: 15px !important;
        }
    }
    </style>
</head>

<body>
    <div class="header-container">
        <!--===============================================HEAD====================================================-->
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
        <!--===============================================NAME SHOP================================================-->
        <nav class="navbar navbar-dark primary-color">
            <div style="position:relative; margin: auto;">
                <img src="Souvernir/souvernirlogo.png" style="width: 300px; height: 80px;">
            </div>
        </nav>
        <!--===============================================MENU BAR=================================================-->
        <div class="wrapper">
            <nav class="menu" style="float: left;">
                <ul class="clearfix">
                    <li>
                        <a href="index.php">TRANG CHỦ</a>
                    </li>
                    <li>
                        <a href="#">SẢN PHẨM <span class="arrow">&#9660;</span></a>
                        <ul class="sub-menu">
                            <form action="product.php" method="post">
                                <li><button name="type1" value="QUÀ ĐỂ BÀN"
                                        style="border: none; font-weight:bold; background:white">QUÀ ĐỂ BÀN</button>
                                </li>
                                <li><button name="type2" value="QUÀ TẶNG PHA LÊ"
                                        style="border: none; font-weight:bold; background:white">QUÀ TẶNG PHA
                                        LÊ</button></li>
                                <li><button name="type3" value="GẤU BÔNG"
                                        style="border: none; font-weight:bold; background:white">GẤU BÔNG</button></li>
                                <li><button name="type4" value="MÓC KHÓA"
                                        style="border: none; font-weight:bold; background:white">MÓC KHÓA</button></li>
                                <li><button name="type5" value="BÚT"
                                        style="border: none; font-weight:bold; background:white">BÚT</button></li>
                                <li><button name="type6" value="TIỆN ÍCH"
                                        style="border: none; font-weight:bold; background:white">TIỆN ÍCH</button></li>
                                <li><button name="type7" value="ĐỒ MĨ NGHỆ"
                                        style="border: none; font-weight:bold; background:white">ĐỒ MĨ NGHỆ</button>
                                </li>
                                <li><button name="type8" value="SÀNH SỨ"
                                        style="border: none; font-weight:bold; background:white">SẢN PHẨM SÀNH
                                        SỨ</button></li>
                            </form>
                        </ul>
                    </li>
                    <li>
                        <a href="introduce.php">GIỚI THIỆU</a>
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
                                <button data-toggle="modal" data-target="#modalLoginForm"
                                    style="border: none; font-size: 16px;">ĐĂNG NHẬP</button>
                            </li>
                            <li>
                                <button data-toggle="modal" data-target="#modalRegisterForm"
                                    style="border: none; font-size: 16px;">ĐĂNG KÍ</button>
                            </li>
                            <?php }
                                        else{?>
                            <li>
                                <p style="text-align: center; background:dodgerblue;"><i class="fas fa-user"
                                        style="font-size:25px;color:tomato"></i>
                                    <?php echo $_SESSION['name'];?>
                                </p>
                            </li>
                            <li>
                                <form action="" method="POST">
                                    <button style="border: none; font-size: 16px;text-align: center;" name="logout">ĐĂNG
                                        XUẤT</button>
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
                                    <button style="border: none; "><i class="fas fa-shopping-cart"
                                            style="color:firebrick;"></i>GIỎ HÀNG</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <form id="searchbox" action="search.php" method="get">
                            <input name="search" type="text" size="15" placeholder="Enter keywords..." />
                            <input id="button-submit" name="ok" type="submit" />
                        </form>
                    </li>
                </ul>
            </div>
        </div>


        <!-- =================================================LIEN HE======================================== -->
        <div style="margin-left:280px;">
            <div class="col-md-8 col-xl-20 chat">
                <div class="card">
                    <div class="card-header msg_head">
                        <div class="d-flex bd-highlight">
                            <div class="img_cont">
                                <img src="Souvernir/Contact/avatarAdmin.png" class="rounded-circle user_img">
                                <span class="online_icon"></span>
                            </div>
                            <div class="user_info">
                                <span>Souvernir Shop</span>
                                <p>17K người thích trang này</p>
                            </div>
                            <div class="video_cam">
                                <span><i class="fas fa-video"></i></span>
                                <span><i class="fas fa-phone"></i></span>
                                <span><i class="fas fa-ellipsis-v"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body msg_card_body" style="background-color:white">
                        <?php for($i=0; $i<count($result);$i++){?>
                        <div class="d-flex justify-content-start mb-4">
                            <?php 
                                    if($result[$i][3]==1){?>
                            <div class="img_cont_msg">
                                <img src="Souvernir/Contact/avatarAdmin.png" class="rounded-circle user_img_msg">
                            </div>
                            <?php }?>
                            <?php 
                                    if($result[$i][3]==1){?>
                            <div class="msg_cotainer">
                                <?php
                                        echo  $result[$i][1];
                                    ?>
                                <span class="msg_time"><?php 
                                        echo  $result[$i][2];
                                    ?></span>
                            </div>
                            <?php }?>
                        </div>
                        <div class="d-flex justify-content-end mb-4">
                            <div class="msg_cotainer_send">
                                <?php 
                                    if($result[$i][3]==$idInbox){
                                        echo  $result[$i][1];
                                    ?>
                                <span class="msg_time_send"><?php 
                                        echo  $result[$i][2];
                                    }
                                    ?></span>
                            </div>
                            <?php 
                                    if($result[$i][3]==$idInbox){?>
                            <div class="img_cont_msg">
                                <img src="Souvernir/Contact/user.png" class="rounded-circle user_img_msg"
                                    style="border-color: black">
                            </div>
                            <?php }?>
                        </div>
                        <?php }?>

                    </div>
                    <div class="card-footer">
                        <form action="" method="post">
                            <div class="input-group"
                                style="background: white; border-color:mediumseagreen;color: black">
                                <div class="input-group-append">
                                    <span class="input-group-text attach_btn"><i class="fas fa-paperclip"></i></span>
                                </div>
                                <textarea name="text" class="form-control type_msg"
                                    placeholder="Type your message..."></textarea>
                                <div class="input-group-append">
                                    <span class="input-group-text send_btn"><button name="send"
                                            style="border: none; background: none;"><i
                                                class="fas fa-location-arrow"></i></button></span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
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
        <!-- =================================================Footer================================================== -->
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
    </div>
    <!--===================================================FORM DANG NHAP================================================-->
    <div class="modal fade" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="" method="post">
                    <div class="modal-header text-center">
                        <h4 class="modal-title w-100 font-weight-bold" style="color:firebrick">ĐĂNG NHẬP</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body mx-3">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1" style="width: 100px;"><i
                                        class="fa fa-user"></i> Account</span>
                            </div>
                            <input type="text" class="form-control" aria-label="account" aria-describedby="basic-addon1"
                                name="accountLogin">
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1" style="width: 100px;"><i
                                        class="fas fa-lock prefix grey-text"></i> Password</span>
                            </div>
                            <input type="password" class="form-control" aria-label="account"
                                aria-describedby="basic-addon1" name="passLogin">
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button class="btn btn-default"
                            style="border-style:solid; background:firebrick;color:floralwhite; font-size:12px"
                            name="login">OK</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--=============================================================FORM DANG KI=========================================-->
    <div class="modal fade" id="modalRegisterForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="" method="post">
                    <div class="modal-header text-center">
                        <h4 class="modal-title w-100 font-weight-bold" style="color:firebrick">ĐĂNG KÍ</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body mx-3">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1" style="width: 100px;"><i
                                        class="fa fa-user"></i>Name</span>
                            </div>
                            <input type="text" class="form-control" aria-label="account" aria-describedby="basic-addon1"
                                name="nameRegister">
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1" style="width: 100px;"><i
                                        class="fa fa-user"></i>Account</span>
                            </div>
                            <input type="text" class="form-control" aria-label="account" aria-describedby="basic-addon1"
                                name="accountRegister">
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1" style="width: 100px;"><i
                                        class="fas fa-lock prefix grey-text"></i> Password</span>
                            </div>
                            <input type="password" class="form-control" aria-label="account"
                                aria-describedby="basic-addon1" name="passRegister">
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button class="btn btn-default"
                            style="border-style:solid; background:firebrick;color:floralwhite; font-size:12px"
                            name="register">OK</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- <script>
            function trochuyen(){
                
            }
        </script> -->
</body>

</html>