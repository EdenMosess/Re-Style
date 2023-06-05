<?php
ob_start();
session_start();
require_once '../db.php';
require 'mailer/src/Exception.php';
require 'mailer/src/PHPMailer.php';
require 'mailer/src/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
if(!isset($_SESSION['admin']))
{
    header("location: login.php");
    die(); exit();
}

if(isset($_POST['submit'])){
    $stmt = $sql->prepare("select * from users");
    $stmt->execute();
    $users = $stmt->fetchAll();
    $mail = new PHPMailer(true);
    include 'newsletter-template.php';
    try {
        $mail->SMTPDebug = 0;                                     
        $mail->isSMTP();                                          
        $mail->Host       = $mailer['host']; 
        $mail->SMTPAuth   = true;                         
        $mail->Username   = $mailer['username'];           
        $mail->Password   = $mailer['password'];                              
        $mail->SMTPSecure = 'tls';                                  
        $mail->Port       = 587;                               
        $mail->setFrom($mailer['from'], $mailer['fromName']);   
        foreach($users as $user){
            $mail->addAddress($user['email'], '');
        }
        $mail->Subject = "Newsletter";
        $mail->isHTML(true);
        $mail->Body    = $message;
        if($mail->send()){
            $msg = "<div class='alert alert-success'>Newsletter sent successfully.</div>";
        }
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        die();
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Send Newsletter</title>
    <?php include 'head.php'; ?>
    <style>
        .form-control{
            border-radius: 0;
        }
    </style>
</head>

<body>
    <?php include 'nav.php'; ?>
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-md-6">
                <h3 class="mt-5 mb-3 text-white fancy">Send Newsletter</h3>
                <div class=" mb-3 ">
                    <div class="text-white">
                        <?php if(isset($msg)){ echo $msg; } ?>
                        <form method="post" class="" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="">Message on Newsletter*</label>
                                <textarea required name="message" class="form-control" id="" cols="30" rows="5"></textarea>
                            </div>
                            <div class="mb-3">
                            <label for="">Select Item(s)*</label>
                            <?php 
                                $stmt = $sql->prepare("select a.*, b.name as category, (select AVG(rating) from reviews where product_id=a.id) as rating from products as a left join categories as b on a.category=b.id order by a.id desc");
                                $stmt->execute();
                                $products = $stmt->fetchAll();
                                foreach($products as $product){
                                    $rating = empty($product['rating'])?0:$product['rating'];
                                    $rating = number_format($rating, 2);
                            ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="<?php echo $product['id']; ?>" id="defaultCheck<?php echo $product['id']; ?>" name="items[]">
                                <label class="form-check-label" for="defaultCheck<?php echo $product['id']; ?>">
                                    <?php echo $product['title']; ?> ($<?php echo $product['price']; ?>)
                                </label>
                            </div>
                            <?php } ?>
                            </div>
                            <div class="">
                                <button name="submit" class="btn btn-primary btn-block">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
			</div>
        </div>
    </div>
    
</body>
</html>