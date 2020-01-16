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
    $tvorder= "SELECT orders.id, customer.name, customer.address, orders.date_order,  orders.total_price
    from orders, customer
    where orders.id_cus=customer.id;";
    $resultOrder= $db->query($tvorder)->fetch_all() ;

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
    $_SESSION['name']="";
    $_SESSION['log']=false;
    header("Location:index.php");
   }
/*==========================================================================================================*/       

/*===================================================Admin xoa san pham================================================*/
	if(isset($_POST['dele'])){
		$del='DELETE FROM Product WHERE id='.$_POST['dele'];
        $db->query($del);
        header("Location:index.php");
	}
/*===================================================Admin sua san pham================================================*/
    if(isset($_POST['edit'])){
       $name_edit=$_POST['namePr'];
       $type_edit=$_POST['typePr'];
	   $price_edit=$_POST['pricePr'];
       /*-------------------------------------------------------------*/
       $target_dir = "Souvernir/Product/";
            $target_file = $target_dir.basename($_FILES["imagePr"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            // Check if image file is a actual image or fake image
            if (isset($_POST["edit"])) {
                $check = getimagesize($_FILES["imagePr"]["tmp_name"]);
                if ($check !== false) {
                    $uploadOk = 1;
                } else {
                    ?>
<script>
alert("File is not an image!");
</script>
<?php
                    $uploadOk = 0;
                }
            }
            // Check if file already exists
            if (file_exists($target_file)) {
                ?>
<script>
alert("Sorry, file already exists!");
</script>
<?php
                $uploadOk = 0;
            }
            // Check file size
            if ($_FILES["imagePr"]["size"] > 500000) {
                ?>
<script>
alert("Sorry, your file is too large!");
</script>
<?php
                $uploadOk = 0;
            }
            // Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif") {
                ?>
<script>
alert("Sorry, only JPG, JPEG, PNG & GIF files are allowed!");
</script>
<?php
                $uploadOk = 0;
            }
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                ?>
<script>
alert("Sorry, your file was not uploaded!");
</script>
<?php
            // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["imagePr"]["tmp_name"], $target_file)) {
                    ?>
<script>
alert("Edit product successful!");
</script>
<?php
                    $stm='UPDATE Product set name="'.$name_edit.'", type="'.$type_edit.'",price='.$price_edit.', image="'.$target_file.'" WHERE id='.$_POST['edit'];
                    $db->query($stm);
                    header("Location:index.php");
                } else {
                    ?>
<script>
alert("Sorry, there was an error uploading your file!");
</script>
<?php
                }
            }
       /*-------------------------------------------------------------*/
      
	   
   }

