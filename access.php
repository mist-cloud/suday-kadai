<?php
/*XAMMPの設定ではパスワードは空欄で問題ない*/
$USER       =   'root';
$PW         =   '';
$dnsinfo    =   "mysql:dbname=salesmanagement;host=localhost;charset=utf8";
try{
    $pd     =   new PDO($dnsinfo,$USER,$PW);
    $res    =   "接続しました";
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
    <h1>PHPでMySQLに接続する</h1>
    <?php
    echo $res;
    ?>
</body>
</html>