<?php
  include 'AdminNav.php';
  session_start();
?>
<html>
  <head>
  <link rel="stylesheet" href="../../ExternalStyle/GeneralStyle.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

  </head>
<style>
  body{
        background-color: #e3e1ff;
      }

</style>
  <body>
  <table class="table table-hover">
    <thead >
      <tr>
        <th scope="col">Email</th>
        <th scope="col">Message</th>
      </tr>
    </thead>
    <tbody>
    <?php
      include "../../db_connection.php";
      $sql = "SELECT * FROM messages ORDER BY ID DESC";
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          $subject = $row['subject'];
          $email = $row['email'];
            echo "
            <tr>
            <td>$email</td>
            <td>$subject</td>
            </tr>
            ";
        }
      }   
    ?>
      </tbody>
    </table>
  </body>
</html>




