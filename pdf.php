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
$pdf -> setRightMargin(15);
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

$pdf -> setFont("", "", 11);
$pdf -> setFillColor(230);
$pdf -> setY(80);

$sql = "SELECT * FROM month_table WHERE company_code = ? AND month = ?";
$stmt = $mysqli -> prepare($sql);
$stmt -> bind_param('ss', $_POST["company-code"], $_POST["month"]);
$stmt -> execute();
$result = $stmt -> get_result();
$row_data = $result -> fetch_array(MYSQLI_NUM);

// プラン表示
if (isset($_POST["output_plan"])) {
    $pdf -> MultiCell(30, 0, "プラン", 1, "", 1, 0, 15);
    $pdf -> MultiCell(0, 0, $row_data[9], 1, "", 0, 1, 45);
}

// Webサイト表示
if (isset($_POST["output_web"])) {
    $pdf -> MultiCell(0, 0, "Webサイト", 1, "", 1, 1, 15);
    $pdf -> MultiCell(0, 0, $row_data[3], 1, "", 0, 1, 15);
}

// 対象期間表示
$pdf -> MultiCell(0, 0, "対象期間", 1, "", 1, 1, 15);
$date = date("Y年n月", strtotime($row_data[5])) . "～" . date("Y年n月", strtotime($row_data[6]));
$pdf -> MultiCell(0, 0, $date, 1, "", 0, 1, 15);

// 業務概要表示
if (isset($_POST["output_overview"])) {
    $pdf -> MultiCell(0, 0, "業務概要", 1, "", 1, 1, 15);
    $pdf -> MultiCell(0, 0, $row_data[4], 1, "", 0, 1, 15);
}

// データを挿入する
$sql = "SELECT * FROM monthreport_table WHERE company_code = ? AND month = ?";
$stmt = $mysqli -> prepare($sql);
$stmt -> bind_param('ss', $_POST["company-code"], $_POST["month"]);
$stmt -> execute();

// 結果を取得
$result = $stmt -> get_result();

$i = 0;

// 結果を出力
if (isset($_POST["output_no"])) {
    $pdf -> MultiCell(8, 6, "No.", 1, "", 1, 0, 15);
}
if (isset($_POST["output_category"])) {
    $pdf -> MultiCell(30, 6, "内容", 1, "", 1, 0);
}
if (isset($_POST["output_detail"])) {
    $pdf -> MultiCell(130, 6, "内容詳細", 1, "", 1, 0);
}
if (isset($_POST["output_time"])) {
    $pdf -> MultiCell(10, 6, "時間", 1, "", 1, 0);
}
if (isset($_POST["output_manager"])) {
    $pdf -> MultiCell(20, 6, "担当者", 1, "", 1, 0);
}
if (isset($_POST["output_status"])) {
    $pdf -> MultiCell(0, 6, "状況", 1, "", 1, 1);
} else {
    $pdf -> ln(6);
}
while( $row_data_contents = $result->fetch_array(MYSQLI_NUM) ) {
    $i++;
    $count = substr_count($row_data_contents[5], "\r\n") + 1;
    if (isset($_POST["output_no"])) {
        $pdf -> MultiCell(8, $count*8, $i, 1, "", 0, 0, 15);
    }
    if (isset($_POST["output_category"])) {
        $pdf -> MultiCell(30, $count*8, $row_data_contents[4], 1, "L", 0, 0);
    }
    if (isset($_POST["output_detail"])) {
        $pdf -> MultiCell(130, $count*8, $row_data_contents[5], 1, "L", 0, 0);
    }
    if (isset($_POST["output_time"])) {
        $pdf -> MultiCell(10, $count*8, $row_data_contents[6], 1, "L", 0, 0);
    }
    if (isset($_POST["output_manager"])) {
        $pdf -> MultiCell(20, $count*8, $row_data_contents[8], 1, "L", 0, 0);
    }
    if (isset($_POST["output_status"])) {
        if ($row_data_contents[9] == "済") {
            $pdf -> MultiCell(0, $count*8, "完了", 1, "", 0, 1);
        } else {
            $pdf -> MultiCell(0, $count*8, "確認中", 1, "", 0, 1);
        }
    } else {
        $pdf -> MultiCell(0, $count*8, "", 0, 1);
    }
}

// アナリティクス
if (isset($_POST["output_analytics"])) {
    $pdf -> AddPage();
    $pdf -> setFont("", "", 20);
    $pdf -> Write(40, "アナリティクス", "", false, "C");
    $pdf -> setFont("", "", 10);
    $pdf -> SetXY(10, 40);

    $sql = "SELECT * FROM analytics_table WHERE company_code = ? AND month = ?";
    $stmt = $mysqli -> prepare($sql);
    $stmt -> bind_param('ss', $_POST["company-code"], $_POST["month"]);
    $stmt -> execute();
    $result = $stmt -> get_result();

    $i = 0;
    while ($row_data = $result->fetch_array(MYSQLI_NUM)){
        if (isset($row_data[3])) {
            $uploaded_path = "images/$row_data[3]";
            $pdf -> Image($uploaded_path, 10, '', 180, '', '', '', 'N');
        }
        $pdf -> Write(0, "\n".$row_data[4], '', false, '', true);
        $i++;
    }
}

$mysqli->close();

$fileName = 'monthReport.pdf';
ob_end_clean();
$pdf -> Output($fileName, "I");
?>
    </body>
</html>