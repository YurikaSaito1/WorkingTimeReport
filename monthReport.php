<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/monthReport.css">
        <script src="js/monthReport_init.js"></script>
        <title>作業時間報告</title>
    </head>
    <body>
        <div class="contents">
            <form action="monthReport.php" method="post">
                <div class="companyArea">
                    <input type="text" class="company" id="company" name="company">
                    <span id="dummyTextBox" aria-hidden="true"></span>
                </div>
                <div class="webArea">
                    <table>
                        <tr>
                            <td><p>Webサイト：</p></td>
                            <td><input type="text" class="web" id="web" name="web"></td>
                        </tr>
                    </table>
                </div>
                <div class="overviewArea">
                    <table>
                        <tr>
                            <td><p>業務概要　：</p></td>
                            <td><pre><textarea class="overview" id="overview" name="overview"></textarea></pre></td>
                        </tr>
                    </table>
                </div>
                <div class="periodArea">
                    <table>
                        <tr>
                            <td><p>期間　　　：</p></td>
                            <td><input type="month" class="period" id="periodStart" name="periodStart"></td>
                            <td><p>～</p></td>
                            <td><input type="month" class="period" id="periodEnd" name="periodEnd"></td>
                        </tr>
                    </table>
                </div>
                <div class="graphArea">
                    <div class="formArea">
                        <table class="inputTable" id="inputTable">
                            <tr><th>日付</th><th>内容</th><th>詳細</th><th>時間</th><th>締切</th><th>担当者</th><th>作業状況</th></tr>
                            <tr id="inputTabletr0">
                                <td><input type="text" class="date" id="date0" name="date0"></td>
                                <td><input type="text" class="category" id="category0" name="category0"/></td>
                                <td><textarea class="detail" id="detail0" name="detail0"></textarea></td>
                                <td><input type="text" class="time" id="time0" name="time0"/></td>
                                <td><input type="date" class="deadline" id="deadline0" name="deadline0"></td>
                                <td><input type="text" class="manager" id="manager0" name="manager0"></td>
                                <td><input type="text" class="status" id="status0" name="status0"></td>
                                <td><button onclick="deleteRow(0)">削除</button></td>
                            </tr>
                        </table>
                    </div>
                </div>
<?php
$companyName = $_POST["companyName"];
// 企業名表示
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
$sql = "SELECT company_name FROM company_table WHERE company_code = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('s', $companyName);            
$stmt->execute();

// 結果を取得
$result = $stmt->get_result();
$row_data = $result->fetch_array(MYSQLI_NUM);
$companyNameJan = json_encode($row_data);

$mysqli->close();
?>
                <div class="appendButtonArea">
                    <button class="button" id="appendButton" type="button" onclick="append()">行追加</button>
                </div>
                <input type="hidden" name="state" value="insert">
                <input type="hidden" name="companyName" value="<?= $companyName ?>">
                <table>
                    <tr>
                        <td><input class="loadsaveButton" id="saveButton" type="submit" value="保存"></td>
            </form>
            <form action="monthReport.php" method="post">
                <input type="hidden" name="state" value="select">
                <input type="hidden" name="companyName" value="<?= $companyName ?>">
                        <td><input class="loadsaveButton" id="loadButton" type="submit" value="読込"></td>
                    </tr>
                </table>
            </form>
        </div>
        <form action="pdf.php" method="post">
        <script src="js/monthReport.js"></script>

