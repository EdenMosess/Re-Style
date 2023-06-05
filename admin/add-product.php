<?php
ob_start();
session_start();
require_once '../db.php';
if(!isset($_SESSION['admin']))
{
    header("location: login.php");
    die(); exit();
}

if(isset($_POST['submit'])){
    $image = "";
    $tmpFilePath = $_FILES['image']['tmp_name'];
    if(!empty($_FILES['image']['name'])){
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image = uniqid(time()).'.'.$ext;
        $newFilePath = "../images/".$image;
        move_uploaded_file($tmpFilePath, $newFilePath);
    }
    $query = "INSERT into products (title, price, category, image, product_number, sku, description, sub_category) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $_POST['title'], PDO::PARAM_STR);
    $stmt->bindParam(2, $_POST['price'], PDO::PARAM_STR);
    $stmt->bindParam(3, $_POST['category'], PDO::PARAM_STR);
    $stmt->bindParam(4, $image, PDO::PARAM_STR);
    $stmt->bindParam(5, $_POST['product_no'], PDO::PARAM_STR);
    $stmt->bindParam(6, $_POST['sku'], PDO::PARAM_STR);
    $stmt->bindParam(7, $_POST['description'], PDO::PARAM_STR);
    $stmt->bindParam(8, $_POST['sub_category'], PDO::PARAM_STR);
    $stmt->execute();
    $productid = $sql->lastInsertId();
    foreach($_POST['sizes'] as $size){
        if(!empty(trim($size))){
            $query = "INSERT into product_sizes (name, product_id) VALUES (?, ?)";
            $stmt = $sql->prepare($query);
            $stmt->bindParam(1, $size, PDO::PARAM_STR);
            $stmt->bindParam(2, $productid, PDO::PARAM_STR);
            $stmt->execute();
        }
    }
    
    $msg = "<div class='alert alert-success'>Product added successfully.</div>";
}

if(isset($_POST['getSubCategory'])){
    $query = "select * from sub_categories where category_id=?";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $_POST['category_id'], PDO::PARAM_STR);
    $stmt->execute();
    $subcategories = $stmt->fetchAll();
    $data = '<option value="">Select</option>';
    foreach($subcategories as $subcategory){
        $data.='<option value="'.$subcategory['id'].'">'.$subcategory['name'].'</option>';
    }
    echo $data;
    die();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Add New Product</title>
    <?php include 'head.php'; ?>
    <style>
        .form-control{
            border-radius: 0;
        }
    </style>
</head>

<body>
    <?php include 'nav.php'; ?>
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-md-6">
                <h3 class="mt-5 mb-3 text-white fancy">Add New Product</h3>
                <div class=" mb-3 ">
                    <div class="text-white">
                        <?php if(isset($msg)){ echo $msg; } ?>
                        <form method="post" class="" enctype="multipart/form-data">
                           <div class="mb-3">
                                <label for="">Title*</label>
                                <input required type="text" class="form-control" name="title">
                            </div>
                            <div class="mb-3">
                                <label for="">Price*</label>
                                <input required type="number" class="form-control" name="price">
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
                                <label for="">Sub Category*</label>
                                <select name="sub_category" class="form-control" id="subCategorySelect">
                                    <option value="">Select</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="">Image*</label>
                                <input required type="file" class="ml-2" name="image">
                            </div>
                            <div class="mb-3">
                                <label for="">Product No.*</label>
                                <input required type="text" class="form-control" name="product_no">
                            </div>
                            <div class="mb-3">
                                <label for="">SKU*</label>
                                <input required type="text" class="form-control" name="sku">
                            </div>
                            
                            <div class="mb-3">
                                <label for="">Description*</label>
                                <textarea required name="description" class="form-control" id="" cols="30" rows="5"></textarea>
                            </div>
                            
                            <div class="mb-5">
                                <label for="">Sizes*</label>
                                <div class="mb-2" id="addAnotherDiv">
                                    <input required type="text" name="sizes[]" class="form-control">
                                </div>
                                <button type="button" id="addAnother" class="btn btn-sm btn-success">Add Another Size</button>
                            </div>
                            
                            <div class="">
                                <button name="submit" class="btn btn-primary btn-block">Submit</button>
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
    
    $(document).on('click', '#addAnother', function(){
        $("#addAnotherDiv").append('<input type="text" name="sizes[]" class="form-control mt-2">');
    }); 
    
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
    })
    
           
</script>
</body>

</html>