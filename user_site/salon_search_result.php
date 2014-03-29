<?php
  require('header.php');
?>


<div id="container">
  <div id="contents">

    <div id="salon_search_result">
     
      <div id="salon_search_item">
        <form action="salon_search_result.php" method="post">
          <table>
            <tr><td>エリア</td><td>キーワード</td><td></td></tr>
            <tr>
              <td><input type="text" name="area" value="<?php echo htmlspecialchars($_POST['area'])?>" size="10"></td>
              <td><input type="text" name="keyword" value="<?php echo htmlspecialchars($_POST['keyword'])?>" size="40"></td>
              <td><input type="submit" value="検索"</td>
            </tr>
          </table>
        </form>
      </div>


      <div id="salon_search_list">
<?php

  $dsn = 'mysql:dbname=mydb;host=127.0.0.1';
  $user = 'root';
  $pswd = '211293g2';
  $options = array(
  PDO::MYSQL_ATTR_READ_DEFAULT_FILE => '/etc/my.cnf',
  ); 


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

    if($area != null){//エリア検索窓に入力があるとき
      //-- 都道府県の検索を先にしておく -------------//
      $pref_ID = 0;
      $sql_p = "SELECT * FROM Prefecture WHERE Prefecture_name LIKE '%".$area."%'";
      $stmt_p = $dbh->query($sql_p);
      if($stmt_p!=null){//'京都'と検索した時に東京都が引っかかる問題がある
        $result_p = $stmt_p->fetch(PDO::FETCH_ASSOC);
        $pref_ID = $result_p['Prefecture_ID'];
      }
      //----------------------------------------------//

      
      if($keyword != null){//キーワード検索窓に入力があるとき
        if($pref_ID != 0){//エリア検索ワードが都道府県名に引っかかるとき（OR条件に足す）
          $sql = "SELECT * FROM Salon WHERE (Prefecture_ID = ".$pref_ID." OR Address1 LIKE '%".$area."%' OR Address2 LIKE '%".$area."%') AND (Introduction_title LIKE '%".$keyword."%' OR Introduction_text LIKE '%".$keyword."%')";
        }
        else{//エリア検索ワードが都道府県名に引っかからない時
          $sql = "SELECT * FROM Salon WHERE (Address1 LIKE '%".$area."%' OR Address2 LIKE '%".$area."%') AND (Introduction_title LIKE '%".$keyword."%' OR Introduction_text LIKE '%".$keyword."%')";
        }
      }
      else {//キーワード検索窓には入力がないとき
        if($pref_ID != 0){//エリア検索ワードが都道府県名に引っかかるとき（OR条件に足す）
          $sql = "SELECT * FROM Salon WHERE Prefecture_ID = ".$pref_ID." OR Address1 LIKE '%".$area."%' OR Address2 LIKE '%".$area."%'";
        }
        else{//エリア検索ワードが都道府県名に引っかからない時
          $sql = "SELECT * FROM Salon WHERE Address1 LIKE '%".$area."%' OR Address2 LIKE '%".$area."%'";
        }
      }

    }
    else if($keyword != null){//エリア検索窓には入力がなく、キーワード検索窓に入力がある時//キーワードでエリア検索も兼ねても良い気がする。
      $sql = "SELECT * FROM Salon WHERE Introduction_title LIKE '%".$keyword."%' OR Introduction_text LIKE '%".$keyword."%'";
    }
    else{$sql='';}//検索窓が共に空のとき

  }

//------------------------------------------------------------------------------------------------------------//

//--- 検索開始↓ ----------------------------------------------------------------------------------------------------//
  if($sql==''){//検索窓が空のとき
    echo '<p>エリア、キーワードを入力して検索ボタンを押して下さい</p><br>';
  }
  else{

    $stmt = $dbh->query($sql);
    if($stmt==""){print_r($dbh->errorInfo());}
    else{
      while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
        echo '        <div class="salon_search_list_box">'."\n";
        echo '          <div class="salon_name">'.'<a href=salon_detail.php?id='.$result['Salon_ID'].'>'.$result['Salon_name']."</a></div>\n";
 //     echo '          <a href="salon_detail.php?id='.$result['Salon_ID'].'"><img src="img_get.php?id='.$result['Picture_ID'].'" /></a>'."\n";/Picture_IDは複数あるから、そのうちを一つを取ってくるような処理にしないとマズイ。そもそもこのwhileが同じIDのものを複数発見する可能性がある気がする /
        echo '          <a href="salon_detail.php?id='.$result['Salon_ID'].'"><img src="img_get.php?id=4" alt="美容室のイメージ" /></a>'."\n";
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
      if($result==null){//検索した結果、該当データがなかったとき(↑のwhileに入らない時)
        echo '<p>該当する美容室はありませんでした。エリア、キーワードを変えて検索してみてください</p><br>';
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


