<?php  
include("navigation.php");
include("database.php");

// SQL query to select all pets data for display
$sql = "SELECT petID, species, breed, name_of_pets, date_of_birth, clientsID, image_name FROM Pets";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pets List</title>
    <link rel="stylesheet" href="style.css">
    <script>
        // Confirm deletion and redirect to pets.php after deletion
        function deletePet(petID) {
            if (confirm("Are you sure you want to delete this pet?")) {
                // Correct the URL to delete the pet
                window.location.href = "pets.php?pet_id=" + petID;
            }
        }
    </script>
</head>
<body>

    <div class="nav-container">
        <a href="pets.php" class="nav-link">Pet List</a>
        <a href="insert_pets.php" class="nav-link">Insert Pet</a>
    </div>

    <table border="1" align="center" cellspacing="0" cellpadding="10">
        <tr>
            <th>Image</th>
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

                // Display image if image_name exists
                echo "<td>";
                if ($row['image_name']) {
                    echo "<img src='uploads/" . $row['image_name'] . "' style='width:50px; height:50px;'>";
                } else {
                    echo "<span style='font-size:10px;'>No image</span>";
                }
                echo "</td>";

                // Display pet details
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
            echo "<tr><td colspan='8'>No pets found</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</body>
</html>

<?php
// Check if pet_id is provided for deletion
if (isset($_GET['pet_id'])) {
    $pet_id = $_GET['pet_id'];

    // Prepare SQL query to delete a pet
    $delete_sql = "DELETE FROM Pets WHERE petID = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $pet_id); 

    if ($stmt->execute()) {
        header("Location: pets.php?delete_success=1");
        exit();
    } else {
        header("Location: pets.php?delete_success=0");
        exit();
    }
}
?>
