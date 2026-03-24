<?php
    include("dbcontroller.php");
    $db_handle = new DBController();
    if (!empty($_POST["eduSystem"]) && !empty($_POST["department"])) {
        $department = $_POST["department"];
        $eduSystem = $_POST["eduSystem"];
        $query = "SELECT DISTINCT major FROM class WHERE eduSystem = '$eduSystem' AND department = '$department'";
        $result = $db_handle->runQuery($query);
?>
<option value="" disabled selected>請選擇科系</option>
<?php
        foreach ($result as $major) {
            echo '<option value="' . $major["major"] . '">' . $major["major"] . '</option>';
        }
    }   
?>