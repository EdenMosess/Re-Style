<?php
session_start();
include 'db.php';
if(isset($_POST['addToCart'])){
    $query = "select * from cart where user_id=? AND product_id=?";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $_SESSION['user'], PDO::PARAM_STR);
    $stmt->bindParam(2, $_POST['product_id'], PDO::PARAM_STR);
    $stmt->execute();
    if($stmt->rowCount()>0){
        $query = "update cart set quantity=quantity+1 where user_id=? AND product_id=?";
        $stmt = $sql->prepare($query);
        $stmt->bindParam(1, $_SESSION['user'], PDO::PARAM_STR);
        $stmt->bindParam(2, $_POST['product_id'], PDO::PARAM_STR);
        $stmt->execute();
    }else{
        $query = "INSERT into cart set user_id=?, product_id=?, quantity=1";
        $stmt = $sql->prepare($query);
        $stmt->bindParam(1, $_SESSION['user'], PDO::PARAM_STR);
        $stmt->bindParam(2, $_POST['product_id'], PDO::PARAM_STR);
        $stmt->execute();
    }
    die();
}