</head>
<body>
    <nav class="navbar navbar-expand-sm navbar-dark custom-nav">
        <div class="container">
            <a class="navbar-brand" href="."><img style="height:40px" src="static/img/CMBrand.png"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#cmNav" aria-controls="cmNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="cmNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link<?php if ($title == 'Home') echo ' active';?>" aria-current="page" href=".">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link<?php if ($title == 'Recent Works') echo ' active';?>" href="#">Recent Works</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link<?php if ($title == 'Portfolio') echo ' active';?>" href="#">Portfolio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link<?php if ($title == 'Contact') echo ' active';?>" href="#">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>