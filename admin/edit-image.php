<?php

//Setup database access
include_once("../includes/header.php");
include_once("../includes/db.php");

//TODO: Define variables

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
                $title = $row['title'];
                $twitter = $row['twitter'];
                $facebook = $row['facebook'];
                $image = $row['image'];
                $description = $row['description'];

                $sql = "SELECT tag_ID FROM tags_rel WHERE image_ID = :image_ID";
                if ($stmt = $pdo->prepare($sql)){
                    $stmt->bindParam(":image_ID", $param_image_ID);

                    $param_image_ID = $image_ID;

                    if ($stmt->execute()){
                        $tags_ID = $stmt->fetchAll();
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


//TODO: Process form data on submit
if (isset($_POST['id']) && !empty(trim($_POST['id']))){
    
}

//Retrieve tags list to populate Select dropdown
$sql = "SELECT * FROM tags_list";
if($stmt = $pdo->query($sql)) {
    $tags_array[] = $stmt->fetchAll();
}
?>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<?php
include_once('../includes/navbar.php');
?>
<div class="page-container">
    <main class="main">
        <div class="container">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="file" name="image" id="image" class="form-control <?php echo (!empty($image_err)) ? 'is-invalid' : ''; ?>" aria-describedby="fileHelp">
                    <div id="fileHelp" class="form-text">File must be a .jpg, .jpeg, .gif, or .png file, and less than 2MB in size.</div> 
                    <span class="invalid-feedback"><?php echo $image_err;?></span>
                </div>
                <br>
                <div class="form-group">
                    <label for="title">Image Title</label>
                    <input type="text" class="form-control <?php echo (!empty($title_err)) ? 'is-invalid' : ''; ?>" name="title" id="title" value="<?php echo $title; ?>">
                    <span class="invalid-feedback"><?php echo $title_err;?></span>
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
                    foreach($tags_array as $tag_sub){
                        foreach($tag_sub as $tag){
                            echo"<option value='" . $tag['ID'] . "'>" . $tag['tag'] . "</option>";
                        }
                    }
                    ?>
                    </select>
                    <span class="invalid-feedback"><?php echo $tags_err;?></span>
                </div>
                <br>
                <button type="submit" class="btn btn-dark btn-submit">Submit</button>
            </form>
        </div>
    <?php
    include_once('../includes/footer.php');
    ?>
    </main>
</div>
<script>
$(document).ready(function() {
    $('.tags-select').select2();
});
</script>
</body>