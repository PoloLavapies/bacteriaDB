<!DOCTYPE html>
<head>
    <link rel="stylesheet" type="text/css" href="result.css">
</head>

<div id="title">Bacteria Protein DataBase</div>
<br/>

<form id="return_btn">
    <input type="button" value="検索画面に戻る" onClick="history.back()">
</form>

<?php
// 関数定義
// thタグで文字を囲う関数
function th($str)
{
    echo("<th>");
    echo($str);
    echo("</th>");
}

// tdタグで文字を囲う関数
function td($str)
{
    echo("<td>");
    echo($str);
    echo("</td>");
}

// 1レコードを表示する関数
function hyoji($array)
{
    echo("<tr>");
    for ($j = 1; $j < 11; $j++) {
        td($array[$j]);
    }
    $link = "<form action='detail.php' method='post'><input type='hidden' name='id' value='" . $array[0] . "'><input type='submit' value='詳細'></form>";
    td($link);
    echo("</tr>");
}

// resultを展開する関数
function display_all($result, $dbid)
{
    while ($array = mysql_fetch_array($result)) {
        // protein_name
        $protein_id = $array[1];
        $id = str_replace(".", "", $protein_id);
        $id = substr($id, -4, 2);
        $sql = "SELECT protein_name FROM protein" . $id . " WHERE protein_id = " . '"' . $protein_id . '"' . " ;";
        $protein_name = mysql_query($sql);
        $protein_name = mysql_fetch_array($protein_name);
        if ($protein_name) {
            $protein_name = $protein_name[0];
        } else {
            $protein_name = "";
        }
        // gene_name
        $protein_id2 = substr($protein_id, 0, -1);
        $sql = "SELECT gene_name FROM gene_namep" . $id . " WHERE protein_id = " . '"' . $protein_id2 . '"' . " ;";
        $gene_name = mysql_query($sql);
        $gene_name = mysql_fetch_array($gene_name);
        if ($gene_name) {
            $gene_name = $gene_name[0];
        } else {
            $gene_name = "";
        }
        // gakumei
        $sql = "SELECT gakumei FROM psp" . $id . " WHERE protein_id = " . '"' . $protein_id2 . '"' . " ;";
        $gakumei = mysql_query($sql);
        $gakumei = mysql_fetch_array($gakumei);
        if ($gakumei) {
            $gakumei = $gakumei[0];
        } else {
            $gakumei = "";
        }
        // NC
        // これを実行する段階で、gakumeiに含まれる特殊な文字はすでに変換されている
        $sql = "SELECT NC FROM NC_gakumei WHERE gakumei = " . '"' . $gakumei . '"' . " ;";
        $NC = mysql_query($sql);
        $NC = mysql_fetch_array($NC);
        if ($NC) {
            $NC = $NC[0];
        } else {
            $NC = "";
        }
        // motif_name
        $motif_id = $array[2];
        $id = substr($motif_id, -3, 2);
        $sql = "SELECT motif_name FROM motif" . $id . " WHERE motif_id = " . '"' . $motif_id . '"' . " ;";
        $motif_name = mysql_query($sql);
        if ($motif_name) {
            $motif_name = mysql_fetch_array($motif_name);
            $motif_name = $motif_name[0];
        } else {
            $motif_name = "";
        }
        $new_array = array($dbid . "_" . $array[0], $array[1], $protein_name, $gene_name, $NC, $gakumei, $array[2], $motif_name, $array[3], $array[4], $array[5],);
        hyoji($new_array);
    }
}

// 目次
echo "<table>";
th("protein_id");
th("protein_name");
th("gene_name");
th("bacteria_id");
th("bacteria_name");
th("motif_id");
th("motif_name");
th("start");
th("end");
th("e_value");
th("詳細");

/*
To test
5つの検索パターンについて、SQL文が正しく発行されているか
その検索結果のnew_arrayの内容が正しいか
*/

$link = mysql_connect("localhost", "root", "");
if (!$link) {
    print(mysql_error());
}
$db = mysql_select_db("bacteria_protein", $link);

// postされたデータを受け取る
if ($_POST) {
    $search = $_POST['search'];
    $text = $_POST['text'];
    // motifのidで検索する場合
    if ($search === "motif_id") {
        if ($text !== "") {
            $dbid = "pmm" . substr($text, -2, 2);
            $sql = "SELECT * FROM " . $dbid . " WHERE motif_id = " . '" ' . $text . ' "' . " ;";
            $result = mysql_query($sql);
            display_all($result, $dbid);
        }
    } elseif ($search === "protein_id") {
        if ($text !== "") {
            $id = str_replace(".", "", $text);
            $dbid = "pmp" . substr($id, -3, 2);
            $sql = "SELECT * FROM " . $dbid . " WHERE protein_id = " . '"' . $text . '"' . " ;";
	    $result = mysql_query($sql);
            display_all($result, $dbid);
        }
    } elseif ($search === "gene_name") {
        if ($text !== "") {
            // protein_idの検索
            $id = str_replace(".", "", $text);
            $id = str_replace("/", "", $id);
            $id = str_replace("(", "", $id);
            $id = str_replace(")", "", $id);
            $id = str_replace("'", "", $id);
            $id = str_replace('"', '', $id);
            $id = str_replace("+", "", $id);
            $id = str_replace("-", "", $id);
            $id = str_replace("_", "", $id);
            $id = substr($id, 0, 1);
            $id = mb_strtolower($id);
            $sql = "SELECT protein_id FROM gene_nameg" . $id . " WHERE gene_name = " . '"' . $text . '"' . " ;";
            $result = mysql_query($sql);
            while ($array = mysql_fetch_array($result)) {
                $protein_id = $array[0];
                // sqlの発行
                $id = str_replace(".", "", $protein_id);
                $dbid = substr($id, -3, 2);
                $sql = "SELECT * FROM pmp" . $dbid . " WHERE protein_id = " . '"' . $protein_id . '"' . " ;";
                echo $sql;
                $result2 = mysql_query($sql);
                display_all($result2, $dbid);
            }
        }
    }

    // tableの終了
    echo "</table>";

    // 接続を閉じる
    mysql_close($link);
}
?>
