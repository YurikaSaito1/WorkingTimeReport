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
$companyName = $_POST["companyName"];
$sql = "SELECT company_name FROM company_table_2 WHERE company_code = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('s', $companyName);            
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

$mysqli->close();
?>

        <div><p class="heading">月選択</p></div>

        <form action="monthReport.php" method="post">
            <input type="hidden" name="state" value="select">
            <input type="hidden" name="companyName" value="asahikensetsu">
            <input type="hidden" name="month" value="2024-03-01">
            <input type="submit" value="2024-03">
        </form>

        <div class="link">
            <a href="index.html" id="topPage">トップページ</a>
        </div>
    </body>
</html>