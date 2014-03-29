<?php
  require('header.php');
?>
  <div id="container">
    <div id="contents">
      <div id="salon_info">
        <?php
	 // $id = 20;
          $id = $_GET['id'];//$idはSalon_IDでmake_salon_detail.phpで参照される
	  if ($id==""){//登録されているIDにない数字が入るケースも本当は処理しないといけない
	    print("This salon page is not found<BR>\n");
	  } else {
	    include('make_salon_detail.php');//id='.$id);
          }
        ?>
      </div>
    </div>

    <div id="right_menu">
      <p>何かしらのコンテンツ</p>
    </div>
  </div>

<?php
  require('footer.php');
?>



