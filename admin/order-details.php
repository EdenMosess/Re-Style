<?php
ob_start();
session_start();
require_once '../db.php';
if(!isset($_SESSION['admin']))
{
    header("location: login.php");
    die(); exit();
}

if(isset($_GET['id'])){
    $stmt = $sql->prepare("select * from orders where id = ?");
    $stmt->bindParam(1, $_GET['id'], PDO::PARAM_STR);
    $stmt->execute();
    $orderdata = $stmt->fetch(); 
    if($stmt->rowCount()==0){
        header("location:index.php");
        die();
    }

    $stmt = $sql->prepare("select * from order_items where order_id = ?");
    $stmt->bindParam(1, $_GET['id'], PDO::PARAM_STR);
    $stmt->execute();
    $orderproducts = $stmt->fetchAll();
    
}else{
    header("location:products.php");
    die();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Order Details</title>
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
        <div class="row">
            
            <div class="col-lg-12 mb-5">
               <h1 class="mt-5 mb-3 fancy text-white">Order #<?php echo $orderdata['id']; ?> Details</h1>
               <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>
                                    <div class="text-uppercase">Product</div>
                                </th>
                                <th >
                                    <div class="text-uppercase">Price</div>
                                </th>
                                <th>
                                    <div class="text-uppercase">Quantity</div>
                                </th>
                                <th >
                                    <div class="text-uppercase">Total</div>
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $total = 0;
                                foreach($orderproducts as $product){
                                    $stmt = $sql->prepare("select * from products where id = ?");
                                    $stmt->bindParam(1, $product['product_id'], PDO::PARAM_STR);
                                    $stmt->execute();
                                    $productdata = $stmt->fetch(); //Get data of each product in this order.
                            ?>
                            <tr>
                                <th>
                                    <div class="p-2">
                                        <img src="../images/<?php echo $productdata['image']; ?>" alt="" width="70" class="img-fluid rounded shadow-sm">
                                        <div class="ml-3 d-inline-block align-middle">
                                            <h5 class="mb-0">
                                                <a target="_blank" href="product-details.php?id=<?php echo $productdata['id']; ?>" class="text-white d-inline-block align-middle">
                                                    <?php echo $productdata['title']; ?> (<?php echo $product['size']; ?>)
                                                </a></h5>
                                        </div>
                                    </div>
                                </th>
                                <td ><strong>$<?php echo number_format($product['price'], 2); ?></strong></td>
                                <td ><strong><?php echo $product['quantity']; ?></strong></td>
                                <td><strong>$<?php echo number_format($product['quantity']*$product['price'], 2); ?></strong></td>

                            </tr>
                            <?php $total+=$product['quantity']*$product['price'];  } ?>
                        </tbody>
                    </table>
            </div>
        </div>
        <div class="row mb-5">
                <div class="col-lg-6">
                    <h3 class="text-uppercase fancy text-white">User Details </h3>
                    <div class="is-divider-small"></div>
                    <div class="text-white">
                        
                        <label class="text-muted">Name: </label> <?php echo $orderdata['firstname'].' '.$orderdata['lastname']; ?> <br>
                        <label class="text-muted">Country: </label> <?php echo $orderdata['country']; ?> <br>
                        <label class="text-muted">City: </label> <?php echo $orderdata['city']; ?><br>
                        <label class="text-muted">Street: </label> <?php echo $orderdata['street']; ?><br>
                        <label class="text-muted">Zip: </label> <?php echo $orderdata['zip']; ?><br>
                        <label class="text-muted">Phone: </label> <?php echo $orderdata['phone']; ?><br>
                        <label class="text-muted">Email: </label> <?php echo $orderdata['email']; ?><br>
                    </div>
                </div>
                <div class="col-lg-6">
                    <h3 class="text-uppercase fancy text-white">Order Summary </h3>
                    <div class="text-white">
                        <ul class="list-unstyled mb-4">
                           
                            <li class="d-flex justify-content-between py-3 "><strong class="text-muted"><h1 class="text-white fancy">Total</h1></strong>
                                <h5 class="font-weight-bold"><h1>$<?php echo number_format($total, 2); ?></h1></h5>
                            </li>
                        </ul>
                        
                    </div>
                </div>
            </div>
        
    </div>
</body>
</html>