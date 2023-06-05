<?php
require_once 'auth.php';
if(isset($_POST['update'])){
    $query = "update products set name=?, price=?, size=?, description=?, category=?, brand=? where id=?";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $_POST['name'], PDO::PARAM_STR);
    $stmt->bindParam(2, $_POST['price'], PDO::PARAM_STR);
    $stmt->bindParam(3, $_POST['size'], PDO::PARAM_STR);
    $stmt->bindParam(4, $_POST['description'], PDO::PARAM_STR);
    $stmt->bindParam(5, $_POST['category'], PDO::PARAM_STR);
    $stmt->bindParam(6, $_POST['brand'], PDO::PARAM_STR);
    $stmt->bindParam(7, $_POST['product_id'], PDO::PARAM_STR);
    $stmt->execute();
    $msg = "<div class='alert alert-success'>Product updated successfully.</div>";
}

if(isset($_POST['remove'])){
    $query = "delete from products where id=?";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $_POST['product_id'], PDO::PARAM_STR);
    $stmt->execute();
    $msg = "<div class='alert alert-success'>Product removed successfully.</div>";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Ad Management</title>
    <?php include 'head.php'; ?>
    <style>
        body{
            background: #e9fbeb;
        }
    </style>
</head>

<body>
    <?php include 'nav.php'; ?>
    <div class="container">
        <div class="row justify-content-center mt-3 mb-5">
           
            <div class="col-md-9">
                <div class="btn-group w-100 mb-5" role="group" aria-label="Basic example">
                  <a href="ad-management.php" class="btn btn-primary">Ad Management</a>
                  <a href="upload-item.php" class="btn btn-outline-primary">Upload an item</a>
                  <a href="buyer-feedbacks.php" class="btn btn-outline-primary">Buyer Feedbacks</a>
                  
                </div>
                <h3 class="mb-5  text-muted"><b>Add Management</b></h3>
                <div class="card mb-3 shadow">
                    <div class="card-body">
                        <?php if(isset($msg)){ echo $msg; } ?>
                        <div class="row">
                            <div class="col-md-12">
                                <?php 
                                $stmt = $sql->prepare("select * from products where uploader=? order by id desc");
                                $stmt->bindParam(1, $_SESSION['user'], PDO::PARAM_STR);
                                $stmt->execute();
                                $products = $stmt->fetchAll();
                                if($stmt->rowCount()>0){
                                foreach($products as $product){
                                    $title = strlen($product['name'])>=30 ? substr($product['name'], 0, 27).'....' : $product['name'];
                            ?>
                            <div class="row mb-3">
                                <div class="col-md-4 mb-5 text-center">
                                    <a href="product.php?id=<?php echo $product['id']; ?>"><img class="product-image img-thumnail" src="images/<?php echo $product['image']; ?>" alt=""></a>
                                </div>
                                <div class="col-md-5">
                                    <div class="p-2 product-data">

                                        <form action="" method="post">
                                            <div class="form-group">
                                                <label for="">Item Name</label>
                                                <input required class="form-control" type="text" name="name" value="<?php echo $product['name']; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Item Type*</label>
                                                <select name="category" required class="form-control" id="category">
                                                    <option value="">Select</option>
                                                    <?php 
                                                        $stmt = $sql->prepare("select * from categories");
                                                        $stmt->execute();
                                                        $categories = $stmt->fetchAll();
                                                        foreach($categories as $category){
                                                    ?>
                                                    <option <?php if($category['id']==$product['category']){ echo 'selected'; } ?> value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Size</label>
                                                <?php if($product['category']=='7'){ ?>
                                                <input required class="form-control" type="number" name="size" value="<?php echo $product['size']; ?>">
                                                <?php }else{ ?>
                                                <select required name="size" class="form-control">
                                                    <option value="">Select</option>
                                                    <option <?php if($product['size']=='XS'){ echo 'selected'; }?> value="XS">XS</option>
                                                    <option <?php if($product['size']=='S'){ echo 'selected'; }?> value="S">S</option>
                                                    <option <?php if($product['size']=='M'){ echo 'selected'; }?> value="M">M</option>
                                                    <option <?php if($product['size']=='L'){ echo 'selected'; }?> value="L">L</option>
                                                    <option <?php if($product['size']=='XL'){ echo 'selected'; }?> value="XL">XL</option>
                                                    <option <?php if($product['size']=='XXL'){ echo 'selected'; }?> value="XXL">XXL</option>
                                                </select>
                                                <?php } ?>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Brand Name*</label>
                                                <select name="brand" required class="form-control" id="brand<?php echo $product['id']; ?>">
                                                    <option value="">Select</option>
                                                    <option value="Adidas">Adidas</option>
                                                    <option value="Anta">Anta</option>
                                                    <option value="Armani">Armani</option>
                                                    <option value="American Eagle">American Eagle</option>
                                                    <option value="Bosideng">Bosideng</option>
                                                    <option value="Boss">Boss</option>
                                                    <option value="Bottega Veneta">Bottega Veneta</option>
                                                    <option value="Brand">Brand</option>
                                                    <option value="Bulgari">Bulgari</option>
                                                    <option value="Burberry">Burberry</option>
                                                    <option value="Calvin Klein">Calvin Klein</option>
                                                    <option value="Cartier">Cartier</option>
                                                    <option value="Celine ">Celine </option>
                                                    <option value="Chanel">Chanel</option>
                                                    <option value="Chow Tai Fook">Chow Tai Fook</option>
                                                    <option value="COACH">COACH</option>
                                                    <option value="Dior">Dior</option>
                                                    <option value="Fila">Fila</option>
                                                    <option value="Forever 21">Forever 21</option>
                                                    <option value="Fox">Fox</option>
                                                    <option value="Givenchy">Givenchy</option>
                                                    <option value="GUCCI">GUCCI</option>
                                                    <option value="H&M">H&M</option>
                                                    <option value="Lao Feng Xiang">Lao Feng Xiang</option>
                                                    <option value="Levi\'s">Levi's</option>
                                                    <option value="Li Ning ">Li Ning </option>
                                                    <option value="Loewe">Loewe</option>
                                                    <option value="Louis Vuitton">Louis Vuitton</option>
                                                    <option value="Lululemon">Lululemon</option>
                                                    <option value="Michael Kors">Michael Kors</option>
                                                    <option value="Moncler">Moncler</option>
                                                    <option value="Next">Next</option>
                                                    <option value="Nike">Nike</option>
                                                    <option value="Old Navy">Old Navy</option>
                                                    <option value="Omega">Omega</option>
                                                    <option value="Pandora">Pandora</option>
                                                    <option value="Prada">Prada</option>
                                                    <option value="Primark / Penney\'s">Primark / Penney's </option>
                                                    <option value="Puma">Puma</option>
                                                    <option value="Ralph Lauren">Ralph Lauren</option>
                                                    <option value="Ray-Ban">Ray-Ban</option>
                                                    <option value="Rolex">Rolex</option>
                                                    <option value="Skechers">Skechers</option>
                                                    <option value="TAG Heuer">TAG Heuer</option>
                                                    <option value="The North Face">The North Face</option>
                                                    <option value="Tiffany & Co">Tiffany & Co</option>
                                                    <option value="Tommy Hilfiger">Tommy Hilfiger</option>
                                                    <option value="Under Armour">Under Armour</option>
                                                    <option value="UNIQLO">UNIQLO</option>
                                                    <option value="Van Cleef & Arpels">Van Cleef & Arpels</option>
                                                    <option value="Victoria\'s Secret">Victoria's Secret</option>
                                                    <option value="Yves Saint Laurent">Yves Saint Laurent</option>
                                                    <option value="ZARA">ZARA</option>
                                                </select>
                                                <script>
                                                    document.getElementById('brand<?php echo $product['id']; ?>').value='<?php echo $product['brand']; ?>';
                                                </script>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Price</label>
                                                <input required class="form-control" type="number" name="price" value="<?php echo $product['price']; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Description</label>
                                                <textarea required name="description" class="form-control" id="" cols="30" rows="3"><?php echo $product['description']; ?></textarea>
                                            </div>
                                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                            <button name="update" type="submit" class="btn btn-primary w-100">Update</button>
                                        </form>
                                        
                                    </div>
                                    
                                </div>
                                <div class="col-md-3">
                                    <form action="" method="post" onsubmit="return confirm('Are you sure want to remove this item?')">
                                        
                                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                        <button class="btn btn-danger w-100" name="remove">Remove</button>
                                       
                                    </form>
                                </div>
                            </div>
                            <hr>
                            <?php }}else{ ?>
                            <center><b>You dont have any ads yet.</b></center>
                            <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
</html>