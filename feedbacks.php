<?php
include 'auth.php';
if(isset($_POST['satisfaction'])){
    
    $query = "INSERT into feedbacks (topic, comments, user_id, satisfaction, score, seller_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $_POST['topic'], PDO::PARAM_STR);
    $stmt->bindParam(2, $_POST['comments'], PDO::PARAM_STR);
    $stmt->bindParam(3, $userid, PDO::PARAM_STR);
    $stmt->bindParam(4, $_POST['satisfaction'], PDO::PARAM_STR);
    $stmt->bindParam(5, $_POST['score'], PDO::PARAM_STR);
    $stmt->bindParam(6, $_POST['seller_id'], PDO::PARAM_STR);
    $stmt->execute();
    $msg = "<div class='alert alert-success'>Feedback added successfully.</div>";
    
}
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
                    <a href="update-personal-details.php" class="btn btn-outline-primary">Personal Details</a>
                    <a href="addresses.php" class="btn btn-outline-primary">Addresses</a>
                    <a href="cards.php" class="btn btn-outline-primary">Cards</a>
                    <a href="feedbacks.php" class="btn btn-outline-primary">Feedbacks</a>
                    <a href="personalization.php" class="btn btn-outline-primary">Personalization</a>
                </div>
                <h3 class="mb-5  text-muted"><b>Feedbacks</b></h3>

                <?php if(isset($msg)){ echo $msg; } ?>
                <div class="row">
                    <div class="col-md-12">

                        <?php 
                            $stmt = $sql->prepare("select * from users where interest='selling' AND id!=?");
                            $stmt->bindParam(1, $userid, PDO::PARAM_STR);
                            $stmt->execute();
                            $users = $stmt->fetchAll();
                            foreach($users as $user){
                        ?>
                        <div class="card mb-3 shadow">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        
                                        <div class="">
                                            <p class="mb-0">Seller Name: <b><?php echo $user['firstname'].' '.$user['lastname']; ?></b></p>
                                            <p class="mb-0">Seller Email: <b><?php echo $user['email']; ?></b></p>
                                            <p class="mb-0">Seller Phone: <b><?php echo $user['phone']; ?></b></p>
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-1"></div>
                                    <div class="col-md-7">
                                        <div class="p-2 product-data">
                                            <?php 
                                            $stmt = $sql->prepare("select * from feedbacks where user_id=? AND seller_id=?");
                                            $stmt->bindParam(1, $userid, PDO::PARAM_STR);
                                            $stmt->bindParam(2, $user['id'], PDO::PARAM_STR);
                                            $stmt->execute();
                                            if($stmt->rowCount()==0){ ?>
                                            <p><b>Write Feedback</b></p>
                                            <form action="" method="post">
                                                <div class="form-group">
                                                    <label for="">Are you satisfied with the product?</label>
                                                    <div class="slidecontainer">
                                                      <input style="width:100%" type="range" min="1" max="5" value="5" class="satisfied" data-id="<?php echo $user['id']; ?>">
                                                      <div style="display:flex;justify-content: space-between;">
                                                          ☹️
                                                          <span id="satisfaction_<?php echo $user['id']; ?>">Excellent</span>
                                                           🙂
                                                      </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="">Choose a topic for feedback*</label>
                                                    <select name="topic" required class="form-control" id="">
                                                        <option value="">Select</option>
                                                        <option value="Suggestion">Suggestion</option>
                                                        <option value="Complement">Complement</option>
                                                        <option value="Problem">Problem</option>
                                                        <option value="I have a question">I have a question</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="">What would you like to share with us?</label>
                                                    <textarea name="comments" class="form-control" id="" cols="30" rows="3"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="">Rate this Buyer</label>
                                                    <div class="slidecontainer">
                                                      <input style="width:100%" type="range" min="1" max="10" value="5" class="score" data-id="<?php echo $user['id']; ?>">
                                                      <div class="text-center">
                                                          
                                                          <span id="score_<?php echo $user['id']; ?>">5</span>
                                                           
                                                      </div>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="satisfaction" id="satisfaction_input_<?php echo $user['id']; ?>" value="Excellent">
                                                <input type="hidden" name="score" id="score_input_<?php echo $user['id']; ?>" value="5">
                                                <input type="hidden" name="seller_id" value="<?php echo $user['id']; ?>">
                                                <button name="'submit" class="btn btn-primary">Submit Feedback</button>
                                            </form>
                                            <?php }else{ ?>
                                                
                                                <?php 
                                                    $stmt = $sql->prepare("select * from feedbacks where user_id=? AND seller_id=?");
                                                    $stmt->bindParam(1, $userid, PDO::PARAM_STR);
                                                    $stmt->bindParam(2, $user['id'], PDO::PARAM_STR);
                                                    $stmt->execute();
                                                    $feedback = $stmt->fetch();
                                                ?>
                                                
                                                <div class="mb-3">
                                                    <p class="mb-0">Satisfaction: <b><?php echo $feedback['satisfaction']; ?></b></p>
                                                </div>
                                                <div class="mb-3">
                                                    <p class="mb-0">Topic: <b><?php echo $feedback['topic']; ?></b></p>
                                                </div>
                                                <div class="mb-3">
                                                    <p class="mb-0">Comments: <b><?php echo $feedback['comments']; ?></b></p>
                                                    
                                                </div>
                                                <div class="mb-3">
                                                    <p class="mb-0">Score: <b><?php echo $feedback['score']; ?></b></p>
                                                </div>
                                            
                                            <?php } ?>
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
<script>
$(".satisfied").on('input', function(){
    var id = $(this).data('id');
    var val = $(this).val();
    var satisfaction = ['Bad', 'Satisfactory', 'Fair', 'Good', 'Excellent'];
    $("#satisfaction_"+id).html(satisfaction[val-1]);
    $("#satisfaction_input_"+id).val(satisfaction[val-1]);
})    
    
$(".score").on('input', function(){
    var id = $(this).data('id');
    var val = $(this).val();
    var scores = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'];
    $("#score_"+id).html(scores[val-1]);
    $("#score_input_"+id).val(scores[val-1]);
})  
</script>

</html>