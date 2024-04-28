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
        .upload-btn {
            margin-top: 20px;
        }
        .redirect-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 8px 20px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
        }
        .redirect-btn:hover {
            background-color: #2980b9;
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
                <button type="submit" name="generate" class="upload-btn">Generate CSV</button>
            </div>
        </form>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['generate'])) {
            $numRecords = $_POST['numRecords'];
            $generatedFile = generateAndSaveCSV($numRecords);
            echo "<p>CSV file generated: <a href='$generatedFile'>$generatedFile</a></p>";
        }
        ?>
        <a href="upload_csv.php" class="redirect-btn">Go to Upload Page</a>
    </div>
</body>
</html>

<?php
//use DateTime;
function generateCSV($numRecords, $names, $surnames) {
    $outputFolder = 'output/';
    if (!file_exists($outputFolder)) {
        mkdir($outputFolder, 0777, true); // Create output folder if it doesn't exist
    }
    $filePath = $outputFolder . 'output.csv';
    $file = fopen($filePath, 'w');

    // Write header fields
    fputcsv($file, array("Id", "Name", "Surname", "Initials", "Age", "DateOfBirth"));

    // Loop to generate random data
    for ($i = 1; $i <= $numRecords; $i++) {
        // Generate random data
        $name = $names[array_rand($names)];
        $surname = $surnames[array_rand($surnames)];
        $initials = substr($name, 0, 1);

        // Generate a random DOB between 1950 and now
        $dobTimestamp = mt_rand(strtotime('01-01-1950'), strtotime('now'));
        $dob = date('d/m/Y', $dobTimestamp);

        // Calculate the age from DOB
        $dobDateTime = new DateTime(date('Y-m-d', $dobTimestamp));
        $currentDate = new DateTime();
        $ageInterval = $currentDate->diff($dobDateTime);
        $age = $ageInterval->y; // Get the number of complete years between DOB and today

        // Write record to CSV file
        fputcsv($file, array("'$i'", "'$name'", "'$surname'", "'$initials'", "'$age'", "'$dob'"));
    }

    fclose($file);
    return $filePath;
}

function generateAndSaveCSV($numRecords) {
    $names = ["John", "Emma", "Michael", "Sophia", "William", "Olivia", "James", "Ava", "Alexander", "Mia", "Daniel", "Charlotte", "Henry", "Amelia", "Joseph", "Isabella", "David", "Grace", "Samuel", "Ella"];
    $surnames = ["Smith", "Johnson", "Williams", "Jones", "Brown", "Davis", "Miller", "Wilson", "Moore", "Taylor", "Anderson", "Thomas", "Jackson", "White", "Harris", "Martin", "Thompson", "Garcia", "Martinez", "Robinson"];

    $generatedFile = generateCSV($numRecords, $names, $surnames);
    return $generatedFile;
}
