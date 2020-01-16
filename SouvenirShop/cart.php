<?php
    require "database.php";
    require "Classes/User.php";
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

    
    if ($_SESSION['name']!="") {
        $sql1 = "SELECT * from cart where cart.idUser=(select id from User where username ='".$_SESSION['name']."')";
        $result1 = $db->query($sql1)->fetch_all()
        ;
    }

    if ($_SESSION['name']!="") {
        $fullnameUser = "SELECT fullName from User where username ='".$_SESSION['name']."'";
        $full=$db->query($fullnameUser)->fetch_all();
        $fullName= $full[0][0];
    }

    if ($_SESSION['name']!="") {
        $idU = "SELECT id from User where username ='".$_SESSION['name']."'";
        $ktraUser=$db->query($idU)->fetch_all();
        $idUser= $ktraUser[0][0];
    }

    
/*====================================================DELETE PRODUCT FROM CART==========================================*/    

    if(isset($_POST["id_cart"])){
        $id = $_POST["id_cart"];
        $sql1 = "DELETE from cart where id= ".$id;
        $db->query($sql1);
        header("Location:cart.php");
        }
  
/*===================================================UPDATE CART======================================================*/
    /*----------------------------------------down------------------------------------------*/
    if(isset($_POST["down"])){
        $id_down = $_POST["down"];
        $id=$result1[$id_down][0];
        $result1[$id_down][5]-=1;
        $sl = $result1[$id_down][5];
        $price=$result1[$id_down][4];
        $total =$price*$sl;
        $result1[$id_down][6]=$total;
        $sql1 = "UPDATE cart SET quantity= $sl, total=$total where id = ".$id;
        $db->query($sql1);
        if($result1[$id_down][5]==0){
            $sql1 = "DELETE from cart where id = ".$id;
            $db->query($sql1);
        }  
    }
    /*----------------------------------------up------------------------------------------*/
    if(isset($_POST["up"])){
        $id_up = $_POST["up"];
        $id = $result1[$id_up][0];
        $result1[$id_up][5]+=1;
        $sl = $result1[$id_up][5];
        $price=$result1[$id_up][4];
        $total =$price*$sl;
        $result1[$id_up][6]=$total;
        $sql1 = "UPDATE cart SET quantity= $sl, total=$total where id = ".$id;
        $db->query($sql1);
    }

    /*=============================================function tinh tong tien==============================================*/            

    function sum($result1){
        $sum=0;
        for($i = 0; $i < count($result1); $i++) {
            $sum+=$result1[$i][6];
        }
        return $sum;
    }
    /*=============================================function tinh so luong==============================================*/            
    function num($result1){
        $num=0;
        for($i = 0; $i < count($result1); $i++) {
            $num+=$result1[$i][5];
        }
        return $num;
    }

    /*=============================================================Dat hang================================================*/
    if(isset($_POST["yes"])){
        $name=$_POST["nameCus"];
        $address=$_POST["addressCus"];
        $phone=$_POST["phoneCus"];
        $date= date("Y-m-d");
        $total =(sum($result1)+(sum($result1)*0.01));

        $cus = "INSERT INTO CUSTOMER VALUES (null,'".$name."','".$address."',".$phone.",".$idUser.")";
        $db->query($cus);

        $idCus = "SELECT id from CUSTOMER where ID_ACCOUNT =".$idUser;
        $idC=$db->query($idCus)->fetch_all();
        $idCustomer= $idC[0][0];

        $order="INSERT INTO ORDERS VALUES (null,'".$date."',".$idCustomer.",".$total.")";
        $db->query($order);

        $tvorder= "SELECT ORDERS.id from ORDERS, CUSTOMER where ORDERS.id_cus=CUSTOMER.id and CUSTOMER.id_account=".$idUser;
        $idOrder= $db->query($tvorder)->fetch_all();
        $resultOrder=$idOrder[0][0];
        
        $sql1 = "SELECT * from cart where cart.idUser=(select id from User where username ='".$_SESSION['name']."')";
        $result1 = $db->query($sql1)->fetch_all();
 
        for($i=0; $i<count($result1); $i++){
            $orderDetail="INSERT INTO ORDER_DETAIL VALUES (".$resultOrder.",".$result1[$i][1].",".$result1[$i][5].")";
            $db->query($orderDetail);
        }     
        $deleteCart = "DELETE from cart where cart.idUser=(select id from User where username ='".$_SESSION['name']."')";
        $db->query($deleteCart);
        header("Location:cart.php");
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>My Shopping Cart</title>
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
                    &emsp;
                    <li>
                        <img src="Souvernir/giohang.png">
                        <ul class="sub-menu" style="width: 200px;">
                            <li>
                                <form action="cart.php" method="" style="text-align: right;">
                                    <button style="border: none; "><i class="fas fa-shopping-cart"
                                            style="color:firebrick;"></i>GIỎ HÀNG (<?php echo num($result1) ?>)</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <form id="searchbox" method="get" action="/search" autocomplete="off">
                            <input name="q" type="text" size="15" placeholder="Enter keywords..." />
                            <input id="button-submit" type="submit" value=" " />
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <!-- ================================HIEN THI DANH SACH SAN PHAM TRONG GIO HANG=========================== -->
        <div class="cart_container">
            <div class="top_body_content">
                <hr width="30%" height="10px" align="center; color:black;">
                <h2>GIỎ HÀNG</h2>
                <hr width="30%" height="10px" align="center; color:black;">
            </div>
            <form action="" method="post">
                <div class="line">
                    <table id="tbl" class="table table-bordered">
                        <tr>
                            <th>Img</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Del</th>
                        </tr>
                        <?php for($i = 0; $i < count($result1); $i++) { ?>
                        <tr>
                            <td><img src="<?php echo $result1[$i][2] ?>" style="width:20px ; height: 20px" alt="Image">
                            </td>
                            <td><?php echo $result1[$i][3]; ?></td>
                            <td><?php echo $result1[$i][4]; ?></td>
                            <td><button name="down" value="<?php echo $i;?>" style="border: none"><i class="fa fa-minus"
                                        style="font-size: 12px;"
                                        aria-hidden="true"></i></button><?php echo $result1[$i][5]; ?><button name="up"
                                    value="<?php echo $i;?>" style="border: none;"><i class="fa fa-plus"
                                        style="font-size: 12px;" aria-hidden="true"></i></button></th>
                            <td><?php echo $result1[$i][6]; ?></td>
                            <td><button name="id_cart" value="<?php echo $result1[$i][0];?>" style="border: none"><i
                                        class="fas fa-trash-alt" style="color:red"></i></button></td>
                        </tr>
                        <?php } ?>
                    </table>
                </div>
            </form>
            <!--=================================================CONG GIO HANG=========================================-->
            <p style="margin-left: 700px;margin-top: 20px; color:black; font-size: 17px;">Tạm tính:
                <?php echo sum($result1)." VND";?></p>
            <p style="margin-left: 700px;margin-top: 20px; color:black; font-size: 17px;">Phí giao hàng:
                <?php echo (sum($result1)*0.01)." VND";?></p>
            <h3 style="margin-left: 700px;margin-top: 20px; color:black; font-size: 17px;">Tổng:
                <?php echo (sum($result1)+(sum($result1)*0.01))." VND";?></h3>
            <hr />
            <div class="flex_tt" id="but">
                <form action="index.php" method="">
                    <button class="btn btn-info" style="font-size: 12px;"><i class="fa fa-arrow-left"
                            style="color: white;"></i> Tiếp tục mua hàng</button>
                </form>
                <button class="btn btn-danger" onclick="dathang()" name="order" style="font-size: 12px;"><i
                        class="fa fa-shopping-cart" style="color:white"></i> Đặt hàng</button>
            </div>
            <!--==============================================FORM DAT HANG========================================-->
            <div class="hd" id="dh123" style="display: none">
                <div class="top_body_content">
                    <hr width="30%" height="10px" align="center; color:black;">
                    <p style="color:firebrick; font-weight: bold;">Thông Tin Người Đặt Hàng</p>
                    <hr width="30%" height="10px" align="center; color:black;">
                </div>
                <hr />
                <!--===================================================================================================-->
                <form action="" method="post">
                    <!-----------------------------------------------full name------------------------------------------->
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1" style="width: 200px;"><i
                                    class="fa fa-user"></i> Họ Tên *</span>
                        </div>
                        <input type="text" class="form-control" name="nameCus" value="<?php echo $fullName; ?>"
                            aria-label="Username" aria-describedby="basic-addon1">
                    </div>
                    <!-------------------------------------------------address-------------------------------------------->
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1" style="width: 200px;"><i
                                    class="fa fa-home"></i> Địa Chỉ *</span>
                        </div>
                        <input type="text" class="form-control" name="addressCus" placeholder="Địa Chỉ"
                            aria-label="Username" aria-describedby="basic-addon1">
                    </div>
                    <!-----------------------------------------------phone number----------------------------------------->
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1" style="width: 200px;"><i
                                    class="fa fa-phone"></i> Số Điện Thoại *</span>
                        </div>
                        <input type="text" class="form-control" name="phoneCus" placeholder="Số Điện Thoại"
                            aria-label="Username" aria-describedby="basic-addon1">
                    </div>
                    <!-----------------------------------------------ht thanh toan------------------------------------------->
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1" style="width: 200px;"><i
                                    class="fa fa-calendar"></i> Hình thức thanh toán </span>
                        </div>
                        <select class="form-control">
                            <option>Thanh toán khi nhận hàng</option>
                        </select>
                    </div>
                    <!-----------------------------------------------dong y dat hang------------------------------------>
                    <div class="yes">
                        <button class="btn btn-danger" style="font-size: 14px" name="yes">Đồng ý</button>
                    </div>
                </form>
            </div>
            <!----------------------------------------------------------------------------------------------------------->
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
                <a href="index.php"> SouvenirShop.com</a>
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

    <script>
    function dathang() {
        document.getElementById('but').style.display = 'none';
        document.getElementById('dh123').style.display = 'block';
    }
    </script>
</body>

</html>