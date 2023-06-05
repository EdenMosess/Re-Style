<?php
ob_start();
session_start();
require_once 'db.php';
if(isset($_POST['uploadDesign'])){
    $path_parts = pathinfo($_FILES["image"]["name"]);
    $extension = $path_parts['extension'];
    $extension = strtolower($extension);
    if($extension=='png' || $extension=='jpg' || $extension=='jpeg' || $extension=='gif'){
        $image = uniqid(time()).".$extension";
        $image_tmp = $_FILES['image']['tmp_name'];
        move_uploaded_file($image_tmp, "designs/$image");
        $date = date('Y-m-d');
        $query = "INSERT into custom_designs set date=?, user_id=?, design_file=?, description=?";
        $stmt = $sql->prepare($query);
        $stmt->bindParam(1, $date, PDO::PARAM_STR);
        $stmt->bindParam(2, $_SESSION['user'], PDO::PARAM_STR);
        $stmt->bindParam(3, $image, PDO::PARAM_STR);
        $stmt->bindParam(4, $_POST['description'], PDO::PARAM_STR);
        $stmt->execute();
        $msg = "<div class='alert alert-success'>Order received. We will be in contact soon.</div>";
    }else{
        $msg = "<div class='alert alert-danger'>Sorry please upload an image file.</div>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo $ln['shop']; ?></title>
    <?php include 'head.php'; ?>
    <style>
        .divider{
            opacity: .35;
            margin-left: 5px;
            margin-right: 5px;
        }
    </style>
</head>

<body>
    <?php include 'nav.php'; ?>
    
    <div class="container">
        <div class="row mb-5 mt-5">
          <div class="col-md-12 text-center mb-5">
              
              <h1 class="text-white fancy"><?php echo $ln['search']; ?>  <span class="divider">/</span> <?php echo ($_GET['q']); ?></h1>
          </div>
           <div class="col-md-12">
               <?php if(isset($msg)){ echo $msg; } ?>
           </div>
            <?php 
                $q = "%".$_GET['q']."%";
                $stmt = $sql->prepare("select * from products where title LIKE ?");
                $stmt->bindParam(1, $q, PDO::PARAM_STR);
                $stmt->execute();
                $products = $stmt->fetchAll();
                foreach($products as $product){
                    $title = strlen($product['title'])>=40 ? substr($product['title'], 0, 37).'....' : $product['title'];
            ?>
            <div class="col-md-4 mb-5 text-center">
                <a href="product.php?id=<?php echo $product['id']; ?>"><img class="product-image" src="images/<?php echo $product['image']; ?>" alt=""></a>
                <div class="p-2 product-data">
                    <small class="text-white category"><?php echo $product['category']; ?></small>
                    <a href="product.php?id=<?php echo $product['id']; ?>"><p class="text-white mb-2 product-name"><?php echo $title; ?></p></a>
                    <p class="text-white"><b>$<?php echo number_format($product['price'], 2); ?></b></p>
                    <button class="btn btn-secondary btn-sm addtocart" data-id="<?php echo $product['id']; ?>" data-quantity="1"><?php echo $ln['add_to_cart']; ?></button>
                </div>
            </div>
            <?php } ?>
        </div>
        
        <div class="modal fade" id="guideModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><?php echo $ln['size_guide']; ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-0">
                        <img class="img-fluid" src="images/guide.jpg" alt="">
                    </div>
                    
                </div>
            </div>
        </div>
        
        
        
    </div>
    <input type="hidden" id="product_quantity" value="1">
    <?php include 'footer.php'; ?>
</body>
</html>