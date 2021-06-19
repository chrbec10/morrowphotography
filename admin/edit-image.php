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

                $sql = "SELECT tags_rel.tag_ID, tags_list.tag FROM tags_rel INNER JOIN tags_list ON tags_list.ID = tags_rel.tag_ID WHERE tags_rel.image_ID = :image_ID";
                if ($stmt = $pdo->prepare($sql)){
                    $stmt->bindParam(":image_ID", $param_image_ID);

                    $param_image_ID = $image_ID;

                    if ($stmt->execure()){
                        $tags = json_encode( $stmt->fetchAll(PDO::FETCH_NUM) );
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


?>