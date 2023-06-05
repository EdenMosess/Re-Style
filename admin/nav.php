<nav class="navbar navbar-expand-lg" style="background:#ededec;padding:0">
    <div class="container">
        <a class="navbar-brand" href="index.php" style="padding:0">
            <img src="../images/logo.png" width="83" alt="">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarColor02">
            <ul class="navbar-nav">
                <?php if(isset($_SESSION['admin'])){ ?>
                <li class="nav-item"><a class="nav-link" href="index.php">Orders</a></li>
                <li class="nav-item"><a class="nav-link" href="products.php">Products</a></li>
                <li class="nav-item"><a class="nav-link" href="categories.php">Categories</a></li>
                <li class="nav-item"><a class="nav-link" href="users.php">Users</a></li>
                <li class="nav-item"><a class="nav-link" href="birthdays.php">Birthdays</a></li>
                <li class="nav-item"><a class="nav-link" href="earnings.php">Earnings</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact Us</a></li>
                <li class="nav-item"><a class="nav-link" href="newsletter.php">Newsletter</a></li>
                <?php } ?>
            </ul>
            <ul class="navbar-nav ml-auto">
                <?php if(isset($_SESSION['admin'])){ ?>
                
                <li class="nav-item mr-3"><a class="nav-link" href="logout.php">Logout</a></li>
                <?php  } ?>
                <li class="nav-item" style="display:none">
                    <form action="changelanguage.php" method="post" id="languageForm">
                        <select name="language" onchange="$('#languageForm').submit()" class="form-control form-control-sm" id="">
                            <option <?php echo $_SESSION['language']=='en'?'selected':''; ?> value="en">EN</option>
                            <option <?php echo $_SESSION['language']=='ar'?'selected':''; ?> value="ar">AR</option>
                        </select>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>