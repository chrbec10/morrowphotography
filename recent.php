<?php
$title = "Recent Works";

include_once("includes/header.php");
require_once("includes/db.php");
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>

<?php
$images = [];

//Grab the 20 most recently uploaded images
$sql = "SELECT * FROM images ORDER BY ID DESC LIMIT 10";
if ($stmt = $pdo->query($sql)) {
    $images = $stmt->fetchAll();
    $count = count($images);
}

//Simple greedy sort to get roughly even columns
function fillColumn($imageArr) {
    $imageGroups = [[],[],[],[],[0,0,0,0]];
    $currHeight = 0;

    foreach($imageArr as $image) {
        //Get the height of the current image
        $imgStats = getimagesize('uploads/thumb_'. $image['image']);
        $currHeight = $imgStats[1];
        
        //Find which list currently has the smallest total height of images
        $index = array_search(min($imageGroups[4]), $imageGroups[4]);

        array_push($imageGroups[$index], $image);
        $imageGroups[4][$index] += $currHeight;
    }

    for($i = 0; $i < 4; $i++) {
        printImages($imageGroups[$i]);
    }
}

//Print out our column of images
function printImages($printArray) {
    echo "<div class='col-lg-3 col-md-6'>";
    foreach($printArray as $image) {
        //Set overlay content
        $img_description = htmlspecialchars($image['description'], ENT_QUOTES);
        $img_title = htmlspecialchars($image['title'], ENT_QUOTES);
        $img_facebook = htmlspecialchars($image['facebook'], ENT_QUOTES);
        $img_twitter = htmlspecialchars($image['twitter'], ENT_QUOTES);
        
        //Print HTML content
        echo "<a href='uploads/" . $image['image'] . "' data-srcset=' uploads/" . $image['image'] . " 1600w, uploads/mob_" . $image['image'] . " 720w' data-fancybox='images' data-fb='" . $img_facebook . "' data-twt='" . $img_twitter . "' data-desc='" . $img_description . "' data-title='" . $img_title . "'>";
        echo "<img style='max-width:100%; padding-top:0.75rem; padding-bottom:0.75rem;' alt='' src='uploads/thumb_" . $image['image'] . "' />";
        echo "</a>";
    }
    echo "</div>";
}

include_once('includes/navbar.php');
?>

<div class="page-container">
    <main class="main">
        <div class="container text-center">
        <h1>Recent Works</h1>
        <br>
            <div class="row">
                <?php
                    fillColumn($images);
                ?>
            </div>
        </div>
<?php
include_once("includes/footer.php");
?>
    </main>
</div>
<script>
//fancybox config
$('[data-fancybox="images"]').fancybox({
    //Turn thumbnails on
    thumbs: {
        autoStart: true,
        axis: 'x'
    },
    //Build caption for each image
    caption: function( instance, item ) {
        var caption = '<h5>' + $(this).data('title') + '</h5><p>' + $(this).data('desc') + '</p><p>View on: <a href="' + $(this).data('fb') + '"><i class="fa fa-facebook-square"></i> Facebook</a> <a href="' + $(this).data('twt') + '"><i class="fa fa-twitter-square"></i> Twitter</a></p>';
        return caption;
    }
});
</script>
</body>