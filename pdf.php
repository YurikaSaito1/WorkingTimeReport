<?php
require_once("lib/TCPDF-main/tcpdf.php");
$pdf = new TCPDF();
$pdf->setPrintHeader( false );
$pdf->SetFont('kozminproregular', '', 11);
$pdf -> AddPage();
$pdf -> setXY(150, 10);
$pdf -> Write(10, date("Y年n月j日"));
$pdf -> setXY(30, 25);
$pdf -> setFont("", "U", 15);
$pdf -> Write(20, "旭建設株式会社　御中");
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
/*$html = <<< EOF
<?php
$date = date(Y-m-d);
?>
<p>エアグラウンド株式会社</p>
EOF;
$tcpdf -> writeHTML($html);*/
$fileName = 'sample.pdf';
ob_end_clean();
$pdf -> Output($fileName, "I");
?>