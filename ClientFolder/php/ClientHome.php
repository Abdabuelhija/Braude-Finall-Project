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
    <style>
        .card {
            text-align: center;
            border-radius: 2px;
            width: 18rem;
            background-color:aliceblue;
            margin-top: 20px;
        }
        .card-body{
            display: grid;
            grid-template-columns: auto auto  ;
        }
        .products-card-body{
            display: grid;
            grid-template-columns: auto auto;
        }
        .card:hover{
            background-color:lightblue;
            
        }
    </style>
    <body>
    <?php
            include "../../db_connection.php";
            $CID=$_SESSION['id'];
            $expectedFixTime = 0;
            $timeOfTheProductsThatConformed = 0;
            $sql = "SELECT * FROM requests WHERE clientID='$CID' AND status='processing'";
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()) {
                $requestID = $row['ID'];
                echo"
                <center>
                <div class='card' style='width:500px;'>
                ";
                $WID=$row['workerID'];
                $sql = "SELECT * FROM  workers WHERE ID = '$WID' ";
                $result = $conn->query($sql);
                $row2 = $result->fetch_assoc();
                echo"
                your request now in processing by the worker :", $row2 ['firstname'],$row2 ['lastname'],"<hr>
                    <div class='card-body'>",
                        "<b>Turn number : </b>",$row['ID'],
                        "<b>Time: </b>",$row['Time'],
                        "<b>status </b> ",$row['status'],
                        "</div>
                        <hr>
                        <div>
                        products that you need to process :<br>";
                        $Search = mysqli_query($conn, "SELECT* FROM turnproducts WHERE RequestID='$requestID'");
                        while ($row = mysqli_fetch_array($Search)) {
                            $TurnQuantity = $row['quantity'];
                            $PID = $row['ProductID'];
                                    $productSearch = mysqli_query($conn, "SELECT* FROM products WHERE ID='$PID'");
                                    $row2 = mysqli_fetch_array($productSearch);
                                    echo $row2['name'];
                                    echo
                                    " : 
                                Quantity :  ", $row['quantity'];
                                if($row['isconfirmed']==1){
                                    $productSearch = mysqli_query($conn, "SELECT* FROM products WHERE ID='$PID'");
                                    $row = mysqli_fetch_array($productSearch);
                                    $timeOfTheProductsThatConformed += $row['expectedFixTime']*$TurnQuantity;
                                    echo
                                    "<input type='submit' value='you confirmed this product' style='background-color:green'><br>";
                                }
                                else
                                echo
                                "<form method='post'><input type='submit' name='$PID' value='confirm'></form><br>";
                                if(isset($_POST[$PID])){
                                    $sql = "UPDATE turnproducts SET isconfirmed=1 WHERE ProductID='$PID' AND RequestID='$requestID'";
                                    if ($conn->query($sql) === FALSE) {
                                    echo "<script>alert('sql update problem ');</script>";
                                    }
            
                                }
                            $productSearch = mysqli_query($conn, "SELECT* FROM products WHERE ID='$PID'");
                            $row = mysqli_fetch_array($productSearch);
                            $expectedFixTime += $row['expectedFixTime']*$TurnQuantity;
                        }
                    echo "</div>";
                    // $sql = "UPDATE requests SET expectedFixTime=...";
                    // if ($conn->query($sql) === FALSE) {
                    // echo "<script>alert('sql update problem ');</script>";
                    // }
                    echo
                    " 
                    <b>the Expecting time to fix your car of all the products : </b>","$expectedFixTime"," minutes
                    <b>the Expecting time of the products that you confirmed : </b>","$timeOfTheProductsThatConformed"," minutes
                    </div>
                    </center>";
            }
        ?>

        <div class="Lform" style="margin-top: 70px;">
            <center><h1>זימון תור לבדיקה </h1></center>
            <form method='post'>
            <input type="text" name="subject" placeholder="type your problem">
                <input type="submit" value="Submit" name="submit" >
            </form>
        </div>

</html>
<?php
    if (isset($_POST['submit'])){
        if (isAlreadyHaveTurn() == false){
            if(makeRequest()){
                echo"<script>alert('Turn created');</script>";
            }
            else{
                echo"<script>alert('the all turn are full try later');</script>"; 
            }
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

    //hasab al mashmeret 
    function makeRequest(){
        include "../../db_connection.php";
        $Search = mysqli_query($conn,"SELECT * FROM shift");
        while($row = mysqli_fetch_array($Search)){
            if($row['competence']==$_SESSION['carType']){
                $W_ID=$row['ID'];
                $ifWorkerBusy = 0;
                $Search = mysqli_query($conn,"SELECT * FROM requests");
                // while($row = mysqli_fetch_array($Search)){
                //     if($row['workerID']==$W_ID and $row['Time']==$Time and $row['status']=='processing'){
                //         $ifWorkerBusy=1;
                //     }
                // }
                if($ifWorkerBusy==0){
                    $subject = $_POST['subject'];
                    $sql = "INSERT INTO requests (clientID,workerID,status,Time,subject)
                    VALUES ('$CID','$W_ID','processing','$Time','$subject')";
                    if ($conn->query($sql) === TRUE) {
                    echo"<script type='text/javascript'>alert('the request now in the worker page');</script>";
                    } 
                    else {
                    echo '<center>','<h6 style="color:red">',"Error: " . $sql . "<br>" . $conn->error;
                    }              
                    return true;
                } 
            }
        }
        return false;
    }

?>

