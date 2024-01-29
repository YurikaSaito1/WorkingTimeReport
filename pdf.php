<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/monthReport.css">
        <title>作業時間報告</title>
    </head>
    <body>
<?php
require_once("lib/TCPDF-main/tcpdf.php");
$tcpdf = new TCPDF();
$tcpdf -> AddPage();
$html = <<< EOF
<p>hello world</p>
EOF;
$tcpdf -> writeHTML($html);
$fileName = 'sample.pdf';
ob_end_clean();
$tcpdf -> Output($fileName, "I");
?>
        <div class="link">
            <a href="yearGraph.html">月選択</a>
        </div>
        <div class="link">
            <a href="index.html" id="topPage">トップページ</a>
        </div>
        <script src="js/monthReport.js"></script>
    </body>
</html>