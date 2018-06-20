<?php
require_once('db.php');
$db     =   new DB();
$sql    =   "SELECT * FROM post";
$res    =   $db->executeSQL($sql,null);
$recordlist     =       "<table>\n";
while($row = $res->fetch(PDO::FETCH_ASSOC)){
    $recordlist .=  "<tr><td>{$row['id']}</td>";
    $recordlist .=  "<td>{$row['title']}</td>";
    $recordlist .=  "<td>{$row['text']}</td>";
    $recordlist .=  "<td>{$row['image']}</td>";
    $recordlist .=  "<td>{$row['regist_date']}</td>";
    $recordlist .=  "<td>{$row['update_date']}</td></tr>\n";
}
$recordlist .= "</table>\n";
?>
<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <title>db.php クラスの使い方テスト</title>
</head>
<body>
    <?php echo $recordlist;?>
</body>
</html>