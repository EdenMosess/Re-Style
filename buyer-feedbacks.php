<?php
include 'auth.php';
$query = "select AVG(score) as seller_score from feedbacks where seller_id=?";
$stmt = $sql->prepare($query);
$stmt->bindParam(1, $userid, PDO::PARAM_STR);
$stmt->execute();
$score = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Feedbacks</title>
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
                  <a href="ad-management.php" class="btn btn-outline-primary">Ad Management</a>
                  <a href="upload-item.php" class="btn btn-outline-primary">Upload an item</a>
                  <a href="buyer-feedbacks.php" class="btn btn-primary">Buyer Feedbacks</a>
                  
                </div>
                <h3 class="mb-5  text-muted"><b>Buyer Feedbacks</b></h3>
                <h3 class="mb-5  text-muted"><b>AVG Score: <?php echo number_format($score['seller_score'], 2); ?></b></h3>

                <?php if(isset($msg)){ echo $msg; } ?>
                <div class="row">
                    <div class="col-md-12">

                        <?php 
                            $stmt = $sql->prepare("select a.*, b.firstname, b.lastname from feedbacks as a left join users as b on a.user_id=b.id where a.seller_id=?");
                            $stmt->bindParam(1, $userid, PDO::PARAM_STR);
                            $stmt->execute();
                            $feedbacks = $stmt->fetchAll();
                            foreach($feedbacks as $feedback){
                        ?>
                        <div class="card mb-3 shadow">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="p-2 product-data">
                                            <h4>Seller Details: </h4>
                                            <p>Name: <?php echo $feedback['firstname']; ?> <?php echo $feedback['lastname']; ?></p>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="p-2 product-data">
                                            
                                            <div class="mb-3">
                                                <p class="mb-0">Satisfaction: <b><?php echo $feedback['satisfaction']; ?></b></p>
                                            </div>
                                            <div class="mb-3">
                                                <p class="mb-0">Topic: <b><?php echo $feedback['topic']; ?></b></p>
                                            </div>
                                            <div class="mb-3">
                                                <p class="mb-0">Comments: <b><?php echo $feedback['comments']; ?></b></p>
                                            </div>
                                            <div class="mb-0">
                                                <p class="mb-0">Score: <b><?php echo $feedback['score']; ?></b></p>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>

</html>