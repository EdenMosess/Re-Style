<?php
require_once 'auth.php';
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
    <title>Re-Style</title>
    <?php include 'head.php'; ?>
    <style>
        body {
            background: #e9fbeb;
        }
    </style>
</head>

<body>
    <?php include 'nav.php'; ?>
    <div class="container">
        <div class="row mt-5 mb-5 justify-content-center">
            <div class="col-md-9">
                <form action="" method="get">
                    <input type="text" name="q" class="form-control search" placeholder="Search Products..." value="<?php if(isset($_GET['q'])){ echo $_GET['q']; }?>">
                </form>
            </div>
        </div>
        <div class="row mb-5 mt-3">
            <div class="col-md-3">
                <p class="text-muted"><b>Categories</b></p>
                <div class="list-group shadow">
                    <a href="products.php" class="list-group-item list-group-item-action <?php if(!isset($_GET['category']) && !isset($_GET['q'])){ echo 'active'; } ?>">All Items</a>
                    <?php 
                        $stmt = $sql->prepare("select * from categories");
                        $stmt->execute();
                        $categories = $stmt->fetchAll();
                        foreach($categories as $category){
                            
                    ?>
                    <a href="products.php?category=<?php echo $category['id']; ?>" class="list-group-item list-group-item-action <?php if(isset($_GET['category'])){ if($_GET['category']==$category['id']){ echo 'active'; }} ?>"><?php echo $category['name']; ?></a>
                    <?php } ?>

                </div>
            </div>
            <div class="col-md-9">
                <p class="text-muted"><b>Items</b></p>
                <div class="card shadow">
                    <div class="card-body">
                        <div class="row">
                            <?php 
                                if(isset($_GET['q'])){
                                    $q = "%".$_GET['q']."%";
                                    $stmt = $sql->prepare("select * from products where status='available' AND name LIKE ? OR description LIKE ? order by id desc");
                                    $stmt->bindParam(1, $q, PDO::PARAM_STR);
                                    $stmt->bindParam(2, $q, PDO::PARAM_STR);
                                }else{
                                    if(isset($_GET['category'])){
                                        $stmt = $sql->prepare("select * from products where category=? AND status='available' order by id desc");
                                        $stmt->bindParam(1, $_GET['category'], PDO::PARAM_STR);
                                    }else{
                                        $stmt = $sql->prepare("select * from products where status='available' order by id desc");
                                    }
                                }    
                        
                                $stmt->execute();
                                $products = $stmt->fetchAll();
                                foreach($products as $product){
                                    $title = strlen($product['name'])>=30 ? substr($product['name'], 0, 27).'....' : $product['name'];
                            ?>
                            <div class="col-md-4 mb-5 text-center">
                                <a href="product.php?id=<?php echo $product['id']; ?>"><img class="product-image img-thumnail" src="images/<?php echo $product['image']; ?>" alt=""></a>
                                <div class="p-2 product-data">

                                    <a href="product.php?id=<?php echo $product['id']; ?>">
                                        <p class=" mb-0 product-name"><?php echo $title; ?></p>
                                    </a>
                                    <p class=" mb-0"><?php echo $product['size']; ?></p>
                                    <p class=""><b>$<?php echo number_format($product['price'], 2); ?></b></p>
                                    <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-secondary btn-sm">View Product</a>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>

</html>