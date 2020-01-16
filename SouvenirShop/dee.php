<?php 
require "database.php";
session_start();
	
	$idInbox=$_GET["chatchat"];
	$nameI="SELECT username from user where id=".$idInbox;
	$resultNameI=$db->query($nameI)->fetch_all();
	$nameInbox=$resultNameI[0][0];

    if(isset($_POST['send'])){
		$text=$_POST['text'];
		$idInbox=$_POST['send'];
        $today = date('Y-m-d H:i:s');
        $insert = "INSERT into chatbox values(null,'".$text."','".$today."',1,$idInbox)";
		$db->query($insert);
		header("Location:dee.php?chatchat=".$idInbox);
	}
	$sql = "SELECT * from chatbox where idUser in (1,".$idInbox.") and idChat in (1,".$idInbox.") ORDER BY timeInbox ASC";
	$result = $db->query($sql)->fetch_all();
	
?>

<!DOCTYPE html>
<html>

<head>
    <title>Chat</title>
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
    <link rel="stylesheet" href="chat.css">
</head>

<body>
    <a href="index.php"><i class="fa fa-arrow-left" aria-hidden="true"></i>Trở lại trang chủ</a>
    <div class="container-fluid h-100">
        <div class="row justify-content-center h-100">

            <!--------------------------------------------------------------------------------------->
            <div class="col-md-8 col-xl-20 chat">
                <div class="card">
                    <div class="card-header msg_head">
                        <div class="d-flex bd-highlight">
                            <div class="img_cont">
                                <img src="Souvernir/Contact/user.png" class="rounded-circle user_img">
                                <span class="online_icon"></span>
                            </div>
                            <div class="user_info">
                                <span><?php echo $nameInbox;?></span>
                                <p>Khách hàng</p>
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
                                    if($result[$i][3]==$idInbox){?>
                            <div class="img_cont_msg">
                                <img src="Souvernir/Contact/user.png" class="rounded-circle user_img_msg"
                                    style="border-color: black">
                            </div>
                            <?php }?>
                            <?php 
                                    if($result[$i][3]==$idInbox){?>
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
                                    if($result[$i][3]==1){
                                        echo  $result[$i][1];
                                    ?>
                                <span class="msg_time_send"><?php 
                                        echo  $result[$i][2];
                                    }
                                    ?></span>
                            </div>
                            <?php 
                                if($result[$i][3]==1){?>
                            <div class="img_cont_msg">
                                <img src="Souvernir/Contact/avatarAdmin.png" class="rounded-circle user_img_msg"
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
                                            style="border: none; background: none;" value=<?php echo $idInbox; ?>><i
                                                class="fas fa-location-arrow"></i></button></span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--------------------------------------------------------------------------------------->
        </div>
</body>

</html>