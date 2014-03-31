<?php require("header.php"); ?>

<div id="container">
  <div id="info_view">
    <div id="info_view_top">
      <p>アクセスランキング</p>
    </div>
    <div id="regist_list">
      <table>
        <tr><td>ランク</td><td>アクセス数</td><td>ID</td><td>美容室名</td><td>都道府県</td><td>紹介本文</td><td>編集</td><td>削除</td></tr>

<?php

$dsn = 'mysql:dbname=mydb;host=127.0.0.1';
$user = 'root';
$pswd = '211293g2';
$options = array(
  PDO::MYSQL_ATTR_READ_DEFAULT_FILE => '/etc/my.cnf',
); 

try{
  $dbh = new PDO($dsn, $user, $pswd, $options);
  $sql = "SELECT * FROM Salon ORDER BY Traffic_count DESC LIMIT 5";
  $stmt = $dbh->query($sql);
  if($stmt!=null){

    $Prefecture_name='';
    $rank_i=1;
    while($result = $stmt->fetch(PDO::FETCH_ASSOC)){

      $sql_p= "SELECT * FROM Prefecture WHERE Prefecture_ID = ".$result['Prefecture_ID'];
      $stmt_p = $dbh->query($sql_p);
      if($stmt_p!=""){
        $result_p = $stmt_p->fetch(PDO::FETCH_ASSOC);
        $Prefecture_name = $result_p['Prefecture_name'];
      }
      else{$Prefecture_name='NO DATA';}

      echo '        <tr><td>'.$rank_i.'位</td><td>'.$result['Traffic_count'].'回</td><td>'.$result['Salon_ID'].'</td><td>'.$result['Salon_name'].'</td><td>'.$Prefecture_name.'</td><td>'.$result['Introduction_text'].'</td><td><a href="edit_salon.php?id='.$result['Salon_ID'].'">編集</a><td><a href="del_salon.php?id='.$result['Salon_ID'].'"'.">削除</td></tr>\n";
      $rank_i++;
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






