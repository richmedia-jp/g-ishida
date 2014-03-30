<?php require("header.php"); ?>
<?php

$dsn = 'mysql:dbname=mydb;host=127.0.0.1';
$user = 'root';
$pswd = '211293g2';
$options = array(
  PDO::MYSQL_ATTR_READ_DEFAULT_FILE => '/etc/my.cnf',
); 

$id=intval($_GET['id']);
 
try{
  $dbh = new PDO($dsn, $user, $pswd, $options);
//  $sql = "INSERT INTO Tag (Tag_name, Icon_picture) VALUES ('$Tag_name','$imgdat')";
  $sql = "SELECT * FROM Tag WHERE Tag_ID = ".$id;
  $stmt = $dbh->query($sql);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
}
catch (PDOException $e){
  print('Error:'.$e->getMessage());
  die();
}
$dbh = null;
  

?>

<div id="container">

  <div id="input_info">
    <p class="edit_caption">タグ 編集</p>

    <form method="POST" enctype="multipart/form-data" action="sql_edit_tag.php?id=<?php echo $id;?>">
      <input type="hidden" name="MAX_FILE_SIZE" value="65536">
        <table>
          <tr><td>現在のタグ名</td><td><?php echo $result['Tag_name']?></td><tr>
          <tr><td>変更後のタグ名</td><td><input type="text" size="50" name="Tag_name" value="<?php echo $result['Tag_name']?>"></td></tr>
        </table>
        <br>
        <table>
          <tr>
            <td>新しい画像のファイルを選択してください</td>
            <td><input type="file" size="50" name="upfile"></td><!-- ファイル選択ボタン -->
          </tr>
          <tr><td><img src="get_tag_img.php?id=<?php echo $id;?>"></td></tr>

        </table>
        <input type="submit" name="submit" value="送信">
    </form>

  </div>

</div>

<?php require("footer.php"); ?>

