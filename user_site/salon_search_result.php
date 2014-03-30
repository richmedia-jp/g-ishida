<?php
require('header.php');


$dsn = 'mysql:dbname=mydb;host=127.0.0.1';
$user = 'root';
$pswd = '211293g2';
$options = array(
PDO::MYSQL_ATTR_READ_DEFAULT_FILE => '/etc/my.cnf',
); 

try{
  $dbh_t = new PDO($dsn,$user, $pswd, $options);
  $sql_t = "SELECT * FROM Tag WHERE Tag_ID >= 2 AND Tag_ID <= 6";
  $stmt_t = $dbh_t->query($sql_t);

}catch (PDOException $e){
  print('Error:'.$e->getMessage());
  die();
}
$dbh_t=null;


?>


<div id="container">
  <div id="contents">

    <div id="salon_search_result">
      <form action="salon_search_result.php" method="post">
      <div id="salon_search_item">
        <div id="salon_search_ward">
          <table>
            <tr><td>エリア</td><td>キーワード</td><td></td></tr>
            <tr>
              <td><input type="text" name="area" value="<?php echo htmlspecialchars($_POST['area'])?>" size="10"></td>
              <td><input type="text" name="keyword" value="<?php echo htmlspecialchars($_POST['keyword'])?>" size="40"></td>
              <td><input type="submit" value="検索"</td>
            </tr>
          </table>
        </div>

        <div id="salon_search_tag">
          <table>
<?php
          $ti=0;
          while($result_t=$stmt_t->fetch(PDO::FETCH_ASSOC)){
            if($ti%3==0){echo '          <tr>';}
            echo '<td><img src="../get_tag_img.php?id='.$result_t['Tag_ID'].'"></td>';
            echo '<td>'.$result_t['Tag_name'].'</td>';
            echo '<td><input type="checkbox" name="tag[]" class="check_form" value="'.$result_t['Tag_ID'].'"';
            for($i=0;$i <count($_POST["tag"]); $i++){
              if($result_t['Tag_ID']==$_POST["tag"][$i]){
                echo ' checked';
              }
            }
            echo'></td>';
            if($ti%3==2){echo "</tr>\n";}
            $ti++;
          }
          if($ti%3!=0){echo "</tr>\n";}
?>
          </table>
        </div>

      </div>
      </form>

      <div id="salon_search_list">

