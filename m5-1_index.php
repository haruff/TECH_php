<?php
require_once "m5-1_funcs.php";
$pdo = create_and_connect_to_tbtest3(); #tbtest1
session_start();
$_POST["name"] = filter_input(INPUT_POST, 'name'); #何も入ってない時にnullになる
$_POST["comment"] = filter_input(INPUT_POST, 'comment');
$_POST["delete_num"] = filter_input(INPUT_POST, 'delete_num');
$_POST["edit_num"] = filter_input(INPUT_POST, 'edit_num');
$_POST["password"] = isset($_POST["password"]) ? $_POST["password"] : "";
$_SESSION["Is_edit_mode"] = isset($_SESSION["Is_edit_mode"]) ? $_SESSION["Is_edit_mode"] : "";
$display_name = "";
$display_comment = "";
$notice_of_no_input_password = "";
?>

<?php
if(isset($_GET["mode"])){
    if($_GET["mode"] == "post"){
        if(strlen($_POST["name"]) == 0 || strlen($_POST["comment"]) == 0){
            echo "名前と投稿内容の両方を記入してください<br>";
        } else {
            if($_SESSION["Is_edit_mode"]){
                if(record_of_tbtest3_by_id($pdo, $_POST["edit_num"])["password"] != $_POST["password"]){
                    echo "パスワードが違います<br>";
                } else {
                    if(strlen($_POST["password"]) == 0){
                        $_POST["password"] == "";
                        #この2行の必要性について：編集前にpasswordを設定、編集時にpassword未入力だと、前のpasswordが設定される。おそらく、SQLの(PDOの)insertの仕様。妥当で自然な仕様だと思うが、掲示板には不向きと思うのでこの2行を追加した。
                    }
                    update_tbtest3_record_by_id($pdo, $_POST["edit_num"], $_POST["name"], $_POST["comment"], $_POST["password"]);
                }
            } else {
                insert_to_tbtest3($pdo, $_POST["name"], $_POST["comment"], $_POST["password"]);
            }
        }
        $_SESSION["Is_edit_mode"] = 0;
    } else if($_GET["mode"] == "delete"){
        if(record_of_tbtest3_by_id($pdo, $_POST["delete_num"])["password"] != $_POST["password"]){
            echo "パスワードが違います<br>";
        } else {
            delete_tbtest3_record_by_id($pdo, $_POST["delete_num"]);
        }
        $_SESSION["Is_edit_mode"] = 0;
    } else if($_GET["mode"] == "edit_preparation"){
        if(strlen($_POST["edit_num"]) == 0){
            echo "編集したい投稿の番号を入力してください<br>";
            $_SESSION["Is_edit_mode"] = 0;
        } else {
            $x = record_of_tbtest3_by_id($pdo, $_POST["edit_num"]);
            $display_name = $x["name"];
            $display_comment = $x["comment"];
            $_SESSION["Is_edit_mode"] = 1;
        }
    }
}
?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_3-5</title>
</head>
<body>
    新規投稿、編集、削除、それぞれきちんと動くかの確認お願いします！
    <br>
    <br>
    新規投稿
    <form action="m5-1_index.php?mode=post" method="POST">
        <input type="text" name="name" placeholder="名前を入力してください" value=<?php echo $display_name; ?>>
        <br>
        <input type="text" name="comment" placeholder="編集内容を入力してください" value=<?php echo $display_comment; ?>>
        <br>
        <input type="password" name="password" placeholder="パスワードを入力してください">
        <input type="submit" name="submit">
    </form>
    <br>

    削除
    <form action="m5-1_index.php?mode=delete" method="post">
        <input type="text" name="delete_num" placeholder="削除番号を入力してください">
        <br>
        <input type="password" name="password" placeholder="パスワードを入力してください">
        <input type="submit" name="submit">
    </form>
    <br>

    編集
    <form action="m5-1_index.php?mode=edit_preparation" method="post">
        <input type="text" name="edit_num" placeholder="編集番号を入力してください">
        <br>
        <input type="password" name="password" placeholder="パスワードを入力してください">
        <input type="submit" name="submit">
    </form>
</body>
</html>

<?php
    show_all_records_of_tbtest3_as_bb($pdo);
?>
