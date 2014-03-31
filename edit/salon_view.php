<?php require("header.php"); ?>

<?php

$dsn = 'mysql:dbname=mydb;host=127.0.0.1';
$user = 'root';
$pswd = '211293g2';
$options = array(
  PDO::MYSQL_ATTR_READ_DEFAULT_FILE => '/etc/my.cnf',
); 

try{
  $dbh_p = new PDO($dsn,$user,$pswd,$options);
  $sql_pre = "SELECT * FROM Prefecture";
  $stmt_pre = $dbh_p->query($sql_pre);
  if($stmt_pre==""){print_r($dbh->errorInfo());}

}catch (PDOException $e){
  print('Error:'.$e->getMessage());
  die();
}
$dbh_p = null;



//result とってきてプルダウンメニューだす

?>

<div id="container">
  <div id="info_view">
    <div id="info_view_top">
      <p>美容室 登録一覧</p>
      <a class="regist_button "href="regist_salon.php">新規登録</a>
      <!-- ここに検索機能  -->
      <form action="salon_view.php" method="post">
        <table>
          <td><input type="text" name="keyword" value="<?php echo $_POST['keyword'];?>" size="40"></td>
          <td>
            <select name="Prefecture_ID">
              <option value="" <?php if($_POST['Prfecture_ID']==""){echo 'selected';}?>>- 都道府県を選択 -</option><br>
          <?php
            while($result_pre=$stmt_pre->fetch(PDO::FETCH_ASSOC)){
              echo '            <option value="'.$result_pre['Prefecture_ID'].'"';
              if($_POST['Prefecture_ID']==$result_pre['Prefecture_ID']){echo ' selected';}
              echo '>'.$result_pre['Prefecture_name']."</option><br>\n";
            }
          ?>
            </select>
          </td>
          <td><input type="submit" value="条件検索"></td>

        </table>
      </form>


    </div>

    <div id="regist_list">
      <table>
        <tr><td>ID</td><td>美容室名</td><td>都道府県</td><td>紹介本文</td><td>アクセス数</td><td>編集</td><td>削除</td></tr>
<?php
try{
  $dbh = new PDO($dsn, $user, $pswd, $options);

  $key=htmlspecialchars($_POST['keyword']);
  $sql_key="";
  if($key!=""){
    $sql_key = $sql_key." WHERE (Address1 LIKE '%".$key."%' OR Address2 LIKE '%".$key."%'";
    $sql_key = $sql_key." OR Salon_name LIKE '%".$key."%' OR Introduction_title LIKE '%".$key."%' OR Introduction_text LIKE '%".$key."%')";
  }
  if($_POST['Prefecture_ID']!=""){
    $sql_pref = "Prefecture_ID =".$_POST['Prefecture_ID'];

    if($key!=""){$sql_key = $sql_key." AND ".$sql_pref;}
    else{$sql_key = " WHERE ".$sql_pref; }

  }


  $sql = "SELECT * FROM Salon".$sql_key;
  $stmt = $dbh->query($sql);
  if($stmt!=null){

    $Prefecture_name='';
    while($result = $stmt->fetch(PDO::FETCH_ASSOC)){

      $sql_p= "SELECT * FROM Prefecture WHERE Prefecture_ID = ".$result['Prefecture_ID'];
      $stmt_p = $dbh->query($sql_p);
      if($stmt_p!=""){
        $result_p = $stmt_p->fetch(PDO::FETCH_ASSOC);
        $Prefecture_name = $result_p['Prefecture_name'];
      }
      else{$Prefecture_name='NO DATA';}

      echo '        <tr><td>'.$result['Salon_ID'].'</td><td>'.$result['Salon_name'].'</td><td>'.$Prefecture_name.'</td><td>'.$result['Introduction_text'].'</td><td>'.$result['Traffic_count'].'</td><td><a href="edit_salon.php?id='.$result['Salon_ID'].'">編集</a><td><a href="del_salon.php?id='.$result['Salon_ID'].'"'.">削除</td></tr>\n";

    }
  }
  else{print_r($dbh->errorInfo());}


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






