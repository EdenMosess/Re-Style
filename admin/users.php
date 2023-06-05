<?php
ob_start();
session_start();
require_once '../db.php';
if(!isset($_SESSION['admin'])){
    header("location:login.php");
    die();
}

if(isset($_POST['delete'])){
    $query = "DELETE from users where id=?";
    $stmt = $sql->prepare($query);
    $stmt->bindParam(1, $_POST['user_id'], PDO::PARAM_STR);
    $stmt->execute();
    $msg = "<div class='alert alert-success'>User removed successfully.</div>";
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Users</title>
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
    </style>
    
</head>

<body>
    <?php include 'nav.php'; ?>
    <div class="container">
        <div class="row  mb-5">
            <div class="col-md-12">
                <h1 class="mt-5 mb-3 fancy text-white">All Users</h1>
                <?php if(isset($msg)){ echo $msg; } ?>
                <table class="table table-bordered">
                <tr class=" text-white">
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>DOB</th>
                    <th>Actions</th>
                </tr>
                <?php 
                    $stmt = $sql->prepare("select * from users");
                    $stmt->execute();
                    $users = $stmt->fetchAll();
                    foreach($users as $user){
                ?>
                <tr>
                    <td><?php echo $user['name']; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo $user['dob']; ?></td>
                    <td>
                        <form action="" method="post" onsubmit="return confirm('Are you sure?')">
                            <input type="hidden" name="user_id" value="<?php echo $user['id'] ?>">
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