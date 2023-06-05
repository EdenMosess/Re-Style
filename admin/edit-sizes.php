<?php
ob_start();
session_start();
require_once '../db.php';
if(!isset($_SESSION['admin'])){
    header("location:login.php");
    die();
}

if(isset($_GET['product_id'])){
    $productid = $_GET['product_id'];
    $stmt = $sql->prepare("select * from products where id = ?");
    $stmt->bindParam(1, $productid, PDO::PARAM_STR);
    $stmt->execute();
    $product = $stmt->fetch(); 
    if($stmt->rowCount()==0){
        header("location:index.php");
        die();
    }
}else{
    header("location:products.php");
    die();
}

if(isset($_POST['submit'])){
    $query = "INSERT into product_sizes set name=?, product_id=?";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $_POST['name'], PDO::PARAM_STR);
    $stmt->bindParam(2, $productid, PDO::PARAM_STR);
    $stmt->execute();
    $msg = "<div class='alert alert-success'>Size added successfully.</div>";
}

if(isset($_POST['update'])){
    $query = "UPDATE product_sizes set name=? where id=?";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $_POST['name'], PDO::PARAM_STR);
    $stmt->bindParam(2, $_POST['size_id'], PDO::PARAM_STR);
    $stmt->execute();
    $msg = "<div class='alert alert-success'>Size updated successfully.</div>";
}

if(isset($_POST['delete'])){
    $query = "DELETE from product_sizes where id=?";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $_POST['size_id'], PDO::PARAM_STR);
    $stmt->execute();
    $msg = "<div class='alert alert-success'>Size removed successfully.</div>";
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Sizes</title>
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
        
        p, th, td{
            color: white;
        }
        
        .form-control{
            border-radius: 0;
        }
        
        .table, th, td{
            border: 0 !important;
            padding: 0 !important;
        }
        
        th, td{
            
            border:3px solid rgba(255,255,255,.08) !important;
            padding: 8px !important;
        }
        
        td{
            padding-bottom: 10px !important;
        }
        
        .btn-primary{
            border-radius: 0;
            background-color: #d26e4b;
            border-color: #d26e4b;
        }
        
        .fa-star{
            color: orange;
        }
        
    </style>
    
</head>

<body>
    <?php include 'nav.php'; ?>
    <div class="container">
        <div class="row justify-content-center  mb-5">
            <div class="col-md-10">
                <h3 class="mt-5 fancy text-white mb-3">Sizes for [<?php echo $product['title']; ?>]
                <span class="float-right">
                    <a href="products.php" class="btn btn-primary btn-sm">Go Back</a>
                    <button data-toggle="modal" data-target="#exampleModal" class="btn btn-success btn-sm" >Add New</button>
                </span>
                </h3>
                <?php if(isset($msg)){ echo $msg; } ?>
                <table class="table table-bordered">
                <tr class=" text-white">
                    <th>Name</th>
                    
                    <th>Actions</th>
                </tr>
                <?php 
                    $stmt = $sql->prepare("select * from product_sizes where product_id=?");
                    $stmt->bindParam(1, $productid, PDO::PARAM_STR);
                    $stmt->execute();
                    $sizes = $stmt->fetchAll();
                    foreach($sizes as $size){
                        
                ?>
                <tr>
                    <td width="70%"><?php echo $size['name']; ?></td>
                    
                    <td>
                        
                        <form action="" method="post" onsubmit="return confirm('Are you sure?')">
                            <button type="button" data-name="<?php echo $size['name']; ?>" data-id="<?php echo $size['id']; ?>" class="btn edit btn-sm btn-primary">Edit</button>
                            <input type="hidden" name="size_id" value="<?php echo $size['id'] ?>">
                            <button class="btn btn-danger btn-sm" name="delete">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php } ?>
                </table>
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Add Size</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="" method="post">
                                    <div class="form-group">
                                        <label for="">Size Name*</label>
                                        <input type="text" class="form-control" name="name" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
                
                <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Edit Size</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="" method="post">
                                    <div class="form-group">
                                        <label for="">Size Name*</label>
                                        <input type="text" class="form-control" name="name" required id="edit_name">
                                        <input type="hidden" class="form-control" name="size_id" required id="edit_id">
                                    </div>
                                    
                                    <div class="form-group">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary" name="update">Update</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.2/js/bootstrap.min.js"></script>
    <script>
        $(".edit").click(function(){
            var id = $(this).data('id');
            var name = $(this).data('name');
            $("#edit_id").val(id);
            $("#edit_name").val(name);
            $("#exampleModal2").modal("show");
        })
    </script>
</body>
</html>