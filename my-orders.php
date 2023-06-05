<?php
session_start();
require_once 'db.php';
if(!isset($_SESSION['user'])){
    header('location:login.php');
    die();
}
$userid = $_SESSION['user'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>My Orders</title>
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
        
        label, p, th, td{
            color: white;
        }
        
        .form-control{
            border-radius: 0;
        }
        
        .table, th, td{
            border: 0 !important;
            padding: 0 !important;
        }
        
        th{
            padding-bottom:5px !important;
            border-bottom:3px solid rgba(255,255,255,.08) !important;
        }
        
        td{
            padding-bottom: 15px !important;
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
       <center>
           <h1 class="mt-5 mb-5 text-white fancy">
                 My Orders
           </h1>
       </center>
        <form action="" method="post">
        <div class="row justify-content-center mb-5">
            <div class="col-md-12">
               <table class="table">
                   <tr>
                       <th>Order ID</th>
                       <th>Order Date</th>
                        <th>Payment Method</th>
                       <th>Total Amount</th>
                      <th></th>
                   </tr>
                   <?php
                    $query = "select * from orders where user_id=? order by id desc";
                    $stmt = $sql->prepare($query);
                    $stmt->bindParam(1, $userid, PDO::PARAM_STR);
                    $stmt->execute();
                    $orders = $stmt->fetchAll();
                    foreach($orders as $order){
                   ?>
                   <tr>
                       <td><?php echo $order['id']; ?></td>
                       <td><?php echo $order['date']; ?></td>
                       <td><?php echo $order['payment_type']; ?></td>
                       <td>$<?php echo number_format($order['total'], 2); ?></td>
                       <td><a target="_blank" href="thank-you.php?order_id=<?php echo $order['id']; ?>"><i class="fa fa-eye"></i> Details</a></td>
                   </tr>
                   <?php } ?>
               </table>
               
               
            </div>
        </div>
        </form>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>