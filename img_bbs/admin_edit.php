<?php
//dbクラスを読み込み
require_once('db.php');
$db     =   new DB();

//パラメータからidを抽出
$id = $_GET["id"];


//現在時刻をエポックタイムラインスタンプ(UNIXタイム)を得る
$now = time();
$regist = date("Y/m/d H:i:s", $now);

//条件分岐で処理を分ける
if(isset($_POST['submit'])){//submitを押されたらアップデートする
		//トランザクション開始
		$db->beginTransaction();
		try {
			$sql	=	"UPDATE post SET title=? text=? image=? regist_date=? WHERE id='$id'";
			$array  =   array($_POST['title'],$_POST['text'],$regist);
			$db->executeSQL($sql,$array); //タイトルとテキスト、投稿日時を一時的に実行
			//ファイルのパスを指定する
			$tmp_file   =   $_FILES["image"]["tmp_name"]; //アップロードされて一時フォルダに保存されたファイルのパス
			$save_file  =   dirname(__FILE__)."/{$id}.$extention"; //画像の移動先の指定、ファイル名の指定。__FILE__は定数。開いているこのファイルのパスとファイル名。dirname().'';でファイル名の部分を置き換えている。
			$image_url	=	"{$id}.$extention"; //画像を表示するためのパス。SQLに保存されるパス。
			//ファイルを指定ディレクトリに保存
			if (!move_uploaded_file($tmp_file, $save_file)) {
				$error = "システムエラーです。管理者に報告してください。"; 
			}
			//ファイルのパスを上書きするSQL文を準備
			$sql    =   "UPDATE post SET image = ? WHERE id = {$id}";
			$array  =   array($image_url);
			$db->executeSQL($sql,$array);
			//投稿完了時に出すメッセージをヒアドキュメントで変数に格納
			$success = "投稿しました。";
			$db->commit();//トランザクションをコミット
		} catch (Exception $e) {
			// トランザクション取り消し
			$db->rollBack();
			throw $e;
		}

}else{//submitを押されていない場合はレコードを表示する
	try{
		//入力済みのレコードを表示する。現状、正常に表示されている。
		$sql	=	"SELECT * FROM post WHERE id='$id'";
		$res    =   $db->executeSQL($sql,null);
		$recordlist = "";
		while($row = $res->fetch(PDO::FETCH_ASSOC)){//ifに変えてみてもいいかも
			$recordlist .= "<table class='table table-bordered admin-table'>\n<tbody>\n";
			$recordlist .= "<tr>\n<th class='th-inverse col-sm-3 col-xs-4'>ID</th>\n<td>{$row['id']}</td>\n</tr>\n";
			$recordlist .= "<tr>\n<th class='th-inverse'>タイトル</th>\n<td>\n<input type='text' class='form-control' id='inputText' maxlength='50' value='{$row['title']}' name='title'>\n<p class='alert alert-danger mt10 mb0'>タイトルは50文字までにしてください。</p>\n</td>\n</tr>\n";
			$recordlist .= "<tr>\n<th class='th-inverse'>本文</th>\n<td>\n<textarea class='form-control' rows='2' id='textArea' name='text'>{$row['text']}</textarea>\n<p class='alert alert-danger mt10 mb0'>本文は500文字までにしてください。</p>\n</td>\n</tr>\n";
			$recordlist .= "<tr class='tr-img'>\n
				<th class='th-inverse'>画像</th>\n
				<td>\n
					<div class='row'>\n
						<div class='col-sm-7 img-block'>\n
							<input type='file' accept='.png,.jpg,.gif' value='参照'>\n
							<p class='alert alert-danger mt10 mb0'>画像は.jpg、.gif、.pngのいずれかにしてください。</p>\n
						</div>\n
						<div class='col-sm-5 img-block'>\n
							<a href='img/uploads/{$row['image']}' target='_blank'>\n
								<img src='{$row['image']}' alt='' width='60%' align='middle'>\n
							</a>\n
							<label class='ml10'>\n
								<input type='checkbox'> 画像を削除\n
							</label>\n
						</div>\n
					</div>\n
				</td>\n
			</tr>\n";
			$recordlist .= "</tbody>\n</table>\n";
		}
	}catch(Exception $e){
		return false;
	}
}

//print_r($sql);
//exit;

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
</head>
<body class="admin">

	<div class="container">
		<div class="page-header">
			<h1>投稿管理</h1>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<form action="" method="post" enctype="multipart/form-data">
					<?php echo $recordlist?>
					<div class="mb30">
						<button type="button" class="btn btn-default" onclick="location.href='admin_list.html'">戻る</button>
						<button type="submit" class="btn btn-default" onclick="return confirm('登録します。よろしいですか？');" name="submit">登録</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- /container -->

</body>
</html>