<?php
try{
  $dbh = new PDO($dsn, $user, $pswd, $options); 

//--- SQL文の決定 ------------------------------------------------------------------------------------------//
  if($_GET['local_area'] != null){//都道府県からの検索のリンクから飛んできた場合
    $sql = "SELECT * FROM Salon WHERE Prefecture_ID = ".$_GET['local_area'];
  }
  else{//エリア・キーワード検索（AND検索）

    $area="";
    $keyword="";
 
    if($_POST['area']!=""){$area=htmlspecialchars($_POST['area']);}
    if($_POST['keyword']!=""){$keyword=htmlspecialchars($_POST['keyword']);}


//新規で書いた処理
    $area_sql="";
    $key_sql="";
    $tag_sql="";
    if($area!=null){
    //-- 都道府県はIDしかわからないのe都道府県で該当するならそれをOR条件に入れるために都道府県名を取得する ----//
      $pref_ID = 0;
      $sql_p = "SELECT * FROM Prefecture WHERE Prefecture_name LIKE '%".$area."%'";
      $stmt_p = $dbh->query($sql_p);
      if($stmt_p!=null){//'京都'と検索した時に東京都が引っかかる問題がある
        $result_p = $stmt_p->fetch(PDO::FETCH_ASSOC);
        $pref_ID = $result_p['Prefecture_ID'];
      }
    //----------------------------------------------------------------------------------------------------------//
    $area_sql="(Address1 LIKE '%".$area."%' OR Address2 LIKE '%".$area."%'";
    if($pref_ID!=0){
      $area_sql = $area_sql.' OR Prefecture_ID='.$pref_ID.')';
    }
    else{$area_sql = $area_sql.')';}
    }

    if($keyword != null){
      $key_sql = "(Introduction_title LIKE '%".$keyword."%' OR Introduction_text LIKE '%".$keyword."%')";
    }
    
    if(isset($_POST["tag"])){//保留　中間テーブルの存在がむずい
      $tagbox=array(0);//23456
      $sql_t='';
      for($i=0; $i < count($_POST["tag"]); $i++) {
        if($i==0){
          $sql_t = "(Tag_Tag_ID = ".$_POST["tag"][$i];
        }
        else{
          $sql_t = $sql_t." OR Tag_Tag_ID = ".$_POST["tag"][$i];
        }
      }

      $sql_t = "SELECT DISTINCT Salon_Salon_ID FROM Salon_has_Tag WHERE ".$sql_t.")";
      $stmt_st = $dbh->query($sql_t);
      if($stmt_st==""){print_r($dbh->errorInfo());}
      $sidbox=array();
      while($result_st=$stmt_st->fetch(PDO::FETCH_ASSOC)){
        //echo $result_st['Salon_Salon_ID']."\n";
        array_push($sidbox, $result_st['Salon_Salon_ID']);
      }

      if(count($sidbox) > 0){//条件のタグを一部でもみたすデータが存在したら
        $tidbox=array();

        for($i=0; $i < count($sidbox); $i++){
          //候補のIDと一致するデータを全部出す//$shastboxに入れる
          $sql_t = "SELECT * FROM Salon_has_Tag WHERE Salon_Salon_ID = ".$sidbox[$i];
          $stmt_st = $dbh->query($sql_t);
          if($stmt_st ==""){print_r($dbh->erroIfo());}
          $shastbox=array();
          while($result_st=$stmt_st->fetch(PDO::FETCH_ASSOC)){
            array_push($shastbox,$result_st['Tag_Tag_ID']);
          }

          //候補のIDがもってるチェック済み項目($shastbox)の中に、検索条件のチェック($_POST["tag"])がすべてあるかを調べる
          $all_tagcheck_flag=false;
          for($j=0; $j<count($_POST["tag"]); $j++){
            $tagcheck_flag=false;
            for($k=0; $k<count($shastbox);$k++){
              if($_POST["tag"][$j] == $shastbox[$k]){$tagcheck_flag=true;break;}//該当するタグをもっていたら
            }
            if($tagcheck_flag==true){//該当するタグがあった
              $all_tagcheck_flag=true;
            }
            else{$all_tagcheck_flag=false;break;}
          }
          if($all_tagcheck_flag == true){array_push($tidbox, $sidbox[$i]);}//最後まで#all_tagcheck_flag==trueならここに入る

        }
        //print_r($tidbox);//すべての条件のチェックをみたしていたIDを表示

        if(count($tidbox)>0){
          for($i=0; $i < count($tidbox); $i++){
            if($i==0){$tag_sql="(Salon_ID = ".$tidbox[$i];}
            else{$tag_sql=$tag_sql." OR Salon_ID = ".$tidbox[$i];}
          }
          $tag_sql=$tag_sql.")";
        }
        else{$tag_sql="(Salon_ID < 1)";}//タグの条件に当てはまるデータがなかった
        
      }
      else{
        $tag_sql="(Salon_ID < 1)";//タグの条件にあてはまるデータがなかったため。絶対にひっかからない文言にする
      }

    }



    if($area_sql==""){//エリアなし

      if($key_sql==""){//キーワードなし

        if($tag_sql==""){//エリアなし、キーワードなし、タグチェックなし
          $sql = '';
        }
        else{//タグのみあり
          $sql = 'SELECT * FROM Salon WHERE '.$tag_sql;
        }

      }
      else{//キーワードあり
        $sql = "SELECT * FROM Salon WHERE ".$key_sql;
        if($tag_sql != ""){//キーワードあり、タグあり
          $sql = $sql." AND ".$tag_sql;
        }
        else{}//キーワードあり、タグ無し
      }

    }
    else{//エリアあり
      $sql = "SELECT * FROM Salon WHERE ".$area_sql;
      if($key_sql==""){//キーワードなし

        if($tag_sql!=""){//エリアあり、キーワードなし、タグあり
         $sql =$sql." AND ".$tag_sql;
        }
        else{}//エリアあり、キーワードなし、タグ無し

      }
      else{//キーワードあり
        $sql = $sql." AND  ".$key_sql;
        if($tag_sql != ""){//エリアあり、キーワードあり、タグ有り
          $sql = $sql." AND ".$tag_sql;
        }
        else{}//エリア有り、キーワードあり、タグ無し
      }

    }

  }
 
//------------------------------------------------------------------------------------------------------------//

//--- 検索開始↓ ----------------------------------------------------------------------------------------------------//
  if($sql==''){//検索窓が空のとき
    echo '<p>条件を指定してから検索ボタンを押して下さい</p><br>';
  }
  else{

    $stmt = $dbh->query($sql);
    if($stmt==""){echo $sql;print_r($dbh->errorInfo());}
    else{
      $no_result_flag=1;//該当件数が0かどうかを判定するフラグ。下のwhile文に入れば、0として該当件数アリと判断
      while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
        //画像のIDの取得-------------//
        $sql_pic = "SELECT * FROM Salon_has_SalonPicture WHERE Salon_Salon_ID = ".$result['Salon_ID'];
        $stmt_pic = $dbh->query($sql_pic);
        $Picture_ID=4;//デフォルト値
        while($result_pic = $stmt_pic->fetch(PDO::FETCH_ASSOC)){
          $Picture_ID=$result_pic['SalonPicture_Picture_ID'];
          break;//代表値ひとつだけでいいので1回でbreak
        }
        //-------------------------///

        $no_result_flag=0;
        echo '        <div class="salon_search_list_box">'."\n";
        echo '          <div class="salon_name">'.'<a href=salon_detail.php?id='.$result['Salon_ID'].'>'.$result['Salon_name']."</a></div>\n";
        echo '          <a href="salon_detail.php?id='.$result['Salon_ID'].'"><img src="../get_salon_img.php?id='.$Picture_ID.'" alt="美容室のイメージ" /></a>'."\n";
        echo '          <div class="introduction_text">'.$result['Introduction_text'].'</div>'."\n";
        echo '          <div class="basic_info">'."\n";
        echo "            <table>\n";

        //都道府県名の取得
        $Prefecture_name='';
        if($result['Prefecture_ID']!=null){
          $sql_p = "SELECT * FROM Prefecture WHERE Prefecture_ID = ".$result['Prefecture_ID'];
          $stmt_p = $dbh->query($sql_p);
          $result_p = $stmt_p->fetch(PDO::FETCH_ASSOC);
          $Prefecture_name = $result_p['Prefecture_name'];
        }
        echo '              <tr><td class="tableattribute">所在地</td><td class="tablevalue">'.$Prefecture_name." ".$result["Address1"]." ".$result["Address2"]."</td></tr>\n";
    
        echo '              <tr><td class="tableattribute">TEL</td><td class="tablevalue">'.$result["TEL"]."</td></tr>\n";
        echo '              <tr><td class="tableattribute">定休日</td><td class="tablevalue">'.$result["Fixed_holiday"]."</td></tr>\n";
        echo '              <tr><td class="tableattribute">営業時間</td><td class="tablevalue">'.$result["Opening_hour"]."</td></tr>\n";
        echo "            </table>\n";
        echo "          </div>\n";
        echo "        </div>\n";
      }
      if ($no_result_flag==1){
        echo "<p>該当するデータが見つかりませんでした。条件を変えて検索してみてください。</p><br>\n";
      }
      
   }

  }
//-----------------------------------------------------------------------------------------------------------------------//

}catch (PDOException $e){
  print('Error:'.$e->getMessage());
  die();
}
$dbh = null;

?>

      </div>

    </div>


    <div id="right_menu"> 
      <p>何かしらのコンテンツ。人気サロン並べるとか。</p>
    </div>

  </div><!-- !contents -->
</div><!-- !container -->



<?php





require('footer.php');

?>


