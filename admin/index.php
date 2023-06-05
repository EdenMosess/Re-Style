<?php
ob_start();
session_start();
require_once '../db.php';
if(!isset($_SESSION['admin'])){
    header("location:login.php");
    die();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin</title>
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
                <h1 class="mt-5 mb-3 text-white fancy">All Orders</h1>
                <table class="table table-bordered">
                <tr class="">
                    <th>Order ID</th>
                    <th>User</th>
                    <th>Date</th>
                    <th>Total Price</th>
                    <th>Payment Type</th>
                    <th>Details</th>
                </tr>
                <?php 
                    $stmt = $sql->prepare("select a.*, b.name from orders as a left join users as b on a.user_id=b.id order by a.id desc");
                    $stmt->execute();
                    $orders = $stmt->fetchAll();
                    foreach($orders as $order){
                ?>
                <tr>
                    <td><?php echo $order['id']; ?></td>
                    <td><?php echo $order['name']; ?></td>
                    <td><?php echo $order['date']; ?></td>
                    <td>$<?php echo $order['total']; ?></td>
                    <td><?php echo $order['payment_type']; ?></td>
                    <td>
                        <a href="order-details.php?id=<?php echo $order['id']; ?>" class="text-white"><i class="fa fa-eye"></i> View Details</a>
                    </td>
                </tr>
                <?php } ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>