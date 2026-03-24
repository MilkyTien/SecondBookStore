<?php
    include("dbcontroller.php");
    $db_handle = new DBController();
    if (!empty($_POST["department"])) {
        $department = $_POST["department"];
        $query = "SELECT DISTINCT eduSystem FROM class WHERE department = '$department'";
        $result = $db_handle->runQuery($query);
?>
<option value="">請選擇學制</option>
<?php
        foreach ($result as $eduSystem) {
            echo '<option value="' . $eduSystem["eduSystem"] . '">' . $eduSystem["eduSystem"] . '</option>';
        }
    }   
?>
