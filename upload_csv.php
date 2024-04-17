<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload CSV</title>
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
        .input-group input[type="file"] {
            padding: 8px;
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
        <h2>Upload CSV File</h2>
        <form action="process_uploaded_csv.php" method="post" enctype="multipart/form-data">
            <div class="input-group">
                <input type="file" name="csvFile" id="csvFile" required accept=".csv">
            </div>
            <div class="input-group">
                <button type="submit" name="upload">Upload CSV</button>
            </div>
        </form>
    </div>
</body>
</html>

