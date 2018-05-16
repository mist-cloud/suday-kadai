<?php
$USER       =   'root';
$PW         =   '';
$dnsinfo    =   "mysql:dbname=salesmanagement;host=localhost;charset=utf8";
try{
    $pdo    =   new PDO($dnsinfo,$USER,$PW);
    $sql    =   "INSERT INTO goods VALUES('1999','神秘的な鉛筆',300)";
    $stmt   =   $pdo->prepare($sql);
    $res    =   $stmt->execute(null);
}catch(PDOException $e){
    $res    =   $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <title>始めようPHP</title>
</head>
<body>
    <h1>goodsテーブルにレコードを追加する</h1>
    <?php
        if($res==TRUE){echo "OK";}
        else if($res==FALSE){echo "NG";}
        else{echo $res;}
    ?>
</body>
</html>