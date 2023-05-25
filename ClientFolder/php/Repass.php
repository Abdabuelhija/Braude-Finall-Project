<?php
  session_start();
?>
<html>
  <head>
    <link rel="stylesheet" href="../../ExternalStyle/FormStyle.css"/>
    <link rel="stylesheet" href="../../ExternalStyle/GeneralStyle.css"/>
  <title>Contact</title>
  </head>
  <body>
    <h6 name="Warn"></h6>
    <div class="Lform">
      <form method="post" > 
        <h6>you should reset your password cause it was typed wrong 3 times</h6>
        <h4 >Reset your password </h3>
        <input type="text"  name="Repass" required placeholder="Type your subject">
        <input type="submit" value="Submit" name="Repasssubmit">
      </form>
    </div>
  </body>
</html>
<?php
  include '../../db_connection.php';
$flag = 0;
if (isset($_POST['Repasssubmit'])) {
  $Repass = $_POST['Repass'];
  $id = $_SESSION['id'];
  $Search = mysqli_query($conn, "SELECT * FROM customers WHERE ID='$id'");
  $row = mysqli_fetch_array($Search);
  if ($Repass == $row['pass'] or $Repass == $row['Pass1'] or $Repass == $row['Pass2'] or $Repass == $row['Pass3']) {
    echo '<center>', '<h6 style="color:red">', "this password is from the old passwords";
    $flag = 1;
    return;
  }
  if ($flag == 0) {
    $sql = "UPDATE customers SET pass='$Repass' WHERE ID='$id' ";
    if ($conn->query($sql) === TRUE) {
      echo"<script>alert('password changed');</script>";
      $sql = "UPDATE customers SET isBlocked='No'";
      if ($conn->query($sql) === TRUE) {
        header( "refresh:2;Home.php" );
      }
    }
  }
}
?> 

