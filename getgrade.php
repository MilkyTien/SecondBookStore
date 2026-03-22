<option value="">請選擇年級</option>
<?php
    include("dbcontroller.php");
    $db_handle = new DBController();
    if (!empty($_POST["major"]) && !empty($_POST["eduSystem"]) && !empty($_POST["department"])) {
        $major = $_POST["major"];
        $eduSystem = $_POST["eduSystem"];
        $department = $_POST["department"];
        $query = "SELECT DISTINCT grade FROM class WHERE major = '$major' AND eduSystem = '$eduSystem' AND department = '$department'";
        $result = $db_handle->runQuery($query);
?>
<?php
        foreach ($result as $grade) {
            echo '<option value="' . $grade["grade"] . '">' . $grade["grade"] . '</option>';
        }
    }   
?>