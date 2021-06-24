<?php
$title = "Search Images";

include_once("../includes/header.php");

require_once("../includes/search.php");

$result_text = '';
$result_type = '';
$result_success = false;
$search_results = '';



//Print out our column of images
function printImages($printArray) {
    echo "<div class='table-responsive'>";
        echo "<table class='table table-bordered table-striped'>";
            echo "<thead>";
                echo "<tr>";
                    echo "<th>Image</th>";
                    echo "<th>ID</th>";
                    echo "<th>Title</th>";
                    echo "<th>Description</th>";
                    echo "<th>Links</th>";
                    echo "<th>Actions</th>";
                echo "</tr>";
            echo "</thead>";
        echo "<tbody>";
    foreach($printArray as $image) {
        //Set overlay content
        $img_image = htmlspecialchars($image['image'], ENT_QUOTES);
        $img_ID = $image['ID'];
        $img_title = htmlspecialchars($image['title'], ENT_QUOTES);
        $img_description = htmlspecialchars($image['description'], ENT_QUOTES);
        $img_facebook = htmlspecialchars($image['facebook'], ENT_QUOTES);
        $img_twitter = htmlspecialchars($image['twitter'], ENT_QUOTES);
        
        //Print HTML content
        echo "<tr>";
        //Image
        echo "<td><img style='max-width:100px;max-height:100px;' src='../uploads/thumb_". $img_image ."' /></td>";
        //ID Number
        echo "<td>". $img_ID . "</td>";
        //Title
        echo "<td>". $img_title ."</td>";
        //Description
        echo "<td>". $img_description ."</td>";
        //Links
        echo "<td><a href='". $img_facebook ."'><i class='fa fa-facebook-square'></i></a>&nbsp;&nbsp;<a href='". $img_twitter ."'><i class='fa fa-twitter-square'></i></a></td>";
        //Actions
        echo '<td><a href="edit-image.php?id=' . $img_ID . '"><i class="fa fa-pencil"></i></a>
        <a class="delete" data-title="'. $img_title .'" href="delete-image.php?id=' . $img_ID . '"><i class="fa fa-trash"></i></a>';
    echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
    echo "</div>";
}

//Simple greedy sort to get roughly even columns
function fillColumn($imageArr) {
    $imageGroups = [[],[],[],[0,0,0]];
    $currHeight = 0;

    foreach($imageArr as $image) {
        //Get the height of the current image
        $imgStats = getimagesize('../uploads/thumb_'. $image['image']);
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
<?php
include_once('includes/navbar.php');
?>

<div class="page-container">
    <main class="main">
        <div class="container text-center">
            <h1>Search Images</h1>
            <br>
            <form id="search">
                <div class="form-group">
                    <label for="title">Search by Title</label>
                    <input type="text" name="searchText" id="searchText" class="form-control" value="<?php echo (isset($title_search)) ? $title_search : ''; ?>">
                </div>
                <br>
                <div class="form-group">
                    <label for="title">Search by Tags</label>
                    <select class="tags-select form-control" name="tags[]" id="tags" multiple="multiple">
                        <?php
                        foreach($tags_array as $tag){
                                echo"<option value='" . $tag['ID'] . "'>" . $tag['tag'] . "</option>";
                        }
                        ?>
                    </select>
                    <br><br>
                    <button type="submit" class="btn btn-dark btn-submit" style="width:100%;">Search</button>
                </div>
            </form>
            <br>
            <div class="row">
            <?php 
                if ($result_success){
                    printImages($search_results);
                }
            ?>
            </div>
        </div>
<?php
include_once("../includes/footer.php");
?>
    </main>
</div>
<script>
    $('.delete').on('click', function () {
        return confirm('Are you sure you want to delete '+ $(this).data('title') +'?\nThis action cannot be undone.');
    });

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
</script>
</body>