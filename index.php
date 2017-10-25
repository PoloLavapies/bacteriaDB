<!DOCTYPE html>
<head>
    <link rel="stylesheet" type="text/css" href="index.css">
</head>

<div id="title">Bacteria Protein DataBase</div>
<br/>

<div class="comment">タンパク質のIDからタンパク質モチーフを検索</div>
タンパク質のIDから、そのタンパク質が持っているタンパク質モチーフの一覧を表示します。
<form action="result.php" method="post">
    <input type="hidden" name="search" value="protein_id">
    <input type="text" name="text">
    <input type="submit" value="検索">
</form>

<div class="comment">遺伝子名からタンパク質モチーフを検索</div>
遺伝子名から、その遺伝子(がコードするタンパク質)が持っているタンパク質モチーフの一覧を表示します。
<form action="result.php" method="post">
    <input type="hidden" name="search" value="gene_name">
    <input type="text" name="text">
    <input type="submit" value="検索">
</form>

<div class="comment">タンパク質モチーフのIDから、タンパク質を検索</div>
タンパク質モチーフのIDから、そのタンパク質モチーフを持っているタンパク質を検索します。(この検索は時間がかかります。)
<form action="result.php" method="post">
    <input type="hidden" name="search" value="motif_id">
    <input type="text" name="text">
    <input type="submit" value="検索">
</form>

<div class="comment">バクテリアのIDから、タンパク質を検索</div>
バクテリアのIDから、そのバクテリアが持っているタンパク質の一覧を表示します。
<form action="result_protein.php" method="post">
    <input type="hidden" name="search" value="NC">
    <input type="text" name="text">
    <input type="submit" value="検索">
</form>

<div class="comment">バクテリアの学名から、タンパク質を検索</div>
バクテリアの学名から、そのバクテリアが持っているタンパク質の一覧を表示します。
<form action="result_protein.php" method="post">
    <input type="hidden" name="search" value="gakumei">
    <input type="text" name="text">
    <input type="submit" value="検索">
</form>
