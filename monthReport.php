<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/monthReport.css">
        <title>作業時間報告</title>
    </head>
    <body>
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
                <div class="graphArea">
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
                        <!--<div class="input">
                            <p>URL:</p>
                            <input type="text" class="url" id="url0" name="url0"/>
                        </div>-->
                        <div class="input">
                            <input type="text" class="time" id="time0" name="time0"/>
                            <p>時間</p>
                        </div>
                    </div>
                </div>
                <input type="submit">
            </form>
        </div>
        <form action="form.php" method="post">
            <input type="hidden" name="month" value="jan">
            <input type="hidden" name="month_jp" value="1月">
            <input type="submit" value="読込">
        </form>
        <button id="appendButton" type="button">追加</button>
        <button id="calculateButton" type="button">計算する</button>
        <a href="yearGraph.html">月選択</a>
        <div class="link">
            <a href="index.html" id="topPage">トップページ</a>
        </div>
        <script src="js/monthReport.js"></script>
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
for ($i=0; isset($_POST["time$i"]); $i++) {
    $sql = "INSERT INTO monthreport_table (date, who, category, detail, time) VALUES (?,?,?,?,?)";
    $stmt = $mysqli->prepare($sql);
    $date = $_POST["date$i"];
    $who = $_POST["who$i"];
    $category = $_POST["category$i"];
    $detail = $_POST["detail$i"];
    $time = $_POST["time$i"];
    $stmt->bind_param('ssssi', $date, $who, $category, $detail, $time);
    $stmt->execute();
}

// 切断
$stmt->close();

?>
    </body>
</html>