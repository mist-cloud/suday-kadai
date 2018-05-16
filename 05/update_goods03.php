<?php
$res        =       "";
$USER       =   'root';
$PW         =   '';
$dnsinfo    =   "mysql:dbname=salesmanagement;host=localhost;charset=utf8";
$pdo        =   new PDO($dnsinfo,$USER,$PW);

//任意のレコードの更新ボタンがクリックされた時の処理
if(isset($_POST['update'])){
    try{
        $sql    =   "SELECT * FROM goods WHERE GoodsID=?";
        $stmt   =   $pdo->prepare($sql);
        $array  =   array($_POST['id']);
        $stmt->execute($array);
        $row    =   $stmt->fetch(PDO::FETCH_ASSOC);
        $GoodsID    =   $row['GoodsID'];
        $GoodsName  =   $row['GoodsName'];
        $Price      =   $row['Price'];
    }catch(Exception $e){
        $res    =  $e->getMessage();
    }
}

//全レコードを更新ボタン付きで表示する処理
try{
    $sql    =   "SELECT * FROM goods";
    $stmt   =   $pdo->prepare($sql);
    $array  =   null;
    $stmt->execute($array);
    $res    =   "<table>\n";
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $res    .=  "<tr><td>" .$row['GoodsID'] ."</td><td>" .$row['GoodsName'] ."</td>";
        //更新ボタンのコード
        $res    .=  <<<eof
        <td><form method='post' action=''>
        <input type='hidden' name='id' value='{$row['GoodsID']}'>
        <input type='submit' name='update' value='更新'>
        </form></td>
eof;
        $res    .=  "</tr>\n";
    }
    $res    .=  "</table>\n";
}catch(Exception $e){
    $res    =   $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <title>売上管理システム</title>
    
</head>
<body>
    <h1>商品マスタの更新</h1>
    <?php
    if(isset($_POST['update'])){
    ?>
    <form action="" method="post">
        <p>GoodsID:<?php echo $GoodsID;?></p>
        <label for="">GoodsName<input type="text" name="GoodsName" size="30" value='<?php echo $GoodsName;?>' required></label>
        <label for="">Price<input type="text" name="Price" size="10" value='<?php echo $Price;?>' required></label>
        <input type="submit" name="submit" value=" 更新 ">
    </form>
    <?php } ?>
    <?php echo $res;?>
</body>
</html>