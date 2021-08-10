<?php
function create_and_connect_to_tbtest3(){
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    #クラスを利用した、接続。https://www.php.net/manual/ja/pdo.connections.php
    #array以降は調べてない

    $sql = "CREATE TABLE IF NOT EXISTS tbtest3"
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
        . "comment TEXT,"
        . "password TEXT,"
        . "date_and_time DATETIME"
        .");";
    $stmt = $pdo->query($sql);
    #$pdoは接続のためのインスタンス。
    #アロー演算子は、(インスタンス)->(そのメソッドなど)という書き方で、メソッドなどを呼び出す。
    return $pdo;
}

function drop($pdo, $table_name){
    $sql = 'DROP TABLE '.$table_name;
    $stmt = $pdo->query($sql);
}

function show_tables($pdo){
    $sql ='SHOW TABLES';
    $result = $pdo -> query($sql);
    foreach ($result as $row){
        echo $row[0];
        echo '<br>';
    }
    echo "<hr>";
}

function show_table_details($pdo, $table_name){
    $sql ='SHOW CREATE TABLE '.$table_name;
    $result = $pdo -> query($sql);
    foreach ($result as $row){
        echo $row[1];
    }
    echo "<hr>";
}

function insert_to_tbtest3($pdo, $insert_name, $insert_comment, $insert_password) {
    $sql = $pdo -> prepare("INSERT INTO tbtest3 (name, comment, password, date_and_time) VALUES (:name, :comment, :password, :date_and_time)");
    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
    $sql -> bindParam(':password', $password, PDO::PARAM_STR);
    $sql -> bindParam(':date_and_time', $date_and_time, PDO::PARAM_STR);
    $name = $insert_name;
    $comment = $insert_comment;
    $password = $insert_password;
    $date_and_time = date("Y/m/d H:i:s");
    $sql -> execute();
}

function update_tbtest3_record_by_id($pdo, $id, $update_name, $update_comment, $update_password){
    $id = 1; //変更する投稿番号
    $name = $update_name;
    $comment = $update_comment;
    $password = $update_password;
    $date_and_time = date("Y/m/d H:i:s");
    $sql = 'UPDATE tbtest3 SET name=:name,comment=:comment,password=:password,date_and_time=:date_and_time WHERE id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    #この文はエラー、変数しか渡せない $stmt->bindParam(':date_and_time', date("Y/m/d H:i:s"), PDO::PARAM_STR);
    $stmt->bindParam(':date_and_time', $date_and_time, PDO::PARAM_STR);
    $stmt->execute();
}

function delete_tbtest3_record_by_id($pdo, $delete_id){
    $id = $delete_id;
    $sql = 'delete from tbtest3 where id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
}

function show_all_records_of_tbtest3($pdo){
    $sql = 'SELECT * FROM tbtest3';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['password'].',';
        echo $row['date_and_time'].'<br>';
    echo "<hr>";
    }
}

function show_all_records_of_tbtest3_as_bb($pdo){
    $sql = 'SELECT * FROM tbtest3';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].'   ';
        echo $row['name'].'   ';
        echo $row['date_and_time'].'<br>';
        echo $row['comment'].'<br>';
        #echo $row['password'].',';
    echo "<hr>";
    }
}


function record_of_tbtest3_by_id($pdo, $id){
    $sql = 'SELECT * FROM tbtest3';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        if($row['id'] == $id){
            return $row;
        }
    }
}
?>
