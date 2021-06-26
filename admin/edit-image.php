<?php
$title = "Edit Image";
//Setup database access
include_once("../includes/header.php");
include_once("../includes/db.php");


//Set alert banner text and colour
if (isset($_GET['r']) && ($_GET['r'] != '')){
    $r = trim($_GET['r']);

    if (isset($_GET['e']))
        $e = trim($_GET['e']);

    switch($r){
        case 1:
            $response_div = 'alert-success';
            $response_txt = 'New image uploaded successfully';
            break;
        case 2:
            $response_div = 'alert-success';
            $response_txt = 'Changes submitted successfully';
            break;
        case 3:
            $response_div = 'alert-danger';
            $response_txt = 'Failed to delete entry.';
            break;
        case 4:
            $response_div = 'alert-danger';
            $response_txt = 'Image not found in database.';
            break;
        default:
            $response_div = 'd-none';
            $response_txt = '';
            break;
    }
} else {
    $response_div = 'd-none';
    $response_txt = '';
}


//Check whether we were given an ID before continuing
if (isset($_GET['id']) && !empty(trim($_GET['id']))){
    //Get our ID parameter
    $image_ID = trim($_GET['id']);

    //Prepare a select statement
    $sql = "SELECT * FROM images WHERE ID = :image_ID";
    if ($stmt = $pdo->prepare($sql)){

        //Bind variables to the select statement
        $stmt->bindParam(":image_ID", $param_image_ID);

        //Set parameter
        $param_image_ID = $image_ID;
        
        //Attempt the select statement
        if($stmt->execute()){
            //Check that we get exactly 1 row back
            if ($stmt->rowCount() == 1){
                //Fetch as an associative array since we're getting only one row back
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                //Pull values from row
                $img_title = $row['title'];
                $twitter = $row['twitter'];
                $facebook = $row['facebook'];
                $image = $row['image'];
                $description = $row['description'];

                $sql = "SELECT tag_ID FROM tags_rel WHERE image_ID = :image_ID";
                if ($stmt = $pdo->prepare($sql)){
                    $stmt->bindParam(":image_ID", $param_image_ID);

                    $param_image_ID = $image_ID;

                    if ($stmt->execute()){
                        $tags = $stmt->fetchAll();
                    }
                }

            } else {
                //URL doesn't contain a valid ID
                header("location: ../404.php");
                exit();
            }
        } else {
            echo "Oops! Something went wrong.";
        }
    }
    //Close statement
    unset($stmt);

} else {
    //We weren't given an ID
    header("location: ../404.php");
    exit();
}

//Set error variables
$img_title_err = $description_err = $twitter_err = $facebook_err = $tags_err = '';

//TODO: Process form data on submit
if (isset($_POST['id']) && !empty(trim($_POST['id']))){

    //Validation for text
    $input_title = trim($_POST['title']);
    if (!empty($input_title)) {
        $img_title = htmlspecialchars($input_title, ENT_QUOTES);
    } else {
        $img_title_err = "Please enter a title for the image";
    }

    $input_description = trim($_POST['description']);
    if (!empty($input_description)) {
        $description = htmlspecialchars($input_description, ENT_QUOTES);
    } else {
        $description_err = "Please enter a description for the image";
    }

    $input_twitter = trim($_POST['twitter']);
    if (!empty($input_twitter)) {
        if (filter_var($input_twitter, FILTER_VALIDATE_URL) !== false) {
            $twitter = $input_twitter;
        } else {
            $twitter_err = "Please enter a valid URL";
        }
    } else {
        $twitter_err = "Please enter a twitter link";
    }

    $input_facebook = trim($_POST['facebook']);
    if (!empty($input_facebook)) {
        if (filter_var($input_facebook, FILTER_VALIDATE_URL) !== false) {
            $facebook = $input_facebook;
        } else {
            $facebook_err = "Please enter a valid URL";
        }
    } else {
        $facebook_err = "Please enter a facebook link";
    }

    if (!empty($_POST['tags'])) {
        $tags = $_POST['tags'];
    } else {
        $tags_err = "Please select at least one tag for the image";
    }
    
    //If there aren't any errors
    if (empty($img_title_err) && empty($description_err) && empty($twitter_err) && empty($facebook_err) && empty($tags_err)) {

        //Create SQL query
        $sql = "UPDATE images SET title = :title, twitter = :twitter, facebook = :facebook, description = :description WHERE ID = :image_ID";

        //If query prepares successfully
        if ($stmt = $pdo->prepare($sql)) {

            //Bind variables
            $stmt->bindParam(":title", $param_title);
            $stmt->bindParam(":twitter", $param_twitter);
            $stmt->bindParam(":facebook", $param_facebook);
            $stmt->bindParam(":description", $param_description);
            $stmt->bindParam(":image_ID", $param_image_ID);

            //Set parameters
            $param_title = $img_title;
            $param_twitter = $twitter;
            $param_facebook = $facebook;
            $param_description = $description;
            $param_image_ID = $image_ID;
            
            //If query executes successfully
            if($stmt->execute()) {
                $sql = "DELETE FROM tags_rel WHERE image_ID = :image_ID";
                if ($stmt = $pdo->prepare($sql)){
                    $stmt->bindParam("image_ID", $param_image_ID);
                    $param_image_ID = $image_ID;

                    if ($stmt->execute()) {
                        $sql = "INSERT INTO tags_rel(image_ID, tag_ID) VALUES";
                    
                        //Build an insert row for each tag
                        foreach($tags as $tag) {
                            
                            $sql .= "(" . $image_ID . "," . $tag . "),";
                        };

                        //Trim last comma from SQL query
                        $sql = substr($sql, 0, -1);
                        $sql .= ";";
                        //If our tags are inserted successfully
                        if($stmt=$pdo->query($sql)) {
                            header("location: edit-image.php?id=" . $image_ID . "&r=2");
                        }
                    }
                }
            }
        }
    }
}


