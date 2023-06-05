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
    $productid = $_GET['id'];
    $stmt = $sql->prepare("select * from products where id=?");
    $stmt->bindParam(1, $productid, PDO::PARAM_STR);
    $stmt->execute();
    $product = $stmt->fetch();
    if($stmt->rowCount()==0){
        header("location:products.php");
        die();
    }
}else{
    header("location:products.php");
    die();
}

if(isset($_POST['submit'])){
    $query = "Update products set title=?, price=?, category=?, description=?, product_number=?, sku=?, sub_category=? where id=?";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $_POST['title'], PDO::PARAM_STR);
    $stmt->bindParam(2, $_POST['price'], PDO::PARAM_STR);
    $stmt->bindParam(3, $_POST['category'], PDO::PARAM_STR);
    $stmt->bindParam(4, $_POST['description'], PDO::PARAM_STR);
    $stmt->bindParam(5, $_POST['product_number'], PDO::PARAM_STR);
    $stmt->bindParam(6, $_POST['sku'], PDO::PARAM_STR);
    $stmt->bindParam(7, $_POST['sub_category'], PDO::PARAM_STR);
    $stmt->bindParam(8, $productid, PDO::PARAM_STR);
    $stmt->execute();
    $msg = "<div class='alert alert-success'>Product updated successfully.</div>";
    $stmt = $sql->prepare("select * from products where id=?");
    $stmt->bindParam(1, $productid, PDO::PARAM_STR);
    $stmt->execute();
    $product = $stmt->fetch();
}

if(isset($_POST['updateimage'])){
    unlink('../images/'.$product['image']);
    $tmpFilePath = $_FILES['image']['tmp_name'];
    if(!empty($_FILES['image']['name'])){
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image = uniqid(time()).'.'.$ext;
        $newFilePath = "../images/".$image;
        move_uploaded_file($tmpFilePath, $newFilePath);
        $query = "UPDATE products set image=? where id=?";
        $stmt = $sql->prepare($query);
        $stmt->bindParam(1, $image, PDO::PARAM_STR);
        $stmt->bindParam(2, $productid, PDO::PARAM_STR);
        $stmt->execute();
        $msg = "<div class='alert alert-success'>Image updated successfully.</div>";
        $product['image'] = $image;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Product</title>
    <?php include 'head.php'; ?>
</head>

<body>
    <?php include 'nav.php'; ?>
    <div class="container">
        <div class="row justify-content-center mb-5">
           <div class="col-md-4 text-white">
               <img class="mt-5 mb-3 img-fluid" src="../images/<?php echo $product['image']; ?>" alt="">
               <form action="" method="post" enctype="multipart/form-data">
                   <div class="mb-3">
                       <label for="">Update Image*</label>
                       <input required type="file" name="image">
                   </div>
                   <button class="btn btn-primary btn-sm" name="updateimage">Update</button>
               </form>
           </div>
            <div class="col-md-6">
                <h3 class="mt-5 mb-3 fancy text-white">Edit Product
                <span class="float-right">
                    <a href="products.php" class="btn btn-primary btn-sm">Go Back</a>
                </span>
                </h3>
                <div class=" mb-3 ">
                    <div class="text-white">
                        <?php if(isset($msg)){ echo $msg; } ?>
                        <form method="post">
                           <div class="mb-3">
                                <label for="">Title*</label>
                                <input required type="text" class="form-control" name="title" value="<?php echo $product['title']; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="">Price*</label>
                                <input required type="number" class="form-control" name="price" value="<?php echo $product['price']; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="">Category*</label>
                                <select required name="category" class="form-control" id="categorySelect">
                                    <option value="">Select</option>
                                    <?php 
                                        $query = "select * from categories";
                                        $stmt = $sql->prepare($query);
                                        $stmt->execute();
                                        $categories = $stmt->fetchAll();
                                        foreach($categories as $category){ ?>
                                        <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="">Sub Category</label>
                                <select  name="sub_category" class="form-control" id="subCategorySelect">
                                    <option value="">Select</option>
                                    <?php 
                                        $query = "select * from sub_categories where category_id=?";
                                        $stmt = $sql->prepare($query);
                                        $stmt->bindParam(1, $product['category'], PDO::PARAM_STR);
                                        $stmt->execute();
                                        $subcategories = $stmt->fetchAll();
                                        foreach($subcategories as $subcategory){ ?>
                                        <option value="<?php echo $subcategory['id']; ?>"><?php echo $subcategory['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="">Product No.*</label>
                                <input required type="text" class="form-control" name="product_number" value="<?php echo $product['product_number']; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="">SKU*</label>
                                <input required type="text" class="form-control" name="sku" value="<?php echo $product['sku']; ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="">Description*</label>
                                <textarea required name="description" class="form-control" id="" cols="30" rows="5"><?php echo $product['description']; ?></textarea>
                            </div>
                            
                            <div class="">
                                <button name="submit" class="btn btn-primary btn-block">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
			</div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.2/js/bootstrap.min.js"></script>
    <script>
        $("#categorySelect").val('<?php echo $product['category']; ?>');
        $("#subCategorySelect").val('<?php echo $product['sub_category']; ?>');
        $("#categorySelect").change(function(){
        var val = $(this).val();
            $.ajax({
                url: 'add-product.php',
                type: 'POST',
                data: {
                    getSubCategory: 1,
                    category_id: val,
                },
                success: function(data) {
                    $("#subCategorySelect").html(data);
                },
            });
        });
    </script>
</body>
</html>