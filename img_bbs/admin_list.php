<?php
//dbクラスを読み込み
require_once('db.php');
$db     =   new DB();

//レコードを表示する
$sql    =   "SELECT * FROM post"; // ORDER BY id DESC　はpostテーブルのidの降順で読み出す追加の記述。DESCは降順の意味。
$res    =   $db->executeSQL($sql,null);
$recordlist = "";
while($row = $res->fetch(PDO::FETCH_ASSOC)){
	$recordlist .= "<tr>\n";
	$recordlist .= "<th class=''>{$row['id']}</th>\n";
	$recordlist .= "<td class=''>{$row['title']}</td>\n";
	$recordlist .= "<td class=''>{$row['text']}</td>\n";
	$recordlist .= "<td class='text-center'><div class='thumbnail'><img src='{$row['image']}' alt=''></div></td>\n";
	$recordlist .= "<td class='text-center'>{$row['regist_date']}<br>/<br>{$row['update_date']}</td>\n";
	$recordlist .= "<td class='text-center'>";
	$recordlist .= "<input type='button' value='変更' onclick='location.href='admin_edit.html?id='+'1''><br><input type='button' value='削除' onclick='msgDelete('1')'>\n";
	$recordlist .= "</td>\n";
	$recordlist .= "</tr>\n";
}

//Sequel Proでデータを消してもidが1に戻らないのは正常。消したデータが何かの拍子で戻った場合誤作動を起こすため。
//変更と削除ボタンの実装をしたい。変更は$_GETでも良いが、削除は勝手に削除されないように$_POSTにする。

if(isset($_POST[''])){
	
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>画像掲示板（管理画面）</title>
	<!-- BootstrapのCSS読み込み -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/custom/common.css" rel="stylesheet">
	<link href="css/custom/admin.css" rel="stylesheet">
	<!-- jQuery読み込み -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<!-- BootstrapのJS読み込み -->
	<script src="js/bootstrap.min.js"></script>
	<script src="js/custom/script.js"></script>
</head>
<body class="admin">

	<div class="container">
		<div class="page-header">
			<h1>投稿管理</h1>
		</div>

		<div class="row">
			<div class="col-lg-12">

			<table class="table table-bordered admin-table">
					<thead>
						<tr>
							<th class="col-md-1">ID</th>
							<th class="col-md-2">タイトル</th>
							<th class="col-md-4">本文</th>
							<th class="col-md-2">画像</th>
							<th class="col-md-2">投稿日時 / 変更日時</th>
							<th class="col-md-1">操作</th>
						</tr>
					</thead>
					<tbody>
						<?php echo $recordlist?>
						<!--<tr>
							<td class="">1</td>
							<td class="">○○○○○○○○○○○○○○○○○○○○○○○○○○○○○</td>
							<td class="">○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○</td>
							<td class="text-center"><div class="thumbnail"><img src="img/uploads/2.jpg" alt=""></div></td>
							<td class="text-center">0000/00/00 00:00<br>0000/00/00 00:00</td>
							<td class="text-center">
								<input type="button" value="変更" onclick="location.href='admin_edit.html?id='+'1'"><br>
								<input type="button" value="削除" onclick="msgDelete('1')">
							</td>
						</tr>-->
					</tbody>
				</table>

			</div>
		</div>
		<!-- /row -->
		<p class="gotoTopBtn"><a href="#"><img src="./img/btn_pagetop.png" alt="ページの先頭へ"></a></p>
	</div>
	<!-- /container -->
<script type="text/javascript" language="javascript">
<!--
function msgDelete(id) {
	// 確認ダイアログを表示
	if(window.confirm("IDが「"+id+"」のデータを削除します。よろしいですか？")){
		// OKボタンを押下した場合
		location.href = "admin_delete.html?id="+id;

		// document.getElementById("form").id.value=id;
		// document.getElementById("form").submit();
	}
}
// -->
</script>
</body>
</html>