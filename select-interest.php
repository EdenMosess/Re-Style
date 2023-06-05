<?php
$select_interest = true;
include 'auth.php';
if(isset($_POST['interest'])){
    $stmt = $sql->prepare("update users set interest=? where id=?");
    $stmt->bindParam(1, $_POST['interest'], PDO::PARAM_STR);
    $stmt->bindParam(2, $userid, PDO::PARAM_STR);
    $stmt->execute();
    if($_POST['interest']=='purchasing'){
        header("location: products.php");
    }else{
        header("location: ad-management.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Select Interest</title>
    <?php include 'head.php'; ?>
    <style>
        body {
            background: #e9fbeb;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row justify-content-center mb-5 mt-3">
            <div class="col-md-8">
                <div class="float-right">
                    <a href="index.php"><img width="150" src="images/logo.png" alt=""></a>
                </div>
            </div>
            <div class="col-md-8">

                <h3 class="mb-4 mt-4 text-center">How would you like to proceed?</h3>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-3 shadow">
                            <div class="card-body">
                                <form method="post" class="">
                                    <h4 class="text-muted mb-5"><b>Interested in <br> purchasing</b></h4>
                                    <input type="hidden" name="interest" value="purchasing">
                                    <button class="btn btn-primary">Select</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-3 shadow">
                            <div class="card-body">
                                <form method="post" class="">
                                    <h4 class="text-muted mb-5"><b>Interested in <br> selling</b></h4>
                                    <input type="hidden" name="interest" value="selling">
                                    <button class="btn btn-primary">Select</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg mt-5" style="padding-top:100px;padding-bottom:100px">
    <div class="container">
        
        

        <div class="collapse navbar-collapse" id="navbarColor02">
            
            <ul class="navbar-nav ml-auto mr-auto">

                
                
                <li class="nav-item"><a class="nav-link" href="about.php">About</a> </li>
                <li class="nav-item mr-3"><a class="nav-link" href="contact.php">Contact</a></li>
                <li class="nav-item mr-3"><a class="nav-link" href="#!">Regulations</a></li>
                <li class="nav-item mr-3"><a class="nav-link" href="#!">Questions and Answers</a></li>
                <li class="nav-item mr-3"><a class="nav-link" href="update-personal-details.php">User Profile Management</a></li>

            </ul>
        </div>
    </div>
</nav>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
</html>