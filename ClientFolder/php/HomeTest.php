<?php
    include 'ClientNav.php';
    date_default_timezone_set("Israel");
    session_start();
?>
<html>  
    <head>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="../../ExternalStyle/FormStyle.css" /> 
        <title>Home page</title>
    </head>
    <body>
        <div class="Lform" style="margin-top: 70px;">
            <center><h1>זימון תור לבדיקה </h1></center>
            <form method='post'>
                <input type="text" name="subject" placeholder="type your problem">
                <input type="submit" value="Submit" name="submit" >
            </form>
        </div>
    </body>
</html>
<?php

    if (isset($_POST['submit'])){
        if (isAlreadyHaveTurn() == false){
            $description = $_POST["subject"];
            $ProblemID=findProblem($description);

        }
        else{
        echo "<script>alert('You already sent request');</script>";
        }
    }   

 
    function isAlreadyHaveTurn(){
        include "../../db_connection.php";
        $CID=$_SESSION['id'];
        $sql = "SELECT * FROM requests WHERE clientID='$CID'";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            if($row['status']=='processing'){
            return true;
            }
        }
        return false;
    }


    function findProblem($description) {
        include '../../db_connection.php';
                echo "<script>alert('good ');</script>";
    }


?>

