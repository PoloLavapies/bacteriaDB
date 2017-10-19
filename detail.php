<!DOCTYPE html>
<head>
<link rel="stylesheet" type="text/css" href="detail.css">
</head>

<div id="title">Bacteria Protein DataBase</div>
<br/>

<?php
$id = $_GET["id"];
$dbid_id=explode("_", $id);
$dbid=$dbid_id[0];
$id=$dbid_id[1];
$link=mysql_connect("localhost","root","");
if (!$link){
    print(mysql_error());
}
$db=mysql_select_db("bacteria_protein",$link);
$sql = "SELECT * FROM " . $dbid . " WHERE id = {$id};";
$result = mysql_query($sql);
$array = mysql_fetch_array($result);

// tableの開始
echo "<table>";

// protein_id
echo "<tr>";
td("protein_id");
$protein_id=$array[1];
td($protein_id);
echo "</tr>";

// protein_name
echo "<tr>";
td("protein_name");
$link=mysql_connect("localhost","root","");
if (!$link){
    print(mysql_error());
}
$db=mysql_select_db("bacteria_protein",$link);
$id=str_replace(".","",$protein_id);
$id=substr($id,-4,2);
$sql="SELECT protein_name FROM protein" . $id ." WHERE protein_id = " . '"' . $protein_id . '"' . " ;";
$protein_name = mysql_query($sql);
if($protein_name){
    $protein_name=mysql_fetch_array($protein_name);
    $protein_name=$protein_name[0];
} else {
    $protein_name = "";
}
td($protein_name);
echo "</tr>";

// sequence
echo "<tr>";
td("sequence");
$link=mysql_connect("localhost","root","");
if (!$link){
    print(mysql_error());
}
$db=mysql_select_db("bacteria_protein",$link);
$id=str_replace(".","",$protein_id);
$id=substr($id,-4,2);
$sql="SELECT seq FROM protein" . $id ." WHERE protein_id = " . '"' . $protein_id . '"' . " ;";
$sequence = mysql_query($sql);
if($sequence){
    $sequence = mysql_fetch_array($sequence);
    $sequence=$sequence[0];
} else {
    $sequence = "";
}
td($sequence);
echo "</tr>";

// length(protein)
echo "<tr>";
td("length");
$link=mysql_connect("localhost","root","");
if (!$link){
    print(mysql_error());
}
$db=mysql_select_db("bacteria_protein",$link);
$id=str_replace(".","",$protein_id);
$id=substr($id,-4,2);
$sql="SELECT length FROM protein" . $id ." WHERE protein_id = " . '"' . $protein_id . '"' . " ;";
$length = mysql_query($sql);
if($length){
    $length = mysql_fetch_array($length);
    $length = $length[0];
} else {
    $length = "";
}
td($length);
echo "</tr>";

// motif_id
echo "<tr>";
td("motif_id");
$motif_id=$array[2];
td($motif_id);
echo "</tr>";

// motif_name
echo "<tr>";
td("motif_name");
$link=mysql_connect("localhost","root","");
if (!$link){
    print(mysql_error());
}
$db=mysql_select_db("bacteria_protein",$link);
$id=substr($motif_id,-3,2);
$sql="SELECT motif_name FROM motif" . $id . " WHERE motif_id = " . '"' . $motif_id . '"' . " ;";
$motif_name = mysql_query($sql);
if($motif_name){
    $motif_name = mysql_fetch_array($motif_name);
    $motif_name=$motif_name[0];
} else {
    $motif_name = "";
}
td($motif_name);
echo "</tr>";echo "</tr>";

// start
echo "<tr>";
td("start");
$start=$array[3];
td($start);
echo "</tr>";

// end
echo "<tr>";
td("end");
$end=$array[4];
td($end);
echo "</tr>";

// e_val
echo "<tr>";
td("e_value");
$e_value=$array[5];
td($e_value);
echo "</tr>";

// tableの終了
echo "</table>";

// 図表
echo "<p id='fig'>Figure</p>";
echo "<img id='figure' src='img.php?id=".$protein_id."'>";

// tdタグで文字を囲う関数
function td($str) {
echo("<td>");
echo($str);
echo("</td>");
}
?>
