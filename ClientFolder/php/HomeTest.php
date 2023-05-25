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
  <center><h1>זימון תור לבדיקה</h1></center>
  <form method="post" >
    <input type="text" name="subject" placeholder="Type your problem">
    <input type="submit" value="Submit" name="submit">
  </form>
</div>


    </body>
</html>

<?php
    

    if (isset($_POST['submit'])){
        if (isAlreadyHaveTurn() == false){
            $description = $_POST["subject"];
           findProblem($description);
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
        include "../../db_connection.php";
        $carType=$_SESSION['carType'];
        // First, check if the description already exists
        $checkSql = "SELECT ID FROM problems WHERE carType=' $carType' and description = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("s", $description);

        if (!$checkStmt->execute()) {
            die("Error executing statement: " . $checkStmt->error);
        }

        $checkResult = $checkStmt->get_result();

        // If the description does not exist in the database, insert it
        if ($checkResult->num_rows == 0) {
            $insertSql = "INSERT INTO problems (description) VALUES (?)";
            $insertStmt = $conn->prepare($insertSql);
            $insertStmt->bind_param("s", $description);

            if (!$insertStmt->execute()) {
                die("Error executing statement: " . $insertStmt->error);
            }
        }
        $sql = "SELECT p.price, p.expectedFixTime FROM problems prob
                INNER JOIN turnProblems tp ON prob.ID = tp.ProblemID
                INNER JOIN products p ON tp.ProductID = p.ID
                WHERE MATCH(prob.description) AGAINST(? IN NATURAL LANGUAGE MODE) and carType ='$carType'";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("s", $description);

        if (!$stmt->execute()) {
            die("Error executing statement: " . $stmt->error);
        }
        $result = $stmt->get_result();
        $TotaPrice=0;
        $TotalProcessTime=0;
        while ($row = $result->fetch_assoc()) {
            $TotaPrice+=$row['price'] ;
            $TotalProcessTime+=$row['expectedFixTime'] ;
        }
        if( $TotaPrice<=0|| $TotalProcessTime){
            echo"the price of your problem  and the expected fix time is unknown";
        }
        else{
            echo"the price of your problem is maximum $TotaPrice  shekels <br> and the expected fix time is about $TotalProcessTime minuts";
        }
        findWorker ($TotalProcessTime);
    }

    function findWorker ($TotalProcessTime){
        include "../../db_connection.php";
        $carType=$_SESSION['carType'];
        $CID=$_SESSION['id'];
        // select * from shift where Histurn=0 limit 1 top 
        // if (worker not Busy exist ){
        // echo("we found worker for you would you like to continue ? ");
        // if (yes){
            // $workerID=$row['workerID'];
            // $endTime=end time of the request that he process +$TotalProcessTime
            // $starrTime=end time of the request that he process +1 ; (1 minute)
            // insert the request to the request table values (clientID = $CID,workerID=$$workerID,status=Processing ,endTime =$$endTime startTime=$starrTime)
            // update in the shift table hisTurn=1;
            // if(all the workers.hisTurn=1 in the shift ){
            //     update the all his turn to all the workers =0 ;
            // }
        // }
        // if(no){
        //     echo ("turn canceled");
        //     return ;
        // }
        // }
    }
    
?>

