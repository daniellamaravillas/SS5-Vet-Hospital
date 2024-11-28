<?php
include("navigation.php");
include("database.php");

// SQL query to select all data from Clients for display (including file_path)
$sql = "SELECT clientsID, full_name, address, contact_number, date_of_birth, file_path FROM Clients";
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
        function deleteClient(client_id) {
            if (confirm("Are you sure you want to delete this client?")) {
                window.location.href = "clients.php?client_id=" + client_id;
            }
        }
    </script>
</head>
<body>
    <div class="nav-container">
        <a href="clients.php" class="nav-link">Clients List</a>
        <a href="insert_clients.php" class="nav-link">Insert Client</a>
    </div>

    <table border="1" align="center" cellspacing="0" cellpadding="10">
        <tr>
            <th>Image</th> <!-- Added header for image -->
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

                // Display the image if file_path exists
                echo "<td>";
                if ($row['file_path']) {
                    echo "<img src='uploads/{$row['file_path']}' style='width:30px;'>";
                } else {
                    echo "<span style='font-size:10px;'>No image uploaded</span>";
                }
                echo "</td>";

                // Display other client details
                echo "<td>" . $row["clientsID"] . "</td>";
                echo "<td>" . $row["full_name"] . "</td>";
                echo "<td>" . $row["address"] . "</td>";
                echo "<td>" . $row["contact_number"] . "</td>";
                echo "<td>" . $row["date_of_birth"] . "</td>";
                
                // Delete button
                echo "<td><button class='delete-btn' onclick='deleteClient(" . $row["clientsID"] . ")'>Delete</button></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No records found</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</body>
</html>
