<?php
$target_dir = "uploads/";

// Loop through each file
foreach ($_FILES["filesToUpload"]["name"] as $key => $name) {
    $target_file = $target_dir . basename($name);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists: $name<br>";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["filesToUpload"]["size"][$key] > 500000) {
        echo "Sorry, your file is too large: $name<br>";
        $uploadOk = 0;
    }

    // Allow only CSV file formats
    if ($imageFileType != "csv") {
        echo "Sorry, only CSV files are allowed: $name<br>";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded: $name<br>";
    } else {
        if (move_uploaded_file($_FILES["filesToUpload"]["tmp_name"][$key], $target_file)) {
            echo "The file " . htmlspecialchars($name) . " has been uploaded.<br>";
            rename($target_file, $target_dir . pathinfo($target_file, PATHINFO_FILENAME) . ".imported.csv");
            
            // Parse and insert CSV data
            include 'parseAndInsertCSV.php';
            $importedFile = $target_dir . pathinfo($target_file, PATHINFO_FILENAME) . ".imported.csv";
            parseAndInsertCSV($importedFile);
        } else {
            echo "Sorry, there was an error uploading your file: $name<br>";
        }
    }
}

// Display button to return to index.php
echo '<a href="index.php"><button>Back to Home</button></a>';
?>
