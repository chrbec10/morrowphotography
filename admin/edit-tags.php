<?php
$title = "Manage Tags";
include_once("../includes/header.php");
require_once("../includes/db.php");

if (isset($_GET['r']) && ($_GET['r'] != '')){
    $r = trim($_GET['r']);

    if (isset($_GET['e']))
        $e = trim($_GET['e']);

    switch($r){
        case 1:
            $response_div = 'alert-success';
            $response_txt = 'New tag created successfully';
            break;
        case 2:
            $response_div = 'alert-success';
            $response_txt = 'Changes submitted successfully';
            break;
        case 3:
            $response_div = 'alert-danger';
            $response_txt = 'Failed to delete entry.';
            break;
        case 4:
            $response_div = 'alert-danger';
            $response_txt = 'Image not found in database.';
            break;
        case 5:
            $response_div = 'alert-success';
            $response_txt = 'Tag deleted successfully.';
            break;
        case 6:
            $response_div = 'alert-danger';
            $response_txt = 'Something went wrong. Please try again later.';
        default:
            $response_div = 'd-none';
            $response_txt = '';
            break;
    }
} else {
    $response_div = 'd-none';
    $response_txt = '';
}


$new = $edit = $edit_text = $delete = '';
$new_err = $edit_err = $edit_text_err = $delete_err = '';

//Retrieve tags list to populate dropdowns
$sql = "SELECT * FROM tags_list ORDER BY tag";
if($stmt = $pdo->query($sql)) {
    $tags_array = $stmt->fetchAll();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //If we're adding a new tag
    if ($_POST['frmname'] == 'new'){

        $input_tag = trim($_POST['newTag']);

        //Strip special characters from tag name
        if(!empty($input_tag)){
            $new = preg_replace('/[^A-Za-z0-9\-\'\s]/', '', $input_tag);

            
        //If the new tag name isn't empty after being stripped prep SQL insert
            if (!empty($new)){ 
                $sql = "INSERT INTO tags_list(tag) VALUES (:new)";

                if ($stmt = $pdo->prepare($sql)) {
                    $stmt->bindParam(":new", $param_new);

                    //Set parameters
                    $param_new = $new;

                    if($stmt->execute()){
                        header("location: edit-tags.php?r=1");
                    }
                } else {
                    header("location: edit-tags.php?r=6");
                }

            } else {
                $new_err = 'Please enter a valid tag. English characters, hyphens, and apostrophes only.';
            }
        } else {
            $new_err = 'Please enter the name of a tag to add.';
        }
    
    //If we're editing an existing tag
    } else if ($_POST['frmname'] == 'edit') {
        $input_tag = trim($_POST['editTag']);
        $input_id = trim($_POST['chooseTag']);
        if(!empty($input_tag)){
            $edit = preg_replace('/[^A-Za-z0-9\-\'\s]/', '', $input_tag); // Removes special chars
            $id = preg_replace('/[^0-9]/', '', $input_id);
            if(!empty($edit) && !empty($id)) {
                $sql = "UPDATE tags_list SET tag = :tag WHERE ID = :ID";

                if ($stmt = $pdo->prepare($sql)) {
                    $stmt->bindParam(":tag", $param_tag);
                    $stmt->bindParam(":ID", $param_id);

                    //Set parameters
                    $param_tag = $edit;
                    $param_id = $id;

                    if($stmt->execute()){
                        header("location: edit-tags.php?r=2");

                    } else {
                        header("location: edit-tags.php?r=6");
                    }

                } else {
                    header("location: edit-tags.php?r=6");
                }

            } else {
                $delete_err = 'Please choose a valid tag to delete';
            }

        } else {
            $edit_err = 'Please enter a new name for the tag.';
        }

    //If we're deleting an existing tag
    } else if ($_POST['frmname'] == 'delete'){
        
        $input_delete = trim($_POST['deleteTag']);
        if(!empty($input_delete)){
            //Strip non-numbers from tag ID
            $delete = preg_replace('/[^0-9]/', '', $input_delete);
            if(!empty($delete)) {
                $sql = "DELETE FROM tags_list WHERE ID = :ID";

                if ($stmt = $pdo->prepare($sql)) {

                    $stmt->bindParam(":ID", $param_delete);

                    //Set parameters
                    $param_delete = $delete;

                    if($stmt->execute()){
                        header("location: edit-tags.php?r=5");

                    } else {
                        header("location: edit-tags.php?r=6");
                    }

                } else {
                    header("location: edit-tags.php?r=6");
                }

            } else {
                $delete_err = 'Please choose a valid tag to delete';
            }

        } else {
            $delete_err = 'Please choose a tag to delete';
        }

    //This shouldn't happen. Throw an error.
    } else {
        header("location: edit-tags.php?r=6");
    }
}

