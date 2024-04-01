<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/monthReport.css">
        <title>作業時間報告</title>
    </head>
    <body>
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

// Webサイト
$sql = "SELECT * FROM company_table WHERE company_code = ?";
$stmt = $mysqli -> prepare($sql);
$stmt -> bind_param('s', $_POST["company-code"]);
$stmt -> execute();
$result = $stmt -> get_result();
$row_data = $result->fetch_array(MYSQLI_NUM);
$companyName = mb_convert_encoding($row_data[2], "UTF-8");

require_once("lib/TCPDF-main/tcpdf.php");
$pdf = new TCPDF("P", "mm", "A4", true, "UTF-8");
$pdf->setPrintHeader( false );
$pdf->SetFont('kozminproregular', '', 11);
$pdf -> AddPage();
$pdf -> setXY(150, 10);
$pdf -> Write(10, date("Y年n月j日"));
$pdf -> setXY(30, 15);
$pdf -> setFont("", "U", 15);
$pdf -> Write(20, $companyName."　御中");
$pdf -> setXY(30, 30);
$pdf -> setFont("", "", 10);
$pdf -> MultiCell(130, 10, "下記の通り作業を行いましたのでご報告
いたします。", 0, "L");
$pdf -> setCellHeightRatio(1.5);
$pdf -> setXY(130, 30);
$pdf -> MultiCell(130, 10, "事業所名　株式会社エアグラウンド
所在地　　尼崎市南武庫之荘二丁目2-7
　　　　　新井ビル2F
電話番号　06-6435-9992
FAX番号　06-6435-9982", 0, "L");
$pdf -> setFont("", "", 20);
$pdf -> setY(50);
$pdf -> Write(40, "業務報告書", "", false, "C");

// Webサイト表示
$pdf -> setFont("", "", 11);
$pdf -> setFillColor(230);
$pdf -> MultiCell(170, 0, "Webサイト", 1, "", 1, 1, 20, 80);
$sql = "SELECT * FROM month_table WHERE company_code = ? AND month = ?";
$stmt = $mysqli -> prepare($sql);
$stmt -> bind_param('ss', $_POST["company-code"], $_POST["month"]);
$stmt -> execute();
$result = $stmt -> get_result();
$row_data = $result -> fetch_array(MYSQLI_NUM);
$pdf -> MultiCell(170, 0, $row_data[3], 1, "", 0, 1, 20);

// 対象期間表示
$pdf -> MultiCell(170, 0, "対象期間", 1, "", 1, 1, 20);
$date = date("Y年n月", strtotime($row_data[5])) . "～" . date("Y年n月", strtotime($row_data[6]));
$pdf -> MultiCell(170, 0, $date, 1, "", 0, 1, 20);

// 業務概要表示
$pdf -> MultiCell(170, 0, "業務概要", 1, "", 1, 1, 20);
$pdf -> MultiCell(170, 0, $row_data[4], 1, "", 0, 1, 20);

// データを挿入する
$sql = "SELECT * FROM monthreport_table WHERE company_code = ? AND month = ?";
$stmt = $mysqli -> prepare($sql);
$stmt -> bind_param('ss', $_POST["company-code"], $_POST["month"]);
$stmt -> execute();

// 結果を取得
$result = $stmt -> get_result();

$i = 0;

// 結果を出力
$pdf -> MultiCell(10, 0, "No.", 1, "", 1, 0, 20);
$pdf -> MultiCell(140, 0, "内容詳細", 1, "", 1, 0);
$pdf -> MultiCell(20, 0, "状況報告", 1, "", 1, 1);
while( $row_data_contents = $result->fetch_array(MYSQLI_NUM) ) {
    $i++;
    $count = substr_count($row_data_contents[5], "\r\n") + 1;
    $pdf -> MultiCell(10, $count*8, $count, 1, "", 0, 0, 20);
    $pdf -> MultiCell(140, $count*8, $row_data_contents[5], 1, "L", 0, 0);
    if ($row_data_contents[9] == "済") {
        $pdf -> MultiCell(20, $count*8, "完了", 1, "", 0, 1);
    } else {
        $pdf -> MultiCell(20, $count*8, "確認中", 1, "", 0, 1);
    }
}

$pdf -> AddPage();
// アナリティクス
$pdf -> setFont("", "", 20);
$pdf -> Write(40, "アナリティクス", "", false, "C");
$pdf -> setFont("", "", 10);
$pdf -> SetXY(10, 40);
$pdf -> Write(0, $row_data[7]);
if(!empty($_FILES)){
    $filename = $_FILES['analyticsFile']['name'];
    $uploaded_path = 'images/'.$filename;
    $result = move_uploaded_file($_FILES['analyticsFile']['tmp_name'],$uploaded_path);
    $pdf -> Image($uploaded_path, 10);
}

$mysqli->close();

$fileName = 'monthReport.pdf';
ob_end_clean();
$pdf -> Output($fileName, "I");
?>
    </body>
</html>