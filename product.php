<?php
require_once 'auth.php';
if(isset($_GET['id'])){
    $id = $_GET['id'];
    $query = "select * from products where id=?";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $id, PDO::PARAM_STR);
    $stmt->execute();
    $product = $stmt->fetch();
    
    $query = "select * from additional_images where product_id=?";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $product['id'], PDO::PARAM_STR);
    $stmt->execute();
    $images = $stmt->fetchAll();
    
    $query = "select * from users where id=?";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $product['uploader'], PDO::PARAM_STR);
    $stmt->execute();
    $seller = $stmt->fetch();
    
    $query = "select AVG(rating) as seller_score from reviews where user_id=?";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $product['uploader'], PDO::PARAM_STR);
    $stmt->execute();
    $score = $stmt->fetch();
    
    $query = "select * from user_addresses where user_id=? order by id asc limit 1";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $product['uploader'], PDO::PARAM_STR);
    $stmt->execute();
    $address = $stmt->fetch();
    
    if($stmt->rowCount()==0){
        header("location:index.php");
        die();
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

        .product-image {
            height: 350px;
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
            <div class="col-md-5">
                <p class="text-muted"><b>Product</b></p>
                <div class="card shadow">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                               
                               
                               <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                                   <div class="carousel-inner">
                                       <div class="carousel-item active">
                                           <img class="product-image d-block mb-3 img-thumnail" src="images/<?php echo $product['image']; ?>" alt="">
                                       </div>
                                       <?php foreach($images as $image){ ?>
                                       <div class="carousel-item">
                                           <img src="images/<?php echo $image['image']; ?>" class="d-block w-100" alt="...">
                                       </div>
                                       <?php } ?>
                                   </div>
                                   <button class="carousel-control-prev" type="button" data-target="#carouselExampleControls" data-slide="prev">
                                       <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                       <span class="sr-only">Previous</span>
                                   </button>
                                   <button class="carousel-control-next" type="button" data-target="#carouselExampleControls" data-slide="next">
                                       <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                       <span class="sr-only">Next</span>
                                   </button>
                               </div>
                               
                               
                               
                               
                                
                                
                                   
                                   
                                   
                                   <div class="p-2 product-data">
                                    <h3 class="mb-3"><?php echo $product['name']; ?></h3>
                                    <p class=" mb-3"><b class="muted">Size:</b> <?php echo $product['size']; ?></p>
                                    <p class="mb-3"><b class="">Price:</b> $<?php echo number_format($product['price'], 2); ?></p>
                                    <p class="mb-3"><b class="">Publication Date:</b> <?php echo $product['dateadded']; ?></p>
                                    <p class="mb-3"><b class="">Description:</b> <?php echo $product['description']; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <p class="text-muted"><b>Actions</b></p>
                <div class="card">
                    <div class="card-body">
                        <button class="btn btn-primary addtocart" data-id="<?php echo $product['id']; ?>">
                            Add to cart
                        </button>
                        <hr>
                        <h4>Seller Details</h4>
                        <p class="mb-0">Seller Name: <b><?php echo $seller['firstname']; ?> <?php echo $seller['lastname']; ?></b></p>
                        <p>Contact Email: <b><?php echo $seller['email']; ?></b></p>
                        <?php if($score['seller_score']!=0){ ?>
                            <p class="mb-0">Seller Score: <b><?php echo number_format($score['seller_score'], 2); ?> / 10</b></p>
                        <?php }else{ ?>
                            The seller did not receive a rating at all.
                        <?php } ?>
                        <?php
                        $stmt = $sql->prepare("SELECT review, id, rating
                        FROM reviews
                        WHERE user_id = ?;");
                        $stmt->bindParam(1, $product['uploader'], PDO::PARAM_STR);
                        $stmt->execute();
                        $reviews = $stmt->fetchAll();
                        foreach ($reviews as $review) {
                        ?>
                        <div class="form-check">
                            <label class="form-check-label" style="color:#888;" for="review_<?php echo $review['id']; ?>">
                                <?php echo $review['review']; ?><b> <?php echo $review['rating']; ?>/10</b>
                            </label>
                        </div>
                        <?php } ?>
                        <div id="map" style="height: 250px"></div>
                        <hr>
                        <h4>You may also like</h4>
                        <?php
                            $query = "select * from products where id!=? AND status='available' ORDER by RAND() LIMIT 1";
                            $stmt = $sql->prepare($query);
                            $stmt->bindParam(1, $id, PDO::PARAM_STR);
                            //$stmt->bindParam(2, $product['category'], PDO::PARAM_STR);
                            $stmt->execute();
                            $similar_product = $stmt->fetch();
                            if($stmt->rowCount()>0){
                        ?>
                        <div class="text-center">
                            <a href="product.php?id=<?php echo $similar_product['id'] ?>">
                                <img class="mb-3 img-thumnail" style="width:200px;height:200px" src="images/<?php echo $similar_product['image']; ?>" alt="">
                                <div class="p-2 product-data">
                                    <p class="mb-0"><?php echo $similar_product['name']; ?></p>
                                    <p class="mb-3"><b class="">Price:</b> $<?php echo number_format($similar_product['price'], 2); ?></p>
                                </div>
                            </a>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<?php include 'footer.php'; ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>
<script>
var address = '<?php echo $address['city'].' '.$address['street'] ?>';    
</script>
<script src="js/map.js"></script>

<script>
        
        $(".addtocart").click(function() {
            var elem = $(this);
            var product_id = $(this).data('id');
            

            elem.prop('disabled', true).html("Please wait");
            $.ajax({
                url: 'addToCart.php',
                type: 'POST',
                data: {
                    addToCart: 1,
                    product_id: product_id,
                    
                },
                success: function(data) {
                    alert('Added to cart');
                    getTotalCartItems();
                    elem.prop('disabled', false).html("Add to cart");
                },
            });
        });
        
        
    </script>

</html>