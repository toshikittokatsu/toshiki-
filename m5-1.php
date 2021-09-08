<!DOCTYPE html>
<html lang=ja>
<head>
    <meta charset="UTF-8">
    <title>keijiban</title>
</head>
<body>

<?php
//接続
    $dsn='データベース名';
    $user='ユーザー名';
    $password='パスワード';
    $pdo=new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));

//table作成
    $sql = "CREATE TABLE IF NOT EXISTS keijiban"
    . "("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name CHAR(32),"
    . "comment VARCHAR(100),"
    . "date CHAR(100),"
    . "password INT(100)"
    . ")";
    $stmt=$pdo->query($sql);
//変数に代入
    $date = date("Y/m/d/ H:i:s");
    $name = $_POST['name'];
    $comment = $_POST['comment'];
    $pass = $_POST['pass'];
//投稿機能
if(!empty($_POST['name']) && !empty($_POST['comment']) && !empty($_POST['pass'])){
    $sql = $pdo -> prepare("INSERT INTO keijiban (name, comment, date, password) VALUES (:name, :comment, :date, :password)");
    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
    $sql -> bindParam(':date', $date, PDO::PARAM_STR);
    $sql -> bindParam(':password', $pass, PDO::PARAM_INT);
    $sql -> execute();
}

//削除機能
 //変数に代入
    $delete = $_POST['delete'];
    $dpass = $_POST['dpass'] ;
if(!empty($_POST['delete']) && !empty($_POST['dpass'] )) {  
    $sql = 'DELETE FROM keijiban WHERE id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':id' => $_POST['delete']));
    $results = $stmt->fetchAll();
    foreach ($results as $row) {
        if ($dpass == $row['password']){
            $stmt->bindParam(':id', $delete, PDO::PARAM_INT);
            $stmt->execute();
        }else{
            echo "パスワードが違います<br>";
        }
    }  
}

//編集機能
 //変数に代入
    $edit = $_POST['edit'];
    $date = date("Y/m/d/ H:i:s");
    $name = $_POST['name'];
    $comment = $_POST['comment'];
    $epass = $_POST['epass'];
if(!empty($_POST['edit']) && !empty($_POST['epass'] )) {
    $sql = 'UPDATE keijiban SET id=:id,name=:name,comment=:comment,date=:date,password=:password WHERE id=:id';
    $stmt = $pdo->prepare($sql);

    $results = $stmt->fetchAll();
    foreach($results as $row) {

      if($edit == $row['id'] && $epass == $row['password']) {
        $ename = $row['name'];
        $ecom = $row['comment'];
        $epass = $row['password'];  

        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':password', $epass, PDO::PARAM_INT);
        $stmt->execute();

      }else{

      }
    }
    $sql = 'SELECT * FROM keijiban';
    $stmt = $pdo->query($sql);
    $stmt->execute();
  }

?>
<form action="" method="post">
  <input type="text" name="name" placeholder="名前" value="<?php if(isset($ename)){echo $ename;}?>"><br>
  <input type="text" name="comment"placeholder="コメント" value="<?php if(isset($ecom)){echo $ecom;} ?>"><br>
   <input type="text" name="pass" placeholder="パスワード"value="<?php if(isset($epass)){echo $epass;} ?>"><br>
   <input type="submit" name="submit" value='投稿'><br>

  <input type="number" name="delete" placeholder="削除対象番号"><br>
   <input type="text" name="dpass" placeholder="パスワード"><br>
   <input type="submit" name="submit" value="削除"><br>

   <input type="number" name="edit" placeholder="編集対象番号"><br>
   <input type="text" name="epass" placeholder="パスワード"><br>
   <input type="submit" name="submit" value="編集">
</form>

<?php
    $sql = 'SELECT * FROM keijiban';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        echo $row['id'].' ';
        echo $row['name'].' ';
        echo $row['comment'].' ';
        echo $row['date'].' ';
        echo $row['password'].'<br>';
    }
?>

</body>
</html>