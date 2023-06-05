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
    <title>Earnings</title>
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
        <h1 class="mt-5 mb-3 text-white fancy">Earnings</h1>
        <div class="row  mb-5">
            <div class="col-md-4">
                <div class="card bg-dark">
                    <div class="card-body">
                        <p class="mb-0">Sales Today</p>
                        <?php 
                            $date = date('Y-m-d');
                            $stmt = $sql->prepare("select SUM(total) as total from orders where DATE(date)=?");
                            $stmt->bindParam(1, $date, PDO::PARAM_STR);
                            $stmt->execute();
                            $orders = $stmt->fetch();
                            $total = empty($orders['total'])?0:$orders['total'];
                        ?>
                        <h1 class="text-white">$<?php echo number_format($total, 2); ?></h1>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-dark">
                    <div class="card-body">
                        <p class="mb-0">Sales This Month</p>
                        <?php 
                            $stmt = $sql->prepare("select SUM(total) as total from orders WHERE MONTH(date) = MONTH(CURRENT_DATE()) AND YEAR(date) = YEAR(CURRENT_DATE())");
                            $stmt->execute();
                            $orders = $stmt->fetch();
                            $total = empty($orders['total'])?0:$orders['total'];
                        ?>
                        <h1 class="text-white">$<?php echo number_format($total, 2); ?></h1>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-dark">
                    <div class="card-body">
                        <p class="mb-0">Sales All Time</p>
                        <?php 
                            $stmt = $sql->prepare("select SUM(total) as total from orders");
                            $stmt->execute();
                            $orders = $stmt->fetch();
                            $total = empty($orders['total'])?0:$orders['total'];
                        ?>
                        <h1 class="text-white">$<?php echo number_format($total, 2); ?></h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>