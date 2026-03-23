
<?php
include("dbcontroller.php");
$db_handle = new DBController();

if(isset($_POST["department"])) {
    $query = "SELECT DISTINCT edu_system FROM class WHERE department = '" . $_POST["department"] . "'";
    $results = $db_handle->runQuery($query);
    
    echo '<option value="" disabled selected>請選擇學制</option>';
    foreach($results as $row) {
        echo '<option value="' . $row["edu_system"] . '">' . $row["edu_system"] . '</option>';
    }
}
?>