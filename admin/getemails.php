<?php
$q = $_GET['q'];
require '../db_conn.php';

$sql="SELECT * FROM customer WHERE email LIKE '%".$q."%'";
$result = mysqli_query($conn, $sql);

while($row = mysqli_fetch_array($result)) {
  echo '<a class="contactItem list-group-item list-group-item-action p-2"
  data-name="'. $row['email'].'"><img src="../uploads/customer_img/'.$row['imgUrl'].'" style="border-radius: 50%; width: 40px; height: 40px"/>&nbsp;&nbsp;'
  . $row['email'].'</a>';
}

mysqli_close($conn);
?>
