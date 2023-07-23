<?php
include 'Navbar.php';
date_default_timezone_set("Israel");
session_start();
?>
<html>

<head>
    <link rel="stylesheet" href="../style/Request.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../../ExternalStyle/FormStyle.css" />
    <link rel="stylesheet" href="../style/CustomerStyle.css" />
    <title>Home page</title>
</head>

<body>
    <div class="Lform" style="margin-top: 70px;">
        <center>
            <h1>Request</h1>
        </center>
        <form method="post">
            <input type="text" name="subject" placeholder="Type your problem">
            <input type="submit" value="Submit" name="submit">
        </form>
    </div>

</body>

</html>

<?php
if (isset($_POST['submit'])) {
    if (isAlreadyHaveTurn() == false) {
        $description = $_POST["subject"];
        findProblem($description);
    } else {
        echo "<script>alert('You already sent request');</script>";
    }
}

function isAlreadyHaveTurn()
{
    include "../../db_connection.php";
    $CID = $_SESSION['id'];
    $sql = "SELECT * FROM requests WHERE clientID='$CID'";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        if ($row['status'] == 'Processing') {
            return true;
        }
    }
    return false;
}
function findProblem($description)
{
    include "../../db_connection.php";
    $carType = $_SESSION['carType'];
    $ProblemID = 0;

    if (strlen($description) < 10 || str_word_count($description) < 3) {
        echo '<center><div class="message-output">Error: Please provide a more detailed description.</div></center>';
        return; 
    }

    // Check if the description already exists in the database
    $sql = "SELECT p.price, p.expectedFixTime, prob.ID as ProblemID FROM problems prob
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
        echo "<center><h6 style='color:orange'>Did you mean: '" . $suggestedDescription . "'?</h6></center>";
        // Retrieve the similar problem's details from the database
        $sql = "SELECT p.price, p.expectedFixTime, prob.ID as ProblemID FROM problems prob
        INNER JOIN turnProblems tp ON prob.ID = tp.ProblemID
        INNER JOIN products p ON tp.ProductID = p.ID
        WHERE prob.ID = ? AND prob.carType = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("is", $similarId, $carType); // Note the change from "ss" to "is"
        if (!$stmt->execute()) {
            die("Error executing statement: " . $stmt->error);
        }
        $result = $stmt->get_result();

        $TotaPrice = 0;
        $TotalProcessTime = 0;
        while ($row = $result->fetch_assoc()) {
            $TotaPrice += $row['price'];
            $TotalProcessTime += $row['expectedFixTime'];
            $ProblemID = $row['ProblemID'];
        }
        if ($TotaPrice <= 0 || $TotalProcessTime <= 0) {
            echo "<div class='message-output'><p class='result-msg__text'>The price of your problem and the expected fix time is unknown.</p></div>";
        } else {
            echo "<div class='message-output'><p class='result-msg__title'>The price of your problem is a maximum of $TotaPrice shekels </p><p class='result-msg__subtitle'> and the expected fix time is about $TotalProcessTime minutes.</p></div>";
        }
        $ProblemID = $similarId; // Set the ProblemID to similarId
        findWorker($TotalProcessTime, $ProblemID);

        return; // Exit the function
    }


    // Insert the new problem into the database if it doesn't already exist
    $sql = "INSERT INTO problems (description, carType) SELECT ?, ? FROM DUAL WHERE NOT EXISTS (SELECT * FROM problems WHERE description = ? AND carType = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $description, $carType, $description, $carType);
    if (!$stmt->execute()) {
        echo '<center><h6 style="color:red">Error: ' . $stmt->error . '</h6></center>';
    }

    // Retrieve the ProblemID
    $sql = "SELECT ID FROM problems WHERE description = ? AND carType = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $description, $carType);
    if (!$stmt->execute()) {
        die("Error executing statement: " . $stmt->error);
    }
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $ProblemID = $row['ID'];
    }

    // Show the details for the new problem
    $sql = "SELECT p.price, p.expectedFixTime, tp.quantity, prob.ID as ProblemID FROM problems prob
                INNER JOIN turnProblems tp ON prob.ID = tp.ProblemID
                INNER JOIN products p ON tp.ProductID = p.ID
                WHERE prob.ID = ? AND prob.carType = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("is", $ProblemID, $carType); // Note the change from "ss" to "is"
    if (!$stmt->execute()) {
        die("Error executing statement: " . $stmt->error);
    }
    $result = $stmt->get_result();

    $TotaPrice = 0;
    $TotalProcessTime = 0;

    while ($row = $result->fetch_assoc()) {
        $TotaPrice += ($row['price'] * $row['quantity']);
        $TotalProcessTime += ($row['expectedFixTime'] * $row['quantity']);
        $ProblemID = $row['ProblemID'];
    }

    if ($TotaPrice <= 0 || $TotalProcessTime <= 0) {
        echo "<div class='message-output'><p class='result-msg__text'>The price of your problem and the expected fix time is unknown.</p></div>";
    } else {
        echo "<div class='message-output'><p class='result-msg__title'>The price of your problem is a maximum of $TotaPrice shekels </p><p class='result-msg__subtitle'> and the expected fix time is about $TotalProcessTime minutes.</p></div>";
    }


    findWorker($TotalProcessTime, $ProblemID);
}




