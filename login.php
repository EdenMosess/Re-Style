<?php
session_start();
include 'db.php';
if(isset($_SESSION['user']))
{
    header("location: index.php");
    die(); exit();
}

if(isset($_POST['submit'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $stmt = $sql->prepare("select * from users where email=?");
    $stmt->bindParam(1, $email, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch();
    $total = $stmt->rowCount();
    if($total>0){
        $db_email = $row['email'];
        $db_password = $row['password'];
        if($email == $db_email && password_verify($password, $db_password)){
            $_SESSION['user'] = $row['id'];
            
            if($row['interest']=='purchasing'){
                header("location: products.php");
            }elseif($row['interest']=='selling'){
                header("location: ad-management.php");
            }else{
                header("location: select-interest.php");
            }
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
    <style>
        body{
            background: #e9fbeb;
        }
    </style>
</head>

<body>
    
    <div class="container">
        <div class="row justify-content-center mb-5 mt-3">
            <div class="col-md-6">
                <center>
                    <a href="index.php"><img width="150" src="images/logo.png" alt=""></a>
                </center>
                <h3 class="mb-3">Login</h3>
                <div class="card mb-3 shadow">
                    <div class="card-body">
                       
                        <?php if(isset($msg)){ echo $msg; } ?>
                        <form method="post" class="">
                            <div class="mb-3">
                                <label for="">Email*</label>
                                <input required type="email" class="form-control" name="email">
                            </div>

                            <div class="mb-3">
                                <label for="">Password*</label>
                                <input required type="password" class="form-control mb-2" name="password">
                            </div>
                            <div class="">
                                <button name="submit" class="btn btn-primary btn-block">Enter</button>
                            </div>
                        </form>
                    </div>
                </div>
				<center>
				    First time with us? <a class="ml-4" href="register.php"> Register here</a>
				</center>
            </div>
        </div>
    </div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
</html>