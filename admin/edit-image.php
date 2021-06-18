<?php

//Setup database access
include_once("../includes/config.php");
include_once("../includes/db.php");

//TODO: Define variables

//Check if we have an image ID
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

            } else {
                //URL doesn't contain a valid ID
                //TODO: Handle
                header("location: ../404.php");
                exit();
            }
        } else {
            echo "Oops! Something went wrong.";
        }
    }
    //Close statement
    unset($stmt);
}


//TODO: Process form data on submit
if (isset($_POST['id']) && !empty(trim($_POST['id']))){
    
}


?>