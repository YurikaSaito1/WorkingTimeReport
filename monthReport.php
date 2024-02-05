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
                <div class="companyArea">
                    <input type="text" class="company" id="company" name="company">
                </div>
                <div class="webArea">
                    <input type="text" class="web" id="web" name="web">
                </div>
                <div class="graphArea">
                    <div class="formArea">
                        <table class="inputTable" id="inputTable">
                            <tr><th>日付</th><th>誰に</th><th>内容</th><th>詳細</th><th>時間</th><th>締切</th><th>担当者</th><th>作業状況</th></tr>
                            <tr>
                                <td><input type="text" class="date" id="date0" name="date0"></td>
                                <td><input type="text" class="who" id="who0" name="who0"/></td>
                                <td><input type="text" class="category" id="category0" name="category0"/></td>
                                <td><textarea class="detail" id="detail0" name="detail0"></textarea></td>
                                <td><input type="text" class="time" id="time0" name="time0"/></td>
                                <td><input type="date" class="deadline" id="deadline0" name="deadline0"></td>
                                <td><input type="text" class="manager" id="manager0" name="manager0"></td>
                                <td><input type="text" class="status" id="status0" name="status0"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <input type="hidden" name="state" value="insert">
                <input type="submit">
            </form>
        </div>
        <form action="monthReport.php" method="post">
            <input type="hidden" name="state" value="select">
            <input type="submit" value="読込">
        </form>
        <button id="appendButton" type="button" onclick="append()">追加</button>
        <button id="calculateButton" type="button">計算する</button>
        <a href="yearGraph.html">月選択</a>
        <div class="link">
            <a href="index.html" id="topPage">トップページ</a>
        </div>
        <script src="js/monthReport.js"></script>
<?php
// 入力欄を初期状態にするか(initial)、データベースに記録するか(insert)、データベースから挿入するか(select)
switch ($_POST["state"]) {
    case "initial":
        $company = json_encode($_POST["company"]);
        echo <<< EOM
        <script type="text/javascript">
            initial($company);
        </script>
        EOM;
        break;
    case "insert":
        // 接続
        $mysqli = new mysqli('localhost', 'root', '2856', 'my_app');
        
        //接続状況の確認
        if (mysqli_connect_errno()) {
            echo "データベース接続失敗" . PHP_EOL;
            echo "errno: " . mysqli_connect_errno() . PHP_EOL;
            echo "error: " . mysqli_connect_error() . PHP_EOL;
            exit();
        }

        $sql = "DELETE FROM monthreport_table";
        $stmt = $mysqli->prepare($sql);
        $stmt->execute();
        // データを挿入する
        for ($i=0; isset($_POST["time$i"]); $i++) {
            $sql = "INSERT INTO monthreport_table (date, who, category, detail, time, deadline, manager, status) VALUES (?,?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($sql);
            $date = $_POST["date$i"];
            $who = $_POST["who$i"];
            $category = $_POST["category$i"];
            $detail = $_POST["detail$i"];
            $time = $_POST["time$i"];
            $deadline = $_POST["deadline$i"];
            $manager = $_POST["manager$i"];
            $status = $_POST["status$i"];
            $stmt->bind_param('ssssisss', $date, $who, $category, $detail, $time, $deadline, $manager, $status);
            $stmt->execute();
        }

        // 切断
        $stmt->close();
        break;
    case "select":
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

        // 結果を出力
        while( $row_data = $result->fetch_array(MYSQLI_NUM) ) {
            $current = json_encode($row_data);

            if ($i != 0) {
                echo <<< EOM
                <script type="text/javascript">
                    append();
                </script>
                EOM;
            }

            echo <<<EOM
            <script type="text/javascript">
                inputForm($i, $current);
            </script>
            EOM;

            $i++;
        }

        $mysqli->close();

        break;
}
?>
        <form action="pdf.php" method="post">
            <input type="submit" value="PDFで出力">
        </form>
    </body>
</html>