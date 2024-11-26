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
    echo "Error fetching pets: " . $conn->error;
}

// Fetch veterinarians
$veterinarianQuery = "SELECT employeesID, full_name FROM Employees";
if ($result = $conn->query($veterinarianQuery)) {
    while ($row = $result->fetch_assoc()) {
        $veterinarians[] = $row;
    }
} else {
    echo "Error fetching veterinarians: " . $conn->error;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $petID = $_POST['petID'] ?? null;
    $employeeID = $_POST['employeeID'] ?? null;
    $date = $_POST['date'] ?? null;
    $time = $_POST['time'] ?? null;

    if ($petID && $employeeID && $date && $time) {
        $insertQuery = "INSERT INTO Appointments (petID, employeesID, date, time) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("iiss", $petID, $employeeID, $date, $time);

        if ($stmt->execute()) {
            echo "<p>Appointment created successfully!</p>";
        } else {
            echo "<p>Error creating appointment: " . $stmt->error . "</p>";
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
    <style>
        form {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-top: 10px;
        }
    </style>
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

        <button type="submit">Create Appointment</button>
    </form>
</body>
</html>
