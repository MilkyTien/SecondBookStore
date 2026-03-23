<?php
include("dbcontroller.php");
$db_handle = new DBController();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. 處理檔案上傳 (假設存入 uploads 資料夾)
    $imageName = "";
    if (isset($_FILES['uploadImg']) && $_FILES['uploadImg']['error'] == 0) {
        $targetDir = "bookimg/";
        $imageName = time() . "_" . $_FILES["uploadImg"]["name"]; // 避免檔名重複
        move_uploaded_file($_FILES["uploadImg"]["tmp_name"], $targetDir . $imageName);
    }

    // 2. 接收表單文字資料
    $isbn = $_POST['isbn'];
    $bookName = $_POST['bookName'];
    $author = $_POST['author'];
    $publisher = $_POST['publisher'];
    $origPrice = $_POST['origPrice'];
    $department = $_POST['department'];
    $eduSystem = $_POST['eduSystem'];
    $major = $_POST['major'];
    $grade = $_POST['grade'];
    $teacher = $_POST['teacher'];
    $courseType = $_POST['courseType'];
    $bookCondition = $_POST['bookCondition'];
    $price = $_POST['price'];
    $classNo="SELECT classNo FROM class Where department='$department' AND eduSystem='$eduSystem' AND major='$major' AND grade='$grade' AND teacher='$teacher' AND courseType='$courseType'";


    // 3. 寫入資料庫 (請根據你的資料表名稱調整 SQL)
    // 這裡建議使用 Prepared Statements 以防止 SQL 注入
    $sql = "INSERT INTO secondhandbook (isbn, bookimg, bookName, author, publisher, origPrice, teacher, bookCondition, price,classNo) 
            VALUES ($isbn, '$imageName', '$bookName', '$author', '$publisher', $origPrice, '$teacher', '$bookCondition', $price ,classNo)";
    
    if($result) {
        echo "<script>alert('上架成功！'); location.href='index.php';</script>";
    } else {
        echo "<script>alert('上架失敗，請稍後再試。');</script>";
    }
}
?>