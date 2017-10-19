<?php
$id = $_GET["id"];
$link=mysql_connect("localhost","root","");
$db=mysql_select_db("bacteria_protein",$link);
$dbid=str_replace(".","",$id);
$dbid="pmp" . substr($dbid,-3,2);
$sql="SELECT motif_id, start, end FROM " . $dbid ." WHERE protein_id = " . '"' . $id . '"' . " ;";
$result = mysql_query($sql);
$arrays = array();
while ($array = mysql_fetch_array($result)){
    array_push($arrays,$array);
}
$entries = count($arrays);

// TODO タンパク質の全長により横線のサイズを変える
// 画像全体の生成
// (画像自体は1500*100)
$img = ImageCreate(2000, 400);
$bground = ImageColorAllocate($img, 255, 255, 255);
//ImageFilledRectangle($img, 0, 0, 2000, 400, $bground);

// 1本の横線
// TODO:1500のところは、タンパク質の長さに変えたい
$black = ImageColorAllocate($img, 0, 0, 0);
//ImageFilledRectangle($img, 0, 94, 1500, 96, $black);

// すでに文字の存在する空間の配列の配列
$filled = array();
$filled[] = array();

// すでに画像(四角)の存在する空間の配列
$filled_square = array();

// 対応する画像を描画していく
for($i=0;$i<$entries;$i++){
    $array = $arrays[$i];
    $motif_id = $array[0];
    $id = substr($motif_id, -3, 2);
    $sql = "SELECT motif_name FROM motif" . $id . " WHERE motif_id = " . '"' . $motif_id . '"' . " ;";
    $motif_name = mysql_query($sql);
    if ($motif_name) {
        $motif_name = mysql_fetch_array($motif_name);
        $motif_name = $motif_name[0];
    } else {
        $motif_name = "";
    }
    $start = $array[1];
    $end = $array[2];
    // 変更する前のmotif_idを出力のために控えておく
    $motif_id_old = $motif_id;
    // ここから描画
    $motif_id = substr($motif_id, 3,5);
    echo $motif_id;
    while(true){
        $first = substr($motif_id, 0, 1);
        if ($first == "0") {
           $motif_id = substr($motif_id, 1); 
        } else {
            break;
        }
    }
    // motif_idを元にして色を自動で決める
    // 色は26**3パターン考える
    $motif_id = (int)$motif_id;
    $red = $motif_id / 676;
    $red = floor($red);
    $red = $red * 8 + 55;
    $amari = $motif_id % 676;
    $green = $amari / 26;
    $green = floor($green);
    $green = $green * 8 + 55;
    $blue = ( $amari % 26 ) * 8 + 55;
    $color = ImageColorAllocate($img, $red, $green, $blue);
    $included_square = 0;
    // 画像が重ならないかどうか
    for ($l = $start; $l < $end; $l++){
        if (in_array($l ,$filled_square)){
            $included_square = 1;
        }
    }
    // 重ならないなら、線より上に表示
    if ($included_square == 0){
        //ImageFilledRectangle($img, $start, 45, $end, 93, $color);
        for ($l = $start; $l < $end; $l++){
            $filled_square[] = $l;
        }
    }
    // 重なるなら、線より下に表示
    else {
        //ImageFilledRectangle($img, $start, 97, $end, 145, $color);
    }
    // 描画する文字
    $message = $motif_id_old." ".$motif_name;
    $str_length = strlen($message);
    $str_end = $start + $str_length*10;

    // 文字を描画する位置を探す
    // 何段ずらすかを決めるための変数
    $position = 0;
    for ($l = 0; $l < count($filled); $l++){
        $array = $filled[$l];
        // startからstr_endの点について、arrayに含まれる点が含まれるかどうか調べる
        // $arrayにstartからstr_endまでの点のどれかが含まれるかを表す変数
        $included = 0;
        for ($n = $start; $n < $str_end; $n++){
            if (in_array($n, $array)){
                $included = 1;
            }
        }
        // $arrayにstartからendまでのどの点も含まれない場合
        if ($included == 0){            
            $position = $l;
            // startからendまでの座標を配列に追加
            for ($n = $start; $n < $str_end; $n++){
                $array[] = $n;
            }
            $filled[$l] = $array;
            break;
        }
        // 含まれる場合、次のループへ
        // ただし、$l=$length-1の場合、配列を追加する
        else {
            if ($l == count($filled)-1){
                $position = count($filled);
                $filled[] = array();
                // 追加した配列
                $array = $filled[$l + 1];
                // その配列にポジションを追加
                for ($n = $start; $n < $str_end; $n++){
                    $array[] = $n;
                }
                $filled[$l + 1] = $array;
                break;
            }
        }
    }   
    // 文字の描画
    if ($position == 0) {
        $draw_position = 30;
    }
    else if ($position == 1) {
        $draw_position = 15;
    }
    else if ($position == 2) {
        $draw_position = 0;
    }
    else {
        $draw_position = 100 + $position * 15;
    }
    //imagestring($img, 5, $start, $draw_position, $message, $black);
}

//header('Content-Type: image/png');
//ImagePNG($img);
?>
