<?php
$title = "Admin Dashboard";
include_once("../includes/header.php");
include_once('includes/navbar.php');
?>

<div class="page-container">
    <main class="main">
        <div class="container text-center">
            <h1>Admin Control Panel</h1>
            <br>
            <div class="row">
                <div class="col-4">
                <a href="upload-image.php" alt="Upload Image">
                <p><i class="fa fa-5x fa-upload"></i></p>
                <h3>Upload Image</h3>
                </a>
                </div>
                <div class="col-4">
                <a href="search-images.php" alt="Search Images">
                <p><i class="fa fa-5x fa-search"></i></p>
                <h3>Search Images</h3>
                </a>
                </div>
                <div class="col-4">
                <a href="edit-tags.php" alt="Manage Tags">
                <p><i class="fa fa-5x fa-tags"></i></p>
                <h3>Manage Tags</h3>
                </a>
                </div>
            </div>
        </div>
    <?php
    include_once('../includes/footer.php');
    ?>
    
    </main>
</div>
</body>