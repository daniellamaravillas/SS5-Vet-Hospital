<?php
include("navigation.php");
include("database.php"); // Ensure this establishes a $conn database connection

// Fetch all pets and veterinarians for the dropdowns
$pets = [];
$veterinarians = [];

// Fetch pets
$petQuery = "SELECT petID, name_of_pets FROM Pets";
if ($result = $conn->query($petQuery)) {
    while ($row = $result->fetch_assoc()) {
        $pets[] = $row;
    }
} else {
    echo "<p>Error fetching pets: " . htmlspecialchars($conn->error) . "</p>";
}

// Fetch veterinarians
$veterinarianQuery = "SELECT employeesID, full_name FROM Employees";
if ($result = $conn->query($veterinarianQuery)) {
    while ($row = $result->fetch_assoc()) {
        $veterinarians[] = $row;
    }
} else {
    echo "<p>Error fetching veterinarians: " . htmlspecialchars($conn->error) . "</p>";
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $petID = $_POST['petID'] ?? null;
    $employeeID = $_POST['employeeID'] ?? null;
    $date = $_POST['date'] ?? null;
    $time = $_POST['time'] ?? null;
    $contactNumber = $_POST['contact_number'] ?? null; // New field for contact number

    if ($petID && $employeeID && $date && $time && $contactNumber) {
        $insertQuery = "INSERT INTO Appointments (petID, employeesID, date, time, contact_number) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("iisss", $petID, $employeeID, $date, $time, $contactNumber);

        if ($stmt->execute()) {
            echo "<script>
                alert('Appointment created successfully!');
                window.location.href = 'appointments.php'; // Redirect to appointments page
            </script>";
            exit; // Ensure the script stops after redirect
        } else {
            echo "<p>Error creating appointment: " . htmlspecialchars($stmt->error) . "</p>";
        }
    } else {
        echo "<p>Please fill in all fields.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Appointment</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Create a New Appointment</h1>
    <form method="POST" action="">
        <label for="petID">Pet:</label>
        <select name="petID" id="petID" required>
            <option value="">Select a pet</option>
            <?php foreach ($pets as $pet): ?>
                <option value="<?= htmlspecialchars($pet['petID']) ?>">
                    <?= htmlspecialchars($pet['name_of_pets']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="employeeID">Attending Veterinarian:</label>
        <select name="employeeID" id="employeeID" required>
            <option value="">Select a veterinarian</option>
            <?php foreach ($veterinarians as $vet): ?>
                <option value="<?= htmlspecialchars($vet['employeesID']) ?>">
                    <?= htmlspecialchars($vet['full_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="date">Date:</label>
        <input type="date" name="date" id="date" required>

        <label for="time">Time:</label>
        <input type="time" name="time" id="time" required>

        <label for="contact_number">Contact Number:</label>
        <input type="text" name="contact_number" id="contact_number" required>

        <button type="submit">Create Appointment</button>
    </form>
</body>
</html>
