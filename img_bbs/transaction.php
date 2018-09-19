<?php
    //サンプルプログラム
    //$str    =   "Ninjin, 300";
    //list($name, $price) = explode(",", $str);   //list構文を使って、explode()関数で複数の変数に値を分割して代入できる
    //echo "name:$name, price:$price";




//トランザクションとは複数の処理が全て成立しないと次の処理を行わなないように出来る処理

$fp     =       fopen("ejdic-hand-utf8.txt", "r");//テキストファイルを開く
$db     =       new PDO("sqlite:ejdict.db");//SQliteデータベースと接続する
//エラーが出たら例外を出して処理を止めるようにする
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//データベースを定義する
$create_query = <<< __SQL__
CREATE TABLE words (
    word_id     INTEGER PRIMARY KEY, /* ID */
    title       TEXT, /* 英単語 */
    body        TEXT /* 意味 */
);
__SQL__;
$db->exec($create_query);

//英単語をデータベースに挿入する準備をする
$insert_query = <<< __SQL__
INSERT INTO words (title, body)
VALUES (?. ? )
__SQL__;
$insert_stmt = $db->prepare($insert_query);
//テキストファイルを読み込んで辞書に挿入する
while (($line = fgets($fp)) !== false ) {
    list($title,$body) = explode("¥t", $line, 2); //タブで区切る
    if ($title == "") continue; //空なら代入しない
    $insert_stmt->execute(array($title, $body)); //データベースに挿入
    echo $title."¥n"; //経過報告を出力
}
$db->commit(); //コミットする
echo "*** comleted ***";

?>