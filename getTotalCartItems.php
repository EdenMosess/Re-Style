<?php
session_start();
include 'db.php';
if(isset($_POST['getTotalCartItems'])){
    $query = "select SUM(quantity) as totalQuantity from cart where user_id=?";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $_SESSION['user'], PDO::PARAM_STR);
    $stmt->execute();
    $totalitems = $stmt->fetch();
    $items = $totalitems['totalQuantity']=='' ? 0 : $totalitems['totalQuantity'];
    echo $items;
    die();
}