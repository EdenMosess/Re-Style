<?php
ob_start();
$status = session_status();

if($status == PHP_SESSION_NONE){
    session_start();
}

else if($status == PHP_SESSION_DISABLED){
    //Sessions are not available
}else if($status == PHP_SESSION_ACTIVE){
    //Destroy current and start new one
    session_destroy();
    session_start();
}

if(!isset($_SESSION['user'])){
    $_SESSION['message'] = "<div class='alert alert-danger'>Please login first to enter the dashboard.</div>";
    header('Location: login.php');
    exit();
}else{
    include 'db.php';
    $userid = $_SESSION['user'];
    $query = "select * from users where id = ?";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $userid, PDO::PARAM_INT);
    $stmt->execute();
    $userdata = $stmt->fetch();
    if(empty($userdata['interest']) && !isset($select_interest)){
        header("location: select-interest.php");
        die();
    }
}
?>