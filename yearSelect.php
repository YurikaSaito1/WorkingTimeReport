<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/yearSelect.css">
        <title>作業時間報告</title>
    </head>
    <body>
        <div class="companyArea">
            <input type="text" class="company" id="company" name="company">
            <span id="dummyTextBox" aria-hidden="true"></span>
        </div>

<?php
// 接続
$mysqli = new mysqli('localhost', 'root', '2856', 'my_app');
$mysqli->set_charset('utf8');
        
//接続状況の確認
if (mysqli_connect_errno()) {
    echo "データベース接続失敗" . PHP_EOL;
    echo "errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "error: " . mysqli_connect_error() . PHP_EOL;
    exit();
}

// 企業名表示
session_start();
if (isset($_SESSION["company-code"])) {
    $companyCode = $_SESSION["company-code"];
} else if (isset($_POST["company-code"])) {
    $companyCode = $_POST["company-code"];
}
$sql = "SELECT company_name FROM company_table WHERE company_code = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('s', $companyCode);            
$stmt->execute();
$result = $stmt->get_result();
$row_data = $result->fetch_array(MYSQLI_NUM);
$companyNameJan = json_encode($row_data);
echo <<< EOM
<script type="text/javascript">
const company = document.getElementById("company");
const dummyTextBox = document.getElementById("dummyTextBox");
company.value = $companyNameJan;
dummyTextBox.textContent = company.value;
company.style.width = dummyTextBox.clientWidth * 2 + 'px';
</script>
EOM;

$sql = "SELECT month FROM month_table";
$stmt = $mysqli->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$mysqli->close();
?>

        <div><p class="heading">月選択</p></div>

<?php

while( $row_data = $result->fetch_array(MYSQLI_NUM) ) {
    $current = date("Y-m-d", strtotime($row_data[0]));
    $currentJan = date("Y年n月", strtotime($row_data[0]));
?>

        <form action="monthReport.php" method="post">
            <input type="hidden" name="state" value="select">
            <input type="hidden" name="company-code" value="asahikensetsu">
            <input type="hidden" name="month" value="<?= $current ?>">
            <input class="submitButton" type="submit" value="<?= $currentJan ?>">
        </form>
<?php
}
?>
        <button class="append-button" id="append-button" type="button">追加</button>
        <div id="popup-wrapper">
            <div id="close">×</div>
            <form action="yearRegister.php" method="post">
                <div id="select-month">
                    <h2>年月を選択してください</h2>
                    <input id="select-month" type="month" name="select-month">
                </div>
                <input type="hidden" name="company-code" value="<?= $companyCode ?>">
                <input class="append-button" type="submit" value="決定">
            </form>
        </div>

        <div class="link">
            <a href="index.html" id="topPage">トップページ</a>
        </div>
        <script src="js/yearSelect.js"></script>
    </body>
</html>
<?php
exit;
?>