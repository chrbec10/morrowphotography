<?php if ($title == "Home") echo '<style>
html, body {scroll-snap-type: y proximity;}
</style>'?>
</head>
<body>
    <nav class="navbar navbar-expand-sm navbar-dark custom-nav"<?php if ($title == "Home") {echo 'style="scroll-snap-align: start;"';} ?>>
        <div class="container">
            <a alt="Home" class="navbar-brand" href="https://morrowphotography.rf.gd/"><img style="height:40px; width:170px" src="<?php echo $site_root?>/static/img/CMBrand.png" alt=''></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#cmNav" aria-controls="cmNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="cmNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link<?php if ($title == 'Home') echo ' active';?>" href="https://morrowphotography.rf.gd/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link<?php if ($title == 'Recent Works') echo ' active';?>" href="<?php echo $site_root . '/recent.php'?>">Recent Works</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link<?php if ($title == 'Portfolio') echo ' active';?>" href="<?php echo $site_root . '/portfolio.php'?>">Portfolio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link<?php if ($title == 'Contact') echo ' active';?>" href="<?php echo $site_root . '/contact.php'?>">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>