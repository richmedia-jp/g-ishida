<?php
$dsn = 'mysql:dbname=mydb;host=127.0.0.1';
$user = 'root';
$pswd = '211293g2';
$options = array(
  PDO::MYSQL_ATTR_READ_DEFAULT_FILE => '/etc/my.cnf',
); 
 
$id = intval($_GET['id']);

try{
  $dbh = new PDO($dsn, $user, $pswd, $options); 
  $sql = "SELECT * FROM SalonPicture WHERE Picture_ID= ".$id;
  $stmt = $dbh->query($sql);
  if($stmt!=null){
    $result = $stmt->fetchObject();//(PDO::FETCH_ARRAY);
    header("Content-Type: image/png");//pngで固定しているので注意
    echo $result->Salon_picture;
  }
}
catch (PDOEXception $e){
  die();
}
$dbh=null;
?>


