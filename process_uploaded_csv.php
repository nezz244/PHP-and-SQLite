<?php

// Check if file is uploaded
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['upload'])) {
    if (isset($_FILES["csvFile"])) {
        // Database connection settings
        $servername = "localhost";
        $username = "root"; // Your MySQL username
        $password = ""; // Your MySQL password
        $database = "test2"; // Your MySQL database name

        // Create connection
        $conn = new mysqli($servername, $username, $password, $database);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Create table if it doesn't exist
        $sql = "CREATE TABLE IF NOT EXISTS csv_import (
                    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(30) NOT NULL,
                    surname VARCHAR(30) NOT NULL,
                    initials VARCHAR(5) NOT NULL,
                    age INT(3) NOT NULL,
                    date_of_birth DATE NOT NULL
                )";

        if ($conn->query($sql) === TRUE) {
            // Parse CSV file and insert data into the table
            $csvFile = fopen($_FILES["csvFile"]["tmp_name"], "r");
            $stmt = $conn->prepare('INSERT INTO csv_import (name, surname, initials, age, date_of_birth) VALUES (?, ?, ?, ?, ?)');

            // Begin transaction
            $conn->begin_transaction();

            $count = 0;
            while (($data = fgetcsv($csvFile)) !== false) {
                if ($count > 0) { // Skip header row
                    // Extract data from CSV row
                    $name = $data[1];
                    $surname = $data[2];
                    $initials = $data[3];
                    $age = intval($data[4]);
                    $dob = date('Y-m-d', strtotime($data[5]));

                    // Bind parameters
                    $stmt->bind_param("sssis", $name, $surname, $initials, $age, $dob);

                    // Execute the prepared statement
                    $stmt->execute();
                }
                $count++;
            }
            fclose($csvFile);

            // Commit transaction
            $conn->commit();

            // Return count of records inserted
            echo "Number of records inserted: " . ($count - 1); // Subtract 1 for the header row
        } else {
            echo "Error creating table: " . $conn->error;
        }

        // Close prepared statement
        $stmt->close();

        // Close database connection
        $conn->close();
    }
}
?>
