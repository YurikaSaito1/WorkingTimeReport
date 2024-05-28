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
$lastMonth = date("Y-m-d", strtotime("-1 year", strtotime($selectMonth)));

// 前月の残り時間とプラン取得
$sql = "SELECT COUNT(*) FROM month_table WHERE company_code = ? AND month = ? LIMIT 1";
$stmt = $mysqli -> prepare($sql);
$stmt -> bind_param('ss', $companyCode, $lastMonth);
$stmt -> execute();
$result = $stmt -> get_result();
$row_data = $result -> fetch_column();

if ($row_data != 0) {
$sql = "SELECT remaining_time, plan FROM month_table WHERE company_code = ? AND month = ?";
$stmt = $mysqli -> prepare($sql);
$stmt -> bind_param('ss', $companyCode, $lastMonth);
$stmt -> execute();
$result = $stmt->get_result();
$row_data = $result->fetch_array(MYSQLI_NUM);
$remainingTime = json_encode($row_data[0]);
} else {
    $remainingTime = 0;
}
$plan = $_POST["plan"];

$sql = "INSERT INTO month_table (company_code, month, web, overview, periodStart, periodEnd, max_time, remaining_time, plan) VALUES (?, ?, '', '', ?, ?, ?, ?, ?)";
$stmt = $mysqli->prepare($sql);
switch ($plan) {
    case "S":
        $maxTime = $remainingTime + 10;
        break;
    case "M":
        $maxTime = $remainingTime + 20;
        break;
    case "L":
        $maxTime = $remainingTime + 30;
        break;
    default:
        $maxTime = $remainingTime;
}
$stmt->bind_param('ssssdds', $companyCode, $selectMonth, $selectMonth, $selectMonth, $maxTime, $maxTime, $plan);
$stmt->execute();

$mysqli->close();

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