<?php
require_once 'auth.php';

if(isset($_POST['save'])){
    $query = "insert into user_addresses (name, street, city, postal_code, user_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $_POST['name'], PDO::PARAM_STR);
    $stmt->bindParam(2, $_POST['street'], PDO::PARAM_STR);
    $stmt->bindParam(3, $_POST['city'], PDO::PARAM_STR);
    $stmt->bindParam(4, $_POST['postal_code'], PDO::PARAM_STR);
    $stmt->bindParam(5, $_SESSION['user'], PDO::PARAM_STR);
    $stmt->execute();
    $msg = "<div class='alert alert-success'>Address added successfully.</div>";
}

if(isset($_POST['update'])){
    $query = "update user_addresses set name=?, street=?, city=?, postal_code=? where id=?";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $_POST['name'], PDO::PARAM_STR);
    $stmt->bindParam(2, $_POST['street'], PDO::PARAM_STR);
    $stmt->bindParam(3, $_POST['city'], PDO::PARAM_STR);
    $stmt->bindParam(4, $_POST['postal_code'], PDO::PARAM_STR);
    $stmt->bindParam(5, $_POST['address_id'], PDO::PARAM_STR);
    $stmt->execute();
    $msg = "<div class='alert alert-success'>Address updated successfully.</div>";
}

if(isset($_POST['remove'])){
    $query = "delete from user_addresses where id=?";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $_POST['address_id'], PDO::PARAM_STR);
    $stmt->execute();
    $msg = "<div class='alert alert-success'>Address removed successfully.</div>";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Address Management</title>
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
                    <a href="update-personal-details.php" class="btn btn-outline-primary">Personal Details</a>
                    <a href="addresses.php" class="btn btn-outline-primary">Addresses</a>
                    <a href="cards.php" class="btn btn-outline-primary">Cards</a>
                    <a href="feedbacks.php" class="btn btn-outline-primary">Feedbacks</a>
                    <a href="personalization.php" class="btn btn-outline-primary">Personalization</a>
                </div>
                <h3 class="mb-5  text-muted"><b>Address Management</b> <span class="float-right"><button id="add_address" class="btn btn-primary">Add Address</button></span></h3>
                
                <div class="card mb-5 shadow" id="add_address_card" style="display:none">
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="">Name</label>
                                        <input type="text" class="form-control" name="name" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="">Street</label>
                                        <input type="text" class="form-control" name="street" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="">City</label>
                                        <input type="text" class="form-control" name="city" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="">Postal Code</label>
                                        <input type="text" class="form-control" name="postal_code" required>
                                    </div>
                                </div>
                            </div>
                            <button name="save" type="submit" class="btn btn-primary w-100">Save</button>
                        </form>
                    </div>
                </div>
                
                
                <div class="card mb-3 shadow">
                    <div class="card-body">
                        <?php if(isset($msg)){ echo $msg; } ?>
                        <div class="row">
                            <div class="col-md-12">
                                <?php 
                                    $stmt = $sql->prepare("select * from user_addresses where user_id=? order by id desc");
                                    $stmt->bindParam(1, $_SESSION['user'], PDO::PARAM_STR);
                                    $stmt->execute();
                                    $addresses = $stmt->fetchAll();
                                    foreach($addresses as $address){
                                ?>
                                <div class="row mb-3">

                                    <div class="col-md-9">
                                        <div class="p-2 product-data">

                                            <form action="" method="post">
                                                <div class="row mb-3">
                                                    <div class="col-md-12">
                                                        <div class="mb-3">
                                                            <label for="">Name</label>
                                                            <input type="text" class="form-control" name="name" value="<?php echo $address['name']; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="mb-3">
                                                            <label for="">Street</label>
                                                            <input type="text" class="form-control" name="street" value="<?php echo $address['street']; ?>">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="">City</label>
                                                            <input type="text" class="form-control" name="city" value="<?php echo $address['city']; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="">Postal Code</label>
                                                            <input type="text" class="form-control" name="postal_code" value="<?php echo $address['postal_code']; ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="address_id" value="<?php echo $address['id']; ?>">
                                                <button name="update" type="submit" class="btn btn-primary w-100">Update</button>
                                            </form>

                                        </div>

                                    </div>
                                    <div class="col-md-3">
                                        <div class="float-right">
                                            <form action="" method="post" onsubmit="return confirm('Are you sure want to remove this address?')">
                                                <input type="hidden" name="address_id" value="<?php echo $address['id']; ?>">
                                                <button class="btn btn-danger " name="remove"><i class="fa fa-trash"></i> </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>

<script>
    $("#add_address").click(function(){
        $("#add_address_card").toggle();
    })
    
</script>
</html>