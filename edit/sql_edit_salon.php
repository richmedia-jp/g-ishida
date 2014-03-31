<?php

$dsn = 'mysql:dbname=mydb;host=127.0.0.1';
$user = 'root';
$pswd = '211293g2';
$options = array(
  PDO::MYSQL_ATTR_READ_DEFAULT_FILE => '/etc/my.cnf',
); 


//--- 前準備　---------------------------------------------//
$id=$_GET['id'];


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

  $sql = "UPDATE Salon SET Salon_name='".htmlspecialchars($_POST['Salon_name'])."', Postcode='".htmlspecialchars($_POST['Postcode'])."', Prefecture_ID=".$_POST['Prefecture_ID'].", Address1='".htmlspecialchars($_POST['Address1'])."', Address2='".htmlspecialchars($_POST['Address2'])."', TEL='".htmlspecialchars($_POST['TEL'])."', Seats=".$Seats.", Opening_hour='".htmlspecialchars($_POST['Opening_hour'])."', Fixed_holiday_ID=".$hol_int.", Introduction_title='".htmlspecialchars($_POST['Introduction_title'])."', Introduction_text='".htmlspecialchars($_POST['Introduction_text'])."', Recommend_flag=".$rec_flag." WHERE Salon_ID=".$id;
  

  $stmt = $dbh->query($sql);
  if(!$stmt){
    print("編集失敗<br>");
    echo '<p>'.$sql.'</p>';
    print_r($dbh->errorInfo());
  }
  else{
    echo '<p>編集完了！</p>';
  }


  $sql = 'SELECT * FROM Salon WHERE Salon_ID ='.$id;
  $stmt = $dbh->query($sql);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  print("ID:".$result['Salon_ID']." ");
  print("NAME:".$result['Salon_name']."<br>\n");

  //一度タグの登録を全部消す//
  $sql="DELETE FROM Salon_has_Tag WHERE Salon_Salon_ID=".$id;
  $stmt = $dbh->query($sql);
  if($stmt==""){print_r($dbh->errorInfo());}
  //新規でタグIDと美容室IDを結びつける//(あまり良くない方法だと思う)
  for($n =1; $n < count($tagbox); $n++){
    $sql_t = "INSERT INTO Salon_has_Tag (Salon_Salon_ID, Tag_Tag_ID) VALUES (".$id.", ".$tagbox[$n].")";
    $stmt_t = $dbh->query($sql_t);
    if($stmt==""){print_r($dbh->errorInfo());}
  }

  //編集あとのタグの確認 
  $sql = 'SELECT * FROM Salon_has_Tag WHERE Salon_Salon_ID ='.$id;
  $stmt = $dbh->query($sql);
  if($stmt==""){print_r($dbh->errorInfo());}
  while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
    print("salonID:".$result['Salon_Salon_ID']." ");
    print("tagID:".$result['Tag_Tag_ID']."<br>\n");
  }

  //画像の更新
  $sql_pic = "SELECT * FROM Salon_has_SalonPicture WHERE Salon_Salon_ID = ".$id;
  $stmt_pic=$dbh->query($sql_pic);
  $picid=array(0,0,0);
  $pii=0;
  while($result_pic=$stmt_pic->fetch(PDO::FETCH_ASSOC)){
    $picid[$pii] = $result_pic['SalonPicture_Picture_ID'];//順番にupfile1,upfile,upfile3に該当する
    $pii++;
  }

  for($i = 1; $i <= 3; $i++){
    $ufn="upfile".$i;
    if ($_FILES["$ufn"]["tmp_name"]!="none"){//ファイル名の取得    
      echo 'pic'.$i;
      $fp = fopen($_FILES["$ufn"]["tmp_name"], "rb");//ファイルオープン
      if(!$fp){//空なら
       echo 'oh'.$i;
       continue;
      }
      else{
        
        $imgdat = fread($fp, filesize($_FILES["$ufn"]["tmp_name"]));
        fclose($fp);
        $imgdat = addslashes($imgdat);
        if($picid[$i-1]!=0){ 
          $sql= "UPDATE SalonPicture SET Salon_picture='$imgdat' WHERE Picture_ID =".$picid[$i-1];
        }
        else{//0のときは新規登録になる 
          $sql = "INSERT INTO SalonPicture (Salon_picture) VALUES ('$imgdat')";
          $stmt = $dbh->query($sql);
          if(!$stmt){print_r($dbh->errorInfo());}

          //いま、上で追加したSalon_pictureのIDを取得する
          $sql = 'SELECT * FROM SalonPicture WHERE Picture_ID IN (SELECT MAX(Picture_ID) FROM SalonPicture)';
          $stmt = $dbh->query($sql);
          $result = $stmt->fetch(PDO::FETCH_ASSOC);
          $Picture_ID = $result['Picture_ID'];
 
          //取得したIDをもとに中間テーブルに新規追加
          $sql = "INSERT INTO Salon_has_SalonPicture (Salon_Salon_ID, SalonPicture_Picture_ID) VALUES (".$id.", ".$Picture_ID.")";
 
        }
        $stmt = $dbh->query($sql);
        if(!$stmt){
          echo 'wow'.$i;
          print_r($dbh->errorInfo());
        }
        unlink($_FILES["$ufn"]["tmp_name"]);
      }
    }
  }


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


