<?php

//Get a string containing tags separated by commas for SQL query
function tagString($tag_string) {
    //Explode tags into an array
    $search_array = explode('+', $tag_string);
    $tag_count = count($search_array);

    //Use tags to build a query string
    foreach ($search_array as $tag) {
        $tags_array .= $tag;
        $tags_array .= ', ';
    }
    $tags_array = substr($tags_array, 0, -2);
    $tags_return = [$tags_Array, $tag_count];
    return $tags_return;
}

//Search for images matching all tags using a '+' separated string. Failure will result in false being returned, lack of results returns "none".
function tagSearch($search_string) {

    //Rearrange string for SQL and get number of tags
    $tags_array = tagString($search_string);

    //Select image IDs from tags join table that have all the searched tags
    $sql = "SELECT image_ID FROM tags_rel WHERE tag_ID IN (:tags_array) GROUP BY image_ID HAVING COUNT(DISTINCT tag_ID) = :tag_count;"
    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(':tags_array', $param_tags_array);
        $stmt->bindParam(':tag_count', $param_tag_count);

        $param_tags_array = $tags_array[0];
        $param_tag_count = $tags_array[1];

        //If it executed successfully
        if ($stmt = $pdo->execute()) {
            $result = $stmt->fetchAll();

            //If we got at least 1 ID back
            if (count($result) > 0) {

                //Process our list of IDs into a string for SQL
                $id_string = '';
                foreach ($result as $ID) {
                    $id_string .= $ID;
                    $id_string .= ', ';
                }
                $id_string = substr($ID_string, 0, -2);

                //Select all images with matching IDs
                $sql = "SELECT * FROM images WHERE ID IN (:id_array);"
                $stmt->bindParam(':id_array', $param_id_string);
                $param_id_string = $id_string;

                //Return an array of all images
                if ($stmt = $pdo->execute()) {
                    $result = $stmt->fetchAll();
                    return $result;

                } else {
                    return false;
                }

            } else {
                return 'none';
            }

        } else {
            return false;
        }

    } else {
        return false;
    }
}

//Search for all images with titles matching a search string. Failure will result in false being returned, lack of results returns "none".
function nameSearch($search_string) {
    //Build SQL query
    $search_name = '%' . $search_string . '%';

    $sql = "SELECT * FROM images WHERE title LIKE :title";
    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(':title', $param_search_name);

        $param_search_name = $search_name;

        //If SQL query succeeds, return an array of images
        if ($stmt = $pdo->execute()) {

            $result = $stmt->fetchAll();
            if (count($result) > 0) {
                return $result
            } else {
                return 'none';
            }

        } else {
            return false;
        }

    } else {
        return false;
    }
}

//Search for images matching all tags using a '+' separated string. Failure will result in false being returned, lack of results returns "none".
function bothSearch($tag_string, $title_string) {

    $search_name = '%' . $title_string . '%';

    //Rearrange string for SQL and get number of tags
    $tags_array = tagString($tag_string);

    //Select image IDs from tags join table that have all the searched tags
    $sql = "SELECT image_ID FROM tags_rel WHERE tag_ID IN (:tags_array) GROUP BY image_ID HAVING COUNT(DISTINCT tag_ID) = :tag_count;"
    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(':tags_array', $param_tags_array);
        $stmt->bindParam(':tag_count', $param_tag_count);

        $param_tags_array = $tags_array[0];
        $param_tag_count = $tags_array[1];

        //If it executed successfully
        if ($stmt = $pdo->execute()) {
            $result = $stmt->fetchAll();

            //If we got at least 1 ID back
            if (count($result) > 0) {

                //Process our list of IDs into a string for SQL
                $id_string = '';
                foreach ($result as $ID) {
                    $id_string .= $ID;
                    $id_string .= ', ';
                }
                $id_string = substr($ID_string, 0, -2);

                //Select all images with matching IDs
                $sql = "SELECT * FROM images WHERE (ID IN (:id_array)) AND (title LIKE :title;"
                $stmt->bindParam(':id_array', $param_id_string);
                $stmt->bindParam(':title', $param_title);

                $param_id_string = $id_string;
                $param_title = $search_name;

                //Return an array of all images
                if ($stmt = $pdo->execute()) {

                    $result = $stmt->fetchAll();

                    if (count($result) > 0) {
                    return $result;

                    } else {
                        return 'none';
                    }

                } else {
                    return false;
                }

            } else {
                return 'none';
            }

        } else {
            return false;
        }

    } else {
        return false;
    }
}

?>