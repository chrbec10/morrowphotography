<?php

//Setup database access
include_once("../includes/config.php");
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
    if imagesy($image) > imagesx($image) {

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


//TODO: Define variables

//Process form data on submit
if ($_SERVER["REQUEST METHOD"] == "POST") {

    //TODO: Validation

    //TODO: Check file upload
    if(isset($_FILES["image"]) && $_FILES["image"]["error"] == 0){

        //Set up constraints
        $allowed = array("jpg", "jpeg", "gif", "png");
        $maxsize = 5 * 1024 * 1024;

        //Set up destination for images
        $destination = "../uploads/properties/";

        //Get information about file
        $filesize = filesize($_FILES["image"]["name"]);
        $filetype = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
        $filename = $_FILES["gallery"]["name"];
        $error = $_FILES["gallery"]["error"];

    }

    //TODO: Do file creation

    //TODO: Check for input erros
    if (empty($err)) {

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
            $param_image = $image;
            $param_description = $description;
            
            //If query executes successfully
            if($stmt->execute()) {

                //Get ID of created entry
                $sql = "SELECT MAX(ID) FROM images";
                if ($stmt = $pdo->query($sql)){

                    $created = ($stmt->fetchColumn());

                    //Ready a statement to insert tags
                    $sql = "INSERT INTO tags_rel(iamge_ID, tag_ID) VALUES"
                    
                    //Build an insert row for each tag
                    //TODO: Setup tag array
                    foreach($tags as $tag){
                        $sql .= "(" . $created . "," . $tag . "),"
                    }

                    //Trim last comma from SQL query
                    rtrim($sql, ",");

                    //If our tags are inserted successfully
                    if($stmt->execute()) {
                        //TODO: Success message
                    }
                }
            }
        }
    }
}
?>



