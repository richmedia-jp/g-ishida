<?php

$dsn = 'mysql:dbname=mydb;host=127.0.0.1';
$user = 'root';
$pswd = '211293g2';
$options = array(
  PDO::MYSQL_ATTR_READ_DEFAULT_FILE => '/etc/my.cnf',
); 
  

try{
  $dbh = new PDO($dsn, $user, $pswd, $options);
   if ($dbh == null){
     echo 'CONNECT MISS<br>';
  }

//--- 情報の取得 -----------------------------------------------------------//
  //基本的な情報の取得
  $sql = "SELECT * FROM Salon WHERE Salon_ID = ".$id;
  $stmt = $dbh->query($sql);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  //都道府県名の取得
  $Prefecture_name = '';//都道府県名
  $sql_prefecture = "SELECT * FROM Prefecture WHERE Prefecture_ID = ".$result["Prefecture_ID"];
  if($result["Prefecture"]!=null){
    $stmt_p = $dbh->query($sql_prefecture);
    $result_p = $stmt_p->fetch(PDO::FETCH_ASSOC);
    $Prefecture_name = $result_p["Prefecture_name"];
  }
  //定休日の取得
  $holiday_text = '';//定休日についての文言をいれる変数
  $sql_holiday = "SELECT * FROM Fixed_holiday WHERE Fixed_holiday_ID = ".$result["Fixed_holiday_ID"];
  if($result["Fixed_holiday_ID"] != null){
    $stmt_h = $dbh->query($sql_holiday);
  
    if ($stmt_h == null){
      print_r($dbh->errorInfo);
    }
    else {
      $result_h = $stmt_h->fetch(PDO::FETCH_ASSOC);
      $bi_holiday = $result_h["Holiday_pattern"];//7桁の2進数
      for ($i=0; $i < 7; $i++) {
        if ($bi_holiday[$i] == '1'){//'1'のとき定休日
          switch ($i) {
            case 0:
              $holiday_text = $holiday_text.",月曜日";
              break;
            case 1:
              $holiday_text = $holiday_text.",火曜日";
              break;
            case 2:
              $holiday_text = $holiday_text.",水曜日";
              break;
            case 3:
              $holiday_text = $holiday_text.",木曜日";
              break;
            case 4:
              $holiday_text = $holiday_text.",金曜日";
              break;
            case 5:
              $holiday_text = $holiday_text.",土曜日";
              break;
            case 6:
              $holiday_text = $holiday_text.",日曜日";
              break;
          }
        }
      }
    }

  }
  if($holiday_text==''){$holiday_text='定休日なし';}
  else{$holiday_text=ltrim($holiday_text,',');}//先頭に','がついているので外す
  
//-----------------------------------------------------------------------------------------------//

  //美容室の名前
  echo '  <div id="salon_name">'.$result["Salon_name"]."</div>\n";
  //美容室の写真//サロンひとつに複数のPicture_IDがあるから全部引っ張ってこんとダメ
  echo '    <div id="salon_left_contents">'."\n";
//    echo '      <img src="img_get.php?id='. $Picture_ID .'" />';
  echo '      <img src="img_get.php?id=4" />'."\n";
  echo '      <img src="img_get.php?id=4" />'."\n";
  echo '      <img src="img_get.php?id=4" />'."\n";
  echo '    </div>'."\n";


//-- 美容室の情報 ------//
  echo '    <div id="salon_right_contents">'."\n";
  //紹介文
  echo '      <div id="salon_introduction_title">'.$result["Introduction_title"].'</div>'."\n";
  echo '      <div id="salon_introduction_text">'.$result["Introduction_text"].'</div>'."\n";
  //情報の箇条書きテーブル
  echo '      <div id="salon_basicinfo">'."\n";
  echo "        <table>\n";
  echo '          <tr><td class="tableattribute">店名</td><td class="tablevalue">'.$result["Salon_name"]."</td></tr>\n";
  echo '          <tr><td closs="tableattribute">郵便番号</td><td class="tablevalue">'.$result["Postcode"]."</td></tr>\n";
  echo '          <tr><td class="tableattribute">所在地</td><td class="tablevalue">'.$Prefecture_name." ".$result["Address1"]." ".$result["Address2"]."</td></tr>\n";
  echo '          <tr><td class="tableattribute">TEL</td><td class="tablevalue">'.$result["TEL"]."</td></tr>\n";
  echo '          <tr><td class="tableattribute">定休日</td><td class="tablevalue">'.$holiday_text."</td></tr>\n";
  echo '          <tr><td class="tableattribute">営業時間</td><td class="tablevalue">'.$result["Opening_hour"]."</td></tr>\n";
  echo '          <tr><td class="tableattribute">席数</td><td class="tablevalue">'.$result["Seats"]."</td></tr>\n";
  echo "        </table>\n";
  echo '      </div>'."\n";
  //タグ（未実装）
  echo '      <div id="salon_tag">'."\n";
  echo '      TAG</div>'."\n";

  echo '    </div>'."\n";
//--------------------------//

}catch (PDOException $e){
  print('Error:'.$e->getMessage());
  die();
}

$dbh = null;

?>



