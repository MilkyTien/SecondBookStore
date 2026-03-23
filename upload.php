<?php
    include("dbcontroller.php");
    $db_handle = new DBController();
    $query = "SELECT DISTINCT department FROM class";
    $result = $db_handle->runQuery($query);
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>上架 </title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="upload.css">
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4 text-center">書籍上架</h2>
    
    <form id="uploadForm" method="POST">
        <div class="row">
            <div class="col-md-5">
                <div class="card shadow-sm p-4 mb-4">
                    <div class="imagePreview" id="imgPreview">尚未選擇圖片</div>
                    <div class="mb-4 text-center">
                        <input type="file" id="uploadImg" name="uploadImg" class="form-control" accept="image/*">
                    </div>

                    <table class="table-form w-100">
                        <tr>
                            <td style="width: 80px;"><label>ISBN</label></td>
                            <td><input type="text" class="form-control" name="isbn" id="isbn"></td>
                        </tr>
                        <tr>
                            <td><label>書名</label></td>
                            <td><input type="text" class="form-control" name="bookName" id="bookName"></td>
                        </tr>
                        <tr>
                            <td><label>作者</label></td>
                            <td><input type="text" class="form-control" name="author" id="author"></td>
                        </tr>
                        <tr>
                            <td><label>出版社</label></td>
                            <td><input type="text" class="form-control" name="publisher" id="publisher"></td>
                        </tr>
                        <tr>
                            <td><label>原價</label></td>
                            <td><input type="number" class="form-control" name="origPrice" id="origPrice"></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card shadow-sm p-4 mb-4">
                    <table class="table-form w-100">
                        <tr>
                            <td style="width: 100px;"><label>部別</label></td>
                            <td>
                                <select class="form-select" name="department" id="department" onchange="geteduSystem(this.value);">
                                    <option value="" disabled selected>請選擇部別</option>
                                    <?php
                                        if(!empty($result)) {
                                            foreach ($result as $department) {
                                                echo '<option value="' . $department["department"] . '">' . $department["department"] . '</option>';
                                            }
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label>學制</label></td>
                            <td>
                                <select class="form-select" name="eduSystem" id="eduSystem" onchange="getmajor(this.value);">
                                    <option value="" disabled selected>請選擇學制</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label>科系</label></td>
                            <td>
                                <select class="form-select" name="major" id="major" onchange="getgrade(this.value);">
                                    <option value="" disabled selected>請選擇科系</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label>年級</label></td>
                            <td>
                                <select class="form-select" name="grade" id="grade">
                                    <option value="" disabled selected>請選擇年級</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label>授課教師</label></td>
                            <td><input type="text" class="form-control" name="teacher" id="teacher"></td>
                        </tr>
                        <tr>
                            <td><label>課程名稱</label></td>
                            <td><input type="text" class="form-control" name="courseType" id="courseType"></td>
                        </tr>
                        <tr>
                            <td><label>書況</label></td>
                            <td><textarea class="form-control" name="bookCondition" id="bookCondition" rows="3"></textarea></td>
                        </tr>
                        <tr>
                            <td><label>售價</label></td>
                            <td><input type="number" class="form-control" name="price" id="price"></td>
                        </tr>
                        <tr>
                            <td><label>交易方式</label></td>
                            <td>面交</td>
                        </tr>
                        <tr>
                            <td><label>支付方式</label></td>
                            <td>現金</td>
                        </tr>
                    </table>
                    
                    <hr>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-outline-danger me-md-2" id="cancel">取消</button>
                        <button type="submit" class="btn btn-primary px-5" id="submit">確認上架</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    // 連動選單 AJAX 邏輯優化
    function geteduSystem(value) {
        if(!value) return;
        $.ajax({
            url: 'geteduSystem.php',
            type: 'POST',
            data: { department: value },
            success: function(data) {
                $("#eduSystem").html(data);
                $("#major").html('<option value="">請選擇科系</option>');
                $("#grade").html('<option value="">請選擇年級</option>');
            }
        });
    }

    function getmajor(value) {
        if(!value) return;
        $.ajax({
            url: 'getmajor.php',
            type: 'POST',
            data: { eduSystem: value, department: $("#department").val() },
            success: function(data) {
                $("#major").html(data);
                $("#grade").html('<option value="">請選擇年級</option>');
            }
        });
    }

    function getgrade(value) {
        if(!value) return;
        $.ajax({
            url: 'getgrade.php',
            type: 'POST',
            data: { major: value, eduSystem: $("#eduSystem").val(), department: $("#department").val() },
            success: function(data) {
                $("#grade").html(data);
            }
        });
    }

    // 圖片預覽功能
    $("#uploadImg").change(function() {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#imgPreview').html('<img src="' + e.target.result + '">');
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>

<script src="main.js"></script>
</body>
</html>