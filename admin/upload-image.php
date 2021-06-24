<?php
$title = "New Image";

//Setup database access
include_once("../includes/header.php");
include_once("../includes/db.php");


//Create full-size and thumbnail images on upload
function compressImage($source, $name, $quality, $filepath) {
    //Get image information
    $info = getimagesize($source);

    //Convert image into image object
    if ($info['mime'] == 'image/jpeg')
        $image = imagecreatefromjpeg($source);
    
    if ($info['mime'] == 'image/gif')
        $image = imagecreatefromgif($source);

    if ($info['mime'] == 'image/png')
        $image = imagecreatefrompng($source);

    //if image is taller than it is wide
    if (imagesy($image) > imagesx($image)) {

        //Scale image to 2000px height for desktop
        $image_hi = imagescale($image, -1, 2000, IMG_BICUBIC);
        //Save image as high-quality jpg
        imagejpeg($image_hi, ($filepath . $name), $quality);
        //Scale image to 1000px height for mobile
        $image_mob = imagescale($image, -1, 1000, IMG_BICUBIC);
        //Save image as mobile-quality jpg
        imagejpeg($image_mob, ($filepath. "mob_" . $name), $quality);
        //Scale image down to 150px height
        $image_tny = imagescale($image, -1, 150, IMG_BICUBIC);
        //Save as tiny
        imagejpeg($image_tny, ($filepath. "tny_" . $name), 50);

    } else {

        //Scale image to 3000px width
        $image_hi = imagescale($image, 3000, -1, IMG_BICUBIC);
        //Save image as high-quality jpeg
        imagejpeg($image_hi, ($filepath . $name), $quality);
        //Scale image to 1000px height for mobile
        $image_mob = imagescale($image, 1500, -1, IMG_BICUBIC);
        //Save image as mobile-quality jpg
        imagejpeg($image_mob, ($filepath. "mob_" . $name), $quality);
        //Scale image down to 150px width
        $image_tny = imagescale($image, 150, -1, IMG_BICUBIC);
        //Save as tiny
        imagejpeg($image_tny, ($filepath. "tny_" . $name), 50);
    }

    //Scale image down to 500px width
    $image_th = imagescale($image, 500, -1, IMG_BICUBIC);
    //Save image as thumbnail
    imagejpeg($image_th, ($filepath . "thumb_" . $name), $quality);

}


//Define variables
$img_title = $description = $twitter = $facebook = '';
$tags = [];
$img_title_err = $description_err = $twitter_err = $facebook_err = $tags_err = $image_err = '';

//Process form data on submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //Validation for text
    $input_title = trim($_POST['title']);
    if (!empty($input_title)) {
        $img_title = preg_replace('/[^A-Za-z0-9\-\']/', '', $input_title); // Removes special chars
    } else {
        $img_title_err = "Please enter a title for the image";
    }

    $input_description = trim($_POST['description']);
    if (!empty($input_description)) {
        $description = preg_replace('/[^A-Za-z0-9\-\']/', '', $input_description); // Removes special chars
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
            //Validate file upload
        if(isset($_FILES["image"]) && $_FILES["image"]["error"] == 0){

            //Set up constraints
            $allowed = array("jpg", "jpeg", "gif", "png");
            $maxsize = 5 * 1024 * 1024;

            //Set up destination for images
            $destination = "../uploads/";

            //Quality setting for images
            $quality = 90;

            //Get information about file
            $filesize = filesize($_FILES["image"]["tmp_name"]);
            $filetype = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
            echo $filetype;
            $filename = $_FILES["image"]["tmp_name"];
            $error = $_FILES["image"]["error"];

            //Check filesize
            if ($filesize > $maxsize) {
                $image_err = 'File too large. Please upload a file that is less than 5MB in size.';
            
            //Check errors
            } else if ($error != 0){
                $image_err = 'Error uploading file. Error code: ' . $error;
            
            //Check filetype
            } else if(!in_array(strtolower($filetype), $allowed)) {
                $image_err = 'File is wrong filetype. Please upload a .jpg, .jpeg, .gif, or .png file.';
            
            //If no problems
            } else {
                //Select image, create image name
                $image = $_FILES["image"]["tmp_name"];
                $image_name = hash_file('sha1', $image) . '.jpg';
                if (!file_exists($destination . '/' . $image_name)){
                    compressImage($image, $image_name, $quality, $destination);
                } else {
                    $image_err = 'Image already exists. Please choose a new file to upload.';
                }
            }
        } else if(!isset($_FILES["image"]) || ($_FILES["image"]["error"] == 4)) {
            $image_err = 'Please choose an image to upload.';

        } else {
            $image_err = 'Error uploading file. Error code: ' . $_FILES["image"]["error"];
        }

        if(empty($image_err)){

            //Create SQL query
            $sql = "INSERT INTO images(title, twitter, facebook, image, description) VALUES (:title, :twitter, :facebook, :image, :description)";

            //If query prepares successfully
            if ($stmt = $pdo->prepare($sql)) {

                //Bind variables
                $stmt->bindParam(":title", $param_title);
                $stmt->bindParam(":twitter", $param_twitter);
                $stmt->bindParam(":facebook", $param_facebook);
                $stmt->bindParam(":image", $param_image);
                $stmt->bindParam(":description", $param_description);

                //Set parameters
                $param_title = $img_title;
                $param_twitter = $twitter;
                $param_facebook = $facebook;
                $param_image = $image_name;
                $param_description = $description;
                
                //If query executes successfully
                if($stmt->execute()) {

                    //Get ID of created entry
                    $sql = "SELECT MAX(ID) FROM images";
                    if ($stmt = $pdo->query($sql)){

                        $created = ($stmt->fetchColumn());
                        //Ready a statement to insert tags
                        $sql = "INSERT INTO tags_rel(image_ID, tag_ID) VALUES";
                        
                        //Build an insert row for each tag
                        foreach($tags as $tag) {
                            
                            $sql .= "(" . $created . "," . $tag . "),";
                        };

                        //Trim last comma from SQL query
                        $sql = substr($sql, 0, -1);
                        $sql .= ";";
                        //If our tags are inserted successfully
                        if($stmt=$pdo->query($sql)) {
                            header("location: edit-image.php?id=" . $created);
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

unset($stmt);
unset($pdo);
?>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<?php
include_once('includes/navbar.php');
?>
<div class="page-container">
    <main class="main">
        <div class="container">
            <h1 class="text-center">New Image</h1>
            <br>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="file" name="image" id="image" class="form-control <?php echo (!empty($image_err)) ? 'is-invalid' : ''; ?>" aria-describedby="fileHelp">
                    <div id="fileHelp" class="form-text">File must be a .jpg, .jpeg, .gif, or .png file, and less than 5MB in size.</div> 
                    <span class="invalid-feedback"><?php echo $image_err;?></span>
                </div>
                <br>
                <div class="form-group">
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
                $selected_tags .= "'" . $tag . "', ";
            }
            $selected_tags = substr($selected_tags, 0, -1);
        ?>
        $('.tags-select').val([<?php echo $selected_tags; ?>]).trigger('change');
    });
</script>
</body>