<?php
ob_start();
session_start();
require_once '../db.php';
if(!isset($_SESSION['admin'])){
    header("location:login.php");
    die();
}

if(isset($_POST['delete'])){
    $query = "DELETE from products where id=?";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $_POST['product_id'], PDO::PARAM_STR);
    $stmt->execute();
    $msg = "<div class='alert alert-success'>Product removed successfully.</div>";
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Products</title>
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
        
        label, p, th, td{
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
        <div class="row  mb-5">
            <div class="col-md-12">
                <h1 class="mt-5 fancy text-white mb-3">All Products
                <span class="float-right">
                    <a class="btn btn-success btn-sm" href="add-product.php">Add New Product</a>
                </span>
                </h1>
                <?php if(isset($msg)){ echo $msg; } ?>
                <table class="table table-bordered">
                <tr class=" text-white">
                    <th>Image</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
                <?php 
                    $stmt = $sql->prepare("select a.*, b.name as category, c.name as sub_category, (select AVG(rating) from reviews where product_id=a.id) as rating from products as a left join categories as b on a.category=b.id left join sub_categories as c on a.sub_category=c.id order by a.id desc");
                    $stmt->execute();
                    $products = $stmt->fetchAll();
                    foreach($products as $product){
                        $rating = empty($product['rating'])?0:$product['rating'];
                        $rating = number_format($rating, 2);
                ?>
                <tr>
                    <td><img width="100%" height="80" style="object-fit:contain" src="../images/<?php echo $product['image']; ?>" alt=""></td>
                    <td width="35%"><?php echo $product['title']; ?> <br> <i class="fa fa-star"></i> <?php echo $rating; ?></td>
                    <td width="20%"><?php echo $product['category']; ?> 
                    <?php echo empty($product['sub_category'])?'':'&#8226; '.$product['sub_category']; ?>
                    </td>
                    <td width="10%">$<?php echo $product['price']; ?></td>
                    <td>
                        
                        <form action="" method="post">
                            
                            <a href="edit-product.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-primary">Edit Product</a>
                            <a class="btn btn-sm btn-success" href="edit-sizes.php?product_id=<?php echo $product['id'] ?>">Edit Sizes</a>
                            <input type="hidden" name="product_id" value="<?php echo $product['id'] ?>">
                            <button class="btn btn-danger btn-sm" name="delete">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php } ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>