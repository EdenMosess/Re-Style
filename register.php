<?php
ob_start();
session_start();
require_once 'db.php';
if(isset($_SESSION['user']))
{
    header("location: index.php");
    die(); exit();
}

if(isset($_POST['submit'])){
    $stmt = $sql->prepare("select * from users where email=?");
    $stmt->bindParam(1, $_POST['email'], PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch();
    if($stmt->rowCount()>0){
        $msg = "<div class='alert alert-danger'>Sorry, an account with this email address already exists.</div>";
    }else{

        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if($password!=$confirm_password){
            $msg = "<div class='alert alert-danger'>The given passwords do not match...</div>";
        }else{
            $day = $_POST['day']<10?'0'.$_POST['day']:$_POST['day'];
            $month = $_POST['month']<10?'0'.$_POST['month']:$_POST['month'];
            $year = $_POST['year'];
            $dob = $year.'-'.$month.'-'.$day;



            $options = [ 'cost' => 11];
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT, $options);
            $query = "INSERT into users (firstname, lastname, email, password, dob, phone, gender) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $sql->prepare($query);
            $stmt->bindParam(1, $_POST['firstname'], PDO::PARAM_STR);
            $stmt->bindParam(2, $_POST['lastname'], PDO::PARAM_STR);
            $stmt->bindParam(3, $_POST['email'], PDO::PARAM_STR);
            $stmt->bindParam(4, $password, PDO::PARAM_STR);
            $stmt->bindParam(5, $dob, PDO::PARAM_STR);
            $stmt->bindParam(6, $_POST['phone'], PDO::PARAM_STR);
            $stmt->bindParam(7, $_POST['gender'], PDO::PARAM_STR);
            $stmt->execute();
            $userid = $sql->lastInsertId();

            $name = 'Home';
            $query = "INSERT into user_addresses (user_id, name, street, city, postal_code) VALUES (?, ?, ?, ?, ?)";
            $stmt = $sql->prepare($query);
            $stmt->bindParam(1, $userid, PDO::PARAM_STR);
            $stmt->bindParam(2, $name, PDO::PARAM_STR);
            $stmt->bindParam(3, $_POST['street'], PDO::PARAM_STR);
            $stmt->bindParam(4, $_POST['city'], PDO::PARAM_STR);
            $stmt->bindParam(5, $_POST['postal_code'], PDO::PARAM_STR);
            $stmt->execute();

            $_SESSION['user'] = $userid;
            $_SESSION['message'] = "<div class='alert alert-success'>Account created successfully.</div>";
            header("location:select-interest.php");
            die();

        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Register</title>
    <?php include 'head.php'; ?>
    <style>
        body{
            background: #e9fbeb;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row justify-content-center mt-3 mb-5">
            <div class="col-md-8">
               <center>
                    <a href="index.php"><img width="150" src="images/logo.png" alt=""></a>
                </center>
                <h3 class="mb-3">Register</h3>
                <div class="card mb-3 shadow">
                    <div class="card-body">
                        <?php if(isset($msg)){ echo $msg; } ?>
                        <form method="post" class="">
                           <div class="row mb-3">
                               <div class="col-md-6">
                                   <div class="mb-3">
                                       <label for="">First Name*</label>
                                       <input required type="text" class="form-control" name="firstname" placeholder="Enter First Name">
                                   </div>
                               </div>
                               <div class="col-md-6">
                                   <div class="mb-3">
                                       <label for="">Last Name*</label>
                                       <input required type="text" class="form-control" name="lastname" placeholder="Enter Last Name">
                                   </div>
                               </div>
                           </div>

                           <div class="row mb-3">
                               <div class="col-md-6">
                                   <div class="mb-3">
                                        <label for="">Email*</label>
                                        <input required type="email" class="form-control" name="email" placeholder="Enter Email">
                                    </div>
                               </div>
                               <div class="col-md-6">
                                   <div class="mb-3">
                                        <label for="">Phone Number*</label>
                                        <input required type="text" class="form-control" name="phone" placeholder="Enter Phone" pattern="[0-9]{10}">
                                    </div>
                               </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="">Password*</label>
                                        <input id="password" required type="password" class="form-control mb-2" name="password" placeholder="Enter Password">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="">Confirm Password*</label>
                                        <input required type="password" class="form-control mb-2" name="confirm_password" placeholder="Confirm Password">
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="">Gender*</label>
                                        <select name="gender" required class="form-control" id="">
                                            <option value="">Select</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Date of Birth*</label>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input required name="day" type="number" class="form-control" placeholder="DD" min="1" max="31">
                                        </div>
                                        <div class="col-md-4">
                                            <input required name="month" type="number" class="form-control" placeholder="MM" min="1" max="12">
                                        </div>
                                        <div class="col-md-4">
                                            <input required name="year" type="number" class="form-control" placeholder="YYYY" min="1960" max="2020">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="">Street</label>
                                        <input type="text" class="form-control" name="street">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="">City</label>
                                        <input type="text" class="form-control" name="city">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="">Postal Code</label>
                                        <input type="number" class="form-control" name="postal_code" >
                                    </div>
                                </div>
                            </div>




                            <div class="">
                                <button name="submit" class="btn btn-primary btn-block">Register</button>
                            </div>
                        </form>
                    </div>
                </div>
				<center>
				    Already registered with us? <a class="ml-4" href="login.php"> Login here</a>
				</center>
            </div>
        </div>
    </div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
<script>

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