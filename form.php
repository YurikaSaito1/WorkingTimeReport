<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/monthReport.css">
        <title>作業時間報告</title>
    </head>
    <body>
        <div class="monthDisplay">
            <p><?= $_POST["month_jp"] ?></p>
        </div>
        <div class="percent">
            <svg>
                <circle class="base" cx="75" cy="75" r="70"></circle>
                <circle class="active" id="line" cx="75" cy="75" r="70"></circle>
            </svg>
            <div class="number">
                <p>残り</p>
                <h3 id="title"><div id="time_form_area">5</div><span>時間</span></h3>
            </div>
        </div>
        <!--<div id="appendCategory">
            <form id="categoryText" name="categoryText">
                <input type="text" name="inputText"/>
                <button id="appendCategoryButton" type="button">カテゴリーの追加</button>
            </form>
        </div>-->
        <div id="contents">
            <form action="monthReport.php" method="post">
                <div class="graphArea" id="graphArea">
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
$month = $_POST["month"];
$sql = "SELECT * FROM monthreport_table";
$stmt = $mysqli->prepare($sql);
$stmt->execute();

// 結果を取得
$result = $stmt->get_result();

$i = 0;

// 結果を出力
while( $row_data = $result->fetch_array(MYSQLI_NUM) ) {
  $dbdate[$i] = $row_data[0];
  $dbwho[$i] = $row_data[1];
  $dbcategory[$i] = $row_data[2];
  $dbdetail[$i] = $row_data[3];
  $dbtime[$i] = $row_data[4];
  ?>
                    <div class="formArea">
                        <div class="date">
                            <p>日付</p>
                            <input type="date" name="date<?= $i ?>" value=<?= $dbdate[$i] ?>>
                        </div>
                        <div class="who">
                            <p>誰に</p>
                            <input type="text" class="who" id="who<?= $i ?>" name="who<?= $i ?>" value=<?= $dbwho[$i] ?>>
                        </div>
                        <div class="category">
                            <p>内容</p>
                            <input type="text" class="category" id="category<?= $i ?>" name="category<?= $i ?>" value=<?= $dbcategory[$i] ?>>
                        </div>
                        <div class="detail">
                            <p>詳細</p>
                            <input type="text" class="" id="detail<?= $i ?>" name="detail<?= $i ?>" value=<?= $dbdetail[$i] ?>>
                        </div>
                        <div class="input">
                            <input type="text" class="time" id="time<?= $i ?>" name="time<?= $i ?>" value=<?= $dbtime[$i] ?>>
                            <p>時間</p>
                        </div>
                    </div>
                </div>
<?php
$i++;
}

if ($i == 0) {
?>
                    <div class="formArea">
                        <div class="date">
                            <p>日付</p>
                            <input type="date" name="date0">
                        </div>
                        <div class="who">
                            <p>誰に</p>
                            <input type="text" class="who" id="who0" name="who0"/>
                        </div>
                        <div class="category">
                            <p>内容</p>
                            <input type="text" class="category" id="category0" name="category0"/>
                        </div>
                        <div class="detail">
                            <p>詳細</p>
                            <input type="text" class="" id="detail0" name="detail0"/>
                        </div>
                        <div class="input">
                            <input type="text" class="time" id="time0" name="time0"/>
                            <p>時間</p>
                        </div>
                    </div>
                </div>
<?php
$i++;
}

$mysqli->close();

?>
        <script>
            categoryNum = <?= $i ?>;
        </script>
        <input type="submit">
        </form>
        </div>
        <div id=number>
        </div>
        <form action="form.php" method="post">
            <input type="hidden" name="month" value="jan">
            <input type="hidden" name="month_jp" value="1月">
            <input type="submit" value="読込">
        </form>
        <button id="appendButton" type="button">追加</button>
        <button id="calculateButton" type="button">計算する</button>
        <div class="link">
            <a href="yearGraph.html">月選択</a>
        </div>
        <div class="link">
            <a href="index.html" id="topPage">トップページ</a>
        </div>
        <script src="js/monthReport.js"></script>
    </body>
</html>