<?php
// File: insert_clients.php
include("navigation.php");
include("database.php");

$uploadDir = 'uploads/';

// Ensure the upload directory exists
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Handle file upload
$uploadedFile = null;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['fileToUpload'])) {
    $file = $_FILES['fileToUpload'];
    $fileName = basename($file['name']);
    $fileSize = $file['size'];
    $fileTmpName = $file['tmp_name'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Define allowed file types and size limit (e.g., 2MB for images)
    $allowedTypes = ['jpg', 'jpeg', 'png', 'pdf'];
    $maxSize = 2 * 1024 * 1024; // 2 MB

    // Validate file type and size
    if (in_array($fileExt, $allowedTypes) && $fileSize <= $maxSize) {
        $newFileName = uniqid('file_', true) . '.' . $fileExt;
        $uploadFile = $uploadDir . $newFileName;

        // Validate MIME type for additional security
        $fileMimeType = mime_content_type($fileTmpName);
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'application/pdf'];

        if (in_array($fileMimeType, $allowedMimeTypes)) {
            if (move_uploaded_file($fileTmpName, $uploadFile)) {
                $uploadedFile = $newFileName;
            } else {
                echo "<p style='color: red;'>Error uploading file. Please try again.</p>";
            }
        } else {
            echo "<p style='color: red;'>Invalid file content. Only JPG, PNG, or PDF files are allowed.</p>";
        }
    } else {
        if (!in_array($fileExt, $allowedTypes)) {
            echo "<p style='color: red;'>Invalid file type. Only JPG, JPEG, PNG, and PDF files are allowed.</p>";
        }
        if ($fileSize > $maxSize) {
            echo "<p style='color: red;'>File size exceeds the 2MB limit.</p>";
        }
    }
}

// Insert client data into database
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['full_name'], $_POST['address'], $_POST['contact_number'], $_POST['date_of_birth'])) {
    $filename = $_FILES['profile-photo']['name'];
    $fileTmpName = $_FILES['profile-photo']['tmp_name'];
    $fileSize = $_FILES['profile-photo']['size'];
    $fileError = $_FILES['profile-photo']['error'];
    $fullName = htmlspecialchars(trim($_POST['full_name']));
    $address = htmlspecialchars(trim($_POST['address']));
    $contactNumber = htmlspecialchars(trim($_POST['contact_number']));
    $dateOfBirth = $_POST['date_of_birth'];
    

    $sql = "INSERT INTO Clients (full_name, address, contact_number, date_of_birth, file_path) 
            VALUES (?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('sssss', $fullName, $address, $contactNumber, $dateOfBirth, $uploadedFile);

        if ($stmt->execute()) {
            echo "<script> alert('Client added successfully!'); window.location='clients.php';</script>";
        } else {
            echo "<p style='color: red;'>Error adding client: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p style='color: red;'>Database error: " . $conn->error . "</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Client</title>
    <style>
        /* Styling remains the same */
    </style>
</head>
<body>
    <div class="nav-container">
        <a href="clients.php" class="nav-link">Clients List</a>
        <a href="insert_clients.php" class="nav-link">Insert Clients</a>
    </div>

    <div class="form-container">
        <h2>Insert New Client</h2>
        <form action="insert_clients.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="full_name">Full Name:</label>
                <input type="text" id="full_name" name="full_name" required>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required>
            </div>
            <div class="form-group">
                <label for="contact_number">Contact Number:</label>
                <input type="text" id="contact_number" name="contact_number" required>
            </div>
            <div class="form-group">
                <label for="date_of_birth">Date of Birth:</label>
                <input type="date" id="date_of_birth" name="date_of_birth" required>
            </div>
            <div class="form-group">
                <label for="fileToUpload">Choose a file to upload:</label>
                <input type="file" name="fileToUpload" id="fileToUpload" required>
            </div>
            <button type="submit" class="submit-btn">Submit</button>
        </form>
    </div>
</body>
</html>
