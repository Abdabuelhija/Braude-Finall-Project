<?php
  session_start();
  $price = $_SESSION['price'];
?>
<html>
  <head>
  <link rel="stylesheet" href="../../ExternalStyle/GeneralStyle.css" />
    <link rel="stylesheet" href="../../ExternalStyle/FormStyle.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>payment</title>
  </head>
  <style>
      .Grid{
    display: grid;
    grid-template-columns:auto auto; 
    column-gap: 5px;
    
    input[type=text], select {
    width: 200px;
    }
  </style>
  <body>
    <form method="post" class="Lform" style="margin-top: 50px;">
      <h2>payment</h2>
      <b> Total price : </b><i class="fa fa-ils" aria-hidden="true"></i><?php echo $price?><br>
      <small>fill the information of the credit card <samll>
      <div class="Grid">
      <input type="text" name='ID'placeholder="ID" required>
      <input type="text" name='Firstname' placeholder="Firstname"required>
      <input type="text" name='Lastname' placeholder="Lastname"required>
      <input type="text" name='CardNum' placeholder="Card number"required>
      <input type="text" name='exp' placeholder="exp" required>
      <input type="text" name='CVV' placeholder="Sercuirty number"required>
      </div>
      <center><img src="../../Imgs/Payment.png" width="300px"></center>
      <input type="submit" value="Submit" name="subPayment">
    </form>
  </body>
</html>
<?php
  $REC_ID=$_SESSION['REC_ID'];
  if (isset($_POST["subPayment"]) ){
  if (checkCard() == true) {
    include "../../db_connection.php";
    if ($price > 0) {
      $sql = "UPDATE receipt SET status='Paid' WHERE ID='$REC_ID' ";
      if ($conn->query($sql) === TRUE) {
      }
      $Search = mysqli_query($conn, "SELECT * FROM revenues");
      $row = mysqli_fetch_array($Search);
      $PresentRevenue = $row['allRevenue'];
      $newRevenue = $PresentRevenue + $price;
      $sql = "UPDATE revenues SET allRevenue='$newRevenue' WHERE ID='0' ";
      if ($conn->query($sql) === TRUE) {
        echo "<script> alert('the price paid');</script>";
        header("Refresh:1;url=Home.php");
      } else {
        echo "error in sql query";
      }

    } else {
      echo "You don't have to pay anything.";
    }
  }
  else{
    echo "<script type='text/javascript'>alert('the information not good');</script>";
    }
  }


  function checkCard(){
    include "../../db_connection.php";
    $sql = "SELECT * FROM api";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        if ($row["userID"] == $_POST['ID'] and $row["firstname"] == $_POST['Firstname'] and $row["lastname"] == $_POST['Lastname'] and $row["CardNum"] == $_POST['CardNum'] and $row["CVV"] == $_POST['CVV'] and $row["Expiration"] == $_POST['exp']){
          return true;
        }
      }
    }
    else{
        return false;
    }
    return false;
  }
?>