<?php
  require('header.php');

$dsn = 'mysql:dbname=mydb;host=127.0.0.1';
$user = 'root';
$pswd = '211293g2';
$options = array(
  PDO::MYSQL_ATTR_READ_DEFAULT_FILE => '/etc/my.cnf',
); 

try{
  $dbh = new PDO($dsn, $user, $pswd, $options);
  
  $sql_salon="SELECT * FROM Salon ORDER BY Traffic_count DESC LIMIT 10";
 // $sql_stylist="SELECT * FROM Stylist ORDER BY Traffic_count DESC LIMIT 10";
  $stmt_salon=$dbh->query($sql_salon);

  $access_picid=array();
  $access_salonid=array();
  $access_salonname=array();

  while($result_salon=$stmt_salon->fetch(PDO::FETCH_ASSOC)){
     $sql_pic = "SELECT * FROM Salon_has_SalonPicture WHERE Salon_Salon_ID = ".$result_salon['Salon_ID'];
     $stmt_pic = $dbh->query($sql_pic);
     if($stmt_pic==null){print_r($dbh->errorInfo());}
     $result_pic = $stmt_pic->fetch(PDO::FETCH_ASSOC);
     $picid = $result_pic['SalonPicture_Picture_ID'];

     if($picid!=""){array_push($access_picid, $picid);}
     else{array_push($access_picid, 4);}

     array_push($access_salonid, $result_salon['Salon_ID']);
     array_push($access_salonname, $result_salon['Salon_name']);
  }


}catch (PDOException $e){
  print('Error:'.$e->getMessage());
  die();
}
$dbh = null;
   


?>

  <div id="container">
    <div id="contents">
      <div id="accesstop_salon">
<?php
      for($i = 0; $i < count($access_salonid); $i++){
       echo '<div class="top_salon_box">'."\n"; 
       echo '<a href="salon_detail.php?id='.$access_salonid[$i].'"><img src="../get_salon_img.php?id='.$access_picid[$i].'">'."</a>\n";
       echo '<p>'.$access_salonname[$i].'</p><br>';
       echo "</div>\n"; 
      }
?>
     </div>
     <div id="accesstop_center">
       <a class="search_btn" href="salon_search_top.php">美容室を検索</a>
       <a class="search_btn" href="stylist_search_top.php">美容師を検索</a>
     </div>
     <div id="accesstop_stylist">
     </div>

    </div>
  </div>



<?php
  require('footer.php');
?>

