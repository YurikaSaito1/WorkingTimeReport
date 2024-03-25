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

$companyCode = $_POST["input-company-code"];
$companyName = $_POST["input-company-name"];

// company_codeに重複がないか
$sql = "SELECT company_code FROM company_table";
$stmt = $mysqli -> prepare(($sql));
$stmt -> execute();
$result = $stmt->get_result();

while( $row_data = $result->fetch_array(MYSQLI_NUM) ) {
    if ($row_data[0] == $companyCode) {
        session_start();
        $_SESSION["input-error"] = "error";
        header("Location: companySelect.php");
        exit;
    }
}

// データを挿入する
$sql = "INSERT INTO company_table (company_code, company_name) VALUES (?,?)";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('ss', $companyCode, $companyName);
$stmt->execute();

$mysqli->close();

header("Location: companySelect.php");
?>
    </body>
</html>