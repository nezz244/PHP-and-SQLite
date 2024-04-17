<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate CSV</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .input-group {
            margin-bottom: 20px;
        }
        .input-group input[type="number"] {
            width: 100px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
        .input-group button {
            padding: 8px 20px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .input-group button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Generate CSV File</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="input-group">
                <label for="numRecords">Number of Records:</label>
                <input type="number" id="numRecords" name="numRecords" min="1" value="100">
            </div>
            <div class="input-group">
                <button type="submit" name="generate">Generate CSV</button>
            </div>
        </form>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['generate'])) {
            $numRecords = $_POST['numRecords'];
            $generatedFile = generateAndSaveCSV($numRecords);
            //echo "<p>CSV file generated: <a href='$generatedFile'>$generatedFile</a></p>";
            echo "<p>CSV file generated: <a href='redirect.php?file=$generatedFile'>$generatedFile</a></p>";
        }
        ?>
    </div>
</body>
</html>

<?php

// Function to generate CSV file with the given number of records
function generateCSV($numRecords, $names, $surnames) {
    // Specify the output folder
    $outputFolder = 'output/';

    // Create the output folder if it doesn't exist
    if (!file_exists($outputFolder)) {
        mkdir($outputFolder, 0777, true); // Create output folder recursively
    }

    // Open the CSV file for writing in the output folder
    $filePath = $outputFolder . 'output.csv';
    $file = fopen($filePath, 'w');

    // Write the header fields
    fputcsv($file, array('Id', 'Name', 'Surname', 'Initials', 'Age', 'DateOfBirth'));

    // Initialize an associative array to keep track of generated records
    $generatedRecords = [];

    // Generate random data for each record
    for ($i = 1; $i <= $numRecords; $i++) {
        // Generate a unique record
        do {
            $name = $names[array_rand($names)]; // Pick a random name
            $surname = $surnames[array_rand($surnames)]; // Pick a random surname
            $initials = substr($name, 0, 1); // Extract the first character as initials
            $age = mt_rand(18, 70); // Generate a random age between 18 and 70
            $dob = date('d/m/Y', mt_rand(strtotime('01-01-1950'), strtotime('now'))); // Generate a random date of birth
            $record = array($i, $name, $surname, $initials, $age, $dob);
            $recordHash = md5(serialize($record)); // Calculate hash of the record

            // Check if the record already exists
            if (!isset($generatedRecords[$recordHash])) {
                // Write the record to the CSV file
                fputcsv($file, $record);

                // Add the generated record to the hash set
                $generatedRecords[$recordHash] = true;
                break;
            }
        } while (true); // Loop until a unique record is generated
    }

    // Close the CSV file
    fclose($file);

    return $filePath;
}

// Function to generate CSV file with the given number of records and save it
function generateAndSaveCSV($numRecords) {
    // Define arrays for names and surnames
    $names = ["John", "Emma", "Michael", "Sophia", "William", "Olivia", "James", "Ava", "Alexander", "Mia", "Daniel", "Charlotte", "Henry", "Amelia", "Joseph", "Isabella", "David", "Grace", "Samuel", "Ella"];
    $surnames = ["Smith", "Johnson", "Williams", "Jones", "Brown", "Davis", "Miller", "Wilson", "Moore", "Taylor", "Anderson", "Thomas", "Jackson", "White", "Harris", "Martin", "Thompson", "Garcia", "Martinez", "Robinson"];

    // Call generateCSV function to generate CSV file
    $generatedFile = generateCSV($numRecords, $names, $surnames);

    // Return the path of the generated CSV file
    return $generatedFile;
}

