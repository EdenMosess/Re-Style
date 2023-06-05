<?php
ob_start();
session_start();
require_once '../db.php';
if(!isset($_SESSION['admin'])){
    header("location:login.php");
    die();
}

if(isset($_GET['id'])){
    $categoryid = $_GET['id'];
    $stmt = $sql->prepare("select * from categories where id = ?");
    $stmt->bindParam(1, $categoryid, PDO::PARAM_STR);
    $stmt->execute();
    $categorydata = $stmt->fetch(); 
    if($stmt->rowCount()==0){
        header("location:index.php");
        die();
    }
}else{
    header("location:products.php");
    die();
}

if(isset($_POST['submit'])){
    $query = "INSERT into sub_categories set name=?, category_id=?";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $_POST['name'], PDO::PARAM_STR);
    $stmt->bindParam(2, $categoryid, PDO::PARAM_STR);
    $stmt->execute();
    $msg = "<div class='alert alert-success'>Category added successfully.</div>";
}

if(isset($_POST['update'])){
    $query = "UPDATE sub_categories set name=? where id=?";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $_POST['name'], PDO::PARAM_STR);
    $stmt->bindParam(2, $_POST['category_id'], PDO::PARAM_STR);
    $stmt->execute();
    $msg = "<div class='alert alert-success'>Category updated successfully.</div>";
}

if(isset($_POST['delete'])){
    $query = "DELETE from sub_categories where id=?";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $_POST['category_id'], PDO::PARAM_STR);
    $stmt->execute();
    $msg = "<div class='alert alert-success'>Category removed successfully.</div>";
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Sub Categories</title>
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
                <h1 class="mt-5 fancy text-white mb-3">Sub Categories for [<?php echo $categorydata['name']; ?>]
                <span class="float-right">
                    <a href="categories.php" class="btn btn-primary btn-sm">Go Back</a>
                    <button data-toggle="modal" data-target="#exampleModal" class="btn btn-success btn-sm" >Add New</button>
                </span>
                </h1>
                <?php if(isset($msg)){ echo $msg; } ?>
                <table class="table table-bordered">
                <tr class=" text-white">
                    <th>Name</th>
                    
                    <th>Actions</th>
                </tr>
                <?php 
                    $stmt = $sql->prepare("select * from sub_categories where category_id=?");
                    $stmt->bindParam(1, $categoryid, PDO::PARAM_STR);
                    $stmt->execute();
                    $categories = $stmt->fetchAll();
                    foreach($categories as $category){
                        
                ?>
                <tr>
                    <td width="70%"><?php echo $category['name']; ?></td>
                    
                    <td>
                        
                        <form action="" method="post" onsubmit="return confirm('Are you sure?')">
                            <button type="button" data-name="<?php echo $category['name']; ?>" data-id="<?php echo $category['id']; ?>" class="btn edit btn-sm btn-primary">Edit</button>
                            <input type="hidden" name="category_id" value="<?php echo $category['id'] ?>">
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
                                <h5 class="modal-title" id="exampleModalLabel">Add Sub Category</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="" method="post">
                                    <div class="form-group">
                                        <label for="">Sub Category Name*</label>
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
                                <h5 class="modal-title" id="exampleModalLabel">Edit Sub Category</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="" method="post">
                                    <div class="form-group">
                                        <label for="">Sub Category Name*</label>
                                        <input type="text" class="form-control" name="name" required id="edit_name">
                                        <input type="hidden" class="form-control" name="category_id" required id="edit_id">
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