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

//Search for images matching all tags using a '+' separated string
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

        if ($stmt = $pdo->execute()) {
            $result = $stmt->fetchAll();
            $ID_string = '';
            foreach ($result as $ID) {
                
            }
        }
    }
}

function nameSearch($search_string) {
    //Build SQL query
    $search_name = '%' . $search_string . '%';

    $sql = "SELECT * FROM images WHERE title LIKE :name";
    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(':name', $param_search_name);

        $param_search_name = $search_name;
    }
}

function bothSearch($tags_string, $name_string) {

}

if (isset($_GET['s']) && !empty(trim($_GET['s'])) && isset($_GET['n']) && !empty(trim($_GET['n']))){
    //Explode tags into an array
    $search_array = explode('+', trim($_GET['search']));
    $tag_count = count($search_array);

    //Use tags to build a query string
    foreach ($search_array as $tag) {
        $tags_array .= $tag;
        $tags_array .= ', ';
    }
    $tags_array = substr($tags_array, 0, -2);

    //Select image IDs from tags join table that have all the searched tags
    $sql = "SELECT image_ID FROM tags_rel WHERE tag_ID IN (:tags_array) GROUP BY image_ID HAVING COUNT(DISTINCT tag_ID) = :tag_count;"
    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(':tags_array', $param_tags_array);
        $stmt->bindParam(':tag_count', $param_tag_count);

        $param_tags_array = $tags_array;
        $param_tag_count = $tag_count;

        if ($stmt = $pdo->execute()) {

        }
    }
} else if (isset($_GET['s']) && !empty(trim($_GET['s']))) {

    $sql = "SELECT image_ID FROM tags_rel WHERE (tag_ID IN :tags_array GROUP BY image_ID HAVING COUNT(DISTINCT tag_ID) = :tag_count;"

} else if (isset($_GET['n']) && !empty(trim($_GET['n']))) {



} else {



}