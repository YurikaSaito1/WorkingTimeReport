<?php
$company = $_POST["companyNameJan"]."　御中";
require_once("lib/TCPDF-main/tcpdf.php");
$pdf = new TCPDF();
$pdf->setPrintHeader( false );
$pdf->SetFont('kozminproregular', '', 11);
$pdf -> AddPage();
$pdf -> setXY(150, 10);
$pdf -> Write(10, date("Y年n月j日"));
$pdf -> setXY(30, 25);
$pdf -> setFont("", "U", 15);
$pdf -> Write(20, $company);
$pdf -> setXY(30, 40);
$pdf -> setFont("", "", 10);
$pdf -> MultiCell(130, 10, "下記の通り作業を行いましたのでご報告
いたします。", 0, "L");
$pdf -> setCellHeightRatio(2);
$pdf -> setXY(130, 40);
$pdf -> MultiCell(130, 10, "事業所名　株式会社エアグラウンド
所在地　　尼崎市南武庫之荘二丁目2-7
　　　　　新井ビル2F
電話番号　06-6435-9992
FAX番号　06-6435-9982", 0, "L");
$pdf -> setFont("", "", 20);
$pdf -> setY(70);
$pdf -> Write(40, "業務報告書", "", false, "C");
$pdf -> setFont("", "", 10);

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
$sql = "SELECT * FROM monthreport_table";
$stmt = $mysqli->prepare($sql);
$stmt->execute();

// 結果を取得
$result = $stmt->get_result();

$i = 0;

$pdf -> setY(100);

// 結果を出力
while( $row_data = $result->fetch_array(MYSQLI_NUM) ) {
    $i++;
    $count = substr_count($row_data[4], "\n");
    $pdf -> MultiCell(10, $count*15, $i, 1, "C", 0, 0, 20);
    $pdf -> MultiCell(140, $count*15, $row_data[4], 1, "L", 0, 0);
    if ($row_data[9] == "済") {
        $pdf -> MultiCell(20, $count*15, "完了", 1, "C", 0, 1);
    } else {
        $pdf -> MultiCell(20, $count*15, "確認中", 1, "C", 0, 1);
    }
}

$mysqli->close();

$fileName = 'sample.pdf';
ob_end_clean();
$pdf -> Output($fileName, "I");
?>