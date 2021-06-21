<?php
if (isset($_GET['search']) && !empty(trim($_GET['search']))){
    $search_array = trim($_GET['search']);
    //Select image IDs from tags join table that have all the searched tags
    $sql = "SELECT image_ID FROM tags_rel WHERE tag_ID IN :tags_array GROUP BY image_ID HAVING COUNT(DISTINCT tag_ID) = :tag_count";
    if ($stmt = $pdo->prepare($sql)) {
        
    }
}