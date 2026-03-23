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
    <link rel="stylesheet" href="upload.css">
    <title>上架</title>
</head>
<body>
    <div class="imagePreview"></div>
    <div class="editImg">
        <input type="file" id="bookimg">
        <label for="bookimg">上傳圖片</label>
    </div>
    <label>isbn</label>
    <input type="text" name="isbn" id="isbn"><br>
    <label>書名</label>
    <input type="text" name="bookName" id="bookName"><br>
    <label>作者</label>
    <input type="text" name="author" id="author"><br>
    <label>出版社</label>
    <input type="text" name="publisher" id="publisher"><br>
    <label>原價</label>
    <input type="text" name="origPrice" id="origPrice"><br>


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
    <label>授課教師</label>
    <input type="text" name="teacher" id="teacher"><br>
    <label>課程種類</label>
    <input type="text" name="courseType" id="courseType"><br>
    <label>書況</label>
    <input type="text" name="bookCondition" id="bookCondition"><br>
    <label>售價</label>
    <input type="text" name="price" id="price"><br>
    <label>交易方式</label>
    <label>面交</label>
    <label>支付方式</label>
    <label>現金</label>
    <button type="submit" id="submit">上架</button>
    <button type="button" id="cancel">取消</button>
    <script src="main.js"></script>
</body>
</html>
