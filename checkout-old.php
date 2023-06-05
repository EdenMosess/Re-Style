<?php
session_start();
require_once 'db.php';
if(!isset($_SESSION['user'])){
    header('location:login.php');
    die();
}
$userid = $_SESSION['user'];

if(isset($_POST['placeorder'])){
    $stmt = $sql->prepare("select SUM(b.price*a.quantity) as total from cart as a left join products as b on a.product_id=b.id where a.user_id=?");
    $stmt->bindParam(1, $userid, PDO::PARAM_STR);
    $stmt->execute();
    $total = $stmt->fetch();
    $total = $total['total'];
    
    $date = date('Y-m-d H:i');
    $query = "INSERT into orders (date, total, user_id, firstname, lastname, company_name, country, city, street, zip, phone, email, notes, payment_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $date, PDO::PARAM_STR);
    $stmt->bindParam(2, $total, PDO::PARAM_STR);
    $stmt->bindParam(3, $userid, PDO::PARAM_STR);
    $stmt->bindParam(4, $_POST['first_name'], PDO::PARAM_STR);
    $stmt->bindParam(5, $_POST['last_name'], PDO::PARAM_STR);
    $stmt->bindParam(6, $_POST['company_name'], PDO::PARAM_STR);
    $stmt->bindParam(7, $_POST['country'], PDO::PARAM_STR);
    $stmt->bindParam(8, $_POST['city'], PDO::PARAM_STR);
    $stmt->bindParam(9, $_POST['street'], PDO::PARAM_STR);
    $stmt->bindParam(10, $_POST['zip'], PDO::PARAM_STR);
    $stmt->bindParam(11, $_POST['phone'], PDO::PARAM_STR);
    $stmt->bindParam(12, $_POST['email'], PDO::PARAM_STR);
    $stmt->bindParam(13, $_POST['notes'], PDO::PARAM_STR);
    $stmt->bindParam(14, $_POST['payment_type'], PDO::PARAM_STR);
    $stmt->execute();
    $orderid = $sql->lastInsertId();

    $stmt = $sql->prepare("select a.quantity, a.size, a.product_id, b.price from cart as a left join products as b on a.product_id=b.id where a.user_id=? order by a.id desc");
    $stmt->bindParam(1, $userid, PDO::PARAM_STR);
    $stmt->execute();
    $cart_items = $stmt->fetchAll();
    $total = 0;
    foreach($cart_items as $cart_item){
        $query = "INSERT into order_items (product_id, order_id, quantity, price, size) VALUES (?, ?, ?, ?, ?)";
        $stmt = $sql->prepare($query);
        $stmt->bindParam(1, $cart_item['product_id'], PDO::PARAM_STR);
        $stmt->bindParam(2, $orderid, PDO::PARAM_STR);
        $stmt->bindParam(3, $cart_item['quantity'], PDO::PARAM_STR);
        $stmt->bindParam(4, $cart_item['price'], PDO::PARAM_STR);
        $stmt->bindParam(5, $cart_item['size'], PDO::PARAM_STR);
        $stmt->execute();
    }

    $stmt = $sql->prepare("delete from cart where user_id=?");
    $stmt->bindParam(1, $userid, PDO::PARAM_STR);
    $stmt->execute();
    header('location:thank-you.php?order_id='.$orderid);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Checkout</title>
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
           <h3 class="mt-5 mb-5 text-white fancy">
               <?php echo $ln['shopping_cart']; ?> <span class="divider">/</span> <?php echo $ln['checkout_details']; ?>
           </h3>
       </center>
        <form action="" method="post">
        <div class="row mb-5">
            <div class="col-md-6">
               <h4 class="fancy text-white"><?php echo $ln['billing_details']; ?></h4>
               <div class="is-divider-small"></div>
               <div class="row">
                   <div class="col-md-6">
                       <div class="form-group">
                           <label for=""><?php echo $ln['firstname']; ?>*</label>
                           <input required type="text" class="form-control" name="first_name">
                       </div>
                   </div>
                   <div class="col-md-6">
                       <div class="form-group">
                           <label for=""><?php echo $ln['lastname']; ?>*</label>
                           <input required type="text" class="form-control" name="last_name">
                       </div>
                   </div>
                   <div class="col-md-6">
                       <div class="form-group">
                           <label for=""><?php echo $ln['company_name']; ?> (<?php echo $ln['optional']; ?>)</label>
                           <input type="text" class="form-control" name="company_name">
                       </div>
                   </div>
                   <div class="col-md-6">
                       <div class="form-group">
                           <label for=""><?php echo $ln['country']; ?>*</label>
                           <input required type="text" class="form-control" name="country">
                       </div>
                   </div>
                   <div class="col-md-6">
                       <div class="form-group">
                           <label for=""><?php echo $ln['street']; ?>*</label>
                           <input required type="text" class="form-control" name="street">
                       </div>
                   </div>
                   <div class="col-md-6">
                       <div class="form-group">
                           <label for=""><?php echo $ln['city']; ?>*</label>
                           <input required type="text" class="form-control" name="city">
                       </div>
                   </div>
                   <div class="col-md-6">
                       <div class="form-group">
                           <label for=""><?php echo $ln['zip']; ?>*</label>
                           <input required type="text" class="form-control" name="zip">
                       </div>
                   </div>
                   <div class="col-md-6">
                       <div class="form-group">
                           <label for=""><?php echo $ln['phone']; ?>*</label>
                           <input required type="text" class="form-control" name="phone">
                       </div>
                   </div>
                   <div class="col-md-12">
                       <div class="form-group">
                           <label for=""><?php echo $ln['email']; ?>*</label>
                           <input required type="email" class="form-control" name="email">
                       </div>
                   </div>
                   <div class="col-md-12">
                       <div class="form-group">
                           <label for=""><?php echo $ln['order_notes']; ?> (<?php echo $ln['optional']; ?>)</label>
                           <textarea name="notes" class="form-control" id="" cols="30" rows="5"></textarea>
                       </div>
                   </div>
               </div>
           </div>
            <div class="col-md-1"></div>
            <div class="col-md-5">
               <h4 class="fancy text-white"><?php echo $ln['your_order']; ?></h4>
               <div class="is-divider-small"></div>
               <table class="table">
                   <tr>
                       <th><?php echo $ln['product']; ?></th>
                       <th style="text-align:right"><?php echo $ln['subtotal']; ?></th>
                   </tr>
                   <?php 
                        $stmt = $sql->prepare("select a.quantity, a.size, a.id as cart_id, b.* from cart as a left join products as b on a.product_id=b.id where a.user_id=? order by a.id desc");
                        $stmt->bindParam(1, $userid, PDO::PARAM_STR);
                        $stmt->execute();
                        $products = $stmt->fetchAll();
                        $total = 0;
                        foreach($products as $product){
                            $total += $product['price']*$product['quantity'];
                    ?>
                    <tr>
                        <td width="70%"><?php echo $product['title']; ?> (Size: <?php echo $product['size']; ?>) x <?php echo $product['quantity']; ?></td>
                        <td align="right">$<?php echo number_format($product['price']*$product['quantity'], 2); ?></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr>
                        <td width="70%"><?php echo $ln['total']; ?></td>
                        <td align="right">$<?php echo number_format($total, 2); ?></td>
                    </tr>
               </table>
               <div class="form-check mt-5">
                   <input class="form-check-input" type="radio" name="payment_type" id="exampleRadios1" value="Cash on Delivery" checked>
                   <label class="form-check-label" for="exampleRadios1">
                       <?php echo $ln['cash_on_delivery']; ?>
                   </label>
               </div>
               <div class="form-check">
                   <input class="form-check-input" type="radio" name="payment_type" id="exampleRadios2" value="Bank Transfer">
                   <label class="form-check-label" for="exampleRadios2">
                       <?php echo $ln['bank_transfer']; ?>
                   </label>
               </div>
               <button class="btn btn-primary mt-5" name="placeorder"><?php echo $ln['place_order']; ?></button>
            </div>
        </div>
        </form>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>