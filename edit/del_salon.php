<?php
  require('header.php');
 ?>

<?php
  
$dsn = 'mysql:dbname=mydb;host=127.0.0.1';
$user = 'root';
$pswd = '211293g2';
$options = array(
  PDO::MYSQL_ATTR_READ_DEFAULT_FILE => '/etc/my.cnf',
); 

try{
  $dbh = new PDO($dsn, $user, $pswd, $options);
  $id = intval($_GET['id']);
  $sql = "SELECT * FROM Salon WHERE Salon_ID =".$id;
  $stmt = $dbh->query($sql);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

}catch (PDOException $e){
  print('Error:'.$e->getMessage());
  die();
}
$dbh = null;

?>
 

<div id="container">
  <div id="input_info">
    <p class="edit_caption">美容室 削除</p>
      <table>
        <tr>
          <td>ID : </td> <td><?php echo $result['Salon_ID']; ?></td>
        </tr>
      </table>
      <table>
        <tr>
          <td>美容室名 : </td> <td><?php echo $result['Salon_name'];?></td>
        </tr>
      </table>
      <br>
      <table>
         <tr><td>本当に削除しますか？</td></tr>
      </table>

    <div id="del_btn"><a href="sql_del_salon.php?id=<?php echo $result['Salon_ID'];?>">削除</a>
   </div>
</div>


<?php
  require('footer.php');         
?>

