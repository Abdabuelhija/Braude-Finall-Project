<?php 
include 'Navbar.php';
include "../../db_connection.php";
?>


<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="../style/AdminStyle.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <style>
        body {
            background: linear-gradient(to right, #232526, #414345);
            color: white;
            font-size: 18px;
        }

        .info {
            background-color: rgba(0,0,0,0.6);
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 15px 5px rgba(0,0,0,0.4);
            transition: all 0.3s ease;
        }

        .info:hover {
            transform: scale(1.03);
        }

        h5 {
            font-size: 20px;
        }

        .fa {
            margin-right: 10px;
        }
        text-sm.mb-0.text-capitalize{

        }
    </style>
    <title>Home</title>
</head>

<body>
    <div style="width:100%; padding-top:60px;">
        <div class="container-fluid py-4 Today">
            <?php 
$data = [
    ["Today", "", ""],
    ["Workers in the shift", 'fa-briefcase', "SELECT shift.*, workers.firstname, workers.lastname FROM shift INNER JOIN workers ON shift.WorkerID = workers.ID WHERE shift.Date = CURDATE()"],
    ["Request in Processing ", 'fa-briefcase', 'SELECT * FROM requests WHERE status="Processing" AND Date = CURDATE()'],
    ["The Next Audi Request Go to ", 'fa-briefcase', 'SELECT shift.*, workers.firstname, workers.lastname FROM shift INNER JOIN workers ON shift.WorkerID = workers.ID WHERE workers.competence="Audi" AND shift.Date = CURDATE()'],
    ["The Next BMW Request Go to ", 'fa-briefcase', 'SELECT shift.*, workers.firstname, workers.lastname FROM shift INNER JOIN workers ON shift.WorkerID = workers.ID WHERE workers.competence="BMW" AND shift.Date = CURDATE()'],
    ["The Next Skoda Request Go to ", 'fa-briefcase', 'SELECT shift.*, workers.firstname, workers.lastname FROM shift INNER JOIN workers ON shift.WorkerID = workers.ID WHERE workers.competence="Skoda" AND shift.Date = CURDATE()'],
    ["The Next Mercedes Request Go to ", 'fa-briefcase', 'SELECT shift.*, workers.firstname, workers.lastname FROM shift INNER JOIN workers ON shift.WorkerID = workers.ID WHERE workers.competence="Mercedes" AND shift.Date = CURDATE()'],
    ["The Next Volkswagen Request Go to ", 'fa-briefcase', 'SELECT shift.*, workers.firstname, workers.lastname FROM shift INNER JOIN workers ON shift.WorkerID = workers.ID WHERE workers.competence="Volkswagen" AND shift.Date = CURDATE()'],
];



            foreach (array_chunk($data, 4) as $row) {
                echo "<div class='row'>";
                foreach ($row as $item) {
                    echo "<div class='col-xl-3 col-sm-6 mb-xl-0 mb-4 info' >";
                    echo "<div class='card-header p-3 pt-2'>";
                    echo "<h5 class='text-sm mb-0 text-capitalize'>" . $item[0] . "</h5>";
                    echo "<h5 class='mb-0'>";
                    if ($item[2] != "") {
                        $result = $conn->query($item[2]);
                        echo "<i class='fa " . $item[1] . "' aria-hidden='true'></i>", $result->num_rows;
                    }
                    echo "</h5>";
                    echo "</div>";
                    echo "</div>";
                }
                echo "</div>";
            }
            ?>
        </div>
        <div class="container-fluid py-4 General">
            <?php 
            $general = [
                ["General", '', ''],
                ["Total Carage balance", 'fa-ils', 'SELECT * FROM revenues'],
                ["Workers Quantity", 'fa-briefcase', 'SELECT * FROM workers'],
                ["Customers Quantity", 'fa-user', 'SELECT * FROM customers'],
                ["Requests Quantity", 'fa-wrench', 'SELECT * FROM requests'],
                ["Paid receipts Quantity", 'fa-shopping-cart', 'SELECT * FROM receipt WHERE status="Paid"'],
                ["Un Paid receipts Quantity", 'fa-gavel', 'SELECT * FROM receipt WHERE status="Not Paid"'],
                ["Messages Quantity", 'fa-comments', 'SELECT * FROM messages'],
                ["Products Quantity", 'fa-tag', 'SELECT * FROM products']
            ];

            foreach (array_chunk($general, 3) as $row) {
                echo "<div class='row'>";
                foreach ($row as $item) {
                    echo "<div class='col-xl-4 col-sm-6 mb-xl-0 mb-4 info'>";
                    echo "<div class='card-header p-3 pt-2'>";
                    echo "<h5 class='text-sm mb-0 text-capitalize'>" . $item[0] . "</h5>";
                    echo "<h5 class='mb-0'>";
                    if ($item[2] != "") {
                        $result = $conn->query($item[2]);
                        echo "<i class='fa " . $item[1] . "' aria-hidden='true'></i>", $result->num_rows;
                    }
                    echo "</h5>";
                    echo "</div>";
                    echo "</div>";
                }
                echo "</div>";
            }
            ?>
        </div>
    </div>
</body>

</html>
