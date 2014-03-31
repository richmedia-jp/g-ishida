<?php require("header.php"); ?>

<div id="container">
  <div id="info_view">
    <div id="info_view_top">
      <p>タグ 登録一覧</p>
      <a class="regist_button "href="regist_tag.php">新規登録</a>
    </div>

    <div id="regist_list">
      <table>
        <tr><td>ID</td><td>タグ名</td><td>アイコン画像</td><td>編集</td><td>削除</td></tr>

<?php

$dsn = 'mysql:dbname=mydb;host=127.0.0.1';
$user = 'root';
$pswd = '211293g2';
$options = array(
  PDO::MYSQL_ATTR_READ_DEFAULT_FILE => '/etc/my.cnf',
); 

try{
  $dbh = new PDO($dsn, $user, $pswd, $options);
  $sql = "SELECT * FROM Tag";
  $stmt = $dbh->query($sql);
  if($stmt!=null){
    while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
      echo '        <tr><td>'.$result['Tag_ID'].'</td><td>'.$result['Tag_name'].'</td><td>'.'<img src="get_tag_img.php?id='.$result['Tag_ID'].'" alt=tag_icon /></td><td><a href="edit_tag.php?id='.$result['Tag_ID'].'">編集</a><td><a href="del_tag.php?id='.$result['Tag_ID'].'"'.">削除</td></tr>\n";
    }
  }

}catch (PDOException $e){
  print('Error:'.$e->getMessage());
  die();
}
$dbh = null;





          
?>
      </table>
    </div>

  </div>
</div>

<?php require("footer.php"); ?>


