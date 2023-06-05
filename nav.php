<nav class="navbar navbar-expand-lg" style="padding:0">
    <div class="container">
        <a class="navbar-brand" href="select-interest.php" style="padding:0">
            <img src="images/logo.png" width="83" alt="">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarColor02">
            <ul class="navbar-nav">
                <?php if($userdata['interest']=='purchasing'){ ?>
                
                <li class="nav-item"><a class="nav-link" href="products.php">Products</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown"> Categories</a>
                    <ul class="dropdown-menu">
                        <?php
                            $query = "select * from categories";
                            $stmt = $sql->prepare($query);
                            $stmt->execute();
                            $categories = $stmt->fetchAll();
                            foreach($categories as $category){
                                
                        ?>
                        <li><a class="dropdown-item" href="products.php?category=<?php echo $category['id']; ?>"><?php echo $category['name']; ?></a></li>
                        <?php } ?>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="api.php">API</a></li>
                <?php }else{ ?>
                <li class="nav-item"><a class="nav-link" href="ad-management.php">Ad Management</a> </li>
                <?php } ?>

                


            </ul>
            <ul class="navbar-nav ml-auto">

                
                <?php if($userdata['interest']=='purchasing'){ ?>
                <li class="nav-item"><a class="nav-link" href="cart.php"> <i class="fa fa-shopping-cart"></i> Cart (<span id="totalCartItems">0</span>)</a> </li>
                <?php } ?>
                <li class="nav-item"><a class="nav-link" href="update-personal-details.php">My Profile</a> </li>
                <li class="nav-item mr-3"><a class="nav-link" href="logout.php">Logout</a></li>

            </ul>
        </div>
    </div>
</nav>