<?php
require_once(__DIR__ . '/db.php');

//Get a string containing tags separated by commas for SQL query
function tagString($search_string) {

    //Explode tags into an array, stripping non-numeric tags
    $tags_array = array_filter(array_map('trim', explode(' ', $search_string)), 'is_numeric');

    //If there are no numeric tags
    if (empty($tags_array)) {
        return false;

    //Else condense them back down into something useful
    } else {
        return implode(', ', $tags_array);
    }
}

//Count the number of numeric tags
function tagCount($search_string) {
    $tags_array = array_filter(array_map('trim', explode(' ', $search_string)), 'is_numeric');
    return count($tags_array);
}

//Search for images matching all tags using a '+' separated string. Failure will result in false being returned, lack of results returns "none".
function tagSearch(PDO $pdo, $search_string) {

    //Rearrange string for SQL and get number of tags
    if (!$tags_array = tagString($search_string)) return 'none';
    //$tags_array = tagString($search_string);
    $tags_count = tagCount($search_string);

    //Select image IDs from tags join table that have all the searched tags
    $sql = "SELECT image_ID FROM tags_rel WHERE tag_ID IN ($tags_array) GROUP BY image_ID HAVING COUNT(*) = :tag_count";

    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(':tag_count', $param_tag_count);

        $param_tag_count = $tags_count;

        //If it executed successfully
        if ($stmt->execute()) {

            $result = $stmt->fetchAll();
            unset($stmt);

            //If we got at least 1 ID back
            if (count($result) > 0) {

                //Process our list of IDs into a string for SQL
                $id_string = '';
                foreach ($result as $ID) {
                    $id_string .= $ID['image_ID'];
                    $id_string .= ', ';
                }
                $id_string = substr($id_string, 0, -2);

                //Select all images with matching IDs
                $sql = "SELECT * FROM images WHERE ID IN (" . $id_string . ")";
                if ($stmt = $pdo->query($sql)){

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
function nameSearch(PDO $pdo, $search_string) {
    //Build SQL query
    $search_name = '%' . $search_string . '%';
    

    $sql = "SELECT * FROM images WHERE title LIKE :title";
    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(':title', $param_search_name);

        $param_search_name = $search_name;

        //If SQL query succeeds, return an array of images
        if ($stmt->execute()) {

            $result = $stmt->fetchAll();
            unset($stmt);
            if (count($result) > 0) {
                return $result;
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
function bothSearch(PDO $pdo, $tag_string, $title_string) {

    $search_name = '%' . $title_string . '%';

    //Rearrange string for SQL and get number of tags
    if (!$tags_array = tagString($tag_string)) return 'none';
    $tags_count = tagCount($tag_string);

    //Select image IDs from tags join table that have all the searched tags
    $sql = "SELECT image_ID FROM tags_rel WHERE tag_ID IN ($tags_array) GROUP BY image_ID HAVING COUNT(*) = :tag_count";
    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(':tag_count', $param_tag_count);

        $param_tag_count = $tags_count;
        
        //If it executed successfully
        if ($stmt->execute()) {
            $result = $stmt->fetchAll();
            unset($stmt);

            //If we got at least 1 ID back
            if (count($result) > 0) {

                //Process our list of IDs into a string for SQL
                $id_string = '';
                foreach ($result as $ID) {
                    $id_string .= $ID['image_ID'];
                    $id_string .= ', ';
                }

                $id_string = substr($id_string, 0, -2);
                //Select all images with matching IDs
                $sql = "SELECT * FROM images WHERE ID IN ($id_string) AND UPPER(title) LIKE UPPER(:title)";

                if ($stmt = $pdo->prepare($sql)){
                    $stmt->bindParam(':title', $param_title);

                    $param_title = $search_name;

                    //Return an array of all images
                    if ($stmt->execute()) {

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

function getAll(PDO $pdo) {
    $sql = "SELECT * FROM images";
    if ($stmt = $pdo->query($sql)) {
        $result = $stmt->fetchAll();

        if (count($result) > 0) {
            return $result;

        } else {
            return 'none';
        }
    
    } else {
        return false;
    }
}

?>