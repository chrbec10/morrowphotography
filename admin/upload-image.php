<?php

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

        //Scale image to 1000px height
        $image_hi = imagescale($image, -1, 1000, IMG_BICUBIC);
        //Save image as high-quality jpeg
        imagejpeg($image_hi, ($filepath . $name), $quality);

    } else {

        //Scale image to 1500px widt and save as high quality
        $image_hi = imagescale($image, 1500, -1, IMG_BICUBIC);
        //Save image as high-quality jpeg
        imagejpeg($image_hi, ($filepath . $name), $quality);
    }

    //Scale image down to 150px width
    $image_th = imagescale($image, 500, -1, IMG_BICUBIC);
    //Save image as thumbnail
    imagejpeg($image_th, ($filepath . "thumb_" . $name), $quality);
}


//Define variables
$title = $description = $twitter = $facebook = $tags = '';
$title_err = $description_err = $twitter_err = $facebook_err = $tags_err = '';

//Process form data on submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //Validation for text
    $input_title = trim($_POST['title']);
    if (!empty($input_title)) {
        $title = $input_title;
    } else {
        $title_err = "Please enter a title for the image";
    }

    $input_description = trim($_POST['description']);
    if (!empty($input_description)) {
        $description = $input_description;
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

    $input_tags = trim($_POST['tags']);
    if (!empty($input_tags)) {
        $tags = $input_tags;
    } else {
        $tags_err = "Please select at least one tag for the image";
    }
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
        $filesize = filesize($_FILES["image"]["name"]);
        $filetype = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
        $filename = $_FILES["image"]["name"];
        $error = $_FILES["image"]["error"];

        //Check filesize
        if ($filesize > $maxsize) {
            die(echo "file too large");
        
        //Check errors
        } else if ($error != 0){
            die(echo "erorr code: " . $error);
        
        //Check filetype
        } else if(!in_array(strtolower($filetype), $allowed)) {
            die(echo "wrong filetype");
        
        //If no problems
        } else {
            //Select image, create image name
            $image = $_FILES["image"]["tmp_name"];
            $image_name = hash_file('sha1', $image) . '.jpg';
            compressImage($image, $image_name, $quality, $destination);
        }


    }

    //If there aren't any errors
    if (empty($title_err) && empty($description_err) && empty($twitter_err) && empty($facebook_err)) {

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
            $param_title = $title;
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
                    //TODO: Setup tag array
                    foreach($tags as $tag) {
                        $sql .= "(" . $created . "," . $tag . "),";
                    };

                    //Trim last comma from SQL query
                    rtrim($sql, ",");
                    $sql .= ";";
                    
                    //If our tags are inserted successfully
                    if($stmt=$pdo->query($sql)) {
                        //TODO: Success message
                    }
                }
            }
        }
    }
}

$sql = "SELECT * FROM tags_list";
if($stmt = $pdo->query($sql)) {
    $tags_array = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
}

include_once('../includes/navbar.php');
?>
<div class="page-container">
    <main class="main">
        <div class="container">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form data">
                <div class="form-group">
                    <input type="hidden" name="MAX_FILE_SIZE" value="2097152">
                    <input type="file" name="image" id="image" class="form-control" aria-describedby="fileHelp">
                    <div id="fileHelp" class="form-text">File must be a .jpg, .jpeg, .gif, or .png file, and less than 2MB in size.</div> 
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
                    <input type="text" class="form-control <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>" name="description" id="description" value="<?php echo $description; ?>">
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
                <button type="submit" class="btn btn-dark btn-submit">Submit</button>
            </form>
        </div>
    <?php
    include_once('../includes/footer.php');
    ?>
    </main>
</div>
</body>

