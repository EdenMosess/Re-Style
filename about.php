<?php
ob_start();
session_start();
require_once 'db.php';
if(isset($_POST['submit'])){
    $msg = "<div class='alert alert-success'>Your message has been sent successfully.</div>";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Contact</title>
    <?php include 'head.php'; ?>
    <style>
        body{
            background: #5b5b5b;
        }
        
        label{
            color: white;
        }
        .form-control{
            border-radius: 0;
        }
        
        .hero-image{
            width: 100%;
            height: 400px;
            object-fit: cover;
        }
        
    </style>
</head>

<body>
    <?php include 'nav.php'; ?>
    <img class="hero-image" src="images/about.webp" alt="">
    <div class="container">
        <div class="row justify-content-center mb-5 mt-3">
            <div class="col-md-10">
                <h1 class="mb-3 text-white text-center fancy"><?php echo $ln['about']; ?></h1>
                <p class="text-white">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam sed risus rutrum, placerat lorem nec, aliquam leo. Nam tristique eros in elit ultrices porttitor. Duis ac neque vitae metus lobortis consectetur. Integer nec metus ac nisi aliquet bibendum pharetra sed lectus. Nullam et dui ultricies, gravida turpis vitae, dignissim tortor. Ut quam nunc, elementum sit amet nibh id, dignissim blandit sem. Pellentesque maximus dolor odio, ac blandit augue mollis et. Quisque ut suscipit ipsum. Aenean ante lacus, fermentum at congue in, lacinia a metus. Vivamus in arcu aliquam, ullamcorper velit ac, interdum nibh.</p>
                <p class="text-white">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam sed risus rutrum, placerat lorem nec, aliquam leo. Nam tristique eros in elit ultrices porttitor. Duis ac neque vitae metus lobortis consectetur. Integer nec metus ac nisi aliquet bibendum pharetra sed lectus. Nullam et dui ultricies, gravida turpis vitae, dignissim tortor. Ut quam nunc, elementum sit amet nibh id, dignissim blandit sem. Pellentesque maximus dolor odio, ac blandit augue mollis et. Quisque ut suscipit ipsum. Aenean ante lacus, fermentum at congue in, lacinia a metus. Vivamus in arcu aliquam, ullamcorper velit ac, interdum nibh.</p>
                <p class="text-white">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam sed risus rutrum, placerat lorem nec, aliquam leo. Nam tristique eros in elit ultrices porttitor. Duis ac neque vitae metus lobortis consectetur. Integer nec metus ac nisi aliquet bibendum pharetra sed lectus. Nullam et dui ultricies, gravida turpis vitae, dignissim tortor. Ut quam nunc, elementum sit amet nibh id, dignissim blandit sem. Pellentesque maximus dolor odio, ac blandit augue mollis et. Quisque ut suscipit ipsum. Aenean ante lacus, fermentum at congue in, lacinia a metus. Vivamus in arcu aliquam, ullamcorper velit ac, interdum nibh.</p>
			</div>
        </div>
    </div>
    <?php include 'footer.php'; ?>    
</body>

</html>