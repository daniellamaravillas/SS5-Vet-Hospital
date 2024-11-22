<?php  
include("navigation.php");
include("database.php");

        $sql ="SELECT Appointment.appoitmentsID , clients.full_name , pet.name_of_pets FROM Appointmets
INNER JOIN clients ON appointments.clientID=clientsID
INNER JOIN employees ON appointments.employeesID = employees.employeesID
INNER JOIN pets ON appointments.petsID= pets.petsID
ORDER BY client_fullname , employees_full_name ASC";



// Insert appointment code: only process if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data safely
    $appointmentsID = $_POST['appointmentsID'];
    $contact_number = $_POST['contact_number'];
    $clientsID = $_POST['clientsID'];
    $employeesID = $_POST['employeesID'];
    $time = $_POST['time'];
    $date = $_POST['date'];
    $petID = $_POST['petID'];

    // Prepare SQL query to prevent SQL injection
    $check_sql = "SELECT appointmentsID FROM Appointments WHERE employeesID = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("s", $employeesID); 
    $stmt->execute();
    $check_result = $stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo "<div class='error-message'>Error: An appointment with this employee already exists.</div>";
    } else {
        // Proceed with insertion if no duplicate is found
        $insert_sql = "INSERT INTO Appointments (appointmentsID, contact_number , clientsID, employeesID , time , date , petID) 
                       VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("sssssss", $appointmentsID, $contact_number, $clientsID, $employeesID, $time, $date, $petID);

        if ($stmt->execute()) {
            echo "<div class='success-message'>New record created successfully</div>";
        } else {
            echo "<div class='error-message'>Error: " . $stmt->error . "</div>";
        }
    }
}

// Check if an appointmentsID is provided for deletion
if (isset($_GET['appointmentsID'])) {
    $appointmentsID = $_GET['appointmentsID'];

    // Prepare SQL query to delete an appointment
    $delete_sql = "DELETE FROM Appointments WHERE appointmentsID = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $appointmentsID); 

    if ($stmt->execute()) {
        header("Location: appointments.php?delete_success=1");
        exit();
    } else {
        header("Location: appointments.php?delete_success=0");
        exit();
    }
}

// SQL query to select all data from Appointments for display
$sql = "SELECT appointmentsID, contact_number, clientsID, employeesID, time, date, petID FROM Appointments";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments List</title>
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
            text-decoration: none; /* Removes underline */
            display: inline-block; /* Makes sure the button is inline */
            text-align: center;
        }

        /* Hover effect for the delete button */
        .delete-btn:hover {
            background-color: #9A0007;
            transform: scale(1.1);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>

<!-- Top bar navigation --> <br><br><br><br><br><br><br><br><br><br>


<table>
    <tr>
        <th>Appointments ID</th>
        <th>Contact Number</th>
        <th>Client ID</th>
        <th>Employee ID</th>
        <th>Time</th>
        <th>Date</th>
        <th>Pet ID</th>
        <th>Action</th>
    </tr>

    <?php
    // Display appointments
    while ($row = $result->fetch_assoc()) {
        // Combine date and time for display in a readable format
        $datetime = $row["date"] . " " . $row["time"];
        $formatted_date = date("Y, D, M", strtotime($datetime)); // Format the date
        $formatted_time = date("g:i A", strtotime($row["time"])); // Format the time

        echo "<tr>";
        echo "<td>" . $result["appointmentID"] . "</td>";
            echo "<td>" . $result["client_fullname"] . "</td>";
            echo "<td>" . $result["date"] . "</td>";
            echo "<td>" . $result["time"] . "</td>";
            echo "<td>" . $result["name_of_pets"] . "</td>";
            echo "<td>" . $result["employees_full_name"] . "</td>";
        echo "<td><a href='appointments.php?appointmentsID=" . $row["appointmentsID"] . "' class='delete-btn' onclick=\"return confirm('Are you sure you want to delete this appointment?');\">Delete</a></td>";
        echo "</tr>";
    }
    ?>
</table>

</body>
</html>
