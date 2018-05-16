<?php
$res    =   "";
if(isset($_POST['insert'])){
    $USER       =   'root';
    $PW         =   '';
    $dnsinfo    =   "mysql:dbname=salesmanagement;host=localhost;charset=utf8";
    try{
        $pdo    =   new PDO($dnsinfo,$USER,$PW);
        $sql    =   "INSERT INTO goods VALUES(?,?,?)";
        $stmt   =   $pdo->prepare($sql);
        $array  =   array($_POST['GoodsID'],$_POST['GoodsName'],$_POST['Price']);
        $res    =   $stmt->execute($array);
        if($res){
            $sql    =   "SELECT * FROM goods";
            $stmt   =   $pdo->prepare($sql);
            $stmt->execute(null);
            $res    =   "<table>\n";
            while($row  =   $stmt->fetch(PDO::FETCH_ASSOC)){
                $res    .=  "<tr><td>" .$row['GoodsID'] ."</td><td>" .$row['GoodsName'] ."</td><td>" .$row['Price'] ."</td></tr>\n";
            }
            $res    .=  "</table>\n";
        }
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
   <h1>商品マスタの登録</h1>
   <form action="" method="post">
       <label for="">GoodsID<input type="text" name="GoodsID" size="20" required></label>
       <label for="">GoodsName<input type="text" name="GoodsName" size="40" required></label>
       <label for="">Price<input type="text" name="Price" size="20" required></label>
       <input type='submit' name='insert' value=' 登録 '>
   </form>
   <?php echo $res;?>
</body>
</html>