<?php
echo <<< EOM
<script type="text/javascript">
const company = document.getElementById("company");
const dummyTextBox = document.getElementById("dummyTextBox");
company.value = $companyNameJan;
dummyTextBox.textContent = company.value;
company.style.width = dummyTextBox.clientWidth * 2 + 'px';
</script>
EOM;
// 入力欄を初期状態にするか(initial)、データベースに記録するか(insert)、データベースから挿入するか(select)
switch ($_POST["state"]) {
    case "initial":
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

        // 企業欄書き換え
        $sql = "UPDATE company_table SET web = ?, overview = ?, periodStart = cast(? as date), periodEnd = cast(? as date) WHERE company_code = 'asahikensetsu'";
        $stmt = $mysqli -> prepare($sql);
        $web = $_POST["web"];
        $overview = $_POST["overview"];
        $periodStart = date("Y-m-d", strtotime($_POST["periodStart"]));
        $periodEnd = date("Y-m-d", strtotime($_POST["periodEnd"]));
        $stmt -> bind_param('ssss', $web, $overview, $periodStart, $periodEnd);
        $stmt -> execute();

        // 企業欄再入力
        $web = json_encode($web);
        $overview = json_encode($overview);
        $periodStart = json_encode($_POST["periodStart"]);
        $periodEnd = json_encode($_POST["periodEnd"]);

        echo <<< EOM
            <script type="text/javascript">
                document.getElementById("web").value = $web;
                document.getElementById("overview").value = $overview;
                document.getElementById("periodStart").value = $periodStart;
                document.getElementById("periodEnd").value = $periodEnd;
            </script>
        EOM;

        // 業務内容の書き換え
        $sql = "DELETE FROM monthreport_table";
        $stmt = $mysqli->prepare($sql);
        $stmt->execute();
        // データを挿入する
        for ($i=0; isset($_POST["time$i"]); $i++) {
            $sql = "INSERT INTO monthreport_table (id, company_id, date, category, detail, time, deadline, manager, status) VALUES (?,?,?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($sql);
            $id = $i + 1;
            $company_id = 1;
            $date = $_POST["date$i"];
            $category = $_POST["category$i"];
            $detail = $_POST["detail$i"];
            $time = $_POST["time$i"];
            $deadline = $_POST["deadline$i"];
            $manager = $_POST["manager$i"];
            $status = $_POST["status$i"];
            $stmt->bind_param('iisssdsss', $id, $company_id, $date, $category, $detail, $time, $deadline, $manager, $status);
            $stmt->execute();
            
            // 業務内容再入力
            $date = json_encode($date);
            $category = json_encode($category);
            $detail = json_encode($detail);
            $time = json_encode($time);
            $deadline = json_encode($deadline);
            $manager = json_encode($manager);
            $status = json_encode($status);

            echo <<< EOM
                <script type="text/javascript">
                    if ($i != 0) {
                        append();
                    }
                    document.getElementById("date" + $i).value = $date;
                    document.getElementById("category" + $i).value = $category;
                    document.getElementById("detail" + $i).value = $detail;
                    document.getElementById("time" + $i).value = $time;
                    document.getElementById("deadline" + $i).value = $deadline;
                    document.getElementById("manager" + $i).value = $manager;
                    document.getElementById("status" + $i).value = $status;
                </script>
            EOM;
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

        // 企業欄取得
        $sql = "SELECT * FROM company_table WHERE company_code = 'asahikensetsu'";
        $stmt = $mysqli -> prepare($sql);
        $stmt -> execute();
        $result = $stmt -> get_result();
        $row_data = $result -> fetch_array(MYSQLI_NUM);
        $web = json_encode($row_data[3]);
        $overview = json_encode($row_data[4]);
        $periodStart = json_encode(date("Y-m", strtotime($row_data[5])));
        $periodEnd = json_encode(date("Y-m", strtotime($row_data[6])));
        echo <<< EOM
            <script type="text/javascript">
                document.getElementById("web").value = $web;
                document.getElementById("overview").value = $overview;
                let date = "";
                date = $periodStart;
                document.getElementById("periodStart").value = date;
                date = $periodEnd;
                document.getElementById("periodEnd").value = date;
            </script>
        EOM;

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
            
            <input type="hidden" id="companyNameJan" name="companyCode" value="<?= $companyName ?>">
            <input class="pdfButton" type="submit" value="PDFで出力">
        </form>
        <div class="link">
            <a href="index.html" id="topPage">トップページ</a>
        </div>
    </body>
</html>