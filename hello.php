<!DOCTYPE html>
<?php

// 接続
$mysqli = new mysqli('localhost', 'root', '2856', 'my_app');
 
//接続状況の確認
if (mysqli_connect_errno()) {
    echo "データベース接続失敗" . PHP_EOL;
    echo "errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "error: " . mysqli_connect_error() . PHP_EOL;
    exit();
}
 
// データを挿入する
$sql = "INSERT INTO fruits_table (id, fruits, value) VALUES (6,'banana', 900)";
 
$result = $mysqli->query($sql);
 
if (!$result) {
    echo 'INSERTが失敗しました。';
}else{
	echo 'INSERTが成功しました';
}
 
// 切断
$mysqli->close();


?>
<html>
<head>
<meta charset="utf-8" />
</head>
<body>
<p>ようこそ、会員向けページへ</p>
</body>
</html>