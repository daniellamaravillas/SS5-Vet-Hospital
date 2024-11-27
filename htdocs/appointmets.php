<?php  
include("navigation.php");
include("database.php"); // Ensure this establishes a $conn database connection

// Correct SQL query
$sql = "SELECT 
    Appointments.appointmentsID AS appointmentsID,
    Pets.name_of_pets AS pet_name,
    Clients.full_name AS client_name,
    Appointments.date AS date,
    Appointments.time AS time,
    Employees.full_name AS veterinarian
FROM 
    Appointments
JOIN 
    Pets ON Appointments.petID = Pets.petID
JOIN 
    Clients ON Pets.clientsID = Clients.clientsID
JOIN 
    Employees ON Appointments.employeesID = Employees.employeesID";

// Execute the query and fetch results
$appointments = [];
if ($result = $conn->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }
} else {
    echo "Error: " . $conn->error;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Appointments</h1> <br>

    <div class="nav-container">
        <a href="appointments,php" class="nav-link">Appointments</a>
        <a href="insert_appointments.php" class="nav-link">Insert Appointments</a>
    </div>
    <?php if (!empty($appointments)): ?>
        <table>
            <thead>
                <tr>
                    <th>Appointment ID</th>
                    <th>Pet Name</th>
                    <th>Client Name</th>
                    <th>Appointment Date</th>
                    <th>Appointment Time</th>
                    <th>Attending Veterinarian</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $appointment): ?>
                    <tr>
                        <td><?= htmlspecialchars($appointment['appointmentsID']) ?></td>
                        <td><?= htmlspecialchars($appointment['pet_name']) ?></td>
                        <td><?= htmlspecialchars($appointment['client_name']) ?></td>
                        <td><?= htmlspecialchars($appointment['date']) ?></td>
                        <td><?= htmlspecialchars($appointment['time']) ?></td>
                        <td><?= htmlspecialchars($appointment['veterinarian']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No appointments found.</p>
    <?php endif; ?>
</body>
</html>
