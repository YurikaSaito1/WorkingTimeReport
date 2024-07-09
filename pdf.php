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

// 企業情報の取得
$sql = "SELECT * FROM month_table WHERE company_code = ? AND month = ?";
$stmt = $mysqli -> prepare($sql);
$stmt -> bind_param('ss', $_POST["company-code"], $_POST["month"]);
$stmt -> execute();
$result = $stmt -> get_result();
$row_data = $result -> fetch_array(MYSQLI_ASSOC);

$output = $_POST["output"]; // 選択した出力項目の配列

// 出力選択項目の保存
if (!empty($_POST["save-checkbox"])) {
    if (array_search("plan", $output) !== false) $plan = true;
    if (array_search("web", $output) !== false) $web = true;
    if (array_search("overview", $output) !== false) $overview = true;
    if (array_search("analytics", $output) !== false) $analytics = true;
    if (array_search("no", $output) !== false) $no = true;
    if (array_search("category", $output) !== false) $category = true;
    if (array_search("detail", $output) !== false) $detail = true;
    if (array_search("time", $output) !== false) $time = true;
    if (array_search("manager", $output) !== false) $manager = true;
    if (array_search("status", $output) !== false) $status = true;
    $sql = "UPDATE checkbox_table SET plan = ?, web = ?, overview = ?, analytics = ?, no = ?, category = ?, detail = ?, time = ?, manager = ?, status = ? WHERE company_code = ?";
    $stmt = $mysqli -> prepare($sql);
    $stmt -> bind_param('iiiiiiiiiis', $plan, $web, $overview, $analytics, $no, $category, $detail, $time, $manager, $status, $_POST["company-code"]);
    $stmt -> execute();
}

// プラン・残り時間表示
if ($plan !== false) {
    $pdf -> MultiCell(30, 0, "プラン", 1, "", 1, 0, 15);
    $pdf -> MultiCell(30, 0, $row_data["plan"], 1, "", 0, 0, 45);
    $pdf -> MultiCell(30, 0, "繰越時間", 1, "", 1, 0, 75);
    $pdf -> MultiCell(30, 0, $row_data["remaining_time"], 1, "", 0, 0, 105);
    $pdf -> MultiCell(30, 0, "月毎追加時間", 1, "", 1, 0, 135);
    switch ($row_data["plan"]) {
        case "S":
            $pdf -> MultiCell(0, 0, "10", 1, "", 0, 1, 165);
            break;
        case "M":
            $pdf -> MultiCell(0, 0, "20", 1, "", 0, 1, 165);
            break;
        case "L":
            $pdf -> MultiCell(0, 0, "30", 1, "", 0, 1, 165);
            break;
        default:
            $pdf -> MultiCell(0, 0, "10", 1, "", 0, 1, 165);
            break;
    }
}

// Webサイト表示
if ($web !== false) {
    $pdf -> MultiCell(0, 0, "Webサイト", 1, "", 1, 1, 15);
    $pdf -> MultiCell(0, 0, $row_data["web"], 1, "", 0, 1, 15);
}

// 対象期間表示
$pdf -> MultiCell(0, 0, "対象期間", 1, "", 1, 1, 15);
$date = date("Y年n月", strtotime($row_data["periodStart"])) . "～" . date("Y年n月", strtotime($row_data["periodEnd"]));
$pdf -> MultiCell(0, 0, $date, 1, "", 0, 1, 15);

// 業務概要表示
if ($overview !== false) {
    $pdf -> MultiCell(0, 0, "業務概要", 1, "", 1, 1, 15);
    $pdf -> MultiCell(0, 0, $row_data["overview"], 1, "", 0, 1, 15);
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
$pdf -> MultiCell(15, 6, "", 0, "", 0, 0, 0);
if (array_search("no", $output) !== false) {
    $pdf -> MultiCell(8, 6, "No.", "LTB", "", 1, 0);
}
if (array_search("category", $output) !== false) {
    $pdf -> MultiCell(30, 6, "内容", "LTB", "", 1, 0);
}
if (array_search("detail", $output) !== false) {
    $pdf -> MultiCell(130, 6, "内容詳細", "LTB", "", 1, 0);
}
if (array_search("time", $output) !== false) {
    $pdf -> MultiCell(10, 6, "時間", "LTB", "", 1, 0);
}
if (array_search("manager", $output) !== false) {
    $pdf -> MultiCell(20, 6, "担当者", "LTB", "", 1, 0);
}
if (array_search("status", $output) !== false) {
    $pdf -> MultiCell(0, 6, "状況", 1, "", 1, 1);
} else {
    $pdf -> MultiCell(0, 6, "", "TBR", "", 1, 1);
}
while( $row_data_contents = $result->fetch_array(MYSQLI_NUM) ) {
    $i++;
    $count = substr_count($row_data_contents[5], "\r\n") + 1;
    $pdf -> MultiCell(15, $count*8, "", 0, "", 0, 0, 0);
    if (array_search("no", $output) !== false) {
        $pdf -> MultiCell(8, $count*8, $i, "LTB", "", 0, 0);
    }
    if (array_search("category", $output) !== false) {
        $pdf -> MultiCell(30, $count*8, $row_data_contents[4], "LTB", "", 0, 0);
    }
    if (array_search("detail", $output) !== false) {
        $pdf -> MultiCell(130, $count*8, $row_data_contents[5], "LTB", "L", 0, 0);
    }
    if (array_search("time", $output) !== false) {
        $pdf -> MultiCell(10, $count*8, $row_data_contents[6], "LTB", "L", 0, 0);
    }
    if (array_search("manager", $output) !== false) {
        $pdf -> MultiCell(20, $count*8, $row_data_contents[8], "LTB", "L", 0, 0);
    }
    if (array_search("status", $output) !== false) {
        if ($row_data_contents[9] == "済") {
            $pdf -> MultiCell(0, $count*8, "完了", 1, "", 0, 1);
        } else {
            $pdf -> MultiCell(0, $count*8, "確認中", 1, "", 0, 1);
        }
    } else {
        $pdf -> MultiCell(0, $count*8, "", "TBR", "", 0, 1);
    }
}

// アナリティクス
if (array_search("analytics", $output) !== false) {
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