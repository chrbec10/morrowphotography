<?php
$title = "Success";

include_once("../includes/header.php");
include_once('includes/navbar.php');
header("refresh:5;url= ./");
?>

<div class="page-container">
    <main class="main">
        <div class="container text-center">
            <div class="alert alert-success">
                Image deleted successfully.
                <br>
                Redirecting to admin home in 5 seconds.
            </div>
        </div>
<?php
include_once("../includes/footer.php");
?>
    </main>
</div>
</body>