<?php
require_once 'auth.php';
if(isset($_POST['submit'])){
    $day = $_POST['day']<10?'0'.$_POST['day']:$_POST['day'];
    $month = $_POST['month']<10?'0'.$_POST['month']:$_POST['month'];
    $year = $_POST['year'];
    $dob = $year.'-'.$month.'-'.$day;

    $query = "update users set firstname=?, lastname=?, dob=?, phone=?, gender=? where id=?";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $_POST['firstname'], PDO::PARAM_STR);
    $stmt->bindParam(2, $_POST['lastname'], PDO::PARAM_STR);
    $stmt->bindParam(3, $dob, PDO::PARAM_STR);
    $stmt->bindParam(4, $_POST['phone'], PDO::PARAM_STR);
    $stmt->bindParam(5, $_POST['gender'], PDO::PARAM_STR);
    $stmt->bindParam(6, $userid, PDO::PARAM_STR);
    $stmt->execute();
    
    $query = "select * from users where id = ?";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $userid, PDO::PARAM_INT);
    $stmt->execute();
    $userdata = $stmt->fetch();
    $dob = explode('-', $userdata['dob']);
    $msg = "<div class='alert alert-success'>Profile updated successfully.</div>";
}

$dob = explode('-', $userdata['dob']);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Update Personal Details</title>
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
                <div class="btn-group w-100 mb-5" role="group" aria-label="Basic example">
                    <a href="update-personal-details.php" class="btn btn-outline-primary">Personal Details</a>
                    <a href="addresses.php" class="btn btn-outline-primary">Addresses</a>
                    <a href="cards.php" class="btn btn-outline-primary">Cards</a>
                    <a href="feedbacks.php" class="btn btn-outline-primary">Feedbacks</a>
                    <a href="personalization.php" class="btn btn-outline-primary">Personalization</a>
                </div>
                <h3 class="mb-5 text-muted"><b>Update Personal Details</b></h3>
                <div class="card mb-3 shadow">
                    <div class="card-body">
                        <?php if(isset($msg)){ echo $msg; } ?>
                        <form method="post" class="">
                           <div class="row mb-3">
                               <div class="col-md-6">
                                   <div class="mb-3">
                                       <label for="">First Name*</label>
                                       <input required type="text" class="form-control" name="firstname" placeholder="Enter First Name" value="<?php echo $userdata['firstname']; ?>">
                                   </div>
                               </div>
                               <div class="col-md-6">
                                   <div class="mb-3">
                                       <label for="">Last Name*</label>
                                       <input required type="text" class="form-control" name="lastname" placeholder="Enter Last Name" value="<?php echo $userdata['lastname']; ?>">
                                   </div>
                               </div>
                           </div>
                           
                           <div class="row mb-3">
                               
                               <div class="col-md-6">
                                   <div class="mb-3">
                                        <label for="">Phone Number*</label>
                                        <input required type="text" class="form-control" name="phone" placeholder="Enter Phone" value="<?php echo $userdata['phone']; ?>">
                                    </div>
                               </div>
                               <div class="col-md-6">
                                    <label for="">Date of Birth*</label>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input required name="day" type="number" class="form-control" placeholder="DD" min="1" max="31" value="<?php echo $dob[2]; ?>">
                                        </div>
                                        <div class="col-md-4">
                                            <input required name="month" type="number" class="form-control" placeholder="MM" min="1" max="12" value="<?php echo $dob[1]; ?>">
                                        </div>
                                        <div class="col-md-4">
                                            <input required name="year" type="number" class="form-control" placeholder="YYYY" min="1960" max="2020" value="<?php echo $dob[0]; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="">Gender*</label>
                                        <select name="gender" required class="form-control" id="">
                                            <option value="">Select</option>
                                            <option <?php if($userdata['gender']=='male'){ echo 'selected'; } ?> value="male">Male</option>
                                            <option <?php if($userdata['gender']=='female'){ echo 'selected'; } ?> value="female">Female</option>
                                            <option <?php if($userdata['gender']=='other'){ echo 'selected'; } ?> value="other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                
                                
                            </div>
                            
                            
                            <div class="float-right">
                                <button name="submit" class="btn btn-primary px-4">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
</html>