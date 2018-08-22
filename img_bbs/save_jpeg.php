<?php
class save_jpeg {
    public function save(){
        //ファイルのパスを指定する
        $tmp_file   =   $_FILES["image"]["tmp_name"];
        $save_file  =   dirname(__FILE__).'/test.jpeg'; // __FILE__は定数。開いているこのファイルのパスとファイル名。dirname().'';でファイル名の部分を置き換えている。
        //指定ファイルがアップロードされたものかチェック
        if (!is_uploaded_file($tmp_file)) {
        echo "アップロードされたファイルが不正です。";
        exit;
        }
        //アップロードされたファイルの形式を調べる
        $finfo  =   finfo_open(FILEINFO_MIME_TYPE);
        $type   =   finfo_file($finfo,$tmp_file);
        if ($type != "image/jpeg") {
        echo "送信されたファイルがJPEGではありません。";
        exit;
        }
        //ファイルを指定ディレクトリに保存
        if (!move_uploaded_file($tmp_file, $save_file)) {
        echo "アップロードに失敗しました。";
        exit;
        }
        //アップした画像を表示する
        echo "<h1>画像ファイルをアップしました！！</h1>";
        echo "<img src='test.jpeg' />";
    }
}
?>