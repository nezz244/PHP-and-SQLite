<?php
if (isset($_GET['file'])) {
    $generatedFile = $_GET['file'];
    header("Location: upload_csv.php?file=$generatedFile");
    exit();
}
?>
