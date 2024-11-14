<?php  
include("navigation.php");
include("database.php");

// Insert pets code: only process if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data safely
    $pets = $_POST['pets'];
    $species = $_POST['species '];
    $breed = $_POST['breed'];
    $name_of_pets = $_POST['name_of_pets'];

    // Prepare SQL query to prevent SQL injection
    $check_sql = "SELECT petsID FROM Pets WHERE name_of_pets = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("s", $name_of_pets);
    $stmt->execute();
    $check_result = $stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo "<div class='error-message'>Error: A Pet with this PetID is already exists.</div>";
    } else {
        // Proceed with insertion if no duplicate is found
        $insert_sql = "INSERT INTO Pets (full_name, address, contact_number, date_of_birth) 
                       VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("ssss", $species, $breed, $name_of_pets, $date_of_birth);

        if ($stmt->execute()) {
            echo "<div class='success-message'>New record created successfully</div>";
        } else {
            echo "<div class='error-message'>Error: " . $stmt->error . "</div>";
        }
    }
}

// Check if pets_id is provided for deletion
if (isset($_GET['pets_id'])) {
    $client_id = $_GET['pets_id'];

    // Prepare SQL query to delete a client
    $delete_sql = "DELETE FROM Pets WHERE petsID = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $client_id); 
    if ($stmt->execute()) {
        header("Location: pets.php?delete_success=1");
        exit();
    } else {
        header("Location: petss.php?delete_success=0");
        exit();
    }
}

// SQL query to select all data from Pets for display
$sql = "SELECT petsID, species , breed , name_of_pets , date_of_birth , clientsID FROM Pets";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clients Table</title>
    <style>
        /* Reset styling */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #C8C6D7;
            color: #4A4063;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            flex-direction: column;
        }

        h2 {
            font-size: 2.5em;
            color: #783F8E;
            margin-bottom: 20px;
            font-family: 'Brush Script MT', cursive;
        }

        /* Section div styling */
        .section {
            width: 80%;
            padding: 15px;
            margin: 10px 0;
            background-color: #BFACC8;
            color: #4A4063;
            border-radius: 8px;
            text-align: center;
            font-size: 1.5em;
            font-weight: bold;
        }

        .insert-section {
            background-color: #4F1271;
            color: #FFFFFF;
        }

        /* Navigation styling */
        .nav-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .nav-link {
            margin: 0 15px;
            padding: 10px 20px;
            background-color: #4F1271;
            color: #FFFFFF;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        /* Hover effects for the navigation links */
        .nav-link:hover {
            background-color: #783F8E;
            transform: scale(1.1);
            text-decoration: underline;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Table styling */
        table {
            width: 80%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #BFACC8;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        
        th, td {
            padding: 15px;
            text-align: left;
        }

        th {
            background-color: #4A4063;
            color: #fff;
            font-weight: bold;
        }

        td {
            background-color: #C8C6D7;
            color: #4A4063;
        }

        /* Hover effect for table rows */
        tr:hover td {
            background-color: #783F8E;
            color: #fff;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Delete button styling */
        .delete-btn {
            background-color: #D32F2F;
            color: white;
            border: none;
            padding: 8px 16px;
            cursor: pointer;
            border-radius: 5px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .delete-btn:hover {
            background-color: #9A0007;
            transform: scale(1.1);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
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
