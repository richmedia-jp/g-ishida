<?php

print("都道府県テーブルを設定します<br>");

$dsn = 'mysql:dbname=mydb;host=127.0.0.1';
$user = 'root';
$pswd = '211293g2';

$options = array(
//   PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
  PDO::MYSQL_ATTR_READ_DEFAULT_FILE => '/etc/my.cnf',
); 

/*
$PDO=NULL;
try {
    $PDO=new PDO($dsn,$user,$pswd,
        array(
            PDO::MYSQL_ATTR_READ_DEFAULT_FILE=>'/etc/my.cnf',
            PDO::MYSQL_ATTR_READ_DEFAULT_GROUP=>'client',
            PDO::ATTR_EMULATE_PREPARES=>'FALSE'));
    $PDO->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
} catch(PDOException $e) {
    var_dump($e->getMessage());
}
 
//$PDO->query("SET NAMES utf8");
 
 
$sth=$PDO->prepare("SHOW VARIABLES LIKE 'char%'");
$sth->execute();
while($ins=$sth->fetchObject()){
    echo $ins->Variable_name . " | " . $ins->Value . "\n";
}
*/

try{
  $dbh = new PDO($dsn, $user, $pswd, $options);
  $i=1;
  $prefbox = array('北海道','青森県','岩手県','宮城県','秋田県','山形県','福島県','茨城県','栃木県','群馬県','埼玉県','千葉県','東京都','神奈川県','新潟県','富山県','石川県','福井県','山梨県','長野県','岐阜県','静岡県','愛知県','三重県','滋賀県','京都府','大阪府','兵庫県','奈良県','和歌山県','鳥取県','島根県','岡山県','広島県','山口県','徳島県','香川県','愛媛県','高知県','福岡県','佐賀県','長崎県','熊本県','大分県','宮崎県','鹿児島県','沖縄県');

  foreach ($prefbox as $pref) {
 //   $sql = "INSERT INTO Prefecture (Prefecture_name) VALUES ('".$pref."')";
    $sql = "UPDATE Prefecture SET Prefecture_name = '".$pref."' WHERE Prefecture_ID = ".$i;

    $stmt = $dbh->query($sql);
    if(!$stmt){
      print("MISS!<br>");
//    echo $sql;
      echo "\nPDOStatement::errorInfo():\n";
      print_r($dbh->errorInfo());
    }
    else{
     //  print("SUCC<br>");
    }
    $i++;
  }

  $sql = 'SELECT * FROM Prefecture';
  $stmt = $dbh->query($sql);
  while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
    print($result['Prefecture_ID']." ");
    print($result['Prefecture_name']."<br>");
  }


}catch (PDOException $e){
    print('Error:'.$e->getMessage());
    die();
}
$dbh = null;






?>


