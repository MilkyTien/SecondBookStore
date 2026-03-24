<?php

if (isset($_POST["uploadImg"]) &&isset($_POST["isbn"]) && isset($_POST["bookName"]) && isset($_POST["price"])&& isset($_POST["department"]) && isset($_POST["eduSystem"]) && isset($_POST["major"]) && isset($_POST["grade"])&& isset($_POST["bookCondition"])) {
    
    require_once("mysqli.inc.php");
    $uploadImg = $_POST["uploadImg"];
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
    

    $sqlclass = "SELECT classNo FROM class WHERE department='$department' AND eduSystem='$eduSystem' AND major='$major' AND grade='$grade'";
    $result=mysqli_query($conn, $sqlclass);
    $row=mysqli_fetch_array($result);
    $classNo = $row['classNo'];
    //echo $classNo.$isbn.$bookName.$author.$publisher.$origPrice.$teacher.$bookCondition.$price;
    $sql = "INSERT INTO secondhandbook (isbn, bookName, author, publisher, origPrice, teacher, bookCondition, price, classNo) 
            VALUES ('$isbn', '$bookName', '$author', '$publisher', $origPrice, '$teacher', '$bookCondition', $price, $classNo)";
    mysqli_query($conn, $sql);
}
?>