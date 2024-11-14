<?php
include("navigation.php");
include("database.php");

// SQL query to fetch appointments with relevant details
$sql = "SELECT 
    appointments.appointmentsID AS appointmentID,
    clients.full_name AS client_fullname,
    DATE(appointments.date_and_time) AS appointment_date,
    TIME(appointments.date_and_time) AS appointment_time,
    appointments.date_and_time AS date_and_time,
    appointments.contact_number AS contact_number,
    appointments.clientsID AS clientsID,
    employees.full_name AS employeeType
FROM 
    appointments
INNER JOIN 
    clients ON appointments.clientsID = clients.clientsID
INNER JOIN 
    employees ON appointments.employeesID = employees.employeesID
ORDER BY client_fullname ASC";

$query = mysqli_query($conn, $sql);

// Error display
if (!$query) {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

if (isset($_GET['delete'])) {
    $appointmentsID = $_GET['delete'];

    // Use prepared statements for secure deletion
    $deleteSql = "DELETE FROM appointments WHERE appointmentsID = ?";
    $stmt = $conn->prepare($deleteSql);
    
    if ($stmt) {
        $stmt->bind_param("i", $appointmentsID);  // 'i' is for integer
        if ($stmt->execute()) {
            echo "<script>alert('Appointment deleted successfully!'); window.location.href = 'Appointments.php';</script>";
        } else {
            echo "Error deleting appointment: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments List</title>
    <style>
        /* Add your CSS styles here */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .delete-button {
            color: red;
            text-decoration: none;
            font-weight: bold;
        }

        .delete-button:hover {
            text-decoration: underline;
        }

        .navbar {
            margin-bottom: 20px;
        }

        .navbar a {
            margin: 0 15px;
            text-decoration: none;
            color: #4CAF50;
            font-weight: bold;
        }

        .navbar a:hover {
            color: #45a049;
        }
    </style>
</head>
<body>

<!-- Top bar navigation -->
<div class="navbar">
    <a href="Appointments.php">Appointments List</a>
    <a href="insert_appointments.php">Insert Appointment</a>
</div>

<table>
    <tr>
        <th>Appointments ID</th>
        <th>Client Full Name</th>
        <th>Date</th>
        <th>Time</th>
        <th>Contact Number</th>
        <th>Employee</th>
        <th>Action</th>
    </tr>

    <?php
    // Display appointments without pets
    while ($result = mysqli_fetch_assoc($query)) {
        $datetime = $result["appointment_date"] . " " . $result["appointment_time"];

        echo "<tr>";
        echo "<td>" . $result["appointmentID"] . "</td>";
        echo "<td>" . $result["client_fullname"] . "</td>";
        echo "<td>" . $result["appointment_date"] . "</td>";
        echo "<td>" . date("g:i A", strtotime($datetime)) . "</td>";
        echo "<td>" . $result["contact_number"] . "</td>";
        echo "<td>" . $result["employeeType"] . "</td>";
        echo "<td><a href='Appointments.php?delete=" . $result["appointmentID"] . "' class='delete-button' onclick=\"return confirm('Are you sure you want to delete this appointment?');\">Delete</a></td>";
        echo "</tr>";
    }
    ?>
</table>

</body>
</html>
