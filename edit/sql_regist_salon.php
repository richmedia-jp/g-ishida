<?php

$dsn = 'mysql:dbname=mydb;host=127.0.0.1';
$user = 'root';
$pswd = '211293g2';
$options = array(
  PDO::MYSQL_ATTR_READ_DEFAULT_FILE => '/etc/my.cnf',
); 


//--- 前準備　---------------------------------------------//
//-- 定休日 --//
$hol='0000000';//結局使わない
$hol_int=0;
if(isset($_POST["holiday"])){

  for($i=0; $i < count($_POST["holiday"]); $i++) {
    switch ($_POST["holiday"][$i]) {
      case 'MON':
        //echo "MONDAY is holiday<br>";
        $hol_int += pow(2,6);
        $hol[0] = '1';
      break;
      case 'TUE':
        //echo "TUESDAY is holiday<br>";
        $hol_int += pow(2,5);
        $hol[1] ='1';
      break;
      case 'WED':
        //echo "WEDNESDAY is holiday<br>";
        $hol_int += pow(2,4);
        $hol[2] ='1';
      break;
      case 'THU':
        //echo "THURSDAY is holiday<br>";
        $hol_int += pow(2,3);
        $hol[3] ='1';
      break;
      case 'FRI':
        //echo "FRISDAY is holiday<br>";
        $hol_int += pow(2,2);
        $hol[4] ='1';
      break;
      case 'SAT':
        //echo "SATURSDAY is hiloday<br>";
        $hol_int += pow(2,1);
        $hol[5] ='1';
      break;
      case 'SUN':
        //echo "SUNDAY is holiday<br>";
        $hol_int += pow(2,0);
        $hol[6] ='1';
      break;
      default;
      break;
    }
  }
//  echo "holint=".$hol_int." ".decbin($hol_int)." ".$hol."<br>";
}
else {
 // print("NO CHECK\n");
 // $hol_int=0;
 // $hol='0000000';
}
$hol_int++;//Fixed_holiday_IDは0始まりではないため、1ずれている

//-- タグ --//
$tagbox=array(0);//23456
if(isset($_POST["tag"])){
  for($i=0; $i < count($_POST["tag"]); $i++) {
    array_push($tagbox, intval($_POST["tag"][$i]));
  }
//  print_r($tagbox);
}


//-- 席数 --//
$Seats = intval($_POST["Seats"]);//席数はINT型

//-- おすすめフラグ  --//
$rec_flag = 0;
if($_POST['Recommend_flag'] == 'on'){
  $rec_flag = 1;//1ならオススメ
}


//---------------------------------------------------------//

try{
  $dbh = new PDO($dsn, $user, $pswd, $options);

 //-- 基本的な情報の登録  -------------------------------------------------------------------------------------//
  $sql1 = "INSERT INTO Salon (Salon_name, Postcode, Prefecture_ID, Address1, Address2, TEL, Seats, Opening_hour, Fixed_holiday_ID, Introduction_title, Introduction_text, Recommend_flag) ";

  $sql2= "VALUES ('".$_POST['Salon_name']."','".$_POST['Postcode']."',".$_POST['Prefecture_ID'].",'".$_POST['Address1']."','".$_POST['Address2']."','".$_POST['TEL']."',".$Seats.",'".$_POST['Opening_hour']."',".$hol_int.",'".$_POST['Introduction_title']."','".$_POST['Introduction_text']."',".$rec_flag.")";
  
  $sql = "$sql1"."$sql2";
 

  $stmt = $dbh->query($sql);
  if(!$stmt){
    print("登録失敗<br>");
    echo '<p>'.$sql.'</p>';
    print_r($dbh->errorInfo());
  }
  else{
    echo '<p>登録完了！</p>';
  }

 //--------------------------------------------------------------------------------------//

 //-- タグの登録 ------------------------------------------------------------------------------------//
  $sql = 'SELECT * FROM Salon WHERE Salon_ID IN (SELECT MAX(Salon_ID) FROM Salon)';
  $stmt = $dbh->query($sql);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  print("ID:".$result['Salon_ID']." ");
  print("NAME:".$result['Salon_name']."<br>\n");
  $Salon_ID = $result['Salon_ID'];

  for($n =1; $n < count($tagbox); $n++){
    $sql_t = "INSERT INTO Salon_has_Tag (Salon_Salon_ID, Tag_Tag_ID) VALUES (".$Salon_ID.", ".$tagbox[$n].")";
    $stmt_t = $dbh->query($sql_t);
    if($stmt_t==""){print_r($dbh->errorInfo());}
  }

  $sql = 'SELECT * FROM Salon_has_Tag';
  $stmt = $dbh->query($sql);
  if($stmt==""){print_r($dbh->errorInfo());}
  while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
    print("salonID:".$result['Salon_Salon_ID']." ");
    print("tagID:".$result['Tag_Tag_ID']."<br>\n");
  }
  
 //--------------------------------------------------------------------------------------//

 //--- 画像の登録 -----------------------------------------------------------------------------------//

  for($i=1; $i<=3; $i++){//画像は3つまでとしているので注意
    $ufn='upfile'.$i;
    if ($_FILES["$ufn"]["tmp_name"]!="none"){//ファイル名の取得
      $fp = fopen($_FILES["$ufn"]["tmp_name"], "rb");//ファイルオープン
      if(!$fp){//空なら
      //  echo '<p>FILE IS EMPTY</p>';
      //  echo $_FILES["$ufn"]['error'];
        continue;
      }
      $imgdat = fread($fp, filesize($_FILES["$ufn"]["tmp_name"]));//ファイルの読み込み/第２引数は長さ（最高何バイトまで読み込むかということ。filesize(ファイル名)で丸々読み込める。

      fclose($fp);

      $imgdat = addslashes($imgdat);//エスケープ文字に対する処理らしい
    }
  
    $sql = "INSERT INTO SalonPicture (Salon_picture) VALUES ('$imgdat')";
    $stmt = $dbh->query($sql);
    if (!$stmt){
    //  echo "<p>画像登録失敗</p><br>\n";
      print_r($dbh->errorInfo());
      continue;
    }
//    else{echo '<p>成功'.$i.'</p>';}
 
    unlink($_FILES["$ufn"]["tmp_name"]);

    $sql = 'SELECT * FROM SalonPicture WHERE Picture_ID IN (SELECT MAX(Picture_ID) FROM SalonPicture)';
    $stmt = $dbh->query($sql);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $Picture_ID = $result['Picture_ID'];
     
    $sql_t = "INSERT INTO Salon_has_SalonPicture (Salon_Salon_ID, SalonPicture_Picture_ID) VALUES (".$Salon_ID.", ".$Picture_ID.")";
    $stmt_t = $dbh->query($sql_t);
    if($stmt_t==""){print_r($dbh->errorInfo());}
  }
  
  $sql = 'SELECT * FROM Salon_has_SalonPicture';
  $stmt = $dbh->query($sql);
  if($stmt==""){print_r($dbh->errorInfo());}
  while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
    print("salonID:".$result['Salon_Salon_ID']." ");
    print("PicID:".$result['SalonPicture_Picture_ID']."<br>\n");
  }
   
 //--------------------------------------------------------------------------------------//

  echo '<a href="salon_view.php">美容室一覧戻る</a>';//自動で戻るようにしたほうがいいかも

}catch (PDOException $e){
    print('Error:'.$e->getMessage());
    die();
}
$dbh = null;



/*
header("Location:salon_veiw.php");
*/
?>


