<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>登録中</title>
    </head>
    <body>
<?php
// DB接続
$mysqli = new mysqli('localhost', 'root', '2856', 'my_app');
if (mysqli_connect_errno()) {
    echo "データベース接続失敗" . PHP_EOL;
    echo "errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "error: " . mysqli_connect_error() . PHP_EOL;
    exit();
}

// データを挿入する
$companyCode = $_POST["company-code"];
$selectMonth = date('Y-m-d', strtotime($_POST["select-month"]));

$sql = "INSERT INTO month_table (company_code, month, web, overview, periodStart, periodEnd) VALUES (?,?,'', '', ?, ?)";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('ssss', $companyCode, $selectMonth, $selectMonth, $selectMonth);
$stmt->execute();

session_start();
$_SESSION["company-code"] = $_POST["company-code"];
$_SESSION["select-month"] = $_POST["select-month"];
header("Location: yearSelect.php");
?>
    </body>
</html>
<?php
exit;
?>