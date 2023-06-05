<?php
include 'auth.php';
$userid = $_SESSION['user'];
if(isset($_GET['order_id'])){
    $id = $_GET['order_id'];
    $query = "select * from orders where id=? AND user_id=?";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $id, PDO::PARAM_STR);
    $stmt->bindParam(2, $userid, PDO::PARAM_STR);
    $stmt->execute();
    $order = $stmt->fetch();
    if($stmt->rowCount()==0){
        header("location:products.php");
        die();
    }
    
    $query = "select a.*, b.name, b.size, b.image, b.uploader from order_items as a left join products as b on a.product_id=b.id where a.order_id=?";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $id, PDO::PARAM_STR);
    $stmt->execute();
    $products = $stmt->fetchAll();
    $total = 0;
    
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Cart</title>
    <?php include 'head.php'; ?>
    <style>
        td{
            vertical-align: middle !important;
            padding: 10px !important;
        }
        
        .pricing{
            text-align: center;
        }
        
        body {
            background: #e9fbeb;
        }
        
    </style>
</head>

<body>
    
    <div class="container">
       <?php include 'nav.php'; ?>
       <center>
           <h3 class="mt-3 mb-5">Order Placed Successfully</h3>
       </center>
        <div class="row justify-content-center mb-5">
            <div class="col-md-8">
                
                <div class="text-center mt-3 mb-5">
                    <h5><b>Order Time:</b></h5>
                    <p class="mb-0"><?php echo $order['date']; ?></p>
                    <br>
                    
                    <h5><b>Payment Details:</b></h5>
                    <p class="mb-0">Payment Type: <?php echo strtoupper($order['payment_type']); ?></p>
                </div>
                
                
                
                <?php foreach($products as $product){ ?>
                    <div class="row align-items-center">
                       <div class="col-md-4">
                           <img style="width:100%;height:200px;object-fit:cover;" src="images/<?php echo $product['image']; ?>" alt="">
                       </div>
                       <div class="col-md-4 text-center">
                           
                       </div>
                        <div class="col-md-4 text-center">
                            <b><?php echo $product['name']; ?></b>
                            <br><b>$<?php echo $product['price']; ?></b>
                            <br>Quantity: <?php echo $product['quantity']; ?>
                            <br>Size: <?php echo $product['size']; ?>
                            <?php
                                $query = "select * from user_addresses where user_id=? Order by id asc limit 1";
                                $stmt = $sql->prepare($query);
                                $stmt->bindParam(1, $product['uploader'], PDO::PARAM_STR);
                                $stmt->execute();
                                $address = $stmt->fetch();
                            ?>
                            <br>Seller Address: <?php echo $address['street']; ?>, <?php echo $address['city']; ?>, <?php echo $address['postal_code']; ?>
                        </div>
                    </div>
                    <hr>
                    <?php $total += $product['price']*$product['quantity']; } ?>
                    <div class="row">
                        <div class="col-md-8"></div>
                        <div class="col-md-4 ">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="float-right">
                                        <h4><span class="mr-4">Sub total</span> </h4>
                                        <h4><span class="mr-4">Discount</span></h4>
                                        <h4><span class="mr-4">Total</span></h4>
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="float-right">
                                        <h4>$<?php echo $total; ?></h4>
                                        <h4>$<?php echo $order['discount']; ?></h4>
                                        <h4>$<?php echo $total-$order['discount']; ?></h4>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                
                
               
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>