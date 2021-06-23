<?php 
$title = "Home";
include_once("includes/header.php");
?>
<link rel="preload" as="image" href="<?php echo $site_root?>/static/img/CM Signature Black.png"/>
<link rel="preload" as="image" href="<?php echo $site_root?>/static/img/hero-desktop.jpg"/>
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
            <p class="scrollmore"><a href="#about" class="scrollmore">Scroll down or click here to learn more</a></p>
            <i class="fa fa-chevron-down"></i>
        </div>
    </div>
    <div class="home-text">
        <div class="container py-5 text-center">
            <h2>About Me</h2>
            <h5>My name is Colin Morrow, and I'm a photographer. 
                <br>
                I was born in the shadow of the Rocky mountains, 
                but left my home when I was very young. Nowadays 
                I operate out of Ontario, but I'm always chasing 
                views that remind me of home. My passion is for 
                nature photography, and finding beauty in the 
                unpredictability, the chaos, and the calmness of 
                the natural world.</h5>
        </div>
    </div>

    <div class="parallax" id="info">
        <div class="container">
            <div class="row py-5 text-center ">
                <div class="col-md-4 p-3 feature">
                    <img src="static/img/rworks.jpg" class="feature-fit">
                    <h2 class="pt-3">Recent Works</h2>
                    <p>Explore a gallery of a selection of my most recent images</p>
                </div>
                <div class="col-md-4 p-3 feature">
                    <img src="static/img/portfolio.jpg" class="feature-fit">
                    <h2 class="pt-3">Portfolio</h2>
                    <p>View my entire gallery of works, or search for particular images by tags or by title</p>
                </div>
                <div class="col-md-4 p-3 feature">
                    <img src="static/img/contact.jpg" class="feature-fit">
                    <h2 class="pt-3">Contact Me</h2>
                    <p>Check here to get in touch through my email form, or via private message using links to all of my social media profiles.</p>
                </div>
            </div>
        </div>
    </div>

    <!--Footer-->
    <div style="background-color: #222;color: #CCC;">
        <div class="container pt-3 pb-1">
            <div class="row">
                <div class="col-6 text-start">
                    <a class="navbar-brand float-left" href="."><img style="height:40px" src="<?php echo $site_root?>/static/img/CMBrand.png" alt=''></a>
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

