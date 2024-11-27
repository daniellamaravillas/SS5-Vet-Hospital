<?php  
include ("navigation.php");
include("database.php");

// Insert client code: only process if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data safely
    $full_name = $_POST['full_name'];
    $address = $_POST['address'];
    $contact_number = $_POST['contact_number'];
    $date_of_birth = $_POST['date_of_birth'];

    // Prepare SQL query to prevent SQL injection
    $check_sql = "SELECT clientsID FROM Clients WHERE contact_number = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("s", $contact_number);  // Bind the contact number
    $stmt->execute();
    $check_result = $stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo "<div class='error-message'>Error: A client with this contact number already exists.</div>";
    } else {
        // Proceed with insertion if no duplicate is found
        $insert_sql = "INSERT INTO Clients (full_name, address, contact_number, date_of_birth) 
                       VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("ssss", $full_name, $address, $contact_number, $date_of_birth);

        if ($stmt->execute()) {
            echo "<div class='success-message'>New record created successfully</div>";
        } else {
            echo "<div class='error-message'>Error: " . $stmt->error . "</div>";
        }
    }
}

// Check if a client_id is provided for deletion
if (isset($_GET['client_id'])) {
    $client_id = $_GET['client_id'];

    // Prepare SQL query to delete a client
    $delete_sql = "DELETE FROM Clients WHERE clientsID = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $client_id);  // Bind the client_id as an integer

    if ($stmt->execute()) {
        header("Location: clients.php?delete_success=1");
        exit();
    } else {
        header("Location: clients.php?delete_success=0");
        exit();
    }
}

// SQL query to select all data from Clients for display
$sql = "SELECT clientsID, full_name, address, contact_number, date_of_birth FROM Clients";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clients Table</title>
    <link rel="stylesheet" href="style.css">
    <script>
        // Confirm deletion and redirect to clients.php after deletion
        function deleteClient(clientID) {
            if (confirm("Are you sure you want to delete this client?")) {
                // Redirect to the same page with the client_id parameter
                window.location.href = "clients.php?client_id=" + clientID;
            }
        }
    </script>
</head>
<body>
    <br><br><br><br><br><br><br><br><br><br><br><br><br>

    <div class="nav-container">
        <a href="clients.php" class="nav-link">Clients List</a>
        <a href="insert_clients.php" class="nav-link">Insert Client</a>
    </div>

    <table>
        <tr>
            <th>Profile Picture</th>
            <th>Client ID</th>
            <th>Full Name</th>
            <th>Address</th>
            <th>Contact Number</th>
            <th>Date of Birth</th>
            <th>Actions</th>
        </tr>
    
    

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["clientsID"] . "</td>";
                echo "<td>" . $row["full_name"] . "</td>";
                echo "<td>" . $row["address"] . "</td>";
                echo "<td>" . $row["contact_number"] . "</td>";
                echo "<td>" . $row["date_of_birth"] . "</td>";
                echo "<td><button class='delete-btn' onclick='deleteClient(" . $row["clientsID"] . ")'>Delete</button></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No records found</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</body>
</html>