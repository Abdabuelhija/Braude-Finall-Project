<?php
  include 'clientNav.php';
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
    <div class="Lform" style="margin-top: 150px;">
      <form method="post" > 
        <h4 >What we can help?</h3>
        <input type="text"  name="subject" placeholder="Type your subject" style="padding: 7%;" required>
        <center><input type="submit" value="Submit" name="submitContact"></center>
      </form>
    </div>
  </body>
</html>
<?php
  include '../../db_connection.php';
  if (isset($_POST['submitContact'])){
    $subject = $_POST['subject'];
    $email =$_SESSION['email'];
    $sql = "INSERT INTO messages (subject,email)
    VALUES ('$subject','$email')";
    if ($conn->query($sql) === TRUE) {
      echo '<center>','<h6 style="color:red">',"New record created successfully";
    } else {
      echo '<center>','<h6 style="color:red">',"Error: " . $sql . "<br>" . $conn->error;
    }
  }
?> 

