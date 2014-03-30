<?php

$dsn = 'mysql:dbname=mydb;host=127.0.0.1';
$user = 'root';
$pswd = '211293g2';
$options = array(
  PDO::MYSQL_ATTR_READ_DEFAULT_FILE => '/etc/my.cnf',
); 


$id=$_GET['id'];


try{
  $dbh = new PDO($dsn, $user, $pswd, $options);

  if ($_FILES["upfile"]["tmp_name"]!="none"){//ファイル名の取得
    
    $fp = fopen($_FILES["upfile"]["tmp_name"], "rb");//ファイルオープン
    if(!$fp){//空なら
     
    }
    else{

      $imgdat = fread($fp, filesize($_FILES["upfile"]["tmp_name"]));
      fclose($fp);
      $imgdat = addslashes($imgdat);
 
      $sql= "UPDATE Tag SET Icon_picture='$imgdat' WHERE Tag_ID =".$id;

      $stmt = $dbh->query($sql);
      if(!$stmt){print_r($dbh->errorInfo());}

      unlink($_FILES["upfile"]["tmp_name"]);
    }
  }
 
  $Tag_name=htmlspecialchars($_POST['Tag_name']);
  if ($Tag_name!=""){
    $sql = "UPDATE Tag SET Tag_name='$Tag_name' WHERE Tag_ID=".$id;
 
    $stmt = $dbh->query($sql);
    if(!$stmt){print_r($dbh->errorInfo());}

   
  }


}catch (PDOException $e){
    print('Error:'.$e->getMessage());
    die();
}
$dbh = null;
header('Location: tag_view.php');
exit;
?>

