<?php  
include("navigation.php");
include("database.php");

// Insert employee code: only process if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data safely
    $employeesID = $_POST['employeesID'];
    $full_name= $_POST['full_name'];
    $date_of_birth = $_POST['date_of_birth'];
    $name_of_school = $_POST['name_of_school'];
    $employeesType = $_POST['employeesType'];

    // Prepare SQL query to prevent SQL injection
    $check_sql = "SELECT employeesID FROM Employees WHERE employeesID = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("s", $employeesID);
    $stmt->execute();
    $check_result = $stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo "<div class='error-message'>Error: An employee with this ID already exists.</div>";
    } else {
        // Proceed with insertion if no duplicate is found
        $insert_sql = "INSERT INTO Employees (employeesID, full_name, date_of_birth, name_of_school, employeesType) 
                       VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("sssss", $employeesID, $full_name, $date_of_birth, $name_of_school, $employeesType);

        if ($stmt->execute()) {
            echo "<div class='success-message'>New record created successfully</div>";
        } else {
            echo "<div class='error-message'>Error: " . $stmt->error . "</div>";
        }
    }
    $stmt->close();
}

// Check if an employee_id is provided for deletion
if (isset($_GET['employees_id'])) {
    $employee_id = $_GET['employees_id'];

    // Prepare SQL query to delete an employee
    $delete_sql = "DELETE FROM Employees WHERE employeesID = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("s", $employee_id);

    if ($stmt->execute()) {
        header("Location: employees.php?delete_success=1");
        exit();
    } else {
        header("Location: employees.php?delete_success=0");
        exit();
    }
}

$sql = "SELECT employeesID, full_name, date_of_birth, name_of_school, employeesType FROM Employees";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Table</title>
    <link rel="stylesheet" href="style.css">
    <script>
        // Confirm deletion and redirect to employees.php after deletion
        function deleteEmployee(employeesID) {
            if (confirm("Are you sure you want to delete this employee?")) {
                // Redirect to the same page with the employees_id parameter
                window.location.href = "employees.php?employees_id=" + employeesID;
            }
        }
    </script>
</head>
<body>
<h1>Employees</h1> 
    <div class="nav-container">
        <a href="employees.php" class="nav-link">Employee List</a>
        <a href="insert_employee.php" class="nav-link">Insert Employee</a>
    </div>

    <table>
        <tr>
            <th>Employee ID</th>
            <th>Full Name</th>
            <th>Date of Birth</th>
            <th>Name of School</th>
            <th>Employee Type</th>
            <th>Actions</th>
        </tr>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["employeesID"] . "</td>";
                echo "<td>" . $row["full_name"] . "</td>";
                echo "<td>" . $row["date_of_birth"] . "</td>";
                echo "<td>" . $row["name_of_school"] . "</td>";
                echo "<td>" . $row["employeesType"] . "</td>";
                echo "<td><button class='delete-btn' onclick='deleteEmployee(" . $row["employeesID"] . ")'>Delete</button></td>";
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
