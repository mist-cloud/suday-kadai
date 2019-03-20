<?php
show_header();//htmlのヘッダを表示する
show_form();//色変更フォームを表示する
show_footer();//htmlのフッタを表示する
//htmlのヘッダを表示する（この時、閉経色を指定する）
function show_header() {
    //色が送信されているか調べて背景色を決定する
    $color = "#FFFFFF";
    if (isset($_GET["color"])) {
        $color = htmlspecialchars($_GET["color"]);
    }
    echo "<html><body bgcolor='$color'>";//htmlのヘッダを表示
}
function  show_footer() { echo "</body></html>";}//htmlのフッタを表示
//フォームの表示処理
function show_form() {
    //色名とカラーコードを連想配列で指定する
    $colors = array("赤"=>"#FF0000","水色"=>"#00FFFF","白"=>"#FFFFFF");
    echo "<form>";
    //ラジオボタンを次々と表示する
    foreach ($colors as $name => $color) {
        echo create_radio_code($name, $color);
    }
    echo "<input type='submit' value='色変更' />";
    echo "</form>";
}
//フォームの表示で利用するラジオボタンとラベル作成
function create_radio_code($name, $code) {
    return <<< __RADIO__
<input type="radio" id="$name" name="color" value="$code" />
<label for="$name">$name</label>
__RADIO__;
}

//配列の要素を処理
$fruits = array("バナナ","リンゴ","イチゴ");
foreach ($fruits as $f) {
    echo "<div>{$f}が食べたい！</div>";
}
echo "<hr />";
//連想配列の要素を処理
$colors = array("赤"=>"#FF0000","青"=>"#0000FF","紫"=>"#FF00FF");
foreach ($colors as $label => $code) {
    echo "<div style='color: $code'>$label ($code)</div>";
}

?>