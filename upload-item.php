<?php
require_once 'auth.php';
if(isset($_POST['update'])){
    
    $image = "";
    $tmpFilePath = $_FILES['image']['tmp_name'];
    if(!empty($_FILES['image']['name'])){
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image = uniqid(time()).'.'.$ext;
        $newFilePath = "images/".$image;
        move_uploaded_file($tmpFilePath, $newFilePath);
    }
    
    $size = $_POST['size'];
    if($_POST['category']==7){
        $size = $_POST['shoe_size'];
    }
    $date = date('Y-m-d');
    $query = "insert into products set name=?, price=?, size=?, description=?, image=?, uploader=?, dateadded=?, category=?, brand=?";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $_POST['name'], PDO::PARAM_STR);
    $stmt->bindParam(2, $_POST['price'], PDO::PARAM_STR);
    $stmt->bindParam(3, $size, PDO::PARAM_STR);
    $stmt->bindParam(4, $_POST['description'], PDO::PARAM_STR);
    $stmt->bindParam(5, $image, PDO::PARAM_STR);
    $stmt->bindParam(6, $_SESSION['user'], PDO::PARAM_STR);
    $stmt->bindParam(7, $date, PDO::PARAM_STR);
    $stmt->bindParam(8, $_POST['category'], PDO::PARAM_STR);
    $stmt->bindParam(9, $_POST['brand'], PDO::PARAM_STR);
    $stmt->execute();
    $product_id = $sql->lastInsertId();
    foreach($_FILES["images"]["tmp_name"] as $key=>$tmp_name) {
        $file_name = $_FILES["images"]["name"][$key];
        $file_tmp = $_FILES["images"]["tmp_name"][$key];
        $extension = pathinfo($file_name,PATHINFO_EXTENSION);
        if($extension=='png' || $extension=='jpg' || $extension=='jpeg' || $extension=='gif'){
            $picture = uniqid(time()).".$extension";
            $query = "INSERT INTO additional_images (image, product_id) VALUES ('$picture', '$product_id')";
            $stmt = $sql->prepare($query);
            $stmt->execute();
            move_uploaded_file($file_tmp, "images/$picture");
        }
    }
    $msg = "<div class='alert alert-success'>Product added successfully.</div>";
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Upload Item</title>
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
        <div class="row justify-content-center mt-3 mb-5">
            <div class="col-md-9">
                <div class="btn-group w-100 mb-5" role="group" aria-label="Basic example">
                    <a href="ad-management.php" class="btn btn-outline-primary">Ad Management</a>
                    <a href="upload-item.php" class="btn btn-primary">Upload an item</a>
                    <a href="buyer-feedbacks.php" class="btn btn-outline-primary">Buyer Feedbacks</a>

                </div>
                <h3 class="mb-5  text-muted"><b>Upload an item</b></h3>
                <div class="card mb-3 shadow">
                    <div class="card-body">
                        <?php if(isset($msg)){ echo $msg; } ?>
                        <div class="row">
                            <div class="col-md-12">
                                <form action="" method="post" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="">Main Item Image*</label><br>
                                        <input required class="" type="file" name="image">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Additional Images (Max 4)</label><br>
                                        <input accept="image/png, image/gif, image/jpeg, image/jpg" type="file" name="images[]" multiple>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Item Name*</label>
                                        <input required class="form-control" type="text" name="name">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="">Price*</label>
                                        <input required class="form-control" type="number" name="price">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Description*</label>
                                        <textarea required name="description" class="form-control" id="" cols="30" rows="3"></textarea>
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
                                            <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Size*</label>
                                        <select name="size" class="form-control" id="other_size">
                                            <option value="">Select</option>
                                            <option value="XS">XS</option>
                                            <option value="S">S</option>
                                            <option value="M">M</option>
                                            <option value="L">L</option>
                                            <option value="XL">XL</option>
                                            <option value="XXL">XXL</option>
                                        </select>
                                        <input type="number" name="shoe_size" class="form-control" id="shoe_size" style="display:none">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="">Brand Name*</label>
                                        <select name="brand" required class="form-control" id="">
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
                                    </div>
                                    <button name="update" type="submit" class="btn btn-primary w-100">Update</button>
                                </form>
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
<script>
$("#category").change(function(){
    var val = $(this).val();
    if(val==7){
        $("#other_size").hide();
        $("#shoe_size").show();
    }else{
        $("#other_size").show();
        $("#shoe_size").hide();
    }
});  
    
</script>
</html>