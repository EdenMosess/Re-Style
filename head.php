<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.2/css/bootstrap.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,400;0,700;0,900;1,100;1,400;1,700;1,900&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
<link href="https://fonts.googleapis.com/css2?family=Sirin+Stencil&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
  />
<style>
    .fancy {
        font-family: 'Sirin Stencil', cursive !important;
    }
    
    .dropdown-submenu {
          position: relative;
        }

        .dropdown-submenu .dropdown-menu {
          top: 0;
          left: 100%;
          margin-top: -1px;
        }
    
    @media (min-width: 992px){
	.dropdown-menu .dropdown-toggle:after{
		border-top: .3em solid transparent;
	    border-right: 0;
	    border-bottom: .3em solid transparent;
	    border-left: .3em solid;
	}
	.dropdown-menu .dropdown-menu{
		margin-left:0; margin-right: 0;
	}
	.dropdown-menu li{
		position: relative;
	}
	.nav-item .submenu{ 
		display: none;
		position: absolute;
		left:100%; top:-7px;
	}
	.nav-item .submenu-left{ 
		right:100%; left:auto;
	}
	.dropdown-menu > li:hover{ background-color: #f1f1f1 }
	.dropdown-menu > li:hover > .submenu{
		display: block;
	}
}

    .img-1 {
        width: 100%;
        height: 220px !important;
        object-fit: cover;
    }



    li a {
        color: rgba(102, 102, 102, .85);
        font-size: .8em;
        font-weight: 700;
        -webkit-transition: all .2s;
        -o-transition: all .2s;
        transition: all .2s;
        text-transform: uppercase;
    }

    .carousel-item img {
        height: 500px;
        object-fit: cover;
    }
    
    .fa-star{
        color: orange;
    }

    .overlay {
        width: 100%;
        text-align: center;
        position: absolute;
        z-index: 99;
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
    }

    body {
        background-color: rgb(34, 34, 34);
        font-family: 'Lato', sans-serif;
    }

    .category {
        color: #f1f1f1;
        opacity: .7;
        font-size: .75em;
    }

    .product-name {
        color: black;
        font-size: .9em;
    }
    
    footer{
        background: black;
        padding-top: 45px;
        padding-bottom: 45px;
    }
    
    .product-image{
        width: 100%;
        height: 200px;
        border-radius: 10px;
        object-fit: cover;
        border: 1px solid #ddd;
    }
    
    .is-divider-small {
        height: 3px;
        display: block;
        background-color: rgba(255,255,255,.3);
        margin: 1em 0 1em;
        width: 100%;
        max-width: 30px;
    }

    .dropdown-menu{
        background-color: #373747f0;
        border: 2px solid gold;
    }
    
    .dropdown-menu a{
        color: white !important;
    }

    .product-data a {
        text-decoration: none;
    }

    .overlay {
        position: absolute;
        background: black;
        width: 100%;
        height: 100%;
        opacity: 0.4;
    }

    .carousel-caption,
    .carousel-control-prev,
    .carousel-control-next {
        z-index: 999 !important;
    }
    
    .search, .search:focus{
        color: black;
        border-radius: 10px;
        background: #fcf8ef;
        border: 2px solid #fcf8ef;
        
        padding-left: 15px;
        padding-right: 15px;
        box-shadow: 0 10px 20px rgb(0 0 0 / 19%), 0 6px 6px rgb(0 0 0 / 22%);
    }
    


    .btn-secondary {
        color: black;
        border-radius: 10px;
        background: #fcf8ef;
        border: 2px solid #fcf8ef;
        
        padding-left: 15px;
        padding-right: 15px;
        box-shadow: 0 10px 20px rgb(0 0 0 / 19%), 0 6px 6px rgb(0 0 0 / 22%);
    }
</style>