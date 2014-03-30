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
  $sql = "DELETE FROM Tag WHERE Tag_ID =".$id;
  $stmt = $dbh->query($sql);

}catch (PDOException $e){
  print('Error:'.$e->getMessage());
  die();
}
$dbh = null;

header('Location: tag_view.php');
exit;
?>



