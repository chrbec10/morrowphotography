<?php
include_once("../includes/config.php");
include_once("../includes/db.php");

//if we got IDs for image and property
if (isset($_GET['id']) && !empty(trim($_GET['id']))) {

    $image_ID = trim($_GET['id']);

    //Grab image filepaths
    $sql = "SELECT image FROM images WHERE ID = :id";

    if($stmt = $pdo->prepare($sql)){

        $stmt->bindParam(":id", $param_image_ID);
        $param_image_ID = $image_ID;

        if($stmt->execute()){
            //If we got an assigned image
            if($stmt->rowCount() > 0){
                $row = $stmt->fetch();
                $imageName = $row['image'];
                //Delete all matching files from the server uploads folder
                foreach(glob('../uploads/*' . $imageName) as $image){
                    unlink($image);
                }
                $param_imageID = '';

                //Delete the gallery entry from gallery table
                $sql = "DELETE FROM images WHERE ID = :id";
                if($stmt = $pdo->prepare($sql)){
                    $stmt->bindParam(":id", $param_image_ID);
                    $param_image_ID = $image_ID;
                    //If all goes well, direct back to edit page with success message
                    if($stmt->execute()){
                        echo("deleted");
                    //else direct back to edit page with error message
                    } else {
                        header("location: edit-listing.php?id=" . $propertyID . "&r=7");
                    }
                } else {
                    header("location: edit-listing.php?id=" . $propertyID . "&r=7");
                }
            } else {
                header("location: edit-listing.php?id=" . $propertyID . "&r=8&e=" . $imageID);
            }
        } else {
            header("location: edit-listing.php?id=" . $propertyID . "&r=7");
        }
    } else {
        header("location: edit-listing.php?id=" . $propertyID . "&r=7");
    }

} else {
    header("location: ../404.php", 404);
}

?>