<?php
require_once("lib/TCPDF-main/tcpdf.php");
$tcpdf = new TCPDF();
$tcpdf->setPrintHeader( false );
$tcpdf->SetFont('kozminproregular', '', 11);
$tcpdf -> AddPage();
$tcpdf -> setXY(150, 10);
$tcpdf -> Write(10, date("Y年n月j日"));
$tcpdf -> setXY(10, 25);
$tcpdf -> Write(20, "旭建設株式会社　御中");
/*$html = <<< EOF
<?php
$date = date(Y-m-d);
?>
<p>エアグラウンド株式会社</p>
EOF;
$tcpdf -> writeHTML($html);*/
$fileName = 'sample.pdf';
ob_end_clean();
$tcpdf -> Output($fileName, "I");
?>