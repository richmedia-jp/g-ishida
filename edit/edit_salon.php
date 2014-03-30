<?php require("header.php"); ?>
<?php

$dsn = 'mysql:dbname=mydb;host=127.0.0.1';
$user = 'root';
$pswd = '211293g2';
$options = array(
  PDO::MYSQL_ATTR_READ_DEFAULT_FILE => '/etc/my.cnf',
); 

try{
  $dbh = new PDO($dsn, $user, $pswd, $options);
  $sql_p = "SELECT * FROM Prefecture";
  $stmt_p = $dbh->query($sql_p);//都道府県のプルダウンメニューを作成するのに使用

  $id = intval($_GET['id']);
  $sql = "SELECT * FROM Salon WHERE Salon_ID =".$id;
  $stmt = $dbh->query($sql);
  if($stmt!=""){
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
  }
  else{
    echo '<p>NO DATA</p>';
    die();
  }


  $hPt='0000000';//定休日をあらわす7桁の2進数
  $sql_h = "SELECT * FROM Fixed_holiday WHERE Fixed_holiday_ID = ".$result['Fixed_holiday_ID'];
  $stmt_h = $dbh->query($sql_h);
  if($stmt_h!=""){
    $result_h = $stmt_h->fetch(PDO::FETCH_ASSOC);
    $hPt = $result_h['Holiday_pattern'];
  }
     

  $sql_t = "SELECT * FROM Tag WHERE (Tag_ID = 2) OR (Tag_ID = 3) OR (Tag_ID = 4) OR (Tag_ID = 5) OR (Tag_ID = 6)";
  $stmt_t = $dbh->query($sql_t);

  $tagCh=array();//check済みのTag_ID入れる配列
  $sql_tch ="SELECT * FROM Salon_has_Tag WHERE Salon_Salon_ID =".$id;
  $stmt_tch =$dbh->query($sql_tch);
  while($result_tch=$stmt_tch->fetch(PDO::FETCH_ASSOC)){
    array_push($tagCh,$result_tch['Tag_Tag_ID']);
  }
//  print_r($tagCh);


}catch (PDOException $e){
  print('Error:'.$e->getMessage());
  die();
}
$dbh = null;


 
?>

<div id="container">

  <div id="input_info">
    <p class="edit_caption">美容室 編集</p>
    <form action="sql_edit_salon.php?id=<?php echo $id;?>" method="post"> 

 <!--   <form action="sql_edit_salon.php" method="post"> 
 -->     <table>
        <tr>
          <td>美容室名</td> <td><input type="text" name="Salon_name" size="30" class="input_form" value="<?php echo $result['Salon_name'];?>"></td>
        </tr>
        <tr>
          <td>郵便番号</td> <td><input type="text" name="Postcode" size="15" class="input_form" value="<?php echo $result['Postcode'];?>"></td>
        </tr>
        <tr>
          <td>都道府県</td><!-- プルダウンメニューから選んで、データとしてはIDだけを送信する -->
          <td>
            <select name="Prefecture_ID">
              <option value="">- 都道府県を選択 -</option><br>
              <?php
              while($result_p = $stmt_p->fetch(PDO::FETCH_ASSOC)){
                if ($result_p['Prefecture_ID']==$result['Prefecture_ID']){
                  echo '              <option value="'.$result_p['Prefecture_ID'].'" selected>'.$result_p['Prefecture_name'].'</option><br>'."\n";
                }
                else{
                  echo '              <option value="'.$result_p['Prefecture_ID'].'">'.$result_p['Prefecture_name'].'</option><br>'."\n";
                }
              }
              ?>
            </select>
          </td>
        </tr>
        <tr>
          <td>住所1</td> <td><input type="text" name="Address1" size="60" class="input_form" value="<?php echo $result['Address1'];?>"></td>
        </tr>
        <tr>
          <td>住所2</td> <td><input type="text" name="Address2" size="60" class="input_form" value="<?php echo $result['Address2'];?>"></td>
        </tr>
        <tr>
          <td>電話番号</td> <td><input type="text" name="TEL" size="20" class="input_form" value="<?php echo $result['TEL'];?>"></td>
        </tr>
        <tr>
          <td>席数</td> <td><input type="text" name="Seats" size="10" class="input_form" value="<?php echo $result['Seats'];?>"></td>
        </tr>
        <tr>
          <td>営業時間</td> <td><input type="text" name="Opening_hour" size="60" class="input_form" value="<?php echo $result['Opening_hour'];?>"></td>
        </tr>
        <tr>
          <td>定休日</td>
        </tr>
      </table>
      <table class="holiday">
        <tr>
          <td>月</td><td><input type="checkbox" name="holiday[]" value="MON" class="check_holiday" <?php if($hPt[0]=='1'){echo 'checked';}?>></td>
          <td>火</td><td><input type="checkbox" name="holiday[]" value="TUE" class="check_holiday" <?php if($hPt[1]=='1'){echo 'checked';}?>></td>
          <td>水</td><td><input type="checkbox" name="holiday[]" value="WED" class="check_form" <?php if($hPt[2]=='1'){echo 'checked';}?>></td>
          <td>木</td><td><input type="checkbox" name="holiday[]" value="THU" class="check_form" <?php if($hPt[3]=='1'){echo 'checked';}?>></td>
        </tr>
        <tr>
          <td>金</td><td><input type="checkbox" name="holiday[]" value="FRI" class="check_form" <?php if($hPt[4]=='1'){echo 'checked';}?>></td>
          <td>土</td><td><input type="checkbox" name="holiday[]" value="SAT" class="check_form" <?php if($hPt[5]=='1'){echo 'checked';}?>></td>
          <td>日</td><td><input type="checkbox" name="holiday[]" value="SUN" class="check_from" <?php if($hPt[6]=='1'){echo 'checked';}?>></td>
        </tr>
      </table>
      <table>
        <tr>
          <td>紹介文タイトル</td> <td><input type="text" name="Introduction_title" size="60" class="input_form" value="<?php echo $result['Introduction_title'];?>"></td>
        </tr>
        <tr>
          <td>紹介文</td> <td><input type="text" name="Introduction_text" size="60" class="input_form" value="<?php echo $result['Introduction_text'];?>"></td>
        </tr>
        <tr>
          <td>おすすめフラッグ</td> <td><input type="checkbox" name="Recommend_flag" value="on" class="input_form" <?php if($result['Introduction_flag']==1){echo 'checked';} ?>></td>
        </tr>
       
      </table>    
      
      <table class="tag_table">
<?php
        while($result_t = $stmt_t->fetch(PDO::FETCH_ASSOC)){
          echo '      <tr><td><img src="get_tag_img.php?id='.$result_t['Tag_ID'].'"/></td> <td>'.$result_t['Tag_name'].'</td> <td><input type ="checkbox" name="tag[]" value="'.$result_t['Tag_ID'].'" class="check_form"';
          for($n=0;$n < count($tagCh); $n++){
           if($tagCh[$n]==$result_t['Tag_ID']){echo ' checked';break;}
          }

          echo '></td> </tr>'."\n";
        }
?>
      </table>   


      <!-- あと画像のアップロードと公開・非公開設定のチェックボックスを入れる  -->
    
      <input type="submit" value="編集完了" class="input_form_button">
    </form>



  </div>

</div>

<?php require("footer.php"); ?>



