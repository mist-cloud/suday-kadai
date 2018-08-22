<?php
    //黄色い本の292ページ
    $str    =   "Ninjin, 300";
    list($name, $price) = explode(",", $str);   //list構文を使って、explode()関数で複数の変数に値を分割して代入できる
    echo "name:$name, price:$price";


    //トランザクションとは複数の処理が全て成立しないと次の処理を行わなないように出来る処理
?>