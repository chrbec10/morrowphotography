<?php
$title = "Portfolio";

include_once("includes/header.php");

require_once("includes/search.php");

$result_text = '';
$result_type = '';
$result_success = false;
$search_results = '';



//Print out our column of images
function printImages($printArray) {
    echo "<div class='col-md'>";
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

//Simple greedy sort to get roughly even columns
function fillColumn($imageArr) {
    $imageGroups = [[],[],[],[0,0,0]];
    $currHeight = 0;

    foreach($imageArr as $image) {
        //Get the height of the current image
        $imgStats = getimagesize('uploads/thumb_'. $image['image']);
        $currHeight = $imgStats[1];
        
        //Find which list currently has the smallest total height of images
        $index = array_search(min($imageGroups[3]), $imageGroups[3]);

        array_push($imageGroups[$index], $image);
        $imageGroups[3][$index] += $currHeight;
    }

    for($i = 0; $i < 3; $i++) {
        printImages($imageGroups[$i]);
    }
}

//If we got search terms for both title and tags
if (isset($_GET['s']) && !empty(trim($_GET['s'])) && isset($_GET['n']) && !empty(trim($_GET['n']))){
    $tags = trim($_GET['s']);
    $title_search = trim($_GET['n']);
    $result = bothSearch($pdo, $tags, $title_search);
    if ($result === 'none') {

        $result_text = 'No images found. Please try a different search.';
        $result_type = 'alert-warning';

    } else if ($result === false) {
        $result_text = 'An error occurred. Please try again later.';
        $result_type = 'alert-danger';

    } else {
        $result_success = true;
        $search_results = $result;
    }

//If we got search terms for just tags
} else if (isset($_GET['s']) && !empty(trim($_GET['s']))) {
    $tags = trim($_GET['s']);
    $result = tagSearch($pdo, $tags);

    //If we got no results back
    if ($result === 'none') {
        $result_text = 'No images found. Please try a different search.';
        $result_type = 'alert-warning';

    //If something went wrong
    } else if ($result === false) {
        $result_text = 'An error occurred. Please try again later.';
        $result_type = 'alert-danger';

    } else {
        $result_success = true;
        $search_results = $result;
    }

//If we got search terms for just title
} else if (isset($_GET['n']) && !empty(trim($_GET['n']))) {
    $title_search = trim($_GET['n']);
    $result = nameSearch($pdo, $title_search);

    //If we got no results back
    if ($result === 'none') {
        $result_text = 'No images found. Please try a different search.';
        $result_type = 'alert-warning';

    //If something went wrong
    } else if ($result === false) {
        $result_text = 'An error occurred. Please try again later.';
        $result_type = 'alert-danger';

    } else {
        $result_success = true;
        $search_results = $result;
    }

//No search terms, display everything
} else {
    $result = getAll($pdo);
    //If we got no results back
    if ($result === 'none') {
        $result_text = 'No images found. Please ask the artist to upload some images and try again later.';
        $result_type = 'alert-danger';

    //If something went wrong
    } else if ($result === false) {
        $result_text = 'An error occurred. Please try again later.';
        $result_type = 'alert-danger';

    } else {
        $result_success = true;
        $search_results = $result;
    }
}

//Retrieve tags list to populate Select dropdown
$sql = "SELECT * FROM tags_list";
if($stmt = $pdo->query($sql)) {
    $tags_array = $stmt->fetchAll();
}

?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>

<?php
include_once('includes/navbar.php');
?>

<div class="page-container">
    <main class="main">
        <div class="container text-center">
            <h1>Portfolio</h1>
            <br>
            <div class="row">
                <div class="col-lg-3 text-start">
                    <form class="sticky-search" id="search">
                        <div class="form-group">
                            <label for="searchText">Search by Title</label>
                            <input type="text" name="searchText" id="searchText" class="form-control" value="<?php echo (isset($title_search)) ? $title_search : ''; ?>">
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="tags">Search by Tags</label>
                            <select class="tags-select form-control" name="tags[]" id="tags" multiple="multiple">
                                <?php
                                foreach($tags_array as $tag){
                                        echo"<option value='" . $tag['ID'] . "'>" . $tag['tag'] . "</option>";
                                }
                                ?>
                            </select>
                            <br><br>
                            <button type="submit" class="btn btn-dark btn-submit">Search</button>
                        </div>
                    </form>
                </div>
                <div class="col-lg-9">

                    <div class="alert <?php echo (!empty($result_type)) ? $result_type : 'd-none'; ?>"><?php echo $result_text; ?></div>
                    <div class="row">
                    <?php 
                        if ($result_success){
                            fillColumn($search_results);
                        }
                    ?>
                    </div>
                </div>
            </div>
        </div>
<?php
include_once("includes/footer.php");
?>
    </main>
</div>
<script>
    $(document).ready(function() {
        //Setup select box for tags
        $('.tags-select').select2();
        //Set search tags as selected
        <?php
            $selected_tags = '';
            if(isset($tags)){
                $current_tags = array_filter(array_map('trim', explode(' ', $tags)), 'is_numeric');
                foreach($current_tags as $tag){
                    $selected_tags .= "'" . $tag . "', ";
                }
                $selected_tags = substr($selected_tags, 0, -1);
            }
        ?>
        $('.tags-select').val([<?php echo $selected_tags; ?>]).trigger('change');
    });

    //Sets search parameters in the URL
    $('#search').on('submit', function () {

        var currentTags = $('#tags').select2('data');
        var currentText = $('#searchText').val();

        if (currentTags.length != 0) {
            var tagList = [];
            for(tag of currentTags) {
                tagList.push(tag.id);
            }
        }

        if ((currentTags.length == 0) && (currentText.length == 0)) {
            window.location.href = "<?php echo $_SERVER["PHP_SELF"]?>";
            return false;

        } else if (currentTags.length == 0) {
            window.location.href = "<?php echo $_SERVER["PHP_SELF"]?>?n=" + currentText;
            return false;

        } else if (currentText.length == 0) {
            var tagSearch = tagList.join('+');
            window.location.href = "<?php echo $_SERVER["PHP_SELF"]?>?s=" + tagSearch;
            return false;

        } else {
            var tagSearch = tagList.join('+');
            window.location.href = "<?php echo $_SERVER["PHP_SELF"]?>?s=" + tagSearch +"&n=" + currentText;
            return false;
        }
    });

    //Setup for Fancybox
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