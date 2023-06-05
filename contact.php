<?php
ob_start();
session_start();
require_once 'db.php';
if(isset($_POST['submit'])){
    $query = "INSERT into contact_messages set name=?, email=?, message=?";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $_POST['name'], PDO::PARAM_STR);
    $stmt->bindParam(2, $_POST['email'], PDO::PARAM_STR);
    $stmt->bindParam(3, $_POST['message'], PDO::PARAM_STR);
    $stmt->execute();
    $msg = "<div class='alert alert-success'>Your message has been sent successfully.</div>";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Contact</title>
    <?php include 'head.php'; ?>
    <style>
        body{
            background: #5b5b5b;
        }
        
        label{
            color: white;
        }
        .form-control{
            border-radius: 0;
        }
        
        .hero-image{
            width: 100%;
            height: 450px;
            object-fit: cover;
        }
        
    </style>
</head>

<body>
    <?php include 'nav.php'; ?>
    <img class="hero-image" src="images/contact.jpg" alt="">
    <div class="container">
        <div class="row justify-content-center mb-5 mt-3">
            <div class="col-md-6">
               
                <h1 class="mb-3 text-white text-center fancy"><?php echo $ln['contact']; ?></h1>
                <div class="">
                    <div class="">
                        <?php if(isset($msg)){ echo $msg; } ?>
                        <form method="post" class="mb-5">
                           <div class="mb-3">
                                <label for=""><?php echo $ln['full_name']; ?>*</label>
                                <input required type="text" class="form-control" name="name" placeholder="">
                            </div>
                            <div class="mb-3">
                                <label for=""><?php echo $ln['email']; ?>*</label>
                                <input required type="email" class="form-control" name="email" placeholder="">
                            </div>
                            <div class="mb-3">
                                <label for=""><?php echo $ln['message']; ?>*</label>
                                <textarea required name="message" class="form-control" id="" cols="30" rows="5"></textarea>
                            </div>
                            <button name="submit" class="btn btn-secondary btn-block m-0"><?php echo $ln['submit']; ?></button>

                        </form>
                    </div>
                </div>
			</div>
        </div>
    </div>    
    <?php include 'footer.php'; ?>    
</body>
</html>