/*====================================================Sắp xếp==================================================*/
if(isset($_POST["sort"])){
    $sql = "SELECT * from Product order by price DESC";
    $result = $db->query($sql)->fetch_all() ;
} 
if(isset($_POST["rsort"])){
    $sql = "SELECT * from Product order by price ASC";
    $result = $db->query($sql)->fetch_all() ;
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
    
/*===================================================Admin them san pham================================================*/
    if(isset($_POST["addpr"])){
        $name=$_POST["name"];
		$price=$_POST["price"];
		$detail=$_POST["detail"];
        $type=$_POST["select"];

        if(empty($name)||empty($price)||empty($detail)||empty($type)){
        ?>
<script>
alert("Vui lòng nhập đầy đủ thông tin!");
</script>
<?php
        }
        else{

        /*---------------------------*/
            $target_dir = "Souvernir/Product/";
            $target_file = $target_dir.basename($_FILES["fileToUpload"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            // Check if image file is a actual image or fake image
            if (isset($_POST["addpr"])) {
                $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                if ($check !== false) {
                    $uploadOk = 1;
                } else {
                    ?>
<script>
alert("File is not an image!");
</script>
<?php
                    $uploadOk = 0;
                }
            }
            // Check if file already exists
            if (file_exists($target_file)) {
                ?>
<script>
alert("Sorry, file already exists!");
</script>
<?php
                $uploadOk = 0;
            }
            // Check file size
            if ($_FILES["fileToUpload"]["size"] > 500000) {
                ?>
<script>
alert("Sorry, your file is too large!");
</script>
<?php
                $uploadOk = 0;
            }
            // Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif") {
                ?>
<script>
alert("Sorry, only JPG, JPEG, PNG & GIF files are allowed!");
</script>
<?php
                $uploadOk = 0;
            }
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                ?>
<script>
alert("Sorry, your file was not uploaded!");
</script>
<?php
            // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    ?>
<script>
alert("Add product successfull!");
</script>
<?php
                    $adminAdd = "INSERT into Product values(null,'".$name."','".$target_file."',".$price.",'".$type."','".$detail."')";
                    $db->query($adminAdd);
                    header("Location:index.php");
                } else {
                    ?>
<script>
alert("Sorry, there was an error uploading your file!");
</script>
<?php
                }
            }
        }
                /*---------------------------*/        
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
    /*-----------------------------------------*/

    $chatbox = "SELECT * from chatbox GROUP BY idUser having idUser>1 ";
    $resultchatbox = $db->query($chatbox)->fetch_all() ;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Souvenir Shop</title>
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css"
        integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.js">
    </script>
    <link rel="stylesheet" href="souvenir.css">
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
                        <a href="index.php">TRANG CHỦ</a>
                    </li>
                    <li>
                        <a href="#">SẢN PHẨM</a>
                    </li>
                    <li>
                        <a href="#">ĐƠN HÀNG</a>
                    </li>
                </ul>
            </nav>
            <div class="menu" style="float: right; margin-left: 10px;">
                <ul class="clearfix">

                    <li>
                        <img src="Souvernir/chat.png" width="50px">
                        <ul class="sub-menu"
                            style="width: 300px; border-style:solid; border-color:firebrick; background:black; border-radius:5px; border-width:2px">
                            <li>
                                <div class="card-header">
                                    <div class="input-group">
                                        <h3 style="color:white; margin-right:10px;">Chat</h3>
                                        <input type="text" placeholder="Search..." name="" class="form-control search">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text search_btn"><i
                                                    class="fas fa-search"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <form action="dee.php" method="get" style="text-align: right;">
                                    <div class="card-body contacts_body">
                                        <ui class="contacts">
                                            <?php for($i=0; $i<count($resultchatbox);$i++){?>
                            <li style="background:gray">
                                <button class="active" name="chatchat" value="<?php echo $resultchatbox[$i][3] ?>">
                                    <div class="d-flex bd-highlight">
                                        <div class="img_cont">
                                            <img src="Souvernir/Contact/user.png" class="rounded-circle user_img">
                                            <span class="online_icon"></span>
                                        </div>
                                        <?php 
                                        $userChat="SELECT username from User where id=".$resultchatbox[$i][3];
                                        $resultUChat=$db->query($userChat)->fetch_all();
                                        $nameUserChat=$resultUChat[0][0];
                                        ?>
                                        <div class="user_info">
                                            <span><?php echo $nameUserChat ?></span>
                                            <p>Khách hàng</p>
                                        </div>
                                    </div>
                                </button>
                            </li>
                            <?php }?>
                            </ui>
            </div>
            </form>
            </li>
            </ul>
            </li>
            &emsp;
            <li>
                <img src="Souvernir/taikhoan.png" style="width: 30px; height: 30px;">
                <ul class="sub-menu" style="width: 200px;">
                    <?php 
                                        error_reporting(0);
                                        if($_SESSION['name']==''){
                                        ?>
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
    <div class="top_body_content" ; style="margin-bottom:20px">
        <hr width="30%" height="10px" align="center; color:black;">
        <h2 style="color:red">PRODUCT MANAGEMENT</h2>
        <hr width="30%" height="10px" align="center; color:black;">
    </div>
    <!--=============================================Tao Form them sp========================================-->
    <div class="top_body_content" ; style="margin-bottom:20px; margin-top:10px;">
        <hr width="30%" height="10px" align="center; color:black;">
        <h2 style="color:firebrick; font-size: 30px;">Add Product</h2>
        <hr width="30%" height="10px" align="center; color:black;">
    </div>
    <div class="add_container">
        <form action="" method="post" enctype="multipart/form-data">
            <!------------------------------------------------------------------------------->
            <div class="form-row">
                <div class="col">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1" style="width: 200px;">Name
                                Product</span>
                        </div>
                        <input type="text" class="form-control" name="name">
                    </div>
                </div>
                <div class="col">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1" style="width: 200px;">Price</span>
                        </div>
                        <input type="text" class="form-control" name="price">
                    </div>
                </div>
            </div>
            <br />
            <!------------------------------------------------------------------------------->
            <div class="form-row">
                <div class="col">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1" style="width: 200px;">Type
                                Product</span>
                        </div>
                        <select name="select" class="form-control col-md-12">
                            <option value="QUÀ ĐỂ BÀN">QUÀ ĐỂ BÀN</option>
                            <option value="QUÀ TẶNG PHA LÊ">QUÀ TẶNG PHA LÊ</option>
                            <option value="GẤU BÔNG">GẤU BÔNG</option>
                            <option value="MÓC KHÓA">MÓC KHÓA</option>
                            <option value="BÚT">BÚT</option>
                            <option value="TIỆN ÍCH">TIỆN ÍCH</option>
                            <option value="ĐỒ MĨ NGHỆ">ĐỒ MĨ NGHỆ</option>
                            <option value="SÀNH SỨ">SẢN PHẨM SÀNH SỨ</option>
                        </select>
                    </div>
                </div>
                <div class="col">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1" style="width: 200px;">Image</span>
                        </div>
                        <input type="file" name="fileToUpload" id="fileToUpload">
                    </div>
                </div>
            </div>
            <br />
            <!------------------------------------------------------------------------------->
            <div class="form-row" style="margin-left:1px;">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1" style="width: 200px;">Detail</span>
                    </div>
                    <input type="text" class="form-control" name="detail">
                </div>
            </div>
            <br />
            <!------------------------------------------------------------------------------->
            <div class="form-group">
                <button class="btn btn-danger" name="addpr">Add Product</button>
            </div>
        </form>
    </div>
    <!--====================================Form sua san pham===============================================-->
    <?php
                    for($i=0;$i<count($result);$i++){ ?>
    <div id="modal<?php echo $result[$i][0]?>" class="modal fade" tabindex="-1" role="dialog"
        aria-labelledby="my-modal-title" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="my-modal-title"
                            style="color:firebrick; margin-left: 150px; font-size: 25px;">EDIT PRODUCT</h5>
                        <button class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1" style="width: 150px;">Name
                                    Product</span>
                            </div>
                            <input type="text" class="form-control" name="namePr" id=""
                                value="<?php echo $Products[$i]->name?>" placeholder="">
                        </div>
                        <!----------------------------------------------------------------------------------------->
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1" style="width: 150px;">Type
                                    Product</span>
                            </div>
                            <select class="form-control col-md-9" name="typePr" id=""
                                value="<?php echo $Products[$i]->getType()?>" placeholder="">
                                <option value="QUÀ ĐỂ BÀN">QUÀ ĐỂ BÀN</option>
                                <option value="QUÀ TẶNG PHA LÊ">QUÀ TẶNG PHA LÊ</option>
                                <option value="GẤU BÔNG">GẤU BÔNG</option>
                                <option value="MÓC KHÓA">MÓC KHÓA</option>
                                <option value="BÚT">BÚT</option>
                                <option value="TIỆN ÍCH">TIỆN ÍCH</option>
                                <option value="ĐỒ MĨ NGHỆ">ĐỒ MĨ NGHỆ</option>
                                <option value="SÀNH SỨ">SẢN PHẨM SÀNH SỨ</option>
                            </select>
                        </div>
                        <!---------------------------------------------------------------------------------------->
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1" style="width: 150px;">Price</span>
                            </div>
                            <input type="number" class="form-control" name="pricePr" id=""
                                value="<?php echo $Products[$i]->getPrice()?>" placeholder="">
                        </div>
                        <!---------------------------------------------------------------------------------------->
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1" style="width: 150px;">Image</span>
                            </div>
                            <input type="file" name="imagePr" id="editFile"
                                value="<?php echo $Products[$i]->getImagePath()?>">
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button class="btn btn-default"
                            style="border-style:solid; background:firebrick;color:floralwhite; font-size:12px"
                            type="submit" name="edit" value="<?php echo $result[$i][0];?>">OK</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php }?>
    <!--================================================Bang tat ca san pham==================================-->
    <div class="top_body_content" ; style="margin-bottom:20px">
        <hr width="30%" height="10px" align="center; color:black;">
        <h2 style="color:firebrick; font-size: 30px;">List Product</h2>
        <hr width="30%" height="10px" align="center; color:black;">
    </div>
    <div class="admin_container">
        <table align="center" width="600px" border="1" cellspacing="0" cellpadding="3"
            class="table table-hover table-bordered" id="mytable">
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
    </div>
    <!--==============================================ORDER MANAGEMENT=======================================-->
    <!--====================================Form xem chi tiet don hang===============================================-->
    <?php
                    for($i=0;$i<count($resultOrder);$i++){ ?>
    <div id="modalOrderDetail<?php echo $resultOrder[$i][0]?>" class="modal fade" tabindex="-1" role="dialog"
        aria-labelledby="my-modal-title" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="my-modal-title" style="margin-left: 180px; color: firebrick">
                            ORDER DETAIL</h5>
                        <button class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table align="center" width="600px" border="1" cellspacing="0" cellpadding="3"
                            class="table table-hover table-bordered" id="mytable">
                            <tr class="bg-success" style="text-align: center;">
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                            <?php 
                                                /*-------------------------chi tiet don hang-------------------------------------*/
                                                    $detailOrder="select p.name, p.price, od.quantity
                                                    from Product as p, orders as o, order_detail as od, customer as c
                                                    where o.id=od.id_ord
                                                    and od.id_pro=p.id
                                                    and o.id_cus=c.id
                                                    and o.id=".$resultOrder[$i][0];
                                                    $resultOd=$db->query($detailOrder)->fetch_all();
                                                /*-------------------------------------------------------------------------------*/
                                                    for ($k=0; $k<count($resultOd);$k++) {
                                                        echo '<tr>';
                                                        echo '<td style="text-align: center;">'.$resultOd[$k][0].'</td>';
                                                        echo '<td style="text-align: center;">'.$resultOd[$k][1].'</td>';
                                                        echo '<td style="text-align: center;">'.$resultOd[$k][2].'</td>';
                                                        echo '<td style="text-align: center;">'.($resultOd[$k][1]*$resultOd[$k][2]).'</td>';
                                                        echo '</tr>';
                                                    }
                                                ?>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php }?>
    <!--=========================================================================================-->
    <div class="top_body_content" ; style="margin-bottom:20px">
        <hr width="30%" height="10px" align="center; color:black;">
        <h2 style="color:red">ORDER MANAGEMENT</h2>
        <hr width="30%" height="10px" align="center; color:black;">
    </div>
    <div class="admin_container">
        <table align="center" width="600px" border="1" cellspacing="0" cellpadding="3"
            class="table table-hover table-bordered" id="mytable">
            <tr class="bg-success" style="text-align: center;">
                <th>ID</th>
                <th>Name Customer</th>
                <th>Address</th>
                <th>Date order</th>
                <th>Total Price</th>
                <th>View Detail</th>
            </tr>
            <?php 
                        for($i = 0; $i < count($resultOrder); $i++) { 
                            echo '<tr>';
                            echo '<td style="text-align: center;">'.$resultOrder[$i][0].'</td>';
                            echo '<td style="text-align: center;">'.$resultOrder[$i][1].'</td>';
                            echo '<td style="text-align: center;">'.$resultOrder[$i][2].'</td>';
                            echo '<td style="text-align: center;">'.$resultOrder[$i][3].'</td>';
                            echo '<td style="text-align: center;">'.$resultOrder[$i][4]." VND".'</td>';
                            echo '<td style="text-align: center;">'.'<button class="OrderDetail" data-toggle="modal" data-target="#modalOrderDetail'.$resultOrder[$i][0].'" name="id" value="'.$resultOrder[$i][0]. '"style="border: none"><i class="fa fa-newspaper"></i></button>'.'</td>';
                            echo '</tr>';
                        }?>
        </table>
    </div>

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
                    <a href="contact.php">LIÊN HỆ</a>
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
    <div class="menuSx">
        <ul class="nav navbar-nav">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                    aria-expanded="true"> <span class="nav-label">Sắp Xếp</span> <span class="caret"></span></a>
                <form action="" method="post">
                    <ul class="dropdown-menu">
                        <li><button name="sort">Giá tăng dần</button></li>
                        <li><button name="rsort">Giá giảm dần</button></li>
                    </ul>
                </form>
            </li>
        </ul>
        <hr />
    </div>
    <!---------------------------------Hien thi san pham---------------------------------->
    <div class="grid-container">
        <?php for($i = 0; $i < count($Products); $i++){  ?>
        <figure class="grid-item">
            <img class="img-fluid d-block mx-auto" src="<?php echo $Products[$i]->getImagePath() ?>"
                style="padding: 5px 5px 5px 5px">
            <figcaption>
                <h3 style="font-size: 15px; text-align: center; color: brown; margin-top: 30px;">
                    <?php 
                                        echo $Products[$i]->name;
                                        ?>
                </h3>
                <p style="font-size: 13px;margin-top: 10px; text-align: center; color:midnightblue; font-weight: bold;">
                    Price:
                    <?php 
                                        echo $Products[$i]->getPrice();
                                        ?>
                </p>
                <?php 
                    $proId = "SELECT id from Product where name='".$Products[$i]->name."'";
                    $resultproId = $db->query($proId)->fetch_all();
                    $idProSx=$resultproId[0][0]-1;
                ?>
                <form action="detail.php" method="get">
                    <button class="detail" name="detail" value="<?php echo $idProSx?>">Xem Chi Tiết</button>
                </form>
            </figcaption>
            <form action="" method="post">
                <button name="insert_cart" value="<?php echo ($idProSx+1);?>"> <i class="fa fa-shopping-cart"
                        style="font-size:20px;color:red"></i></button>
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
                        <img class="img-fluid d-block mx-auto" src="<?php echo $Products[0]->getImagePath() ?>"
                            style="padding: 5px 5px 5px 5px">
                        <figcaption>
                            <h3 style="font-size: 13px; text-align: center; color: brown; margin-top: 30px;">
                                <?php 
                                                echo $Products[0]->name;
                                                ?>
                            </h3>
                            <p
                                style="font-size: 13px;margin-top: 10px; text-align: center; color:midnightblue; font-weight: bold;">
                                Price:
                                <?php 
                                                echo $Products[0]->getPrice();
                                                ?>
                            </p>
                            <form action="detail.php" method="get">
                                <button class="detail" name="detail" value=0>Xem Chi Tiết</button>
                            </form>
                        </figcaption>
                        <form action="" method="post">
                            <button name="insert_cart" value="<?php echo $Products[0]->id;?>"> <i
                                    class="fa fa-shopping-cart" style="font-size:20px;color:red"></i></button>
                        </form>
                    </figure>
                    <figure class="grid-item">
                        <img class="img-fluid d-block mx-auto" src="<?php echo $Products[1]->getImagePath() ?>"
                            style="padding: 5px 5px 5px 5px">
                        <figcaption>
                            <h3 style="font-size: 13px; text-align: center; color: brown;margin-top: 30px;">
                                <?php 
                                            echo $Products[1]->name;
                                            ?>
                            </h3>
                            <p
                                style="font-size: 13px;margin-top: 10px; text-align: center; color:midnightblue; font-weight: bold;">
                                Price:
                                <?php 
                                            echo $Products[1]->getPrice();
                                            ?>
                            </p>
                            <form action="detail.php" method="get">
                                <button class="detail" name="detail" value=1>Xem Chi Tiết</button>
                            </form>
                        </figcaption>
                        <form action="" method="post">
                            <button name="insert_cart" value="<?php echo $Products[1]->id;?>"> <i
                                    class="fa fa-shopping-cart" style="font-size:20px;color:red"></i></button>
                        </form>
                    </figure>
                    <figure class="grid-item">
                        <img class="img-fluid d-block mx-auto" src="<?php echo $Products[2]->getImagePath() ?>"
                            style="padding: 5px 5px 5px 5px">
                        <figcaption>
                            <h3 style="font-size: 13px; text-align: center; color: brown;margin-top: 30px;">
                                <?php 
                                            echo $Products[2]->name;
                                            ?>
                            </h3>
                            <p
                                style="font-size: 13px;margin-top: 10px; text-align: center; color:midnightblue; font-weight: bold;">
                                Price:
                                <?php 
                                            echo $Products[2]->getPrice();
                                            ?>
                            </p>
                            <form action="detail.php" method="get">
                                <button class="detail" name="detail" value=2>Xem Chi Tiết</button>
                            </form>
                        </figcaption>
                        <form action="" method="post">
                            <button name="insert_cart" value="<?php echo $Products[2]->id;?>"> <i
                                    class="fa fa-shopping-cart" style="font-size:20px;color:red"></i></button>
                        </form>
                    </figure>
                    <figure class="grid-item">
                        <img class="img-fluid d-block mx-auto" src="<?php echo $Products[3]->getImagePath() ?>"
                            style="padding: 5px 5px 5px 5px">
                        <figcaption>
                            <h3 style="font-size: 13px; text-align: center; color: brown;margin-top: 30px;">
                                <?php 
                                                echo $Products[3]->name;
                                                ?>
                            </h3>
                            <p
                                style="font-size: 13px;margin-top: 10px; text-align: center; color:midnightblue; font-weight: bold;">
                                Price:
                                <?php 
                                                echo $Products[3]->getPrice();
                                                ?>
                            </p>
                            <form action="detail.php" method="get">
                                <button class="detail" name="detail" value=3>Xem Chi Tiết</button>
                            </form>
                        </figcaption>
                        <form action="" method="post">
                            <button name="insert_cart" value="<?php echo $Products[3]->id;?>"> <i
                                    class="fa fa-shopping-cart" style="font-size:20px;color:red"></i></button>
                        </form>
                    </figure>
                </div>
            </div>
            <!------------------------------------------------------------------------------------------------------------>
            <div class="carousel-item">
                <div class="numbertext">
                    <figure class="grid-item">
                        <img class="img-fluid d-block mx-auto" src="<?php echo $Products[6]->getImagePath() ?>"
                            style="padding: 5px 5px 5px 5px">
                        <figcaption>
                            <h3 style="font-size: 13px; text-align: center; color: brown;margin-top: 30px;">
                                <?php 
                                                echo $Products[6]->name;
                                                ?>
                            </h3>
                            <p
                                style="font-size: 13px;margin-top: 10px; text-align: center; color:midnightblue; font-weight: bold;">
                                Price:
                                <?php 
                                                echo $Products[6]->getPrice();
                                                ?>
                            </p>
                            <form action="detail.php" method="get">
                                <button class="detail" name="detail" value=6>Xem Chi Tiết</button>
                            </form>
                        </figcaption>
                        <form action="" method="post">
                            <button name="insert_cart" value="<?php echo $Products[6]->id;?>"> <i
                                    class="fa fa-shopping-cart" style="font-size:20px;color:red"></i></button>
                        </form>
                    </figure>
                    <figure class="grid-item">
                        <img class="img-fluid d-block mx-auto" src="<?php echo $Products[8]->getImagePath() ?>"
                            style="padding: 5px 5px 5px 5px">
                        <figcaption>
                            <h3 style="font-size: 13px; text-align: center; color: brown ;margin-top: 30px;">
                                <?php 
                                                echo $Products[8]->name;
                                                ?>
                            </h3>
                            <p
                                style="font-size: 13px;margin-top: 10px; text-align: center; color:midnightblue; font-weight: bold;">
                                Price:
                                <?php 
                                                echo $Products[8]->getPrice();
                                                ?>
                            </p>
                            <form action="detail.php" method="get">
                                <button class="detail" name="detail" value=8>Xem Chi Tiết</button>
                            </form>
                        </figcaption>
                        <form action="" method="post">
                            <button name="insert_cart" value="<?php echo $Products[8]->id;?>"> <i
                                    class="fa fa-shopping-cart" style="font-size:20px;color:red"></i></button>
                        </form>
                    </figure>
                    <figure class="grid-item">
                        <img class="img-fluid d-block mx-auto" src="<?php echo $Products[10]->getImagePath() ?>"
                            style="padding: 5px 5px 5px 5px">
                        <figcaption>
                            <h3 style="font-size: 13px; text-align: center; color: brown;margin-top: 30px;">
                                <?php 
                                                echo $Products[10]->name;
                                                ?>
                            </h3>
                            <p
                                style="font-size: 13px;margin-top: 10px; text-align: center; color:midnightblue; font-weight: bold;">
                                Price:
                                <?php 
                                                echo $Products[10]->getPrice();
                                                ?>
                            </p>
                            <form action="detail.php" method="get">
                                <button class="detail" name="detail" value=10>Xem Chi Tiết</button>
                            </form>
                        </figcaption>
                        <form action="" method="post">
                            <button name="insert_cart" value="<?php echo $Products[10]->id;?>"> <i
                                    class="fa fa-shopping-cart" style="font-size:20px;color:red"></i></button>
                        </form>
                    </figure>
                    <figure class="grid-item">
                        <img class="img-fluid d-block mx-auto" src="<?php echo $Products[5]->getImagePath() ?>"
                            style="padding: 5px 5px 5px 5px">
                        <figcaption>
                            <h3 style="font-size: 13px; text-align: center; color: brown;margin-top: 30px;">
                                <?php 
                                                echo $Products[5]->name;
                                                ?>
                            </h3>
                            <p
                                style="font-size: 13px;margin-top: 10px; text-align: center; color:midnightblue; font-weight: bold;">
                                Price:
                                <?php 
                                                echo $Products[5]->getPrice();
                                                ?>
                            </p>
                            <form action="detail.php" method="get">
                                <button class="detail" name="detail" value=5>Xem Chi Tiết</button>
                            </form>
                        </figcaption>
                        <form action="" method="post">
                            <button name="insert_cart" value="<?php echo $Products[5]->id;?>"> <i
                                    class="fa fa-shopping-cart" style="font-size:20px;color:red"></i></button>
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
            <a href="index.php"> SouvenirShop.com</a>
        </div>
    </footer>
    <div class="header-container">
    </div>
    <!----------------------------------------------------------------------------------------------------------------->
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
</body>

</html>