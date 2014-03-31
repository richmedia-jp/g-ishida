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
  $sql_t = "SELECT * FROM Tag WHERE Tag_ID >= 2 AND Tag_ID <= 6";
  $stmt_t = $dbh->query($sql_t);
  
  $sql="SELECT * FROM Salon WHERE Recommend_flag = 1 ORDER BY Traffic_count DESC LIMIT 5";
  $stmt=$dbh->query($sql);
  $recommend_id=array();
  $recommend_namebox=array();
  $recommend_imgidbox=array();//4はデフォルト値
  while($result=$stmt->fetch(PDO::FETCH_ASSOC)){
     $sql_p = "SELECT * FROM Salon_has_SalonPicture WHERE Salon_Salon_ID = ".$result['Salon_ID'];
     $stmt_p = $dbh->query($sql_p);
     if($stmt_p==null){print_r($dbh->errorInfo());}
     $result_p = $stmt_p->fetch(PDO::FETCH_ASSOC);
     $picid = $result_p['SalonPicture_Picture_ID'];

     if($picid!=""){array_push($recommend_imgidbox,$picid);}
     else{array_push($recommend_imgidbox,4);}
     array_push($recommend_id,$result['Salon_ID']);
     array_push($recommend_namebox,$result['Salon_name']);
  }

}catch (PDOException $e){
  print('Error:'.$e->getMessage());
  die();
}
$dbh = null;
     
?>

<div id="container">
  <div id="contents">

    <div id="prefecture_search">

      <p>都道府県から美容室を探す</p><!-- 都道府県のリストを並べる  -->

      <div class="local_area">北海道・東北</div>
      <ul>
        <li><a href="salon_search_result.php?local_area=1">北海道</a></li>
        <li><a href="salon_search_result.php?local_area=2">青森</a></li>
        <li><a href="salon_search_result.php?local_area=3">岩手</a></li>
        <li><a href="salon_search_result.php?local_area=4">宮城</a></li>
        <li><a href="salon_search_result.php?local_area=5">秋田</a></li>
        <li><a href="salon_search_result.php?local_area=6">山形</a></li>
        <li><a href="salon_search_result.php?local_area=7">福島</a></li>
      </ul>

      <div class="local_area">関東</div>
      <ul>
        <li><a href="salon_search_result.php?local_area=13">東京<a></li>
        <li><a href="salon_search_result.php?local_area=14">神奈川</a></li>
        <li><a href="salon_search_result.php?local_area=11">埼玉</a></li>
        <li><a href="salon_search_result.php?local_area=12">千葉</a></li>
        <li><a href="salon_search_result.php?local_area=9">栃木</a></li>
        <li><a href="salon_search_result.php?local_area=8">茨城</a></li>
        <li><a href="salon_search_result.php?local_area=10">群馬</a></li>
      </ul>

      <div class="local_area">中部</div>
      <ul>
        <li><a href="salon_search_result.php?local_area=23">愛知</a></li>
        <li><a href="salon_search_result.php?local_area=21">岐阜</a></li>
        <li><a href="salon_search_result.php?local_area=22">静岡</a></li>
        <li><a href="salon_search_result.php?local_area=24">三重</a></li>
        <li><a href="salon_search_result.php?local_area=15">新潟</a></li>
        <li><a href="salon_search_result.php?local_area=19">山梨</a></li> 
        <li><a href="salon_search_result.php?local_area=20">長野</a></li> 
        <li><a href="salon_search_result.php?local_area=17">石川</a></li> 
        <li><a href="salon_search_result.php?local_area=16">富山</a></li> 
        <li><a href="salon_search_result.php?local_area=18">福井</a></li>
      </ul>

      <div class="local_area">関西</div>
      <ul>
        <li><a href="salon_search_result.php?local_area=27">大阪</a></li>
        <li><a href="salon_search_result.php?local_area=28">兵庫</a></li> 
        <li><a href="salon_search_result.php?local_area=26">京都</a></li> 
        <li><a href="salon_search_result.php?local_area=25">滋賀</a></li> 
        <li><a href="salon_search_result.php?local_area=29">奈良</a></li> 
        <li><a href="salon_search_result.php?local_area=30">和歌山</a></li>
      </ul>

      <div class="local_area">中国・四国</div>
      <ul>
        <li><a href="salon_search_result.php?local_area=33">岡山</a></li> 
        <li><a href="salon_search_result.php?local_area=34">広島</a></li> 
        <li><a href="salon_search_result.php?local_area=31">鳥取</a></li> 
        <li><a href="salon_search_result.php?local_area=32">島根</a></li> 
        <li><a href="salon_search_result.php?local_area=35">山口</a></li> 
        <li><a href="salon_search_result.php?local_area=37">香川</a></li> 
        <li><a href="salon_search_result.php?local_area=36">徳島</a></li> 
        <li><a href="salon_search_result.php?local_area=38">愛媛</a></li> 
        <li><a href="salon_search_result.php?local_area=39">高知</a></li>
      </ul>

      <div class="local_area">九州・沖縄</div>
      <ul>
        <li><a href="salon_search_result.php?local_area=40">福岡</a></li> 
        <li><a href="salon_search_result.php?local_area=41">佐賀</a></li> 
        <li><a href="salon_search_result.php?local_area=42">長崎</a></li> 
        <li><a href="salon_search_result.php?local_area=43">熊本</a></li>
        <li><a href="salon_search_result.php?local_area=44">大分</a></li> 
        <li><a href="salon_search_result.php?local_area=45">宮崎</a></li> 
        <li><a href="salon_search_result.php?local_area=46">鹿児島</a></li>
        <li><a href="salon_search_result.php?local_area=47">沖縄</a></li>
      </ul>


    </div>


    <div id="basic_search">
      <form action="salon_search_result.php" method="post">
    
      <div id="ward_search">
          <table>
            <tr><td>エリア</td><td>キーワード</td><td></td></tr>
            <tr>
              <td><input type="text" name="area" value="<?php echo $_POST['area']?>" size="10"></td>
              <td><input type="text" name="keyword" value="<?php echo $_POST['keyword']?>" size="40"></td>
              <td><input type="submit" value="検索"</td>
            </tr>
          </table>
      </div>
    
      <div id="tag_search">
        <table>
        <?php
         while($result_t = $stmt_t->fetch(PDO::FETCH_ASSOC)){
            echo '          <tr><td><img src="../get_tag_img.php?id='.$result_t['Tag_ID'].'"/></td> <td>'.$result_t['Tag_name'].'</td> <td><input type ="checkbox" name="tag[]" value="'.$result_t['Tag_ID'].'" class="check_form"></td> </tr>'."\n";

          }
         
        ?>
        </table>
      </div>

      </form>
    </div>


    <div id="bottom_contents">
      <p>オススメ美容室</p>
      <div id="bottom_contents_imgbox">
      <?php
        for($i=0;$i<count($recommend_namebox);$i++){
          echo '        <div class="recommend_box">'."\n";
          echo '          <div class="recommend_img"><a href="salon_detail.php?id='.$recommend_id[$i].'"><img src="../get_salon_img.php?id='.$recommend_imgidbox[$i].'" ></a></div>'."\n";
          echo '          <div class="recommend_name">'.$recommend_namebox[$i]."</div>\n";
          echo "        </div>\n";
       }
      ?>
      </div>
    </div>

  </div>
</div>

 

<?php





require('footer.php');

?>


