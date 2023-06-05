<?php
require_once 'auth.php';

if(isset($_POST['save'])){
    $query = "insert into user_cards (cardholder_name, card_number, security_code, validity, user_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $_POST['cardholder_name'], PDO::PARAM_STR);
    $stmt->bindParam(2, $_POST['card_number'], PDO::PARAM_STR);
    $stmt->bindParam(3, $_POST['security_code'], PDO::PARAM_STR);
    $stmt->bindParam(4, $_POST['validity'], PDO::PARAM_STR);
    $stmt->bindParam(5, $_SESSION['user'], PDO::PARAM_STR);
    $stmt->execute();
    $msg = "<div class='alert alert-success'>Card added successfully.</div>";
}

if(isset($_POST['update'])){
    $query = "update user_cards set cardholder_name=?, card_number=?, security_code=?, validity=? where id=?";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $_POST['cardholder_name'], PDO::PARAM_STR);
    $stmt->bindParam(2, $_POST['card_number'], PDO::PARAM_STR);
    $stmt->bindParam(3, $_POST['security_code'], PDO::PARAM_STR);
    $stmt->bindParam(4, $_POST['validity'], PDO::PARAM_STR);
    $stmt->bindParam(5, $_POST['card_id'], PDO::PARAM_STR);
    $stmt->execute();
    $msg = "<div class='alert alert-success'>Card updated successfully.</div>";
}

if(isset($_POST['remove'])){
    $query = "delete from user_cards where id=?";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $_POST['card_id'], PDO::PARAM_STR);
    $stmt->execute();
    $msg = "<div class='alert alert-success'>Card removed successfully.</div>";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Cards Management</title>
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
                <h3 class="mb-5  text-muted"><b>Cards Management</b> <span class="float-right"><button id="add_address" class="btn btn-primary">Add Card</button></span></h3>
                
                <div class="card mb-5 shadow" id="add_address_card" style="display:none">
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="">Card Holder Name</label>
                                        <input type="text" class="form-control" name="cardholder_name" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="">Card Number</label>
                                        <input type="text" class="form-control" name="card_number" required onkeypress="return isNumber(event)" maxlength="16">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="">Security Code</label>
                                        <input type="text" class="form-control" name="security_code" required onkeypress="return isNumber(event)" maxlength="3">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="">Validity</label>
                                        <input type="text" class="form-control" name="validity" required maxlength="4" onkeypress="return isNumber(event)" placeholder="Example: MMYY">
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
                                    $stmt = $sql->prepare("select * from user_cards where user_id=? order by id desc");
                                    $stmt->bindParam(1, $_SESSION['user'], PDO::PARAM_STR);
                                    $stmt->execute();
                                    $cards = $stmt->fetchAll();
                                    foreach($cards as $card){
                                ?>
                                <div class="row mb-3">

                                    <div class="col-md-9">
                                        <div class="p-2 product-data">

                                            <form action="" method="post">
                                                <div class="row mb-3">
                                                    <div class="col-md-12">
                                                        <div class="mb-3">
                                                            <label for="">Card Holder Name</label>
                                                            <input type="text" class="form-control" name="cardholder_name" value="<?php echo $card['cardholder_name']; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="mb-3">
                                                            <label for="">Card Number</label>
                                                            <input type="text" class="form-control" name="card_number" value="<?php echo $card['card_number']; ?>" maxlength="16" onkeypress="return isNumber(event)">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="">Security Code</label>
                                                            <input type="text" class="form-control" name="security_code" value="<?php echo $card['security_code']; ?>" onkeypress="return isNumber(event)" maxlength="3">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="">Validity</label>
                                                            <input type="text" class="form-control" name="validity" value="<?php echo $card['validity']; ?>" onkeypress="return isNumber(event)" maxlength="4" placeholder="Example: MMYY">
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="card_id" value="<?php echo $card['id']; ?>">
                                                <button name="update" type="submit" class="btn btn-primary w-100">Update</button>
                                            </form>

                                        </div>

                                    </div>
                                    <div class="col-md-3">
                                        <div class="float-right">
                                            <form action="" method="post" onsubmit="return confirm('Are you sure want to remove this card?')">
                                                <input type="hidden" name="card_id" value="<?php echo $card['id']; ?>">
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
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
<script>
    $("#add_address").click(function(){
        $("#add_address_card").toggle();
    })
    
    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }
    
</script>
</html>