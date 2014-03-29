<?php
  require('header.php');
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
        <table>
          <tr><td>エリア</td><td>キーワード</td><td></td></tr>
          <tr>
            <td><input type="text" name="area" value="<?php echo $_POST['area']?>" size="10"></td>
            <td><input type="text" name="keyword" value="<?php echo $_POST['keyword']?>" size="40"></td>
            <td><input type="submit" value="検索"</td>
          </tr>
        </table>
      </form>
    </div>

    <div id="bottom_contents">
      <p>オススメ美容室</p>
      <div id="bottom_contents_imgbox">
      <!-- ここにはおすすめの美容室の画像リンクをならべる  -->
      </div>
    </div>

  </div>
</div>

 

<?php





require('footer.php');

?>


