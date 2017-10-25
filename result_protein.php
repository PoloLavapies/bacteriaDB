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
    for ($j = 0; $j < 5; $j++) {
        td($array[$j]);
    }
    $link = "<form action='result.php' method='post'><input type='hidden' name='search' value='protein_id'><input type='hidden' name='text' value='" . $array[0] . "'><input type='submit' value='詳細'></form>";
    td($link);
    echo("</tr>");
}

// resultを展開する関数
// resultにはprotein_idのリストが格納されている
function display_all($result, $NC, $gakumei)
{
    while ($array = mysql_fetch_array($result)) {
        // protein_name
        $protein_id = $array[0];
        $id = str_replace(".", "", $protein_id);
        $id = substr($id, -3, 2);
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
        $new_array = array($array[0], $protein_name, $gene_name, $NC, $gakumei);
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
th("detail");

$link = mysql_connect("localhost", "root", "");
if (!$link) {
    print(mysql_error());
}
$db = mysql_select_db("bacteria_protein", $link);

// postされたデータを受け取る
if ($_POST) {
    $search = $_POST['search'];
    $text = $_POST['text'];
    // NCからprotein_idを検索
    if ($search === "NC") {
        if ($text !== "") {
            // 学名を検索
            $sql = "SELECT gakumei FROM NC_gakumei WHERE NC = " . '"' . $text . '"' . " ;";
	    $gakumei = mysql_query($sql);
            $gakumei = mysql_fetch_array($gakumei);
            if ($gakumei) {
                $gakumei = $gakumei[0];
            } else {
                $gakumei = "";
            }
            // protein_idを検索
            $id = substr($gakumei, 0, 1);
            $id = mb_strtolower($id);
            $sql = "SELECT * FROM pss" . $id . " WHERE gakumei = " . '"' . $gakumei . '"' . " ;";
            $result = mysql_query($sql);
            // 結果表示
            display_all($result, $text, $gakumei);
        }
        // 学名からprotein_idを検索する場合
    }
    if ($search === "gakumei") {
        if ($text !== "") {
            // protein_idを検索
            $text = str_replace("'", "", $text);
            $text = str_replace('"', '', $text);
            $text = str_replace('[', '', $text);
            $text = str_replace(']', '', $text);
            $text = str_replace('(', '', $text);
            $text = str_replace(')', '', $text);
            $text = str_replace(',', '', $text);
            $text = str_replace('-', '', $text);
            $text = str_replace(';', '', $text);
            $text = str_replace('^', '', $text);
            while (true) {
                $first = substr($text, 0, 1);
                if ($first === "") {
                    $text = substr($text, 1);
                } else if ($first === "") {
                    $text = substr($text, 1);
                } else {
                    break;
                }
            }
            $id = substr($text, 0, 1);
	    $id = mb_strtolower($id);
            $sql = "SELECT protein_id FROM pss" . $id . " WHERE gakumei = " . '"' . $text . '"' . " ;";  
$result = mysql_query($sql);
            // NCを検索
            $sql = "SELECT NC FROM NC_gakumei WHERE gakumei = " . '" ' . $text . ' "' . " ;";
            $NC = mysql_query($sql);
            $NC = mysql_fetch_array($NC);
            if ($NC) {
                $NC = $NC[0];
            } else {
                $NC = "";
            }
            // 結果表示
            display_all($result, $NC, $text);
        }
    }

    // tableの終了
    echo "</table>";

    // 接続を閉じる
    mysql_close($link);
}
?>

