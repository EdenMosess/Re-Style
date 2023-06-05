<?php
ob_start();
session_start();
require_once '../db.php';
if(isset($_SESSION['admin']))
{
    header("location: index.php");
    die(); exit();
}

if(isset($_POST['submit'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $stmt = $sql->prepare("select * from admins where username = ?");
    $stmt->bindParam(1, $username, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch();
    $total = $stmt->rowCount();
    if($total>0){
        $db_username = $row['username'];
        $db_password = $row['password'];
        if($username == $db_username && password_verify($password, $db_password)){
            $_SESSION['admin'] = $row['id'];
            header("location: index.php");
        }else {
            $msg = "<div class='alert alert-danger'>Email or Password is incorrect.</div>";
        }
    }else {
        $msg = "<div class='alert alert-danger'>Email or Password is incorrect.</div>";
    }
}

if(isset($_SESSION['message'])){
    $msg = $_SESSION['message'];
    unset($_SESSION['message']);
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
    <?php include 'head.php'; ?>
</head>

<body>
    <?php include 'nav.php'; ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h3 class="mt-5 mb-3 text-white fancy">Admin Login</h3>
                <div class="card mb-3 shadow">
                    <div class="card-body">
                        <?php if(isset($msg)){ echo $msg; } ?>
                        <form method="post" class="">
                            <div class="mb-3">
                                <label for="">Username</label>
                                <input required type="text" class="form-control" name="username">
                            </div>

                            <div class="mb-3">
                                <label for="">Password</label>
                                <input id="password" required type="password" class="form-control mb-2" name="password">
                            </div>
                            <div class="">
                                <button name="submit" class="btn btn-primary btn-block">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
				
            </div>
        </div>
    </div>
</body>
</html>