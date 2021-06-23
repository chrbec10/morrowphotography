<?php 
$title = "Home";
include_once("includes/header.php");
?>
<link rel="preload" as="image" href="<?php echo $site_root?>/static/img/CM Signature White.png"/>
<?php
include_once("includes/navbar.php");
?>

    <div class="parallax" id="hero">
        <div class="title">
            <div class="container">
                <img class="titlesignature" src="static/img/CM Signature White.png" alt=''>
            </div>
        </div>
        <div class="scrollcontainer mb-3">    
            <p class="scrollmore"><a href="#links" class="scrollmore">Scroll down or click here to learn more</a></p>
            <i class="fa fa-chevron-down"></i>
        </div>
    </div>



    <div id="links" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#links" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#links" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#links" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active overflow-hidden">
                <div class="image-center">
                    <img src="static/img/rworks.jpg" class="d-block" alt="">
                    <div class="carousel-caption d-block pb-5">
                    <a href="recent.php"><h1>Recent Works</h1></a>
                        <p>Click here to view a selection of my most recent images</p>
                    </div>
                </div>
            </div>
            <div class="carousel-item overflow-hidden">
                <div class="image-center">
                    <img src="static/img/portfolio.jpg" class="d-block" alt="">
                    <div class="carousel-caption d-block pb-5">
                        <a href="portfolio.php"><h1>Portfolio</h1></a>
                        <p>Click here to view my entire portfolio, and search for images by tags or title</p>
                    </div>
                </div>
            </div>
            <div class="carousel-item overflow-hidden">
                <div class="image-center">
                    <img src="static/img/contact.jpg" class="d-block" alt="">
                    <div class="carousel-caption d-block pb-5">
                    <a href="contact.php"><h1>Contact Me</h1></a>
                        <p>Click here to get in touch, either through my email form or via PM on one of my social media pages</p>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#links" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#links" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
        </div>


    <!--Footer-->
    <div style="background-color: #222;color: #CCC; scroll-snap-align: start;">
        <div class="container pt-3 pb-1">
            <div class="row">
                <div class="col-6 text-start">
                    <a class="navbar-brand float-left" href="https://morrowphotography.rf.gd/"><img style="height:40px" src="<?php echo $site_root?>/static/img/CMBrand.png" alt=''></a>
                </div>
                <div class="col-6 text-end">
                    <span class="float-right"><a alt="Morrow Photography Facebook" href="https://morrowphotography.rf.gd/" class="footer-link"><i class="fa fa-3x fa-facebook-square"></i></a></span>
                    <span class="float-right"><a alt="Morrow Photography Twitter" href="https://morrowphotography.rf.gd/" class="footer-link"><i class="fa fa-3x fa-twitter-square"></i></a></span>
                </div>
            </div>
            <div class="text-center footer-copyright">
                    <p>All photos are © Colin Morrow. Website by Chris Becker.<p>
            </div>
        </div>
    </div>
</body>