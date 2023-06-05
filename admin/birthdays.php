<?php
ob_start();
session_start();
require_once '../db.php';
require 'mailer/src/Exception.php';
require 'mailer/src/PHPMailer.php';
require 'mailer/src/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
if(!isset($_SESSION['admin'])){
    header("location:login.php");
    die();
}

if(isset($_POST['send'])){
    $stmt = $sql->prepare("select * from users where id=?");
    $stmt->bindParam(1, $_POST['user_id'], PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch();
    $mail = new PHPMailer(true);
    include 'promotion-template.php';
    
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
        $mail->addAddress($user['email'], '');
        $mail->Subject = "Happy Birthday from Wesam.com";
        $mail->isHTML(true);
        $mail->Body    = $message;
        if($mail->send()){
            $msg = "<div class='alert alert-success'>Promotion sent successfully.</div>";
        }
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        die();
    }
}


if(isset($_POST['delete'])){
    $query = "DELETE from users where id=?";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $_POST['user_id'], PDO::PARAM_STR);
    $stmt->execute();
    $msg = "<div class='alert alert-success'>User removed successfully.</div>";
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>User Birthdays</title>
    <?php include 'head.php'; ?>
    <style>
        td{
            vertical-align: middle !important;
            padding: 10px !important;
        }
        
        .pricing{
            text-align: center;
        }
        
        .divider{
            opacity: .35;
            margin-left: 5px;
            margin-right: 5px;
        }
        
         p, th, td{
            color: white;
        }
        
        .form-control{
            border-radius: 0;
        }
        
        .table, th, td{
            border: 0 !important;
            padding: 0 !important;
        }
        
        th, td{
            
            border:3px solid rgba(255,255,255,.08) !important;
            padding: 8px !important;
        }
        
        td{
            padding-bottom: 10px !important;
        }
        
        .btn-primary{
            border-radius: 0;
            background-color: #d26e4b;
            border-color: #d26e4b;
        }
    </style>
    
</head>

<body>
    <?php include 'nav.php'; ?>
    <div class="container">
        <div class="row  mb-5">
            <div class="col-md-12">
                <h1 class="mt-5 mb-3 fancy text-white">User birthdays in next 7 days</h1>
                <?php if(isset($msg)){ echo $msg; } ?>
                <table class="table table-bordered">
                <tr class=" text-white">
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>DOB</th>
                    <th>Actions</th>
                </tr>
                <?php 
                    $stmt = $sql->prepare("select * from users
                    WHERE  DATE_ADD(dob, 
                        INTERVAL YEAR(CURDATE())-YEAR(dob)
                                 + IF(DAYOFYEAR(CURDATE()) > DAYOFYEAR(dob),1,0)
                        YEAR)  
                    BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY);
                    ");
                    $stmt->execute();
                    $users = $stmt->fetchAll();
                    foreach($users as $user){
                ?>
                <tr>
                    <td><?php echo $user['name']; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo $user['dob']; ?></td>
                    <td>
                        <button type="button" class="btn btn-primary btn-sm sendPromotion" data-id="<?php echo $user['id']; ?>">Send Promotion</button>
                    </td>
                </tr>
                <?php } ?>
                </table>
                
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Send Promotion</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="" method="post">
                                    <div class="form-group">
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
                                        <input type="hidden" class="form-control" name="user_id" required id="user_id">
                                    </div>
                                    
                                    <div class="form-group">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary" name="send">Send</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.2/js/bootstrap.min.js"></script>
<script>
    $(".sendPromotion").click(function(){
        var id = $(this).data('id');
        $("#user_id").val(id);
        $("#exampleModal").modal("show");
    });
</script>
</body>
</html>