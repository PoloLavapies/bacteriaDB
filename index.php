<!DOCTYPE html>
<head>
    <link rel="stylesheet" type="text/css" href="index.css">
</head>

<div id="title">Bacteria Protein DataBase</div>
<br/>

<div class="comment">Search protein motifs by protein ids</div>
<form action="result.php" method="post">
    <input type="hidden" name="search" value="protein_id">
    <input type="text" name="text">
    <input type="submit" value="検索">
</form>

<div class="comment">Search protein motifs by gene names</div>
<form action="result.php" method="post">
    <input type="hidden" name="search" value="gene_name">
    <input type="text" name="text">
    <input type="submit" value="検索">
</form>

<div class="comment">Search proteins by protein motif ids</div>
<form action="result.php" method="post">
    <input type="hidden" name="search" value="motif_id">
    <input type="text" name="text">
    <input type="submit" value="検索">
</form>
