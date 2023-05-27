<?php
    include 'Navbar.php';
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
    <center><h1>Request</h1></center>
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
            if($row['status']=='Processing'){
            return true;
            }
        }
        return false;
    }

    function findProblem($description) {
        include "../../db_connection.php";
        $carType = $_SESSION['carType'];
        
        // Check if the description is too short or doesn't have enough words
        if (strlen($description) < 10 || str_word_count($description) < 3) {
            echo '<center><h6 style="color:red">Error: Please provide a more detailed description.</h6></center>';
            return; // Exit the function early
        }
    
        // Check if the description already exists in the database
        $sql = "SELECT p.price, p.expectedFixTime FROM problems prob
            INNER JOIN turnProblems tp ON prob.ID = tp.ProblemID
            INNER JOIN products p ON tp.ProductID = p.ID
            WHERE prob.description = ? AND prob.carType = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("ss", $description, $carType);
        if (!$stmt->execute()) {
            die("Error executing statement: " . $stmt->error);
        }
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            // Problem exists in the database, show the details
            $TotaPrice = 0;
            $TotalProcessTime = 0;
            while ($row = $result->fetch_assoc()) {
                $TotaPrice += $row['price'];
                $TotalProcessTime += $row['expectedFixTime'];
            }
            if ($TotaPrice <= 0 || $TotalProcessTime <= 0) {
                echo "<br>The price of your problem and the expected fix time is unknown.";
            } else {
                echo "The price of your problem is a maximum of $TotaPrice shekels <br> and the expected fix time is about $TotalProcessTime minutes.";
            }
            return; // Exit the function
        }
    
        // Check if there is a similar description in the database
        $sql = "SELECT ID, description FROM problems WHERE carType = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $carType);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $similarId = null;
        $maxSimilarity = 0.0;
        $suggestedDescription = "";
    
        while ($row = $result->fetch_assoc()) {
            similar_text($description, $row['description'], $percent);
            if ($percent > $maxSimilarity) {
                $maxSimilarity = $percent;
                $suggestedDescription = $row['description'];
                $similarId = $row['ID'];
            }
        }
    
        if ($maxSimilarity > 80.0 && $suggestedDescription !== $description) {
            // Show the details for the similar description
            echo "<center><h6 style='color:orange'>Did you mean: '" . $suggestedDescription . "'?</h6></center>";
            $sql = "SELECT p.price, p.expectedFixTime FROM problems prob
                INNER JOIN turnProblems tp ON prob.ID = tp.ProblemID
                INNER JOIN products p ON tp.ProductID = p.ID
                WHERE prob.ID = ? AND prob.carType = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die("Error preparing statement: " . $conn->error);
            }
            $stmt->bind_param("ss", $similarId, $carType);
            if (!$stmt->execute()) {
                die("Error executing statement: " . $stmt->error);
            }
            $result = $stmt->get_result();
    
            $TotaPrice = 0;
            $TotalProcessTime = 0;
            while ($row = $result->fetch_assoc()) {
                $TotaPrice += $row['price'];
                $TotalProcessTime += $row['expectedFixTime'];
            }
            if ($TotaPrice <= 0 || $TotalProcessTime <= 0) {
                echo "<br>The price of your problem and the expected fix time is unknown.";
            } else {
                echo "The price of your problem is a maximum of $TotaPrice shekels <br> and the expected fix time is about $TotalProcessTime minutes.";
            }
            return; // Exit the function
        }
    
        // Insert the new problem into the database
        $sql = "INSERT INTO problems (description, carType) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $description, $carType);
        if (!$stmt->execute()) {
            echo '<center><h6 style="color:red">Error: ' . $stmt->error . '</h6></center>';
        }
    
        // Show the details for the new problem
        $sql = "SELECT p.price, p.expectedFixTime, tp.quantity FROM problems prob
        INNER JOIN turnProblems tp ON prob.ID = tp.ProblemID
        INNER JOIN products p ON tp.ProductID = p.ID
        WHERE prob.description = ? AND prob.carType = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("ss", $description, $carType);
        if (!$stmt->execute()) {
            die("Error executing statement: " . $stmt->error);
        }
        $result = $stmt->get_result();
    
        $TotaPrice = 0;
        $TotalProcessTime = 0;
        while ($row = $result->fetch_assoc()) {
            $TotaPrice = ($TotaPrice +$row['price'])*$row['quantity'];
            $TotalProcessTime =  ($TotalProcessTime +$row['expectedFixTime'])*$row['quantity'];
        }
        if ($TotaPrice <= 0 || $TotalProcessTime <= 0) {
            echo "<br>The price of your problem and the expected fix time is unknown.";
        } else {
            echo "The price of your problem is a maximum of $TotaPrice shekels <br> and the expected fix time is about $TotalProcessTime minutes.";
        }
         findWorker ($TotalProcessTime);
}


