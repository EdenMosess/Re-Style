<?php
ob_start();
session_start();
require_once 'db.php';
if(!isset($_SESSION['user'])){
    header('location:login.php');
    die();
}

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
        $msg = "<div class='alert alert-success'>Design received. We will be in contact soon.</div>";
    }else{
        $msg = "<div class='alert alert-danger'>Sorry please upload an image file.</div>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo $ln['upload_design']; ?></title>
    <?php include 'head.php'; ?>
    <style>
        body{
            background: #5b5b5b;
        }
        
        label{
            color: white;
        }
        .form-control{
            border-radius: 0;
        }
        
        .hero-image{
            width: 100%;
            height: 450px;
            object-fit: cover;
        }
        
    </style>
</head>

<body>
    <?php include 'nav.php'; ?>
    
    <div class="container">
        <div class="row justify-content-center mb-5 mt-3">
            <div class="col-md-6">
               
                <h1 class="mb-3 text-white text-center fancy"><?php echo $ln['upload_design']; ?></h1>
                <div class="">
                    <div class="">
                        <?php if(isset($msg)){ echo $msg; } ?>
                        <form action="" method="post" enctype="multipart/form-data" id="uploadForm">
                            <div class="form-group">
                                <label for=""><?php echo $ln['select_design']; ?>*</label><br>
                                <input required type="file" name="image" class="">
                            </div>
                            <div class="form-group">
                                <label for=""><?php echo $ln['description']; ?>*</label>
                                <textarea required name="description" class="form-control" id="" cols="30" rows="10"></textarea>
                            </div>
                            <button type="submit" name="uploadDesign" class="ml-0 btn btn-secondary"><?php echo $ln['upload']; ?></button>
                        </form>
                    </div>
                </div>
			</div>
        </div>
    </div>    
    <?php include 'footer.php'; ?>    
</body>
</html>