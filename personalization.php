<?php
require_once 'auth.php';
if(isset($_POST['submit'])){
    $email_alerts = isset($_POST['email_alerts'])?1:0;
    $query = "update users set email_alerts=?, interest=? where id=?";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $email_alerts, PDO::PARAM_STR);
    $stmt->bindParam(2, $_POST['interest'], PDO::PARAM_STR);
    $stmt->bindParam(3, $userid, PDO::PARAM_STR);
    $stmt->execute();
    
    $query = "select * from users where id = ?";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $userid, PDO::PARAM_INT);
    $stmt->execute();
    $userdata = $stmt->fetch();
    $msg = "<div class='alert alert-success'>Updated successfully.</div>";
}

if(isset($_POST['delete'])){
    $query = "delete from users where id = ?";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $userid, PDO::PARAM_INT);
    $stmt->execute();
    
    $query = "delete from products where uploader = ?";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $userid, PDO::PARAM_INT);
    $stmt->execute();
    
    session_destroy();
    header("location: login.php");
    die();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Personalization</title>
    <?php include 'head.php'; ?>
    <style>
        body{
            background: #e9fbeb;
        }
    </style>
</head>

<body>
    <?php include 'nav.php'; ?>
    <div class="container">
        <div class="row justify-content-center mt-3 mb-5">
           
            <div class="col-md-9">
                <div class="btn-group w-100 mb-5" role="group" aria-label="Basic example">
                    <a href="update-personal-details.php" class="btn btn-outline-primary">Personal Details</a>
                    <a href="addresses.php" class="btn btn-outline-primary">Addresses</a>
                    <a href="cards.php" class="btn btn-outline-primary">Cards</a>
                    <a href="feedbacks.php" class="btn btn-outline-primary">Feedbacks</a>
                    <a href="personalization.php" class="btn btn-outline-primary">Personalization</a>
                </div>
                <h3 class="mb-5 text-muted"><b>Personalization</b></h3>
                <div class="card mb-3 shadow">
                    <div class="card-body">
                        <?php if(isset($msg)){ echo $msg; } ?>
                        <form method="post" class="">
                           <div class="row mb-3">
                               <div class="col-md-12">
                                   <div class="form-group">
                                       <label for=""><b>Your Interest</b></label>
                                       <div class="form-check">
                                           <input <?php if($userdata['interest']=='selling'){ echo 'checked'; } ?> class="form-check-input" type="radio" name="interest" id="selling" value="selling">
                                           <label class="form-check-label" for="selling">
                                               Selling
                                           </label>
                                       </div>
                                       <div class="form-check">
                                           <input <?php if($userdata['interest']=='purchasing'){ echo 'checked'; } ?> class="form-check-input" type="radio" name="interest" id="purchasing" value="purchasing">
                                           <label class="form-check-label" for="purchasing">
                                               Purchasing
                                           </label>
                                       </div>
                                   </div>
                                   <div class="form-check mb-3">
                                       <input <?php if($userdata['email_alerts']==1){ echo 'checked'; } ?> class="form-check-input" type="checkbox" name="email_alerts" id="email" value="1">
                                       <label class="form-check-label" for="email">
                                           Receive Email Alerts
                                       </label>
                                   </div>
                               </div>
                           </div>
                           <div class="float-right">
                                <button name="submit" class="btn btn-primary px-4">Save</button>
                            </div>
                        </form>
                        <div class="clearfix"></div>
                        <hr>
                        <form action="" method="post" onsubmit="return confirm('Are you sure want to delete your account?')">
                            <button name="delete" class="btn btn-danger px-4">Delete Account</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
</html>