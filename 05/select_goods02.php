<?php
$res        =       "";
$USER       =   'root';
$PW         =   '';
$dnsinfo    =   "mysql:dbname=salesmanagement;host=localhost;charset=utf8";
$pdo        =   new PDO($dnsinfo,$USER,$PW);

//selectタグを生成
$sql        =   "SELECT GoodsID, GoodsName FROM goods";
$stmt       =   $pdo->prepare($sql);
$stmt->execute(null);
$selectTag  =   "<select name='GoodsID'>\n";
$selectTag  .=  "<option value=''>選択してください</option>\n";
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $selectTag .=  "<option value=" .$row['GoodsID'] .">" .$row['GoodsName'] ."</option>\n";
}
$selectTag  .=  "</select>";

//レコードの選択
if(isset($_POST['select'])){
    try{
        $sql    =   "SELECT * FROM goods WHERE GoodsID=?";
        $stmt   =   $pdo->prepare($sql);
        $array  =   array($_POST['GoodsID']);
        $stmt->execute($array);
        $res    =   "<table>\n";
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $res .= "<tr><td>" .$row['GoodsID'] ."</td><td>" .$row['GoodsName'] ."<td><td>" .$row['Price'] ."</td></tr>\n";
        }
        $res    .=  "</table>\n";
    }catch(Exception $e){
        $res    =   $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <title>売上管理システム</title>
    
</head>
<body>
    <h1>商品マスタの選択</h1>
    <form action="" method="post">
        <label for="">GoodsID<?php echo $selectTag;?></label>
        <input type="submit" name="select" value=" 表示 ">
    </form>
    <?php echo $res;?>
</body>
</html>