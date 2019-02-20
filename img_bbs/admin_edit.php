<?php
//dbクラスを読み込み
require_once('db.php');
$db     =   new DB();

//パラメータからidを抽出
$id = $_GET["id"];

//条件分岐で処理を分ける
if(isset($_POST['submit'])){//submitを押されたらアップデートする
		//変更する場合
		//ここにトランザクションを使ったバリデーションを書く
		//index.phpを参考にできる
		//ただ今回の場合、エラー表示箇所が1箇所にまとまっていないのでエラーチェックのバリデーションで配列の
		//$errormsg[]の[]内に何か入れる



		//トランザクション開始
		$db->beginTransaction();
		//エラーチェックのための変数に値を代入
		$title	=	$_POST["title"];
		$text	=	$_POST["text"];
		//エラーメッセージの配列の初期化
		$errormsg = array();
		//タイトルの必須チェック
		if ($title = null) {
			$errormsg[1] = "タイトルを入力してください。";
		}
		//テキストの文字数チェック mb_strlenは文字数を得る
		if (mb_strlen($text) > 500 ) {
			$errormsg[2] = "本文は500文字までにしてください。";
		}
		//画像拡張子チェック
		$extention = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION); //画像の拡張子を取得 PATHINFO_EXTENSIONは定数
		//エラーチェックは機能しているがテキストファイルをアップした場合でも画像のエラーが出てしまう。。。if文を使ってUPLOAD_ERR_OKを使って解決する
		if (!in_array($extention,array('jpg','jpeg','gif','png'))){
			$errormsg[3] = "画像は.jpg、.gif、.pngのいずれかにしてください。";
		}
		//今回はこれは使えない気がする
		//$errors = ""; //表示用の変数を作成
		//foreach($errormsg as $error) { //foreach文のasの後の変数は配列の中身を一個ずつ代入する入れ物
		//	$errors .= $error."<br>";
		//}

		// 将来的にif文で$errormsg[]に値が一つも入っていなければ下記のtryを実行するように書き換える！！！！
		if (empty($errormsg)) {
			try {
				$sql	=	"UPDATE post SET title=? text=? image=? regist_date=? WHERE id='$id'";//

				$sql    =   "INSERT INTO post (title,text,regist_date) VALUES(?,?,?)";
				$array  =   array($_POST['title'],$_POST['text'],$regist);
				$db->executeSQL($sql,$array); //タイトルとテキスト、投稿日時を一時的に実行
				$post_id = $db->lastInsertId(); //投稿したpostの最新のidを変数に代入
				//ファイルのパスを指定する
				$tmp_file   =   $_FILES["image"]["tmp_name"]; //アップロードされて一時フォルダに保存されたファイルのパス
				$save_file  =   dirname(__FILE__)."/{$post_id}.$extention"; //画像の移動先の指定、ファイル名の指定。__FILE__は定数。開いているこのファイルのパスとファイル名。dirname().'';でファイル名の部分を置き換えている。
				$image_url	=	"{$post_id}.$extention"; //画像を表示するためのパス。SQLに保存されるパス。
				//ファイルを指定ディレクトリに保存
				if (!move_uploaded_file($tmp_file, $save_file)) {
					$error = "システムエラーです。管理者に報告してください。"; 
				}
				//ファイルのパスを上書きするSQL文を準備
				$sql    =   "UPDATE post SET image = ? WHERE id = {$post_id}";
				$array  =   array($image_url);
				
				$db->executeSQL($sql,$array);

				//投稿完了時に出すメッセージをヒアドキュメントで変数に格納
				$success = "投稿しました。";

				$db->commit();//トランザクションをコミット
			} catch (Exception $e) {
				// トランザクション取り消し
				$db->rollBack();
				throw $e;
				/*
				var_dump($extention);
				exit;
				*/
			}
		}

		
}else{//submitを押されていない場合はレコードを表示する
	try{
		//入力済みのレコードを表示する
		$sql	=	"SELECT * FROM post WHERE id='$id'";
		$res    =   $db->executeSQL($sql,null);
		$recordlist = "";
		while($row = $res->fetch(PDO::FETCH_ASSOC)){//ifに変えてみてもいいかも
			$recordlist .= "<table class='table table-bordered admin-table'>\n<tbody>\n";
			$recordlist .= "<tr>\n<th class='th-inverse col-sm-3 col-xs-4'>ID</th>\n<td>{$row['id']}</td>\n</tr>\n";
			$recordlist .= "<tr>\n<th class='th-inverse'>タイトル</th>\n<td>\n<input type='text' class='form-control' id='inputText' maxlength='50' value='{$row['title']}'>\n<p class='alert alert-danger mt10 mb0'>タイトルは50文字までにしてください。</p>\n</td>\n</tr>\n";
			$recordlist .= "<tr>\n<th class='th-inverse'>本文</th>\n<td>\n<textarea class='form-control' rows='2' id='textArea'>{$row['text']}</textarea>\n<p class='alert alert-danger mt10 mb0'>本文は500文字までにしてください。</p>\n</td>\n</tr>\n";
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
			<form action="admin_update.html" method="post" enctype="multipart/form-data">
				<?php echo $recordlist?>
				<!--<table class="table table-bordered admin-table">
					<tbody>
						<tr>
							<th class="th-inverse col-sm-3 col-xs-4">ID</th>
							<td>00</td>
						</tr>
						<tr>
							<th class="th-inverse">タイトル</th>
							<td>
								<input type="text" class="form-control" id="inputText" maxlength="50" value="○○○○○○○○○○○○○○○○○○○○○○○○○○○○○">
								<p class="alert alert-danger mt10 mb0">タイトルは50文字までにしてください。</p>
							</td>
						</tr>
						<tr>
							<th class="th-inverse">本文</th>
							<td>
								<textarea class="form-control" rows="2" id="textArea">○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○○</textarea>
								<p class="alert alert-danger mt10 mb0">本文は500文字までにしてください。</p>
							</td>
						</tr>
						<tr class="tr-img">
							<th class="th-inverse">画像</th>
							<td>
								<div class="row">
									<div class="col-sm-7 img-block">
										<input type="file" accept=".png,.jpg,.gif" value="参照">
										<p class="alert alert-danger mt10 mb0">画像は.jpg、.gif、.pngのいずれかにしてください。</p>
									</div>
									<div class="col-sm-5 img-block">
										<a href="img/uploads/1.jpg" target="_blank">
											<img src="img/uploads/1.jpg" alt="" width="60%" align="middle">
										</a>
										<label class="ml10">
											<input type="checkbox"> 画像を削除
										</label>
									</div>
								</div>
							</td>
						</tr>
					</tbody>
				</table>-->
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