<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/companySelect.css">
        <title>作業時間報告</title>
    </head>
    <body>
        <h1 class="subtitle">企業選択</h1>

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

$sql = "SELECT * FROM company_table";
$stmt = $mysqli->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$mysqli->close();

while( $row_data = $result->fetch_array(MYSQLI_NUM) ) {
    $company_code = $row_data[1];
    $company_name = $row_data[2];
?>

        <form action="yearSelect.php" method="post">
            <input type="hidden" name="company-code" value="<?= $company_code ?>">
            <input class="submitButton" type="submit" value="<?= $company_name ?>">
        </form>

<?php
}
?>

        <button class="append-button" id="append-button" type="button">追加</button>
        <div id="popup-wrapper">
            <div id="close">×</div>
            <form action="companyRegister.php" method="post">
                <div id="input-company">
                    <h2>企業コードを入力してください</h2>
                    <input id="input-company-code" type="text" name="input-company-code">
                    <h2>企業名を入力してください</h2>
                    <input id="input-company-name" type="text" name="input-company-name">
                </div>
                <input class="append-button" type="submit" value="決定">
            </form>
        </div>

        <div class="link">
            <a href="index.html" id="topPage">トップページ</a>
        </div>
        <script src="js/companySelect.js"></script>
    </body>
</html>