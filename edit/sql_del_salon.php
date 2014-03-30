<?php
  
$dsn = 'mysql:dbname=mydb;host=127.0.0.1';
$user = 'root';
$pswd = '211293g2';
$options = array(
  PDO::MYSQL_ATTR_READ_DEFAULT_FILE => '/etc/my.cnf',
); 

try{
  $dbh = new PDO($dsn, $user, $pswd, $options);
  $id = intval($_GET['id']);
  $sql = "DELETE FROM Salon WHERE Salon_ID =".$id;
  $stmt = $dbh->query($sql);

  //美容室と画像の中間テーブルからも削除する
  $sql = "DELETE FROM Salon_has_SalonPicture WHERE Salon_Salon_ID=".$id;
  $stmt = $dbh->query($sql);

  //美容室と画像の中間テーブルからも削除する
  $sql = "DELETE FROM Salon_has_Tag WHERE Salon_Salon_ID=".$id;
  $stmt = $dbh->query($sql);

}catch (PDOException $e){
  print('Error:'.$e->getMessage());
  die();
}
$dbh = null;

header('Location: salon_view.php');
exit;
?>




