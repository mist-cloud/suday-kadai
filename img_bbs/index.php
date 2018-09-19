<?php
//dbクラスを読み込み
require_once('db.php');
$db     =   new DB();

//タイムゾーンを明示的に指定する
date_default_timezone_set("Asia/Tokyo");
//現在時刻をエポックタイムラインスタンプ(UNIXタイム)を得る
$now = time();
$regist = date("Y/m/d H:i:s", $now);

//レコードを追加する
if(isset($_POST['insert'])){
	$db->beginTransaction();
  	try {
		$sql    =   "INSERT INTO post (title,text,regist_date) VALUES(?,?,?)";
		$array  =   array($_POST['title'],$_POST['text'],$regist);
		$db->executeSQL($sql,$array); //タイトルとテキスト、投稿日時を一時的に実行
		$post_id = $db->lastInsertId(); //投稿したpostの最新のidを変数に代入
		//ファイルのパスを指定する
		$tmp_file   =   $_FILES["image"]["tmp_name"]; //アップロードされて一時フォルダに保存されたファイルのパス
		$save_file  =   dirname(__FILE__)."/{$post_id}.jpeg"; //画像の移動先の指定、ファイル名の指定。__FILE__は定数。開いているこのファイルのパスとファイル名。dirname().'';でファイル名の部分を置き換えている。
		$image_url	=	"{$post_id}.jpeg"; //画像を表示するためのパス。SQLに保存されるパス。
		//ファイルを指定ディレクトリに保存
		if (!move_uploaded_file($tmp_file, $save_file)) {
			$error = "アップロードに失敗しました。"; //配列でやるといいかも。格納される変数が一つ以上あるとエラーエッセージが表示されるif文。バリデーション機能？
		}
		//ファイルのパスを上書きするSQL文を準備
		$sql    =   "UPDATE post SET image = ? WHERE id = {$post_id}";
		$array  =   array($image_url);
		/*
		var_dump($sql);
		exit;*/
		$db->executeSQL($sql,$array);
		$db->commit();//トランザクションをコミット
  	} catch (Exception $e) {
		// トランザクション取り消し
		$db->rollBack();
		throw $e;
	}
}
//レコードを表示する
$sql    =   "SELECT * FROM post ORDER BY id DESC"; // ORDER BY id DESC　はpostテーブルのidの降順で読み出す追加の記述。DESCは降順の意味。
$res    =   $db->executeSQL($sql,null);
$recordlist = "";
while($row = $res->fetch(PDO::FETCH_ASSOC)){
    $recordlist .= "<div class='well'>\n<div class='row'>\n<div class='col-sm-6 mb10'>\n";
    $recordlist .= "<img class='upImg' src='{$row['image']}' alt=''>\n</div>\n<div class='col-sm-6 mb10'>\n";
    $recordlist .= "<h2 class='lead'>{$row['title']}</h2>\n";
    $recordlist .= "<p>{$row['text']}</p>\n";
    $recordlist .= "<p class='day'>{$row['regist_date']}</p>\n";
    $recordlist .= "</div>\n</div>\n</div>\n";
}


?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>画像掲示板（ユーザー画面）</title>
	<!-- BootstrapのCSS読み込み -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/custom/common.css" rel="stylesheet">
	<link href="css/custom/user.css" rel="stylesheet">
	<!-- jQuery読み込み -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<!-- BootstrapのJS読み込み -->
	<script src="js/bootstrap.min.js"></script>
	<script src="js/custom/script.js"></script>
</head>
<body>
	<div class="container">
		<div class="page-header">
			<h1>画像掲示板</h1>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="well">
					<form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
						<div class="row">
							<div class="col-lg-12">
								
								<!-- 画像のアップロードエラーをここに表示したい -->
								
								<!-- 投稿に成功したときだけ表示するよう変更しましょう -->
								<p class="alert alert-success mb10" role="alert" style="display:none;"><!-- display:none;で非表示 -->
									投稿しました。
								</p>

								<!-- 入力エラーがあるときだけ表示するよう変更しましょう -->
								<p class="alert alert-danger mb10" role="alert" style="display:none;"><!-- display:none;で非表示 -->
									本文は500文字までにしてください。<br>
									画像は.jpg、.gif、.pngのいずれかにしてください。
								</p>

								<label for="title" class="mt10 mb10">タイトル</label>
								<input type="text" class="form-control mb10" id="title" name="title">

								<label for="text" class="mt10 mb10">本文</label>
								<textarea class="form-control mb20" rows="2" id="text" name="text"></textarea>

								<label for="image" class="mb10">画像</label>
									<input type="file" id="image" name="image">

								<div class="text-center mt20 mb20">
									<input type="submit" class="btn btn-lg btn-primary" value="投稿する" name="insert">
								</div>
							</div>
						</div>

					</form>
				</div>
				<!-- /well -->
				
				
				<div class="posts">
				    <?php echo $recordlist?>

					<!-- /well　サンプル 
					<div class="well">
						<div class="row">
							<div class="col-sm-6 mb10">
								<img class="upImg" src="img/uploads/2.jpg" alt="">
							</div>
							<div class="col-sm-6 mb10">
								<h2 class="lead">投稿タイトル</h2>
								<p>投稿メッセージ</p>
								<p class="day">2017-01-02 02:56</p>
							</div>
						</div>
					</div>
					<div class="well">
						<div class="row">
							<div class="col-sm-6 mb10">
								<img class="upImg" src="img/uploads/1.jpg" alt="">
							</div>
							<div class="col-sm-6 mb10">
								<h2 class="lead">四万十川</h2>
								<p>四万十川（しまんとがわ）は、高知県の西部を流れる渡川水系の本川で、一級河川。全長196km、流域面積2186km2。四国内で最長の川で、流域面積も吉野川に次ぎ第2位となっている。</p>
								<p class="day">2017-01-01 12:03</p>
							</div>
						</div>
					</div>
					-->
				</div>
				<!-- /posts -->

			</div>
		</div>
		<!-- /row -->
		<p class="gotoTopBtn"><a href="#"><img src="./img/btn_pagetop.png" alt="ページの先頭へ"></a></p>
	</div>
	<!-- /container -->
	<script type="text/javascript">
		$(function(){
			if ($('.alert-success').size() > 0) {
				setTimeout(function(){
					$('.alert-success').fadeOut("slow");
				}, 800);
			}
		});
	</script>
</body>
</html>