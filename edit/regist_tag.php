<?php require("header.php"); ?>
<?php

$dsn = 'mysql:dbname=mydb;host=127.0.0.1';
$user = 'root';
$pswd = '211293g2';
$options = array(
  PDO::MYSQL_ATTR_READ_DEFAULT_FILE => '/etc/my.cnf',
); 
 
?>

<div id="container">

  <div id="input_info">
    <p class="edit_caption">タグ 新規登録</p>

    <form method="POST" enctype="multipart/form-data" action="regist_tag.php">
      <input type="hidden" name="MAX_FILE_SIZE" value="65536">
        <table>
          <tr>
            <td>画像のファイルを選択してください</td>
            <td><input type="file" size="50" name="upfile"></td><!-- ファイル選択ボタン -->
          <tr>
            <td>タグの名前</td>
            <td><input type="text" size="50" name="Tag_name"></td>
          </tr>
        </table>
        <input type="submit" name="submit" value="送信">
        <input type="reset" name="reset" value= "リセット">
    </form>


<!--  以下、送信ボタンが押されたあとに初めて動く処理。    -->
<?php
if ($_POST["submit"]!=""){

  if ($_FILES["upfile"]["tmp_name"]=="none"){//ファイル名の取得
    echo "<p>ファイルのアップロードができませんでした。</p><br>\n";
    exit;
  }

  $fp = fopen($_FILES["upfile"]["tmp_name"], "rb");//ファイルオープン
  if(!$fp){//空なら
   echo "<p>アップロードしたファイルを開けませんでした。</p><br>\n";
   exit;
  }

  $imgdat = fread($fp, filesize($_FILES["upfile"]["tmp_name"]));//ファイルの読み込み/第２引数は長さ（最高何バイトまで読み込むかということ。filesize(ファイル名)で丸々読み込める。
  fclose($fp);

//  print("<p>ファイルサイズ：{".$_FILES["upfile"]["size"]."}</p><br>\n");
//  $len = strlen($imgdat);
//  print("<p>データ長 = ".$len."</p><br>");

  $imgdat = addslashes($imgdat);//エスケープ文字に対する処理らしい
  $Tag_name = htmlspecialchars($_POST['Tag_name']);

  if($Tag_name != ""){
    try{
      $dbh = new PDO($dsn, $user, $pswd, $options);
      if ($dbh==null){
        echo "<p>MySQLへの接続に失敗しました</p>\n";
        exit;
      }


      $sql = "INSERT INTO Tag (Tag_name, Icon_picture) VALUES ('$Tag_name','$imgdat')";

      $stmt = $dbh->query($sql);
      if (!$stmt){
        echo "<p>登録失敗</p><br>\n";
        print_r($dbh->errorInfo());
        exit;
      }
 
 
      $sql = "SELECT * FROM Tag WHERE Tag_ID IN (SELECT MAX(Tag_ID) FROM Tag)";
      $stmt = $dbh->query($sql);
      if (!$stmt){
        echo "<p>SQLの実行に失敗しました</p><br>\n";
        print_r($dbh->errorInfo());
        exit;
      }
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      echo "<p>以下の情報を登録しました</p><br>\n";
      echo '<p>登録ID: '.$result['Tag_ID'].' タグ名:'.$result['Tag_name'].'</p><br>'."\n";
      echo "<img src=get_tag_img.php?id=".$result['Tag_ID']."/>";
      
    }
    catch (PDOException $e){
      print('Error:'.$e->getMessage());
      die();
    }
    $dbh = null;
  }
  else{
    echo "<p>タグの名を入力して下さい</p>\n";
  }
  unlink($_FILES["upfile"]["tmp_name"]);

}

?>


  </div>

</div>

<?php require("footer.php"); ?>



