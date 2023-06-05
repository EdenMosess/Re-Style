<?php
require_once 'auth.php';
if(isset($_POST['getList'])){
    $query = "select * from products order by id desc";
    $stmt = $sql->prepare($query);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($products, JSON_PRETTY_PRINT);
    die();
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>API</title>
    <?php include 'head.php'; ?>
    <style>
        body{
            background: #e9fbeb;
        }
    </style>
</head>

<body>
    <?php include 'nav.php'; ?>
    <div class="container">
        <div class="row justify-content-center mt-3 mb-5">
           
            <div class="col-md-9">
                
                <h3 class="mb-5 text-muted"><b>API</b></h3>
                <div class="card mb-3 shadow">
                    <div class="card-body">
                        <?php if(isset($msg)){ echo $msg; } ?>
                        <textarea readonly class="form-control mb-3" id="list" cols="30" rows="10"></textarea>
                        <button id="getList" class="btn btn-primary px-4">Get Products List</button>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
<script>
$("#list").focus(function() { $(this).select(); } );   

$("#getList").click(function() {
    $.ajax({
        url: 'api.php',
        type: 'POST',
        data: {
            getList: 1,
        },
        success: function(data) {
           $("#list").val(data);
        },
    });
});    
    
</script>
</html>