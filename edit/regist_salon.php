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
  $sql = "SELECT * FROM Prefecture";// 都道府県のプルダウンメニューを作成するのに使用
  $stmt = $dbh->query($sql);

  $sql_t = "SELECT * FROM Tag WHERE (Tag_ID = 2) OR (Tag_ID = 3) OR (Tag_ID = 4) OR (Tag_ID = 5) OR (Tag_ID = 6)";
  $stmt_t = $dbh->query($sql_t);

}catch (PDOException $e){
  print('Error:'.$e->getMessage());
  die();
}
$dbh = null;


 
?>

<div id="container">

  <div id="input_info">
    <p class="edit_caption">美容室 新規登録</p>
    <form method="POST" enctype="multipart/form-data" action="sql_regist_salon.php"> 

      <input type="hidden" name="MAX_FILE_SIZE" value="100000"><!--65536">-->

      <table>
        <tr>
          <td>美容室名</td> <td><input type="text" name="Salon_name" size="30" class="input_form"></td>
        </tr>
        <tr>
          <td>郵便番号</td> <td><input type="text" name="Postcode" size="15" class="input_form"></td>
        </tr>
        <tr>
          <td>都道府県</td><!-- プルダウンメニューから選んで、データとしてはIDだけを送信する -->
          <td>
            <select name="Prefecture_ID">
              <option value="" selected>- 都道府県を選択 -</option><br>
              <?php
              while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
                echo '              <option value="'.$result['Prefecture_ID'].'">'.$result['Prefecture_name'].'</option><br>'."\n";
              }
              ?>
            </select>
          </td>
        </tr>
        <tr>
          <td>住所1</td> <td><input type="text" name="Address1" size="60" class="input_form"></td>
        </tr>
        <tr>
          <td>住所2</td> <td><input type="text" name="Address2" size="60" class="input_form"></td>
        </tr>
        <tr>
          <td>電話番号</td> <td><input type="text" name="TEL" size="20" class="input_form"></td>
        </tr>
        <tr>
          <td>席数</td> <td><input type="text" name="Seats" size="10" class="input_form"></td>
        </tr>
        <tr>
          <td>営業時間</td> <td><input type="text" name="Opening_hour" size="60" class="input_form"></td>
        </tr>
        <tr>
          <td>定休日</td>
        </tr>
      </table>

      <table class="holiday">
        <tr>
          <td>月</td><td><input type="checkbox" name="holiday[]" value="MON" class="check_holiday"></td>
          <td>火</td><td><input type="checkbox" name="holiday[]" value="TUE" class="check_holiday"></td>
          <td>水</td><td><input type="checkbox" name="holiday[]" value="WED" class="check_form"></td>
          <td>木</td><td><input type="checkbox" name="holiday[]" value="THU" class="check_form"></td>
        </tr>
        <tr>
          <td>金</td><td><input type="checkbox" name="holiday[]" value="FRI" class="check_form"></td>
          <td>土</td><td><input type="checkbox" name="holiday[]" value="SAT" class="check_form"></td>
          <td>日</td><td><input type="checkbox" name="holiday[]" value="SUN" class="check_from"></td>
        </tr>
      </table>

      <table>
        <tr>
          <td>紹介文タイトル</td> <td><input type="text" name="Introduction_title" size="60" class="input_form"></td>
        </tr>
        <tr>
          <td>紹介文</td> <td><input type="text" name="Introduction_text" size="60" class="input_form"></td>
        </tr>
        <tr>
          <td>おすすめフラッグ</td> <td><input type="checkbox" name="Recommend_flag" value="on" class="input_form"></td>
        </tr>   
      </table> 
      
      <table class="tag_table">
<?php
        while($result_t = $stmt_t->fetch(PDO::FETCH_ASSOC)){
          echo '      <tr><td><img src="get_tag_img.php?id='.$result_t['Tag_ID'].'"/></td> <td>'.$result_t['Tag_name'].'</td> <td><input type ="checkbox" name="tag[]" value="'.$result_t['Tag_ID'].'" class="check_form"></td> </tr>'."\n";
        }
?>
      </table>   
      
      <table>
        <tr>
          <td>画像のファイルを選択してください</td>
          <td><input type="file" size="50" name="upfile1"></td><!-- ファイル選択ボタン -->
        </tr>
        <tr>
          <td>画像のファイルを選択してください</td>
          <td><input type="file" size="50" name="upfile2"></td><!-- ファイル選択ボタン -->
        </tr>
        <tr>
          <td>画像のファイルを選択してください</td>
          <td><input type="file" size="50" name="upfile3"></td><!-- ファイル選択ボタン -->
        </tr>
     </table>
 


      <!-- あと画像のアップロードと公開・非公開設定のチェックボックスを入れる  -->
      <input type="reset" name="reset" value="リセット">  
      <input type="submit" value="新規登録" class="input_form_button">
    </form>



  </div>

</div>

<?php require("footer.php"); ?>



