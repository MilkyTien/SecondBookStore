<?php
    include("dbcontroller.php");
    $db_handle = new DBController();
    $query = "SELECT DISTINCT department FROM class";
    $result = $db_handle->runQuery($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <title>上架</title>
</head>
<body>

    <label>部別</label><br>
    <select name="department" id="department" onchange="geteduSystem(this.value);">
        <option value="" disabled selected>請選擇部別</option>
        <?php
            foreach ($result as $department) {
                echo '<option value="' . $department["department"] . '">' . $department["department"] . '</option>';
            }
        ?>
    </select><br>
    <label>學制</label><br>
    <select name="eduSystem" id="eduSystem" onchange="getmajor(this.value);">
        <option value="" disabled selected>請選擇學制</option>
    </select><br>
    <label>科系</label><br>
    <select name="major" id="major" onchange="getgrade(this.value);">
        <option value="" disabled selected>請選擇科系</option>
    </select><br>
    <label>年級</label><br>
    <select name="grade" id="grade">
        <option value="" disabled selected>請選擇年級</option>
    </select>
    <script type="text/javascript">
        function geteduSystem(value) {
            $.ajax({
                url: 'geteduSystem.php',
                type: 'POST',
                data: 'department=' + value,
                success: function(data) {
                    $("#eduSystem").html(data);
                    getmajor();
                }
            });
        }

        function getmajor(value) {
            $.ajax({
                url: 'getmajor.php',
                type: 'POST',
                data: 'eduSystem=' + value + '&department=' + $("#department").val(),
                success: function(data) {
                    $("#major").html(data);
                    getgrade();
                }
            });
        }

        function getgrade(value) {
            $.ajax({
                url: 'getgrade.php',
                type: 'POST',
                data: 'major=' + value  + '&eduSystem=' + $("#eduSystem").val() + '&department=' + $("#department").val(),
                success: function(data) {
                    $("#grade").html(data);
                }
            });
        }
    </script>
</body>
</html>
