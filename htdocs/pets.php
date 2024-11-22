<?php  
include("navigation.php");
include("database.php");

// Insert pets code: only process if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data safely
    $pet = $_POST['pet'];
    $species = $_POST['species '];
    $breed = $_POST['breed'];
    $name_of_pets = $_POST['name_of_pets'];

    // Prepare SQL query to prevent SQL injection
    $check_sql = "SELECT petID FROM Pets WHERE name_of_pets = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("s", $name_of_pets);
    $stmt->execute();
    $check_result = $stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo "<div class='error-message'>Error: A Pet with this PetID is already exists.</div>";
    } else {
        // Proceed with insertion if no duplicate is found
        $insert_sql = "INSERT INTO Pets (full_name, address, contact_number, date_of_birth,clientsID) 
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
if (isset($_GET['pet_id'])) {
    $client_id = $_GET['pet_id'];

    // Prepare SQL query to delete a client
    $delete_sql = "DELETE FROM Pets WHERE petID = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $client_id); 
    if ($stmt->execute()) {
        header("Location: pet.php?delete_success=1");
        exit();
    } else {
        header("Location: pet.php?delete_success=0");
        exit();
    }
}

// SQL query to select all data from Pets for display
$sql = "SELECT petID, species , breed , name_of_pets , date_of_birth , clientsID FROM Pets";
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
        // Confirm deletion and redirect to petss.php after deletion
        function deleteClient(petsID) {
            if (confirm("Are you sure you want to delete this pet?")) {
                // Redirect to the same page with the pet_id parameter
                window.location.href = "pets.php?client_id=" + petID;
            }
        }
    </script>
</head>
<body>
    <br><br><br><br><br><br><br><br><br><br><br><br><br>

    <div class="nav-container">
        <a href="pets.php" class="nav-link">Pet List</a>
        <a href="insert_pets.php" class="nav-link">Insert Pet</a>
    </div>

    <table>
        <tr>
            <th>Pets ID</th>
            <th>Species</th>
            <th>Breed</th>
            <th>Pet Name</th>
            <th>Date of Birth</th>
            <th>Client ID</th>
            <th>Actions</th>
        </tr>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["petID"] . "</td>";
                echo "<td>" . $row["species"] . "</td>";
                echo "<td>" . $row["breed"] . "</td>";
                echo "<td>" . $row["name_of_pets"] . "</td>";
                echo "<td>" . $row["date_of_birth"] . "</td>";
                echo "<td>" . $row["clientsID"] . "</td>";
                echo "<td><button class='delete-btn' onclick='deletePet(" . $row["petID"] . ")'>Delete</button></td>";
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