include_once('includes/navbar.php');
?>

<div class="page-container">
    <main class="main">
        <div class="container">
        <div class="alert <?php echo $response_div; ?>"><?php echo $response_txt; ?></div>
        <?php if($response_div != "d-none") {echo "<br>";} ?>
        <h1 class="text-center">Manage Tags</h1>
        <br>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <h5>Add New Tag</h5>
                <div class="form-group">
                    <label for="newTag">Tag Name</label>
                    <input type="text" class="form-control <?php echo (!empty($new_err)) ? 'is-invalid' : ''; ?>" name="newTag" id="newTag" value="<?php echo $new; ?>">
                    <span class="invalid-feedback"><?php echo $new_err;?></span>
                </div>
                <br>
                <button type="submit" name="frmname" value="new" class="btn btn-dark btn-submit float-end">Submit</button>
            </form>
            <br>
            <br>
            <hr>
            <br>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <h5>Edit Existing Tag</h5>
                <div class="form-group">
                    <label for="chooseTag">Choose a tag to modify</label>
                    <select class="form-control <?php echo (!empty($edit_err)) ? 'is-invalid' : ''; ?>" id="chooseTag" name="chooseTag">
                        <?php
                        foreach($tags_array as $tag){
                                echo"<option value='" . $tag['ID'] . "'>" . $tag['tag'] . "</option>";
                        }
                        ?>
                    </select>
                    <span class="invalid-feedback"><?php echo $edit_err;?></span>
                </div>
                <br>
                <div class="form-group">
                    <label for="editTag">New tag text</label>
                    <input type="text" class="form-control <?php echo (!empty($edit_text_err)) ? 'is-invalid' : ''; ?>" name="editTag" id="editTag" value="<?php echo $edit_text; ?>">
                    <span class="invalid-feedback"><?php echo $edit_text_err;?></span>
                </div>
                <br>
                <button type="submit" name="frmname" value="edit" class="btn btn-dark btn-submit float-end">Submit</button>
            </form>
            <br>
            <br>
            <hr>
            <br>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <h5>Delete Tag</h5>
                <div class="form-group">
                    <label for="deleteTag">Choose a tag to delete</label>
                    <select class="form-control <?php echo (!empty($delete_err)) ? 'is-invalid' : ''; ?>" id="deleteTag" name="deleteTag">
                        <?php
                        foreach($tags_array as $tag){
                                echo"<option value='" . $tag['ID'] . "'>" . $tag['tag'] . "</option>";
                        }
                        ?>
                    </select>
                <span class="invalid-feedback"><?php echo $delete_err;?></span>
                </div>
                <br>
                <button type="submit" name="frmname" value="delete" class="btn btn-delete float-end">Delete</button>
            </form>
            <br>
            <br>
            <hr>
        </div>
    <?php
    include_once('../includes/footer.php');
    ?>
    
    </main>
</div>
<script>
    $('.btn-delete').on('click', function () {
        return confirm('Are you sure you want to delete this tag?\nThis action cannot be undone.');
    });
</script>
</body>