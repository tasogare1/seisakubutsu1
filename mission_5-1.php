<!DOCTYPE html>

<html lang="ja">

<head>

    <meta charset="UTF-8">

    <title>mission_5-1</title>

</head>

<body>
<?php
    // DB接続設定
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $pass = 'パスワード';
    $pdo = new PDO($dsn, $user, $pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));


    //テーブル作成
    $sql = "CREATE TABLE IF NOT EXISTS tbtest"
    ."("
    ."id INT AUTO_INCREMENT PRIMARY KEY,"
    ."name char(32),"
    ."comment TEXT,"
    ."datedata DATETIME,"
    ."password char(32)"
    .");";
    $stmt = $pdo->query($sql);

    


    

    
    //投稿機能
if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["password"])){
        // editnumafterがないときは新規投稿、ある場合は編集とする
        if(empty($_POST["editnumafter"])){ //←新規投稿
                $name = $_POST["name"];
                $comment = $_POST["comment"];
                $password = $_POST["password"];
                date_default_timezone_set('Asia/Tokyo');
                $datedata = date("Y-m-d H:i:s");
                $sql = $pdo -> prepare("INSERT INTO tbtest (name,comment,datedata,password) VALUES(:name,:comment,:datedata,:password)");
                $sql -> bindParam(':name', $name, PDO::PARAM_STR);        
                $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                $sql -> bindParam(':password', $password, PDO::PARAM_STR);
                $sql -> bindParam(':datedata', $datedata , PDO::PARAM_STR); 
                $sql -> execute();              //実行する
        }else{ //←編集
                $id = $_POST["editnumafter"];      
                $name = $_POST["name"];
                $comment = $_POST["comment"];
                $password = $_POST["password"]; 
                $sql = 'update tbtest set name=:name,comment=:comment,password=:password where id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                
        }      
}    
  
    
     //削除機能
 if(!empty($_POST['deletenum']) && !empty($_POST['deletepassword'])){
    $deletenum=$_POST['deletenum']; 
    $deletepassword=$_POST['deletepassword'];
    $sql = 'SELECT * FROM tbtest WHERE id=:id ';
    $delete = $pdo->prepare($sql);                  
    $delete->bindParam(':id', $deletenum, PDO::PARAM_INT); 
    $delete->execute(); 
    $lines = $delete -> fetchAll(); 
    foreach ($lines as $line){
        if($deletepassword==$line['password']){
            $sql = 'delete from tbtest where id=:id';
        	$del = $pdo->prepare($sql);
        	$del->bindParam(':id', $deletenum, PDO::PARAM_INT);
        	$del->execute();
        }//if
    }//foreach
}//if
  

      //編集番号選択機能
if(!empty($_POST["editnum"]) && !empty($_POST["editpassword"])){
    $editnum = $_POST["editnum"];
    $editpassword = $_POST["editpassword"];
    $sql = 'SELECT * FROM tbtest WHERE id=:id';
    $edit = $pdo->prepare($sql);
    $edit->bindParam(':id', $editnum, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
    $edit->execute(); 
    
    $lines = $edit->fetchAll();
        
    foreach ($lines as $line){
        if($line['password'] == $editpassword){
        $editname = $line['name'];
        $editcomment = $line['comment'];
        $editnumafter = $line['id'];     
        }
    }
}





    
 ?>
 
<form action="" method="post">
        <input type="text" name="name" PLACEHOLDER="名前を入力してください" value="<?php if(isset($editname)) { echo $editname ;} ?>">　<!-- 入力フォーム　-->
        <input type="text" size="30" name="comment" PLACEHOLDER="コメントを入力してください" value="<?php if(isset($editcomment)) {echo $editcomment ;} ?>">　<!-- 入力フォーム　-->
        <input type="text"  name ="password" size="6" maxlength = "10" placeholder="パスワード" value="<?php if(isset($editpassword1)) {echo $editpassword1 ;} ?>">
        <input type="hidden" name="editnumafter" value="<?php if(isset($editnumafter)) {echo $editnumafter;} ?>">　<!-- 入力フォーム　-->
        <input type="submit" name="submit" value = "投稿">　<!-- 送信ボタン　-->
        <br>
        <input type="number" name = "deletenum" placeholder="削除対象番号">
        <input type="password"  name ="deletepassword" size="6" maxlength = "10" placeholder="パスワード">
        <input type="submit" name="submit" value = "削除">
         
        <br>
        <input type="number" name = "editnum" placeholder="編集対象番号">
        <input type="password"  name ="editpassword" size="6" maxlength = "10" placeholder="パスワード">
        <input type="submit" name="submit" value = "編集">
        
    </form>
<?php
    //表示
    $sql = 'SELECT * FROM tbtest';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].'<>';
        echo $row['name'].'<>';
        echo $row['comment'].'<>';
        echo $row['datedata'].'<br>';
        
    echo "<hr>";
    }

?>
































































</body>
</html>
</html>