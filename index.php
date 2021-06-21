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
                <img class="titlesignature" src="static/img/CM Signature Black.png" alt=''>
            </div>
        </div>
        <div class="scrollcontainer mb-3">    
            <p class="scrollmore"><a href="#about" class="scrollmore">Scroll down or click here to learn more</a></p>
            <i class="fa fa-chevron-down"></i>
        </div>
    </div>
    <div class="home-text" id="about">
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
            <div class="row pt-5 text-center">
                <div class="col-md-4" id="feature">
                    <h2>Recent Works</h2>
                </div>
                <div class="col-md-4" id="feature">
                    <h2>Portfolio</h2>
                </div>
                <div class="col-md-4" id="feature">
                    <h2>Contact Me</h2>
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

</body>