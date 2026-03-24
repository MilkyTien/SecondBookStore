

<?php
    include("dbcontroller.php");
    $db_handle = new DBController();
    $query = "SELECT DISTINCT eduSystem FROM class";
    $result2 = $db_handle->runQuery($query);
?>
<?php
session_start();
include("mysqli.inc.php");

$msg = "";
$msgType = "";
$activePanel = ""; // register 或空字串

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $formType = $_POST['form_type'] ?? '';

    // =========================
    // 登入
    // =========================
    if ($formType === "login") {
        $sId = trim($_POST['login_sId'] ?? "");
        $password = trim($_POST['login_password'] ?? "");
        $activePanel = "";               //  $activePanel = "" 留在登入面板 ;  $activePanel = "register"; // 留在註冊面板

        if (!empty($sId) && !empty($password)) {
            $stmt = $conn->prepare("SELECT sId, password FROM student WHERE sId = ?");
            $stmt->bind_param("s", $sId);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if ($row) {
                if (password_verify($password, $row['password'])) {
                    $_SESSION['sId'] = $row['sId'];
                    header("Location: index.php");
                    exit;
                } else {
                    $msg = "密碼錯誤";
                    $msgType = "danger";
                }
            } else {
                $msg = "查無此學號";
                $msgType = "danger";
            }

            $stmt->close();
        } else {
            $msg = "請輸入學號與密碼";
            $msgType = "danger";
        }
    }

    // =========================
    // 註冊
    // =========================
    if ($formType === "register") {
        $sId = trim($_POST['reg_sId'] ?? '');
        $name = trim($_POST['reg_name'] ?? '');
        $password = trim($_POST['reg_password'] ?? '');
        $major = trim($_POST['reg_major'] ?? '');
        $eduSystem = trim($_POST['reg_eduSystem'] ?? '');
        $mail = trim($_POST['reg_mail'] ?? '');
        $activePanel = "register"; // 留在註冊面板

        if ($sId !== '' && $name !== '' && $password !== '' && $major !== '' && $eduSystem !== '' && $mail !== '') {

            // 檢查學號是否重複
            $stmtCheck = $conn->prepare("SELECT sId FROM student WHERE sId = ?");
            $stmtCheck->bind_param("s", $sId);
            $stmtCheck->execute();
            $resultCheck = $stmtCheck->get_result();

            if ($resultCheck->num_rows > 0) {
                $msg = "學號重複";
                $msgType = "danger";
            } else {
                $hashPassword = password_hash($password, PASSWORD_DEFAULT);

                $stmtInsert = $conn->prepare("INSERT INTO student (sId, name, password, major, eduSystem, mail) VALUES (?, ?, ?, ?, ?, ?)");
                $stmtInsert->bind_param("ssssss", $sId, $name, $hashPassword, $major, $eduSystem, $mail);

                if ($stmtInsert->execute()) {
                    $msg = "註冊成功，請登入";
                    $msgType = "success";
                    $activePanel = ""; // 成功後切回登入面板
                } else {
                    $msg = "註冊失敗：" . $conn->error;
                    $msgType = "danger";
                }

                $stmtInsert->close();
            }

            $stmtCheck->close();
        } else {
            $msg = "請完整填寫所有欄位";
            $msgType = "danger";
        }
    }
}
?>
<!doctype html>
<html lang="zh-Hant">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>登入 / 註冊</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="login_reg.css" rel="stylesheet">
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-container <?php echo ($activePanel === 'register') ? 'right-panel-active' : ''; ?>" id="authContainer">

        <!-- 註冊區 -->
        <div class="form-container register-container">
            <form method="post" action="" class="auth-form">
                <input type="hidden" name="form_type" value="register">
                <h1 class="auth-title">註冊</h1>

                <?php if (!empty($msg) && $activePanel === 'register') { ?>
                    <div class="alert alert-<?php echo $msgType; ?> w-100 text-center mb-3">
                        <?php echo htmlspecialchars($msg); ?>
                    </div>
                <?php } ?>

                <div class="row w-100">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label custom-label">學號</label>
                            <input 
                                type="text" 
                                name="reg_sId" 
                                class="form-control custom-input" 
                                placeholder="請輸入學號"
                                value="<?php echo htmlspecialchars($_POST['reg_sId'] ?? ''); ?>"
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label custom-label">姓名</label>
                            <input 
                                type="text" 
                                name="reg_name" 
                                class="form-control custom-input" 
                                placeholder="請輸入姓名"
                                value="<?php echo htmlspecialchars($_POST['reg_name'] ?? ''); ?>"
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label custom-label">密碼</label>
                            <div class="password-box">
                                <input 
                                    type="password" 
                                    name="reg_password" 
                                    id="reg_password"
                                    class="form-control custom-input password-input" 
                                    placeholder="請輸入密碼"
                                >
                                <span class="eye-btn" data-target="reg_password">
                                    <img src="./img/eye_open.png" alt="eye">
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label custom-label">學制</label>
                            <select name="eduSystem" id="eduSystem" class="form-control custom-input"  onchange="getmajor(this.value);">
                                <option value="">選擇學制</option>
                                <?php
                                        if(!empty($result2)) {
                                            foreach ($result2 as $eduSystem) {
                                                echo '<option value="' . $eduSystem["eduSystem"] . '">' . $eduSystem["eduSystem"] . '</option>';
                                            }
                                        }
                                    ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label custom-label" for="reg_major">科系</label>
                            <select name="reg_major" id="reg_major" class="form-control custom-input">
                                <option value="">選擇科系</option>
                                
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label custom-label">Mail</label>
                            <input 
                                type="email" 
                                name="reg_mail" 
                                class="form-control custom-input" 
                                placeholder="請輸入信箱"
                                value="<?php echo htmlspecialchars($_POST['reg_mail'] ?? ''); ?>"
                            >
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn auth-btn mt-2">註冊</button>
            </form>
        </div>

        <!-- 登入區 -->
        <div class="form-container login-container">
            <form method="post" action="" class="auth-form">
                <input type="hidden" name="form_type" value="login">
                <h1 class="auth-title">Login</h1>

                <?php if (!empty($msg) && $activePanel !== 'register') { ?>
                    <div class="alert alert-<?php echo $msgType; ?> w-100 text-center mb-3">
                        <?php echo htmlspecialchars($msg); ?>
                    </div>
                <?php } ?>

                <div class="w-100">
                    <div class="mb-3">
                        <label class="form-label custom-label">學號</label>
                        <input 
                            type="text" 
                            name="login_sId" 
                            class="form-control custom-input" 
                            placeholder="請輸入學號"
                            value="<?php echo htmlspecialchars($_POST['login_sId'] ?? ''); ?>"
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label custom-label">密碼</label>
                        <div class="password-box">
                            <input 
                                type="password" 
                                name="login_password" 
                                id="login_password"
                                class="form-control custom-input password-input" 
                                placeholder="請輸入密碼"
                            >
                            <span class="eye-btn" data-target="login_password">
                                <img src="./img/eye_open.png" alt="eye">
                            </span>
                        </div>
                    </div>

                    <div class="text-end mb-3">
                        <a href="password.php" class="forgot-link">忘記密碼</a>
                    </div>
                </div>

                <button type="submit" class="btn auth-btn">登入</button>
            </form>
        </div>

        <!-- 滑動面板 -->
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h2>歡迎回來</h2>
                    <p>已經有帳號了嗎？<br>直接登入進入系統吧。</p>
                    <button type="button" class="ghost-btn" id="showLogin">登入</button>
                </div>

                <div class="overlay-panel overlay-right">
                    <h2>第一次使用？</h2>
                    <p>建立你的帳號，開始使用系統功能。</p>
                    <button type="button" class="ghost-btn" id="showRegister">註冊</button>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
const authContainer = document.getElementById("authContainer");
const showRegister = document.getElementById("showRegister");
const showLogin = document.getElementById("showLogin");

showRegister.addEventListener("click", () => {
    authContainer.classList.add("right-panel-active");
});

showLogin.addEventListener("click", () => {
    authContainer.classList.remove("right-panel-active");
});

// 顯示 / 隱藏密碼
document.querySelectorAll(".eye-btn").forEach(btn => {
    btn.addEventListener("click", function () {
        const targetId = this.getAttribute("data-target");
        const input = document.getElementById(targetId);
        const img = this.querySelector("img");

        if (input.type === "password") {
            input.type = "text";
            img.src = "./img/eye_close.png";
        } else {
            input.type = "password";
            img.src = "./img/eye_open.png";
        }
    });
});
function getmajor(value) {
        if(!value) return;
        $.ajax({
            url: 'getmajor_login.php',
            type: 'POST',
            data: { eduSystem: value},
            success: function(data) {
                $("#reg_major").html(data);
            }
        });
    }
</script>

</body>
</html>