function findWorker($TotalProcessTime, $ProblemID)
{
    include "../../db_connection.php";
    $carType = $_SESSION['carType'];
    $description = $_POST["subject"];
    $CID = $_SESSION['id'];
    $sql = "SELECT s.WorkerID, w.firstname, w.lastname FROM shift s JOIN workers w ON s.WorkerID = w.ID WHERE s.Histurn = 0 AND s.Date = CURDATE() AND w.competence = '$carType' LIMIT 1";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $workerID = $row['WorkerID'];
        $worker_Fname=$row['firstname'];
        $worker_Lname=$row['lastname'];
        $sql2 = "SELECT * FROM requests WHERE date = CURDATE() AND status = 'Processing' AND workerID= '$workerID'";
        $result2 = $conn->query($sql2);
        $row2 = $result2->fetch_assoc();
        if ($result2->num_rows > 0) {
            $finishTime = strtotime($row2['finishTime']);
            $newFinishTime = $finishTime + $TotalProcessTime;
            $newstartTime = $finishTime + 1;
            $newFinishTimeFormatted = date("H:i:s", $newFinishTime);
            $newstartTimeFormatted = date("H:i:s", $newstartTime);

            echo "<div class='timestamp'>";
            echo "<p>The worker will start processing your request at <span class='highlight'>$newstartTimeFormatted</span> and finish at <span class='highlight'>$newFinishTimeFormatted</span></p>";

            $sql3 = "INSERT INTO requests (clientID, workerID, status, description, Date, finishTime, startTime, ProblemID) 
                VALUES ('$CID', '$workerID', 'Processing', '$description', CURDATE(), '$newFinishTimeFormatted', '$newstartTimeFormatted', '$ProblemID')";
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
            echo "<p>Your request is being processed by worker <span class='highlight'>$worker_Fname $worker_Lname</span>.</p>";
            echo "</div>";
        } else {
            $now = strtotime(date("H:i:s"));
            $newFinishTime = $now + $TotalProcessTime;
            $newstartTime = $now;
            $newFinishTimeFormatted = date("H:i:s", $newFinishTime);
            $newstartTimeFormatted = date("H:i:s", $newstartTime);

            echo "<div class='timestamp'>";
            echo "<p>The worker will start processing your request now and finish at <span class='highlight'>$newFinishTimeFormatted</span></p>";

            $sql3 = "INSERT INTO requests (clientID, workerID, status, Date, finishTime, startTime, ProblemID) 
                VALUES ('$CID', '$workerID', 'Processing', CURDATE(), '$newFinishTimeFormatted', '$newstartTimeFormatted', '$ProblemID')";
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
            echo "<p>Your request is being processed by worker <span class='highlight'>$worker_Fname $worker_Lname</span>.</p>";
            echo "</div>";
        }
    } else {
        echo "<div class='message-output'><p class='result-msg__text'>All workers are busy, Please try again later </p></div>";
    }
}