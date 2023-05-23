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
        <div class="Lform" style="margin-top: 70px;">
            <center><h1>Request turn </h1></center>
            <form method='post'>
            <input type="text" name="subject" placeholder="type your problem">
                <input type="submit" value="Submit" name="submit" >
            </form>
        </div>
    </body>
</html>

<?php
    if (isset($_POST['submit'])) {
        $problem_description = $_POST['subject'];
        $keywords = ['brakes', 'engine', 'battery', 'tire', 'oil'];
        foreach ($keywords as $keyword) {
            if (strpos($problem_description, $keyword) !== false) {
                echo "The problem might be related to: " . $keyword;
                return;
            }
        }
        echo"sorry we cant find your problem .";
    }
?>

<?php

    include 'db_connection.php';
    // Check if form was submitted
    if (isset($_POST['submit'])) {
        $description = $_POST['subject'];

        // Insert the description into the database
        $stmt = $pdo->prepare("INSERT INTO descriptions (description) VALUES (:description)");
        $stmt->execute(['description' => $description]);

        // Analyze all descriptions to find most common words
        $stmt = $pdo->query("SELECT description FROM descriptions");
        $all_descriptions = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $word_counts = array();
        foreach ($all_descriptions as $desc) {
            $words = explode(' ', $desc);
            foreach ($words as $word) {
                if (!isset($word_counts[$word])) {
                    $word_counts[$word] = 0;
                }
                $word_counts[$word]++;
            }
        }

        // Find the words that appear more than a certain threshold
        $threshold = 3;
        $common_words = array_filter($word_counts, function ($count) use ($threshold) {
            return $count > $threshold;
        });

        print_r($common_words);
    }
?>




