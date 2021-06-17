<?php

//Setup database access
include_once("../includes/config.php");
include_once("../includes/db.php");

//TODO: Define variables

//Process form data on submit
if ($_SERVER["REQUEST METHOD"] == "POST") {

    //TODO: Validation

    //TODO: Check file upload

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



