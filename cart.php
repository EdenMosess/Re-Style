<?php
include 'auth.php';
$userid = $_SESSION['user'];
if(isset($_POST['removefromcart'])){
    $stmt = $sql->prepare("delete from cart where id=? AND user_id=?");
    $stmt->bindParam(1, $_POST['cart_id'], PDO::PARAM_STR);
    $stmt->bindParam(2, $userid, PDO::PARAM_STR);
    $stmt->execute();
    $msg = "<div class='alert alert-success'>Removed from cart successfully.</div>";
}

if(isset($_POST['placeorder'])){
    $stripe = new \Stripe\StripeClient('sk_test_51J1n6cLkIMUkDPVV8qyrQRulgxfWhVABZUiTrqvwqXRMh2APUPyp9h3KeFZHvMELgQnk12IVSKLFnovkcVK2ebUF00SizNnLtg'
    );
    
    $stmt = $sql->prepare("select SUM(b.price*a.quantity) as total from cart as a left join cakes as b on a.cake_id=b.id where a.user_id=?");
    $stmt->bindParam(1, $userid, PDO::PARAM_STR);
    $stmt->execute();
    $total = $stmt->fetch();
    $total = $total['total'];
    $stripetotal =  $total*100;
    
    $charge = $stripe->charges->create([
      'amount' =>$stripetotal,
      'currency' => 'usd',
      'source' => $_POST['stripeToken'],
      'description' => 'Cake shop order',
    ]);
    
    if($charge->status=='succeeded'){
        $time = date('Y-m-d H:i');
        $query = "INSERT into orders (time, total, user_id) VALUES (?, ?, ?)";
        $stmt = $sql->prepare($query);
        $stmt->bindParam(1, $time, PDO::PARAM_STR);
        $stmt->bindParam(2, $total, PDO::PARAM_STR);
        $stmt->bindParam(3, $userid, PDO::PARAM_STR);
        $stmt->execute();
        $orderid = $sql->lastInsertId();
        
        $stmt = $sql->prepare("select a.quantity, a.cake_id, b.price from cart as a left join cakes as b on a.cake_id=b.id where user_id=? order by a.id desc");
        $stmt->bindParam(1, $userid, PDO::PARAM_STR);
        $stmt->execute();
        $cart_items = $stmt->fetchAll();
        $total = 0;
        foreach($cart_items as $cart_item){
            $query = "INSERT into order_items (cake_id, order_id, quantity, price) VALUES (?, ?, ?, ?)";
            $stmt = $sql->prepare($query);
            $stmt->bindParam(1, $cart_item['cake_id'], PDO::PARAM_STR);
            $stmt->bindParam(2, $orderid, PDO::PARAM_STR);
            $stmt->bindParam(3, $cart_item['quantity'], PDO::PARAM_STR);
            $stmt->bindParam(4, $cart_item['price'], PDO::PARAM_STR);
            $stmt->execute();
        }
        
        $stmt = $sql->prepare("delete from cart where user_id=?");
        $stmt->bindParam(1, $userid, PDO::PARAM_STR);
        $stmt->execute();
        header('location:thank-you.php?order_id='.$orderid);
    }
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
           <h3 class="mt-3 mb-5">Your Shopping Cart</h3>
       </center>
        <div class="row justify-content-center mb-5">
            <div class="col-md-8">
                <?php if(isset($msg)){ echo $msg; } ?>
                
                <?php 
                    $stmt = $sql->prepare("select a.quantity, a.id as cart_id, b.* from cart as a left join products as b on a.product_id=b.id where a.user_id=? order by a.id desc");
                    $stmt->bindParam(1, $_SESSION['user'], PDO::PARAM_STR);
                    $stmt->execute();
                    $products = $stmt->fetchAll();
                    $total = 0;
                    $totalcart = $stmt->rowCount();
                    if($totalcart>0){
                ?>
                
                <?php foreach($products as $product){ ?>
                    <div class="row align-items-center">
                       <div class="col-md-4">
                           <img style="width:100%;height:200px;object-fit:cover;" src="images/<?php echo $product['image']; ?>" alt="">
                       </div>
                       <div class="col-md-4 text-center">
                           <form action="" method="post">
                               <input type="hidden" name="removefromcart" value="1">
                               <input type="hidden" name="cart_id" value="<?php echo $product['cart_id']; ?>">
                               <button type="submit" class="btn btn-secondary btn-sm" ><i class="fa fa-trash"></i></button>
                           </form>
                       </div>
                        <div class="col-md-4 text-center">
                            <b><?php echo $product['name']; ?></b>
                            <br><b>$<?php echo $product['price']; ?></b>
                            <br>Quantity: <?php echo     $product['quantity']; ?>
                            <br>Size: <?php echo $product['size']; ?>
                        </div>
                    </div>
                    <hr>
                    <?php $total += $product['price']*$product['quantity']; } ?>
                    <div class="row">
                        <div class="col-md-8"></div>
                        <div class="col-md-4 text-center">
                            <h4><span class="mr-4">Total</span> $<?php echo $total; ?></h4>
                        </div>
                    </div>
                
                
                <div class="mt-5 mb-5 float-right">
                    <a href="products.php" class="btn btn-secondary mr-3">Continue Shopping</a>
                    <a href="checkout.php" class="btn btn-secondary">Proceed to Checkout</a>
                </div>
                <?php }else{ ?>
                <div class="text-center">
                    <h5 class="mb-4">Oops. No items in your cart.</h5>
                    <a href="products.php" class="btn btn-secondary">Continue Shopping</a>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>