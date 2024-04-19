<?php
        
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['upload'])) {
                if (isset($_FILES["csvFile"])) {
                    
                    $servername = "localhost";
                    $username = "root"; 
                    $password = ""; 

                    
                    $conn = new mysqli($servername, $username, $password);

                    
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $createDatabaseSQL = "CREATE DATABASE IF NOT EXISTS test2";
                    if ($conn->query($createDatabaseSQL) === TRUE) {
                        echo "";
                    } else {
                        echo "Error creating database: " . $conn->error;
                        exit; 
                    }

                    $conn->close();

                    
                    $conn = new mysqli($servername, $username, $password, "test2");

                
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

     
        $sql = "CREATE TABLE IF NOT EXISTS csv_import (
                    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(30) NOT NULL,
                    surname VARCHAR(30) NOT NULL,
                    initials VARCHAR(5) NOT NULL,
                    age INT(3) NOT NULL,
                    date_of_birth VARCHAR(10) NOT NULL
                )";

        if ($conn->query($sql) === FALSE) {
            echo "Error creating table: " . $conn->error;
        }

        
        $csvFile = fopen($_FILES["csvFile"]["tmp_name"], "r");

        $conn->begin_transaction();

        $count = 0;
        $batchSize = 100; 
        $batchValues = array(); // Array to store batch values
        while (($data = fgetcsv($csvFile)) !== false) {
            if ($count > 0) { // Skip header row
              
                $name = $data[1];
                $surname = $data[2];
                $initials = $data[3];
                $age = intval($data[4]);
                $dob = DateTime::createFromFormat('d/m/Y', $data[5]); 

                if ($dob !== false) {
                    $dobFormatted = $dob->format('d/m/Y'); // Format date as dd/mm/yyyy
                    
                    $batchValues[] = "('$name', '$surname', '$initials', $age, '$dobFormatted')";
                } else {
                    echo "Invalid date format for record: " . implode(', ', $data) . "<br>";
                }
            }
            $count++;

            // Check if batch size is reached or end of file is reached
            if (count($batchValues) == $batchSize || feof($csvFile)) {
                
                $sql = "INSERT INTO csv_import (name, surname, initials, age, date_of_birth) VALUES " . implode(',', $batchValues);
                if ($conn->query($sql) === FALSE) {
                    echo "Error inserting records: " . $conn->error;
                }
                // Clear batch values array
                $batchValues = array();
            }
        }

       
        $conn->commit();

        
        fclose($csvFile);

        
        $conn->close();

        echo "Number of records inserted: " . ($count - 1); 
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animated GIF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #FFFFFF;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="UX Success GIF.gif" alt="" width="250">
    </div>

   
</body>
</html>
