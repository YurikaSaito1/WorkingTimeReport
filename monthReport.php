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
        <input type="file" id="selectedFile" multiple />
        <div id="appendCategory">
            <form id="categoryText" name="categoryText">
                <input type="text" name="inputText"/>
                <button id="appendCategoryButton" type="button">カテゴリーの追加</button>
            </form>
        </div>
        <div id="contents">
            <form action="monthReport.php" method="post">
                <div class="graphArea">
                    <div class="date">
                        <p>日付</p>
                        <input type="date" name="date">
                    </div>
                    <div class="category">
                        <select id="categoryPullDown0" name="categoryPullDown">
                            <option>動画編集</option>
                            <option>Web構築</option>
                        </select>
                    </div>
                    <div class="input">
                        <input type="text" class="time" id="time0" name="time"/>
                        <p>時間</p>
                    </div>
                </div>
                <input type="submit">
            </form>
        </div>
        <button id="appendButton" type="button">追加</button>
        <button id="calculateButton" type="button">計算する</button>
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
$sql = "INSERT INTO monthReport_table (date, category, time) VALUES (?,?,?)";
$stmt = $mysqli->prepare($sql);
$date = $_POST["date"];
$fruits = $_POST["categoryPullDown"];
$time = $_POST["time"];
$stmt->bind_param('ssi', $date, $fruits, $time);
$stmt->execute();

// 切断
$stmt->close();

?>
    </body>
</html>