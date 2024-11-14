<?php  
include("navigation.php");
include("database.php");

// SQL query to select all data from Clients
$sql = "SELECT clientsID, full_name, address, contact_number, date_of_birth FROM Clients";
$result = $conn->query($sql);

// Insert client code
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $address = $_POST['address'];
    $contact_number = $_POST['contact_number'];
    $date_of_birth = $_POST['date_of_birth'];

    // Generate a unique clientsID if needed or rely on auto-increment
    $clientsID = uniqid('client_');

    // Check if a client with this contact number already exists
    $check_sql = "SELECT clientsID FROM Clients WHERE contact_number = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("s", $contact_number);
    $stmt->execute();
    $check_result = $stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo "<div class='error-message'>Error: A client with this contact number already exists.</div>";
    } else {
        // Proceed with insertion if no duplicate is found
        $insert_sql = "INSERT INTO Clients (clientsID, full_name, address, contact_number, date_of_birth) 
                       VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("sssss", $clientsID, $full_name, $address, $contact_number, $date_of_birth);

        if ($stmt->execute() === TRUE) {
            echo "<div class='success-message'>New record created successfully</div>";
            header("Location: clients.php"); // Redirect after successful insertion
            exit();
        } else {
            echo "<div class='error-message'>Error: " . $stmt->error . "</div>";
        }
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Client</title>
    <style>
        /* CSS styling as before */
        /* Reset styling */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #C8C6D7;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            flex-direction: column;
        }

        h2 {
            font-size: 2.5em;
            color: #5C4B8C;
            margin-bottom: 20px;
        }

        .nav-container {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }

        .nav-link {
            margin: 0 15px;
            padding: 10px 20px;
            background-color: #5C4B8C;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .nav-link:hover {
            background-color: #7A68A1;
        }

        .form-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 50%;
            max-width: 800px;
            text-align: left;
        }

        .form-container label {
            font-size: 1.2em;
            color: #333;
            margin-bottom: 10px;
            display: block;
        }

        .form-container input, .form-container select {
            width: 100%;
            padding: 12px;
            margin: 10px 0 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 1em;
            color: #333;
        }

        .form-container input[type="date"] {
            padding: 10px;
        }

        .form-container button {
            background-color: #5C4B8C;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1.2em;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .form-container button:hover {
            background-color: #7A68A1;
            transform: scale(1.05);
        }

        .success-message {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            margin-top: 20px;
            border-radius: 5px;
            font-size: 1.2em;
        }

        .error-message {
            background-color: #F44336;
            color: white;
            padding: 10px 20px;
            margin-top: 20px;
            border-radius: 5px;
            font-size: 1.2em;
        }
    </style>
</head>
<body>
<br><br><br><br><br><br>
<div class="nav-container">
    <a href="clients.php" class="nav-link">Clients List</a>
    <a href="insert_clients.php" class="nav-link">Insert Client</a>
</div>

<div class="form-container">
    <form action="insert_clients.php" method="POST">

        <label for="full_name">Full Name:</label>
        <input type="text" id="full_name" name="full_name" list="full-name-list" required>
        <datalist id="full-name-list">
            <?php
            $result->data_seek(0); // Reset pointer
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['full_name'] . "'>";
            }
            ?>
        </datalist>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" list="address-list" required>
        <datalist id="address-list">
            <?php
            $result->data_seek(0); // Reset pointer
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['address'] . "'>";
            }
            ?>
        </datalist>

        <label for="contact_number">Contact Number:</label>
        <input type="text" id="contact_number" name="contact_number" list="contact-number-list" required>
        <datalist id="contact-number-list">
            <?php
            $result->data_seek(0); // Reset pointer
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['contact_number'] . "'>";
            }
            ?>
        </datalist>

        <label for="date_of_birth">Date of Birth:</label>
        <input type="date" id="date_of_birth" name="date_of_birth" required><br><br>

        <button type="submit">Insert Client</button>
    </form>
</div>

</body>
</html>