//Retrieve tags list to populate Select dropdown
$sql = "SELECT * FROM tags_list ORDER BY tag";
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
        <div class="container">
            <div class="alert <?php echo $response_div; ?>"><?php echo $response_txt; ?></div>
            <?php if($response_div != "d-none") {echo "<br>";} ?>
            <h1 class="text-center">Edit Image</h1>
            <br>
            <div class="container text-center" style="max-width:400px;">
                <img style="max-width:100%; max-height:400px; text-align:center; box-shadow: 0 5px 10px #555;" src="<?php echo '../uploads/thumb_' . $image;?>">
            </div>
            <br>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $image_ID; ?>" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="hidden" value="<?php echo $image_ID?>" name="id" id="id">
                    <label for="title">Image Title</label>
                    <input type="text" class="form-control <?php echo (!empty($img_title_err)) ? 'is-invalid' : ''; ?>" name="title" id="title" value="<?php echo $img_title; ?>">
                    <span class="invalid-feedback"><?php echo $img_title_err;?></span>
                </div>
                <br>
                <div class="form-group">
                    <label for="description">Image Description</label>
                    <textarea class="form-control <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>" name="description" id="description"><?php echo $description; ?></textarea>
                    <span class="invalid-feedback"><?php echo $description_err;?></span>
                </div>
                <br>
                <div class="form-group">
                    <label for="twitter">Twitter Link</label>
                    <input type="text" class="form-control <?php echo (!empty($twitter_err)) ? 'is-invalid' : ''; ?>" name="twitter" id="twitter" value="<?php echo $twitter; ?>">
                    <span class="invalid-feedback"><?php echo $twitter_err;?></span>
                </div>
                <br>
                <div class="form-group">
                    <label for="facebook">Facebook Link</label>
                    <input type="text" class="form-control <?php echo (!empty($facebook_err)) ? 'is-invalid' : ''; ?>" name="facebook" id="facebook" value="<?php echo $facebook; ?>">
                    <span class="invalid-feedback"><?php echo $facebook_err;?></span>
                </div>
                <br>
                <div class="form-group">
                    <label for="tags">Search Tags</label>
                    <select class="tags-select form-control <?php echo (!empty($tags_err)) ? 'is-invalid' : ''; ?>" name="tags[]" id="tags" multiple="multiple">
                    <?php
                    foreach($tags_array as $tag){
                        echo"<option value='" . $tag['ID'] . "'>" . $tag['tag'] . "</option>";
                    }
                    ?>
                    </select>
                    <span class="invalid-feedback"><?php echo $tags_err;?></span>
                </div>
                <br>
                <button type="submit" class="btn btn-dark btn-submit">Submit</button>
                <a data-title="<?php echo $img_title?>" href="delete-image.php?id=<?php echo $image_ID; ?>" class="btn btn-delete float-end confirm">Delete</a>
            </form>
        </div>
    <?php
    include_once('../includes/footer.php');
    ?>
    </main>
</div>
<script>

    
    $(document).ready(function() {
        //Setup select box for tags
        $('.tags-select').select2();
        //Set image's current tags as selected by default
        <?php
            $selected_tags = '';
            foreach($tags as $tag){
                $selected_tags .= "'" . $tag['tag_ID'] . "', ";
            }
            $selected_tags = substr($selected_tags, 0, -1);
        ?>
        $('.tags-select').val([ <?php echo $selected_tags; ?>]).trigger('change');
    });

    $('.confirm').on('click', function () {
        return confirm('Are you sure you want to delete '+ $(this).data('title') +'?\nThis action cannot be undone.');
    });
</script>
</body>