function findWorker($TotalProcessTime) {
    include "../../db_connection.php";
    $carType = $_SESSION['carType'];
    $description = $_POST["subject"];
    $CID = $_SESSION['id'];
    $sql = "SELECT s.WorkerID FROM shift s JOIN workers w ON s.WorkerID = w.ID WHERE s.Histurn = 0 AND s.Date = CURDATE() AND w.competence = '$carType' LIMIT 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $workerID=$row['WorkerID'];
        $sql2="SELECT * FROM requests WHERE date = CURDATE() AND status = 'Processing' AND workerID= '$workerID'";
        $result2 = $conn->query($sql2);
        $row2 = $result2->fetch_assoc();
        if ($result2->num_rows > 0) {
            // Assuming $row['finishTime'] contains a time value in the format HH:MM:SS
            $finishTime = strtotime($row2['finishTime']); 
            // Add $TotalProcessTime (in seconds) to $finishTime
            $newFinishTime = $finishTime + $TotalProcessTime;
            $newstartTime = $finishTime + 1;
            // Convert new timestamp back to time format (HH:MM:SS)
            $newFinishTimeFormatted = date("H:i:s", $newFinishTime);
            $newstartTimeFormatted = date("H:i:s", $newstartTime); 
            echo"<br>the worker will prosecing in your request at $newstartTimeFormatted and finish at $newstartTimeFormatted";
            $sql3 = "INSERT INTO requests (clientID, workerID, status,description,Date, finishTime, startTime) 
            VALUES ('$CID', '$workerID', 'Processing','$description', CURDATE(), '$newFinishTimeFormatted', '$newstartTimeFormatted')";
            $conn->query($sql3);
            $sql4 = "UPDATE shift SET Histurn = 1 WHERE WorkerID = '$workerID' AND Date = CURDATE()";
            $conn->query($sql4);
            $sql5 = "SELECT COUNT(*) as count 
            FROM shift 
            INNER JOIN workers ON shift.WorkerID = workers.ID
            WHERE shift.hisTurn != 1 AND shift.Date = CURDATE() AND workers.competence = '$carType'";
            $result = $conn->query($sql5);
            $row = $result->fetch_assoc();
            $count = $row['count'];
            if ($count == 0) {
                $sql6 = "UPDATE shift
                INNER JOIN workers ON shift.WorkerID = workers.ID
                SET shift.hisTurn = 0
                WHERE shift.Date = CURDATE() AND workers.competence = '$carType'";
                $conn->query($sql6);
            }
            echo"<br>your request is processing by the worker $workerID ";
        }
        else{
            $now = strtotime(date("H:i:s")); 
            $newFinishTime = $now + $TotalProcessTime;
            $newstartTime = $now;
            $newFinishTimeFormatted = date("H:i:s", $newFinishTime);
            $newstartTimeFormatted = date("H:i:s", $newstartTime); 
            echo"<br>the worker will prosecing in your request now and finish at $newstartTimeFormatted";
            $sql3 = "INSERT INTO requests (clientID, workerID, status, Date, finishTime, startTime) 
            VALUES ('$CID', '$workerID', 'Processing', CURDATE(), '$newFinishTimeFormatted', '$newstartTimeFormatted')";
            $conn->query($sql3);
            $sql4 = "UPDATE shift SET Histurn = 1 WHERE WorkerID = '$workerID' AND Date = CURDATE()";
            $conn->query($sql4);
            $sql5 = "SELECT COUNT(*) as count 
            FROM shift 
            INNER JOIN workers ON shift.WorkerID = workers.ID
            WHERE shift.hisTurn != 1 AND shift.Date = CURDATE() AND workers.competence = '$carType'";
            $result = $conn->query($sql5);
            $row = $result->fetch_assoc();
            $count = $row['count'];
            if ($count == 0) {
                $sql6 = "UPDATE shift
                INNER JOIN workers ON shift.WorkerID = workers.ID
                SET shift.hisTurn = 0
                WHERE shift.Date = CURDATE() AND workers.competence = '$carType'";
                $conn->query($sql6);
            }
            echo"<br>your request is processing by the worker $workerID ";
        }
    } 
    else {
        echo "<br>All workers are busy. Please try again later.";
    }
}



?>

    
    
    