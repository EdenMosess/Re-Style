<?php
ob_start();
session_start();
require_once 'db.php';
if(isset($_GET['category'])){ 
    $query = "select * from categories where id=?";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $_GET['category'], PDO::PARAM_STR);
    $stmt->execute();
    $shopcategory = $stmt->fetch();
}

if(isset($_GET['sub_category'])){ 
    $query = "select * from sub_categories where id=?";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $_GET['sub_category'], PDO::PARAM_STR);
    $stmt->execute();
    $shopsubcategory = $stmt->fetch();
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
        $msg = "<div class='alert alert-success'>Order received. We will be in contact soon.</div>";
    }else{
        $msg = "<div class='alert alert-danger'>Sorry please upload an image file.</div>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo $ln['shop']; ?></title>
    <?php include 'head.php'; ?>
    <style>
        .divider{
            opacity: .35;
            margin-left: 5px;
            margin-right: 5px;
        }
    </style>
</head>

<body>
    <?php include 'nav.php'; ?>
    
    <div class="container">
        <div class="row mb-5 mt-5">
          <div class="col-md-12 mb-5">
              <h2 class="text-white fancy"><?php echo $ln['home']; ?> <span class="divider">/</span> <?php echo $ln['shop']; ?> <?php if(isset($_GET['category'])){ ?> <span class="divider">/</span> <?php echo strtoupper($shopcategory['name']); } ?> <?php if(isset($_GET['sub_category'])){ ?> <span class="divider">/</span> <?php echo strtoupper($shopsubcategory['name']); } ?>
              <span class="float-right">
                  <form action="" id="filter" style="display:flex">
                     <select name="category" class="form-control mr-3" id="categorySelect" onchange="$('#filter').submit()">
                          <option value="">Category</option>
                          <?php foreach($categories as $category){ ?>
                          <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                          <?php } ?>
                      </select>
                      <select name="filter" class="form-control" id="filterSelect" onchange="$('#filter').submit()">
                          <option value="">Filter</option>
                          <option value="price_asc">Price Low to High</option>
                          <option value="price_desc">Price High to Low</option>
                          <option value="rating_asc">Rating Low to Low</option>
                          <option value="rating_desc">Rating High to Low</option>
                      </select>
                      
                  </form>
              </span>
              </h2>
          </div>
           <div class="col-md-12">
               <?php if(isset($msg)){ echo $msg; } ?>
           </div>
            <?php 
                $filterQuery = "order by id desc";
                if(isset($_GET['filter'])){
                    $filter = $_GET['filter'];
                    if($filter=='price_asc'){
                        $filterQuery = "order by a.price asc";
                    }elseif($filter=='price_desc'){
                        $filterQuery = "order by a.price desc";
                    }elseif($filter=='rating_desc'){
                        $filterQuery = "order by rating desc";
                    }elseif($filter=='rating_asc'){
                        $filterQuery = "order by rating asc";
                    }
                }
            
                if(isset($_GET['category']) && isset($_GET['sub_category'])){
                    $stmt = $sql->prepare("select a.*, b.name as category, c.name as sub_category, (select AVG(rating) from reviews where product_id=a.id) as rating from products as a left join categories as b on a.category=b.id left join sub_categories as c on a.sub_category=c.id where a.category=? AND a.sub_category=? $filterQuery");
                    $stmt->bindParam(1, $_GET['category'], PDO::PARAM_STR);
                    $stmt->bindParam(2, $_GET['sub_category'], PDO::PARAM_STR);
                }elseif(isset($_GET['category']) && !isset($_GET['sub_category'])){
                    $stmt = $sql->prepare("select a.*, b.name as category, c.name as sub_category, (select AVG(rating) from reviews where product_id=a.id) as rating from products as a left join categories as b on a.category=b.id left join sub_categories as c on a.sub_category=c.id where a.category=? $filterQuery");
                    $stmt->bindParam(1, $_GET['category'], PDO::PARAM_STR);
                }else{
                    $stmt = $sql->prepare("select a.*, b.name as category, c.name as sub_category, (select AVG(rating) from reviews where product_id=a.id) as rating from products as a left join categories as b on a.category=b.id left join sub_categories as c on a.sub_category=c.id $filterQuery");
                }
                
                $stmt->execute();
                $products = $stmt->fetchAll();
                foreach($products as $product){
                    $title = strlen($product['title'])>=40 ? substr($product['title'], 0, 37).'....' : $product['title'];
                    $rating = empty($product['rating'])?0:$product['rating'];
                    $rating = number_format($rating, 2);
            ?>
            <div class="col-md-4 mb-5 text-center">
                <a href="product.php?id=<?php echo $product['id']; ?>"><img class="product-image" src="images/<?php echo $product['image']; ?>" alt=""></a>
                <div class="p-2 product-data">
                    <small class="text-white category"><?php echo $product['category']; ?> <?php echo empty($product['sub_category'])?'':'&#8226; '.$product['sub_category']; ?></small>
                    <a href="product.php?id=<?php echo $product['id']; ?>"><p class="text-white mb-2 product-name"><?php echo $title; ?></p></a>
                    <p class="text-white mb-0"><i class="fa fa-star"></i> <?php echo $rating; ?></p>
                    <p class="text-white"><b>$<?php echo number_format($product['price'], 2); ?></b></p>
                    <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-secondary btn-sm">View Product</a>
                </div>
            </div>
            <?php } ?>
        </div>
        
        <div class="modal fade" id="guideModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><?php echo $ln['size_guide']; ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-0">
                        <img class="img-fluid" src="images/guide.jpg" alt="">
                    </div>
                    
                </div>
            </div>
        </div>
        
        
        
    </div>
    <input type="hidden" id="product_quantity" value="1">
    <?php include 'footer.php'; ?>
    <script>
    <?php if(isset($_GET['filter'])){ ?>
    $("#filterSelect").val("<?php echo $_GET['filter']; ?>")
    <?php } ?>
        
    <?php if(isset($_GET['category'])){ ?>
    $("#categorySelect").val("<?php echo $_GET['category']; ?>")
    <?php } ?>
    </script>
</body>
</html>