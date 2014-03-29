<?php

print("定休日テーブルを設定します<br>");

$dsn = 'mysql:dbname=mydb;host=127.0.0.1';
$user = 'root';
$pswd = '211293g2';
$options = array(
  PDO::MYSQL_ATTR_READ_DEFAULT_FILE => '/etc/my.cnf',
); 

try{
  $dbh = new PDO($dsn, $user, $pswd, $options);

  $i=0;
  while(true){
  
    $bi = decbin($i);
    $bilen = strlen($bi);
    if ($bilen > 7){break;}

    for ($n = 7-$bilen; $n > 0; $n--) {
      $bi = '0'.$bi;
    }    
    $hol = $bi;//７桁の２進数
 
//    $sql = "INSERT INTO Fixed_holiday (Holiday_pattern) VALUES ('".$hol."')";
    $ii = $i+1;//IDは0始まりではないため
    $sql = "UPDATE Fixed_holiday SET Holiday_pattern = '".$hol."' WHERE Fixed_holiday_ID = ".$ii;

    $stmt = $dbh->query($sql);
    if(!$stmt){
      print("MISS!<br>");
      print_r($dbh->errorInfo());
    }
    else{
//       print("SUCC<br>");
    }
    $i++;
 
  }

  $sql = 'SELECT * FROM Fixed_holiday';
  $stmt = $dbh->query($sql);
  while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
    print($result['Fixed_holiday_ID']." ");
    print($result['Holiday_pattern']."<br>");
  }


}catch (PDOException $e){
    print('Error:'.$e->getMessage());
    die();
}
$dbh = null;






?>



