<?php 
include 'auth.php';
if($userdata['interest']=='purchasing'){
    header("location:products.php");
    die();
}else{
    header("location:ad-management.php");
    die();
}


?>